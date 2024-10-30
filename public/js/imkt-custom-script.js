jQuery(document).ready(function() {
    if (jQuery(".pr-detail-page-wrap").length > 0) {
        var nav = jQuery("body").find("nav").css("position");
        var header = jQuery("body").find("header").css("position");
        var padding = '';

        if (header === 'fixed') {
            padding = jQuery("body").find("header").height();
        } else if (nav === 'fixed') {
            padding = jQuery("body").find("nav").height();
        }
        if (padding !== '') {
            jQuery(".pr-page-wrap").css('padding-top', padding);
        }

        var artical_location = jQuery(".press-content-location").text();
        var artical_date = jQuery(".press-content-date").text();
        jQuery(".pr-content p").eq(0).prepend(artical_location + artical_date);
    }
});