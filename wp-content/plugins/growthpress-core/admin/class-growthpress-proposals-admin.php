<?php
/**
 * GrowthPress Proposals Admin Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Proposals_Admin {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_proposals_menu' ) );
    }

    public function add_proposals_menu() {
        add_submenu_page(
            'growthpress-dashboard',
            'Business Proposals',
            'Proposals',
            'manage_options',
            'growthpress-proposals',
            array( $this, 'render_proposals_page' )
        );
    }

    public function render_proposals_page() {
        $proposals = get_posts(array('post_type' => 'gp_proposal', 'posts_per_page' => -1));
        ?>
        <div class="wrap">
            <h1>Active Business Proposals</h1>
            <p class="description">Manage and track your high-ticket service quotes.</p>

            <div class="glass-card" style="margin-top:20px;">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Proposal Title</th>
                            <th>Related Lead</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($proposals as $p):
                            $status = get_post_meta($p->ID, '_gp_proposal_status', true) ?: 'Sent';
                            $lead_id = get_post_meta($p->ID, '_related_lead', true); ?>
                            <tr>
                                <td><strong><?php echo esc_html($p->post_title); ?></strong></td>
                                <td><?php echo get_the_title($lead_id); ?></td>
                                <td><span class="status-badge"><?php echo $status; ?></span></td>
                                <td><a href="<?php echo get_edit_post_link($p->ID); ?>" class="button button-small">Edit Content</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <style>.status-badge { background:#e2e8f0; padding:2px 8px; border-radius:10px; font-size:11px; }</style>
        <?php
    }
}

new GrowthPress_Proposals_Admin();
