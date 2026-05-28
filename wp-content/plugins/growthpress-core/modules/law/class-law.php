<?php
/**
 * Law Niche specialized Closer Tools - Ultra Elite v4.0
 */
class GrowthPress_Law {
    public function __construct() {
        add_shortcode('gp_legal_intake', array($this, 'render_legal_intake'));
    }

    public function render_legal_intake() {
        $nonce = wp_create_nonce('gp_lead_nonce');
        $brand = get_option('growthpress_brand_name', 'GrowthPress');
        return '<div class="gp-legal-intake glass-card gp-reveal" style="border-right: 20px solid var(--secondary); background: linear-gradient(135deg, var(--surface), var(--bg)); position:relative; overflow:hidden; padding:100px 80px;">
            <div style="position:absolute; top:0; right:0; background:var(--secondary); color:white; font-size:12px; font-weight:950; padding:12px 50px; transform:rotate(45deg) translate(30px, -30px); letter-spacing:3px;">SECURE</div>
            <div style="text-align:center; margin-bottom:70px;">
                <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">LITIGATION INTELLIGENCE v4.0</div>
                <h3 class="text-gradient" style="font-size:3.5rem; line-height:1.0;">Elite Case Merit Analysis</h3>
                <p style="font-size:1.25rem; opacity:0.7; max-width:700px; margin:25px auto 0;">Submit your matter for real-time neural triage and high-stakes litigation prioritization.</p>
            </div>

            <div style="background:rgba(0,0,0,0.03); border-radius:44px; padding:50px; margin-bottom:50px; border:1px solid rgba(0,0,0,0.05); position:relative;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <span style="font-size:12px; font-weight:950; opacity:0.5; letter-spacing:2px;">NEURAL MERIT PROBABILITY</span>
                    <span id="merit-score" style="font-size:14px; font-weight:900; color:var(--primary); letter-spacing:1px;">ANALYZING...</span>
                </div>
                <div style="height:14px; background:rgba(0,0,0,0.05); border-radius:10px; overflow:hidden; box-shadow:inset 0 2px 5px rgba(0,0,0,0.05);">
                    <div id="merit-fill" style="height:100%; width:0%; background:linear-gradient(90deg, var(--primary), var(--primary-alt)); transition:width 2s cubic-bezier(0.16, 1, 0.3, 1);"></div>
                </div>
            </div>

            <form class="gp-form" data-action="gp_submit_lead">
                <input type="hidden" name="nonce" value="'.$nonce.'">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:30px;">
                    <div><label style="font-size:12px; font-weight:950; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:12px;">CLAIMANT IDENTITY</label><input type="text" name="lead_name" placeholder="Full Legal Name" required style="height:75px; border-radius:20px; border:2px solid #F1F5F9; padding:0 25px; font-size:16px;"></div>
                    <div><label style="font-size:12px; font-weight:950; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:12px;">SECURE CHANNEL</label><input type="email" name="lead_email" placeholder="Direct Email" required style="height:75px; border-radius:20px; border:2px solid #F1F5F9; padding:0 25px; font-size:16px;"></div>
                </div>
                <div style="margin-bottom:30px;">
                    <label style="font-size:12px; font-weight:950; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:12px;">MATTER CLASSIFICATION</label>
                    <select id="case_type" style="width:100%; height:75px; border-radius:20px; font-weight:700; border:2px solid #F1F5F9; padding:0 25px; font-size:16px;">
                        <option value="Commercial">High-Value Commercial Litigation</option>
                        <option value="Tort">Elite Personal Injury / Catastrophic</option>
                        <option value="Corporate">Strategic Corporate Transactional</option>
                        <option value="Estate">Family Office & Asset Protection</option>
                    </select>
                </div>
                <textarea name="lead_msg" placeholder="Summarize the matter events, involved parties, and target resolution... (Privileged)" style="height:220px; margin-bottom:40px; border-radius:28px; padding:30px; font-size:16px; border:2px solid #F1F5F9; line-height:1.7;"></textarea>
                <button type="submit" class="gp-btn" style="width:100%; height:90px; font-size:22px;">Initiate Supreme Merit Review</button>
            </form>
            <div style="margin-top:40px; font-size:11px; opacity:0.4; text-align:center; line-height:1.8; max-width:600px; margin-left:auto; margin-right:auto;">ENCRYPTION: AES-256 BIT SHA-2. NOTICE: This terminal is for administrative intake and neural triage only. Use of this system does not establish an attorney-client relationship. Data is processed under strict confidentiality protocols.</div>

            <script>
                jQuery(document).ready(function($) {
                    setTimeout(function() {
                        $("#merit-fill").css("width", "82%");
                        $("#merit-score").text("NEURAL ENGINE ONLINE");
                    }, 1800);
                });
            </script>
        </div>';
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Sterling IP Matter', 'post_content' => 'High-stakes litigation inquiry regarding multi-national patent infringement.', 'post_type' => 'gp_lead', 'post_status' => 'publish'));
    }
}
new GrowthPress_Law();
