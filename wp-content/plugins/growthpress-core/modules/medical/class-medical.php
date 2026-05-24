<?php
/**
 * GrowthPress Medical Module - Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Medical {

    public function __construct() {
        add_shortcode( 'gp_symptom_checker', array( $this, 'render_symptom_checker' ) );
        add_action( 'wp_ajax_gp_check_symptoms', array( $this, 'handle_symptom_check' ) );
        add_action( 'wp_ajax_nopriv_gp_check_symptoms', array( $this, 'handle_symptom_check' ) );
    }

    public function render_symptom_checker() {
        $nonce = wp_create_nonce('gp_medical_nonce');
        ob_start(); ?>
        <div class="gp-symptom-checker glass-card">
            <h3>AI Symptom Checker</h3>
            <p>Describe what you're feeling, and our AI will provide general guidance.</p>
            <input type="hidden" id="gp_medical_nonce" value="<?php echo $nonce; ?>">
            <textarea id="symptoms" placeholder="Example: I have a persistent cough and a slight fever..."></textarea>
            <button onclick="checkSymptoms()">Analyze Symptoms</button>
            <div id="ai-medical-advice" class="ai-response-area"></div>
            <p class="ai-warning"><strong>Disclaimer:</strong> Not a medical diagnosis. Consult a doctor for professional advice.</p>
        </div>
        <?php
        return ob_get_clean();
    }

    public function handle_symptom_check() {
        check_ajax_referer('gp_medical_nonce', 'nonce');

        $symptoms = sanitize_textarea_field($_POST['symptoms']);
        $ai = GrowthPress_AI::get_instance();

        $prompt = "A patient describes these symptoms: \"$symptoms\". Provide a list of potential concerns and advise on urgency (e.g., 'Consult a GP' or 'Seek Emergency Care'). Maintain a professional, medical tone.";
        $response = $ai->call_ai($prompt, "You are a specialized medical triage assistant.");

        if (is_wp_error($response)) {
            wp_send_json_error($response->get_error_message());
        }

        wp_send_json_success($response);
    }

    public function generate_sample_data() {
        $services = array('Primary Care', 'Pediatrics', 'Cardiology');
        foreach($services as $service) {
            wp_insert_post(array('post_title' => $service, 'post_type' => 'page', 'post_status' => 'publish'));
        }
    }
}

new GrowthPress_Medical();
