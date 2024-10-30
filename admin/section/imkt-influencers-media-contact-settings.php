<?php
/*
 *      Settings > Media Contact tab
 */
$table_influencers = $wpdb->prefix . 'imkt_influencers';
$feeds_sites = array('RSS', 'Twitter', 'YouTube Channel');
$get_all_feeds = $wpdb->get_results("SELECT * FROM `$table_influencers`");
?>
<div class="pull-right" style="margin: 10px 0 5px;">
    <button class="button button-orange imkt-add-new-btn" data-feed-id="0" data-btn="add" data-modal="#imkt_feed_modal"><span class="dashicons dashicons-plus"></span> Add</button>
</div>
<div class="clear"></div>
<form id="imkt_feeds_frm" method="post">
    <?php wp_nonce_field('imkt_feed_action', 'imkt_feed_action'); ?>
    <table class="widefat fixed striped tbl-syndication" id="tbl-ifns-contact">
        <thead>
            <tr>
                <th width="3%"></th>
                <th width="72%">Name</th>
                <th width="25%" style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($get_all_feeds)) {
                foreach ($get_all_feeds as $feeds) {
                    $check_feed = '';
                    if ($feeds->ifns_feed == "Twitter") {
                        $check_feed = '<span class="dashicons dashicons-twitter"></span>';
                    } else if ($feeds->ifns_feed == "RSS") {
                        $check_feed = '<span class="dashicons dashicons-rss"></span>';
                    } else if ($feeds->ifns_feed == "YouTube Channel") {
                        $check_feed = '<span class="dashicons dashicons-video-alt3"></span>';
                    }
                    ?>
                    <tr>
                        <td width="3%"><a href="<?php echo $feeds->ifns_feed_url; ?>"><?php echo $check_feed; ?></a></td>
                        <td width="72%"><?php echo $feeds->ifns_name; ?></td>
                        <td width="25%" style="text-align: center;">
                            <a class="imkt-round-bg imkt-edit" title="Edit" href="#" data-btn="edit" data-feed-id="<?php echo $feeds->ifns_id; ?>" data-modal="#imkt_feed_modal"><span class="dashicons dashicons-edit"></span></a>
                            <a class="imkt-round-bg imkt-delete" title="Delete" href="#" data-feed-id="<?php echo $feeds->ifns_id; ?>"><span class="dashicons dashicons-no-alt"></span></a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="3" align="center"><h3>No Influencers / Media Contacts / Bloggers / YouTubers found... yet. Why not <a href="javascript:void(0)" class="imkt-add-new-link" data-feed-id="0" data-btn="add" data-modal="#imkt_feed_modal">click here</a> to add some now?</h3></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</form>

<div class="imkt_modal imkt_feed_modal" id="imkt_feed_modal" style="display: none;">
    <div class="imkt_modal_container">
        <form method="post" id="frm_imkt_feed" name="frm_imkt_feed">
            <div class="imkt_modal_header">
                <h2 class="imkt_modal_title">Add Influencer / Media Contact</h2>
                <span class="dashicons dashicons-no imkt_modal_close"></span>
            </div>
            <div class="imkt_modal_content">
                <div class="imkt_control_group">
                    <label for="imkt_feed_label">Name / Label</label>
                    <input type="text" name="imkt_feed_label" id="imkt_feed_label" class="imkt_form_control regular-text" required="required" />
                </div>
                <div class="imkt_control_group">
                    <label for="imkt_feed">Source Type</label>
                    <select name="imkt_feed" id="imkt_feed" class="imkt_form_control" required="required">
                        <?php foreach ($feeds_sites as $f_site): ?>
                            <option value="<?php echo $f_site; ?>"><?php echo $f_site; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="imkt_control_group imkt_feed_url_wrap">
                    <label for="imkt_feed_url">URL</label>
                    <input type="text" name="imkt_feed_url" id="imkt_feed_url" class="imkt_form_control regular-text" />
                </div>

                <div class="imkt_control_group imkt_feed_twitter_wrap" style="display: none;">
                    <label for="imkt_feed_twitter_url">Twitter ID / handle <span class="dashicons dashicons-editor-help ttip" title="I.e. just SwiftCloud_io from https://twitter.com/swiftcloud_io"></span></label>
                    <input type="text" name="imkt_feed_url" id="imkt_feed_twitter_url" class="imkt_form_control regular-text" />
                </div>

                <div class="imkt_control_group">
                    <div class="imkt_control_group" style="margin-bottom: 5px;">
                        <label for="imkt_whitelist_flag">Whitelist <span class="dashicons dashicons-editor-help ttip" title="We'll keep only feed-items that match one of these keyphrases exactly, and discard all the rest. One phrase per line."></span></label>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_whitelist_flag" id="imkt_whitelist_flag" class="imkt_whitlist_flag" >
                    </div>
                    <div class="imkt_control_group imkt_whitelist_textarea" style="display: none;">
                        <label></label>
                        <textarea name="imkt_whitelist" id="imkt_whitelist" class="imkt_form_control" cols="50" rows="5"></textarea>
                    </div>

                    <div class="imkt_control_group" style="margin-bottom: 5px;">
                        <label for="imkt_blacklist_flag">Blacklist <span class="dashicons dashicons-editor-help ttip" title="We'll discard any feed-item that contains any of these phrases. One phrase per line."></span></label>
                        <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_blacklist_flag" id="imkt_blacklist_flag" class="imkt_blacklist_flag">
                    </div>
                    <div class="imkt_control_group imkt_blacklist_textarea" style="display: none;">
                        <label></label>
                        <textarea name="imkt_blacklist" id="imkt_blacklist" class="imkt_form_control" cols="50" rows="5"></textarea>
                    </div>
                </div>

            </div>
            <div class="imkt_modal_footer textright">
                <?php wp_nonce_field('save_imkt_feeds', 'save_imkt_feeds'); ?>
                <button type="submit" name="imkt_feed_submit" id="imkt_feed_submit" value="Add" class="button button-primary" />Add</button>
            </div>
        </form>
    </div>
</div>