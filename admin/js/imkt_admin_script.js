jQuery(document).ready(function($) {
    /* tooltip */
    jQuery(".ttip").tooltip();

    //whitelist toggle
    if (jQuery("#imkt_whitelist_flag").length > 0) {
        jQuery('#imkt_whitelist_flag').lc_switch();
        jQuery('body').delegate('#imkt_whitelist_flag', 'lcs-on', function() {
            jQuery(".imkt_whitelist_textarea").fadeIn();
        });
        jQuery('body').delegate('#imkt_whitelist_flag', 'lcs-off', function() {
            jQuery(".imkt_whitelist_textarea").fadeOut();
        });
    }
    //blacklist toggle
    if (jQuery("#imkt_blacklist_flag").length > 0) {
        jQuery('#imkt_blacklist_flag').lc_switch();
        jQuery('body').delegate('#imkt_blacklist_flag', 'lcs-on', function() {
            jQuery(".imkt_blacklist_textarea").fadeIn();
        });
        jQuery('body').delegate('#imkt_blacklist_flag', 'lcs-off', function() {
            jQuery(".imkt_blacklist_textarea").fadeOut();
        });
    }


    /* plugin activation notice dismis.*/
    jQuery(".imkt-notice .notice-dismiss").on('click', function() {
        var data = {
            'action': 'imkt_dismiss_notice'
        };
        jQuery.post(imkt_admin_ajax_object.ajax_url, data, function(response) {

        });
    });
    // Aadd/Edit feed popup
    jQuery(".imkt-add-new-btn,.imkt-add-new-link,.imkt-edit").on('click', function(e) {
        var modalID = jQuery(this).attr('data-modal');
        var btnType = jQuery(this).attr('data-btn');
        var modalTitleText = btnType === "edit" ? 'Edit Influencer / Media Contact' : 'Add Influencer / Media Contact';
        var modalBtn = btnType === "edit" ? 'Update' : 'Add';
        jQuery(".imkt_modal_title").text('');
        jQuery(".imkt_modal_title").text(modalTitleText);
        jQuery("#imkt_feed_submit").val(modalBtn);
        jQuery("#imkt_feed_submit").text(modalBtn);
        if (btnType === 'edit') {
            e.preventDefault();
            var feed_id = jQuery(this).attr('data-feed-id');
            var data = {
                'action': 'imkt_edit_feeds',
                'data': feed_id,
                'imkt_feed_action': jQuery('#imkt_feed_action').val()
            };
            jQuery.post(ajaxurl, data, function(response) {
                var res = jQuery.parseJSON(response);
                jQuery("#imkt_feed_label").val(res['ifns_name']);
                jQuery("#imkt_feed").val(res['ifns_feed']);
                jQuery("#imkt_whitelist").val(res['ifns_whitelist']);
                jQuery("#imkt_blacklist").val(res['ifns_blacklist']);

                if (res['ifns_feed'] === 'Twitter') {
                    jQuery("#imkt_feed_twitter_url").val(res['ifns_feed_url']);

                    jQuery(".imkt_feed_twitter_wrap").fadeIn();
                    jQuery(".imkt_feed_url_wrap").hide();
                } else {
                    jQuery("#imkt_feed_url").val(res['ifns_feed_url']);

                    jQuery(".imkt_feed_twitter_wrap").hide();
                    jQuery(".imkt_feed_url_wrap").fadeIn();
                }
                /**wihtelist**/
                var whitelist_flag = res['ifns_whitelist_flag'];
                if (whitelist_flag === '1') {
                    jQuery('#imkt_whitelist_flag').lcs_on();
                    jQuery(".imkt_whitelist_textarea").fadeIn();
                } else {
                    jQuery(".imkt_whitelist_textarea").fadeOut();
                }
                /**blacklist **/
                var blacklist_flag = res['ifns_blacklist_flag'];
                if (blacklist_flag === '1') {
                    jQuery('#imkt_blacklist_flag').lcs_on();
                    jQuery(".imkt_blacklist_textarea").fadeIn();
                } else {
                    jQuery(".imkt_blacklist_textarea").fadeOut();
                }
                jQuery("#imkt_feed_submit").before('<input type="hidden" name="ifns_id" id="upd_ifns_id" value="' + res['ifns_id'] + '"  />');
                jQuery(modalID).fadeIn();
            });
        } else {
            jQuery(modalID).fadeIn();
        }
    });
    //modal close
    jQuery(".imkt_modal_close").on('click', function() {
        jQuery('#frm_imkt_feed').trigger("reset");
        jQuery('.edit-hidden').remove();
        jQuery(".imkt-toggle-wrap").fadeOut();
        jQuery("#imkt_feed_modal").fadeOut();
    });
    jQuery(".imkt-delete").on('click', function(e) {
        e.preventDefault();
        if (confirm("Are you sure you want to delete this Feed?")) {
            var del_feed_id = jQuery(this).attr('data-feed-id');
            if (del_feed_id) {
                jQuery(this).after('<input type="hidden" name="feed_id" value="' + del_feed_id + '" />');
                jQuery("#imkt_feeds_frm").submit();
            }
        }
    });

    //  Source type on change
    jQuery("#imkt_feed").on("change", function() {
        var source_val = jQuery(this).val();
        if (source_val === 'Twitter') {
            jQuery("#imkt_feed_url").val('');

            jQuery(".imkt_feed_twitter_wrap").fadeIn();
            jQuery(".imkt_feed_url_wrap").hide();
        } else {
            jQuery("#imkt_feed_twitter_url").val('');

            jQuery(".imkt_feed_twitter_wrap").hide();
            jQuery(".imkt_feed_url_wrap").fadeIn();
        }
    });
}); //main