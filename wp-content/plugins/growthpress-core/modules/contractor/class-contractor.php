<?php
/**
 * GrowthPress Contractor Module - Map & Estimation Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Contractor {

    public function __construct() {
        add_action( 'init', array( $this, 'register_contractor_cpts' ) );
        add_shortcode( 'gp_contractor_estimator', array( $this, 'render_estimator' ) );
        add_shortcode( 'gp_service_area', array( $this, 'render_service_area' ) );
        add_shortcode( 'gp_project_tracker', array( $this, 'render_project_tracker' ) );
    }

    public function register_contractor_cpts() {
        register_post_type( 'gp_project', array(
            'labels'      => array( 'name' => 'Portfolio', 'singular_name' => 'Project' ),
            'public'      => true, 'show_ui' => true, 'menu_icon' => 'dashicons-admin-tools',
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
        ) );
    }

    public function render_service_area() {
        $area = get_option('gp_contractor_zip_codes', '90210, 90211, 90212');
        return '<div class="gp-map-box glass-card"><h3>Our Service Area</h3><p>We provide expert services in the following areas: ' . esc_html($area) . '</p><div id="gp-mock-map" style="background:#e2e8f0; height:200px; display:flex; align-items:center; justify-content:center; border-radius:8px;">[Interactive Map Integration]</div></div>';
    }

    public function render_project_tracker() {
        if ( ! is_user_logged_in() ) return '<p>Login to track your project.</p>';
        return '<div class="glass-card"><h3>Project Progress Tracker</h3><div style="height:10px; background:#f1f5f9; border-radius:5px; margin:20px 0;"><div style="width:65%; height:100%; background:#2563EB; border-radius:5px;"></div></div><p style="font-size:12px;">Stage: <strong>Active Construction (65% Complete)</strong></p></div>';
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
            <button onclick="calcEstimate()" style="margin-top:15px;">Calculate Quote</button>
            <div id="estimate-result" style="margin-top:20px; font-weight:bold; color: #2563EB;"></div>
        </div>
        <script>
        function calcEstimate() {
            var sqft = jQuery('#gp-sq-ft').val();
            var type = jQuery('#gp-renov-type').val();
            var base = type === 'kitchen' ? 150 : (type === 'bath' ? 200 : 80);
            var total = sqft * base;
            if(sqft) {
                jQuery('#estimate-result').html('Estimated Investment: $' + total.toLocaleString());
            }
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function generate_sample_data() {
        $portfolio = array(
            'Luxury Kitchen Remodel' => 'Total transformation with custom cabinetry and quartz surfaces.',
            'Master Suite Expansion' => 'Added 500 sq ft and a spa-inspired bathroom.',
            'Outdoor Living Space' => 'Custom deck and integrated outdoor kitchen.'
        );
        foreach($portfolio as $t => $c) {
            if ( ! get_page_by_path( sanitize_title($t), OBJECT, 'gp_project' ) ) {
                wp_insert_post(array('post_title' => $t, 'post_content' => $c, 'post_type' => 'gp_project', 'post_status' => 'publish'));
            }
        }
    }
}
new GrowthPress_Contractor();
