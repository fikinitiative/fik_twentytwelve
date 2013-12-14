<?php
/**
 * Twenty Twelve functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

// Set up the content width value based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 625;

/**
 * Twenty Twelve setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Twelve supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_setup() {
    /*
     * Makes Twenty Twelve available for translation.
     *
     * Translations can be added to the /languages/ directory.
     * If you're building a theme based on Twenty Twelve, use a find and replace
     * to change 'twentytwelve' to the name of your theme in all the template files.
     */
    load_theme_textdomain('twentytwelve', get_template_directory() . '/languages');

    // This theme styles the visual editor with editor-style.css to match the theme style.
    add_editor_style();

    // Adds RSS feed links to <head> for posts and comments.
    add_theme_support('automatic-feed-links');

    // This theme supports a variety of post formats.
    add_theme_support('post-formats', array('aside', 'image', 'link', 'quote', 'status'));

    // This theme uses wp_nav_menu() in three locations.
    register_nav_menu('primary', __('Primary Menu', 'twentytwelve'));
    // Asign default manu to Primary menu area
    if (!has_nav_menu('primary'))
        if (is_nav_menu(get_option('fik_default_main_menu')))
            set_theme_mod('nav_menu_locations', array_map('absint', array("primary" => get_option('fik_default_main_menu'))));

    // This menu goes in the store as a secondary menu.
    register_nav_menu('store_menu', __('Store Menu', 'twentytwelve'));

    // This menu goes in the footer.
    register_nav_menu('footer_menu', __('Footer Menu', 'twentytwelve'));

    /*
     * This theme supports custom background color and image, and here
     * we also set up the default background color.
     */
    add_theme_support('custom-background', array(
        'default-color' => 'e6e6e6',
    ));

    // This theme uses a custom image size for featured images, displayed on "standard" posts.
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(620, 9999); // Unlimited height, soft crop
    // Additional image sizes used in the store theme:
    add_image_size('fik2012-thumb-sq', 172, 172, true); //square thumbnail
    add_image_size('fik2012-thumb-h', 172, 129, true); //horizontal thumbnail
    add_image_size('fik2012-thumb-v', 172, 229, true); //vertical thumbnail
}

add_action('after_setup_theme', 'twentytwelve_setup');

// Set default templates on theme activation:
function fikactivationfunction($oldname, $oldtheme = false) {
    // Set front page to fik 2012 home page
    update_option('show_on_front', 'page');
    update_option('page_on_front', get_option('fik_home_page_id'));
    // Apply Store front page template
    update_post_meta(get_option('fik_home_page_id'), '_wp_page_template', 'page-templates/store-front-page.php');
    update_option('page_for_posts', get_option('fik_blog_page_id'));
}

add_action("after_switch_theme", "fikactivationfunction", 10, 2);

/**
 * Adds support for a custom header image.
 */
require( get_template_directory() . '/inc/custom-header.php' );

/**
 * Enqueues scripts and styles for front-end.
 *
 */
