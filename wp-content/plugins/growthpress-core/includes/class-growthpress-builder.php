<?php
/**
 * GrowthPress Page Builder Integration Class - Pattern Enhanced
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

        // Hero Pattern
        register_block_pattern( 'growthpress/hero', array(
            'title' => 'GP Hero Section',
            'categories' => array( 'growthpress' ),
            'content' => '<!-- wp:group {"className":"gp-hero glass-card"} --><div class="wp-block-group gp-hero glass-card"> <!-- wp:heading {"level":1} --><h1>Grow Your Business with AI</h1><!-- /wp:heading --> <!-- wp:paragraph --><p>The ultimate operating system for high-ticket service providers.</p><!-- /wp:paragraph --> <!-- wp:shortcode -->[gp_lead_form]<!-- /wp:shortcode --> </div><!-- /wp:group -->'
        ) );

        // Pricing Pattern
        register_block_pattern( 'growthpress/pricing', array(
            'title' => 'GP Pricing Table',
            'categories' => array( 'growthpress' ),
            'content' => '<!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column glass-card"><h3>Starter</h3><p>$99/mo</p><ul><li>Lead Capture</li><li>AI Triage</li></ul></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column glass-card" style="border: 2px solid #2563EB"><h3>Pro</h3><p>$299/mo</p><ul><li>CRM Integration</li><li>AI Marketing Studio</li></ul></div><!-- /wp:column --></div><!-- /wp:columns -->'
        ) );

        // Testimonial Pattern
        register_block_pattern( 'growthpress/testimonials', array(
            'title' => 'GP Testimonial Grid',
            'categories' => array( 'growthpress' ),
            'content' => '<!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column glass-card"><p>"GrowthPress transformed our clinic\'s intake process. Highly recommend!"</p><strong>- Dr. Jane Smith</strong></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column glass-card"><p>"The AI content studio saves us 10 hours a week on marketing."</p><strong>- Mark Johnson, Solar Co.</strong></div><!-- /wp:column --></div><!-- /wp:columns -->'
        ) );
    }
}

new GrowthPress_Builder();
