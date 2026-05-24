<?php
/**
 * GrowthPress Solar Module - Enhanced
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

    public function render_calculator() {
        ob_start(); ?>
        <div class="gp-solar-calc glass-card">
            <h3>Solar Savings & ROI Estimator</h3>
            <div class="calc-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label>Average Monthly Bill ($)</label>
                    <input type="number" id="gp-bill" value="150">

                    <label>Roof Orientation</label>
                    <select id="gp-orientation">
                        <option value="south">South Facing (Ideal)</option>
                        <option value="east_west">East/West</option>
                        <option value="north">North Facing</option>
                    </select>

                    <button onclick="runSolarCalc()" style="margin-top: 15px;">Calculate Savings</button>
                </div>
                <div id="solar-results" style="background: #f8fafc; padding: 15px; border-radius: 8px;">
                    <p>Enter your details to see estimated ROI.</p>
                </div>
            </div>
            <div class="ai-consultant" style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px;">
                <h4>Ask our AI Energy Consultant</h4>
                <input type="text" id="gp-solar-query" placeholder="e.g. Is solar worth it in cloudy climates?">
                <button onclick="askSolarAI()">Consult AI</button>
                <div id="solar-ai-response"></div>
            </div>
        </div>
        <script>
        function runSolarCalc() {
            var bill = jQuery('#gp-bill').val();
            var orient = jQuery('#gp-orientation').val();
            var multiplier = orient === 'south' ? 0.9 : (orient === 'east_west' ? 0.7 : 0.4);
            var savings = bill * 12 * multiplier;
            jQuery('#solar-results').html('<strong>Estimated Yearly Savings:</strong> $' + savings.toFixed(2) + '<br><strong>System Payback:</strong> ' + (18000/savings).toFixed(1) + ' years');
        }

        function askSolarAI() {
            var q = jQuery('#gp-solar-query').val();
            jQuery('#solar-ai-response').text('Consulting...');
            jQuery.post(gp_ajax.ajaxurl, {
                action: 'gp_solar_ai_consult',
                query: q
            }, function(res) {
                if(res.success) jQuery('#solar-ai-response').html('<p>' + res.data + '</p>');
            });
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_solar_ai() {
        $query = sanitize_text_field($_POST['query']);
        $ai = GrowthPress_AI::get_instance();
        $response = $ai->call_ai($query, "You are a specialized solar energy consultant. Help the user understand the benefits and ROI of solar power.");
        wp_send_json_success($response);
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Residential Solar Installation', 'post_type' => 'gp_project', 'post_status' => 'publish'));
    }
}

new GrowthPress_Solar();
