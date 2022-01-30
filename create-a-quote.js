jQuery(document).ready(function(){

//Create a Quote click
jQuery('body').delegate('#create-a-quote', 'click', function(e){

    e.preventDefault();

    if(jQuery(this).hasClass('creating-quote')){
        //Add price markup boxes
        jQuery('table.shop_table.woocommerce-checkout-review-order-table > thead > tr').append(
            '<th><strong>Client Cost</strong></th>'
        );    
        jQuery('tr.cart_item').append(
            '<td class="quote-val-cell"><input style="width: 80px; font-size: 14.25px;" type="text" class="quote-val quote-line-item"/></td>'
        );
        jQuery('tr.cart-subtotal').append(
            '<td class="quote-val-cell"><input disabled style="width: 80px; font-size: 14.25px;" type="text" class="quote-val quote-subtotal"/></td>'
        );
        jQuery('tr.order-total').append(
            '<td class="quote-val-cell"><input disabled style="width: 80px; font-size: 14.25px;" type="text" class="quote-val quote-total"/></td>'
        );
        jQuery(this).addClass('removing-quote');
        jQuery(this).removeClass('creating-quote');
        jQuery('#create-a-quote').text('Remove Quote');
        jQuery('#download-quote').css('display', 'inline');

        if(Object.keys(markupData).length == 0){

            var line_items = jQuery('tr.cart_item').get();
            
            for(i=0;i<line_items.length;i++){

                var placeholder_amt = jQuery(line_items[i]).find('td.product-total > span > bdi').text();

                jQuery(line_items[i]).find('td.quote-val-cell > input').val(placeholder_amt);

            }

            jQuery('input.quote-val.quote-subtotal').val(jQuery('tr.cart-subtotal > td:first-of-type span > bdi').text());

            jQuery('input.quote-val.quote-total').val(jQuery('tr.order-total > td:first-of-type span > bdi').text());

        }
        else {

            var quoteVals = jQuery('input.quote-val.quote-line-item').get();

            var lineValTotals = 0;

            for(i=0;i<quoteVals.length;i++){

                jQuery(quoteVals[i]).val(markupData['lineitems'][i]);

                var lineVal = parseFloat(markupData['lineitems'][i].replace(/,/g, '').replace('$', ''));
        
                lineValTotals += lineVal;

            }

            var origSubTotal = parseFloat(jQuery('tr.cart-subtotal bdi').text().replace(/,/g, '').replace('$', ''));

            var origTotal = parseFloat(jQuery('tr.order-total bdi').text().replace(/,/g, '').replace('$', ''));

            var dynamicTotals = origTotal - origSubTotal;

            var newSubTotal = '$' + lineValTotals.toFixed(2).toString();

            var newTotal = '$' + (lineValTotals + dynamicTotals).toFixed(2).toString();

            jQuery('input.quote-val.quote-subtotal').val(markupData['subtotal']);

            jQuery('input.quote-val.quote-total').val(newTotal);

        }

    }
    else if(jQuery(this).hasClass('removing-quote')){

        //Remove price markup boxes
        jQuery('table.shop_table.woocommerce-checkout-review-order-table > thead > tr > th:last-child').remove();
        jQuery('td.quote-val-cell').remove();
        jQuery(this).addClass('creating-quote');
        jQuery(this).removeClass('removing-quote');
        jQuery('#create-a-quote').text('Create a Quote');
        jQuery('#download-quote').css('display', 'none');

        //Remove quote deyails
        removeQuoteDetails();

    }


    

});

//Download Quote click
jQuery('body').delegate('#download-quote', 'click', function(e){

    e.preventDefault();

    var woo_block = '.woocommerce-checkout-payment, .woocommerce-checkout-review-order-table';

    jQuery(woo_block).block({
        message: null,
        overlayCSS: {
            background: "#fff",
            opacity: .6
        }
    });

    var quote_details_raw = {};
    var quote_details_lines_raw = [];

    var line_quotes = jQuery('input.quote-val.quote-line-item').get();

    for(i=0;i<line_quotes.length;i++){

        quote_details_lines_raw.push(jQuery(line_quotes[i]).val());

    }

    quote_details_raw['lines'] = quote_details_lines_raw;
    quote_details_raw['subtotal'] = jQuery('input.quote-val.quote-subtotal').val();
    quote_details_raw['total'] = jQuery('input.quote-val.quote-total').val();

    jQuery.ajax({

        type: "POST",
        url: cart_pdf_urls.ajaxurl,
        data: {
            action: 'save_quote_details',
            quote_details: JSON.stringify(quote_details_raw),
        },
        success: function(data) {  

            jQuery(woo_block).unblock();
            window.location = cart_pdf_urls.pdf_url;
                            
        },
        complete: function(){

        },
        error: function(){
            $(a).unblock();
        }

    });

});

var markupData = {};

//Change a quote value
jQuery('body').delegate('input.quote-val', 'input propertychange paste', function(){

    var markupLineItemVals = new Array();

    var quoteVals = jQuery('input.quote-val.quote-line-item').get();

    var lineValTotals = 0;

    for(i=0;i<quoteVals.length;i++){

        var lineVal = parseFloat(jQuery(quoteVals[i]).val().replace(/,/g, '').replace('$', ''));

        lineValTotals += lineVal;

        markupLineItemVals.push(jQuery(quoteVals[i]).val());

    }

    markupData['lineitems'] = markupLineItemVals;

    var origSubTotal = parseFloat(jQuery('tr.cart-subtotal bdi').text().replace(/,/g, '').replace('$', ''));

    var origTotal = parseFloat(jQuery('tr.order-total bdi').text().replace(/,/g, '').replace('$', ''));

    var dynamicTotals = origTotal - origSubTotal;

    var newSubTotal = '$' + lineValTotals.toFixed(2).toString();

    var newTotal = '$' + (lineValTotals + dynamicTotals).toFixed(2).toString();

    jQuery('input.quote-val.quote-subtotal').val(newSubTotal);

    jQuery('input.quote-val.quote-total').val(newTotal);

    markupData['subtotal'] = newSubTotal;

    markupData['total'] = newTotal;

});

});

function removeSessionMarkup(){


    
}