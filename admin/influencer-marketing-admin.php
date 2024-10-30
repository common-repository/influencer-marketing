<?php
/**
 *  On plugin activation notice
 */
// check is plugin version
if (version_compare($GLOBALS['wp_version'], IMKT_VERSION, '>=')) {
    add_action('admin_notices', 'imkt_admin_notice');
}
if (!function_exists('imkt_admin_notice')) {

    function imkt_admin_notice() {
        if (!get_option('imkt_notice') && !get_option('imkt_pages')) {
            ?>
            <div class="notice notice-warning is-dismissible ">
                <p>You need to save <a href="<?php echo admin_url('options-permalink.php'); ?>">permalinks</a> to work Press Release correctly.</p>
            </div>
            <div class="notice notice-success is-dismissible imkt-notice">
                <p><b>Influencer Marketing Plugin</b></p>
                <form method="post">
                    <p class="imkt-notice-msg"><?php _e('Want to auto-create the following pages to quickly get you set up? ', 'impress-pr'); ?></p>
                    <ul>
                        <li>Press</li>
                        <li>Clippings</li>
                        <li>Media Kit</li>
                    </ul>
                    <?php wp_nonce_field('imkt_autogen_pages', 'imkt_autogen_pages'); ?>
                    <button type="submit" value="yes" name="imkt_autogen_yes" class="button button-green"><i class="fa fa-check"></i> Yes</button>  <button type="submit" name="imkt_autogen_no" value="no" class="button button-default button-red"><i class="fa fa-ban"></i> No</button>
                </form>
            </div>
            <?php
        }
    }

}


/**
 *      Admin Menu
 */
add_action('admin_menu', 'imkt_control_panel');
if (!function_exists('imkt_control_panel')) {

    function imkt_control_panel() {
        $icon_url = plugins_url('/images/swiftcloud.png', __FILE__);
        $menu_capability = 'manage_options';
        $parent_menu_slug = 'imkt_settings';

        add_menu_page('Press Releases', 'Press Releases', $menu_capability, $parent_menu_slug, 'imkt_settings_callback', $icon_url, 27);
        add_submenu_page($parent_menu_slug, "Settings", "Settings", $menu_capability, "imkt_settings", null);
        add_submenu_page($parent_menu_slug, "Tools", "Tools", $menu_capability, "imkt_tools", 'imkt_tools_callback');
        //cpt menu
        add_submenu_page($parent_menu_slug, "All Releases", "All Releases", $menu_capability, "edit.php?post_type=press_release", null);
        add_submenu_page($parent_menu_slug, "Add Press Release", "Add Press Release", $menu_capability, "post-new.php?post_type=press_release", null);
        add_submenu_page($parent_menu_slug, "Categories", "Categories", $menu_capability, "edit-tags.php?taxonomy=press_release_category&post_type=press_release", null);
        add_submenu_page($parent_menu_slug, "Tags", "Tags", $menu_capability, "edit-tags.php?taxonomy=press_release_tag&post_type=press_release", null);
        //other menu
        add_submenu_page($parent_menu_slug, "Influencer Feed", "Influencer Feed", $menu_capability, "imkt_feed", 'imkt_feed_callback');
        add_submenu_page($parent_menu_slug, "Opportunities", "Opportunities", $menu_capability, "imkt_opportunities", 'imkt_opportunities_callback');
        add_submenu_page($parent_menu_slug, "Release Wizard", "Release Wizard", $menu_capability, "imkt_release_wizard", 'imkt_release_wizard_callback');
        add_submenu_page($parent_menu_slug, "Updates & Tips", "Updates & Tips", 'manage_options', 'imkt_dashboard', 'imkt_dashboard_callback');
    }

}

/**
 *      Set current menu selected
 */
