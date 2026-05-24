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

define( 'GROWTHPRESS_CORE_VERSION', '1.0.0' );
define( 'GROWTHPRESS_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'GROWTHPRESS_CORE_URL', plugin_dir_url( __FILE__ ) );

function growthpress_core_load_modules() {
    $files = array(
        'includes/class-growthpress-ai.php',
        'includes/class-growthpress-ai-faq.php',
        'includes/class-growthpress-crm.php',
        'includes/class-growthpress-booking.php',
        'includes/class-growthpress-api.php',
        'includes/class-growthpress-seo.php',
        'includes/class-growthpress-builder.php',
        'includes/class-growthpress-builder-ext.php',
        'includes/class-growthpress-reputation.php',
        'includes/class-growthpress-portal.php',
        'includes/class-growthpress-locations.php',
        'includes/class-growthpress-activity.php',
        'includes/class-growthpress-payments.php',
        'includes/class-growthpress-funnels.php',
        'admin/class-growthpress-dashboard.php',
        'admin/class-growthpress-content-studio.php',
        'admin/class-growthpress-settings.php',
    );

    foreach ( $files as $file ) {
        if ( file_exists( GROWTHPRESS_CORE_PATH . $file ) ) {
            require_once GROWTHPRESS_CORE_PATH . $file;
        }
    }

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
