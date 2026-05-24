<?php
/**
 * GrowthPress CRM Core Class - Enhanced with Internal Notes
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
        add_action( 'wp_ajax_gp_submit_lead', array( $this, 'handle_lead_submission' ) );
        add_action( 'wp_ajax_nopriv_gp_submit_lead', array( $this, 'handle_lead_submission' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_internal_notes_meta_box' ) );
        add_action( 'save_post_gp_lead', array( $this, 'save_internal_notes' ) );
    }

    public function register_cpts() {
        register_post_type( 'gp_lead', array(
            'labels'      => array( 'name' => 'Leads', 'singular_name' => 'Lead' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-groups',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
        register_taxonomy( 'gp_lead_stage', 'gp_lead', array( 'hierarchical' => true, 'show_ui' => true ) );

        register_post_type( 'gp_task', array(
            'labels'      => array( 'name' => 'Tasks', 'singular_name' => 'Task' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-forms',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
    }

    public function add_internal_notes_meta_box() {
        add_meta_box( 'gp_internal_notes', 'Internal Team Notes', array( $this, 'render_internal_notes' ), 'gp_lead', 'side' );
    }

    public function render_internal_notes( $post ) {
        $notes = get_post_meta( $post->ID, '_gp_internal_notes', true ); ?>
        <textarea name="gp_internal_notes" style="width:100%; height:100px;"><?php echo esc_textarea($notes); ?></textarea>
        <?php
    }

    public function save_internal_notes( $post_id ) {
        if ( isset($_POST['gp_internal_notes']) ) {
            update_post_meta( $post_id, '_gp_internal_notes', sanitize_textarea_field($_POST['gp_internal_notes']) );
        }
    }

    public function create_task( $title, $description, $lead_id = 0 ) {
        $task_id = wp_insert_post( array(
            'post_title'   => $title,
            'post_content' => $description,
            'post_type'    => 'gp_task',
            'post_status'  => 'publish',
        ) );
        if ( $lead_id ) update_post_meta( $task_id, '_related_lead', $lead_id );
        return $task_id;
    }

    public function trigger_lead_automations( $lead_id ) {
        $niche = get_option( 'growthpress_niche', 'business' );
        $ai = GrowthPress_AI::get_instance();

        $lead = get_post( $lead_id );
        $analysis = $ai->analyze_sentiment( $lead->post_content );
        update_post_meta( $lead_id, '_gp_ai_analysis', $analysis );

        if ( strpos( $analysis, '10' ) !== false || strpos( $analysis, '9' ) !== false ) {
            $this->create_task( "URGENT: Call " . $lead->post_title, "AI detected high urgency for this lead.", $lead_id );
        }

        $followup = $ai->generate_followup( array( 'name' => $lead->post_title, 'niche' => $niche ) );
        update_post_meta( $lead_id, '_gp_pending_followup', $followup );
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
            do_action( 'gp_lead_captured', $lead_id );
            wp_send_json_success( 'Lead captured and automation triggered!' );
        }
        wp_send_json_error( 'Failed to capture lead.' );
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
        <?php
        return ob_get_clean();
    }
}
GrowthPress_CRM::get_instance();
