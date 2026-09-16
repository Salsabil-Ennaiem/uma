<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uma_workflow_definitions', function (Blueprint $table) {
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

        Schema::create('uma_workflow_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_definition_id')->constrained('uma_workflow_definitions')->cascadeOnDelete();
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

        Schema::create('uma_workflow_guards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_transition_id')->constrained('uma_workflow_transitions')->cascadeOnDelete();
            $table->string('rule');
            $table->json('params')->nullable();
            $table->string('error_message')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('uma_workflow_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_definition_id')->constrained('uma_workflow_definitions')->cascadeOnDelete();
            $table->morphs('subject');
            $table->string('current_state');
            $table->json('data')->nullable();
            $table->string('status')->default('active');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('uma_workflow_audit_trails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_instance_id')->constrained('uma_workflow_instances')->cascadeOnDelete();
            $table->string('from_state')->nullable();
            $table->string('to_state');
            $table->string('transition_code');
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });

        Schema::create('uma_reclamations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commission_id')->nullable()->constrained('uma_commissions')->nullOnDelete();
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

        Schema::create('uma_reclamation_discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reclamation_id')->constrained('uma_reclamations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->timestamp('created_at')->nullable()->index();
        });

        Schema::create('uma_reservations', function (Blueprint $table) {
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
        Schema::dropIfExists('uma_reservations');
        Schema::dropIfExists('uma_reclamation_discussions');
        Schema::dropIfExists('uma_reclamations');
        Schema::dropIfExists('uma_workflow_audit_trails');
        Schema::dropIfExists('uma_workflow_instances');
        Schema::dropIfExists('uma_workflow_guards');
        Schema::dropIfExists('uma_workflow_transitions');
        Schema::dropIfExists('uma_workflow_definitions');
    }
};