<?php
/**
 * GrowthPress Booking Engine Class - Frontend Enhanced
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
        ob_start(); ?>
        <div class="gp-booking-widget glass-card">
            <h3>Book an Appointment</h3>
            <form id="gp-booking-form">
                <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
                <div class="form-group">
                    <label>Select Service</label>
                    <select name="service">
                        <option value="consultation">Initial Consultation</option>
                        <option value="followup">Follow-up Session</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" required>
                </div>
                <div class="form-group">
                    <label>Time</label>
                    <input type="time" name="time" required>
                </div>
                <div class="form-group">
                    <label>Your Name</label>
                    <input type="text" name="client_name" required>
                </div>
                <button type="submit">Confirm Booking</button>
                <div class="form-feedback"></div>
            </form>
        </div>
        <script>
        jQuery('#gp-booking-form').on('submit', function(e) {
            e.preventDefault();
            var $form = jQuery(this);
            var $feedback = $form.find('.form-feedback');
            $feedback.text('Processing...');
            jQuery.post(gp_ajax.ajaxurl, {
                action: 'gp_submit_booking',
                formData: $form.serialize()
            }, function(res) {
                if(res.success) {
                    $feedback.text('Appointment booked successfully!');
                    $form[0].reset();
                } else {
                    $feedback.text('Error: ' + res.data);
                }
            });
        });
        </script>
        <?php
        return ob_get_clean();
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
            do_action( 'gp_appointment_created', $appointment_id );
        }
        return $appointment_id;
    }
}

GrowthPress_Booking::get_instance();
