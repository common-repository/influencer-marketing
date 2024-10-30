<?php
/*
 *      News Feeds Page
 */
if (!function_exists('imkt_feed_callback')) {

    function imkt_feed_callback() {
        wp_enqueue_script('swift-form-jstz', IMKT__PLUGIN_URL . 'admin/js/jstz.min.js', '', '', true);

        global $wpdb;
        $table_influencers = $wpdb->prefix . 'imkt_influencers';
        $feeds_sites = array('RSS', 'Twitter', 'YouTube Channel');

        $get_all_feeds = $wpdb->get_results("SELECT * FROM `$table_influencers`");
        $feeds_data = array();
        if (!empty($get_all_feeds)) {
            foreach ($get_all_feeds as $feed) {
                if ($feed->ifns_feed == 'RSS') {
                    if (!empty($feed->ifns_feed_url)) {

                        $fetch_feed = fetch_feed($feed->ifns_feed_url);

                        if (!is_wp_error($fetch_feed)) {

                            $feed_data = $fetch_feed->get_items(0, 0);
                            foreach ($feed_data as $item) {
                                $feed_thumb_src = '';
                                $doc = new DOMDocument();

                                @$doc->loadHTML($item->get_content());
                                $tags = $doc->getElementsByTagName('img');

                                foreach ($tags as $tag) {
                                    $feed_thumb_src = $tag->getAttribute('src');
                                }
                                $feed_thumb_src = ($tags->length > 0) ? $feed_thumb_src : IMKT__PLUGIN_URL . 'public/images/blank-img.png';

                                $link = esc_url($item->get_permalink());
                                $date = $item->get_date('j F Y | g:i a');
                                $content = $item->get_content();
                                $title = $item->get_title();

                                $only_date = explode("|", $date);

                                if (isset($_GET['impress_s']) && !empty($_GET['impress_s'])) {
                                    $s_keyword = sanitize_text_field($_GET['impress_s']);
                                    if (strstr($title, $s_keyword) || strstr($content, $s_keyword)) {
                                        $feeds_data[strtotime($only_date[0])] = array(
                                            'feed_title' => $title,
                                            'feed_content' => $content,
                                            'feed_link' => $link,
                                            'feed_date' => $date,
                                            'feed_thumb_src' => $feed_thumb_src,
                                        );
                                    }
                                } else {
                                    $feeds_data[strtotime($only_date[0])] = array(
                                        'feed_title' => $title,
                                        'feed_content' => $content,
                                        'feed_link' => $link,
                                        'feed_date' => $date,
                                        'feed_thumb_src' => $feed_thumb_src,
                                    );
                                }
                            }
                        }
                    }
                }
                if (count($feeds_data) >= 30) {
                    break;
                }
            }
        }
        ?>
        <div class="wrap dashboard-wrap imkt-feeds">
            <h2 class="dashboard-title">Influencers & Press Updates</h2>
            <div class="imkt-add-new-source pull-right">
                <button class="button button-orange imkt-add-new-btn" data-feed-id="0" data-btn="add" data-modal="#imkt_feed_modal"><span class="dashicons dashicons-plus"></span> Add Influencer Source</button>
            </div>
            <div class="clear"></div>
            <hr/>
            <?php if (isset($_GET['update']) && !empty($_GET['update']) && $_GET['update'] == 1) { ?>
                <div id="message" class="notice notice-success is-dismissible below-h2">
                    <p>Setting updated successfully.</p>
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text">Dismiss this notice.</span>
                    </button>
                </div>
            <?php } else if (isset($_GET['update']) && !empty($_GET['update']) && $_GET['update'] == 2) {
                ?>
                <div id="message" class="notice notice-success is-dismissible below-h2">
                    <p>Influencers / Media contact added successfully.</p>
                </div>
            <?php }
            ?>

            <div class="inner_content">
                <div class="sc-feed-row">
                    <div class="dashboard-col-8 col-left">
                        <div class="dashboard-search-feed">
                            <form method="get" id="FrmSearchFeeds" action="<?php echo admin_url("admin.php?page=imkt_feed"); ?>">
                                <input type="text" id="impress_search_feed_input" name="impress_s" value="<?php echo (isset($_GET['impress_s']) && !empty($_GET['impress_s'])) ? sanitize_text_field($_GET['impress_s']) : ""; ?>" required="required" />
                                <input type="hidden" id="impress_search_page" name="page" value="imkt_feed"/>
                                <button type="submit" name="impress_search_btn" id="impress_search_feed_btn" value="impress_search_feed_btn" class="button button-default"><span class="dashicons dashicons-search"></span></button>
                            </form>
                        </div>
                        <div class="col-dashboard-block">
                            <div class="col-dashboard-block-content">
                                <?php if (count($feeds_data) == 0) : ?>
                                    <div class="col-dashboard-item">
                                        <h3><?php _e('No items found', 'impress-pr'); ?></h3>
                                    </div>
                                <?php else : ?>
                                    <?php // Loop through each feed item and display each item as a hyperlink. ?>
                                    <?php
                                    krsort($feeds_data);
                                    foreach ($feeds_data as $item) :
                                        ?>
                                        <div class="col-dashboard-item">
                                            <div class="col-dashboard-item-img">
                                                <span class="dashboard-item-date"><b><?php echo $item['feed_date']; ?></b></span>
                                                <a target="_blank" href="<?php echo esc_url($item['feed_link']); ?>" title="<?php printf(__('Posted %s', 'my-text-domain'), $item['feed_date']); ?>"><img src="<?php echo $item['feed_thumb_src']; ?>" alt="<?php echo esc_html($item['feed_title']); ?>" /></a>
                                            </div>
                                            <div class="col-dashboard-item-content">
                                                <div class="col-dashboard-item-title"><a target="_blank" href="<?php echo esc_url($item['feed_link']); ?>" title="<?php printf(__('Posted %s', 'my-text-domain'), $item['feed_date']); ?>"><?php echo esc_html($item['feed_title']); ?></a></div>
                                                <?php echo $item['feed_content']; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard-col-4 col-right">
                        <div class="col-right-wrap">
                            <!--<div class="col-right-title"><h3>Swift Recommendation</h3></div>-->
                            <div class="col-right-content" style="display: none;">
                                <?php
                                $rss_recomm = fetch_feed('https://SwiftCRM.Com/support/tag/offers/feed');
                                $maxitems_recomm = 0;

                                if (!is_wp_error($rss_recomm)) : // Checks that the object is created correctly
                                    // Figure out how many total items there are, but limit it to 5.
                                    $maxitems_recomm = $rss_recomm->get_item_quantity(5);

                                    // Build an array of all the items, starting with element 0 (first element).
                                    $recommendations = $rss_recomm->get_items(0, $maxitems_recomm);
                                endif;
                                ?>
                                <?php if ($maxitems_recomm == 0) : ?>

                                <?php else : ?>
                                    <?php // Loop through each feed item and display each item as a hyperlink. ?>
                                    <?php foreach ($recommendations as $recomm) : ?>
                                        <div class="col-right-item">
                                            <div class="col-right-item-title"><a target="_blank" href="<?php echo esc_url($recomm->get_permalink()); ?>" title="<?php printf(__('Posted %s', 'my-text-domain'), $recomm->get_date('j F Y | g:i a')); ?>"><?php echo esc_html($recomm->get_title()); ?></a></div>
                                            <div class="col-right-item-content">
                                                <?php
                                                $recomm_thumb_src = '';
                                                $recomm_doc = new DOMDocument();
                                                @$recomm_doc->loadHTML($recomm->get_content());
                                                $recomm_tags = $recomm_doc->getElementsByTagName('img');

                                                foreach ($recomm_tags as $recomm_tag) {
                                                    $recomm_thumb_src = $recomm_tag->getAttribute('src');
                                                }
                                                $recomm_thumb_src = ($recomm_tags->length > 0) ? $recomm_thumb_src : plugins_url('../images/blank-img.png', __FILE__);
                                                ?>
                                                <a target="_blank" href="<?php echo esc_url($recomm->get_permalink()); ?>" title="<?php printf(__('Posted %s', 'my-text-domain'), $recomm->get_date('j F Y | g:i a')); ?>"><img src="<?php echo $recomm_thumb_src; ?>" alt="<?php echo esc_html($recomm->get_title()); ?>" /></a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                    <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="imkt_whitelist_flag" id="imkt_whitelist_flag" class="imkt_whitlist_flag">
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
        </div>
        <?php
    }

}
?>