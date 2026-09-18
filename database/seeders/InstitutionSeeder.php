<?php

namespace Database\Seeders;

use App\Models\EcoleDoctorale;
use App\Models\Etablissement;
use App\Models\Universite;
use Illuminate\Database\Seeder;

/**
 * Hiérarchie institutionnelle de recette : une université, ses écoles
 * doctorales et leurs établissements rattachés.
 *
 * Idempotent (clés naturelles + updateOrCreate).
 */
class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        $universite = Universite::updateOrCreate(
            ['nom' => 'Université de la Manouba'],
            ['code' => 'UMAN'],
        );

        $edSti = $this->ecoleDoctorale($universite, "École doctorale Sciences et Technologies de l'Information");
        $edShs = $this->ecoleDoctorale($universite, 'École doctorale Sciences Humaines et Sociales');
        $edAgro = $this->ecoleDoctorale($universite, 'École doctorale Agronomie et Bio-Ressources');

        $this->etablissement("École Nationale des Sciences de l'Informatique (ENSI)", $edSti);
        $this->etablissement('Institut Supérieur des Arts Multimédias (ISAMM)', $edSti);
        $this->etablissement('Faculté des Sciences de Tunis (FST)', $edSti);
        $this->etablissement('Faculté des Sciences Juridiques, Économiques et de Gestion (FSJEG)', $edShs);
    }

    protected function ecoleDoctorale(Universite $universite, string $nom): EcoleDoctorale
    {
        return EcoleDoctorale::updateOrCreate(
            ['nom' => $nom],
            ['universite_id' => $universite->id],
        );
    }

    protected function etablissement(string $nom, EcoleDoctorale $ecole): Etablissement
    {
        return Etablissement::updateOrCreate(
            ['nom' => $nom],
            ['ecole_doctorale_id' => $ecole->id],
        );
    }
}
