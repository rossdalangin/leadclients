<?php
/**
 * GrowthPress Settings Page - Final Elite v4.5 Multi-AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Settings {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_settings_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    public function add_settings_menu() {
        add_submenu_page( 'growthpress-dashboard', 'Settings', 'Settings', 'manage_options', 'growthpress-settings', array( $this, 'render_settings' ) );
    }

    public function register_settings() {
        $keys = array(
            'growthpress_ai_provider', 'growthpress_openai_api_key', 'growthpress_claude_api_key',
            'growthpress_gemini_api_key', 'growthpress_perplexity_api_key', 'growthpress_ollama_host',
            'growthpress_ollama_model', 'growthpress_niche', 'growthpress_api_token',
            'growthpress_brand_name', 'growthpress_primary_color', 'growthpress_hot_threshold',
            'growthpress_twilio_sid', 'growthpress_twilio_token', 'growthpress_whatsapp_key',
            'growthpress_google_maps_key', 'growthpress_stripe_key', 'growthpress_stripe_secret',
            'growthpress_license_key', 'growthpress_dashboard_logo', 'growthpress_agency_mode'
        );
        foreach($keys as $k) register_setting( 'growthpress_settings_group', $k );
        add_action( 'wp_ajax_gp_test_connectivity', array( $this, 'test_connectivity' ) );
    }

    public function test_connectivity() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error();
        $ai = GrowthPress_AI::get_instance();
        $res = $ai->call_ai("Ping", "Health Check");
        if ( is_wp_error($res) ) wp_send_json_error( $res->get_error_message() );
        wp_send_json_success( "Connection successful! " . strtoupper(get_option('growthpress_ai_provider', 'openai')) . " engine is online." );
    }

    public function render_settings() {
        ?>
        <div class="wrap growthpress-settings">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px;">
                <h1>Ecosystem Intelligence & Configuration</h1>
                <div style="background:var(--secondary); color:white; padding:8px 16px; border-radius:30px; font-size:11px; font-weight:900; letter-spacing:1px;">ELITE v4.5 OMNI-AI</div>
            </div>

            <div class="gp-settings-tabs">
                <h2 class="nav-tab-wrapper" style="border-bottom:none; margin-bottom:30px;">
                    <a href="#tab-config" class="nav-tab nav-tab-active">Configuration</a>
                    <a href="#tab-ai" class="nav-tab">AI Providers</a>
                    <a href="#tab-white-label" class="nav-tab">White-Label & Agency</a>
                    <a href="#tab-docs" class="nav-tab">Master Ops Manual</a>
                </h2>
            </div>

            <div id="tab-config" class="tab-content">
                <form method="post" action="options.php" class="glass-card" style="max-width:1000px;">
                    <?php settings_fields( 'growthpress_settings_group' ); ?>
                    <table class="form-table">
                        <tr class="section-header"><th colspan="2"><h3>Active Intelligence Routing</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Active AI Provider</label></th>
                            <td>
                                <select name="growthpress_ai_provider" style="width:100%; height:50px; border-radius:10px; font-weight:700;">
                                    <option value="openai" <?php selected('openai', get_option('growthpress_ai_provider'), true); ?>>OpenAI (GPT-4 Turbo)</option>
                                    <option value="claude" <?php selected('claude', get_option('growthpress_ai_provider'), true); ?>>Anthropic (Claude 3 Opus)</option>
                                    <option value="gemini" <?php selected('gemini', get_option('growthpress_ai_provider'), true); ?>>Google (Gemini Pro)</option>
                                    <option value="perplexity" <?php selected('perplexity', get_option('growthpress_ai_provider'), true); ?>>Perplexity AI</option>
                                    <option value="ollama" <?php selected('ollama', get_option('growthpress_ai_provider'), true); ?>>Ollama (Local LLM)</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="section-header"><th colspan="2"><h3>Communications Hub</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Twilio SID</label></th>
                            <td><input type="text" name="growthpress_twilio_sid" value="<?php echo esc_attr( get_option('growthpress_twilio_sid') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Twilio Auth Token</label></th>
                            <td><input type="password" name="growthpress_twilio_token" value="<?php echo esc_attr( get_option('growthpress_twilio_token') ); ?>" class="regular-text"></td>
                        </tr>
                    </table>
                    <?php submit_button('Update OS Core'); ?>
                </form>
            </div>

            <div id="tab-ai" class="tab-content" style="display:none;">
                <form method="post" action="options.php" class="glass-card" style="max-width:1000px;">
                    <?php settings_fields( 'growthpress_settings_group' ); ?>
                    <table class="form-table">
                        <tr class="section-header"><th colspan="2"><h3>Cloud Intelligence API Keys</h3></th></tr>
                        <tr>
                            <th scope="row"><label>OpenAI Secret Key</label></th>
                            <td><input type="password" name="growthpress_openai_api_key" value="<?php echo esc_attr( get_option('growthpress_openai_api_key') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Anthropic Claude Key</label></th>
                            <td><input type="password" name="growthpress_claude_api_key" value="<?php echo esc_attr( get_option('growthpress_claude_api_key') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Google Gemini Key</label></th>
                            <td><input type="password" name="growthpress_gemini_api_key" value="<?php echo esc_attr( get_option('growthpress_gemini_api_key') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Perplexity API Key</label></th>
                            <td><input type="password" name="growthpress_perplexity_api_key" value="<?php echo esc_attr( get_option('growthpress_perplexity_api_key') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr class="section-header"><th colspan="2"><h3>Local Intelligence (Ollama)</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Ollama Host URL</label></th>
                            <td><input type="text" name="growthpress_ollama_host" value="<?php echo esc_attr( get_option('growthpress_ollama_host', 'http://localhost:11434') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Ollama Model Name</label></th>
                            <td><input type="text" name="growthpress_ollama_model" value="<?php echo esc_attr( get_option('growthpress_ollama_model', 'llama3') ); ?>" class="regular-text"></td>
                        </tr>
                    </table>
                    <div style="margin-top:20px; padding:20px; background:#F0FDF4; border-radius:15px; border:1px solid #BBF7D0;">
                        <button type="button" class="button" onclick="testAI()">Verify Active AI Connection</button>
                        <span id="ai-test-res" style="margin-left:15px; font-weight:700;"></span>
                    </div>
                    <script>
                    function testAI() {
                        jQuery('#ai-test-res').text('SYNCHRONIZING...').css('color', '#666');
                        jQuery.post(ajaxurl, { action: 'gp_test_connectivity' }, function(res) {
                            jQuery('#ai-test-res').text(res.data).css('color', res.success ? '#10B981' : '#EF4444');
                        });
                    }
                    </script>
                    <?php submit_button('Save AI Intelligence Cluster'); ?>
                </form>
            </div>

            <div id="tab-white-label" class="tab-content" style="display:none;">
                <form method="post" action="options.php" class="glass-card" style="max-width:1000px;">
                    <?php settings_fields( 'growthpress_settings_group' ); ?>
                    <table class="form-table">
                        <tr class="section-header"><th colspan="2"><h3>Agency Branding</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Proprietary OS Name</label></th>
                            <td><input type="text" name="growthpress_brand_name" value="<?php echo esc_attr( get_option('growthpress_brand_name', 'GrowthPress') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Dashboard Logo URL</label></th>
                            <td><input type="text" name="growthpress_dashboard_logo" value="<?php echo esc_attr( get_option('growthpress_dashboard_logo') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Enable Agency Mode</label></th>
                            <td><input type="checkbox" name="growthpress_agency_mode" value="1" <?php checked(1, get_option('growthpress_agency_mode'), true); ?>></td>
                        </tr>
                    </table>
                    <?php submit_button('Update Agency Cluster'); ?>
                </form>
            </div>

            <div id="tab-docs" class="tab-content" style="display:none;">
                <div class="glass-card" style="max-width:1000px;">
                    <h2 class="text-gradient">Master Operations Manual v4.5</h2>
                    <p>GrowthPress v4.5 now supports **Multi-Intelligence Nodes**. You can toggle between providers instantly based on specialized niche requirements.</p>
                    <hr style="opacity:0.1; margin:30px 0;">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:40px;">
                        <div>
                            <h4>Specialized AI Use-Cases</h4>
                            <ul style="font-size:13px; opacity:0.7;">
                                <li><strong>Claude 3:</strong> Best for high-stakes legal & consulting content.</li>
                                <li><strong>GPT-4:</strong> Optimal for general conversion & triage.</li>
                                <li><strong>Perplexity:</strong> Best for real-time market insights.</li>
                                <li><strong>Ollama:</strong> Privacy-first local execution.</li>
                            </ul>
                        </div>
                        <div>
                            <h4>White-Label Strategy</h4>
                            <p style="font-size:13px; opacity:0.7;">Use the Agency Cluster to rebrand the neural assistant for your clients. See <code>agency-white-label-guide.md</code>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
        jQuery(document).ready(function($) {
            $('.nav-tab').on('click', function(e) {
                e.preventDefault();
                $('.nav-tab').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                $('.tab-content').hide();
                $($(this).attr('href')).show();
            });
        });
        </script>
        <?php
    }
}
new GrowthPress_Settings();
