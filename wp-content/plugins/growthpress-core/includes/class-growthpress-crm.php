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
        register_taxonomy( 'gp_lead_tag', 'gp_lead', array( 'hierarchical' => false, 'show_ui' => true ) );

        register_post_type( 'gp_task', array(
            'labels'      => array( 'name' => 'Tasks', 'singular_name' => 'Task' ),
            'public'      => false, 'show_ui' => true, 'menu_icon' => 'dashicons-forms',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
    }

    public function render_quiz_form() {
        $nonce = wp_create_nonce('gp_lead_nonce');
        // Filterable quiz steps for dynamic customization
        $steps = apply_filters('gp_quiz_steps', array(
            array('q' => 'What is your primary business goal?', 'o' => array('Growth', 'Automation', 'Authority')),
            array('q' => 'Estimated monthly revenue?', 'o' => array('< $10k', '$10k - $50k', '$50k+'))
        ));

        ob_start(); ?>
        <div class="gp-quiz-container glass-card" id="gp-quiz-form">
            <input type="hidden" name="gp_nonce" value="<?php echo $nonce; ?>">
            <?php foreach($steps as $i => $step): ?>
                <div class="quiz-step" data-step="<?php echo $i+1; ?>" style="<?php echo $i === 0 ? '' : 'display:none;'; ?>">
                    <h3><?php echo $step['q']; ?></h3>
                    <?php foreach($step['o'] as $opt): ?>
                        <button type="button" class="quiz-btn" onclick="nextStep(<?php echo $i+2; ?>)"><?php echo $opt; ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <div class="quiz-step" data-step="<?php echo count($steps)+1; ?>" style="display:none;">
                <h3>Your Details</h3>
                <input type="text" id="quiz-name" placeholder="Name" required>
                <input type="email" id="quiz-email" placeholder="Email" required>
                <button type="button" onclick="submitQuiz()">Get My AI Roadmap</button>
            </div>
        </div>
        <script>
        function nextStep(s) { jQuery('.quiz-step').hide(); jQuery('.quiz-step[data-step="'+s+'"]').show(); }
        function submitQuiz() {
            var data = { action:'gp_submit_lead', lead_name:jQuery('#quiz-name').val(), lead_email:jQuery('#quiz-email').val(), lead_message:'Quiz Completed', gp_nonce:jQuery('input[name="gp_nonce"]').val() };
            jQuery.post(gp_ajax.ajaxurl, data, function(res) { if(res.success) jQuery('#gp-quiz-form').html('<h3>Thank you! AI is analyzing...</h3>'); });
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_lead_submission() {
        if ( ! check_ajax_referer( 'gp_lead_nonce', 'gp_nonce', false ) ) wp_send_json_error( 'Security failed.' );
        $lead_id = wp_insert_post( array( 'post_title' => sanitize_text_field( $_POST['lead_name'] ), 'post_content' => sanitize_textarea_field( $_POST['lead_message'] ), 'post_type' => 'gp_lead', 'post_status' => 'publish' ) );
        if ( $lead_id ) {
            update_post_meta( $lead_id, '_lead_email', sanitize_email( $_POST['lead_email'] ) );
            do_action( 'gp_lead_captured', $lead_id );
            wp_send_json_success();
        }
        wp_send_json_error();
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

    public function trigger_lead_automations($id) {}
    public function add_internal_notes_meta_box() {}
    public function save_internal_notes($id) {}
    public function handle_export() {}
    public function create_task($t, $d, $lid=0) {}
}
GrowthPress_CRM::get_instance();
