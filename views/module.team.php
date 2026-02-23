<?php
$teamdatas = team::find_all_active();
$team_list = '';
$team = '';

if (!empty($teamdatas)) {
    foreach ($teamdatas as $teamdata) {
        // Fallback logo if no image is found
        $imgsrc = IMAGE_PATH . 'preference/' . $siteRegulars->logo_upload;
        $file_path = SITE_ROOT . 'images/team/' . $teamdata->image;
        if (file_exists($file_path) and !empty($teamdata->image)) {
            $imgsrc = IMAGE_PATH . 'team/' . $teamdata->image;
        }

        $team_list .= '
            <div class="text-center group w-full max-w-[280px] flex flex-col h-full">
                <div class="relative mb-6 overflow-hidden rounded-xl aspect-[3/4] shrink-0">
                    <img src="' . $imgsrc . '" alt="' . $teamdata->name . '"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-6">
                    </div>
                </div>
                <div class="flex-1 flex flex-col justify-start">
                    <h4 class="text-xl font-serif font-semibold text-text-main dark:text-white mb-1">' . $teamdata->name . '</h4>
                    <p class="text-primary text-sm font-bold uppercase tracking-wider">' . $teamdata->title . '</p>
                </div>
            </div>';




    }
    $team .='

    <section id="board-members" class="py-24 px-6 md:px-10 bg-gray-50 dark:bg-background-dark/30">
        <div class="max-w-[1280px] mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-primary font-bold uppercase tracking-widest text-xs mb-3">Our Leadership</h2>
                <h3 class="text-3xl md:text-4xl font-serif font-semibold text-text-main dark:text-white">Board of
                    Directors</h3>
                <p class="text-text-main dark:text-white mt-4 max-w-6xl mx-auto">Actively involved in diverse business
                    ventures and social organizations, playing a key role in driving economic growth, supporting
                    community initiatives, and contributing to the overall development of the nation through responsible
                    leadership and collaboration.</p>
                <div class="w-20 h-1 bg-primary mx-auto mt-6"></div>
            </div>

            <div class="flex flex-wrap justify-center gap-10 items-stretch">
                ' . $team_list . '
            </div>
        </div>
    </section>';
}

$jVars['module:team_list'] = $team_list;
$jVars['module:team'] = $team;

// Backward compatibility for existing template tags
$jVars['module:team:leader'] = $team_list;
$jVars['module:team:sales'] = '';
$jVars['module:team:guide'] = '';
$jVars['module:team:past'] = $team_list;
$jVars['module:team:life'] = '';
$jVars['module:team:bread'] = '';
?>
