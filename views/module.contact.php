<?php
/*
* Contact form
*/
$rescont = '';
$img = '';
if (defined('CONTACT_PAGE')) {

    $siteRegulars = Config::find_by_id(1);

    // Phone links
    $tellinked = '';
    $telno = explode(",", $siteRegulars->contact_info);
    $lastElement = array_shift($telno);
    $tellinked .= '<a href="tel:+977' . str_replace(' ', '', $lastElement) . '" target="_blank">+977 ' . $lastElement . '</a><br>';
    foreach ($telno as $tel) {
        $tellinked .= '<a href="tel:+977' . str_replace(' ', '', $tel) . '" target="_blank">+977 ' . $tel . '</a>';
        if (end($telno) != $tel) {
            $tellinked .= '/';
        }
    }

    // Office address
    $office = '';
    $ot = explode(",", $siteRegulars->pobox);
    $first = trim(array_shift($ot));
    $office .= '<span>' . $first . '</span>';
    foreach ($ot as $o) {
        $o = trim($o);
        $office .= ', <span>' . $o . '</span>';
    }



    $emailinked = '';
    $emails = explode(",", $siteRegulars->email_address); // use only one field
    $emails = array_map('trim', $emails); // remove spaces
    $totalEmails = count($emails);
    $countEmail = 0;

    foreach ($emails as $email) {
        $countEmail++;
        $emailinked .= '<a href="mailto:' . $email . '" target="_blank" rel="noreferrer" title="' . $email . '">' . $email . '</a>';
        if ($countEmail < $totalEmails) {
            $emailinked .= '<br> '; // add comma only between emails
        }
    }


    // WhatsApp / phone links
    $phonelinked = '';
    $phoneno = explode("/", $siteRegulars->whatsapp);
    $lastElement = array_shift($phoneno);
    $phonelinked .= '<a href="tel:+977' . str_replace(' ', '', $lastElement) . '" target="_blank">' . $lastElement . '</a>/';
    foreach ($phoneno as $phone) {
        $phonelinked .= '<a href="tel:+977' . str_replace(' ', '', $phone) . '" target="_blank">' . $phone . '</a>';
        if (end($phoneno) != $phone) {
            $phonelinked .= '/';
        }
    }

    // Image
    $imglink = $siteRegulars->contact_upload;
    if (!empty($imglink)) {
        $img = IMAGE_PATH . 'preference/contact/' . $siteRegulars->contact_upload;
    } else {
        $img = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
    }

    // Section HTML
    $rescont .= '


        <div class="ul-contact-infos">
            <div class="ul-section-spacing ul-container">
                <div class="row row-cols-md-3 row-cols-2 row-cols-xxs-1 ul-bs-row">
                    <!-- single info -->
                    <div class="col">
                        <div class="ul-contact-info">
                            <div class="icon"><i class="flaticon-location"></i></div>
                            <div class="txt">
                                <span class="title">Office Address</span>
                                <span class="descr">' . $siteRegulars->fiscal_address . '</span>
                            </div>
                        </div>
                    </div>
                    <!-- single info -->
                    <div class="col">
                        <div class="ul-contact-info">
                            <div class="icon"><i class="flaticon-phone-call"></i></div>
                            <div class="txt">
                                <span class="title">Phone number</span>
                                ' . $tellinked . '
                            </div>
                        </div>
                    </div>
                    <!-- single info -->
                    <div class="col">
                        <div class="ul-contact-info">
                            <div class="icon"><i class="flaticon-comment"></i></div>
                            <div class="txt">
                                <span class="title">Email address</span>
                                ' . $emailinked . '
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
';
    $map = '
                        <div class="ul-contact-map">
                        <iframe src="' . $siteRegulars->location_map . '" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            
                        </div>
';

    $jVars['module:contact-us'] = $rescont;
    $jVars['module:contact-map'] = $map;



    //uc contact details
    $home_contact = '';
    $configRec = Config::find_by_id(1);
    $home_contact .= ' 

            <div class="whats_app">
                <a href="' . $configRec->whatsapp . '" data-original-href="' . $configRec->whatsapp . '" target="_blank" class="whatsapp" rel="noreferrer">
                    <img src="' . BASE_URL . 'template/web/assets/img/whatsapp.png" class="whatsapp_img" alt="whatsapp">
                </a>
            </div>
                ';

    $jVars['module:contact-home'] = $home_contact;
}
