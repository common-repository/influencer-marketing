<?php
/*
 *      TOOLS
 */
if (!function_exists('imkt_tools_callback')) {

    function imkt_tools_callback() {
        ?>
        <div class="wrap">
            <h2>Tools</h2>
            <hr/>
            <?php if (isset($_GET['update']) && !empty($_GET['update']) && $_GET['update'] == 1) { ?>
                <div id="message" class="notice notice-success is-dismissible below-h2">
                    <p>Setting updated successfully.</p>
                </div>
            <?php } ?>
            <div class="inner_content">
                <div class="imkt-tools-left" style="">
                    <table class="form-table">
                        <tr>
                            <th style="padding-bottom: 0;" colspan="2">Influencer Scoring <span class="dashicons dashicons-editor-help ttip" title="Alexa rank: Lower is better, ideal is anything < 50k. PageRank: Higher is better but anything above 1 is good"></span></th>
                        </tr>
                        <tr>
                            <td style="padding-left: 0;">
                                <input type="text" class="regular-text" id="imkt_tools_url" name="imkt_tools_url" placeholder="Enter URL" /><button type="button" class="button" id="imkt-tools-btn" value="imkt_tools_check_domain" name="imkt_tools_check_domain">Check Domain</button>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="imkt-tools-right">
                    <table class="form-table">
                        <tr>
                            <th style="padding-bottom: 0;" colspan="2">Guest Posting Search <span class="dashicons dashicons-editor-help ttip" title="Goal: find influential blogs that allow guest posting, relevant to your space. Ideal blogs have a higher pagerank and lower Alexa score, but if just starting out we recommend aiming for PR1-2 and Alexa 500,000 to 200,000."></span></th>
                        </tr>
                        <tr>
                            <td style="padding-left: 0;" >
                                <input type="text" class="regular-text" id="imkt_tools_search_keyword" name="imkt_tools_url" placeholder="Enter Keyword" /><button type="button" class="button" id="imkt_tools_btn_search_keyword" value="imkt_tools_btn_search_keyword" name="imkt_tools_btn_search_keyword"><span class="dashicons dashicons-search" style="vertical-align: text-top;"></span></button>
                            </td>
                        </tr>
                    </table>
                </div>

                <script type="text/javascript">
                    jQuery(document).ready(function() {
                        //left button :: check domain
                        jQuery("#imkt-tools-btn").on("click", function() {
                            var domain_url = jQuery.trim(jQuery("#imkt_tools_url").val());
                            if (domain_url !== '') {
                                var final_domain_url = "http://www.alexa.com/siteinfo/" + domain_url;
                                window.open(final_domain_url, "_blank");
                            } else {
                                jQuery("#imkt-tools-btn").after('<span class="keyword_error_msg">Please enter URL.</span>');
                                jQuery(".keyword_error_msg").delay(1400).fadeOut();
                            }
                        });

                        jQuery("#imkt_tools_btn_search_keyword").on('click', function() {
                            jQuery('.keyword_error_msg').remove();
                            var entered_keyword = jQuery.trim(jQuery("#imkt_tools_search_keyword").val());
                            if (entered_keyword !== '') {
                                var url1 = "https://www.google.com/search?q=" + entered_keyword + " submit a guest post";
                                var url2 = "https://www.google.com/search?q=" + entered_keyword + " guest post";
                                var url3 = "https://www.google.com/search?q=" + entered_keyword + " guest post by";
                                var url4 = "https://www.google.com/search?q=" + entered_keyword + " accepting guest posts";
                                var url5 = "https://www.google.com/search?q=" + entered_keyword + " guest post guidelines";
                                window.open(url1, "_blank");
                                window.open(url2, "_blank");
                                window.open(url3, "_blank");
                                window.open(url4, "_blank");
                                window.open(url5, "_blank");
                            }
                            else {
                                jQuery("#imkt_tools_btn_search_keyword").after('<span class="keyword_error_msg">Please enter keyword.</span>');
                                jQuery(".keyword_error_msg").delay(1400).fadeOut();
                            }
                        });
                    });
                </script>
            </div>
        </div>
        <?php
    }

}
?>