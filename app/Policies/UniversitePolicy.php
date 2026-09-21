<?php

namespace App\Policies;

/**
 * Doublon supprimé : utiliser InstitutionPolicy (Université → École
 * doctorale → Établissement). Alias conservé pour compatibilité.
 */
class UniversitePolicy extends InstitutionPolicy {}
