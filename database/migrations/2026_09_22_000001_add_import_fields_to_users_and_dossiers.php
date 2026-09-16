<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('grade')->nullable()->after('role');
            $table->string('structure_recherche')->nullable()->after('grade');
            $table->foreignId('etablissement_id')->nullable()->after('structure_recherche')
                ->constrained('uma_etablissements')->nullOnDelete();
        });

        Schema::table('uma_dossiers', function (Blueprint $table) {
            $table->foreignId('directeur_id')->nullable()->after('doctorant_id')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('uma_dossiers', function (Blueprint $table) {
            $table->dropForeign(['directeur_id']);
            $table->dropColumn('directeur_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['etablissement_id']);
            $table->dropColumn(['etablissement_id', 'structure_recherche', 'grade']);
        });
    }
};