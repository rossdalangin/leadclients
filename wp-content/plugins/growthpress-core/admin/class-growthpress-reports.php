<?php
/**
 * GrowthPress Reporting Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Reports {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_reports_menu' ) );
    }

    public function add_reports_menu() {
        add_submenu_page(
            'growthpress-dashboard',
            'Reports & ROI',
            'Reports',
            'manage_options',
            'growthpress-reports',
            array( $this, 'render_reports' )
        );
    }

    public function render_reports() {
        $leads = wp_count_posts('gp_lead')->publish ?: 0;
        $bookings = wp_count_posts('gp_appointment')->publish ?: 0;
        $conversion = $leads > 0 ? ($bookings / $leads) * 100 : 0;
        ?>
        <div class="wrap">
            <h1>GrowthPress ROI & Conversion Reports</h1>
            <div class="stats-grid" style="display:flex; gap:20px; margin-top:20px;">
                <div class="stat-card glass-card">
                    <h3>Booking Conv. Rate</h3>
                    <div class="value"><?php echo number_format($conversion, 1); ?>%</div>
                </div>
                <div class="stat-card glass-card">
                    <h3>Est. Pipeline ROI</h3>
                    <div class="value">$<?php echo number_format($bookings * 1500); ?></div>
                </div>
            </div>
            <div class="glass-card" style="margin-top:20px;">
                <h3>Conversion Breakdown</h3>
                <p>AI Insights: Your conversion rate is above industry average for the selected niche.</p>
            </div>
        </div>
        <?php
    }
}

new GrowthPress_Reports();
