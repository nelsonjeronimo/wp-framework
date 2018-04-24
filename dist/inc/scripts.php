<?php

function scripts_and_styles() {

    $protocol = ( isset( $_SERVER['HTTPS'] ) && 'on' == $_SERVER['HTTPS'] ) ? 'https' : 'http';

    
    if (!is_admin()) {

        // jQuery
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


        // popper.js
        wp_register_script( 'popper', get_stylesheet_directory_uri().'/assets/js/popper.min.js', array(), true );

        // bootstrap.js
        wp_register_script( 'bootstrapjs', get_stylesheet_directory_uri().'/assets/js/bootstrap.min.js', array('jquery', 'popper'), '4.0', true );

        // Theme scripts
        wp_register_script( 'themeScripts', get_stylesheet_directory_uri().'/assets/js/theme_scripts.js', array('jquery', 'bootstrapjs'), '4.0', true );


        // enqueue the scripts
        wp_enqueue_script( 'popper' );
        wp_enqueue_script( 'bootstrapjs' );
        wp_enqueue_script( 'themeScripts' );

    }






}

add_action( 'wp_enqueue_scripts', 'scripts_and_styles' );