<?php
/**
 * GrowthPress Settings Page - Instruction Enhanced
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
        <div class="wrap growthpress-settings">
            <h1>GrowthPress System Configuration</h1>
            <p class="description">Configure the core parameters of your Business Operating System. <strong>Note:</strong> Ensure your OpenAI API key is active to enable AI triage and content features.</p>

            <form method="post" action="options.php" class="glass-card" style="max-width: 800px; margin-top: 20px;">
                <?php settings_fields( 'growthpress_settings_group' ); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label>OpenAI API Key</label>
                            <p class="description">Used for AI Triage, Content Gen, and Lead Scoring.</p>
                        </th>
                        <td>
                            <input type="password" name="growthpress_openai_api_key" value="<?php echo esc_attr( get_option('growthpress_openai_api_key') ); ?>" class="regular-text">
                            <p class="help-text" style="font-size: 11px; color: #666;">Example: sk-proj-xxxxxxxxxxxxxxxxxxxx</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label>Business Niche</label>
                            <p class="description">Select your industry to load custom logic and tools.</p>
                        </th>
                        <td>
                            <select name="growthpress_niche" style="width: 25em;">
                                <?php
                                $niches = array('dental', 'law', 'contractor', 'roofing', 'solar', 'accounting', 'medical', 'real-estate', 'coaches', 'consultants');
                                $current = get_option('growthpress_niche');
                                foreach($niches as $n): ?>
                                    <option value="<?php echo $n; ?>" <?php selected($current, $n); ?>><?php echo ucfirst($n); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="help-text" style="font-size: 11px; color: #666;">This affects Schema markup, Urgency banners, and AI prompts.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label>API Auth Token</label>
                            <p class="description">Secure Bearer token for Zapier / Make.com.</p>
                        </th>
                        <td>
                            <input type="text" name="growthpress_api_token" value="<?php echo esc_attr( get_option('growthpress_api_token') ); ?>" class="regular-text">
                            <p class="help-text" style="font-size: 11px; color: #666;">Example: gp_live_998877665544332211</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save OS Configuration'); ?>
            </form>

            <div class="glass-card" style="margin-top: 20px; max-width: 800px; border-left: 4px solid #2563EB;">
                <h4>Quick Instructions</h4>
                <ol>
                    <li>Generate an API key at <a href="https://platform.openai.com" target="_blank">OpenAI</a>.</li>
                    <li>Select your niche to activate industry-specific Custom Post Types.</li>
                    <li>Use the <strong>Niche Setup Wizard</strong> on the main dashboard to generate demo pages.</li>
                </ol>
            </div>
        </div>
        <?php
    }
}

new GrowthPress_Settings();
