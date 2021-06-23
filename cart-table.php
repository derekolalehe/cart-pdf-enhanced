<?php
/**
 * WC Cart PDF template
 *
 * @package wc-cart-pdf
 */

/**
 * Before template hook
 *
 * @since 1.0.4
 */
do_action( 'wc_cart_pdf_before_template' );

global $woocommerce;

$customer = new \WC_Customer( get_current_user_id() );

$pre_order_details = unserialize( $_SESSION[ 'pre_order_details' ] );

$logo = parse_url( get_option( 'wc_cart_pdf_logo', get_option( 'woocommerce_email_header_image' ) ), PHP_URL_PATH );
$logo = ! $logo ? '' : $_SERVER['DOCUMENT_ROOT'] . $logo;

?>
<table id="cart-header">
	<tr>
		<td style="text-align: center;">
			<?php
			if ( file_exists( $logo ) ) {
				printf(
					'<div id="template_header_image"><p style="margin-top: 0; text-align: %s;"><img src="%s" style="width: %s;" alt="%s" /></p></div>',
					get_option( 'wc_cart_pdf_logo_alignment', 'center' ),
					esc_url( $logo ),
					get_option( 'wc_cart_pdf_logo_width' ) ? esc_attr( get_option( 'wc_cart_pdf_logo_width' ) ) . 'px' : 'auto',
					esc_attr( get_bloginfo( 'name', 'display' ) )
				);
			}
			?>
			<p>5720 South Arville - Suite #102, Las Vegas, NV 89119</p>
			<p>Phone: 702-222-2103 - EMAIL: info@blueplanetlighting.com</p>	
		</td>		
		<td style="text-align: center;">
			<h2>Date: <?php echo esc_html( gmdate( get_option( 'date_format' ) ) ); ?></h2>
			<h2>Quote#: <?php echo (string)$customer->get_id() . (string)rand(1000, 5000);?></h2>
			<p>Sales Rep:</p>
			<p>
				<?php 
					$csr_id = get_user_meta( $customer->get_id(), '_customer_csr', true ) ;
					$csr_name = get_the_title( $csr_id );					
				?>
				<strong><?php echo $csr_name;?></strong>
			</p>
			<p>702-222-2103</p>
			<p><srong><?php echo get_post_meta( $csr_id, 'woo_csr_sales_rep_email', true )?></srong></p>
		</td>
	</tr>
</table>
<hr/>
<table style="width: 100%;" id="cart-addresses">
	<tr>
		<td style="width: 50%; vertical-align: top;">
			<p style="color: #29AAE1; font-weight: 700;">BILL TO:</p>
			<p>
				<span><?php echo $pre_order_details['billing_company_name'];?></span><br/>
				<span><?php echo $pre_order_details['billing_first_name'] . ' ' . $pre_order_details['billing_last_name'];?></span><br/>
				<span><?php echo $pre_order_details['billing_address_1'] . ', ' . $pre_order_details['billing_address_2'];?></span><br/>
				<span><?php echo $pre_order_details['billing_city'] . ', ' . $pre_order_details['billing_state'] . ', ' . $pre_order_details['billing_postcode'];?></span><br/>
				<span><?php echo $pre_order_details['billing_phone'];?></span><br/>
				<span><?php echo $pre_order_details['billing_email'];?></span><br/>
			</p>
		</td>
		<td style="width: 50%; vertical-align: top;">
			<p style="color: #29AAE1; font-weight: 700;">SHIP TO:</p>
			<?php if( array_key_exists( "ship_to_different_address", $output ) ): ?>
			<p>
				<span><?php echo $pre_order_details['shipping_company_name'];?></span><br/>
				<span><?php echo $pre_order_details['shipping_first_name'] . ' ' . $pre_order_details['shipping_last_name'];?></span><br/>
				<span><?php echo $pre_order_details['shipping_address_1'] . ', ' . $pre_order_details['shipping_address_2'];?></span><br/>
				<span><?php echo $pre_order_details['shipping_city'] . ', ' . $pre_order_details['shipping_state'] . ', ' . $pre_order_details['shipping_postcode'];?></span><br/>
				<span><?php echo $pre_order_details['shipping_phone'];?></span><br/>
				<span><?php echo $pre_order_details['shipping_email'];?></span><br/>
			</p>
			<?php else:?>
			<p>
				<span><?php echo $pre_order_details['billing_company_name'];?></span><br/>
				<span><?php echo $pre_order_details['billing_first_name'] . ' ' . $pre_order_details['billing_last_name'];?></span><br/>
				<span><?php echo $pre_order_details['billing_address_1'] . ', ' . $pre_order_details['billing_address_2'];?></span><br/>
				<span><?php echo $pre_order_details['billing_city'] . ', ' . $pre_order_details['billing_state'] . ', ' . $pre_order_details['billing_postcode'];?></span><br/>
				<span><?php echo $pre_order_details['billing_phone'];?></span><br/>
				<span><?php echo $pre_order_details['billing_email'];?></span><br/>
			</p>
			<?php endif;?>
		</td>
	</tr>
