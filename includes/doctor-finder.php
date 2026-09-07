<?php
/**
 * Bangladesh Doctor Finder module loader.
 *
 * The public package keeps the production feature code in small modules for maintainability.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

foreach ( array(
    'core.php',
    'frontend.php',
    'search.php',
    'voting.php',
    'data-entry.php',
    'profiles-reviews.php',
    'duplicates.php',
) as $bdf_module ) {
    require_once __DIR__ . '/modules/' . $bdf_module;
}
