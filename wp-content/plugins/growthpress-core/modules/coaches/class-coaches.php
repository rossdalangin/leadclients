<?php
class GrowthPress_Coaches {
    public function __construct() {
        add_shortcode( 'gp_coaching_assistant', array( $this, 'render_ai_coach' ) );
        add_action( 'init', array( $this, 'register_course_cpt' ) );
    }

    public function register_course_cpt() {
        register_post_type( 'gp_course', array(
            'labels'      => array( 'name' => 'Online Courses' ),
            'public'      => true, 'show_ui' => true, 'menu_icon' => 'dashicons-welcome-learn-more'
        ) );
    }

    public function render_ai_coach() {
        return '<div class="glass-card"><h3>AI Performance Assistant</h3><p>Get instant coaching tips based on your current challenges.</p><textarea id="coach-challenge" placeholder="What is your biggest roadblock?"></textarea><button class="button">Get Coaching</button></div>';
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Discovery Session', 'post_type' => 'page', 'post_status' => 'publish'));
        wp_insert_post(array('post_title' => 'Scaling to 7 Figures Course', 'post_type' => 'gp_course', 'post_status' => 'publish'));
    }
}
new GrowthPress_Coaches();
