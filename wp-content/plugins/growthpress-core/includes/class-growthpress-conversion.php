<?php
/**
 * GrowthPress Conversion & CRO Engine
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

        return '<div class="gp-urgency-banner" style="background:linear-gradient(90deg, #2563EB, #1D4ED8); color:white; padding:12px; text-align:center; font-weight:700; font-size:14px; position:relative; overflow:hidden;">
            <div class="gp-pulse-icon" style="display:inline-block; width:8px; height:8px; background:#10B981; border-radius:50%; margin-right:8px; box-shadow: 0 0 0 rgba(16, 185, 129, 0.4); animation: gp-pulse 2s infinite;"></div>
            ' . esc_html($msg) . '
            <style>
                @keyframes gp-pulse {
                    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
                    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
                    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
                }
            </style>
        </div>';
    }

    public function render_review_feed() {
        $reputation = new GrowthPress_Reputation();
        $reviews = $reputation->get_top_reviews();

        ob_start(); ?>
        <div class="gp-review-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:20px;">
            <?php if($reviews): foreach($reviews as $r): ?>
                <div class="glass-card" style="padding:25px;">
                    <div style="color:#F59E0B; margin-bottom:10px;">★★★★★</div>
                    <p style="font-style:italic; font-size:14px; color:#475569; line-height:1.6;">"<?php echo esc_html($r->post_content); ?>"</p>
                    <div style="margin-top:15px; font-weight:bold; font-size:13px;">— <?php echo esc_html($r->post_title); ?></div>
                    <div style="font-size:11px; color:#94A3B8;">Verified Client</div>
                </div>
            <?php endforeach; else: echo "Social proof loading..."; endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_location_switcher() {
        $locations = get_option('gp_locations', array('Primary HQ'));
        ob_start(); ?>
        <div class="gp-location-switcher glass-card">
            <h4 style="margin-top:0;">Serving Multiple Locations</h4>
            <select style="width:100%;" onchange="window.location.search = '?location=' + this.value">
                <?php foreach($locations as $loc): ?>
                    <option value="<?php echo esc_attr(sanitize_title($loc)); ?>"><?php echo esc_html($loc); ?></option>
                <?php endforeach; ?>
            </select>
            <p class="description" style="font-size:11px; margin-top:8px;">AI automatically routes your inquiry to the nearest branch.</p>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_trust_badges() {
        return '<div class="gp-trust-badges" style="display:flex; justify-content:center; gap:30px; opacity:0.6; filter:grayscale(1); margin:40px 0;">
            <div style="font-weight:900; font-size:20px;">FORBES</div>
            <div style="font-weight:900; font-size:20px;">CLUTCH</div>
            <div style="font-weight:900; font-size:20px;">UPCITY</div>
            <div style="font-weight:900; font-size:20px;">BBB A+</div>
        </div>';
    }
}
new GrowthPress_Conversion();
