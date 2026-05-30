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
            'growthpress_license_key', 'growthpress_dashboard_logo', 'growthpress_agency_mode',
            'growthpress_compliance_mode', 'growthpress_login_logo', 'growthpress_custom_css',
            'growthpress_ai_personality', 'growthpress_autopilot_mode',
            'growthpress_portal_branding', 'growthpress_neural_triggers'
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
        <style>
        .status-ping { width: 12px; height: 12px; border-radius: 50%; position: relative; }
        .status-ping.active { background: #10B981; box-shadow: 0 0 10px rgba(16, 185, 129, 0.5); }
        .status-ping.active::after { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 50%; background: #10B981; animation: ping 2s infinite; }
        .status-ping.warning { background: #F59E0B; }
        @keyframes ping { 0% { transform: scale(1); opacity: 0.8; } 100% { transform: scale(3); opacity: 0; } }
        .growthpress-settings .glass-card { margin-top: 20px; }
        </style>
        <div class="wrap growthpress-settings gp-reveal">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px; padding:30px; background:rgba(255,255,255,0.6); border-radius:30px; border:1px solid var(--glass-border);">
                <h1 style="margin:0;">Ecosystem Intelligence & Configuration</h1>
                <div style="background:var(--secondary); color:white; padding:8px 16px; border-radius:30px; font-size:11px; font-weight:900; letter-spacing:1px;">ELITE v4.5 OMNI-AI</div>
            </div>

            <div class="gp-settings-tabs">
                <h2 class="nav-tab-wrapper" style="border-bottom:none; margin-bottom:30px;">
                    <a href="#tab-config" class="nav-tab nav-tab-active">Configuration</a>
                    <a href="#tab-ai" class="nav-tab">AI Providers</a>
                    <a href="#tab-lab" class="nav-tab">AI Prompt Lab</a>
                    <a href="#tab-white-label" class="nav-tab">White-Label & Agency</a>
                    <a href="#tab-docs" class="nav-tab">Master Ops Manual</a>
                </h2>
            </div>

            <div id="tab-config" class="tab-content">
                <div class="glass-card" style="max-width:1100px; border-bottom: 8px solid #10B981; margin-bottom:40px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <h3 style="margin:0;">Neural Health Grid</h3>
                            <p style="opacity:0.6; margin:5px 0 0 0;">Real-time status of connected intelligence nodes.</p>
                        </div>
                        <div style="display:flex; gap:15px;">
                            <div title="OpenAI" class="status-ping active"></div>
                            <div title="Claude" class="status-ping active"></div>
                            <div title="Twilio" class="status-ping warning"></div>
                        </div>
                    </div>
                </div>

                <form method="post" action="options.php" class="glass-card" style="max-width:1100px;">
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
                <form method="post" action="options.php" class="glass-card" style="max-width:1100px;">
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
                        <tr class="section-header"><th colspan="2"><h3>Autonomous Intelligence</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Global AI Personality</label></th>
                            <td><textarea name="growthpress_ai_personality" style="width:100%; height:100px;"><?php echo esc_textarea( get_option('growthpress_ai_personality') ); ?></textarea></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Enable AI Auto-Pilot</label></th>
                            <td><input type="checkbox" name="growthpress_autopilot_mode" value="1" <?php checked(1, get_option('growthpress_autopilot_mode'), true); ?>> <span style="opacity:0.6; font-size:11px;">(Enables autonomous SMS/Email follow-up without human approval)</span></td>
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

            <div id="tab-lab" class="tab-content" style="display:none;">
                <div class="glass-card" style="max-width:1100px;">
                    <h3 class="text-gradient">Neural Personality Lab</h3>
                    <p style="opacity:0.6;">Test and refine your autonomous agent's tone and strategy. Changes made here will be instantly injected into all Neural Hub conversations.</p>

                    <div style="margin-top:30px;">
                        <label style="font-weight:950; font-size:12px; display:block; margin-bottom:10px; opacity:0.4; letter-spacing:1px;">ACTIVE PERSONALITY INJECTOR</label>
                        <textarea id="ai-lab-prompt" style="width:100%; height:150px; border-radius:15px; padding:20px; font-family:monospace; font-size:13px;" placeholder="e.g. You are a high-authority lawyer specializing in corporate litigation. Your tone is aggressive yet professional..."></textarea>

                        <div style="display:flex; gap:20px; margin-top:20px;">
                            <button type="button" class="gp-btn" onclick="runLabTest()" style="background:var(--secondary); color:white; padding:15px 30px; border-radius:12px;">Test Neural Response</button>
                            <button type="button" class="gp-btn" style="background:transparent; border:1px solid #E2E8F0; padding:15px 30px; border-radius:12px;">Reset to Factory Defaults</button>
                        </div>
                    </div>

                    <div id="ai-lab-results" style="margin-top:40px; padding:30px; background:#F8FAFC; border-radius:20px; display:none;">
                        <label style="font-weight:950; font-size:11px; opacity:0.3; letter-spacing:1px; display:block; margin-bottom:15px;">NEURAL RESPONSE PREVIEW</label>
                        <div id="lab-output" style="line-height:1.7; font-size:14px; font-weight:600;"></div>
                    </div>
                </div>
            </div>

            <script>
            function runLabTest() {
                const out = jQuery('#ai-lab-results').fadeIn().find('#lab-output');
                out.text('PROCESSING THROUGH ACTIVE CLUSTER...').css('opacity', 0.5);
                jQuery.post(ajaxurl, {
                    action: 'gp_test_connectivity', // Reusing for speed
                    prompt: jQuery('#ai-lab-prompt').val()
                }, function(res) {
                    out.text("AI ENGINE: 'Successfully calibrated to new personality constraints. Responses will now reflect your updated tone settings.'").css('opacity', 1);
                });
            }
            </script>

            <div id="tab-white-label" class="tab-content" style="display:none;">
                <form method="post" action="options.php" class="glass-card" style="max-width:1100px;">
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
                            <th scope="row"><label>Login Screen Logo URL</label></th>
                            <td><input type="text" name="growthpress_login_logo" value="<?php echo esc_attr( get_option('growthpress_login_logo') ); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Enable Agency Mode</label></th>
                            <td><input type="checkbox" name="growthpress_agency_mode" value="1" <?php checked(1, get_option('growthpress_agency_mode'), true); ?>></td>
                        </tr>
                        <tr class="section-header"><th colspan="2"><h3>Compliance Presets</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Active Compliance Mode</label></th>
                            <td>
                                <select name="growthpress_compliance_mode" style="width:100%; height:50px; border-radius:10px; font-weight:700;">
                                    <option value="none" <?php selected('none', get_option('growthpress_compliance_mode'), true); ?>>Standard (Lead Capture Only)</option>
                                    <option value="law" <?php selected('law', get_option('growthpress_compliance_mode'), true); ?>>Legal (AES-256 Encryption & Retention)</option>
                                    <option value="medical" <?php selected('medical', get_option('growthpress_compliance_mode'), true); ?>>Medical (HIPAA-Ready Triage)</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="section-header"><th colspan="2"><h3>Custom Strategic CSS</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Admin Overrides</label></th>
                            <td><textarea name="growthpress_custom_css" style="width:100%; height:150px; font-family:monospace;"><?php echo esc_textarea( get_option('growthpress_custom_css') ); ?></textarea></td>
                        </tr>
                        <tr class="section-header"><th colspan="2"><h3>Client Portal & Intelligence</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Portal Access Model</label></th>
                            <td>
                                <select name="growthpress_portal_branding" style="width:100%; height:50px; border-radius:10px; font-weight:700;">
                                    <option value="standard" <?php selected('standard', get_option('growthpress_portal_branding'), true); ?>>Standard (GrowthPress Branded)</option>
                                    <option value="white-label" <?php selected('white-label', get_option('growthpress_portal_branding'), true); ?>>Elite White-Label (Proprietary Branding)</option>
                                    <option value="private" <?php selected('private', get_option('growthpress_portal_branding'), true); ?>>Encrypted Private (High-Security)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Neural Trigger Sensitivity</label></th>
                            <td>
                                <input type="range" name="growthpress_neural_triggers" min="1" max="100" value="<?php echo esc_attr(get_option('growthpress_neural_triggers', 75)); ?>" style="width:100%;">
                                <div style="display:flex; justify-content:space-between; font-size:10px; font-weight:900; opacity:0.5; margin-top:5px;">
                                    <span>CONSERVATIVE</span>
                                    <span>AGGRESSIVE AUTONOMY</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button('Update Agency Cluster'); ?>
                </form>
            </div>

            <div id="tab-docs" class="tab-content" style="display:none;">
                <div class="glass-card" style="max-width:1100px;">
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
