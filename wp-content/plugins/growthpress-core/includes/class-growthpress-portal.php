<?php
/**
 * GrowthPress Customer Portal Class - Law Enhanced
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
        $cases = array();
        if ( ! empty($lead_ids) ) {
            $proposals = get_posts( array( 'post_type' => 'gp_proposal', 'meta_key' => '_related_lead', 'meta_compare' => 'IN', 'meta_value' => $lead_ids ) );
            $cases = get_posts( array( 'post_type' => 'gp_legal_case', 'meta_key' => '_related_lead', 'meta_compare' => 'IN', 'meta_value' => $lead_ids ) );
        }

        ob_start(); ?>
        <div class="gp-portal-container container">
            <div class="glass-card">
                <h2>Customer Dashboard</h2>
                <div class="portal-nav" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    <a href="#appointments">Appointments</a> | <a href="#proposals">Proposals</a> | <a href="#cases">Legal Cases</a>
                </div>

                <div id="cases" style="margin-top:30px;">
                    <h3>Your Legal Cases</h3>
                    <?php if($cases): foreach($cases as $c):
                        $status = get_post_meta($c->ID, '_gp_case_status', true) ?: 'Under Review'; ?>
                        <div class="case-item"><?php echo esc_html($c->post_title); ?>: <strong><?php echo $status; ?></strong></div>
                    <?php endforeach; else: echo "<p>No active legal cases.</p>"; endif; ?>
                </div>

                <div id="proposals" style="margin-top:30px;">
                    <h3>Active Proposals</h3>
                    <?php foreach($proposals as $prop):
                        $prop_status = get_post_meta($prop->ID, '_gp_proposal_status', true) ?: 'Pending'; ?>
                        <div class="glass-card" style="margin-bottom:15px; border-left: 4px solid <?php echo $prop_status === 'Accepted' ? '#10B981' : '#2563EB'; ?>;">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <strong><?php echo esc_html($prop->post_title); ?></strong>
                                    <div style="font-size:11px; opacity:0.7;">Status: <?php echo $prop_status; ?></div>
                                </div>
                                <?php if($prop_status !== 'Accepted'): ?>
                                    <button class="button button-small" onclick="viewProposal(<?php echo $prop->ID; ?>)">Review & Accept</button>
                                <?php endif; ?>
                            </div>
                            <div id="prop-body-<?php echo $prop->ID; ?>" style="display:none; margin-top:20px; font-size:13px; border-top:1px solid #eee; padding-top:15px;">
                                <?php echo apply_filters('the_content', $prop->post_content); ?>
                                <hr>
                                <button class="button" onclick="acceptProposal(<?php echo $prop->ID; ?>)">Confirm & Accept Proposal</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div id="appointments" style="margin-top:30px;">
                    <h3>Upcoming Appointments</h3>
                    <?php
                    $appts = get_posts( array( 'post_type' => 'gp_appointment', 'meta_key' => '_lead_email', 'meta_value' => $email ) );
                    if($appts): foreach($appts as $a):
                        $date = get_post_meta($a->ID, '_appointment_date', true);
                        $link = get_post_meta($a->ID, '_gp_telemedicine_link', true); ?>
                        <div class="glass-card" style="margin-bottom:10px;">
                            <strong><?php echo esc_html($a->post_title); ?></strong><br>
                            <small>Scheduled: <?php echo $date; ?></small>
                            <?php if($link): ?>
                                <br><a href="<?php echo esc_url($link); ?>" class="button button-small" style="margin-top:10px;">Join Meeting</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; else: echo "<p>No upcoming appointments.</p>"; endif; ?>
                </div>
            </div>
        </div>
        <script>
            function viewProposal(id) {
                jQuery('#prop-body-' + id).slideToggle();
            }
            function acceptProposal(id) {
                if(!confirm("By accepting this proposal, you agree to the terms and conditions. Continue?")) return;
                jQuery.post(gp_ajax.ajaxurl, { action: 'gp_accept_proposal', proposal_id: id }, function(res) {
                    if(res.success) {
                        alert("Proposal Accepted! We have created a project kickoff task.");
                        location.reload();
                    }
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
