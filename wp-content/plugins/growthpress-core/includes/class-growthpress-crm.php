<?php
/**
 * GrowthPress CRM Core Class - AI Insights Enhanced
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
        add_action( 'add_meta_boxes', array( $this, 'add_lead_meta_boxes' ) );
        add_action( 'gp_lead_captured', array( $this, 'trigger_lead_automations' ) );
    }

    public function register_cpts() {
        register_post_type( 'gp_lead', array(
            'labels'      => array( 'name' => 'Leads', 'singular_name' => 'Lead' ),
            'public'      => false, 'show_ui' => true, 'menu_icon' => 'dashicons-groups',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
    }

    public function add_lead_meta_boxes() {
        add_meta_box( 'gp_ai_sales_insights', '🧠 AI Sales Intelligence', array( $this, 'render_ai_insights' ), 'gp_lead', 'normal', 'high' );
    }

    public function render_ai_insights( $post ) {
        $ai = GrowthPress_AI::get_instance();
        $prob = $ai->predict_deal_probability($post->ID);
        $tactics = $ai->suggest_closing_tactics($post->ID);
        $history = get_post_meta($post->ID, '_behavior_history', true) ?: '[]';
        ?>
        <div class="gp-insights-box">
            <div style="display:flex; gap:20px; margin-bottom:20px;">
                <div class="glass-card" style="flex:1; text-align:center;">
                    <div style="font-size:12px; color:#666;">Closing Probability</div>
                    <div style="font-size:32px; font-weight:bold; color:#10B981;"><?php echo $prob; ?>%</div>
                </div>
                <div class="glass-card" style="flex:2;">
                    <h4>Suggested Closing Tactics</h4>
                    <pre style="white-space:pre-wrap; font-size:12px;"><?php echo esc_html($tactics); ?></pre>
                </div>
            </div>
            <h4>Prospect Behavior History</h4>
            <div style="background:#f8fafc; padding:10px; border-radius:8px; font-size:11px; max-height:100px; overflow-y:auto;">
                <?php foreach(json_decode($history, true) as $h) echo "<div>Viewed: {$h['url']} at " . date('Y-m-d H:i', $h['time']/1000) . "</div>"; ?>
            </div>
        </div>
        <?php
    }

    public function trigger_lead_automations($id) {}
}
GrowthPress_CRM::get_instance();
