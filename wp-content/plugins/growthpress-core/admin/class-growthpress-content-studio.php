<?php
/**
 * GrowthPress AI Content Studio - Conversion Enhanced
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
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $type = sanitize_text_field($_POST['content_type']);
        $topic = sanitize_text_field($_POST['topic']);
        $niche = get_option('growthpress_niche', 'business');
        $ai = GrowthPress_AI::get_instance();

        switch($type) {
            case 'blog': $result = $ai->generate_blog_post($topic, $niche); break;
            case 'social': $result = $ai->generate_social_content($topic); break;
            case 'ad': $result = $ai->generate_ad_copy($topic, $niche); break;
            case 'sales': $result = $ai->call_ai("Generate discovery script for $niche: $topic", "Sales Coach"); break;
            case 'headline': $result = $ai->call_ai("Generate 5 high-converting headlines and 3 CTAs for a $niche business regarding \"$topic\". Focus on conversion psychology.", "Elite Copywriter"); break;
            default: $result = 'Invalid type.';
        }
        wp_send_json_success($result);
    }

    public function render_studio() {
        ?>
        <div class="wrap growthpress-studio">
            <h1>AI Content & Conversion Studio</h1>
            <div class="studio-layout" style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-top:20px;">
                <div class="studio-input glass-card">
                    <h3>Conversion Asset Generation</h3>
                    <select id="gp-content-type" style="width:100%;">
                        <option value="blog">SEO Blog Post</option>
                        <option value="social">Social Media Bundle</option>
                        <option value="ad">Direct-Response Ads</option>
                        <option value="headline">Headlines & CTAs (High-Converting)</option>
                        <option value="sales">Sales Scripts</option>
                    </select>
                    <p class="description">Use 'Headlines & CTAs' to optimize your landing pages for your <?php echo get_option('growthpress_niche'); ?> niche.</p>
                    <input type="text" id="gp-content-topic" placeholder="e.g. Free Consultation" style="width:100%; margin-top:10px;">
                    <button class="button button-primary" onclick="generateContent()" style="margin-top:15px;">Generate Assets</button>
                </div>
                <div class="studio-output glass-card">
                    <div id="gp-studio-output">Results appear here...</div>
                </div>
            </div>
        </div>
        <script>
        function generateContent() {
            jQuery('#gp-studio-output').html('Thinking...');
            jQuery.post(ajaxurl, { action:'gp_generate_content', content_type:jQuery('#gp-content-type').val(), topic:jQuery('#gp-content-topic').val(), gp_nonce:gp_admin.nonce }, function(res) {
                if(res.success) jQuery('#gp-studio-output').html('<pre style="white-space:pre-wrap;">' + res.data + '</pre>');
            });
        }
        </script>
        <?php
    }
}
new GrowthPress_Content_Studio();
