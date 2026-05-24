<?php
/**
 * GrowthPress Settings Page - White Label Enhanced
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
        register_setting( 'growthpress_settings_group', 'growthpress_hot_threshold' );
    }

    public function render_settings() {
        ?>
        <div class="wrap growthpress-settings">
            <h1>OS Branding & API</h1>
            <p class="description">Configure the global identity and AI parameters of your Business OS.</p>

            <form method="post" action="options.php" class="glass-card" style="max-width: 800px; margin-top: 20px;">
                <?php settings_fields( 'growthpress_settings_group' ); ?>

                <table class="form-table">
                    <tr class="section-header"><th colspan="2"><h3>Agency & White-Label</h3></th></tr>
                    <tr>
                        <th scope="row"><label>Business/Brand Name</label></th>
                        <td>
                            <input type="text" name="growthpress_brand_name" value="<?php echo esc_attr( get_option('growthpress_brand_name', 'GrowthPress') ); ?>" class="regular-text">
                            <p class="description">Replaces the default dashboard title.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Primary Brand Color</label></th>
                        <td>
                            <input type="color" name="growthpress_primary_color" value="<?php echo esc_attr( get_option('growthpress_primary_color', '#2563EB') ); ?>">
                            <p class="description">Main accent color for themes and dashboard elements.</p>
                        </td>
                    </tr>

                    <tr class="section-header"><th colspan="2"><h3>AI Intelligence & Triage</h3></th></tr>
                    <tr>
                        <th scope="row"><label>OpenAI Secret Key</label></th>
                        <td><input type="password" name="growthpress_openai_api_key" value="<?php echo esc_attr( get_option('growthpress_openai_api_key') ); ?>" class="regular-text" placeholder="sk-..."></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Hot Lead Threshold</label></th>
                        <td>
                            <input type="number" name="growthpress_hot_threshold" value="<?php echo esc_attr( get_option('growthpress_hot_threshold', '80') ); ?>" style="width:60px;">
                            <p class="description">AI Score at which a lead is tagged as 'Hot'.</p>
                        </td>
                    </tr>

                    <tr class="section-header"><th colspan="2"><h3>Core Configuration</h3></th></tr>
                    <tr>
                        <th scope="row"><label>Target Niche</label></th>
                        <td>
                            <select name="growthpress_niche">
                                <?php foreach(array('dental','law','contractor','roofing','solar','accounting','medical','real-estate','coaches','consultants') as $n): ?>
                                    <option value="<?php echo $n; ?>" <?php selected(get_option('growthpress_niche'), $n); ?>><?php echo ucfirst($n); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <style>
            .section-header h3 { border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 0; color: #2563EB; }
        </style>
        <?php
    }
}
new GrowthPress_Settings();
