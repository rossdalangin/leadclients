<?php
/**
 * GrowthPress Admin Dashboard Class - Visual Enhanced
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
        GrowthPress_Activity::log( "Lead #$lead_id updated." );
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
        // Hero Content from Customizer
        $hero_headline = get_theme_mod('gp_hero_headline', 'Transform Your Business with AI');
        $hero_sub = get_theme_mod('gp_hero_subheadline', 'Consolidate your CRM, Booking, and Marketing into one unified Operating System.');

        $niche_label = ucwords(str_replace('-', ' ', $n));

        // Industry-Specific Dynamic Elements
        $niche_shortcodes = array(
            'solar'       => '[gp_solar_calculator]',
            'contractor'  => '[gp_contractor_estimator]',
            'medical'     => '[gp_symptom_checker]',
            'dental'      => '[gp_ai_faq]',
            'law'         => '[gp_legal_intake]',
            'accounting'  => '[gp_tax_estimator]',
            'coaches'     => '[gp_coaching_assistant]',
            'real-estate' => '[gp_location_switcher]'
        );
        $industry_hook = $niche_shortcodes[$n] ?? '[gp_urgency_banner]';

        $home_content = "
<!-- wp:group {\"tagName\":\"section\",\"className\":\"gp-hero\",\"layout\":{\"type\":\"constrained\"}} -->
<section class=\"wp-block-group gp-hero\">
    <div class=\"glass-card\" style=\"padding:60px; text-align:center;\">
        <h1 style=\"font-size:3.5rem; margin-bottom:20px;\">{$hero_headline}</h1>
        <p style=\"font-size:1.25rem; opacity:0.9; margin-bottom:40px;\">{$hero_sub}</p>
        <div style=\"max-width:500px; margin:0 auto;\">
            [gp_quiz_lead_form]
        </div>
    </div>
</section>
<!-- /wp:group -->

<!-- wp:group {\"tagName\":\"section\",\"layout\":{\"type\":\"constrained\"}} -->
<section class=\"wp-block-group\">
    <div class=\"glass-card\" style=\"padding:30px; margin-top:-40px; position:relative; z-index:10;\">
        <h3>Why Leading Businesses Choose Our {$niche_label} OS</h3>
        <div class=\"wp-block-columns\">
            <div class=\"wp-block-column\"><h4>AI Automation</h4><p>We reduce manual labor by 40% using custom AI workflows.</p></div>
            <div class=\"wp-block-column\"><h4>Instant Booking</h4><p>Book discovery calls in seconds with our integrated engine.</p></div>
            <div class=\"wp-block-column\"><h4>Elite Conversion</h4><p>Psychologically optimized layouts designed for high-ticket sales.</p></div>
        </div>
    </div>
</section>
<!-- /wp:group -->

<!-- wp:group {\"tagName\":\"section\",\"layout\":{\"type\":\"constrained\"}} -->
<section class=\"wp-block-group\" style=\"margin-top:60px; text-align:center;\">
    <h2>Market Insights for {$niche_label}</h2>
    <div style=\"max-width:800px; margin:0 auto;\">{$industry_hook}</div>
</section>
<!-- /wp:group -->";

        // Services Page
        $services_headline = get_theme_mod('gp_services_intro', 'Elite ' . $niche_label . ' Solutions');
        $services_cta = get_theme_mod('gp_services_cta', 'Book a Discovery Call');

        $services_content = "
<h1>{$services_headline}</h1>
<p>We provide industry-leading {$niche_label} services designed for high-impact results and long-term growth.</p>
<!-- wp:columns -->
<div class=\"wp-block-columns\">
    <div class=\"wp-block-column\">
        <h3>Advanced AI Automation</h3>
        <p>We leverage cutting-edge technology to streamline our {$niche_label} processes and improve client outcomes.</p>
    </div>
    <div class=\"wp-block-column\">
        <h3>High-Ticket Results</h3>
        <p>Our methodology focuses on the highest ROI activities, ensuring you dominate the local search market.</p>
    </div>
</div>
<!-- /wp:columns -->
<div class=\"glass-card\" style=\"margin-top:40px; padding:40px; background:linear-gradient(135deg, #ffffff, #f1f5f9); border:2px solid #2563EB;\">
    <div class=\"wp-block-columns\">
        <div class=\"wp-block-column\" style=\"flex-basis:60%;\">
            <h2>{$services_cta}</h2>
            <p>Speak with our senior specialists to identify growth opportunities in your business.</p>
            [gp_booking_form]
        </div>
        <div class=\"wp-block-column\">
            <h4>What to Expect:</h4>
            <ul>
                <li>Custom Growth Roadmap</li>
                <li>AI Automation Audit</li>
                <li>Competitor Gap Analysis</li>
            </ul>
        </div>
    </div>
</div>";

        // FAQ Page
        $faq_content = "
<div class=\"container\" style=\"max-width:800px;\">
    <h1 style=\"text-align:center;\">Expert Insights & FAQ</h1>
    <p style=\"text-align:center; opacity:0.7;\">Common questions about our {$niche_label} services and AI-powered growth strategies.</p>
    <div style=\"margin-top:40px;\">
        [gp_ai_faq]
    </div>
</div>";

        // Contact Page
        $addr = get_theme_mod('gp_contact_address', '123 Business Growth Way, Silicon Valley, CA');
        $phone = get_theme_mod('gp_contact_phone', '+1 (555) 000-GROW');
        $email = get_theme_mod('gp_contact_email', 'hello@growthpress.ai');

        $contact_content = "
<div class=\"wp-block-columns\">
    <div class=\"wp-block-column\">
        <h2>Connect with Our Team</h2>
        <p>Reach out to discuss how we can scale your operations and implement AI automation.</p>
        <p><strong>Address:</strong> {$addr}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Email:</strong> {$email}</p>
        <hr />
        <h4>Service Coverage:</h4>
        [gp_location_switcher]
    </div>
    <div class=\"wp-block-column\">
        <div class=\"glass-card\">
            <h3>Priority Inquiry</h3>
            [gp_lead_form]
        </div>
    </div>
</div>";

        // Pricing Page
        $pricing_content = "
<div class=\"container\" style=\"text-align:center;\">
    <h1>Transparent Investment Plans</h1>
    <p>Choose the level of service that matches your {$niche_label} growth goals.</p>
    <div class=\"wp-block-columns\" style=\"margin-top:50px;\">
        <div class=\"wp-block-column glass-card\" style=\"padding:30px;\">
            <h3>Standard</h3>
            <div style=\"font-size:2rem; font-weight:800; color:#2563EB;\">$1,999/mo</div>
            <p>Essential {$niche_label} services and basic AI automation.</p>
            <ul style=\"text-align:left; font-size:14px;\">
                <li>Core Lead Capture</li>
                <li>Email Nurturing</li>
                <li>Standard Support</li>
            </ul>
        </div>
        <div class=\"wp-block-column glass-card\" style=\"padding:30px; border:2px solid #2563EB; transform:scale(1.05);\">
            <div style=\"background:#2563EB; color:white; padding:5px; border-radius:4px; font-size:10px; margin-bottom:10px;\">MOST POPULAR</div>
            <h3>Premium</h3>
            <div style=\"font-size:2rem; font-weight:800; color:#2563EB;\">$3,999/mo</div>
            <p>Full-scale AI-powered growth for high-ticket results.</p>
            <ul style=\"text-align:left; font-size:14px;\">
                <li>Advanced AI Triage</li>
                <li>Omnichannel Automation</li>
                <li>Priority Support</li>
            </ul>
        </div>
        <div class=\"wp-block-column glass-card\" style=\"padding:30px;\">
            <h3>Enterprise</h3>
            <div style=\"font-size:2rem; font-weight:800; color:#2563EB;\">Custom</div>
            <p>Bespoke solutions for multi-location teams.</p>
            <ul style=\"text-align:left; font-size:14px;\">
                <li>Custom API Integration</li>
                <li>Dedicated Account Mgr</li>
                <li>White-Label OS</li>
            </ul>
        </div>
    </div>
</div>";

        // Case Studies Page
        $cases_content = "
<div class=\"container\">
    <h1>Results & Transformation</h1>
    <p>How we've helped {$niche_label} businesses scale their operations.</p>
    <div class=\"wp-block-columns\" style=\"margin-top:40px;\">
        <div class=\"wp-block-column glass-card\">
            <h3>300% Growth in Bookings</h3>
            <p>A regional {$niche_label} firm increased their efficiency by 40% in just 90 days.</p>
            <a href=\"#\">Read Full Story</a>
        </div>
        <div class=\"wp-block-column glass-card\">
            <h3>$1.2M Revenue Increase</h3>
            <p>Leveraging our AI lead qualification to identify high-value clients instantly.</p>
            <a href=\"#\">Read Full Story</a>
        </div>
    </div>
</div>";

        $pages = array(
            'Home'         => $home_content,
            'Services'     => $services_content,
            'Pricing'      => $pricing_content,
            'Case Studies' => $cases_content,
            'FAQ'          => $faq_content,
            'Contact'      => $contact_content
        );

        foreach($pages as $t => $c) {
            $existing = get_page_by_path(sanitize_title($t), OBJECT, 'page');
            if(!$existing) {
                wp_insert_post(array(
                    'post_title'   => $t,
                    'post_content' => $c,
                    'post_type'    => 'page',
                    'post_status'  => 'publish'
                ));
            }
        }
    }

    private function generate_niche_funnel($n) {
        $title = ucwords(str_replace('-', ' ', $n)) . ' Growth Strategy';
        if (!get_page_by_path(sanitize_title($title), OBJECT, 'page')) {
            wp_insert_post(array(
                'post_title'   => $title,
                'post_content' => '<!-- wp:heading --><h2>Download Your Free AI-Powered Strategy</h2><!-- /wp:heading --><p>Enter your details below to get instant access to the guide.</p>[gp_lead_form]',
                'post_type'    => 'page',
                'post_status'  => 'publish'
            ));
        }
    }

    public function render_dashboard() {
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'posts_per_page' => -1 ) );
        $stages = array( 'new' => 'New Leads', 'qualified' => 'Qualified', 'booked' => 'Booked', 'closed' => 'Closed' );
        $ai = GrowthPress_AI::get_instance();

        $view_file = GROWTHPRESS_CORE_PATH . 'admin/views/dashboard.php';
        if ( file_exists( $view_file ) ) {
            include $view_file;
        }
    }
}
new GrowthPress_Dashboard();
