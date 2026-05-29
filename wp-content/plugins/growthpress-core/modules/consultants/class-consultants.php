<?php
/**
 * Consultants Niche specialized Closer Tools - Ultra Elite v4.5
 */
class GrowthPress_Consultants {
    public function __construct() {
        add_shortcode('gp_consulting_audit', array($this, 'render_audit'));
    }

    public function render_audit() {
        return '<div class="gp-consulting-audit glass-card gp-reveal" style="padding:100px 80px; border-top: 20px solid var(--primary); background: linear-gradient(135deg, var(--surface), #F8FAFC);">
            <div style="text-align:center; margin-bottom:70px;">
                <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;">OPERATIONAL INTELLIGENCE AUDIT v4.5</div>
                <h3 class="text-gradient" style="font-size:3.5rem; line-height:1.0;">Strategic Efficiency Engine</h3>
                <p style="font-size:1.25rem; opacity:0.7; max-width:750px; margin:25px auto 0;">Our neural-calibrated engine analyzes your current business model to identify high-impact automation and scaling opportunities across all departments.</p>
            </div>

            <div id="consult-steps" class="glass-card" style="background:#FFF; padding:60px; border-radius:44px; box-shadow:0 30px 60px rgba(0,0,0,0.03);">
                <div style="margin-bottom:45px;">
                    <label style="font-weight:950; font-size:12px; opacity:0.5; letter-spacing:2px; display:block; margin-bottom:20px;">PRIMARY SCALING BOTTLENECK</label>
                    <textarea id="consult-challenge" style="height:180px; margin-bottom:30px; border-radius:28px; border:2px solid #F1F5F9; padding:30px; font-size:16px; line-height:1.7;" placeholder="e.g. Our current client intake protocol is manual and high-value leads are dropping out of the CRM funnel before booking..."></textarea>
                </div>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:50px;">
                    <div style="background:var(--primary-glow); padding:30px; border-radius:24px; border:1px solid rgba(37,99,235,0.08); text-align:center;">
                        <div style="font-size:11px; font-weight:900; color:var(--primary); opacity:0.6; letter-spacing:1px; margin-bottom:10px;">ANALYSIS DEPTH</div>
                        <div style="font-size:28px; font-weight:950; color:var(--primary);">ULTIMATE TIER</div>
                    </div>
                    <div style="background:var(--primary-glow); padding:30px; border-radius:24px; border:1px solid rgba(37,99,235,0.08); text-align:center;">
                        <div style="font-size:11px; font-weight:900; color:var(--primary); opacity:0.6; letter-spacing:1px; margin-bottom:10px;">NEURAL STATUS</div>
                        <div style="font-size:28px; font-weight:950; color:var(--primary);">SYNCHRONIZED</div>
                    </div>
                </div>
                <button class="gp-btn" style="width:100%; height:95px; font-size:24px; border-radius:25px;" onclick="jQuery(\'#consult-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Execute Strategic Intelligence Analysis</button>
            </div>
            <div id="gp-quiz-form" style="display:none;">[gp_lead_form]</div>
            <div style="margin-top:40px; font-size:11px; opacity:0.4; text-align:center; line-height:1.8; max-width:600px; margin-left:auto; margin-right:auto;">NOTICE: This strategic terminal is for executive audit purposes and does not constitute a formal binding management consultation. All proprietary data is handled via AES-256 encrypted neural nodes.</div>
        </div>';
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Global SaaS Expansion', 'post_content' => 'High-authority consulting for a Series B technology entity moving into the North American market.', 'post_type' => 'gp_project', 'post_status' => 'publish'));
    }
}
new GrowthPress_Consultants();
