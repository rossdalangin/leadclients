<?php
/**
 * GrowthPress Accounting Module - Intelligence Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Accounting {

    public function __construct() {
        add_shortcode( 'gp_tax_estimator', array( $this, 'render_tax_estimator' ) );
        add_action( 'gp_client_portal_dashboard', array( $this, 'render_tax_docs' ) );
    }

    public function render_tax_estimator() {
        ob_start(); ?>
        <div class="gp-tax-calc glass-card">
            <h3>Small Business Tax Estimator</h3>
            <input type="number" id="gp-revenue" placeholder="Annual Revenue ($)">
            <input type="number" id="gp-expenses" placeholder="Annual Expenses ($)">
            <button onclick="runTaxCalc()">Estimate Liability</button>
            <div id="tax-result"></div>
        </div>
        <script>
        function runTaxCalc() {
            var rev = jQuery('#gp-revenue').val();
            var exp = jQuery('#gp-expenses').val();
            var profit = rev - exp;
            var tax = profit * 0.25; // 25% mock rate
            if(profit > 0) jQuery('#tax-result').html('Estimated Liability: $' + tax.toLocaleString());
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function render_tax_docs() {
        ?>
        <div class="gp-doc-upload glass-card" style="margin-top:20px;">
            <h4>Upload Financial Documents</h4>
            <p class="description">Upload your W2s, 1099s, and Expense sheets securely.</p>
            <input type="file" multiple>
        </div>
        <?php
    }

    public function generate_sample_data() {
        wp_insert_post(array('post_title'=>'Tax Strategy Session','post_type'=>'page','post_status'=>'publish'));
    }
}
new GrowthPress_Accounting();
