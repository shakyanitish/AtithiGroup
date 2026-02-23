<?php
$result = '';

$menuRec = Menu::getMenuByParent(0, 1);

$current_url = $_SERVER["REQUEST_URI"];
$data = explode('/', $current_url);

if ($menuRec):
    $result .= '<ul class="mad-navigation mad-navigation--vertical-sm">';
    foreach ($menuRec as $menuRow):
        $linkActive = $PlinkActive = '';
        $tot = strlen(SITE_FOLDER) + 2;
        $data = substr($_SERVER['REQUEST_URI'], $tot);

        if (!empty($data)):
            $linkActive = ($menuRow->linksrc == $data) ? " current-menu-item" : "";
            $parentInfo = Menu::find_by_linksrc($data);
            if ($parentInfo):
                $PlinkActive = ($menuRow->id == $parentInfo->parentOf) ? " " : "";
            endif;
        endif;

        $menusubRec = Menu::getMenuByParent($menuRow->id, 1);
        $subclass = ($menusubRec) ? 'nav-item dropdown' : 'nav-item';
        $classLink = !empty($menusubRec) ? 'nav-link dropdown-toggle' : 'nav-link';
        $chkchild = !empty($menusubRec) ? ' data-bs-toggle="dropdown" role="button" aria-expanded="false" ' : ' role="button" aria-haspopup="true" aria-expanded="false" ';
        $drop1 = !empty($menusubRec) ? ' <i class=icon-down-open-mini></i>' : '';
        $result .=  '<li class="' . $subclass . ' ">';
        $result .= getMenuList($menuRow->name, $menuRow->linksrc, $menuRow->linktype, $linkActive . $PlinkActive . $classLink, $chkchild);
        /* Second Level Menu */
        if ($menusubRec):
            $result .= '<ul class="sub-menu">';
            foreach ($menusubRec as $menusubRow):
                $menusub2Rec = Menu::getMenuByParent($menusubRow->id, 1);
                $chkparent2 = (!empty($menusub2Rec)) ? 1 : 0;
                $drop2 = !empty($menusub2Rec) ? 'menu-item' : 'menu-item';
                $result .= '<li class="' . $drop2 . '">';
                $result .= getMenuList($menusubRow->name, $menusubRow->linksrc, $menusubRow->linktype, '', $chkparent2);
                /* Third Level Menu */
                if ($menusub2Rec):
                    $result .= '<ul class="sub-menu">';
                    foreach ($menusub2Rec as $menusub2Row):
                        $menusub3Rec = Menu::getMenuByParent($menusub2Row->id, 1);
                        $chkparent3 = (!empty($menusub3Rec)) ? 1 : 0;
                        $drop3 = !empty($menusub3Rec) ? 'class="dropdown"' : '';
                        $result .= '<li id="menu-item-' . $menusub2Row->id . '" ' . $drop3 . '>';
                        $result .= getMenuList($menusub2Row->name, $menusub2Row->linksrc, $menusub2Row->linktype, '', $chkparent3);
                        /* Fourth Level Menu */
                        if ($menusub3Rec):
                            $result .= '<ul class="dropdown-menu">';
                            foreach ($menusub3Rec as $menusub3Row):
                                $menusub4Rec = Menu::getMenuByParent($menusub3Row->id, 1);
                                $chkparent4 = (!empty($menusub4Rec)) ? 1 : 0;
                                $result .= '<li id="menu-item-' . $menusub2Row->id . '">';
                                $result .= getMenuList($menusub3Row->name, $menusub3Row->linksrc, $menusub3Row->linktype, '', $chkparent4);
                                /* Fifth Level Menu */
                                if ($menusub4Rec):
                                    $result .= '<ul>';
                                    foreach ($menusub4Rec as $menusub4Row):
                                        $menusub5Rec = Menu::getMenuByParent($menusub4Row->id, 1);
                                        $chkparent5 = (!empty($menusub4Rec)) ? 1 : 0;
                                        $result .= '<li>' . getMenuList($menusub4Row->name, $menusub4Row->linksrc, $menusub4Row->linktype, $chkparent5) . '</li>';
                                    endforeach;
                                    $result .= '</ul>';
                                endif;
                                $result .= '</li>';
                            endforeach;
                            $result .= '</ul>';
                        endif;
                        $result .= '</li>';
                    endforeach;
                    $result .= '</ul>';
                endif;
                $result .= '</li>';
            endforeach;
            $result .= '</ul>';
        endif;
        $result .= '</li>';
    endforeach;
    $result .= '</ul>';
endif;

$jVars['module:res-menu'] = $result;





$result = '';

$menuRec = Menu::getMenuByParent(0, 1);

$tot = strlen(SITE_FOLDER) + 2;
$currentPath = substr($_SERVER['REQUEST_URI'], $tot);

