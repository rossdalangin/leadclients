<?php
/**
 * GrowthPress Reviews Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Reviews_Manager {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_reviews_menu' ) );
        add_action( 'wp_ajax_gp_generate_review_reply', array( $this, 'handle_reply_generation' ) );
    }

    public function add_reviews_menu() {
        add_submenu_page(
            'growthpress-dashboard',
            'Reputation & Reviews',
            'Reviews',
            'manage_options',
            'growthpress-reviews',
            array( $this, 'render_reviews_page' )
        );
    }

    public function handle_reply_generation() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $review_id = intval($_POST['review_id']);
        $reputation = new GrowthPress_Reputation();
        $reply = $reputation->suggest_review_reply($review_id);
        wp_send_json_success($reply);
    }

    public function render_reviews_page() {
        $reviews = get_posts(array('post_type' => 'gp_review', 'posts_per_page' => 10));
        ?>
        <div class="wrap">
            <h1>Reputation Management</h1>
            <p class="description">Monitor your business reviews and use AI to generate professional replies.</p>

            <div class="reviews-list" style="margin-top:20px;">
                <?php foreach($reviews as $review): ?>
                    <div class="glass-card" style="margin-bottom:15px;">
                        <h3><?php echo esc_html($review->post_title); ?></h3>
                        <p><?php echo esc_html($review->post_content); ?></p>
                        <button class="button" onclick="generateReply(<?php echo $review->ID; ?>)">Generate AI Reply</button>
                        <div id="reply-<?php echo $review->ID; ?>" style="margin-top:10px; font-style:italic;"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <script>
        function generateReply(id) {
            var $ = jQuery;
            $('#reply-' + id).text('AI is writing...');
            $.post(ajaxurl, { action: 'gp_generate_review_reply', review_id: id, gp_nonce: gp_admin.nonce }, function(res) {
                if(res.success) $('#reply-' + id).html('<strong>Suggested Reply:</strong><br>' + res.data);
            });
        }
        </script>
        <?php
    }
}

new GrowthPress_Reviews_Manager();
