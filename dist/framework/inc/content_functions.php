<?php

// Dynamic Copyright Date
// echo this function on the footer
function frmw_copyright() {
    global $wpdb;
    $copyright_dates = $wpdb->get_results("
        SELECT
        YEAR(min(post_date_gmt)) AS firstdate,
        YEAR(max(post_date_gmt)) AS lastdate
        FROM
        $wpdb->posts
        WHERE
        post_status = 'publish'
    ");

    $output = '';

    if($copyright_dates) {
        $copyright = "© " . $copyright_dates[0]->firstdate;
    if($copyright_dates[0]->firstdate != $copyright_dates[0]->lastdate) {
        $copyright .= '-' . $copyright_dates[0]->lastdate;
    }
    $output = $copyright;
    }
    return $output;
}


// Output Brand logo, if exists, else just the name
function the_brand() {
    $brand = get_theme_mod('custom_logo');
    
    if ( !$brand ) {
        $brand = get_bloginfo('name');
        $output = $brand;
    } else {
        $image = wp_get_attachment_image_src( $custom_logo_id , 'full' ); 
        $output = '<img src="'.$image[0].'" alt="'.get_bloginfo('name').'" />';
    }
    
    return $output;
}


// MAIN NAVIGATION
function main_navigation($nav_ID="main_nav", $classes="") {
    ?>
    <nav class="navbar navbar-expand-md <?php echo $classes ?>">
        <a class="navbar-brand" href="#"><?php echo the_brand(); ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#<?php echo $nav_ID; ?>" aria-controls="bs4navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
        </button>
    <?php
        wp_nav_menu([
            'menu'            => 'top',
            'theme_location'  => 'top',
            'container'       => 'div',
            'container_id'    => $nav_ID,
            'container_class' => 'collapse navbar-collapse',
            'menu_id'         => false,
            'menu_class'      => 'navbar-nav mr-auto',
            'depth'           => 2,
            'fallback_cb'     => 'bs4navwalker::fallback',
            'walker'          => new bs4navwalker()
        ]);
    ?>
    </nav>
    <?php
}


// SOCIAL NETWORKS 

function the_socials($classes='') {

    $mods_array = get_theme_mods();

    $mods_social = array();

    foreach( $mods_array as $key => $value ) {
        $exp_key = explode('_', $key );
        if($exp_key[0] == 'social') {
            $networks[] = array ( 'name' => $exp_key[1], 'url' => $value );
        }
    }

    $classes = "site_socials ".$classes;
    
    $html = '<nav class="'.$classes.'">';
    
    foreach ( $networks as $network ) {
        if ( $network['url'] !== false ){
            $html.= '<a href="'.$network['url'].'" class="network-link" target="_blank"><i class="fa fa-'.$network['name'].'"></i></a>';
        }
    }
    $html.='</nav>';
    echo $html;
}

// CONTACTS
function the_contacts ($classes='') {
    
    $html='';
    
    $classes = "site_contacts ".$classes;
    
    $phone = get_theme_mod('phone_details');
    $email = get_theme_mod('mail_details');
    
    $html = '<address class="'.$classes.'">';
    $html.= '<span><a href="tel:'.$phone.'"><i class="fa fa-phone"></i>'.$phone.'</a></span>';
    $html.= '<span><a href="mailto:'.$email.'"><i class="fa fa-envelope"></i>'.$email.'</a></span>';
    $html.= '</address>';
    
    echo $html;
}