<?php
/**
 * GrowthPress Medical Module - Admin UI Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Medical {

    public function __construct() {
        add_shortcode( 'gp_symptom_checker', array( $this, 'render_symptom_checker' ) );
        add_action( 'wp_ajax_gp_check_symptoms', array( $this, 'handle_symptom_check' ) );
        add_action( 'wp_ajax_nopriv_gp_check_symptoms', array( $this, 'handle_symptom_check' ) );
        add_action( 'gp_appointment_created', array( $this, 'generate_telemedicine_link' ) );
    }

    public function generate_telemedicine_link( $appointment_id ) {
        $meeting_link = "https://telehealth.growthpress.io/room/" . wp_generate_password(8, false);
        update_post_meta( $appointment_id, '_gp_telemedicine_link', $meeting_link );
    }

    public function render_symptom_checker() {
        $nonce = wp_create_nonce('gp_medical_nonce');
        ob_start(); ?>
        <div class="gp-symptom-checker glass-card">
            <h3>AI Health Assistant</h3>
            <p class="help-text" style="font-size:12px;">Example: "I have a sharp pain in my lower back that started 2 days ago."</p>
            <input type="hidden" id="gp_medical_nonce" value="<?php echo $nonce; ?>">
            <textarea id="symptoms" placeholder="Describe your symptoms..."></textarea>
            <button onclick="checkSymptoms()">Start AI Triage</button>
            <div id="ai-medical-advice"></div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function handle_symptom_check() {
        check_ajax_referer( 'gp_medical_nonce', 'nonce' );
        $symptoms = sanitize_textarea_field($_POST['symptoms']);
        $ai = GrowthPress_AI::get_instance();

        $prompt = "As a medical triage assistant, analyze these symptoms: \"$symptoms\". Provide a preliminary assessment, potential urgency level, and suggest specific doctor types. Include a strong disclaimer that this is not a diagnosis.";
        $result = $ai->call_ai($prompt, "Medical Triage Assistant");

        if ( is_wp_error($result) ) wp_send_json_error($result->get_error_message());
        wp_send_json_success($result);
    }

    public function generate_sample_data() {
        $clinics = array(
            'Family Wellness Clinic' => 'Comprehensive care for patients of all ages.',
            'Pediatric Excellence Hub' => 'Specialized care for infants, children, and adolescents.',
            'Geriatric Health Partners' => 'Focused medical services for senior citizens.'
        );
        foreach($clinics as $t => $c) {
            wp_insert_post(array('post_title' => $t, 'post_content' => $c, 'post_type' => 'page', 'post_status' => 'publish'));
        }
    }
}
new GrowthPress_Medical();
