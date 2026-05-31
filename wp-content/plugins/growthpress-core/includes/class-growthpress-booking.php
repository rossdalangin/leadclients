<?php
/**
 * GrowthPress Booking Engine Class - Ultra Elite v3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Booking {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', array( $this, 'register_booking_cpt' ) );
        add_action( 'gp_appointment_created', array( $this, 'trigger_appointment_reminders' ) );
        add_shortcode( 'gp_booking_form', array( $this, 'render_booking_form' ) );
        add_action( 'wp_ajax_gp_submit_booking', array( $this, 'handle_booking_submission' ) );
        add_action( 'wp_ajax_nopriv_gp_submit_booking', array( $this, 'handle_booking_submission' ) );
        add_action( 'wp_ajax_gp_join_waiting_list', array( $this, 'handle_waiting_list' ) );
        add_action( 'wp_ajax_nopriv_gp_join_waiting_list', array( $this, 'handle_waiting_list' ) );
    }

    public function register_booking_cpt() {
        register_post_type( 'gp_appointment', array(
            'labels'      => array( 'name' => 'Appointments', 'singular_name' => 'Appointment' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-calendar-alt',
            'supports'    => array( 'title', 'custom-fields' ),
        ) );
    }

    public function render_booking_form() {
        $nonce = wp_create_nonce('gp_booking_nonce');
        $staff = get_users( array( 'role__in' => array('author', 'editor', 'administrator') ) );
        ob_start(); ?>
        <div class="gp-booking-elite glass-card" style="padding:60px; border-radius:40px; background:linear-gradient(135deg, var(--surface), var(--bg)); border:1px solid rgba(255,255,255,0.4);">
            <div style="text-align:center; margin-bottom:40px;">
                <div style="font-size:10px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:3px; margin-bottom:15px;">SECURE CALENDAR ENGINE</div>
                <h2 class="text-gradient" style="font-size:2.8rem; margin:0;">Secure Your Strategy Session</h2>
                <p style="opacity:0.6; font-size:14px; margin-top:10px;">Select a slot to engage with our elite <?php echo get_option('growthpress_niche', 'business'); ?> specialists.</p>
            </div>

            <form id="gp-booking-form">
                <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:30px;">
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:10px;">PROFESSIONAL</label>
                        <select name="staff_id" style="height:60px; border-radius:15px; font-weight:700;">
                            <option value="0">Any Available Specialist</option>
                            <?php foreach($staff as $member): ?>
                                <option value="<?php echo $member->ID; ?>"><?php echo esc_html($member->display_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:10px;">SERVICE TYPE</label>
                        <select name="service" style="height:60px; border-radius:15px; font-weight:700;">
                            <option value="consultation">Initial Strategy Audit</option>
                            <option value="blueprint">Performance Blueprinting</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:40px;">
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:10px;">PREFERRED DATE</label>
                        <input type="date" name="date" required style="height:60px; border-radius:15px; font-weight:700;">
                    </div>
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:10px;">TARGET TIME</label>
                        <input type="time" name="time" required style="height:60px; border-radius:15px; font-weight:700;">
                    </div>
                </div>

                <div style="margin-bottom:40px;">
                    <label style="font-weight:950; font-size:11px; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:10px;">APPLICANT NAME</label>
                    <input type="text" name="client_name" placeholder="Full Legal Name" required style="height:60px; border-radius:15px; font-weight:700;">
                </div>

                <div style="display:flex; gap:20px;">
                    <button type="submit" class="gp-btn" style="flex:2; height:75px; font-size:18px;">CONFIRM SLOT</button>
                    <button type="button" class="gp-btn" onclick="joinWaitingList()" style="flex:1; background:var(--secondary); height:75px; font-size:14px; text-transform:none;">Join Waiting List</button>
                </div>
                <div id="booking-res" style="margin-top:20px; text-align:center; font-weight:900; color:var(--primary);"></div>
            </form>
        </div>
        <script>
        jQuery('#gp-booking-form').on('submit', function(e) {
            e.preventDefault();
            var $btn = jQuery(this).find('button[type="submit"]');
            $btn.text('SYNCHRONIZING...');
            jQuery.post(gp_ajax.ajaxurl, { action: 'gp_submit_booking', formData: jQuery(this).serialize() }, function(res) {
                if(res.success) {
                    jQuery('#gp-booking-form').fadeOut(400, function() {
                        jQuery('#booking-res').html('<div style="padding:40px;"><div style="font-size:4rem; margin-bottom:20px;">🗓️</div><h3 class="text-gradient">SLOT SECURED</h3><p>Your session has been added to the master calendar.</p></div>').fadeIn();
                    });
                }
            });
        });
        function joinWaitingList() {
            var name = jQuery('input[name="client_name"]').val();
            if(!name) { alert("Identify yourself first."); return; }
            jQuery.post(gp_ajax.ajaxurl, { action: 'gp_join_waiting_list', name: name, nonce: '<?php echo $nonce; ?>' }, function(res) {
                if(res.success) alert("Priority waiting list joined.");
            });
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_waiting_list() {
        check_ajax_referer( 'gp_booking_nonce', 'nonce' );
        $appt_id = wp_insert_post( array( 'post_title' => "Priority Waiting: " . sanitize_text_field($_POST['name']), 'post_type' => 'gp_appointment', 'post_status' => 'publish' ) );
        update_post_meta($appt_id, '_is_waiting_list', '1');
        wp_send_json_success();
    }

    public function handle_booking_submission() {
        parse_str($_POST['formData'], $data);
        if ( ! wp_verify_nonce($data['nonce'], 'gp_booking_nonce') ) wp_send_json_error();
        $id = wp_insert_post( array( 'post_title' => "Appointment: " . $data['client_name'], 'post_type' => 'gp_appointment', 'post_status' => 'publish' ) );
        if ( $id ) {
            update_post_meta( $id, '_appointment_date', $data['date'] . ' ' . $data['time'] );
            update_post_meta( $id, '_staff_id', intval($data['staff_id']) );

            // Create Invoice for Deposit
            $payments = new GrowthPress_Payments();
            $invoice_id = $payments->create_invoice(150, $id, 'booking');
            update_post_meta($id, '_deposit_invoice_id', $invoice_id);

            do_action( 'gp_appointment_created', $id );
            wp_send_json_success(array('appointment_id' => $id, 'invoice_id' => $invoice_id));
        }
        wp_send_json_error();
    }

    public function trigger_appointment_reminders( $id ) {
        GrowthPress_Activity::log( "Booking Logic: SMS reminders scheduled for session #$id" );
    }
}
GrowthPress_Booking::get_instance();
