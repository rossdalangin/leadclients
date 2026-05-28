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
        add_action( 'wp_ajax_gp_regenerate_pages', array( $this, 'handle_page_regeneration' ) );
        add_action( 'wp_ajax_gp_update_lead_stage', array( $this, 'handle_lead_stage_update' ) );
    }

    public function add_dashboard_menu() {
        $brand = get_option('growthpress_brand_name', 'GrowthPress');
        add_menu_page( $brand, $brand, 'manage_options', 'growthpress-dashboard', array( $this, 'render_dashboard' ), 'dashicons-chart-line', 2 );
    }

    public function enqueue_dashboard_assets( $hook ) {
        if ( strpos($hook, 'growthpress') === false ) return;

        wp_enqueue_style( 'growthpress-admin-menu-css', GROWTHPRESS_CORE_URL . 'assets/css/admin-menu.css', array(), GROWTHPRESS_CORE_VERSION );
        wp_enqueue_style( 'growthpress-admin-css', GROWTHPRESS_CORE_URL . 'assets/css/admin-dashboard.css', array(), GROWTHPRESS_CORE_VERSION );

        if ( 'toplevel_page_growthpress-dashboard' === $hook || strpos($hook, 'growthpress-studio') !== false ) {
            wp_enqueue_script( 'chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.9.1', true );
            wp_enqueue_script( 'jquery-ui-draggable' );
            wp_enqueue_script( 'jquery-ui-droppable' );
            wp_enqueue_script( 'growthpress-admin-js', GROWTHPRESS_CORE_URL . 'assets/js/admin-dashboard.js', array( 'jquery', 'chart-js', 'jquery-ui-draggable', 'jquery-ui-droppable' ), GROWTHPRESS_CORE_VERSION, true );
            wp_localize_script( 'growthpress-admin-js', 'gp_admin', array( 'nonce' => wp_create_nonce( 'gp_admin_nonce' ) ));
        }
    }

    public function handle_lead_stage_update() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $lead_id = intval($_POST['lead_id']);
        wp_set_object_terms( $lead_id, sanitize_text_field($_POST['stage']), 'gp_lead_stage' );
        GrowthPress_Activity::log( "Lead #$lead_id updated." );
        wp_send_json_success();
    }

    public function handle_niche_setup() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $niche = sanitize_text_field($_POST['niche']);
        $this->generate_niche_pages($niche);
        $this->generate_niche_funnel($niche);
        $this->run_niche_sample_data($niche);
        update_option( 'growthpress_niche', $niche );
        wp_send_json_success();
    }

    public function handle_page_regeneration() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $niche = get_option('growthpress_niche', 'business');
        $this->generate_niche_pages($niche, true);
        $this->generate_niche_funnel($niche);
        $this->run_niche_sample_data($niche);
        wp_send_json_success("Pages and sample data regenerated for " . ucwords($niche));
    }

    private function run_niche_sample_data($niche) {
        $class_name = 'GrowthPress_' . str_replace(' ', '', ucwords(str_replace('-', ' ', $niche)));
        if ( class_exists($class_name) ) {
            $instance = new $class_name();
            if ( method_exists($instance, 'generate_sample_data') ) $instance->generate_sample_data();
        }
        $reputation = new GrowthPress_Reputation();
        $reputation->generate_sample_data();
    }

    private function get_industry_copy($n) {
        $data = array(
            'solar' => array(
                'home_h1' => 'Power Your Home with Intelligent Solar Solutions',
                'home_sub' => 'Lock in lower energy costs and maximize your ROI with our AI-optimized solar systems.',
                'service_h1' => 'Solar Engineering & Installation',
                'service_p' => 'We provide full-service solar deployments, from custom engineering to federal tax credit optimization.'
            ),
            'dental' => array(
                'home_h1' => 'Elite Dental Care Powered by Precision AI',
                'home_sub' => 'Experience a new standard of dental wellness with our advanced triage and patient-first approach.',
                'service_h1' => 'Advanced Cosmetic & Restorative Dentistry',
                'service_p' => 'From Invisalign to full-mouth restoration, our specialists deliver life-changing results.'
            ),
            'law' => array(
                'home_h1' => 'High-Stakes Legal Representation for Modern Firms',
                'home_sub' => 'Our firm combines deep legal expertise with AI-driven case management to secure the results you deserve.',
                'service_h1' => 'Strategic Litigation & Corporate Counsel',
                'service_p' => 'Protecting your interests with aggressive representation and sophisticated legal strategy.'
            )
        );
        return $data[$n] ?? array(
            'home_h1' => 'Elite Solutions Powered by Business Intelligence',
            'home_sub' => 'Consolidate your CRM, Booking, and Marketing into one unified Operating System.',
            'service_h1' => 'Strategic Services for High-Growth Firms',
            'service_p' => 'We provide industry-leading services designed for high-impact results and long-term growth.'
        );
    }

    private function generate_niche_pages($n, $replace = false) {
        $niche_label = ucwords(str_replace('-', ' ', $n));
        $copy = $this->get_industry_copy($n);
        $hero_headline = get_theme_mod('gp_hero_headline', $copy['home_h1']);
        $hero_sub = get_theme_mod('gp_hero_subheadline', $copy['home_sub']);

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
<!-- wp:group {\"tagName\":\"section\",\"className\":\"gp-hero grainy-bg\",\"layout\":{\"type\":\"constrained\"}} -->
<section class=\"wp-block-group gp-hero grainy-bg\">
    <!-- wp:columns {\"verticalAlignment\":\"center\"} -->
    <div class=\"wp-block-columns are-vertically-aligned-center\">
        <!-- wp:column {\"verticalAlignment\":\"center\"} -->
        <div class=\"wp-block-column are-vertically-aligned-center\">
            <!-- wp:heading {\"level\":1,\"className\":\"text-gradient\"} --><h1 class=\"text-gradient\">{$hero_headline}</h1><!-- /wp:heading -->
            <!-- wp:paragraph --><p>{$hero_sub}</p><!-- /wp:paragraph -->
            <!-- wp:buttons --><div class=\"wp-block-buttons\"><!-- wp:button {\"className\":\"gp-btn\"} --><a class=\"wp-block-button__link gp-btn\">Analyze My Needs</a><!-- /wp:button --></div><!-- /wp:buttons -->
        </div>
        <!-- wp:column {\"verticalAlignment\":\"center\"} -->
        <div class=\"wp-block-column are-vertically-aligned-center\">
            <!-- wp:group {\"className\":\"glass-card\"} --><div class=\"wp-block-group glass-card\">
                <h3>Get Your Strategy</h3>
                [gp_quiz_lead_form]
            </div><!-- /wp:group -->
        </div>
    </div>
    <!-- /wp:columns -->
</section>
<!-- /wp:group -->";

        $services_content = "<h1>{$copy['service_h1']}</h1><p>{$copy['service_p']}</p>[gp_booking_form]";

        $pages = array(
            'Home'         => array('content' => $home_content, 'desc' => "Transform your $niche_label business with our AI-powered operating system."),
            'Services'     => array('content' => $services_content, 'desc' => "Explore our elite $niche_label services designed for high-ticket growth."),
            'Contact'      => array('content' => "[gp_lead_form]", 'desc' => "Connect with our $niche_label specialists today.")
        );

        foreach($pages as $t => $data) {
            $c = $data['content'];
            $query = new WP_Query(array( 'post_type' => 'page', 'title' => $t, 'post_status' => 'any', 'posts_per_page' => 1 ));
            if ( $query->have_posts() ) {
                if ( $replace ) wp_update_post(array( 'ID' => $query->posts[0]->ID, 'post_content' => $c, 'post_excerpt' => $data['desc'] ));
            } else {
                wp_insert_post(array( 'post_title' => $t, 'post_content' => $c, 'post_excerpt' => $data['desc'], 'post_type' => 'page', 'post_status' => 'publish' ));
            }
            wp_reset_postdata();
        }
    }

    private function generate_niche_funnel($n) {
        $title = ucwords(str_replace('-', ' ', $n)) . ' Growth Strategy';
        if (!get_page_by_path(sanitize_title($title), OBJECT, 'page')) {
            wp_insert_post(array( 'post_title' => $title, 'post_content' => '[gp_lead_form]', 'post_type' => 'page', 'post_status' => 'publish' ));
        }
    }

    public function render_dashboard() {
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'posts_per_page' => -1 ) );
        $appointments = get_posts( array( 'post_type' => 'gp_appointment', 'posts_per_page' => -1 ) );
        $stages = array( 'new' => 'New Leads', 'qualified' => 'Qualified', 'booked' => 'Booked', 'closed' => 'Closed' );
        $lead_count_30d = count($leads);
        $booking_count = count($appointments);
        $conv_rate = $lead_count_30d > 0 ? round(($booking_count / $lead_count_30d) * 100) : 0;
        $view_file = GROWTHPRESS_CORE_PATH . 'admin/views/dashboard.php';
        if ( file_exists( $view_file ) ) include $view_file;
    }
}
new GrowthPress_Dashboard();
