<?php
/**
 * GrowthPress Settings Page - Integrations Enhanced
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
        $settings = array(
            'growthpress_openai_api_key', 'growthpress_niche', 'growthpress_api_token',
            'growthpress_brand_name', 'growthpress_primary_color', 'growthpress_hot_threshold',
            'growthpress_twilio_sid', 'growthpress_twilio_token', 'growthpress_twilio_number',
            'growthpress_whatsapp_token', 'growthpress_whatsapp_phone_id'
        );
        foreach($settings as $s) register_setting( 'growthpress_settings_group', $s );
    }

    public function render_settings() {
        ?>
        <div class="wrap growthpress-settings">
            <h1>OS Settings & Integrations</h1>
            <form method="post" action="options.php" class="glass-card" style="max-width: 900px; margin-top: 20px;">
                <?php settings_fields( 'growthpress_settings_group' ); ?>

                <table class="form-table">
                    <tr class="section-header"><th colspan="2"><h3>Agency & White-Label</h3></th></tr>
                    <tr>
                        <th scope="row"><label>Brand Name</label></th>
                        <td><input type="text" name="growthpress_brand_name" value="<?php echo esc_attr( get_option('growthpress_brand_name', 'GrowthPress') ); ?>" class="regular-text"></td>
                    </tr>

                    <tr class="section-header"><th colspan="2"><h3>Communication (Twilio & WhatsApp)</h3></th></tr>
                    <tr>
                        <th scope="row"><label>Twilio Account SID</label></th>
                        <td><input type="text" name="growthpress_twilio_sid" value="<?php echo esc_attr( get_option('growthpress_twilio_sid') ); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Twilio Auth Token</label></th>
                        <td><input type="password" name="growthpress_twilio_token" value="<?php echo esc_attr( get_option('growthpress_twilio_token') ); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>WhatsApp Phone ID</label></th>
                        <td><input type="text" name="growthpress_whatsapp_phone_id" value="<?php echo esc_attr( get_option('growthpress_whatsapp_phone_id') ); ?>" class="regular-text"></td>
                    </tr>

                    <tr class="section-header"><th colspan="2"><h3>AI Intelligence</h3></th></tr>
                    <tr>
                        <th scope="row"><label>OpenAI Secret Key</label></th>
                        <td><input type="password" name="growthpress_openai_api_key" value="<?php echo esc_attr( get_option('growthpress_openai_api_key') ); ?>" class="regular-text"></td>
                    </tr>
                </table>
                <?php submit_button('Update Business OS'); ?>
            </form>
        </div>
        <style>.section-header h3 { border-bottom: 2px solid #eee; padding-bottom: 10px; color: #2563EB; }</style>
        <?php
    }
}
new GrowthPress_Settings();
