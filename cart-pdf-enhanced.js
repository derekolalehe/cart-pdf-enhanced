jQuery(document).ready(function(){

    jQuery('a.cart-pdf-button').remove();
    
    //Get chosen shpping method
    jQuery('body').delegate('input.shipping_method', 'click', function(){

        var shipping_label = jQuery(this).closest('li').find('label').text().split(':')[0];

        jQuery.ajax({

            type: "POST",
            url: cart_pdf_urls.ajaxurl,
            data: {
                action: 'store_shipping_label',
                shipping_label: shipping_label,
            },
            success: function(data) {  
                
            },
            error: function(){
                
            }
    
        });

    });

});