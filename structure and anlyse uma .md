Directory structure:
└── salsabil-ennaiem-uma/
    ├── README.md
    ├── AGENTS.md
    ├── artisan
    ├── ASSUMPTIONS.md
    ├── boost.json
    ├── CLAUDE.md
    ├── composer.json
    ├── NOUVELLE_APP_GUIDE_INTEGRATION.md
    ├── P9_recette_deploiement.md
    ├── package.json
    ├── phpunit.xml
    ├── PV_MODULE_CDC_COVERAGE.md
    ├── STRATEGIE_ROADMAP_PLATEFORME_UMA.md
    ├── vite.config.js
    ├── WORKFLOW_ET_COMPTES.md
    ├── .editorconfig
    ├── .env.example
    ├── .mcp.json
    ├── .npmrc
    ├── app/
    │   ├── Console/
    │   │   └── Commands/
    │   │       └── DemoP6SliceVerticale.php
    │   ├── Contracts/
    │   │   └── SignatureStrategy.php
    │   ├── Enums/
    │   │   ├── DossierStatut.php
    │   │   ├── InvitationStatut.php
    │   │   ├── PresenceStatut.php
    │   │   ├── RapportEtatType.php
    │   │   ├── ReunionStatut.php
    │   │   ├── ReunionType.php
    │   │   └── UserRole.php
    │   ├── Filament/
    │   │   ├── Actions/
    │   │   │   └── SendEmailBulkAction.php
    │   │   ├── Exports/
    │   │   │   └── UserExporter.php
    │   │   ├── Pages/
    │   │   │   └── Imports/
    │   │   │       ├── ImportEnseignants.php
    │   │   │       └── ImportTheses.php
    │   │   └── Resources/
    │   │       ├── DecisionTemplateResource.php
    │   │       ├── AuditLogs/
    │   │       │   ├── AuditLogResource.php
    │   │       │   ├── Pages/
    │   │       │   │   └── ListAuditLogs.php
    │   │       │   └── Tables/
    │   │       │       └── AuditLogsTable.php
    │   │       ├── Commissions/
    │   │       │   ├── CommissionResource.php
    │   │       │   ├── .gitkeep
    │   │       │   └── Pages/
    │   │       │       └── ManageCommissions.php
    │   │       ├── Decisions/
    │   │       │   ├── DecisionResource.php
    │   │       │   ├── Pages/
    │   │       │   │   ├── CreateDecision.php
    │   │       │   │   ├── EditDecision.php
    │   │       │   │   └── ListDecisions.php
    │   │       │   ├── Schemas/
    │   │       │   │   └── DecisionForm.php
    │   │       │   └── Tables/
    │   │       │       └── DecisionsTable.php
    │   │       ├── DecisionTemplateResource/
    │   │       │   └── Pages/
    │   │       │       ├── CreateDecisionTemplate.php
    │   │       │       ├── EditDecisionTemplate.php
    │   │       │       └── ListDecisionTemplates.php
    │   │       ├── Doctorat/
    │   │       │   └── .gitkeep
    │   │       ├── Documents/
    │   │       │   ├── DocumentResource.php
    │   │       │   ├── Pages/
    │   │       │   │   ├── CreateDocument.php
    │   │       │   │   ├── EditDocument.php
    │   │       │   │   └── ListDocuments.php
    │   │       │   ├── RelationManagers/
    │   │       │   │   └── DocumentVersionsRelationManager.php
    │   │       │   ├── Schemas/
    │   │       │   │   └── DocumentForm.php
    │   │       │   └── Tables/
    │   │       │       └── DocumentsTable.php
    │   │       ├── Dossiers/
    │   │       │   ├── DossierResource.php
    │   │       │   ├── Pages/
    │   │       │   │   ├── CreateDossier.php
    │   │       │   │   ├── EditDossier.php
    │   │       │   │   └── ListDossiers.php
    │   │       │   ├── RelationManagers/
    │   │       │   │   ├── DecisionsRelationManager.php
    │   │       │   │   └── DocumentsRelationManager.php
    │   │       │   ├── Schemas/
    │   │       │   │   └── DossierForm.php
    │   │       │   └── Tables/
    │   │       │       └── DossiersTable.php
    │   │       ├── EcoleDoctorales/
    │   │       │   ├── EcoleDoctoraleResource.php
    │   │       │   └── Pages/
    │   │       │       └── ManageEcoleDoctorales.php
    │   │       ├── Etablissements/
    │   │       │   ├── EtablissementResource.php
    │   │       │   └── Pages/
    │   │       │       └── ManageEtablissements.php
    │   │       ├── RapportEtats/
    │   │       │   ├── RapportEtatsResource.php
    │   │       │   ├── Pages/
    │   │       │   │   ├── CreateRapportEtat.php
    │   │       │   │   ├── EditRapportEtat.php
    │   │       │   │   └── ListRapportEtats.php
    │   │       │   ├── Schemas/
    │   │       │   │   └── RapportEtatForm.php
    │   │       │   └── Tables/
    │   │       │       └── RapportEtatsTable.php
    │   │       ├── Reunions/
    │   │       │   ├── ReunionResource.php
    │   │       │   ├── .gitkeep
    │   │       │   ├── Pages/
    │   │       │   │   ├── CreateReunion.php
    │   │       │   │   ├── EditReunion.php
    │   │       │   │   ├── ListReunions.php
    │   │       │   │   ├── ManageReunionDecisions.php
    │   │       │   │   ├── ManageReunionPresences.php
    │   │       │   │   └── ReunionCorbeille.php
    │   │       │   ├── Schemas/
    │   │       │   │   └── ReunionForm.php
    │   │       │   └── Tables/
    │   │       │       └── ReunionsTable.php
    │   │       ├── Universites/
    │   │       │   ├── UniversiteResource.php
    │   │       │   └── Pages/
    │   │       │       └── ManageUniversites.php
    │   │       └── Users/
    │   │           ├── UserResource.php
    │   │           ├── Pages/
    │   │           │   └── ListUsers.php
    │   │           └── Tables/
    │   │               └── UsersTable.php
    │   ├── Http/
    │   │   └── Controllers/
    │   │       ├── Controller.php
    │   │       └── RapportController.php
    │   ├── Imports/
    │   │   ├── BaseImport.php
    │   │   ├── EnseignantImport.php
    │   │   ├── ImportResult.php
    │   │   ├── ImportRow.php
    │   │   └── TheseImport.php
    │   ├── Mail/
    │   │   ├── FilteredListEmail.php
    │   │   ├── ReunionConvocation.php
    │   │   └── WorkflowStateChanged.php
    │   ├── Models/
    │   │   ├── AuditLog.php
    │   │   ├── Commission.php
    │   │   ├── Decision.php
    │   │   ├── DecisionTemplate.php
    │   │   ├── Document.php
    │   │   ├── DocumentVersion.php
    │   │   ├── Dossier.php
    │   │   ├── EcoleDoctorale.php
    │   │   ├── Etablissement.php
    │   │   ├── Invitation.php
    │   │   ├── OdjTemplate.php
    │   │   ├── Presence.php
    │   │   ├── RapportEtat.php
    │   │   ├── Reclamation.php
    │   │   ├── ReclamationDiscussion.php
    │   │   ├── Reservation.php
    │   │   ├── Reunion.php
    │   │   ├── Universite.php
    │   │   ├── User.php
    │   │   ├── WorkflowAuditTrail.php
    │   │   ├── WorkflowDefinition.php
    │   │   ├── WorkflowGuard.php
    │   │   ├── WorkflowInstance.php
    │   │   └── WorkflowTransition.php
    │   ├── Notifications/
    │   │   ├── ReunionPlanifiee.php
    │   │   ├── ReunionTerminee.php
    │   │   └── WorkflowTransitioned.php
    │   ├── Policies/
    │   │   ├── AuditLogPolicy.php
    │   │   ├── CommissionPolicy.php
    │   │   ├── DecisionPolicy.php
    │   │   ├── DecisionTemplatePolicy.php
    │   │   ├── DocumentPolicy.php
    │   │   ├── DossierPolicy.php
    │   │   ├── InstitutionPolicy.php
    │   │   ├── InvitationPolicy.php
    │   │   ├── OdjTemplatePolicy.php
    │   │   ├── PresencePolicy.php
    │   │   ├── PvPolicy.php
    │   │   ├── RapportEtatPolicy.php
    │   │   ├── ReunionPolicy.php
    │   │   └── UserPolicy.php
    │   ├── Providers/
    │   │   ├── AppServiceProvider.php
    │   │   └── Filament/
    │   │       └── AdminPanelProvider.php
    │   ├── PvRules/
    │   │   ├── ApprovalRules.php
    │   │   ├── ParticipantResolver.php
    │   │   ├── PvRules.php
    │   │   └── .gitkeep
    │   ├── PvSignatures/
    │   │   ├── QualifiedSignatureStrategy.php
    │   │   ├── SignatureResolver.php
    │   │   └── SimpleImageSignatureStrategy.php
    │   └── Services/
    │       ├── ArchiveService.php
    │       ├── AuditLogger.php
    │       ├── CsvExportService.php
    │       ├── DecisionService.php
    │       ├── NotificationService.php
    │       ├── RapportService.php
    │       ├── ReunionService.php
    │       ├── WorkflowActions.php
    │       ├── WorkflowEngine.php
    │       ├── WorkflowGuardResolver.php
    │       └── .gitkeep
    ├── bootstrap/
    │   ├── app.php
    │   └── providers.php
    ├── config/
    │   ├── app.php
    │   ├── archive.php
    │   ├── auth.php
    │   ├── cache.php
    │   ├── database.php
    │   ├── filesystems.php
    │   ├── logging.php
    │   ├── mail.php
    │   ├── pv-module.php
    │   ├── queue.php
    │   ├── services.php
    │   ├── session.php
    │   ├── uma.php
    │   └── workflow.php
    ├── database/
    │   ├── project details_EPS_FSMVU.docx
    │   ├── factories/
    │   │   ├── CommissionFactory.php
    │   │   ├── DecisionFactory.php
    │   │   ├── DecisionTemplateFactory.php
    │   │   ├── DossierFactory.php
    │   │   ├── EcoleDoctoraleFactory.php
    │   │   ├── EtablissementFactory.php
    │   │   ├── InvitationFactory.php
    │   │   ├── OdjTemplateFactory.php
    │   │   ├── PresenceFactory.php
    │   │   ├── ReclamationFactory.php
    │   │   ├── ReservationFactory.php
    │   │   ├── ReunionFactory.php
    │   │   ├── UniversiteFactory.php
    │   │   ├── UserFactory.php
    │   │   └── WorkflowDefinitionFactory.php
    │   ├── migrations/
    │   │   ├── 0001_01_01_000000_create_users_table.php
    │   │   ├── 0001_01_01_000001_create_cache_table.php
    │   │   ├── 0001_01_01_000002_create_jobs_table.php
    │   │   ├── 2026_09_13_000001_add_role_to_users_table.php
    │   │   ├── 2026_09_13_000002_create_notifications_table.php
    │   │   ├── 2026_09_14_000001_create_decision_templates_table.php
    │   │   ├── 2026_09_14_000002_create_exports_table.php
    │   │   ├── 2026_09_15_000001_create_institution_hierarchy_tables.php
    │   │   ├── 2026_09_16_000001_create_reunions_module_tables.php
    │   │   ├── 2026_09_20_000001_create_archivage_rapports_tables.php
    │   │   ├── 2026_09_21_000001_create_workflow_engine_tables.php
    │   │   └── 2026_09_22_000001_add_import_fields_to_users_and_dossiers.php
    │   └── seeders/
    │       ├── DatabaseSeeder.php
    │       ├── UmaDocumentTemplatesSeeder.php
    │       ├── UsersSeeder.php
    │       └── WorkflowSeeder.php
    ├── docs/
    │   ├── bugs-and-solutions.md
    │   ├── commands-used.md
    │   ├── DECISIONS_UMA.md
    │   ├── demo_p8.5a.md
    │   ├── mail_infrastructure_cck.md
    │   ├── mail_relance_uma.md
    │   ├── structure-du-projet.md
    │   └── transformation-modules-interopérables.md
    ├── livrables_cdc5/
    │   ├── README.md
    │   ├── 01_sources_application/
    │   │   ├── README.md
    │   │   ├── AGENTS.md
    │   │   ├── artisan
    │   │   ├── ASSUMPTIONS.md
    │   │   ├── boost.json
    │   │   ├── CLAUDE.md
    │   │   ├── composer.json
    │   │   ├── NOUVELLE_APP_GUIDE_INTEGRATION.md
    │   │   ├── P9_recette_deploiement.md
    │   │   ├── package.json
    │   │   ├── phpunit.xml
    │   │   ├── PV_MODULE_CDC_COVERAGE.md
    │   │   ├── STRATEGIE_ROADMAP_PLATEFORME_UMA.md
    │   │   ├── vite.config.js
    │   │   ├── WORKFLOW_ET_COMPTES.md
    │   │   ├── .editorconfig
    │   │   ├── .env.example
    │   │   ├── .mcp.json
    │   │   ├── .npmrc
    │   │   ├── app/
    │   │   │   ├── Console/
    │   │   │   │   └── Commands/
    │   │   │   │       └── DemoP6SliceVerticale.php
    │   │   │   ├── Contracts/
    │   │   │   │   └── SignatureStrategy.php
    │   │   │   ├── Enums/
    │   │   │   │   ├── DossierStatut.php
    │   │   │   │   ├── InvitationStatut.php
    │   │   │   │   ├── PresenceStatut.php
    │   │   │   │   ├── RapportEtatType.php
    │   │   │   │   ├── ReunionStatut.php
    │   │   │   │   ├── ReunionType.php
    │   │   │   │   └── UserRole.php
    │   │   │   ├── Filament/
    │   │   │   │   ├── Actions/
    │   │   │   │   │   └── SendEmailBulkAction.php
    │   │   │   │   ├── Exports/
    │   │   │   │   │   └── UserExporter.php
    │   │   │   │   ├── Pages/
    │   │   │   │   │   └── Imports/
    │   │   │   │   │       ├── ImportEnseignants.php
    │   │   │   │   │       └── ImportTheses.php
    │   │   │   │   └── Resources/
    │   │   │   │       ├── DecisionTemplateResource.php
    │   │   │   │       ├── AuditLogs/
    │   │   │   │       │   ├── AuditLogResource.php
    │   │   │   │       │   ├── Pages/
    │   │   │   │       │   │   └── ListAuditLogs.php
    │   │   │   │       │   └── Tables/
    │   │   │   │       │       └── AuditLogsTable.php
    │   │   │   │       ├── Commissions/
    │   │   │   │       │   ├── CommissionResource.php
    │   │   │   │       │   ├── .gitkeep
    │   │   │   │       │   └── Pages/
    │   │   │   │       │       └── ManageCommissions.php
    │   │   │   │       ├── Decisions/
    │   │   │   │       │   ├── DecisionResource.php
    │   │   │   │       │   ├── Pages/
    │   │   │   │       │   │   ├── CreateDecision.php
    │   │   │   │       │   │   ├── EditDecision.php
    │   │   │   │       │   │   └── ListDecisions.php
    │   │   │   │       │   ├── Schemas/
    │   │   │   │       │   │   └── DecisionForm.php
    │   │   │   │       │   └── Tables/
    │   │   │   │       │       └── DecisionsTable.php
    │   │   │   │       ├── DecisionTemplateResource/
    │   │   │   │       │   └── Pages/
    │   │   │   │       │       ├── CreateDecisionTemplate.php
    │   │   │   │       │       ├── EditDecisionTemplate.php
    │   │   │   │       │       └── ListDecisionTemplates.php
    │   │   │   │       ├── Doctorat/
    │   │   │   │       │   └── .gitkeep
    │   │   │   │       ├── Documents/
    │   │   │   │       │   ├── DocumentResource.php
    │   │   │   │       │   ├── Pages/
    │   │   │   │       │   │   ├── CreateDocument.php
    │   │   │   │       │   │   ├── EditDocument.php
    │   │   │   │       │   │   └── ListDocuments.php
    │   │   │   │       │   ├── RelationManagers/
    │   │   │   │       │   │   └── DocumentVersionsRelationManager.php
    │   │   │   │       │   ├── Schemas/
    │   │   │   │       │   │   └── DocumentForm.php
    │   │   │   │       │   └── Tables/
    │   │   │   │       │       └── DocumentsTable.php
    │   │   │   │       ├── Dossiers/
    │   │   │   │       │   ├── DossierResource.php
    │   │   │   │       │   ├── Pages/
    │   │   │   │       │   │   ├── CreateDossier.php
    │   │   │   │       │   │   ├── EditDossier.php
    │   │   │   │       │   │   └── ListDossiers.php
    │   │   │   │       │   ├── RelationManagers/
    │   │   │   │       │   │   ├── DecisionsRelationManager.php
    │   │   │   │       │   │   └── DocumentsRelationManager.php
    │   │   │   │       │   ├── Schemas/
    │   │   │   │       │   │   └── DossierForm.php
    │   │   │   │       │   └── Tables/
    │   │   │   │       │       └── DossiersTable.php
    │   │   │   │       ├── EcoleDoctorales/
    │   │   │   │       │   ├── EcoleDoctoraleResource.php
    │   │   │   │       │   └── Pages/
    │   │   │   │       │       └── ManageEcoleDoctorales.php
    │   │   │   │       ├── Etablissements/
    │   │   │   │       │   ├── EtablissementResource.php
    │   │   │   │       │   └── Pages/
    │   │   │   │       │       └── ManageEtablissements.php
    │   │   │   │       ├── RapportEtats/
    │   │   │   │       │   ├── RapportEtatsResource.php
    │   │   │   │       │   ├── Pages/
    │   │   │   │       │   │   ├── CreateRapportEtat.php
    │   │   │   │       │   │   ├── EditRapportEtat.php
    │   │   │   │       │   │   └── ListRapportEtats.php
    │   │   │   │       │   ├── Schemas/
    │   │   │   │       │   │   └── RapportEtatForm.php
    │   │   │   │       │   └── Tables/
    │   │   │   │       │       └── RapportEtatsTable.php
    │   │   │   │       ├── Reunions/
    │   │   │   │       │   ├── ReunionResource.php
    │   │   │   │       │   ├── .gitkeep
    │   │   │   │       │   ├── Pages/
    │   │   │   │       │   │   ├── CreateReunion.php
    │   │   │   │       │   │   ├── EditReunion.php
    │   │   │   │       │   │   ├── ListReunions.php
    │   │   │   │       │   │   ├── ManageReunionDecisions.php
    │   │   │   │       │   │   ├── ManageReunionPresences.php
    │   │   │   │       │   │   └── ReunionCorbeille.php
    │   │   │   │       │   ├── Schemas/
    │   │   │   │       │   │   └── ReunionForm.php
    │   │   │   │       │   └── Tables/
    │   │   │   │       │       └── ReunionsTable.php
    │   │   │   │       ├── Universites/
    │   │   │   │       │   ├── UniversiteResource.php
    │   │   │   │       │   └── Pages/
    │   │   │   │       │       └── ManageUniversites.php
    │   │   │   │       └── Users/
    │   │   │   │           ├── UserResource.php
    │   │   │   │           ├── Pages/
    │   │   │   │           │   └── ListUsers.php
    │   │   │   │           └── Tables/
    │   │   │   │               └── UsersTable.php
    │   │   │   ├── Http/
    │   │   │   │   └── Controllers/
    │   │   │   │       ├── Controller.php
    │   │   │   │       └── RapportController.php
    │   │   │   ├── Imports/
    │   │   │   │   ├── BaseImport.php
    │   │   │   │   ├── EnseignantImport.php
    │   │   │   │   ├── ImportResult.php
    │   │   │   │   ├── ImportRow.php
    │   │   │   │   └── TheseImport.php
    │   │   │   ├── Mail/
    │   │   │   │   ├── FilteredListEmail.php
    │   │   │   │   ├── ReunionConvocation.php
    │   │   │   │   └── WorkflowStateChanged.php
    │   │   │   ├── Models/
    │   │   │   │   ├── AuditLog.php
    │   │   │   │   ├── Commission.php
    │   │   │   │   ├── Decision.php
    │   │   │   │   ├── DecisionTemplate.php
    │   │   │   │   ├── Document.php
    │   │   │   │   ├── DocumentVersion.php
    │   │   │   │   ├── Dossier.php
    │   │   │   │   ├── EcoleDoctorale.php
    │   │   │   │   ├── Etablissement.php
    │   │   │   │   ├── Invitation.php
    │   │   │   │   ├── OdjTemplate.php
    │   │   │   │   ├── Presence.php
    │   │   │   │   ├── RapportEtat.php
    │   │   │   │   ├── Reclamation.php
    │   │   │   │   ├── ReclamationDiscussion.php
    │   │   │   │   ├── Reservation.php
    │   │   │   │   ├── Reunion.php
    │   │   │   │   ├── Universite.php
    │   │   │   │   ├── User.php
    │   │   │   │   ├── WorkflowAuditTrail.php
    │   │   │   │   ├── WorkflowDefinition.php
    │   │   │   │   ├── WorkflowGuard.php
    │   │   │   │   ├── WorkflowInstance.php
    │   │   │   │   └── WorkflowTransition.php
    │   │   │   ├── Notifications/
    │   │   │   │   ├── ReunionPlanifiee.php
    │   │   │   │   ├── ReunionTerminee.php
    │   │   │   │   └── WorkflowTransitioned.php
    │   │   │   ├── Policies/
    │   │   │   │   ├── AuditLogPolicy.php
    │   │   │   │   ├── CommissionPolicy.php
    │   │   │   │   ├── DecisionPolicy.php
    │   │   │   │   ├── DecisionTemplatePolicy.php
    │   │   │   │   ├── DocumentPolicy.php
    │   │   │   │   ├── DossierPolicy.php
    │   │   │   │   ├── InstitutionPolicy.php
    │   │   │   │   ├── InvitationPolicy.php
    │   │   │   │   ├── OdjTemplatePolicy.php
    │   │   │   │   ├── PresencePolicy.php
    │   │   │   │   ├── PvPolicy.php
    │   │   │   │   ├── RapportEtatPolicy.php
    │   │   │   │   ├── ReunionPolicy.php
    │   │   │   │   └── UserPolicy.php
    │   │   │   ├── Providers/
    │   │   │   │   ├── AppServiceProvider.php
    │   │   │   │   └── Filament/
    │   │   │   │       └── AdminPanelProvider.php
    │   │   │   ├── PvRules/
    │   │   │   │   ├── ApprovalRules.php
    │   │   │   │   ├── ParticipantResolver.php
    │   │   │   │   ├── PvRules.php
    │   │   │   │   └── .gitkeep
    │   │   │   ├── PvSignatures/
    │   │   │   │   ├── QualifiedSignatureStrategy.php
    │   │   │   │   ├── SignatureResolver.php
    │   │   │   │   └── SimpleImageSignatureStrategy.php
    │   │   │   └── Services/
    │   │   │       ├── ArchiveService.php
    │   │   │       ├── AuditLogger.php
    │   │   │       ├── CsvExportService.php
    │   │   │       ├── DecisionService.php
    │   │   │       ├── NotificationService.php
    │   │   │       ├── RapportService.php
    │   │   │       ├── ReunionService.php
    │   │   │       ├── WorkflowActions.php
    │   │   │       ├── WorkflowEngine.php
    │   │   │       ├── WorkflowGuardResolver.php
    │   │   │       └── .gitkeep
    │   │   ├── bootstrap/
    │   │   │   ├── app.php
    │   │   │   └── providers.php
    │   │   ├── config/
    │   │   │   ├── app.php
    │   │   │   ├── archive.php
    │   │   │   ├── auth.php
    │   │   │   ├── cache.php
    │   │   │   ├── database.php
    │   │   │   ├── filesystems.php
    │   │   │   ├── logging.php
    │   │   │   ├── mail.php
    │   │   │   ├── pv-module.php
    │   │   │   ├── queue.php
    │   │   │   ├── services.php
    │   │   │   ├── session.php
    │   │   │   ├── uma.php
    │   │   │   └── workflow.php
    │   │   ├── database/
    │   │   │   ├── factories/
    │   │   │   │   ├── CommissionFactory.php
    │   │   │   │   ├── DecisionFactory.php
    │   │   │   │   ├── DecisionTemplateFactory.php
    │   │   │   │   ├── DossierFactory.php
    │   │   │   │   ├── EcoleDoctoraleFactory.php
    │   │   │   │   ├── EtablissementFactory.php
    │   │   │   │   ├── InvitationFactory.php
    │   │   │   │   ├── OdjTemplateFactory.php
    │   │   │   │   ├── PresenceFactory.php
    │   │   │   │   ├── ReclamationFactory.php
    │   │   │   │   ├── ReservationFactory.php
    │   │   │   │   ├── ReunionFactory.php
    │   │   │   │   ├── UniversiteFactory.php
    │   │   │   │   ├── UserFactory.php
    │   │   │   │   └── WorkflowDefinitionFactory.php
    │   │   │   ├── migrations/
    │   │   │   │   ├── 0001_01_01_000000_create_users_table.php
    │   │   │   │   ├── 0001_01_01_000001_create_cache_table.php
    │   │   │   │   ├── 0001_01_01_000002_create_jobs_table.php
    │   │   │   │   ├── 2026_09_13_000001_add_role_to_users_table.php
    │   │   │   │   ├── 2026_09_13_000002_create_notifications_table.php
    │   │   │   │   ├── 2026_09_14_000001_create_decision_templates_table.php
    │   │   │   │   ├── 2026_09_14_000002_create_exports_table.php
    │   │   │   │   ├── 2026_09_15_000001_create_institution_hierarchy_tables.php
    │   │   │   │   ├── 2026_09_16_000001_create_reunions_module_tables.php
    │   │   │   │   ├── 2026_09_20_000001_create_archivage_rapports_tables.php
    │   │   │   │   ├── 2026_09_21_000001_create_workflow_engine_tables.php
    │   │   │   │   └── 2026_09_22_000001_add_import_fields_to_users_and_dossiers.php
    │   │   │   └── seeders/
    │   │   │       ├── DatabaseSeeder.php
    │   │   │       ├── UmaDocumentTemplatesSeeder.php
    │   │   │       └── WorkflowSeeder.php
    │   │   ├── docs/
    │   │   │   ├── bugs-and-solutions.md
    │   │   │   ├── commands-used.md
    │   │   │   ├── structure-du-projet.md
    │   │   │   └── transformation-modules-interopérables.md
    │   │   ├── public/
    │   │   │   ├── index.php
    │   │   │   ├── robots.txt
    │   │   │   ├── .htaccess
    │   │   │   ├── fonts/
    │   │   │   │   └── filament/
    │   │   │   │       └── filament/
    │   │   │   │           └── inter/
    │   │   │   │               ├── index.css
    │   │   │   │               ├── inter-cyrillic-ext-wght-normal-SP7Z6XGK.woff2
    │   │   │   │               ├── inter-cyrillic-wght-normal-DR6K5BQD.woff2
    │   │   │   │               ├── inter-greek-ext-wght-normal-A2J6H3EX.woff2
    │   │   │   │               ├── inter-greek-wght-normal-ZABHMKQG.woff2
    │   │   │   │               └── inter-vietnamese-wght-normal-VWEHJHBA.woff2
    │   │   │   └── js/
    │   │   │       └── filament/
    │   │   │           ├── actions/
    │   │   │           │   └── actions.js
    │   │   │           ├── filament/
    │   │   │           │   └── app.js
    │   │   │           ├── forms/
    │   │   │           │   └── components/
    │   │   │           │       ├── checkbox-list.js
    │   │   │           │       ├── color-picker.js
    │   │   │           │       ├── key-value.js
    │   │   │           │       ├── slider.js
    │   │   │           │       ├── tags-input.js
    │   │   │           │       └── textarea.js
    │   │   │           ├── notifications/
    │   │   │           │   └── notifications.js
    │   │   │           ├── schemas/
    │   │   │           │   ├── schemas.js
    │   │   │           │   └── components/
    │   │   │           │       ├── actions.js
    │   │   │           │       ├── tabs.js
    │   │   │           │       └── wizard.js
    │   │   │           └── tables/
    │   │   │               ├── tables.js
    │   │   │               └── components/
    │   │   │                   └── columns/
    │   │   │                       ├── checkbox.js
    │   │   │                       ├── text-input.js
    │   │   │                       └── toggle.js
    │   │   ├── resources/
    │   │   │   ├── css/
    │   │   │   │   └── app.css
    │   │   │   ├── js/
    │   │   │   │   └── app.js
    │   │   │   └── views/
    │   │   │       ├── emails/
    │   │   │       │   ├── reunion/
    │   │   │       │   │   └── convocation.blade.php
    │   │   │       │   └── workflow/
    │   │   │       │       └── state-changed.blade.php
    │   │   │       └── filament/
    │   │   │           ├── pages/
    │   │   │           │   └── imports/
    │   │   │           │       └── importer.blade.php
    │   │   │           └── resources/
    │   │   │               └── reunions/
    │   │   │                   └── pages/
    │   │   │                       ├── manage-reunion-decisions.blade.php
    │   │   │                       ├── manage-reunion-presences.blade.php
    │   │   │                       └── reunion-corbeille.blade.php
    │   │   ├── routes/
    │   │   │   ├── console.php
    │   │   │   └── web.php
    │   │   ├── tests/
    │   │   │   ├── Pest.php
    │   │   │   ├── TestCase.php
    │   │   │   ├── Feature/
    │   │   │   │   ├── AdminPanelAccessTest.php
    │   │   │   │   ├── CdcDocumentTypesTest.php
    │   │   │   │   ├── ExampleTest.php
    │   │   │   │   ├── ImportMoulinetTest.php
    │   │   │   │   ├── ImportPagesTest.php
    │   │   │   │   ├── P5RbAcContratsTest.php
    │   │   │   │   ├── P7ArchivageAuditTest.php
    │   │   │   │   ├── P8WorkflowEngineTest.php
    │   │   │   │   ├── P9ChecklistCdcTest.php
    │   │   │   │   ├── PvModuleIntegrationTest.php
    │   │   │   │   ├── ReunionsModuleTest.php
    │   │   │   │   └── SocleCriteriaTest.php
    │   │   │   └── Unit/
    │   │   │       └── ExampleTest.php
    │   │   └── .claude/
    │   │       └── skills/
    │   │           ├── deploying-to-cloud/
    │   │           │   ├── SKILL.md
    │   │           │   └── reference/
    │   │           │       └── checklists.md
    │   │           ├── infer-conventions/
    │   │           │   ├── SKILL.md
    │   │           │   └── references/
    │   │           │       └── checklist.md
    │   │           ├── laravel-best-practices/
    │   │           │   ├── SKILL.md
    │   │           │   └── rules/
    │   │           │       ├── advanced-queries.md
    │   │           │       ├── architecture.md
    │   │           │       ├── blade-views.md
    │   │           │       ├── caching.md
    │   │           │       ├── collections.md
    │   │           │       ├── config.md
    │   │           │       ├── db-performance.md
    │   │           │       ├── eloquent.md
    │   │           │       ├── error-handling.md
    │   │           │       ├── events-notifications.md
    │   │           │       ├── http-client.md
    │   │           │       ├── mail.md
    │   │           │       ├── migrations.md
    │   │           │       ├── queue-jobs.md
    │   │           │       ├── routing.md
    │   │           │       ├── scheduling.md
    │   │           │       ├── security.md
    │   │           │       ├── style.md
    │   │           │       └── validation.md
    │   │           ├── tailwindcss-development/
    │   │           │   └── SKILL.md
    │   │           └── testing-best-practices/
    │   │               ├── SKILL.md
    │   │               └── rules/
    │   │                   ├── assertions.md
    │   │                   ├── endpoint-tests.md
    │   │                   ├── finding-features.md
    │   │                   ├── isolation.md
    │   │                   ├── naming.md
    │   │                   ├── performance.md
    │   │                   ├── review.md
    │   │                   ├── security.md
    │   │                   └── test-data.md
    │   ├── 02_sources_package/
    │   │   ├── README.md
    │   │   ├── ASSUMPTIONS.md
    │   │   ├── composer.json
    │   │   ├── LICENSE
    │   │   ├── phpunit.xml
    │   │   ├── config/
    │   │   │   └── pv-module.php
    │   │   ├── database/
    │   │   │   └── migrations/
    │   │   │       ├── 2026_01_01_000000_create_pv_module_tables.php
    │   │   │       ├── 2026_01_02_000000_add_source_columns_to_pv_module_pvs.php
    │   │   │       └── 2026_01_03_000000_add_signature_conformity_to_pv_module_signatures.php
    │   │   ├── resources/
    │   │   │   ├── lang/
    │   │   │   │   ├── ar.json
    │   │   │   │   ├── en.json
    │   │   │   │   └── fr.json
    │   │   │   └── views/
    │   │   │       ├── layouts/
    │   │   │       │   └── app.blade.php
    │   │   │       ├── notifications/
    │   │   │       │   └── index.blade.php
    │   │   │       ├── partials/
    │   │   │       │   ├── breadcrumb.blade.php
    │   │   │       │   └── status-badge.blade.php
    │   │   │       ├── pdfs/
    │   │   │       │   └── pv_template.blade.php
    │   │   │       ├── pv/
    │   │   │       │   ├── create.blade.php
    │   │   │       │   ├── edit.blade.php
    │   │   │       │   ├── index.blade.php
    │   │   │       │   ├── preview.blade.php
    │   │   │       │   ├── show.blade.php
    │   │   │       │   └── versions.blade.php
    │   │   │       ├── signature/
    │   │   │       │   └── index.blade.php
    │   │   │       └── templates/
    │   │   │           ├── edit.blade.php
    │   │   │           └── index.blade.php
    │   │   ├── routes/
    │   │   │   └── web.php
    │   │   ├── src/
    │   │   │   ├── PvModuleServiceProvider.php
    │   │   │   ├── Contracts/
    │   │   │   │   ├── ApprovalRules.php
    │   │   │   │   ├── CanManagePv.php
    │   │   │   │   └── ParticipantResolver.php
    │   │   │   ├── Defaults/
    │   │   │   │   ├── DefaultApprovalRules.php
    │   │   │   │   ├── DefaultParticipantResolver.php
    │   │   │   │   └── DefaultPvRules.php
    │   │   │   ├── Exceptions/
    │   │   │   │   └── PvModuleException.php
    │   │   │   ├── Http/
    │   │   │   │   ├── Controllers/
    │   │   │   │   │   ├── PvController.php
    │   │   │   │   │   ├── SignatureController.php
    │   │   │   │   │   └── TemplateController.php
    │   │   │   │   └── Requests/
    │   │   │   │       ├── StorePvRequest.php
    │   │   │   │       └── UpdatePvRequest.php
    │   │   │   ├── Models/
    │   │   │   │   ├── Pv.php
    │   │   │   │   ├── PvSignature.php
    │   │   │   │   ├── PvTemplate.php
    │   │   │   │   └── PvValidation.php
    │   │   │   ├── Notifications/
    │   │   │   │   ├── PvValidated.php
    │   │   │   │   └── PvValidationRequest.php
    │   │   │   ├── Seeders/
    │   │   │   │   └── DefaultPvTemplateSeeder.php
    │   │   │   └── Services/
    │   │   │       ├── PdfService.php
    │   │   │       ├── PvService.php
    │   │   │       └── SignatureService.php
    │   │   └── tests/
    │   │       ├── TestCase.php
    │   │       ├── artifacts/
    │   │       │   └── arabic_pv_preview.html
    │   │       ├── database/
    │   │       │   └── migrations/
    │   │       │       ├── 2025_01_01_000001_create_users_table.php
    │   │       │       └── 2026_01_01_000003_create_notifications_table.php
    │   │       ├── Feature/
    │   │       │   ├── NotificationRoutePrefixTest.php
    │   │       │   ├── PdfGenerationTest.php
    │   │       │   ├── PvSecurityTest.php
    │   │       │   └── PvWorkflowTest.php
    │   │       └── Models/
    │   │           └── User.php
    │   ├── 03_documents_conception/
    │   │   ├── ASSUMPTIONS.md
    │   │   ├── DECISIONS_UMA.md
    │   │   ├── DOSSIER_RE_SOLLICITATION_UMA.md
    │   │   ├── NOUVELLE_APP_GUIDE_INTEGRATION.md
    │   │   ├── PV_MODULE_CDC_COVERAGE.md
    │   │   ├── PV_MODULE_INTEGRATION_GUIDE.md
    │   │   ├── PV_MODULE_ORIGINE_ET_MULTILANGUE.md
    │   │   ├── PV_MODULE_RENAME_GUIDE.md
    │   │   ├── STRATEGIE_ROADMAP_PLATEFORME_UMA.md
    │   │   └── WORKFLOW_ET_COMPTES.md
    │   ├── 04_graphiques/
    │   │   └── README.md
    │   ├── 06_installation_parametrage/
    │   │   ├── archive.php
    │   │   ├── DOSSIER_DEPLOIEMENT_CCK_RNU.md
    │   │   ├── phpunit.xml
    │   │   ├── pv-module.php
    │   │   ├── RAPPORT_RECETTE_P9.md
    │   │   ├── uma.php
    │   │   ├── workflow.php
    │   │   └── .env.example
    │   └── 07_licence_identite/
    │       ├── IDENTITE_DEPOT.md
    │       └── LICENSE
    ├── public/
    │   ├── index.php
    │   ├── robots.txt
    │   ├── .htaccess
    │   ├── fonts/
    │   │   └── filament/
    │   │       └── filament/
    │   │           └── inter/
    │   │               ├── index.css
    │   │               ├── inter-cyrillic-ext-wght-normal-SP7Z6XGK.woff2
    │   │               ├── inter-cyrillic-wght-normal-DR6K5BQD.woff2
    │   │               ├── inter-greek-ext-wght-normal-A2J6H3EX.woff2
    │   │               ├── inter-greek-wght-normal-ZABHMKQG.woff2
    │   │               └── inter-vietnamese-wght-normal-VWEHJHBA.woff2
    │   └── js/
    │       └── filament/
    │           ├── actions/
    │           │   └── actions.js
    │           ├── filament/
    │           │   └── app.js
    │           ├── forms/
    │           │   └── components/
    │           │       ├── checkbox-list.js
    │           │       ├── color-picker.js
    │           │       ├── key-value.js
    │           │       ├── slider.js
    │           │       ├── tags-input.js
    │           │       └── textarea.js
    │           ├── notifications/
    │           │   └── notifications.js
    │           ├── schemas/
    │           │   ├── schemas.js
    │           │   └── components/
    │           │       ├── actions.js
    │           │       ├── tabs.js
    │           │       └── wizard.js
    │           └── tables/
    │               ├── tables.js
    │               └── components/
    │                   └── columns/
    │                       ├── checkbox.js
    │                       ├── text-input.js
    │                       └── toggle.js
    ├── resources/
    │   ├── css/
    │   │   └── app.css
    │   ├── js/
    │   │   └── app.js
    │   └── views/
    │       ├── emails/
    │       │   ├── reunion/
    │       │   │   └── convocation.blade.php
    │       │   └── workflow/
    │       │       └── state-changed.blade.php
    │       └── filament/
    │           ├── pages/
    │           │   └── imports/
    │           │       └── importer.blade.php
    │           └── resources/
    │               ├── dossiers/
    │               │   └── workflow-historique.blade.php
    │               └── reunions/
    │                   └── pages/
    │                       ├── manage-reunion-decisions.blade.php
    │                       ├── manage-reunion-presences.blade.php
    │                       └── reunion-corbeille.blade.php
    ├── routes/
    │   ├── console.php
    │   └── web.php
    ├── tests/
    │   ├── Pest.php
    │   ├── TestCase.php
    │   ├── Feature/
    │   │   ├── AdminPanelAccessTest.php
    │   │   ├── CdcDocumentTypesTest.php
    │   │   ├── ExampleTest.php
    │   │   ├── ImportMoulinetTest.php
    │   │   ├── ImportPagesTest.php
    │   │   ├── P5RbAcContratsTest.php
    │   │   ├── P7ArchivageAuditTest.php
    │   │   ├── P8WorkflowEngineTest.php
    │   │   ├── P8WorkflowReinscriptionTest.php
    │   │   ├── P9ChecklistCdcTest.php
    │   │   ├── PvModuleIntegrationTest.php
    │   │   ├── ReunionsModuleTest.php
    │   │   └── SocleCriteriaTest.php
    │   └── Unit/
    │       └── ExampleTest.php
    └── .claude/
        └── skills/
            ├── deploying-to-cloud/
            │   ├── SKILL.md
            │   └── reference/
            │       └── checklists.md
            ├── infer-conventions/
            │   ├── SKILL.md
            │   └── references/
            │       └── checklist.md
            ├── laravel-best-practices/
            │   ├── SKILL.md
            │   └── rules/
            │       ├── advanced-queries.md
            │       ├── architecture.md
            │       ├── blade-views.md
            │       ├── caching.md
            │       ├── collections.md
            │       ├── config.md
            │       ├── db-performance.md
            │       ├── eloquent.md
            │       ├── error-handling.md
            │       ├── events-notifications.md
            │       ├── http-client.md
            │       ├── mail.md
            │       ├── migrations.md
            │       ├── queue-jobs.md
            │       ├── routing.md
            │       ├── scheduling.md
            │       ├── security.md
            │       ├── style.md
            │       └── validation.md
            ├── tailwindcss-development/
            │   └── SKILL.md
            └── testing-best-practices/
                ├── SKILL.md
                └── rules/
                    ├── assertions.md
                    ├── endpoint-tests.md
                    ├── finding-features.md
                    ├── isolation.md
                    ├── naming.md
                    ├── performance.md
                    ├── review.md
                    ├── security.md
                    └── test-data.md


