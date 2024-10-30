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
?>
asdf
<div class="pr-container container">
    <div class="pr-page-wrap press-release">
        <div class="pr-col-8">
            <div class="pr-content-wrap">
                <?php if (have_posts()) : ?>
                    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="pr-title">
                            <h1><?php the_archive_title(); ?></h1>
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
                            <?php while (have_posts()) : the_post(); ?>
                                <?php
                                $op.='<div class="press-release-wrap"><div class="press-release-content">';
                                $press_location = get_post_meta(get_the_ID(), 'press_location', true);

                                $op.='<div class="pr-artical"><a href="' . get_permalink(get_the_ID()) . '">';
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
                                $op.=get_the_term_list(get_the_ID(), 'tag_press_release', '<ul class="pr-tags-list"><li>', '</li><li>', '</li></ul>');
                                $op.='</div>';
                                $op.='</div>';

                                $op.="</div></div>";
                                $op.='<div class="pr-pagination"><div class="pr-pre">' . get_previous_posts_link("Previous", $post->max_num_pages) . '</div><div class="pr-next">' . get_next_posts_link("Next", $post->max_num_pages) . '</div></div>';
                                echo $op;
                                ?>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="pr-title">
                        <h1><?php the_archive_title(); ?></h1>
                    </div>
                    <div><h3>No press release found....!</h3></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="pr-col-4 pr-sidebar-bg">
            <div class="pr-sidebar">
                <?php include_once 'public/section/imkt-press-release-sidebar.php'; ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
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
?>