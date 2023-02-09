<?php
include 'config/config.php'; 

$json = "";
$response = "F";
$message = "กรุณาลองใหม่อีกครั้ง";
$username = "";
$password = "";
if(!empty($_POST)){
	$username = (isset($_POST["username"])) ? $_POST["username"] : "";
	$password = (isset($_POST["password"])) ? $_POST["password"] : "";
	
	$sql_count_user = "SELECT user_id,COUNT(user_id) AS c FROM user_tb WHERE user_name = BINARY  '".$username."'" ;
	$row_count_user = $db->query($sql_count_user)->row();
	$count_user = $row_count_user["c"];
	
	if($count_user>=1){
		$sql_check = $sql_count_user." AND user_password = BINARY '".$password."'" ;
		$row_check = $db->query($sql_check)->row();
		$check_password  = $row_check["c"];
		$user_id = $row_check["user_id"];
		
		if($check_password>=1){
			$row_user = __data_user($user_id);
			$_SESSION["user_id"] = $row_user["user_id"];
			$_SESSION["user_firstname"] = $row_user["user_firstname"];
			$_SESSION["user_surname"] = $row_user["user_surname"];
			$_SESSION["user_name"] = $row_user["user_name"];
			$_SESSION["user_levid"] = $row_user["user_levid"];
			$_SESSION["user_levname"] = $row_user["user_levname"];
			$_SESSION["user_levcolor"] = $row_user["user_levcolor"];
			$_SESSION["user_depid"] = $row_user["user_depid"];
			$_SESSION["user_depname"] = $row_user["user_depname"];
			$_SESSION["user_depname_th"] = $row_user["user_depname_th"];
			$_SESSION["user_depname_en"] = $row_user["user_depname_en"];
			$_SESSION["user_compid"] = $row_user["user_compid"];
			$_SESSION["user_compname"] = $row_user["user_compname"];
			$_SESSION["user_compname_short"] = $row_user["user_compname_short"];	
			
			$response = "S";
			$message = "Success";
		}else{
			$message = "รหัสผ่านไม่ถูกต้อง";
		}
	}else{
		$message = "ไม่พบบัญชี ".$username;
	}
}
	
__log_logging("login",$username,$password,$response,$message);

$json = array('response' =>$response,	'message' => $message);
echo json_encode($json);
?>
