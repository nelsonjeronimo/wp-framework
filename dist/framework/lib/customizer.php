<?php

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
function frmw_customizer_section($wp_customize) {


    //***************************************************
    // 
    // BASIC CONTACTS SECTION
    //
    //***************************************************    

    $wp_customize->add_section('contacts_section', array(
        'title' => __('Basic contacts', 'frmw'),
        'description' => __('Basic contacts like a phone and an e.mail to display where needed', 'fmrw'),
        'priority' => 20
    ));

    // Telephone number
    $wp_customize->add_setting('phone_details', array(
        'default' => '',
        'sanitize_callback' => 'frmw_sanitize_number'
            )
    );

    $wp_customize->add_control('phone_details', array(
        'label' => __('Phone number', 'fmrw'),
        'type' => 'text',
        'section' => 'contacts_section',
        'settings' => 'phone_details'
    ));

    // Mail
    $wp_customize->add_setting('mail_details', array(
        'default' => '',
        'sanitize_callback' => 'frmw_sanitize_email',
            )
    );
    $wp_customize->add_control('mail_details', array(
        'label' => __('Email', 'fmrw'),
        'type' => 'email',
        'section' => 'contacts_section',
        'settings' => 'mail_details',
    ));

    //***************************************************
    // 
    // SOCIAL NETWORKS SECTION
    //
    //***************************************************


    $networks = array ( 'twitter', 'facebook', 'flickr', 'linkedin', 'gplus', 'youtube', 'pinterest', 'instagram' );

    $wp_customize->add_section('social_networks', array(
        'title' => __('Social Networks', 'fmrw'),
        'description' => __('Your Social Networks URLs', 'fmrw'),
        'priority' => 25
            )
    );


    foreach ( $networks as $network ) {
        $wp_customize->add_setting( 'social_'.$network.'_details', array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
                )
        );
        $wp_customize->add_control( 'social_'.$network.'_details', array(
            'label' => __( ucfirst($network).' URL', 'fmrw'),
            'type' => 'url',
            'section' => 'social_networks',
            'settings' => 'social_'.$network.'_details',
        ));        
    }

    //***************************************************
    // 
    // CONTENT PROTECTION
    //
    //***************************************************    

    $wp_customize->add_section('content_protection_section', array(
        'title' => __('Content protection', 'fmrw'),
        'description' => __('Protect content from selection and copy/past. Disable contextual menu.', 'fmrw'),
        'priority' => 25
    ));

    // disable select
    $wp_customize->add_setting('select_details', array(
        'default' => '',
            )
    );

    $wp_customize->add_control('select_details', array(
        'label' => __('Disable text selection', 'fmrw'),
        'type' => 'checkbox',
        'section' => 'content_protection_section',
        'settings' => 'select_details'
    ));

    // disable contextual menu
    $wp_customize->add_setting('contextual_menu', array(
        'default' => '',
            )
    );
    $wp_customize->add_control('contextual_menu_details', array(
        'label' => __('Disable contextual menu', 'fmrw'),
        'type' => 'checkbox',
        'section' => 'content_protection_section',
        'settings' => 'contextual_menu',
    ));

    //***************************************************
    // 
    // Google Analytics
    //
    //***************************************************    

    $wp_customize->add_section('analytics_section', array(
        'title' => 'Google Analytics',
        'description' => __('Google ID for the Google Analytics account', 'fmrw'),
        'priority' => 25
    ));

    // google analytics ID
    $wp_customize->add_setting('analytics_details', array(
        'default' => '',
            )
    );

    $wp_customize->add_control('analytics_details', array(
        'label' => 'Google ID',
        'type' => 'text',
        'section' => 'analytics_section',
        'settings' => 'analytics_details'
    ));
    
    //***************************************************
    // 
    // FOOTER
    //
    //***************************************************    

    $wp_customize->add_section('footer_section', array(
        'title' => __('Footer layout', 'fmrw'),
        'description' => __('How many footter areas do you want? The content of the footer areas will be set with widgets. If 0, no footer will be displayed (except the one with copyright notice)', 'fmrw'),
        'priority' => 25
    ));
    
    // Full width or container
     $wp_customize->add_setting('footer_width', array(
        'default' => 'full',
            )
    );

    $wp_customize->add_control('footer_width', array(
        'label' => __('Footer content width', 'fmrw'),
        'section' => 'footer_section',
        'settings' => 'footer_width',
        'type' => 'select',
        'choices' => array(
            'full' =>  __('Full Width', 'fmrw'),
            'container' =>  __('Container', 'fmrw')
        )
    ));   

    // Number of areas. One by default. Up to 4
    $wp_customize->add_setting('footer_areas', array(
        'default' => '1',
            )
    );

    $wp_customize->add_control('footer_areas', array(
        'label' => __('Widgetized areas', 'fmrw'),
        'section' => 'footer_section',
        'settings' => 'footer_areas',
        'type' => 'select',
        'choices' => array(
            '0' =>  '0',
            '1' =>  '1',
            '2' =>  '2',
            '3' =>  '3',
            '4' =>  '4'
        )
    ));
    
    // Copyright Message
    $wp_customize->add_setting('copyright_details', array(
        'default' => '',
            )
    );

    $wp_customize->add_control('copyright_details', array(
        'label' => __('Copywright Message (© and year added automatically before this message)', 'fmrw'),
        'type' => 'text',
        'section' => 'footer_section',
        'settings' => 'copyright_details'
    ));
}

add_action('customize_register', 'frmw_customizer_section');

// sanitize email address
function frmw_sanitize_email($mail) {
    return sanitize_email($mail);
}

//sanitize numbers
function frmw_sanitize_number($number) {
    $number = filter_var($number, FILTER_SANITIZE_NUMBER_INT);
    return $number;
}