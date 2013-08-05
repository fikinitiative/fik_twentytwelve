<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<h1 class="entry-title"><?php _e('Cart','twentytwelve'); // the_title(); ?></h1>
		</header>

		<?php the_fik_checkout(); ?>

		<div class="entry-content">
			<?php // the_content(); No content in the cart page! ?>
		</div><!-- .entry-content -->
		<footer class="entry-meta">
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
