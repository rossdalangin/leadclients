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
            'growthpress_twilio_sid', 'growthpress_twilio_token', 'growthpress_whatsapp_key',
            'growthpress_google_maps_key', 'growthpress_stripe_key', 'growthpress_stripe_secret'
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

                    <tr class="section-header"><th colspan="2"><h3>System Health & Connectivity</h3></th></tr>
                    <tr>
                        <th scope="row"><label>Engine Status</label></th>
                        <td>
                            <?php $ai_key = get_option('growthpress_openai_api_key'); ?>
                            <span class="status-indicator <?php echo $ai_key ? 'active' : 'inactive'; ?>" style="display:inline-block; width:10px; height:10px; border-radius:50%; background: <?php echo $ai_key ? '#10B981' : '#EF4444'; ?>; margin-right:5px;"></span>
                            <strong>OpenAI:</strong> <?php echo $ai_key ? 'Connected' : 'Missing Key'; ?>
                            <br>
                            <?php $tw_sid = get_option('growthpress_twilio_sid'); ?>
                            <span class="status-indicator <?php echo $tw_sid ? 'active' : 'inactive'; ?>" style="display:inline-block; width:10px; height:10px; border-radius:50%; background: <?php echo $tw_sid ? '#10B981' : '#F59E0B'; ?>; margin-right:5px;"></span>
                            <strong>Twilio:</strong> <?php echo $tw_sid ? 'Active' : 'Optional (SMS Disabled)'; ?>
                        </td>
                    </tr>

                    <tr class="section-header"><th colspan="2"><h3>Operational Integrations</h3></th></tr>
                    <tr>
                        <th scope="row"><label>Google Maps API Key</label></th>
                        <td><input type="text" name="growthpress_google_maps_key" value="<?php echo esc_attr( get_option('growthpress_google_maps_key') ); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Stripe Public Key</label></th>
                        <td><input type="text" name="growthpress_stripe_key" value="<?php echo esc_attr( get_option('growthpress_stripe_key') ); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Stripe Secret Key</label></th>
                        <td><input type="password" name="growthpress_stripe_secret" value="<?php echo esc_attr( get_option('growthpress_stripe_secret') ); ?>" class="regular-text"></td>
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
                    <div class="doc-header" style="text-align:center; margin-bottom:40px;">
                        <h2 style="font-size:2rem; margin-bottom:10px;">Master Operations Manual</h2>
                        <p>Follow the success roadmap to turn your site into an autonomous growth engine.</p>
                    </div>

                    <h3>🚀 Launch Readiness Roadmap</h3>
                    <div class="roadmap-ui" style="display:flex; gap:10px; margin-bottom:30px; text-align:center;">
                        <div style="flex:1; padding:10px; background:#f0f9ff; border-radius:8px; border:1px solid #bae6fd;"><strong>1</strong><br><small>Connect API</small></div>
                        <div style="flex:1; padding:10px; background:#f0f9ff; border-radius:8px; border:1px solid #bae6fd;"><strong>2</strong><br><small>Run Wizard</small></div>
                        <div style="flex:1; padding:10px; background:#f0f9ff; border-radius:8px; border:1px solid #bae6fd;"><strong>3</strong><br><small>Set Brand</small></div>
                        <div style="flex:1; padding:10px; background:#f0f9ff; border-radius:8px; border:1px solid #bae6fd;"><strong>4</strong><br><small>Go Live</small></div>
                    </div>

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

                    <div class="doc-section" style="margin-bottom:25px;">
                        <h4>5. Automations & System Processes</h4>
                        <p>GrowthPress runs the following autonomous processes to scale your business:</p>
                        <ul style="font-size:13px; color:#475569;">
                            <li><strong>Lead Triage:</strong> Immediate sentiment analysis and urgency scoring (0-100) on all new inquiries.</li>
                            <li><strong>Smart Routing:</strong> Leads scoring 80+ are instantly flagged for priority handling.</li>
                            <li><strong>Abandoned Follow-up:</strong> Automated re-engagement for leads stuck in "New" stage for >24 hours.</li>
                            <li><strong>Meeting Automation:</strong> Dynamic generation of secure telemedicine or Zoom links upon booking.</li>
                            <li><strong>Sync & Regen:</strong> One-click ecosystem refresh to keep your site pages aligned with Customizer branding.</li>
                        </ul>
                    </div>

                    <div class="doc-section" style="margin-bottom:25px;">
                        <h4>5. The GrowthPress Process Map</h4>
                        <p>Your system automates these 5 critical business lifecycles:</p>
                        <div style="background:#f8fafc; padding:20px; border-radius:12px; font-size:13px; border:1px solid #e2e8f0;">
                            <strong>1. Lead Triage:</strong> Immediate AI analysis of every inquiry.<br>
                            <strong>2. Opportunity Scoring:</strong> Real-time deal probability calculation.<br>
                            <strong>3. Action Planning:</strong> Autonomous generation of sales tasks for every lead.<br>
                            <strong>4. Automated Nurture:</strong> 5-day intent-based re-engagement.<br>
                            <strong>5. Operation Sync:</strong> Self-service client portal & document hub.<br>
                            <strong>6. Closing:</strong> One-click proposal generation and acceptance.
                        </div>
                    </div>

                    <div class="doc-section" style="margin-bottom:25px;">
                        <h4>6. Strategic Brief & Market Dominance</h4>
                        <p>To dominate your local market, use the **AI Content Studio** to identify "Angle of Attack" strategies. High-ticket sales are won by answering questions the prospect hasn't even asked yet.</p>
                        <ul style="font-size:13px;">
                            <li><strong>Positioning:</strong> Position yourself as the <em>Authority</em> using the 'Our Mission' page generator.</li>
                            <li><strong>Triage:</strong> Use AI Triage to handle 80% of common questions, reserving human staff for final closure.</li>
                        </ul>
                    </div>

                    <div class="doc-section" style="margin-bottom:25px;">
                        <h4>7. High-Ticket Sales Playbook</h4>
                        <p>Success in high-ticket niches requires <strong>speed-to-lead</strong>. When the AI alerts you of a "Hot" lead (Score 80+):</p>
                        <ul style="font-size:13px;">
                            <li><strong>Call within 5 mins:</strong> The AI sentiment analysis will tell you their pain point. Mention it immediately.</li>
                            <li><strong>Use the Kanban:</strong> Drag leads to 'Booked' as soon as the discovery call is set to trigger automation.</li>
                            <li><strong>Proposals:</strong> Use the AI Proposal generator in the Client Portal to send a professional quote before you hang up.</li>
                        </ul>
                    </div>

                    <div class="doc-section" style="margin-bottom:25px;">
                        <h4>6. Technical System Audit</h4>
                        <div style="font-size:12px; border:1px solid #e2e8f0; padding:10px; border-radius:8px;">
                            <div style="display:flex; justify-content:space-between; margin-bottom:5px;"><span>PHP Version</span> <span><?php echo PHP_VERSION; ?> (OK)</span></div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:5px;"><span>WP Version</span> <span><?php echo get_bloginfo('version'); ?> (OK)</span></div>
                            <div style="display:flex; justify-content:space-between;"><span>Memory Limit</span> <span><?php echo ini_get('memory_limit'); ?></span></div>
                        </div>
                    </div>

                    <div class="doc-section">
                        <h4>7. Troubleshooting & Support</h4>
                        <ul style="font-size:13px;">
                            <li><strong>AI not responding?</strong> Check your OpenAI API key and credit balance.</li>
                            <li><strong>Pages not syncing?</strong> Use the "Regenerate Core Assets" button in the Maintenance tab.</li>
                            <li><strong>SMS not sending?</strong> Ensure your Twilio SID and Token are correct and your account is active.</li>
                        </ul>
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
