<?php
/**
 * The template for displaying a "No posts found" message.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<article id="post-0" class="post no-results not-found">
		<header class="entry-header">
			<h1 class="entry-title"><?php _e( 'We are sold out of products in this section! Sorry for the inconvenience', 'twentytwelve' ); ?></h1>
		</header>

		<div class="entry-content">
			<p><?php _e( 'Apologies, but no products were found. Perhaps searching will help find great stuff.', 'twentytwelve' ); ?></p>
			<?php get_search_form(); ?>

		</div><!-- .entry-content -->
        <div>
            <?php 
                        query_posts('post_type=fik_product&posts_per_page=20');
                        if ( have_posts() ) : ?>
            <br><br>
                        <header class="archive-header clearfix">
                            <h1 class="archive-title"><span><?php _e( 'Check out other products of our store:', 'twentytwelve' ); ?></span></h1>
			</header>
                        
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
                        
                        <?php endif; ?>
        </div>
	</article><!-- #post-0 -->
