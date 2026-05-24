<?php
/**
 * GrowthPress Medical Module
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Medical {

    public function __construct() {
        add_shortcode( 'gp_symptom_checker', array( $this, 'render_symptom_checker' ) );
    }

    /**
     * AI Symptom Checker Integration Shortcode
     */
    public function render_symptom_checker() {
        ?>
        <div class="gp-symptom-checker glass-card">
            <h3>AI Symptom Checker</h3>
            <textarea id="symptoms" placeholder="Describe your symptoms..."></textarea>
            <button onclick="checkSymptoms()">Analyze</button>
            <div id="ai-medical-advice" class="ai-warning">Not a medical diagnosis.</div>
        </div>
        <?php
    }
}

new GrowthPress_Medical();