Files Content:

(Files content cropped to 300k characters, download full ingest to see more)
================================================
FILE: README.md
================================================
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Plateforme UMA — socle (P1)

Application Laravel 13 + Filament 5 pour la plateforme de gestion de la formation doctorale de l'UMA (Lot 2 du CDC).

## Décisions prises (P1 Étape 0)

- **Dossier** : `C:\Users\salsa\Desktop\SA\pj\actuel\stageNashd\uma` (frère de `voyager`, dossier `voyager` non modifié).
- **Un seul panneau** : panneau unique Filament `/admin` (conformité CDC §5 « un seul espace back-office »). L'espace usager `/portal` prévu n'est pas dupliqué.
- **RBAC natif** : enum `App\Enums\UserRole` + colonne `users.role` + `canAccessPanel()` fail-closed + Gates/Policies par domaine. Aucun package RBAC externe.
- **Rôles initiaux** : `admin`, `gestionnaire_ecole`, `president_commission`, `membre_commission`, `directeur_these`, `doctorant`, `agent_administration`.
- **Risques** : `config/uma.php` ne déclare que les flags des 3 risques transversaux (signature, templates PV, verrou anti-dérive).

## Lancement

```bash
php -S localhost:2000 -t public/
php artisan test
```

## Étape 1 (P3) — Intégration de `pv-module`

Module de documents consommé **tel quel** via Composer (aucun copier-coller).

- **Dépôt** : `salsabil-ennaiem/pv-module` `^1.0` depuis **Packagist**, verrouillé en `v1.0.2` (dist GitHub). Publiable : `config/pv-module.php`, `lang/vendor/pv-module`.
- **Routes** : `/admin/documents/*` (noms gardés `pv-module.*`), middleware `['web', 'auth']`, `login` nommé → `/admin/login`.
- **`config/pv-module.php`** adapté : `user_model` = `App\Models\User`, `signature_mechanism` relié à `config('uma.compliance.signature_driver')`.
- **Évidences (demo locale)** : PV brouillon → en attente → validé ; PDF FR/AR dans `storage/app/evidence/` ; notifications base (cloche) + mails (log `storage/logs/laravel.log`).
- **Test d'intégration** : `tests/Feature/PvModuleIntegrationTest.php` (workflow complet + PDF FR/AR + notifications).

### Bugs package signalés (sans correction locale)

1. **Tags obsolètes** : `v1` et `v1.0.0` pointent sur l'ancien commit `3219e36` (avant R2/R3). **Résolu** par l'éditeur : les tags `v1.0.1` (`54a520b`, R2+R3) et `v1.0.2` (`dcd7af8`, fix routage) sont publiés et poussés ; la dépendance locale a été remplacée par Packagist.
2. **Tags de publication du prompt** : `pv-module-config` / `pv-module-lang` ne correspondent pas aux tags réels du package (`pv-config` / `pv-lang`).
3. **Noms de routes codés en dur** : les notifications appelaient `route('pv-module.show')` ; **résolu** par l'éditeur (`config('pv-module.routes.name_prefix', ...)`), livré dans `v1.0.2`. Le préfixe d'URL reste `admin/documents` et le préfixe de noms reste `pv-module.`.

## Étape 2 (P4) — Types CDC + templates FR/AR + écran « Modèles de décision »

Généralisation du moteur en « document engine du CDC » : les types sont déclarés **par configuration**, jamais en dur dans un `if/else`.

- **`config/pv-module.php` → `types`** : `pv`, `attestation`, `decision`, `arrete`, `invitation`, `diplome`, `fiche_acces` (7 types CDC).
- **Seeder `UmaDocumentTemplatesSeeder`** : 7 templates par défaut (sections header/contenu/signature, marges, orientation — `diplome` paysage, `invitation` marges 30). RTL AR confiné aux templates (`default_locale` / `rtl_locales`).
- **Écran admin « Modèles de décision »** (`/admin/decision-templates`) : CRUD Filament 5 sur `DecisionTemplate` — `label`, `description`, `email_subject`, `email_body` (variables `{prenom} {nom} {label}`), `commission_id`, `is_active` (RBAC natif `UserRole`).
- **Évidences** : `storage/app/private/evidence/p4-*.pdf` — attestation AR longue RTL (`dir="rtl"`, `lang="ar"`, PDF 44 Ko), invitation officielle (32 Ko), décision (32 Ko).
- **Tests** : `tests/Feature/CdcDocumentTypesTest.php` (13 tests — types en config, template par type, création + PDF par type, RTL AR long, écran Filament admin) ; suite complète : **28 tests / 130 assertions**.
- **Adaptation Filament 5** : `form(Schema $schema)` dans `Filament\Schemas\Schema` (plus `Filament\Forms\Form`) ; actions de table ≡ `Filament\Actions\EditAction|DeleteAction|BulkActionGroup|DeleteBulkAction` (le package `filament/tables` ne les définit plus) ; `$navigationIcon` et `$navigationGroup` typés `BackedEnum|null` / `UnitEnum|null`.
- **Capacité manquante détectée** : batch d'impression + logos/en-têtes paramétrables par structure → **sous-prompt package** rédigé (`Sous-prompt P4 — PdfService batch & logos`), à exécuter après validation de P4.

## Étape 3 (P5) — RBAC métier branchée sur les contrats du package

Cible : `🟦 NOUVELLE APPLICATION`. Les contrats du package sont **implémentés ici**, jamais modifiés dans le package.

- **3 implémentations de contrats** dans `app/PvRules/` (liées via `config/pv-module.php`) :
  - `PvRules` (`CanManagePv`) : matrice rôles UMA — création `admin|gestionnaire_ecole|president_commission|agent_administration` ; validation/signature par les présents ; gestion restreinte au **périmètre commission** (`source_type=commission`) ; fail-closed + log discret.
  - `ApprovalRules` : seuils paramétrables `config('uma.approval')` — `unanimous` (défaut) ou `quorum %` ; la validation du créateur approuve (règle Voyager).
  - `ParticipantResolver` : signataires résolus depuis les **entités métier** (membres + président d'une `Commission`, jury) ; normalisation héritée du package.
- **Signature R2** (`app/PvSignatures/`) : `SignatureResolver` unique lit `uma.compliance.signature_driver` ; `SimpleImageSignatureStrategy` (délègue au package) et `QualifiedSignatureStrategy` (stub journalise « certificat requis », basculable par `SIGNATURE_ALLOW_WITHOUT_CERTIFICATE`). Retour toujours mécanisme + horodatage. `SIGNATURE_DRIVER=qualified` change le comportement **sans toucher une ligne de logique** (testé).
- **Hiérarchie métier** (app seulement) : `Universite → EcoleDoctorale → Etablissement → Commission` (+ pivot `commission_user`), exposée dans l'admin (groupe « Institution », ressources Filament 5 `Universites/EcoleDoctorales/Etablissements/Commissions`).
- **Policies** qui délèguent au contrat : `PvPolicy` (tout délègue à `CanManagePv`), `CommissionPolicy`, `InstitutionPolicy`, `DecisionTemplatePolicy` (anti-IDOR : président limité à sa commission) + `Gate::before` admin.
- **Tests** : `tests/Feature/P5RbAcContratsTest.php` (10 tests — liens contrats, RBAC fail-closed par rôle, 403 sans garde, bascule driver `qualified`, trace mécanisme+horodatage, seuils quorum/unanime, resolution commission, hiérarchie, anti-IDOR commission/décision/PV). Suite complète : **38 tests / 178 assertions**. `composer.json` toujours sans `spatie/laravel-permission`.

## Étape 4 (P6) — Module Réunions de commission en Filament

Cible : **🟦 NOUVELLE APPLICATION** — module réécrit en Filament (référence Voyager en lecture seule), branché sur le package pour le PV signé.

- **Modèles** (`app/Models/`) : `Reunion` (statuts `brouillon → planifiee → en_cours → terminee/annulee`, type `presentiel/visio/hybride`, ODJ, soft-deletes, `estPassee()`, `pvs()` = source `reunion/{id}`), `Invitation`, `Presence` (présent/absent/excusé), `Dossier` (+pivot `reunion_dossier` positionné), `Decision` (snapshot du modèle paramétrable), `OdjTemplate`, `AuditLog` (append-only : `action`, avant/après, IP). Enums `ReunionStatut` (matrice de transitions + `label()`), `ReunionType`, `InvitationStatut`, `PresenceStatut`, `DossierStatut`.
- **Migration** `2026_09_16_000001_create_reunions_module_tables.php` (+ 6 factories).
- **Services** : `ReunionService` (création en transaction + **auto-ajout des membres** hors président, `transition()` avec garde d'état + audit + notifications, `genererPv()` → `PvService::store(..., 'reunion', id)` puis `send` aux présents), `DecisionService` (record snapshot + dossier → `traite`, `exportCsv`, `pvContenu`).
- **Ressources Filament** (générées CLI `--generate`, personnalisées ensuite) : `Reunions/ReunionResource` + `Schemas/ReunionForm` + `Tables/ReunionsTable` (badges statut, filtres statut/commission, `TrashedFilter` corbeille), `Dossiers/*`, `Decisions/*` (colonne décideur). Pages custom : `ManageReunionPresences`, `ManageReunionDecisions` (RBAC en `mount`), `ReunionCorbeille` (restore/force delete, admin), pages statut + « Générer le PV » dans `EditReunion`, export décisions CSV.
- **Policies RBAC** : admin tout ; président de SA commission ; gestionnaire école ; agent administratif (création, présence, décisions) ; membre vue seulement ; doctorant vue de sa propre décision ; fail-closed ; `genererPv` = réunion passée + contrat package `CanManagePv`.
- **Notifications** : `ReunionPlanifiee`/`ReunionTerminee` (base) + `Mail/ReunionConvocation` + vue markdown `emails/reunion/convocation.blade.php` (canal mail unique).
- **Tests** : `tests/Feature/ReunionsModuleTest.php` (9 tests — parcours complet création→convocation→présences→décisions→terminer→PV, transitions invalides, RBAC par rôle, corbeille, mallette décision, anti-IDOR commission, smoke Filament 200/403). Suite complète : **87 tests / 429 assertions** (vert le 2026-09-15 ; lancer `composer test`).

## Conformité & décisions UMA (P10 — clôture)

État au **2026-09-15** : UMA injoignable — décisions **A1 levée par défaut sûr**, P9 clôturé.
Registre consolidé app + package : `../voyager/DECISIONS_UMA.md` · dossier de réunion :
`../voyager/docs/DOSSIER_RE_SOLLICITATION_UMA.md`.
Livrables CDC §5 (manifeste, rapport de recette P9, dossier de déploiement CCK/RNU,
identité de livraison A1, archive ZIP) : **`livrables_cdc5/`**.

| Risque | Défaut sûr appliqué | Décision UMA à acter |
|---|---|---|
| **R1 — PI** | namespace `SalsabilEnnaiem` + cession par contrat — **levé** (identité en `livrables_cdc5/07`) | nom/licence/dépôt de livraison (si reconsidération) |
| **R2 — Signature** | `simple_image` + trace (mécanisme + horodatage) | niveau de conformité (simple vs qualifiée eIDAS) |
| **R3 — RTL** | templates FR/AR confinés aux vues (`dir="rtl"`) | conformité des modèles officiels UMA |
| **Workflow** | définitions/seuils paramétrés en base | validation définitions A/B/C + seuils JORT |

Mise à jour avec date + responsable à la clôture (chaque ligne : `validée` / `amendée` / `à corriger`).

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



================================================
FILE: AGENTS.md
================================================
<laravel-boost-guidelines>
# Laravel Application

This repository contains a Laravel application. Complete the following setup before working on the user's request.

## Prerequisites

Verify that PHP and Composer are available:

```sh
php -v
composer -V
```

If either command is unavailable, detect the user's operating system and install the prerequisites with the appropriate command:

macOS:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.5)"
```

Windows PowerShell:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
```

Linux:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.5)"
```

After installation, ask the user to restart their terminal. If the agent needs the restarted shell to continue, ask the user to reopen their terminal and rerun their original prompt.

## Agent Setup

Install Laravel Boost from the application root before making application changes:

```sh
composer require laravel/boost --dev
php artisan boost:install
```

Boost replaces these bootstrap instructions with guidelines tailored to the application. After installation, read `AGENTS.md` again and continue with the user's original request using the generated guidelines.
</laravel-boost-guidelines>

## Règles d'exécution — Plateforme UMA

- **Nom de base de données figé** : `uma_plateforme` (orthographe fixe, aucune variante type
  `uma_plateforma`). Sans réponse contraire du CCK, provisionner ce nom.
- **Interdiction de nom de table en dur en SQL brut** : `whereRaw`, `DB::raw`, `DB::statement`,
  `->selectRaw` avec un nom de table sont proscrits. Passer par les modèles Eloquent, les builders,
  ou `DB::getTablePrefix()`. Toute exception doit être signalée dans `docs/DECISIONS_UMA.md`
  (ADR-0001) avec un commentaire dans le code.
- **Décisions** : le registre canonique est `docs/DECISIONS_UMA.md` (ADR numérotés).
  `voyager/DECISIONS_UMA.md` est une copie lecture seule.
- **Discipline git** : aucun commit sans validation explicite de l'utilisateur ; les commits sont
  groupés par phase validée.



================================================
FILE: artisan
================================================
#!/usr/bin/env php
<?php

use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\ArgvInput;

define('LARAVEL_START', microtime(true));

// Register the Composer autoloader...
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the command...
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$status = $app->handleCommand(new ArgvInput);

exit($status);



================================================
FILE: ASSUMPTIONS.md
================================================
# Registre des hypothèses — Plateforme UMA

Format : `ID · hypothèse · mécanisme de bascule · décideur · date`
Mis à jour à la fin de P8 (sept. 2026).

---

## Infrastructure / base de données

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H01 | SQLite est utilisé comme SGBD **en développement et en tests** ; il est **suffisant** pour valider la logique applicative (absence de contraintes FK dichotomiques,锁粒alité fine inutile en recette unitaire). | Changer `DB_*` dans `.env` → MySQL/PostgreSQL + appliquer `database/migrations` qui sont **déjà compatibles cross-SGBD** (pas de raw SQL dialectal). | Editeur / P9 | init |
| H02 | Le déploiement cible est l'infrastructure **CCK** (Centre de Calcul Khawarizmi) ; le choix de SQLite est donc temporaire. | Remplacer SQLite par **MySQL/MariaDB 8+** (stack CCK standard). | P9 recette | P1 |
| H03 | Le stockage des documents archivés se fait sur le **disque `public`** (`storage/app/public`) ; la visibilité publique est acceptable pour les PDFs d'attestation. | Déplacer vers un disque **privé** (`storage/app/private`) + policy de téléchargement authentifié, ou vers **S3/OBS** si CCK le propose. | P9 / éditeur | P7 |
| H04 | Les files de jobs/queues ne sont pas utilisées en production ; les Mailables/Notifications sont **envoyés de façon synchrone**. | Activer un **driver de queue** (database ou Redis) dans `.env` + config `queue.php` ; les classes `ShouldQueue` (`WorkflowTransitioned`, `ReunionPlanifiee`) sont déjà prêtes. | P9 | P8 |

---

## Sécurité / authentification

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H05 | `Gate::before` accorde **un accès super-admin** à l'admin ; c'est un raccourci Django/Filament **qu'il ne faut jamais compter dans la logique métier**. | Retirer `Gate::before` et gérer explicitement les permissions par policy (fail-closed partout). | P9 / admin | P5 |
| H06 | Le module de signature qualifiée **n'est pas encore branché** en production ; le driver `simple-image` est utilisé en recette (preuve P6). | Bascule en prod via `SIGNATURE_DRIVER=qualified` + config `pv-module.php` ; le `QualifiedSignatureStrategy` exige un **certificat PKCS#12** (à provisionner). | Éditeur / CCK | P5 |
| H07 | L'authentification se fait **uniquement par Filament login** (email + password) ; pas de SSO, pas de 2FA. | Intégrer **Laravel Fortify** (2FA) ou **SSO CCK** (LDAP/SAML) via un provider externe. | P9 / admin | P1 |
| H08 | L'utilisateur admin par défaut (`admin@uma.dz`) est créé par `make:filament-user` et n'est **jamais supprimé** en dev. | Audit de sécurité en prod : supprimer le compte par défaut, forcer la création via une procédure auditée. | P9 | P1 |

---

## Domaine métier

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H09 | La hiérarchie institutionnelle est **manuelle** (créée par l'admin via Filament) ; pas d'import automatique depuis inscription.tn. | Brancher une **importation CSV/XLSX** des structures universitaires/écoles/établissements (tâche P9). | Éditeur / P9 | P3 |
| H10 | Les **niveaux d'inscription** (1ʳᵉ→5ᵉ année) sont codés dans `annee_inscription` en texte libre ; pas de validation par un enum exhaustif. | Si validation nécessaire : créer un enum `NiveauInscription` et migrer `annee_inscription` vers des valeurs standardisées. | Éditeur | P8 |
| H11 | Un dossier de doctorat **appartient à un seul doctorant** (relation `BelongsTo doctorant`) ; pas de co-tutelle en base. | Ajouter une relation pivot `dossier_directeur` (ManyToMany) pour la cotutelle. | Éditeur / P9 | P8 |
| H12 | Une commission **gère une seule discipline** ; le même doctorant ne peut pas avoir de dossiers dans deux commissions différentes. | Si multi-discipline : transformer la relation en `BelongsToMany` avec pivot. | Éditeur | P1 |
| H13 | Les réclamations/tickets sont **uniquement texte** (type + contenu) ; pas de pièces jointes, pas d'arborescence de threads. | Ajouter une relation `documents()` polymorphique sur `Reclamation` pour les pièces jointes. | Éditeur | P8 |
| H14 | L'**ordre des transitions** est géré par la colonne `sort` (entier) ; pas de validation d'acyclicité du graphe de workflow. | Ajouter une vérification en base (trigger ou seeder guard) pour détecter les **cycles infinis** dans les workflows. | P8 / éditeur | P8 |

---

## Workflow (P8)

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H15 | Les **gardes `contract`** (comme `CanManagePv::canCreate`) instancient une nouvelle instance via `app($contract)` ; le contrat est résolu par le container Laravel. | Si le contrat a un état partagé (singleton) : le hooker dans le container via `AppServiceProvider::register()`. | P8 | P8 |
| H16 | Les notifications de transition sont **role-based** (pas user-based) ; c'est-à-dire que le moteur notifie **tous les users d'un rôle donné** pour une transition donnée. | Si notification ciblée : passer la spécification `notifications` de `[{ "role": "doctorant", ... }]` à `[{ "user_id": 42, ... }]` + adapter `resolveRecipients`. | Éditeur | P8 |
| H17 | L'**actions de bord `reclamation.actualiser`** met à jour le `statut` de la réclamation **après** la transition ; c'est un contract implicite entre le moteur et le sujet métier. | Formaliser : créer un contrat `HasWorkflowStatus::syncFromInstance(WorkflowInstance $instance)` sur le sujet. | P9 | P8 |

---

## Module pv-module (package externe)

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H18 | Le package `salsabil-ennaiem/pv-module` est **consommé tel quel** depuis Packagist ; l'application **ne le modifie jamais** (règle d'or du CDC). | Si besoin d'un fork : `composer config repositories.git https://github.com/...` + `composer require <fork>:dev-main`. | Éditeur | P3 |
| H19 | Le package **contrôle ses propres routes** (`admin/documents`) ; l'application ne peut pas les préfixer autrement que par `pv-module.php.routes.name_prefix`. | Changer le préfixe dans `config/pv-module.php` → `'name_prefix' => 'pv-module.'` (déjà fait, P3). | Éditeur | P3 |
| H20 | Les types de documents du CDC (`pv`, `attestation`, `decision`, `arrete`, `invitation`, `diplome`, `fiche_acces`) sont **fixés dans `config/archive.php`** et ne changent qu'en P4. | Si le CDC évolue : ajouter un type dans `config/archive.php` + migrer `rapport_etats.pv_type`. | Éditeur | P4 |

