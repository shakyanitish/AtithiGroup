<?php
$bl =  '';
$singleblog = '';
$singleblog_more = '';
$homebloglist = '';
$homeblog = '';

if (defined('BLOG_PAGE')) {
    $record = Blog::get_allblog();
    $linkTarget = '';
    $pagelink = '';
    if (!empty($record)) {

        $counter = 0; // NEW: counter to track which blog we're on

        foreach ($record as $homebl) {

            if (!empty($homebl->linksrc)) {
                $linkTarget = ($homebl->linktype == 1) ? ' target="_blank" ' : '';
                $linksrc = ($homebl->linktype == 1) ? $homebl->linksrc : BASE_URL . $homebl->linksrc;
            } else {
                $linksrc = BASE_URL . 'blog/' . $homebl->slug;
            }

            $blog_html = '
                    <!-- single blog -->
                    <div class="col">
                        <div class="ul-blog ul-blog-2">
                            <div class="ul-blog-img"><img src="' . IMAGE_PATH . 'blog/' . $homebl->image . '" alt="' . $homebl->title . '">
                                <div class="date">
                                    <span class="number">' . date('d', strtotime($homebl->blog_date)) . '</span>
                                    <span class="txt">' . date('M Y', strtotime($homebl->blog_date)) . '</span>
                                </div>
                            </div>
                            <div class="ul-blog-txt">
                                <div class="ul-blog-infos">
                                    <!-- single info -->
                                    <div class="ul-blog-info">
                                        <span class="icon"><i class="flaticon-account"></i></span>
                                        <span class="text font-normal text-[14px] text-etGray">' . $homebl->author . '</span>
                                    </div>
                                </div>
                                <a href="' . BASE_URL . 'blog/' . $homebl->slug . '" class="ul-blog-title">' . $homebl->title . '</a>
                                <a href="' . BASE_URL . 'blog/' . $homebl->slug . '" class="ul-blog-btn">Read More <span class="icon"><i class="flaticon-next"></i></span></a>
                            </div>
                        </div>
                    </div>';

            // NEW: Add to first 3 blogs OR to "load more" section
            if ($counter < 3) {
                $singleblog .= $blog_html;
            } else {
                $singleblog_more .= $blog_html;
            }

            $counter++; // Increment counter
        }

        // Build the final HTML
        $bl = '
        <section class="ul-blogs-2 ul-section-spacing">
            <div class="ul-container wow animate__fadeInUp">
                <div class="row row-cols-md-3 row-cols-2 row-cols-xxs-1 ul-bs-row justify-content-center">
                    ' . $singleblog . '
                </div>

                <span id="dots">...</span>
                <span id="more">
                    <div class="row row-cols-md-3 row-cols-2 row-cols-xxs-1 ul-bs-row justify-content-center mt-4">
                        ' . $singleblog_more . '
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
        </section>';
    } else {
        redirect_to(BASE_URL);
    }
}
$jVars['module:bloglist'] = $bl;




// Home Page Blog List
$linkTarget = '';
$homebloglist = '';
$homeblogs = '';
if (defined('HOME_PAGE')) {
    $homeblog= Blog::get_latestblog_by(3);
    // $homeblogs = Blog:: get_latestblog_by(3);
    if (!empty($homeblog)) {

        foreach ($homeblog as $homebl) {

            if (!empty($homebl->linksrc)) {
                // $pagelink = ($homebl->linktype == 1) ? ' target="_blank" ' : '';
                $linkTarget = ($homebl->linktype == 1) ? ' target="_blank" ' : '';
                $linksrc = ($homebl->linktype == 1) ? $homebl->linksrc : BASE_URL . $homebl->linksrc;
            } else {
                $linksrc =  BASE_URL . 'blog/' . $homebl->slug;
            }
            $homebloglist .= '

                <a href="' . BASE_URL . 'blog/' . $homebl->slug . '" class="group cursor-pointer flex flex-col h-full px-2">
                    <div class="aspect-[16/10] rounded-2xl overflow-hidden mb-6 relative shrink-0">
                        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110"
                            style="background-image: url(' . IMAGE_PATH . 'blog/' . $homebl->image . ')"></div>
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-4 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest text-primary">Destination</div>
                    </div>
                    <div class="space-y-3 flex flex-col flex-1">
                        <div class="flex items-center gap-4 text-[10px] text-text-muted font-bold uppercase tracking-widest">
                            <span>' . date('M d, Y', strtotime($homebl->blog_date)) . '</span>
                            <span class="w-1 h-1 bg-primary rounded-full"></span>
                            <span>' . $homebl->read_time . '</span>
                        </div>
                        <h3 class="text-2xl font-serif font-semibold text-text-main dark:text-white group-hover:text-primary transition-colors leading-tight">' . $homebl->title . '</h3>
                        <p class="text-text-muted text-sm leading-relaxed line-clamp-2">' . $homebl->short_brief . '</p>
                    </div>
                </a>

           
                  
           ';
        }
        $homeblogs = '

    <section id="blog" class="py-24 px-6 md:px-10 bg-white dark:bg-background-dark">
        <div class="max-w-[1280px] mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-primary font-bold uppercase tracking-widest text-xs mb-3">Insights &
                    Stories</h2>
                <h3 class="text-3xl md:text-4xl font-serif font-semibold text-text-main dark:text-white">Latest
                    from our Blog</h3>
                <p class="text-text-main dark:text-white mt-4 max-w-6xl mx-auto">Discovery journeys, travel tips, and
                    stories from the heart of Nepal&rsquo;s hospitality.</p>
                <div class="w-20 h-1 bg-primary mx-auto mt-6"></div>
            </div>

            <div class="owl-carousel owl-theme blog-slider" id="dynamic-blog-container">
                ' . $homebloglist . '
            </div>
        </div>
    </section>
        ';
    }
}

