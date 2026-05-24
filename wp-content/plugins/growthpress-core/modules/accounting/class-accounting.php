<?php
/**
 * GrowthPress Accounting Module
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Accounting {

    public function __construct() {
        add_action( 'gp_client_portal_dashboard', array( $this, 'render_tax_docs' ) );
    }

    /**
     * Render Tax Document Upload in Client Portal
     */
    public function render_tax_docs() {
        ?>
        <div class="gp-doc-upload glass-card">
            <h4>Upload Financial Documents</h4>
            <input type="file" multiple>
        </div>
        <?php
    }
}

new GrowthPress_Accounting();
