<?php
/**
 * GrowthPress Dental Clinic Module
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Dental {

    public function __construct() {
        add_action( 'init', array( $this, 'register_dental_cpts' ) );
    }

    public function register_dental_cpts() {
        // Treatment Pages
        register_post_type( 'gp_treatment', array(
            'labels'      => array( 'name' => 'Treatments', 'singular_name' => 'Treatment' ),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-heart',
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
            'has_archive' => true,
        ) );

        // Smile Gallery
        register_post_type( 'gp_smile_gallery', array(
            'labels'      => array( 'name' => 'Smile Gallery', 'singular_name' => 'Smile Case' ),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-format-gallery',
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
        ) );
    }

    public function generate_sample_data() {
        $treatments = array(
            'Dental Implants' => 'Restore your smile with permanent, natural-looking dental implants.',
            'Invisalign' => 'Straighten your teeth discreetly with the world\'s most advanced clear aligner system.',
            'Teeth Whitening' => 'Brighten your smile in just one visit with our professional whitening treatments.'
        );

        foreach ( $treatments as $title => $content ) {
            wp_insert_post( array(
                'post_title'   => $title,
                'post_content' => $content,
                'post_type'    => 'gp_treatment',
                'post_status'  => 'publish'
            ) );
        }
    }

    /**
     * Dental specific automation: Insurance inquiry trigger
     */
    public function handle_insurance_inquiry( $lead_id ) {
        // Logic for insurance verification workflow
    }
}

new GrowthPress_Dental();
