<?php
/**
 * GrowthPress Contractor Module - Enhanced
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
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-admin-tools',
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
        ) );
    }

    public function render_estimator() {
        ob_start(); ?>
        <div class="gp-estimator glass-card">
            <h3>Instant Project Estimator</h3>
            <p>Get a quick estimate for your next renovation project.</p>
            <div class="calc-row">
                <label>Total Square Footage</label>
                <input type="number" id="gp-sq-ft" placeholder="e.g. 500">
            </div>
            <div class="calc-row">
                <label>Material Quality</label>
                <select id="gp-material">
                    <option value="basic">Standard</option>
                    <option value="premium">High-End / Luxury</option>
                </select>
            </div>
            <button onclick="runEstimate()" style="margin-top: 15px;">Get Estimate</button>
            <div id="estimate-result" style="margin-top: 20px; font-weight: bold; color: #2563EB;"></div>
        </div>
        <script>
        function runEstimate() {
            var sqft = jQuery('#gp-sq-ft').val();
            var material = jQuery('#gp-material').val();
            var rate = material === 'premium' ? 120 : 50;
            var total = sqft * rate;
            if (sqft) {
                jQuery('#estimate-result').html('Estimated Project Cost: $' + total.toLocaleString() + '<br><small>Final price subject to onsite inspection.</small>');
            }
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function generate_sample_data() {
        $projects = array(
            'Modern Kitchen Remodel' => 'A complete overhaul of a 1950s kitchen into a modern chef\'s paradise.',
            'Luxury Bathroom Suite' => 'Transforming a standard bathroom into a spa-like retreat.'
        );
        foreach($projects as $title => $content) {
            wp_insert_post(array('post_title' => $title, 'post_content' => $content, 'post_type' => 'gp_project', 'post_status' => 'publish'));
        }
    }
}

new GrowthPress_Contractor();
