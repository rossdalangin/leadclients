<?php
/**
 * GrowthPress Solar Module
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Solar {

    public function __construct() {
        add_shortcode( 'gp_solar_calculator', array( $this, 'render_calculator' ) );
    }

    /**
     * Solar ROI Estimator Shortcode
     */
    public function render_calculator() {
        ob_start();
        ?>
        <div class="gp-solar-calc glass-card">
            <h3>Estimate Your Solar Savings</h3>
            <input type="number" id="monthly_bill" placeholder="Monthly Bill ($)">
            <button onclick="calculateSolarROI()">Calculate ROI</button>
            <div id="gp-result"></div>
        </div>
        <?php
        return ob_get_clean();
    }
}

new GrowthPress_Solar();
