<?php
/**
 * GrowthPress Reputation Management Class - Automated
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Reputation {

    public function __construct() {
        add_action( 'init', array( $this, 'register_review_cpt' ) );
        add_shortcode( 'gp_review_feed', array( $this, 'render_review_feed' ) );
        add_action( 'gp_appointment_completed', array( $this, 'trigger_review_request' ) );
    }

    public function register_review_cpt() {
        register_post_type( 'gp_review', array(
            'labels'      => array( 'name' => 'Reviews', 'singular_name' => 'Review' ),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-star-filled',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
    }

    public function trigger_review_request( $appointment_id ) {
        $email = get_post_meta( $appointment_id, '_client_email', true );
        // Logic to send review request email/SMS
        GrowthPress_Activity::log( "Review request sent to $email for appointment #$appointment_id" );
    }

    public function render_review_feed() {
        $reviews = get_posts( array( 'post_type' => 'gp_review', 'posts_per_page' => 5 ) );
        ob_start(); ?>
        <div class="gp-review-feed">
            <?php foreach ( $reviews as $review ) :
                $rating = get_post_meta( $review->ID, '_gp_rating', true ) ?: 5; ?>
                <div class="gp-review-card glass-card" style="margin-bottom: 15px;">
                    <div class="rating"><?php echo str_repeat('⭐', intval($rating)); ?></div>
                    <p>"<?php echo esc_html($review->post_content); ?>"</p>
                    <strong>- <?php echo esc_html($review->post_title); ?></strong>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public function suggest_review_reply( $review_id ) {
        $review = get_post( $review_id );
        $ai = GrowthPress_AI::get_instance();
        $prompt = "A client left this review: \"{$review->post_content}\". Generate a professional reply.";
        return $ai->call_ai( $prompt, "You are a customer success manager." );
    }
}

new GrowthPress_Reputation();
