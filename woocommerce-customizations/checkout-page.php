<?php

add_action( 'woocommerce_cart_calculate_fees', 'cpe_add_vendor_specific_fees' );
 
function cpe_add_vendor_specific_fees() {

    if ( !WC()->cart->is_empty() ){

        foreach( WC()->cart->get_cart() as $cart_item ){

            $product_id = $cart_item[ 'product_id' ];

            $post_data = get_post( $cart_item[ 'product_id' ] ); 
            $post_data->post_author; 
            $vendor_id = $post_data->post_author;

            $terms = get_the_terms( $vendor_id, 'wcpv_product_vendors' );

            //print_r( $terms );

        }

        //WC()->cart->add_fee( 'Dropship Fees:' . $_SESSION[ 'tax_1_name' ], $_SESSION[ 'checkout_item_tax_1' ], false, '' );
      
    }

}

// add_action( 'woocommerce_checkout_after_customer_details', 'display_nandi_shipping_costing' );

// function display_nandi_shipping_costing(){

//     $total_dropship_fees = 0;

//     if ( !WC()->cart->is_empty() ){

//         foreach( WC()->cart->get_cart() as $cart_item ){

//             $product_id = $cart_item[ 'product_id' ];

//             $post_data = get_post( $cart_item[ 'product_id' ] ); 
//             $post_data->post_author; 
//             $vendor_id = $post_data->post_author;
            
//             $term = wc_get_product_terms( $product_id, 'wcpv_product_vendors' );
//             $term_id = $term[0]->term_id;
//             $vendor_data = get_term_meta( $term_id );
            
//             $total_dropship_fees += (float)$vendor_data[ 'wcpv_product_vendors_dropship_fee' ][0];

//         }
        
//         WC()->cart->add_fee( 'Dropship Fees: ', $total_dropship_fees, false, '' );
      
//     }

// }

add_action('woocommerce_cart_calculate_fees', function() {

    $total_dropship_fees = 0;

    if ( !WC()->cart->is_empty() ){

        foreach( WC()->cart->get_cart() as $cart_item ){

            $product_id = $cart_item[ 'product_id' ];

            $post_data = get_post( $cart_item[ 'product_id' ] ); 
            $post_data->post_author; 
            $vendor_id = $post_data->post_author;
            
            $term = wc_get_product_terms( $product_id, 'wcpv_product_vendors' );
            $term_id = $term[0]->term_id;
            $vendor_data = get_term_meta( $term_id );
            
            $total_dropship_fees += (float)$vendor_data[ 'wcpv_product_vendors_dropship_fee' ][0];

        }
        
        WC()->cart->add_fee( __( 'Dropship Fees: ', 'txtdomain' ), $total_dropship_fees);
      
    }

});

add_filter( 'woocommerce_order_button_html', 'replace_order_button_html', 10, 1 );

function replace_order_button_html( $order_button ) {

    $subMinAmt = checkMOA();
   
    if( $subMinAmt == false ) {
        return $order_button;
    }
    else {        
        return;
    }    

}

add_filter( 'woocommerce_cart_needs_payment', 'toggle_payments_section' );


function toggle_payments_section (){

    $subMinAmt = checkMOA();
   
    if( $subMinAmt == true ) {
        return false;
    }
    else {
        return true;
    }

}

function pdfe_get_item_data( $item_data, $cart_item_data ) {

    $moa = checkMOAByItem( $cart_item_data );
    
    if( $moa > 0 ) {    
            
        $item_data[] = array(
            'key' => __( 'Notice', 'cart-pdf-enhanced' ),
            'value' => '<span style="color: red;">Vendor Minimum Order: $' . (string)number_format((float)$moa, 2, '.', '') . '</span>',
        );

    }
    
    return $item_data;
}

add_filter( 'woocommerce_get_item_data', 'pdfe_get_item_data', 10, 2 );

function checkMOA() {

    $subtotal = 0;

    foreach( WC()->cart->get_cart() as $cart_item ){

        $product = $cart_item['data'];

        $product_id = $cart_item[ 'product_id' ];

        $post_data = get_post( $cart_item[ 'product_id' ] ); 
        $post_data->post_author; 
        $vendor_id = $post_data->post_author;
        
        $term = wc_get_product_terms( $product_id, 'wcpv_product_vendors' );
        $term_id = $term[0]->term_id;
        $vendor_data = get_term_meta( $term_id );

        $subtotal = (float)$cart_item[ 'line_subtotal' ];
        
        $moa = (float)$vendor_data[ 'wcpv_product_vendors_moa' ][0];
        
        if( $moa > $subtotal ){
            $subMinAmt = true;
            break;
        }

    }

    return $subMinAmt;

}

function checkMOAByItem( $item_data ) {

    //Check MOA of a single item
    $subtotal = 0;

    $product_id = $item_data[ 'product_id' ];

    $post_data = get_post( $item_data[ 'product_id' ] ); 
    $post_data->post_author; 
    $vendor_id = $post_data->post_author;
    
    $term = wc_get_product_terms( $product_id, 'wcpv_product_vendors' );
    $term_id = $term[0]->term_id;
    $vendor_data = get_term_meta( $term_id );

    $subtotal = (float)$item_data[ 'line_subtotal' ];
    
    $moa = (float)$vendor_data[ 'wcpv_product_vendors_moa' ][0];
    
    if( $moa > $subtotal ){
        return $moa;
    }
    else {
        return 0;
    }

    
}

?>