<?php
class GrowthPress_Lead_Form_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'gp_lead_form'; }
    public function get_title() { return 'GP Lead Form'; }
    public function get_icon() { return 'eicon-form-horizontal'; }
    public function get_categories() { return [ 'growthpress' ]; }
    protected function render() {
        echo do_shortcode('[gp_lead_form]');
    }
}
