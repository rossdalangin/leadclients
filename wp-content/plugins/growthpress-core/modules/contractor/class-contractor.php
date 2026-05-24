<?php
/**
 * GrowthPress Contractor Module - UI Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Contractor {

    public function __construct() {
        add_action( 'init', array( $this, 'register_contractor_cpts' ) );
        add_shortcode( 'gp_contractor_estimator', array( $this, 'render_estimator' ) );
    }

    public function register_contractor_cpts() {
        register_post_type( 'gp_project', array(
            'labels'      => array( 'name' => 'Portfolio', 'singular_name' => 'Project' ),
            'public'      => true, 'show_ui' => true, 'menu_icon' => 'dashicons-admin-tools',
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
        ) );
    }

    public function render_estimator() {
        ob_start(); ?>
        <div class="gp-estimator glass-card">
            <h3>Precision Project Estimator</h3>
            <div class="form-row">
                <label>Area (Sq Ft)</label>
                <input type="number" id="gp-sq-ft" placeholder="e.g. 1000">
            </div>
            <div class="form-row">
                <label>Type of Renovation</label>
                <select id="gp-renov-type">
                    <option value="kitchen">Kitchen Remodel</option>
                    <option value="bath">Bathroom Remodel</option>
                    <option value="full">Whole Home</option>
                </select>
            </div>
            <div class="form-row">
                <label>Material Tier</label>
                <select id="gp-tier">
                    <option value="standard">Standard Contractor Grade</option>
                    <option value="luxury">Luxury / Bespoke</option>
                </select>
            </div>
            <button onclick="calcEstimate()" style="margin-top:15px;">Calculate Quote</button>
            <div id="estimate-result" style="margin-top:20px; font-weight:bold;"></div>
        </div>
        <script>
        function calcEstimate() {
            var sqft = jQuery('#gp-sq-ft').val();
            var type = jQuery('#gp-renov-type').val();
            var tier = jQuery('#gp-tier').val();
            var base = type === 'kitchen' ? 150 : (type === 'bath' ? 200 : 80);
            var mult = tier === 'luxury' ? 1.5 : 1.0;
            var total = sqft * base * mult;
            if(sqft) {
                jQuery('#estimate-result').html('Projected Investment: $' + total.toLocaleString() + '<br><small>Includes labor, materials, and management.</small>');
            }
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Sample Renovation', 'post_type' => 'gp_project', 'post_status' => 'publish'));
    }
}
new GrowthPress_Contractor();
