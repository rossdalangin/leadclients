<?php
/**
 * GrowthPress Shortcode Reference Page
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Shortcode_Ref {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_shortcode_menu' ) );
    }

    public function add_shortcode_menu() {
        add_submenu_page(
            'growthpress-dashboard',
            'Shortcode Library',
            'Shortcodes',
            'manage_options',
            'growthpress-shortcodes',
            array( $this, 'render_shortcodes_page' )
        );
    }

    public function render_shortcodes_page() {
        $shortcodes = array(
            array(
                'tag'   => '[gp_lead_form]',
                'title' => 'Standard Lead Capture',
                'desc'  => 'Displays the primary high-converting lead form with AI spam filtering.',
                'usage' => 'Place on Landing Pages or your Contact page.',
                'example' => '[gp_lead_form]'
            ),
            array(
                'tag'   => '[gp_quiz_lead_form]',
                'title' => 'Conversational AI Quiz',
                'desc'  => 'A multi-step quiz to qualify high-ticket leads before capture.',
                'usage' => 'Best used on the Homepage Hero or a dedicated Triage page.',
                'example' => '[gp_quiz_lead_form]'
            ),
            array(
                'tag'   => '[gp_booking_form]',
                'title' => 'Appointment Scheduling',
                'desc'  => 'Displays the staff-aware calendar for booking discovery calls.',
                'usage' => 'Place on Services pages or after a lead is captured.',
                'example' => '[gp_booking_form]'
            ),
            array(
                'tag'   => '[gp_solar_calculator]',
                'title' => 'Solar ROI Estimator',
                'desc'  => 'Interactive calculator for solar niches with AI energy consulting.',
                'usage' => 'Specific to Solar niche companies.',
                'example' => '[gp_solar_calculator]'
            ),
            array(
                'tag'   => '[gp_solar_financing]',
                'title' => 'Solar Financing Form',
                'desc'  => 'Secure inquiry form for $0-down solar financing eligibility.',
                'usage' => 'Place on Solar pricing or financing pages.',
                'example' => '[gp_solar_financing]'
            ),
            array(
                'tag'   => '[gp_dental_insurance_form]',
                'title' => 'Dental Insurance Verifier',
                'desc'  => 'Frontend tool for patients to check insurance coverage instantly.',
                'usage' => 'Place on Dental intake or pricing pages.',
                'example' => '[gp_dental_insurance_form]'
            ),
            array(
                'tag'   => '[gp_contractor_estimator]',
                'title' => 'Construction Cost Estimator',
                'desc'  => 'Precision labor and material calculator for renovation niches.',
                'usage' => 'Specific to Contractor/Roofing niches.',
                'example' => '[gp_contractor_estimator]'
            ),
            array(
                'tag'   => '[gp_urgency_banner]',
                'title' => 'Dynamic Urgency Alert',
                'desc'  => 'Displays a niche-specific alert banner (e.g. Emergency Dental available).',
                'usage' => 'Place at the very top of your site or hero sections.',
                'example' => '[gp_urgency_banner]'
            )
        );
        ?>
        <div class="wrap growthpress-shortcodes">
            <h1>GrowthPress Shortcode Library</h1>
            <p class="description">Use these shortcodes to deploy the Business OS features anywhere on your site.</p>

            <div class="shortcode-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap:20px; margin-top:20px;">
                <?php foreach($shortcodes as $s): ?>
                    <div class="glass-card">
                        <code style="font-size:18px; color:#2563EB;"><?php echo $s['tag']; ?></code>
                        <h3 style="margin-top:10px;"><?php echo $s['title']; ?></h3>
                        <p><?php echo $s['desc']; ?></p>
                        <div style="background:#f8fafc; padding:10px; border-radius:8px; font-size:12px;">
                            <strong>Instructions:</strong> <?php echo $s['usage']; ?><br>
                            <strong>Sample Data:</strong> Automatically populated based on Niche.
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}

new GrowthPress_Shortcode_Ref();