if ($menuRec):
    $result .= '<div class="ul-header-nav-wrapper">';
    $result .= '    <div class="to-go-to-sidebar-in-mobile">';
    $result .= '        <nav class="ul-header-nav">';
    
    foreach ($menuRec as $key => $menuRow):
        $linkActive = '';
        
        // Check if this is the Home menu and we're on the homepage
        $isHomePage = (empty($currentPath) || $currentPath == '/' || $currentPath == 'index.php');
        $isHomeMenu = ($menuRow->linksrc == 'home' || $menuRow->linksrc == '' || $menuRow->linksrc == '/');
        
        if ($isHomePage && $isHomeMenu):
            $linkActive = " active";
        // Check if current link is active for other pages
        elseif (!empty($currentPath)):
            $linkActive = ($menuRow->linksrc == $currentPath) ? " active" : "";
            
            // Check if current path starts with menu link (for detail pages)
            if (empty($linkActive) && !empty($menuRow->linksrc) && $menuRow->linksrc != 'home' && $menuRow->linksrc != '/'):
                if (strpos($currentPath, $menuRow->linksrc) === 0):
                    $linkActive = " active";
                endif;
            endif;
            
            // Check if parent is active (when a submenu item is selected)
            if (empty($linkActive)):
                $parentInfo = Menu::find_by_linksrc($currentPath);
                if ($parentInfo):
                    $linkActive = ($menuRow->id == $parentInfo->parentOf) ? " active" : "";
                endif;
            endif;
            
            // Also check if any child menu is active (to highlight parent)
            if (empty($linkActive)):
                $childMenus = Menu::getMenuByParent($menuRow->id, 1);
                if ($childMenus):
                    foreach ($childMenus as $child):
                        // Check exact match or if current path starts with child link
                        if ($child->linksrc == $currentPath || (!empty($child->linksrc) && strpos($currentPath, $child->linksrc) === 0)):
                            $linkActive = " active";
                            break;
                        endif;
                    endforeach;
                endif;
            endif;
        endif;
        
        // Check if menu has children
        $childMenus = Menu::getMenuByParent($menuRow->id, 1);
        
        if ($childMenus && count($childMenus) > 0):
            // Menu with submenu
            $result .= '            <div class="has-sub-menu' . $linkActive . '">';
            $result .= '                <a role="button" class="menu-toggle' . $linkActive . '" data-toggle="submenu" aria-expanded="false">' . $menuRow->name . '<i class="flaticon-down-arrow"></i></a>';
            $result .= '                <div class="ul-header-submenu">';
            $result .= '                    <ul>';
            
            foreach ($childMenus as $childMenu):
                $childActive = ($childMenu->linksrc == $currentPath) ? ' class="active"' : '';
                $childLink = ($childMenu->linksrc == 'home' || $childMenu->linksrc == '' || $childMenu->linksrc == '/') ? BASE_URL : BASE_URL . $childMenu->linksrc;
                $result .= '                        <li><a href="' . $childLink . '"' . $childActive . '>' . $childMenu->name . '</a></li>';;
            endforeach;
            
            $result .= '                    </ul>';
            $result .= '                </div>';
            $result .= '            </div>';
        else:
            // Simple menu link
            $menuLink = ($menuRow->linksrc == 'home' || $menuRow->linksrc == '' || $menuRow->linksrc == '/') ? BASE_URL : BASE_URL . $menuRow->linksrc;
            $result .= '            <a href="' . $menuLink . '"' . ($linkActive ? ' class="' . trim($linkActive) . '"' : '') . '>' . $menuRow->name . '</a>';
        endif;
    
    endforeach;
    
    $result .= '        </nav>';
    $result .= '    </div>';
    $result .= '</div>';
endif;

$jVars['module:res-menu1'] = $result;


// Footer Menu List
$resfooter = '';
$FmenuRec = Menu::getMenuByParent(0, 1);
if ($FmenuRec):
    // $resfooter .= '<h3>Quick Link</h3><ul>';

    foreach ($FmenuRec as $FmenuRow):
        $resfooter .= '<li>';
        $resfooter .= getMenuList($FmenuRow->name, $FmenuRow->linksrc, $FmenuRow->linktype, 'mad-text-link');
        $resfooter .= '</li>';
    endforeach;
    // $resfooter .= '</ul>';
endif;




$result = '';

$menuRec = Menu::getMenuByParent(0, 2);

if ($menuRec):
    $links = [];

    foreach ($menuRec as $menuRow):
        $linkActive = '';
        $tot = strlen(SITE_FOLDER) + 2;
        $data = substr($_SERVER['REQUEST_URI'], $tot);

        if (!empty($data)):
            $linkActive = ($menuRow->linksrc == $data) ? " active" : "";
        endif;

        // Build inline <a> tag
        $links[] = '<a href="' . $menuRow->linksrc . '" class="mad-text-link' . $linkActive . '">' 
                    . strtoupper($menuRow->name) . '</a>';
    endforeach;

    // Join with pipe
    $result = implode('  ', $links);
endif;

$jVars['module:footer-menu'] = $result;


//menu for uc
$result_uc = '';

$menuRec_uc = Menu::getMenuByParent(0, 1, 1);

$current_url = $_SERVER["REQUEST_URI"];
$data = explode('/', $current_url);

