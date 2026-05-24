<?php
/**
 * GrowthPress AI Core Class - Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_AI {

    private static $instance = null;
    private $api_key;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->api_key = get_option('growthpress_openai_api_key', '');
    }

    public function call_ai( $prompt, $context = '' ) {
        if ( empty( $this->api_key ) ) {
            return new WP_Error( 'missing_api_key', __( 'OpenAI API key is missing.', 'growthpress-core' ) );
        }

        $response = wp_remote_post( 'https://api.openai.com/v1/chat/completions', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type'  => 'application/json',
            ),
            'body'    => json_encode( array(
                'model'    => 'gpt-4',
                'messages' => array(
                    array( 'role' => 'system', 'content' => $context ),
                    array( 'role' => 'user', 'content' => $prompt ),
                ),
            ) ),
            'timeout' => 30,
        ) );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        return $body['choices'][0]['message']['content'] ?? '';
    }

    public function analyze_sentiment( $message ) {
        $prompt = "Analyze the sentiment and urgency of this lead message: \"$message\". Return a JSON object with 'sentiment' (positive, neutral, negative) and 'urgency' (1-10).";
        return $this->call_ai( $prompt, "You are a lead qualification assistant." );
    }

    /**
     * Missed Call Automation Prompt
     */
    public function generate_missed_call_reply( $niche ) {
        $context = "You are a specialized receptionist for a $niche business.";
        $prompt = "A potential client just called and we missed it. Generate a friendly, professional SMS offering to book a discovery call or appointment immediately.";
        return $this->call_ai( $prompt, $context );
    }

    /**
     * Re-engagement Campaign Prompt
     */
    public function generate_reactivation_email( $lead_name, $niche ) {
        $context = "You are a marketing strategist for a $niche.";
        $prompt = "Write a short, engaging re-activation email for $lead_name who inquired 30 days ago but hasn't booked yet. Offer a small incentive or helpful advice.";
        return $this->call_ai( $prompt, $context );
    }
}

GrowthPress_AI::get_instance();
