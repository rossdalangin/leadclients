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
        return '<div class="gp-insurance-optimizer glass-card gp-reveal" style="padding:120px 80px; border-left: 20px solid #0EA5E9; background: linear-gradient(135deg, var(--surface), #F0F9FF);">
            <div style="text-align:center; margin-bottom:80px;">
                <div style="font-size:12px; font-weight:950; color:#0EA5E9; text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">CLINICAL COVERAGE INTELLIGENCE v4.0</div>
                <h3 class="text-gradient" style="font-size:4rem; line-height:1.0;">Insurance Optimization Engine</h3>
                <p style="font-size:1.3rem; opacity:0.7; max-width:700px; margin:25px auto 0;">Our neural engine instantly verifies your coverage parameters to maximize clinical benefits and eliminate financial friction.</p>
            </div>

            <div id="ins-steps" class="glass-card" style="background:#FFF; padding:80px; border-radius:50px; box-shadow:0 40px 80px rgba(0,0,0,0.04);">
                <div style="margin-bottom:50px;">
                    <label style="font-weight:950; font-size:12px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:25px;">SELECT ELITE PROVIDER NETWORK</label>
                    <select id="ins-provider" style="width:100%; height:85px; border-radius:20px; font-weight:700; border:2px solid #F1F5F9; padding:0 35px; font-size:20px;">
                        <option value="Delta">Delta Dental Strategic PPO</option>
                        <option value="MetLife">MetLife Executive Elite</option>
                        <option value="Cigna">Cigna Platinum Advantage</option>
                        <option value="Other">Custom Global Enterprise Coverage</option>
                    </select>
                </div>
                <div style="background:rgba(14, 165, 233, 0.04); border:2px solid rgba(14, 165, 233, 0.08); padding:60px; border-radius:40px; text-align:center; margin-bottom:60px;">
                    <div style="font-size:12px; font-weight:950; opacity:0.5; letter-spacing:2px; margin-bottom:20px;">ESTIMATED COVERAGE INTEL</div>
                    <div class="text-gradient" style="font-size:6rem; font-weight:950; color:#0EA5E9; line-height:1; letter-spacing:-0.05em;">65% - 98%</div>
                </div>
                <button class="gp-btn" style="width:100%; height:95px; font-size:24px; background:#0EA5E9; border-radius:25px;" onclick="jQuery(\'#ins-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Execute Coverage Protocol</button>
            </div>
            <div id="gp-quiz-form" style="display:none;">[gp_lead_form]</div>
        </div>';
    }

    public function render_smile_gallery() {
        return '<div class="gp-smile-gallery" style="margin-top:150px;">
            <div style="text-align:center; margin-bottom:120px;">
                <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;">TRANSFORMATION ARCHIVE v4.0</div>
                <h2 class="text-gradient" style="font-size:4.5rem; line-height:0.9; letter-spacing:-0.07em;">Elite Patient Transformations</h2>
                <p style="max-width:800px; margin:30px auto 0; font-size:1.4rem; opacity:0.7;">Visual confirmation of our precision reconstructive engineering and aesthetic excellence.</p>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:80px;">
                <div class="glass-card gp-reveal" style="padding:0; border-radius:60px; overflow:hidden;">
                    <div style="height:600px; background:#F1F5F9; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:950; opacity:0.15; letter-spacing:3px;">INTEL: FULL-ARCH RECONSTRUCTION</div>
                    <div style="padding:60px; text-align:center; border-top:1px solid #F1F5F9;">
                        <h4 style="margin:0; font-size:30px; font-weight:950; letter-spacing:-0.04em;">Architectural Smile Sequence</h4>
                        <p style="font-size:15px; opacity:0.5; margin-top:20px; font-weight:800; letter-spacing:2px;">TIER: ELITE RECONSTRUCTIVE</p>
                    </div>
                </div>
                <div class="glass-card gp-reveal" style="padding:0; border-radius:60px; overflow:hidden;" data-delay="400">
                    <div style="height:600px; background:#F1F5F9; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:950; opacity:0.15; letter-spacing:3px;">INTEL: AESTHETIC VENEERS</div>
                    <div style="padding:60px; text-align:center; border-top:1px solid #F1F5F9;">
                        <h4 style="margin:0; font-size:30px; font-weight:950; letter-spacing:-0.04em;">Minimal Prep Ceramic Blueprint</h4>
                        <p style="font-size:15px; opacity:0.5; margin-top:20px; font-weight:800; letter-spacing:2px;">TIER: COSMETIC PRECISION</p>
                    </div>
                </div>
            </div>
        </div>';
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Sarah V. (Supreme Transformation)', 'post_content' => 'High-authority smile reconstruction for a global leadership profile.', 'post_type' => 'gp_project', 'post_status' => 'publish'));
    }
}
new GrowthPress_Dental();
