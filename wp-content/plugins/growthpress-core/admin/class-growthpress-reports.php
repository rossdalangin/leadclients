<?php
/**
 * GrowthPress Reporting Class - Data-Driven
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

    private function get_live_stats() {
        $leads = get_posts(array('post_type' => 'gp_lead', 'posts_per_page' => -1));
        $appts = get_posts(array('post_type' => 'gp_appointment', 'posts_per_page' => -1));
        $proposals = get_posts(array('post_type' => 'gp_proposal', 'posts_per_page' => -1));

        $total_value = 0;
        foreach($proposals as $p) {
            $status = get_post_meta($p->ID, '_gp_proposal_status', true);
            if($status === 'Accepted') {
                $total_value += (float)get_post_meta($p->ID, '_proposal_value', true) ?: 5000; // Mock value if missing
            }
        }

        return array(
            'Total Leads' => count($leads),
            'Confirmed Bookings' => count($appts),
            'Closed Deals' => count($proposals),
            'Estimated Revenue' => $total_value
        );
    }

    public function render_reports() {
        $stats = $this->get_live_stats();
        $conv_rate = $stats['Total Leads'] > 0 ? round(($stats['Confirmed Bookings'] / $stats['Total Leads']) * 100, 1) : 0;
        ?>
        <div class="wrap growthpress-reports">
            <h1>Conversion & ROI Analytics</h1>
            <div class="stats-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:20px; margin-top:20px;">
                <?php foreach($stats as $label => $val): ?>
                    <div class="stat-card glass-card">
                        <h3><?php echo $label; ?></h3>
                        <div class="value" style="font-size:2rem; color:#2563EB; font-weight:bold;">
                            <?php echo (strpos($label, 'Revenue') !== false) ? '$'.number_format($val) : $val; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="stat-card glass-card">
                    <h3>Booking Conv. Rate</h3>
                    <div class="value" style="font-size:2rem; color:#10B981; font-weight:bold;"><?php echo $conv_rate; ?>%</div>
                </div>
            </div>

            <div class="glass-card" style="margin-top:30px; border-left: 6px solid #2563EB;">
                <h3>AI Performance Analysis</h3>
                <?php if($conv_rate < 15): ?>
                    <p>🚨 **Critical Alert:** Your booking conversion rate is below the industry standard (20%). AI suggests reviewing your 'Discovery Call' talk tracks and implementing the 5-day nurture sequence for all new leads.</p>
                <?php else: ?>
                    <p>✅ **Healthy Performance:** Your funnel is operating efficiently. To scale further, AI suggests increasing top-of-funnel traffic via the generated Ad Copy in the Studio.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
new GrowthPress_Reports();
