<?php

function store_shipping_label(){
    $_SESSION[ 'cart_pdf_shipping_label' ] = $_POST[ 'shipping_label' ];
}

?>