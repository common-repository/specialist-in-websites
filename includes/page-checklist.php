<?php
$siw = new SIW_TO();
if($siw->is_siw_developer()){
	settings_errors();
	$active_tab = "avg-options";
	if(isset($_GET["tab"])){
		if($_GET["tab"] == "checklist-toggl"){
			$active_tab = "checklist-toggl";
		} else {
			$active_tab = "checklist-list";
		}
	}
//	$curl = curl_init();
//
//	curl_setopt_array($curl, array(
//		CURLOPT_URL => "https://www.toggl.com/api/v8/workspaces/2284190/projects?user_agent=marthijn@specialistinwebsites.nl",
//		CURLOPT_RETURNTRANSFER => true,
//		CURLOPT_TIMEOUT => 30,
//		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//		CURLOPT_CUSTOMREQUEST => "GET",
//		CURLOPT_HTTPHEADER => array(
//			"cache-control: no-cache",
//			"Authorization: Basic ZDM1NWIyNzAxYjdmOWE3MmQ5MjBlYzY3NTcwOWU1NmU6YXBpX3Rva2Vu"
//		),
//	));
//	$response = curl_exec($curl);
//	$err = curl_error($curl);
//	curl_close($curl);
//	$response = json_decode($response, true); //because of true, it's in an array
//    var_dump($response);
//	$toggl_project_id = isset($_COOKIE['toggl_project_id']) ? $_COOKIE['toggl_project_id'] : 0;
//	?>
<!--    <div class="siw_checklist_toggl_section">-->
<!--    <div class="siw_checklist_toggl-check">Toggl project: <select class='toggl-projects' id='toggl-projects'>-->
<!--			--><?php //foreach($response as $project){ ?>
<!--                <option name="--><?php //echo $project['id']?><!--"--><?php //if($toggl_project_id == $project['id']){echo"selected";} //echo $project['name']; ?><!--</option>-->
<!--			--><?php //} ?>
<!--        </select></div>-->
<!--	--><?php
//	foreach($response as $project){
//		if($project['id'] == $toggl_project_id) {
//			$totaltime = isset( $project['estimated_hours'] ) ? $project['estimated_hours'] : "??";
//			$timenow   = $project['actual_hours'];
//			if ( $totaltime == "??" ) {
//				$percentage = 100;
//			} else {
//				$percentage = ( $timenow / $totaltime ) * 100;
//			}
//			?>
<!--            <div class="siw_toggl_progress" id="siw_toggl_progress">-->
<!--                <div class="siw_toggl_progress_min">--><?php //echo $timenow; ?><!--h</div>-->
<!--                <div class="siw_toggl_progress_bar" style="width: --><?php //if ( $percentage >= 100 ) {
//					echo "100";
//				} else {
//					echo $percentage;
////				}; ?><!--/*-->
<!--/*                </div>*/-->
<!--/*                <div class="siw_toggl_progress_bar_marks_container">*/-->
<!--/*                    <div class="siw_toggl_progress_bar_marks siw_toggl_progress_bar_marks_part_1" title="Design in ontwikkeling" style="max-width: 15%"><span>0-15</span></div>*/-->
<!--/*                    <div class="siw_toggl_progress_bar_marks siw_toggl_progress_bar_marks_part_2" title="Project in ontwikkeling" style="max-width: 45%"><span>15-60</span></div>*/-->
<!--/*                    <div class="siw_toggl_progress_bar_marks siw_toggl_progress_bar_marks_part_3" title="Testfase" style="max-width: 10%"><span>60-70</span></div>*/-->
<!--/*                    <div class="siw_toggl_progress_bar_marks siw_toggl_progress_bar_marks_part_4" title="Optimaliseren na testfase" style="max-width: 10%"><span>70-80</span></div>*/-->
<!--/*                    <div class="siw_toggl_progress_bar_marks siw_toggl_progress_bar_marks_part_5" title="Verwerken feedback" style="max-width: 10%"><span>80-90</span></div>*/-->
<!--/*                    <div class="siw_toggl_progress_bar_marks siw_toggl_progress_bar_marks_part_6" title="Laatste check" style="max-width: 5%"><span>90-95</span></div>*/-->
<!--/*                    <div class="siw_toggl_progress_bar_marks siw_toggl_progress_bar_marks_part_7" title="Klaar voor oplevering" style="max-width: 5%"><span>95-100</span></div>*/-->
<!--/*                </div>*/-->
<!--/*                <div class="siw_toggl_progress_max">*/-->
    <?php //if ($totaltime == "??" ) {
//						echo $totaltime;
//					} else {
//						echo $totaltime . "h";
//					} ?>
<!--                </div>-->
<!--            </div>-->
<!--            </div>-->
<!--			--><?php
//		}
//	}
//	?>
<!--<h4>Progressie checklist</h4>-->
<!--    <div class="siw_checklist_progress" id="siw_checklist_progress">-->
<!--        <div class="siw_checklist_progress_min"></div>-->
<!--        <div class="siw_checklist_progress_bar" style="">-->
<!--            <span class="siw_checklist_progress_percentage"></span>-->
<!--        </div>-->
<!--        <div class="siw_checklist_progress_bar siw_checklist_progress_bar_auto" style=""></div>-->
<!--        <div class="siw_checklist_progress_max"></div>-->
<!--    </div>-->

    <form method="post" action="options.php" id="siwChecklist" class="siw_checklist">
		<?php
		settings_fields("section_checklist");
		do_settings_sections("theme-options-checklist");
		submit_button(); ?>
    </form>

<?php } else { ?>
    <h1>Je moet een SiW developer zijn om dit scherm te kunnen bekijken.</h1>
<?php }?>