<?php
/*
* Programs list - Dynamic carousel on homepage
*/

$programsContent = '';

// Get all active programs (packages with type = 0 for programs)
$programsRec = Package::getHomePackage();

if (!empty($programsRec)) {
    $programsHtml = '';

    $counter = 0;
    foreach ($programsRec as $program) {
        $counter++;
        $isEven = ($counter % 2 == 0);

        // Order classes for alternating layout
        $sidebarOrder = $isEven ? 'lg:order-1 order-2' : '';
        $mainOrder = $isEven ? 'lg:order-2 order-1' : '';

        // Get main image
        $imglink = '';
        if (!empty($program->banner_image) && $program->banner_image != "a:0:{}") {
            $imageList = unserialize($program->banner_image);
            if (!empty($imageList[0])) {
                $file_path = SITE_ROOT . 'images/package/banner/' . $imageList[0];
                if (file_exists($file_path)) {
                    $imglink = IMAGE_PATH . 'package/banner/' . $imageList[0];
                }
            }
        }

        // Fallback to default image if no banner image
        if (empty($imglink)) {
            $siteRegulars = Config::find_by_id(1);
            $imglink = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
        }

        // Build program link
        $programLink = BASE_URL . 'program/' . $program->slug;

        // Get title
        $title = !empty($program->title) ? $program->title : 'Program';

        // Get description from brief or sub_title
        $description = $program->sub_title ?? '';
        $content = $program->content ?? '';

        // Property specific links (Hardcoded for now as they are external sites)
        $ext_link = 'https://atithigroup.com';
        $booking_link = '#';
        $location_text = '';
        
        if ($program->slug == 'atithi-suite') {
            $ext_link = 'https://atithigroupnepal.com/atithisuite/';
            $booking_link = 'https://www.swiftbook.io/inst/#/home?propertyId=422NTaNhXon1R6gPJd8kXiLzG7UxNjE=&checkIn=2026-02-13&checkOut=2026-02-14&clientWidth=1513&JDRN=Y&RoomID=213068,213069,213072,213071&noofrooms=1&adult0=2&child0=0&gsId=422NTaNhXon1R6gPJd8kXiLzG7UxNjE=';
            $location_text = '

            <div class="mt-8 text-center">
                <p class="text-text-muted text-sm italic font-light">"A perfect blend of luxury and
                    convenience."</p>
            </div>
            
            
            
            
            
            ';
            $view_all_link = 'https://atithigroupnepal.com/atithisuite/contact-us';
        } else if ($program->slug == 'atithi-resort') {
            $ext_link = 'https://atithigroupnepal.com/atithiresort/';
            $booking_link = 'https://atithigroupnepal.com/atithiresort//booking?date=2026-02-12&nights=1&adults=2&children-age=&access-code=';
            $location_text = '

            <div class="mt-8 text-center text-text-muted text-sm flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">location_on</span>
                <span class="font-medium">Shanti Patan, Lakeside, Pokhara-6</span>
            </div> ';
            $view_all_link = 'https://atithigroupnepal.com/atithiresort/testimonial';
        }

        $subpkg_sliders = '';
        
        // 1. Get images directly tied to the package
        $pkgGalleryImages = SubPackageImage::getImagelist_by_package($program->id);
        if (!empty($pkgGalleryImages)) {
            foreach ($pkgGalleryImages as $galleryImage) {
                $file_path = SITE_ROOT . 'images/package/galleryimages/' . $galleryImage->image;
                if (file_exists($file_path) && !empty($galleryImage->image)) {
                    $img_path = IMAGE_PATH . 'package/galleryimages/' . $galleryImage->image;
                    $subpkg_sliders .= '
                        <div class="aspect-square rounded-lg overflow-hidden">
                            <div class="w-full h-full bg-cover bg-center"
                                style="background-image: url(' . $img_path . ')"></div>
                        </div>';
                }
            }
        }

        // 2. Also get images from related subpackages
        $subpackages = Subpackage::getPackage_limit($program->id);
        if (!empty($subpackages)) {
            foreach ($subpackages as $subpkg) {
                $subpkgGalleryImages = SubPackageImage::getImagelist_by($subpkg->id);
                if (!empty($subpkgGalleryImages)) {
                    foreach ($subpkgGalleryImages as $galleryImage) {
                        $file_path = SITE_ROOT . 'images/package/galleryimages/' . $galleryImage->image;
                        if (file_exists($file_path) && !empty($galleryImage->image)) {
                            $img_path = IMAGE_PATH . 'package/galleryimages/' . $galleryImage->image;
                            $subpkg_sliders .= '
                                <div class="aspect-square rounded-lg overflow-hidden">
                                    <div class="w-full h-full bg-cover bg-center"
                                        style="background-image: url(' . $img_path . ')"></div>
                                </div>';
                        }
                    }
                }
            }
        }

        // Get testimonials for this property
        $testimonialHtml = '';
        $testimonials = Testimonial::get_alltest_by($program->id, 3); // Assuming type stores package ID for relevancy, adjust if needed
        if (empty($testimonials)) {
            // Fallback to random if none specific
            $testimonials = Testimonial::get_alltestimonial(2);
        }

        if (!empty($testimonials)) {
            foreach ($testimonials as $t) {
                $testimonialHtml .= '
                <div class="flex flex-col justify-between h-full">
                    <div>
                        <div class="flex gap-1 text-primary mb-4">
                            <span class="material-symbols-outlined fill-1">star</span>
                            <span class="material-symbols-outlined fill-1">star</span>
                            <span class="material-symbols-outlined fill-1">star</span>
                            <span class="material-symbols-outlined fill-1">star</span>
                            <span class="material-symbols-outlined fill-1">star</span>
                        </div>
                        <h4 class="text-xl font-serif mb-2 italic">' . $t->via_type . '</h4>
                        <p class="text-white/70 text-sm font-light leading-relaxed mb-4">' . strip_tags($t->content) . '</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold tracking-widest uppercase">' . $t->name . '</span>
                    </div>
                </div>';
            }
        }

        $programsContent .= '
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Main Featured Card (2/3 width) -->
                <div class="lg:col-span-2 relative group rounded-2xl overflow-hidden h-[500px] lg:h-[700px] shadow-2xl ' . $mainOrder . '">
                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 group-hover:scale-105"
                        style="background-image: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 60%, transparent 100%), url(\'' . $imglink . '\');">
                    </div>

                    <!-- Content Overlay -->
                    <div class="relative z-10 h-full flex flex-col justify-end p-8 md:p-12">
                        <div class="backdrop-blur-md bg-white/10 p-6 rounded-xl border border-white/20 max-w-xl">
                            <span class="text-primary font-bold tracking-[0.2em] uppercase text-xs mb-3 block">' . $description . '</span>
                            <h3 class="text-white font-serif text-4xl md:text-5xl lg:text-6xl mb-4 leading-tight">' . $title . '</h3>
                            <p class="text-white/80 text-lg mb-8 font-light hidden md:block lg:block"> ' . strip_tags($content) . '</p>

                            <div class="flex flex-wrap gap-4">
                                <a href="' . $ext_link . '" target="_blank" rel="noopener noreferrer"
                                    class="px-8 py-3 bg-primary text-white font-bold uppercase tracking-widest text-xs rounded-full hover:bg-white hover:text-primary transition-all duration-300 shadow-lg">
                                    Discover More
                                </a>
                                <a href="' . $booking_link . '"
                                    target="_blank" rel="noopener noreferrer"
                                    class="px-8 py-3 bg-white/10 backdrop-blur-sm text-white border border-white/30 font-bold uppercase tracking-widest text-xs rounded-full hover:bg-white hover:text-gray-900 transition-all duration-300">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar: Details & Interactive (1/3 width) -->
                <div class="flex flex-col gap-8 ' . $sidebarOrder . '">
                    <!-- Photo Gallery Slider -->
                    <div
                        class="bg-surface-light dark:bg-surface-dark p-8 rounded-2xl flex-1 flex flex-col justify-between shadow-sm">
                        <div class="w-full overflow-hidden">
                            <div class="flex justify-between items-center mb-6">
                                <h4 class="font-serif text-2xl text-text-main dark:text-white">Impressions</h4>
                                <a href="' . $ext_link . 'gallery-list" target="_blank"
                                    class="text-primary text-xs font-bold uppercase tracking-widest border-b border-primary/30 hover:border-primary transition-all">View
                                    All</a>
                            </div>
                            <!-- Owl Carousel Gallery -->
                            <div class="owl-carousel owl-theme gallery-slider">
                                ' . $subpkg_sliders . '
                            </div>
                        </div>
                        ' . $location_text . '
                    </div>

                    <!-- Reviews Slider -->
                    <div
                        class="bg-background-dark text-white p-8 rounded-2xl shadow-xl relative overflow-hidden group h-72">
                        <div
                            class="absolute top-0 right-0 p-4 text-white/10 group-hover:scale-110 transition-transform duration-700">
                            <span class="material-symbols-outlined text-8xl">format_quote</span>
                        </div>
                        <div class="relative z-10 h-full">
                            <div class="owl-carousel owl-theme testimonial-slider h-full">
                                ' . $testimonialHtml . '
                            </div>
                            <div class="absolute top-8 right-8 z-20">
                                <a href="' . $view_all_link . '" target="_blank"
                                    class="text-primary text-xs font-bold uppercase tracking-widest border-b border-primary/30 hover:border-primary transition-all">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    }
}

