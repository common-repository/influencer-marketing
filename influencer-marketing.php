<?php

/*
  Plugin Name: Influencer Marketing
  Plugin URL: https://SwiftMarketing.com/IMPRESS
  Description: Influencer Marketing
  Version: 3.0
  Author: Roger Vaughn, Tejas Hapani
  Author URI: https://SwiftCRM.Com/
  Text Domain: impress-pr
 */

// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
    _e('Hi there!  I\'m just a plugin, not much I can do when called directly.', 'impress-pr');
    exit;
}

define('IMKT_VERSION', '3.0');
define('IMKT__MINIMUM_WP_VERSION', '5.0');
define('IMKT__PLUGIN_URL', plugin_dir_url(__FILE__));
define('IMKT__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('IMKT_PLUGIN_PREFIX', 'imkt_');

register_activation_hook(__FILE__, 'imkt_install');

function imkt_install() {
    if (version_compare($GLOBALS['wp_version'], IMKT__MINIMUM_WP_VERSION, '<')) {
        add_action('admin_notices', 'imkt_version_admin_notice');

        function imkt_version_admin_notice() {
            echo '<div class="notice notice-error is-dismissible sc-admin-notice"><p>' . sprintf(esc_html__('Influencer Marketing %s requires WordPress %s or higher.', 'impress-pr'), IMKT_VERSION, IMKT__MINIMUM_WP_VERSION) . '</p></div>';
        }

        add_action('admin_init', 'imkt_deactivate_self');

        function imkt_deactivate_self() {
            if (isset($_GET["activate"]))
                unset($_GET["activate"]);
            deactivate_plugins(plugin_basename(__FILE__));
        }

        return;
    }
    update_option('influencer_marketing_version', IMKT_VERSION);
    imkt_preload_options();

    flush_rewrite_rules();
}

//Load admin modules
require_once('public/section/imkt-preload-data.php');
require_once('admin/influencer-marketing-admin.php');
require_once('imkt-pagetemplater.php');

/**
 *       plugin load
 * 
 */
add_action('plugins_loaded', 'imkt_update_check_callback');
if (!function_exists('imkt_update_check_callback')) {

    function imkt_update_check_callback() {
        if (get_option("influencer_marketing_version") != IMKT_VERSION) {
            imkt_install();
        }
    }

}

add_action('upgrader_process_complete', 'imkt_update_process');
if (!function_exists('imkt_update_process')) {

    function imkt_update_process($upgrader_object, $options = '') {
        $current_plugin_path_name = plugin_basename(__FILE__);

        if (isset($options) && !empty($options) && $options['action'] == 'update' && $options['type'] == 'plugin' && $options['bulk'] == false && $options['bulk'] == false) {
            foreach ($options['packages'] as $each_plugin) {
                if ($each_plugin == $current_plugin_path_name) {
                    imkt_install();
                    imkt_initial_data();
                }
            }
        }
    }

}

/**
 *      Deactive plugin
 */
register_deactivation_hook(__FILE__, 'imkt_deactive_plugin');
if (!function_exists('imkt_deactive_plugin')) {

    function imkt_deactive_plugin() {
        flush_rewrite_rules();
    }

}

/**
 *      Uninstall plugin
 */
register_uninstall_hook(__FILE__, 'imkt_uninstall_callback');
if (!function_exists('imkt_uninstall_callback')) {

    function imkt_uninstall_callback() {
        global $wpdb;

        delete_option("influencer_marketing_version");
        delete_option("imkt_notice");

        // delete pages
        $pages = get_option('imkt_pages');
        if ($pages) {
            $pages = explode(",", $pages);
            foreach ($pages as $pid) {
                wp_delete_post($pid, true);
            }
        }
        delete_option("imkt_pages");

        /* Drop tables */
        $table_influencers = $wpdb->prefix . 'imkt_influencers';
        $wpdb->query($wpdb->prepare("DROP TABLE IF EXISTS %s", $table_influencers));

        /**
         * Delete CPT press release and terms
         */
        // Delete Taxonomy
        foreach (array('press_release_category') as $taxonomy) {
            $wpdb->delete(
                    $wpdb->term_taxonomy, array('taxonomy' => $taxonomy)
            );
        }

        // Delete CPT posts
        $wpdb->query("DELETE FROM $wpdb->posts WHERE post_type IN ('press_release')");
        $wpdb->query("DELETE meta FROM $wpdb->postmeta meta LEFT JOIN $wpdb->posts posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL");

        // Deregister CPT
        if (function_exists('unregister_post_type')) {
            unregister_post_type('press_release');
        }
    }

}

/**
 *      Register Location Navigations.
 */
add_action('init', 'imkt_register_menu');
if (!function_exists('imkt_register_menu')) {

    function imkt_register_menu() {
        register_nav_menus(array(
            'menu-press-media-kit' => __('Press Media Kit', 'influencer-marketing'),
        ));
    }

}
/**
 *         Add sidebar
 */
add_action('widgets_init', 'imkt_widget_init');
if (!function_exists('imkt_widget_init')) {

    function imkt_widget_init() {
        register_sidebar(array(
            'name' => __('Press Room Sidebar', 'impress-pr'),
            'id' => 'imkt-pr-sidebar',
            'description' => __('Add widgets here to appear in press release sidebar', 'impress-pr'),
            'before_widget' => '<div class="pr-widget-border pr-m-b-15">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="pr-widget-title">',
            'after_title' => '</h3>',
        ));
    }

}
/**
 *      Enqueue scripts and styles.
 */
add_action('wp_enqueue_scripts', 'imkt_enqueue_scripts_styles');
if (!function_exists('imkt_enqueue_scripts_styles')) {

    function imkt_enqueue_scripts_styles() {
        wp_enqueue_style('imkt-custom', plugins_url('public/css/imkt-style.css', __FILE__), '', '', '');
        wp_enqueue_style('swiftcloud-plugin-tooltip', plugins_url('public/css/swiftcloud-tooltip.css', __FILE__), '', '', '');
        wp_enqueue_style('swiftcloud-fontawesome', plugins_url('public/css/font-awesome.min.css', __FILE__), '', '', '');

        //ajax obj
        //wp_localize_script('imkt-custom-script', 'imkt_ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'file_path' => plugins_url('/section/swift-reviews-callback.php', __FILE__)));
    }

}

include "public/section/imkt-function.php";
include "public/section/imkt-shortcodes.php";
include "public/section/press-release-print.php";

/**
 *      RSS Feeds
 */
add_action('init', 'imkt_rss_feed_init');
if (!function_exists('imkt_rss_feed_init')) {

    function imkt_rss_feed_init() {
        add_feed('press-feed', 'imkt_rss_feed_callback');
    }

}

if (!function_exists('imkt_rss_feed_callback')) {

    function imkt_rss_feed_callback() {
        get_template_part('rss', 'press-feed');
    }

}

// Add press custom post type to feed
function pressfeed_request($qv) {
    if (isset($qv['feed']) && !isset($qv['post_type']))
        $qv['post_type'] = array('post', 'press_release', 'event_marketing', 'swift_jobs', 'vhcard');
    return $qv;
}

add_filter('request', 'pressfeed_request');

function myplugin_auto_update_setting_html($html, $plugin_file, $plugin_data) {
    if ('influencer-marketing/influencer-marketing.php' === $plugin_file) {
        global $status, $page;

        $action = 'enable';

        $auto_updates = (array) get_site_option('auto_update_plugins', array());
//        print_r($plugin_data);
        if (isset($plugin_data['auto-update-forced'])) {
            if ($plugin_data['auto-update-forced']) {
                // Forced on
                $text = __('Auto-updates enabled');
            } else {
                $text = __('Auto-updates disabled');
            }
            $action = 'unavailable';
            $time_class = ' hidden';
        } elseif (!$plugin_data['update-supported']) {
            $text = '';
            $action = 'unavailable';
            $time_class = ' hidden';
        } elseif (in_array($plugin_file, $auto_updates, true)) {
            $text = __('Disable auto-updates');
            $action = 'disable';
            $time_class = '';
        } else {
            $text = __('Enable auto-updates');
            $action = 'enable';
            $time_class = ' hidden';
        }

        $query_args = array(
            'action' => "{$action}-auto-update",
            'plugin' => $plugin_file,
            'paged' => $page,
            'plugin_status' => $status,
        );
        $url = add_query_arg($query_args, 'plugins.php');
        $updateURL = wp_nonce_url($url, 'updates');

//        $updateURL = wp_nonce_url(
//                add_query_arg(
//                        array(
//            'action' => "{$action}-auto-update",
//            'plugin' => $plugin_file,
//            'plugin_status' => $status,
//            'paged' => ((get_query_var('paged')) ? get_query_var('paged') : 1),
////            'plugin_status' => ((isset($_GET['plugin_status']) && !empty($_GET['plugin_status'])) ? esc_attr($_GET['plugin_status']) : "all")
//                        ), admin_url('plugins.php')
//                ), $action . '_' . $slug
//        );
//        echo $plugin_auto_updates_enabled = wp_is_auto_update_enabled_for_type('plugin');
        $html = __('<a href="' . $updateURL . '" class="toggle-auto-update" data-wp-action="'.$action.'">
    <span class="dashicons dashicons-update spin hidden" aria-hidden="true"></span>
    <span class="label">Enable auto-updates</span>
</a>', 'impress-pr');
    }

    return $html;
}

//add_filter('plugin_auto_update_setting_html', 'myplugin_auto_update_setting_html', 10, 3);
