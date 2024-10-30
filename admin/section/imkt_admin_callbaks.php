<?php

/*
 *      Ajax callback functions
 */

/* edit feeds */
add_action('wp_ajax_imkt_edit_feeds', 'imkt_edit_feeds_callback');
add_action('wp_ajax_nopriv_imkt_edit_feeds', 'imkt_edit_feeds_callback');
if (!function_exists('imkt_edit_feeds_callback')) {

    function imkt_edit_feeds_callback() {
        check_ajax_referer('imkt_feed_action', 'imkt_feed_action');
        if (isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == 'imkt_edit_feeds') {
            global $wpdb;
            $table_influencers = $wpdb->prefix . 'imkt_influencers';
            $feed_id = sanitize_text_field($_POST['data']);

            if (!empty($feed_id)) {
                $get_feed_result = $wpdb->get_row("SELECT * FROM `$table_influencers` WHERE `ifns_id`=$feed_id", ARRAY_A);
                print_r(json_encode($get_feed_result));
            }
        }
        wp_die();
    }

}
?>