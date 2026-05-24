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
        add_action( 'wp_ajax_gp_setup_niche', array( $this, 'handle_niche_setup' ) );
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

        // Pass nonce to JS
        wp_localize_script( 'growthpress-admin-js', 'gp_admin', array(
            'nonce' => wp_create_nonce( 'gp_admin_nonce' )
        ));
    }

    private function get_stats() {
        return array(
            'leads' => wp_count_posts('gp_lead')->publish ?? 0,
            'appointments' => wp_count_posts('gp_appointment')->publish ?? 0,
            'revenue' => 0,
        );
    }

    public function handle_niche_setup() {
        if ( ! check_ajax_referer( 'gp_admin_nonce', 'gp_nonce', false ) ) {
            wp_send_json_error( 'Security check failed.' );
        }

        $niche = sanitize_text_field($_POST['niche']);

        // Dynamic call to niche sample data generator
        $class_name = 'GrowthPress_' . str_replace('-', '', ucwords($niche, '-'));
        if ( class_exists($class_name) ) {
            $instance = new $class_name();
            if ( method_exists($instance, 'generate_sample_data') ) {
                $instance->generate_sample_data();
            }
        }

        wp_send_json_success( "System configured for $niche with sample data." );
    }

    public function render_dashboard() {
        $stats = $this->get_stats();
        ?>
        <div class="wrap growthpress-dashboard">
            <header class="dashboard-header">
                <h1>GrowthPress Business OS</h1>
                <div class="ai-status">AI Engine: <span class="status-active">Active</span></div>
            </header>

            <div class="setup-wizard glass-card" style="margin-bottom: 30px;">
                <h2>Welcome to GrowthPress!</h2>
                <p>Select your niche to optimize your business operating system.</p>
                <select id="gp-niche-select">
                    <option value="dental">Dental Clinic</option>
                    <option value="law">Law Firm</option>
                    <option value="contractor">Contractor</option>
                    <option value="roofing">Roofing</option>
                    <option value="solar">Solar</option>
                    <option value="accounting">Accounting</option>
                    <option value="medical">Medical Clinic</option>
                    <option value="real-estate">Real Estate</option>
                    <option value="coaches">Coaches</option>
                    <option value="consultants">Consultants</option>
                </select>
                <button class="button button-primary" onclick="setupNiche()">Generate System Data</button>
            </div>

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
                        <div class="kanban-col">New Leads</div>
                        <div class="kanban-col">Qualified</div>
                        <div class="kanban-col">Booked</div>
                        <div class="kanban-col">Closed</div>
                    </div>
                </div>
                <aside class="side-panel glass-card">
                    <h3>AI Recommendations</h3>
                    <ul class="ai-suggestions">
                        <li>⚡ High urgency lead detected in "Dental"</li>
                        <li>📅 Follow-up needed for 5 roofing inquiries</li>
                    </ul>
                </aside>
            </div>
        </div>
        <?php
    }
}

new GrowthPress_Dashboard();
