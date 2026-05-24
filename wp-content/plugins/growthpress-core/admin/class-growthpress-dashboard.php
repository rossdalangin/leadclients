<?php
/**
 * GrowthPress Admin Dashboard Class - Final
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
        add_menu_page( 'GrowthPress', 'GrowthPress', 'manage_options', 'growthpress-dashboard', array( $this, 'render_dashboard' ), 'dashicons-chart-line', 2 );
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
        GrowthPress_Activity::log( "Lead #$lead_id updated." );
        wp_send_json_success();
    }

    public function handle_niche_setup() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $niche = sanitize_text_field($_POST['niche']);
        $class_name = 'GrowthPress_' . str_replace(' ', '', ucwords(str_replace('-', ' ', $niche)));
        if ( class_exists($class_name) ) {
            $instance = new $class_name();
            if ( method_exists($instance, 'generate_sample_data') ) $instance->generate_sample_data();
        }
        update_option( 'growthpress_niche', $niche );
        wp_send_json_success();
    }

    public function render_dashboard() {
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'posts_per_page' => -1 ) );
        $tasks = get_posts( array( 'post_type' => 'gp_task', 'posts_per_page' => 10 ) );
        ?>
        <div class="wrap growthpress-dashboard">
            <h1>GrowthPress Business OS</h1>

            <div class="dashboard-content" style="display:grid; grid-template-columns: 2fr 1fr; gap:20px;">
                <div class="main-panel">
                    <div id="gp-kanban-board" style="display:flex; gap:10px; overflow-x:auto; margin-bottom:20px;">
                        <?php
                        $stages = array( 'new' => 'New', 'qualified' => 'Qualified', 'booked' => 'Booked', 'closed' => 'Closed' );
                        foreach ( $stages as $slug => $label ) : ?>
                            <div class="kanban-col" data-stage="<?php echo $slug; ?>" style="min-width:180px; background:#eee; padding:10px; border-radius:8px;">
                                <strong><?php echo $label; ?></strong>
                                <div class="kanban-cards">
                                    <?php foreach ( $leads as $lead ) :
                                        $stage = wp_get_object_terms( $lead->ID, 'gp_lead_stage', array('fields' => 'slugs') );
                                        if ( (empty($stage) && $slug === 'new') || in_array($slug, $stage) ) : ?>
                                            <div class="kanban-card glass-card" data-id="<?php echo $lead->ID; ?>" style="background:white; padding:10px; margin-top:5px; cursor:grab;">
                                                <?php echo esc_html($lead->post_title); ?>
                                                <span style="font-size:0.7rem; color:#2563EB;">Score: <?php echo get_post_meta($lead->ID, '_gp_lead_score', true); ?></span>
                                            </div>
                                        <?php endif;
                                    endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="glass-card">
                        <h3>Team Productivity & Tasks</h3>
                        <button class="button" onclick="exportLeads()">Export Leads CSV</button>
                        <table class="wp-list-table widefat fixed striped" style="margin-top:15px;">
                            <thead><tr><th>Assigned Task</th><th>Related Lead</th></tr></thead>
                            <tbody>
                                <?php foreach($tasks as $t): ?>
                                    <tr><td><?php echo esc_html($t->post_title); ?></td><td><?php echo get_the_title(get_post_meta($t->ID, '_related_lead', true)); ?></td></tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="side-panel">
                    <div class="glass-card">
                        <h3>Recent Activity</h3>
                        <ul style="font-size:0.8rem;">
                            <?php foreach(GrowthPress_Activity::get_logs() as $log) echo "<li>{$log['msg']}</li>"; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function exportLeads() {
                window.location.href = ajaxurl + '?action=gp_export_leads&gp_nonce=' + gp_admin.nonce;
            }
        </script>
        <?php
    }
}
new GrowthPress_Dashboard();
