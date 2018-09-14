<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */
get_header(); ?>

<main>

<?php 

	if ( have_posts() ) :

		if ( is_home() && ! is_front_page() ) :
			?>
			<header>
				<h1><?php single_post_title(); ?></h1>
			</header>
			<?php
		endif;

		while(have_posts()) : the_post(); ?>

		title: <?php the_title(); ?><br />
		ID: <?php the_ID(); ?><br />
		time: <?php the_time(get_option('date_format')); ?><br />
		excerpt: <?php the_excerpt(); ?><br />
		link: <a href="<?php the_permalink(); ?>">link</a><br />
		<hr />

<?php endwhile; 

	frmw_pagination();

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif;

?>


<?php get_footer(); ?>