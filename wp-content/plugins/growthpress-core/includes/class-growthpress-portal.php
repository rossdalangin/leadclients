<?php
/**
 * GrowthPress Customer Portal Class - v4.0 SaaS-Pro Standards
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Portal {

    public function __construct() {
        add_shortcode( 'gp_client_portal', array( $this, 'render_portal' ) );
        add_action( 'wp_ajax_gp_accept_proposal', array( $this, 'handle_proposal_acceptance' ) );
        add_action( 'wp_ajax_gp_request_reschedule', array( $this, 'handle_reschedule_request' ) );
    }

    public function handle_reschedule_request() {
        update_post_meta(intval($_POST['appointment_id']), '_reschedule_requested', '1');
        wp_send_json_success();
    }

    public function render_portal() {
        if ( ! is_user_logged_in() ) return '<div class="glass-card" style="text-align:center; padding:120px 60px; border-radius:50px;"><div style="font-size:5rem; margin-bottom:30px;">🔐</div><h2 class="text-gradient" style="font-size:3.5rem;">Secure Node Authentication</h2><p style="opacity:0.7; font-size:1.2rem; margin-bottom:50px;">Identify yourself to access proprietary strategic metrics and legal/financial documents.</p><a href="'.wp_login_url(get_permalink()).'" class="gp-btn" style="height:80px; padding:0 80px; font-size:20px;">AUTHENTICATE SESSION</a></div>';

        $user = wp_get_current_user();
        $email = $user->user_email;
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'meta_key' => '_lead_email', 'meta_value' => $email ) );
        $lead_ids = wp_list_pluck($leads, 'ID');
        $proposals = ! empty($lead_ids) ? get_posts( array( 'post_type' => 'gp_proposal', 'meta_key' => '_related_lead', 'meta_compare' => 'IN', 'meta_value' => $lead_ids ) ) : array();

        ob_start(); ?>
        <div class="gp-portal-v4 container" style="padding:120px 0;">
            <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:80px;">
                <div>
                    <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">AUTHENTICATED COMMAND CENTER</div>
                    <h1 class="text-gradient" style="margin:0; font-size:4.5rem; letter-spacing:-0.06em;">Command: <?php echo esc_html($user->display_name); ?></h1>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:10px; font-weight:950; opacity:0.3; letter-spacing:2px; margin-bottom:10px;">ENCRYPTION: AES-256-GCM</div>
                    <a href="<?php echo wp_logout_url(home_url()); ?>" class="gp-btn" style="background:var(--secondary); padding:12px 30px; font-size:11px; border-radius:15px; text-transform:none;">TERMINATE SESSION</a>
                </div>
            </div>

            <div class="gp-portal-grid" style="display:grid; grid-template-columns: 2.5fr 1fr; gap:60px;">
                <div class="portal-main">
                    <!-- Project Velocity Tracker -->
                    <div class="glass-card" style="border-left: 15px solid var(--primary); margin-bottom:60px; padding:70px; border-radius:44px;">
                        <h3 class="text-gradient" style="font-size:2.2rem; margin-bottom:40px;">Operational Velocity</h3>
                        <div style="background:rgba(0,0,0,0.02); height:12px; border-radius:10px; overflow:hidden; margin-bottom:15px;">
                            <div style="width:64%; height:100%; background:var(--primary); box-shadow:0 0 20px var(--primary-glow);"></div>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:11px; font-weight:950; opacity:0.5; letter-spacing:1px;">
                            <span>INTAKE: COMPLETE</span>
                            <span>TRIAGE: ACTIVE</span>
                            <span>EXECUTION: 64%</span>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:30px; margin-top:50px;">
                            <div style="background:#F8FAFC; padding:35px; border-radius:35px; border:1px solid #E2E8F0; text-align:center;">
                                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:12px;">DOCUMENTS</div>
                                <div style="font-size:42px; font-weight:950;"><?php echo count($proposals); ?></div>
                            </div>
                            <div style="background:#F8FAFC; padding:35px; border-radius:35px; border:1px solid #E2E8F0; text-align:center;">
                                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:12px;">ACTIVE NODES</div>
                                <div style="font-size:42px; font-weight:950; color:var(--accent);">03</div>
                            </div>
                            <div style="background:#F8FAFC; padding:35px; border-radius:35px; border:1px solid #E2E8F0; text-align:center;">
                                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:12px;">URGENCY</div>
                                <div style="font-size:42px; font-weight:950; color:#EF4444;">HI</div>
                            </div>
                        </div>
                    </div>

                    <div id="proposals">
                        <h2 style="font-size:32px; margin-bottom:40px; letter-spacing:-0.05em; font-weight:950;">Strategic Terminal: Agreements</h2>
                        <?php if($proposals): foreach($proposals as $prop):
                            $status = get_post_meta($prop->ID, '_gp_proposal_status', true) ?: 'Pending Execution'; ?>
                            <div class="glass-card" style="margin-bottom:30px; border-radius:40px; border: 1px solid rgba(0,0,0,0.05); border-left: 12px solid <?php echo $status === 'Accepted' ? '#10B981' : 'var(--primary)'; ?>;">
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <div>
                                        <h4 style="margin:0; font-size:24px; font-weight:900;"><?php echo esc_html($prop->post_title); ?></h4>
                                        <div style="font-size:12px; font-weight:950; color:<?php echo $status === 'Accepted' ? '#10B981' : 'var(--primary)'; ?>; margin-top:10px; letter-spacing:2px;">STATUS: <?php echo strtoupper($status); ?></div>
                                    </div>
                                    <button class="gp-btn" style="padding:15px 45px; font-size:13px; border-radius:18px;" onclick="jQuery('#p-<?php echo $prop->ID; ?>').slideToggle()">OPEN TERMINAL</button>
                                </div>
                                <div id="p-<?php echo $prop->ID; ?>" style="display:none; margin-top:50px; padding-top:50px; border-top:2px solid #F1F5F9;">
                                    <div class="entry-content" style="font-size:17px; line-height:1.9; color:var(--text); font-family:'Inter', sans-serif;"><?php echo apply_filters('the_content', $prop->post_content); ?></div>
                                    <?php if($status !== 'Accepted'): ?>
                                        <div style="margin-top:60px; text-align:center; background:#F8FAFC; padding:60px; border-radius:35px; border:1px solid #E2E8F0;">
                                            <h3 style="margin-bottom:20px;">Execute Strategic Agreement</h3>
                                            <p style="opacity:0.6; margin-bottom:40px;">By clicking below, you authenticate and digitally sign this binding strategic proposal.</p>
                                            <button class="gp-btn" style="width:100%; height:85px; font-size:20px;" onclick="acceptProposal(<?php echo $prop->ID; ?>)">EXECUTE & INITIATE KICKOFF</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; else: echo "<div class='glass-card' style='opacity:0.5; text-align:center; padding:80px; border-radius:40px;'>No pending strategic documents detected in current secure node.</div>"; endif; ?>
                    </div>
                </div>

                <div class="portal-side">
                    <!-- Secure Messenger Mockup -->
                    <div class="glass-card" style="background:var(--secondary); color:white; border:none; margin-bottom:40px; padding:50px; border-radius:44px;">
                        <h3 style="color:white; font-size:24px; margin-bottom:20px;">Strategy Uplink</h3>
                        <p style="font-size:16px; opacity:0.7; line-height:1.7;">Direct priority connection to your dedicated operational specialists.</p>
                        <div style="display:grid; gap:20px; margin-top:40px;">
                            <a href="<?php echo home_url('/book-now'); ?>" class="gp-btn" style="width:100%; border-radius:20px; background:var(--primary); text-transform:none; height:70px; font-size:16px;">Book Briefing</a>
                            <button class="gp-btn" style="width:100%; background:rgba(255,255,255,0.06); border-radius:20px; text-transform:none; height:70px; border:1px solid rgba(255,255,255,0.1); font-size:16px;">Secure Message</button>
                        </div>
                    </div>

                    <div class="glass-card" style="padding:50px; border-radius:44px;">
                        <h3 style="font-size:22px; margin-bottom:30px; letter-spacing:-0.03em;">Intelligence Logs</h3>
                        <?php $appts = get_posts( array( 'post_type' => 'gp_appointment', 'meta_key' => '_lead_email', 'meta_value' => $email ) );
                        if($appts): foreach($appts as $a):
                            $date = get_post_meta($a->ID, '_appointment_date', true); ?>
                            <div style="padding:25px 0; border-bottom:1px solid #F1F5F9;">
                                <div style="font-size:16px; font-weight:900; color:var(--secondary);"><?php echo esc_html($a->post_title); ?></div>
                                <div style="font-size:11px; font-weight:800; opacity:0.5; margin-top:10px; text-transform:uppercase; letter-spacing:1px;">TIMESTAMP: <?php echo $date; ?></div>
                            </div>
                        <?php endforeach; else: echo "<p style='font-size:14px; opacity:0.5;'>No previous session data synchronized.</p>"; endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function acceptProposal(id) {
                if(!confirm("Execute agreement and engagement of proprietary services?")) return;
                jQuery.post(gp_ajax.ajaxurl, { action: 'gp_accept_proposal', proposal_id: id }, function(res) {
                    if(res.success) {
                        alert("AGREEMENT DIGITALLY EXECUTED. KICKOFF PROTOCOL ENGAGED.");
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
        GrowthPress_Activity::log( "Proprietary agreement executed for Doc #$proposal_id." );
        wp_send_json_success();
    }
}
new GrowthPress_Portal();