$jVars['module:programlist'] = $programsContent;


$booking_code = Config::getField('hotel_code', true);

$roomlist = $roombread = $singlepage = '';
$modalpopup = '';
$room_package = '';
$single_more = '';

/*
* package listing page - LIST VIEW (no slug)
*/
if (defined('PACKAGE_PAGE') and !isset($_REQUEST['slug'])) {
    $pkgList = Package::find_all();
    if (!empty($pkgList)) {
        $counter = 0;
        $singlepage = '';
        $single_more = '';

        foreach ($pkgList as $pkgRow) {
            $siteRegulars = Config::find_by_id(1);
            if ($pkgRow->type == 0 && $pkgRow->status == 1) {
                $imglink = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
                $pkgRowImg = $pkgRow->banner_image;

                if ($pkgRowImg != "a:0:{}") {
                    $pkgRowList = unserialize($pkgRowImg);
                    $file_path = SITE_ROOT . 'images/package/banner/' . $pkgRowList[0];
                    if (file_exists($file_path) and !empty($pkgRowList[0])) {
                        $imglink = IMAGE_PATH . 'package/banner/' . $pkgRowList[0];
                    }
                }

                $single = '
                    <!-- single slide -->
                    <div class="col">
                        <div class="ul-service">
                            <div class="ul-service-img">
                                <img src="' . $imglink . '" alt="' . $pkgRow->title . '">
                            </div>
                            <div class="ul-service-txt">
                                <h3 class="ul-service-title"><a href="' . BASE_URL . 'program/' . $pkgRow->slug . '">' . $pkgRow->title . '</a></h3>
                                <p class="ul-service-descr">
                                ' . $pkgRow->sub_title . '</p>
                                <a href="' . BASE_URL . 'program/' . $pkgRow->slug . '" class="ul-service-btn"><i class="flaticon-up-right-arrow"></i> View Details</a>
                            </div>
                        </div>
                    </div>';

                if ($counter < 6) {
                    $singlepage .= $single;
                } else {
                    $single_more .= $single;
                }

                $counter++;
            }
        }

        $roombread .= '
        <section class=" ul-section-spacing overflow-hidden">
            <div class="ul-container">
                <div class="row row-cols-md-3 row-cols-2 row-cols-xxs-1 ul-bs-row">
                    ' . $singlepage . '

                </div>
                
                <span id="dots">...</span>
                <span id="more">
                    <div class="row row-cols-md-3 row-cols-2 row-cols-xxs-1 ul-bs-row mt-4">
                    ' . $single_more . '
                    </div>
                </span>

                <!-- pagination -->
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="btns-block btns-center">
                            <button onclick="myFunction()" id="myBtn1" class="ul-btn d-sm-inline-flex px-4 mt-4">Load More</button>
                        </div>
                    </div> 
                </div>
            </div>
        </section>

';
    }
    $jVars['module:package'] = $roombread;
}


