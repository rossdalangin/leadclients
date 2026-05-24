<?php
/**
 * GrowthPress CRM Core Class - Tracking Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_CRM {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', array( $this, 'register_cpts' ) );
        add_action( 'gp_lead_captured', array( $this, 'trigger_lead_automations' ) );
        add_action( 'wp_ajax_gp_track_behavior', array( $this, 'handle_tracking' ) );
        add_action( 'wp_ajax_nopriv_gp_track_behavior', array( $this, 'handle_tracking' ) );
        add_action( 'wp_footer', array( $this, 'inject_tracking_js' ) );
        add_shortcode( 'gp_lead_form', array( $this, 'render_lead_form' ) );
        add_action( 'wp_ajax_gp_submit_lead', array( $this, 'handle_lead_submission' ) );
        add_action( 'wp_ajax_nopriv_gp_submit_lead', array( $this, 'handle_lead_submission' ) );
    }

    public function register_cpts() {
        register_post_type( 'gp_lead', array(
            'labels'      => array( 'name' => 'Leads', 'singular_name' => 'Lead' ),
            'public'      => false, 'show_ui' => true, 'menu_icon' => 'dashicons-groups',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
        register_taxonomy( 'gp_lead_stage', 'gp_lead', array( 'hierarchical' => true, 'show_ui' => true ) );
    }

    public function inject_tracking_js() {
        if ( is_admin() ) return;
        ?>
        <script>
            (function($) {
                var history = JSON.parse(localStorage.getItem('gp_history') || '[]');
                history.push({ url: window.location.href, time: Date.now() });
                localStorage.setItem('gp_history', JSON.stringify(history.slice(-10))); // Keep last 10
            })(jQuery);
        </script>
        <?php
    }

    public function handle_tracking() {
        // Logic to link local history to lead after form submission
    }

    public function handle_lead_submission() {
        if ( ! check_ajax_referer( 'gp_lead_nonce', 'gp_nonce', false ) ) wp_send_json_error( 'Security check failed.' );

        $lead_id = wp_insert_post( array(
            'post_title'   => sanitize_text_field( $_POST['lead_name'] ),
            'post_content' => sanitize_textarea_field( $_POST['lead_message'] ),
            'post_type'    => 'gp_lead',
            'post_status'  => 'publish',
        ) );

        if ( $lead_id ) {
            update_post_meta( $lead_id, '_lead_email', sanitize_email( $_POST['lead_email'] ) );
            if ( isset($_POST['history']) ) {
                update_post_meta( $lead_id, '_behavior_history', $_POST['history'] );
            }
            do_action( 'gp_lead_captured', $lead_id );
            wp_send_json_success( 'Lead captured!' );
        }
        wp_send_json_error( 'Failed.' );
    }

    public function render_lead_form() {
        $nonce = wp_create_nonce( 'gp_lead_nonce' );
        ob_start(); ?>
        <form id="gp-lead-form" class="glass-card">
            <input type="hidden" name="gp_nonce" value="<?php echo $nonce; ?>">
            <input type="text" name="lead_name" placeholder="Full Name" required>
            <input type="email" name="lead_email" placeholder="Email Address" required>
            <textarea name="lead_message" placeholder="How can we help?"></textarea>
            <button type="submit">Get Started</button>
        </form>
        <script>
            jQuery('#gp-lead-form').on('submit', function(e) {
                e.preventDefault();
                var data = jQuery(this).serialize();
                data += '&action=gp_submit_lead&history=' + localStorage.getItem('gp_history');
                jQuery.post(gp_ajax.ajaxurl, data, function(res) {
                    if(res.success) alert('Success!');
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }

    public function trigger_lead_automations( $lead_id ) {
        // AI logic...
    }
}
GrowthPress_CRM::get_instance();