</table>
<hr/>

<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
	<thead>
		<tr>
			<th class="product-thumbnail">&nbsp;</th>
			<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
			<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
			<th class="product-subtotal"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<td class="product-thumbnail">
					<?php
					$thumbnail = apply_filters(
						'woocommerce_cart_item_thumbnail',
						$_product->get_image(
							'woocommerce_thumbnail',
							array(
								'width'  => 60,
								'height' => 'auto',
							)
						),
						$cart_item,
						$cart_item_key
					);

					if ( ! $product_permalink ) {
						echo $thumbnail; // PHPCS: XSS ok.
					} else {
						printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
					}
					?>
					</td>

					<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
					<?php
					if ( ! $product_permalink ) {
						echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
					} else {
						echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
					}

					do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

					// Meta data.
					echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

					// Backorder notification.
					if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
						echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
					}
					?>
					</td>

					<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
						?>
					</td>

					<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
						<?php print esc_html( $cart_item['quantity'] ); ?>
					</td>

					<td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
						?>
					</td>
				</tr>
				<?php
			}
		}
		?>

		<tr class="cart-subtotal cart-total-row">
			<th class="row-subtotal" colspan="4" style="text-align: right;"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			<td class="row-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>
		
		<?php if ( 0 < WC()->cart->get_shipping_total() ) : ?>
			<tr class="shipping cart-total-row">
				<th class="row-subtotal" colspan="4" style="text-align: right;"><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></th>
				<td class="row-subtotal" data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>"><?php echo WC()->cart->get_cart_shipping_total(); ?></td>
			</tr>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?> cart-total-row">
				<th class="row-coupon" colspan="4" style="text-align: right;"><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td class="row-coupon" data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee cart-total-row">
				<th class="row-subtotal" colspan="4" style="text-align: right;"><?php echo esc_html( $fee->name ); ?></th>
				<td class="row-subtotal" data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) :
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
					? sprintf( ' <small>' . __( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] )
					: '';

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) :
				?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?> cart-total-row">
						<th class="row-subtotal" colspan="4" style="text-align: right;"><?php echo esc_html( $tax->label ) . $estimated_text; ?></th>
						<td class="row-subtotal" data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total cart-total-row">
					<th class="row-subtotal" colspan="4" style="text-align: right;"><?php echo esc_html( WC()->countries->tax_or_vat() ) . esc_html( $estimated_text ); ?></th>
					<td class="row-subtotal" data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<tr class="order-total cart-total-row">
			<th class="row-subtotal" colspan="4" style="text-align: right;"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			<td class="row-subtotal" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
	</tbody>
</table>
<div id="template_pre_footer" style="margin: 50px auto; text-align: center; padding: 20px; background-color: #f2f2f2l">
	<p>
		<span style="color: #29AAE1; font-weight: 700;">Payment Options and Fees</span><br/><br/>
		<span>Wire Transfer - $30 Fee</span><br/>
		<span>Paypal - 2.95% Fee</span><br/>
		<span>Credit Card - 2.95% Fee</span><br/>
		<span>Checkout - No Charge, Order Ships When Check Clears</span><br/>
	</p>		
</div>

<?php
/**
 * After template hook
 *
 * @since 1.0.4
 */
do_action( 'wc_cart_pdf_after_template' );
