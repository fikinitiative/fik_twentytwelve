<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	</div><!-- #main .wrapper -->
	<footer id="colophon" role="contentinfo">
                <?php wp_nav_menu( array( 'theme_location' => 'footer_menu', 'menu_class' => 'menu' ) ); ?>
		<div class="site-info">
			<?php the_fikstores_badge(); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->
<?php wp_footer(); ?>
<?php
$custom_js = get_theme_mod( 'fik_theme_js', '' );
if ($custom_js!=='') {
    echo ($custom_js); // <script type="text/javascript" id="fik_custom_js">jQuery(document).ready(function() {});</script>
}
?>
</body>
</html>