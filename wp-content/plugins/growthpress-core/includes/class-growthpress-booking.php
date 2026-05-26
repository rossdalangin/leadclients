<?php
/**
 * GrowthPress Booking Engine Class - Waiting List Enhanced
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
        add_action( 'wp_ajax_gp_cancel_appointment', array( $this, 'handle_cancellation' ) );
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
        <div class="gp-booking-widget glass-card">
            <h3>Book an Appointment</h3>
            <form id="gp-booking-form">
                <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
                <div class="form-group">
                    <label>Preferred Professional</label>
                    <select name="staff_id">
                        <option value="0">Any Available</option>
                        <?php foreach($staff as $member): ?>
                            <option value="<?php echo $member->ID; ?>"><?php echo esc_html($member->display_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Select Service</label>
                    <select name="service">
                        <option value="consultation">Initial Consultation</option>
                        <option value="followup">Follow-up Session</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date & Time</label>
                    <input type="date" name="date" required>
                    <input type="time" name="time" required>
                </div>
                <div class="form-group">
                    <label>Your Name</label>
                    <input type="text" name="client_name" required>
                </div>
                <div class="booking-options" style="display:flex; gap:10px;">
                    <button type="submit" class="button-primary" style="flex:1;">Confirm Booking</button>
                    <button type="button" class="button" onclick="joinWaitingList()" style="flex:1; background:#1E293B;">Join Waiting List</button>
                </div>
                <div class="form-feedback" style="margin-top:15px; font-weight:bold; color: #2563EB;"></div>
            </form>
        </div>
        <script>
        jQuery('#gp-booking-form').on('submit', function(e) {
            e.preventDefault();
            var $form = jQuery(this);
            jQuery.post(gp_ajax.ajaxurl, {
                action: 'gp_submit_booking',
                formData: $form.serialize()
            }, function(res) {
                if(res.success) {
                    $form.find('.form-feedback').text('Appointment booked successfully!');
                    $form[0].reset();
                }
            });
        });
        function joinWaitingList() {
            var name = jQuery('input[name="client_name"]').val();
            if(!name) { alert("Please enter your name first."); return; }
            jQuery.post(gp_ajax.ajaxurl, {
                action: 'gp_join_waiting_list',
                name: name,
                nonce: '<?php echo $nonce; ?>'
            }, function(res) {
                if(res.success) jQuery('.form-feedback').text('You have been added to the priority waiting list.');
            });
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_waiting_list() {
        check_ajax_referer( 'gp_booking_nonce', 'nonce' );
        $name = sanitize_text_field($_POST['name']);

        $appt_id = wp_insert_post( array(
            'post_title'  => "Waiting List: " . $name,
            'post_type'   => 'gp_appointment',
            'post_status' => 'publish',
        ) );
        update_post_meta($appt_id, '_is_waiting_list', '1');
        GrowthPress_Activity::log( "User $name joined the appointment waiting list." );
        wp_send_json_success();
    }

    public function handle_cancellation() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $id = intval($_POST['appointment_id']);
        wp_update_post( array( 'ID' => $id, 'post_status' => 'trash' ) );
        GrowthPress_Activity::log( "Appointment #$id cancelled by user/admin." );
        wp_send_json_success();
    }

    public function handle_booking_submission() {
        parse_str($_POST['formData'], $data);
        if ( ! wp_verify_nonce($data['nonce'], 'gp_booking_nonce') ) {
            wp_send_json_error('Security check failed.');
        }

        $appointment_id = $this->create_appointment( array(
            'service'     => $data['service'],
            'client_name' => $data['client_name'],
            'date'        => $data['date'] . ' ' . $data['time'],
            'staff_id'    => intval($data['staff_id']),
        ) );

        if ( $appointment_id ) {
            wp_send_json_success('Appointment created.');
        }
        wp_send_json_error('Failed to create appointment.');
    }

    public function trigger_appointment_reminders( $appointment_id ) {
        error_log( "GP Automation: SMS Reminder scheduled for appointment $appointment_id" );
    }

    public function create_appointment( $data ) {
        $appointment_id = wp_insert_post( array(
            'post_title'  => sprintf( 'Appointment: %s with %s', $data['service'], $data['client_name'] ),
            'post_type'   => 'gp_appointment',
            'post_status' => 'publish',
        ) );

        if ( $appointment_id ) {
            update_post_meta( $appointment_id, '_appointment_date', $data['date'] );
            update_post_meta( $appointment_id, '_staff_id', $data['staff_id'] );
            do_action( 'gp_appointment_created', $appointment_id );
        }
        return $appointment_id;
    }
}

GrowthPress_Booking::get_instance();
