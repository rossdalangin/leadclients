<?php
/**
 * GrowthPress AI Content Studio
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
        if ( 'growthpress_page_growthpress-studio' !== $hook ) {
            return;
        }
        wp_enqueue_script( 'growthpress-studio-js', GROWTHPRESS_CORE_URL . 'assets/js/admin-dashboard.js', array( 'jquery' ), GROWTHPRESS_CORE_VERSION, true );
        wp_localize_script( 'growthpress-studio-js', 'gp_admin', array(
            'nonce' => wp_create_nonce( 'gp_admin_nonce' )
        ));
    }

    public function handle_generation() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $type = sanitize_text_field($_POST['content_type']);
        $topic = sanitize_text_field($_POST['topic']);
        $niche = get_option('growthpress_niche', 'business');

        $ai = GrowthPress_AI::get_instance();

        switch($type) {
            case 'blog':
                $result = $ai->generate_blog_post($topic, $niche);
                break;
            case 'social':
                $result = $ai->generate_social_content($topic);
                break;
            case 'ad':
                $result = $ai->generate_ad_copy($topic, $niche);
                break;
            default:
                $result = 'Invalid content type.';
        }

        wp_send_json_success($result);
    }

    public function render_studio() {
        ?>
        <div class="wrap growthpress-studio">
            <h1>AI Content Studio</h1>
            <div class="glass-card">
                <h3>Generate Marketing Assets</h3>
                <div class="studio-form">
                    <label>Content Type</label>
                    <select id="gp-content-type">
                        <option value="blog">Blog Post</option>
                        <option value="social">Social Media Posts</option>
                        <option value="ad">Ad Copy</option>
                    </select>

                    <label>Topic / Keywords</label>
                    <input type="text" id="gp-content-topic" placeholder="e.g. Benefits of Dental Implants">

                    <button class="button button-primary" onclick="generateContent()">Generate with AI</button>
                </div>
                <div id="gp-studio-output" class="output-area" style="margin-top: 20px;"></div>
            </div>
        </div>
        <script>
        function generateContent() {
            var $ = jQuery;
            var data = {
                action: 'gp_generate_content',
                content_type: $('#gp-content-type').val(),
                topic: $('#gp-content-topic').val(),
                gp_nonce: gp_admin.nonce
            };
            $('#gp-studio-output').html('Thinking...');
            $.post(ajaxurl, data, function(res) {
                if(res.success) {
                    $('#gp-studio-output').html('<div class="glass-card" style="background: #f8fafc;"><pre style="white-space: pre-wrap; font-family: inherit;">' + res.data + '</pre></div>');
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
