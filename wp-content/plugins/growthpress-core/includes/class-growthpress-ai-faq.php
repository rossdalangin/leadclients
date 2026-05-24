<?php
/**
 * GrowthPress AI FAQ Assistant
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_AI_FAQ {

    public function __construct() {
        add_shortcode( 'gp_ai_faq', array( $this, 'render_faq_assistant' ) );
        add_action( 'wp_ajax_gp_ai_faq_ask', array( $this, 'handle_faq_query' ) );
        add_action( 'wp_ajax_nopriv_gp_ai_faq_ask', array( $this, 'handle_faq_query' ) );
    }

    public function render_faq_assistant() {
        $nonce = wp_create_nonce('gp_ai_faq_nonce');
        ob_start(); ?>
        <div class="gp-ai-faq glass-card">
            <h3>AI Business Assistant</h3>
            <p>Ask anything about our services...</p>
            <div id="gp-faq-chat-box" style="height: 200px; overflow-y: auto; border: 1px solid #eee; padding: 10px; margin-bottom: 10px;"></div>
            <input type="hidden" id="gp_ai_faq_nonce" value="<?php echo $nonce; ?>">
            <input type="text" id="gp-faq-input" placeholder="Type your question..." style="width: 70%;">
            <button onclick="askAI()">Ask</button>
        </div>
        <script>
        function askAI() {
            var query = jQuery('#gp-faq-input').val();
            var nonce = jQuery('#gp_ai_faq_nonce').val();
            var $chat = jQuery('#gp-faq-chat-box');

            $chat.append('<p><strong>You:</strong> ' + query + '</p>');
            jQuery('#gp-faq-input').val('');

            jQuery.post(gp_ajax.ajaxurl, {
                action: 'gp_ai_faq_ask',
                query: query,
                nonce: nonce
            }, function(res) {
                if(res.success) {
                    $chat.append('<p><strong>AI:</strong> ' + res.data + '</p>');
                }
            });
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_faq_query() {
        check_ajax_referer('gp_ai_faq_nonce', 'nonce');

        $query = sanitize_text_field($_POST['query']);
        $niche = get_option('growthpress_niche', 'Business');

        $ai = GrowthPress_AI::get_instance();
        $prompt = "A visitor is asking: \"$query\". As an expert in $niche, provide a helpful and concise answer. If you don't know, suggest they book a call.";
        $response = $ai->call_ai($prompt, "You are a professional business assistant for a $niche company.");

        wp_send_json_success($response);
    }
}

new GrowthPress_AI_FAQ();
