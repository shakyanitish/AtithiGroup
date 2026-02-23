<?php
$siteRegulars = Config::find_by_id(1);
$lastElement = '';
$phonelinked = '';
$whatsapp = '';
$tellinked = '';
$contact = '';

$tellinked = '';
$telno = array_map('trim', explode(',', $siteRegulars->contact_info));

foreach ($telno as $index => $tel) {
    // remove spaces for tel link
    $cleanTel = str_replace(' ', '', $tel);

    $tellinked .= '<a href="tel:+977' . $cleanTel . '">
                    <i class="flaticon-telephone-call"></i>+977 ' . $tel . '
               </a>';

    // separator except last item
    if ($index !== array_key_last($telno)) {
        $tellinked .= ' ';
    }
}




$office = '';
$ot = explode(",", $siteRegulars->pobox);

$first = trim(array_shift($ot));
$office .= '<span>' . $first . '</span>';

foreach ($ot as $o) {
    $o = trim($o);
    $office .= ', <span>' . $o . '</span>';
}


$emailinked = '';
$emails = array_map('trim', explode(',', $siteRegulars->email_address));

foreach ($emails as $index => $email) {
    $emailinked .= '<a href="mailto:' . $email . '">
                        <i class="flaticon-mail"></i>' . $email . '
                   </a>';

    // separator except last item
    if ($index !== array_key_last($emails)) {
        $emailinked .= ' ';
    }
}

$whatsapp = '';
$phoneno = explode("/", $siteRegulars->whatsapp);
$lastElement = array_shift($phoneno);
$phonelinked .= '<a href="tel:+977-' . $lastElement . '" target="_blank" rel="noreferrer">' . $lastElement . '</a>/';
foreach ($phoneno as $phone) {

    $phonelinked .= '<a href="tel:+977-' . $phone . '" target="_blank" rel="noreferrer">' . $phone . '</a>';
    if (end($phoneno) != $phone) {
        $phonelinked .= '/';
    }
}
$breif = explode('<hr id="system_readmore" style="border-style: dashed; border-color: orange;" />', trim($siteRegulars->breif));
$icons = '';
if (!empty($socialRec)) {
    foreach ($socialRec as $socialRow) {
        $icons .= '
            <a href="' . $socialRow->linksrc . '" class="ms-2" target="_blank" rel="noreferrer noopener">
                <img src="' . IMAGE_PATH . 'social/' . $socialRow->image . '" height="20" alt="">
            </a>
        ';
    }
}


$footer = '

    <footer class=" text-white pb-10 px-6 md:px-10 overflow-hidden">
        <div class="max-w-[1280px] mx-auto">
            <div
                class="flex flex-col md:flex-row justify-center items-center border-t border-black/10 pt-8 text-sm text-gray-500">
                <p>' . $jVars['site:copyright'] . '
                    Developed by <a href="https://longtail.info/" target="_blank" rel="noopener noreferrer"
                        class="hover:text-primary">Longtail e-media</a></p>
            </div>
        </div>
    </footer> ';



$jVars['module:footer'] = $footer;