function twentytwelve_scripts_styles() {
    global $wp_styles;

    /*
     * Adds JavaScript intoduced by Fik Stores for retina image handling.
     */
    wp_enqueue_script('fik_twentytwelve-common', get_template_directory_uri() . '/js/common.js', array('jquery'), '1.0', true);

    /*
     * Adds javascript to the product page
     */
    if (is_single() && ( 'fik_product' == get_post_type() )) {
        wp_enqueue_script('fik_twentytwelve-product', get_template_directory_uri() . '/js/product.js', array('jquery'), '1.01', true);
        wp_enqueue_script('fik_twentytwelve-product-zoom', get_template_directory_uri() . '/js/elevatezoom-master/jquery.elevateZoom-2.5.6.min.js', array('jquery'), '2.5.6', true);
    }

    /* // This javascript is now loaded for all themes for the cart page from _fik_theming.php
      if (is_page(get_option('fik_cart_page_id'))) {
      wp_enqueue_script( 'fik_twentytwelve-cart', get_template_directory_uri() . '/js/cart.js', array('jquery'), '1.01', true );
      }
     */

    /*
     * Adds CSS tyles intoduced by Fik Stores for the product thumbnail
     */

    wp_enqueue_style('fik_twentytwelve-product-style', get_template_directory_uri() . '/css/product-style.css');




    /*
     * Adds JavaScript to pages with the comment form to support
     * sites with threaded comments (when in use).
     */
    if (is_singular() && comments_open() && get_option('thread_comments'))
        wp_enqueue_script('comment-reply');

    /*
     * Adds JavaScript for handling the navigation menu hide-and-show behavior.
     */
    wp_enqueue_script('twentytwelve-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), '1.0', true);

    /*
     * Loads our special font CSS file.
     *
     * The use of Open Sans by default is localized. For languages that use
     * characters not supported by the font, the font can be disabled.
     *
     * To disable in a child theme, use wp_dequeue_style()
     * function mytheme_dequeue_fonts() {
     *     wp_dequeue_style( 'twentytwelve-fonts' );
     * }
     * add_action( 'wp_enqueue_scripts', 'mytheme_dequeue_fonts', 11 );
     */

    /* translators: If there are characters in your language that are not supported
      by Open Sans, translate this to 'off'. Do not translate into your own language. */
    if ('off' !== _x('on', 'Open Sans font: on or off', 'twentytwelve')) {
        $subsets = 'latin,latin-ext';

        /* translators: To add an additional Open Sans character subset specific to your language, translate
          this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language. */
        $subset = _x('no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'twentytwelve');

        if ('cyrillic' == $subset)
            $subsets .= ',cyrillic,cyrillic-ext';
        elseif ('greek' == $subset)
            $subsets .= ',greek,greek-ext';
        elseif ('vietnamese' == $subset)
            $subsets .= ',vietnamese';

        $protocol = is_ssl() ? 'https' : 'http';
        $query_args = array(
            'family' => 'Open+Sans:400italic,700italic,400,700',
            'subset' => $subsets,
        );
        wp_enqueue_style('twentytwelve-fonts', add_query_arg($query_args, "$protocol://fonts.googleapis.com/css"), array(), null);
    }

    /*
     * Loads our main stylesheet.
     */
    wp_enqueue_style('twentytwelve-style', get_stylesheet_uri());

    /*
     * Loads the Internet Explorer specific stylesheet.
     */
    wp_enqueue_style('twentytwelve-ie', get_template_directory_uri() . '/css/ie.css', array('twentytwelve-style'), '20121010');
    $wp_styles->add_data('twentytwelve-ie', 'conditional', 'lt IE 9');
}

add_action('wp_enqueue_scripts', 'twentytwelve_scripts_styles');

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since Twenty Twelve 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function twentytwelve_wp_title($title, $sep) {
    global $paged, $page;

    if (is_feed())
        return $title;

    // Add the site name.
    $title .= get_bloginfo('name');

    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && ( is_home() || is_front_page() ))
        $title = "$title $sep $site_description";

    // Add a page number if necessary.
    if ($paged >= 2 || $page >= 2)
        $title = "$title $sep " . sprintf(__('Page %s', 'twentytwelve'), max($paged, $page));

    return $title;
}

add_filter('wp_title', 'twentytwelve_wp_title', 10, 2);

/**
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_page_menu_args($args) {
    if (!isset($args['show_home']))
        $args['show_home'] = true;
    return $args;
}

add_filter('wp_page_menu_args', 'twentytwelve_page_menu_args');

/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_widgets_init() {

    register_sidebar(array(
        'name' => __('First Front Page Footer Widget Area', 'twentytwelve'),
        'id' => 'sidebar-2',
        'description' => __('Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => __('Product Page Sidebar Widget Area', 'twentytwelve'),
        'id' => 'sidebar-4',
        'description' => __('Appears in the Product Page, bellow the Add to cart button in screens over 600px and bellow the product description in screens bellow 600px.', 'twentytwelve'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => __('Post and Page Sidebar Widget Area', 'twentytwelve'),
        'id' => 'sidebar-1',
        'description' => __('Appears on posts and pages except the optional Front Page template, which has its own widgets', 'twentytwelve'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

add_action('widgets_init', 'twentytwelve_widgets_init');

if (!function_exists('twentytwelve_content_nav')) :

    /**
     * Displays navigation to next/previous pages when applicable.
     *
     * @since Twenty Twelve 1.0
     */
    function twentytwelve_content_nav($html_id) {
        global $wp_query;

        $html_id = esc_attr($html_id);

        if ($wp_query->max_num_pages > 1) :
            ?>
            <nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
                <h3 class="assistive-text"><?php _e('Post navigation', 'twentytwelve'); ?></h3>
                <div class="nav-previous alignleft"><?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'twentytwelve')); ?></div>
                <div class="nav-next alignright"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'twentytwelve')); ?></div>
            </nav><!-- #<?php echo $html_id; ?> .navigation -->
        <?php
        endif;
    }

