<?php
/**
 * GrowthPress REST API Class
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

        register_rest_route( 'growthpress/v1', '/appointments', array(
            'methods'  => 'GET',
            'callback' => array( $this, 'get_appointments' ),
            'permission_callback' => array( $this, 'check_api_permission' ),
        ) );
    }

    public function check_api_permission() {
        // Basic Bearer Token Auth for demonstration
        $auth_token = get_option('growthpress_api_token', '');
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if ( empty($auth_token) || $header !== "Bearer $auth_token" ) {
            return new WP_Error( 'rest_forbidden', __( 'Invalid API Token.', 'growthpress-core' ), array( 'status' => 401 ) );
        }
        return true;
    }

    public function create_lead( $request ) {
        $params = $request->get_params();
        $lead_id = wp_insert_post( array(
            'post_title'   => sanitize_text_field( $params['name'] ),
            'post_content' => sanitize_textarea_field( $params['message'] ?? '' ),
            'post_type'    => 'gp_lead',
            'post_status'  => 'publish',
        ) );

        if ( $lead_id ) {
            update_post_meta( $lead_id, '_lead_email', sanitize_email( $params['email'] ) );
            return new WP_REST_Response( array( 'id' => $lead_id, 'message' => 'Lead created.' ), 201 );
        }
        return new WP_Error( 'creation_failed', 'Could not create lead.', array( 'status' => 500 ) );
    }

    public function get_appointments() {
        $appointments = get_posts( array( 'post_type' => 'gp_appointment', 'posts_per_page' => 50 ) );
        return new WP_REST_Response( $appointments, 200 );
    }
}

new GrowthPress_API();
