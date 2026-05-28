<?php
/**
 * Solar Niche specialized Closer Tools - Ultra Elite v4.0
 */
class GrowthPress_Solar {
    public function __construct() {
        add_shortcode('gp_solar_calculator', array($this, 'render_solar_calc'));
    }

    public function render_solar_calc() {
        return '<div class="gp-solar-calc glass-card gp-reveal" style="border-top: 15px solid #F59E0B; text-align:center; padding:100px 80px; background: linear-gradient(180deg, rgba(245,158,11,0.03) 0%, transparent 100%), var(--glass-bg);">
            <div style="text-align:center; margin-bottom:60px;">
                <div style="font-size:12px; font-weight:950; color:#F59E0B; text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">ENERGY INDEPENDENCE ENGINE v4.0</div>
                <h3 class="text-gradient" style="font-size:3.5rem; line-height:1.0;">Precision ROI Predictor</h3>
                <p style="font-size:1.2rem; opacity:0.7; max-width:650px; margin:25px auto 0;">Determine your 25-year energy equity and federal incentive eligibility with neural precision.</p>
            </div>

            <div style="background:#FFF; border-radius:44px; border:1px solid #F1F5F9; padding:60px; margin-bottom:50px; position:relative; box-shadow:0 30px 60px rgba(0,0,0,0.03);">
                <div style="display:grid; grid-template-columns: 1.2fr 1fr; gap:60px; text-align:left; align-items:center;">
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:25px;">MONTHLY UTILITY LOAD ($)</label>
                        <input type="range" min="100" max="2000" value="350" class="solar-slider" id="solar-input" style="height:12px; background:#F1F5F9; border-radius:10px; appearance:none; width:100%;">
                        <div style="font-size:42px; font-weight:950; color:var(--secondary); margin-top:25px;">$<span id="solar-val">350</span></div>
                    </div>
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">ESTIMATED 25-YR EQUITY</label>
                        <div class="text-gradient" style="font-size:5rem; font-weight:950; line-height:1;">$<span id="roi-val">73,500</span></div>
                        <div style="margin-top:20px; display:flex; align-items:center; gap:10px;">
                            <div style="width:8px; height:8px; background:#10B981; border-radius:50%;"></div>
                            <span style="font-size:11px; font-weight:900; color:#10B981; letter-spacing:1px;">INC. 30% FEDERAL TAX CREDIT</span>
                        </div>
                    </div>
                </div>
                <!-- Visual Performance Chart -->
                <div style="margin-top:60px; height:150px; display:flex; align-items:flex-end; gap:8px;">
                    <div style="flex:1; background:#F1F5F9; height:25%; border-radius:6px;"></div>
                    <div style="flex:1; background:#F1F5F9; height:40%; border-radius:6px;"></div>
                    <div style="flex:1; background:#F1F5F9; height:55%; border-radius:6px;"></div>
                    <div style="flex:1; background:#F59E0B; height:70%; border-radius:6px; box-shadow:0 10px 30px rgba(245,158,11,0.3);"></div>
                    <div style="flex:1; background:#F59E0B; height:85%; border-radius:6px; box-shadow:0 10px 30px rgba(245,158,11,0.3);"></div>
                    <div style="flex:1; background:#10B981; height:100%; border-radius:6px; box-shadow:0 15px 40px rgba(16,185,129,0.4);"></div>
                </div>
            </div>

            <button class="gp-btn" style="width:100%; height:85px; font-size:20px;" onclick="jQuery(\'#gp-quiz-step-1\').hide(); jQuery(\'#gp-quiz-form\').show();">Execute Engineering Audit</button>

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
        wp_insert_post(array('post_title' => 'High-Performance Array', 'post_content' => 'Strategic solar deployment for a luxury residential estate.', 'post_type' => 'gp_project', 'post_status' => 'publish'));
    }
}
new GrowthPress_Solar();
