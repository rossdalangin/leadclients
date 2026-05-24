<?php
class GrowthPress_Roofing {
    public function __construct() { add_action( 'gp_emergency_booking', array( $this, 'handle_emergency' ) ); }
    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Emergency Leak Repair', 'post_type' => 'page', 'post_status' => 'publish'));
    }
}
new GrowthPress_Roofing();
