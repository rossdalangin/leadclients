<?php
/**
 * Solar Niche specialized Closer Tools - Ultra Elite v4.0
 */
class GrowthPress_Solar {
    public function __construct() {
        add_shortcode('gp_solar_calculator', array($this, 'render_solar_calc'));
    }

    public function render_solar_calc() {
        return '<div class="gp-solar-calc glass-card gp-reveal" style="border-top: 20px solid #F59E0B; text-align:center; padding:120px 80px; background: linear-gradient(180deg, rgba(245,158,11,0.04) 0%, transparent 100%), var(--glass-bg);">
            <div style="text-align:center; margin-bottom:80px;">
                <div style="font-size:12px; font-weight:950; color:#F59E0B; text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;">ENERGY INDEPENDENCE ENGINE v4.0</div>
                <h3 class="text-gradient" style="font-size:4rem; line-height:1.0;">Precision ROI Predictor</h3>
                <p style="font-size:1.3rem; opacity:0.7; max-width:700px; margin:25px auto 0;">Determine your 25-year energy equity and federal incentive eligibility with neural precision modeling.</p>
            </div>

            <div style="background:#FFF; border-radius:50px; border:1px solid #F1F5F9; padding:80px; margin-bottom:60px; position:relative; box-shadow:0 40px 80px rgba(0,0,0,0.04);">
                <div style="display:grid; grid-template-columns: 1.3fr 1fr; gap:80px; text-align:left; align-items:center;">
                    <div>
                        <label style="font-weight:950; font-size:12px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:30px;">MONTHLY UTILITY LOAD ($)</label>
                        <input type="range" min="100" max="2500" value="400" class="solar-slider" id="solar-input" style="height:15px; background:#F1F5F9; border-radius:15px; appearance:none; width:100%;">
                        <div style="font-size:54px; font-weight:950; color:var(--secondary); margin-top:35px; letter-spacing:-0.04em;">$<span id="solar-val">400</span></div>
                    </div>
                    <div>
                        <label style="font-weight:950; font-size:12px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:15px;">ESTIMATED 25-YR EQUITY</label>
                        <div class="text-gradient" style="font-size:6rem; font-weight:950; line-height:1; letter-spacing:-0.06em;">$<span id="roi-val">84,000</span></div>
                        <div style="margin-top:25px; display:flex; align-items:center; gap:12px;">
                            <div style="width:12px; height:12px; background:#10B981; border-radius:50%; box-shadow:0 0 10px rgba(16,185,129,0.4);"></div>
                            <span style="font-size:12px; font-weight:900; color:#10B981; letter-spacing:1px;">INC. 30% FEDERAL TAX CREDIT</span>
                        </div>
                    </div>
                </div>
                <!-- Neural Performance Chart -->
                <div style="margin-top:80px; height:180px; display:flex; align-items:flex-end; gap:10px;">
                    <div style="flex:1; background:#F1F5F9; height:20%; border-radius:8px;"></div>
                    <div style="flex:1; background:#F1F5F9; height:35%; border-radius:8px;"></div>
                    <div style="flex:1; background:#F1F5F9; height:50%; border-radius:8px;"></div>
                    <div style="flex:1; background:#F59E0B; height:65%; border-radius:8px; box-shadow:0 15px 40px rgba(245,158,11,0.4);"></div>
                    <div style="flex:1; background:#F59E0B; height:80%; border-radius:8px; box-shadow:0 15px 40px rgba(245,158,11,0.4);"></div>
                    <div style="flex:1; background:#10B981; height:100%; border-radius:8px; box-shadow:0 20px 50px rgba(16,185,129,0.5);"></div>
                </div>
            </div>

            <button class="gp-btn" style="width:100%; height:95px; font-size:24px; border-radius:25px;" onclick="jQuery(\'#gp-quiz-step-1\').hide(); jQuery(\'#gp-quiz-form\').show();">Initiate Precision Engineering Audit</button>

            <script>
                jQuery("#solar-input").on("input", function() {
                    var v = parseInt(jQuery(this).val());
                    jQuery("#solar-val").text(v);
                    jQuery("#roi-val").text((v * 12 * 25 * 0.75).toLocaleString());
                });
            </script>
        </div>';
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Elite Residential Array', 'post_content' => 'Full-scale neural-optimized solar deployment for a premium estate.', 'post_type' => 'gp_project', 'post_status' => 'publish'));
    }
}
new GrowthPress_Solar();
