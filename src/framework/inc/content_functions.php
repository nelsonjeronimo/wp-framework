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

	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	} else {
		$brand = get_bloginfo('name');
        $output = $brand;
	}
}

// MAIN NAVIGATION

require_once get_template_directory()."/framework/lib/bs4navwalker.php"; // Bootstrap 4 navigation + wordpress menus

function main_navigation($nav_ID="main_nav", $classes="fixed-top", $location="header-menu") {
    ?>
    <nav class="navbar navbar-expand-md <?php echo $classes ?>">
        <a class="navbar-brand" href="#"><?php echo the_brand(); ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_navigation" aria-controls="bs4navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
		</button>
		<div id="main_navigation" class="collapse navbar-collapse" role="navigation">
		<?php
			wp_nav_menu([
				'menu'            => $location,
				'theme_location'  => $location,
				'container'       => 'div',
				'container_id'    => $nav_ID,
				'container_class' => '',
				'menu_id'         => false,
				'menu_class'      => 'navbar-nav',
				'depth'           => 2,
				'fallback_cb'     => 'bs4navwalker::fallback',
				'walker'          => new bs4navwalker()
			]);
			the_socials();
		?>
		</div>
    </nav>
    <?php
}


// SOCIAL NETWORKS 

function the_socials($classes='') {

    $mods_array = get_theme_mods();
    $mods_social = array();
    $networks = [];
    

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
    $email = sanitize_email( get_theme_mod('mail_details') );
    
	$html = '<address class="'.$classes.'">';
	
	if ( $phone ) {
		$html.= '<span><i class="fa fa-phone"></i><a href="tel:'.$phone.'">'.$phone.'</a></span>';
		$c = 1;
	}

	if ( $email ) {
		$html.= '<span><i class="fa fa-envelope"></i><a href="mailto:'.antispambot($email,1).'">'.antispambot($email).'</a></span>';
		$c++;
	}
    
    $html.= '</address>';
	
	if ( $c ) { echo $html; }
    
}


if ( ! function_exists( 'frmw_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function frmw_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'frmw' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'frmw_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function frmw_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'frmw' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'frmw_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function frmw_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'frmw' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'frmw' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'frmw' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'frmw' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'frmw' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'frmw' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'frmw_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function frmw_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail( 'post-thumbnail', array(
				'alt' => the_title_attribute( array(
					'echo' => false,
				) ),
			) );
			?>
		</a>

		<?php
		endif; // End is_singular().
	}
endif;
