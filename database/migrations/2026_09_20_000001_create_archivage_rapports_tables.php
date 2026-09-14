<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->morphs('documentable');
            $table->string('type');
            $table->string('label');
            $table->text('description')->nullable();
            $table->unsignedInteger('retention_months')->nullable();
            $table->date('retention_until')->nullable();
            $table->boolean('is_archived')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['documentable_type', 'documentable_id', 'type']);
            $table->index('retention_until');
        });

        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->string('hash', 64);
            $table->json('metadata')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->nullable()->index();

            $table->unique(['document_id', 'version']);
        });

        Schema::create('rapport_etats', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('type')->default('etat');
            $table->string('pv_type')->default('pv');
            $table->text('description')->nullable();
            $table->text('en_tete')->nullable();
            $table->string('orientation')->default('portrait');
            $table->string('format_papier')->default('A4');
            $table->json('marges')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapport_etats');
        Schema::dropIfExists('document_versions');
        Schema::dropIfExists('documents');
    }
};
