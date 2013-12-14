<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
<?php
$custom_css = get_theme_mod( 'fik_theme_css', '' );
if ($custom_css!=='') {
    echo ('<style type="text/css" id="fik_custom_css">'.$custom_css.'</style>');
}
?>
</head>
<?php
/*
 *  Add the full-width class to the body when loading a product archive page or a store section page (no sidebar included)
 */
if ( is_tax('store-section') || is_post_type_archive( 'fik_product' ) || (is_single() && ( 'fik_product' == get_post_type() )) || is_page_template( 'page-templates/store-front-page.php' ) || is_page(get_option('fik_cart_page_id'))) {
    add_filter('body_class',function($classes){
	$classes[] = 'full-width';
	return $classes;
    });
}
?>
<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<header id="masthead" class="site-header" role="banner">
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></a>
		<?php endif; ?>
		<hgroup class="<?php echo get_theme_mod( 'fik_head_align', 'align-center' ); ?>">
			<h1 class="site-title" style="font-weight:400; font-family: '<?php echo get_theme_mod( 'fik_head_font', 'Open Sans' ); ?>', Helvetica, Arial, sans-serif; font-size:<?php echo get_theme_mod( 'fik_head_size', '26px' ); ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		</hgroup>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<h3 class="menu-toggle"><?php _e( 'Site Menu', 'twentytwelve' ); ?></h3>
			<a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentytwelve' ); ?>"><?php _e( 'Skip to content', 'twentytwelve' ); ?></a>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
		</nav><!-- #site-navigation -->
                <?php 
                // Add the secondary store menu if it is a store section, product page
                if ( is_tax('store-section') || is_post_type_archive( 'fik_product' ) || (is_single() && ( 'fik_product' == get_post_type() ))) {
                    if (has_nav_menu('store_menu')) {
                ?>
                <nav id="store-navigation" class="main-navigation store-navigation">
			<a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentytwelve' ); ?>"><?php _e( 'Skip to content', 'twentytwelve' ); ?></a>
			<?php wp_nav_menu( array( 'theme_location' => 'store_menu', 'menu_class' => 'nav-menu' ) ); ?>
		</nav><!-- #site-navigation -->
                <?php
                    }
                }
                ?>
	</header><!-- #masthead -->
        <?php echo fik_messages(); ?>
	<div id="main" class="wrapper">