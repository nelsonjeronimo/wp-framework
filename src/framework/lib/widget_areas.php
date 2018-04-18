<?php

// Test to see how many widget areas we'll have on footer
// If only one, default will be the Copyright

function frmw_footer_widgets() {
    $number_areas = (int) get_theme_mod('footer_areas');
    if ($number_areas !== 0) {

        $i = 1;
        while ($i <= $number_areas) {

            register_sidebar(array(
                'name' => 'Footer Area ' . (string) $i,
                'id' => 'frmw_footer_area_' . (string) $i,
                'before_widget' => '<div>',
                'after_widget' => '</div>',
                'before_title' => '<h4>',
                'after_title' => '</h4>'
            ));

            $i++;
        }
    }
    
    register_sidebar( array(
        'name'          => __( 'Main Sidebar', 'frmw' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Sidebar widget area', 'frmw' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>' 
    ));
}

add_action('widgets_init', 'frmw_footer_widgets');
