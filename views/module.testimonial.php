<?php

/*
* Testimonial List Home page
*/
$restst = '';
$tstRec = Testimonial::get_alltestimonial(5);
if (!empty($tstRec)) {
    $restst .= '';
    foreach ($tstRec as $tstRow) {
        $slink = !empty($tstRow->linksrc) ? $tstRow->linksrc : 'javascript:void(0);';
        $target = !empty($tstRow->linksrc) ? 'target="_blank"' : '';
        $rating = '';
        for ($i = 0; $i < $tstRow->type; $i++) {
            $rating .= '<a href="#"><i class="fa-solid fa-star"></i></a>';
        }
        $restst .= '';
        $restst .= '
                                <div class="swiper-slide">
                                    <div class="ul-review ul-review-2">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="reviewer-image"><img src="' . IMAGE_PATH . 'testimonial/' . $tstRow->image . '"
                                                        alt="reviewer image"></div>
                                            </div>

                                            <div class="col-md-9">
                                                <div class="ul-review-bottom">
                                                    <div class="ul-review-reviewer">
                                                        <div>
                                                            <h3 class="reviewer-name">' . $tstRow->name . '</h3>
                                                            <span class="reviewer-role">' . $tstRow->via_type . '</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="impact-text">
                                                    <p class="ul-review-descr">
                                                        ' . strip_tags($tstRow->content) . '</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                    ';
    }
}

$result_last = '
        <section class="ul-testimonial-2 ul-section-spacing">
            <div class="ul-container wow animate__fadeInUp">
                <div class="ul-section-heading">
                    <div>
                        <span class="ul-section-sub-title"> Generous contribution to support communities</span>
                        <h2 class="ul-section-title">Impact stories</h2>
                    </div>
                </div>

                <div class="row ul-testimonial-2-row gy-4">
                    <div class="col-md-9">
                        <div class="ul-testimonial-2-slider swiper">
                            <div class="swiper-wrapper">
                            ' . $restst . '
                            </div>

                            <div class="ul-testimonial-2-slider-nav">
                                <button class="prev"><i class="flaticon-back"></i></button>
                                <button class="next"><i class="flaticon-next"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="ul-testimonial-2-overview">
                            <img src="template/web/assets/img/images.jpg">
                        </div>
                    </div>
                </div>
            </div>
        </section> 
';


$jVars['module:testimonialList123'] = $result_last;



/*
* Testimonial Header Title
*/
$tstHtitle = '';

if (defined('HOME_PAGE')) {
    $tstHtitle .= '<section class="promo_full">
    <div class="promo_full_wp">
        <div>
            <h3>What Guest say</h3>
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="carousel_testimonials">';
    $tstRec = Testimonial::get_alltestimonial();
    if (!empty($tstRec)) {
        foreach ($tstRec as $tstRow) {
            $tstHtitle .= '<div>
                                <div class="box_overlay">
                                    <div class="pic">
                                        <figure><img src="' . IMAGE_PATH . 'testimonial/' . $tstRow->image . '" alt="' . $tstRow->name . '" class="img-circle"></figure>
                                        <h4>' . $tstRow->name . '</h4>
                                    </div>
                                    <div class="comment">
                                       ' . strip_tags($tstRow->content) . '
                                    </div>
                                </div><!-- End box_overlay -->
                            </div>';
        }
        $tstHtitle .= '</div><!-- End carousel_testimonials -->
                    </div><!-- End col-md-8 -->
                </div><!-- End row -->
            </div><!-- End container -->
        </div><!-- End promo_full_wp -->
    </div><!-- End promo_full -->
    </section><!-- End section -->';
    }
}
$jVars['module:testimonial-title'] = $tstHtitle;


/*
* Testimonial Rand
*/
$tstHead = '';

$tstRand = Testimonial::get_by_rand();
if (!empty($tstRand)) {
    $tstHead .= '<!-- Quote | START -->
	<div class="section quote fade">
		<div class="center">
	    
	        <div class="col-1">
	        	<div class="thumb"><img src="' . IMAGE_PATH . 'testimonial/' . $tstRand->image . '" alt="' . $tstRand->name . '"></div>
	            <h5><em>' . strip_tags($tstRand->content) . '</em></h5>
	            <p><span><strong>' . $tstRand->name . ', ' . $tstRand->country . '</strong> (Via : ' . $tstRand->via_type . ')</span></p>
	        </div>
	        
	    </div>
	</div>
	<!-- Quote | END -->';
}

$jVars['module:testimonial-rand'] = $tstHead;


/*
* Testimonial List
*/
$restst = '';
$tstRec = Testimonial::get_alltestimonial(9);

if (!empty($tstRec)) {
    $restst .= '<div class="testimonial-slider owl-carousel owl-theme">';

    foreach ($tstRec as $tstRow) {
        // Rating stars
        $rating = '';
        for ($i = 0; $i < $tstRow->rating; $i++) {
            $rating .= '<i class="fas fa-star"></i>';
        }
        $slink = !empty($tstRow->linksrc) ? $tstRow->linksrc : 'javascript:void(0);';
        $target = !empty($tstRow->linksrc) ? 'target="_blank"' : '';

        $restst .= '
                    <div class="testimonial-single">
                        <div class="testimonial-quote">
                            <span class="testimonial-quote-icon"><i class="fal fa-quote-right"></i></span>
                            <p>' . strip_tags($tstRow->content) . '</p>
                            <div class="testimonial-rate">
                                ' . $rating . '
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="testimonial-author-img">
                                <img src="' . IMAGE_PATH . 'testimonial/' . $tstRow->image . '" alt="">
                            </div>
                            <div class="testimonial-author-info">
                                <h4> ' . $tstRow->name . '</h4>
<p><a href="' . $slink . '" ' . $target . '>' . $tstRow->via_type . '</a></p>                            
</div>
                        </div>
                    </div>
                    ';
    }

    $restst .= '</div>';
}
$jVars['module:testimonialList'] = $restst;
