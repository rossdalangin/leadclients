<?php
/**
 * GrowthPress Payments Engine
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Payments {

    public function __construct() {
        add_action( 'init', array( $this, 'register_payment_cpt' ) );
        add_action( 'wp_ajax_gp_process_deposit', array( $this, 'handle_deposit' ) );
    }

    public function register_payment_cpt() {
        register_post_type( 'gp_transaction', array(
            'labels'      => array( 'name' => 'Transactions', 'singular_name' => 'Transaction' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-money-alt',
            'supports'    => array( 'title', 'custom-fields' ),
        ) );
    }

    public function create_invoice( $amount, $related_id, $type = 'booking' ) {
        $transaction_id = wp_insert_post( array(
            'post_title'  => sprintf( 'Invoice for %s #%d', ucfirst($type), $related_id ),
            'post_type'   => 'gp_transaction',
            'post_status' => 'publish',
        ) );
        update_post_meta( $transaction_id, '_amount', $amount );
        update_post_meta( $transaction_id, '_status', 'Pending' );
        update_post_meta( $transaction_id, '_related_id', $related_id );
        return $transaction_id;
    }

    public function handle_deposit() {
        // Mock Stripe/PayPal integration logic
        $transaction_id = intval($_POST['transaction_id']);
        update_post_meta( $transaction_id, '_status', 'Paid' );
        GrowthPress_Activity::log( "Payment received for Transaction #$transaction_id" );
        wp_send_json_success('Payment confirmed.');
    }
}

new GrowthPress_Payments();
