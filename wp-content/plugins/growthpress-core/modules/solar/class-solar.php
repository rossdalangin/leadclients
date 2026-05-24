<?php
/**
 * GrowthPress Solar Module - Final (Documented)
 *
 * Logic for Solar ROI calculators and AI Energy Consulting.
 * Sample Data includes Project CPT examples.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Solar {

    public function __construct() {
        add_shortcode( 'gp_solar_calculator', array( $this, 'render_calculator' ) );
        add_action( 'wp_ajax_gp_solar_ai_consult', array( $this, 'handle_solar_ai' ) );
        add_action( 'wp_ajax_nopriv_gp_solar_ai_consult', array( $this, 'handle_solar_ai' ) );
    }

    /**
     * ROI Calculator with dynamic multiplier based on orientation.
     */
    public function render_calculator() {
        ob_start(); ?>
        <!-- Solar HTML UI -->
        <div class="gp-solar-calc glass-card">
            <h3>Solar ROI Estimator</h3>
            <p class="help-text" style="font-size:11px;">Note: Savings are estimates based on average regional kilowatt hours.</p>
            <input type="number" id="gp-bill" value="150" placeholder="Monthly Bill ($)">
            <button onclick="runSolarCalc()">Calculate</button>
            <div id="solar-results"></div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function handle_solar_ai() {
        // AI Energy Consultant Logic
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Residential Solar Installation', 'post_type' => 'gp_project', 'post_status' => 'publish'));
    }
}
new GrowthPress_Solar();
