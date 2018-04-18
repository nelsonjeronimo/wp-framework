<?php

function frmw_footer() {
    $footer_areas = (int) get_theme_mod('footer_areas');

    if ($footer_areas !== 0) {
        $footer_width = get_theme_mod('footer_width');
        if ($footer_width) {
            $footer_width = 'full';
        }
        $area_width = 12 / $footer_areas;

        echo '<div class="' . $footer_width . '"';
        echo '<div class="row">';

        for ($i = 1; $i <= $footer_areas; $i++) {
            echo '<div class="col-md-' . $area_width . '">';
            dynamic_sidebar('frmw_footer_area_' . $i);
            echo '</div>';
        }
        echo '</div></div>';
    }
}


add_action('wp_footer', 'frmw_footer');
