<?php
/**
 * GrowthPress Dental Clinic Module - Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Dental {

    public function __construct() {
        add_action( 'init', array( $this, 'register_dental_cpts' ) );
        add_shortcode( 'gp_before_after', array( $this, 'render_before_after' ) );
    }

    public function register_dental_cpts() {
        register_post_type( 'gp_treatment', array(
            'labels'      => array( 'name' => 'Treatments', 'singular_name' => 'Treatment' ),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-heart',
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
        ) );

        register_post_type( 'gp_smile_gallery', array(
            'labels'      => array( 'name' => 'Smile Gallery', 'singular_name' => 'Smile Case' ),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-format-gallery',
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
        ) );
    }

    public function render_before_after( $atts ) {
        $a = shortcode_atts( array( 'before' => '', 'after' => '' ), $atts );
        ob_start(); ?>
        <div class="gp-before-after glass-card" style="display: flex; gap: 10px;">
            <div class="side">
                <img src="<?php echo esc_url($a['before']); ?>" style="width:100%; border-radius: 8px;">
                <p style="text-align:center;">Before</p>
            </div>
            <div class="side">
                <img src="<?php echo esc_url($a['after']); ?>" style="width:100%; border-radius: 8px;">
                <p style="text-align:center;">After</p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function generate_sample_data() {
        $treatments = array(
            'Dental Implants' => 'Restore your smile with permanent, natural-looking dental implants.',
            'Invisalign' => 'Straighten your teeth discreetly with clear aligners.',
        );
        foreach ( $treatments as $title => $content ) {
            wp_insert_post( array('post_title' => $title, 'post_content' => $content, 'post_type' => 'gp_treatment', 'post_status' => 'publish') );
        }
    }
}

new GrowthPress_Dental();
