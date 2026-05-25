<?php
/**
 * GrowthPress Multi-Location Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Locations {

    public function __construct() {
        add_action( 'init', array( $this, 'register_location_cpt' ) );
        add_action( 'gp_lead_captured', array( $this, 'route_lead_by_location' ) );
        add_shortcode( 'gp_location_switcher', array( $this, 'render_location_switcher' ) );
    }

    public function register_location_cpt() {
        register_post_type( 'gp_location', array(
            'labels'      => array( 'name' => 'Locations', 'singular_name' => 'Location' ),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-location',
            'supports'    => array( 'title', 'editor', 'custom-fields' ),
        ) );
    }

    public function get_locations() {
        return get_posts( array( 'post_type' => 'gp_location', 'posts_per_page' => -1 ) );
    }

    public function render_location_switcher() {
        $locations = $this->get_locations();
        if ( empty($locations) ) return '';

        ob_start(); ?>
        <div class="gp-location-selector glass-card">
            <select onchange="window.location.href=this.value">
                <option>Select Nearest Location...</option>
                <?php foreach($locations as $loc): ?>
                    <option value="<?php echo get_permalink($loc->ID); ?>"><?php echo esc_html($loc->post_title); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
        return ob_get_clean();
    }

    public function route_lead_by_location( $lead_id ) {
        $zip = get_post_meta( $lead_id, '_lead_zip', true );
        if ( ! $zip ) return;

        $locations = $this->get_locations();
        foreach ( $locations as $loc ) {
            $serviced_zips = get_post_meta( $loc->ID, '_serviced_zips', true );
            if ( strpos( $serviced_zips, $zip ) !== false ) {
                update_post_meta( $lead_id, '_assigned_location', $loc->ID );
                GrowthPress_Activity::log( "Lead #$lead_id routed to location: " . $loc->post_title );
                break;
            }
        }
    }
}

new GrowthPress_Locations();
