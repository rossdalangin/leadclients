<?php
/**
 * GrowthPress Law Firm Module - Meta Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Law {

    public function __construct() {
        add_action( 'init', array( $this, 'register_law_cpts' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_law_meta_boxes' ) );
        add_action( 'save_post_gp_legal_case', array( $this, 'save_law_meta' ) );
    }

    public function register_law_cpts() {
        register_post_type( 'gp_legal_case', array(
            'labels'      => array( 'name' => 'Legal Cases', 'singular_name' => 'Case' ),
            'public'      => false, 'show_ui' => true, 'menu_icon' => 'dashicons-hammer',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
    }

    public function add_law_meta_boxes() {
        add_meta_box( 'gp_case_status_box', 'Case Lifecycle & Status', array( $this, 'render_case_meta' ), 'gp_legal_case', 'side', 'high' );
    }

    public function render_case_meta( $post ) {
        $status = get_post_meta($post->ID, '_gp_case_status', true);
        ?>
        <div class="gp-meta-field">
            <label>Current Case Status</label>
            <select name="gp_case_status" style="width:100%;">
                <option value="discovery" <?php selected($status, 'discovery'); ?>>Discovery Phase</option>
                <option value="litigation" <?php selected($status, 'litigation'); ?>>In Litigation</option>
                <option value="settled" <?php selected($status, 'settled'); ?>>Settled / Closed</option>
            </select>
            <p class="description">Example: Update to 'Litigation' once a court date is set. This appears in the Client Portal.</p>
        </div>
        <?php
    }

    public function save_law_meta( $id ) {
        if ( isset($_POST['gp_case_status']) ) update_post_meta($id, '_gp_case_status', sanitize_text_field($_POST['gp_case_status']));
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title' => 'Personal Injury: Case #102', 'post_type' => 'gp_legal_case', 'post_status' => 'publish'));
    }
}
new GrowthPress_Law();
