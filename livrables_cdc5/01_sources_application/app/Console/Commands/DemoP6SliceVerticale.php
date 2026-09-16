<?php

namespace App\Console\Commands;

use App\Enums\PresenceStatut;
use App\Enums\ReunionStatut;
use App\Enums\ReunionType;
use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\Commission;
use App\Models\Decision;
use App\Models\DecisionTemplate;
use App\Models\Dossier;
use App\Models\EcoleDoctorale;
use App\Models\Etablissement;
use App\Models\Invitation;
use App\Models\OdjTemplate;
use App\Models\Presence;
use App\Models\Reunion;
use App\Models\Universite;
use App\Models\User;
use App\Services\DecisionService;
use App\Services\ReunionService;
use Database\Seeders\UmaDocumentTemplatesSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use SalsabilEnnaiem\PvModule\Seeders\DefaultPvTemplateSeeder;
use SalsabilEnnaiem\PvModule\Services\PdfService;
use SalsabilEnnaiem\PvModule\Services\PvService;
use SalsabilEnnaiem\PvModule\Services\SignatureService;

/**
 * Démo slice verticale P6 : dépôt → ODJ → présence → décisions → PV signé → attestation.
 * Génère les captures d'évidence dans storage/app/private/evidence/p6-*.
 */
class DemoP6SliceVerticale extends Command
{
    protected $signature = 'uma:demo-p6-slice';

    protected $description = 'Déroule la slice verticale du module Réunions (P6) et génère les évidences p6-*.';

