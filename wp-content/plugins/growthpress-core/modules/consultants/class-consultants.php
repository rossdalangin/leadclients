<?php
/**
 * Consultants Niche specialized Closer Tools
 */
class GrowthPress_Consultants {
    public function __construct() {
        add_shortcode('gp_consulting_audit', array($this, 'render_audit'));
    }

    public function render_audit() {
        return '<div class="gp-consulting-audit glass-card" style="padding:60px; border-top: 10px solid var(--primary);">
            <h3 class="text-gradient">Strategic Efficiency Audit</h3>
            <p>Our AI analyzes your business model to identify high-impact automation opportunities.</p>
            <div id="consult-steps" style="margin-top:40px;">
                <div style="margin-bottom:30px;">
                    <label style="font-weight:700; font-size:11px; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:10px;">PRIMARY BUSINESS CHALLENGE</label>
                    <textarea id="consult-challenge" style="height:100px; margin-bottom:20px;" placeholder="e.g. Manual client onboarding is taking too long..."></textarea>
                </div>
                <button class="gp-btn" style="width:100%;" onclick="jQuery(\'#consult-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Analyze My Model</button>
            </div>
            <div id="gp-quiz-form" style="display:none;">[gp_lead_form]</div>
        </div>';
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'SaaS Global Expansion', 'post_content' => 'Strategic consulting for a Series B entity moving into the EMEA market.', 'post_type' => 'gp_project', 'post_status' => 'publish'));
    }
}
new GrowthPress_Consultants();
