<?php
/**
 * GrowthPress Admin Dashboard Class - Analytics Enhanced
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
        wp_set_object_terms( intval($_POST['lead_id']), sanitize_text_field($_POST['stage']), 'gp_lead_stage' );
        wp_send_json_success( 'Lead stage updated.' );
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
        wp_send_json_success( "System configured for $niche." );
    }

    public function render_dashboard() {
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'posts_per_page' => -1 ) );
        $stats = array(
            'leads' => wp_count_posts('gp_lead')->publish ?? 0,
            'appointments' => wp_count_posts('gp_appointment')->publish ?? 0,
        );
        ?>
        <div class="wrap growthpress-dashboard">
            <h1>GrowthPress Business OS</h1>

            <div class="stats-grid">
                <div class="stat-card glass-card">
                    <h3>Leads</h3>
                    <div class="value"><?php echo $stats['leads']; ?></div>
                </div>
                <div class="stat-card glass-card">
                    <h3>Bookings</h3>
                    <div class="value"><?php echo $stats['appointments']; ?></div>
                </div>
            </div>

            <div class="dashboard-content">
                <div class="main-panel glass-card">
                    <h3>Lead Growth Trend</h3>
                    <canvas id="gp-leads-chart" height="100"></canvas>
                </div>
                <aside class="side-panel glass-card">
                    <h3>AI Recommendations</h3>
                    <ul><li>💡 Optimize follow-up timing.</li></ul>
                </aside>
            </div>

            <div id="gp-kanban-board" class="kanban-board" style="margin-top: 30px;">
                <?php
                $stages = array( 'new' => 'New Leads', 'qualified' => 'Qualified', 'booked' => 'Booked', 'closed' => 'Closed' );
                foreach ( $stages as $slug => $label ) : ?>
                    <div class="kanban-col" data-stage="<?php echo $slug; ?>">
                        <h3><?php echo $label; ?></h3>
                        <div class="kanban-cards">
                            <?php foreach ( $leads as $lead ) :
                                $stage = wp_get_object_terms( $lead->ID, 'gp_lead_stage', array('fields' => 'slugs') );
                                if ( (empty($stage) && $slug === 'new') || in_array($slug, $stage) ) : ?>
                                    <div class="kanban-card glass-card" data-id="<?php echo $lead->ID; ?>">
                                        <?php echo esc_html($lead->post_title); ?>
                                    </div>
                                <?php endif;
                            endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('gp-leads-chart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [{
                        label: 'New Leads',
                        data: [12, 19, 3, 5],
                        borderColor: '#2563EB',
                        tension: 0.4
                    }]
                }
            });
        });
        </script>
        <?php
    }
}
new GrowthPress_Dashboard();
