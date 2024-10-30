<?php
$imkt_logo_image_url = get_option('imkt_logo_image_url');
$imkt_press_contact_name = get_option('imkt_press_contact_name');
$imkt_press_contact_email = get_option('imkt_press_contact_email');
$imkt_press_contact_phone = get_option('imkt_press_contact_phone');
?>
<div class="pr-sidebar-feeds pr-widget-border pr-widget">
    <h3 class="pr-widget-title">Press & Media Contact</h3>
    <ul>
        <?php if (!empty($imkt_press_contact_name)): ?><li><?php echo $imkt_press_contact_name; ?></li><?php endif; ?>
        <li><?php echo (!empty($imkt_press_contact_phone) ? '<i class="fa fa-phone"></i>' . $imkt_press_contact_phone : ""); ?></li>
        <?php if (!empty($imkt_press_contact_email)): ?>
            <?php
            $email_id = substr($imkt_press_contact_email, 0, strrpos($imkt_press_contact_email, '@'));
            $email_domain = substr($imkt_press_contact_email, (strrpos($imkt_press_contact_email, '@') + 1));
            ?>
            <li>
                <SCRIPT TYPE="text/javascript">
                    emailE = ('<?php echo $email_id ?>' + '@<?php echo $email_domain; ?>');
                    document.write('<a class="imkt-sidebar-email" href="mailto:' + emailE + '"><i class="fa fa-envelope"></i>' + emailE + '</a>')
                </script>
                <NOSCRIPT>
                <em>Email address protected by JavaScript.<BR>
                    Please enable JavaScript to contact me.</em>
                </NOSCRIPT>
            </li>
        <?php endif; ?>
    </ul>
</div>
<!--sidebar widget-->
<?php if (is_active_sidebar('imkt-pr-sidebar')) : ?>
    <div class="pr-sidebar-widget pr-widget">
        <?php dynamic_sidebar('imkt-pr-sidebar'); ?>
    </div>
<?php endif; ?>