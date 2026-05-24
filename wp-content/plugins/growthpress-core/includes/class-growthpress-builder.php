<?php
/**
 * GrowthPress Page Builder Integration Class - Funnel Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Builder {

    public function __construct() {
        add_action( 'elementor/elements/categories_registered', array( $this, 'register_elementor_category' ) );
        add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );
        add_action( 'init', array( $this, 'register_block_patterns' ) );
    }

    public function register_elementor_category( $elements_manager ) {
        $elements_manager->add_category( 'growthpress', array( 'title' => 'GrowthPress OS', 'icon' => 'fa fa-chart-line' ) );
    }

    public function register_elementor_widgets( $widgets_manager ) {
        require_once __DIR__ . '/widgets/elementor-lead-form.php';
        $widgets_manager->register( new \GrowthPress_Lead_Form_Widget() );
    }

    public function register_block_patterns() {
        if ( ! function_exists( 'register_block_pattern' ) ) return;

        register_block_pattern_category( 'growthpress', array( 'label' => 'GrowthPress' ) );

        // Lead Magnet Funnel Pattern
        register_block_pattern( 'growthpress/lead-magnet', array(
            'title' => 'GP Lead Magnet Funnel',
            'categories' => array( 'growthpress' ),
            'content' => '<!-- wp:group {"className":"gp-funnel glass-card"} --><div class="wp-block-group gp-funnel glass-card"><h2>Download Your Free AI Strategy Guide</h2><p>Learn how to automate your client intake in 5 minutes.</p><!-- wp:shortcode -->[gp_lead_form]<!-- /wp:shortcode --></div><!-- /wp:group -->'
        ) );

        // Webinar Funnel Pattern
        register_block_pattern( 'growthpress/webinar', array(
            'title' => 'GP Webinar Funnel',
            'categories' => array( 'growthpress' ),
            'content' => '<!-- wp:group {"className":"gp-webinar glass-card"} --><div class="wp-block-group gp-webinar glass-card"><h2>LIVE TRAINING: Scaling with AI</h2><p>Register now for our upcoming session on business automation.</p><!-- wp:shortcode -->[gp_booking_form]<!-- /wp:shortcode --></div><!-- /wp:group -->'
        ) );

        // Legacy Patterns
        register_block_pattern( 'growthpress/hero', array(
            'title' => 'GP Hero Section',
            'categories' => array( 'growthpress' ),
            'content' => '<!-- wp:group {"className":"gp-hero glass-card"} --><div class="wp-block-group gp-hero glass-card"><h1>Scale Your Business with AI</h1><!-- wp:shortcode -->[gp_lead_form]<!-- /wp:shortcode --></div><!-- /wp:group -->'
        ) );
    }
}

new GrowthPress_Builder();
