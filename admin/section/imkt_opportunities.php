<?php
/*
 *      Opportunities
 */
if (!function_exists('imkt_opportunities_callback')) {

    function imkt_opportunities_callback() {
        ?>
        <div class="wrap">
            <h2>Opportunities</h2><hr/>
        <?php if (isset($_GET['update']) && !empty($_GET['update']) && $_GET['update'] == 1) { ?>
                <div id="message" class="notice notice-success below-h2">
                    <p>Setting updated successfully.</p>
                </div>
                <?php
            }
            ?>
            <div class="inner_content">
                <h2>Coming soon...</h2>
            </div>
        </div>
        <?php
    }

}
?>