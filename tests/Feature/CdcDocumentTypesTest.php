<?php

use App\Enums\UserRole;
use App\Models\DecisionTemplate;
use App\Models\User;
use Database\Seeders\UmaDocumentTemplatesSeeder;
use SalsabilEnnaiem\PvModule\Models\Pv;
use SalsabilEnnaiem\PvModule\Models\PvTemplate;

$signaturePng = static function (): string {
    return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
};

beforeEach(function () use ($signaturePng) {
    (new UmaDocumentTemplatesSeeder)->run();
    $this->signaturePng = $signaturePng;
});

test('les 7 types CDC sont déclarés en configuration', function () {
    expect(config('pv-module.types'))
        ->toBe(['pv', 'attestation', 'decision', 'arrete', 'invitation', 'diplome', 'fiche_acces']);
});

test('chaque type CDC dispose d’un template par défaut', function () {
    foreach (config('pv-module.types') as $type) {
        $template = PvTemplate::getDefault($type);
        expect($template)->not->toBeNull("Template manquant pour {$type}")
            ->and($template->type)->toBe($type)
            ->and($template->is_default)->toBeTrue();
    }

    expect(PvTemplate::whereNull('user_id')->where('is_default', true)->count())->toBe(7);
});

test('un document de chaque type peut être créé en brouillon puis rendu en PDF', function (string $type) {
    $creator = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($creator)
        ->post(route('pv-module.store'), [
            'titre' => 'Document de test — '.$type,
            'contenu' => ['contenu' => 'Contenu du document généré pour le type '.$type.'.'],
            'type' => $type,
        ])
        ->assertRedirect();

    $pv = Pv::query()->where('type', $type)->firstOrFail();
    expect($pv->statut)->toBe(Pv::STATUT_BROUILLON)
        ->and($pv->type)->toBe($type);

    $pdf = $this->actingAs($creator)->get(route('pv-module.pdf', $pv));
    $pdf->assertOk()
        ->assertHeader('Content-Type', 'application/pdf');
    expect(substr($pdf->getContent(), 0, 4))->toBe('%PDF');
})->with(['pv', 'attestation', 'decision', 'arrete', 'invitation', 'diplome', 'fiche_acces']);

test('attestation AR longue : rendu RTL valide (R3)', function () {
    $creator = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($creator)
        ->post(route('pv-module.store'), [
            'titre' => 'شهادة حضور',
            'contenu' => ['contenu' => 'تشهد إدارة الدراسات والتكميل بجامعة منوبة أن الطالب محمد بن علي المولود بتاريخ 1980-01-01 بمنوبة والمقيد بسنة أولى دكتوراه قد حضر جميع أيام الدرس النظرية خلال السنة الجامعية 2025-2026 وفق برنامج التكوين بالمدرسة الوطنية للإعلاميات وشبكات الاتصالات التابعة لجامعة منوبة. وقد أثبت حضورا كاملًا ونظاما في الحضور خلال الفترة الممتدة من شهر سبتمبر إلى شهر جوان. وحيث يطلب من المذكور أعلاه تقديم هذه الشهادة إلى مختلف الإدارات والمصالح العمومية لهذا الغرض أنجزت هذه الشهادة لتسلم لذی الحق فی طلبها.'],
            'type' => 'attestation',
        ])
        ->assertRedirect();

    $pv = Pv::query()->where('type', 'attestation')->firstOrFail();

    config(['pv-module.default_locale' => 'ar']);

    $preview = $this->actingAs($creator)->get(route('pv-module.preview', $pv));
    $preview->assertOk()
        ->assertSee('dir="rtl"', false)
        ->assertSee('lang="ar"', false);

    $pdf = $this->actingAs($creator)->get(route('pv-module.pdf', $pv));
    $pdf->assertOk()
        ->assertHeader('Content-Type', 'application/pdf');
    expect(substr($pdf->getContent(), 0, 4))->toBe('%PDF');
});

test('invitation officielle générée en PDF', function () {
    $creator = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($creator)
        ->post(route('pv-module.store'), [
            'titre' => 'Invitation à la soutenance de thèse',
            'contenu' => [
                'contenu' => "J'ai l'honneur d'inviter Monsieur le Professeur à participer au jury de soutenance de thèse qui se tiendra le 15 décembre 2026 à 10h00 à l'amphithéâtre B de la Faculté des Sciences de Tunis.",
            ],
            'type' => 'invitation',
        ])
        ->assertRedirect();

    $pv = Pv::query()->where('type', 'invitation')->firstOrFail();

    $template = PvTemplate::getDefault('invitation');
    expect($template->orientation)->toBe('portrait');

    $pdf = $this->actingAs($creator)->get(route('pv-module.pdf', $pv));
    $pdf->assertOk()
        ->assertHeader('Content-Type', 'application/pdf');
    expect(substr($pdf->getContent(), 0, 4))->toBe('%PDF');
});

test('le modèle de décision est paramétrable en base (label + email)', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $template = DecisionTemplate::query()->create([
        'label' => 'Dérogation accordée',
        'description' => 'Octroi d’une dérogation pour la 4ᵉ inscription.',
        'email_subject' => 'Décision de dérogation — {prenom} {nom}',
        'email_body' => "Bonjour {prenom} {nom},\n\nVotre demande de dérogation a été acceptée par la commission.",
        'is_active' => true,
        'created_by' => $admin->id,
    ]);

    expect($template->label)->toBe('Dérogation accordée')
        ->and(str_contains($template->email_body, '{prenom}'))->toBeTrue()
        ->and($template->is_active)->toBeTrue();
});

test('l’écran Filament « Modèles de décision » répond pour un admin', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->get('/admin/decision-templates')
        ->assertOk()
        ->assertSee('/admin/decision-templates/create', false);
});
