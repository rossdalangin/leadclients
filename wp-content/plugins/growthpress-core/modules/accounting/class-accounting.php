<?php
/**
 * Accounting Niche specialized Closer Tools - Ultra Elite v4.5
 */
class GrowthPress_Accounting {
    public function __construct() {
        add_shortcode('gp_tax_estimator', array($this, 'render_tax_estimator'));
    }

    public function render_tax_estimator() {
        return '<div class="gp-tax-estimator glass-card gp-reveal" style="border-right: 20px solid #7C3AED; padding:120px 80px; background: linear-gradient(135deg, var(--surface), #F5F3FF);">
            <div style="text-align:center; margin-bottom:80px;">
                <div style="font-size:12px; font-weight:950; color:#7C3AED; text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">WEALTH PRESERVATION ENGINE v4.5</div>
                <h3 class="text-gradient" style="font-size:4rem; line-height:1.0;">AI Tax Optimization Estimator</h3>
                <p style="font-size:1.3rem; opacity:0.7; max-width:700px; margin:25px auto 0;">Determine your potential tax optimization nodes and capital preservation benefits based on your current corporate profile.</p>
            </div>

            <div id="tax-steps" class="glass-card" style="background:#FFF; padding:60px; border-radius:44px; box-shadow:0 30px 60px rgba(0,0,0,0.03);">
                <div style="margin-bottom:40px;">
                    <label style="font-weight:950; font-size:12px; opacity:0.5; letter-spacing:2px; display:block; margin-bottom:20px;">ESTIMATED ANNUAL REVENUE</label>
                    <select id="rev-select" style="width:100%; height:85px; border-radius:20px; font-weight:700; border:2px solid #F1F5F9; padding:0 35px; font-size:20px;">
                        <option value="18000">$250k - $750k</option>
                        <option value="65000">$750k - $3M</option>
                        <option value="215000">$3M - $15M</option>
                        <option value="480000">$15M+</option>
                    </select>
                </div>
                <div style="background:rgba(124, 58, 237, 0.05); border:2px solid rgba(124, 58, 237, 0.1); padding:60px; border-radius:40px; text-align:center; margin-bottom:60px;">
                    <div style="font-size:12px; font-weight:950; color:#7C3AED; opacity:0.6; letter-spacing:2px; margin-bottom:15px;">ESTIMATED SAVINGS POTENTIAL</div>
                    <div class="text-gradient" style="font-size:6rem; font-weight:950; color:#7C3AED; line-height:1; letter-spacing:-0.05em;">$<span id="tax-savings">18,000</span></div>
                </div>
                <button class="gp-btn" style="width:100%; height:95px; font-size:24px; background:#7C3AED; border-radius:25px;" onclick="jQuery(\'#tax-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Secure High-Stakes Financial Audit</button>
            </div>
            <div id="gp-quiz-form" style="display:none;">[gp_lead_form]</div>
            <script>
                jQuery("#rev-select").on("change", function() {
                    jQuery("#tax-savings").text(parseInt(jQuery(this).val()).toLocaleString());
                });
            </script>
        </div>';
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Nexus Financial Restructuring', 'post_content' => 'Full capital optimization for a high-growth SaaS entity moving into Series C.', 'post_type' => 'gp_project', 'post_status' => 'publish'));
    }
}
new GrowthPress_Accounting();
