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
        add_action( 'wp_ajax_gp_get_lead_brief', array( $this, 'handle_get_lead_brief' ) );
        add_action( 'wp_ajax_gp_strategic_search', array( $this, 'handle_strategic_search' ) );
    }

    public function add_dashboard_menu() {
        $brand = get_option('growthpress_brand_name', 'GrowthPress');
        add_menu_page( $brand, $brand, 'manage_options', 'growthpress-dashboard', array( $this, 'render_dashboard' ), 'dashicons-chart-line', 2 );
    }

    public function enqueue_dashboard_assets( $hook ) {
        if ( strpos($hook, 'growthpress') === false ) return;

        // Elite Typography Injection
        wp_enqueue_style( 'gp-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&family=Lexend:wght@300;400;600;700;800;900&display=swap', array(), null );

        wp_enqueue_style( 'growthpress-admin-menu-css', GROWTHPRESS_CORE_URL . 'assets/css/admin-menu.css', array(), GROWTHPRESS_CORE_VERSION );
        wp_enqueue_style( 'growthpress-admin-css', GROWTHPRESS_CORE_URL . 'assets/css/admin-dashboard.css', array(), GROWTHPRESS_CORE_VERSION );

        wp_add_inline_style( 'growthpress-admin-css', '
            .status-ping { width: 10px; height: 10px; border-radius: 50%; position: relative; }
            .status-ping.active { background: #10B981; }
            .status-ping.active::after { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 50%; background: #10B981; animation: gp-ping 2s infinite; }
            @keyframes gp-ping { 0% { transform: scale(1); opacity: 0.8; } 100% { transform: scale(3); opacity: 0; } }
        ' );

        $custom_css = get_option('growthpress_custom_css');
        if ( $custom_css ) {
            wp_add_inline_style( 'growthpress-admin-css', $custom_css );
        }

        if ( 'toplevel_page_growthpress-dashboard' === $hook || strpos($hook, 'growthpress-studio') !== false ) {
            wp_enqueue_script( 'chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.9.1', true );
            wp_enqueue_script( 'jquery-ui-draggable' );
            wp_enqueue_script( 'jquery-ui-droppable' );
            wp_enqueue_script( 'growthpress-admin-js', GROWTHPRESS_CORE_URL . 'assets/js/admin-dashboard.js', array( 'jquery', 'chart-js', 'jquery-ui-draggable', 'jquery-ui-droppable' ), GROWTHPRESS_CORE_VERSION, true );
            wp_localize_script( 'growthpress-admin-js', 'gp_admin', array( 'nonce' => wp_create_nonce( 'gp_admin_nonce' ) ));
        }
    }

    public function handle_strategic_search() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $query = sanitize_text_field($_POST['query']);
        $results = get_posts(array(
            'post_type' => array('gp_lead', 'gp_appointment', 'gp_project'),
            's' => $query,
            'posts_per_page' => 10
        ));
        $html = '';
        foreach($results as $r) {
            $html .= '<div class="search-res-item" style="padding:15px; border-bottom:1px solid #EEE; cursor:pointer;" onclick="location.href=\''.get_edit_post_link($r->ID).'\'">';
            $html .= '<strong style="font-size:13px;">'.esc_html($r->post_title).'</strong><br>';
            $html .= '<span style="font-size:10px; opacity:0.5; text-transform:uppercase;">'.esc_html($r->post_type).'</span>';
            $html .= '</div>';
        }
        wp_send_json_success(array('html' => $html ?: '<div style="padding:20px; opacity:0.4;">No intelligence found for "'.esc_html($query).'"</div>'));
    }

    public function handle_get_lead_brief() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $lead_id = intval($_POST['lead_id']);
        $lead = get_post($lead_id);
        if ( ! $lead ) wp_send_json_error('Lead not found');

        $prob = get_post_meta($lead_id, '_gp_ai_probability', true) ?: 75;
        $closing = get_post_meta($lead_id, '_gp_ai_closing_tips', true) ?: 'Analyzing closing vectors...';
        $discovery = get_post_meta($lead_id, '_gp_ai_discovery_questions', true) ?: 'Calibrating discovery questions...';
        $suggested = get_post_meta($lead_id, '_gp_ai_suggested_reply', true) ?: 'Drafting personalized response...';

        ob_start();
        ?>
        <div class="gp-intel-brief-modal-content">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px; padding-bottom:20px; border-bottom:1px solid #EEE;">
                <div style="display:flex; align-items:center; gap:15px;">
                    <div class="status-ping active"></div>
                    <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:2px;">NEURAL LINK STABLE</div>
                </div>
                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px;">LATENCY: 84MS</div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 2fr; gap:30px;">
                <div class="brief-side">
                    <div style="background:var(--primary-glow); padding:30px; border-radius:25px; text-align:center; margin-bottom:30px;">
                        <div style="font-size:38px; font-weight:950; color:var(--primary);"><?php echo $prob; ?>%</div>
                        <div style="font-size:10px; font-weight:900; opacity:0.5; letter-spacing:1px;">DEAL PROBABILITY</div>
                    </div>
                    <div style="background:#F8FAFC; padding:25px; border-radius:20px;">
                        <h4 style="margin-top:0; font-size:13px; text-transform:uppercase; letter-spacing:1px;">Closing Tactics</h4>
                        <div style="font-size:12px; line-height:1.6; opacity:0.7;"><?php echo nl2br(esc_html($closing)); ?></div>
                    </div>
                </div>
                <div class="brief-main">
                    <h3 style="margin-top:0;"><?php echo esc_html($lead->post_title); ?></h3>
                    <div style="font-size:13px; background:#FFFBEB; padding:20px; border-radius:15px; border:1px solid #FEF3C7; color:#92400E; margin-bottom:25px;">
                        <strong>AI Discovery Strategy:</strong><br>
                        <?php echo nl2br(esc_html($discovery)); ?>
                    </div>
                    <h4 style="margin-bottom:10px;">Neural Draft Response</h4>
                    <textarea style="width:100%; height:120px; border-radius:12px; padding:15px; font-size:13px; background:#F0FDF4; border:1px solid #DCFCE7;"><?php echo esc_textarea($suggested); ?></textarea>
                    <div style="margin-top:20px; display:flex; gap:10px;">
                        <button class="gp-btn" style="flex:1; background:var(--secondary); color:white !important; padding:12px; border-radius:12px;">Sync to CRM</button>
                        <a href="<?php echo get_edit_post_link($lead_id); ?>" class="gp-btn" style="flex:1; text-align:center; background:transparent; border:1px solid #E2E8F0; padding:12px; border-radius:12px;">Full Dossier</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        wp_send_json_success(array('html' => $html));
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
