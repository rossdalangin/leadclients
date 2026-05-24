<?php
/**
 * GrowthPress AI Core Class
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

    /**
     * Call OpenAI API
     */
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

    /**
     * Perform Sentiment Analysis on Lead Message
     */
    public function analyze_sentiment( $message ) {
        $prompt = "Analyze the sentiment and urgency of this lead message: \"$message\". Return a JSON object with 'sentiment' (positive, neutral, negative) and 'urgency' (1-10).";
        return $this->call_ai( $prompt, "You are a lead qualification assistant." );
    }

    /**
     * Generate Follow-up Response
     */
    public function generate_followup( $lead_data, $previous_interaction = '' ) {
        $prompt = "Generate a professional follow-up message for a lead with these details: " . json_encode($lead_data);
        return $this->call_ai( $prompt, "You are a high-ticket sales consultant." );
    }
}

// Initialize
GrowthPress_AI::get_instance();
