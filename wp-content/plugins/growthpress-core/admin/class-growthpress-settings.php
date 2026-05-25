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
        <style>.section-header h3 { border-bottom: 2px solid #2563EB; padding-bottom: 10px; color: #1e293b; }</style>
        <?php
    }
}
new GrowthPress_Settings();
