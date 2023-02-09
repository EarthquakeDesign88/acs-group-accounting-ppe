<?php
include '../config/config.php';
__check_login();

$json = "";
$response = "F";
$message = "กรุณาลองใหม่อีกครั้ง";

$action = (isset($_POST["action"])) ? $_POST["action"] : "";

switch ($action) {
	case "save_menu_access_company" :
		$comp_id = (isset($_POST["comp_id"])) ? $_POST["comp_id"] : 0;
		$check_page = (isset($_POST["check_page"])) ? $_POST["check_page"] : array();
		
		$array_access_old =  __menu_list_access_company($comp_id);
		if(count($array_access_old)>=1){
			$list_access_old = implode(",", $array_access_old);
		}
		
		$arrQuery = array();
		$list_menu_page  = __menu_list_page();
		if(count($list_menu_page)>=1){
			foreach ($list_menu_page as $menu_id) {
				if(!empty($check_page[$menu_id])){
					$arrQuery[$menu_id] = "('" . $comp_id  . "','" . $menu_id . "')";
				}
			}
		}
		
		if(count($arrQuery)>=1){
			$sql_command_add = @implode(",", $arrQuery);
			$sql_add = "INSERT INTO menu_access_company_tb (comp_id, menu_id) VALUES $sql_command_add; ";
			$query_add = $db->query($sql_add);
			
			if (!$db->error(2)) {
				$sql_delete = "DELETE FROM menu_access_company_tb WHERE acc_comp_id IN (".$list_access_old.")";
				$db->query($sql_delete); 
				
				$response = "S";
			}else{
				$message = "บันทึกข้อมูลไม่สำเร็จ";
			}
		}else{
			$sql_delete = "DELETE FROM menu_access_company_tb WHERE acc_comp_id IN (".$list_access_old.")";
			$db->query($sql_delete);
			
			$response = "S"; 
		}
		
		$json = array('response' => $response, 'message' => $message);
		echo json_encode($json);
	break;
	
	case "save_menu_access_level" :
		$level_id = (isset($_POST["level_id"])) ? $_POST["level_id"] : 0;
		$check_page = (isset($_POST["check_page"])) ? $_POST["check_page"] : array();
		
		$array_access_old =  __menu_list_access_level($level_id);
		if(count($array_access_old)>=1){
			$list_access_old = implode(",", $array_access_old);
		}
		
		$list_menu_page  = __menu_list_page();
		$arrQuery = array();
		if(count($list_menu_page)>=1){
			foreach ($list_menu_page as $menu_id) {
				if(!empty($check_page[$menu_id])){
					$arrQuery[$menu_id] = "('" . $level_id  . "','" . $menu_id . "')";
				}
			}
		}
		
		if(count($arrQuery)>=1){
			$sql_command_add = @implode(",", $arrQuery);
			$sql_add = "INSERT INTO menu_access_level_tb (level_id, menu_id) VALUES $sql_command_add; ";
			$query_add = $db->query($sql_add);
			
	
			if (!$db->error(2)) {
				$sql_delete = "DELETE FROM menu_access_level_tb WHERE acc_level_id IN (".$list_access_old.")";
				$db->query($sql_delete); 
				
				$response = "S";
			}else{
				$message = "บันทึกข้อมูลไม่สำเร็จ";
			}
		}else{
			
			$sql_delete = "DELETE FROM menu_access_level_tb WHERE acc_level_id IN (".$list_access_old.")";
			$db->query($sql_delete);
			
			$response = "S"; 
		}
		
		$json = array('response' => $response, 'message' => $message);
		echo json_encode($json);
	break;
	
	case "save_menu_access_user" :
		$user_id = (isset($_POST["user_id"])) ? $_POST["user_id"] : 0;
		$check_page = (isset($_POST["check_page"])) ? $_POST["check_page"] : array();
		
		$array_access_old =  __menu_list_access_user($user_id);
		if(count($array_access_old)>=1){
			$list_access_old = implode(",", $array_access_old);
		}
		
		$arrQuery = array();
		$list_menu_page  = __menu_list_page();
		if(count($list_menu_page)>=1){
			foreach ($list_menu_page as $menu_id) {
				if(!empty($check_page[$menu_id])){
					$arrQuery[$menu_id] = "('" . $user_id  . "','" . $menu_id . "')";
				}
			}
		}
		
		if(count($arrQuery)>=1){
			$sql_command_add = @implode(",", $arrQuery);
			$sql_add = "INSERT INTO menu_access_user_tb (user_id, menu_id) VALUES $sql_command_add; ";
			$query_add = $db->query($sql_add);
			
			
			if (!$db->error(2)) {
				$sql_delete = "DELETE FROM menu_access_user_tb WHERE acc_user_id IN (".$list_access_old.")";
				$db->query($sql_delete); 
				
				$response = "S";
			}else{
				$message = "บันทึกข้อมูลไม่สำเร็จ";
			}
		}else{
			$sql_delete = "DELETE FROM menu_access_user_tb WHERE acc_user_id IN (".$list_access_old.")";
			$db->query($sql_delete); 
			
			$response = "S"; 
		}
		
		$json = array('response' => $response, 'message' => $message);
		echo json_encode($json);
	break;
		
	default ;
}
?>