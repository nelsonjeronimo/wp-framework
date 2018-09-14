<?php
/*
         d8b      888          888                       
         Y8P      888          888                       
                  888          888                       
.d8888b  888  .d88888  .d88b.  88888b.   8888b.  888d888 
88K      888 d88" 888 d8P  Y8b 888 "88b     "88b 888P"   
"Y8888b. 888 888  888 88888888 888  888 .d888888 888     
     X88 888 Y88b 888 Y8b.     888 d88P 888  888 888     
 88888P' 888  "Y88888  "Y8888  88888P"  "Y888888 888     
*/

/**
 * The sidebar containing the main widget area
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->