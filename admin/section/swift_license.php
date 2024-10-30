<?php
/*
 *      License section
 */
if (isset($_POST['imkt_license_nonce']) && wp_verify_nonce($_POST['imkt_license_nonce'], 'imkt_license_nonce')) {
    $imkt_license_flage = sanitize_text_field(!empty($_POST['imkt_license'])) == 1 ? "pro" : "lite";

    $update1 = update_option("imkt_license", $imkt_license_flage);

    if ($update1) {
        wp_redirect(admin_url("admin.php?page=imkt-pr&update=1"));
        die;
    }
}

$sr_license_flag = get_option("imkt_license");
$license_flag = ($sr_license_flag == "pro") ? 'checked="checked"' : '';
$license_toggle = ($sr_license_flag == "pro") ? 'display:block;' : 'display:none;';
?>
<div class="inner_content">
    <div class="sc-license-wrap bg-light-yellow">
        <h4>License: Now running the <input type="checkbox" value="1" data-ontext="Pro" data-offtext="Lite" name="imkt_license" id="imkt_license" <?php echo $license_flag; ?>> Version.</h4>
        <div class="pro-license-wrap" style="<?php echo $license_toggle; ?>">
            <form id="frmImpressProLicense" method="post">
                <?php wp_nonce_field('imkt_license_nonce', 'imkt_license_nonce'); ?>
                <input type="text" required="required" name="impress_pro_license" id="impress_pro_license" class="regular-text" /><button type="submit" id="btn_event_pro_license" class="button button-pro-license"><span class="dashicons dashicons-unlock"></span> Connect / Enable</button>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        /* License togggle*/
        if (jQuery("#imkt_license").length > 0) {
            jQuery('#imkt_license').rcSwitcher().on({
                width: 80,
                height: 24,
                autoFontSize: true,
                'turnon.rcSwitcher': function(e, dataObj) {
                    jQuery(".pro-license-wrap").fadeIn();
                },
                'turnoff.rcSwitcher': function(e, dataObj) {
                    jQuery(".pro-license-wrap").fadeOut();
                }
            });
        }
    });
</script>