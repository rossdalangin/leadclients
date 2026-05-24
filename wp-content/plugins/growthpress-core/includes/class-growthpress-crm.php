<?php
/**
 * GrowthPress CRM Core Class - List UI Enhanced
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
        add_shortcode( 'gp_lead_form', array( $this, 'render_lead_form' ) );
        add_shortcode( 'gp_quiz_lead_form', array( $this, 'render_quiz_form' ) );
        add_action( 'wp_ajax_gp_submit_lead', array( $this, 'handle_lead_submission' ) );
        add_action( 'wp_ajax_nopriv_gp_submit_lead', array( $this, 'handle_lead_submission' ) );
        add_action( 'manage_gp_lead_posts_columns', array( $this, 'add_lead_columns' ) );
        add_action( 'manage_gp_lead_posts_custom_column', array( $this, 'render_lead_columns' ), 10, 2 );
    }

    public function register_cpts() {
        register_post_type( 'gp_lead', array(
            'labels'      => array( 'name' => 'Leads', 'singular_name' => 'Lead' ),
            'public'      => false, 'show_ui' => true, 'menu_icon' => 'dashicons-groups',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
        register_taxonomy( 'gp_lead_stage', 'gp_lead', array( 'hierarchical' => true, 'show_ui' => true ) );
        register_taxonomy( 'gp_lead_tag', 'gp_lead', array( 'hierarchical' => false, 'show_ui' => true ) );
    }

    public function add_lead_columns( $columns ) {
        $new_columns = array();
        foreach($columns as $key => $value) {
            $new_columns[$key] = $value;
            if($key === 'title') {
                $new_columns['gp_score'] = 'AI Score';
                $new_columns['gp_location'] = 'Assigned Location';
            }
        }
        return $new_columns;
    }

    public function render_lead_columns( $column, $post_id ) {
        if ( $column === 'gp_score' ) {
            $score = get_post_meta($post_id, '_gp_lead_score', true) ?: 0;
            echo "<span style='font-weight:bold; color:#2563EB;'>$score</span>";
        }
        if ( $column === 'gp_location' ) {
            $loc_id = get_post_meta($post_id, '_assigned_location', true);
            echo $loc_id ? get_the_title($loc_id) : '—';
        }
    }

    public function trigger_lead_automations($id) {}
    public function handle_lead_submission() {}
    public function render_lead_form() {}
    public function render_quiz_form() {}
}
GrowthPress_CRM::get_instance();
