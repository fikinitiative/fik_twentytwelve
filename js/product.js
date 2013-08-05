function resizegallery() {
    if (jQuery('ul.product-image-thumbnails>li>a').length>0) {
        jQuery('div.product-image-frame img').css('max-width',jQuery('div.product-image-frame').width());
        var minheight = 465;
        jQuery('ul.product-image-thumbnails>li>a').each(function(){
            var thisheight =jQuery(this).data('height');
            if(thisheight<minheight) {
                minheight = thisheight;
            }
        });
        var widthfactor = jQuery('div.product-image-frame').width()/620;
        maxheight = widthfactor*minheight;
        if ((maxheight<348) && (jQuery('ul.product-image-thumbnails a.product-video').length>0)){
            maxheight = 348;
        }
        jQuery('div.product-image-frame img').css('max-height',maxheight);
        jQuery('div.product-image-frame img').css('width','auto');
        jQuery('div.product-image-frame').animate({
            height:maxheight
        },200);
        jQuery('div.product-image-frame').css('line-height',maxheight+"px");
        activatezoom(jQuery('div.product-image-frame img').data('zoomimagewidth'));
    }
}
function activatezoom(zoomimagewidth) {
    jQuery('.zoomContainer').remove();
    if (jQuery('div.product-image-frame img').width() < (zoomimagewidth)*0.77) {
        jQuery('div.product-image-frame img').elevateZoom({
            zoomType : "lens",
            lensShape : "square",
            lensSize : 250
        });
    } else {
        jQuery('.zoomLens').css('opacity','0');
    }
}
jQuery(document).ready(function() {
    if (jQuery('ul.product-image-thumbnails>li>a').length>0) {
        if (jQuery('ul.product-image-thumbnails>li>a').length==1) {
            jQuery('ul.product-image-thumbnails').hide();
        }
        jQuery('div.product-image-frame img').data('zoomimagewidth',jQuery('ul.product-image-thumbnails>li>a').first().data('zoomimagewidth'));
    }
    resizegallery();
    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();
    jQuery(window).resize(function() {
        delay(function(){
            resizegallery();
        }, 500);
    });
    jQuery(document).on('click',".product-gallery .product-image-thumbnails a", function(e) {
        if (jQuery(this).hasClass('product-video')) {
            jQuery('div.product-image-frame').html(jQuery(this).data('embed'));
        } else {
            var newimage = jQuery(this).children('img').clone();
            jQuery(newimage).attr('src', jQuery(this).attr('href'));
            jQuery(newimage).attr('width', 'auto');
            jQuery(newimage).attr('height', 'auto');
            jQuery(newimage).data('zoom-image',jQuery(this).data('zoom-image'));
            jQuery(newimage).data('zoomimagewidth',jQuery(this).data('zoomimagewidth'));
            jQuery('div.product-image-frame').html(newimage);
            jQuery('div.product-image-frame img').css('max-height','0px');
            jQuery('div.product-image-frame img').one('load',function() {
                resizegallery();
            });
        }
        e.preventDefault();
    });   
    // Disable form submit after first send
    jQuery('form').submit(function(){
        // On submit disable its submit button
        jQuery('button[type=submit]', this).attr('disabled', 'disabled');
    });
});