/*
* Program Detail Page
*/
$program_detail = $program_detail_title = '';
if (defined("PACKAGE_DETAIL_PAGE") && isset($_REQUEST['slug'])) {
    $slug = !empty($_REQUEST['slug']) ? $_REQUEST['slug'] : '';
    $Package = Package::find_by_slug($slug);

    if (!empty($Package)) {
        // Breadcrumb title
        $program_detail_title = '
        <section class="ul-breadcrumb ul-section-spacing">
            <div class="ul-container">
                <h2 class="ul-breadcrumb-title">' . $Package->title . '</h2>
            </div>
        </section>
        ';
        $jVars['module:program-detail-title'] = $program_detail_title;

        // Get all subpackages related to this package
        $subpackages = Subpackage::getPackage_limit($Package->id);
        
        // Generate tab buttons and tab content dynamically
        $tab_buttons = '';
        $tab_panels = '';
        $tab_count = 0;
        
        if (!empty($subpackages)) {
            foreach ($subpackages as $subpkg) {
                $tab_count++;
                $tab_id = 'tab' . $tab_count;
                $active_class = ($tab_count == 1) ? ' active' : '';
                
                // Tab button
                $tab_buttons .= '
                            <button class="tab-btn' . $active_class . '" data-tab="' . $tab_id . '">' . $subpkg->title . '</button>';
                
                // Get gallery images for this subpackage
                $subpkg_sliders = '';
                $subpkgGalleryImages = SubPackageImage::getImagelist_by($subpkg->id);
                
                if (!empty($subpkgGalleryImages)) {
                    foreach ($subpkgGalleryImages as $galleryImage) {
                        $file_path = SITE_ROOT . 'images/package/galleryimages/' . $galleryImage->image;
                        if (file_exists($file_path) && !empty($galleryImage->image)) {
                            $img_path = IMAGE_PATH . 'package/galleryimages/' . $galleryImage->image;
                            $subpkg_sliders .= '
                                    <!-- single slide -->
                                    <div class="swiper-slide">
                                        <div class="ul-event-details-img">
                                            <img src="' . $img_path . '" alt="' . $subpkg->title . '">
                                        </div>
                                    </div>';
                        }
                    }
                }
                
                // Fallback to subpackage main image
                if (empty($subpkg_sliders) && !empty($subpkg->image)) {
                    $file_path = SITE_ROOT . 'images/subpackage/' . $subpkg->image;
                    if (file_exists($file_path)) {
                        $img_path = IMAGE_PATH . 'subpackage/' . $subpkg->image;
                        $subpkg_sliders = '
                                    <!-- single slide -->
                                    <div class="swiper-slide">
                                        <div class="ul-event-details-img">
                                            <img src="' . $img_path . '" alt="' . $subpkg->title . '">
                                        </div>
                                    </div>';
                    }
                }
                
                // Tab panel content
                $tab_panels .= '
                        <div id="' . $tab_id . '" class="tab-panel' . $active_class . '">
                            ' . (!empty($subpkg_sliders) ? '
                            <div class="ul-testimonial-2-slider swiper">
                                <div class="swiper-wrapper">
                                    ' . $subpkg_sliders . '
                                </div>
                            </div>' : '') . '
                    
                            <h2 class="ul-event-details-title">' . $subpkg->title . '</h2>
                            ' . $subpkg->content . '
                        </div>';
            }
        }

        $program_detail = '
        <div class="ul-container ul-section-spacing">
            <div class="row gy-4 flex-column-reverse flex-lg-row">
                <div class="col-lg-12">
                <p>
                    ' . $Package->content . '
                </p>
                </div>
                <!-- left sidebar -->
                <div class="col-lg-4">
                    <div class="ul-inner-sidebar">
                        <div class="tab-buttons">
                        ' . $tab_buttons . '
                        </div>
                    </div>
                </div>

                <!-- event details content -->
                <div class="col-lg-8">
                    <div class="ul-event-details ul-donation-details tab-content">
                    ' . $tab_panels . '
                    </div>
                </div>
            </div>
        </div>';

        $jVars['module:program-detail'] = $program_detail;
    }
}
