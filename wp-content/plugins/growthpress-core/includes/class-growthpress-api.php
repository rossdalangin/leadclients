<?php
/**
 * GrowthPress REST API Class - Automation Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_API {

    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    public function register_routes() {
        register_rest_route( 'growthpress/v1', '/leads', array(
            'methods'  => 'POST',
            'callback' => array( $this, 'create_lead' ),
            'permission_callback' => array( $this, 'check_api_permission' ),
        ) );

        // Missed Call Automation Webhook
        register_rest_route( 'growthpress/v1', '/missed-call', array(
            'methods'  => 'POST',
            'callback' => array( $this, 'handle_missed_call' ),
            'permission_callback' => '__return_true', // Twilio uses signature validation
        ) );
    }

    public function check_api_permission() {
        $auth_token = get_option('growthpress_api_token', '');
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        return ($header === "Bearer $auth_token");
    }

    public function handle_missed_call( $request ) {
        $from = $request->get_param('From');
        $niche = get_option('growthpress_niche', 'business');

        $ai = GrowthPress_AI::get_instance();
        $sms_body = $ai->generate_missed_call_reply($niche);

        GrowthPress_Activity::log( "Missed call from $from. AI response generated." );

        return new WP_REST_Response( array( 'reply' => $sms_body, 'status' => 'handled' ), 200 );
    }

    public function create_lead( $request ) {
        $params = $request->get_params();
        $lead_id = wp_insert_post( array( 'post_title' => $params['name'], 'post_type' => 'gp_lead', 'post_status' => 'publish' ) );
        if ($lead_id) {
            update_post_meta($lead_id, '_lead_email', $params['email']);
            do_action('gp_lead_captured', $lead_id);
            return new WP_REST_Response( array('id' => $lead_id), 201 );
        }
        return new WP_Error('failed', 'Error', array('status' => 500));
    }
}

new GrowthPress_API();
