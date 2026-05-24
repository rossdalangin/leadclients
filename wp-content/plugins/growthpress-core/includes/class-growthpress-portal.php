<?php
/**
 * GrowthPress Customer Portal Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Portal {

    public function __construct() {
        add_shortcode( 'gp_client_portal', array( $this, 'render_portal' ) );
    }

    public function render_portal() {
        if ( ! is_user_logged_in() ) {
            return '<p class="glass-card">Please <a href="' . wp_login_url() . '">login</a> to access your portal.</p>';
        }

        $user_id = get_current_user_id();
        $email = wp_get_current_user()->user_email;

        // Fetch appointments for this user
        $appointments = get_posts( array(
            'post_type'  => 'gp_appointment',
            'meta_key'   => '_client_email',
            'meta_value' => $email,
        ) );

        ob_start(); ?>
        <div class="gp-portal-container container">
            <div class="glass-card">
                <h2>Welcome to Your Client Portal</h2>
                <div class="portal-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="portal-section">
                        <h3>Your Appointments</h3>
                        <?php if ( $appointments ) : ?>
                            <ul>
                            <?php foreach ( $appointments as $app ) : ?>
                                <li><?php echo esc_html($app->post_title); ?> - <?php echo get_post_meta($app->ID, '_appointment_date', true); ?></li>
                            <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p>No upcoming appointments.</p>
                        <?php endif; ?>
                    </div>
                    <div class="portal-section">
                        <h3>Secure Documents</h3>
                        <?php do_action('gp_client_portal_dashboard'); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

new GrowthPress_Portal();
