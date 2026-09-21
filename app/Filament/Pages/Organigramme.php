<?php
// PART 1 — voir ADR-0006 : sidebar Organigramme jsOrgChart local +
// calendrier réunions (DateTimeSlotPicker wooserv). Policies : InstitutionPolicy
// existante réutilisée (aucun doublon).
namespace App\Filament\Pages;

use App\Filament\Resources\Commissions\CommissionResource;
use App\Filament\Resources\EcoleDoctorales\EcoleDoctoraleResource;
use App\Filament\Resources\Etablissements\EtablissementResource;
use App\Filament\Resources\Universites\UniversiteResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Commission;
use App\Models\EcoleDoctorale;
use App\Models\Etablissement;
use App\Models\Universite;
use App\Models\User;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class Organigramme extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShare;
    protected static string|\UnitEnum|null $navigationGroup = 'Institution';
    protected static ?string $navigationLabel = 'Organigramme';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Organigramme hierarchique';
    protected string $view = 'filament.pages.organigramme';
    public ?string $selectedType = null;
    public ?int $selectedId = null;
    public ?array $editData = [];

    public static function canAccess(): bool
    {
        return (bool) auth()->user();
    }

    public function mount(): void
    {
    }

    public function getTitle(): string|Htmlable
    {
        return 'Organigramme hierarchique';
    }



    public function getHeadingX(): string
    {
        return 'Organigramme';
    }

    public function detail(): array
    {
        $m = $this->selected;
        if (! $m) {
            return [];
        }
        if ($m instanceof Universite) {
            return ['Nom' => $m->nom, 'Code' => $m->code ?? '-', 'Ecoles' => $m->ecoleDoctorales()->count()];
        }
        if ($m instanceof EcoleDoctorale) {
            return ['Nom' => $m->nom, 'Universite' => $m->universite?->nom ?? '-', 'Etablissements' => $m->etablissements()->count()];
        }
        if ($m instanceof Etablissement) {
            return ['Nom' => $m->nom, 'Ecole' => $m->ecoleDoctorale?->nom ?? '-', 'Directeur' => $m->directeur?->name ?? '-', 'Commissions' => $m->commissions()->count()];
        }
        if ($m instanceof Commission) {
            return ['Nom' => $m->nom, 'Discipline' => $m->discipline ?? '-', 'Etablissement' => $m->etablissement?->nom ?? '-', 'President' => $m->president?->name ?? '-', 'Membres' => $m->membres()->count(), 'Active' => $m->is_active ? 'Oui' : 'Non'];
        }

        return ['Nom' => $m->name, 'Email' => $m->email, 'Role' => $m->role?->label() ?? '-', 'Etablissement' => $m->etablissement?->nom ?? '-', 'Commissions' => $m->commissions()->count()];
    }

    public function nodes(): array
    {
        $nodes = [];
        $us = Universite::query()->with(['ecoleDoctorales.etablissements.commissions.membres'])->orderBy('nom')->get();
        foreach ($us as $u) {
            $uid = 'universite-'.$u->getKey();
            $nodes[] = ['id' => $uid, 'parent' => null, 'type' => 'universite', 'modelId' => $u->getKey(), 'name' => $u->nom, 'title' => 'Universite'];
            foreach ($u->ecoleDoctorales as $e) {
                $eid = 'ecole-'.$e->getKey();
                $nodes[] = ['id' => $eid, 'parent' => $uid, 'type' => 'ecole', 'modelId' => $e->getKey(), 'name' => $e->nom, 'title' => 'Ecole doctorale'];
                foreach ($e->etablissements as $t) {
                    $tid = 'etablissement-'.$t->getKey();
                    $nodes[] = ['id' => $tid, 'parent' => $eid, 'type' => 'etablissement', 'modelId' => $t->getKey(), 'name' => $t->nom, 'title' => 'Etablissement'];
                    foreach ($t->commissions as $c) {
                        $cid = 'commission-'.$c->getKey();
                        $nodes[] = ['id' => $cid, 'parent' => $tid, 'type' => 'commission', 'modelId' => $c->getKey(), 'name' => $c->nom, 'title' => 'Commission'];
                        foreach ($c->membres as $m) {
                            $nodes[] = ['id' => 'membre-'.$c->getKey().'-'.$m->getKey(), 'parent' => $cid, 'type' => 'membre', 'modelId' => $m->getKey(), 'name' => $m->name, 'title' => 'Membre'];
                        }
                    }
                }
            }
        }

        return $nodes;
    }

    public function sel(string $type, int $id): void
    {
        $model = $this->findNode($type, $id);
        if (! $model) {
            Notification::make()->danger('Element introuvable.')->send();

            return;
        }
        if (! auth()->user()?->can('view', $model)) {
            Notification::make()->danger('Acces refuse.')->send();

            return;
        }
        $this->selectedType = $type;
        $this->selectedId = $id;
        $this->editData = $model->only(array_keys($this->attrs($type)));
        $this->dispatch('org-chart-select', id: $type.'-'.$id);
    }

    public function getSelectedProperty(): mixed
{
    if (! $this->selectedType || ! $this->selectedId) {
        return null;
    }

    return $this->findNode($this->selectedType, $this->selectedId);
}

    public function findNode(string $type, int $id): mixed
    {
        if ($type === 'universite') {
            return Universite::query()->find($id);
        }
        if ($type === 'ecole') {
            return EcoleDoctorale::query()->find($id);
        }
        if ($type === 'etablissement') {
            return Etablissement::query()->find($id);
        }
        if ($type === 'commission') {
            return Commission::query()->find($id);
        }

        return User::query()->find($id);
    }

    public function attrs(string $type): array
    {
        if ($type === 'universite') {
            return ['nom' => 1, 'code' => 1];
        }
        if ($type === 'ecole') {
            return ['nom' => 1, 'universite_id' => 1];
        }
        if ($type === 'etablissement') {
            return ['nom' => 1];
        }
        if ($type === 'commission') {
            return ['nom' => 1, 'discipline' => 1];
        }

        return ['name' => 1, 'email' => 1];
    }

    public function canEdit(): bool
    {
        $m = $this->selected;
        if (! $m) {
            return false;
        }

        return (bool) auth()->user()?->can('update', $m);
    }

    public function selectedResourceUrl(): ?string
    {
        $m = $this->selected;
        if (! $m || ! $this->selectedType) {
            return null;
        }

        if (! auth()->user()?->can('view', $m)) {
            return null;
        }

        $resource = match ($this->selectedType) {
            'universite' => UniversiteResource::class,
            'ecole' => EcoleDoctoraleResource::class,
            'etablissement' => EtablissementResource::class,
            'commission' => CommissionResource::class,
            'membre' => UserResource::class,
            default => null,
        };

        if (! $resource) {
            return null;
        }

        try {
            return $resource::getUrl('index');
        } catch (\Throwable) {
            return null;
        }
    }

    public function selectedResourceEditUrl(): ?string
    {
        $m = $this->selected;
        if (! $m || ! $this->selectedType || ! $this->canEdit()) {
            return null;
        }

        // Les resources Institution sont des ManageRecords (édition inline
        // dans le tableau) : on renvoie vers l'index. Les réunions ont une
        // vraie page edit dédiée gérée côté calendrier.
        $resource = match ($this->selectedType) {
            'universite' => UniversiteResource::class,
            'ecole' => EcoleDoctoraleResource::class,
            'etablissement' => EtablissementResource::class,
            'commission' => CommissionResource::class,
            'membre' => UserResource::class,
            default => null,
        };

        if (! $resource) {
            return null;
        }

        try {
            return $resource::getUrl('index');
        } catch (\Throwable) {
            return null;
        }
    }

    public function saveSel(): void
    {
        $m = $this->selected;
        if (! $m) {
            return;
        }
        if (! auth()->user()?->can('update', $m)) {
            Notification::make()->danger('Pas autorise.')->send();

            return;
        }
        $m->fill($this->editData ?? []);
        $m->save();
        Notification::make()->success('Mis a jour.')->send();
    }
}

