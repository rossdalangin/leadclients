<?php
/**
 * Contractor Niche specialized Closer Tools - Ultra Elite v3.0
 */
class GrowthPress_Contractor {
    public function __construct() {
        add_shortcode('gp_contractor_estimator', array($this, 'render_estimator'));
    }

    public function render_estimator() {
        return '<div class="gp-estimator glass-card gp-reveal" style="border-left: 15px solid #EF4444; padding:80px 60px; background: linear-gradient(135deg, var(--surface), #FFF5F5);">
            <div style="text-align:center; margin-bottom:50px;">
                <div style="font-size:10px; font-weight:950; color:#EF4444; text-transform:uppercase; letter-spacing:3px; margin-bottom:15px;">PRECISION QUOTATION ENGINE</div>
                <h3 class="text-gradient" style="font-size:2.8rem;">Elite Renovation Estimator</h3>
                <p style="font-size:1.1rem; opacity:0.7; max-width:600px; margin:20px auto 0;">Instant baseline engineering audit for your high-ticket renovation project.</p>
            </div>

            <div id="est-steps" class="glass-card" style="background:#FFF; padding:50px; border-radius:32px;">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:40px;">
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.5; letter-spacing:2px; display:block; margin-bottom:15px;">PROJECT TYPE</label>
                        <select id="proj-type" style="width:100%; height:70px; border-radius:18px; font-weight:700; border:2px solid #F1F5F9; padding:0 25px; font-size:16px;">
                            <option value="250">Kitchen Transformation</option>
                            <option value="180">Master Bath Elite</option>
                            <option value="350">Full Structural Overhaul</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.5; letter-spacing:2px; display:block; margin-bottom:15px;">SQ FOOTAGE (EST)</label>
                        <input type="number" id="proj-sqft" value="600" style="width:100%; height:70px; border-radius:18px; font-weight:700; border:2px solid #F1F5F9; padding:0 25px; font-size:16px;">
                    </div>
                </div>
                <div style="background:#FEF2F2; border:2px solid #FEE2E2; padding:40px; border-radius:28px; text-align:center; margin-bottom:40px;">
                    <div style="font-size:11px; font-weight:900; color:#991B1B; opacity:0.6; letter-spacing:1px; margin-bottom:10px;">ESTIMATED INVESTMENT RANGE</div>
                    <div class="text-gradient" style="font-size:4rem; font-weight:950; color:#EF4444;">$<span id="est-val">150,000</span></div>
                </div>
                <button class="gp-btn" style="width:100%; height:80px; font-size:20px; background:#EF4444;" onclick="jQuery(\'#est-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Request Engineering Audit</button>
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
        wp_insert_post(array('post_title' => 'Bel Air Transformation', 'post_content' => 'Complete structural and aesthetic transformation of a luxury estate.', 'post_type' => 'gp_project', 'post_status' => 'publish'));
    }
}
new GrowthPress_Contractor();
