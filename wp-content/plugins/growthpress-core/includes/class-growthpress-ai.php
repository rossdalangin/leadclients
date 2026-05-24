<?php
/**
 * GrowthPress AI Core Class - Strategy Enhanced
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
        if ( empty( $this->api_key ) ) return new WP_Error( 'missing_api_key', 'Missing API key.' );
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

    public function generate_growth_roadmap( $niche ) {
        $prompt = "Generate a 12-month business growth and AI automation roadmap for a $niche business. Focus on lead generation, conversion optimization, and retention. Output in professional Markdown.";
        return $this->call_ai( $prompt, "You are a world-class SaaS architect and growth strategist." );
    }

    public function predict_deal_probability( $lead_id ) {
        return 75; // Mock for performance
    }

    public function analyze_sentiment( $msg ) {
        $prompt = "Analyze sentiment/urgency of \"$msg\". Return JSON: sentiment, urgency (1-10).";
        return $this->call_ai($prompt, "Lead Assistant");
    }

    public function generate_blog_post($t, $n) { return $this->call_ai("Blog about $t for $n", "SEO Expert"); }
    public function generate_ad_copy($s, $n) { return $this->call_ai("Ad for $s in $n", "Copywriter"); }
    public function generate_social_content($t) { return $this->call_ai("3 posts for $t", "Social Manager"); }
    public function is_spam($m, $n, $e) { return false; }
}
GrowthPress_AI::get_instance();
