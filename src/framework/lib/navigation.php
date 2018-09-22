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
    $big = 999999999; // need an unlikely integer
    $pages = paginate_links( array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '?paged=%#%',
        'current' => max( 1, get_query_var('paged') ),
        'total' => $wp_query->max_num_pages,
        'prev_next' => false,
        'type'  => 'array',
        'prev_next'   => true,
        'prev_text'    => __( '«', 'text-domain' ),
        'next_text'    => __( '»', 'text-domain'),
    ) );
    $output = '';

    if ( is_array( $pages ) ) {
        $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var( 'paged' );

        $output .=  '<ul class="pagination">';
        foreach ( $pages as $page ) {
            $output .= "<li>$page</li>";
        }
        $output .= '</ul>';

        // Create an instance of DOMDocument 
        $dom = new \DOMDocument();

        // Populate $dom with $output, making sure to handle UTF-8, otherwise
        // problems will occur with UTF-8 characters.
        $dom->loadHTML( mb_convert_encoding( $output, 'HTML-ENTITIES', 'UTF-8' ) );

        // Create an instance of DOMXpath and all elements with the class 'page-numbers' 
        $xpath = new \DOMXpath( $dom );

        // http://stackoverflow.com/a/26126336/3059883
        $page_numbers = $xpath->query( "//*[contains(concat(' ', normalize-space(@class), ' '), ' page-numbers ')]" );

        // Iterate over the $page_numbers node...
        foreach ( $page_numbers as $page_numbers_item ) {

            // Add class="mynewclass" to the <li> when its child contains the current item.
            $page_numbers_item_classes = explode( ' ', $page_numbers_item->attributes->item(0)->value );
            if ( in_array( 'current', $page_numbers_item_classes ) ) {          
                $list_item_attr_class = $dom->createAttribute( 'class' );
                $list_item_attr_class->value = 'mynewclass';
                $page_numbers_item->parentNode->appendChild( $list_item_attr_class );
            }

            // Replace the class 'current' with 'active'
            $page_numbers_item->attributes->item(0)->value = str_replace( 
                            'current',
                            'active',
                            $page_numbers_item->attributes->item(0)->value );

            // Replace the class 'page-numbers' with 'page-link'
            $page_numbers_item->attributes->item(0)->value = str_replace( 
                            'page-numbers',
                            'page-link',
                            $page_numbers_item->attributes->item(0)->value );
        }

        // Save the updated HTML and output it.
        $output = $dom->saveHTML();
    }

    return $output;
}