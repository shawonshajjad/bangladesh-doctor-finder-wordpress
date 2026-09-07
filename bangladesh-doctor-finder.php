<?php
/**
 * Plugin Name: Bangladesh Doctor Finder
 * Plugin URI: https://github.com/shawonshajjad/bangladesh-doctor-finder-wordpress
 * Description: Bangladesh-focused doctor directory with location filtering, AJAX search, profiles, reviews, recommendations, CSV import, duplicate detection, and data-entry tools.
 * Version: 1.0.0
 * Author: Sajjadur Rahaman Shawon
 * Author URI: https://github.com/shawonshajjad
 * Text Domain: bangladesh-doctor-finder
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'BDF_VERSION', '1.0.0' );
define( 'BDF_FILE', __FILE__ );
define( 'BDF_PATH', plugin_dir_path( __FILE__ ) );
define( 'BDF_URL', plugin_dir_url( __FILE__ ) );

require_once BDF_PATH . 'includes/doctor-finder.php';

function bdf_activate_plugin() {
    dr_finder_v37_full_init();
    reset_data_entry_role_on_activate();
    dr_create_review_table();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'bdf_activate_plugin' );

function bdf_deactivate_plugin() { flush_rewrite_rules(); }
register_deactivation_hook( __FILE__, 'bdf_deactivate_plugin' );
