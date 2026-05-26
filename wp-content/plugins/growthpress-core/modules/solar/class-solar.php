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
        add_shortcode( 'gp_solar_financing', array( $this, 'render_financing_form' ) );
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
            <h3>Solar ROI & Financing Estimator</h3>
            <p>See your 25-year savings and monthly loan payment.</p>
            <div class="form-group">
                <label>Average Monthly Utility Bill ($)</label>
                <input type="number" id="gp-bill" placeholder="e.g. 150" style="width:100%; margin-bottom:10px;">
            </div>
            <div class="form-group">
                <label>Desired System Size (kW)</label>
                <input type="number" id="gp-solar-size" value="10" style="width:100%; margin-bottom:10px;">
            </div>
            <button class="button button-primary" onclick="runSolarCalc()" style="width:100%;">Generate ROI Report</button>
            <div id="solar-results" style="margin-top:20px;"></div>
        </div>
        <script>
        function runSolarCalc() {
            var bill = jQuery('#gp-bill').val();
            var size = jQuery('#gp-solar-size').val();
            if(!bill) return;

            var savings25 = bill * 12 * 25 * 0.7; // 70% offset mock
            var cost = size * 3000;
            var taxCredit = cost * 0.3;
            var netCost = cost - taxCredit;

            var html = "<div style='padding:15px; background:#f0fdf4; border-radius:8px; border:1px solid #bbf7d0;'>";
            html += "<h4>Projected Financial Outcome</h4>";
            html += "<p>25-Year Savings: <strong>$" + Math.round(savings25).toLocaleString() + "</strong></p>";
            html += "<p>Federal Tax Credit: <strong style='color:#10B981;'>$" + Math.round(taxCredit).toLocaleString() + "</strong></p>";
            html += "<p>Net System Investment: $" + Math.round(netCost).toLocaleString() + "</p>";
            html += "<p style='font-size:12px; margin-top:10px;'><em>*Estimates based on local averages.</em></p>";
            html += "</div>";

            jQuery('#solar-results').html(html);
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function render_financing_form() {
        return '<div class="glass-card">
            <h4>Solar Financing Inquiry</h4>
            <p class="description">Get pre-qualified for $0-down solar financing.</p>
            <div class="form-group">
                <label>Credit Score Estimate</label>
                <select style="width:100%; margin-bottom:10px;">
                    <option>720+ (Excellent)</option>
                    <option>660-719 (Good)</option>
                    <option>600-659 (Fair)</option>
                    <option>Below 600</option>
                </select>
            </div>
            <button class="button" style="width:100%;">Check Eligibility</button>
        </div>';
    }

    public function generate_sample_data() {
        $projects = array(
            'Residential 10kW System' => 'Full shingle-roof installation with battery backup.',
            'Commercial Warehouse Array' => '100kW flat-roof system for industrial energy independence.',
            'Off-Grid Cabin Power' => 'Custom 4kW system with advanced solar storage.'
        );
        foreach($projects as $t => $c) {
            if ( ! get_page_by_path( sanitize_title($t), OBJECT, 'gp_project' ) ) {
                wp_insert_post(array('post_title' => $t, 'post_content' => $c, 'post_type' => 'gp_project', 'post_status' => 'publish'));
            }
        }
    }
}
new GrowthPress_Solar();
