<?php

/**

* Cart PDF Enhanced plugin class file.

*

* @package Cart PDF Enhanced

* @author Derek Olalehe

* @license GPL2

* @copyright 2021

*/

if (!session_id()) {

    session_start();

}

class CartPDFEnhanced {

    protected static $version = '1.0.0';

    protected static $plugin_slug = 'cart-pdf-enhanced';

    protected static $instance = null;

    private function __construct() {

        function cart_pdf_enhanced_scripts_styles() {

            wp_enqueue_script('jquery','', false, true );

            //Make ajax url available on the front end
            $protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';        

            $params = array(

                'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
                'home_url' => home_url(),
                'theme_url' => get_template_directory_uri(),
                'plugins_url' => plugins_url(),

            );            

            if( is_cart() ){

                wp_enqueue_script( 'cart-pdf-enhanced', plugins_url( 'cart-pdf-enhanced.js', __FILE__ ), array(), '1.0.0', true);

                wp_localize_script( 'cart-pdf-enhanced', 'cart_pdf_urls', $params ); 

            }       

        }

        add_action( 'wp_enqueue_scripts', 'cart_pdf_enhanced_scripts_styles' );  

        function add_cart_pdf_button() {	
            if( current_user_can( 'manage_options' ) ){
            ?>
        
            <a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'cart-pdf' => '1' ), wc_get_cart_url() ), 'cart-pdf' ) ); ?>"
            style="margin: 25px 0; background-color: #f2f2f2;" class="cart-pdf-button button" target="_blank">
                <?php esc_html_e( get_option( 'wc_cart_pdf_button_label', __( 'Download Cart as PDF', 'wc-cart-pdf' ) ) ); ?>
            </a>
        
            <?php
            }
        }
        add_action( 'woocommerce_review_order_after_submit', 'add_cart_pdf_button', 10 );  
        
        function save_pre_order_details( $post_data ) {
        
            $output = array();
        
            $vars = explode('&', $post_data);
        
            foreach ($vars as $k => $value){
        
                $v = explode('=', urldecode($value));
        
                $output[$v[0]] = $v[1];
        
            }
        
            $pre_order_details = array();  
            
            $pre_order_details['billing_first_name'] = $output[ 'billing_first_name' ];
            $pre_order_details['billing_last_name'] = $output[ 'billing_last_name' ];
            $pre_order_details['billing_company_name'] = $output[ 'billing_company' ];
            $pre_order_details['billing_address_1'] = $output[ 'billing_address_1' ];
            $pre_order_details['billing_address_2'] = $output[ 'billing_address_2' ];
            $pre_order_details['billing_city'] = $output[ 'billing_city' ];
            $pre_order_details['billing_state'] = $output[ 'billing_state' ];
            $pre_order_details['billing_post_code'] = $output[ 'billing_postcode' ];
            $pre_order_details['billing_phone'] = $output[ 'billing_phone' ];
            $pre_order_details['billing_email'] = $output[ 'billing_email' ];

            $pre_order_details['shipping_first_name'] = $output[ 'shipping_first_name' ];
            $pre_order_details['shipping_last_name'] = $output[ 'shipping_last_name' ];
            $pre_order_details['shipping_company_name'] = $output[ 'shipping_company' ];
            $pre_order_details['shipping_address_1'] = $output[ 'shipping_address_1' ];
            $pre_order_details['shipping_address_2'] = $output[ 'shipping_address_2' ];
            $pre_order_details['shipping_city'] = $output[ 'shipping_city' ];
            $pre_order_details['shipping_state'] = $output[ 'shipping_state' ];
            $pre_order_details['shipping_post_code'] = $output[ 'shipping_postcode' ];
            $pre_order_details['shipping_phone'] = $output[ 'shipping_phone' ];
            $pre_order_details['shipping_email'] = $output[ 'shipping_email' ];

            $_SESSION[ 'pre_order_details' ] = serialize( $pre_order_details );
            
        }

        add_action( 'woocommerce_checkout_update_order_review', 'save_pre_order_details' );

        function save_pre_order_fees(){
           
            $_SESSION['fees'] = serialize(WC()->cart->get_fees());
           

        }

        add_action( 'woocommerce_cart_calculate_fees','save_pre_order_fees' );

    }



    public static function get_instance() {    

        if ( null == self::$instance ) {

            self::$instance = new self;

        }
        return self::$instance;        

    }

}

