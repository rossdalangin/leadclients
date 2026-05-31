<?php
/**
 * GrowthPress AI FAQ Assistant - Industry Deep Dive v2.5
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
        $primary = get_option('growthpress_primary_color', '#2563EB');
        $brand = get_option('growthpress_brand_name', 'GrowthPress');
        ?>
        <div id="gp-ai-chat-bubble" class="gp-chat-bubble-container">
            <div id="gp-chat-launcher" class="gp-chat-launcher">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 11.5C21 16.7467 16.9706 21 12 21C10.1587 21 8.44851 20.4431 7.02534 19.4842L3 21L4.5 16.9747C3.5411 15.5515 3 13.8413 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 11.5Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <div class="gp-launcher-pulse"></div>
            </div>

            <div id="gp-chat-window" class="gp-chat-window glass-card" style="display:none;">
                <div class="gp-chat-header" style="background: <?php echo $primary; ?>;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:10px; height:10px; background:#10B981; border-radius:50%; border:2px solid white;"></div>
                        <div>
                            <div style="font-size:14px; font-weight:900; letter-spacing:0.5px;"><?php echo $brand; ?> AI</div>
                            <div style="font-size:10px; opacity:0.8; font-weight:600;">ACTIVE NOW</div>
                        </div>
                    </div>
                    <div id="gp-chat-close" style="cursor:pointer; opacity:0.7;">&times;</div>
                </div>

                <div id="gp-faq-chat-box" class="gp-chat-body">
                    <div class="gp-msg-ai">Hello! I'm your specialized <?php echo get_option('growthpress_niche', 'business'); ?> assistant. How can I help you grow today?</div>
                </div>

                <div id="gp-chat-typing" style="display:none; padding:10px 20px; font-size:11px; color:#64748B; font-weight:700;">AI is thinking...</div>

                <div class="gp-chat-footer">
                    <input type="hidden" id="gp_ai_faq_nonce" value="<?php echo $nonce; ?>">
                    <input type="text" id="gp-faq-input" placeholder="Type your inquiry...">
                    <button id="gp-faq-send" onclick="askAI()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22 2L11 13M22 2L15 22L11 13M11 13L2 9L22 2" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <style>
            .gp-chat-bubble-container { position: fixed; bottom: 30px; right: 30px; z-index: 10001; font-family: 'Inter', sans-serif; }
            .gp-chat-launcher { width: 64px; height: 64px; border-radius: 50%; background: <?php echo $primary; ?>; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 10px 25px rgba(0,0,0,0.15); transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); position:relative; }
            .gp-chat-launcher:hover { transform: scale(1.1) rotate(5deg); }

            .gp-launcher-pulse { position:absolute; top:0; left:0; width:100%; height:100%; border-radius:50%; background:<?php echo $primary; ?>; opacity:0.4; z-index:-1; animation: gp-pulse 2s infinite; }
            @keyframes gp-pulse { 0% { transform: scale(1); opacity: 0.4; } 100% { transform: scale(1.6); opacity: 0; } }

            .gp-chat-window { position: absolute; bottom: 85px; right: 0; width: 360px; height: 520px; display: flex; flex-direction: column; padding: 0 !important; border-radius: 28px !important; box-shadow: 0 40px 80px -15px rgba(0,0,0,0.2) !important; animation: gp-slide-up 0.5s cubic-bezier(0.16, 1, 0.3, 1); border: 1px solid rgba(255,255,255,0.2); }

            .gp-chat-header { padding: 30px; color: white; display: flex; justify-content: space-between; align-items: center; border-radius: 28px 28px 0 0; }
            .gp-chat-body { flex: 1; overflow-y: auto; padding: 30px; display: flex; flex-direction: column; gap: 20px; background: #F8FAFC; }

            .gp-msg-ai, .gp-msg-user { padding: 15px 20px; border-radius: 20px; max-width: 85%; font-size: 14px; line-height: 1.5; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
            .gp-msg-ai { background: white; color: #1E293B; border-bottom-left-radius: 4px; border: 1px solid #E2E8F0; align-self: flex-start; }
            .gp-msg-user { background: <?php echo $primary; ?>; color: white; border-bottom-right-radius: 4px; align-self: flex-end; }

            .gp-chat-footer { padding: 20px; background: white; border-top: 1px solid #F1F5F9; display: flex; gap: 10px; align-items: center; border-radius: 0 0 28px 28px; }
            .gp-chat-footer input { flex: 1; border: 1px solid #E2E8F0; border-radius: 16px; padding: 14px 18px; font-size: 14px; outline: none; transition: border-color 0.2s; margin:0; }
            .gp-chat-footer input:focus { border-color: <?php echo $primary; ?>; }
            .gp-chat-footer button { background: <?php echo $primary; ?>; border: none; width: 48px; height: 48px; border-radius: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: transform 0.2s; }
            .gp-chat-footer button:hover { transform: scale(1.05); }

            @keyframes gp-slide-up { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        </style>

        <script>
            jQuery('#gp-chat-launcher').on('click', function() {
                jQuery('#gp-chat-window').fadeIn(400);
                jQuery(this).fadeOut(200);
            });
            jQuery('#gp-chat-close').on('click', function() {
                jQuery('#gp-chat-window').fadeOut(200);
                jQuery('#gp-chat-launcher').fadeIn(400);
            });

            function askAI() {
                var query = jQuery('#gp-faq-input').val();
                var nonce = jQuery('#gp_ai_faq_nonce').val();
                var $chat = jQuery('#gp-faq-chat-box');
                var $typing = jQuery('#gp-chat-typing');
                if(!query) return;

                $chat.append('<div class="gp-msg-user">' + query + '</div>');
                jQuery('#gp-faq-input').val('');
                $chat.scrollTop($chat[0].scrollHeight);
                $typing.show();

                jQuery.post(gp_ajax.ajaxurl, { action: 'gp_ai_faq_ask', query: query, nonce: nonce }, function(res) {
                    $typing.hide();
                    if(res.success) {
                        $chat.append('<div class="gp-msg-ai">' + res.data.answer + '</div>');
                        if(res.data.intent === 'booking') {
                            $chat.append('<div class="gp-msg-ai" style="background:#f0f9ff; border-color:#bae6fd;">🗓️ <strong>Strategic Session:</strong> You can secure your slot here: <br><br><a href="/book-now" class="gp-btn" style="font-size:12px; padding:10px 15px; width:100%; text-align:center; border-radius:12px;">Book Consultation</a></div>');
                        }
                    } else {
                        $chat.append('<div class="gp-msg-ai">Engine Timeout. Please retry.</div>');
                    }
                    $chat.scrollTop($chat[0].scrollHeight);
                });
            }

            jQuery('#gp-faq-input').on('keypress', function(e) { if(e.which == 13) askAI(); });
        </script>
        <?php
    }

    public function render_faq_assistant() {
        $niche = get_option('growthpress_niche', 'business');
        return '<div class="glass-card gp-reveal" style="text-align:center; padding:80px 60px;">
            <h3 class="text-gradient" style="font-size:2.5rem;">24/7 Intelligence Terminal</h3>
            <p style="font-size:1.2rem; opacity:0.7; margin-bottom:40px;">Our neural-calibrated assistant is ready to handle your specialized ' . esc_html($niche) . ' inquiries.</p>
            <button onclick="jQuery(\'#gp-chat-launcher\').click()" class="gp-btn" style="height:70px; padding:0 50px; font-size:18px;">Initiate Assistant</button>
        </div>';
    }

    public function handle_faq_query() {
        check_ajax_referer('gp_ai_faq_nonce', 'nonce');
        if ( ! isset( $_POST['query'] ) ) {
            wp_send_json_error( 'Missing query' );
        }
        $query = sanitize_text_field($_POST['query']);
        $niche = get_option('growthpress_niche', 'Business');
        $ai = GrowthPress_AI::get_instance();
        $prompt = "A visitor is asking: \"$query\". As a specialist in $niche, provide expert advice and next steps. Return ONLY a valid JSON object with keys 'answer' and 'intent' ('booking' if they want to schedule, 'general' otherwise).";
        $response_raw = $ai->call_ai($prompt, "Elite $niche Strategist");
        if ( is_wp_error($response_raw) ) wp_send_json_error($response_raw->get_error_message());
        $response = json_decode($response_raw, true);
        if(!$response) $response = array('answer' => $response_raw, 'intent' => (stripos($response_raw, 'book') !== false) ? 'booking' : 'general');
        wp_send_json_success($response);
    }
}
new GrowthPress_AI_FAQ();
