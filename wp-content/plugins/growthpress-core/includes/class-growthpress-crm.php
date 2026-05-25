<?php
/**
 * GrowthPress CRM Core Class - Qualification Enhanced
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
        add_shortcode( 'gp_quiz_lead_form', array( $this, 'render_quiz_form' ) );
        add_action( 'wp_ajax_gp_submit_lead', array( $this, 'handle_lead_submission' ) );
        add_action( 'wp_ajax_nopriv_gp_submit_lead', array( $this, 'handle_lead_submission' ) );
    }

    public function register_cpts() {
        register_post_type( 'gp_lead', array( 'labels' => array( 'name' => 'Leads' ), 'public' => false, 'show_ui' => true, 'supports' => array( 'title', 'editor', 'custom-fields' ) ) );
    }

    public function render_quiz_form() {
        $nonce = wp_create_nonce('gp_lead_nonce');
        $niche = get_option('growthpress_niche', 'business');
        ob_start(); ?>
        <div class="gp-quiz-form glass-card" id="gp-ai-quiz">
            <input type="hidden" name="gp_nonce" value="<?php echo $nonce; ?>">
            <div id="quiz-step-1">
                <h3>Let's see if we're a match...</h3>
                <p>What's your biggest challenge right now?</p>
                <button type="button" onclick="nextQuizStep(2, 'Growth')">Scaling Revenue</button>
                <button type="button" onclick="nextQuizStep(2, 'Efficiency')">Saving Time</button>
            </div>
            <div id="quiz-step-2" style="display:none;">
                <h3>Almost there!</h3>
                <input type="text" id="lead-name" placeholder="Name" required>
                <input type="email" id="lead-email" placeholder="Email" required>
                <button type="button" onclick="submitAIQuiz()">Get My Free AI Strategy</button>
            </div>
        </div>
        <script>
        var quizData = { intent: '' };
        function nextQuizStep(s, val) {
            if(val) quizData.intent = val;
            jQuery('#gp-ai-quiz > div').hide();
            jQuery('#quiz-step-' + s).show();
        }
        function submitAIQuiz() {
            var data = {
                action: 'gp_submit_lead',
                lead_name: jQuery('#lead-name').val(),
                lead_email: jQuery('#lead-email').val(),
                lead_message: 'Intent: ' + quizData.intent + ' for <?php echo $niche; ?> niche.',
                gp_nonce: jQuery('input[name="gp_nonce"]').val()
            };
            jQuery.post(gp_ajax.ajaxurl, data, function(res) {
                if(res.success) jQuery('#gp-ai-quiz').html('<h3>Analysis Complete! Check your email for your custom roadmap.</h3>');
            });
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function trigger_lead_automations( $lead_id ) {
        $ai = GrowthPress_AI::get_instance();
        $lead = get_post($lead_id);

        // Qualification Logic
        $analysis = $ai->analyze_sentiment($lead->post_content);
        update_post_meta($lead_id, '_gp_ai_qualification', $analysis);

        // Automated Tagging based on high-ticket criteria
        if (strpos($analysis, '10') !== false) {
            wp_set_post_terms($lead_id, 'High Priority', 'gp_lead_tag');
        }
    }

    public function handle_lead_submission() {
        if ( ! check_ajax_referer( 'gp_lead_nonce', 'gp_nonce', false ) ) wp_send_json_error();
        $lead_id = wp_insert_post( array(
            'post_title' => sanitize_text_field($_POST['lead_name']),
            'post_content' => sanitize_textarea_field($_POST['lead_message']),
            'post_type' => 'gp_lead',
            'post_status' => 'publish'
        ) );
        if ($lead_id) {
            update_post_meta($lead_id, '_lead_email', sanitize_email($_POST['lead_email']));
            do_action('gp_lead_captured', $lead_id);
            wp_send_json_success();
        }
        wp_send_json_error();
    }
}
GrowthPress_CRM::get_instance();
