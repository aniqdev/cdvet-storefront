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
	include 'templates/category-product-bottom.php';
}
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_after_shop_loop_item_before', 5 );

// удаляем стандартную корзину woocommerce
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
// удаляем стандартную цену woocommerce
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );


function cs_product_after_title()
{
	include 'templates/single-product-after-title.php';
}
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_single_product_summary', 'cs_product_after_title', 6 );

// FOOTER
function cs_footer()
{
	include 'templates/cs-footer.php';
}
add_action( 'storefront_footer', 'cs_footer', 15 );