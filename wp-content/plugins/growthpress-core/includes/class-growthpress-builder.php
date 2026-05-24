<?php
/**
 * GrowthPress Page Builder Integration Class - Bricks Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Builder {

    public function __construct() {
        add_action( 'elementor/elements/categories_registered', array( $this, 'register_elementor_category' ) );
        add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );
        add_action( 'init', array( $this, 'register_block_patterns' ) );
        add_filter( 'bricks/builder/i18n', array( $this, 'register_bricks_category' ) );
    }

    public function register_elementor_category( $elements_manager ) {
        $elements_manager->add_category( 'growthpress', array( 'title' => 'GrowthPress OS', 'icon' => 'fa fa-chart-line' ) );
    }

    public function register_elementor_widgets( $widgets_manager ) {
        require_once __DIR__ . '/widgets/elementor-lead-form.php';
        $widgets_manager->register( new \GrowthPress_Lead_Form_Widget() );
    }

    public function register_bricks_category( $i18n ) {
        $i18n['growthpress'] = 'GrowthPress OS';
        return $i18n;
    }

    public function register_block_patterns() {
        if ( ! function_exists( 'register_block_pattern' ) ) return;
        register_block_pattern_category( 'growthpress', array( 'label' => 'GrowthPress' ) );

        register_block_pattern( 'growthpress/hero', array(
            'title' => 'GP Hero Section',
            'categories' => array( 'growthpress' ),
            'content' => '<!-- wp:group {"className":"gp-hero glass-card"} --><div class="wp-block-group gp-hero glass-card"><h1>Scale Your Business with AI</h1><!-- wp:shortcode -->[gp_lead_form]<!-- /wp:shortcode --></div><!-- /wp:group -->'
        ) );
    }
}

new GrowthPress_Builder();
