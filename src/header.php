<?php
/*

888                             888                        .d888 d8b 888          
888                             888                       d88P"  Y8P 888          
888                             888                       888        888          
88888b.   .d88b.   8888b.   .d88888  .d88b.  888d888      888888 888 888  .d88b.  
888 "88b d8P  Y8b     "88b d88" 888 d8P  Y8b 888P"        888    888 888 d8P  Y8b 
888  888 88888888 .d888888 888  888 88888888 888          888    888 888 88888888 
888  888 Y8b.     888  888 Y88b 888 Y8b.     888          888    888 888 Y8b.     
888  888  "Y8888  "Y888888  "Y88888  "Y8888  888          888    888 888  "Y8888

*/ ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php wp_title(''); ?><?php if (wp_title('', false)) { echo ' :';} bloginfo('name'); ?></title>

    <!-- dns prefetch -->
    <link href="//www.google-analytics.com" rel="dns-prefetch">

    <!-- meta -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <!-- icons -->
    <link href="<?php echo get_template_directory_uri(); ?>/favicon.ico" rel="shortcut icon">
    <link href="<?php echo get_template_directory_uri(); ?>/touch.png" rel="apple-touch-icon-precomposed">

    <?php
        wp_head();
    ?>
</head>

    <body <?php body_class(); ?> >

    <?php main_navigation(); ?>
    <?php the_socials('xpto'); ?>