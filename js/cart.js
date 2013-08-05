jQuery(document).ready(function() {
    jQuery('.cart_item_update').hide();  
    jQuery('input[type=number]').focus(function() {
        jQuery('.cart_item_update').hide();
        jQuery(this).siblings('button').show();

    });

    jQuery('input[type=number]').change(function() {
    	if(jQuery('input[type=number]').val() == 0){
	    	jQuery(this).siblings('button').text("Delete");
    	}else{
    		jQuery(this).siblings('button').text("Update");
    	}

    });

    jQuery('input[type=number]').keyup(function() {
    	if(jQuery('input[type=number]').val() == 0){
    		jQuery(this).siblings('button').text("Delete");
    	}else{
    		jQuery(this).siblings('button').text("Update");
    	}

    });
    // Disable form submit after first send
    jQuery('form').submit(function(){
        // On submit disable its submit button
        jQuery('button[type=submit]', this).attr('disabled', 'disabled');
    });

    
});
