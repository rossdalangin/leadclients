<?php
/**
 * GrowthPress Settings Page - Final Polished
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
            'growthpress_openai_api_key', 'growthpress_niche', 'growthpress_api_token',
            'growthpress_brand_name', 'growthpress_primary_color', 'growthpress_hot_threshold',
            'growthpress_twilio_sid', 'growthpress_twilio_token', 'growthpress_whatsapp_key'
        );
        foreach($keys as $k) register_setting( 'growthpress_settings_group', $k );
    }

    public function render_settings() {
        ?>
        <div class="wrap growthpress-settings">
            <h1>Ecosystem Global Configuration</h1>
            <p class="description">Manage the identity, security, and intelligence parameters of your Business OS.</p>

            <div class="gp-settings-tabs" style="margin-top:20px;">
                <h2 class="nav-tab-wrapper">
                    <a href="#tab-config" class="nav-tab nav-tab-active">Configuration</a>
                    <a href="#tab-docs" class="nav-tab">How to Use & Documentation</a>
                </h2>
            </div>

            <div id="tab-config" class="tab-content">
            <form method="post" action="options.php" class="glass-card" style="max-width: 900px; margin-top: 20px;">
                <?php settings_fields( 'growthpress_settings_group' ); ?>

                <table class="form-table">
                    <tr class="section-header"><th colspan="2"><h3>Agency & White-Label Settings</h3></th></tr>
                    <tr>
                        <th scope="row"><label>Business/Brand Name</label></th>
                        <td>
                            <input type="text" name="growthpress_brand_name" value="<?php echo esc_attr( get_option('growthpress_brand_name', 'GrowthPress') ); ?>" class="regular-text">
                            <p class="description">Example: "Elite Dental Group". This rebrands the admin dashboard and AI Assistant.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Global Primary Color</label></th>
                        <td>
                            <input type="color" name="growthpress_primary_color" value="<?php echo esc_attr( get_option('growthpress_primary_color', '#2563EB') ); ?>">
                            <p class="description">Default: #2563EB. Used for all primary CTAs, Chat Bubble, and UI accents.</p>
                        </td>
                    </tr>

                    <tr class="section-header"><th colspan="2"><h3>Artificial Intelligence (OpenAI)</h3></th></tr>
                    <tr>
                        <th scope="row"><label>Secret API Key</label></th>
                        <td>
                            <input type="password" name="growthpress_openai_api_key" value="<?php echo esc_attr( get_option('growthpress_openai_api_key') ); ?>" class="regular-text">
                            <p class="description">Instruction: Get your key from platform.openai.com. Required for Triage and Content Studio.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Hot Lead Score Threshold</label></th>
                        <td>
                            <input type="number" name="growthpress_hot_threshold" value="<?php echo esc_attr( get_option('growthpress_hot_threshold', '80') ); ?>" style="width:70px;">
                            <p class="description">Score (1-100) at which a lead is automatically tagged as "Hot". Sample: 85.</p>
                        </td>
                    </tr>

                    <tr class="section-header"><th colspan="2"><h3>Infrastructure & Security</h3></th></tr>
                    <tr>
                        <th scope="row"><label>Bearer Auth Token</label></th>
                        <td>
                            <input type="text" name="growthpress_api_token" value="<?php echo esc_attr( get_option('growthpress_api_token') ); ?>" class="regular-text">
                            <p class="description">Secure token for REST API. Example: gp_sec_token_99.</p>
                        </td>
                    </tr>

                    <tr class="section-header"><th colspan="2"><h3>Maintenance & Reset</h3></th></tr>
                    <tr>
                        <th scope="row"><label>Regenerate Core Assets</label></th>
                        <td>
                            <button type="button" class="button" onclick="regenerateOS()">Regenerate Pages & Sample Data</button>
                            <p class="description">Updates existing core pages (Home, Services, etc.) with fresh Customizer content and adds new demo CPT records. Does not delete existing user content.</p>
                            <script>
                            function regenerateOS() {
                                if(!confirm("This will overwrite existing core page content with updated settings. Continue?")) return;
                                jQuery.post(ajaxurl, { action: 'gp_regenerate_pages', gp_nonce: '<?php echo wp_create_nonce("gp_admin_nonce"); ?>' }, function(res) {
                                    alert(res.data);
                                    location.reload();
                                });
                            }
                            </script>
                        </td>
                    </tr>

                    <tr class="section-header"><th colspan="2"><h3>SMS & Communication</h3></th></tr>
                    <tr>
                        <th scope="row"><label>Twilio SID</label></th>
                        <td><input type="text" name="growthpress_twilio_sid" value="<?php echo esc_attr( get_option('growthpress_twilio_sid') ); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Twilio Auth Token</label></th>
                        <td><input type="password" name="growthpress_twilio_token" value="<?php echo esc_attr( get_option('growthpress_twilio_token') ); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>WhatsApp Business API Key</label></th>
                        <td>
                            <input type="text" name="growthpress_whatsapp_key" value="<?php echo esc_attr( get_option('growthpress_whatsapp_key') ); ?>" class="regular-text">
                            <p class="description">Required for high-ticket WhatsApp automation workflows.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Update Operating System Settings'); ?>
            </form>
            </div>

            <div id="tab-docs" class="tab-content" style="display:none; max-width:900px; margin-top:20px;">
                <div class="glass-card">
                    <h3>Getting Started with GrowthPress</h3>
                    <p>GrowthPress is designed to be your business's "brain". Here is how to maximize its potential:</p>

                    <div class="doc-section" style="margin-bottom:25px;">
                        <h4>1. The "Golden" Setup</h4>
                        <ol>
                            <li><strong>Connect OpenAI:</strong> Without an API key, the "brain" is inactive. Lead scoring and AI triage will not function.</li>
                            <li><strong>Run the Wizard:</strong> Go to the <a href="<?php echo admin_url('admin.php?page=growthpress-dashboard'); ?>">Dashboard</a> and select your niche. This creates your sales funnel pages instantly.</li>
                            <li><strong>Branding:</strong> Use the <a href="<?php echo admin_url('customize.php'); ?>">Customizer</a> to set your brand color. This propagates to all AI chat bubbles and interactive widgets.</li>
                        </ol>
                    </div>

                    <div class="doc-section" style="margin-bottom:25px;">
                        <h4>2. Mastering Lead Generation</h4>
                        <p>Use the <code>[gp_quiz_lead_form]</code> on your homepage. Behavioral psychology shows that multi-step quizzes convert 3x better than standard forms for high-ticket services.</p>
                        <p><strong>Pro Tip:</strong> Check your "Leads" menu frequently. AI scores appear next to each lead to tell you who is ready to buy <em>now</em>.</p>
                    </div>

                    <div class="doc-section" style="margin-bottom:25px;">
                        <h4>3. SEO & Market Dominance</h4>
                        <p>The <strong>AI Content Studio</strong> is your growth engine. Use the "Market Insights" tool to find gaps in your local competitors' strategies, then generate 5 blog posts targeting those gaps.</p>
                    </div>

                    <div class="doc-section" style="margin-bottom:25px;">
                        <h4>4. Mobile & User Experience</h4>
                        <p>All GrowthPress components are <strong>mobile-first</strong>. We recommend testing your "Booking" page on a smartphone to see the optimized "Glassmorphism" interface in action.</p>
                    </div>

                    <div class="doc-section">
                        <h4>5. Niche-Specific Next Steps</h4>
                        <div style="background:#f1f5f9; padding:15px; border-radius:8px; font-size:13px;">
                            <?php
                            $niche = get_option('growthpress_niche', 'business');
                            $next_steps = array(
                                'dental'    => "Upload before/after photos to the 'Treatments' post type to populate your Smile Gallery.",
                                'law'       => "Use the 'Legal Intake' shortcode to qualify leads before booking a consultation.",
                                'solar'     => "Check the 'ROI Estimator' on your homepage to ensure it matches your local utility rates.",
                                'contractor'=> "Add your service area ZIP codes in the Locations menu to enable smart lead routing."
                            );
                            echo $next_steps[$niche] ?? "Initialize your OS in the Dashboard to see industry-specific recommendations.";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .section-header h3 { border-bottom: 2px solid #2563EB; padding-bottom: 10px; color: #1e293b; }
            .nav-tab-wrapper { margin-bottom: 20px; }
            .glass-card { background: white; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
            .doc-section h4 { color: #2563EB; margin-top: 0; }
        </style>
        <script>
        jQuery(document).ready(function($) {
            $('.nav-tab').on('click', function(e) {
                e.preventDefault();
                var target = $(this).attr('href');
                $('.nav-tab').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                $('.tab-content').hide();
                $(target).show();
            });
        });
        </script>
        <?php
    }
}
new GrowthPress_Settings();
