<?php
/**
 * GrowthPress Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

function growthpress_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'growthpress' ),
		'footer'  => __( 'Footer Menu', 'growthpress' ),
	) );
}
add_action( 'after_setup_theme', 'growthpress_setup' );

function growthpress_scripts() {
	wp_enqueue_style( 'growthpress-inter-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap' );
	wp_enqueue_style( 'growthpress-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'growthpress_scripts' );
