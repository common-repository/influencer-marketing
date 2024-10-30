<?php
/*
 * Template Name: Press Releases
 * Description:
 */
get_header();

$imkt_logo_image_url = get_option('imkt_logo_image_url');
$imkt_press_contact_name = get_option('imkt_press_contact_name');
$imkt_press_contact_email = get_option('imkt_press_contact_email');
$imkt_press_contact_phone = get_option('imkt_press_contact_phone');
$press_rss_feed_page_id = get_option('imkt_press_feed_page_id');
?>
<div class="pr-container container">
    <div class="pr-page-wrap press-release">
        <div class="pr-col-8">
            <div class="pr-content-wrap">
                <?php
                // Start the loop.
                while (have_posts()) : the_post();
                    ?>
                    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="pr-title">
                            <h1><?php the_title(); ?></h1>
                        </div>
                        <div class="pr-search-box">
                            <form id="prSearchForm" class="pr-search-form" action="<?php echo home_url(); ?>" method="get" role="search">
                                <div class="pr-input-group">
                                    <input type="text" id="s" name="s" value="" class="pr-search-control" placeholder="Search...">
                                    <input type="hidden" name="post_type" value="press_release" />
                                    <span class="pr-group-btn">
                                        <button type="submit" value="Search" id="searchsubmit" class="pr-btn-orange"><i class="fa fa-search fa-lg"></i></button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <div class="pr-content">
                            <?php the_content(); ?>
                        </div>
                        <div class="pr-content">
                            <div class="pr_powered_by">
                                Powered by <a href="https://SwiftCRM.Com/" target="_blank">SwiftCloud</a> <a href="https://wordpress.org/plugins/influencer-marketing/" target="_blank">Press Release Software</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="pr-col-4 pr-sidebar-bg">
            <div class="pr-sidebar">
                <div class="pr-widget">
                    <div class="pr-print pr-pagination">
                        <div class="pr-prev-link pr-pagination-link tooltip-left" data-tooltip="Send this release to someone"><a href="mailto:?Subject=<?php echo get_the_title(); ?>&Body=<?php echo get_the_permalink(); ?>" target="_new"><i class="fa fa-paper-plane"></i></a></div>
                        <div class="pr-next-link pr-pagination-link tooltip-left" data-tooltip="RSS Feed"><a href="<?php echo get_permalink($press_rss_feed_page_id); ?>" target="_blank"><i class="fa fa-rss fa-lg"></i></a></div>
                    </div>
                </div>
                <?php include_once 'public/section/imkt-press-release-sidebar.php'; ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        var nav = jQuery("body").find("nav").css("position");
        var header = jQuery("body").find("header").css("position");
        var padding = '';

        if (header === 'fixed') {
            padding = jQuery("body").find("header").height();
        } else if (nav === 'fixed') {
            padding = jQuery("body").find("nav").height();
        }
        if (padding !== '') {
            jQuery(".pr-page-wrap").css('padding-top', padding);
        }
    });
</script>
<?php
get_footer();