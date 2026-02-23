<?php
$siteRegulars = Config::find_by_id(1);
$booking_code = Config::getField('hotel_code', true);
$header = ob_get_clean();
$sidebarlogo = '';
$header = '



    <header class="ul-header ul-header-2">
        <div class="ul-header-bottom to-be-sticky">
            <div class="ul-header-bottom-wrapper ul-header-container">
                <div class="logo-container">
                    <a href="' . BASE_URL . '' . '" class="d-inline-block">
                        <img src="' . IMAGE_PATH . 'preference/' . $siteRegulars->logo_upload . '" alt="logo" class="logo">
                    </a>
                </div>

                <!-- header nav -->
                ' . $jVars['module:res-menu1'] . '
                <!-- actions -->
                <div class="ul-header-actions">
                    <a href="#" class="ul-btn d-sm-inline-flex" data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                            class="flaticon-fast-forward-double-right-arrows-symbol"></i>  Become A Volunteer </a>
                    <button class="ul-header-sidebar-opener d-lg-none d-inline-flex"><i
                            class="flaticon-menu"></i></button>
                </div>
            </div>
        </div>
    </header>



';

$jVars['module:header'] = $header;

$sidebarlogo = '
                <a href="' . BASE_URL . 'home' . '">
                    <img src="' . IMAGE_PATH . 'preference/' . $siteRegulars->logo_upload . '" alt="logo" class="logo">
                </a>



';

$jVars['module:sidebarlogo'] = $sidebarlogo;






// $header1 = '
//                 <header class="site-header">
//                <div class="logo">
//                	<a href="' . BASE_URL . 'home' . '"><img src="' . IMAGE_PATH . 'preference/' . $siteRegulars->logo_upload . '" style="border-radius: 6%; background-color: white;"></a>
//                </div> 
//             </header>

//             <div id="main-content" class="twelve columns">
//                 ' . $jVars['module:slideshow-content'] . '
$headerscript = '';
$tellinked = '';
$telno = explode("/", $siteRegulars->contact_info);
$lastElement = array_shift($telno);
$tellinked .= '<a href="tel:' . $lastElement . '" target="_blank" rel="noreferrer">' . $lastElement . '</a>/';
foreach ($telno as $tel) {

    $tellinked .= '<a href="tel:+977-' . $tel . '" target="_blank" rel="noreferrer">' . $tel . '</a>';
    if (end($telno) != $tel) {
        $tellinked .= '/';
    }
}
