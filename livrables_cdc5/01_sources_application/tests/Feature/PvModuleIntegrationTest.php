<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use SalsabilEnnaiem\PvModule\Models\Pv;
use SalsabilEnnaiem\PvModule\Models\PvValidation;
use SalsabilEnnaiem\PvModule\Notifications\PvValidated;
use SalsabilEnnaiem\PvModule\Notifications\PvValidationRequest;
use SalsabilEnnaiem\PvModule\Seeders\DefaultPvTemplateSeeder;
use SalsabilEnnaiem\PvModule\Services\SignatureService;

$signaturePng = static function (): string {
    return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
};

$storeSignature = static function (User $user) use ($signaturePng): void {
    app(SignatureService::class)->storeFromBase64($signaturePng(), $user);
};

beforeEach(function () use ($storeSignature) {
    (new DefaultPvTemplateSeeder)->run();
    $this->storeSignature = $storeSignature;
});

test('workflow PV : création → envoi → signature → PDF généré (FR et AR)', function () {
    $creator = User::factory()->create(['role' => UserRole::Admin]);
    $receiver = User::factory()->create(['role' => UserRole::MembreCommission]);
    ($this->storeSignature)($creator);
    ($this->storeSignature)($receiver);

    $this->actingAs($creator)
        ->post(route('pv-module.store'), [
            'titre' => 'PV test intégration P3',
            'contenu' => ['Délibération sur les candidatures de la commission.', 'Décision adoptée à la majorité.'],
            'type' => 'pv',
        ])
        ->assertRedirect();

    $pv = Pv::query()->firstOrFail();
    expect($pv->statut)->toBe(Pv::STATUT_BROUILLON);

    $this->actingAs($creator)
        ->post(route('pv-module.send', $pv), ['receivers' => [$receiver->getKey()]])
        ->assertRedirect();

    $pv->refresh();
    expect($pv->statut)->toBe(Pv::STATUT_EN_ATTENTE)
        ->and($receiver->notifications()->where('type', PvValidationRequest::class)->count())->toBe(1);

    $this->actingAs($receiver)
        ->post(route('pv-module.sign', $pv))
        ->assertRedirect();

    $pv->refresh();
    expect($pv->statut)->toBe(Pv::STATUT_VALIDE)
        ->and(PvValidation::query()->where('pv_id', $pv->id)->where('user_id', $receiver->id)->value('statut'))
        ->toBe(PvValidation::STATUT_VALIDE)
        ->and($creator->notifications()->where('type', PvValidated::class)->count())->toBe(1);

    $pdf = $this->actingAs($creator)->get(route('pv-module.pdf', $pv));
    $pdf->assertOk()
        ->assertHeader('Content-Type', 'application/pdf');
    expect(substr($pdf->getContent(), 0, 4))->toBe('%PDF');

    config(['pv-module.default_locale' => 'ar']);
    $pdfAr = $this->actingAs($creator)->get(route('pv-module.pdf', $pv));
    $pdfAr->assertOk();
    expect(substr($pdfAr->getContent(), 0, 4))->toBe('%PDF');

    config(['pv-module.default_locale' => 'ar']);
    $previewAr = $this->actingAs($creator)->get(route('pv-module.preview', $pv));
    $previewAr->assertOk()->assertSee('dir="rtl"', false);

    $this->actingAs($receiver)
        ->get(route('pv-module.notifications.index'))
        ->assertOk();
});

test('les notifications mail et base sont envoyées au changement d’état', function () {
    Notification::fake();

    $creator = User::factory()->create(['role' => UserRole::Admin]);
    $receiver = User::factory()->create(['role' => UserRole::MembreCommission]);
    ($this->storeSignature)($creator);
    ($this->storeSignature)($receiver);

    $this->actingAs($creator)
        ->post(route('pv-module.store'), [
            'titre' => 'PV notifications P3',
            'contenu' => ['Importante décision.'],
            'type' => 'pv',
        ]);

    $pv = Pv::query()->firstOrFail();

    $this->actingAs($creator)
        ->post(route('pv-module.send', $pv), ['receivers' => [$receiver->getKey()]]);

    $this->actingAs($receiver)
        ->post(route('pv-module.sign', $pv));

    Notification::assertSentTo($receiver, PvValidationRequest::class);
    Notification::assertSentTo($creator, PvValidated::class);
});

test('seul un participant avec validation en attente peut signer', function () {
    $creator = User::factory()->create(['role' => UserRole::Admin]);
    $receiver = User::factory()->create(['role' => UserRole::MembreCommission]);
    $outsider = User::factory()->create(['role' => UserRole::AgentAdministration]);
    ($this->storeSignature)($creator);
    ($this->storeSignature)($receiver);

    $this->actingAs($creator)
        ->post(route('pv-module.store'), [
            'titre' => 'PV accès restreint',
            'contenu' => ['Contenu sensible.'],
            'type' => 'pv',
        ]);

    $pv = Pv::query()->firstOrFail();

    $this->actingAs($creator)
        ->post(route('pv-module.send', $pv), ['receivers' => [$receiver->getKey()]]);

    $this->actingAs($outsider)
        ->post(route('pv-module.sign', $pv))
        ->assertForbidden();
});
