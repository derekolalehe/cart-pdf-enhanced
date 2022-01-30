<?php

function store_shipping_label(){
    $_SESSION[ 'cart_pdf_shipping_label' ] = $_POST[ 'shipping_label' ];
}

function save_quote_details(){

    $_SESSION[ 'quote_details' ] = $_POST[ 'quote_details' ];

    wp_die();

}

?>