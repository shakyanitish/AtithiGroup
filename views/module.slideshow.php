<?php
/* First Slideshow */
$reslide = '';
$Records = Slideshow::getSlideshow_by(0);

if ($Records) {
    foreach ($Records as $RecRow) {
        $file_path = SITE_ROOT . 'images/slideshow/' . $RecRow->image;
        if (file_exists($file_path) && !empty($RecRow->image)) {
            $img_path = IMAGE_PATH . 'slideshow/' . $RecRow->image;
            
            // We use inline styles for dynamic background images to avoid Tailwind JIT issues with variable paths
            $reslide .= '
            <div class="absolute inset-0 z-0 bg-cover bg-center bg-no-repeat bg-scroll lg:bg-fixed" 
                 style="background-image: linear-gradient(to top, rgba(0,0,0,0.2), rgba(0,0,0,0.6)), url(\'' . $img_path . '\');"
                 data-alt="' . htmlspecialchars($RecRow->title) . '">
            </div>';
            
            // For now, we only show the first active slide as a static hero background
            // unless a slider script is added to handle multiple absolute divs.
            break; 
        }
    }
}

$jVars["module:slideshow-uc"] = $reslide;
?>