GitDiagram

GitHub repository
https://github.com/salsabil-ennaiem/uma

Step 2/3
Explanation

<explanation>
## Purpose and entry points
UMA is a Laravel 13 / Filament 5 back-office platform for doctoral-training administration: institutional hierarchy, dossiers, commission meetings, decisions, document/PV generation, workflow, reporting, and audit. HTTP enters through `public/index.php` and `bootstrap/app.php`; the single authenticated Filament panel is configured by `app/Providers/Filament/AdminPanelProvider.php` at `/admin`. Limited conventional routes are in `routes/web.php`; CLI entry is `artisan`.

## Persistence and domain core
Eloquent models define the durable domain, backed by Laravel database, cache, job, notification, institutional, meeting, archival, and workflow migrations.
- Core academic/meeting records: `app/Models/Dossier.php`, `app/Models/Reunion.php`, `app/Models/Decision.php`.
- Institutional scope: `app/Models/Universite.php`, `app/Models/EcoleDoctorale.php`, `app/Models/Commission.php`.
- Document versioning and reporting: `app/Models/Document.php`, `app/Models/DocumentVersion.php`, `app/Models/RapportEtat.php`.
- Workflow state is data-driven: `app/Models/WorkflowDefinition.php`, `app/Models/WorkflowInstance.php`, `database/migrations/2026_09_21_000001_create_workflow_engine_tables.php`.

## Administrative interface and authorization boundary
Filament resources are the primary user-facing control plane, rather than a separate public portal. They bind forms/tables to domain models and expose custom meeting operations.
- Meetings: `app/Filament/Resources/Reunions/ReunionResource.php`, `app/Filament/Resources/Reunions/Pages/ManageReunionDecisions.php`.
- Dossiers/documents/decisions: `app/Filament/Resources/Dossiers/DossierResource.php`, `app/Filament/Resources/Documents/DocumentResource.php`, `app/Filament/Resources/Decisions/DecisionResource.php`.
- Authorization is native role enum plus policies, fail-closed outside explicit grants: `app/Enums/UserRole.php`, `app/Policies/ReunionPolicy.php`, `app/Policies/PvPolicy.php`.

## Meeting-to-decision-to-PV flow
`ReunionService` creates meetings transactionally, adds commission participants, governs status transitions, records audit data, and triggers notifications. Operators record presence and decisions in custom Filament pages; decisions snapshot templates and advance linked dossiers. A completed eligible meeting generates a PV sourced as `reunion/{id}` and sends it to present participants.
- Orchestration: `app/Services/ReunionService.php`, `app/Services/DecisionService.php`.
- Supporting records/audit: `app/Models/Invitation.php`, `app/Models/Presence.php`, `app/Models/AuditLog.php`.
- State constraints: `app/Enums/ReunionStatut.php`, `app/Services/AuditLogger.php`.

## Reusable PV/document package integration
The application consumes the external Composer `pv-module` rather than duplicating it. Local adapters implement package contracts and isolate UMA-specific participant resolution, access control, approval thresholds, and signature policy. PV configuration also declares seven CDC document types and FR/AR template behavior.
- App integration boundary: `config/pv-module.php`, `app/PvRules/PvRules.php`, `app/PvRules/ApprovalRules.php`.
- Signature strategy boundary: `app/Contracts/SignatureStrategy.php`, `app/PvSignatures/SignatureResolver.php`, `app/PvSignatures/QualifiedSignatureStrategy.php`.
- Package source/deliverable: `livrables_cdc5/02_sources_package/src/PvModuleServiceProvider.php`, `livrables_cdc5/02_sources_package/src/Services/PvService.php`, `livrables_cdc5/02_sources_package/src/Services/PdfService.php`.

## Workflow, communications, and compliance
Generic workflow services apply configured transitions and guards to instances, producing audit trails and notifications. Meeting and workflow events use database notifications and mail; configured mail/filesystem services are infrastructure boundaries. Archiving, CSV export, reports, imports, and immutable-style audit logs support administrative traceability.
- Workflow engine: `app/Services/WorkflowEngine.php`, `app/Services/WorkflowActions.php`, `app/Services/WorkflowGuardResolver.php`.
- Communications: `app/Services/NotificationService.php`, `app/Mail/ReunionConvocation.php`, `app/Notifications/WorkflowTransitioned.php`.
- Operational services: `app/Services/ArchiveService.php`, `app/Services/RapportService.php`, `config/archive.php`.

## Runtime and deployment shape
The runtime is PHP/Laravel with Composer dependencies, Filament server-rendered admin assets, Vite-managed frontend assets, and a configurable relational database, cache, queue, filesystem, mail transport, and session layer. Runtime configuration centralizes UMA compliance flags, approval rules, workflow definitions, and signature driver selection in `config/uma.php` and `config/workflow.php`. `livrables_cdc5/` is a delivery archive containing a duplicated application source tree, standalone package source, conception documents, deployment material, and evidence; root application paths are the active architecture.
</explanation>

