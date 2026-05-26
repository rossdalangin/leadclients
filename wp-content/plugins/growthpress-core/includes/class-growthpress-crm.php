<?php
/**
 * GrowthPress CRM Core Class - Final Advanced
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
            'supports' => array( 'title', 'editor' ),
            'menu_icon' => 'dashicons-yes'
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
            <input type="text" name="lead_name" placeholder="Full Name" required>
            <input type="email" name="lead_email" placeholder="Email Address" required>
            <textarea name="lead_msg" placeholder="Tell us about your needs..."></textarea>
            <button type="submit" class="button button-primary">Scale My Business</button>
        </form>';
    }

    public function render_quiz_form() {
        return '<div class="gp-quiz-container glass-card">
            <h3>Quick Qualification Quiz</h3>
            <div id="gp-quiz-step-1">
                <p>What is your current monthly revenue?</p>
                <button onclick="nextStep(1)">$0 - $10k</button>
                <button onclick="nextStep(2)">$10k - $50k</button>
                <button onclick="nextStep(3)">$50k+</button>
            </div>
            <div id="gp-quiz-form" style="display:none;">
                ' . $this->render_lead_form() . '
            </div>
        </div>';
    }

    public function handle_lead_submission() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'gp_lead_nonce' ) ) {
            wp_send_json_error('Security check failed.');
        }

        $name = sanitize_text_field($_POST['lead_name']);
        $email = sanitize_email($_POST['lead_email']);
        $msg = sanitize_textarea_field($_POST['lead_msg']);

        $ai = GrowthPress_AI::get_instance();
        if ( $ai->is_spam($msg, $name, $email) ) {
            wp_send_json_error("Inquiry flagged as spam. Please try again with valid information.");
        }

        $lead_id = wp_insert_post(array(
            'post_title' => $name,
            'post_content' => $msg,
            'post_type' => 'gp_lead',
            'post_status' => 'publish'
        ));

        update_post_meta($lead_id, '_lead_email', $email);
        wp_set_object_terms($lead_id, 'new', 'gp_lead_stage');

        do_action('gp_lead_captured', $lead_id);
        wp_send_json_success("Lead captured! We will contact you soon.");
    }

    public function trigger_lead_automations( $lead_id ) {
        // Offload to async event to prevent frontend blocking
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

        // Lead Intent Scoring
        $intent_score = ($prob > 80) ? 'High' : ($prob > 40 ? 'Medium' : 'Low');
        update_post_meta($lead_id, '_gp_lead_intent_score', $intent_score);

        // Auto-tagging based on content analysis
        $tag_prompt = "Categorize this lead inquiry: \"{$lead->post_content}\" as either 'Residential', 'Commercial', or 'Enterprise'. Return ONLY the word.";
        $tag = $ai->call_ai($tag_prompt, "Lead Classifier");
        if ( ! is_wp_error($tag) && in_array(trim($tag), array('Residential', 'Commercial', 'Enterprise')) ) {
            wp_set_object_terms($lead_id, trim($tag), 'gp_lead_tag');
        }

        if ( isset($analysis['urgency']) && $analysis['urgency'] >= 9 ) {
            $staff = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
            if ( ! empty($staff) ) {
                update_post_meta( $lead_id, '_assigned_staff', $staff[0]->ID );
                GrowthPress_Activity::log( "URGENT LEAD #$lead_id routed to " . $staff[0]->display_name );
            }
        }
    }

    public function add_crm_meta_boxes() {
        add_meta_box( 'gp_lead_insights', 'AI Sales Insights', array( $this, 'render_insights_meta' ), 'gp_lead', 'normal', 'high' );
        add_meta_box( 'gp_lead_tasks', 'Related Tasks & Notes', array( $this, 'render_tasks_meta' ), 'gp_lead', 'normal', 'default' );
        add_meta_box( 'gp_lead_behavior', 'Lead Behavioral Log', array( $this, 'render_behavior_meta' ), 'gp_lead', 'side' );
    }

    public function render_tasks_meta( $post ) {
        $tasks = get_posts( array( 'post_type' => 'gp_task', 'meta_key' => '_related_lead', 'meta_value' => $post->ID, 'posts_per_page' => -1 ) );
        ?>
        <div class="gp-tasks-meta">
            <?php if($tasks): foreach($tasks as $t): ?>
                <div style="padding:10px; border:1px solid #ddd; border-radius:6px; margin-bottom:10px; background:#fff;">
                    <strong><?php echo esc_html($t->post_title); ?></strong>
                    <p style="margin:5px 0 0; font-size:12px;"><?php echo esc_html($t->post_content); ?></p>
                </div>
            <?php endforeach; else: echo "No tasks assigned to this lead."; endif; ?>
            <hr>
            <p><a href="<?php echo admin_url('post-new.php?post_type=gp_task'); ?>" class="button">Create New Task</a></p>
        </div>
        <?php
    }

    public function render_insights_meta( $post ) {
        $prob = get_post_meta($post->ID, '_gp_ai_probability', true) ?: 50;
        $intent = get_post_meta($post->ID, '_gp_lead_intent_score', true) ?: 'Medium';
        $ai = GrowthPress_AI::get_instance();

        $closing_tips = $ai->call_ai("Provide 3 high-ticket closing tactics for this lead: \"{$post->post_content}\"", "Sales Closer");
        $suggested_reply = $ai->call_ai("Generate a professional, high-ticket personalized email reply for this lead inquiry: \"{$post->post_content}\". Mention their specific concern.", "Executive Assistant");
        ?>
        <div class="gp-insights-box">
            <div style="display:flex; align-items:center; gap:20px; margin-bottom:20px;">
                <div style="text-align:center; padding:15px; border-radius:12px; background:#f0f9ff; border:1px solid #bae6fd;">
                    <div style="font-size:24px; font-weight:800; color:#2563EB;"><?php echo $prob; ?>%</div>
                    <div style="font-size:10px; text-transform:uppercase; opacity:0.7;">Deal Probability</div>
                </div>
                <div style="text-align:center; padding:15px; border-radius:12px; background:#fefce8; border:1px solid #fef08a;">
                    <div style="font-size:24px; font-weight:800; color:#a16207;"><?php echo $intent; ?></div>
                    <div style="font-size:10px; text-transform:uppercase; opacity:0.7;">Lead Intent</div>
                </div>
            </div>
            <h4>AI Suggested Closing Strategy:</h4>
            <div style="background:#f8fafc; padding:15px; border-radius:8px; font-size:13px; margin-bottom:20px;"><?php echo nl2br(esc_html($closing_tips)); ?></div>

            <h4>Suggested AI Response:</h4>
            <div style="position:relative;">
                <textarea id="gp-ai-reply-text" style="width:100%; height:120px; font-size:12px; background:#f0f9ff; border:1px solid #bae6fd; padding:10px; border-radius:8px;"><?php echo esc_textarea($suggested_reply); ?></textarea>
                <button type="button" class="button button-small" onclick="copyReply()" style="margin-top:5px;">Copy to Clipboard</button>
            </div>
            <script>
            function copyReply() {
                var copyText = document.getElementById("gp-ai-reply-text");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(copyText.value);
                alert("Response copied!");
            }
            </script>
        </div>
        <?php
    }

    public function render_behavior_meta( $post ) {
        $log = get_post_meta($post->ID, '_behavior_log', true) ?: array();
        ?>
        <div class="gp-behavior-list">
            <?php if($log): foreach(array_reverse($log) as $item): ?>
                <div style="font-size:11px; margin-bottom:8px; border-bottom:1px solid #eee; padding-bottom:4px;">
                    <strong><?php echo esc_html($item['page']); ?></strong><br>
                    <span style="opacity:0.6;"><?php echo esc_html($item['time']); ?></span>
                </div>
            <?php endforeach; else: echo "No behavior tracked yet."; endif; ?>
        </div>
        <?php
    }

    public function handle_lead_export() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'posts_per_page' => -1 ) );

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="growthpress_leads_export.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, array('Lead Name', 'Email', 'Date', 'Stage'));

        foreach ( $leads as $l ) {
            $email = get_post_meta($l->ID, '_lead_email', true);
            $stage = wp_get_object_terms( $l->ID, 'gp_lead_stage', array('fields' => 'names') );
            fputcsv($output, array($l->post_title, $email, $l->post_date, implode(', ', $stage)));
        }
        fclose($output);
        exit;
    }

    public function handle_behavior_logging() {
        $email = sanitize_email($_POST['email']);
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'meta_key' => '_lead_email', 'meta_value' => $email, 'number' => 1 ) );
        if ( ! empty($leads) ) {
            $lead_id = $leads[0]->ID;
            $log = get_post_meta($lead_id, '_behavior_log', true) ?: array();
            $log[] = array('page' => sanitize_text_field($_POST['page']), 'time' => current_time('mysql'));
            update_post_meta($lead_id, '_behavior_log', array_slice($log, -10)); // Keep last 10
        }
        wp_send_json_success();
    }

    public function create_task( $title, $desc = '', $lead_id = 0 ) {
        $task_id = wp_insert_post( array(
            'post_title'   => $title,
            'post_content' => $desc,
            'post_type'    => 'gp_task',
            'post_status'  => 'publish'
        ) );
        if ( $lead_id ) {
            update_post_meta( $task_id, '_related_lead', $lead_id );
        }
        return $task_id;
    }

    public function handle_abandoned_inquiry_followup() {
        $new_leads = get_posts( array(
            'post_type'  => 'gp_lead',
            'posts_per_page' => 20,
            'tax_query' => array( array( 'taxonomy' => 'gp_lead_stage', 'field' => 'slug', 'terms' => 'new' ) ),
            'date_query' => array( array( 'before' => '24 hours ago' ) ),
        ) );

        foreach ( $new_leads as $lead ) {
            if ( get_post_meta( $lead->ID, '_followup_sent', true ) ) continue;

            $ai = GrowthPress_AI::get_instance();
            $niche = get_option('growthpress_niche', 'business');
            $msg = $ai->call_ai("Generate a short, friendly re-engagement message for a lead that hasn't responded in 24 hours for a $niche business.", "Sales Assistant");

            GrowthPress_Activity::log( "CRM Automation: Abandoned inquiry follow-up triggered for Lead #{$lead->ID}." );
            update_post_meta( $lead->ID, '_followup_sent', 'true' );
        }
    }
}
GrowthPress_CRM::get_instance();
