<?php
class GrowthPress_Coaches {
    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Discovery Session', 'post_type' => 'page', 'post_status' => 'publish'));
    }
}
new GrowthPress_Coaches();
