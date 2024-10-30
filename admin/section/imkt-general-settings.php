<?php
/*
 *      Settings > General Setting tab
 */
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

$imkt_default_location = get_option('imkt_default_location');
$imkt_press_contact_name = get_option('imkt_press_contact_name');
$imkt_press_contact_email = get_option('imkt_press_contact_email');
$imkt_press_contact_phone = get_option('imkt_press_contact_phone');
$imkt_logo_image_url = get_option('imkt_logo_image_url');
$imkt_press_release_subscribe_list_id = get_option('imkt_press_release_subscribe_list_id');
?>
<form name="FrmImktGeneralSettings" id="FrmImktGeneralSettings" method="post" enctype="multipart/form-data">
    <table class="form-table">
        <tr>
            <th><label for="default_location">Default Location</label></th>
            <td><input type="text" id="default_location" name="imkt_default_location" class="regular-text" value="<?php echo $imkt_default_location; ?>" placeholder="Location" /></td>
        </tr>
        <tr>
            <th colspan="2" style="padding-bottom: 0;"><label for="bio_more_info">Bio / More Info Default Text <span class="dashicons dashicons-editor-help ttip" title="We'll pre-load this into each release at the bottom. You can then touch it up specific to the release if needed. Note we will encrypt these on your website to thwart spam harvesting. "></span></label></th>
        </tr>
        <tr>
            <th><label for="imkt_press_contact_name">Press Contact Name</label></th>
            <td>
                <input id="imkt_press_contact_name" type="text" name="imkt_press_contact_name" class="regular-text" value="<?php echo $imkt_press_contact_name; ?>" placeholder="Contact Name" />
            </td>
        </tr>
        <tr>
            <th><label for="imkt_press_contact_email">Press Contact Email</label></th>
            <td>
                <input id="imkt_press_contact_email" type="text" name="imkt_press_contact_email" class="regular-text" value="<?php echo $imkt_press_contact_email; ?>" placeholder="Contact Email" />
            </td>
        </tr>
        <tr>
            <th><label for="imkt_press_contact_phone">Press Contact Phone</label></th>
            <td>
                <input id="imkt_press_contact_phone" type="text" name="imkt_press_contact_phone" class="regular-text" value="<?php echo $imkt_press_contact_phone; ?>" placeholder="Contact Phone" />
            </td>
        </tr>
        <tr>
            <th><label for="imkt_logo">Enter a URL or Upload logo <span class="dashicons dashicons-editor-help ttip" title="Upload image 250x60px dimensions in JPEG, PNG or GIF format."></span></label></th>
            <td>
                <input id="upload_image" type="text" size="36" name="logo_image_url" class="regular-text" value="<?php echo $imkt_logo_image_url; ?>" placeholder="URL" />
                <input id="upload_image_button" class="button button-primary" type="button" value="Upload Image" />
            </td>
        </tr>
        <tr>
            <th><label for="imkt_press_release_subs_list_id"><a href="https://swiftcrm.com/software/forms-generator" target="_blank">SwiftCloud Form ID for Press Release Notification #</a></label></th>
            <td><input type="text" id="imkt_press_release_subs_list_id" name="imkt_press_release_subs_list_id" class="" value="<?php echo $imkt_press_release_subscribe_list_id; ?>" /></td>
        </tr>
        <tr>
            <th colspan="2">
                <?php wp_nonce_field('save_imkt_general_settings', 'save_imkt_general_settings'); ?>
                <button type="submit" class="button-primary" id="imkt-general-settings-btn" value="imkt-general-settings" name="imkt_settings">Save Settings</button>
            </th>
        </tr>
    </table>
</form>