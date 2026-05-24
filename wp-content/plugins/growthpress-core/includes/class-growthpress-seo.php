<?php
/**
 * GrowthPress SEO & Schema Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_SEO {

    public function __construct() {
        add_action( 'wp_head', array( $this, 'inject_schema' ) );
    }

    public function inject_schema() {
        $schema = $this->generate_schema();
        if ( ! empty( $schema ) ) {
            echo '<script type="application/ld+json">' . json_encode( $schema ) . '</script>';
        }
    }

    private function generate_schema() {
        $niche = get_option( 'growthpress_niche', 'ProfessionalService' );

        $type_map = array(
            'dental'      => 'Dentist',
            'medical'     => 'MedicalClinic',
            'law'         => 'LegalService',
            'contractor'  => 'HomeAndConstructionBusiness',
            'real-estate' => 'RealEstateAgent'
        );

        $schema_type = $type_map[$niche] ?? 'LocalBusiness';

        return array(
            '@context' => 'https://schema.org',
            '@type'    => $schema_type,
            'name'     => get_bloginfo( 'name' ),
            'url'      => get_home_url(),
            'description' => get_bloginfo( 'description' ),
        );
    }
}

new GrowthPress_SEO();
