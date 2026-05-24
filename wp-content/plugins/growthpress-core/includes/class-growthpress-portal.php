<?php
/**
 * GrowthPress Customer Portal Class - Final
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
        $crm = GrowthPress_CRM::get_instance();
        $crm->create_task( "PROJECT KICKOFF: " . get_the_title($lead_id), "Proposal accepted.", $lead_id );

        $payments = new GrowthPress_Payments();
        $payments->create_invoice( 500, $proposal_id, 'proposal' );

        // AI Welcome
        $ai = GrowthPress_AI::get_instance();
        $msg = $ai->call_ai("Generate a welcome email for a new project.", "You are a customer success manager.");
        update_post_meta($proposal_id, '_kickoff_msg', $msg);
    }

    public function render_portal() {
        if ( ! is_user_logged_in() ) return '<p>Please login.</p>';
        $email = wp_get_current_user()->user_email;

        // Fetch projects (linked to this email via lead)
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'meta_key' => '_lead_email', 'meta_value' => $email ) );
        $lead_ids = wp_list_pluck($leads, 'ID');

        $proposals = array();
        $projects = array();
        if ( ! empty($lead_ids) ) {
            $proposals = get_posts( array( 'post_type' => 'gp_proposal', 'meta_key' => '_related_lead', 'meta_compare' => 'IN', 'meta_value' => $lead_ids ) );
            $projects = get_posts( array( 'post_type' => 'gp_project', 'meta_key' => '_related_lead', 'meta_compare' => 'IN', 'meta_value' => $lead_ids ) );
        }

        ob_start(); ?>
        <div class="gp-portal-container container">
            <div class="glass-card">
                <h2>Customer Dashboard</h2>
                <div class="portal-grid" style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                    <div class="portal-section">
                        <h3>Project Status</h3>
                        <?php if($projects): foreach($projects as $p):
                            $status = get_post_meta($p->ID, '_gp_project_status', true) ?: 'Active'; ?>
                            <div class="project-item"><?php echo $p->post_title; ?>: <strong><?php echo $status; ?></strong></div>
                        <?php endforeach; else: echo "<p>No active projects.</p>"; endif; ?>
                    </div>
                    <div class="portal-section">
                        <h3>Proposals & Documents</h3>
                        <?php foreach($proposals as $prop): ?>
                            <div class="proposal"><?php echo $prop->post_title; ?> <button onclick="acceptProposal(<?php echo $prop->ID; ?>)">View</button></div>
                        <?php endforeach; ?>
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
        do_action( 'gp_proposal_accepted', $proposal_id );
        wp_send_json_success('Accepted.');
    }
}
new GrowthPress_Portal();
