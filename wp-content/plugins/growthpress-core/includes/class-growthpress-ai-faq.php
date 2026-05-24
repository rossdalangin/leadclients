<?php
/**
 * GrowthPress AI FAQ Assistant - Industry Deep Dive
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_AI_FAQ {

    public function __construct() {
        add_shortcode( 'gp_ai_faq', array( $this, 'render_faq_assistant' ) );
        add_action( 'wp_footer', array( $this, 'render_chat_bubble' ) );
        add_action( 'wp_ajax_gp_ai_faq_ask', array( $this, 'handle_faq_query' ) );
        add_action( 'wp_ajax_nopriv_gp_ai_faq_ask', array( $this, 'handle_faq_query' ) );
    }

    public function render_chat_bubble() {
        if ( is_admin() ) return;
        $nonce = wp_create_nonce('gp_ai_faq_nonce');
        ?>
        <div id="gp-ai-chat-bubble" class="gp-chat-bubble">
            <div id="gp-chat-icon">💬</div>
            <div id="gp-chat-window" style="display:none;">
                <div class="chat-header"><?php echo esc_html(get_option('growthpress_brand_name', 'GrowthPress')); ?> Assistant</div>
                <div id="gp-faq-chat-box" class="chat-body"></div>
                <div class="chat-footer">
                    <input type="hidden" id="gp_ai_faq_nonce" value="<?php echo $nonce; ?>">
                    <input type="text" id="gp-faq-input" placeholder="How can we help?">
                    <button onclick="askAI()">Send</button>
                </div>
            </div>
        </div>
        <style>
            .gp-chat-bubble { position: fixed; bottom: 20px; right: 20px; z-index: 9999; }
            #gp-chat-icon { background: <?php echo get_option('growthpress_primary_color', '#2563EB'); ?>; width: 60px; height: 60px; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 24px; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
            #gp-chat-window { position: absolute; bottom: 70px; right: 0; width: 300px; background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #eee; overflow: hidden; }
            .chat-header { background: <?php echo get_option('growthpress_primary_color', '#2563EB'); ?>; color: white; padding: 15px; font-weight: bold; }
            .chat-body { height: 250px; overflow-y: auto; padding: 15px; background: #f8fafc; font-size: 14px; }
            .chat-footer { padding: 10px; border-top: 1px solid #eee; display: flex; }
            .chat-footer input { flex: 1; border: 1px solid #ddd; border-radius: 4px; padding: 5px; margin-right: 5px; }
            .chat-footer button { background: <?php echo get_option('growthpress_primary_color', '#2563EB'); ?>; color: white; border: none; border-radius: 4px; padding: 5px 10px; cursor: pointer; }
        </style>
        <script>
            jQuery('#gp-chat-icon').on('click', function() { jQuery('#gp-chat-window').toggle(); });
            function askAI() {
                var query = jQuery('#gp-faq-input').val();
                var nonce = jQuery('#gp_ai_faq_nonce').val();
                var $chat = jQuery('#gp-faq-chat-box');
                if(!query) return;
                $chat.append('<p><strong>You:</strong> ' + query + '</p>');
                jQuery('#gp-faq-input').val('');
                jQuery.post(gp_ajax.ajaxurl, { action: 'gp_ai_faq_ask', query: query, nonce: nonce }, function(res) {
                    if(res.success) {
                        $chat.append('<p style="color:#2563EB;"><strong>AI:</strong> ' + res.data.answer + '</p>');
                        if(res.data.intent === 'booking') {
                            $chat.append('<div class="glass-card" style="margin-top:10px; font-size:12px;">🗓️ <a href="/services">Click here to book your consultation.</a></div>');
                        }
                    }
                    $chat.scrollTop($chat[0].scrollHeight);
                });
            }
        </script>
        <?php
    }

    public function render_faq_assistant() { return '<div class="glass-card">Use the chat bubble for assistance.</div>'; }

    public function handle_faq_query() {
        check_ajax_referer('gp_ai_faq_nonce', 'nonce');
        $query = sanitize_text_field($_POST['query']);
        $niche = get_option('growthpress_niche', 'Business');

        $ai = GrowthPress_AI::get_instance();
        $prompt = "A visitor is asking: \"$query\". As a specialist in $niche, provide expert advice and next steps. For Law, focus on legal intake triage. For Accounting, focus on tax/financial strategy. Detect booking intent and return JSON: answer, intent.";
        $response_raw = $ai->call_ai($prompt, "You are an elite $niche advisor.");

        $response = json_decode($response_raw, true) ?: array('answer' => $response_raw, 'intent' => 'general');
        wp_send_json_success($response);
    }
}
new GrowthPress_AI_FAQ();
