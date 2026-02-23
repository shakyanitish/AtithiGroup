<?php
// Load the header files first
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("cache-control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

// Load necessary files then...
require_once('../initialize.php');

$action = $_REQUEST['action'];

switch ($action) {
	case "slug":
		$slug=$msg='';
		if(!empty($_REQUEST['title'])) {
			$nslug = create_slug($_REQUEST['title']);	
			$chk = check_slug($_REQUEST['actid'], $nslug);
			if($chk=='1') {					
				$msg="Slug already exists !";				
			}
			else {
				$slug = $nslug;					
			}				
		}
		echo json_encode(array('msgs'=>$msg, 'result'=>$slug));
		break;
	case "add":
		$record = new Services();

		$record->slug 			= create_slug($_REQUEST['title']);
		$record->title 			= $_REQUEST['title'];
		// $record->sub_title 			= $_REQUEST['sub_title'];
		// $record->image			= !empty($_REQUEST['imageArrayname']) ? serialize(array_values(array_filter($_REQUEST['imageArrayname']))) : '';
		$record->icon		= !empty($_REQUEST['icon']) ? $_REQUEST['icon'] : '';
		$record->linksrc 	= !empty($_REQUEST['linksrc']) ? $_REQUEST['linksrc'] : '';
		$record->linktype 	= !empty($_REQUEST['linktype']) ? $_REQUEST['linktype'] : '';
		$record->booklinksrc 	= !empty($_REQUEST['booklinksrc']) ? $_REQUEST['booklinksrc'] : '';
		$record->booklinktype 	= !empty($_REQUEST['booklinktype']) ? $_REQUEST['booklinktype'] : '';
		$record->explorelinksrc 	= !empty($_REQUEST['explorelinksrc']) ? $_REQUEST['explorelinksrc'] : '';
		$record->explorelinktype 	= !empty($_REQUEST['explorelinktype']) ? $_REQUEST['explorelinktype'] : '';
		$record->content		= !empty($_REQUEST['content']) ? $_REQUEST['content'] : '';
		$record->status			= $_REQUEST['status'];
		$record->service_type 		    = $_REQUEST['type'];
		// $record->brief 		= $_REQUEST['brief'];		
		$record->youtube_link 		= !empty($_REQUEST['youtube_link']) ? $_REQUEST['youtube_link'] : '';
		$record->instagram_link		= !empty($_REQUEST['instagram_link']) ? $_REQUEST['instagram_link'] : '';
		$record->x_link 			= !empty($_REQUEST['x_link']) ? $_REQUEST['x_link'] : '';
		$record->facebook_link 		= !empty($_REQUEST['facebook_link']) ? $_REQUEST['facebook_link'] : '';
		$record->linkedin_link 		= !empty($_REQUEST['linkedin_link']) ? $_REQUEST['linkedin_link'] : '';
		$record->tiktok_link 		= !empty($_REQUEST['tiktok_link']) ? $_REQUEST['tiktok_link'] : '';
		$record->contact_info 		= !empty($_REQUEST['contact_info']) ? $_REQUEST['contact_info'] : '';
		$record->fiscal_address 	= !empty($_REQUEST['fiscal_address']) ? $_REQUEST['fiscal_address'] : '';
		$record->email_address 		= !empty($_REQUEST['email_address']) ? $_REQUEST['email_address'] : '';
		$record->iconimage		= !empty($_REQUEST['iconArrayname']) ? serialize(array_values(array_filter($_REQUEST['iconArrayname']))) : '';
		// $record->bannerimage		= !empty($_REQUEST['bannerArrayname']) ? serialize(array_values(array_filter($_REQUEST['bannerArrayname']))) : '';
		// $record->has_website = !empty($_REQUEST['has_website']) ? (int)$_REQUEST['has_website'] : 0;;




		$record->sortorder		= Services::find_maximum();
		$record->added_date 	= registered();


		// 			$checkDupliName=Services::checkDupliName($record->title);			
		// 			if($checkDupliName):
		// 				echo json_encode(array("action"=>"warning","message"=>"Services Title Already Exists."));		
		// 				exit;		
		// 			endif;

		// if(empty($_REQUEST['imageArrayname'])):				
		// 	echo json_encode(array("action"=>"warning","message"=>"Required Upload Image !"));
		// 	exit;					
		// endif;

		$db->begin();
		if ($record->save()): $db->commit();
			$message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Services '" . $record->title . "'");
			echo json_encode(array("action" => "success", "message" => $message));
			log_action("Services [" . $record->title . "]" . $GLOBALS['basic']['addedSuccess'], 1, 3);
		else: $db->rollback();
			echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['unableToSave']));
		endif;
		break;

	case "edit":
		$record = Services::find_by_id($_REQUEST['idValue']);



		// 		if($record->title!=$_REQUEST['title']){
		// 			$checkDupliName=Services::checkDupliName($_REQUEST['title']);
		// 			if($checkDupliName):
		// 				echo json_encode(array("action"=>"warning","message"=>"Services title is already exist."));		
		// 				exit;		
		// 			endif;
		// 		}

		$record->slug 			= create_slug($_REQUEST['title']);
		$record->title 			= $_REQUEST['title'];
		// $record->sub_title 			= $_REQUEST['sub_title'];
		// $record->image			= !empty($_REQUEST['imageArrayname']) ? serialize(array_values(array_filter($_REQUEST['imageArrayname']))) : '';
		$record->icon		= !empty($_REQUEST['icon']) ? $_REQUEST['icon'] : '';
		$record->linksrc 	= !empty($_REQUEST['linksrc']) ? $_REQUEST['linksrc'] : '';
		$record->linktype 	= !empty($_REQUEST['linktype']) ? $_REQUEST['linktype'] : '';
		$record->booklinksrc 	= !empty($_REQUEST['booklinksrc']) ? $_REQUEST['booklinksrc'] : '';
		$record->booklinktype 	= !empty($_REQUEST['booklinktype']) ? $_REQUEST['booklinktype'] : '';
		$record->explorelinksrc 	= !empty($_REQUEST['explorelinksrc']) ? $_REQUEST['explorelinksrc'] : '';
		$record->explorelinktype 	= !empty($_REQUEST['explorelinktype']) ? $_REQUEST['explorelinktype'] : '';
		$record->content		= !empty($_REQUEST['content']) ? $_REQUEST['content'] : '';
		$record->status			= $_REQUEST['status'];
		$record->service_type 		    = $_REQUEST['type'];
		// $record->brief 		= $_REQUEST['brief'];	
		$record->youtube_link 		= !empty($_REQUEST['youtube_link']) ? $_REQUEST['youtube_link'] : '';
		$record->instagram_link		= !empty($_REQUEST['instagram_link']) ? $_REQUEST['instagram_link'] : '';

		$record->linkedin_link 		= !empty($_REQUEST['linkedin_link']) ? $_REQUEST['linkedin_link'] : '';
		$record->tiktok_link 		= !empty($_REQUEST['tiktok_link']) ? $_REQUEST['tiktok_link'] : '';
		$record->x_link 			= !empty($_REQUEST['x_link']) ? $_REQUEST['x_link'] : '';
		$record->facebook_link 		= !empty($_REQUEST['facebook_link']) ? $_REQUEST['facebook_link'] : '';
		$record->contact_info 		= !empty($_REQUEST['contact_info']) ? $_REQUEST['contact_info'] : '';
		$record->fiscal_address 	= !empty($_REQUEST['fiscal_address']) ? $_REQUEST['fiscal_address'] : '';
		$record->email_address 		= !empty($_REQUEST['email_address']) ? $_REQUEST['email_address'] : '';

		$record->iconimage		= !empty($_REQUEST['iconArrayname']) ? serialize(array_values(array_filter($_REQUEST['iconArrayname']))) : '';
		// $record->bannerimage		= !empty($_REQUEST['bannerArrayname']) ? serialize(array_values(array_filter($_REQUEST['bannerArrayname']))) : '';
		// $record->has_website = !empty($_REQUEST['has_website']) ? (int)$_REQUEST['has_website'] : 0;




		$db->begin();
		if ($record->save()): $db->commit();
			$message  = sprintf($GLOBALS['basic']['changesSaved_'], "Services '" . $record->title . "'");
			echo json_encode(array("action" => "success", "message" => $message));

			log_action("Services [" . $record->title . "] Edit Successfully", 1, 4);
		else: $db->rollback();
			echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
		endif;
		break;

	case "delete":
		$id = $_REQUEST['id'];
		$record = Services::find_by_id($id);
		log_action("Servicess  [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
		$db->query("DELETE FROM tbl_services WHERE id='{$id}'");

		reOrder("tbl_services", "sortorder");

		$message  = sprintf($GLOBALS['basic']['deletedSuccess_'], "Services '" . $record->title . "'");
		echo json_encode(array("action" => "success", "message" => $message));
		log_action("Services  [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
		break;

	// Module Setting Sections  >> <<
	case "toggleStatus":
		$id = $_REQUEST['id'];
		$record = Services::find_by_id($id);
		$record->status = ($record->status == 1) ? 0 : 1;
		$record->save();
		echo "";
		break;

	case "bulkToggleStatus":
		$id = $_REQUEST['idArray'];
		$allid = explode("|", $id);
		$return = "0";
		for ($i = 1; $i < count($allid); $i++) {
			$record = Services::find_by_id($allid[$i]);
			$record->status = ($record->status == 1) ? 0 : 1;
			$record->save();
		}
		echo "";
		break;

	case "bulkDelete":
		$id = $_REQUEST['idArray'];
		$allid = explode("|", $id);
		$return = "0";
		$db->begin();
		for ($i = 1; $i < count($allid); $i++) {
			$record = Services::find_by_id($allid[$i]);
			log_action("Services  [" . $record->title . "]" . $GLOBALS['basic']['deletedSuccess'], 1, 6);
			$res = $db->query("DELETE FROM tbl_services WHERE id='" . $allid[$i] . "'");
			$return = 1;
		}
		if ($res) $db->commit();
		else $db->rollback();
		reOrder("tbl_services", "sortorder");

		if ($return == 1):
			$message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Services");
			echo json_encode(array("action" => "success", "message" => $message));
		else:
			echo json_encode(array("action" => "error", "message" => $GLOBALS['basic']['noRecords']));
		endif;
		break;

	case "sort":
		$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
		$sortIds = $_REQUEST['sortIds'];
		$posId   = Services::field_by_id($id, 'type');
		datatableReordering('tbl_services', $sortIds, "sortorder", "type", $posId, 1);
		$message  = sprintf($GLOBALS['basic']['sorted_'], "Services");
		echo json_encode(array("action" => "success", "message" => $message));
		break;


	case "setschoolsId":
		$session->set('type_id_service', $_REQUEST['type_id_service']);
		echo json_encode(array("action" => "success", "message" => "Social type updated successfully"));
		break;
	case "metadata":
		$page_name = $_REQUEST['page_name'];
		$metatitle = $_REQUEST['meta_title'];
		$metakeywords = $_REQUEST['meta_keywords'];
		$metadescription = $_REQUEST['meta_description'];
		$addeddate = registered();
		// pr("SELECT * FROM tbl_metadata WHERE page_name='$page_name' LIMIT 1");
		$metasql = $db->query("SELECT * FROM tbl_metadata WHERE page_name='$page_name'LIMIT 1");
		$metadata = $metasql->fetch_array();

		$metaexist = !empty($metadata) ? array_shift($metadata) : false;
		if ($metaexist) {
			$metadata = "UPDATE tbl_metadata SET meta_title='" . $_REQUEST['meta_title'] . "', meta_keywords='" . $_REQUEST['meta_keywords'] . "', meta_description='" . $_REQUEST['meta_description'] . "' WHERE page_name='" . $_REQUEST['page_name'] . "'";
		} else {
			$metadata = "INSERT INTO tbl_metadata SET module_id='" . $_REQUEST['module_id'] . "', page_name='" . $_REQUEST['page_name'] . "', meta_title='" . $_REQUEST['meta_title'] . "', meta_keywords='" . $_REQUEST['meta_keywords'] . "', meta_description='" . $_REQUEST['meta_description'] . "', added_date='" . $addeddate . "'";
		}
		$db->begin();
		$sucess = $db->query($metadata);
		if ($sucess == 1): $db->commit();
			$message  = sprintf($GLOBALS['basic']['changesSaved_'], "services Meta Data saved successfully");
			echo json_encode(array("action" => "success", "message" => $message));
			log_action("services Meta Data Edit Successfully", 1, 4);
		else: $db->rollback();
			echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
		endif;
		break;
}
