<?php
/**
 * GrowthPress AI Content Studio - Enhanced Instructions
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
        add_submenu_page(
            'growthpress-dashboard',
            'AI Content Studio',
            'AI Content Studio',
            'manage_options',
            'growthpress-studio',
            array( $this, 'render_studio' )
        );
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
            default: $result = 'Invalid type.';
        }
        wp_send_json_success($result);
    }

    public function render_studio() {
        ?>
        <div class="wrap growthpress-studio">
            <h1>AI Content Studio</h1>
            <p class="description">Generate high-converting marketing materials tailored to your specific business niche.</p>

            <div class="studio-layout" style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-top:20px;">
                <div class="studio-input glass-card">
                    <h3>Asset Configuration</h3>
                    <div class="form-group" style="margin-bottom:15px;">
                        <label style="display:block; font-weight:bold;">Asset Type</label>
                        <select id="gp-content-type" style="width:100%;">
                            <option value="blog">SEO Blog Post (Long Form)</option>
                            <option value="social">Social Media Bundle (3 Posts)</option>
                            <option value="ad">Direct-Response Ad Copy</option>
                        </select>
                        <p class="help-text" style="font-size:11px;">Blogs include H2 tags and CTA. Ads include Google/FB formats.</p>
                    </div>

                    <div class="form-group" style="margin-bottom:15px;">
                        <label style="display:block; font-weight:bold;">Primary Topic / Keyword</label>
                        <input type="text" id="gp-content-topic" placeholder="e.g. Why Invisalign is better than braces" style="width:100%;">
                        <p class="help-text" style="font-size:11px;">Example: "Emergency roofing repair" or "Dental implants for seniors".</p>
                    </div>

                    <button class="button button-primary button-hero" onclick="generateContent()">Generate Asset with AI</button>
                    <div style="margin-top:20px; font-size:12px; background:#fff9db; padding:10px; border-radius:5px;">
                        <strong>Pro Tip:</strong> Use specific keywords to get better SEO results. The AI automatically adapts the tone to your <strong><?php echo get_option('growthpress_niche'); ?></strong> niche.
                    </div>
                </div>

                <div class="studio-output glass-card">
                    <h3>Generated Output</h3>
                    <div id="gp-studio-output" style="min-height:200px; border:1px dashed #ccc; padding:15px; background:#fff;">
                        <p style="color:#999; text-align:center;">Configure and click generate to see results...</p>
                    </div>
                </div>
            </div>
        </div>
        <script>
        function generateContent() {
            var $ = jQuery;
            $('#gp-studio-output').html('<div style="text-align:center; padding:50px;"><span class="spinner is-active"></span> Thinking...</div>');
            $.post(ajaxurl, {
                action: 'gp_generate_content',
                content_type: $('#gp-content-type').val(),
                topic: $('#gp-content-topic').val(),
                gp_nonce: gp_admin.nonce
            }, function(res) {
                if(res.success) {
                    $('#gp-studio-output').html('<div style="white-space:pre-wrap; font-family:Inter, sans-serif; line-height:1.6;">' + res.data + '</div>');
                } else {
                    $('#gp-studio-output').html('Error: ' + res.data);
                }
            });
        }
        </script>
        <?php
    }
}

new GrowthPress_Content_Studio();