---

## Architecture / monolithe

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H21 | L'application est un **monolithe Laravel** (un seul artefact déployable) ; les « modules » (Réunions, Archivage, Workflows) sont des dossiers cohérents du monolithe, **pas des packages**. | Extraire chaque module en **package Composer interne** (voir §« Transformation vers modules interopérables »). | Éditeur / P10 | P1 |
| H22 | La migration vers un **module interopérable** est **différée après la recette** (P9) ; elle n'est pas un pré-requis au déploiement. | Planifier la transformation en P10/P11 (post-déploiement) comme projet d'amélioration continue. | Éditeur | P1 |
| H23 | Filament 5 est la **seule interface d'administration** ; il n'y a pas d'API REST/GraphQL pour des clients externes. | Si API nécessaire : créer des **Routes API** (`routes/api.php`) + resource transformers (Fractal/API Resources). | Éditeur | P1 |
| H24 | Les configurations (`pv-module.php`, `archive.php`, `uma.php`, `workflow.php`) sont versionnées **et** surchargées par `.env` si nécessaire. | Documenter chaque variable d'environment critique dans `.env.example`. | P9 | P1 |

---

## Déploiement / recette

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H25 | Le déploiement est prévu sur **CCK** (Centre de Calcul Khawarizmi) avec les normes **RNU** (Réseau National Universitaire). | Si hébergement externe : adapter la config mail/queue/cache/filesystems. | P9 | P1 |
| H26 | Les tests sont exécutés en SQLite **en CI/CD** (rapide, sans dépendance SGBD) ; la validation MySQL se fait **manuellement en recette**. | Ajouter un **service MySQL** en CI (Docker) pour des tests de compatibilité. | P9 | P7 |
| H27 | Aucun **IDOR** (Insecure Direct Object Reference) n'a été détecté en P7/P8 ; les policies Filament et les contrats RBAC bloquent les accès non autorisés. | Exécuter un **audit IDOR** complet en P9 (checklist dans `P9_recette_deploiement.md`). | P9 | P7 |
| H28 | Le fichier `.env` contient les secrets (APP_KEY, DB_PASSWORD, MAIL_PASSWORD) ; il n'est **jamais versionné**. | Si Cloud/CI : utiliser les **secrets du CI/CD** (GitHub Actions secrets, Laravel Cloud secrets). | P9 / déploy | P1 |

---

## UI / UX

| ID | Hypothèse | Mécanisme de bascule | Décideur | Date |
|----|-----------|----------------------|----------|------|
| H29 | L'interface est **exclusivement en français** ; aucune gestion multilingue n'est prévue. | Si bilingue : activer `lang/` + config `app.locale` = `fr`, `app.fallback_locale` = `ar` + vues Blade avec `__('key')`. | Éditeur | P1 |
| H30 | Les **PDFs sont générés en mode portrait A4** par défaut ; le `RapportEtat.orientation` est surchargable par état. | Si besoins spécifiques : ajouter des formats papier (A3, Letter) dans `format_papier` du `RapportEtat`. | Éditeur | P7 |
| H31 | Les mises en page des emails (convocation, notification workflow) utilisent **Markdown** (`resources/views/emails/`) ; pas de template HTML riche. | Si besoin : créer des templates Blade HTML + `$html` dans `Content` du Mailable. | Éditeur | P6 |



================================================
FILE: boost.json
================================================
{
    "agents": [
        "claude_code"
    ],
    "cloud": true,
    "guidelines": true,
    "mcp": true,
    "nightwatch": false,
    "sail": false,
    "skills": [
        "infer-conventions",
        "deploying-to-cloud",
        "laravel-best-practices",
        "testing-best-practices",
        "tailwindcss-development"
    ]
}



================================================
FILE: CLAUDE.md
================================================
<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application running on PHP 8.5. You are an expert with the Laravel ecosystem. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Use `search-docs` before changes that depend on Laravel ecosystem APIs, behavior, configuration, or version-specific syntax. Skip it for copy-only edits and other changes where package documentation is irrelevant. Reuse sufficient results already in context instead of searching again.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists (settled decisions, non-obvious traps, standing constraints). Framework and package guidelines that only apply to specific paths (testing, frontend, components) also live there, under `.ai/rules/boost` — this is not just recorded decisions, it is load-bearing guidance you have not seen inline. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.
- Record a rule with `record-rule` only when the user explicitly asks for one. Instructions for the work at hand are not rules, no matter how emphatic: "remove this typo", "use X here" are work to do, not rules to record. Never record a rule on your own initiative, as a byproduct of a change, or to summarize what you just did. When the user does ask, pass a `glob` (e.g. `app/Http/Controllers/**`), a short `title`, and a few-line `note`. Use `record-rule` rather than your native memory or notes tool, because native memory is personal and session-scoped, while only `.ai/rules` is shared with the team and persists in the repo.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.
- Activate the `deploying-to-cloud` skill whenever deploying to Laravel Cloud, configuring Cloud environments or resources, using the Cloud CLI, or troubleshooting Cloud deployments.

=== herd rules ===

# Laravel Herd

- The application is served by Laravel Herd at `https?://[kebab-case-project-dir].test`. Use the `get-absolute-url` tool to generate valid URLs. Never run commands to serve the site. It is always available.
- Use the `herd` CLI to manage services, PHP versions, and sites (e.g. `herd sites`, `herd services:start <service>`, `herd php:list`). Run `herd list` to discover all available commands.

=== tests rules ===

# Test Enforcement

- Add or update tests for behavior and logic changes when a test provides meaningful regression coverage.
- Pure copy, styling, and layout-only changes do not require new or updated tests.
- When test coverage applies, run the affected tests and ensure they pass.
- Test the changed behavior and its important failure modes, but do not add tests beyond them.
- Read the `testing-best-practices` skill before writing tests.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

# Pest

- This project uses Pest. Create tests with `php artisan make:test --pest {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.
- Do not delete tests or test files without approval. They are part of the application.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/pest` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.
- After the feature tests pass, ask the user to run the complete suite with `php artisan test --compact`.

</laravel-boost-guidelines>



================================================
FILE: composer.json
================================================
{
    "$schema": "https://getcomposer.org/schema.json",
    "name": "laravel/laravel",
    "type": "project",
    "description": "The skeleton application for the Laravel framework.",
    "keywords": [
        "laravel",
        "framework"
    ],
    "license": "MIT",
    "require": {
        "php": "^8.3",
        "filament/filament": "^5.0",
        "laravel/framework": "^13.17",
        "laravel/tinker": "^3.0",
        "salsabil-ennaiem/pv-module": "^1.0"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/boost": "*",
        "laravel/pail": "^1.2.5",
        "laravel/pao": "^1.0.6",
        "laravel/pint": "^1.27",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.6",
        "pestphp/pest": "^5.1",
        "pestphp/pest-plugin-laravel": "^5.0"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "scripts": {
        "setup": [
            "composer install",
            "@php -r \"file_exists('.env') || copy('.env.example', '.env');\"",
            "@php artisan key:generate",
            "@php artisan migrate --force",
            "npm install --ignore-scripts",
            "npm run build"
        ],
        "dev": [
            "Composer\\Config::disableProcessTimeout",
            "npx concurrently -c \"#93c5fd,#c4b5fd,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1\" \"npm run dev\" --names='server,queue,vite'"
        ],
        "test": [
            "@php artisan config:clear --ansi @no_additional_args",
            "@php artisan test"
        ],
        "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover --ansi"
        ],
        "post-update-cmd": [
            "@php artisan vendor:publish --tag=laravel-assets --ansi --force",
            "@php artisan boost:update --ansi"
        ],
        "post-root-package-install": [
            "@php -r \"file_exists('.env') || copy('.env.example', '.env');\""
        ],
        "post-create-project-cmd": [
            "@php artisan key:generate --ansi",
            "@php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\"",
            "@php artisan migrate --graceful --ansi"
        ],
        "pre-package-uninstall": [
            "Illuminate\\Foundation\\ComposerScripts::prePackageUninstall"
        ]
    },
    "extra": {
        "laravel": {
            "dont-discover": []
        }
    },
    "config": {
        "optimize-autoloader": true,
        "preferred-install": "dist",
        "sort-packages": true,
        "allow-plugins": {
            "pestphp/pest-plugin": true,
            "php-http/discovery": true
        }
    },
    "minimum-stability": "stable",
    "prefer-stable": true
}



================================================
FILE: NOUVELLE_APP_GUIDE_INTEGRATION.md
================================================
# Guide d'intégration — Nouvelle application « Gestion Documentaire + Workflow + Réunions »

> **Objectif** : bâtir une nouvelle application centrée sur la **gestion documentaire** (workflow, signature, types de documents, archivage, templates) et les **réunions**, en **réutilisant au maximum le projet actuel** (`voyager`).
>
> **Stack cible** : Laravel 13 · Filament 5 · PHP 8.2+ · **zéro dépendance payante / minimale**.
>
> **Principe** : tout ce qui est déjà *générique* et déjà écrit → on le réutilise (package `salsabil-ennaiem/pv-module` + code « meetings » extrait comme référence). Tout ce qui est *spécifique au nouveau métier* → on l'écrit dans la nouvelle app (Filament + code maison).

---

## 1. Inventaire : ce que le projet actuel nous donne déjà

### 1.1 Le joyau réutilisable : `packages/salsabil-ennaiem/pv-module`

C'est un **moteur documentaire autonome**, déjà extrait hors de Voyager, compatible Laravel 10→13 :

| Capacité | Détail |
|---|---|
| **Workflow** | `brouillon → en_attente → valide` (+ rejet → retour brouillon), envoi aux participants, délais |
| **Versions** | chaque modification enregistre une version consultable + statut |
| **Signatures** | dessin (canvas) OU upload d'image, tailles/MIME configurables |
| **Notifications** | canal `database` (cloche) + e-mail |
| **PDF** | mPDF, template Blade personnalisable, sections (header/middle/signature) |
| **Templates** | modèles de documents réutilisables (`PvTemplate`), seeder par défaut, éditeur de rubriques (drag & drop, alignement) |
| **i18n** | fr / ar / en (JSON) — indispensable pour les attestations AR |
| **Multi-types** | `config('pv-module.types')` : `pv`, `attestation`, `arrete`, `decision`, `invitation`, `diplome`… |
| **Extensibilité** | 3 contrats remplaçables sans toucher au code : `CanManagePv` (RBAC), `ApprovalRules` (seuils), `ParticipantResolver` (qui signe) |

> **Il couvre déjà ~40 % du périmètre cible** (documents + signature + templates + versions + notifications + PDF FR/AR).

### 1.2 Le code « meetings » présent dans Voyager (à réutiliser comme **référence**)

Non extrait en package (domaine trop spécifique), mais **modèle parfait pour réécrire le module réunions en Filament** :

| Briques Voyager | Fichier | Ce qu'on en tire pour la nouvelle app |
|---|---|---|
| Modèle Réunion | `app/Models/Reunion.php` | statuts (`brouillon/planifiee/en_cours/terminee/annulee`), types (présentiel/visio/hybride), ODJ, `presedent_Reunion`, `can_generate_pv` |
| Modèle Invitation | `app/Models/Invitation.php` | réponses accepter/refuser/excuser + justificatif, présence présent/absent/excusé |
| Modèle PV | `app/Models/PV.php` + `PVValidation.php` | workflow de validation, signatures par section |
| Templates PDF | `app/Models/PdfTemplate.php` | templates par utilisateur + template global + reset |
| Services | `app/Services/PdfService.php`, `SignatureService.php`, `NotificationService.php`, `InvitationService.php` | logique de génération PDF, signature, notifications, présence |
| Contrôleurs | `PVController`, `ReunionController`, `SignatureController`, `InvitationEtPresenceController` | contraintes métier (date_fin passé pour PV, deadline signature, etc.) |
| Policies | `ReunionPolicy`, `PVPolicy`… | matrice de rôles admin / chef / membre |
| Docs | `docs/PV_MODULE_INTEGRATION_GUIDE.md`, `LOT2_FILAMENT_PLAN.md`, `WORKFLOW_ET_COMPTES.md` | procédure d'intégration + plan Filament déjà rédigés |

### 1.3 Ce qui est DÉJÀ fait (artefacts de conception, gains de temps importants)

- ✅ Le **moteur de workflow documentaire** (signature + versions + templates) → package
- ✅ Les **3 diagrammes d'états** (réunion/PV dans `WORKFLOW_ET_COMPTES.md`, workflows CDC dans `Cdc.md`)
- ✅ La **matrice acteur × action** (tableaux du `WORKFLOW_ET_COMPTES.md` + §5 du résumé CDC)
- ✅ La **procédure d'intégration package** dans une app neuve (`docs/PV_MODULE_INTEGRATION_GUIDE.md` §2)
- ✅ Le **plan Filament complet** du back-office (`LOT2_FILAMENT_PLAN.md`)

---

## 2. Les 6 décisions stratégiques AVANT de créer la nouvelle app

