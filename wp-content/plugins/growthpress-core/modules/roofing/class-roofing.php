<?php
/**
 * Roofing Niche specialized Closer Tools - Ultra Elite v3.0
 */
class GrowthPress_Roofing {
    public function __construct() {
        add_shortcode('gp_roofing_estimator', array($this, 'render_roofing_estimator'));
    }

    public function render_roofing_estimator() {
        return '<div class="gp-estimator glass-card gp-reveal" style="border-left: 15px solid #475569; padding:80px 60px; background: linear-gradient(135deg, var(--surface), #F1F5F9);">
            <div style="text-align:center; margin-bottom:50px;">
                <div style="font-size:10px; font-weight:950; color:#475569; text-transform:uppercase; letter-spacing:3px; margin-bottom:15px;">ASSET PROTECTION ENGINE</div>
                <h3 class="text-gradient" style="font-size:2.8rem;">Elite Roof Replacement Estimator</h3>
                <p style="font-size:1.1rem; opacity:0.7; max-width:600px; margin:20px auto 0;">Determine your replacement investment based on material quality and structural complexity.</p>
            </div>

            <div id="roof-steps" class="glass-card" style="background:#FFF; padding:50px; border-radius:32px;">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:40px;">
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.5; letter-spacing:2px; display:block; margin-bottom:15px;">MATERIAL GRADE</label>
                        <select id="roof-mat" style="width:100%; height:70px; border-radius:18px; font-weight:700; border:2px solid #F1F5F9; padding:0 25px; font-size:16px;">
                            <option value="550">Architectural Shingle</option>
                            <option value="1100">Standing Seam Metal</option>
                            <option value="2200">Luxury Natural Slate</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.5; letter-spacing:2px; display:block; margin-bottom:15px;">TOTAL SQUARES (100sqft)</label>
                        <input type="number" id="roof-sqs" value="30" style="width:100%; height:70px; border-radius:18px; font-weight:700; border:2px solid #F1F5F9; padding:0 25px; font-size:16px;">
                    </div>
                </div>
                <div style="background:#F1F5F9; border:2px solid #E2E8F0; padding:40px; border-radius:28px; text-align:center; margin-bottom:40px;">
                    <div style="font-size:11px; font-weight:900; color:var(--secondary); opacity:0.6; letter-spacing:1px; margin-bottom:10px;">ESTIMATED REPLACEMENT INVESTMENT</div>
                    <div class="text-gradient" style="font-size:4rem; font-weight:950; color:#475569;">$<span id="roof-val">16,500</span></div>
                </div>
                <button class="gp-btn" style="width:100%; height:80px; font-size:20px; background:#475569;" onclick="jQuery(\'#roof-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Initiate Drone Site Survey</button>
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
        wp_insert_post(array('post_title' => 'Coastal Manor Slate', 'post_content' => 'High-stakes roof replacement for a heritage property.', 'post_type' => 'gp_project', 'post_status' => 'publish'));
    }
}
new GrowthPress_Roofing();
