<?php

/*
 *      Custom excerpt function
 */

if (!function_exists('imkt_get_excerpt')) {

    function imkt_get_excerpt($excerpt_length = 55, $id = false, $echo = false) {
        return imkt_excerpt($excerpt_length, $id, $echo);
    }

}

if (!function_exists('imkt_excerpt')) {

    function imkt_excerpt($excerpt_length = 55, $id = false, $echo = false) {

        $text = '';

        if ($id) {
            $the_post = & get_post($my_id = $id);
            $text = ($the_post->post_excerpt) ? $the_post->post_excerpt : $the_post->post_content;
        } else {
            global $post;
            $text = ($post->post_excerpt) ? $post->post_excerpt : get_the_content('');
        }

        $text = strip_shortcodes($text);
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]&gt;', $text);
        $text = strip_tags($text);

        $excerpt_more = ' ' . '<a href=' . get_permalink($id) . ' class="imkt-readmore">...continued</a>';
        $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
        if (count($words) > $excerpt_length) {
            array_pop($words);
            $text = implode(' ', $words);
            $text = $text . $excerpt_more;
        } else {
            $text = implode(' ', $words);
        }
        if ($echo)
            echo apply_filters('the_content', $text);
        else
            return $text;
    }

}


/*
 *      function : imkt_get_rss_feeds(limit)
 *          - limit  : Default 30;
 *          return   : array()
 */
if (!function_exists('imkt_get_rss_feeds')) {

    function imkt_get_rss_feeds($feed_limit = 30) {
        global $wpdb;
        $table_influencers = $wpdb->prefix . 'imkt_influencers';

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
                                $feed_thumb_src = ($tags->length > 0) ? $feed_thumb_src : IMKT__PLUGIN_URL.'public/images/blank-img.png';
                                $link = esc_url($item->get_permalink());
                                $date = $item->get_date('j F Y | g:i a');
                                $content = $item->get_content();
                                $title = $item->get_title();

                                $feeds_data[] = array(
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
                if (count($feeds_data) >= $feed_limit) {
                    break;
                }
            }
            return $feeds_data;
        }
    }

}
?>