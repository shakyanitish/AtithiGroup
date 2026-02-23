    <?php
    /*
    * Service home list
    */

    $rescont = $res = '';


    $subpkgRec = Services::getservice_list(1000, 2); 

    if (!empty($subpkgRec)) {

        foreach ($subpkgRec as $k => $v) {


                // Main image
                $imglink = '';
                if ($v->image != "a:0:{}") {
                    $imageList = unserialize($v->image);
                    $file_path = SITE_ROOT . 'images/services/' . $imageList[0];
                    if (file_exists($file_path)) {
                        $imglink = IMAGE_PATH . 'services/' . $imageList[0];
                    }
                }

                // Icon image
                $iconlink = '';
                if (!empty($v->iconimage) && $v->iconimage != "a:0:{}") {
                    $iconList = unserialize($v->iconimage);
                    $file_path_icon = SITE_ROOT . 'images/services/icon/' . $iconList[0];
                    if (file_exists($file_path_icon)) {
                        $iconlink = IMAGE_PATH . 'services/icon/' . $iconList[0];
                    }
                }
                // Only create link if linksrc exists in database
                if (!empty($v->linksrc)) {
                    $linkTarget = ($v->linktype == 1) ? ' target="_blank" ' : '';
                    $linksrc = ($v->linktype == 1) ? $v->linksrc : BASE_URL . $v->linksrc;
                    $titleHtml = '<a href="' . $linksrc . '"' . $linkTarget . '>
                            <h3 class="ul-feature-title">' . $v->title . '</h3>
                            </a>';
                } else {
                    // No link - just display the title without anchor
                    $titleHtml = '
                    <h3 class="ul-feature-title">' . $v->title . '</h3>';
                }

                $res .= '

                            <!-- single slide -->
                        <div class="swiper-slide">
                            <div class="ul-feature">
                                <div class="ul-feature-icon">
                                    <img src="' . $iconlink . '">
                                </div>
                                ' . $titleHtml . '
                            </div>
                        </div>
                ';

            }
        }

    // Wrap the features in the section structure
    $rescont = '

            <section class="ul-features ul-section-spacing">
            <div class="ul-container">
                <div class="ul-testimonial-2-slider-home swiper">
                    <div class="swiper-wrapper">
                     ' . $res . '
                    </div>
                </div>
            </div>
        </section>
    ';

    $jVars['module:home-service-list'] = $rescont;       



    $restscont = '';

    $servpkgRec = Services::find_all();
    // var_dump($subpkgRec); die();
    if (isset($_REQUEST['slug']) and !empty($_REQUEST['slug'])) {
        $slug = $_REQUEST['slug'];
    } else {
        $slug = 'health-club';
    }
    if (!empty($subpkgRec)) {
        $i = 0;
        $j = 0;
        $restscont .= '<div class="tab-section bg-gray body-room-5">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="text-center">
                                <h2 class="mb-0">Services</h2>
                                <ul class="pages-link">
                                    <li><a href="' . BASE_URL . 'home">Home</a></li>
                                    <li>/</li>
                                    <li>Services</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="dining-tabs">
                            <ul class="nav nav-tabs">';
        foreach ($servpkgRec as $key => $serRec) {
            if ($slug == $serRec->slug) {
                $class = "active";
            } else {
                $class = "";
            }
            $actv = ($i == 0) ? 'active' : '';
            $restscont .= '<li class="' . $class . '">
                                    <a href="#Sauna' . $serRec->id . '" id="' . $serRec->slug . '" role="tab" data-toggle="tab">' . $serRec->title . '<small class="d-block">' . $serRec->sub_title . '</small></a>
                                </li>';
            $i++;
        }
        $restscont .= '  </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block small-padding both-padding page">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="tab-content">';
        foreach ($servpkgRec as $key => $serRec) {
            $imageList = '';
            if ($serRec->image != "a:0:{}") {
                $imageList = unserialize($serRec->image);
            }
            if ($slug == $serRec->slug) {
                $class1 = "active";
            } else {
                $class1 = "";
            }
            $actv = ($j == 0) ? 'active' : '';
            $restscont .= '<div role="tabpanel" class="tab-pane fade in ' . $class1 . '" id="Sauna' . $serRec->id . '">
                                    <div class="dining-detail">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="dining-detail-carousel">';
            // var_dump($imageList); die();
            if ($serRec->image != "a:0:{}") {
                foreach ($imageList as $key => $imgServ) {
                    $restscont .= ' <div class="item">
                                                <img src="' . IMAGE_PATH . 'services/' . $imgServ . '" alt="' . $serRec->title . '" />
                                            </div>';
                }
            }
            $restscont .= ' </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="service-content">
                                                ' . substr(strip_tags($serRec->content), 0, 30000) . '
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
            $j++;
        }
        $restscont .= '</div>
                        </div>
                    </div>
                </div><!-- container -->
            </div><!-- block -->';
    }

    $jVars['module:service-detail-list'] = $restscont;

    $facility_bread = '';
    if (defined('FACILITY_PAGE')) {
        $siteRegulars = Config::find_by_id(1);
        $imglink = $siteRegulars->facility_upload;
        // pr($imglink);
        if (!empty($imglink)) {
            $img = IMAGE_PATH . 'preference/facility/' . $siteRegulars->facility_upload;
        } else {
            $img = '';
        }

        $facility_bread = '<div class="mad-breadcrumb with-bg-img with-overlay" data-bg-image-src="' . $img . '">
        <div class="container wide">
            <h1 class="mad-page-title">Hotel Amenities</h1>
            <nav class="mad-breadcrumb-path">
                <span><a href="index.html" class="mad-link">Home</a></span> /
                <span>Facilities</span>
            </nav>
        </div>
    </div>';
    }
    $jVars['module:facilitybread'] = $facility_bread;

    $facility = "";
    if (defined('FACILITY_PAGE')) {


        $record = Services::getservice_list();
        if (!empty($record)) {
            $count = $countsec = 0;
            foreach ($record as $recRow) {
                if (!empty($recRow->icon)) {
                    $facility .= ' 
                    <div class="mad-col">
                                    <!--================ Icon Box ================-->
                                    <article class="mad-icon-box">
                                    span class="' . $recRow->icon . '"></span>
                                        <div class="mad-icon-box-content">
                                            <h6 class="mad-icon-box-title">
                                            ' . $recRow->title . '
                                            </h6>
                                        </div>
                                    </article>
                                    <!--================ End of Icon Box ================-->
                                </div>
                    
                    ';
                } else {

                    $img = unserialize($recRow->image);
                    $file_path = SITE_ROOT . 'images/services/' . $img[0];
                    if (file_exists($file_path) && $img[0] != NULL) {
                        $imglink = IMAGE_PATH . 'services/' . $img[0];
                        $facility .= ' 
                        <div class="mad-col">
                                    <!--================ Icon Box ================-->
                                    <article class="mad-icon-box">
                                        <img src="' . $imglink . '" alt = ' . $recRow->title . '>
                                        <div class="mad-icon-box-content">
                                            <h6 class="mad-icon-box-title">
                                            ' . $recRow->title . '
                                            </h6>
                                        </div>
                                    </article>
                                    <!--================ End of Icon Box ================-->
                                </div>


                        ';
                    }
                }
            }
        }
    }

    $jVars['module:facility-list'] = $facility;



    /*
    * Service Page
    */
    $rescont = '';


    $rescont .= '';


    $subpkgRec = services::find_8();

    if (!empty($subpkgRec)) {
        $rescont .= '';
        foreach ($subpkgRec as $k => $v) {
            $img_nm = unserialize($v->image);
            $rescont .= '
                
                            
                            ';
        }
        $rescont .= '';
    }

    // pr($rescont_left);
    $rescont_final = '
                        <!-- detail features starts -->
                        <div class="mad-section mad-section.no-pb">
                        <div class="row justify-content-center">
                            <div class="col-xxl-10">
                                <div class="mad-title-wrap align-center">
                                    <div class="mad-pre-title">The Advantages</div>
                                    <h2 class="mad-section-title">Amenities and Facilities</h2>
                                </div>
                                <!--================ Icon Boxes ================-->
                                <div class="mad-icon-boxes align-center small-size item-col-5">
                                        ' . $rescont . '
                                        </div>
                                        <!--================ End of Icon Boxes ================-->
                                    </div>
                                </div>
                            </div>';
    $jVars['module:service-homepage'] = $rescont_final;



    $facilityhome = "";

    if (defined('COMPANY_PAGE') && isset($_GET['slug'])) {

        $slug   = trim($_GET['slug']);
        $recRow = Services::find_by_slugs($slug);

        if ($recRow) {

            // Set main image
            $imglink = '';
            if (!empty($recRow->image) && $recRow->image != "a:0:{}") {
                $img = unserialize($recRow->image);
                $file_path = SITE_ROOT . 'images/services/' . $img[0];
                if (file_exists($file_path) && $img[0] != NULL) {
                    $imglink = IMAGE_PATH . 'services/' . $img[0];
                }
            }

            // Set icon image
            $iconlink = '';
            if (!empty($recRow->iconimage) && $recRow->iconimage != "a:0:{}") {
                $iconList = unserialize($recRow->iconimage);
                $file_path_icon = SITE_ROOT . 'images/services/icon/' . $iconList[0];
                if (file_exists($file_path_icon)) {
                    $iconlink = IMAGE_PATH . 'services/icon/' . $iconList[0];
                }
            }
            $bannerlink = '';

            if (!empty($recRow->bannerimage) && $recRow->bannerimage != "a:0:{}") {

                $iconList = unserialize($recRow->bannerimage);

                if (!empty($iconList[0])) {
                    $file_path_icon = SITE_ROOT . 'images/services/banner/' . $iconList[0];

                    if (file_exists($file_path_icon)) {
                        $bannerlink = IMAGE_PATH . 'services/banner/' . $iconList[0];
                    }
                }
            }

            /* fallback default image */
            if (empty($bannerlink) && !empty($siteRegulars->other_upload)) {

                $default_path = SITE_ROOT . 'images/preference/other/' . $siteRegulars->other_upload;

                if (file_exists($default_path)) {
                    $bannerlink = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
                }
            }



            $exploreLink = $recRow->explorelinksrc;

            // If external link → leave it
            if (strpos($exploreLink, 'http') === 0) {
                $finalExploreLink = $exploreLink;
            }
            // If internal file/link → make absolute
            else {
                $finalExploreLink = BASE_URL . ltrim($exploreLink, '/');
            }



            $facilityhome .= ' 
            <section class="no-top no-bottom jarallax vertical-center" >
                <img src="' . $bannerlink . '" alt="Main Image" class="img-fluid vh-100 w-100 opacity-75" style="object-fit: cover;">

                    <div class="de-overlay v-center t5">
                        <div class="container">
                            <div class="mx-auto text-center">
                                <a href="#" class="d-block mb-4"></a>
                                        <img src=" ' . $iconlink . ' " class="img-fluid mb-3 wow fadeInUp" width="250" height="250" data-wow-duration="1s" alt="Main Logo">
                                    </a>
                            </div>
                            <div class="row mb30">
                                <div class="col-lg-6 offset-lg-3 text-center text__responsive">
                                    <h1>' . $recRow->sub_title . '</h1>
                                    <p class="lead">' . strip_tags($recRow->content) . '</p>
                                    <div class="mx-auto gap-3 d-flex justify-content-center">

                                            <a class="btn-line wow fadeInUp animated" href="' . $finalExploreLink . '" target="_blank"><span>Explore</span></a>
                                        <a class="btn-line book" href="' . BASE_URL . 'contact-us"><span>Contact Us</span></a>

                                    </div>
                                </div>
                            </div>
                        <div class="white-border-bottom"></div>
                        
                        <div class="row mt10">
                            <div class="col-md-6 text-md-start text-center">' . $recRow->fiscal_address . ' | <a href="tel:' . $recRow->contact_info . '">' . $recRow->contact_info . '</a></div>
                            <div class="col-md-6 text-md-end text-center">
                                <div class="social-icons">
                                    ' . (!empty($recRow->facebook_link) ? '<a href="' . htmlspecialchars($recRow->facebook_link) . '" target="_blank" rel="noreferrer noopener"><i class="fab fa-facebook-f"></i></a>' : '') . '
                                    ' . (!empty($recRow->x_link) ? '<a href="' . htmlspecialchars($recRow->x_link) . '" target="_blank" rel="noreferrer noopener"><i class="fab fa-x-twitter"></i></a>' : '') . '
                                    ' . (!empty($recRow->instagram_link) ? '<a href="' . htmlspecialchars($recRow->instagram_link) . '" target="_blank" rel="noreferrer noopener"><i class="fab fa-instagram"></i></a>' : '') . '
                                    ' . (!empty($recRow->linkedin_link) ? '<a href="' . htmlspecialchars($recRow->linkedin_link) . '" target="_blank" rel="noreferrer noopener"><i class="fab fa-linkedin-in"></i></a>' : '') . '
                                    ' . (!empty($recRow->tiktok_link) ? '<a href="' . htmlspecialchars($recRow->tiktok_link) . '" target="_blank" rel="noreferrer noopener"><i class="fab fa-tiktok"></i></a>' : '') . '
                                    ' . (!empty($recRow->youtube_link) ? '<a href="' . htmlspecialchars($recRow->youtube_link) . '" target="_blank" rel="noreferrer noopener"><i class="fab fa-youtube"></i></a>' : '') . '
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section> 


            






            
            <!--================ End of Icon Box ================-->
            ';
        }
    }


    $jVars['module:facility-list-home'] = $facilityhome;

    $destinationhome = "";
    if (defined('FACILITY_PAGE')) {


        $record = Services::getservice_list(30);
        if (!empty($record)) {
            foreach ($record as $recRow) {
                if (!empty($recRow->icon)) {
                    $facilityhome .= ' 
                        <a href="destination.html" class="destination-item wow fadeInUp" data-wow-delay=".25s">
                            <div class="destination-img">
                                <img src="' . $imglink . '" alt="">
                            </div>
                            <div class="destination-info">
                                <h4>' . $recRow->title . '</h4>
                                <span><i class="far fa-destination-dot"></i>Nepal</span>
                            </div>
                        </a>
                    </div>
                ';
                } else {

                    $img = unserialize($recRow->image);
                    $file_path = SITE_ROOT . 'images/services/' . $img[0];
                    if (file_exists($file_path) && $img[0] != NULL) {
                        $imglink = IMAGE_PATH . 'services/' . $img[0];
                        $facilityhome .= '        
                        <a href="destination.html" class="destination-item wow fadeInUp" data-wow-delay=".25s">
                            <div class="destination-img">
                                <img src="' . $imglink . '" alt="">
                            </div>
                            <div class="destination-info">
                                <h4>' . $recRow->title . '</h4>
                                <span><i class="far fa-destination-dot"></i>Nepal</span>
                            </div>
                        </a>
                    ';
                    }
                }
            }
        }
    }
    $jVars['module:destination-page'] = $destinationhome;





// End of Service Page
