<?php
/**
 * GrowthPress Solar Module - Ultra Polished
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Solar {

    public function __construct() {
        add_shortcode( 'gp_solar_calculator', array( $this, 'render_calculator' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_solar_meta' ) );
    }

    public function add_solar_meta() {
        add_meta_box( 'gp_solar_instructions', 'Solar Sales Intelligence', array( $this, 'render_solar_help' ), 'gp_lead', 'side' );
    }

    public function render_solar_help( $post ) {
        $niche = get_option('growthpress_niche');
        if($niche !== 'solar') return;
        ?>
        <div class="gp-help-context">
            <p><strong>Sales Angle:</strong> Focus on the 30% Federal Tax Credit if the lead mentions "cost".</p>
            <p><strong>ROI Note:</strong> If their bill is >$150, emphasize the "Immediate Cash-Flow Positive" benefit.</p>
        </div>
        <?php
    }

    public function render_calculator() {
        ob_start(); ?>
        <div class="gp-solar-calc glass-card">
            <h3>Solar ROI Estimator</h3>
            <p>Calculate your 25-year energy savings instantly.</p>
            <input type="number" id="gp-bill" placeholder="Average Monthly Bill ($)">
            <button onclick="runSolarCalc()">See Savings</button>
            <div id="solar-results"></div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title'=>'Residential 10kW System','post_type'=>'gp_project','post_status'=>'publish'));
    }
}
new GrowthPress_Solar();
