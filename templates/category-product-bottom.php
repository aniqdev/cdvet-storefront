<?php
global $product;

if ($product->is_type('variable')):
?>
<div class="loop-product-bottom" id="cs_product_<?= $product->get_id(); ?>">
	<div class="product-price">
		€<?= $product->get_variation_regular_price(); ?>
	</div>
	<div class="product-variables">
		<select name="" id="">
			<?php foreach ($product->get_available_variations() as $key => $variation): ?>
				<option 
					name="<?= $variation['variation_id']; ?>"
					value="<?= $variation['attributes']['attribute_pa_size']; ?>"
					><?= str_replace('-', '', $variation['attributes']['attribute_pa_size']); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="product-add-to-cart-btn">
		<div style="display: flex;">
			<?php
				echo '<a href="" class="button product_type_simple add_to_cart_button ajax_add_to_cart"></a>';
			?>
			<form class="cs-variant" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>">
				<button type="submit" class="buy-btn">to cart</button>
				<input type="hidden" name="attribute_pa_size" class="cs-variation-attr"> value="<?php echo '' //$variation['attributes']['attribute_pa_size']; ?>">
				<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
				<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
				<input type="hidden" name="quantity" value="1" />
				<input type="hidden" name="variation_id" class="cs-variation-id" value="<?php //$variation['variation_id'] ?>" />
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