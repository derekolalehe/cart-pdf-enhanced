jQuery(document).ready(function(){

//Creta a Quote click
jQuery('#create-a-quote').click(function(){

    if(jQuery(this).hasClass('creating-quote')){
        //Show price markup boxes
        jQuery('td.price-markup > input').css('display','inline');
        jQuery(this).addClass('removing-quote');
        jQuery(this).removeClass('creating-quote');
    }
    else if(jQuery(this).hasClass('removing-quote')){
        //Hide price markup boxes
        jQuery('td.price-markup > input').css('display','none');
        jQuery(this).addClass('creating-quote');
        jQuery(this).removeClass('removing-quote');

        //Remove markup session variable
        removeSessionMarkup();
    }

});

});

function removeSessionMarkup(){


    
}