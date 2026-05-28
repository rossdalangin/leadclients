<?php
/**
 * Accounting Niche specialized Closer Tools - Ultra Elite v3.0
 */
class GrowthPress_Accounting {
    public function __construct() {
        add_shortcode('gp_tax_estimator', array($this, 'render_tax_estimator'));
    }

    public function render_tax_estimator() {
        return '<div class="gp-tax-estimator glass-card gp-reveal" style="border-right: 15px solid #7C3AED; padding:80px 60px; background: linear-gradient(135deg, var(--surface), #F5F3FF);">
            <div style="text-align:center; margin-bottom:50px;">
                <div style="font-size:10px; font-weight:950; color:#7C3AED; text-transform:uppercase; letter-spacing:3px; margin-bottom:15px;">WEALTH PRESERVATION ENGINE</div>
                <h3 class="text-gradient" style="font-size:2.8rem;">AI Tax Savings Estimator</h3>
                <p style="font-size:1.1rem; opacity:0.7; max-width:600px; margin:20px auto 0;">Determine your potential tax optimization benefits based on your current corporate revenue profile.</p>
            </div>

            <div id="tax-steps" class="glass-card" style="background:#FFF; padding:50px; border-radius:32px;">
                <div style="margin-bottom:30px;">
                    <label style="font-weight:950; font-size:11px; opacity:0.5; letter-spacing:2px; display:block; margin-bottom:15px;">ESTIMATED ANNUAL REVENUE</label>
                    <select id="rev-select" style="width:100%; height:70px; border-radius:18px; font-weight:700; border:2px solid #F1F5F9; padding:0 25px; font-size:16px;">
                        <option value="12000">$250k - $500k</option>
                        <option value="45000">$500k - $2M</option>
                        <option value="185000">$2M - $10M</option>
                        <option value="420000">$10M+</option>
                    </select>
                </div>
                <div style="background:rgba(124, 58, 237, 0.05); border:2px solid rgba(124, 58, 237, 0.1); padding:40px; border-radius:28px; text-align:center; margin-bottom:40px;">
                    <div style="font-size:11px; font-weight:800; color:#7C3AED; opacity:0.6; letter-spacing:1px; margin-bottom:10px;">ESTIMATED SAVINGS POTENTIAL</div>
                    <div class="text-gradient" style="font-size:4rem; font-weight:950; color:#7C3AED;">$<span id="tax-savings">12,000</span></div>
                </div>
                <button class="gp-btn" style="width:100%; height:80px; font-size:20px; background:#7C3AED;" onclick="jQuery(\'#tax-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Secure High-Level Financial Audit</button>
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
        wp_insert_post(array('post_title' => 'Nexus Restructuring', 'post_content' => 'Full restructuring for a high-growth SaaS entity.', 'post_type' => 'gp_project', 'post_status' => 'publish'));
    }
}
new GrowthPress_Accounting();
