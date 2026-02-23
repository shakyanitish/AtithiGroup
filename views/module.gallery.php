<?php
$reslgall = '';
$res_gallery = '';
$gallRec = Gallery::getParentgallery(2);
if (!empty($gallRec)) {
    foreach ($gallRec as $gallRow) {
        $childRec = GalleryImage::getGalleryImages($gallRow->id);
        if (!empty($childRec)) {
            $reslgall .= '';
            foreach ($childRec as $childRow) {
                $file_path = SITE_ROOT . 'images/gallery/galleryimages/' . $childRow->image;
                if (file_exists($file_path) and !empty($childRow->image)) {
                    $reslgall .= '
                <div class="gallery-image">
                    <img src="' . IMAGE_PATH . 'gallery/galleryimages/' . $childRow->image . '" alt="' . $childRow->title . '">
                </div>
                    ';
                }
            }
            $reslgall .= '';
        }
    }
}

$res_gallery = '
                <!-- Gallery starts -->
                <section class="content gallery gallery1">
                    <div class="container">
                        <div class="section-title title-white">
                            <h2>Beautiful View of <span>Shangrila Blu</span></h2>
                            <p class="mar-bottom-30">Few collection of our pictures. We are quiet sure that you will find it more beautiful once you physically visit us.</p>
                        </div>
                    </div>
                    <div class="gallery-main gallery-slider">
                        ' . $reslgall . '
                    </div>
                </section>
                <!-- Gallery Ends -->';

$jVars['module:galleryHome'] = $res_gallery;



$dininggallery = '';
$galldining = GalleryImage::getImagelist_by(19, 3);
if (!empty($galldining)) {
    $dininggallery .= '<div class="row about">
                     <div class="demo-gallery">
    		     <div id="lightgallery" class="list-unstyled">';
    foreach ($galldining as $row) {
        $dininggallery .= '<div class="item col-sm-4 col-xs-12" data-responsive="' . IMAGE_PATH . 'gallery/galleryimages/' . $row->image . '" data-src="' . IMAGE_PATH . 'gallery/galleryimages/' . $row->image . '" data-sub-html="<h4>' . $row->title . '</h4>">
                        <a href="">
                            <img src="' . IMAGE_PATH . 'gallery/galleryimages/' . $row->image . '"/>
                        </a>
                    </div>';
    }
    $dininggallery .= '</div>
    </div>
    </div>';
}
$jVars['module:dining-gallery'] = $dininggallery;

$gallerybread = '';
$videobread = '';
$siteRegulars = Config::find_by_id(1);
$imglink = $siteRegulars->gallery_upload;
if (!empty($imglink)) {
    $img = IMAGE_PATH . 'preference/gallery/' . $siteRegulars->gallery_upload;
} else {
    $img = IMAGE_PATH . 'preference/other/' . $siteRegulars->other_upload;
}

$gallerybread = '

        <div class="site-breadcrumb" style="background: url(' . $img . ')">
            <div class="container">
                <h2 class="breadcrumb-title">Photo Gallery</h2>
            </div>
        </div>';

$jVars['module:gallery-bread'] = $gallerybread;

$videobread = '

        <div class="site-breadcrumb" style="background: url(' . $img . ')">
            <div class="container">
                <h2 class="breadcrumb-title">Video Gallery</h2>
            </div>
        </div>';

$jVars['module:video-bread'] = $videobread;


/**
 *      Main Gallery
 */
$thegal = $gallerylistbread = $thegalnav = '';
$gallRectit = Gallery::getParentgallery();

if ($gallRectit) {
    $thegal .= '';
    foreach ($gallRectit as $row) {
        $thegalnav .= '
        <li class="col-md" data-class="' . $row->id . '">' . $row->title . '</li>';
    }
    $thegal .= '';

    // $thegal .= '
    //     <div id="gallery" class="gallery full-gallery de-gallery gallery-3-cols">
    // ';
    foreach ($gallRectit as $row) {

        $gallRec = GalleryImage::getGalleryImages($row->id);
        foreach ($gallRec as $row1) {
            // pr($row1);

            $file_path = SITE_ROOT . 'images/gallery/galleryimages/' . $row1->image;
            if (file_exists($file_path) and !empty($row1->image)):
                $thegal .= ' 
                    <div class="col-md-3 images" data-class="' . $row1->galleryid . '" data-src="' . IMAGE_PATH . 'gallery/galleryimages/' . $row1->image . '">
                        <img src="' . IMAGE_PATH . 'gallery/galleryimages/' . $row1->image . '" class="img-fluid">
                    </div>         
                   
                ';
            endif;
        }
    }
    $thegal .= '';
}

$jVars['module:gallery-list'] = $thegal;
$jVars['module:gallery-nav'] = $thegalnav;




// <video src=""></video>



$videomain = '';
if (defined('VIDEO_PAGE')) {
    $videodatas = Video::getAllVideos();
    if (!empty($videodatas)) {
        $videomain .= '  <section class="section-padding bg-darkbrown">
        <div class="container">
            <div class="row">';

        foreach ($videodatas as $videodata) {

            $videomain .= ' 
                        <div class="col-md-6">
                            <div class="video-info">
                                <iframe width="560" height="315" src="' . $videodata->url . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                            </div>
                        </div>
';
        }
    }

    // pr($videodatas);


}
$jVars['module:video-list'] = $videomain;
