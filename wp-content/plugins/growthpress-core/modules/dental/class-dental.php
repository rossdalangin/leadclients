<?php
/**
 * Dental Niche specialized Closer Tools - Ultra Elite v4.0
 */
class GrowthPress_Dental {
    public function __construct() {
        add_shortcode('gp_insurance_optimizer', array($this, 'render_insurance_optimizer'));
        add_shortcode('gp_smile_gallery', array($this, 'render_smile_gallery'));
    }

    public function render_insurance_optimizer() {
        return '<div class="gp-insurance-optimizer glass-card gp-reveal" style="padding:100px 80px; border-left: 15px solid #0EA5E9; background: linear-gradient(135deg, var(--surface), #F0F9FF);">
            <div style="text-align:center; margin-bottom:60px;">
                <div style="font-size:12px; font-weight:950; color:#0EA5E9; text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">COVERAGE INTELLIGENCE v4.0</div>
                <h3 class="text-gradient" style="font-size:3.5rem; line-height:1.0;">Insurance Optimization Engine</h3>
                <p style="font-size:1.2rem; opacity:0.7; max-width:650px; margin:25px auto 0;">Our neural engine instantly verifies your coverage parameters to maximize treatment benefits and minimize out-of-pocket friction.</p>
            </div>

            <div id="ins-steps" class="glass-card" style="background:#FFF; padding:60px; border-radius:44px; box-shadow:0 30px 60px rgba(0,0,0,0.03);">
                <div style="margin-bottom:40px;">
                    <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:20px;">SELECT ELITE PROVIDER</label>
                    <select id="ins-provider" style="width:100%; height:75px; border-radius:18px; font-weight:700; border:2px solid #F1F5F9; padding:0 30px; font-size:18px;">
                        <option value="Delta">Delta Dental Elite PPO</option>
                        <option value="MetLife">MetLife Executive Premium</option>
                        <option value="Cigna">Cigna Platinum Advantage</option>
                        <option value="Other">Custom Enterprise Coverage</option>
                    </select>
                </div>
                <div style="background:rgba(14, 165, 233, 0.05); border:2px solid rgba(14, 165, 233, 0.1); padding:50px; border-radius:35px; text-align:center; margin-bottom:50px;">
                    <div style="font-size:11px; font-weight:950; opacity:0.5; letter-spacing:2px; margin-bottom:15px;">ESTIMATED COVERAGE INTEL</div>
                    <div class="text-gradient" style="font-size:5rem; font-weight:950; color:#0EA5E9; line-height:1;">65% - 98%</div>
                </div>
                <button class="gp-btn" style="width:100%; height:85px; font-size:20px; background:#0EA5E9;" onclick="jQuery(\'#ins-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Finalize Coverage Audit</button>
            </div>
            <div id="gp-quiz-form" style="display:none;">[gp_lead_form]</div>
        </div>';
    }

    public function render_smile_gallery() {
        return '<div class="gp-smile-gallery" style="margin-top:120px;">
            <div style="text-align:center; margin-bottom:100px;">
                <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">TRANSFORMATION ARCHIVE v4.0</div>
                <h2 class="text-gradient" style="font-size:4.5rem; line-height:0.9;">Elite Patient Outcomes</h2>
                <p style="max-width:700px; margin:25px auto 0; font-size:1.3rem; opacity:0.7;">Visual confirmation of our precision cosmetic engineering and clinical excellence.</p>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:60px;">
                <div class="glass-card gp-reveal" style="padding:0; border-radius:50px; overflow:hidden;">
                    <div style="height:550px; background:#F1F5F9; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:950; opacity:0.2; letter-spacing:2px;">TRANSFORMATION: FULL RECONSTRUCTION</div>
                    <div style="padding:50px; text-align:center; border-top:1px solid #F1F5F9;">
                        <h4 style="margin:0; font-size:26px; font-weight:950; letter-spacing:-0.03em;">Full-Arch Elite Sequence</h4>
                        <p style="font-size:14px; opacity:0.5; margin-top:15px; font-weight:800; letter-spacing:1px;">TIER: ARCHITECTURAL RECONSTRUCTION</p>
                    </div>
                </div>
                <div class="glass-card gp-reveal" style="padding:0; border-radius:50px; overflow:hidden;" data-delay="300">
                    <div style="height:550px; background:#F1F5F9; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:950; opacity:0.2; letter-spacing:2px;">TRANSFORMATION: AESTHETIC VENEERS</div>
                    <div style="padding:50px; text-align:center; border-top:1px solid #F1F5F9;">
                        <h4 style="margin:0; font-size:26px; font-weight:950; letter-spacing:-0.03em;">Minimal Prep Ceramic Sequence</h4>
                        <p style="font-size:14px; opacity:0.5; margin-top:15px; font-weight:800; letter-spacing:1px;">TIER: COSMETIC PRECISION</p>
                    </div>
                </div>
            </div>
        </div>';
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Sarah V. (Full Reconstruction)', 'post_content' => 'High-authority smile transformation for a global leadership profile.', 'post_type' => 'gp_project', 'post_status' => 'publish'));
    }
}
new GrowthPress_Dental();
