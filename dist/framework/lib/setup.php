<?php
add_action('init', 'disable_contextual_menu');      // Check if protection of data is set on customizer and disables contextual menu
add_action('wp_head', 'disable_text_select');

// Script to Disable the contextual menu
function disable_contextual_menu() {
    $disabled = get_theme_mod('contextual_menu');
    if ( $disabled == 1 && !is_admin() ) {
        wp_enqueue_script(
                'disable_contextual_menu', get_template_directory_uri() . "/framework/assets/js/disable_contextual_menu.js", true);
    }
}

// If it's checked in backend the protection of content, 
// loads this CSS that disables select
function disable_text_select() {
    if (get_theme_mod('select_details')) {
        wp_enqueue_style('noselect', get_template_directory_uri() . '/framework/assets/css/disable_select.css');
    }
}