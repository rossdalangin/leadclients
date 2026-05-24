<?php
/**
 * GrowthPress AI Core Class - Filter Enhanced
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
            return new WP_Error( 'missing_api_key', 'OpenAI API key is missing.' );
        }
        $response = wp_remote_post( 'https://api.openai.com/v1/chat/completions', array(
            'headers' => array( 'Authorization' => 'Bearer ' . $this->api_key, 'Content-Type'  => 'application/json' ),
            'body'    => json_encode( array(
                'model'    => 'gpt-4',
                'messages' => array( array( 'role' => 'system', 'content' => $context ), array( 'role' => 'user', 'content' => $prompt ) ),
            ) ),
            'timeout' => 30,
        ) );
        if ( is_wp_error( $response ) ) return $response;
        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        return $body['choices'][0]['message']['content'] ?? '';
    }

    /**
     * AI Spam Filtering
     */
    public function is_spam( $message, $name, $email ) {
        $prompt = "Classify this lead submission as 'spam' or 'valid'. Name: $name, Email: $email, Message: \"$message\". Return only the word 'spam' or 'valid'.";
        $result = $this->call_ai( $prompt, "You are a specialized security assistant." );
        return trim(strtolower($result)) === 'spam';
    }

    public function suggest_closing_tactics( $lead_id ) {
        $lead = get_post($lead_id);
        $history = get_post_meta($lead_id, '_behavior_history', true);
        $prompt = "Analyze this lead (Name: {$lead->post_title}, History: $history). Suggest 3 closing tactics.";
        return $this->call_ai($prompt, "You are a sales psychologist.");
    }

    public function generate_proposal( $client_name, $service, $niche ) {
        $prompt = "Create a professional business proposal for $client_name regarding our $service services.";
        return $this->call_ai( $prompt, "You are a senior business development consultant." );
    }

    public function generate_blog_post( $topic, $niche ) {
        $prompt = "Write a high-quality, SEO-optimized blog post about \"$topic\" for a $niche business.";
        return $this->call_ai( $prompt, "You are an elite content strategist." );
    }

    public function generate_social_content( $topic, $platform = 'Instagram' ) {
        $prompt = "Create 3 engaging $platform posts about \"$topic\".";
        return $this->call_ai( $prompt, "You are a creative social media manager." );
    }

    public function generate_ad_copy( $service, $niche ) {
        $prompt = "Generate high-converting Facebook and Google ad copy for a $service offered by a $niche business.";
        return $this->call_ai( $prompt, "You are a world-class copywriter." );
    }

    public function analyze_sentiment( $message ) {
        $prompt = "Analyze the sentiment and urgency of: \"$message\". Return JSON: sentiment, urgency (1-10).";
        return $this->call_ai( $prompt, "You are a lead qualification assistant." );
    }

    public function generate_followup( $lead_data, $previous_interaction = '' ) {
        $prompt = "Generate a professional follow-up message for: " . json_encode($lead_data);
        return $this->call_ai( $prompt, "You are a sales consultant." );
    }

    public function generate_missed_call_reply( $niche ) {
        $prompt = "A potential client called and we missed it. Generate a friendly SMS to book a call.";
        return $this->call_ai( $prompt, "You are a receptionist for a $niche." );
    }

    public function generate_reactivation_email( $lead_name, $niche ) {
        $prompt = "Write a re-activation email for $lead_name (inquired 30 days ago).";
        return $this->call_ai( $prompt, "You are a marketing strategist." );
    }
}
GrowthPress_AI::get_instance();
