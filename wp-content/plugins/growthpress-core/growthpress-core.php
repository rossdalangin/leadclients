<?php
/**
 * Plugin Name: GrowthPress Core
 * Plugin URI: https://growthpress.io
 * Description: Core engine for the GrowthPress Business Operating System.
 * Version: 1.0.0
 * Author: GrowthPress Team
 * Text Domain: growthpress-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// Define constants
define( 'GROWTHPRESS_CORE_VERSION', '1.0.0' );
define( 'GROWTHPRESS_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'GROWTHPRESS_CORE_URL', plugin_dir_url( __FILE__ ) );

// Autoloader or simple inclusions
function growthpress_core_load_modules() {
    $files = array(
        'includes/class-growthpress-ai.php',
        'includes/class-growthpress-crm.php',
        'includes/class-growthpress-booking.php',
        'admin/class-growthpress-dashboard.php',
    );

    foreach ( $files as $file ) {
        if ( file_exists( GROWTHPRESS_CORE_PATH . $file ) ) {
            require_once GROWTHPRESS_CORE_PATH . $file;
        }
    }

    // Load niche modules
    $modules = array(
        'dental', 'law', 'contractor', 'roofing', 'solar', 'accounting', 'medical', 'real-estate', 'coaches', 'consultants'
    );
    foreach($modules as $module) {
        $module_file = GROWTHPRESS_CORE_PATH . "modules/$module/class-$module.php";
        if ( file_exists( $module_file ) ) {
            require_once $module_file;
        }
    }
}
add_action( 'plugins_loaded', 'growthpress_core_load_modules' );
