<?php
/**
 * GrowthPress Dental Clinic Module - Ultra Polished
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Dental {

    public function __construct() {
        add_action( 'init', array( $this, 'register_dental_cpts' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_dental_meta_boxes' ) );
    }

    public function register_dental_cpts() {
        register_post_type( 'gp_treatment', array(
            'labels'      => array( 'name' => 'Treatments', 'singular_name' => 'Treatment' ),
            'public'      => true, 'show_ui' => true, 'menu_icon' => 'dashicons-heart',
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
        ) );
        register_post_type( 'gp_smile_gallery', array(
            'labels'      => array( 'name' => 'Smile Gallery', 'singular_name' => 'Smile Case' ),
            'public'      => true, 'show_ui' => true, 'menu_icon' => 'dashicons-format-gallery',
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
        ) );
    }

    public function add_dental_meta_boxes() {
        add_meta_box( 'gp_dental_help', 'Dental OS Instructions', array( $this, 'render_help_box' ), 'gp_treatment', 'side', 'high' );
        add_meta_box( 'gp_smile_help', 'Gallery Best Practices', array( $this, 'render_smile_help' ), 'gp_smile_gallery', 'side', 'high' );
    }

    public function render_help_box() {
        ?>
        <div class="gp-help-context">
            <p><strong>Treatment Pages:</strong> These are high-authority landing pages.</p>
            <p><em>Example:</em> "Advanced Dental Implants" should focus on benefits (comfort, longevity) rather than just technical specs.</p>
            <p><strong>SEO Tip:</strong> Include "Dentist in [City]" in the H2 tags.</p>
        </div>
        <?php
    }

    public function render_smile_help() {
        ?>
        <div class="gp-help-context">
            <p><strong>Conversion Rule:</strong> Always use high-resolution "Before & After" photos.</p>
            <p><em>Note:</em> Ensure you have patient consent forms uploaded to the secure documents portal.</p>
        </div>
        <?php
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title'=>'Full Mouth Reconstruction','post_content'=>'Comprehensive restorative care.','post_type'=>'gp_treatment','post_status'=>'publish'));
    }
}
new GrowthPress_Dental();
