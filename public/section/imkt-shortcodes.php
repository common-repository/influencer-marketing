<?php

/*
 *     Press Release Shortcodes
 */

/*
 *      Shortcode : [press_release_list category="category id"]
 *      - Display list of press release post.
 *      - category : Optional;category slug; single or comma separated;  Show reviews in a specific category(s)
 *      - display : Optional; title, title+date, all; display listing
 */
add_shortcode('press_release_list', 'imkt_press_release_list_callback');
if (!function_exists('imkt_press_release_list_callback')) {

    function imkt_press_release_list_callback($atts) {
        $op = '';
        $a = shortcode_atts(
                array(
            'category' => '',
            'display' => '',
                ), $atts);
        extract($a);

        $pr_paged = (get_query_var('paged') ) ? get_query_var('paged') : 1;
        $args = array(
            'post_type' => 'press_release',
            'post_status' => 'publish',
            'posts_per_page' => 10,
            'paged' => $pr_paged,
            'orderby' => 'date',
            'order' => 'DESC'
        );

        $cat_array = !empty($category) ? explode(",", $category) : '';
        if ($cat_array) {
            $args['tax_query'] = array(array('taxonomy' => 'press_release_category', 'field' => 'slug', 'terms' => $cat_array));
        }

        $press_release_posts = new WP_Query($args);

        $op.='<div class="press-release-wrap"><div class="press-release-content">';
        if ($press_release_posts->have_posts()):
            while ($press_release_posts->have_posts()) : $press_release_posts->the_post();
                $press_location = get_post_meta(get_the_ID(), 'press_location', true);

                switch ($display) {
                    case 'title': {
                            $op.='<div class="pr-artical imkt-display-title">';
                            $op.='<a href="' . get_permalink(get_the_ID()) . '">';
                            $op.='<div class="pr-artical-header">';
                            $op.='<div class="pr-artical-title"><h2>' . get_the_title() . '</h2></div>';
                            $op.='</div>';
                            $op.='</a>';
                            $op.='</div>';
                            break;
                        }
                    case 'title+date': {
                            $op.='<div class="pr-artical imkt-display-title-date">';
                            $op.='<a href="' . get_permalink(get_the_ID()) . '">';
                            $op.='<div class="pr-artical-header">';
                            $op.='<div class="pr-artical-title"><h2>' . get_the_title() . '<span>- ' . get_the_time('d F, Y', get_the_ID()) . '</span></h2></div>';
                            $op.='</div>';
                            $op.='</a>';
                            $op.='</div>';
                            break;
                        }
                    default : {
                            $op.='<div class="pr-artical imkt-display-all"><a href="' . get_permalink(get_the_ID()) . '">';
                            $op.='<div class="pr-artical-header">';
                            $op.='<div class="pr-artical-title"><h2>' . get_the_title() . '</h2></div>';
                            $op.='</div>';
                            $op.='<div class="pr-artical-content">';
                            if (!empty($press_location)) {
                                $op.='<div class="pr-artical-location">' . $press_location . '&nbsp;-&nbsp;</div>';
                            }
                            $op.='<div class="pr-artical-date">' . get_the_time('d F, Y', get_the_ID()) . '&nbsp;-&nbsp;</div>';
                            $op.= imkt_get_excerpt();
                            $op.= '</div>';
                            $op.='</a>';
                            $op.='<div class="pr-tags-wrap">';
                            $op.=get_the_term_list(get_the_ID(), 'press_release_tag', '<ul class="pr-tags-list"><li>', '</li><li>', '</li></ul>');
                            $op.='</div>';
                            $op.='</div>';
                        }
                }
            endwhile;
        else :
            $op.='<div><h3>No press releases found....yet</h3></div>';
        endif;

        $op.="</div></div>";
        $op.='<div class="pr-pagination"><div class="pr-pre">' . get_previous_posts_link("Previous", $press_release_posts->max_num_pages) . '</div><div class="pr-next">' . get_next_posts_link("Next", $press_release_posts->max_num_pages) . '</div></div>';

        wp_reset_postdata();

        return $op;
    }

}