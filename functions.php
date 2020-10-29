<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';

	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
		require 'inc/nux/class-storefront-nux-starter-content.php';
	}
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */


function woocommerce_after_shop_loop_item_title_short_description() {
	global $product;

	if ( ! $short_description = $product->get_short_description() ) return;
	?>
	<div itemprop="description" class="product-description">
		<?php echo apply_filters( 'woocommerce_short_description', $short_description ) ?>
	</div>
	<?php
}
add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_after_shop_loop_item_title_short_description', 5);

function woocommerce_after_shop_loop_item_title_price() {
	global $product;

	if ( ! $short_description = $product->get_short_description() ) return;
	?>
	<div itemprop="description" class="product-description">
		<?php echo apply_filters( 'woocommerce_short_description', $short_description ) ?>
	</div>
	<?php
}
// add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_after_shop_loop_item_title_price', 5);


function woocommerce_after_shop_loop_item_after()
{
	global $product;
	if ($product->is_type('variable')){
		echo "<pre>";
		foreach ( $product->get_variation_prices() as $attribute_name => $options ){
			echo "<hr>";
			print_r($attribute_name);
			print_r($options);
		}
		echo "</pre>";
	}
	?>
	<h4>After</h4>
	<?php
}
// add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_after_shop_loop_item_after', 15 );


function woocommerce_after_shop_loop_item_before() {
	global $product;

	if ( ! $short_description = $product->get_short_description() ) return;
	?>
	<div class="loop-product-bottom">
		<div class="product-price">
		<?php
			if ($product->is_type('variable')) {
				echo '€'.$product->get_variation_regular_price();
			}else{
				echo '€'.$product->get_regular_price();
			}
		?>
		</div>
		<?php if ($product->is_type('variable')){ ?>
		<div class="product-variables">
			<select name="" id="">
				<option value="">100гр</option>
				<option value="">200гр</option>
				<option value="">300гр</option>
				<option value="">400гр</option>
				<option value="">500гр</option>
			</select>
		</div>
		<?php }	?>
		<div class="product-add-to-cart-btn"><div style="display: flex;">
		<?php
			if ($product->is_type('variable')) {
				echo '<a href="" class="button product_type_simple add_to_cart_button ajax_add_to_cart"></a>';
			}else{
				echo sprintf(
					'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
					esc_url( $product->add_to_cart_url() ),
					esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
					esc_attr( isset( $args['class'] ) ? $args['class'] : 'button product_type_simple add_to_cart_button ajax_add_to_cart' ),
					isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : 'data-product_id="'.$product->get_id().'"',
					esc_html( $product->add_to_cart_text() )
				);
			}
		?>
		</div></div>
	</div>
	<?php
}
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_after_shop_loop_item_before', 5 );

// удаляем стандартную корзину woocommerce
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
// удаляем стандартную цену woocommerce
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );


function cs_product_after_title()
{
	global $product;
	?>
	<h5>After Title</h5>
	<h4><?php //print_r($product->get_variation_prices()); ?></h4>
	<ul class="cs-variants">
	<?php foreach ($product->get_available_variations() as $key => $variation): ?>
		<li class="cs-variant">
			<div class="price">€<?= $variation['display_regular_price']; ?></div>
			<div class="attr"><?= str_replace('-', '', $variation['attributes']['attribute_pa_size']); ?></div>
			<select class="amo">
				<option name="1" id="">1st.</option>
				<option name="2" id="">2st.</option>
				<option name="3" id="">3st.</option>
				<option name="4" id="">4st.</option>
				<option name="5" id="">5st.</option>
				<option name="6" id="">6st.</option>
				<option name="7" id="">7st.</option>
				<option name="8" id="">8st.</option>
				<option name="9" id="">9st.</option>
				<option name="10" id="">10st.</option>
			</select>
			<div class="buy-btn">In den Warenkorb</div>
		</li>
	<?php endforeach; ?>
	</ul>
	<pre>
	<?php //print_r($product->get_available_variations()) ?>
	</pre>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'cs_product_after_title', 6 );