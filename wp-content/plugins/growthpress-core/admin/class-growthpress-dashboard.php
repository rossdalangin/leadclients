<?php
/**
 * GrowthPress Admin Dashboard Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Dashboard {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_dashboard_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_dashboard_assets' ) );
    }

    public function add_dashboard_menu() {
        add_menu_page(
            'GrowthPress',
            'GrowthPress',
            'manage_options',
            'growthpress-dashboard',
            array( $this, 'render_dashboard' ),
            'dashicons-chart-line',
            2
        );
    }

    public function enqueue_dashboard_assets( $hook ) {
        if ( 'toplevel_page_growthpress-dashboard' !== $hook ) {
            return;
        }
        wp_enqueue_style( 'growthpress-admin-css', GROWTHPRESS_CORE_URL . 'assets/css/admin-dashboard.css', array(), GROWTHPRESS_CORE_VERSION );
        wp_enqueue_script( 'growthpress-admin-js', GROWTHPRESS_CORE_URL . 'assets/js/admin-dashboard.js', array( 'jquery' ), GROWTHPRESS_CORE_VERSION, true );
    }

    private function get_stats() {
        return array(
            'leads' => wp_count_posts('gp_lead')->publish ?? 0,
            'appointments' => wp_count_posts('gp_appointment')->publish ?? 0,
            'revenue' => 0, // Placeholder for actual revenue tracking
        );
    }

    public function render_dashboard() {
        $stats = $this->get_stats();
        ?>
        <div class="wrap growthpress-dashboard">
            <header class="dashboard-header">
                <h1>GrowthPress Business OS</h1>
                <div class="ai-status">AI Engine: <span class="status-active">Active</span></div>
            </header>

            <div class="stats-grid">
                <div class="stat-card glass-card">
                    <h3>Total Leads</h3>
                    <div class="value"><?php echo number_format($stats['leads']); ?></div>
                    <div class="trend positive">Live System Data</div>
                </div>
                <div class="stat-card glass-card">
                    <h3>Bookings</h3>
                    <div class="value"><?php echo number_format($stats['appointments']); ?></div>
                    <div class="trend positive">Confirmed Appointments</div>
                </div>
                <div class="stat-card glass-card">
                    <h3>Revenue Est.</h3>
                    <div class="value">$<?php echo number_format($stats['revenue']); ?></div>
                    <div class="trend">Pipeline Value</div>
                </div>
            </div>

            <div class="dashboard-content">
                <div class="main-panel glass-card">
                    <h3>Sales Pipeline (Kanban)</h3>
                    <div id="gp-kanban-board">
                        <?php
                        $stages = get_terms( array('taxonomy' => 'gp_lead_stage', 'hide_empty' => false) );
                        if (!empty($stages) && !is_wp_error($stages)):
                            foreach($stages as $stage): ?>
                                <div class="kanban-col"><?php echo esc_html($stage->name); ?></div>
                            <?php endforeach;
                        else: ?>
                            <div class="kanban-col">New Leads</div>
                            <div class="kanban-col">Qualified</div>
                            <div class="kanban-col">Booked</div>
                            <div class="kanban-col">Closed</div>
                        <?php endif; ?>
                    </div>
                </div>
                <aside class="side-panel glass-card">
                    <h3>AI Recommendations</h3>
                    <ul class="ai-suggestions">
                        <li>⚡ High urgency lead detected in "Dental"</li>
                        <li>📅 Follow-up needed for 5 roofing inquiries</li>
                        <li>💡 Optimize CTA on "Solar Savings" page</li>
                    </ul>
                </aside>
            </div>
        </div>
        <?php
    }
}

new GrowthPress_Dashboard();
