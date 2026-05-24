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
        add_action( 'wp_ajax_gp_export_leads', array( $this, 'handle_export' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_internal_notes_meta_box' ) );
        add_action( 'save_post_gp_lead', array( $this, 'save_internal_notes' ) );
    }

    public function register_cpts() {
        register_post_type( 'gp_lead', array(
            'labels'      => array( 'name' => 'Leads', 'singular_name' => 'Lead' ),
            'public'      => false, 'show_ui' => true, 'menu_icon' => 'dashicons-groups',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
        register_taxonomy( 'gp_lead_stage', 'gp_lead', array( 'hierarchical' => true, 'show_ui' => true ) );

        register_post_type( 'gp_task', array(
            'labels'      => array( 'name' => 'Tasks', 'singular_name' => 'Task' ),
            'public'      => false, 'show_ui' => true, 'menu_icon' => 'dashicons-forms',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
    }

    public function handle_export() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        if ( ! current_user_can('manage_options') ) wp_die();

        $leads = get_posts( array( 'post_type' => 'gp_lead', 'posts_per_page' => -1 ) );

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="growthpress-leads.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, array('Name', 'Email', 'Message', 'Score'));

        foreach ($leads as $lead) {
            $email = get_post_meta($lead->ID, '_lead_email', true);
            $score = get_post_meta($lead->ID, '_gp_lead_score', true);
            fputcsv($output, array($lead->post_title, $email, $lead->post_content, $score));
        }
        fclose($output);
        wp_die();
    }

    public function render_lead_form() {
        $nonce = wp_create_nonce( 'gp_lead_nonce' );
        $locations = get_posts( array( 'post_type' => 'gp_location', 'posts_per_page' => -1 ) );
        ob_start(); ?>
        <form id="gp-lead-form" class="glass-card">
            <input type="hidden" name="gp_nonce" value="<?php echo $nonce; ?>">
            <input type="text" name="lead_name" placeholder="Full Name" required>
            <input type="email" name="lead_email" placeholder="Email Address" required>
            <?php if($locations): ?>
                <select name="location_id">
                    <option value="">Select Location</option>
                    <?php foreach($locations as $loc) echo "<option value='{$loc->ID}'>{$loc->post_title}</option>"; ?>
                </select>
            <?php endif; ?>
            <textarea name="lead_message" placeholder="How can we help?"></textarea>
            <button type="submit">Get Started</button>
        </form>
        <script>
            jQuery('#gp-lead-form').on('submit', function(e) {
                e.preventDefault();
                var data = jQuery(this).serialize() + '&action=gp_submit_lead&history=' + localStorage.getItem('gp_history');
                jQuery.post(gp_ajax.ajaxurl, data, function(res) { if(res.success) alert('Success!'); });
            });
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_lead_submission() {
        if ( ! check_ajax_referer( 'gp_lead_nonce', 'gp_nonce', false ) ) wp_send_json_error( 'Security failed.' );
        $lead_id = wp_insert_post( array(
            'post_title'   => sanitize_text_field( $_POST['lead_name'] ),
            'post_content' => sanitize_textarea_field( $_POST['lead_message'] ),
            'post_type'    => 'gp_lead', 'post_status'  => 'publish',
        ) );
        if ( $lead_id ) {
            update_post_meta( $lead_id, '_lead_email', sanitize_email( $_POST['lead_email'] ) );
            if ( isset($_POST['location_id']) ) update_post_meta( $lead_id, '_assigned_location', intval($_POST['location_id']) );
            do_action( 'gp_lead_captured', $lead_id );
            wp_send_json_success('Captured.');
        }
        wp_send_json_error('Failed.');
    }

    public function trigger_lead_automations( $lead_id ) {
        // AI logic...
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
        if ( isset($_POST['gp_internal_notes']) ) update_post_meta( $post_id, '_gp_internal_notes', sanitize_textarea_field($_POST['gp_internal_notes']) );
    }

    public function create_task( $title, $description, $lead_id = 0 ) {
        $task_id = wp_insert_post( array( 'post_title' => $title, 'post_content' => $description, 'post_type' => 'gp_task', 'post_status' => 'publish' ) );
        if ( $lead_id ) update_post_meta( $task_id, '_related_lead', $lead_id );
        return $task_id;
    }

    public function render_quiz_form() {
        // Quiz form logic...
    }
}
GrowthPress_CRM::get_instance();
