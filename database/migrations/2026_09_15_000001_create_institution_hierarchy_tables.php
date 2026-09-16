<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uma_universites', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code')->nullable()->unique();
            $table->timestamps();
        });

        Schema::create('uma_ecole_doctorales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('universite_id')->constrained('uma_universites')->cascadeOnDelete();
            $table->string('nom');
            $table->timestamps();
        });

        Schema::create('uma_etablissements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecole_doctorale_id')->constrained('uma_ecole_doctorales')->cascadeOnDelete();
            $table->foreignId('directeur_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nom');
            $table->timestamps();
        });

        Schema::create('uma_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etablissement_id')->nullable()->constrained('uma_etablissements')->nullOnDelete();
            $table->foreignId('president_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nom');
            $table->string('discipline')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('commission_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commission_id')->constrained('uma_commissions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role')->nullable()->default('membre');
            $table->timestamps();

            $table->unique(['commission_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_user');
        Schema::dropIfExists('uma_commissions');
        Schema::dropIfExists('uma_etablissements');
        Schema::dropIfExists('uma_ecole_doctorales');
        Schema::dropIfExists('uma_universites');
    }
};
