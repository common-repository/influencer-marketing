<?php

/**
 *      On plugin active default set data in options
 * 
 */
function imkt_preload_options() {
    //General settings > Bio
    $imkt_bio_more_info = wp_kses_post("Name\nCompany\nPhone");
    update_option('imkt_bio_more_info', $imkt_bio_more_info);

    //Authority Marketing Toggles
    $imkt_role_permission_admin = $imkt_role_permission_editor = $imkt_role_permission_author = $imkt_role_permission_contributor = $imkt_role_permission_subscriber = array();

    $imkt_role_permission_admin['new_draft'] = 1;
    $imkt_role_permission_admin['view_theirs'] = 1;
    $imkt_role_permission_admin['view_all'] = 1;
    $imkt_role_permission_admin['publish'] = 1;

    $imkt_role_permission_editor['new_draft'] = 1;
    $imkt_role_permission_editor['view_theirs'] = 1;
    $imkt_role_permission_editor['view_all'] = 1;
    $imkt_role_permission_editor['publish'] = 1;

    $imkt_role_permission_author['new_draft'] = 1;
    $imkt_role_permission_author['view_theirs'] = 1;
    $imkt_role_permission_author['view_all'] = 1;
    $imkt_role_permission_author['publish'] = 0;

    $imkt_role_permission_contributor['new_draft'] = 1;
    $imkt_role_permission_contributor['view_theirs'] = 1;
    $imkt_role_permission_contributor['view_all'] = 0;
    $imkt_role_permission_contributor['publish'] = 0;

    $imkt_role_permission_subscriber['new_draft'] = 0;
    $imkt_role_permission_subscriber['view_theirs'] = 0;
    $imkt_role_permission_subscriber['view_all'] = 0;
    $imkt_role_permission_subscriber['publish'] = 0;

    update_option('imkt_role_permission_administrator', $imkt_role_permission_admin);
    update_option('imkt_role_permission_editor', $imkt_role_permission_editor);
    update_option('imkt_role_permission_author', $imkt_role_permission_author);
    update_option('imkt_role_permission_contributor', $imkt_role_permission_contributor);
    update_option('imkt_role_permission_subscriber', $imkt_role_permission_subscriber);

    update_option('imkt_license', "lite");

    /* Influencers Feeds table */
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $table_influencers = $wpdb->prefix . 'imkt_influencers';

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_influencers'") != $table_influencers) {
        $create_influencers_table = "CREATE TABLE IF NOT EXISTS `$table_influencers` (
                `ifns_id` int(11) NOT NULL AUTO_INCREMENT,
                `ifns_name` varchar(100) NOT NULL,
                `ifns_feed` varchar(100) NOT NULL,
                `ifns_feed_url` TEXT NOT NULL,
                `ifns_date_time` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                `ifns_whitelist_flag` INT NOT NULL DEFAULT '0',
                `ifns_whitelist` VARCHAR( 255 ) NOT NULL,
                `ifns_blacklist_flag` INT NOT NULL DEFAULT '0',
                `ifns_blacklist` VARCHAR( 255 ) NOT NULL,
                PRIMARY KEY (`ifns_id`)
            ) $charset_collate ;";
        dbDelta($create_influencers_table);
    } else {
        $is_column = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $table_influencers . "' AND COLUMN_NAME = 'ifns_whitelist_flag'");
        if (empty($is_column->COLUMN_NAME)) {
            $wpdb->query("ALTER TABLE `$table_influencers`
                            ADD `ifns_whitelist` VARCHAR( 255 ) NOT NULL AFTER `ifns_whitelist_flag` ,
                            ADD `ifns_blacklist_flag` INT NOT NULL DEFAULT '0' AFTER `ifns_whitelist` ,
                            ADD `ifns_blacklist` VARCHAR( 255 ) NOT NULL AFTER `ifns_blacklist_flag` ;"
            );
        }
    }
}

/**
 *      After plugin active with permission add data
 * 
 */
function imkt_initial_data() {
    global $wpdb;

    /**
     *   Auto generate page
     */
    $imkt_pages_id = '';
    $page_id = 0;
    $child_page_id = array();

    $press_release_content = wp_kses_post('[press_release_list category="press_releases"]');
    $press_clippings_content = wp_kses_post('[press_release_list category="press_clippings"]');
    $press_media_kit_content = wp_kses_post('[press_release_list category="press_media_kit"]');
    $press_rss_feed_content = wp_kses_post('This page is being used for RSS Feed');

    $pages_array = array(
        "press-feed" => array("title" => sanitize_text_field("Press Release Feed"), "content" => $press_rss_feed_content, "slug" => "press-release-feed", "option" => "imkt_press_feed_page_id", "template" => "rss-press-feed.php"),
        "press-releases" => array("title" => sanitize_text_field("Press"), "content" => $press_release_content, "slug" => "press", "option" => "imkt_pr_listing_page_id", "template" => "press-releases-template.php",
            "child-pages" => array(
                "clippings" => array("title" => sanitize_text_field("Clippings"), "content" => $press_clippings_content, "slug" => "clippings", "option" => "", "template" => "press-releases-template.php"),
                "media-kit" => array("title" => sanitize_text_field("Media Kit"), "content" => $press_media_kit_content, "slug" => "kit", "option" => "", "template" => "press-releases-template.php"),
            )
        ),
    );

    foreach ($pages_array as $key => $page) {
        $page_data = array(
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_title' => $page['title'],
            'post_name' => $page['slug'],
            'post_content' => $page['content'],
            'comment_status' => 'closed'
        );
        $page_id = wp_insert_post($page_data);

        if ($page['child-pages']) {
            $child_page_id[] = $page_id;
            foreach ($page['child-pages'] as $c_page) {
                $page_data = array(
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_title' => $c_page['title'],
                    'post_name' => $c_page['slug'],
                    'post_content' => $c_page['content'],
                    'post_parent' => $page_id,
                    'comment_status' => 'closed'
                );
                $c_page_id = wp_insert_post($page_data);
                $child_page_id[] = $c_page_id;

                /* Set default template */
                if (isset($c_page['template']) && !empty($c_page['template'])) {
                    update_post_meta($c_page_id, "_wp_page_template", sanitize_text_field($c_page['template']));
                }
            }
        } else {
            $child_page_id[] = $page_id;
        }

        /* Set default template */
        if (isset($page['template']) && !empty($page['template'])) {
            update_post_meta($page_id, "_wp_page_template", sanitize_text_field($page['template']));
        }

        if (isset($page['option']) && !empty($page['option'])) {
            update_option($page['option'], sanitize_text_field($page_id));
        }
    }
    $imkt_pages_id = @implode(",", $child_page_id);
    if (!empty($imkt_pages_id)) {
        update_option('imkt_pages', sanitize_text_field($imkt_pages_id));
    }
}
