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
        add_shortcode( 'gp_legal_intake', array( $this, 'render_legal_intake' ) );
        add_action( 'wp_ajax_gp_legal_triage', array( $this, 'handle_legal_triage' ) );
        add_action( 'wp_ajax_nopriv_gp_legal_triage', array( $this, 'handle_legal_triage' ) );
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

    public function render_legal_intake() {
        $nonce = wp_create_nonce('gp_legal_nonce');
        return '
        <div class="gp-legal-intake glass-card">
            <h3>Secure Legal Intake</h3>
            <p>Describe your situation for a preliminary AI assessment.</p>
            <textarea id="legal-inquiry" placeholder="Describe your legal matter..."></textarea>
            <input type="hidden" id="legal-nonce" value="' . $nonce . '">
            <button onclick="runLegalTriage()" class="button">Start Secure Assessment</button>
            <div id="legal-ai-result" style="margin-top:15px; font-size:14px;"></div>
        </div>
        <script>
        function runLegalTriage() {
            var inquiry = jQuery("#legal-inquiry").val();
            var out = jQuery("#legal-ai-result");
            out.html("AI Legal Assistant is analyzing...");
            jQuery.post(gp_ajax.ajaxurl, {
                action: "gp_legal_triage",
                inquiry: inquiry,
                nonce: jQuery("#legal-nonce").val()
            }, function(res) {
                if(res.success) out.html(res.data);
            });
        }
        </script>';
    }

    public function handle_legal_triage() {
        check_ajax_referer( 'gp_legal_nonce', 'nonce' );
        $inquiry = sanitize_textarea_field($_POST['inquiry']);
        $ai = GrowthPress_AI::get_instance();
        $result = $ai->get_legal_triage($inquiry);
        wp_send_json_success($result);
    }

    public function generate_sample_data() {
        $cases = array(
            'Personal Injury: Case #102' => 'Ongoing litigation for motor vehicle accident.',
            'Corporate Merger: Project Alpha' => 'Drafting master service agreements and equity structures.',
            'Estate Planning: Miller Family' => 'Setting up living trusts and healthcare directives.'
        );
        foreach($cases as $title => $desc) {
            $id = wp_insert_post(array(
                'post_title'   => $title,
                'post_content' => $desc,
                'post_type'    => 'gp_legal_case',
                'post_status'  => 'publish'
            ));
            update_post_meta($id, '_gp_case_status', 'discovery');
        }
    }
}
new GrowthPress_Law();
