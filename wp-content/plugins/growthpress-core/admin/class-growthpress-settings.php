<?php
/**
 * GrowthPress Settings Page
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
        add_submenu_page(
            'growthpress-dashboard',
            'Settings',
            'Settings',
            'manage_options',
            'growthpress-settings',
            array( $this, 'render_settings' )
        );
    }

    public function register_settings() {
        register_setting( 'growthpress_settings_group', 'growthpress_openai_api_key' );
        register_setting( 'growthpress_settings_group', 'growthpress_niche' );
        register_setting( 'growthpress_settings_group', 'growthpress_api_token' );
    }

    public function render_settings() {
        ?>
        <div class="wrap">
            <h1>GrowthPress Settings</h1>
            <form method="post" action="options.php" class="glass-card">
                <?php settings_fields( 'growthpress_settings_group' ); ?>
                <?php do_settings_sections( 'growthpress_settings_group' ); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">OpenAI API Key</th>
                        <td><input type="password" name="growthpress_openai_api_key" value="<?php echo esc_attr( get_option('growthpress_openai_api_key') ); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">System Niche</th>
                        <td>
                            <select name="growthpress_niche">
                                <?php
                                $niches = array('dental', 'law', 'contractor', 'roofing', 'solar', 'accounting', 'medical', 'real-estate', 'coaches', 'consultants');
                                $current = get_option('growthpress_niche');
                                foreach($niches as $n): ?>
                                    <option value="<?php echo $n; ?>" <?php selected($current, $n); ?>><?php echo ucfirst($n); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">REST API Token (Bearer)</th>
                        <td><input type="text" name="growthpress_api_token" value="<?php echo esc_attr( get_option('growthpress_api_token') ); ?>" class="regular-text"></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

new GrowthPress_Settings();
