<?php
/**
 * Template Name: Store Front Page Template
 *
 * Description: The store front page template displays the content of the static page that uses this template
 * followed by a selection of products that can be configured in the theme customization area
 * followed by the latest blog posts
 * 
 */
get_header();
?>

<div id="primary" class="site-content">
    <div id="content" role="main">

        <?php
        while (have_posts()) : the_post(); // The Home page content. Use classes font-size-bigger, font-size-double or font-size-triple to make text bigger.
            if (get_the_content() != '') : // Display only if there is content to display in the Home page
                ?>
                <div class="home-content clearfix">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="entry-page-image">
                            <?php the_post_thumbnail(); ?>
                        </div><!-- .entry-page-image -->
                    <?php endif; ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header assistive-text">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                        </header>

                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div><!-- .entry-content -->
                    </article><!-- #post -->
                </div>

                <?php
            endif; // if (get_the_content()!='')
        endwhile; // end of the loop for the home content
        // Now we prepare the loop for the products that are displayed in the front page
        $store_section = get_theme_mod('fik_home_section', '');
        $product_amount = get_theme_mod('fik_home_product_amount', 10);
        query_posts('post_type=fik_product&store-section=' . $store_section . '&posts_per_page=' . $product_amount);
        if (have_posts()) :
            ?>
            <section class="home-product-archive">
                <?php
                if ($store_section != '') :
                    $store_section_term = get_term_by('slug', $store_section, 'store-section');
                    ?>
                    <header class="archive-header">
                        <div class="archive-link">
                            <a href="<?php echo(get_post_type_archive_link('fik_product')); ?>" title="<?php _e('See all products', 'twentytwelve'); ?>"><?php _e('See all products', 'twentytwelve'); ?></a>
                        </div>
                        <h1 class="archive-title"><a href="<?php echo(get_term_link($store_section_term)); ?>" title="<?php echo($store_section_term->name); ?>"><?php echo($store_section_term->name); ?></a></h1>
                    </header><!-- .archive-header -->
                    <?php
                else:
                    ?>
                    <header class="archive-header">
                        <div class="archive-link">
                            <a href="<?php echo(get_post_type_archive_link('fik_product')); ?>" title="<?php _e('See all products', 'twentytwelve'); ?>"><?php _e('See all products', 'twentytwelve'); ?></a>
                        </div>
                        <h1 class="archive-title"><a href="<?php echo(get_post_type_archive_link('fik_product')); ?>" title="<?php _e('Store', 'twentytwelve'); ?>"><?php _e('Store', 'twentytwelve'); ?></a></h1>
                    </header><!-- .archive-header -->
                <?php
                endif;
                ?>
                <ul class="product-list">
                    <?php
                    while (have_posts()) : the_post();
                        get_template_part('content', 'fik_product');
                    endwhile; // product loop
                    ?>
                </ul>
            </section>
            <?php
        endif; // Products
        // Now we prepare the loop for the posts that are displayed in the front page
        $blog_category = get_theme_mod('fik_home_category', '');
        $post_amount = get_theme_mod('fik_home_post_amount', 2);
        query_posts('post_type=post&category=' . $blog_category . '&posts_per_page=' . $post_amount);
        if (have_posts()) :
            $blog_title = __('Blog');
            if (get_option('show_on_front', true)=="page")
                $blog_title = get_the_title(get_option('page_for_posts', true));
            $blog_permalink = get_permalink(get_option('page_for_posts', true))
            ?>
            <section class="home-post-archive">
                <?php
                if ($blog_category != '') :
                    $blog_category_term = get_term_by('slug', $blog_category, 'category');
                    ?>
                    <header class="archive-header">
                        <div class="archive-link">
                            <a href="<?php echo($blog_permalink); ?>" title="<?php _e('See all posts', 'twentytwelve'); ?>"><?php _e('See all posts', 'twentytwelve'); ?></a>
                        </div>
                        <h1 class="archive-title"><a href="<?php echo(get_term_link($blog_category_term)); ?>" title="<?php echo($blog_category_term->name); ?>"><?php echo($blog_category_term->name); ?></a></h1>
                    </header><!-- .archive-header -->
                    <?php
                else:
                    ?>
                    <header class="archive-header">
                        <div class="archive-link">
                            <a href="<?php echo($blog_permalink); ?>" title="<?php _e('See all posts', 'twentytwelve'); ?>"><?php _e('See all posts', 'twentytwelve'); ?></a>
                        </div>
                        <h1 class="archive-title"><a href="<?php echo($blog_permalink); ?>" title="<?php echo($blog_title); ?>"><?php echo($blog_title); ?></a></h1>
                    </header><!-- .archive-header -->
                <?php
                endif;
                ?>
                <?php
                while (have_posts()) : the_post();
                    get_template_part('content', 'fik_home_post');
                endwhile; // product loop
                ?>

            </section>
            <?php
        endif; // Blog posts
        ?>

    </div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar('front'); ?>
<?php get_footer(); ?>