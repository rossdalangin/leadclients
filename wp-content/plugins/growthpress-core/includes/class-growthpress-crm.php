<?php
/**
 * GrowthPress CRM Core Class - Final Enhanced
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
        add_shortcode( 'gp_lead_form', array( $this, 'render_lead_form' ) );
        add_shortcode( 'gp_quiz_lead_form', array( $this, 'render_quiz_form' ) );
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
        register_taxonomy( 'gp_lead_tag', 'gp_lead', array( 'hierarchical' => false, 'show_ui' => true ) );

        register_post_type( 'gp_task', array(
            'labels'      => array( 'name' => 'Tasks', 'singular_name' => 'Task' ),
            'public'      => false, 'show_ui' => true, 'menu_icon' => 'dashicons-forms',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
    }

    public function render_lead_form() {
        $nonce = wp_create_nonce( 'gp_lead_nonce' );
        $niche = get_option('growthpress_niche');
        ob_start(); ?>
        <form id="gp-lead-form" class="glass-card">
            <input type="hidden" name="gp_nonce" value="<?php echo $nonce; ?>">
            <input type="text" name="lead_name" placeholder="Full Name" required>
            <input type="email" name="lead_email" placeholder="Email Address" required>

            <?php if($niche === 'dental'): ?>
                <input type="text" name="insurance_provider" placeholder="Insurance Provider (Optional)">
            <?php endif; ?>

            <textarea name="lead_message" placeholder="How can we help?"></textarea>
            <button type="submit">Get Started</button>
        </form>
        <script>
            jQuery('#gp-lead-form').on('submit', function(e) {
                e.preventDefault();
                var data = jQuery(this).serialize() + '&action=gp_submit_lead';
                jQuery.post(gp_ajax.ajaxurl, data, function(res) { if(res.success) alert('Captured!'); });
            });
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_lead_submission() {
        if ( ! check_ajax_referer( 'gp_lead_nonce', 'gp_nonce', false ) ) wp_send_json_error();
        $lead_id = wp_insert_post( array( 'post_title' => sanitize_text_field( $_POST['lead_name'] ), 'post_content' => sanitize_textarea_field( $_POST['lead_message'] ), 'post_type' => 'gp_lead', 'post_status' => 'publish' ) );
        if ( $lead_id ) {
            update_post_meta( $lead_id, '_lead_email', sanitize_email( $_POST['lead_email'] ) );
            if ( isset($_POST['insurance_provider']) ) update_post_meta( $lead_id, '_insurance', sanitize_text_field($_POST['insurance_provider']) );
            do_action( 'gp_lead_captured', $lead_id );
            wp_send_json_success();
        }
        wp_send_json_error();
    }

    public function trigger_lead_automations($id) {}
    public function render_quiz_form() {}
}
GrowthPress_CRM::get_instance();
