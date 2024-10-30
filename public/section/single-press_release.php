<?php
/**
 * The template for displaying all single press release posts
 *
 */
get_header();
wp_enqueue_script('imkt-custom-script', IMKT__PLUGIN_URL . 'public/js/imkt-custom-script.js', array('jquery'), '', true);

$imkt_logo_image_url = get_option('imkt_logo_image_url');
$imkt_press_contact_name = get_option('imkt_press_contact_name');
$imkt_press_contact_email = get_option('imkt_press_contact_email');
$imkt_press_contact_phone = get_option('imkt_press_contact_phone');
$press_post_id = '';

add_filter('the_content', 'impress_pr_content_general_info_filter', 9);
if (!function_exists('impress_pr_content_general_info_filter')) {

    function impress_pr_content_general_info_filter($content) {
        if (is_singular('press_release')) {
            $imkt_press_contact_name = get_option('imkt_press_contact_name');
            $imkt_press_contact_email = get_option('imkt_press_contact_email');
            $imkt_press_contact_phone = get_option('imkt_press_contact_phone');

            $phone = !empty($imkt_press_contact_name) && !empty($imkt_press_contact_phone) ? "call " . ucwords($imkt_press_contact_name) . " at <a href='tel:" . $imkt_press_contact_phone . "'>" . $imkt_press_contact_phone . "</a> " : "";
            $or = !empty($imkt_press_contact_name) ? "or" : "";
            $email = (!empty($imkt_press_contact_email) ? $or . ' email at <a href="mailto:' . $imkt_press_contact_email . '">' . $imkt_press_contact_email . '</a>' : "");

            $content = $content . '<div class="pr-detail-general-info pr-text-center">###<br/><br/><p>If you\'d like more information about this topic, please ' . $phone . $email . '</p></div>';
        }
        return $content;
    }

}
?>
<div class="pr-container container">
    <div class="pr-page-wrap pr-detail-page-wrap">
        <div class="pr-col-8">
            <div class="pr-content-wrap">
                <?php
                // Start the loop.
                while (have_posts()) : the_post();
                    $press_post_id = get_the_ID();
                    $pr_location = get_post_meta(get_the_ID(), 'press_location', true);
                    $press_headline = get_post_meta(get_the_ID(), 'press_release_subheadline', true);
                    $pr_content_date = get_the_date('d F, Y', get_the_ID());
                    ?>
                    <div id="post-<?php the_ID(); ?>" <?php post_class('pr-main-content'); ?>>
                        <div class="pr-row">
                            <?php if (!empty($imkt_logo_image_url)) { ?>
                                <div class="pr-logo">
                                    <img src="<?php echo $imkt_logo_image_url; ?>" alt="<?php echo strtoupper(get_the_title()); ?>" />
                                </div>
                            <?php } ?>
                        </div>
                        <div class="pr-row">
                            <div class="pr-col-5">
                                <div class="pr-contact-details">
                                    <ul>
                                        <li><?php echo (!empty($imkt_press_contact_name) ? $imkt_press_contact_name : ""); ?></li>
                                        <li><?php echo (!empty($imkt_press_contact_phone) ? $imkt_press_contact_phone : ""); ?></li>
                                        <li><?php echo (!empty($imkt_press_contact_email) ? '<a href="mailto:' . $imkt_press_contact_email . '">' . $imkt_press_contact_email . '</a>' : ""); ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="pr-col-5">
                                <p class="pr-location">FOR IMMEDIATE RELEASE</p>
                            </div>
                        </div>
                        <div class="pr-row">
                            <div class="pr-title">
                                <h1><?php echo strtoupper(get_the_title()); ?></h1>
                                <span class="pr-sub-title"><?php echo ucwords($press_headline); ?></span>
                            </div>
                        </div>
                        <div class="pr-row">
                            <div class="pr-content">
                                <?php
                                the_content();
                                echo (!empty($pr_location) ? '<span class="press-content-location pr-hidden">' . strtoupper($pr_location) . ' - </span>' : '');
                                echo (!empty($pr_content_date) ? '<span class="press-content-date pr-hidden">' . get_the_time('d F, Y', get_the_ID()) . ' - </span>' : '');
                                ?>
                            </div>
                        </div>
                        <div class="pr-row">
                            <div class="pr-tags-wrap">
                                <?php echo get_the_term_list(get_the_ID(), 'press_release_tag', '<ul class="pr-tags-list"><li>', '</li><li>', '</li></ul>'); ?>
                            </div>
                        </div>
                    </div>
                    <?php edit_post_link('Edit', '<p class="pr-edit-post tooltip-right" data-tooltip="Only you can see this because you\'re logged in.">', '</p>'); ?>
                    <?php
                endwhile;
                ?>
            </div>
        </div>
        <div class="pr-col-4 pr-sidebar-bg">
            <div class="pr-sidebar">
                <div class="pr-widget">
                    <div class="pr-pagination">
                        <div class="pr-prev-link pr-pagination-link tooltip-right" data-tooltip="Previous Release"><?php echo get_previous_post_link('%link', '<i class="fa fa-angle-double-left fa-lg"></i>'); ?></div>
                        <?php if (get_option('imkt_pr_listing_page_id')): ?><div class="pr-list-link pr-pagination-link tooltip-right" data-tooltip="Back to Press Room"><a href="<?php echo get_permalink(get_option('imkt_pr_listing_page_id')); ?>"><i class="fa fa-list"></i></a></div><?php endif; ?>
                        <div class="pr-next-link pr-pagination-link tooltip-right" data-tooltip="Next Release"><?php echo get_next_post_link('%link', '<i class="fa fa-angle-double-right fa-lg"></i>'); ?></div>
                    </div>
                    <div class="pr-print pr-pagination">
                        <div class="pr-prev-link pr-pagination-link tooltip-left" data-tooltip="Send this release to someone"><a href="mailto:?Subject=<?php echo get_the_title(); ?>&Body=<?php echo get_the_permalink(); ?>" target="_new"><i class="fa fa-paper-plane"></i></a></div>
                        <div class="pr-next-link pr-pagination-link tooltip-left" data-tooltip="PDF / Print Version"><a href="<?php echo get_permalink() . "?print=1&pr=" . $press_post_id; ?>"><i class="fa fa-file-pdf-o fa-lg"></i></a></div>
                    </div>
                </div>
                <?php include_once 'imkt-press-release-sidebar.php'; ?>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
