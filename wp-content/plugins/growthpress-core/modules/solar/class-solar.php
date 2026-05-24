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

    public function generate_sample_data() {
        $leads = array(
            'John Solar' => 'Interested in 10kW system for residential home.',
            'Green Energy Corp' => 'Commercial inquiry for warehouse solar installation.'
        );

        foreach ( $leads as $name => $msg ) {
            wp_insert_post( array(
                'post_title'   => $name,
                'post_content' => $msg,
                'post_type'    => 'gp_lead',
                'post_status'  => 'publish'
            ) );
        }
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
