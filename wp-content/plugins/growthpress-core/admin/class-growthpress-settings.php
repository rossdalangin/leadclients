<?php
/**
 * GrowthPress Settings Page - Tooltip Enhanced
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
    }

    public function render_settings() {
        ?>
        <div class="wrap growthpress-settings">
            <h1>OS Settings & API</h1>
            <p class="description">Central management for your AI connections and business niche configuration.</p>

            <form method="post" action="options.php" class="glass-card" style="max-width: 800px; margin-top: 20px;">
                <?php settings_fields( 'growthpress_settings_group' ); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label>OpenAI Secret Key</label>
                            <span class="gp-help-icon" title="Required for AI Lead Scoring, Content Studio, and Industry Calculators.">❔</span>
                        </th>
                        <td>
                            <input type="password" name="growthpress_openai_api_key" value="<?php echo esc_attr( get_option('growthpress_openai_api_key') ); ?>" class="regular-text" placeholder="sk-...">
                            <p class="description">Get your key from <a href="https://platform.openai.com" target="_blank">OpenAI Dashboard</a>.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label>Target Niche</label>
                            <span class="gp-help-icon" title="Selecting a niche loads industry-specific CPTs and AI prompts.">❔</span>
                        </th>
                        <td>
                            <select name="growthpress_niche">
                                <?php foreach(array('dental','law','contractor','roofing','solar','accounting','medical','real-estate','coaches','consultants') as $n): ?>
                                    <option value="<?php echo $n; ?>" <?php selected(get_option('growthpress_niche'), $n); ?>><?php echo ucfirst($n); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label>Bearer Token</label>
                            <span class="gp-help-icon" title="Used to authenticate Zapier or Make.com REST requests.">❔</span>
                        </th>
                        <td>
                            <input type="text" name="growthpress_api_token" value="<?php echo esc_attr( get_option('growthpress_api_token') ); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <style>.gp-help-icon { cursor:help; color:#2563EB; font-weight:bold; margin-left:5px; }</style>
        <?php
    }
}
new GrowthPress_Settings();
