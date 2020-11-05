<?php
global $product;

if ($product->is_type('variable')):
	$variations = $product->get_available_variations();
?>
<div class="loop-product-bottom" id="cs_product_<?= $product->get_id(); ?>">
	<div class="product-price">
		€<?= $product->get_variation_regular_price(); ?>
	</div>
	<div class="product-variables">
		<select name="<?php echo absint( $product->get_id() ); ?>" class="js-variation-select" disabled>
			<?php foreach ($variations as $variation): ?>
				<option value="<?= $variation['variation_id'].'|'.$variation['attributes']['attribute_pa_size']; ?>"
					><?= str_replace('-', '', $variation['attributes']['attribute_pa_size']); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="product-add-to-cart-btn">
		<div style="display: flex;">
			<form class="js-product-<?php echo absint( $product->get_id() ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" style="margin: 0;">
				<button type="submit" class="add_to_cart_button">to cart</button>
				<input type="hidden" name="attribute_pa_size" class="cs-variation-attr js-attr-input" value="<?= $variations[0]['attributes']['attribute_pa_size']; ?>">
				<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>">
				<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>">
				<input type="hidden" name="quantity" value="1" />
				<input type="hidden" name="variation_id" class="cs-variation-id js-varid-input" value="<?= $variations[0]['variation_id']; ?>">
			</form>
		</div>
	</div>
</div>
<?php else: ?>

<div class="loop-product-bottom">
	<div class="product-price">
		€<?= $product->get_regular_price(); ?>
	</div>
	<div class="product-add-to-cart-btn"><div style="display: flex;">
	<?php
		echo sprintf(
			'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
			esc_attr( isset( $args['class'] ) ? $args['class'] : 'button product_type_simple add_to_cart_button ajax_add_to_cart' ),
			isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : 'data-product_id="'.$product->get_id().'"',
			esc_html( $product->add_to_cart_text() )
		);
	?>
	</div></div>
</div>
<?php endif; ?>