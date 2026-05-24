<?php
class GrowthPress_Consultants {
    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Strategic Planning', 'post_type' => 'page', 'post_status' => 'publish'));
    }
}
new GrowthPress_Consultants();
