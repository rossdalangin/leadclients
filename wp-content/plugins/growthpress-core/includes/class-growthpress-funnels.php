<?php
/**
 * GrowthPress Funnel Management Class - A/B Testing Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Funnels {

    public function __construct() {
        add_action( 'init', array( $this, 'register_funnel_cpt' ) );
        add_shortcode( 'gp_funnel_step', array( $this, 'render_funnel_step' ) );
        add_action( 'wp_ajax_gp_track_funnel', array( $this, 'handle_tracking' ) );
        add_action( 'wp_ajax_nopriv_gp_track_funnel', array( $this, 'handle_tracking' ) );
    }

    public function register_funnel_cpt() {
        register_post_type( 'gp_funnel', array(
            'labels'      => array( 'name' => 'Funnels', 'singular_name' => 'Funnel' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-filter',
            'supports'    => array( 'title', 'custom-fields' ),
        ) );
    }

    public function render_funnel_step( $atts ) {
        $a = shortcode_atts( array( 'id' => 0, 'variation' => 'A' ), $atts );
        if ( ! $a['id'] ) return '';

        ob_start(); ?>
        <script>
        jQuery(document).ready(function($) {
            $.post(gp_ajax.ajaxurl, {
                action: 'gp_track_funnel',
                funnel_id: <?php echo intval($a['id']); ?>,
                variation: '<?php echo esc_js($a['variation']); ?>'
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_tracking() {
        $id = intval($_POST['funnel_id']);
        $var = sanitize_text_field($_POST['variation']);
        $this->track_variation_hit($id, $var);
        wp_send_json_success();
    }

    public function track_variation_hit( $funnel_id, $variation = 'A' ) {
        $hits = get_post_meta( $funnel_id, "_hits_$variation", true ) ?: 0;
        update_post_meta( $funnel_id, "_hits_$variation", ++$hits );
    }

    public function get_performance( $funnel_id ) {
        return array(
            'A' => get_post_meta( $funnel_id, '_hits_A', true ) ?: 0,
            'B' => get_post_meta( $funnel_id, '_hits_B', true ) ?: 0,
        );
    }
}

new GrowthPress_Funnels();
