<?php
/**
 * GrowthPress Booking Engine Class
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
    }

    /**
     * Register Appointment CPT
     */
    public function register_booking_cpt() {
        register_post_type( 'gp_appointment', array(
            'labels'      => array( 'name' => 'Appointments', 'singular_name' => 'Appointment' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-calendar-alt',
            'supports'    => array( 'title', 'custom-fields' ),
        ) );
    }

    /**
     * Create Appointment
     */
    public function create_appointment( $data ) {
        $appointment_id = wp_insert_post( array(
            'post_title'  => sprintf( 'Appointment: %s with %s', $data['service'], $data['client_name'] ),
            'post_type'   => 'gp_appointment',
            'post_status' => 'publish',
        ) );

        if ( $appointment_id ) {
            update_post_meta( $appointment_id, '_appointment_date', $data['date'] );
            update_post_meta( $appointment_id, '_client_email', $data['email'] );
            update_post_meta( $appointment_id, '_staff_id', $data['staff_id'] );

            // Trigger automation
            do_action( 'gp_appointment_created', $appointment_id );
        }

        return $appointment_id;
    }
}

// Initialize
GrowthPress_Booking::get_instance();
