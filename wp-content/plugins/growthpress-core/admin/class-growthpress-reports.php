<?php
/**
 * GrowthPress Reporting Class - Funnel Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Reports {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_reports_menu' ) );
    }

    public function add_reports_menu() {
        add_submenu_page( 'growthpress-dashboard', 'Reports & ROI', 'Reports', 'manage_options', 'growthpress-reports', array( $this, 'render_reports' ) );
    }

    private function get_funnel_stats() {
        // Mock data for funnel performance
        return array(
            'Landing Page Views' => 2450,
            'Lead Conversions'  => 185,
            'Booking Conversions' => 42,
            'Total ROI' => 63000
        );
    }

    public function render_reports() {
        $stats = $this->get_funnel_stats();
        ?>
        <div class="wrap growthpress-reports">
            <h1>Conversion & ROI Analytics</h1>
            <div class="stats-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:20px; margin-top:20px;">
                <?php foreach($stats as $label => $val): ?>
                    <div class="stat-card glass-card">
                        <h3><?php echo $label; ?></h3>
                        <div class="value" style="font-size:2rem; color:#2563EB; font-weight:bold;">
                            <?php echo (is_numeric($val) && $val > 1000) ? '$'.number_format($val) : $val; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="glass-card" style="margin-top:30px;">
                <h3>AI Funnel Insights</h3>
                <p>Your 'Strategy Guide' funnel is performing 15% better than the industry average. Suggest increasing ad spend on Facebook for the 'Solar ROI' campaign.</p>
            </div>
        </div>
        <?php
    }
}
new GrowthPress_Reports();
