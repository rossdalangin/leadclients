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
            <h4>Secure Financial Document Portal</h4>
            <p class="description">Upload your W2s, 1099s, and Expense sheets securely for review.</p>
            <div class="upload-zone" style="border: 2px dashed #cbd5e1; padding: 20px; text-align: center; border-radius: 12px; background: #f8fafc;">
                <input type="file" multiple id="gp-accounting-upload" style="display:none;">
                <label for="gp-accounting-upload" style="cursor:pointer; color: #2563EB; font-weight: bold;">Click to upload or drag and drop</label>
            </div>
            <div id="gp-uploaded-docs" style="margin-top:15px;">
                <ul style="list-style:none; padding:0; font-size:13px; color:#64748b;">
                    <li>📄 sample_invoice_2023.pdf (Pending Review)</li>
                </ul>
            </div>
        </div>
        <?php
    }

    public function generate_sample_data() {
        $services = array(
            'Tax Strategy Session' => 'Comprehensive planning to minimize tax liability.',
            'Fractional CFO Services' => 'Executive financial leadership for growing firms.',
            'Audit Representation' => 'Professional defense and guidance during tax audits.'
        );
        foreach($services as $t => $c) {
            wp_insert_post(array('post_title' => $t, 'post_content' => $c, 'post_type' => 'page', 'post_status' => 'publish'));
        }
    }
}
new GrowthPress_Accounting();
