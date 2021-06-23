<?php

/*

Plugin Name: Cart PDF Enhanced

Description: Enhance the Cart PDF document

Version: 1.0.0

Author: Derek Olalehe

Author URI: https://codeable.io/developers/derek-olalehe/

License: GPL2

*/



/* Copyright 2021 BluePlanetB2B

This program is free software; you can redistribute it and/or modify

it under the terms of the GNU General Public License, version 2, as

published by the Free Software Foundation.



This program is distributed in the hope that it will be useful,

but WITHOUT ANY WARRANTY; without even the implied warranty of

MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the

GNU General Public License for more details.



You should have received a copy of the GNU General Public License

along with this program; if not, write to the Free Software

Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

*/



// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {

    die;

}

require_once( plugin_dir_path( __FILE__ ) . 'class-cart-pdf-enhanced.php' );

CartPDFEnhanced::get_instance();

register_activation_hook( __FILE__, 'cart_pdf_enhanced_activations' );

function cart_pdf_enhanced_activations(){


}


register_deactivation_hook( __FILE__, 'cart_pdf_enhanced_deactivations' );


function cart_pdf_enhanced_deactivations(){    
    

}


?>