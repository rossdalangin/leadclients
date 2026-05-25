<?php
/**
 * GrowthPress Dental Clinic Module - Help Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Dental {

    public function __construct() {
        add_action( 'init', array( $this, 'register_dental_cpts' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_dental_meta_boxes' ) );
        add_action( 'admin_head', array( $this, 'add_dental_help_tabs' ) );
    }

    public function register_dental_cpts() {
        register_post_type( 'gp_treatment', array(
            'labels'      => array( 'name' => 'Treatments', 'singular_name' => 'Treatment' ),
            'public'      => true, 'show_ui' => true, 'menu_icon' => 'dashicons-heart',
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
        ) );
    }

    public function add_dental_meta_boxes() {
        add_meta_box( 'gp_dental_settings', 'Niche Configuration', array( $this, 'render_dental_meta' ), 'gp_treatment', 'side', 'default' );
    }

    public function render_dental_meta( $post ) {
        ?>
        <div class="gp-meta-field">
            <label>Authority Focus</label>
            <select name="gp_auth_type" style="width:100%;">
                <option value="cosmetic">Cosmetic Dentistry</option>
                <option value="restorative">Restorative Care</option>
            </select>
            <p class="description">Help: Choose 'Cosmetic' for Veneers/Invisalign to trigger premium UI elements.</p>
        </div>
        <?php
    }

    public function add_dental_help_tabs() {
        $screen = get_current_screen();
        if ( $screen->post_type !== 'gp_treatment' ) return;

        $screen->add_help_tab( array(
            'id'      => 'gp_dental_overview',
            'title'   => 'Treatment Mastery',
            'content' => '<p>Mastering treatment pages: Use high-ticket keywords like "painless", "life-changing", and "expert care". Ensure every page has a [gp_booking_form] shortcode.</p>',
        ) );
    }

    public function generate_sample_data() {
        $data = array(
            'Advanced Dental Implants' => 'Replace missing teeth with natural-looking, high-durability implants.',
            'Invisalign Smile Design'  => 'Clear aligner therapy for a perfectly straight smile without braces.',
            'Emergency Dental Care'    => '24/7 priority care for acute dental pain and injuries.',
            'Teeth Whitening Elite'    => 'Professional medical-grade whitening for immediate results.'
        );
        foreach($data as $title => $content) {
            if ( ! get_page_by_path( sanitize_title($title), OBJECT, 'gp_treatment' ) ) {
                wp_insert_post(array(
                    'post_title'   => $title,
                    'post_content' => $content,
                    'post_type'    => 'gp_treatment',
                    'post_status'  => 'publish'
                ));
            }
        }
    }
}
new GrowthPress_Dental();
