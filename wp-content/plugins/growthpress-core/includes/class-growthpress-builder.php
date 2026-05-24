<?php
/**
 * GrowthPress Page Builder Integration Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Builder {

    public function __construct() {
        add_action( 'elementor/elements/categories_registered', array( $this, 'register_elementor_category' ) );
        add_action( 'init', array( $this, 'register_block_patterns' ) );
    }

    public function register_elementor_category( $elements_manager ) {
        $elements_manager->add_category(
            'growthpress',
            array(
                'title' => __( 'GrowthPress OS', 'growthpress-core' ),
                'icon'  => 'fa fa-chart-line',
            )
        );
    }

    public function register_block_patterns() {
        if ( function_exists( 'register_block_pattern_category' ) ) {
            register_block_pattern_category( 'growthpress', array( 'label' => __( 'GrowthPress', 'growthpress-core' ) ) );
        }

        // Example Pattern: Hero Section
        register_block_pattern(
            'growthpress/hero',
            array(
                'title'       => __( 'GrowthPress Hero', 'growthpress-core' ),
                'categories'  => array( 'growthpress' ),
                'content'     => '<!-- wp:group {"className":"gp-hero-glass"} --><div class="wp-block-group gp-hero-glass"><!-- wp:heading --><h1>Scale Your Business with AI</h1><!-- /wp:heading --><!-- wp:shortcode -->[gp_lead_form]<!-- /wp:shortcode --></div><!-- /wp:group -->',
            )
        );
    }
}

new GrowthPress_Builder();
