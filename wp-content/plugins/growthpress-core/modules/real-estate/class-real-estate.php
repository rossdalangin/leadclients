<?php
/**
 * GrowthPress Real Estate Module - Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_RealEstate {

    public function __construct() {
        add_action( 'init', array( $this, 'register_property_cpt' ) );
        add_action( 'wp_ajax_gp_property_match', array( $this, 'handle_property_match' ) );
        add_action( 'wp_ajax_nopriv_gp_property_match', array( $this, 'handle_property_match' ) );
    }

    public function register_property_cpt() {
        register_post_type( 'gp_property', array(
            'labels'      => array( 'name' => 'Properties', 'singular_name' => 'Property' ),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-admin-home',
            'supports'    => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        ) );
    }

    public function handle_property_match() {
        $buyer_intent = sanitize_textarea_field($_POST['intent']);

        // Fetch current property titles
        $properties = get_posts(array('post_type' => 'gp_property', 'posts_per_page' => 20));
        $prop_list = "";
        foreach($properties as $p) { $prop_list .= "- " . $p->post_title . "\n"; }

        $ai = GrowthPress_AI::get_instance();
        $prompt = "A buyer is looking for: \"$buyer_intent\". Based on these available properties:\n$prop_list\nRecommend the top 3 best matches and explain why.";
        $response = $ai->call_ai($prompt, "You are an expert real estate consultant.");

        wp_send_json_success($response);
    }

    public function generate_sample_data() {
        $props = array(
            'Sunset Hills Estate' => 'Luxury 5-bedroom home with panoramic views.',
            'Modern Downtown Loft' => 'Sleek 2-bedroom loft in the heart of the city.',
            'Cozy Suburban Cottage' => '3-bedroom home with a large garden, perfect for families.'
        );
        foreach($props as $title => $content) {
            wp_insert_post(array('post_title' => $title, 'post_content' => $content, 'post_type' => 'gp_property', 'post_status' => 'publish'));
        }
    }
}

new GrowthPress_RealEstate();
