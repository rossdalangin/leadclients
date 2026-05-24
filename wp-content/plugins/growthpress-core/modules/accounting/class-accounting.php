<?php
class GrowthPress_Accounting {
    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Tax Preparation Service', 'post_type' => 'page', 'post_status' => 'publish'));
    }
}
new GrowthPress_Accounting();
