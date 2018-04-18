<?php

add_action( 'init', 'register_frmw_menus' );
add_action('nav_menu_css_class', 'add_current_nav_class', 10, 2);       // Add current-menu-item class to menu in CPT's single pages
add_action('init', 'frmw_pagination');                                  // Add our Custom Pagination Navigation


//
// Register two basic menus, one main and other to use on the footer
//
function register_frmw_menus() {
    register_nav_menus(
      array(
        'header-menu' => __( 'Header Menu' ),
        'extra-menu' => __( 'Footer Menu' )
      )
    );
  }

// Add current-menu-item class to menu in CPT's single pages
function add_current_nav_class($classes, $item) {

    // Getting the current post details
    global $post;
    $id = ( isset( $post->ID ) ? get_the_ID() : NULL );
    
    if (isset( $id )){
        // Getting the post type of the current post
        $current_post_type = get_post_type_object(get_post_type($post->ID));
        $current_post_type_slug = $current_post_type->rewrite['slug'];

        // Getting the URL of the menu item
        $menu_slug = strtolower(trim($item->url));

        // If the menu item URL contains the current post types slug add the current-menu-item class
        if (strpos($menu_slug, $current_post_type_slug) !== false) {

            $classes[] = 'current-menu-item';
        }
    }
    // Return the corrected set of classes to be added to the menu item
    return $classes;
}

//
// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
//
function frmw_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}