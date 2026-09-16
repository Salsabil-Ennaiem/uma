<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('subject_type');
            $table->json('states');
            $table->string('initial_state');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('workflow_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_definition_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('label')->nullable();
            $table->string('from_state');
            $table->string('to_state');
            $table->json('roles')->nullable();
            $table->json('actions')->nullable();
            $table->json('notifications')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['workflow_definition_id', 'code']);
            $table->index(['workflow_definition_id', 'from_state']);
        });

        Schema::create('workflow_guards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_transition_id')->constrained()->cascadeOnDelete();
            $table->string('rule');
            $table->json('params')->nullable();
            $table->string('error_message')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('workflow_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_definition_id')->constrained()->cascadeOnDelete();
            $table->morphs('subject');
            $table->string('current_state');
            $table->json('data')->nullable();
            $table->string('status')->default('active');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('workflow_audit_trails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_instance_id')->constrained()->cascadeOnDelete();
            $table->string('from_state')->nullable();
            $table->string('to_state');
            $table->string('transition_code');
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });

        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commission_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('objet');
            $table->text('contenu');
            $table->string('urgence')->default('prioritaire');
            $table->string('statut')->default('ouverte');
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['commission_id', 'statut']);
        });

        Schema::create('reclamation_discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reclamation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->timestamp('created_at')->nullable()->index();
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'salle' | 'jury'
            $table->string('salle')->nullable();
            $table->foreignId('membre_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->string('objet')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['type', 'salle']);
            $table->index(['type', 'membre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('reclamation_discussions');
        Schema::dropIfExists('reclamations');
        Schema::dropIfExists('workflow_audit_trails');
        Schema::dropIfExists('workflow_instances');
        Schema::dropIfExists('workflow_guards');
        Schema::dropIfExists('workflow_transitions');
        Schema::dropIfExists('workflow_definitions');
    }
};