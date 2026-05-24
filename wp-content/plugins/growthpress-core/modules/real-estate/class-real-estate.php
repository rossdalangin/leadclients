<?php
/**
 * GrowthPress Real Estate Module - Admin UI Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_RealEstate {

    public function __construct() {
        add_action( 'init', array( $this, 'register_property_cpt' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_property_meta_boxes' ) );
        add_action( 'save_post_gp_property', array( $this, 'save_property_meta' ) );
        add_action( 'wp_ajax_gp_property_match', array( $this, 'handle_property_match' ) );
    }

    public function register_property_cpt() {
        register_post_type( 'gp_property', array(
            'labels'      => array( 'name' => 'Properties', 'singular_name' => 'Property' ),
            'public'      => true, 'show_ui' => true, 'menu_icon' => 'dashicons-admin-home',
            'supports'    => array( 'title', 'editor', 'thumbnail' ),
        ) );
    }

    public function add_property_meta_boxes() {
        add_meta_box( 'gp_property_details', 'Property Configuration & AI Matchmaking', array( $this, 'render_property_details' ), 'gp_property', 'normal', 'high' );
    }

    public function render_property_details( $post ) {
        $price = get_post_meta($post->ID, '_gp_property_price', true);
        $tour = get_post_meta($post->ID, '_gp_virtual_tour', true);
        ?>
        <div class="gp-meta-box">
            <p class="description">Configure the property details used for AI matching and frontend display.</p>
            <table class="form-table">
                <tr>
                    <th><label>Listing Price ($)</label></th>
                    <td>
                        <input type="number" name="gp_property_price" value="<?php echo esc_attr($price); ?>" class="regular-text" placeholder="e.g. 450000">
                        <p class="help-text" style="font-size:11px; color:#666;">Example: Enter 500000 for a $500k listing.</p>
                    </td>
                </tr>
                <tr>
                    <th><label>Virtual Tour URL</label></th>
                    <td>
                        <input type="url" name="gp_virtual_tour" value="<?php echo esc_attr($tour); ?>" class="large-text" placeholder="https://my.matterport.com/show/?m=...">
                        <p class="help-text" style="font-size:11px; color:#666;">Note: Supports Matterport, YouTube, or Vimeo embeds.</p>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }

    public function save_property_meta( $post_id ) {
        if ( isset($_POST['gp_property_price']) ) update_post_meta($post_id, '_gp_property_price', sanitize_text_field($_POST['gp_property_price']));
        if ( isset($_POST['gp_virtual_tour']) ) update_post_meta($post_id, '_gp_virtual_tour', esc_url_raw($_POST['gp_virtual_tour']));
    }

    public function handle_property_match() {
        // AI matching logic...
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Sunset Hills Estate', 'post_type' => 'gp_property', 'post_status' => 'publish'));
    }
}
new GrowthPress_RealEstate();
