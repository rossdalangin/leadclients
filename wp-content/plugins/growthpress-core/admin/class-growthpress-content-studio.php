<?php
/**
 * GrowthPress AI Content Studio - Command Center v1.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Content_Studio {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_studio_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_studio_assets' ) );
        add_action( 'wp_ajax_gp_generate_content', array( $this, 'handle_generation' ) );
    }

    public function add_studio_menu() {
        add_submenu_page( 'growthpress-dashboard', 'AI Content Studio', 'AI Content Studio', 'manage_options', 'growthpress-studio', array( $this, 'render_studio' ) );
    }

    public function enqueue_studio_assets( $hook ) {
        if ( 'growthpress_page_growthpress-studio' !== $hook ) return;
        wp_enqueue_script( 'growthpress-studio-js', GROWTHPRESS_CORE_URL . 'assets/js/admin-dashboard.js', array( 'jquery' ), GROWTHPRESS_CORE_VERSION, true );
        wp_localize_script( 'growthpress-studio-js', 'gp_admin', array( 'nonce' => wp_create_nonce( 'gp_admin_nonce' ) ));
    }

    public function handle_generation() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        if ( ! isset( $_POST['content_type'] ) || ! isset( $_POST['topic'] ) ) {
            wp_send_json_error( 'Missing parameters' );
        }

        $type = sanitize_text_field($_POST['content_type']);
        $topic = sanitize_text_field($_POST['topic']);
        $niche = get_option('growthpress_niche', 'business');
        $ai = GrowthPress_AI::get_instance();

        switch($type) {
            case 'blog': $result = $ai->generate_blog_post($topic, $niche); break;
            case 'campaign': $result = $ai->generate_email_campaign($topic, $niche); break;
            case 'market': $result = $ai->generate_market_insights($topic, $niche); break;
            case 'sales': $result = $ai->call_ai("Generate high-ticket discovery call talk tracks and objection handling for a $niche firm regarding \"$topic\".", "AI Sales Coach"); break;
            case 'ad': $result = $ai->generate_ad_copy($topic, $niche); break;
            case 'headlines': $result = $ai->call_ai("Generate 5 elite headlines for \"$topic\" in the $niche industry.", "CRO Expert"); break;
            default: $result = 'Invalid selection.';
        }

        if ( is_wp_error($result) ) wp_send_json_error($result->get_error_message());
        wp_send_json_success($result);
    }

    public function render_studio() {
        $niche = get_option('growthpress_niche', 'business');
        $prompt_library = array(
            'dental'        => array('Invisalign vs Braces', 'Emergency Dental Care', 'Smile Makeovers'),
            'law'           => array('Personal Injury Rights', 'Estate Planning 101', 'Business Litigation'),
            'solar'         => array('Federal Tax Credits', 'Battery Backup Value', 'Net Metering'),
            'contractor'    => array('Kitchen Remodel ROI', 'Outdoor Living Spaces', 'Foundation Repair'),
            'accounting'    => array('Small Business Tax Prep', 'Audit Protection', 'Virtual CFO'),
            'medical'       => array('Telemedicine Benefits', 'Wellness Checklists', 'Heart Health'),
            'real-estate'   => array('Selling in a High-Rate Market', 'First-Time Buyer Guide', 'Investment ROI'),
            'coaches'       => array('High-Performance Mindset', 'Scaling to 7 Figures', 'Overcoming Burnout'),
            'consultants'   => array('Process Automation', 'Digital Transformation', 'Team Efficiency')
        );
        $current_prompts = $prompt_library[$niche] ?? array('Market Dominance', 'Client Acquisition', 'Authority Building');
        ?>
        <div class="wrap growthpress-studio">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px;">
                <h1>AI Content & Insights Command Center</h1>
                <div style="background:var(--primary-glow); color:var(--primary); padding:8px 16px; border-radius:30px; font-size:11px; font-weight:900; letter-spacing:1px;">ENGINE: GPT-4-TURBO</div>
            </div>

            <div class="studio-layout" style="display:grid; grid-template-columns: 1fr 1fr 1.2fr; gap:30px;">
                <!-- Column 1: Config -->
                <div class="glass-card" style="padding:40px;">
                    <h3 style="margin-top:0;">Asset Calibration</h3>
                    <p style="font-size:13px; opacity:0.7; margin-bottom:30px;">Define the strategic asset you need for your <?php echo ucwords($niche); ?> practice.</p>

                    <div style="margin-bottom:25px;">
                        <label style="font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:1px; display:block; margin-bottom:10px;">Select Asset Type</label>
                        <select id="gp-content-type" style="width:100%; height:50px; border-radius:12px; font-weight:600;">
                            <option value="blog">SEO Authority Post</option>
                            <option value="campaign">5-Day Nurture Sequence</option>
                            <option value="market">Market Angle of Attack</option>
                            <option value="sales">Discovery Talk Tracks</option>
                            <option value="ad">Direct-Response Ad Suite</option>
                            <option value="headlines">Conversion Headlines</option>
                        </select>
                    </div>

                    <div style="margin-bottom:30px;">
                        <label style="font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:1px; display:block; margin-bottom:10px;">Target Topic or Focus</label>
                        <input type="text" id="gp-content-topic" placeholder="e.g. Luxury Renovation" style="width:100%; height:50px; border-radius:12px; border:1px solid #E2E8F0; padding:0 15px;">
                    </div>

                    <button class="button button-primary button-hero" onclick="generateContent()" style="width:100%; height:60px !important; border-radius:15px !important; font-size:16px !important;">Generate Strategic Asset</button>
                </div>

                <!-- Column 2: Library -->
                <div class="glass-card" style="padding:40px;">
                    <h3 style="margin-top:0;">Prompt Intelligence</h3>
                    <p style="font-size:13px; opacity:0.7; margin-bottom:30px;">Click a specialized topic below to pre-fill the command engine.</p>

                    <div style="display:grid; gap:15px;">
                        <?php foreach($current_prompts as $p): ?>
                            <div class="prompt-card" style="background:#F8FAFC; border:1px solid #E2E8F0; padding:20px; border-radius:18px; cursor:pointer; transition:all 0.3s ease;" onclick="jQuery('#gp-content-topic').val('<?php echo esc_js($p); ?>')">
                                <div style="font-size:14px; font-weight:800; color:var(--primary);">🎯 <?php echo esc_html($p); ?></div>
                                <div style="font-size:11px; opacity:0.5; margin-top:5px;">Optimized for your <?php echo $niche; ?> niche.</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Column 3: Output -->
                <div class="studio-output glass-card" style="padding:0; overflow:hidden; border:none; background: #0F172A; color:white;">
                    <div style="padding:30px 40px; border-bottom:1px solid rgba(255,255,255,0.1); display:flex; justify-content:space-between; align-items:center;">
                        <h3 style="margin:0; color:white; font-size:16px;">Strategic Intelligence Output</h3>
                        <button class="button button-small" onclick="copyStudioOutput()" style="background:rgba(255,255,255,0.1); color:white; border:none;">COPY</button>
                    </div>
                    <div id="gp-studio-output" style="padding:40px; font-family:'JetBrains Mono', monospace; font-size:13px; line-height:1.7; height:500px; overflow-y:auto; color:rgba(255,255,255,0.8);">
                        <span style="opacity:0.3;">// Waiting for strategic command...</span>
                    </div>
                </div>
            </div>
        </div>
        <script>
        function copyStudioOutput() {
            var content = jQuery('#gp-studio-output').text();
            navigator.clipboard.writeText(content);
            alert("Strategic asset copied to clipboard!");
        }
        </script>
        <style>
            .prompt-card:hover { transform: translateX(8px); border-color: var(--primary); background: white; box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        </style>
        <?php
    }
}
new GrowthPress_Content_Studio();
