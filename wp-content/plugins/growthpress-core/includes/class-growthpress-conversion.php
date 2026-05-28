<?php
/**
 * GrowthPress Conversion & CRO Engine - Elite v1.5.0
 * Implements scarcity, urgency, and social proof components.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Conversion {

    public function __construct() {
        add_shortcode( 'gp_urgency_banner', array( $this, 'render_urgency_banner' ) );
        add_shortcode( 'gp_review_feed', array( $this, 'render_review_feed' ) );
        add_shortcode( 'gp_location_switcher', array( $this, 'render_location_switcher' ) );
        add_shortcode( 'gp_trust_badges', array( $this, 'render_trust_badges' ) );
        add_shortcode( 'gp_stats_bar', array( $this, 'render_stats_bar' ) );
    }

    public function render_urgency_banner() {
        $niche = get_option('growthpress_niche', 'business');
        $messages = array(
            'dental'    => '🚨 2 Emergency appointments remaining for today. Call now!',
            'law'       => '⚖️ High-priority case slots available for ' . date('F') . '. Secure your consultation.',
            'solar'     => '☀️ Federal Tax Credit Alert: 30% Savings still active. Lock in your rate.',
            'contractor'=> '🔨 Spring booking schedule is 85% full. Get your estimate today.',
            'roofing'   => '🏠 Post-storm inspections prioritized this week. 4 slots left.',
            'medical'   => '🏥 Same-day telemedicine appointments available. Book now.',
            'real-estate'=> '🔑 3 New high-yield properties just hit the off-market list. Inquire now.'
        );
        $msg = $messages[$niche] ?? '🚀 Limited availability for new high-ticket strategy sessions this month.';

        return '<div class="gp-urgency-banner gp-reveal" style="background: var(--secondary); color: white; padding: 15px; text-align: center; font-weight: 800; font-size: 13px; position: relative; overflow: hidden; letter-spacing: 1px; text-transform: uppercase; border-bottom: 1px solid rgba(255,255,255,0.1);">
            <div class="gp-pulse-icon" style="display: inline-block; width: 10px; height: 10px; background: var(--accent); border-radius: 50%; margin-right: 12px; animation: gp-pulse 2s infinite;"></div>
            ' . esc_html($msg) . '
        </div>';
    }

    public function render_review_feed() {
        $reputation = new GrowthPress_Reputation();
        $reviews = $reputation->get_top_reviews();

        ob_start(); ?>
        <div class="gp-review-wall gp-reveal" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; margin-top: 50px;">
            <?php if($reviews): foreach($reviews as $r): ?>
                <div class="glass-card" style="padding: 40px; border-bottom: 6px solid var(--primary);">
                    <div style="display: flex; gap: 5px; color: #F59E0B; margin-bottom: 20px; font-size: 18px;">★★★★★</div>
                    <p style="font-style: italic; font-size: 16px; color: var(--text); line-height: 1.7; margin-bottom: 25px;">"<?php echo esc_html($r->post_content); ?>"</p>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 44px; height: 44px; background: var(--primary-glow); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 900; color: var(--primary); font-size: 14px;">
                            <?php echo substr($r->post_title, 0, 1); ?>
                        </div>
                        <div>
                            <div style="font-weight: 900; font-size: 15px; color: var(--secondary);"><?php echo esc_html($r->post_title); ?></div>
                            <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Verified Result</div>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: echo "Social proof loading..."; endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_location_switcher() {
        $locations = get_option('gp_locations', array('Global Headquarters', 'Regional Strategy Hub'));
        ob_start(); ?>
        <div class="gp-location-nav glass-card" style="padding: 40px; border-left: 8px solid var(--accent);">
            <h4 class="text-gradient" style="margin-top: 0; font-size: 20px;">Precision Service Coverage</h4>
            <p style="font-size: 14px; margin-bottom: 25px;">Select your nearest location for optimized AI routing.</p>
            <div style="position: relative;">
                <select style="width: 100%; height: 60px; padding: 0 20px; border-radius: 12px; font-weight: 700; appearance: none; border: 2px solid var(--border);" onchange="window.location.search = '?location=' + this.value">
                    <?php foreach($locations as $loc): ?>
                        <option value="<?php echo esc_attr(sanitize_title($loc)); ?>"><?php echo esc_html($loc); ?></option>
                    <?php endforeach; ?>
                </select>
                <div style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); pointer-events: none; opacity: 0.5;">▼</div>
            </div>
            <div style="margin-top: 20px; display: flex; align-items: center; gap: 10px; font-size: 11px; font-weight: 800; color: var(--accent);">
                <span style="width: 6px; height: 6px; background: var(--accent); border-radius: 50%;"></span>
                NEAREST AGENT RESPONDING IN < 4 MINS
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_trust_badges() {
        return '<div class="gp-trust-grid gp-reveal" style="display: flex; justify-content: center; align-items: center; gap: 60px; margin: 80px 0; flex-wrap: wrap;">
            <div class="trust-badge" style="font-weight: 950; font-size: 24px; letter-spacing: -1px; opacity: 0.3;">FORBES</div>
            <div class="trust-badge" style="font-weight: 950; font-size: 24px; letter-spacing: -1px; opacity: 0.3;">BLOOMBERG</div>
            <div class="trust-badge" style="font-weight: 950; font-size: 24px; letter-spacing: -1px; opacity: 0.3;">TECHCRUNCH</div>
            <div class="trust-badge" style="font-weight: 950; font-size: 24px; letter-spacing: -1px; opacity: 0.3;">WIRED</div>
            <div class="trust-badge" style="font-weight: 950; font-size: 24px; letter-spacing: -1px; opacity: 0.3;">INC.</div>
        </div>';
    }

    public function render_stats_bar() {
        return '<div class="gp-stats-bar glass-card gp-reveal" style="display: flex; justify-content: space-around; padding: 60px; text-align: center; margin-top: 80px;">
            <div>
                <div class="text-gradient" style="font-size: 3rem; font-weight: 950; line-height: 1;">$250M+</div>
                <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; margin-top: 10px; letter-spacing: 2px; opacity: 0.6;">Pipeline Managed</div>
            </div>
            <div>
                <div class="text-gradient" style="font-size: 3rem; font-weight: 950; line-height: 1;">14k+</div>
                <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; margin-top: 10px; letter-spacing: 2px; opacity: 0.6;">Inquiries Captured</div>
            </div>
            <div>
                <div class="text-gradient" style="font-size: 3rem; font-weight: 950; line-height: 1;">98%</div>
                <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; margin-top: 10px; letter-spacing: 2px; opacity: 0.6;">Client Retention</div>
            </div>
        </div>';
    }
}
new GrowthPress_Conversion();
