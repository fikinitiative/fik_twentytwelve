<?php
/**
 * The template for displaying Product Section pages.
 *
 * Used to display archive-type pages for products in a section.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header clearfix">
                                <?php 
                                // If the store section has an associated image, this function will print it. It accepts the image size as an argument:
                                the_term_thumbnail(array(960,720));
                                ?>
				<h1 class="archive-title"><?php echo( '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>
			<?php if ( category_description() ) : // Show an optional category description ?>
				<div class="archive-meta"><?php echo category_description(); ?></div>
			<?php endif; ?>
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

<?php // get_sidebar(); // Product section pages have no sidebar in this theme ?>
<?php get_footer(); ?>