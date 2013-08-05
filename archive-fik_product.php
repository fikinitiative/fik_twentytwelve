<?php
/**
 * The template for displaying Product Archive pages.
 *
 * Used to display product archive-type pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php _e('All products in the store', 'twentytwelve') ?></h1>
			</header><!-- .archive-header -->
                        
                        <ul class="product-list">
                        <?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/* Include the post format-specific template for the content. If you want to
				 * this in a child theme then include a file called called content-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */
				get_template_part( 'content', 'fik_product' );

			endwhile;
                        ?>
                        </ul>
                        <?php
			fik_twentytwelve_product_nav( 'nav-below' );
			?>

		<?php else : ?>
			<?php get_template_part( 'content', 'fik_product-none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php // get_sidebar(); // Product archive pages have no sidebar in this theme ?>
<?php get_footer(); ?>