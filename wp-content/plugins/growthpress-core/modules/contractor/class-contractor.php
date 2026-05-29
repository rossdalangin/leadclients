<?php
/**
 * Contractor Niche specialized Closer Tools - Ultra Elite v4.5
 */
class GrowthPress_Contractor {
    public function __construct() {
        add_shortcode('gp_contractor_estimator', array($this, 'render_estimator'));
    }

    public function render_estimator() {
        return '<div class="gp-estimator glass-card gp-reveal" style="border-left: 20px solid #EF4444; padding:100px 80px; background: linear-gradient(135deg, var(--surface), #FFF5F5);">
            <div style="text-align:center; margin-bottom:70px;">
                <div style="font-size:12px; font-weight:950; color:#EF4444; text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">PRECISION QUOTATION ENGINE v4.5</div>
                <h3 class="text-gradient" style="font-size:3.5rem; line-height:1.0;">Elite Renovation Estimator</h3>
                <p style="font-size:1.25rem; opacity:0.7; max-width:700px; margin:25px auto 0;">Instant baseline engineering audit for your high-ticket renovation project using neural estimation logic.</p>
            </div>

            <div id="est-steps" class="glass-card" style="background:#FFF; padding:60px; border-radius:44px; box-shadow:0 30px 60px rgba(0,0,0,0.03);">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:40px;">
                    <div>
                        <label style="font-weight:950; font-size:12px; opacity:0.5; letter-spacing:2px; display:block; margin-bottom:20px;">PROJECT CLASSIFICATION</label>
                        <select id="proj-type" style="width:100%; height:75px; border-radius:20px; font-weight:700; border:2px solid #F1F5F9; padding:0 25px; font-size:18px;">
                            <option value="250">Kitchen Transformation</option>
                            <option value="180">Master Bath Elite</option>
                            <option value="350">Full Structural Overhaul</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-weight:950; font-size:12px; opacity:0.5; letter-spacing:2px; display:block; margin-bottom:20px;">SQ FOOTAGE (EST)</label>
                        <input type="number" id="proj-sqft" value="750" style="width:100%; height:75px; border-radius:20px; font-weight:700; border:2px solid #F1F5F9; padding:0 25px; font-size:18px;">
                    </div>
                </div>
                <div style="background:#FEF2F2; border:2px solid #FEE2E2; padding:50px; border-radius:35px; text-align:center; margin-bottom:50px;">
                    <div style="font-size:12px; font-weight:950; color:#991B1B; opacity:0.6; letter-spacing:1px; margin-bottom:15px;">ESTIMATED INVESTMENT RANGE</div>
                    <div class="text-gradient" style="font-size:5rem; font-weight:950; color:#EF4444; line-height:1; letter-spacing:-0.05em;">$<span id="est-val">187,500</span></div>
                </div>
                <button class="gp-btn" style="width:100%; height:90px; font-size:22px; background:#EF4444;" onclick="jQuery(\'#est-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Request Engineering Audit</button>
            </div>
            <div id="gp-quiz-form" style="display:none;">[gp_lead_form]</div>
            <script>
                jQuery("#proj-type, #proj-sqft").on("change input", function() {
                    var rate = parseInt(jQuery("#proj-type").val());
                    var sqft = parseInt(jQuery("#proj-sqft").val());
                    jQuery("#est-val").text((rate * sqft).toLocaleString());
                });
            </script>
        </div>';
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Elite Transformation', 'post_content' => 'Complete structural overhaul of a luxury residential asset.', 'post_type' => 'gp_project', 'post_status' => 'publish'));
    }
}
new GrowthPress_Contractor();
