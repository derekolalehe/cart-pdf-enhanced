<?php

add_action( 'wcpv_product_vendors_edit_form_fields', 'edit_additional_wcpv_product_vendors_fields', 10, 2 );
 
function edit_additional_wcpv_product_vendors_fields( $term, $taxonomy ) {
 
	$value_dropship = get_term_meta( $term->term_id, 'wcpv_product_vendors_dropship_fee', true );
    $value_moa = get_term_meta( $term->term_id, 'wcpv_product_vendors_moa', true );
 
	echo    '<tr class="form-field">
                <th>
                    <label for="wcpv-product-vendors-dropship-fee">Dropship fee</label>
                </th>
                <td>                
                    <input type="number" step="0.01" min="0" value="' . esc_attr( $value_dropship ) .'" name="wcpv_product_vendors_dropship_fee" id="wcpv-product-vendors-dropship-fee" />
                </td>
            </tr>
            <tr class="form-field">
                <th>
                    <label for="wcpv-product-vendors-moa">Minimum Order Amount</label>
                </th>
                <td>
                    <input type="number" step="0.01" min="0" value="' . esc_attr( $value_moa ) .'" name="wcpv_product_vendors_moa" id="wcpv-product-vendors-moa" />
                </td>
            </tr>';
 
}

add_action( 'created_wcpv_product_vendors', 'save_additional_wcpv_product_vendors_fields' );
add_action( 'edited_wcpv_product_vendors', 'save_additional_wcpv_product_vendors_fields' );
 
function save_additional_wcpv_product_vendors_fields( $term_id ) {
 
	update_term_meta(
		$term_id,
		'wcpv_product_vendors_dropship_fee',
		sanitize_text_field( $_POST[ 'wcpv_product_vendors_dropship_fee' ] )
	);

    update_term_meta(
		$term_id,
		'wcpv_product_vendors_moa',
		sanitize_text_field( $_POST[ 'wcpv_product_vendors_moa' ] )
	);
 
}

?>