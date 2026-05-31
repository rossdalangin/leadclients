<?php
/**
 * GrowthPress CRM Core Class - Final Advanced Elite v2
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_CRM {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', array( $this, 'register_cpts' ) );
        add_action( 'gp_lead_captured', array( $this, 'trigger_lead_automations' ) );
        add_action( 'gp_cron_followup', array( $this, 'handle_abandoned_inquiry_followup' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_crm_meta_boxes' ) );
        add_action( 'wp_ajax_gp_log_behavior', array( $this, 'handle_behavior_logging' ) );
        add_action( 'wp_ajax_nopriv_gp_log_behavior', array( $this, 'handle_behavior_logging' ) );
        add_action( 'wp_ajax_gp_export_leads', array( $this, 'handle_lead_export' ) );
        add_action( 'wp_ajax_gp_add_lead_note', array( $this, 'handle_add_note' ) );
        if ( ! wp_next_scheduled( 'gp_cron_followup' ) ) {
            wp_schedule_event( time(), 'hourly', 'gp_cron_followup' );
        }
    }

    public function register_cpts() {
        register_post_type( 'gp_lead', array(
            'labels' => array( 'name' => 'Leads' ),
            'public' => false,
            'show_ui' => true,
            'supports' => array( 'title', 'editor', 'custom-fields' ),
            'menu_icon' => 'dashicons-id-alt'
        ) );

        register_post_type( 'gp_task', array(
            'labels' => array( 'name' => 'Tasks' ),
            'public' => false,
            'show_ui' => true,
            'supports' => array( 'title', 'editor', 'custom-fields' ),
            'menu_icon' => 'dashicons-yes'
        ) );

        register_post_type( 'gp_kb', array(
            'labels' => array( 'name' => 'Knowledge Base', 'singular_name' => 'Article' ),
            'public' => true,
            'show_ui' => true,
            'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
            'menu_icon' => 'dashicons-book-alt'
        ) );

        register_post_type( 'gp_project', array(
            'labels'      => array( 'name' => 'Case Studies/Projects', 'singular_name' => 'Project' ),
            'public'      => true, 'show_ui' => true, 'menu_icon' => 'dashicons-portfolio',
            'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        ) );

        register_post_type( 'gp_service', array(
            'labels'      => array( 'name' => 'Service Lines', 'singular_name' => 'Service' ),
            'public'      => true, 'show_ui' => true, 'menu_icon' => 'dashicons-hammer',
            'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        ) );

        register_taxonomy( 'gp_lead_stage', 'gp_lead', array(
            'labels' => array( 'name' => 'Lead Stages' ),
            'hierarchical' => true,
            'show_ui' => true
        ) );

        register_taxonomy( 'gp_lead_tag', 'gp_lead', array(
            'labels' => array( 'name' => 'Lead Tags' ),
            'hierarchical' => false,
            'show_ui' => true
        ) );

        add_shortcode( 'gp_lead_form', array( $this, 'render_lead_form' ) );
        add_shortcode( 'gp_quiz_lead_form', array( $this, 'render_quiz_form' ) );
        add_action( 'wp_ajax_gp_submit_lead', array( $this, 'handle_lead_submission' ) );
        add_action( 'wp_ajax_nopriv_gp_submit_lead', array( $this, 'handle_lead_submission' ) );
        add_action( 'gp_async_lead_analysis', array( $this, 'process_async_analysis' ) );
    }

    public function render_lead_form() {
        $nonce = wp_create_nonce('gp_lead_nonce');
        return '<form class="gp-form glass-card" data-action="gp_submit_lead">
            <input type="hidden" name="nonce" value="' . $nonce . '">
            <div style="margin-bottom:20px;"><label style="font-weight:900; font-size:10px; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:10px;">IDENTITY</label><input type="text" name="lead_name" placeholder="Full Name" required></div>
            <div style="margin-bottom:20px;"><label style="font-weight:900; font-size:10px; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:10px;">COMMUNICATION</label><input type="email" name="lead_email" placeholder="Email Address" required></div>
            <div style="margin-bottom:20px;"><label style="font-weight:900; font-size:10px; opacity:0.5; letter-spacing:1px; display:block; margin-bottom:10px;">INQUIRY DETAIL</label><textarea name="lead_msg" placeholder="Describe your growth goals..."></textarea></div>
            <button type="submit" class="gp-btn" style="width:100%;">Initialize Sequence</button>
        </form>';
    }

    public function render_quiz_form() {
        $niche = get_option('growthpress_niche', 'business');
        $questions = array(
            'solar'       => array('q' => 'What is your average monthly energy bill?', 'opts' => array('$50-$150', '$150-$300', '$300+')),
            'dental'      => array('q' => 'What type of treatment are you interested in?', 'opts' => array('Cosmetic/Invisalign', 'Routine/Checkup', 'Emergency')),
            'law'         => array('q' => 'How urgent is your legal matter?', 'opts' => array('Immediate', 'This Month', 'Just Researching')),
            'contractor'  => array('q' => 'What is your estimated project budget?', 'opts' => array('$5k-$15k', '$15k-$50k', '$50k+')),
            'accounting'  => array('q' => 'What is your annual business revenue?', 'opts' => array('< $250k', '$250k-$1M', '$1M+')),
        );
        $data = $questions[$niche] ?? array('q' => 'What is your primary goal?', 'opts' => array('Rapid Growth', 'Process Automation', 'Lead Generation'));

        $opts_html = '';
        foreach($data['opts'] as $o) $opts_html .= '<button class="gp-btn" style="margin-bottom:15px; width:100%; border-radius:15px; text-transform:none;" onclick="nextStep(\''.esc_js($o).'\')">'.esc_html($o).'</button>';

        return '<div class="gp-quiz-container glass-card" style="padding:60px;">
            <div class="gp-quiz-progress" style="height:6px; background:#F1F5F9; border-radius:10px; margin-bottom:40px; overflow:hidden;"><div class="gp-quiz-progress-fill" style="width:33%; height:100%; background:var(--primary); transition:width 0.5s ease;"></div></div>
            <h3 class="text-gradient" style="margin-bottom:30px;">'.ucwords($niche).' OS Qualification</h3>
            <div id="gp-quiz-step-1">
                <p style="font-size:20px; font-weight:700; margin-bottom:40px;">'.esc_html($data['q']).'</p>
                <div style="display:flex; flex-direction:column;">'.$opts_html.'</div>
            </div>
            <div id="gp-quiz-form" style="display:none;">'.$this->render_lead_form().'</div>
        </div>';
    }

    public function handle_lead_submission() {
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'gp_lead_nonce' ) ) {
            wp_send_json_error( 'Security failed.' );
        }
        $name  = isset( $_POST['lead_name'] ) ? sanitize_text_field( $_POST['lead_name'] ) : '';
        $email = isset( $_POST['lead_email'] ) ? sanitize_email( $_POST['lead_email'] ) : '';
        $msg   = isset( $_POST['lead_msg'] ) ? sanitize_textarea_field( $_POST['lead_msg'] ) : '';

        if ( empty( $name ) || empty( $email ) ) {
            wp_send_json_error( 'Required fields missing.' );
        }

        $ai = GrowthPress_AI::get_instance();
        if ( $ai->is_spam($msg, $name, $email) ) wp_send_json_error("Flagged as spam.");
        $lead_id = wp_insert_post(array( 'post_title' => $name, 'post_content' => $msg, 'post_type' => 'gp_lead', 'post_status' => 'publish' ));
        update_post_meta($lead_id, '_lead_email', $email);
        wp_set_object_terms($lead_id, 'new', 'gp_lead_stage');
        do_action('gp_lead_captured', $lead_id);
        wp_send_json_success("Sequence initiated. AI Triage in progress.");
    }

    public function trigger_lead_automations( $lead_id ) {
        wp_schedule_single_event( time(), 'gp_async_lead_analysis', array($lead_id) );
    }

    public function process_async_analysis( $lead_id ) {
        $ai = GrowthPress_AI::get_instance();
        $lead = get_post($lead_id);
        if ( ! $lead ) return;

        $analysis_raw = $ai->analyze_sentiment($lead->post_content);
        $analysis = json_decode($analysis_raw, true) ?: array('urgency' => 5);
        $prob = $ai->predict_deal_probability($lead_id);
        update_post_meta($lead_id, '_gp_ai_probability', $prob);
        update_post_meta($lead_id, '_gp_ai_sentiment_json', $analysis_raw);

        $tag_prompt = "Categorize lead: \"{$lead->post_content}\" as 'Residential', 'Commercial', or 'Enterprise'. Return ONE word.";
        $tag = $ai->call_ai($tag_prompt, "Classifier");
        if ( ! is_wp_error($tag) ) wp_set_object_terms($lead_id, trim($tag), 'gp_lead_tag');

        $action_plan = $ai->call_ai("3 sales steps for lead: \"{$lead->post_content}\"", "Strategist");
        if ( ! is_wp_error($action_plan) ) $this->create_task( "Action Plan: " . $lead->post_title, $action_plan, $lead_id );

        $closing = $ai->call_ai("3 closing tactics for: \"{$lead->post_content}\"", "Closer");
        if ( ! is_wp_error($closing) ) update_post_meta($lead_id, '_gp_ai_closing_tips', $closing);

        $discovery = $ai->call_ai("4 discovery questions for: \"{$lead->post_content}\"", "Qualifier");
        if ( ! is_wp_error($discovery) ) update_post_meta($lead_id, '_gp_ai_discovery_questions', $discovery);

        $suggested = $ai->call_ai("Personalized reply for: \"{$lead->post_content}\"", "Assistant");
        if ( ! is_wp_error($suggested) ) update_post_meta($lead_id, '_gp_ai_suggested_reply', $suggested);

        do_action('gp_niche_lead_analysis', $lead_id);
    }

    public function add_crm_meta_boxes() {
        add_meta_box( 'gp_lead_insights', '🧠 AI Strategic Intelligence', array( $this, 'render_insights_meta' ), 'gp_lead', 'normal', 'high' );
        add_meta_box( 'gp_lead_behavior', '📈 Behavioral Timeline', array( $this, 'render_behavior_meta' ), 'gp_lead', 'side', 'default' );
        add_meta_box( 'gp_lead_notes', 'Team Collaboration', array( $this, 'render_notes_meta' ), 'gp_lead', 'side', 'low' );
    }

    public function render_behavior_meta( $post ) {
        $log = get_post_meta($post->ID, '_behavior_log', true) ?: array();
        ?>
        <div class="gp-timeline" style="position:relative; padding-left:30px;">
            <div style="position:absolute; left:10px; top:0; bottom:0; width:2px; background:#E2E8F0;"></div>
            <?php if($log): foreach(array_reverse($log) as $item): ?>
                <div class="timeline-item" style="position:relative; margin-bottom:20px;">
                    <div style="position:absolute; left:-25px; top:3px; width:12px; height:12px; border-radius:50%; background:var(--primary); border:2px solid white; box-shadow:0 0 5px rgba(0,0,0,0.1);"></div>
                    <div style="font-size:12px; font-weight:800; color:var(--secondary);"><?php echo esc_html($item['page']); ?></div>
                    <div style="font-size:10px; opacity:0.5; font-weight:600;"><?php echo date('M j, H:i', strtotime($item['time'])); ?></div>
                </div>
            <?php endforeach; else: echo "<p style='font-size:11px; opacity:0.5;'>Tracking visitor movements...</p>"; endif; ?>
            <div class="timeline-item" style="position:relative;">
                <div style="position:absolute; left:-25px; top:3px; width:12px; height:12px; border-radius:50%; background:#10B981; border:2px solid white;"></div>
                <div style="font-size:12px; font-weight:800; color:#10B981;">LEAD CAPTURED</div>
                <div style="font-size:10px; opacity:0.5; font-weight:600;"><?php echo get_the_date('M j, H:i', $post->ID); ?></div>
            </div>
        </div>
        <?php
    }

    public function render_insights_meta( $post ) {
        $prob = get_post_meta($post->ID, '_gp_ai_probability', true) ?: 50;
        $closing = get_post_meta($post->ID, '_gp_ai_closing_tips', true);
        $discovery = get_post_meta($post->ID, '_gp_ai_discovery_questions', true);
        $suggested = get_post_meta($post->ID, '_gp_ai_suggested_reply', true);
        ?>
        <div style="display:grid; grid-template-columns: 1fr 2fr; gap:30px; padding:10px;">
            <div>
                <div style="background:#F0F9FF; padding:30px; border-radius:24px; text-align:center; border:1px solid #BAE6FD;">
                    <div style="font-size:48px; font-weight:950; color:#2563EB;"><?php echo $prob; ?>%</div>
                    <div style="font-size:11px; font-weight:900; opacity:0.6; text-transform:uppercase; letter-spacing:1px;">Probability</div>
                </div>
                <div style="margin-top:30px; background:#F8FAFC; padding:25px; border-radius:20px; border:1px solid #E2E8F0;">
                    <h4 style="margin-top:0; font-size:13px;">Closing Tactics</h4>
                    <div style="font-size:13px; line-height:1.6; opacity:0.8;"><?php echo nl2br(esc_html($closing)); ?></div>
                </div>
            </div>
            <div>
                <h4 style="margin-top:0;">AI Suggested Discovery Call Questions</h4>
                <div style="background:#FFFBEB; padding:25px; border-radius:20px; border:1px solid #FEF3C7; color:#92400E; font-size:14px; line-height:1.7; margin-bottom:30px;">
                    <?php echo nl2br(esc_html($discovery)); ?>
                </div>
                <h4>Draft Response</h4>
                <textarea id="gp-ai-reply" style="width:100%; height:200px; border-radius:15px; border:1px solid #E2E8F0; padding:20px; font-size:14px; background:#F0FDF4;"><?php echo esc_textarea($suggested); ?></textarea>
                <div style="margin-top:15px; display:flex; gap:10px;">
                    <button type="button" class="button button-primary" style="flex:1;" onclick="copyGPReply()">Copy Strategy</button>
                    <button type="button" class="button" style="flex:1;" onclick="window.location.href='mailto:<?php echo get_post_meta($post->ID, '_lead_email', true); ?>?body=' + encodeURIComponent(jQuery('#gp-ai-reply').val())">Send via Email</button>
                </div>
            </div>
        </div>
        <script>function copyGPReply() { var t = document.getElementById('gp-ai-reply'); t.select(); navigator.clipboard.writeText(t.value); alert('Strategy copied!'); }</script>
        <?php
    }

    public function render_notes_meta( $post ) {
        $notes = get_post_meta($post->ID, '_gp_internal_notes', true) ?: array();
        ?>
        <div id="gp-notes-list" style="max-height:250px; overflow-y:auto; margin-bottom:15px;">
            <?php foreach(array_reverse($notes) as $n): ?>
                <div style="background:#F1F5F9; padding:12px; border-radius:10px; margin-bottom:10px; font-size:12px;">
                    <strong><?php echo esc_html($n['user']); ?>:</strong> <?php echo esc_html($n['text']); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <textarea id="gp-new-note" style="width:100%; height:60px; font-size:12px;" placeholder="Add team note..."></textarea>
        <button type="button" class="button" style="width:100%; margin-top:5px;" onclick="addGPNote(<?php echo $post->ID; ?>)">Post Update</button>
        <script>function addGPNote(id) { var t = jQuery('#gp-new-note').val(); if(!t) return; jQuery.post(ajaxurl, {action:'gp_add_lead_note', lead_id:id, note:t, gp_nonce:'<?php echo wp_create_nonce("gp_admin_nonce"); ?>'}, function(){location.reload();}); }</script>
        <?php
    }

    public function handle_add_note() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        if ( ! isset( $_POST['lead_id'] ) || ! isset( $_POST['note'] ) ) {
            wp_send_json_error( 'Missing parameters' );
        }

        $lead_id = intval( $_POST['lead_id'] );
        $notes   = get_post_meta( $lead_id, '_gp_internal_notes', true ) ?: array();
        $notes[] = array(
            'user' => wp_get_current_user()->display_name,
            'time' => current_time( 'mysql' ),
            'text' => sanitize_textarea_field( $_POST['note'] )
        );
        update_post_meta( $lead_id, '_gp_internal_notes', $notes );
        wp_send_json_success();
    }

    public function handle_behavior_logging() {
        if ( ! isset( $_POST['email'] ) || ! isset( $_POST['page'] ) ) {
            wp_send_json_error( 'Missing parameters' );
        }

        $leads = get_posts( array(
            'post_type'  => 'gp_lead',
            'meta_key'   => '_lead_email',
            'meta_value' => sanitize_email( $_POST['email'] ),
            'number'     => 1
        ) );

        if ( ! empty( $leads ) ) {
            $log   = get_post_meta( $leads[0]->ID, '_behavior_log', true ) ?: array();
            $log[] = array(
                'page' => sanitize_text_field( $_POST['page'] ),
                'time' => current_time( 'mysql' )
            );
            update_post_meta( $leads[0]->ID, '_behavior_log', array_slice( $log, - 15 ) );
        }
        wp_send_json_success();
    }

    public function create_task( $title, $desc = '', $lead_id = 0 ) {
        $task_id = wp_insert_post( array( 'post_title' => $title, 'post_content' => $desc, 'post_type' => 'gp_task', 'post_status' => 'publish' ) );
        if ( $lead_id ) update_post_meta( $task_id, '_related_lead', $lead_id );
        return $task_id;
    }

    public function handle_lead_export() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        header('Content-Type: text/csv'); header('Content-Disposition: attachment; filename="gp_leads.csv"');
        $output = fopen('php://output', 'w'); fputcsv($output, array('Name', 'Email', 'Date'));
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'posts_per_page' => -1 ) );
        foreach($leads as $l) fputcsv($output, array($l->post_title, get_post_meta($l->ID, '_lead_email', true), $l->post_date));
        fclose($output); exit;
    }

    public function handle_abandoned_inquiry_followup() {
        $new_leads = get_posts( array( 'post_type' => 'gp_lead', 'posts_per_page' => 20, 'tax_query' => array( array( 'taxonomy' => 'gp_lead_stage', 'field' => 'slug', 'terms' => 'new' ) ), 'date_query' => array( array( 'before' => '24 hours ago' ) ) ) );
        foreach ( $new_leads as $lead ) {
            if ( get_post_meta( $lead->ID, '_followup_sent', true ) ) continue;
            update_post_meta( $lead->ID, '_followup_sent', 'true' );
        }
    }
}
GrowthPress_CRM::get_instance();