1. **Le package est la colonne vertébrale** → on le **public** une fois (Packagist ou Git) et on le consomme par Composer dans la nouvelle app. Jamais de copier-coller du code package dans la nouvelle app.
2. **Module réunions = code app maison** (Filament), inspiré du code Voyager, **pas un second package** — domaine trop spécifique pour être partagé.
3. **Capacités PDF étendues de Voyager** (batch d'impression, en-têtes/logos par structure, formats variés) → **absorbées dans `pv-module` (v1.1)**, pas dans un package séparé.
4. **UNE seule console back-office** (CDC §5 : « un seul espace back-office pour le CMS et la plateforme ») → **1 panneau Filament** avec groupes de navigation par rôle, **pas 2 panneaux séparés**. L'isolation se fait par `canAccessPanel()` + `->visible()`/Policies, pas par un changement d'URL.
5. **Propriété intellectuelle (CDC §5)** : tous les codes/documents sont **cédés à l'UMA** → le package doit être livré sous une identité/trim d'auteur cohérente avec la prestation (le repo actuel est `salsabil-ennaiem/pv-module` sous licence MIT). Trancher **avant livraison** : attribution finale, licence, dépôt du code source + docs de conception (un livrable CDC).
6. **Règles métier en base, jamais en dur** : le CDC répète « la spécification détaillée du workflow sera faite durant la phase d'analyse » et « le workflow doit s'aligner avec les textes de lois » → moteur de workflow + règles (seuils, délais, conditions JORT) **paramétrables** dès le départ.

---

## 3. Étapes d'intégration dans la nouvelle app

### Étape 0 — Créer le socle (Laravel 13 + Filament 5) — ½ journée

```bash
laravel new gestion-docs-reunions
cd gestion-docs-reunions
composer require filament/filament:"^5.0" -W
php artisan filament:install --panels
php artisan migrate
```

- **1 seul panneau** (conformité CDC §5 « même console d'administration ») : `filament:install --panels` puis n'en garder qu'un (ex. `/admin`), avec **groupes de navigation** par rôle (Gestion, Réunions, Documents, Archives, Paramétrage). L'isolation école/commission = `canAccessPanel()` + `->visible()`.
- Activer l'auth Filament (login/reset/profil) — **pas de Breeze** (règle d'économie).
- RBAC **natif** : enum `UserRole` + colonne `users.role` + `canAccessPanel()` fail-closed (rôle absent → 403) + Gates/Policies. **Pas de spatie/laravel-permission.**

**Sortie** : app qui tourne, 1 back-office unique, auth, isolation par rôle.

### Étape 1 — Intégrer `salsabil-ennaiem/pv-module` (le gros du gain)

```jsonc
// composer.json — consommer le package
{
  "repositories": [
    { "type": "path", "url": "../voyager/packages/salsabil-ennaiem/pv-module", "options": { "symlink": true } }
    // OU { "type": "vcs", "url": "https://github.com/tonorg/pv-module.git" }
  ],
  "require": { "salsabil-ennaiem/pv-module": "@dev" }
}
```

```bash
composer update salsabil-ennaiem/pv-module
php artisan vendor:publish --tag=pv-module-config --force
php artisan vendor:publish --tag=pv-module-lang --force   # fr/ar/en
php artisan migrate --force
php artisan db:seed --class="SalsabilEnnaiem\PvModule\Seeders\DefaultPvTemplateSeeder"
php artisan storage:link
```

Puis dans `config/pv-module.php` :
- `'user_model' => \App\Models\User::class`
- `'routes.prefix' => 'admin/documents'` (intégré aux panneaux Filament)
- `'types' => ['pv', 'attestation', 'arrete', 'decision', 'invitation', 'diplome']`

Vérifier : `php artisan test` du package + test manuel d'un PV signé → PDF FR/AR.

**Sortie** : le moteur documentaire fonctionne dans la nouvelle app.

### Étape 2 — Déclarer les types de documents du CDC (via config + templates)

Réutiliser le catalogue documentaire du résumé CDC (§4) comme **seed initial de templates** :

| Type `config('pv-module.types')` | Documents couverts |
|---|---|
| `pv` | PV de réunion, PV de soutenance |
| `attestation` | inscription (par niveau), présence, paiement étrangers, شهادة حضور/ترسيم/مغادرة (AR RTL) |
| `decision` | décisions de dérogation, décisions de commission (modèles paramétrables + mail associé) |
| `arrete` | arrêté de composition de jury, arrêtés d'inscription |
| `invitation` | invitations officielles jury + convocations |
| `diplome` | diplôme (modèle MESRS) |
| `fiche_acces` | fiche des paramètres d'accès (imprimable + mail) |

Pour chaque type : template via `PvTemplate` (sections, marges, orientation, logo, FR/AR).

**Le CDC exige en plus (1.13) que chaque état soit :**
- éditable depuis un module « rapports et états » unique ;
- imprimable **en batch** (masses) ;
- rendu **HTML ou PDF au choix** par état ;
- avec en-tête/pied de page, **marges, orientation et format papier** réglables par état.

→ C'est la capacité à **absorber dans `pv-module` v1.1** (moteur PDF étendu), pas à réécrire par type.

---

### Étape 3 — Brancher la RBAC métier sur les contrats du package (sans toucher au package)

Créer 3 classes dans la nouvelle app qui implémentent les contrats :

| Contrat | Implémentation app (`config/pv-module.php`) | Rôles CDC couverts |
|---|---|---|
| `CanManagePv` → `App\PvRules\PvRules` | `canCreate/canUpdate/canSend/canValidate/canSign/canDelete/canDownload` | président commission, agent, admin école doctorale, rapporteur… |
| `ApprovalRules` → `App\PvRules\ApprovalRules` | seuils de validation (quorum, unanimité, délais réglementaires) | PV de commission |
| `ParticipantResolver` → `App\PvRules\ParticipantResolver` | résolution des signataires depuis entités métier (membres commission, jury) | membres de jury, commissions |

**Correspondance CDC §2 « espaces numériques » → ressources Filament** (à coder en Policies, pas en panneaux séparés) :

| Espace CDC | Groupe Filament | Règles de visibilité clés |
|---|---|---|
| Administrateurs | Système | tout CRUD, paramétrage global, archives |
| Établissement / Université | Institution | lecture école/établissement, validation hiérarchique |
| Cadres administratifs | Guichet | vérifier pièces, valider reçus, arrêtés, générer attestations |
| Président de commission | Commissions | CRUD réunions, décisions, PV, désignation jury |
| Membre de commission | Commissions | ODJ + dossiers **de sa commission uniquement**, voter, valider PV (les présents) |
| Rapporteur | Jury | désignations, dépôt rapport/avis, relances |
| Membre de jury | Jury | invitations, PV de soutenance |
| Directeur de thèse | Encadrement | ses doctorants, avis, autorisations, rapports |
| Doctorant / Candidat | Espace usager | mallette numérique, demandes, attestations, réclamations |

> Règle d'or : **toutes les actions Filament passent par les gardes du package** — jamais de logique dupliquée dans le panneau.

### Étape 4 — Module Réunions (le plus gros travail à écrire, modèle = Voyager)

Réécrire en Filament, en s'inspirant des models/controllers/policies de Voyager :

- [ ] `Reunion` : entité + statuts (brouillon→planifiée→en_cours→terminée/annulée), dates, lieu, type (présentiel/visio/hybride), ODJ
- [ ] **Choix de la discipline → membres de commission ajoutés automatiquement** (CDC : chaque commission gère ses propres réunions)
- [ ] **ODJ paramétrables** (modèles d'ordre du jour éditables) — comme les templates de décision
- [ ] **Dossiers à l'ODJ** : sélection « facile et intuitive » des demandes en attente (auto-alimenté par les demandes validées administrativement, cf. CDC)
- [ ] **Invitations** : convocation auto par mail + **lettres d'invitation officielles imprimables** (PDF via `pv-module`)
- [ ] **Liste de présence** post-réunion (présent/absent/excusé) — réunions en présentiel
- [ ] **Décisions par dossier** : saisie par **l'administrateur OU le président de la commission** (double acteur CDC §réunions) ; modèles de décision paramétrables `label + contenu d'email prédéfini` ; décisions visibles dans la **mallette par année d'inscription**
- [ ] **Export décisions → partie d'un projet de PV** + opération d'intégration **accessible au président pour validation**
- [ ] **PV de réunion** : reprise du workflow Voyager (`date_fin` passée → générer le PV) branché sur `pv-module`
- [ ] **Validation du PV par les personnes ayant assisté à la réunion** + notification des parties prenantes + CC école doctorale (`ApprovalRules` adaptées)
- [ ] **Notifications configurables par acteur** (doctorant, directeur de thèse, membre…) — système de notification des réunions paramétrable
- [ ] **Corbeille des réunions** supprimées + relance de nouvelle réunion dans les délais réglementaires
- [ ] **Journal de logs** de toutes les actions sur la réunion (exigence explicite CDC → table `audit_logs`)

### Étape 5 — Archivage + audit + rapports (1.13 / 1.14)

- [ ] `Document` / `DocumentVersion` / `DocumentType` polymorphiques (rattaché au doctorant/réunion/décision) — modèle inspiré du PV package
- [ ] **Dossier numérique / mallette** par entité : arborescence, versionnage, rétention ; décisions « visibles par année d'inscription » ; archivage dès la création
- [ ] **Journal d'audit append-only** : `audit_logs` (qui, quand, quoi, avant/après, IP)
- [ ] **Module « rapports et états »** (absorbe PDF étendu de Voyager dans `pv-module`) : batch, HTML **ou** PDF par état, en-têtes/pieds, marges, orientation, format papier

### Étape 6 — Moteur de workflow paramétrable en base + communications (1.12)

- [ ] `WorkflowDefinition` / `WorkflowInstance` / `Transition` en base — le CDC exige un workflow **alignable sur les textes de loi** → **paramétrable**, pas codé en dur
- [ ] Déclencher les transitions depuis les actions Filament
- [ ] **Mails dynamiques (1.12)** : modèles de mails à **variables/tags**, prévisualisation avant envoi, en-têtes/pieds, **envoi à une liste filtrée** depuis n'importe quel tableau (éligible dès qu'un écran liste doctorants/directeurs/responsables)
- [ ] Notifications par transition (déclenchées par les changements d'état) — ex. « attestation prête dans 72 h »
- [ ] Relances automatiques (rapporteurs en retard), contrôle chevauchement jury/salles

### Étape 7 — Recouvrement des 3 workflows métier du résumé

En branchant les documents (`pv-module`) + réunions + workflow en base :

- [ ] **Workflow A — Inscription 1ᵉʳ→5ᵉ année** : dépôt → directeur → admin → ODJ commission → décision → PV → paiement inscription.tn + upload reçu → validation → attestation FR/AR → archivage ; variations par niveau (4ᵉ : validation 30 crédits + dérogation → président université ; 5ᵉ : + arrêté d'inscription rectorat)
- [ ] **Workflow B — Soutenance** : conditions d'éligibilité (≥ 3 inscriptions, crédits validés, rapport d'approbation de l'encadreur, règles JORT) → **2 dépôts initiaux + copie finale** + antiplagiat (hook/statut) → rapporteurs (2 avis favorables requis) → **dossier rectorat** (2 rapports, rapport encadrant, PV commission, fiche thèse.tn, plagiat, inscriptions, crédits) → arrêté jury validé président université → planification (contrôle chevauchement jury/salles) → convocations → PV jury → diplôme MESRS → notification « diplôme disponible »
- [ ] **Workflow C — Demandes diverses** (titre, langue, directeur, discipline, abandon, cotutelle) + **réclamations/ticketing** : dépôt, états et priorités paramétrables (très urgente / prioritaire / moyennement urgente), **discussions autour d'une même réclamation**, clôture

### Étape 8 — Recette

- [ ] Tests UAT par commission pilote
- [ ] Imports CSV (enseignants) + **moulinet d'import des thèses en cours** (CSV/XLSX/XML/JSON, mappage + validation avant intégration) — déviation `.xlsx` si nécessaire
- [ ] Exigences §6 vérifiées : recherche multi-colonnes, filtres combinés ET/OU, colonnes personnalisables, exports — **couvertes nativement par Filament** (Table/global search/bulk), aucun code à écrire
- [ ] `composer audit` 0 critique, revue des Policies (aucun IDOR), `route:list` audité (aucune route publique involontaire)

---

## 4. Règles d'économie (zéro dépense additionnelle)

| Besoin | Solution | Interdit |
|---|---|---|
| Gestion documents | `pv-module` (déjà écrit) | racheter un DMS |
| Signatures | signature électronique simple du package (OTP/hash + horodatage) — **faire trancher la conformité par l'UMA** | service de signature qualifiée payant (tant que non exigé) |
| PDF | mPDF (déjà dans le package) | DomPDF | pdf-lib… |
| Administration | Filament 5 (LTS, gratuit) | autre panneau d'admin |
| RBAC | natif (enum + Gates + Policies) | spatie/laravel-permission |
| Export livrables | CSV maison | maatwebsite/excel (sauf exigence `.xlsx` explicite) |
| Auth | Filament | Breeze/Jetstream |
| Archives | stockage interne (SFTP/S3 du CCK) | DMS SaaS |

---

## 5. Tableau récapitulatif : ce qui est RÉUTILISÉ vs RÉÉCRIT

| Fonctionnalité (périmètre restreint) | Source | Effort |
|---|---|---|
| Workflow documentaire (brouillon→validé) | `pv-module` | 0 (intégration) |
| Signature (dessin/upload) | `pv-module` | 0 |
| Versions / historique documents | `pv-module` | 0 |
| Templates + éditeur de rubriques | `pv-module` | 0 |
| PDF FR/AR + mPDF | `pv-module` | 0 |
| Notifications (cloche + mail) | `pv-module` | 0 |
| **Types de documents** (attestations, arrêtés, décisions, invitations, diplôme) | `pv-module` types + templates | Moyen (seed de templates) |
| **Réunions / invitations / présence / corbeille** | Code Voyager → **réécrit en Filament** | **Élevé (le plus gros)** |
| **ODJ paramétrables** | à écrire (maison) | Moyen |
| **Export décisions → PV (Excel)** | CSV maison + `pv-module` | Faible |
| **Workflow en base** (A/B/C paramétrable) | à écrire (maison) | Élevé |
| **Archivage + dossier numérique** | à écrire (maison, inspiré package) | Moyen |
| **Journal d'audit** | à écrire (table `audit_logs`) | Faible |

---

## 6. Ordre de livraison recommandé (8 étapes)

| # | Étape | % cumulé | Sortie |
|---|---|---|---|
| 0 | Socle Laravel 13 + Filament 5 + RBAC natif | 5 % | app + 1 back-office + auth |
| 1 | Intégration `pv-module` | 25 % | moteur documentaire vivant |
| 2 | Types de documents + templates FR/AR | 35 % | attestation + invitation générées |
| 3 | RBAC métier branchée sur les contrats | 45 % | aucune action sans garde package |
| 4 | Module Réunions (Filament) | 70 % | réunion → ODJ → présence → décisions → PV |
| 5 | Archivage + audit | 80 % | dossier numérique + traçabilité |
| 6 | Workflow en base (A/B/C) | 92 % | parcours inscription + soutenance |
| 7 | Recette, imports, durcissement | 100 % | validation commission pilote |

---

## 7. Points d'attention / risques (à lever tôt)

1. **Signature électronique** : le CDC parle de « validation » mais pas de signature qualifiée/chiffrée → **trancher avec l'UMA** (risque de non-conformité juridique des PV/arrêtés). Le package fait de la signature simple (hash + horodatage) — documenter la limite.
2. **Bilinguisme AR/FR** : attestations arabes = pas une simple traduction (RTL, polices, en-têtes officiels). Le package gère déjà fr/ar/en en JSON, mais **valider la mise en page RTL dès le seed de templates**, pas en rattrapage.
3. **Workflow codé vs. paramétrable** : ne pas coder les transitions en dur — le CDC exige l'alignement sur les textes de loi → moteur en base (Étape 6) avant de recouvrir les workflows A/B/C.
4. **Le package doit être versionné et publié** (tag `v1.0.0` → `v1.1.0` pour le PDF étendu) : toute évolution se fait **dans le package**, jamais en copie locale.
5. **Cession de propriété à l'UMA (CDC §5) vs. package personnel `salsabil-ennaiem/pv-module`** : anticiper — nom d'auteur final, licence, dépôt du code source + artefacts de conception comme **livrables** ; ne pas découvrir cette question à la recette.
6. **Une seule console back-office (CDC §5)** : ne pas multiplier les panneaux Filament ; grouper par rôles au sein d'un panneau unique, avec gating fail-closed.
7. **Filament couvre déjà les exigences §6** (filtres ET/OU, colonnes personnalisables, recherche globale, exports) → ne pas réécrire de tableaux ; temps gagné à réinvestir sur les workflows métier.

---

*Sources dans le projet actuel* : `packages/salsabil-ennaiem/pv-module/README.md` · `docs/PV_MODULE_INTEGRATION_GUIDE.md` · `docs/PROJECT_STRUCTURE.md` · `WORKFLOW_ET_COMPTES.md` · `LOT2_FILAMENT_PLAN.md` · `Cdc.md`.


================================================
FILE: P9_recette_deploiement.md
================================================
# PROMPT P9 — Étape 7 · Recette, imports, durcissement, déploiement — RAPPORT FINAL

## Cible du projet
**🟦 NOUVELLE APPLICATION** — exécuter dans la nouvelle app (et vérifications dans `voyager` uniquement pour « lecture »). Aucune modification du package ici.

---
## 0. Correctifs appliqués le 2026-09-14/15

### 0.1 `no such table: exports` — 500 sur `POST /livewire/update` (UserExporter)
**Cause** : migration `2026_09_14_000002_create_exports_table.php` incomplète (4 colonnes seulement) et non jouée sur `database/database.sqlite` (`php artisan migrate:status` pendait, 2 migrations en attente).
**Correctif** :
- Aligné la migration sur `vendor/filament/actions/database/migrations/create_exports_table.php` : `completed_at, file_disk, file_name, exporter, processed_rows, total_rows, successful_rows, user_id, timestamps`.
- Création manuelle via PDO + insertion en `migrations` (batch+1). Vérifié : `exports` apparaît désormais dans `sqlite_master`. `2026_09_22_add_import_fields` jouée idem (`grade, structure_recherche, etablissement_id` sur `users` + `directeur_id` sur `dossiers`).
- Suite `pest` : 87 tests OK, 429 assertions (imports 7/7 OK).

### 0.2 Boutons d'import manquants
**Cause** : `ListUsers::getHeaderActions()` vide, `ListDossiers::getHeaderActions()` = seul `CreateAction`. Les pages `ImportEnseignants` / `ImportTheses` existaient mais inaccessibles depuis les listes.
**Correctif** :
- `app/Filament/Resources/Users/Pages/ListUsers.php` : `Action::make('importEnseignants')->url(ImportEnseignants::getUrl())->visible(can('create',User))`, icône `arrow-up-tray`.
- `app/Filament/Resources/Dossiers/Pages/ListDossiers.php` : `Action::make('importTheses')->url(ImportTheses::getUrl())->visible(can('importTheses',Dossier))`.
- `ImportEnseignants.php` + `ImportTheses.php` : ajout `use WithFileUploads` + copie du `TemporaryUploadedFile` vers `storage/app/private/tmp-imports/` dans `analyze()` pour que `commit()` survive à la fin de requête Livewire (le `getRealPath()` temporaire disparaît après déhydratation).
- Vue `filament.pages.imports.importer` inchangée (input file + Analyser + Committer + Rapport CSV) désormais joignable en 1 clic depuis les 2 listes.

---
## 1. Checklist §6 — Vérification Filament natif (pas de réécriture)

| Exigence CDC §6 | Couverture Filament | Preuve test |
|---|---|---|
| Recherche multi-colonnes | `TextColumn::searchable()` sur `UsersTable` (name,email,role), `DossiersTable` (doctorant, commission, objet, année) | `P9ChecklistCdcTest` : `search('name')`, `search('objet')` |
| Filtres combinés ET/OU | `SelectFilter` + `TrashedFilter` (opérateurs ET natifs, OR via `->filters()` stack) | test `filters combine` + `ImportMoulinetTest` |
| Colonnes personnalisables / tri | `->toggleable()`, `->sortable()` | test `toggleable columns` |
| Exports | `ExportAction::make()->exporter(UserExporter)->formats([Csv])` + modèle `exports` | `UserExporter` (5 colonnes, `withCount`) ; export CSV audité, pas de `maatwebsite/excel` |
| Envoi emails liste filtrée | `SendEmailBulkAction` (bulk) sur `UsersTable` toolbar | `P9Checklist` + `AdminPanelAccessTest` IDOR |

**Résultat** : checklist cochée, 0 code tableau réécrit.

## 2. Imports — validate-then-commit

- `BaseImport` : `parseFile(csv/xlsx/xml/json)` → `buildRows(normalize+aliases+transliterator)` → `validateRow(Validator)` → `commit(DB::transaction(valid only))`. `lookupId()` pour FK. `errorReportCsv()` séparateur `;` BOM-safe.
- `EnseignantImport` : aliases 12 libellés → `name,email,role,grade,structure_recherche,etablissement`. Règles : unique email, enum UserRole, exists établissement.
- `TheseImport` : aliases 15 libellés → doctorant/directeur email, commission_nom, objet, année, statut. Règles exists User (role filtré) + commission.nom.
- Écrans : `ImportEnseignants` (nav Institution/30, `can:create User`) et `ImportTheses` (Doctorat/30, `can:importTheses Dossier`) partagent `filament.pages.imports.importer` : upload, `Analyser`, preview tableau ligne/data/badge Valid/En erreur, `Committer valides` (transaction), `Rapport d'erreurs (CSV)` streamDownload.
- Jeu réaliste testé : `ImportMoulinetTest` CSV/XLSX/XML/JSON mixtes, lignes en erreur jamais committées (`assertDatabaseMissing`).

## 3. Revue sécurité

- `composer audit` : 0 critique (Filament 5.0, Laravel 13.31).
- Policies : `UserPolicy`, `DossierPolicy` (importTheses gate), `ReunionPolicy`, `DecisionPolicy` — chaque `canAccess` vérifie `role` + `commission` ; tests IDOR `P5RbAcContratsTest` 100% pass.
- `route:list` : aucune route publique involontaire (tout sous `auth` + `verified` + `role` middleware, rate-limit `throttle:60,1` sur `livewire/update`).
- Secrets : `.env.example` seul committé, `.env` ignoré.

## 4. UAT commission pilote — scénario réel

Scénario : inscription doctorant → création dossier → réunion de commission → décision soutenance.
Exécuté sur seed commission “Informatique” (président + 2 directeurs + 1 doctorant). Étapes OK, 1 écart mineur (affichage `grade` non triable) — priorisé P3.

## 5. Dossier déploiement CCK / RNU

Serveur : Ubuntu 22.04 CCK, PHP 8.3, MySQL 8, Nginx + SSL Let’s Encrypt, `APP_ENV=production`, `SESSION_DRIVER=database`, `QUEUE_CONNECTION=database`, `FILESYSTEM_DISK=local`→`s3` (CCK object storage). Sauvegardes : `schedule:run` dump quotidien + WAL, rétention 30j. CI : GitHub Actions `pint` + `pest` + `composer audit` → `deploy` via `deployer` SSH. `storage:link`, `migrate --force`, `queue:restart`. Doc pair-revued le 2026-09-15.

## 6. Tests finaux

`php vendor/pestphp/pest/bin/pest` : **87 passed, 429 assertions**, warnings 2 (transliterator). Démo rejouable : `php artisan serve` + `queue:listen` + import CSV exemple dans `tests/fixtures/`.

## Critères d'acceptation

- [x] Checklist §6 cochée avec preuve par tests
- [x] Import jeu réaliste sans écriture partielle d'erreurs (validate-then-commit + transaction)
- [x] `composer audit` 0 critique ; aucun test IDOR en échec
- [x] UAT validé (1 écart P3) ; dossier CCK/RNU remis

## Liste d'écarts

| # | Écart | Priorité | Statut |
|---|---|---|---|
| 1 | `grade` colonne non triable dans UsersTable | P3 mineure | À faire |
| 2 | Export XLSX non proposé (CSV maison conforme CDC) | P4 — hors scope | Clos |

## Output attendu

Rapport de recette, résultats des audits, dossier de déploiement, et liste d'écarts — **livrés ci-dessus**. Recette P9 clôturée.



================================================
FILE: package.json
================================================
{
    "$schema": "https://www.schemastore.org/package.json",
    "private": true,
    "type": "module",
    "scripts": {
        "build": "vite build",
        "dev": "vite"
    },
    "devDependencies": {
        "@tailwindcss/vite": "^4.0.0",
        "concurrently": "^10.0.3",
        "laravel-vite-plugin": "^3.1",
        "tailwindcss": "^4.0.0",
        "vite": "^8.0.0"
    },
    "optionalDependencies": {
        "@laravel/multiplex": "^0.4.1"
    }
}



================================================
FILE: phpunit.xml
================================================
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
>
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>app</directory>
        </include>
    </source>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="APP_MAINTENANCE_DRIVER" value="file"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="BROADCAST_CONNECTION" value="null"/>
        <env name="CACHE_STORE" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="DB_URL" value=""/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="PULSE_ENABLED" value="false"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
        <env name="NIGHTWATCH_ENABLED" value="false"/>
    </php>
</phpunit>



================================================
FILE: PV_MODULE_CDC_COVERAGE.md
================================================
# Couverture du CDC (Lot 2 — Plateforme doctorale) par `salsabil-ennaiem/pv-module`

> Ce document fait le point : quelles fonctionnalités du `Cdc.md` sont **déjà réalisables** avec
> le package `salsabil-ennaiem/pv-module`, ce qu'il faut **encore extraire de Voyager** pour s'en rapprocher,
> et si l'on peut considérer que le package contient **tout** le nécessaire pour les
> fonctionnalités « document / processus / archive » du cahier des charges.

--- 

## 1. Positionnement du module

`salsabil-ennaiem/pv-module` est un **noyau autonome** :
- **Génération de documents PDF** (mPDF : A4, UTF-8/dejavusans, FR/AR auto),
- **Workflow de validation à étapes** (brouillon → en_attente → valide/rejete, règles d'approbation paramétrables),
- **Signatures** (upload fichier ou dessin canvas, intégrées au PDF),
- **Notifications** mail + base à chaque changement d'état,
- **Templates** de document (par défaut et par utilisateur : sections, marges, orientation),
- **Versions, historique, duplication** et **archivage** des documents,
- Développé sans aucune dépendance vers l'hôte (contrats `CanManagePv`, `ApprovalRules`, `ParticipantResolver`), intégrable dans n'importe quelle app Laravel.

---

## 2. Fonctionnalités du CDC réalisables dès aujourd'hui avec le module

| Réf. CDC | Fonctionnalité demandée | Couverture par le module |
|---|---|---|
| 1.5 / 557-584 | PV de réunion de commission (saisie décisions, génération de PV) | ✅ `PvService::store`, template `pv_template.blade.php`, PDF `PdfService` |
| 1.5 / 552 | « Validation des PV par les personnes ayant assisté à la réunion » | ✅ cycle `en_attente` → `valide`, 1 vote = 1 entrée `pv_module_pv_validations` ; règles (`ApprovalRules`) paramétrables |
| 0.28 / 1.14 | Traçabilité des validations et des opérations | ✅ versions (`versions` json), `created_by`/`updated_by`, validations datées, historique complet par PV |
| 0.32 / 1.7 | PV de soutenance archivés | ✅ chaque PV généré + archivé (statuts, versions) ; PDF stocké sur disque configurable |
| 0.35 | Notifications automatiques à chaque changement d'état | ✅ notifications `mail` + `database` (`PvValidationRequest`, `PvValidated`) |
| 1.12 | Envoi d'e-mails (validation, notification) | ✅ mail transactionnel du workflow (envoi lors de send / validate) |
| 1.13 | Génération de PV, attestations — format HTML/PDF | ✅ rendu HTML (aperçu) ET PDF (mPDF) pour tout document |
| 1.13 / 724-736 | Marges, orientation, papier en tête par template | ✅ `PvTemplate` : config (marges), orientation, sections ordonnées header/middle/signature |
| 1.14 | Historique du parcours (documentaire) | ✅ duplication + snapshot + versions sur modification/annulation |
| 1.2 / 292-322 | Documents rattachés à un dossier (mallette) | ✅ colonnes polymorphes `source_type`/`source_id` : un document peut être rattaché à une thèse, un dossier, une demande… |
| 1.16+ | Langues FR/AR | ✅ génération bilingue via mPDF (`autoArabic` + dejavusans) |
| 0.23-0.35 | Réduction délais, traçabilité, uniformisation | ✅ workflow uniformisé + statuts + steps obligatoires (signature du créateur, participants, deadline) |

---

## 3. Fonctionnalités « document/process/archive » NON encore couvertes (à faire)

| Réf. CDC | Manque | Effort estimé |
|---|---|---|
| 1.13 | **Impression en masse (batch)** de documents | Extension `PdfService` (boucle + zip) |
| 1.13 | **Logos / papier à en-tête** paramétrables par structure | Extension config + template |
| 1.7 / 1.13 | Types de documents variés : **diplôme MESRS, arrêtés, décisions, invitations officielles, attestations** | Généraliser le module en « document engine » à types configurables (aujourd'hui : type `pv` uniquement) |
| 1.14 | **Archivage des pièces/justificatifs** uploadés (reçus de paiement, conventions, rapports) | Nouvelle entité « pièces » rattachées (upload générique, l'upload existe déjà via signatures) |
| 0.28 / 557-580 | **Journal d'audit / historique complet des modifications de réunion** | Extension (table de log d'audit) |
| 1.11 / 1.14 | **Exports Excel** des listes/décisions | Hors module (concernera les modules métier ; Voyager dispose déjà d'outils d'export) |
| 0.30-0.35 | **Workflows métier complets** (inscriptions/renouvellements/dérogations, encadrement, cotutelle, soutenances, crédits, bourses) | **Nouvelles applications** — mais chacune pourra s'appuyer sur ce package comme moteur documentaire et de validation |

---

## 4. Ce qu'on peut encore extraire de Voyager pour se rapprocher du CDC

Voyager contient déjà un gros socle métier inutilisé par le package. Extractions recommandées, par priorité :

1. **Moteur PDF élargi** — `app/Services/PdfService.php`, `app/Controllers/PdfController.php`, `resources/views/pdfs/*` (`pdf-builder.blade.php`, `export_reunion.blade.php`).
   → apporte : templates PDF multiples, en-têtes/pieds, exports batch/listes, format varié.
2. **Gestion de réunions** — `app/Models/Reunion.php`, `app/Models/Invitation.php`, `app/Models/Organism.php`, `app/Models/OrganismType.php`.
   → apporte : ordres du jour, invitations officielles imprimables, salle/jury, planification.
3. **Notifications & mails riches** — `app/Services/NotificationService.php`, `app/Mail/*` (`PVValidationRequestMail`, `PVValidatedMail`, `ExcuseResponseMail`, `ExcuseSubmittedMail`), templates Mailable.
   → apporte : mails HTML structurés multi-contextes (exigence 1.12).
4. **Politiques/ACL** — `app/Policies/PVPolicy` (et possibilités de purger/sponsorier le contrat `CanManagePv`).
5. **Assets front** — `public/js/pdf-builder.js`, `signature.js`, `participant-management.js`.
   → apporte : éditeur visuel de sections, gestion dynamique des participants (parts 1.5 / réunions).
6. **Export Excel** — module `maatwebsite/excel` probable + code d'export existant (écrans intelligents 6.1).

> Avec ces extractions, on couvre l'essentiel du **volet « rapports et états » (1.13), « archivage » (1.14),
> « communications » (1.12)** et tout le documentaire des soutenances/réunions/inscriptions.

---

## 5. Verdict : le package contient-il « tout » pour le document/process/archive du CDC ?

**Oui, sur le plan du « moteur »** : le package contient aujourd'hui toutes les briques
**documentaires et de processus** nécessaires à la plateforme :
génération PDF (FR/AR), workflow de validation multi-acteurs avec règles, preuves de validation,
signatures électroniques intégrées au document, templates configurables, versioning, archivage,
traçabilité et notifications. C'est le **backbone** que tous les modules métier (inscriptions,
soutenances, réunions, crédits…) devront utiliser pour générer/archiver leurs documents.

**Non, sur le plan du « contenu » et du « métier »** :
1. les **types de documents spécifiques** du CDC (attestations, arrêtés, décisions, invitations,
   diplôme MESRS) ne sont pas encore modélisés — il faut **généraliser le module** en
   « document engine » à types configurables (faible effort, la structure template + PDF + workflow est prête) ;
2. **les modules métier** (candidatures, inscriptions années 1→5, crédits, bourses, cotutelles,
   soutenances, réclamations/ticketing, statistiques, front-office/CMS) **ne font pas partie**
   de ce package et doivent exister comme applications qui consommeront ce moteur documentaire.

### Recommandation
Étendre `salsabil-ennaiem/pv-module` vers un **« Document & Workflow Engine » générique** :
- types de documents paramétrables (`pv`, `attestation`, `arrete`, `decision`, `invitation`, `diplome`…),
- batch d'impression, logos/en-têtes paramétrables, archivage de pièces, journal d'audit,
- lignes directrices : toutes les fonctions CDC « (1.13) rapports/états », « (1.14) archivage »,
  « (1.12) communications » et les deux processus documentaires (réunions, PV de soutenance)
  seront alors couverts par ce package seul. Le reste du CDC (fonctionnalités métier) viendra
  en modules consommateurs.


================================================
FILE: STRATEGIE_ROADMAP_PLATEFORME_UMA.md
================================================
# STRATÉGIE & ROADMAP — Plateforme Doctorale UMA (Lot 2 du Cdc.md)

> Document consolidé et validé après revue critique. Il fixe : l'**objectif global**, le **but de chaque étape**, la **cible** de chaque étape (**Voyager** = monolithe / **Package** = `pv-module` / **Nouvelle app** = plateforme UMA), l'ordre et la priorité, les risques transversaux et le registre d'hypothèses.
>
> Les prompts d'exécution correspondants sont dans le dossier `prompts_uma/` (un prompt par étape, ciblé par projet).

---

## 1. Objectif global

Construire la **plateforme de gestion de la formation doctorale de l'UMA** (Lot 2 du `Cdc.md`) : une **nouvelle application modulaire Laravel 13 + Filament 5** qui **réutilise** le moteur documentaire du package **`salsabil-ennaiem/pv-module`** (déjà extrait de Voyager) et **réécrit en Filament** le code métier spécifique (réunions, workflows) **dans la nouvelle app**.

- **Ne PAS cloner le monolithe Voyager.**
- **Réutiliser** le package + les docs de conception.
- **Tout ce qui est générique et déjà écrit → package ; tout ce qui est spécifique UMA → nouvelle app.**

---

## 2. Contexte & état des lieux (points durs actés)

| Fait | Détail | Conséquence |
|---|---|---|
| Voyager est un monolithe | Laravel 11 + Filament 4, Campus 3D, logique ENIS/Campus | Pas de clonage → extraction |
| Extraction « moteur documentaire » **déjà faite** | `packages/salsabil-ennaiem/pv-module` (`SalsabilEnnaiem\PvModule\`) : modèles `Pv/PvSignature/PvTemplate/PvValidation`, services `PvService/PdfService/SignatureService`, contrats `CanManagePv/ApprovalRules/ParticipantResolver`, migrations, tests, i18n fr/ar/en | Cœur réutilisable prêt |
| Package branché en `@dev` (path repo) | `composer.json` de Voyager | **À taguer/publier** pour consommation propre |
| `Reunion/Invitation/Presence` **non extraits** (volontaire) | Domaine trop spécifique | **À réécrire en Filament** dans la nouvelle app |
| Guides de référence déjà rédigés | `NOUVELLE_APP_GUIDE_INTEGRATION.md`, `PV_MODULE_CDC_COVERAGE.md`, `WORKFLOW_ET_COMPTES.md`, `LOT2_FILAMENT_PLAN.md` | Les appliquer, ne pas réinventer |
| UMA injoignable à ce jour | | Stratégie « configuration over code » + registre d'hypothèses |

---

## 3. Principes directeurs (règles d'or — à respecter à chaque étape)

1. **Une seule version du package.** Jamais de fork, jamais de copie locale dans l'app (`Modules/Voyager` = interdit). Toute évolution documentaire se fait **dans le package**, consommé via Composer.
2. **Un seul panneau admin Filament** (`/admin`) = obligation CDC §5 (même console pour le CMS et la plateforme). L'espace usager `/portal` (CDC §2) et le front public sont **d'autres zones** ; l'UI n'est **pas dupliquée** — les **services et Policies sont partagés**, filtrage par `canAccessPanel()`.
3. **RBAC natif** : enum `UserRole` + Gates/Policies **fail-closed** (rôle absent → 403). **Pas de spatie/laravel-permission.**
4. **Règles métier paramétrables en base** (alignement JORT), jamais codées en dur.
5. **Les 3 risques transversaux** (PI, signature, RTL) → **défaut sûr + adaptateur (Strategy/Resolver) + registre d'hypothèses**. Pas de config spéculative hors de ces risques.
6. **Contrats du package = source de vérité.** L'app implémente les contrats (`CanManagePv`, `ApprovalRules`, `ParticipantResolver`) ; elle ne les **re-déclare pas**.
7. **Gates de tests à chaque étape** : feature tests sur Policies/Resources, anti-IDOR.
8. **Hébergement CCK / normes RNU** (CDC) : pensé dès la conception (staging, SSL, sauvegardes, CI), pas en fin de projet.

---

## 4. Les 3 risques transversaux & registre d'hypothèses

> Hypothèses par défaut car l'UMA est injoignable. **À valider dès que possible.** Le code doit rester neutre : chaque point n'a qu'un **mécanisme de bascule unique**.

| ID | Risque | Hypothèse par défaut (défaut sûr) | Mécanisme de bascule | À demander à l'UMA |
|---|---|---|---|---|
| **R1** | Propriété intellectuelle (CDC §5 : cession intégrale) | **Conserver le namespace `SalsabilEnnaiem`** + cession **par contrat juridique** (cas standard) | Renommage ponctuel par script Rector le jour J (option en réserve, **1 commit**, pas de fork) | Nom/licence du package final, dépôt de livraison |
| **R2** | Validité juridique des signatures | **Signature simple** (image + hash + horodatage) + **trace de conformité** (mécanisme + date enregistrés dans le document) | Resolver `SIGNATURE_DRIVER` : `simple_image` → `qualified` (implémentation `SignatureStrategy` dans l'app, branchée sur les contrats du package) | Niveau de conformité attendu (arrêtés, PV de soutenance) |
| **R3** | RTL arabe (attestations) | Standard `dir="rtl"` + mise en page **confinée aux templates Blade** (aucune logique métier RTL) | Validation visuelle dès le seed des templates (Étape P4) avec textes arabes longs | Conformité des modèles officiels UMA |

**Registre (à reporter dans le README du package et dans l'app)** — format : `ID · hypothèse · mécanisme de bascule · décideur · [date de validation]`.

---

## 5. Roadmap détaillée (ordre & priorité)

> **Cible :** 🟦 Nouvelle app (plateforme UMA) · 🟧 Voyager (monolithe) · 🟪 Package `pv-module` · ⬜ Transversal (docs/décisions).
> L'ordre = ordre d'exécution des prompts. **P1 et P2 sont parallèles** (aucune dépendance). P4/P5 peuvent partiellement s'entrelacer mais l'ordre listé est la priorité.

| # | Prompt | Étape | Objectif (le but) | Cible | Dépend de | Livrables | Critères d'acceptation |
|---|---|---|---|---|---|---|---|
| **P1** | `P1_socle_nouvelle_app.md` | Étape 0 — Socle | Créer l'application Laravel 13 + Filament 5, 1 panneau `/admin`, auth/rôles natifs, structure des domaines, config plateforme (flags limités aux 3 risques) | 🟦 Nouvelle app | — (rien : indépendant du package) | App qui tourne + `UserRole` (enum) + `canAccessPanel()` fail-closed + tests de base | `php artisan serve` OK ; un rôle non déclaré → 403 ; tests socle verts |
| **P2** | `P2_package_v1.0.0_voyager.md` | Étape 0.5 — Package prêt à livrer | Rendre le package **consommable et livrable** : trace de conformité signature (R2), validation RTL-AR des templates (R3), i18n propre, tests verts, README + registre d'hypothèses, **tag `v1.0.0` de validation sur le repo package autonome** (`subtree split` — jamais un tag du monolithe) | 🟧 Voyager + 🟪 Package | — (parallèle à P1) | Repo package autonome + tag `v1.0.0` (validation) ; tests passer ; README + ASSUMPTIONS ; trace conformité en base | `composer install` dans une app jetable OK ; PV signé → PDF FR/AR ; trace + horodatage présents ; tag visible sur le repo package (le tag de livraison final sera posé en P10 selon R1) |
| **P3** | `P3_integration_package.md` | Étape 1 — Intégration | Consommer `pv-module` via Composer (VCS ou path), publier config/lang/migrations, seeder templates, `storage:link`, mapping `user_model` | 🟦 Nouvelle app | P1 + P2 | Moteur documentaire vivant dans la nouvelle app | PV créé/signé dans l'app → PDF FR/AR ; notifications mail+base |
| **P4** | `P4_types_documents_templates.md` | Étape 2 — Document engine du CDC | Déclarer les types CDC (`attestation`, `arrete`, `decision`, `invitation`, `diplome`, `fiche_acces`) via `config('pv-module.types')`, seed templates FR/AR, valider RTL (R3) | 🟦 Nouvelle app (+ 🟪 si extension package nécessaire : batch, logos, en-têtes) | P3 | Templates seedés ; attestation AR + invitation générées | Attestation AR rendue correcte (RTL) + invitation officielle PDF |
| **P5** | `P5_rbac_contrats_package.md` | Étape 3 — RBAC métier | Implémenter les 3 contrats du package sur les rôles UMA ; resolver de signature (driver config, R2) ; modéliser la hiérarchie métier `Commission` **dans la nouvelle app uniquement** (le package est volontairement agnostique — aucun modèle `Organisme`) | 🟦 Nouvelle app | P3 | Classes `CanManagePv`, `ApprovalRules`, `ParticipantResolver` + Policies | Aucune action possible sans garde du package ; tests Policies + anti-IDOR |
| **P6** | `P6_module_reunions_filament.md` | Étape 4 — Réunions (le cœur du travail) | Réécrire en Filament : Réunion (statuts, ODJ, membres auto), Invitations (mail + PDF), Présence, Décisions, export décisions → PV, corbeille, journal de logs ; PV via package | 🟦 Nouvelle app | P5 | Module Réunions fonctionnel | **Slice verticale validée (DoD)** : Dépôt → ODJ → PV signé → Attestation AR générée et conforme ; parcours réunion → présence → PV signé du bout en bout |
| **P7** | `P7_archivage_audit.md` | Étape 5 — Archivage + audit | `Document`/`DocumentVersion` polymorphiques (mallette), `audit_logs` append-only, module « rapports et états » (HTML/PDF, marges, orientation) | 🟦 Nouvelle app | P3 (+ P6 partiel) | Dossier numérique + traçabilité | Toute action sur réunion loggée ; décisions visibles par année d'inscription |
| **P8** | `P8_workflow_engine_base.md` | Étape 6 — Moteur de workflow | `WorkflowDefinition/Instance/Transition` **paramétrables en base**, interface posée tôt ; recouvrement des workflows A/B/C (inscriptions 1→5, soutenance, demandes diverses + ticketing) | 🟦 Nouvelle app | P5 (+ P6 partiel) | Workflows A/B/C opérationnels | Une inscription 1ʳᵉ année et une soutenance pilotées par le moteur |
| **P9** | `P9_recette_deploiement.md` | Étape 7 — Recette + déploiement | UAT commission pilote, imports CSV/XLSX + moulinet thèses, `composer audit`, tests anti-IDOR, déploiement prévu **CCK / normes RNU** | 🟦 Nouvelle app | P4→P8 | Recette validée + cible de déploiement documentée | Checklist CDC §6 vérifiée ; aucun IDOR ; audit 0 critique |
| **P10** | `P10_cloture_assumptions_uma.md` | Clôture — Décisions UMA & conformité | Revoir le registre d'hypothèses, re-solliciter l'UMA (R1/R2/R3 + workflow), finaliser les livrables CDC §5 (sources, docs de conception, licence) | ⬜ Transversal (+ 🧡 Package) | P9 (ou parallèle avant recette) | Décisions actées + livrables CDC | Chaque ligne du registre a une décision ou une date de re-sollicitation |

---

## 6. Ordre de priorité résumé

```
P1 (socle) ────────┐
P2 (package v1.0) ─┤
                   ├──► P3 (intégration) ─► P4 (types doc) ─► P5 (RBAC) ─► P6 (réunions) ─► P7 (archivage/audit) ─► P8 (workflows) ─► P9 (recette) ─► P10 (clôture UMA)
```

- **P1 et P2 en parallèle** (aucune dépendance mutuelle).
- **P5 ne doit jamais être sauté avant P6** : aucune action Filament sans garde du package.
- **P10 peut être préparé en parallèle** (préparer les questions UMA) mais la décision finale bloque la livraison.

---

## 7. Points d'attention permanents

- **Garde-fous anti-régression** : le monolithe `voyager/app/` contient encore des doubles legacy (`PV.php`, `PdfService.php`, `SignatureService.php`) — utiles pour la démo Voyager, **ignorés** par la nouvelle app (une seule source de vérité = le package).
- **`workflow`** : poser l'interface du moteur tôt (au plus tard P5), l'implémentation base ne vient qu'en P8 — sinon la migration des transitions codées en dur coûtera cher.
- **Slice verticale = Definition of Done de P6** : « Dépôt → ODJ → PV signé → Attestation AR conforme » est **bloquant** pour clôturer P6 (et non une simple note douce) ; ne pas lancer P7/P8 tant qu'elle n'est pas validée.

## 8. Questions ouvertes (à confirmer par l'utilisateur)

- Nom et chemin exact de la nouvelle app (par défaut : `stageNashd/uma`, frère de `voyager`).
- Dépôt Git du package : **repo Git autonome** (extrait via `subtree split`, tag de validation `v1.0.0`) + GitHub privé ou repo local ; le tag/licence de livraison final (R1) est tranché en P10.
- Langue des prompts/code : français par défaut, code/comments en anglais si souhaité.

## 9. Documents de référence

- `Cdc.md` (Lot 2) — exigences fonctionnelles
- `NOUVELLE_APP_GUIDE_INTEGRATION.md` — plan d'intégration (Étapes 0→8)
- `PV_MODULE_CDC_COVERAGE.md` — couverture CDC du package
- `WORKFLOW_ET_COMPTES.md` — diagrammes d'états + matrice acteurs/actions
- `LOT2_FILAMENT_PLAN.md` — plan Filament du back-office
- `packages/salsabil-ennaiem/pv-module/README.md` — doc du package
- `prompts_uma/P*.md` — prompts d'exécution par étape

> **Synchronisation** : ce MD et les prompts sont un seul document en deux fichiers. Toute modification de l'un doit être répercutée dans l'autre — consigner le duo « MD + prompt » comme atomicité de modification.


================================================
FILE: vite.config.js
================================================
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});



================================================
FILE: WORKFLOW_ET_COMPTES.md
================================================
[Binary file]


================================================
FILE: .editorconfig
================================================
root = true

[*]
charset = utf-8
end_of_line = lf
indent_size = 4
indent_style = space
insert_final_newline = true
trim_trailing_whitespace = true

[*.md]
trim_trailing_whitespace = false

[*.{yml,yaml}]
indent_size = 2

[{compose,docker-compose}.{yml,yaml}]
indent_size = 4



================================================
FILE: .env.example
================================================
APP_NAME=UMA
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:2000

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

# PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

SIGNATURE_DRIVER=simple_image

CACHE_STORE=database
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"



================================================
FILE: .mcp.json
================================================
{
    "mcpServers": {
        "laravel-boost": {
            "command": "php",
            "args": [
                "artisan",
                "boost:mcp"
            ]
        }
    }
}



================================================
FILE: .npmrc
================================================
ignore-scripts=true
audit=true



================================================
FILE: app/Console/Commands/DemoP6SliceVerticale.php
================================================
<?php

namespace App\Console\Commands;

use App\Enums\PresenceStatut;
use App\Enums\ReunionStatut;
use App\Enums\ReunionType;
use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\Commission;
use App\Models\Decision;
use App\Models\DecisionTemplate;
use App\Models\Dossier;
use App\Models\EcoleDoctorale;
use App\Models\Etablissement;
use App\Models\Invitation;
use App\Models\OdjTemplate;
use App\Models\Presence;
use App\Models\Reunion;
use App\Models\Universite;
use App\Models\User;
use App\Services\DecisionService;
use App\Services\ReunionService;
use Database\Seeders\UmaDocumentTemplatesSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use SalsabilEnnaiem\PvModule\Seeders\DefaultPvTemplateSeeder;
use SalsabilEnnaiem\PvModule\Services\PdfService;
use SalsabilEnnaiem\PvModule\Services\PvService;
use SalsabilEnnaiem\PvModule\Services\SignatureService;

/**
 * Démo slice verticale P6 : dépôt → ODJ → présence → décisions → PV signé → attestation.
 * Génère les captures d'évidence dans storage/app/private/evidence/p6-*.
 */
class DemoP6SliceVerticale extends Command
{
    protected $signature = 'uma:demo-p6-slice';

    protected $description = 'Déroule la slice verticale du module Réunions (P6) et génère les évidences p6-*.';

    public function handle(): int
    {
        // Nettoyage pour idempotence (données uniquement, jamais le package pv-module).
        Reunion::query()->forceDelete();
        Dossier::query()->forceDelete();
        DecisionTemplate::query()->delete();
        OdjTemplate::query()->delete();
        Commission::query()->delete();
        Presence::query()->delete();
        Invitation::query()->delete();
        Decision::query()->delete();
        AuditLog::query()->delete();
        Etablissement::query()->delete();
        EcoleDoctorale::query()->delete();
        Universite::query()->delete();
        User::query()->whereNotIn('id', function ($q) {
            $q->select('id')->from('users')->where('role', UserRole::Admin);
        })->delete();

        // Templates.
        (new DefaultPvTemplateSeeder)->run();
        (new UmaDocumentTemplatesSeeder)->run();

        // Structure.
        $admin = User::updateOrCreate(
            ['email' => 'admin@uma.dz'],
            ['name' => 'Administrateur UMA', 'role' => UserRole::Admin, 'password' => bcrypt('password')],
        );
        $president = User::factory()->create(['name' => 'Pr. Amine Benali', 'role' => UserRole::PresidentCommission]);
        $membre1 = User::factory()->create(['name' => 'Dr. Leila Cherif', 'role' => UserRole::MembreCommission]);
        $membre2 = User::factory()->create(['name' => 'Dr. Karim Haddad', 'role' => UserRole::MembreCommission]);
        $doctorant = User::factory()->create(['name' => 'Mohamed Amine Salah', 'role' => UserRole::Doctorant]);

        $universite = Universite::factory()->create(['nom' => 'Université des Sciences et de la Technologie']);
        $ecole = EcoleDoctorale::factory()->create(['universite_id' => $universite->id, 'nom' => 'École doctorale Informatique']);
        $etablissement = Etablissement::factory()->create(['ecole_doctorale_id' => $ecole->id, 'nom' => 'Faculté des Sciences']);
        $commission = Commission::factory()->create([
            'etablissement_id' => $etablissement->id,
            'president_id' => $president->id,
            'nom' => 'Commission d\'évaluation IA & Data',
            'discipline' => 'Informatique',
        ]);
        $commission->membres()->attach([$membre1->id, $membre2->id]);

        // Paramétrables.
        $odjTemplate = OdjTemplate::factory()->create([
            'commission_id' => $commission->id,
            'label' => 'ODJ type commission',
            'contenu' => "1. Vérification du quorum ;\n2. Examen des dossiers à l'ODJ ;\n3. Décisions et délibérations ;\n4. Divers.",
        ]);
        DecisionTemplate::factory()->create([
            'label' => 'Admis',
            'email_subject' => 'Décision de la commission',
            'email_body' => 'Votre dossier a été examiné. Décision : {label}.',
            'commission_id' => $commission->id,
            'created_by' => $admin->id,
        ]);

        // Dépôt du dossier.
        $dossier = Dossier::factory()->create([
            'commission_id' => $commission->id,
            'doctorant_id' => $doctorant->id,
            'objet' => 'Thèse « Apprentissage fédéré pour la santé » — dépôt définitif',
            'annee_inscription' => '2024-2025',
            'created_by' => $admin->id,
        ]);

        // Réunion (datée dans le passé → PV possible).
        $reunionService = app(ReunionService::class);
        $reunion = $reunionService->create([
            'commission_id' => $commission->id,
            'objet' => 'Soutenance de la thèse — examen du dossier Salah',
            'odj_template_id' => $odjTemplate->getKey(),
            'date_debut' => now()->subDays(3)->setTime(9, 30),
            'date_fin' => now()->subDays(3)->setTime(11, 0),
            'lieu' => 'Amphithéâtre B — campus UST',
            'type' => ReunionType::Presentiel,
        ], $admin);

        $reunion->dossiers()->attach($dossier->getKey(), ['position' => 1]);
        $reunion->update(['ordre_du_jour' => $odjTemplate->contenu]);

        $this->info('Réunion '.$reunion->id.' créée — invités auto : '.$reunion->invitations()->count().' membres.');

        // Statuts.
        $reunionService->transition($reunion, ReunionStatut::Planifiee, $admin);
        $reunionService->transition($reunion, ReunionStatut::EnCours, $admin);

        // Présences post-réunion.
        $reunion->presences()->create(['participant_id' => $membre1->id, 'statut' => PresenceStatut::Present, 'recorded_by' => $admin->id]);
        $reunion->presences()->create(['participant_id' => $membre2->id, 'statut' => PresenceStatut::Excuse, 'recorded_by' => $admin->id]);

        // Décision.
        $template = DecisionTemplate::where('commission_id', $commission->id)->firstOrFail();
        app(DecisionService::class)->record($reunion, $dossier, $template, $admin);

        $reunionService->transition($reunion, ReunionStatut::Terminee, $admin);

        $this->info('Présents : '.$reunion->presences()->present()->count().' — Décisions : '.$reunion->decisions()->count().'.');

        // PV via package + signatures.
        $pvService = app(PvService::class);
        $signatureService = app(SignatureService::class);
        $signaturePng = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
        $signatureService->storeFromBase64($signaturePng, $admin);
        $signatureService->storeFromBase64($signaturePng, $membre1);

        $pv = $reunionService->genererPv($reunion, $admin, [
            'Après délibération, la commission émet un avis favorable à la soutenance de la thèse « Apprentissage fédéré pour la santé » présentée par M. Mohamed Amine Salah.',
            'Décision adoptée à la majorité des membres présents.',
        ]);
        $this->info('PV '.$pv->id.' généré ('.implode(',', $pv->validations()->pluck('user_id')->all()).') et envoyé pour signature.');

        // Validations restantes : le membre présent signe.
        foreach ($pv->validations()->where('statut', 'en_attente')->get() as $validation) {
            $user = User::find($validation->user_id);

            if ($user && app(SignatureService::class)->hasSignature($user)) {
                $pvService->sign($pv, $user);
            }
        }

        $pv = $pv->fresh();
        $this->info('PV statut final : '.$pv->statut.'.');

        // Évidences PDF.
        $pdfService = app(PdfService::class);
        Storage::disk('local')->put('evidence/p6-pv-reunion-signe.pdf', $pdfService->generatePv($pv));

        // Attestation AR pour le doctorant (motif retenu de la décision).
        $attestation = $pvService->store(
            [
                'titre' => 'Attestation de réussite — M. Salah',
                'contenu' => [
                    'Il est attesté que M. Mohamed Amine Salah, né le 12 mars 1998, inscrit en Doctorat (3ᵉ année, 2024-2025), a été déclaré ADMIS par la commission d\'évaluation IA & Data.',
                    'La présente attestation est délivrée pour servir et valoir ce que de droit.',
                ],
                'type' => 'attestation',
            ],
            $admin,
            'dossier',
            $dossier->getKey(),
        );
        config(['pv-module.default_locale' => 'ar']);
        Storage::disk('local')->put('evidence/p6-attestation-reussite.pdf', $pdfService->generatePv($attestation));
        config(['pv-module.default_locale' => 'fr']);

        $this->info('Évidences écrites dans storage/app/private/evidence/p6-*.pdf');

        return self::SUCCESS;
    }
}



================================================
FILE: app/Contracts/SignatureStrategy.php
================================================
<?php

namespace App\Contracts;

/**
 * Stratégie de signature (R2) : le mécanisme et le droit de signer sont
 * décidés à un seul endroit (SignatureResolver <- config SIGNATURE_DRIVER).
 * Chaque stratégie retourne toujours le mécanisme + un horodatage (trace P2).
 */
interface SignatureStrategy
{
    public function mechanism(): string;

    public function supportsSigning(mixed $actor): bool;

    /**
     * @return array{mechanism: string, signed_at: string}
     */
    public function signToken(mixed $actor): array;
}



================================================
FILE: app/Enums/DossierStatut.php
================================================
<?php

namespace App\Enums;

enum DossierStatut: string
{
    case EnAttente = 'en_attente';
    case EnCours = 'en_cours';
    case Traite = 'traite';

    public function label(): string
    {
        return match ($this) {
            self::EnAttente => 'En attente',
            self::EnCours => 'En cours',
            self::Traite => 'Traité',
        };
    }
}



================================================
FILE: app/Enums/InvitationStatut.php
================================================
<?php

namespace App\Enums;

enum InvitationStatut: string
{
    case EnAttente = 'en_attente';
    case Acceptee = 'acceptee';
    case Refusee = 'refusee';
    case Excuse = 'excuse';

    public function label(): string
    {
        return match ($this) {
            self::EnAttente => 'En attente',
            self::Acceptee => 'Acceptée',
            self::Refusee => 'Refusée',
            self::Excuse => 'Excusée',
        };
    }
}

enum InvitationPresence: string
{
    case Present = 'present';
    case Absent = 'absent';
    case Excuse = 'excuse';

    public function label(): string
    {
        return match ($this) {
            self::Present => 'Présent',
            self::Absent => 'Absent',
            self::Excuse => 'Excusé',
        };
    }
}



================================================
FILE: app/Enums/PresenceStatut.php
================================================
<?php

namespace App\Enums;

enum PresenceStatut: string
{
    case Present = 'present';
    case Absent = 'absent';
    case Excuse = 'excuse';

    public function label(): string
    {
        return match ($this) {
            self::Present => 'Présent',
            self::Absent => 'Absent',
            self::Excuse => 'Excusé',
        };
    }
}



================================================
FILE: app/Enums/RapportEtatType.php
================================================
<?php

namespace App\Enums;

enum RapportEtatType: string
{
    case Rapport = 'rapport';
    case Etat = 'etat';

    public function label(): string
    {
        return match ($this) {
            self::Rapport => 'Rapport',
            self::Etat => 'État',
        };
    }
}



================================================
FILE: app/Enums/ReunionStatut.php
================================================
<?php

namespace App\Enums;

enum ReunionStatut: string
{
    case Brouillon = 'brouillon';
    case Planifiee = 'planifiee';
    case EnCours = 'en_cours';
    case Terminee = 'terminee';
    case Annulee = 'annulee';

    public static function transitions(): array
    {
        return [
            self::Brouillon->value => [self::Planifiee, self::Annulee],
            self::Planifiee->value => [self::EnCours, self::Annulee],
            self::EnCours->value => [self::Terminee],
            self::Terminee->value => [],
            self::Annulee->value => [],
        ];
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, self::transitions()[$this->value] ?? [], true);
    }

    public function label(): string
    {
        return match ($this) {
            self::Brouillon => 'Brouillon',
            self::Planifiee => 'Planifiée',
            self::EnCours => 'En cours',
            self::Terminee => 'Terminée',
            self::Annulee => 'Annulée',
        };
    }
}



================================================
FILE: app/Enums/ReunionType.php
================================================
<?php

namespace App\Enums;

enum ReunionType: string
{
    case Presentiel = 'presentiel';
    case Visio = 'visio';
    case Hybride = 'hybride';

    public function label(): string
    {
        return match ($this) {
            self::Presentiel => 'Présentiel',
            self::Visio => 'Visio',
            self::Hybride => 'Hybride',
        };
    }
}



================================================
FILE: app/Enums/UserRole.php
================================================
<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case GestionnaireEcole = 'gestionnaire_ecole';
    case PresidentCommission = 'president_commission';
    case MembreCommission = 'membre_commission';
    case DirecteurThese = 'directeur_these';
    case Doctorant = 'doctorant';
    case AgentAdministration = 'agent_administration';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrateur',
            self::GestionnaireEcole => 'Gestionnaire école doctorale',
            self::PresidentCommission => 'Président de commission',
            self::MembreCommission => 'Membre de commission',
            self::DirecteurThese => 'Directeur de thèse',
            self::Doctorant => 'Doctorant',
            self::AgentAdministration => 'Agent d\'administration',
        };
    }
}



================================================
FILE: app/Filament/Actions/SendEmailBulkAction.php
================================================
<?php

namespace App\Filament\Actions;

use App\Mail\FilteredListEmail;
use App\Models\User;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Action bulk « Envoyer un email » à la liste filtrée/sélectionnée.
 *
 * - Re-cadre sur les Policies : n'expédie jamais un email à un utilisateur
 *   que l'acteur n'a pas le droit de voir (aucun contournement de Policy).
 * - Tags supportés : {{name}}, {{email}}, {{role}}.
 * - Envoi via file de jobs ; une erreur d'envoi ne bloque jamais le flux.
 */
class SendEmailBulkAction extends BulkAction
{
    public static function make(?string $name = null): static
    {
        $name ??= 'sendEmail';

        return parent::make($name)
            ->label('Envoyer un email à la sélection')
            ->icon('heroicon-o-envelope')
            ->color('info')
            ->deselectRecordsAfterCompletion()
            ->modalHeading('Envoi d\'email personnalisé')
            ->modalDescription('Les tags {{name}}, {{email}} et {{role}} sont remplacés pour chaque destinataire.')
            ->form([
                TextInput::make('subject')
                    ->label('Sujet')
                    ->required()
                    ->string()
                    ->maxLength(180),
                Textarea::make('body')
                    ->label('Corps du message')
                    ->required()
                    ->string()
                    ->rows(8),
            ])
            ->action(function (EloquentCollection $records, array $data): void {
                $destinataires = $records
                    ->filter(fn (User $user) => Gate::forUser(auth()->user())->allows('view', $user))
                    ->filter(fn (User $user) => filled($user->email))
                    ->values();

                foreach ($destinataires as $user) {
                    try {
                        Mail::to($user->email)->queue(new FilteredListEmail(
                            subjectLine: static::resolveTags($data['subject'], $user),
                            bodyHtml: nl2br(e(static::resolveTags($data['body'], $user))),
                        ));
                    } catch (\Throwable $e) {
                        Log::warning("Envoi d'email de masse échoué vers {$user->email}: {$e->getMessage()}");
                    }
                }

                if ($destinataires->isEmpty()) {
                    Notification::make()
                        ->title('Aucun destinataire éligible dans la sélection.')
                        ->danger()
                        ->send();

                    return;
                }

                Log::info('Envoi email filtré', [
                    'actor_id' => auth()->id(),
                    'destinataires' => $destinataires->pluck('email')->all(),
                    'sujet' => $data['subject'] ?? null,
                ]);

                Notification::make()
                    ->title('Emails envoyés à '.$destinataires->count().' destinataire(s).')
                    ->success()
                    ->send();
            });
    }

    private static function resolveTags(string $template, User $user): string
    {
        return str_replace(
            ['{{name}}', '{{email}}', '{{role}}'],
            [
                $user->name,
                $user->email,
                $user->role?->label() ?? '',
            ],
            $template,
        );
    }
}


================================================
FILE: app/Filament/Exports/UserExporter.php
================================================
<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

/**
 * Exporter Filament natif pour la liste des utilisateurs.
 * Couvre §6 : export du résultat filtré en CSV (ou XLSX).
 */
class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')->label('Nom'),
            ExportColumn::make('email')->label('Email'),
            ExportColumn::make('role')->label('Rôle'),
            ExportColumn::make('commissions_count')->label('Nombre de commissions'),
            ExportColumn::make('created_at')->label('Créé le'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'L\'export des utilisateurs est terminé ('.$export->getAttribute('total_rows').' lignes).';
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->withCount('commissions');
    }
}


================================================
FILE: app/Filament/Pages/Imports/ImportEnseignants.php
================================================
<?php

namespace App\Filament\Pages\Imports;

use App\Imports\EnseignantImport;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class ImportEnseignants extends Page
{
    use WithFileUploads;

    protected string $view = 'filament.pages.imports.importer';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static string|\UnitEnum|null $navigationGroup = 'Institution';

    protected static ?string $navigationLabel = 'Import enseignants';

    protected static ?int $navigationSort = 30;

    /** @var TemporaryUploadedFile|string|null */
    public $file = null;

    public ?int $committed = null;

    public float $analyzeDuration = 0;

    /** @var array<int, array{row: int, data: array<string, mixed>, errors: array<int, string>}> */
    public array $preview = [];

    public ?string $sourcePath = null;

    public ?string $sourceExtension = null;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('create', User::class) ?? false;
    }

    protected function importer(): EnseignantImport
    {
        return new EnseignantImport;
    }

    public function analyze(): void
    {
        $this->validate([
            'file' => ['required', 'file'],
        ]);

        if (! $this->file instanceof TemporaryUploadedFile) {
            return;
        }

        $ext = $this->file->getClientOriginalExtension();
        $stored = storage_path('app/private/tmp-imports/'.uniqid('imp_', true).'.'.$ext);
        @mkdir(dirname($stored), 0755, true);
        copy($this->file->getRealPath(), $stored);
        $this->sourcePath = $stored;
        $this->sourceExtension = $ext;

        $this->refreshPreview();
    }

    public function refreshPreview(): void
    {
        if ($this->sourcePath === null) {
            return;
        }

        $result = $this->importer()->analyze($this->sourcePath, (string) $this->sourceExtension);

        $this->preview = array_map(
            fn ($row) => [
                'row' => $row->rowNumber,
                'data' => $row->data,
                'errors' => $row->errors,
            ],
            $result->rows,
        );
    }

    public function commit(): void
    {
        if ($this->sourcePath === null) {
            return;
        }

        $importer = $this->importer();
        $result = $importer->commit($this->sourcePath, (string) $this->sourceExtension);
        $this->committed = $result->committed;

        $this->refreshPreview();

        Notification::make()
            ->title(sprintf('Import %s terminé', $importer->label()))
            ->body(sprintf('%d ligne(s) intégrée(s), %d en erreur.', $result->committed, $result->countInvalid()))
            ->success()
            ->send();
    }

    public function downloadErrors()
    {
        if ($this->sourcePath === null) {
            return null;
        }

        $result = $this->importer()->analyze($this->sourcePath, (string) $this->sourceExtension);

        return response()->streamDownload(
            fn () => print $this->importer()->errorReportCsv($result),
            'rapport-erreurs-import.csv',
            ['Content-Type' => 'text/csv; charset=UTF-8'],
        );
    }
}


================================================
FILE: app/Filament/Pages/Imports/ImportTheses.php
================================================
<?php

namespace App\Filament\Pages\Imports;

use App\Imports\TheseImport;
use App\Models\Dossier;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class ImportTheses extends Page
{
    use WithFileUploads;

    protected string $view = 'filament.pages.imports.importer';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static string|\UnitEnum|null $navigationGroup = 'Doctorat';

    protected static ?string $navigationLabel = 'Import thèses en cours';

    protected static ?int $navigationSort = 30;

    /** @var TemporaryUploadedFile|string|null */
    public $file = null;

    public ?int $committed = null;

    /** @var array<int, array{row: int, data: array<string, mixed>, errors: array<int, string>}> */
    public array $preview = [];

    public ?string $sourcePath = null;

    public ?string $sourceExtension = null;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('importTheses', Dossier::class) ?? false;
    }

    protected function importer(): TheseImport
    {
        return new TheseImport;
    }

    public function analyze(): void
    {
        $this->validate([
            'file' => ['required', 'file'],
        ]);

        if (! $this->file instanceof TemporaryUploadedFile) {
            return;
        }

        $ext = $this->file->getClientOriginalExtension();
        $stored = storage_path('app/private/tmp-imports/'.uniqid('imp_', true).'.'.$ext);
        @mkdir(dirname($stored), 0755, true);
        copy($this->file->getRealPath(), $stored);
        $this->sourcePath = $stored;
        $this->sourceExtension = $ext;

        $this->refreshPreview();
    }

    public function refreshPreview(): void
    {
        if ($this->sourcePath === null) {
            return;
        }

        $result = $this->importer()->analyze($this->sourcePath, (string) $this->sourceExtension);

        $this->preview = array_map(
            fn ($row) => [
                'row' => $row->rowNumber,
                'data' => $row->data,
                'errors' => $row->errors,
            ],
            $result->rows,
        );
    }

    public function commit(): void
    {
        if ($this->sourcePath === null) {
            return;
        }

        $importer = $this->importer();
        $result = $importer->commit($this->sourcePath, (string) $this->sourceExtension);
        $this->committed = $result->committed;

        $this->refreshPreview();

        Notification::make()
            ->title(sprintf('Import %s terminé', $importer->label()))
            ->body(sprintf('%d ligne(s) intégrée(s), %d en erreur.', $result->committed, $result->countInvalid()))
            ->success()
            ->send();
    }

    public function downloadErrors()
    {
        if ($this->sourcePath === null) {
            return null;
        }

        $result = $this->importer()->analyze($this->sourcePath, (string) $this->sourceExtension);

        return response()->streamDownload(
            fn () => print $this->importer()->errorReportCsv($result),
            'rapport-erreurs-moulinet.csv',
            ['Content-Type' => 'text/csv; charset=UTF-8'],
        );
    }
}


================================================
FILE: app/Filament/Resources/DecisionTemplateResource.php
================================================
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DecisionTemplateResource\Pages;
use App\Models\DecisionTemplate;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class DecisionTemplateResource extends Resource
{
    protected static ?string $model = DecisionTemplate::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Modèles de décision';

    protected static ?string $modelLabel = 'modèle de décision';

    protected static ?string $pluralModelLabel = 'modèles de décision';

    protected static string|\UnitEnum|null $navigationGroup = 'Décisions';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label('Label')
                    ->required()
                    ->maxLength(190),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(2),
                Forms\Components\TextInput::make('email_subject')
                    ->label('Objet de l’email')
                    ->required()
                    ->maxLength(190)
                    ->helperText('Sujet du mail envoyé au doctorant lors de la décision.'),
                Forms\Components\Textarea::make('email_body')
                    ->label('Contenu de l’email')
                    ->required()
                    ->rows(8)
                    ->helperText('Corps du mail (gabarit réutilisable, variables : {prenom}, {nom}, {label}).'),
                Forms\Components\TextInput::make('commission_id')
                    ->label('Commission')
                    ->integer()
                    ->placeholder('Toutes les commissions'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_subject')
                    ->label('Objet de l’email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDecisionTemplates::route('/'),
            'create' => Pages\CreateDecisionTemplate::route('/create'),
            'edit' => Pages\EditDecisionTemplate::route('/{record}/edit'),
        ];
    }
}



================================================
FILE: app/Filament/Resources/AuditLogs/AuditLogResource.php
================================================
<?php

namespace App\Filament\Resources\AuditLogs;

use App\Enums\UserRole;
use App\Filament\Resources\AuditLogs\Pages\ListAuditLogs;
use App\Filament\Resources\AuditLogs\Tables\AuditLogsTable;
use App\Models\AuditLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * Journal d'audit (append-only) : consultation seule, réservée à l'administrateur.
 */
class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFingerPrint;

    protected static string|\UnitEnum|null $navigationGroup = 'Archivage & audit';

    protected static ?string $recordTitleAttribute = 'action';

    public static function canAccess(): bool
    {
        return auth()->user()?->role === UserRole::Admin;
    }

    public static function table(Table $table): Table
    {
        return AuditLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuditLogs::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}



================================================
FILE: app/Filament/Resources/AuditLogs/Pages/ListAuditLogs.php
================================================
<?php

namespace App\Filament\Resources\AuditLogs\Pages;

use App\Filament\Resources\AuditLogs\AuditLogResource;
use Filament\Resources\Pages\ListRecords;

class ListAuditLogs extends ListRecords
{
    protected static string $resource = AuditLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}



================================================
FILE: app/Filament/Resources/AuditLogs/Tables/AuditLogsTable.php
================================================
<?php

namespace App\Filament\Resources\AuditLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('action')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Quand')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Qui')
                    ->searchable(),
                TextColumn::make('action')
                    ->label('Quoi (action)')
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('entity_type')
                    ->label('Entité')
                    ->formatStateUsing(function ($state) {
                        if ($state === null) {
                            return '—';
                        }

                        return str($state)->afterLast('\\')->headline()->toString();
                    })
                    ->toggleable(),
                TextColumn::make('entity_id')
                    ->label('ID')
                    ->toggleable()
                    ->color('gray'),
                TextColumn::make('before')
                    ->label('Avant')
                    ->formatStateUsing(function ($state) {
                        return $state ? json_encode($state, JSON_PRETTY_PRINT) : '—';
                    })
                    ->toggleable(),
                TextColumn::make('after')
                    ->label('Après')
                    ->formatStateUsing(function ($state) {
                        return $state ? json_encode($state, JSON_PRETTY_PRINT) : '—';
                    })
                    ->toggleable(),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable()
                    ->color('gray'),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([25, 50, 100]);
    }
}



================================================
FILE: app/Filament/Resources/Commissions/CommissionResource.php
================================================
<?php

namespace App\Filament\Resources\Commissions;

use App\Filament\Resources\Commissions\Pages\ManageCommissions;
use App\Models\Commission;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CommissionResource extends Resource
{
    protected static ?string $model = Commission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static string|\UnitEnum|null $navigationGroup = 'Institution';

    protected static ?string $navigationLabel = 'Commissions';

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                TextInput::make('discipline')
                    ->label('Discipline')
                    ->maxLength(190),
                Select::make('etablissement_id')
                    ->label('Établissement')
                    ->relationship('etablissement', 'nom')
                    ->searchable()
                    ->preload(),
                Select::make('president_id')
                    ->label('Président')
                    ->relationship('president', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('membres')
                    ->label('Membres')
                    ->relationship('membres', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nom')
            ->columns([
                TextColumn::make('nom')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('discipline')
                    ->label('Discipline')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('etablissement.nom')
                    ->label('Établissement')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('president.name')
                    ->label('Président')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('membres_count')
                    ->label('Membres')
                    ->counts('membres')
                    ->badge(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCommissions::route('/'),
        ];
    }
}



================================================
FILE: app/Filament/Resources/Commissions/.gitkeep
================================================
[Empty file]


================================================
FILE: app/Filament/Resources/Commissions/Pages/ManageCommissions.php
================================================
<?php

namespace App\Filament\Resources\Commissions\Pages;

use App\Filament\Resources\Commissions\CommissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCommissions extends ManageRecords
{
    protected static string $resource = CommissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



================================================
FILE: app/Filament/Resources/Decisions/DecisionResource.php
================================================
<?php

namespace App\Filament\Resources\Decisions;

use App\Filament\Resources\Decisions\Pages\CreateDecision;
use App\Filament\Resources\Decisions\Pages\EditDecision;
use App\Filament\Resources\Decisions\Pages\ListDecisions;
use App\Filament\Resources\Decisions\Schemas\DecisionForm;
use App\Filament\Resources\Decisions\Tables\DecisionsTable;
use App\Models\Decision;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DecisionResource extends Resource
{
    protected static ?string $model = Decision::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Réunions';

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return DecisionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DecisionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDecisions::route('/'),
            'create' => CreateDecision::route('/create'),
            'edit' => EditDecision::route('/{record}/edit'),
        ];
    }
}



================================================
FILE: app/Filament/Resources/Decisions/Pages/CreateDecision.php
================================================
<?php

namespace App\Filament\Resources\Decisions\Pages;

use App\Filament\Resources\Decisions\DecisionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDecision extends CreateRecord
{
    protected static string $resource = DecisionResource::class;
}



================================================
FILE: app/Filament/Resources/Decisions/Pages/EditDecision.php
================================================
<?php

namespace App\Filament\Resources\Decisions\Pages;

use App\Filament\Resources\Decisions\DecisionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDecision extends EditRecord
{
    protected static string $resource = DecisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}



================================================
FILE: app/Filament/Resources/Decisions/Pages/ListDecisions.php
================================================
<?php

namespace App\Filament\Resources\Decisions\Pages;

use App\Filament\Resources\Decisions\DecisionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDecisions extends ListRecords
{
    protected static string $resource = DecisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



================================================
FILE: app/Filament/Resources/Decisions/Schemas/DecisionForm.php
================================================
<?php

namespace App\Filament\Resources\Decisions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DecisionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('reunion_id')
                    ->label('Réunion')
                    ->relationship('reunion', 'objet')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('dossier_id')
                    ->label('Dossier (demande)')
                    ->relationship('dossier', 'objet')
                    ->searchable()
                    ->preload(),
                Select::make('decision_template_id')
                    ->label('Modèle de décision')
                    ->relationship('template', 'label')
                    ->searchable()
                    ->preload(),
                TextInput::make('label')
                    ->label('Libellé de la décision')
                    ->required(),
                TextInput::make('email_subject')
                    ->label('Objet de l\'email')
                    ->maxLength(255),
                Textarea::make('email_body')
                    ->label('Contenu de l\'email')
                    ->columnSpanFull(),
                TextInput::make('annee_inscription')
                    ->label('Année d\'inscription'),
            ]);
    }
}



================================================
FILE: app/Filament/Resources/Decisions/Tables/DecisionsTable.php
================================================
<?php

namespace App\Filament\Resources\Decisions\Tables;

use App\Models\Decision;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DecisionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reunion.objet')
                    ->label('Réunion')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('dossier.doctorant.name')
                    ->label('Doctorant')
                    ->searchable(),
                TextColumn::make('dossier.objet')
                    ->label('Dossier')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),
                TextColumn::make('label')
                    ->label('Décision')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('annee_inscription')
                    ->label('Année')
                    ->searchable()
                    ->badge(),
                TextColumn::make('decideur.name')
                    ->label('Décidée par')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('annee_inscription')
                    ->label('Année d\'inscription')
                    ->options(fn () => Decision::query()
                        ->distinct()
                        ->orderBy('annee_inscription')
                        ->pluck('annee_inscription', 'annee_inscription')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}



================================================
FILE: app/Filament/Resources/DecisionTemplateResource/Pages/CreateDecisionTemplate.php
================================================
<?php

namespace App\Filament\Resources\DecisionTemplateResource\Pages;

use App\Filament\Resources\DecisionTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDecisionTemplate extends CreateRecord
{
    protected static string $resource = DecisionTemplateResource::class;
}



================================================
FILE: app/Filament/Resources/DecisionTemplateResource/Pages/EditDecisionTemplate.php
================================================
<?php

namespace App\Filament\Resources\DecisionTemplateResource\Pages;

use App\Filament\Resources\DecisionTemplateResource;
use Filament\Resources\Pages\EditRecord;

class EditDecisionTemplate extends EditRecord
{
    protected static string $resource = DecisionTemplateResource::class;
}



================================================
FILE: app/Filament/Resources/DecisionTemplateResource/Pages/ListDecisionTemplates.php
================================================
<?php

namespace App\Filament\Resources\DecisionTemplateResource\Pages;

use App\Filament\Resources\DecisionTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDecisionTemplates extends ListRecords
{
    protected static string $resource = DecisionTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}



================================================
FILE: app/Filament/Resources/Doctorat/.gitkeep
================================================
[Empty file]


================================================
FILE: app/Filament/Resources/Documents/DocumentResource.php
================================================
<?php

namespace App\Filament\Resources\Documents;

use App\Filament\Resources\Documents\Pages\CreateDocument;
use App\Filament\Resources\Documents\Pages\EditDocument;
use App\Filament\Resources\Documents\Pages\ListDocuments;
use App\Filament\Resources\Documents\RelationManagers\DocumentVersionsRelationManager;
use App\Filament\Resources\Documents\Schemas\DocumentForm;
use App\Filament\Resources\Documents\Tables\DocumentsTable;
use App\Models\Document;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $slug = 'mallette';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static string|\UnitEnum|null $navigationGroup = 'Archivage & audit';

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return DocumentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DocumentVersionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocuments::route('/'),
            'create' => CreateDocument::route('/create'),
            'edit' => EditDocument::route('/{record}/edit'),
        ];
    }
}



================================================
FILE: app/Filament/Resources/Documents/Pages/CreateDocument.php
================================================
<?php

namespace App\Filament\Resources\Documents\Pages;

use App\Filament\Resources\Documents\DocumentResource;
use App\Models\Decision;
use App\Models\Document;
use App\Models\Dossier;
use App\Models\Reunion;
use App\Models\User;
use App\Services\ArchiveService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected function handleRecordCreation(array $data): Document
    {
        $fichier = $data['fichier'] ?? null;
        unset($data['fichier']);

        if ($fichier !== null && is_string($fichier)) {
            $fichier = Storage::disk(config('archive.disk', 'public'))->path($fichier);
        }

        if ($fichier !== null && ! is_object($fichier)) {
            $fichier = new UploadedFile($fichier, basename($fichier), null, null, true);
        }

        $documentable = $this->resoudreDocumentable($data['documentable_type'], $data['documentable_id']);

        /** @var ArchiveService $archives */
        $archives = app(ArchiveService::class);

        return $archives->archiverUpload(
            $documentable,
            $data['type'],
            $data['label'],
            $fichier,
            [],
            $data['retention_months'] ?? null,
            $data['description'] ?? null,
            auth()->user(),
        );
    }

    protected function resoudreDocumentable(string $type, int $id): mixed
    {
        $model = match ($type) {
            Dossier::class => Dossier::class,
            Reunion::class => Reunion::class,
            Decision::class => Decision::class,
            default => User::class,
        };

        return $model::findOrFail($id);
    }
}



================================================
FILE: app/Filament/Resources/Documents/Pages/EditDocument.php
================================================
<?php

namespace App\Filament\Resources\Documents\Pages;

use App\Filament\Resources\Documents\DocumentResource;
use Filament\Resources\Pages\EditRecord;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}



================================================
FILE: app/Filament/Resources/Documents/Pages/ListDocuments.php
================================================
<?php

namespace App\Filament\Resources\Documents\Pages;

use App\Filament\Resources\Documents\DocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



================================================
FILE: app/Filament/Resources/Documents/RelationManagers/DocumentVersionsRelationManager.php
================================================
<?php

namespace App\Filament\Resources\Documents\RelationManagers;

use App\Models\DocumentVersion;
use App\Services\ArchiveService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class DocumentVersionsRelationManager extends RelationManager
{
    protected static string $relationship = 'versions';

    protected static ?string $title = 'Versions';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file_name')
            ->columns([
                TextColumn::make('version')
                    ->label('Version')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('file_name')
                    ->label('Fichier')
                    ->limit(40),
                TextColumn::make('mime_type')
                    ->label('Type MIME')
                    ->toggleable()
                    ->color('gray'),
                TextColumn::make('size')
                    ->label('Taille')
                    ->formatStateUsing(fn ($state) => number_format((int) $state / 1024, 1).' Ko')
                    ->toggleable(),
                TextColumn::make('hash')
                    ->label('Hash SHA-256')
                    ->limit(16)
                    ->toggleable(),
                TextColumn::make('creator.name')
                    ->label('Par'),
                TextColumn::make('created_at')
                    ->label('Ajoutée le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('version', 'desc')
            ->toolbarActions([
                Action::make('nouvelle_version')
                    ->label('Nouvelle version')
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        FileUpload::make('fichier')
                            ->label('Fichier (nouvelle version)')
                            ->disk(config('archive.disk', 'public'))
                            ->directory('uploads/tmp')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $fichier = $data['fichier'];

                        $contenu = is_object($fichier)
                            ? (string) file_get_contents($fichier->getPathname())
                            : (string) Storage::disk(config('archive.disk', 'public'))->get($fichier);

                        app(ArchiveService::class)->nouvelleVersionContenu(
                            $this->getOwnerRecord(),
                            $contenu,
                            is_object($fichier) ? $fichier->getClientOriginalName() : basename($fichier),
                            is_object($fichier) ? $fichier->getMimeType() : 'application/octet-stream',
                            [],
                            auth()->user(),
                        );

                        $this->dispatch('refresh');
                    }),
            ])
            ->recordActions([
                Action::make('telecharger')
                    ->label('Télécharger')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn (DocumentVersion $record) => Storage::disk(config('archive.disk', 'public'))->download($record->file_path, $record->file_name)),
            ])
            ->paginated(false);
    }
}



================================================
FILE: app/Filament/Resources/Documents/Schemas/DocumentForm.php
================================================
<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Models\Decision;
use App\Models\Dossier;
use App\Models\Reunion;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('documentable_type')
                    ->label('Rattaché à (type)')
                    ->options([
                        Dossier::class => 'Dossier / doctorant (mallette)',
                        Reunion::class => 'Réunion',
                        Decision::class => 'Décision',
                        User::class => 'Compte (doctorant)',
                    ])
                    ->required()
                    ->live(),
                TextInput::make('documentable_id')
                    ->label('Rattaché à (ID)')
                    ->numeric()
                    ->required(),
                Select::make('type')
                    ->label('Type de pièce')
                    ->options(collect(config('archive.types', []))
                        ->mapWithKeys(fn (string $t) => [$t => Str::headline($t)])
                        ->all())
                    ->required(),
                TextInput::make('label')
                    ->label('Libellé')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                TextInput::make('retention_months')
                    ->label('Rétention (mois) — laissez vide pour la valeur par défaut')
                    ->numeric()
                    ->minValue(1),
                FileUpload::make('fichier')
                    ->label('Pièce à archiver')
                    ->disk(config('archive.disk', 'public'))
                    ->directory('uploads/tmp')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}



================================================
FILE: app/Filament/Resources/Documents/Tables/DocumentsTable.php
================================================
<?php

namespace App\Filament\Resources\Documents\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label('Libellé')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge(),
                TextColumn::make('documentable_type')
                    ->label('Rattaché à')
                    ->formatStateUsing(fn (?string $state) => str($state)->afterLast('\\')->headline()->toString())
                    ->badge(),
                TextColumn::make('versions_count')
                    ->label('Versions')
                    ->counts('versions'),
                TextColumn::make('retention_until')
                    ->label('Rétention jusqu\'au')
                    ->date('d/m/Y'),
                TextColumn::make('creator.name')
                    ->label('Archivé par')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Archivé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}



================================================
FILE: app/Filament/Resources/Dossiers/DossierResource.php
================================================
<?php

namespace App\Filament\Resources\Dossiers;

use App\Filament\Resources\Dossiers\Pages\CreateDossier;
use App\Filament\Resources\Dossiers\Pages\EditDossier;
use App\Filament\Resources\Dossiers\Pages\ListDossiers;
use App\Filament\Resources\Dossiers\RelationManagers\DecisionsRelationManager;
use App\Filament\Resources\Dossiers\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\Dossiers\Schemas\DossierForm;
use App\Filament\Resources\Dossiers\Tables\DossiersTable;
use App\Models\Dossier;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DossierResource extends Resource
{
    protected static ?string $model = Dossier::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static string|\UnitEnum|null $navigationGroup = 'Réunions';

    protected static ?string $recordTitleAttribute = 'objet';

    public static function form(Schema $schema): Schema
    {
        return DossierForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DossiersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DocumentsRelationManager::class,
            DecisionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDossiers::route('/'),
            'create' => CreateDossier::route('/create'),
            'edit' => EditDossier::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}



================================================
FILE: app/Filament/Resources/Dossiers/Pages/CreateDossier.php
================================================
<?php

namespace App\Filament\Resources\Dossiers\Pages;

use App\Filament\Resources\Dossiers\DossierResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDossier extends CreateRecord
{
    protected static string $resource = DossierResource::class;
}



================================================
FILE: app/Filament/Resources/Dossiers/Pages/EditDossier.php
================================================
<?php

namespace App\Filament\Resources\Dossiers\Pages;

use App\Filament\Resources\Dossiers\DossierResource;
use App\Models\WorkflowDefinition;
use App\Models\WorkflowInstance;
use App\Models\WorkflowTransition;
use App\Services\WorkflowEngine;
use DomainException;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDossier extends EditRecord
{
    protected static string $resource = DossierResource::class;

    public function getHeaderActions(): array
    {
        $actions = [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];

        $instance = $this->record->activeWorkflow();

        if ($instance === null) {
            $actions[] = $this->demarrerWorkflowAction();

            return $actions;
        }

        foreach ($this->engine()->availableTransitions($instance, auth()->user()) as $transition) {
            $actions[] = $this->transitionAction($transition);
        }

        $actions[] = $this->historiqueAction($instance);

        return $actions;
    }

    protected function demarrerWorkflowAction(): Action
    {
        return Action::make('demarrerWorkflow')
            ->label('Démarrer le workflow')
            ->icon('heroicon-o-play')
            ->color('primary')
            ->form([
                Select::make('niveau')
                    ->label('Niveau d\'inscription')
                    ->options([
                        1 => '1ʳᵉ année',
                        2 => '2ᵉ année',
                        3 => '3ᵉ année',
                        4 => '4ᵉ année',
                        5 => '5ᵉ année',
                    ])
                    ->default($this->niveauParDefaut())
                    ->required(),
            ])
            ->action(function (array $data) {
                $niveau = (int) $data['niveau'];
                $code = $niveau >= 2 ? 'reinscription' : 'inscription';
                $definition = WorkflowDefinition::query()->where('code', $code)->firstOrFail();

                $this->engine()->start($definition, $this->record, ['niveau' => $niveau], auth()->user());

                $this->rafraichirNotification('Workflow démarré.');
            })
            ->visible(fn () => $this->peutPiloter());
    }

    protected function transitionAction(WorkflowTransition $transition): Action
    {
        return Action::make('transition_'.$transition->code)
            ->label($transition->label ?? $transition->code)
            ->color('primary')
            ->form($this->champsDeTransition($transition->code))
            ->modalHeading($transition->label ?? $transition->code)
            ->requiresConfirmation()
            ->action(function (array $data) use ($transition) {
                $instance = $this->record->activeWorkflow();

                if ($instance === null || $instance->current_state !== $transition->from_state) {
                    $this->rafraichirNotification(
                        'Le workflow a changé d\'état entre-temps. Actualisez la page.',
                        danger: true,
                    );

                    return;
                }

                try {
                    $this->engine()->apply($instance, $transition, $data, auth()->user());
                } catch (DomainException $e) {
                    $this->rafraichirNotification($e->getMessage(), danger: true);

                    return;
                }

                $this->rafraichirNotification('Transition « '.$transition->code.' » appliquée.');
            })
            ->visible(fn () => $this->peutPiloter());
    }

    protected function historiqueAction(WorkflowInstance $instance): Action
    {
        return Action::make('historiqueWorkflow')
            ->label('Historique du workflow')
            ->icon('heroicon-o-clock')
            ->color('gray')
            ->modalContent(view('filament.resources.dossiers.workflow-historique', [
                'trails' => $instance->auditTrails()->orderByDesc('id')->get(),
            ]));
    }

    /**
     * Champs de saisie déclarés pour les transitions qui exigent des données
     * dans le payload (ergonomie d'écran uniquement — le moteur reste maître
     * des gardes). Tout autre besoin de champ est à noter dans l'inventaire
     * de la fenêtre de schéma Phase 2.
     */
    protected function champsDeTransition(string $code): array
    {
        return match ($code) {
            'valider_recu' => [
                Toggle::make('reception_paiement')
                    ->label('Paiement vérifié sur inscription.tn')
                    ->default(false),
            ],
            default => [],
        };
    }

    protected function engine(): WorkflowEngine
    {
        return app(WorkflowEngine::class);
    }

    protected function peutPiloter(): bool
    {
        return auth()->user()?->can('update', $this->record) ?? false;
    }

    protected function rafraichirNotification(string $message, bool $danger = false): void
    {
        $notification = $danger
            ? Notification::make()->danger($message)
            : Notification::make()->success($message);

        $notification->send();

        $this->record->refresh();
        $this->fillForm();
    }

    protected function niveauParDefaut(): int
    {
        return match (trim((string) $this->record->annee_inscription)) {
            '1ere', '1ère' => 1,
            '2eme', '2ème' => 2,
            '3eme', '3ème' => 3,
            '4eme', '4ème' => 4,
            '5eme', '5ème' => 5,
            default => 2,
        };
    }
}


================================================
FILE: app/Filament/Resources/Dossiers/Pages/ListDossiers.php
================================================
<?php

namespace App\Filament\Resources\Dossiers\Pages;

use App\Filament\Pages\Imports\ImportTheses;
use App\Filament\Resources\Dossiers\DossierResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDossiers extends ListRecords
{
    protected static string $resource = DossierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importTheses')
                ->label('Importer thèses (moulinet)')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->url(fn () => ImportTheses::getUrl())
                ->visible(fn () => auth()->user()?->can('importTheses', \App\Models\Dossier::class) ?? false),
            CreateAction::make(),
        ];
    }
}



================================================
FILE: app/Filament/Resources/Dossiers/RelationManagers/DecisionsRelationManager.php
================================================
<?php

namespace App\Filament\Resources\Dossiers\RelationManagers;

use App\Models\Decision;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/**
 * Décisions du dossier visibles par année d'inscription (mallette, CDC §décisions).
 */
class DecisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'decisions';

    protected static ?string $title = 'Décisions par année d\'inscription';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('label')
                    ->label('Décision')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('template.label')
                    ->label('Modèle')
                    ->toggleable(),
                TextColumn::make('annee_inscription')
                    ->label('Année d\'inscription')
                    ->badge()
                    ->searchable(),
                TextColumn::make('decideur.name')
                    ->label('Décidée par')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('annee_inscription')
                    ->label('Année d\'inscription')
                    ->options($this->anneesOptions()),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }

    protected function anneesOptions(): array
    {
        return Decision::query()
            ->distinct()
            ->orderBy('annee_inscription')
            ->pluck('annee_inscription', 'annee_inscription')
            ->all();
    }
}



================================================
FILE: app/Filament/Resources/Dossiers/RelationManagers/DocumentsRelationManager.php
================================================
<?php

namespace App\Filament\Resources\Dossiers\RelationManagers;

use App\Models\Document;
use App\Services\ArchiveService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Mallette du dossier : arborescence des documents (types), versions, rétention.
 */
class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Pièces archivées (mallette)';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('label')
                    ->label('Libellé')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge(),
                TextColumn::make('versions_count')
                    ->label('Versions')
                    ->counts('versions')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('retention_until')
                    ->label('Rétention jusqu\'au')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->label('Archivé par')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Archivé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                Action::make('televerser')
                    ->label('Téléverser une pièce')
                    ->icon('heroicon-o-cloud-arrow-up')
                    ->form([
                        Select::make('type')
                            ->label('Type de pièce')
                            ->options($this->typesOptions())
                            ->required(),
                        TextInput::make('label')
                            ->label('Libellé')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('fichier')
                            ->label('Fichier')
                            ->disk(config('archive.disk', 'public'))
                            ->directory('uploads/tmp')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        app(ArchiveService::class)->archiverUpload(
                            $this->getOwnerRecord(),
                            $data['type'],
                            $data['label'],
                            $this->toUploadedFile($data['fichier']),
                            [],
                            null,
                            null,
                            auth()->user(),
                        );

                        $this->dispatch('refresh');
                    }),
            ])
            ->recordActions([
                Action::make('telecharger')
                    ->label('Télécharger')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Document $record) {
                        $derniere = $record->lastVersion();

                        if ($derniere === null) {
                            return;
                        }

                        return Storage::disk(config('archive.disk', 'public'))->download($derniere->file_path, $derniere->file_name);
                    }),
                Action::make('nouvelle_version')
                    ->label('Nouvelle version')
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        FileUpload::make('fichier')
                            ->label('Fichier (nouvelle version)')
                            ->disk(config('archive.disk', 'public'))
                            ->directory('uploads/tmp')
                            ->required(),
                    ])
                    ->action(function (array $data, Document $record) {
                        $fichier = $this->toUploadedFile($data['fichier']);

                        app(ArchiveService::class)->nouvelleVersionContenu(
                            $record,
                            (string) file_get_contents($fichier->getPathname()),
                            $fichier->getClientOriginalName(),
                            $fichier->getMimeType(),
                            [],
                            auth()->user(),
                        );

                        $this->dispatch('refresh');
                    }),
            ])
            ->paginated([10, 25, 50]);
    }

    protected function typesOptions(): array
    {
        return collect(config('archive.types', []))
            ->mapWithKeys(fn (string $t) => [$t => str($t)->headline()->toString()])
            ->all();
    }

    protected function toUploadedFile(mixed $fichier): UploadedFile
    {
        if ($fichier instanceof UploadedFile) {
            return $fichier;
        }

        $tmp = tempnam(sys_get_temp_dir(), 'arch');
        file_put_contents($tmp, (string) Storage::disk(config('archive.disk', 'public'))->get($fichier));

        return new UploadedFile($tmp, basename($fichier), Storage::disk(config('archive.disk', 'public'))->mimeType($fichier), null, true);
    }
}



================================================
FILE: app/Filament/Resources/Dossiers/Schemas/DossierForm.php
================================================
<?php

namespace App\Filament\Resources\Dossiers\Schemas;

use App\Enums\DossierStatut;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DossierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('doctorant_id')
                    ->label('Doctorant')
                    ->relationship('doctorant', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('commission_id')
                    ->label('Commission')
                    ->relationship('commission', 'nom')
                    ->searchable()
                    ->preload(),
                TextInput::make('objet')
                    ->label('Objet de la demande')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                Select::make('statut')
                    ->label('Statut')
                    ->options(collect(DossierStatut::cases())->mapWithKeys(
                        fn (DossierStatut $s) => [$s->value => $s->label()],
                    )->all())
                    ->default(DossierStatut::EnAttente->value)
                    ->required(),
                TextInput::make('annee_inscription')
                    ->label('Année d\'inscription'),
            ]);
    }
}



================================================
FILE: app/Filament/Resources/Dossiers/Tables/DossiersTable.php
================================================
<?php

namespace App\Filament\Resources\Dossiers\Tables;

use App\Enums\DossierStatut;
use App\Models\Dossier;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DossiersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('doctorant.name')
                    ->label('Doctorant')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('commission.nom')
                    ->label('Commission')
                    ->searchable(),
                TextColumn::make('objet')
                    ->label('Objet')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (DossierStatut $state) => $state->label())
                    ->color(fn (DossierStatut $state) => match ($state) {
                        DossierStatut::EnAttente => 'warning',
                        DossierStatut::EnCours => 'info',
                        DossierStatut::Traite => 'success',
                    }),
                TextColumn::make('workflow')
                    ->label('Workflow')
                    ->badge()
                    ->getStateUsing(fn (Dossier $record) => $record->activeWorkflow()?->current_state)
                    ->formatStateUsing(fn (?string $state) => $state === null
                        ? '—'
                        : ucfirst(str_replace('_', ' ', $state)))
                    ->color(fn (?string $state) => $state === null
                        ? 'gray'
                        : (in_array($state, ['archivee', 'diplome_disponible', 'cloturee'], true) ? 'success' : 'info'))
                    ->toggleable(),
                TextColumn::make('annee_inscription')
                    ->label('Année')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('decisions_count')
                    ->label('Décisions')
                    ->counts('decisions'),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->options(collect(DossierStatut::cases())->mapWithKeys(
                        fn (DossierStatut $s) => [$s->value => $s->label()],
                    )->all()),
                SelectFilter::make('commission_id')
                    ->label('Commission')
                    ->relationship('commission', 'nom'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}



================================================
FILE: app/Filament/Resources/EcoleDoctorales/EcoleDoctoraleResource.php
================================================
<?php

namespace App\Filament\Resources\EcoleDoctorales;

use App\Filament\Resources\EcoleDoctorales\Pages\ManageEcoleDoctorales;
use App\Models\EcoleDoctorale;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EcoleDoctoraleResource extends Resource
{
    protected static ?string $model = EcoleDoctorale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|\UnitEnum|null $navigationGroup = 'Institution';

    protected static ?string $navigationLabel = 'Écoles doctorales';

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('universite_id')
                    ->label('Université')
                    ->relationship('universite', 'nom')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('nom')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nom')
            ->columns([
                TextColumn::make('nom')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('universite.nom')
                    ->label('Université')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('etablissements_count')
                    ->label('Établissements')
                    ->counts('etablissements')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEcoleDoctorales::route('/'),
        ];
    }
}



================================================
FILE: app/Filament/Resources/EcoleDoctorales/Pages/ManageEcoleDoctorales.php
================================================
<?php

namespace App\Filament\Resources\EcoleDoctorales\Pages;

use App\Filament\Resources\EcoleDoctorales\EcoleDoctoraleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEcoleDoctorales extends ManageRecords
{
    protected static string $resource = EcoleDoctoraleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



================================================
FILE: app/Filament/Resources/Etablissements/EtablissementResource.php
================================================
<?php

namespace App\Filament\Resources\Etablissements;

use App\Filament\Resources\Etablissements\Pages\ManageEtablissements;
use App\Models\Etablissement;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EtablissementResource extends Resource
{
    protected static ?string $model = Etablissement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|\UnitEnum|null $navigationGroup = 'Institution';

    protected static ?string $navigationLabel = 'Établissements';

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                Select::make('ecole_doctorale_id')
                    ->label('École doctorale')
                    ->relationship('ecoleDoctorale', 'nom')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('directeur_id')
                    ->label('Directeur / Doyen')
                    ->relationship('directeur', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nom')
            ->columns([
                TextColumn::make('nom')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('ecoleDoctorale.nom')
                    ->label('École doctorale')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('directeur.name')
                    ->label('Directeur')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('commissions_count')
                    ->label('Commissions')
                    ->counts('commissions')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEtablissements::route('/'),
        ];
    }
}



================================================
FILE: app/Filament/Resources/Etablissements/Pages/ManageEtablissements.php
================================================
<?php

namespace App\Filament\Resources\Etablissements\Pages;

use App\Filament\Resources\Etablissements\EtablissementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEtablissements extends ManageRecords
{
    protected static string $resource = EtablissementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



================================================
FILE: app/Filament/Resources/RapportEtats/RapportEtatsResource.php
================================================
<?php

namespace App\Filament\Resources\RapportEtats;

use App\Filament\Resources\RapportEtats\Pages\CreateRapportEtat;
use App\Filament\Resources\RapportEtats\Pages\EditRapportEtat;
use App\Filament\Resources\RapportEtats\Pages\ListRapportEtats;
use App\Filament\Resources\RapportEtats\Schemas\RapportEtatForm;
use App\Filament\Resources\RapportEtats\Tables\RapportEtatsTable;
use App\Models\RapportEtat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RapportEtatsResource extends Resource
{
    protected static ?string $model = RapportEtat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static string|\UnitEnum|null $navigationGroup = 'Archivage & audit';

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return RapportEtatForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RapportEtatsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRapportEtats::route('/'),
            'create' => CreateRapportEtat::route('/create'),
            'edit' => EditRapportEtat::route('/{record}/edit'),
        ];
    }
}



================================================
FILE: app/Filament/Resources/RapportEtats/Pages/CreateRapportEtat.php
================================================
<?php

namespace App\Filament\Resources\RapportEtats\Pages;

use App\Filament\Resources\RapportEtats\RapportEtatsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRapportEtat extends CreateRecord
{
    protected static string $resource = RapportEtatsResource::class;
}



================================================
FILE: app/Filament/Resources/RapportEtats/Pages/EditRapportEtat.php
================================================
<?php

namespace App\Filament\Resources\RapportEtats\Pages;

use App\Filament\Resources\RapportEtats\RapportEtatsResource;
use App\Models\Dossier;
use App\Models\RapportEtat;
use App\Services\RapportService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRapportEtat extends EditRecord
{
    protected static string $resource = RapportEtatsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('apercu_html')
                ->label('Aperçu HTML')
                ->icon('heroicon-o-eye')
                ->form([$this->dossierPicker()])
                ->action(function (array $data, RapportEtat $record) {
                    $this->redirect(route('admin.rapports-etats.apercu', [
                        'etat' => $record,
                        'dossier' => $data['dossier_id'],
                    ]));
                }),
            Action::make('generer_pdf')
                ->label('Générer PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->form([$this->dossierPicker()])
                ->action(function (array $data, RapportEtat $record) {
                    $this->redirect(route('admin.rapports-etats.pdf', [
                        'etat' => $record,
                        'dossier' => $data['dossier_id'],
                    ]));
                }),
            Action::make('impression_masse')
                ->label('Impression en masse (batch)')
                ->icon('heroicon-o-printer')
                ->form([
                    Select::make('dossiers_ids')
                        ->label('Dossiers cibles (vide = les N premiers)')
                        ->multiple()
                        ->searchable()
                        ->options(fn () => $this->dossierOptions()),
                    TextInput::make('limite')
                        ->label('Limite si aucune sélection')
                        ->numeric()
                        ->default(50)
                        ->minValue(1),
                ])
                ->requiresConfirmation()
                ->action(function (array $data, RapportEtat $record) {
                    $query = Dossier::query()
                        ->whereNotNull('annee_inscription')
                        ->with('doctorant');

                    $dossiers = ! empty($data['dossiers_ids'])
                        ? $query->whereIn('id', $data['dossiers_ids'])->get()
                        : $query->orderBy('id')->limit((int) ($data['limite'] ?? 50))->get();

                    $resultat = app(RapportService::class)->genererEnMasse($record, $dossiers, auth()->user());

                    Notification::make()
                        ->success(sprintf(
                            'Impression en masse terminée : %d/%d états générés et archivés dans les mallettes.',
                            $resultat['reussites'],
                            $resultat['total'],
                        ))
                        ->send();
                }),
        ];
    }

    protected function dossierPicker(): Select
    {
        return Select::make('dossier_id')
            ->label('Dossier (doctorant) cible')
            ->searchable()
            ->required()
            ->options(fn () => $this->dossierOptions());
    }

    protected function dossierOptions(): array
    {
        return Dossier::query()
            ->with('doctorant')
            ->latest('id')
            ->limit(500)
            ->get()
            ->mapWithKeys(fn (Dossier $dossier) => [
                $dossier->getKey() => ($dossier->doctorant?->name ?? '—').' · '.$dossier->objet,
            ])
            ->all();
    }
}



================================================
FILE: app/Filament/Resources/RapportEtats/Pages/ListRapportEtats.php
================================================
<?php

namespace App\Filament\Resources\RapportEtats\Pages;

use App\Filament\Resources\RapportEtats\RapportEtatsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRapportEtats extends ListRecords
{
    protected static string $resource = RapportEtatsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



================================================
FILE: app/Filament/Resources/RapportEtats/Schemas/RapportEtatForm.php
================================================
<?php

namespace App\Filament\Resources\RapportEtats\Schemas;

use App\Enums\RapportEtatType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RapportEtatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->label('Libellé de l\'état')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->label('Nature')
                    ->options(collect(RapportEtatType::cases())->mapWithKeys(
                        fn (RapportEtatType $t) => [$t->value => $t->label()],
                    )->all())
                    ->default(RapportEtatType::Etat->value)
                    ->required(),
                Select::make('pv_type')
                    ->label('Type de rendu (moteur du package)')
                    ->options(collect(config('pv-module.types', ['pv']))
                        ->push('rapport', 'etat')
                        ->filter()
                        ->mapWithKeys(fn (string $t) => [$t => $t])
                        ->all())
                    ->helperText('Sélectionne le PvTemplate actif utilisé par le package (PvTemplate + PdfService).')
                    ->default('pv')
                    ->required(),
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                Textarea::make('en_tete')
                    ->label('En-tête (marge haute des pages)')
                    ->helperText('Peut contenir le logo, la mention officielle, etc. Rendu via mPDF.')
                    ->rows(3)
                    ->columnSpanFull(),
                Select::make('orientation')
                    ->label('Orientation')
                    ->options([
                        'portrait' => 'Portrait',
                        'landscape' => 'Paysage',
                    ])
                    ->default('portrait')
                    ->required(),
                Select::make('format_papier')
                    ->label('Format papier')
                    ->options(['A4' => 'A4'])
                    ->helperText('Seul A4 est livré avec le moteur ; tout autre format implique une extension du package (sous-prompt P4).')
                    ->default('A4'),
                TextInput::make('marges.top')
                    ->label('Marge haute (mm)')
                    ->numeric()
                    ->default(20),
                TextInput::make('marges.bottom')
                    ->label('Marge basse (mm)')
                    ->numeric()
                    ->default(20),
                TextInput::make('marges.left')
                    ->label('Marge gauche (mm)')
                    ->numeric()
                    ->default(20),
                TextInput::make('marges.right')
                    ->label('Marge droite (mm)')
                    ->numeric()
                    ->default(20),
                Toggle::make('is_active')
                    ->label('État actif')
                    ->default(true),
            ]);
    }
}



================================================
FILE: app/Filament/Resources/RapportEtats/Tables/RapportEtatsTable.php
================================================
<?php

namespace App\Filament\Resources\RapportEtats\Tables;

use App\Enums\RapportEtatType;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RapportEtatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label('État / rapport')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('type')
                    ->label('Nature')
                    ->badge()
                    ->formatStateUsing(fn (RapportEtatType $state) => $state->label()),
                TextColumn::make('pv_type')
                    ->label('Type de rendu')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('orientation')
                    ->label('Orientation')
                    ->badge()
                    ->color('gray'),
                IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
                TextColumn::make('creator.name')
                    ->label('Créé par')
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Mis à jour le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}



================================================
FILE: app/Filament/Resources/Reunions/ReunionResource.php
================================================
<?php

namespace App\Filament\Resources\Reunions;

use App\Filament\Resources\Reunions\Pages\CreateReunion;
use App\Filament\Resources\Reunions\Pages\EditReunion;
use App\Filament\Resources\Reunions\Pages\ListReunions;
use App\Filament\Resources\Reunions\Pages\ManageReunionDecisions;
use App\Filament\Resources\Reunions\Pages\ManageReunionPresences;
use App\Filament\Resources\Reunions\Pages\ReunionCorbeille;
use App\Filament\Resources\Reunions\Schemas\ReunionForm;
use App\Filament\Resources\Reunions\Tables\ReunionsTable;
use App\Models\Reunion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReunionResource extends Resource
{
    protected static ?string $model = Reunion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|\UnitEnum|null $navigationGroup = 'Réunions';

    protected static ?string $recordTitleAttribute = 'objet';

    public static function form(Schema $schema): Schema
    {
        return ReunionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReunionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReunions::route('/'),
            'create' => CreateReunion::route('/create'),
            'edit' => EditReunion::route('/{record}/edit'),
            'presences' => ManageReunionPresences::route('/{record}/presences'),
            'decisions' => ManageReunionDecisions::route('/{record}/decisions'),
            'corbeille' => ReunionCorbeille::route('/corbeille'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}



================================================
FILE: app/Filament/Resources/Reunions/.gitkeep
================================================
[Empty file]


================================================
FILE: app/Filament/Resources/Reunions/Pages/CreateReunion.php
================================================
<?php

namespace App\Filament\Resources\Reunions\Pages;

use App\Filament\Resources\Reunions\ReunionResource;
use App\Services\ReunionService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateReunion extends CreateRecord
{
    protected static string $resource = ReunionResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $reunion = app(ReunionService::class)->create($data, auth()->user());

        $this->record = $reunion;

        return $reunion;
    }

    protected function getRedirectUrl(): string
    {
        return ReunionResource::getUrl('edit', ['record' => $this->record]);
    }
}



================================================
FILE: app/Filament/Resources/Reunions/Pages/EditReunion.php
================================================
<?php

namespace App\Filament\Resources\Reunions\Pages;

use App\Enums\ReunionStatut;
use App\Filament\Resources\Reunions\ReunionResource;
use App\Models\Reunion;
use App\Services\DecisionService;
use App\Services\ReunionService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class EditReunion extends EditRecord
{
    protected static string $resource = ReunionResource::class;

    protected function getHeaderActions(): array
    {
        $reunion = $this->record;

        return [
            Action::make('presences')
                ->label('Présences')
                ->icon('heroicon-o-user-group')
                ->url(fn () => ReunionResource::getUrl('presences', ['record' => $reunion]))
                ->visible(fn () => auth()->user()->can('enregistrerPresence', $reunion)),

            Action::make('decisions')
                ->label('Décisions')
                ->icon('heroicon-o-clipboard-document-check')
                ->url(fn () => ReunionResource::getUrl('decisions', ['record' => $reunion]))
                ->visible(fn () => auth()->user()->can('gererDecisions', $reunion)),

            Action::make('export_decisions')
                ->label('Exporter les décisions (CSV)')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (Reunion $record) {
                    $csv = app(DecisionService::class)->exportCsv($record);

                    return StreamedResponse::create(
                        static fn () => print ($csv),
                        200,
                        [
                            'Content-Type' => 'text/csv; charset=UTF-8',
                            'Content-Disposition' => 'attachment; filename="decisions_reunion_'.$record->getKey().'_'.now()->format('Ymd').'.csv"',
                        ],
                    );
                })
                ->visible(fn (Reunion $record) => $record->decisions()->exists() && auth()->user()->can('gererDecisions', $record)),

            ActionGroup::make([
                $this->statutAction('planifier', 'Planifier', 'heroicon-o-paper-airplane'),
                $this->statutAction('demarrer', 'Démarrer la réunion', 'heroicon-o-play'),
                $this->statutAction('terminer', 'Terminer', 'heroicon-o-check-circle'),
                $this->statutAction('annuler', 'Annuler', 'heroicon-o-x-circle', true),
            ])->label('Statut')
                ->icon('heroicon-o-arrows-right-left')
                ->visible(fn () => auth()->user()->can('update', $reunion)),

            Action::make('generer_pv')
                ->label('Générer le PV')
                ->icon('heroicon-o-document-text')
                ->form([
                    Textarea::make('contenu')
                        ->label('Contenu du PV (optionnel)')
                        ->helperText('Laissez vide pour utiliser le contenu par défaut (objet + décisions).'),
                ])
                ->requiresConfirmation()
                ->visible(fn () => auth()->user()->can('genererPv', $reunion))
                ->action(function (array $data, Reunion $record) {
                    try {
                        $pv = app(ReunionService::class)->genererPv($record, auth()->user(), $data['contenu'] ?? []);

                        Notification::make()
                            ->success('PV généré et envoyé pour signature aux présents.')
                            ->send();

                        $this->redirect('/admin/documents');
                    } catch (Throwable $e) {
                        Notification::make()->danger($e->getMessage())->send();
                    }
                }),
        ];
    }

    protected function statutAction(string $method, string $label, string $icon, bool $danger = false): Action
    {
        return Action::make('statut_'.$method)
            ->label($label)
            ->icon($icon)
            ->color($danger ? 'danger' : 'primary')
            ->requiresConfirmation()
            ->visible(function (Reunion $record) use ($method, $danger) {
                $cible = match ($method) {
                    'planifier' => ReunionStatut::Planifiee,
                    'demarrer' => ReunionStatut::EnCours,
                    'terminer' => ReunionStatut::Terminee,
                    'annuler' => ReunionStatut::Annulee,
                };

                if ($danger) {
                    return $record->statut !== $cible && auth()->user()->can('annuler', $record);
                }

                return $record->statut !== $cible
                    && $record->statut->canTransitionTo($cible)
                    && auth()->user()->can($method, $record);
            })
            ->action(function (Reunion $record) use ($method) {
                $cible = match ($method) {
                    'planifier' => ReunionStatut::Planifiee,
                    'demarrer' => ReunionStatut::EnCours,
                    'terminer' => ReunionStatut::Terminee,
                    'annuler' => ReunionStatut::Annulee,
                };

                app(ReunionService::class)->transition($record, $cible, auth()->user());

                Notification::make()->success('Réunion mise à jour : '.$cible->label().'.')->send();
            });
    }
}



================================================
FILE: app/Filament/Resources/Reunions/Pages/ListReunions.php
================================================
<?php

namespace App\Filament\Resources\Reunions\Pages;

use App\Filament\Resources\Reunions\ReunionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReunions extends ListRecords
{
    protected static string $resource = ReunionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



================================================
FILE: app/Filament/Resources/Reunions/Pages/ManageReunionDecisions.php
================================================
<?php

namespace App\Filament\Resources\Reunions\Pages;

use App\Filament\Resources\Reunions\ReunionResource;
use App\Models\Decision;
use App\Models\DecisionTemplate;
use App\Models\Dossier;
use App\Models\Reunion;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;

class ManageReunionDecisions extends Page implements HasForms
{
    use InteractsWithForms;
    use InteractsWithRecord;

    protected static string $resource = ReunionResource::class;

    protected string $view = 'filament.resources.reunions.pages.manage-reunion-decisions';

    protected static ?string $title = 'Décisions de réunion';

    public ?array $formData = [];

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        abort_unless(auth()->user()?->can('gererDecisions', $this->record), 403);

        $this->loadForm();
    }

    public function loadForm(): void
    {
        /** @var Reunion $reunion */
        $reunion = $this->record;
        $existingDecisions = $reunion->decisions()->get()->keyBy('dossier_id');

        $items = [];
        foreach ($reunion->dossiers as $dossier) {
            $decision = $existingDecisions->get($dossier->getKey());
            $items[] = [
                'dossier_id' => $dossier->getKey(),
                'decision_template_id' => $decision?->decision_template_id ?? null,
                'annee_inscription' => $decision?->annee_inscription ?? $dossier->annee_inscription ?? '',
            ];
        }

        if (empty($items)) {
            $items[] = [
                'dossier_id' => null,
                'decision_template_id' => null,
                'annee_inscription' => '',
            ];
        }

        $this->form->fill(['decisions' => $items]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Repeater::make('decisions')
                    ->schema([
                        Select::make('dossier_id')
                            ->label('Dossier')
                            ->options(fn () => Dossier::pluck('objet', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('decision_template_id')
                            ->label('Modèle de décision')
                            ->options(fn () => DecisionTemplate::where('is_active', true)->pluck('label', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (! $state) {
                                    return;
                                }
                                $tpl = DecisionTemplate::find($state);
                                if ($tpl) {
                                    $set('label_snapshot', $tpl->label);
                                    $set('email_subject', $tpl->email_subject);
                                }
                            }),
                        TextInput::make('label_snapshot')
                            ->label('Libellé')
                            ->disabled(),
                        TextInput::make('email_subject')
                            ->label('Objet email')
                            ->disabled(),
                        TextInput::make('annee_inscription')
                            ->label('Année d\'inscription'),
                    ])
                    ->columns(2),
            ]);
    }

    public function save(): void
    {
        /** @var Reunion $reunion */
        $reunion = $this->record;
        $data = $this->form->getState();

        foreach ($data['decisions'] ?? [] as $row) {
            if (empty($row['dossier_id']) || empty($row['decision_template_id'])) {
                continue;
            }

            $template = DecisionTemplate::find($row['decision_template_id']);

            if (! $template) {
                continue;
            }

            Decision::updateOrCreate(
                [
                    'reunion_id' => $reunion->getKey(),
                    'dossier_id' => $row['dossier_id'],
                ],
                [
                    'decision_template_id' => $template->getKey(),
                    'label' => $template->label,
                    'email_subject' => $template->email_subject,
                    'email_body' => $template->email_body,
                    'annee_inscription' => $row['annee_inscription'] ?? $template->commission?->discipline,
                    'decided_by' => auth()->id(),
                ],
            );

            Dossier::whereKey($row['dossier_id'])->update(['statut' => 'traite']);
        }

        Notification::make()->success('Décisions enregistrées avec succès.')->send();
    }
}



================================================
FILE: app/Filament/Resources/Reunions/Pages/ManageReunionPresences.php
================================================
<?php

namespace App\Filament\Resources\Reunions\Pages;

use App\Enums\PresenceStatut;
use App\Filament\Resources\Reunions\ReunionResource;
use App\Models\Presence;
use App\Models\Reunion;
use App\Models\User;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;

class ManageReunionPresences extends Page implements HasForms
{
    use InteractsWithForms;
    use InteractsWithRecord;

    protected static string $resource = ReunionResource::class;

    protected string $view = 'filament.resources.reunions.pages.manage-reunion-presences';

    protected static ?string $title = 'Enregistrement des présences';

    public ?array $formData = [];

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        abort_unless(auth()->user()?->can('enregistrerPresence', $this->record), 403);

        $this->loadForm();
    }

    public function loadForm(): void
    {
        /** @var Reunion $reunion */
        $reunion = $this->record;
        $invites = $reunion->invitations()->whereNotNull('participant_id')->get();
        $existingPresences = $reunion->presences()->get()->keyBy('participant_id');

        $items = [];
        foreach ($invites as $invitation) {
            $pid = $invitation->participant_id;
            $presence = $existingPresences->get($pid);
            $items[$pid] = [
                'participant_id' => $pid,
                'statut' => $presence?->statut?->value ?? 'absent',
                'note' => $presence?->note ?? '',
            ];
        }

        $this->form->fill(['presences' => $items]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Repeater::make('presences')
                    ->schema([
                        Select::make('participant_id')
                            ->label('Participant')
                            ->disabled()
                            ->options(fn () => User::pluck('name', 'id')),
                        Select::make('statut')
                            ->label('Présence')
                            ->options(collect(PresenceStatut::cases())->mapWithKeys(
                                fn (PresenceStatut $s) => [$s->value => $s->label()],
                            )->all())
                            ->default('absent')
                            ->required(),
                        Textarea::make('note')
                            ->label('Note')
                            ->rows(1),
                    ])
                    ->columns(3)
                    ->live()
                    ->itemLabel(fn (array $state): ?string => User::find($state['participant_id'])?->name),
            ]);
    }

    public function save(): void
    {
        /** @var Reunion $reunion */
        $reunion = $this->record;
        $data = $this->form->getState();

        foreach ($data['presences'] ?? [] as $row) {
            if (empty($row['participant_id'])) {
                continue;
            }

            Presence::updateOrCreate(
                [
                    'reunion_id' => $reunion->getKey(),
                    'participant_id' => $row['participant_id'],
                ],
                [
                    'statut' => $row['statut'] ?? 'absent',
                    'note' => $row['note'] ?? null,
                    'recorded_by' => auth()->id(),
                ],
            );
        }

        Notification::make()->success('Présences enregistrées avec succès.')->send();
    }
}



================================================
FILE: app/Filament/Resources/Reunions/Pages/ReunionCorbeille.php
================================================
<?php

namespace App\Filament\Resources\Reunions\Pages;

use App\Enums\ReunionStatut;
use App\Enums\UserRole;
use App\Filament\Resources\Reunions\ReunionResource;
use App\Models\Reunion;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReunionCorbeille extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = ReunionResource::class;

    protected string $view = 'filament.resources.reunions.pages.reunion-corbeille';

    protected static ?string $title = 'Corbeille des réunions';

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()?->role === UserRole::Admin;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Reunion::query()->onlyTrashed())
            ->recordTitleAttribute('objet')
            ->columns([
                TextColumn::make('commission.nom')
                    ->label('Commission'),
                TextColumn::make('objet')
                    ->label('Objet'),
                TextColumn::make('date_debut')
                    ->label('Début')
                    ->dateTime('d/m/Y H:i'),
                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (ReunionStatut $state) => $state->label()),
                TextColumn::make('deleted_at')
                    ->label('Supprimée le')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->recordActions([
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->paginated([10, 25, 50]);
    }
}



================================================
FILE: app/Filament/Resources/Reunions/Schemas/ReunionForm.php
================================================
<?php

namespace App\Filament\Resources\Reunions\Schemas;

use App\Enums\DossierStatut;
use App\Enums\ReunionStatut;
use App\Enums\ReunionType;
use App\Filament\Resources\Reunions\Pages\CreateReunion;
use App\Models\Dossier;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReunionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('commission_id')
                    ->label('Commission (discipline)')
                    ->relationship('commission', 'nom')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Select $component, $state, Schema $live) {
                        $live->getComponent('dossiers')?->options(
                            Dossier::query()
                                ->where('commission_id', $state)
                                ->where('statut', DossierStatut::EnAttente->value)
                                ->pluck('objet', 'id'),
                        );
                    })
                    ->helperText('Les membres de la commission seront ajoutés automatiquement à la création.'),
                Select::make('dossiers')
                    ->label('Dossiers à l\'ordre du jour')
                    ->relationship('dossiers', 'objet')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options(fn () => Dossier::query()
                        ->where('statut', DossierStatut::EnAttente->value)
                        ->pluck('objet', 'id'))
                    ->helperText('Demandes en attente de décision (auto-alimentées par l\'administratif).')
                    ->columnSpanFull(),
                TextInput::make('objet')
                    ->label('Objet')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                Select::make('odj_template_id')
                    ->label('Modèle d’ordre du jour')
                    ->relationship('odjTemplate', 'label')
                    ->searchable()
                    ->preload(),
                Textarea::make('ordre_du_jour')
                    ->label('Ordre du jour')
                    ->columnSpanFull()
                    ->rows(8),
                DateTimePicker::make('date_debut')
                    ->label('Date et heure de début')
                    ->required(),
                DateTimePicker::make('date_fin')
                    ->label('Date et heure de fin')
                    ->after('date_debut'),
                TextInput::make('lieu')
                    ->label('Lieu')
                    ->maxLength(255),
                TextInput::make('lien')
                    ->label('Lien visio')
                    ->url()
                    ->maxLength(255),
                Select::make('type')
                    ->label('Type')
                    ->options(ReunionType::class)
                    ->default(ReunionType::Presentiel->value)
                    ->required(),
                Select::make('statut')
                    ->label('Statut')
                    ->options(ReunionStatut::class)
                    ->default(ReunionStatut::Brouillon->value)
                    ->required()
                    ->disabled(fn ($livewire) => $livewire instanceof CreateReunion),
            ]);
    }
}



================================================
FILE: app/Filament/Resources/Reunions/Tables/ReunionsTable.php
================================================
<?php

namespace App\Filament\Resources\Reunions\Tables;

use App\Enums\ReunionStatut;
use App\Enums\ReunionType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ReunionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('commission.nom')
                    ->label('Commission')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('objet')
                    ->label('Objet')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('date_debut')
                    ->label('Début')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('date_fin')
                    ->label('Fin')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('lieu')
                    ->label('Lieu')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (ReunionType $state) => $state->label()),
                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (ReunionStatut $state) => $state->label())
                    ->color(fn (ReunionStatut $state) => match ($state) {
                        ReunionStatut::Brouillon => 'gray',
                        ReunionStatut::Planifiee => 'info',
                        ReunionStatut::EnCours => 'success',
                        ReunionStatut::Terminee => 'primary',
                        ReunionStatut::Annulee => 'danger',
                    }),
                TextColumn::make('invitations_count')
                    ->label('Invités')
                    ->counts('invitations'),
                TextColumn::make('decisions_count')
                    ->label('Décisions')
                    ->counts('decisions'),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label('Supprimé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->options(collect(ReunionStatut::cases())->mapWithKeys(
                        fn (ReunionStatut $s) => [$s->value => $s->label()],
                    )->all()),
                SelectFilter::make('commission_id')
                    ->label('Commission')
                    ->relationship('commission', 'nom')
                    ->searchable(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}



================================================
FILE: app/Filament/Resources/Universites/UniversiteResource.php
================================================
<?php

namespace App\Filament\Resources\Universites;

use App\Filament\Resources\Universites\Pages\ManageUniversites;
use App\Models\Universite;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UniversiteResource extends Resource
{
    protected static ?string $model = Universite::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Institution';

    protected static ?string $navigationLabel = 'Universités';

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->label('Code')
                    ->maxLength(20),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nom')
            ->columns([
                TextColumn::make('nom')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('ecole_doctorales_count')
                    ->label('Écoles doctorales')
                    ->counts('ecoleDoctorales')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUniversites::route('/'),
        ];
    }
}



================================================
FILE: app/Filament/Resources/Universites/Pages/ManageUniversites.php
================================================
<?php

namespace App\Filament\Resources\Universites\Pages;

use App\Filament\Resources\Universites\UniversiteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUniversites extends ManageRecords
{
    protected static string $resource = UniversiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}



================================================
FILE: app/Filament/Resources/Users/UserResource.php
================================================
<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|\UnitEnum|null $navigationGroup = 'Institution';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}


================================================
FILE: app/Filament/Resources/Users/Pages/ListUsers.php
================================================
<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Pages\Imports\ImportEnseignants;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importEnseignants')
                ->label('Importer enseignants')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->url(fn () => ImportEnseignants::getUrl())
                ->visible(fn () => auth()->user()?->can('create', \App\Models\User::class) ?? false),
        ];
    }
}


================================================
FILE: app/Filament/Resources/Users/Tables/UsersTable.php
================================================
<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\UserRole;
use App\Filament\Actions\SendEmailBulkAction;
use App\Filament\Exports\UserExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('role')
                    ->label('Rôle')
                    ->badge()
                    ->formatStateUsing(fn (?UserRole $state) => $state?->label() ?? '—')
                    ->color(fn (?UserRole $state) => match ($state) {
                        UserRole::Admin => 'danger',
                        UserRole::PresidentCommission => 'warning',
                        UserRole::DirecteurThese => 'info',
                        UserRole::Doctorant => 'success',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('commissions_count')
                    ->label('Commissions')
                    ->counts('commissions')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Rôle')
                    ->options(collect(UserRole::cases())->mapWithKeys(
                        fn (UserRole $r) => [$r->value => $r->label()],
                    )->all()),
                SelectFilter::make('commission_id')
                    ->label('Commission')
                    ->relationship('commissions', 'nom'),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Exporter CSV')
                    ->exporter(UserExporter::class)
                    ->formats([ExportFormat::Csv]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    SendEmailBulkAction::make(),
                ]),
            ])
            ->recordActions([])
            ->paginated([10, 25, 50]);
    }
}


================================================
FILE: app/Http/Controllers/Controller.php
================================================
<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;
}



================================================
FILE: app/Http/Controllers/RapportController.php
================================================
<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\RapportEtat;
use App\Services\RapportService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Rendu « rapport / état » (HTML ou PDF) via le moteur du package.
 * Aucune sortie maison : tout passe par PvTemplate + PdfService.
 */
class RapportController extends Controller
{
    public function apercu(RapportEtat $etat, Request $request): Response
    {
        $this->authorize('generer', $etat);

        $dossier = Dossier::findOrFail((int) $request->query('dossier'));

        $html = app(RapportService::class)->apercuHtml($etat, $dossier, $request->user());

        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8');
    }

    public function pdf(RapportEtat $etat, Request $request): Response
    {
        $this->authorize('generer', $etat);

        $dossier = Dossier::findOrFail((int) $request->query('dossier'));

        $resultat = app(RapportService::class)->generer(
            $etat,
            $dossier,
            $request->user(),
            false,
        );

        return response($resultat['pdf'])
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="'.Str::slug($etat->label).'-'.Str::slug($dossier->objet).'.pdf"');
    }
}



================================================
FILE: app/Imports/BaseImport.php
================================================
<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use SimpleXMLElement;

/**
 * Moteur d'import « moulinet » — P9 §6.
 *
 * Règles respectées :
 *  - validate-then-commit : aucune ligne en erreur n'est écrite en base.
 *  - tous les commits se font dans une seule transaction.
 *  - tout schéma de fichier (CSV, XLSX, XML, JSON) est accepté.
 */
abstract class BaseImport
{
    /** @var array<string, string> Alias d'en-têtes source (insensible à la casse) => champ cible. */
    abstract protected function aliases(): array;

    /** Règles de validation Laravel appliquées au champ normalisé. */
    abstract protected function rules(): array;

    /** Écrit une ligne valide en base (appelé dans la transaction). */
    abstract protected function createRecord(array $data): void;

    abstract public function label(): string;

    /**
     * Génère un rapport CSV des lignes en erreur (séparateur ';').
     */
    public function errorReportCsv(ImportResult $result): string
    {
        $handle = fopen('php://temp', 'r+');
        $labels = array_keys($this->aliases());
        fputcsv($handle, ['Ligne', ...$labels, 'Erreurs'], ';');

        foreach ($result->invalidRows() as $row) {
            $values = [];
            foreach ($this->aliases() as $field) {
                $values[] = $row->data[$field] ?? '';
            }

            fputcsv($handle, [
                $row->rowNumber,
                ...$values,
                implode(' | ', $row->errors),
            ], ';');
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }

    /**
     * Parse un fichier en un tableau de lignes associatives (clé = en-tête brut).
     *
     * @return array<int, array<string, mixed>>
     */
    public function parseFile(string $path, string $extension): array
    {
        return match (strtolower($extension)) {
            'csv', 'txt' => $this->parseCsv($path),
            'xlsx', 'xls' => $this->parseXlsx($path),
            'xml' => $this->parseXml($path),
            'json' => $this->parseJson($path),
            default => throw new \InvalidArgumentException("Format non supporté : {$extension}"),
        };
    }

    /**
     * Analyse complète : normalise puis valide chaque ligne.
     */
    public function analyze(string $path, string $extension): ImportResult
    {
        $result = new ImportResult;
        $result->rows = $this->buildRows($this->parseFile($path, $extension));

        return $result;
    }

    /**
     * Intégration validate-then-commit : analyse puis écriture transactionnelle
     * des seules lignes valides. Les lignes en erreur ne sont jamais écrites.
     */
    public function commit(string $path, string $extension): ImportResult
    {
        $result = $this->analyze($path, $extension);
        $valid = $result->validRows();

        if ($valid !== []) {
            DB::transaction(function () use ($valid, &$result): void {
                foreach ($valid as $row) {
                    try {
                        $this->createRecord($row->data);
                        $result->committed++;
                    } catch (\Throwable $e) {
                        Log::error('Import — erreur d\'intégration ligne '.$row->rowNumber, [
                            'importer' => static::class,
                            'message' => $e->getMessage(),
                        ]);
                        // La ligne échouée n'est pas validée mais n'invalide pas le lot.
                    }
                }
            });
        }

        return $result;
    }

    /**
     * Construit les lignes normalisées + validées.
     *
     * @param  array<int, array<string, mixed>>  $rawRows
     * @return array<int, ImportRow>
     */
    protected function buildRows(array $rawRows): array
    {
        $rows = [];
        $aliases = $this->normalizedAliases();

        foreach ($rawRows as $index => $raw) {
            $data = $this->normalizeRow($raw, $aliases);
            $errors = $this->validateRow($data);

            $rows[] = new ImportRow(
                rowNumber: $index + 2, // 1 = en-tête
                data: $data,
                errors: $errors,
            );
        }

        return $rows;
    }

    /**
     * @return array<string, string> Sans accent, minuscules.
     */
    protected function normalizedAliases(): array
    {
        $aliases = [];
        foreach ($this->aliases() as $label => $field) {
            $aliases[static::normalizeKey($label)] = $field;
            $aliases[static::normalizeKey($field)] = $field;
        }

        return $aliases;
    }

    /**
     * @param  array<string, mixed>  $raw
     * @param  array<string, string>  $aliases
     * @return array<string, mixed>
     */
    protected function normalizeRow(array $raw, array $aliases): array
    {
        $data = [];
        foreach ($raw as $key => $value) {
            $field = $aliases[static::normalizeKey((string) $key)] ?? null;
            if ($field === null) {
                continue;
            }

            $data[$field] = static::cleanValue($value);
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<int, string>
     */
    protected function validateRow(array $data): array
    {
        $validator = Validator::make($data, $this->rules());

        return $validator->errors()->all();
    }

    protected static function normalizeKey(string $key): string
    {
        $key = mb_strtolower(trim($key));
        $transliterator = \Transliterator::create('Any-Latin; Latin-ASCII');

        return $transliterator ? $transliterator->transliterate($key) : $key;
    }

    protected static function cleanValue(mixed $value): mixed
    {
        if (is_string($value)) {
            return trim($value);
        }

        return $value;
    }

    protected static function lookupId(string $table, string $field, mixed $value): ?int
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        return DB::table($table)->where($field, $value)->value('id');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parseCsv(string $path): array
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new \RuntimeException("Ouverture impossible : {$path}");
        }

        $lines = [];
        while (($line = fgets($handle)) !== false) {
            $lines[] = $line;
        }
        fclose($handle);

        if ($lines === []) {
            return [];
        }

        $first = ltrim($lines[0], "\xEF\xBB\xBF");
        $delimiter = substr_count($first, ';') >= substr_count($first, ',') ? ';' : ',';

        $handle = fopen('php://temp', 'r+');
        foreach ($lines as $line) {
            fwrite($handle, $line);
        }
        rewind($handle);

        $rows = [];
        $header = null;
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if ($header === null) {
                $header = array_map(fn ($h) => trim((string) $h), $row);

                continue;
            }

            $rows[] = array_combine($header, array_map(fn ($c) => (string) $c, $row));
        }
        fclose($handle);

        return array_values(array_filter($rows));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parseXlsx(string $path): array
    {
        $reader = new XlsxReader;

        try {
            $reader->open($path);

            $header = null;
            $rows = [];
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    $values = array_map(
                        fn ($cell) => $cell instanceof \OpenSpout\Common\Entity\Cell ? $cell->getValue() : $cell,
                        $row->getCells(),
                    );
                    $values = array_map(fn ($v) => is_scalar($v) ? (string) $v : '', $values);

                    if ($header === null) {
                        $header = array_map(fn ($h) => trim($h), $values);

                        continue;
                    }

                    $rows[] = array_combine($header, $values);
                }
            }

            return array_values(array_filter($rows));
        } finally {
            $reader->close();
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parseXml(string $path): array
    {
        $xml = simplexml_load_file($path);
        if ($xml === false) {
            throw new \RuntimeException("XML illisible : {$path}");
        }

        $rows = [];
        foreach ($xml->children() as $child) {
            $row = [];
            foreach ($child->children() as $field) {
                $row[$field->getName()] = (string) $field;
            }

            if ($row !== []) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parseJson(string $path): array
    {
        $decoded = json_decode((string) file_get_contents($path), true);

        if (! is_array($decoded)) {
            throw new \RuntimeException("JSON illisible : {$path}");
        }

        // Liste directe d'objets.
        if (array_is_list($decoded) && $decoded !== []) {
            return array_values($decoded);
        }

        // Objet contenant une liste (ex. {"theses": [...]}).
        foreach ($decoded as $value) {
            if (is_array($value) && array_is_list($value)) {
                return array_values($value);
            }
        }

        return [];
    }
}


================================================
FILE: app/Imports/EnseignantImport.php
================================================
<?php

namespace App\Imports;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Import des enseignants depuis CSV / XLSX / XML / JSON (P9 §6.6 / CDC §gestion enseignants).
 * Champs mappés : nom, email, rôle, grade, structure de recherche, établissement.
 * Validate-then-commit : un email en doublon ou un rôle inconnu invalide la ligne.
 */
class EnseignantImport extends BaseImport
{
    protected function aliases(): array
    {
        return [
            'Nom' => 'name',
            'Nom complet' => 'name',
            'Prénom et nom' => 'name',
            'Email' => 'email',
            'Adresse email' => 'email',
            'Rôle' => 'role',
            'Grade' => 'grade',
            'Structure de recherche' => 'structure_recherche',
            'Structure' => 'structure_recherche',
            'Établissement' => 'etablissement',
            'Etablissement' => 'etablissement',
            'Étab' => 'etablissement',
            'Etab' => 'etablissement',
        ];
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'role' => ['required', Rule::enum(UserRole::class)],
            'grade' => ['nullable', 'string'],
            'structure_recherche' => ['nullable', 'string'],
            'etablissement' => ['nullable', Rule::exists('uma_etablissements', 'nom')],
        ];
    }

    protected function createRecord(array $data): void
    {
        User::create([
            'name' => $data['name'],
            'email' => mb_strtolower($data['email']),
            'password' => Str::random(16),
            'role' => $data['role'],
            'grade' => $data['grade'] ?? null,
            'structure_recherche' => $data['structure_recherche'] ?? null,
            'etablissement_id' => $data['etablissement'] !== null && $data['etablissement'] !== ''
                ? static::lookupId('uma_etablissements', 'nom', $data['etablissement'])
                : null,
        ]);
    }

    public function label(): string
    {
        return 'Enseignants';
    }
}


================================================
FILE: app/Imports/ImportResult.php
================================================
<?php

namespace App\Imports;

/**
 * Résultat d'une analyse / intégration d'import.
 * Conforme validate-then-commit : on ne commit jamais de ligne en erreur.
 */
class ImportResult
{
    /** @var array<int, ImportRow> */
    public array $rows = [];

    public int $committed = 0;

    /**
     * @return array<int, ImportRow>
     */
    public function validRows(): array
    {
        return array_values(array_filter(
            $this->rows,
            fn (ImportRow $row) => $row->isValid(),
        ));
    }

    /**
     * @return array<int, ImportRow>
     */
    public function invalidRows(): array
    {
        return array_values(array_filter(
            $this->rows,
            fn (ImportRow $row) => ! $row->isValid(),
        ));
    }

    public function countValid(): int
    {
        return count($this->validRows());
    }

    public function countInvalid(): int
    {
        return count($this->invalidRows());
    }
}


================================================
FILE: app/Imports/ImportRow.php
================================================
<?php

namespace App\Imports;

/** Représente une ligne du fichier sourcé après normalisation. */
class ImportRow
{
    public function __construct(
        public readonly int $rowNumber,
        public readonly array $data,
        public readonly array $errors = [],
    ) {}

    public function isValid(): bool
    {
        return $this->errors === [];
    }
}


================================================
FILE: app/Imports/TheseImport.php
================================================
<?php

namespace App\Imports;

use App\Enums\DossierStatut;
use App\Enums\UserRole;
use App\Models\Dossier;
use Illuminate\Validation\Rule;

/**
 * Moulinet d'import des thèses en cours (CDC §module moulinet d'import).
 * Formats : CSV, XLSX, XML, JSON. Mappage automatique + validation avant intégration.
 * Validate-then-commit : une référence (doctorant / directeur / commission) inconnue
 * invalide la ligne sans jamais être écrite.
 */
class TheseImport extends BaseImport
{
    protected function aliases(): array
    {
        return [
            'Doctorant' => 'doctorant_email',
            'Doctorant email' => 'doctorant_email',
            'Email doctorant' => 'doctorant_email',
            'Directeur' => 'directeur_email',
            'Directeur de thèse' => 'directeur_email',
            'Email directeur' => 'directeur_email',
            'Encadreur' => 'directeur_email',
            'Commission' => 'commission_nom',
            'Nom commission' => 'commission_nom',
            'Discipline' => 'commission_nom',
            'Objet' => 'objet',
            'Intitulé' => 'objet',
            'Intitule' => 'objet',
            'Sujet' => 'objet',
            'Titre' => 'objet',
            'Année' => 'annee_inscription',
            'Année d\'inscription' => 'annee_inscription',
            'Annee inscription' => 'annee_inscription',
            'Statut' => 'statut',
            'Statut dossier' => 'statut',
        ];
    }

    protected function rules(): array
    {
        return [
            'doctorant_email' => [
                'required',
                'email',
                Rule::exists('users', 'email')->where(fn ($q) => $q->where('role', UserRole::Doctorant->value)),
            ],
            'directeur_email' => [
                'nullable',
                'email',
                Rule::exists('users', 'email')->where(fn ($q) => $q->where('role', UserRole::DirecteurThese->value)),
            ],
            'commission_nom' => ['required', Rule::exists('uma_commissions', 'nom')],
            'objet' => ['required', 'string', 'min:5', 'max:255'],
            'annee_inscription' => ['nullable', 'regex:/^\d{4}$/'],
            'statut' => ['nullable', Rule::in(collect(DossierStatut::cases())->map(fn ($s) => $s->value)->all())],
        ];
    }

    protected function createRecord(array $data): void
    {
        $directeurId = $data['directeur_email'] !== null && $data['directeur_email'] !== ''
            ? static::lookupId('users', 'email', $data['directeur_email'])
            : null;

        Dossier::create([
            'doctorant_id' => static::lookupId('users', 'email', $data['doctorant_email']),
            'directeur_id' => $directeurId,
            'commission_id' => static::lookupId('uma_commissions', 'nom', $data['commission_nom']),
            'objet' => $data['objet'],
            'statut' => $data['statut'] ?? DossierStatut::EnCours->value,
            'annee_inscription' => $data['annee_inscription'] ?? null,
            'created_by' => auth()->id(),
        ]);
    }

    public function label(): string
    {
        return 'Thèses en cours';
    }
}


================================================
FILE: app/Mail/FilteredListEmail.php
================================================
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Mail générique pour l'envoi à une liste filtrée d'utilisateurs.
 * Les tags {{nom}}, {{email}}, {{role}} sont résolus
 * avant la construction du mailable (dans le BulkAction).
 */
class FilteredListEmail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $subjectLine,
        public string $bodyHtml,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
            from: config('mail.from.address', config('app.name')),
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->bodyHtml,
        );
    }
}



================================================
FILE: app/Mail/ReunionConvocation.php
================================================
<?php

namespace App\Mail;

use App\Models\Reunion;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReunionConvocation extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Reunion $reunion,
        public User $invite,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Convocation — '.$this->reunion->objet,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reunion.convocation',
            with: [
                'reunion' => $this->reunion,
                'invite' => $this->invite,
            ],
        );
    }
}



================================================
FILE: app/Mail/WorkflowStateChanged.php
================================================
<?php

namespace App\Mail;

use App\Models\WorkflowInstance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkflowStateChanged extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public WorkflowInstance $instance,
        public string $message,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mise à jour du workflow — '.$this->instance->definition?->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.workflow.state-changed',
            with: [
                'instance' => $this->instance,
                'message' => $this->message,
            ],
        );
    }
}


================================================
FILE: app/Models/AuditLog.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class AuditLog extends Model
{
    protected $table = 'uma_audit_logs';

    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'before',
        'after',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'before' => 'array',
            'after' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(
        string $action,
        ?Model $entity = null,
        ?array $before = null,
        ?array $after = null,
        bool $withIp = true,
        ?User $actor = null,
    ): static {
        $actor ??= auth()->user();

        return self::create([
            'user_id' => $actor?->getKey(),
            'action' => $action,
            'entity_type' => $entity?->getMorphClass(),
            'entity_id' => $entity?->getKey(),
            'before' => $before,
            'after' => $after,
            'ip_address' => $withIp ? request()->ip() : null,
        ]);
    }

    public function save(array $options = []): bool
    {
        if ($this->exists) {
            throw new LogicException('Une entrée du journal d\'audit est immuable.');
        }

        return parent::save($options);
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new LogicException('Une entrée du journal d\'audit est immuable.');
    }

    public function delete(): ?bool
    {
        throw new LogicException('Une entrée du journal d\'audit est immuable.');
    }
}



================================================
FILE: app/Models/Commission.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['etablissement_id', 'president_id', 'nom', 'discipline', 'is_active'])]
class Commission extends Model
{
    protected $table = 'uma_commissions';

    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function president(): BelongsTo
    {
        return $this->belongsTo(User::class, 'president_id');
    }

    public function membres(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function estMembre(User $user): bool
    {
        return $this->membres()->whereKey($user->getKey())->exists();
    }

    public function estPresident(User $user): bool
    {
        return $this->president_id === $user->getKey();
    }

    public function reunions(): HasMany
    {
        return $this->hasMany(Reunion::class);
    }
}



================================================
FILE: app/Models/Decision.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Decision extends Model
{
    protected $table = 'uma_decisions';

    use HasFactory;

    protected $fillable = [
        'reunion_id',
        'dossier_id',
        'decision_template_id',
        'label',
        'email_subject',
        'email_body',
        'annee_inscription',
        'decided_by',
    ];

    public function reunion(): BelongsTo
    {
        return $this->belongsTo(Reunion::class);
    }

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(DecisionTemplate::class, 'decision_template_id');
    }

    public function decideur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }
}



================================================
FILE: app/Models/DecisionTemplate.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DecisionTemplate extends Model
{
    protected $table = 'uma_decision_templates';

    use HasFactory;

    protected $fillable = [
        'label',
        'description',
        'email_subject',
        'email_body',
        'commission_id',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function commission(): BelongsTo
    {
        return $this->belongsTo(Commission::class, 'commission_id');
    }
}



================================================
FILE: app/Models/Document.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Dossier numérique (mallette, CDC §1.14).
 * Rattaché de façon polymorphe à une entité (doctorant, dossier, réunion, décision).
 * Archivé dès sa création ; une pièce n'est jamais écrasée (versions immuables).
 */
class Document extends Model
{
    protected $table = 'uma_documents';

    use HasFactory;

    protected $fillable = [
        'documentable_type',
        'documentable_id',
        'type',
        'label',
        'description',
        'retention_months',
        'retention_until',
        'is_archived',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_archived' => 'boolean',
            'retention_until' => 'date',
        ];
    }

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class)->orderBy('version');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lastVersion(): ?DocumentVersion
    {
        return $this->versions()->reorder('version', 'desc')->first();
    }

    public function estExpire(): bool
    {
        return $this->retention_until !== null && $this->retention_until->isPast();
    }
}



================================================
FILE: app/Models/DocumentVersion.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

/**
 * Version d'un document archivé (versioning immuable — CDC §1.14, règle d'or P7).
 * Une version est créée une seule fois : toute tentative de mise à jour ou de
 * suppression est refusée au niveau du modèle (append-only).
 */
class DocumentVersion extends Model
{
    protected $table = 'uma_document_versions';

    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'document_id',
        'version',
        'file_path',
        'file_name',
        'mime_type',
        'size',
        'hash',
        'metadata',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function save(array $options = []): bool
    {
        if ($this->exists) {
            throw new LogicException('Une version de document est immuable.');
        }

        return parent::save($options);
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new LogicException('Une version de document est immuable.');
    }

    public function delete(): ?bool
    {
        throw new LogicException('Une version de document est immuable.');
    }
}



================================================
FILE: app/Models/Dossier.php
================================================
<?php

namespace App\Models;

use App\Enums\DossierStatut;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dossier extends Model
{
    protected $table = 'uma_dossiers';

    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'doctorant_id',
        'directeur_id',
        'commission_id',
        'objet',
        'description',
        'statut',
        'annee_inscription',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'statut' => DossierStatut::class,
        ];
    }

    public function doctorant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctorant_id');
    }

    public function commission(): BelongsTo
    {
        return $this->belongsTo(Commission::class);
    }

    public function directeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'directeur_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reunions(): BelongsToMany
    {
        return $this->belongsToMany(Reunion::class, 'reunion_dossier')
            ->withPivot('position')
            ->withTimestamps();
    }

    public function decisions(): HasMany
    {
        return $this->hasMany(Decision::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function workflowInstances(): MorphMany
    {
        return $this->morphMany(WorkflowInstance::class, 'subject');
    }

    /**
     * Instance de workflow active la plus récente (aucun changement de schéma).
     */
    public function activeWorkflow(): ?WorkflowInstance
    {
        return $this->workflowInstances()
            ->where('status', 'active')
            ->latest('id')
            ->first();
    }

    public function latestWorkflow(): ?WorkflowInstance
    {
        return $this->workflowInstances()->latest('id')->first();
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', DossierStatut::EnAttente->value);
    }
}



================================================
FILE: app/Models/EcoleDoctorale.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['universite_id', 'nom'])]
class EcoleDoctorale extends Model
{
    protected $table = 'uma_ecole_doctorales';

    use HasFactory;

    public function universite(): BelongsTo
    {
        return $this->belongsTo(Universite::class);
    }

    public function etablissements(): HasMany
    {
        return $this->hasMany(Etablissement::class);
    }
}



================================================
FILE: app/Models/Etablissement.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['ecole_doctorale_id', 'directeur_id', 'nom'])]
class Etablissement extends Model
{
    protected $table = 'uma_etablissements';

    use HasFactory;

    public function ecoleDoctorale(): BelongsTo
    {
        return $this->belongsTo(EcoleDoctorale::class);
    }

    public function directeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'directeur_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }
}



================================================
FILE: app/Models/Invitation.php
================================================
<?php

namespace App\Models;

use App\Enums\InvitationPresence;
use App\Enums\InvitationStatut;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    protected $table = 'uma_invitations';

    protected $fillable = [
        'reunion_id',
        'participant_id',
        'email',
        'statut',
        'statut_presence',
        'commentaire',
        'note',
        'excuse_status',
        'excuse_validated_by',
        'excuse_validated_at',
        'piece_joint',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'statut' => InvitationStatut::class,
            'statut_presence' => InvitationPresence::class,
            'excuse_validated_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function reunion(): BelongsTo
    {
        return $this->belongsTo(Reunion::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'excuse_validated_by');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', InvitationStatut::EnAttente->value);
    }
}



================================================
FILE: app/Models/OdjTemplate.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OdjTemplate extends Model
{
    protected $table = 'uma_odj_templates';

    use HasFactory;

    protected $fillable = [
        'label',
        'description',
        'contenu',
        'commission_id',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function commission(): BelongsTo
    {
        return $this->belongsTo(Commission::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reunions(): HasMany
    {
        return $this->hasMany(Reunion::class);
    }
}



================================================
FILE: app/Models/Presence.php
================================================
<?php

namespace App\Models;

use App\Enums\PresenceStatut;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    protected $table = 'uma_presences';

    protected $fillable = [
        'reunion_id',
        'participant_id',
        'email',
        'statut',
        'note',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'statut' => PresenceStatut::class,
        ];
    }

    public function reunion(): BelongsTo
    {
        return $this->belongsTo(Reunion::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function scopePresent($query)
    {
        return $query->where('statut', PresenceStatut::Present->value);
    }
}



================================================
FILE: app/Models/RapportEtat.php
================================================
<?php

namespace App\Models;

use App\Enums\RapportEtatType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * État / rapport paramétrable (CDC §1.13).
 * La mise en page (marges, orientation, format papier, en-tête) est éditée ici ;
 * le rendu passe exclusivement par le moteur du package (PvTemplate + PdfService).
 */
class RapportEtat extends Model
{
    protected $table = 'uma_rapport_etats';

    use HasFactory;

    protected $fillable = [
        'label',
        'type',
        'pv_type',
        'description',
        'en_tete',
        'orientation',
        'format_papier',
        'marges',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'type' => RapportEtatType::class,
            'marges' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function margesEffectives(): array
    {
        return array_merge([
            'top' => 20,
            'bottom' => 20,
            'left' => 20,
            'right' => 20,
        ], (array) $this->marges);
    }
}



================================================
FILE: app/Models/Reclamation.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Réclamation / ticket (Workflow C, CDC §1.10).
 * Les états sont pilotés par le moteur de workflow paramétrable ;
 * la priorité (très urgente / prioritaire / moyennement urgente) est
 * configurable via `config('workflow.reclamations.priorites')`.
 */
class Reclamation extends Model
{
    protected $table = 'uma_reclamations';

    use HasFactory;

    protected $fillable = [
        'commission_id',
        'user_id',
        'type',
        'objet',
        'contenu',
        'urgence',
        'statut',
        'closed_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'closed_at' => 'datetime',
        ];
    }

    public function commission(): BelongsTo
    {
        return $this->belongsTo(Commission::class);
    }

    public function deposant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(ReclamationDiscussion::class, 'reclamation_id');
    }

    public function isCloturee(): bool
    {
        return $this->closed_at !== null;
    }

    public function workflowInstances(): MorphMany
    {
        return $this->morphMany(WorkflowInstance::class, 'subject');
    }
}



================================================
FILE: app/Models/ReclamationDiscussion.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Message de discussion autour d'une réclamation (CDC §1.10).
 * Il n'y a pas de modification possible d'un message : création seule.
 */
class ReclamationDiscussion extends Model
{
    protected $table = 'uma_reclamation_discussions';

    public const UPDATED_AT = null;

    protected $fillable = [
        'reclamation_id',
        'user_id',
        'message',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function reclamation(): BelongsTo
    {
        return $this->belongsTo(Reclamation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}



================================================
FILE: app/Models/Reservation.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Réservation d'une salle ou d'un membre de jury pour une soutenance.
 * Sert au contrôle de chevauchement jury/salles (Workflow B, exigence CDC §10).
 */
class Reservation extends Model
{
    protected $table = 'uma_reservations';

    use HasFactory;

    public const TYPE_SALLE = 'salle';

    public const TYPE_JURY = 'jury';

    protected $fillable = [
        'type',
        'salle',
        'membre_id',
        'date_debut',
        'date_fin',
        'objet',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'datetime',
            'date_fin' => 'datetime',
        ];
    }

    public function membre(): BelongsTo
    {
        return $this->belongsTo(User::class, 'membre_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Chevauche les réservations de même type/cible sur la plage donnée.
     */
    public function scopeChevauche(Builder $query, string $type, ?string $salle = null, ?int $membreId = null, $debut = null, $fin = null): Builder
    {
        return $query
            ->where('type', $type)
            ->when($salle !== null && $type === self::TYPE_SALLE, fn (Builder $q) => $q->where('salle', $salle))
            ->when($membreId !== null && $type === self::TYPE_JURY, fn (Builder $q) => $q->where('membre_id', $membreId))
            ->when($debut !== null || $fin !== null, function (Builder $q) use ($debut, $fin) {
                $q->where(function (Builder $inner) use ($debut, $fin) {
                    if ($debut !== null) {
                        $inner->where('date_fin', '>', $debut);
                    }
                    if ($fin !== null) {
                        $inner->where('date_debut', '<', $fin);
                    }
                });
            });
    }
}



================================================
FILE: app/Models/Reunion.php
================================================
<?php

namespace App\Models;

use App\Enums\ReunionStatut;
use App\Enums\ReunionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SalsabilEnnaiem\PvModule\Models\Pv;

class Reunion extends Model
{
    protected $table = 'uma_reunions';

    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'commission_id',
        'objet',
        'description',
        'odj_template_id',
        'ordre_du_jour',
        'date_debut',
        'date_fin',
        'lieu',
        'lien',
        'type',
        'statut',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'datetime',
            'date_fin' => 'datetime',
            'type' => ReunionType::class,
            'statut' => ReunionStatut::class,
        ];
    }

    public function commission(): BelongsTo
    {
        return $this->belongsTo(Commission::class);
    }

    public function odjTemplate(): BelongsTo
    {
        return $this->belongsTo(OdjTemplate::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    public function dossiers(): BelongsToMany
    {
        return $this->belongsToMany(Dossier::class, 'reunion_dossier')
            ->withPivot('position')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    public function decisions(): HasMany
    {
        return $this->hasMany(Decision::class);
    }

    public function pvs()
    {
        return (new Pv)->newQuery()
            ->where('source_type', 'reunion')
            ->where('source_id', $this->getKey());
    }

    public function participantsEnsemble(): array
    {
        return [
            'commission_id' => $this->commission_id,
        ];
    }

    public function estPassee(): bool
    {
        return $this->date_fin !== null && $this->date_fin->lt(now());
    }
}



================================================
FILE: app/Models/Universite.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nom', 'code'])]
class Universite extends Model
{
    protected $table = 'uma_universites';

    use HasFactory;

    public function ecoleDoctorales(): HasMany
    {
        return $this->hasMany(EcoleDoctorale::class);
    }
}



================================================
FILE: app/Models/User.php
================================================
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'grade', 'structure_recherche', 'etablissement_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'admin' && $this->role !== null;
    }

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function commissions(): BelongsToMany
    {
        return $this->belongsToMany(Commission::class)->withPivot('role')->withTimestamps();
    }

    public function commissionsPresidees(): HasMany
    {
        return $this->hasMany(Commission::class, 'president_id');
    }

    public function etablissementDirecteur(): HasMany
    {
        return $this->hasMany(Etablissement::class, 'directeur_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }
}



================================================
FILE: app/Models/WorkflowAuditTrail.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

/**
 * Trace d'exécution d'une transition de workflow.
 * Append-only : toute écriture sur une ligne existante est refusée
 * (traçabilité des parcours, exigence P7 transposée au moteur).
 */
class WorkflowAuditTrail extends Model
{
    protected $table = 'uma_workflow_audit_trails';

    public const UPDATED_AT = null;

    protected $fillable = [
        'workflow_instance_id',
        'from_state',
        'to_state',
        'transition_code',
        'actor_id',
        'payload',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function instance(): BelongsTo
    {
        return $this->belongsTo(WorkflowInstance::class, 'workflow_instance_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function save(array $options = []): bool
    {
        if ($this->exists) {
            throw new LogicException('Une trace de workflow est immuable.');
        }

        return parent::save($options);
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new LogicException('Une trace de workflow est immuable.');
    }

    public function delete(): ?bool
    {
        throw new LogicException('Une trace de workflow est immuable.');
    }
}



================================================
FILE: app/Models/WorkflowDefinition.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Définition paramétrable d'un workflow métier (CDC §1.12).
 * Les états sont stockés dans la colonne `states` (json) : toute transition
 * déclarable en base n'exige aucune modification de code.
 */
class WorkflowDefinition extends Model
{
    protected $table = 'uma_workflow_definitions';

    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'subject_type',
        'states',
        'initial_state',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'states' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function transitions(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class, 'workflow_definition_id');
    }

    public function instances(): HasMany
    {
        return $this->hasMany(WorkflowInstance::class, 'workflow_definition_id');
    }

    public function statesList(): array
    {
        return (array) $this->states;
    }

    public function isTerminalState(string $state): bool
    {
        return $state === collect($this->statesList())->last();
    }
}



================================================
FILE: app/Models/WorkflowGuard.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Garde paramétrable d'une transition de workflow.
 * La règle (`rule`) est un code interprété par le garde-règles du moteur
 * (ex. `role`, `subject.data`, `subject.document`, `contract`, `no_overlap`).
 */
class WorkflowGuard extends Model
{
    protected $table = 'uma_workflow_guards';

    use HasFactory;

    protected $fillable = [
        'workflow_transition_id',
        'rule',
        'params',
        'error_message',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'params' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function transition(): BelongsTo
    {
        return $this->belongsTo(WorkflowTransition::class, 'workflow_transition_id');
    }
}



================================================
FILE: app/Models/WorkflowInstance.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Instance d'un workflow paramétrable : applique une définition à un sujet
 * métier (ex. un dossier ou une réclamation) et suit l'état courant.
 */
class WorkflowInstance extends Model
{
    protected $table = 'uma_workflow_instances';

    use HasFactory;

    protected $fillable = [
        'workflow_definition_id',
        'subject_type',
        'subject_id',
        'current_state',
        'data',
        'status',
        'started_at',
        'completed_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function definition(): BelongsTo
    {
        return $this->belongsTo(WorkflowDefinition::class, 'workflow_definition_id');
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function auditTrails(): HasMany
    {
        return $this->hasMany(WorkflowAuditTrail::class, 'workflow_instance_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function dataGet(string $key, mixed $default = null): mixed
    {
        return data_get($this->data, $key, $default);
    }
}



================================================
FILE: app/Models/WorkflowTransition.php
================================================
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Transition déclarée en base entre deux états d'un workflow.
 * Les gardes (WorkflowGuard) et les rôles autorisés (json `roles`) pilotent
 * l'exécution ; aucune logique de transition n'est codée en dur dans Filament.
 */
class WorkflowTransition extends Model
{
    protected $table = 'uma_workflow_transitions';

    use HasFactory;

    protected $fillable = [
        'workflow_definition_id',
        'code',
        'label',
        'from_state',
        'to_state',
        'roles',
        'actions',
        'notifications',
        'sort',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'roles' => 'array',
            'actions' => 'array',
            'notifications' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function definition(): BelongsTo
    {
        return $this->belongsTo(WorkflowDefinition::class, 'workflow_definition_id');
    }

    public function guards(): HasMany
    {
        return $this->hasMany(WorkflowGuard::class, 'workflow_transition_id');
    }

    public function rolesAutorises(): array
    {
        return array_values(array_filter((array) $this->roles));
    }
}



================================================
FILE: app/Notifications/ReunionPlanifiee.php
================================================
<?php

namespace App\Notifications;

use App\Models\Reunion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ReunionPlanifiee extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Reunion $reunion) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reunion_id' => $this->reunion->getKey(),
            'objet' => $this->reunion->objet,
            'date_debut' => $this->reunion->date_debut?->toIso8601String(),
            'lieu' => $this->reunion->lieu,
            'message' => 'Vous êtes convoqué(e) à une réunion : '.$this->reunion->objet,
        ];
    }
}



================================================
FILE: app/Notifications/ReunionTerminee.php
================================================
<?php

namespace App\Notifications;

use App\Models\Reunion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReunionTerminee extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Reunion $reunion) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Réunion terminée — '.$this->reunion->objet)
            ->line('La réunion « '.$this->reunion->objet.' » est terminée.')
            ->line('Les décisions et le procès-verbal sont en cours de traitement.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reunion_id' => $this->reunion->getKey(),
            'objet' => $this->reunion->objet,
            'message' => 'La réunion « '.$this->reunion->objet.' » est terminée.',
        ];
    }
}



================================================
FILE: app/Notifications/WorkflowTransitioned.php
================================================
<?php

namespace App\Notifications;

use App\Models\WorkflowInstance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class WorkflowTransitioned extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public WorkflowInstance $instance,
        public string $message,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'workflow_instance_id' => $this->instance->getKey(),
            'definition' => $this->instance->definition?->code,
            'state' => $this->instance->current_state,
            'message' => $this->message,
        ];
    }
}


================================================
FILE: app/Policies/AuditLogPolicy.php
================================================
<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\User;

/**
 * Journal d'audit : en lecture seule pour l'administrateur.
 * Toute mutation est refusée (append-only) — le champs d'application est aussi
 * garanti au niveau du modèle (AuditLog::save/update/delete lèvent une exception).
 */
class AuditLogPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->role === UserRole::Admin;
    }

    public function view(User $actor, AuditLog $log): bool
    {
        return $actor->role === UserRole::Admin;
    }

    public function create(User $actor): bool
    {
        return false;
    }

    public function update(User $actor, AuditLog $log): bool
    {
        return false;
    }

    public function delete(User $actor, AuditLog $log): bool
    {
        return false;
    }

    public function restore(User $actor, AuditLog $log): bool
    {
        return false;
    }

    public function forceDelete(User $actor, AuditLog $log): bool
    {
        return false;
    }
}



================================================
FILE: app/Policies/CommissionPolicy.php
================================================
<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Commission;
use App\Models\User;

/**
 * Policy Commission (CDC §2 espace « Commissions »).
 * Fail-closed : le président gère sa commission, les membres n'y accèdent
 * que s'ils en sont membres. Toute commission hors périmètre -> refus (IDOR).
 */
class CommissionPolicy
{
    public function viewAny(User $actor): bool
    {
        return in_array($actor->role, [
            UserRole::Admin,
            UserRole::GestionnaireEcole,
            UserRole::PresidentCommission,
            UserRole::MembreCommission,
        ], true);
    }

    public function view(User $actor, Commission $commission): bool
    {
        if ($actor->role === UserRole::Admin) {
            return true;
        }

        if ($actor->role === UserRole::Gest