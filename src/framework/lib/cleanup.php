<?php

add_filter( 'script_loader_src', 'frmw_remove_wp_version_strings' );    // Remove Generator Version Number
add_filter( 'style_loader_src', 'frmw_remove_wp_version_strings' );     // Remove Generator Version Number
add_filter( 'the_generator', 'frmw_remove_meta_version');               // Remove metatag generator from head
add_filter('xmlrpc_enabled', '__return_false');                         // Disable XML-RPC
add_filter('the_category', 'remove_category_rel_from_category_list');   // Remove invalid rel attribute
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10);   // Remove width and height dynamic attributes to thumbnails
remove_action('welcome_panel', 'wp_welcome_panel');                     // Remove Welcome Panell on Dashboard
add_action('admin_init', 'frmw_imagelink_setup', 10);                   // Set imagelink to none on upload, can be change later

// Remove the version string from js and css
function frmw_remove_wp_version_strings ( $src ) {
    global $wp_version;
    parse_str( parse_url( $src, PHP_URL_QUERY), $query );

    if ( !empty( $query['ver'] ) && $query['ver'] === $wp_version ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}

// remove metatag generator from head 
function frmw_remove_meta_version() {
    return '';
}

// Set imagelink to none on upload, can be change later
function frmw_imagelink_setup() {
    $image_set = get_option( 'image_default_link_type' );
     
    if ($image_set !== 'none') {
        update_option('image_default_link_type', 'none');
    }
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist) {
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions($html) {
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}