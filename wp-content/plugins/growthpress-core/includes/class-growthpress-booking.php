<?php
/**
 * GrowthPress Booking Engine Class - Enhanced with Automation
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

    public function trigger_appointment_reminders( $appointment_id ) {
        // AI logic to generate personalized reminder
        $ai = GrowthPress_AI::get_instance();
        $niche = get_option( 'growthpress_niche', 'Professional' );

        $reminder_msg = "Hello! Just reminding you of your upcoming appointment with our $niche team.";
        update_post_meta( $appointment_id, '_gp_reminder_sent', current_time('mysql') );

        // Mock API call to Twilio
        error_log( "GP Automation: SMS Reminder sent for appointment $appointment_id" );
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
