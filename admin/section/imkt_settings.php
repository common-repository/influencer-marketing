<?php
/*
 * Settings page
 */
add_action("init", "imkt_settings_post_init");

function imkt_settings_post_init() {
    global $wpdb;
    
    // General Setting tab *******************************
    if (isset($_POST['save_imkt_general_settings']) && wp_verify_nonce($_POST['save_imkt_general_settings'], 'save_imkt_general_settings')) {
        $imkt_default_location = sanitize_text_field($_POST['imkt_default_location']);
        $imkt_cnt_name = sanitize_text_field($_POST['imkt_press_contact_name']);
        $imkt_cnt_email = sanitize_email($_POST['imkt_press_contact_email']);
        $imkt_cnt_phone = sanitize_text_field($_POST['imkt_press_contact_phone']);
        $imkt_logo_url = esc_url_raw($_POST['logo_image_url']);
        $imkt_subs_id = sanitize_text_field($_POST['imkt_press_release_subs_list_id']);

        $update1 = update_option('imkt_default_location', $imkt_default_location);
        $update2 = update_option('imkt_press_contact_name', $imkt_cnt_name);
        $update3 = update_option('imkt_logo_image_url', $imkt_logo_url);
        $update4 = update_option('imkt_press_release_subscribe_list_id', $imkt_subs_id);
        $update5 = update_option('imkt_press_contact_email', $imkt_cnt_email);
        $update6 = update_option('imkt_press_contact_phone', $imkt_cnt_phone);

        if ($update1 || $update2 || $update3 || $update4 || $update5 || $update6) {
            wp_redirect(admin_url("admin.php?page=imkt_settings&tab=imkt-general-settings&update=1"));
            die;
        }
    }

    // Media Contact tab *******************************
    $table_influencers = $wpdb->prefix . 'imkt_influencers';
    if (isset($_POST['imkt_feed_action']) && wp_verify_nonce($_POST['imkt_feed_action'], 'imkt_feed_action')) {
        if (!empty($_POST['feed_id'])) {
            $del_feed_id = sanitize_text_field($_POST['feed_id']);
            $del_feed = $wpdb->delete($table_influencers, array('ifns_id' => $del_feed_id), array('%d'));
        }
        if ($del_feed) {
            wp_redirect(admin_url("admin.php?page=imkt_settings&tab=imkt-influencers-media-contacts-settings&update=3"));
            die;
        }
    }

    // Role and Permissions tab *******************************
    if (isset($_POST['save_imkt_role_permission']) && wp_verify_nonce($_POST['save_imkt_role_permission'], 'save_imkt_role_permission')) {
        $imkt_role_permission_admin = $imkt_role_permission_editor = $imkt_role_permission_author = $imkt_role_permission_contributor = $imkt_role_permission_subscriber = array();

        $imkt_role_permission_admin['new_draft'] = sanitize_text_field($_POST['imkt_role_permission_new_draft_administrator']);
        $imkt_role_permission_admin['view_theirs'] = sanitize_text_field($_POST['imkt_role_permission_view_theirs_administrator']);
        $imkt_role_permission_admin['view_all'] = sanitize_text_field($_POST['imkt_role_permission_view_all_administrator']);
        $imkt_role_permission_admin['publish'] = sanitize_text_field($_POST['imkt_role_permission_publish_administrator']);

        $imkt_role_permission_editor['new_draft'] = sanitize_text_field($_POST['imkt_role_permission_new_draft_editor']);
        $imkt_role_permission_editor['view_theirs'] = sanitize_text_field($_POST['imkt_role_permission_view_theirs_editor']);
        $imkt_role_permission_editor['view_all'] = sanitize_text_field($_POST['imkt_role_permission_view_all_editor']);
        $imkt_role_permission_editor['publish'] = sanitize_text_field($_POST['imkt_role_permission_publish_editor']);

        $imkt_role_permission_author['new_draft'] = sanitize_text_field($_POST['imkt_role_permission_new_draft_author']);
        $imkt_role_permission_author['view_theirs'] = sanitize_text_field($_POST['imkt_role_permission_view_theirs_author']);
        $imkt_role_permission_author['view_all'] = sanitize_text_field($_POST['imkt_role_permission_view_all_author']);
        $imkt_role_permission_author['publish'] = sanitize_text_field($_POST['imkt_role_permission_publish_author']);

        $imkt_role_permission_contributor['new_draft'] = sanitize_text_field($_POST['imkt_role_permission_new_draft_contributor']);
        $imkt_role_permission_contributor['view_theirs'] = sanitize_text_field($_POST['imkt_role_permission_view_theirs_contributor']);
        $imkt_role_permission_contributor['view_all'] = sanitize_text_field($_POST['imkt_role_permission_view_all_contributor']);
        $imkt_role_permission_contributor['publish'] = sanitize_text_field($_POST['imkt_role_permission_publish_contributor']);

        $imkt_role_permission_subscriber['new_draft'] = sanitize_text_field($_POST['imkt_role_permission_new_draft_subscriber']);
        $imkt_role_permission_subscriber['view_theirs'] = sanitize_text_field($_POST['imkt_role_permission_view_theirs_subscriber']);
        $imkt_role_permission_subscriber['view_all'] = sanitize_text_field($_POST['imkt_role_permission_view_all_subscriber']);
        $imkt_role_permission_subscriber['publish'] = sanitize_text_field($_POST['imkt_role_permission_publish_subscriber']);

        $update1 = update_option('imkt_role_permission_administrator', $imkt_role_permission_admin);
        $update2 = update_option('imkt_role_permission_editor', $imkt_role_permission_editor);
        $update3 = update_option('imkt_role_permission_author', $imkt_role_permission_author);
        $update4 = update_option('imkt_role_permission_contributor', $imkt_role_permission_contributor);
        $update5 = update_option('imkt_role_permission_subscriber', $imkt_role_permission_subscriber);

        if ($update1 || $update2 || $update3 || $update4 || $update5) {
            wp_redirect(admin_url("admin.php?page=imkt_settings&tab=imkt-roles-permissions&update=1"));
            die;
        }
    }
}

