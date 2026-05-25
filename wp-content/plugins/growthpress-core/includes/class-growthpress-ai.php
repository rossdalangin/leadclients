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
        if ( empty( $this->api_key ) ) return new WP_Error( 'missing_api_key', 'Missing API key. Please configure in GrowthPress Settings.' );

        $response = wp_remote_post( 'https://api.openai.com/v1/chat/completions', array(
            'headers' => array( 'Authorization' => 'Bearer ' . $this->api_key, 'Content-Type'  => 'application/json' ),
            'body'    => json_encode( array(
                'model'    => 'gpt-4',
                'messages' => array( array( 'role' => 'system', 'content' => $context ), array( 'role' => 'user', 'content' => $prompt ) ),
            ) ),
            'timeout' => 30,
        ) );

        if ( is_wp_error( $response ) ) return $response;

        $code = wp_remote_retrieve_response_code($response);
        if ( $code !== 200 ) {
            return new WP_Error( 'ai_api_error', 'AI Service returned error code: ' . $code );
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        return $body['choices'][0]['message']['content'] ?? new WP_Error('empty_response', 'Empty response from AI.');
    }

    public function generate_growth_roadmap( $niche ) {
        $prompt = "Generate a 12-month business growth and AI automation roadmap for a $niche business. Focus on lead generation, conversion optimization, and retention. Output in professional Markdown.";
        return $this->call_ai( $prompt, "You are a world-class SaaS architect and growth strategist." );
    }

    public function predict_deal_probability( $lead_id ) {
        $lead = get_post($lead_id);
        if (!$lead) return 50;
        $content = $lead->post_content;
        $prompt = "Based on this lead inquiry: \"$content\", predict the probability of closing this deal as a percentage (0-100). Return ONLY the number.";
        $res = $this->call_ai($prompt, "Sales Predictor");
        return is_numeric(trim($res)) ? intval(trim($res)) : 75;
    }

    public function analyze_sentiment( $msg ) {
        $prompt = "Analyze sentiment/urgency of \"$msg\". Return JSON: sentiment, urgency (1-10).";
        return $this->call_ai($prompt, "Lead Assistant");
    }

    public function generate_blog_post($t, $n) { return $this->call_ai("Write a 1000-word SEO-optimized blog post about \"$t\" specifically for a $n. Include H2s, H3s, and a conversion-focused conclusion.", "SEO Content Expert"); }
    public function generate_ad_copy($s, $n) { return $this->call_ai("Create 3 variations of high-converting direct-response ad copy (Facebook/Google) for \"$s\" in the $n niche.", "Direct-Response Copywriter"); }
    public function generate_social_content($t) { return $this->call_ai("Generate a week of social media content (5 posts) about \"$t\". Include hooks and call-to-actions.", "Social Media Strategist"); }
    public function generate_email_campaign($topic, $niche) { return $this->call_ai("Generate a 5-day high-ticket email nurture sequence for \"$topic\" in the $niche niche. Focus on building authority and booking a call.", "Email Marketing Specialist"); }
    public function generate_market_insights($topic, $niche) { return $this->call_ai("Analyze the market for \"$topic\" in the $niche industry. Identify competitor weaknesses and provide a 'Market Angle of Attack'.", "Market Strategist"); }
    public function generate_proposal($client, $service, $niche) { return $this->call_ai("Generate a high-ticket $service proposal for $client in the $niche niche. Focus on ROI and transformation.", "Sales Closer"); }
    public function generate_missed_call_reply($niche) { return "Hi, this is the AI Assistant for our $niche practice. We missed your call, but we are ready to help. What can we assist you with today?"; }
    public function generate_niche_funnel($niche) { return $this->call_ai("Generate a 5-step sales funnel strategy for a $niche business.", "Funnel Architect"); }

    public function get_coaching_advice($challenge) {
        return $this->call_ai("Provide high-performance coaching advice for this challenge: \"$challenge\". Focus on mindset and actionable scaling tactics.", "Executive Coach");
    }

    public function get_legal_triage($inquiry) {
        return $this->call_ai("Analyze this legal inquiry: \"$inquiry\". Identify the potential legal area (e.g. Tort, Contract, Family) and urgency. Disclaimer: Not legal advice.", "Legal Intake Specialist");
    }

    public function get_dental_faq($question) {
        return $this->call_ai("Answer this dental question: \"$question\". Focus on patient comfort and treatment benefits (e.g. Invisalign, Implants).", "Dental Assistant");
    }

    public function get_solar_consult($usage) {
        return $this->call_ai("Act as an energy consultant. Given this usage data/question: \"$usage\", explain the ROI of switching to solar and federal tax credit benefits.", "Solar Expert");
    }

    public function is_spam($m, $n, $e) { return false; }
}
GrowthPress_AI::get_instance();