    public function handle(): int
    {
        // Nettoyage pour idempotence (données uniquement, jamais le package pv-module).
        Reunion::query()->forceDelete();
        Dossier::query()->forceDelete();
        DecisionTemplate::query()->delete();
        OdjTemplate::query()->delete();
        Commission::query()->delete();
        Presence::query()->delete();
        Invitation::query()->delete();
        Decision::query()->delete();
        AuditLog::query()->delete();
        Etablissement::query()->delete();
        EcoleDoctorale::query()->delete();
        Universite::query()->delete();
        User::query()->whereNotIn('id', function ($q) {
            $q->select('id')->from('users')->where('role', UserRole::Admin);
        })->delete();

        // Templates.
        (new DefaultPvTemplateSeeder)->run();
        (new UmaDocumentTemplatesSeeder)->run();

        // Structure.
        $admin = User::updateOrCreate(
            ['email' => 'admin@uma.dz'],
            ['name' => 'Administrateur UMA', 'role' => UserRole::Admin, 'password' => bcrypt('password')],
        );
        $president = User::factory()->create(['name' => 'Pr. Amine Benali', 'role' => UserRole::PresidentCommission]);
        $membre1 = User::factory()->create(['name' => 'Dr. Leila Cherif', 'role' => UserRole::MembreCommission]);
        $membre2 = User::factory()->create(['name' => 'Dr. Karim Haddad', 'role' => UserRole::MembreCommission]);
        $doctorant = User::factory()->create(['name' => 'Mohamed Amine Salah', 'role' => UserRole::Doctorant]);

        $universite = Universite::factory()->create(['nom' => 'Université des Sciences et de la Technologie']);
        $ecole = EcoleDoctorale::factory()->create(['universite_id' => $universite->id, 'nom' => 'École doctorale Informatique']);
        $etablissement = Etablissement::factory()->create(['ecole_doctorale_id' => $ecole->id, 'nom' => 'Faculté des Sciences']);
        $commission = Commission::factory()->create([
            'etablissement_id' => $etablissement->id,
            'president_id' => $president->id,
            'nom' => 'Commission d\'évaluation IA & Data',
            'discipline' => 'Informatique',
        ]);
        $commission->membres()->attach([$membre1->id, $membre2->id]);

        // Paramétrables.
        $odjTemplate = OdjTemplate::factory()->create([
            'commission_id' => $commission->id,
            'label' => 'ODJ type commission',
            'contenu' => "1. Vérification du quorum ;\n2. Examen des dossiers à l'ODJ ;\n3. Décisions et délibérations ;\n4. Divers.",
        ]);
        DecisionTemplate::factory()->create([
            'label' => 'Admis',
            'email_subject' => 'Décision de la commission',
            'email_body' => 'Votre dossier a été examiné. Décision : {label}.',
            'commission_id' => $commission->id,
            'created_by' => $admin->id,
        ]);

        // Dépôt du dossier.
        $dossier = Dossier::factory()->create([
            'commission_id' => $commission->id,
            'doctorant_id' => $doctorant->id,
            'objet' => 'Thèse « Apprentissage fédéré pour la santé » — dépôt définitif',
            'annee_inscription' => '2024-2025',
            'created_by' => $admin->id,
        ]);

        // Réunion (datée dans le passé → PV possible).
        $reunionService = app(ReunionService::class);
        $reunion = $reunionService->create([
            'commission_id' => $commission->id,
            'objet' => 'Soutenance de la thèse — examen du dossier Salah',
            'odj_template_id' => $odjTemplate->getKey(),
            'date_debut' => now()->subDays(3)->setTime(9, 30),
            'date_fin' => now()->subDays(3)->setTime(11, 0),
            'lieu' => 'Amphithéâtre B — campus UST',
            'type' => ReunionType::Presentiel,
        ], $admin);

        $reunion->dossiers()->attach($dossier->getKey(), ['position' => 1]);
        $reunion->update(['ordre_du_jour' => $odjTemplate->contenu]);

        $this->info('Réunion '.$reunion->id.' créée — invités auto : '.$reunion->invitations()->count().' membres.');

        // Statuts.
        $reunionService->transition($reunion, ReunionStatut::Planifiee, $admin);
        $reunionService->transition($reunion, ReunionStatut::EnCours, $admin);

        // Présences post-réunion.
        $reunion->presences()->create(['participant_id' => $membre1->id, 'statut' => PresenceStatut::Present, 'recorded_by' => $admin->id]);
        $reunion->presences()->create(['participant_id' => $membre2->id, 'statut' => PresenceStatut::Excuse, 'recorded_by' => $admin->id]);

        // Décision.
        $template = DecisionTemplate::where('commission_id', $commission->id)->firstOrFail();
        app(DecisionService::class)->record($reunion, $dossier, $template, $admin);

        $reunionService->transition($reunion, ReunionStatut::Terminee, $admin);

        $this->info('Présents : '.$reunion->presences()->present()->count().' — Décisions : '.$reunion->decisions()->count().'.');

        // PV via package + signatures.
        $pvService = app(PvService::class);
        $signatureService = app(SignatureService::class);
        $signaturePng = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
        $signatureService->storeFromBase64($signaturePng, $admin);
        $signatureService->storeFromBase64($signaturePng, $membre1);

        $pv = $reunionService->genererPv($reunion, $admin, [
            'Après délibération, la commission émet un avis favorable à la soutenance de la thèse « Apprentissage fédéré pour la santé » présentée par M. Mohamed Amine Salah.',
            'Décision adoptée à la majorité des membres présents.',
        ]);
        $this->info('PV '.$pv->id.' généré ('.implode(',', $pv->validations()->pluck('user_id')->all()).') et envoyé pour signature.');

        // Validations restantes : le membre présent signe.
        foreach ($pv->validations()->where('statut', 'en_attente')->get() as $validation) {
            $user = User::find($validation->user_id);

            if ($user && app(SignatureService::class)->hasSignature($user)) {
                $pvService->sign($pv, $user);
            }
        }

        $pv = $pv->fresh();
        $this->info('PV statut final : '.$pv->statut.'.');

        // Évidences PDF.
        $pdfService = app(PdfService::class);
        Storage::disk('local')->put('evidence/p6-pv-reunion-signe.pdf', $pdfService->generatePv($pv));

        // Attestation AR pour le doctorant (motif retenu de la décision).
        $attestation = $pvService->store(
            [
                'titre' => 'Attestation de réussite — M. Salah',
                'contenu' => [
                    'Il est attesté que M. Mohamed Amine Salah, né le 12 mars 1998, inscrit en Doctorat (3ᵉ année, 2024-2025), a été déclaré ADMIS par la commission d\'évaluation IA & Data.',
                    'La présente attestation est délivrée pour servir et valoir ce que de droit.',
                ],
                'type' => 'attestation',
            ],
            $admin,
            'dossier',
            $dossier->getKey(),
        );
        config(['pv-module.default_locale' => 'ar']);
        Storage::disk('local')->put('evidence/p6-attestation-reussite.pdf', $pdfService->generatePv($attestation));
        config(['pv-module.default_locale' => 'fr']);

        $this->info('Évidences écrites dans storage/app/private/evidence/p6-*.pdf');

        return self::SUCCESS;
    }
}
