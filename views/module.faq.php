<?php

$faq_details = '';

if (defined('FAQ_PAGE')) {

$faqs = Faq::find_all();




    if (!empty($faqs)) {
        


        foreach ($faqs as $i => $faq) {

            // dynamic IDs
            $headingId = "heading".$i;
            $collapseId = "collapse".$i;

            // status control (same logic as your reference)
            $collapsed  = ($i == 0) ? 'show' : '';          // open body
            $expanded   = ($i == 0) ? 'true' : 'false';      // aria-expanded
            $btnClass   = ($i == 0) ? '' : 'collapsed';      // button collapse class

            $faq_details .= '
                <div class="accordion-item">
                    <h2 class="accordion-header" id="'.$headingId.'">
                        <button class="accordion-button '.$btnClass.'" type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#'.$collapseId.'"
                            aria-expanded="'.$expanded.'"
                            aria-controls="'.$collapseId.'">
                            <span><i class="far fa-square-check"></i></span> '.$faq->title.'
                        </button>
                    </h2>

                    <div id="'.$collapseId.'" class="accordion-collapse collapse '.$collapsed.'"
                        aria-labelledby="'.$headingId.'"
                        data-bs-parent="#accordionExample">

                        <div class="accordion-body">
                            '.$faq->content.'
                        </div>

                    </div>
                </div>
            ';
        }
    }
}



$jVars['module:faq:details'] = $faq_details;


$faq_details = '';

if (defined('HOME_PAGE')) {

    $faqs = Faq::find_few(3);

    if (!empty($faqs)) {
        $faq_details .= '';
        
        foreach ($faqs as $i => $faq) {
            $collapsed = ($i == 0) ? 'mad-panels-active' : '';
            $show = ($i == 0) ? 'show' : '';
            $faq_details .= '
            <dt class="mad-panels-title '. $collapsed .'">
                <button id="' . $faq->id . '-button" type="button" aria-expanded="false" aria-controls="' . $faq->id . '" aria-disabled="false">
                '. $faq->title .'
                </button>
            </dt>
            <dd id="' . $faq->id . '" class="mad-panels-definition">
                <p> '. $faq->content .'</p>
            </dd>

                
                ';
        }

        $faq_details .= '';
    } else {
        $faq_details .= '<h3 class="text-center p-4">No FAQ Found</h3>';
    }
}

$jVars['module:faq:details-homepage'] = $faq_details;

$jVars['module:faqlink'] = BASE_URL . 'faq';