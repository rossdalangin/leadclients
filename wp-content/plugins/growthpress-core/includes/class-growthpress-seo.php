<?php
/**
 * GrowthPress SEO & Schema Class - Final
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_SEO {

    public function __construct() {
        add_action( 'wp_head', array( $this, 'inject_meta' ) );
        add_shortcode( 'gp_breadcrumbs', array( $this, 'render_breadcrumbs' ) );
    }

    public function inject_meta() {
        $schema = $this->generate_schema();
        if ( ! empty( $schema ) ) {
            echo '<script type="application/ld+json">' . json_encode( $schema ) . '</script>';
        }
        echo '<meta name="growthpress-os" content="active">';
        if ( is_singular() ) {
            echo '<meta name="description" content="' . wp_trim_words( get_the_excerpt(), 25 ) . '">';
            echo '<link rel="canonical" href="' . get_permalink() . '">';
        }
    }

    public function render_breadcrumbs() {
        global $post;
        $crumbs = '<nav class="gp-breadcrumbs"><a href="' . home_url() . '">Home</a>';
        if ( is_singular() ) {
            $crumbs .= ' / ' . get_the_title();
        }
        $crumbs .= '</nav>';
        return $crumbs;
    }

    private function generate_schema() {
        $niche = get_option( 'growthpress_niche', 'ProfessionalService' );
        $type_map = array( 'dental' => 'Dentist', 'medical' => 'MedicalClinic', 'law' => 'LegalService', 'contractor' => 'HomeAndConstructionBusiness', 'real-estate' => 'RealEstateAgent' );
        $schema_type = $type_map[$niche] ?? 'LocalBusiness';
        return array( '@context' => 'https://schema.org', '@type' => $schema_type, 'name' => get_bloginfo( 'name' ), 'url' => get_home_url() );
    }
}
new GrowthPress_SEO();