if ($menuRec_uc):
    $result_uc .= '<ul>';
    foreach ($menuRec_uc as $key => $menuRec_uc):
        $linkActive = $PlinkActive = '';
        $tot = strlen(SITE_FOLDER) + 2;
        $data = substr($_SERVER['REQUEST_URI'], $tot);

        if (!empty($data)):
            $linkActive = ($menuRec_uc->linksrc == $data) ? " " : "";
            $parentInfo = Menu::find_by_linksrc($data);
            if ($parentInfo):
                $PlinkActive = ($menuRec_uc->id == $parentInfo->parentOf) ? " " : "";
            endif;
        endif;

        $hrefId = '#mod-about';

        if ($menuRec_uc->name == 'Our Location'):
            $hrefId = '#mod-map';
        elseif ($menuRec_uc->name == 'Career'):
            $hrefId = '#mod-career';
        endif;

        $locationStyle = '';
        if ($menuRec_uc->name == 'Our Location'):
            $locationStyle = ' style="border-right: 1px solid #dfc175; color: white;"';
        endif;


        $menusubRec = Menu::getMenuByParent($menuRec_uc->id, 1);
        $subclass = ($menusubRec) ? 'menu-item menu-item-has-children' : ' ';
        $classLink = !empty($menusubRec) ? '' : '';
        $chkchild = !empty($menusubRec) ? ' ' : '';
        $drop1 = !empty($menusubRec) ? ' <i class=icon-down-open-mini></i>' : '';
        $result_uc .=  '<li class="' . $subclass . $linkActive . $PlinkActive . ' ' . $classLink . ' imgclass' . $key . '"' . $locationStyle . '">
        <style>
            .imgclass' . $key . ' a::before {
                width: 28px;
                height: 28px;
                line-height: 28px;
                background-image: url(' . IMAGE_PATH . 'menu/' . $menuRec_uc->image . ') !important;
                left: 24px;
                background-size: contain;
            }
        </style>
        ';
        $result_uc .= getMenuList($menuRec_uc->name, $menuRec_uc->linksrc, $menuRec_uc->linksrc, $menuRec_uc->linktype, $linkActive . $PlinkActive . $classLink, $chkchild);
        /* Second Level Menu */
        if ($menusubRec):
            $result_uc .= '<ul class="sub-menu">';
            foreach ($menusubRec as $menusubRow):
                $menusub2Rec = Menu::getMenuByParent($menusubRow->id, 1);
                $chkparent2 = (!empty($menusub2Rec)) ? 1 : 0;
                $drop2 = !empty($menusub2Rec) ? 'menu-item' : 'menu-item';
                $result_uc .= '<li class="' . $drop2 . '">';
                $result_uc .= getMenuList($menusubRow->name, $menusubRow->linksrc, $menusubRow->linktype, '', $chkparent2);
                /* Third Level Menu */
                if ($menusub2Rec):
                    $result_uc .= '<ul class="sub-menu">';
                    foreach ($menusub2Rec as $menusub2Row):
                        $menusub3Rec = Menu::getMenuByParent($menusub2Row->id, 1);
                        $chkparent3 = (!empty($menusub3Rec)) ? 1 : 0;
                        $drop3 = !empty($menusub3Rec) ? 'class="dropdown"' : '';
                        $result_uc .= '<li id="menu-item-' . $menusub2Row->id . '" ' . $drop3 . '>';
                        $result_uc .= getMenuList($menusub2Row->name, $menusub2Row->linksrc, $menusub2Row->linktype, '', $chkparent3);
                        /* Fourth Level Menu */
                        if ($menusub3Rec):
                            $result_uc .= '<ul class="dropdown-menu">';
                            foreach ($menusub3Rec as $menusub3Row):
                                $menusub4Rec = Menu::getMenuByParent($menusub3Row->id, 1);
                                $chkparent4 = (!empty($menusub4Rec)) ? 1 : 0;
                                $result_uc .= '<li id="menu-item-' . $menusub2Row->id . '">';
                                $result_uc .= getMenuList($menusub3Row->name, $menusub3Row->linksrc, $menusub3Row->linktype, '', $chkparent4);
                                /* Fifth Level Menu */
                                if ($menusub4Rec):
                                    $result_uc .= '<ul>';
                                    foreach ($menusub4Rec as $menusub4Row):
                                        $menusub5Rec = Menu::getMenuByParent($menusub4Row->id, 1);
                                        $chkparent5 = (!empty($menusub4Rec)) ? 1 : 0;
                                        $result_uc .= '<li>' . getMenuList($menusub4Row->name, $menusub4Row->linksrc, $menusub4Row->linktype, $chkparent5) . '</li>';
                                    endforeach;
                                    $result_uc .= '</ul>';
                                endif;
                                $result_uc .= '</li>';
                            endforeach;
                            $result_uc .= '</ul>';
                        endif;
                        $result_uc .= '</li>';
                    endforeach;
                    $result_uc .= '</ul>';
                endif;
                $result_uc .= '</li>';
            endforeach;
            $result_uc .= '</ul>';
        endif;


        $result_uc .= '</li>';
    endforeach;
    $result_uc .= '</ul>';
endif;

$jVars['module:res-menu-uc'] = $result_uc;

// Mobile menu toggle script
?>