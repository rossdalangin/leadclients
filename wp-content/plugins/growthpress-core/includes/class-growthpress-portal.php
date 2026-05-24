<?php
/**
 * GrowthPress Customer Portal Class - Proposal Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Portal {

    public function __construct() {
        add_shortcode( 'gp_client_portal', array( $this, 'render_portal' ) );
        add_action( 'wp_ajax_gp_accept_proposal', array( $this, 'handle_proposal_acceptance' ) );
    }

    public function render_portal() {
        if ( ! is_user_logged_in() ) {
            return '<p class="glass-card">Please login to access your portal.</p>';
        }

        $email = wp_get_current_user()->user_email;
        $appointments = get_posts( array( 'post_type' => 'gp_appointment', 'meta_key' => '_client_email', 'meta_value' => $email ) );

        // Find leads associated with this email to get proposals
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'meta_key' => '_lead_email', 'meta_value' => $email ) );
        $proposals = array();
        if ( ! empty($leads) ) {
            $lead_ids = wp_list_pluck($leads, 'ID');
            $proposals = get_posts( array( 'post_type' => 'gp_proposal', 'meta_key' => '_related_lead', 'meta_compare' => 'IN', 'meta_value' => $lead_ids ) );
        }

        ob_start(); ?>
        <div class="gp-portal-container container">
            <div class="glass-card">
                <h2>Welcome, <?php echo wp_get_current_user()->display_name; ?></h2>
                <div class="portal-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                    <div class="portal-section">
                        <h3>Your Appointments</h3>
                        <?php if($appointments): ?>
                            <ul><?php foreach($appointments as $app) echo "<li>" . esc_html($app->post_title) . "</li>"; ?></ul>
                        <?php else: echo "<p>No appointments found.</p>"; endif; ?>
                    </div>
                    <div class="portal-section">
                        <h3>Proposals</h3>
                        <?php if($proposals): ?>
                            <?php foreach($proposals as $prop):
                                $status = get_post_meta($prop->ID, '_gp_proposal_status', true) ?: 'Pending'; ?>
                                <div class="proposal-item" style="border-bottom: 1px solid #eee; padding: 10px 0;">
                                    <strong><?php echo esc_html($prop->post_title); ?></strong> (<?php echo $status; ?>)
                                    <?php if($status === 'Pending'): ?>
                                        <button onclick="acceptProposal(<?php echo $prop->ID; ?>)">Accept Proposal</button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: echo "<p>No active proposals.</p>"; endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
        function acceptProposal(id) {
            jQuery.post(gp_ajax.ajaxurl, { action: 'gp_accept_proposal', proposal_id: id }, function(res) {
                if(res.success) location.reload();
            });
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_proposal_acceptance() {
        $proposal_id = intval($_POST['proposal_id']);
        update_post_meta( $proposal_id, '_gp_proposal_status', 'Accepted' );
        wp_send_json_success('Proposal accepted.');
    }
}

new GrowthPress_Portal();
