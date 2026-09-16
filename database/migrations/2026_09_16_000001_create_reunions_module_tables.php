<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uma_odj_templates', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->text('description')->nullable();
            $table->text('contenu');
            $table->foreignId('commission_id')->nullable()->constrained('uma_commissions')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('uma_reunions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commission_id')->constrained('uma_commissions')->cascadeOnDelete();
            $table->string('objet');
            $table->text('description')->nullable();
            $table->foreignId('odj_template_id')->nullable()->constrained('uma_odj_templates')->nullOnDelete();
            $table->longText('ordre_du_jour')->nullable();
            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();
            $table->string('lieu')->nullable();
            $table->string('lien')->nullable();
            $table->string('type')->default('presentiel');
            $table->string('statut')->default('brouillon');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('uma_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reunion_id')->constrained('uma_reunions')->cascadeOnDelete();
            $table->foreignId('participant_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('email')->nullable();
            $table->string('statut')->default('en_attente');
            $table->string('statut_presence')->nullable();
            $table->text('commentaire')->nullable();
            $table->text('note')->nullable();
            $table->string('excuse_status')->nullable();
            $table->foreignId('excuse_validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('excuse_validated_at')->nullable();
            $table->string('piece_joint')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('uma_presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reunion_id')->constrained('uma_reunions')->cascadeOnDelete();
            $table->foreignId('participant_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('email')->nullable();
            $table->string('statut')->default('absent');
            $table->text('note')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['reunion_id', 'participant_id', 'email']);
        });

        Schema::create('uma_dossiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctorant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('commission_id')->nullable()->constrained('uma_commissions')->nullOnDelete();
            $table->string('objet');
            $table->text('description')->nullable();
            $table->string('statut')->default('en_attente');
            $table->string('annee_inscription')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

Schema::create('reunion_dossier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reunion_id')->constrained('uma_reunions')->cascadeOnDelete();
            $table->foreignId('dossier_id')->constrained('uma_dossiers')->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['reunion_id', 'dossier_id']);
        });

        Schema::create('uma_decisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reunion_id')->constrained('uma_reunions')->cascadeOnDelete();
            $table->foreignId('dossier_id')->nullable()->constrained('uma_dossiers')->cascadeOnDelete();
            $table->foreignId('decision_template_id')->nullable()->constrained('uma_decision_templates')->nullOnDelete();
            $table->string('label');
            $table->string('email_subject')->nullable();
            $table->text('email_body')->nullable();
            $table->string('annee_inscription')->nullable();
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('uma_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('before')->nullable();
            $table->json('after')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uma_audit_logs');
        Schema::dropIfExists('uma_decisions');
        Schema::dropIfExists('reunion_dossier');
        Schema::dropIfExists('uma_dossiers');
        Schema::dropIfExists('uma_presences');
        Schema::dropIfExists('uma_invitations');
        Schema::dropIfExists('uma_reunions');
        Schema::dropIfExists('uma_odj_templates');
    }
};