if (!function_exists('imkt_settings_callback')) {

    function imkt_settings_callback() {

        global $wpdb;
        ?>
        <div class="wrap">
            <h2>Settings</h2><hr/>
            <?php
            if (isset($_GET['update']) && !empty($_GET['update'])) {
                if ($_GET['update'] == 1) {
                    ?>
                    <div id="message" class="notice notice-success is-dismissible below-h2">
                        <p>Setting updated successfully.</p>
                    </div>
                    <?php
                } else if ($_GET['update'] == 2) {
                    ?>
                    <div id="message" class="notice notice-success is-dismissible below-h2">
                        <p>Influencers / Media contact added successfully.</p>
                    </div>
                    <?php
                } else if ($_GET['update'] == 3) {
                    ?>
                    <div id="message" class="notice notice-success is-dismissible below-h2">
                        <p>Influencers / Media contact deleted successfully.</p>
                    </div>
                    <?php
                }
            }
            ?>
            <div class="inner_content">
                <h2 class="nav-tab-wrapper" id="imkt-setting-tabs">
                    <a class="nav-tab custom-tab <?php echo (!isset($_GET['tab']) || $_GET['tab'] == "imkt-general-settings") ? 'nav-tab-active' : ''; ?>" id="imkt-general-settings-tab" href="#imkt-general-settings">General Settings</a>
                    <a class="nav-tab custom-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == "imkt-influencers-media-contacts-settings") ? 'nav-tab-active' : ''; ?>" id="imkt-influencers-media-contacts-tab" href="#imkt-influencers-media-contacts-settings">Influencers / Media Contacts</a>
                    <a class="nav-tab custom-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == "imkt-help-setup-settings") ? 'nav-tab-active' : ''; ?>" id="imkt-help-setup-tab" href="#imkt-help-setup-settings">Help & Setup</a>
                    <a class="nav-tab custom-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == "imkt-roles-permissions") ? 'nav-tab-active' : ''; ?>" id="imkt-roles-permissions-tab" href="#imkt-roles-permissions">Roles & Permissions</a>
                </h2>

                <div class="tabwrapper">
                    <div id="imkt-general-settings" class="panel <?php echo (!isset($_GET['tab']) || $_GET['tab'] == "imkt-general-settings") ? 'active' : ''; ?>">
                        <?php include 'imkt-general-settings.php'; ?>
                    </div>

                    <div id="imkt-influencers-media-contacts-settings" class="panel <?php echo (isset($_GET['tab']) && $_GET['tab'] == "imkt-influencers-media-contacts-settings") ? 'active' : ''; ?>">
                        <?php include 'imkt-influencers-media-contact-settings.php'; ?>
                    </div>

                    <div id="imkt-help-setup-settings" class="panel <?php echo (isset($_GET['tab']) && $_GET['tab'] == "imkt-help-setup-settings") ? 'active' : ''; ?>">
                        <?php include 'imkt-help-setup-settings.php'; ?>
                    </div>

                    <div id="imkt-roles-permissions" class="panel <?php echo (isset($_GET['tab']) && $_GET['tab'] == "imkt-roles-permissions") ? 'active' : ''; ?>">
                        <?php include 'imkt-role-permissions-settings.php'; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}
?>