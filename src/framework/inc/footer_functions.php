<?php

function google_analytics() {
    $google_ID = get_theme_mod('analytics_details'); ?>

<!-- Google Analytics -->
    <script>
        window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
        ga('create','<?php echo $google_ID; ?>','auto');ga('send','pageview')
    </script>
    <script src="https://www.google-analytics.com/analytics.js" async defer></script>

<?php }

add_action( 'wp_footer', 'google_analytics' );