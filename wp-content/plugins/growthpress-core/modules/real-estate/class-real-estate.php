<?php
/**
 * GrowthPress Real Estate Module - Help Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_RealEstate {

    public function __construct() {
        add_action( 'init', array( $this, 'register_property_cpt' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_property_meta_boxes' ) );
        add_action( 'admin_head', array( $this, 'add_property_help_tabs' ) );
        add_action( 'wp_ajax_gp_property_match', array( $this, 'handle_property_match' ) );
        add_action( 'wp_ajax_nopriv_gp_property_match', array( $this, 'handle_property_match' ) );
    }

    public function register_property_cpt() {
        register_post_type( 'gp_property', array(
            'labels'      => array( 'name' => 'Properties', 'singular_name' => 'Property' ),
            'public'      => true, 'show_ui' => true, 'menu_icon' => 'dashicons-admin-home',
            'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        ) );
    }

    public function add_property_meta_boxes() {
        add_meta_box( 'gp_prop_config', 'AI Asset Configuration', array( $this, 'render_prop_meta' ), 'gp_property', 'side' );
    }

    public function render_prop_meta( $post ) {
        ?>
        <div class="gp-meta-field">
            <label>Virtual Tour (Matterport/YouTube)</label>
            <input type="url" name="gp_virtual_tour" style="width:100%;" placeholder="https://...">
            <p class="description">Example: https://my.matterport.com/show/?m=XXXXXXXXX</p>
        </div>
        <?php
    }

    public function add_property_help_tabs() {
        $screen = get_current_screen();
        if ( ! $screen || $screen->post_type !== 'gp_property' ) return;

        $screen->add_help_tab( array(
            'id'      => 'gp_re_ai',
            'title'   => 'AI Matchmaking',
            'content' => '<p>The AI Matchmaker scans your property descriptions and meta data to suggest listings to leads based on their specific lifestyle intent.</p>',
        ) );
    }

    public function handle_property_match() {
        $lead_id = intval($_POST['lead_id'] ?? 0);
        if ( ! $lead_id ) wp_send_json_error('Invalid Lead');

        $result = $this->suggest_properties_for_lead($lead_id);
        wp_send_json_success($result);
    }

    public function suggest_properties_for_lead( $lead_id ) {
        $lead = get_post($lead_id);
        $all_props = get_posts(array('post_type' => 'gp_property', 'posts_per_page' => 10));

        $prop_list = '';
        foreach($all_props as $p) $prop_list .= "- {$p->post_title}: {$p->post_excerpt}\n";

        $ai = GrowthPress_AI::get_instance();
        $prompt = "Based on this lead inquiry: \"{$lead->post_content}\", which of these properties are the best match? \n$prop_list\n Return the top 2 property names and why.";

        return $ai->call_ai($prompt, "Real Estate Matchmaker");
    }

    public function generate_sample_data() {
        $props = array(
            'Modern Penthouse' => 'Luxury living in the heart of downtown.',
            'Suburban Family Estate' => 'Spacious 5-bedroom home with large backyard.',
            'Oceanfront Villa' => 'Direct beach access and panoramic views.'
        );
        foreach($props as $title => $desc) {
            if ( ! get_page_by_path( sanitize_title($title), OBJECT, 'gp_property' ) ) {
                wp_insert_post(array(
                    'post_title'   => $title,
                    'post_content' => $desc,
                    'post_type'    => 'gp_property',
                    'post_status'  => 'publish'
                ));
            }
        }
    }
}
new GrowthPress_RealEstate();
