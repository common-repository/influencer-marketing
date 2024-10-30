<?php
/*
 *      CPT: Press Release
 */

add_action('init', 'imkt_cpt_press_release_callback');
if (!function_exists('imkt_cpt_press_release_callback')) {

    function imkt_cpt_press_release_callback() {
        $icon_url = plugins_url('../images/swiftcloud.png', __FILE__);
        $labels = array(
            'name' => _x('Press Releases', 'post type general name', 'impress-pr'),
            'singular_name' => _x('Press Release', 'post type singular name', 'impress-pr'),
            'menu_name' => _x('Press Releases', 'admin menu', 'impress-pr'),
            'add_new' => _x('Add New', '', 'impress-pr'),
            'add_new_item' => __('Add New', 'impress-pr'),
            'new_item' => __('New Press', 'impress-pr'),
            'edit_item' => __('Edit Press', 'impress-pr'),
            'view_item' => __('View Press', 'impress-pr'),
            'all_items' => __('All Releases', 'impress-pr'),
            'search_items' => __('Search Press', 'impress-pr'),
            'not_found' => __('No press releases found....yet.', 'impress-pr'),
            'not_found_in_trash' => __('No press release found in trash.', 'impress-pr')
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => true,
            'menu_icon' => __($icon_url, 'impress-pr'),
            'menu_position' => null,
            'supports' => array('title', 'editor'),
            'taxonomies' => array('press_release_category', 'press_release_tag'),
            'rewrite' => array('slug' => 'press_release')
        );
        register_post_type('press_release', $args);

        /* -------------------------------------
         *      Add new taxonomy
         */
        $cat_labels = array(
            'name' => _x('Press Release Categories', 'taxonomy general name'),
            'singular_name' => _x('Press Release Category', 'taxonomy singular name'),
            'add_new_item' => __('Add New Category'),
            'new_item_name' => __('New Category Name'),
            'menu_name' => __('Categories'),
            'search_items' => __('Search Category'),
            'not_found' => __('No Category found.'),
        );

        $cat_args = array(
            'hierarchical' => true,
            'labels' => $cat_labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'press_release_category'),
        );

        register_taxonomy('press_release_category', 'press_release', $cat_args);

        // insert default tags
        $default_cat = array(
            "Press Releases" => array("slug" => "press_releases"),
            "Press Clippings" => array("slug" => "press_clippings"),
            "Press Media Kit" => array("slug" => "press_media_kit"),
        );
        foreach ($default_cat as $d_cat_key => $d_cat_val) {
            $press_cats = wp_insert_term($d_cat_key, "press_release_category", array('slug' => $d_cat_val['slug'], 'parent' => 0));
            if ($d_cat_val['slug'] == 'press_releases') {
                if (!empty($press_cats) && !array_key_exists("errors", $press_cats)) {
                    update_option('imkt_default_category', sanitize_text_field($press_cats->term_id));
                } else {
                    update_option('imkt_default_category', sanitize_text_field($press_cats->error_data['term_exists']));
                }
            }
        }

        /**
         *      press release tags
         */
        $imkt_tags_labels = array(
            'name' => _x('Press Release Tags', 'taxonomy general name'),
            'singular_name' => _x('Press Release Tag', 'taxonomy singular name'),
            'search_items' => __('Search Tags'),
            'popular_items' => __('Popular Tags'),
            'all_items' => __('All Tags'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Tag'),
            'update_item' => __('Update Tag'),
            'add_new_item' => __('Add New Tag'),
            'new_item_name' => __('New Tag Name'),
            'separate_items_with_commas' => __('Separate tags with commas'),
            'add_or_remove_items' => __('Add or remove tags'),
            'choose_from_most_used' => __('Choose from the most used tags'),
            'menu_name' => __('Tags'),
        );

        register_taxonomy('press_release_tag', 'press_release', array(
            'hierarchical' => false,
            'labels' => $imkt_tags_labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array('slug' => 'press_release_tag'),
        ));
    }

}

add_filter('single_template', 'imkt_plugin_templates_callback');
if (!function_exists('imkt_plugin_templates_callback')) {

    function imkt_plugin_templates_callback($template) {
        $post_types = array('press_release');
        if (is_singular($post_types) && !file_exists(get_stylesheet_directory() . '/single-press_release.php')) {
            $template = IMKT__PLUGIN_DIR . "public/section/single-press_release.php";
        }
        return $template;
    }

}

add_filter('archive_template', 'imkt_set_archive_template_callback');
if (!function_exists('imkt_set_archive_template_callback')) {

    function imkt_set_archive_template_callback($archive_template) {
        global $post;
        if (get_post_type() == 'press_release' && is_archive('press_release')) {
            $archive_template = IMKT__PLUGIN_DIR . 'public/section/archive-press_release.php';
        }
        return $archive_template;
    }

}

/*
 *  Custom field :Reviews
 */

add_action('add_meta_boxes', 'imkt_metaboxes');
if (!function_exists('imkt_metaboxes')) {

    function imkt_metaboxes() {
        add_meta_box('imkt_location', 'Location', 'imkt_location_callback', 'press_release', 'normal', 'default');
        add_meta_box('press_release_subheadline', 'Sub headline', 'imkt_press_release_subheadline_callback', 'press_release', 'normal', 'high');
    }

}

if (!function_exists('imkt_location_callback')) {

    function imkt_location_callback($post) {
        $default_location = get_option('imkt_default_location');
        $press_location = get_post_meta($post->ID, 'press_location', true);
        $location = !empty($press_location) ? $press_location : $default_location;
        ?>
        <input type="text" name="press_location" id="press_location" class="regular-text" value="<?php echo $location; ?>" />
        <?php
    }

}
if (!function_exists('imkt_press_release_subheadline_callback')) {

    function imkt_press_release_subheadline_callback($post) {
        $press_headline = get_post_meta($post->ID, 'press_release_subheadline', true);
        ?>
        <input type="text" name="press_release_subheadline" id="press_release_subheadline" class="regular-text" value="<?php echo $press_headline; ?>" />
        <?php
    }

}

/**
 *      Save meta
 */
add_action('save_post', 'imkt_save_post');
if (!function_exists('imkt_save_post')) {

    function imkt_save_post($post_id) {
        if (isset($_POST["press_location"])) {
            $loction = sanitize_text_field($_POST['press_location']);
            update_post_meta($post_id, 'press_location', $loction);
        }
        if (isset($_POST["press_release_subheadline"])) {
            $subheadline = sanitize_text_field($_POST['press_release_subheadline']);
            update_post_meta($post_id, 'press_release_subheadline', $subheadline);
        }
    }

}