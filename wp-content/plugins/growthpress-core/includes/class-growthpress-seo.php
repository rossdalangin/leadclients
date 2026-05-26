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
            echo "\n" . '<!-- GrowthPress SEO --><script type="application/ld+json">' . json_encode( $schema ) . '</script>' . "\n";
        }
        echo '<meta name="growthpress-os" content="active">' . "\n";

        if ( is_singular() ) {
            $excerpt = get_the_excerpt();
            if ( $excerpt ) {
                echo '<meta name="description" content="' . esc_attr( wp_trim_words( $excerpt, 25 ) ) . '">' . "\n";
            }
            echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";

            // Dynamic Title Optimization
            $niche = get_option('growthpress_niche', 'business');
            $location = get_option('growthpress_brand_name', 'Professional');
            add_filter( 'pre_get_document_title', function($title) use ($niche, $location) {
                return get_the_title() . " | " . ucwords($niche) . " Specialist in " . $location;
            }, 999);
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

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => $schema_type,
            'name' => get_bloginfo( 'name' ),
            'url' => get_home_url()
        );

        if ( is_singular('gp_location') ) {
            global $post;
            $schema['address'] = array(
                '@type' => 'PostalAddress',
                'streetAddress' => get_post_meta($post->ID, '_gp_address', true)
            );
            $schema['telephone'] = get_post_meta($post->ID, '_gp_phone', true);
        }

        return $schema;
    }
}
new GrowthPress_SEO();
