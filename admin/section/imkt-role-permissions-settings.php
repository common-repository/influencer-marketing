<?php
/**
 *      Role and Permissions Tab
 */

$imkt_role_permission_admin = get_option('imkt_role_permission_administrator');
$imkt_role_permission_editor = get_option('imkt_role_permission_editor');
$imkt_role_permission_author = get_option('imkt_role_permission_author');
$imkt_role_permission_contributor = get_option('imkt_role_permission_contributor');
$imkt_role_permission_subscriber = get_option('imkt_role_permission_subscriber');
?>
<div class="inner_content">
    <form method="post" id="frmAuthMkt">
        <table class="widefat fixed striped imkt-auth-mkt">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Create New Draft</th>
                    <th>View Theirs</th>
                    <th>View All</th>
                    <th>Publish</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Administrator</td>
                    <td>
                        <?php $flag_new_draft_administrator = ($imkt_role_permission_admin['new_draft'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_new_draft_administrator" id="imkt_role_permission_new_draft_administrator" class="imkt_role_permission_toggle" <?php echo $flag_new_draft_administrator; ?>>
                    </td>
                    <td>
                        <?php $flag_view_theirs_administrator = ($imkt_role_permission_admin['view_theirs'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_view_theirs_administrator" id="imkt_role_permission_view_theirs_administrator" class="imkt_role_permission_toggle" <?php echo $flag_view_theirs_administrator; ?>>
                    </td>
                    <td>
                        <?php $flag_view_all_administrator = ($imkt_role_permission_admin['view_all'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_view_all_administrator" id="imkt_role_permission_view_all_administrator" class="imkt_role_permission_toggle" <?php echo $flag_view_all_administrator; ?>>
                    </td>
                    <td>
                        <?php $flag_publish_administrator = ($imkt_role_permission_admin['publish'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_publish_administrator" id="imkt_role_permission_publish_administrator" class="imkt_role_permission_toggle" <?php echo $flag_publish_administrator; ?>>
                    </td>
                </tr>
                <tr>
                    <td>Editor</td>
                    <td>
                        <?php $flag_new_draft_editor = ($imkt_role_permission_editor['new_draft'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_new_draft_editor" id="imkt_role_permission_new_draft_editor" class="imkt_role_permission_toggle" <?php echo $flag_new_draft_editor; ?>>
                    </td>
                    <td>
                        <?php $flag_view_theirs_editor = ($imkt_role_permission_editor['view_theirs'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_view_theirs_editor" id="imkt_role_permission_view_theirs_editor" class="imkt_role_permission_toggle" <?php echo $flag_view_theirs_editor; ?>>
                    </td>
                    <td>
                        <?php $flag_view_all_editor = ($imkt_role_permission_editor['view_all'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_view_all_editor" id="imkt_role_permission_view_all_editor" class="imkt_role_permission_toggle" <?php echo $flag_view_all_editor; ?>>
                    </td>
                    <td>
                        <?php $flag_publish_editor = ($imkt_role_permission_editor['publish'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_publish_editor" id="imkt_role_permission_publish_editor" class="imkt_role_permission_toggle" <?php echo $flag_publish_editor; ?>>
                    </td>
                </tr>
                <tr>
                    <td>Author</td>
                    <td>
                        <?php $flag_new_draft_author = ($imkt_role_permission_author['new_draft'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_new_draft_author" id="imkt_role_permission_new_draft_author" class="imkt_role_permission_toggle" <?php echo $flag_new_draft_author; ?>>
                    </td>
                    <td>
                        <?php $flag_view_theirs_author = ($imkt_role_permission_author['view_theirs'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_view_theirs_author" id="imkt_role_permission_view_theirs_author" class="imkt_role_permission_toggle" <?php echo $flag_view_theirs_author; ?>>
                    </td>
                    <td>
                        <?php $flag_view_all_author = ($imkt_role_permission_author['view_all'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_view_all_author" id="imkt_role_permission_view_all_author" class="imkt_role_permission_toggle" <?php echo $flag_view_all_author; ?>>
                    </td>
                    <td>
                        <?php $flag_publish_author = ($imkt_role_permission_author['publish'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_publish_author" id="imkt_role_permission_publish_author" class="imkt_role_permission_toggle" <?php echo $flag_publish_author; ?>>
                    </td>
                </tr>
                <tr>
                    <td>Contributor</td>
                    <td>
                        <?php $flag_new_draft_contributor = ($imkt_role_permission_contributor['new_draft'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_new_draft_contributor" id="imkt_role_permission_new_draft_contributor" class="imkt_role_permission_toggle" <?php echo $flag_new_draft_contributor; ?>>
                    </td>
                    <td>
                        <?php $flag_view_theirs_contributor = ($imkt_role_permission_contributor['view_theirs'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_view_theirs_contributor" id="imkt_role_permission_view_theirs_contributor" class="imkt_role_permission_toggle" <?php echo $flag_view_theirs_contributor; ?>>
                    </td>
                    <td>
                        <?php $flag_view_all_contributor = ($imkt_role_permission_contributor['view_all'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_view_all_contributor" id="imkt_role_permission_view_all_contributor" class="imkt_role_permission_toggle" <?php echo $flag_view_all_contributor; ?>>
                    </td>
                    <td>
                        <?php $flag_publish_contributor = ($imkt_role_permission_contributor['publish'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_publish_contributor" id="imkt_role_permission_publish_contributor" class="imkt_role_permission_toggle" <?php echo $flag_publish_contributor; ?>>
                    </td>
                </tr>
                <tr>
                    <td>Subscriber</td>
                    <td>
                        <?php $flag_new_draft_subscriber = ($imkt_role_permission_subscriber['new_draft'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_new_draft_subscriber" id="imkt_role_permission_new_draft_subscriber" class="imkt_role_permission_toggle" <?php echo $flag_new_draft_subscriber; ?>>
                    </td>
                    <td>
                        <?php $flag_view_theirs_subscriber = ($imkt_role_permission_subscriber['view_theirs'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_view_theirs_subscriber" id="imkt_role_permission_view_theirs_subscriber" class="imkt_role_permission_toggle" <?php echo $flag_view_theirs_subscriber; ?>>
                    </td>
                    <td>
                        <?php $flag_view_all_subscriber = ($imkt_role_permission_subscriber['view_all'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_view_all_subscriber" id="imkt_role_permission_view_all_subscriber" class="imkt_role_permission_toggle" <?php echo $flag_view_all_subscriber; ?>>
                    </td>
                    <td>
                        <?php $flag_publish_subscriber = ($imkt_role_permission_subscriber['publish'] == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_role_permission_publish_subscriber" id="imkt_role_permission_publish_subscriber" class="imkt_role_permission_toggle" <?php echo $flag_publish_subscriber; ?>>
                    </td>
                </tr>
            </tbody>
        </table>
        <br/>
        <?php wp_nonce_field('save_imkt_role_permission', 'save_imkt_role_permission'); ?>
        <button type="submit" class="button-primary" id="imkt-role-permission-btn" value="imkt-role-permission" name="imkt_settings">Save Settings</button>
    </form>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('.imkt_role_permission_toggle:checkbox').rcSwitcher();
        });
    </script>
</div>