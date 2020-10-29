<?php
/**
 * Template Name: Setka
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package storefront
 */

get_header(); ?>
<style>
.setka-li{
	float: left;
	width: 2%;
	list-style: none;
}
.setka-img{

}
</style>
	<div id="primary" class="content-area" style="width: 100%;">
		<main id="main" class="site-main" role="main" file="<?= basename(__FILE__) ?>">
<ul class="products">
	
			<?php
$products = wc_get_products( ['limit'=>700] );
// echo "<pre>";
foreach ($products as $key => $product) {
	if ( $product->get_image_id() ) {
		$post_thumbnail_id = $product->get_image_id();
		$image_attributes = wp_get_attachment_image_src( $post_thumbnail_id );
		// echo '<img class="setka-img" src="'. $image_attributes[0] .'">';
	}
	?>
<li class="setka-li">
	<a class="setka-link" href="/<?= $product->get_slug() ?>/">
		<img src="<?= $image_attributes[0] ?>" alt="">
	</a>
</li>
	<?php
	// print_r($product);
}
// echo "</pre>";
			?>

</ul>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
// do_action( 'storefront_sidebar' );
get_footer();
