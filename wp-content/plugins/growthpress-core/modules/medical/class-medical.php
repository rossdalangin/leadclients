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
        add_action( 'gp_client_portal_dashboard', array( $this, 'render_medical_portal' ) );
    }

    public function generate_telemedicine_link( $appointment_id ) {
        $meeting_link = "https://telehealth.growthpress.io/room/" . wp_generate_password(8, false);
        update_post_meta( $appointment_id, '_gp_telemedicine_link', $meeting_link );
    }

    public function render_medical_portal() {
        ?>
        <div class="gp-medical-portal glass-card" style="margin-top:20px;">
            <h4>Secure Medical Records</h4>
            <p class="description">Access your lab results and medical history securely.</p>
            <div style="background:#f0f9ff; padding:15px; border-radius:8px; border:1px solid #bae6fd; font-size:13px;">
                <strong>Latest Result:</strong> Blood Panel (Oct 2023) - <a href="#">Download PDF</a>
            </div>
            <div style="margin-top:15px;">
                <button class="button button-small">Request Records Transfer</button>
            </div>
        </div>
        <?php
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
            if ( ! get_page_by_path( sanitize_title($t), OBJECT, 'page' ) ) {
                wp_insert_post(array('post_title' => $t, 'post_content' => $c, 'post_type' => 'page', 'post_status' => 'publish'));
            }
        }
    }
}
new GrowthPress_Medical();
