<?php
class GrowthPress_Roofing {
    public function __construct() {
        add_action( 'gp_emergency_booking', array( $this, 'handle_emergency' ) );
        add_shortcode( 'gp_roofing_estimator', array( $this, 'render_roofing_estimator' ) );
    }

    public function render_roofing_estimator() {
        ob_start(); ?>
        <div class="gp-roofing-calc glass-card">
            <h3>Precision Roofing Estimate</h3>
            <div class="form-row">
                <label>Roof Surface Area (Squares)</label>
                <input type="number" id="gp-roof-squares" placeholder="e.g. 20">
            </div>
            <div class="form-row">
                <label>Material Quality</label>
                <select id="gp-roof-material">
                    <option value="standard">Standard Asphalt Shingle</option>
                    <option value="premium">Premium Architectural Shingle</option>
                    <option value="metal">Standing Seam Metal</option>
                </select>
            </div>
            <div class="form-row">
                <label>Roof Pitch</label>
                <select id="gp-roof-pitch">
                    <option value="flat">Flat / Low Slope</option>
                    <option value="standard">Standard (4/12 - 8/12)</option>
                    <option value="steep">Steep (>9/12)</option>
                </select>
            </div>
            <button onclick="calcRoofEstimate()" style="margin-top:15px;">Get Instant Quote</button>
            <div id="roof-estimate-result" style="margin-top:20px; font-weight:bold; color: #2563EB;"></div>
        </div>
        <script>
        function calcRoofEstimate() {
            var squares = jQuery('#gp-roof-squares').val();
            var material = jQuery('#gp-roof-material').val();
            var pitch = jQuery('#gp-roof-pitch').val();

            var basePrice = material === 'metal' ? 1200 : (material === 'premium' ? 600 : 400);
            var pitchMultiplier = pitch === 'steep' ? 1.3 : 1.0;

            var total = squares * basePrice * pitchMultiplier;
            if(squares) {
                jQuery('#roof-estimate-result').html('Estimated Investment: $' + Math.round(total).toLocaleString());
            }
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Emergency Leak Repair', 'post_type' => 'page', 'post_status' => 'publish'));
    }
}
new GrowthPress_Roofing();