endif;

if (!function_exists('twentytwelve_comment')) :

    /**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own twentytwelve_comment(), and that function will be used instead.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @since Twenty Twelve 1.0
     */
    function twentytwelve_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                // Display trackbacks differently than normal comments.
                ?>
                <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
                    <p><?php _e('Pingback:', 'twentytwelve'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(__('(Edit)', 'twentytwelve'), '<span class="edit-link">', '</span>'); ?></p>
                            <?php
                            break;
                        default :
                            // Proceed with normal comments.
                            global $post;
                            ?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                    <article id="comment-<?php comment_ID(); ?>" class="comment">
                        <header class="comment-meta comment-author vcard">
                            <?php
                            echo get_avatar($comment, 44);
                            printf('<cite class="fn">%1$s %2$s</cite>', get_comment_author_link(),
                                    // If current post author is also comment author, make it known visually.
                                    ( $comment->user_id === $post->post_author ) ? '<span> ' . __('Post author', 'twentytwelve') . '</span>' : ''
                            );
                            printf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>', esc_url(get_comment_link($comment->comment_ID)), get_comment_time('c'),
                                    /* translators: 1: date, 2: time */ sprintf(__('%1$s at %2$s', 'twentytwelve'), get_comment_date(), get_comment_time())
                            );
                            ?>
                        </header><!-- .comment-meta -->

                            <?php if ('0' == $comment->comment_approved) : ?>
                            <p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'twentytwelve'); ?></p>
                <?php endif; ?>

                        <section class="comment-content comment">
                            <?php comment_text(); ?>
                <?php edit_comment_link(__('Edit', 'twentytwelve'), '<p class="edit-link">', '</p>'); ?>
                        </section><!-- .comment-content -->

                        <div class="reply">
                    <?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply', 'twentytwelve'), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                        </div><!-- .reply -->
                    </article><!-- #comment-## -->
                    <?php
                    break;
            endswitch; // end comment_type check
        }

    endif;

    if (!function_exists('twentytwelve_entry_meta')) :

        /**
         * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
         *
         * Create your own twentytwelve_entry_meta() to override in a child theme.
         *
         * @since Twenty Twelve 1.0
         */
        function twentytwelve_entry_meta() {
            // Translators: used between list items, there is a space after the comma.
            $categories_list = get_the_category_list(__(', ', 'twentytwelve'));

            // Translators: used between list items, there is a space after the comma.
            $tag_list = get_the_tag_list('', __(', ', 'twentytwelve'));

            $date = sprintf('<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>', esc_url(get_permalink()), esc_attr(get_the_time()), esc_attr(get_the_date('c')), esc_html(get_the_date())
            );

            $author = sprintf('<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>', esc_url(get_author_posts_url(get_the_author_meta('ID'))), esc_attr(sprintf(__('View all posts by %s', 'twentytwelve'), get_the_author())), get_the_author()
            );

            // Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
            if ($tag_list) {
                $utility_text = __('This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve');
            } elseif ($categories_list) {
                $utility_text = __('This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve');
            } else {
                $utility_text = __('This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve');
            }

            printf(
                    $utility_text, $categories_list, $tag_list, $date, $author
            );
        }

    endif;

    /**
     * Extends the default WordPress body class to denote:
     * 1. Using a full-width layout, when no active widgets in the sidebar
     *    or full-width template.
     * 2. Front Page template: thumbnail in use and number of sidebars for
     *    widget areas.
     * 3. White or empty background color to change the layout and spacing.
     * 4. Custom fonts enabled.
     * 5. Single or multiple authors.
     *
     * @since Twenty Twelve 1.0
     *
     * @param array Existing class values.
     * @return array Filtered class values.
     */
    function twentytwelve_body_class($classes) {
        $background_color = get_background_color();

        if (!is_active_sidebar('sidebar-1') || is_page_template('page-templates/full-width.php'))
            $classes[] = 'full-width';

        if (is_page_template('page-templates/store-front-page.php') || is_page_template('page-templates/blank-front-page.php')) {
            $classes[] = 'template-front-page';
            if (has_post_thumbnail())
                $classes[] = 'has-post-thumbnail';
            if (is_active_sidebar('sidebar-2') && is_active_sidebar('sidebar-3'))
                $classes[] = 'two-sidebars';
        }

        if (empty($background_color))
            $classes[] = 'custom-background-empty';
        elseif (in_array($background_color, array('fff', 'ffffff')))
            $classes[] = 'custom-background-white';

        // Enable custom font class only if the font CSS is queued to load.
        if (wp_style_is('twentytwelve-fonts', 'queue'))
            $classes[] = 'custom-font-enabled';

        if (!is_multi_author())
            $classes[] = 'single-author';

        return $classes;
    }

    add_filter('body_class', 'twentytwelve_body_class');

    /**
     * Adjusts content_width value for full-width and single image attachment
     * templates, and when there are no active widgets in the sidebar.
     *
     * @since Twenty Twelve 1.0
     */
    function twentytwelve_content_width() {
        if (is_page_template('page-templates/full-width.php') || is_attachment() || !is_active_sidebar('sidebar-1')) {
            global $content_width;
            $content_width = 960;
        }
    }

    add_action('template_redirect', 'twentytwelve_content_width');

    /**
     * Add postMessage support for site title and description for the Theme Customizer.
     *
     * @since Twenty Twelve 1.0
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    function twentytwelve_customize_register($wp_customize) {
        $wp_customize->get_setting('blogname')->transport = 'postMessage';
        $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    }

    add_action('customize_register', 'twentytwelve_customize_register');

    /**
     * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
     *
     * @since Twenty Twelve 1.0
     */
    function twentytwelve_customize_preview_js() {
        wp_enqueue_script('twentytwelve-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array('customize-preview'), '20120827', true);
    }

    add_action('customize_preview_init', 'twentytwelve_customize_preview_js');

    /**
     * Adds fik-archive-thumb image size, that is the size of product featured images in archive pages
     */
    if (function_exists('add_image_size')) {
        add_image_size('fik2012-thumb-sq', 172, 172, true); //square thumbnail
        add_image_size('fik2012-thumb-h', 172, 129, true); //horizontal thumbnail
        add_image_size('fik2012-thumb-v', 172, 229, true); //vertical thumbnail
    }

    if (!function_exists('fik_twentytwelve_product_nav')) :

        /**
         * Displays navigation to next/previous product pages when applicable.
         *
         */
        function fik_twentytwelve_product_nav($html_id) {
            global $wp_query;

            $html_id = esc_attr($html_id);

            if ($wp_query->max_num_pages > 1) :
                ?>
                <nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
                    <h3 class="assistive-text"><?php _e('Product navigation', 'twentytwelve'); ?></h3>
                    <div class="nav-previous alignleft"><?php next_posts_link(__('<span class="meta-nav">&larr;</span> Previous products', 'twentytwelve')); ?></div>
                    <div class="nav-next alignright"><?php previous_posts_link(__('Newer products <span class="meta-nav">&rarr;</span>', 'twentytwelve')); ?></div>
            </nav><!-- #<?php echo $html_id; ?> .navigation -->
            <?php
            endif;
        }

    endif;

    /*
     * Add theme customization options
     */

    function fik_2012_customize_register($wp_customize) {

        // We remove the Static front page customization section from theme customization screen with this theme
        //$wp_customize->remove_section('static_front_page');

        class fik_2012_Customize_Thumb_Select_Control extends WP_Customize_Control {

            public $type = 'select';

            public function render_content() {
                ?>
                <label>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                    <select <?php $this->link(); ?>>
                        <option value="fik2012-thumb-sq" <?php selected($this->value(), "fik2012-thumb-sq"); ?>><?php _e('Square thumbnails', 'twentytwelve'); ?></option>
                        <option value="fik2012-thumb-h" <?php selected($this->value(), "fik2012-thumb-h"); ?>><?php _e('Horizontal thumbnails (4:3 ratio)', 'twentytwelve'); ?></option>
                        <option value="fik2012-thumb-v" <?php selected($this->value(), "fik2012-thumb-v"); ?>><?php _e('Vertical thumbnails (3:4 ratio)', 'twentytwelve'); ?></option>
                    </select>
                </label>
                <?php
            }

        }

        class fik_2012_Customize_amount_input_Control extends WP_Customize_Control {

            public $type = 'input';

            public function render_content() {
                ?>
                <label>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                    <input type="number" <?php $this->link(); ?> min="1" max="30" step="1" value="<?php echo $this->value(); ?>" required="">
                </label>
                    <?php
                }

            }

            class fik_2012_Customize_Section_Select_Control extends WP_Customize_Control {

                public $type = 'select';

                public function render_content() {
                    ?>
                <label>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                    <?php
                    $args = array(
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => true,
                        'fields' => 'all',
                        'hierarchical' => false
                    );
                    $myterms = get_terms('store-section', $args);
                    echo "<select ";
                    $this->link();
                    echo ">";
                    echo "<option value=''>" . __('All sections') . "</option>";
                    foreach ($myterms as $term) {
                        $root_url = get_bloginfo('url');
                        $term_slug = $term->slug;
                        $term_name = $term->name;
                        echo "<option value='" . $term_slug . "' ";
                        selected($this->value(), $term_slug);
                        echo ">" . $term_name . "</option>";
                    }
                    echo "</select>";
                    ?>
                    <br>
                    <div style="margin-left:4px;"><?php _e('Most recent products from the specified Store Section will be displayed'); ?></div>
                </label>
                    <?php
                }

            }

            class fik_2012_Customize_Category_Select_Control extends WP_Customize_Control {

                public $type = 'select';

                public function render_content() {
                    ?>
                <label>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                    <?php
                    $args = array(
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => true,
                        'fields' => 'all',
                        'hierarchical' => false
                    );
                    $myterms = get_terms('category', $args);
                    echo "<select ";
                    $this->link();
                    echo ">";
                    echo "<option value=''>" . __('All categories') . "</option>";
                    foreach ($myterms as $term) {
                        $root_url = get_bloginfo('url');
                        $term_slug = $term->slug;
                        $term_name = $term->name;
                        echo "<option value='" . $term_slug . "' ";
                        selected($this->value(), $term_slug);
                        echo ">" . $term_name . "</option>";
                    }
                    echo "</select>";
                    ?>
                    <br>
                    <div style="margin-left:4px;"><?php _e('Most recent posts from the specified Blog Category will be displayed'); ?></div>
                </label>
            <?php
        }

    }

    class fik_2012_Customize_Head_Align_Control extends WP_Customize_Control {

        public $type = 'radio';

        public function render_content() {
            ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <label>
                    <input type="radio" name="fik_head_align" <?php $this->link(); ?> value="align-left" <?php checked($this->value(), "align-left"); ?>><?php _e('Left', 'twentytwelve'); ?><br>
                </label>
                <label>
                    <input type="radio" name="fik_head_align" <?php $this->link(); ?> value="align-center" <?php checked($this->value(), "align-center"); ?>><?php _e('Center', 'twentytwelve'); ?><br>
                </label>
                <label>
                    <input type="radio" name="fik_head_align" <?php $this->link(); ?> value="align-right" <?php checked($this->value(), "align-right"); ?>><?php _e('Right', 'twentytwelve'); ?>
                </label>
            <?php
        }

    }

    class fik_2012_Customize_Head_Font_Control extends WP_Customize_Control {

        public $type = 'select';

        public function render_content() {
            ?>
                <label>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                    <select <?php $this->link(); ?>>
                        <option value="Open Sans" <?php selected($this->value(), "Open Sans"); ?>><?php _e('Default (Open Sans)', 'twentytwelve'); ?></option>
                        <option value="PT Sans" <?php selected($this->value(), "PT Sans"); ?>><?php _e('PT Sans', 'twentytwelve'); ?></option>
                        <option value="Josefin Sans" <?php selected($this->value(), "Josefin Sans"); ?>><?php _e('Josefin Sans', 'twentytwelve'); ?></option>
                        <option value="Krona One" <?php selected($this->value(), "Krona One"); ?>><?php _e('Krona One', 'twentytwelve'); ?></option>
                        <option value="McLaren" <?php selected($this->value(), "McLaren"); ?>><?php _e('McLaren', 'twentytwelve'); ?></option>
                        <option value="Cutive" <?php selected($this->value(), "Cutive"); ?>><?php _e('Cutive', 'twentytwelve'); ?></option>
                        <option value="Bree Serif" <?php selected($this->value(), "Bree Serif"); ?>><?php _e('Bree Serif', 'twentytwelve'); ?></option>
                        <option value="Clicker Script" <?php selected($this->value(), "Clicker Script"); ?>><?php _e('Clicker Script', 'twentytwelve'); ?></option>
                        <option value="Sacramento" <?php selected($this->value(), "Sacramento"); ?>><?php _e('Sacramento', 'twentytwelve'); ?></option>
                        <option value="Averia Libre" <?php selected($this->value(), "Averia Libre"); ?>><?php _e('Averia Libre', 'twentytwelve'); ?></option>
                        <option value="Press Start 2P" <?php selected($this->value(), "Press Start 2P"); ?>><?php _e('Press Start 2P', 'twentytwelve'); ?></option>
                        <option value="Sancreek" <?php selected($this->value(), "Sancreek"); ?>><?php _e('Sancreek', 'twentytwelve'); ?></option>
                        <option value="Fontdiner Swanky" <?php selected($this->value(), "Fontdiner Swanky"); ?>><?php _e('Fontdiner Swanky', 'twentytwelve'); ?></option>
                    </select>
                </label>
                <?php
            }

        }

        class fik_2012_Customize_Head_Size_Control extends WP_Customize_Control {

            public $type = 'select';

            public function render_content() {
                ?>
                <label>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                    <select <?php $this->link(); ?>>
                        <option value="26px" <?php selected($this->value(), "26px"); ?>><?php _e('Default (26px)', 'twentytwelve'); ?></option>
                        <option value="36px" <?php selected($this->value(), "36px"); ?>><?php _e('36px', 'twentytwelve'); ?></option>
                        <option value="46px" <?php selected($this->value(), "46px"); ?>><?php _e('46px', 'twentytwelve'); ?></option>
                        <option value="56px" <?php selected($this->value(), "56px"); ?>><?php _e('56px', 'twentytwelve'); ?></option>
                        <option value="66px" <?php selected($this->value(), "66px"); ?>><?php _e('66px', 'twentytwelve'); ?></option>
                        <option value="86px" <?php selected($this->value(), "86px"); ?>><?php _e('86px', 'twentytwelve'); ?></option>
                        <option value="106px" <?php selected($this->value(), "106px"); ?>><?php _e('106px', 'twentytwelve'); ?></option>
                    </select>
                </label>
                <?php
            }

        }

        //All our sections, settings, and controls will be added here
        $wp_customize->add_setting('fik_head_font', array(
            'default' => 'Open Sans',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control(new fik_2012_Customize_Head_Font_Control($wp_customize, 'fik_head_font', array(
                    'label' => __('Site Title Font', 'twentytwelve'),
                    'section' => 'title_tagline',
                    'settings' => 'fik_head_font',
                        )));
        $wp_customize->add_setting('fik_head_size', array(
            'default' => '26px',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control(new fik_2012_Customize_Head_Size_Control($wp_customize, 'fik_head_size', array(
                    'label' => __('Site Title Size', 'twentytwelve'),
                    'section' => 'title_tagline',
                    'settings' => 'fik_head_size',
                        )));
        $wp_customize->add_setting('fik_head_align', array(
            'default' => 'align-center',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control(new fik_2012_Customize_Head_Align_Control($wp_customize, 'fik_head_align', array(
                    'label' => __('Site Header Alignment', 'twentytwelve'),
                    'section' => 'title_tagline',
                    'settings' => 'fik_head_align',
                        )));
        $wp_customize->add_section('fik_2012_home_options', array(
            'title' => __('Front Page Options', 'twentytwelve'),
            'priority' => 100,
        ));
        $wp_customize->add_setting('fik_product_thumb_type', array(
            'default' => 'fik2012-thumb-sq',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control(new fik_2012_Customize_Thumb_Select_Control($wp_customize, 'fik_product_thumb_type', array(
                    'label' => __('Product Thumbnail type', 'twentytwelve'),
                    'section' => 'fik_2012_home_options',
                    'settings' => 'fik_product_thumb_type',
                        )));
        $wp_customize->add_setting('fik_home_section', array(
            'default' => '',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control(new fik_2012_Customize_Section_Select_Control($wp_customize, 'fik_home_section', array(
                    'label' => __('Products to display in home', 'twentytwelve'),
                    'section' => 'fik_2012_home_options',
                    'settings' => 'fik_home_section',
                        )));
        $wp_customize->add_setting('fik_home_product_amount', array(
            'default' => '10',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control(new fik_2012_Customize_amount_input_Control($wp_customize, 'fik_home_product_amount', array(
                    'label' => __('Number of products in home', 'twentytwelve'),
                    'section' => 'fik_2012_home_options',
                    'settings' => 'fik_home_product_amount',
                        )));
        $wp_customize->add_setting('fik_home_category', array(
            'default' => '',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control(new fik_2012_Customize_Category_Select_Control($wp_customize, 'fik_home_category', array(
                    'label' => __('Posts to display in home', 'twentytwelve'),
                    'section' => 'fik_2012_home_options',
                    'settings' => 'fik_home_category',
                        )));
        $wp_customize->add_setting('fik_home_post_amount', array(
            'default' => '2',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control(new fik_2012_Customize_amount_input_Control($wp_customize, 'fik_home_post_amount', array(
                    'label' => __('Number of blog posts in home', 'twentytwelve'),
                    'section' => 'fik_2012_home_options',
                    'settings' => 'fik_home_post_amount',
                        )));
    }

    add_action('customize_register', 'fik_2012_customize_register');

    /*
     * Add the selected Title font from Google fonts to the header of the page
     */
    add_action('wp_head', function() {
                switch (get_theme_mod('fik_head_font', 'Open Sans')) {
                    case 'Kotta One':
                        echo("<link href='https://fonts.googleapis.com/css?family=Kotta+One' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Ubuntu Mono':
                        echo("<link href='https://fonts.googleapis.com/css?family=Ubuntu+Mono' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Macondo Swash Caps':
                        echo("<link href='https://fonts.googleapis.com/css?family=Macondo+Swash+Caps' rel='stylesheet' type='text/css'>");
                        break;
                    case 'PT Sans':
                        echo("<link href='https://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Poiret One':
                        echo("<link href='https://fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Caesar Dressing':
                        echo("<link href='https://fonts.googleapis.com/css?family=Caesar+Dressing' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Gudea':
                        echo("<link href='https://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Josefin Sans':
                        echo("<link href='https://fonts.googleapis.com/css?family=Josefin+Sans' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Skranji':
                        echo("<link href='https://fonts.googleapis.com/css?family=Skranji' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Engagement':
                        echo("<link href='https://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Libre Baskerville':
                        echo("<link href='https://fonts.googleapis.com/css?family=Libre+Baskerville' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Convergence':
                        echo("<link href='https://fonts.googleapis.com/css?family=Convergence' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Fontdiner Swanky':
                        echo("<link href='https://fonts.googleapis.com/css?family=Fontdiner+Swanky' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Clicker Script':
                        echo("<link href='https://fonts.googleapis.com/css?family=Clicker+Script' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Sacramento':
                        echo("<link href='https://fonts.googleapis.com/css?family=Sacramento' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Krona One':
                        echo("<link href='https://fonts.googleapis.com/css?family=Krona+One' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Audiowide':
                        echo("<link href='https://fonts.googleapis.com/css?family=Audiowide' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Averia Libre':
                        echo("<link href='https://fonts.googleapis.com/css?family=Averia+Libre' rel='stylesheet' type='text/css'>");
                        break;
                    case 'McLaren':
                        echo("<link href='https://fonts.googleapis.com/css?family=McLaren' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Cutive':
                        echo("<link href='https://fonts.googleapis.com/css?family=Cutive' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Scada':
                        echo("<link href='https://fonts.googleapis.com/css?family=Scada' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Play':
                        echo("<link href='https://fonts.googleapis.com/css?family=Play' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Bree Serif':
                        echo("<link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Press Start 2P':
                        echo("<link href='https://fonts.googleapis.com/css?family=Press+Start+2P' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Sancreek':
                        echo("<link href='https://fonts.googleapis.com/css?family=Sancreek' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Homemade Apple':
                        echo("<link href='https://fonts.googleapis.com/css?family=Homemade+Apple' rel='stylesheet' type='text/css'>");
                        break;
                    case 'Dr Sugiyama':
                        echo("<link href='https://fonts.googleapis.com/css?family=Dr+Sugiyama' rel='stylesheet' type='text/css'>");
                        break;
                    default:
                        return;
                        break;
                }
            });

    /*
     *  Load the Custom CSS introduced by the store admin in the customizer
     */


    /*
     * Add the Continue Reading link at the end of an excerpt
     */

    function new_excerpt_more($more) {
        global $post;
        return ' [...] <a href="' . get_permalink($post->ID) . '">' . __('Continue reading <span class="meta-nav">&rarr;</span>', 'twentytwelve') . '</a>';
    }

    add_filter('excerpt_more', 'new_excerpt_more');
