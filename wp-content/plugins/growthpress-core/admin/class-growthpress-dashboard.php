<?php
/**
 * GrowthPress Admin Dashboard Class - AI Closing Logic
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Dashboard {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_dashboard_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_dashboard_assets' ) );
        add_action( 'wp_ajax_gp_setup_niche', array( $this, 'handle_niche_setup' ) );
        add_action( 'wp_ajax_gp_update_lead_stage', array( $this, 'handle_lead_stage_update' ) );
    }

    public function add_dashboard_menu() {
        $brand = get_option('growthpress_brand_name', 'GrowthPress');
        add_menu_page( $brand, $brand, 'manage_options', 'growthpress-dashboard', array( $this, 'render_dashboard' ), 'dashicons-chart-line', 2 );
    }

    public function enqueue_dashboard_assets( $hook ) {
        if ( 'toplevel_page_growthpress-dashboard' !== $hook ) return;
        wp_enqueue_style( 'growthpress-admin-css', GROWTHPRESS_CORE_URL . 'assets/css/admin-dashboard.css', array(), GROWTHPRESS_CORE_VERSION );
        wp_enqueue_script( 'chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.9.1', true );
        wp_enqueue_script( 'jquery-ui-draggable' );
        wp_enqueue_script( 'jquery-ui-droppable' );
        wp_enqueue_script( 'growthpress-admin-js', GROWTHPRESS_CORE_URL . 'assets/js/admin-dashboard.js', array( 'jquery', 'chart-js', 'jquery-ui-draggable', 'jquery-ui-droppable' ), GROWTHPRESS_CORE_VERSION, true );
        wp_localize_script( 'growthpress-admin-js', 'gp_admin', array( 'nonce' => wp_create_nonce( 'gp_admin_nonce' ) ));
    }

    public function handle_lead_stage_update() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $lead_id = intval($_POST['lead_id']);
        wp_set_object_terms( $lead_id, sanitize_text_field($_POST['stage']), 'gp_lead_stage' );
        GrowthPress_Activity::log( "Lead #$lead_id stage updated." );
        wp_send_json_success();
    }

    public function handle_niche_setup() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $niche = sanitize_text_field($_POST['niche']);
        $this->generate_niche_pages($niche);
        $this->generate_niche_funnel($niche);
        $class_name = 'GrowthPress_' . str_replace(' ', '', ucwords(str_replace('-', ' ', $niche)));
        if ( class_exists($class_name) ) {
            $instance = new $class_name();
            if ( method_exists($instance, 'generate_sample_data') ) $instance->generate_sample_data();
        }
        update_option( 'growthpress_niche', $niche );
        wp_send_json_success();
    }

    private function generate_niche_pages($n) {
        $pages = array('Home' => '[gp_lead_form]', 'Services' => '[gp_booking_form]');
        foreach($pages as $t => $c) { if(!get_page_by_title($t)) wp_insert_post(array('post_title'=>$t,'post_content'=>$c,'post_type'=>'page','post_status'=>'publish')); }
    }

    private function generate_niche_funnel($n) {
        wp_insert_post(array('post_title'=>'Strategy Guide','post_content'=>'[gp_lead_form]','post_type'=>'page','post_status'=>'publish'));
    }

    public function render_dashboard() {
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'posts_per_page' => -1 ) );
        $stages = array( 'new' => 'New', 'qualified' => 'Qualified', 'booked' => 'Booked', 'closed' => 'Closed' );
        ?>
        <div class="wrap growthpress-dashboard">
            <h1><?php echo get_option('growthpress_brand_name', 'GrowthPress'); ?> OS</h1>

            <div id="gp-kanban-board" style="display:flex; gap:15px; margin-top:20px; overflow-x:auto;">
                <?php foreach ( $stages as $slug => $label ) : ?>
                    <div class="kanban-col" data-stage="<?php echo $slug; ?>" style="min-width:220px; background:#f4f4f4; padding:10px; border-radius:8px;">
                        <h4><?php echo $label; ?></h4>
                        <div class="kanban-cards">
                            <?php foreach ( $leads as $lead ) :
                                $stage = wp_get_object_terms( $lead->ID, 'gp_lead_stage', array('fields' => 'slugs') );
                                if ( (empty($stage) && $slug === 'new') || in_array($slug, $stage) ) : ?>
                                    <div class="kanban-card glass-card" data-id="<?php echo $lead->ID; ?>" style="background:white; margin-bottom:10px; padding:10px; cursor:grab;">
                                        <strong><?php echo esc_html($lead->post_title); ?></strong>
                                        <div class="ai-next-step" style="font-size:10px; color:#2563EB; margin-top:5px; border-top:1px solid #eee; padding-top:3px;">
                                            AI Tip: Send case study.
                                        </div>
                                    </div>
                                <?php endif;
                            endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
new GrowthPress_Dashboard();
