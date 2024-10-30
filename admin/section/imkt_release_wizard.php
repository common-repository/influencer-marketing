<?php
/*
 *      Release Wizard
 */
if (!function_exists('imkt_release_wizard_callback')) {

    function imkt_release_wizard_callback() {
        if (isset($_POST['imkt_draft_release']) && wp_verify_nonce($_POST['imkt_draft_release'], 'imkt_draft_release')) {
            $headline = sanitize_text_field($_POST['imkt_release_headline']);
            $subheadline = sanitize_text_field($_POST['imkt_release_sub_headline']);
            $qus_one = wp_kses_post($_POST['imkt_qus_one']);
            $qus_two = wp_kses_post($_POST['imkt_qus_two']);
            $qus_three = wp_kses_post($_POST['imkt_qus_three']);
            $qus_four = wp_kses_post($_POST['imkt_qus_four']);
            $qus_five = wp_kses_post($_POST['imkt_qus_five']);
            $qus_six = wp_kses_post($_POST['imkt_qus_six']);
            $qus_seven = wp_kses_post($_POST['imkt_qus_seven']);
            $qus_eight = wp_kses_post($_POST['imkt_qus_eight']);

            $post_content = '';
            $post_content .= !empty($qus_one) ? "<p>" . $qus_one . "</p>" : '';
            $post_content .= !empty($qus_two) ? "<p>" . $qus_two . "</p>" : '';
            $post_content .= !empty($qus_three) ? "<p>" . $qus_three . "</p>" : '';
            $post_content .= !empty($qus_four) ? "<p>" . $qus_four . "</p>" : '';
            $post_content .= !empty($qus_five) ? "<p>" . $qus_five . "</p>" : '';
            $post_content .= !empty($qus_six) ? "<p>" . $qus_six . "</p>" : '';
            $post_content .= !empty($qus_seven) ? "<p>" . $qus_seven . "</p>" : '';
            $post_content .= !empty($qus_eight) ? "<p>" . $qus_eight . "</p>" : '';


            if (!empty($qus_seven)) {
                $update_comp_info = update_option('imkt_info_about_company', $qus_seven);
            }
            if (!empty($headline)) {
                $imkt_release_array = array(
                    'post_status' => 'draft',
                    'post_type' => 'press_release',
                    'post_title' => $headline,
                    'post_content' => $post_content,
                    'comment_status' => 'closed'
                );
                $imkt_release_id = wp_insert_post($imkt_release_array);
                if (!empty($imkt_release_id)) {
                    wp_set_object_terms($imkt_release_id, 'press_releases', 'press_release_category', true);
                    if (!empty($subheadline)) {
                        update_post_meta($imkt_release_id, 'press_release_subheadline', sanitize_text_field($subheadline));
                    }
                }
            }
            if ($update_comp_info || $imkt_release_id) {
                $release_id = (!empty($imkt_release_id)) ? '&releaseid=' . $imkt_release_id : '';
                wp_redirect(admin_url("admin.php?page=imkt_release_wizard" . $release_id . "&update=1"));
                die;
            }
        }

        $company_info = get_option('imkt_info_about_company');
        ?>
        <div class="wrap">
            <h2>Release Wizard</h2><hr/>
            <?php
            if (isset($_GET['update']) && !empty($_GET['update']) && $_GET['update'] == 1) {
                $releaseid = sanitize_text_field($_GET['releaseid']);
                $preview_link = !empty($releaseid) ? '<a target="_blank" href="' . home_url() . '?post_type=press_release&p=' . $releaseid . '&preview=true">See preview</a>' : '';
                ?>
                <div id="message" class="notice notice-success is-dismissible below-h2">
                    <p>Press release added successfully. <?php echo $preview_link; ?></p>
                </div>
                <?php
            }
            ?>
            <div class="inner_content">
                <form name="FrmImktReleaseWizard" id="FrmImktReleaseWizard" method="post">
                    <table class="form-table" style="width: 50%;">
                        <tr>
                            <th><label for="imkt_release_headline">Headline: Who did what, in as few words as possible</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" required="required" id="imkt_release_headline" name="imkt_release_headline" style="width: 100%;" /></td>
                        </tr>
                        <tr>
                            <th><label for="imkt_release_sub_headline">Optional: Subheadline</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" id="imkt_release_sub_headline" name="imkt_release_sub_headline" style="width: 100%;" /></td>
                        </tr>

                        <tr>
                            <th><label for="imkt_qus_one">Who did what? Summarize in as few words as possible</label></th>
                        </tr>
                        <tr>
                            <td><textarea rows="3" cols="75" id="imkt_qus_one" name="imkt_qus_one" ></textarea></td>
                        </tr>

                        <tr>
                            <th><label for="imkt_qus_two">Who should care, and why should they care?</label></th>
                        </tr>
                        <tr>
                            <td><textarea rows="3" cols="75" id="imkt_qus_two" name="imkt_qus_two" ></textarea></td>
                        </tr>

                        <tr>
                            <th><label for="imkt_qus_three">Give us a quote from you or the CEO explaining further, in terms the audience will understand:</label></th>
                        </tr>
                        <tr>
                            <td><textarea rows="3" cols="75" id="imkt_qus_three" name="imkt_qus_three" ></textarea></td>
                        </tr>

                        <tr>
                            <th><label for="imkt_qus_four">What broad trend(s) does this play into?</label></th>
                        </tr>
                        <tr>
                            <td><textarea rows="3" cols="75" id="imkt_qus_four" name="imkt_qus_four" ></textarea></td>
                        </tr>

                        <tr>
                            <th><label for="imkt_qus_five">What's newsworthy and unique and new about this tool, approach, method?</label></th>
                        </tr>
                        <tr>
                            <td><textarea rows="3" cols="75" id="imkt_qus_five" name="imkt_qus_five" ></textarea></td>
                        </tr>

                        <tr>
                            <th><label for="imkt_qus_six">Give us another quote! Recap the benefits</label></th>
                        </tr>
                        <tr>
                            <td><textarea rows="3" cols="75" id="imkt_qus_six" name="imkt_qus_six" ></textarea></td>
                        </tr>

                        <tr>
                            <th><label for="imkt_qus_seven">More information about the company:</label></th>
                        </tr>
                        <tr>
                            <td><textarea rows="5" cols="75" id="imkt_qus_seven" name="imkt_qus_seven" ><?php echo $company_info; ?></textarea></td>
                        </tr>

                        <tr>
                            <th><label for="imkt_qus_eight">Why did you / your team / this company do this? It's in a response to what industry changes, etc.?</label></th>
                        </tr>
                        <tr>
                            <td><textarea rows="3" cols="75" id="imkt_qus_eight" name="imkt_qus_eight" ></textarea></td>
                        </tr>

                        <tr>
                            <th colspan="2" style="text-align: center;">
                                <?php wp_nonce_field('imkt_draft_release', 'imkt_draft_release'); ?>
                                <button type="submit" class="button-orange" id="imkt-release-wizard-btn" value="imkt-release-wizard" name="imkt_release_wizard">Generate Press Release Starter Draft</button>
                            </th>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <?php
    }

}
?>