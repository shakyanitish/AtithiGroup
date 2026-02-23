<?php 
/*
* Facilities list
*/
$rescont= $facbred= '';
  
$subpkgRec = Features::find_all_byparnt(47);
$pkgRec = Features::find_by_id(119);
// var_dump($subpkgRec); die();
    if(!empty($subpkgRec)) {
        $rescont.='
        <!--================ Breadcrumb ================-->
        <div class="mad-breadcrumb with-bg-img with-overlay" data-bg-image-src="template/web/images/facilities.jpg">
            <div class="container wide">
                <h1 class="mad-page-title">Hotel Amenities</h1>
                <nav class="mad-breadcrumb-path">
                    <span><a href="home" class="mad-link">Home</a></span> /
                    <span>Facilities</span>
                </nav>
            </div>
        </div>
        <!--================ End of Breadcrumb ================-->
        
        <div class="mad-section mad-section.no-pb">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-10">
                <div class="mad-icon-boxes align-center small-size item-col-5">';
                      
                        foreach($subpkgRec as $k=>$v){
                                 $rescont.='
                                 <div class="mad-col">
                                <!--================ Icon Box ================-->
                                <article class="mad-icon-box">
                                    <img src="'.IMAGE_PATH.'features/' . $v->image.'">
                                    <div class="mad-icon-box-content">
                                        <h6 class="mad-icon-box-title">
                                        '.$v->title.'
                                        </h6>
                                    </div>
                                </article>
                                <!--================ End of Icon Box ================-->
                                </div>
                                  
                                ';    
                }
                                       
                            
                    $rescont.='</div>
                    <!--================ End of Icon Boxes ================-->
                </div>
            </div>
        </div>
    </div>';      
    }
   
$jVars['module:generalfacilities-list'] = $rescont;

?>