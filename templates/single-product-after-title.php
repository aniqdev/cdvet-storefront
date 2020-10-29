<?php

global $product;
?>
<div class="cs-product-title-wrapper">
	<h1 class="cs-product-title"><?= $product->get_title(); ?></h1>
	<div class="cs-product-sku">SKU: <br> 12345</div>
</div>
<ul class="cs-variants">
<?php foreach ($product->get_available_variations() as $key => $variation): ?>
	<li class="cs-varianta">
		<form class="cs-variant" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>">
			<div class="price">€<?= $variation['display_regular_price']; ?></div>
			<div class="attr"><?= str_replace('-', '', $variation['attributes']['attribute_pa_size']); ?></div>
			<select name="quantity" class="amo">
				<option name="1" value="1" id="" selected>1st.</option>
				<option name="2" value="2" id="">2st.</option>
				<option name="3" value="3" id="">3st.</option>
				<option name="4" value="4" id="">4st.</option>
				<option name="5" value="5" id="">5st.</option>
				<option name="6" value="6" id="">6st.</option>
				<option name="7" value="7" id="">7st.</option>
				<option name="8" value="8" id="">8st.</option>
				<option name="9" value="9" id="">9st.</option>
				<option name="10" value="10" id="">10st.</option>
			</select>
			<button type="submit" class="buy-btn">In den Warenkorb</button>
			<input type="hidden" name="attribute_pa_size" value="<?= $variation['attributes']['attribute_pa_size']; ?>">
			<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
			<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
			<input type="hidden" name="variation_id" class="variation_id" value="<?= $variation['variation_id'] ?>" />
		</form>
	</li>
<?php endforeach; ?>
</ul>

<?php
// echo "<pre>";
// print_r($product->get_available_variations())
// echo "</pre>";
?>
