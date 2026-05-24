<?php
/**
 * GrowthPress Settings Page - License Enhanced
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
        register_setting( 'growthpress_settings_group', 'growthpress_openai_api_key' );
        register_setting( 'growthpress_settings_group', 'growthpress_niche' );
        register_setting( 'growthpress_settings_group', 'growthpress_api_token' );
        register_setting( 'growthpress_settings_group', 'growthpress_brand_name' );
        register_setting( 'growthpress_settings_group', 'growthpress_primary_color' );
        register_setting( 'growthpress_settings_group', 'growthpress_license_key' );
    }

    public function render_settings() {
        ?>
        <div class="wrap growthpress-settings">
            <h1>OS Settings & License</h1>

            <form method="post" action="options.php" class="glass-card" style="max-width: 800px; margin-top: 20px;">
                <?php settings_fields( 'growthpress_settings_group' ); ?>

                <table class="form-table">
                    <tr class="section-header"><th colspan="2"><h3>Agency & Licensing</h3></th></tr>
                    <tr>
                        <th scope="row"><label>License Activation Key</label></th>
                        <td>
                            <input type="text" name="growthpress_license_key" value="<?php echo esc_attr( get_option('growthpress_license_key') ); ?>" class="regular-text" placeholder="XXXX-XXXX-XXXX-XXXX">
                            <p class="description">Status: <?php echo get_option('growthpress_license_key') ? '<span style="color:green;">Activated</span>' : '<span style="color:red;">Not Active</span>'; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Brand Name</label></th>
                        <td><input type="text" name="growthpress_brand_name" value="<?php echo esc_attr( get_option('growthpress_brand_name', 'GrowthPress') ); ?>" class="regular-text"></td>
                    </tr>

                    <tr class="section-header"><th colspan="2"><h3>AI Intelligence</h3></th></tr>
                    <tr>
                        <th scope="row"><label>OpenAI Secret Key</label></th>
                        <td><input type="password" name="growthpress_openai_api_key" value="<?php echo esc_attr( get_option('growthpress_openai_api_key') ); ?>" class="regular-text"></td>
                    </tr>
                </table>
                <?php submit_button('Save Configuration'); ?>
            </form>
        </div>
        <style>.section-header h3 { border-bottom: 2px solid #eee; padding-bottom: 10px; color: #2563EB; }</style>
        <?php
    }
}
new GrowthPress_Settings();
