<?php
/**
 * GrowthPress Customer Portal Class - Document Enhanced
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

        $ai = GrowthPress_AI::get_instance();
        $msg = $ai->call_ai("Generate a welcome email for a new project.", "You are a customer success manager.");
        update_post_meta($proposal_id, '_kickoff_msg', $msg);
    }

    public function render_portal() {
        if ( ! is_user_logged_in() ) return '<p>Please login to access your portal.</p>';
        $email = wp_get_current_user()->user_email;
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'meta_key' => '_lead_email', 'meta_value' => $email ) );
        $lead_ids = wp_list_pluck($leads, 'ID');

        $proposals = array();
        $docs = array();
        if ( ! empty($lead_ids) ) {
            $proposals = get_posts( array( 'post_type' => 'gp_proposal', 'meta_key' => '_related_lead', 'meta_compare' => 'IN', 'meta_value' => $lead_ids ) );
            // Stubs for secure documents
            $docs = array(
                array('title' => 'Project Strategy.pdf', 'url' => '#'),
                array('title' => 'Onboarding Guide.pdf', 'url' => '#')
            );
        }

        ob_start(); ?>
        <div class="gp-portal-container container">
            <div class="glass-card">
                <h2>Welcome, <?php echo wp_get_current_user()->display_name; ?></h2>
                <div class="portal-nav" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    <a href="#appointments">Appointments</a> | <a href="#proposals">Proposals</a> | <a href="#documents">Documents</a>
                </div>

                <div id="appointments">
                    <h3>Your Appointments</h3>
                    <p>No upcoming appointments found.</p>
                </div>

                <div id="proposals" style="margin-top:30px;">
                    <h3>Active Proposals</h3>
                    <?php foreach($proposals as $prop): ?>
                        <div class="proposal"><?php echo esc_html($prop->post_title); ?> <button onclick="acceptProposal(<?php echo $prop->ID; ?>)">View</button></div>
                    <?php endforeach; ?>
                </div>

                <div id="documents" style="margin-top:30px;">
                    <h3>Secure Files</h3>
                    <?php if($docs): foreach($docs as $doc): ?>
                        <div class="doc-item">📄 <?php echo $doc['title']; ?> - <a href="<?php echo $doc['url']; ?>">Download</a></div>
                    <?php endforeach; else: echo "<p>No documents uploaded yet.</p>"; endif; ?>
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