add_filter('parent_file', 'imkt_set_current_menu');
if (!function_exists('imkt_set_current_menu')) {

    function imkt_set_current_menu($parent_file) {
        global $submenu_file, $current_screen, $pagenow;

        if ($current_screen->post_type == 'press_release') {
            if ($pagenow == 'post.php') {
                $submenu_file = "edit.php?post_type=" . $current_screen->post_type;
            }
            if ($pagenow == 'edit-tags.php') {
                if ($current_screen->taxonomy == 'press_release_category') {
                    $submenu_file = "edit-tags.php?taxonomy=press_release_category&post_type=" . $current_screen->post_type;
                }
                if ($current_screen->taxonomy == 'press_release_tag') {
                    $submenu_file = "edit-tags.php?taxonomy=press_release_tag&post_type=" . $current_screen->post_type;
                }
            }
            $parent_file = 'imkt_settings';
        }
        return $parent_file;
    }

}


/*
 *      Admin enqueue script and styles
 */
add_action('admin_enqueue_scripts', 'imkt_admin_enqueue');
if (!function_exists('imkt_admin_enqueue')) {

    function imkt_admin_enqueue($hook) {
        wp_enqueue_style('imkt-admin', plugins_url('/css/imkt_admin_style.css', __FILE__));

        wp_enqueue_script('jquery-ui-tooltip');
        wp_enqueue_style('swift-cloud-jquery-ui', plugins_url('/css/jquery-ui.min.css', __FILE__));
        wp_enqueue_script('imkt-admin-script', plugins_url('/js/imkt_admin_script.js', __FILE__), array('jquery'), '', true);
        wp_enqueue_script('imkt-tab-script', plugins_url('/js/sc_tab.js', __FILE__), array('jquery'), '', true);
        wp_localize_script('imkt-admin-script', 'imkt_admin_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

        wp_enqueue_style('swift-toggle-style', plugins_url('/css/sc_rcswitcher.css', __FILE__), '', '', '');
        wp_enqueue_script('swift-toggle', plugins_url('/js/sc_rcswitcher.js', __FILE__), array('jquery'), '', true);

        wp_enqueue_style('imkt-switch-css', plugins_url('css/imkt_lc_switch.css', __FILE__), '', '', '');
        wp_enqueue_script('imkt-switch-js', plugins_url('js/imkt_lc_switch.min.js', __FILE__), array('jquery'), '', true);
        //only for setting page
        if (isset($_GET['page']) && $_GET['page'] == 'imkt_settings') {
            wp_enqueue_media();
            wp_enqueue_script('imkt-upload-media', plugins_url('/js/imkt_admin_media_upload.js', __FILE__), array('jquery'), '', true);
        }
    }

}

include "section/cpt_press_release.php";
include "section/swift_dashboard.php";
include "section/imkt_settings.php";
include "section/imkt_feed.php";
include "section/imkt_admin_callbaks.php";
include "section/imkt_opportunities.php";
include "section/imkt_release_wizard.php";
include "section/imkt_widget_latest_release.php";
include "section/imkt_tools.php";

/**
 *  Init
 */
add_action("init", "imkt_admin_forms_submit");
if (!function_exists('imkt_admin_forms_submit')) {

    function imkt_admin_forms_submit() {
        /* on plugin active auto generate pages and options */
        if (isset($_POST['imkt_autogen_pages']) && wp_verify_nonce($_POST['imkt_autogen_pages'], 'imkt_autogen_pages')) {
            if ($_POST['imkt_autogen_yes'] == 'yes') {
                imkt_initial_data();
            }
            update_option('imkt_notice', true);
        }

        /*
         *      save/update media contact settings
         */
        if (isset($_POST['save_imkt_feeds']) && wp_verify_nonce($_POST['save_imkt_feeds'], 'save_imkt_feeds')) {
            global $wpdb;
            $table_influencers = $wpdb->prefix . 'imkt_influencers';

            $imkt_feed_label = sanitize_text_field($_POST['imkt_feed_label']);
            $imkt_feed = sanitize_text_field($_POST['imkt_feed']);
            $imkt_feed_url = esc_url_raw($_POST['imkt_feed_url']);
            $imkt_feed_date_time = sanitize_text_field(date("Y-m-d H:i:s"));
            $imkt_whitelist_flag = sanitize_text_field(!empty($_POST['imkt_whitelist_flag']) ? 1 : 0);
            $imkt_whitelist = sanitize_text_field($_POST['imkt_whitelist']);
            $imkt_blacklist_flag = sanitize_text_field(!empty($_POST['imkt_blacklist_flag']) ? 1 : 0);
            $imkt_blacklist = sanitize_text_field($_POST['imkt_blacklist']);


            if (isset($_POST['imkt_feed_submit']) && !empty($_POST['imkt_feed_submit']) && $_POST['imkt_feed_submit'] == 'Add') {
                $imkt_feeds_insert = $wpdb->insert($table_influencers, array(
                    'ifns_name' => $imkt_feed_label,
                    'ifns_feed' => $imkt_feed,
                    'ifns_feed_url' => $imkt_feed_url,
                    'ifns_date_time' => $imkt_feed_date_time,
                    'ifns_whitelist_flag' => $imkt_whitelist_flag,
                    'ifns_whitelist' => $imkt_whitelist,
                    'ifns_blacklist_flag' => $imkt_blacklist_flag,
                    'ifns_blacklist' => $imkt_blacklist,
                        ), array('%s', '%s', '%s', '%s', '%d', '%s', '%d', '%s')
                );

                if ($imkt_feeds_insert) {
                    if (isset($_POST['imkt_redirect']) && !empty($_POST['imkt_redirect'])) {
                        wp_redirect(sanitize_text_field($_POST['imkt_redirect']));
                    } else {
                        wp_redirect(admin_url("admin.php?page=imkt_settings&tab=imkt-influencers-media-contacts-settings&update=2"));
                    }
                    die;
                }
            }

            /*
             *  update
             */
            if (isset($_POST['imkt_feed_submit']) && !empty($_POST['imkt_feed_submit']) && $_POST['imkt_feed_submit'] == 'Update') {
                $upd_id = sanitize_text_field($_POST['ifns_id']);

                $feed_update = $wpdb->update($table_influencers, array(
                    'ifns_name' => $imkt_feed_label,
                    'ifns_feed' => $imkt_feed,
                    'ifns_feed_url' => $imkt_feed_url,
                    'ifns_whitelist_flag' => $imkt_whitelist_flag,
                    'ifns_whitelist' => $imkt_whitelist,
                    'ifns_blacklist_flag' => $imkt_blacklist_flag,
                    'ifns_blacklist' => $imkt_blacklist,
                        )
                        , array('ifns_id' => $upd_id), array('%s', '%s', '%s', '%d', '%s', '%d', '%s'), array('%d'));
                if ($feed_update) {
                    wp_redirect(admin_url("admin.php?page=imkt_settings&tab=imkt-influencers-media-contacts-settings&update=1"));
                    die;
                }
            }
        }
    }

}


/* Dismiss notice callback */
add_action('wp_ajax_imkt_dismiss_notice', 'imkt_dismiss_notice_callback');
add_action('wp_ajax_nopriv_imkt_dismiss_notice', 'imkt_dismiss_notice_callback');
if (!function_exists('imkt_dismiss_notice_callback')) {

    function imkt_dismiss_notice_callback() {
        update_option('imkt_notice', true);
    }

}

/*
 *      Auto checked press release category in add new post
 */
add_action('admin_head-post-new.php', 'imkt_auto_checked_category');
if (!function_exists('imkt_auto_checked_category')) {

    function imkt_auto_checked_category() {
        if (isset($_GET['post_type']) && !empty($_GET['post_type']) && $_GET['post_type'] == 'press_release') {
            $default_cat = get_option('imkt_default_category');
            if (!empty($default_cat)) {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        var default_cat = '<?php echo $default_cat; ?>';
                        jQuery("#press_release_categorychecklist li #in-press_release_category-" + default_cat).attr("checked", "checked");
                    });
                </script>
                <?php
            }
        }
    }

}