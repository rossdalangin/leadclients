<?php
/**
 * GrowthPress Settings Page - Final Elite v2.5
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
        wp_send_json_success( "Connection successful! AI Engine is online." );
    }

    public function render_settings() {
        ?>
        <div class="wrap growthpress-settings">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px;">
                <h1>Ecosystem Intelligence & Configuration</h1>
                <div style="background:var(--secondary); color:white; padding:8px 16px; border-radius:30px; font-size:11px; font-weight:900; letter-spacing:1px;">ELITE v2.5</div>
            </div>

            <div class="gp-settings-tabs">
                <h2 class="nav-tab-wrapper" style="border-bottom:none; margin-bottom:30px;">
                    <a href="#tab-config" class="nav-tab nav-tab-active">Configuration</a>
                    <a href="#tab-white-label" class="nav-tab">White-Label & Agency</a>
                    <a href="#tab-docs" class="nav-tab">Master Ops Manual</a>
                </h2>
            </div>

            <div id="tab-config" class="tab-content">
                <form method="post" action="options.php" class="glass-card" style="max-width:1000px;">
                    <?php settings_fields( 'growthpress_settings_group' ); ?>
                    <table class="form-table">
                        <tr class="section-header"><th colspan="2"><h3>Core Intelligence (OpenAI)</h3></th></tr>
                        <tr>
                            <th scope="row"><label>GPT-4 API Secret</label></th>
                            <td><input type="password" name="growthpress_openai_api_key" value="<?php echo esc_attr( get_option('growthpress_openai_api_key') ); ?>" class="regular-text" style="border-radius:10px;"></td>
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

            <div id="tab-white-label" class="tab-content" style="display:none;">
                <form method="post" action="options.php" class="glass-card" style="max-width:1000px;">
                    <?php settings_fields( 'growthpress_settings_group' ); ?>
                    <table class="form-table">
                        <tr class="section-header"><th colspan="2"><h3>Agency Branding</h3></th></tr>
                        <tr>
                            <th scope="row"><label>Proprietary OS Name</label></th>
                            <td>
                                <input type="text" name="growthpress_brand_name" value="<?php echo esc_attr( get_option('growthpress_brand_name', 'GrowthPress') ); ?>" class="regular-text">
                                <p class="description">Updates all admin references to your chosen product name.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Custom Dashboard Logo</label></th>
                            <td><input type="text" name="growthpress_dashboard_logo" value="<?php echo esc_attr( get_option('growthpress_dashboard_logo') ); ?>" class="regular-text" placeholder="https://..."></td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Enable Agency Mode</label></th>
                            <td>
                                <input type="checkbox" name="growthpress_agency_mode" value="1" <?php checked(1, get_option('growthpress_agency_mode'), true); ?>>
                                <p class="description">Hides all direct developer credits and technical GrowthPress branding for client-facing installs.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Agency License Key</label></th>
                            <td><input type="password" name="growthpress_license_key" value="<?php echo esc_attr( get_option('growthpress_license_key') ); ?>" class="regular-text"></td>
                        </tr>
                    </table>
                    <?php submit_button('Update Agency Settings'); ?>
                </form>
            </div>

            <div id="tab-docs" class="tab-content" style="display:none;">
                <div class="glass-card" style="max-width:1000px;">
                    <h2 class="text-gradient">Master Operations Manual</h2>
                    <p>Refer to the <code>/docs</code> directory in the core plugin for complete technical specifications and GTM plans.</p>
                    <hr style="opacity:0.1; margin:30px 0;">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:40px;">
                        <div>
                            <h4>Elite Onboarding</h4>
                            <p style="font-size:13px; opacity:0.7;">Detailed workflow for migrating standard firms to the OS. Check <code>onboarding-flow.md</code>.</p>
                        </div>
                        <div>
                            <h4>Agency White-Label Kit</h4>
                            <p style="font-size:13px; opacity:0.7;">Complete instructions for proprietary rebranding. Check <code>agency-white-label-guide.md</code>.</p>
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
