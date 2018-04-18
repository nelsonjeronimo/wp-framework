<?php

function theme_support_features() {

	// Custom Logo
	add_theme_support( 'custom-logo', array(
		'flex-width' => true,
	) );
	
	// Post Thumbnails
	add_theme_support( 'post-thumbnails' ); 
	
	// Title Tag
	add_theme_support( 'title-tag' );

	// HTML5
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

}
add_action( 'after_setup_theme', 'theme_support_features' );


// Enable shortcodes in text widgets
add_filter('widget_text','do_shortcode');

// Enable Support for SVG files
function frmw_myme_types($mime_types){
    $mime_types['svg'] = 'image/svg+xml'; //Adding svg extension
    return $mime_types;
}
add_filter('upload_mimes', 'frmw_myme_types', 1, 1);

// Facebook OpenGraph DocType support
function doctype_opengraph($output) {
    return $output . '
    xmlns:og="http://opengraphprotocol.org/schema/"
    xmlns:fb="http://www.facebook.com/2008/fbml"';
}
add_filter('language_attributes', 'doctype_opengraph');