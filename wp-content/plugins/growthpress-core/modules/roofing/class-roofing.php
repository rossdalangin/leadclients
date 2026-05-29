<?php
/**
 * Roofing Niche specialized Closer Tools - Ultra Elite v4.5
 */
class GrowthPress_Roofing {
    public function __construct() {
        add_shortcode('gp_roofing_estimator', array($this, 'render_roofing_estimator'));
    }

    public function render_roofing_estimator() {
        return '<div class="gp-estimator glass-card gp-reveal" style="border-left: 20px solid #475569; padding:100px 80px; background: linear-gradient(135deg, var(--surface), #F1F5F9);">
            <div style="text-align:center; margin-bottom:70px;">
                <div style="font-size:12px; font-weight:950; color:#475569; text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">ASSET PROTECTION ENGINE v4.5</div>
                <h3 class="text-gradient" style="font-size:3.5rem; line-height:1.0;">Elite Roof Replacement Estimator</h3>
                <p style="font-size:1.25rem; opacity:0.7; max-width:700px; margin:25px auto 0;">Determine your replacement investment based on high-authority material quality and structural complexity.</p>
            </div>

            <div id="roof-steps" class="glass-card" style="background:#FFF; padding:60px; border-radius:44px; box-shadow:0 30px 60px rgba(0,0,0,0.03);">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:40px; margin-bottom:40px;">
                    <div>
                        <label style="font-weight:950; font-size:12px; opacity:0.5; letter-spacing:2px; display:block; margin-bottom:15px;">MATERIAL SPECIFICATION</label>
                        <select id="roof-mat" style="width:100%; height:75px; border-radius:20px; font-weight:700; border:2px solid #F1F5F9; padding:0 25px; font-size:18px;">
                            <option value="650">Architectural Shingle</option>
                            <option value="1200">Standing Seam Metal</option>
                            <option value="2500">Luxury Natural Slate</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-weight:950; font-size:12px; opacity:0.5; letter-spacing:2px; display:block; margin-bottom:15px;">TOTAL SQUARES (100sqft)</label>
                        <input type="number" id="roof-sqs" value="35" style="width:100%; height:75px; border-radius:18px; font-weight:700; border:2px solid #F1F5F9; padding:0 25px; font-size:18px;">
                    </div>
                </div>
                <div style="background:#F1F5F9; border:2px solid #E2E8F0; padding:50px; border-radius:35px; text-align:center; margin-bottom:50px;">
                    <div style="font-size:12px; font-weight:950; color:var(--secondary); opacity:0.6; letter-spacing:1px; margin-bottom:15px;">ESTIMATED REPLACEMENT INVESTMENT</div>
                    <div class="text-gradient" style="font-size:5rem; font-weight:950; color:#475569; line-height:1; letter-spacing:-0.05em;">$<span id="roof-val">22,750</span></div>
                </div>
                <button class="gp-btn" style="width:100%; height:90px; font-size:22px; background:#475569;" onclick="jQuery(\'#roof-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Initiate Drone Site Survey</button>
            </div>
            <div id="gp-quiz-form" style="display:none;">[gp_lead_form]</div>
            <script>
                jQuery("#roof-mat, #roof-sqs").on("change input", function() {
                    var mat = parseInt(jQuery("#roof-mat").val());
                    var sqs = parseInt(jQuery("#roof-sqs").val());
                    jQuery("#roof-val").text((mat * sqs).toLocaleString());
                });
            </script>
        </div>';
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Coastal Heritage Re-Roof', 'post_content' => 'High-stakes roof replacement for a premium asset.', 'post_type' => 'gp_project', 'post_status' => 'publish'));
    }
}
new GrowthPress_Roofing();
