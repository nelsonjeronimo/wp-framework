<?php

$protocol = ( isset( $_SERVER['HTTPS'] ) && 'on' == $_SERVER['HTTPS'] ) ? 'https' : 'http';

// jQuery
if (!is_admin()) {
    // deregister possible wordpress version of the library
    wp_deregister_script('jquery');
    
    $jq_version = '3.3.1';
    $jquery_cdn_url = $protocol.'://code.jquery.com/jquery-'.$jq_version.'.min.js';
    $jquery_local_url = $protocol. get_template_directory_uri().'/framework/assets/js/jquery-'.$jq_version.'.min.js';
    
    $test_jq_cdn = fopen ($jquery_cdn_url, 'r');
    
    if ( $test_jq_cdn !== false ) {
        $jq = $jquery_cdn_url;
    } else {
        $jq = $jquery_local_url;
    }
    
    wp_register_script("jquery", $jq, $jq_version );
    wp_enqueue_script("jquery", false); //enque jQuery in head
}