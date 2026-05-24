<?php
/**
 * GrowthPress Customer Portal Class - Final Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Portal {

    public function __construct() {
        add_shortcode( 'gp_client_portal', array( $this, 'render_portal' ) );
        add_action( 'wp_ajax_gp_accept_proposal', array( $this, 'handle_proposal_acceptance' ) );
        add_action( 'gp_proposal_accepted', array( $this, 'trigger_post_acceptance_logic' ) );
    }

    public function trigger_post_acceptance_logic( $proposal_id ) {
        $lead_id = get_post_meta($proposal_id, '_related_lead', true);

        // 1. Create project kickoff task
        $crm = GrowthPress_CRM::get_instance();
        $crm->create_task( "PROJECT KICKOFF: " . get_the_title($lead_id), "Proposal accepted. Start onboarding.", $lead_id );

        // 2. Generate Invoice
        $payments = new GrowthPress_Payments();
        $payments->create_invoice( 500, $proposal_id, 'proposal' ); // Mock 500 deposit

        // 3. AI generated confirmation
        $ai = GrowthPress_AI::get_instance();
        $msg = $ai->call_ai("Generate a project kickoff email.", "You are a customer success manager.");
        update_post_meta($proposal_id, '_kickoff_msg', $msg);
    }

    public function render_portal() {
        if ( ! is_user_logged_in() ) return '<p>Please login.</p>';
        $email = wp_get_current_user()->user_email;
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'meta_key' => '_lead_email', 'meta_value' => $email ) );
        $proposals = array();
        if ( ! empty($leads) ) {
            $lead_ids = wp_list_pluck($leads, 'ID');
            $proposals = get_posts( array( 'post_type' => 'gp_proposal', 'meta_key' => '_related_lead', 'meta_compare' => 'IN', 'meta_value' => $lead_ids ) );
        }
        ob_start(); ?>
        <div class="gp-portal-container glass-card">
            <h2>Client Portal</h2>
            <h3>Active Proposals</h3>
            <?php foreach($proposals as $prop):
                $status = get_post_meta($prop->ID, '_gp_proposal_status', true) ?: 'Pending'; ?>
                <div class="proposal">
                    <strong><?php echo esc_html($prop->post_title); ?></strong> - Status: <?php echo $status; ?>
                    <?php if($status === 'Pending'): ?>
                        <button onclick="acceptProposal(<?php echo $prop->ID; ?>)">Accept & Pay Deposit</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
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
        do_action( 'gp_proposal_accepted', $proposal_id );
        wp_send_json_success('Accepted.');
    }
}
new GrowthPress_Portal();
