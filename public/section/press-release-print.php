<?php

/*
 *      Print PDF
 */
require_once IMKT__PLUGIN_DIR . '/include/pdf/html2pdf.php';

add_action("init", "imkt_impress_pr_print_pdf_callback");
if (!function_exists('imkt_impress_pr_print_pdf_callback')) {

    function imkt_impress_pr_print_pdf_callback() {
        if (isset($_GET['print']) && !empty($_GET['print']) && $_GET['print'] == '1' && isset($_GET['pr']) && !empty($_GET['pr'])) {
            $pr_post_id = sanitize_text_field($_GET['pr']);
            $pr_post_id = intval($pr_post_id);
            if ($pr_post_id) {
                $pr_post = get_post($pr_post_id);

                if (!empty($pr_post)) {
                    $content = '';

                    //$imkt_default_location = get_option('imkt_default_location');
                    $imkt_logo_image_url = get_option('imkt_logo_image_url');
                    $imkt_contact_name = get_option('imkt_press_contact_name');
                    $imkt_contact_email = get_option('imkt_press_contact_email');
                    $imkt_contact_phone = get_option('imkt_press_contact_phone');

                    $pr_location = get_post_meta($pr_post->ID, 'press_location', true);
                    $pr_sub_headline = get_post_meta($pr_post->ID, 'press_release_subheadline', true);
                    $pr_content_date = get_the_date('d F, Y', $pr_post->ID);

                    $location = (!empty($pr_location) ? strtoupper($pr_location) . ' - ' : '');
                    $pr_date = (!empty($pr_content_date) ? $pr_content_date . ' - ' : '');

                    $title = stripslashes($pr_post->post_title);
                    $title = iconv('UTF-8', 'windows-1252', $title);

                    $pr_sub_headline = stripslashes($pr_sub_headline);
                    $pr_sub_headline = iconv('UTF-8', 'windows-1252', $pr_sub_headline);

                    $content = stripslashes($pr_post->post_content);
                    $content = iconv('UTF-8', 'windows-1252', $content);

                    /* general info */
                    $phone = !empty($imkt_contact_name) && !empty($imkt_contact_phone) ? "call " . ucwords($imkt_contact_name) . " at " . $imkt_contact_phone : "";
                    $or = !empty($imkt_contact_name) ? "or" : "";
                    $email = (!empty($imkt_contact_email) ? $or . ' email at ' . $imkt_contact_email : "");

                    $general_info = "If you'd like more information about this topic, please " . $phone . $email;

                    $supported_img_ext = array('JPG', 'JPEG', 'PNG', 'GIF');
                    $img_ext = pathinfo($imkt_logo_image_url, PATHINFO_EXTENSION);

                    /* start pdf write */
                    $pdf = new PDF_HTML('P', 'mm', 'A4');
                    $pdf->AddPage();

                    //Logo
                    if (in_array(strtoupper($img_ext), $supported_img_ext)) {
                        $pdf->Image($imkt_logo_image_url, 80, 7, 50, 12, $img_ext);
                        $pdf->Ln(15);
                    }
                    $x = $pdf->GetX();
                    $y = $pdf->GetY();

                    // left side: Name
                    $pdf->SetFont('Arial', '', 14);
                    $pdf->MultiCell(90, 0, $imkt_contact_name, 0);
                    $pdf->SetXY($x + 95, $y);

                    //right side : location
                    $pdf->MultiCell(95, 0, $pr_location, 0, "R");
                    $pdf->Ln(7);

                    // left side : Phone
                    $pdf->Cell(50, 0, $imkt_contact_phone, 0, 0);
                    $pdf->Ln(7);

                    // left side: Email
                    $pdf->Cell(50, 0, $imkt_contact_email, 0, 0);
                    $pdf->Ln(6);

                    // main title
                    $pdf->SetFont('Arial', '', 20);
                    $pdf->SetX($x);
                    $pdf->MultiCell(190, 9, $title, 0, "C");
                    $pdf->Ln(2);

                    // sub title
                    $pdf->SetFont('Arial', '', 14);
                    $pdf->SetX($x + 15);
                    $pdf->MultiCell(160, 6, $pr_sub_headline, 0, "C");
                    $pdf->Ln(7);

                    // content
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->WriteHTML($location . $pr_date . $content);
                    $pdf->Ln(10);

                    $pdf->Cell(0, 6, '###', 0, 0, 'C');
                    $pdf->Ln(10);

                    $pdf->Write(6, $general_info);

                    $pdf->Output('D','press_release.pdf',TRUE);
                }
            }
        }
    }

}