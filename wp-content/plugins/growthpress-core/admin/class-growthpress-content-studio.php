<?php
/**
 * GrowthPress AI Content Studio - Market Insights Enhanced
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
        $type = sanitize_text_field($_POST['content_type']);
        $topic = sanitize_text_field($_POST['topic']);
        $niche = get_option('growthpress_niche', 'business');
        $ai = GrowthPress_AI::get_instance();

        switch($type) {
            case 'blog': $result = $ai->generate_blog_post($topic, $niche); break;
            case 'social': $result = $ai->generate_social_content($topic); break;
            case 'ad': $result = $ai->generate_ad_copy($topic, $niche); break;
            case 'campaign': $result = $ai->generate_email_campaign($topic, $niche); break;
            case 'market': $result = $ai->generate_market_insights($topic, $niche); break;
            case 'sales': $result = $ai->call_ai("Generate high-ticket discovery call talk tracks, power questions, and objection handling for a $niche firm regarding \"$topic\".", "AI Sales Coach"); break;
            default: $result = 'Invalid.';
        }

        if ( is_wp_error($result) ) {
            wp_send_json_error($result->get_error_message());
        }

        wp_send_json_success($result);
    }

    public function render_studio() {
        $niche = get_option('growthpress_niche', 'business');
        $prompt_library = array(
            'dental'        => array('Invisalign vs Braces', 'Emergency Dental Care', 'Pediatric Dentistry Tips', 'Smile Makeovers'),
            'law'           => array('Personal Injury Rights', 'Estate Planning 101', 'DUI Defense Strategies', 'Business Litigation'),
            'contractor'    => array('Kitchen Remodel ROI', 'Outdoor Living Spaces', 'Foundation Repair Signs', 'Smart Home Upgrades'),
            'roofing'       => array('Storm Damage Claims', 'Metal vs Shingle Roofs', 'Roof Life Extension', 'Emergency Leak Repair'),
            'solar'         => array('Federal Tax Credits', 'Battery Backup Value', 'Solar for Off-Grid', 'Net Metering Explained'),
            'accounting'    => array('Small Business Tax Prep', 'Audit Protection', 'Cash Flow Management', 'Virtual CFO Benefits'),
            'medical'       => array('Telemedicine Benefits', 'Wellness Checklists', 'Sports Injury Recovery', 'Heart Health AI'),
            'real-estate'   => array('Selling in a High-Rate Market', 'First-Time Buyer Guide', 'Investment Property ROI', 'Staging for Top Dollar'),
            'coaches'       => array('High-Performance Mindset', 'Scaling to 7 Figures', 'Overcoming Burnout', 'Executive Leadership'),
            'consultants'   => array('Process Automation', 'Digital Transformation', 'Team Efficiency Boost', 'Market Entry Strategy')
        );
        $current_prompts = isset($prompt_library[$niche]) ? $prompt_library[$niche] : array('General Growth', 'Market Dominance', 'Client Acquisition');
        ?>
        <div class="wrap growthpress-studio">
            <h1>AI Content & Insights Studio</h1>
            <div class="studio-layout" style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-top:20px;">
                <div class="studio-input-column" style="display:flex; flex-direction:column; gap:20px;">
                    <div class="studio-input glass-card">
                        <h3>Asset & Strategy Generation</h3>
                        <label>Content Type</label>
                        <select id="gp-content-type" style="width:100%; margin-bottom:15px;">
                            <option value="blog">SEO Blog Post</option>
                            <option value="campaign">Nurture Campaign</option>
                            <option value="market">Market Insights & Angle of Attack</option>
                            <option value="sales">AI Sales Assistant (Talk Tracks)</option>
                            <option value="headlines">AI Headline & CTA Optimizer</option>
                            <option value="ad">Direct-Response Ads</option>
                            <option value="social">Omnichannel Social Suite</option>
                        </select>
                        <label>Target Topic / Location</label>
                        <input type="text" id="gp-content-topic" placeholder="e.g. Dallas, Texas" style="width:100%; margin-top:5px; margin-bottom:15px;">
                        <button class="button button-primary" onclick="generateContent()" style="width:100%;">Generate High-Ticket Strategy</button>
                    </div>

                    <div class="studio-prompts glass-card">
                        <h3>Recommended AI Topics</h3>
                        <p class="description">Click a topic below to auto-fill the generator. These are optimized for the <strong><?php echo ucwords(str_replace('-', ' ', $niche)); ?></strong> niche.</p>
                        <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:10px;">
                            <?php foreach($current_prompts as $p): ?>
                                <button type="button" class="button button-small" onclick="jQuery('#gp-content-topic').val('<?php echo esc_js($p); ?>')"><?php echo esc_html($p); ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="studio-output glass-card" style="min-height:400px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                        <h3 style="margin:0;">Strategic Output</h3>
                        <button class="button button-small" onclick="copyStudioOutput()">Copy to Clipboard</button>
                    </div>
                    <div id="gp-studio-output" style="background:#f8fafc; padding:20px; border-radius:8px; border:1px solid #e2e8f0; font-family:monospace; min-height:300px; max-height:600px; overflow-y:auto;">
                        Your generated strategy or content will appear here...
                    </div>
                </div>
                <script>
                function copyStudioOutput() {
                    var content = jQuery('#gp-studio-output').text();
                    navigator.clipboard.writeText(content);
                    alert("Output copied to clipboard!");
                }
                </script>
            </div>
        </div>
        <?php
    }
}
new GrowthPress_Content_Studio();