$jVars['module:homebloglist'] = $homeblogs;




// Blog Detail Page

$blog_detail = $recent_posts = $blog_detail_title = '';
if (defined("BLOG_DETAIL_PAGE")) {
    $slug = !empty($_REQUEST['slug']) ? $_REQUEST['slug'] : '';
    $Blogs = Blog::find_by_slug($slug);
    //pr($Blogs);


    if (!empty($slug) && !empty($Blogs)) {
        $blog_detail_title .= '
        <section class="ul-breadcrumb ul-section-spacing">
            <div class="ul-container">
                <h2 class="ul-breadcrumb-title">' . $Blogs->title . '</h2>
            </div>
        </section>
        ';
        $jVars['module:blog-detail-title'] = $blog_detail_title;

        // Get blog images
        $blogImages = BlogImage::getImagelist_by($Blogs->id);
        $galleryHtml = '';
        if (!empty($blogImages)) {
            foreach ($blogImages as $blogImg) {
                $file_path = SITE_ROOT . 'images/blog/blogimages/' . $blogImg->image;
                if (file_exists($file_path) && !empty($blogImg->image)) {
                    $galleryHtml .= '
                            <div class="col-md-3 images" data-class="' . $blogImg->blogid . '" data-src="' . IMAGE_PATH . 'blog/blogimages/' . $blogImg->image . '">
                                <img src="' . IMAGE_PATH . 'blog/blogimages/' . $blogImg->image . '" class="img-fluid">
                            </div>';
                }
            }
        }

        // Only show gallery section if there are images
        $gallerySection = '';
        if (!empty($galleryHtml)) {
            $gallerySection = '
                <section class="main text-center">
                    <div class="container-fluid">
                        <h3 class="text-center" style="color:#d93431;">Memories / Gallery</h3>
                        <div class="row" id="gallery">
                            ' . $galleryHtml . '
                        </div>
                    </div>
                </section>';
        }

        $blog_detail .= '



        <section class="ul-service-details ul-section-spacing">
            <div class="ul-container">
                <div>
                    <div class="ul-service-details-txt">
                        <h3 class="ul-service-details-inner-title">' . date('M jS Y', strtotime($Blogs->blog_date)) . '</h3>
                         ' . $Blogs->content . '                    
                    </div>
                </div>

                ' . $gallerySection . '
            </div>
        </section>

   ';
        $jVars['module:blog-detail'] = $blog_detail;


        // Recent Posts Sidebar
        $recent_posts = '';
        $recent_items = '';
        $recents = Blog::get_latestblog_by(3);

        if (!empty($recents)) {
            foreach ($recents as $recent) {
                // Skip the post currently being viewed
                if ($slug != $recent->slug) {
                    $recent_items .= '
                        <a href="' . BASE_URL . 'blog/' . $recent->slug . '" class="group flex gap-4">
                            <div class="size-20 rounded-xl overflow-hidden shrink-0">
                                <img src="' . IMAGE_PATH . 'blog/' . $recent->image . '" alt="' . $recent->title . '" class="h-full w-full object-cover group-hover:scale-110 transition-transform">
                            </div>
                            <div>
                                <h4 class="font-serif font-semibold text-lg leading-tight group-hover:text-primary transition-colors">' . $recent->title . '</h4>
                                <span class="text-[10px] text-text-muted uppercase font-bold tracking-widest mt-2 block">' . date("d M Y", strtotime($recent->blog_date)) . '</span>
                            </div>
                        </a>';
                }
            }

            if (!empty($recent_items)) {
                $recent_posts = '
                    <div class="bg-white dark:bg-background-dark border border-black/5 dark:border-white/5 p-8 rounded-2xl">
                        <h3 class="text-xl font-serif font-bold mb-8 items-center flex gap-2">
                            <span class="w-8 h-px bg-primary"></span>
                            Recent Stories
                        </h3>
                        <div class="space-y-8">
                            ' . $recent_items . '
                        </div>
                    </div>';
            }
        }

        $jVars['module:blog-detail'] = $blog_detail;
        
        // Granular Variables for Template
        $jVars['module:blog-title'] = $Blogs->title;
        $jVars['module:blog-date'] = date('M d, Y', strtotime($Blogs->blog_date));
        $jVars['module:blog-author'] = $Blogs->author;
        $jVars['module:blog-content'] = $Blogs->content;
        $jVars['module:blog-image'] = IMAGE_PATH . 'blog/' . $Blogs->image;
        $jVars['module:blog-read-time'] = $Blogs->read_time;
        $jVars['module:blog-subtitle'] = $Blogs->sub_title;
        $jVars['module:blog-recent-posts'] = $recent_posts;
        $jVars['module:blog-brief'] = $Blogs->brief;
    } else {
        $blog_detail .= '
        <!--================ Breadcrumb ================-->
        <div class="mad-breadcrumb with-bg-img with-overlay" data-bg-image-src="' . BASE_URL . 'template/web/images/default.jpg">
            <div class="container wide">
                <h1 class="mad-page-title">About Us</h1>
                <nav class="mad-breadcrumb-path">
                    <span><a href="' . BASE_URL . 'home" class="mad-link">Home</a></span> /
                    <span>Blogs</span>
                </nav>
            </div>
        </div>
        
        <div class="mad-title-wrap align-center">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="mad-pre-title">Make memories happen</div>
                            <h2 class="mad-page-title">Club Himalaya Experience</h2>
                        </div>
                    </div>
                </div>
                
                
                <div class="mad-section no-pt mad-section-pb-mobile mad-section--stretched-content-no-px mad__colorizer--scheme-color-2">
                <div class="mad-entities mad-owl-center mad-pricing type-3 with-img-border mad-grid owl__carousel mad-owl__moving mad-grid--cols-2 nav-size-2 no-dots d-flex flex-wrap">
                  
                ';
        $Blogs = Blog::get_allblog();
        //pr($Blogs);
        foreach ($Blogs as $homebl) {

            if (!empty($homebl->linksrc)) {
                // $pagelink = ($homebl->linktype == 1) ? ' target="_blank" ' : '';
                $linkTarget = ($homebl->linktype == 1) ? ' target="_blank" ' : '';
                $linksrc = ($homebl->linktype == 1) ? $homebl->linksrc : BASE_URL . $homebl->linksrc;
            } else {
                $linksrc = BASE_URL . 'blog/' . $homebl->slug;
            }
            $blog_detail .= '
                            <!--================ Entity ================-->
                                <div class="mad-entity-media mad-owl-center-img">
                                    <a href="' . $linksrc . '" ' . $linkTarget . '>
                                        <img src="' . IMAGE_PATH . 'blog/' . $homebl->image . '" alt="' . $homebl->title . '" />
                                    </a>
                                </div>
                                <div class="mad-entity__content mad-owl-center-element">
                                    <div class="mad-entity-inner">
                                        <h4 class="mad__entity-title">' . $homebl->title . '</h4>
                                        <h4 class="mad__entity-title">' . date("d M Y", strtotime($homebl->blog_date)) . '</h4>
                                        <p>
                                            A Rare Blend Of Nature And Modern Amenities and has become synonymous with Nagarkot.
                                        </p>
                                        <div class="mad-entity-footer">
                                            <a href="' . $linksrc . '" ' . $linkTarget . ' class="btn btn-big">View More</a>
                                        </div>
                                    </div>
                                </div>

           ';
        }
        $blog_detail .= '
    </div>
    
                </div>
            ';
    }
}


$jVars['module:blog-detail'] = $blog_detail;
$jVars['module:blog-recent-posts'] = $recent_posts;
