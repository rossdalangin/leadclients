<?php
/**
 * GrowthPress CRM Core Class
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
        add_action( 'init', array( $this, 'register_lead_cpt' ) );
        add_action( 'save_post_gp_lead', array( $this, 'calculate_lead_score' ), 10, 2 );
        add_shortcode( 'gp_lead_form', array( $this, 'render_lead_form' ) );
        add_action( 'wp_ajax_gp_submit_lead', array( $this, 'handle_lead_submission' ) );
        add_action( 'wp_ajax_nopriv_gp_submit_lead', array( $this, 'handle_lead_submission' ) );
    }

    public function register_lead_cpt() {
        register_post_type( 'gp_lead', array(
            'labels'      => array( 'name' => 'Leads', 'singular_name' => 'Lead' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-groups',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );

        register_taxonomy( 'gp_lead_stage', 'gp_lead', array(
            'labels' => array( 'name' => 'Stages' ),
            'hierarchical' => true,
            'show_ui' => true,
        ) );
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
            <div class="form-feedback"></div>
        </form>
        <?php
        return ob_get_clean();
    }

    public function handle_lead_submission() {
        if ( ! check_ajax_referer( 'gp_lead_nonce', 'gp_nonce', false ) ) {
            wp_send_json_error( 'Security check failed.' );
        }

        $lead_id = wp_insert_post( array(
            'post_title'   => sanitize_text_field( $_POST['lead_name'] ),
            'post_content' => sanitize_textarea_field( $_POST['lead_message'] ),
            'post_type'    => 'gp_lead',
            'post_status'  => 'publish',
        ) );

        if ( $lead_id ) {
            update_post_meta( $lead_id, '_lead_email', sanitize_email( $_POST['lead_email'] ) );
            wp_send_json_success( 'Lead captured successfully!' );
        }
        wp_send_json_error( 'Failed to capture lead.' );
    }

    public function calculate_lead_score( $post_id, $post ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        $score = 0;
        $content = $post->post_content;
        if ( strpos( $content, 'emergency' ) !== false ) $score += 50;
        update_post_meta( $post_id, '_gp_lead_score', $score );
    }
}

GrowthPress_CRM::get_instance();
