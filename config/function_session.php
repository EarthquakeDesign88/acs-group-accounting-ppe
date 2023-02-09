<?php
$path = realpath(dirname(__FILE__). '/../');
include $path.'/database.php';
if (!function_exists('__session_user')) {
	function __session_user($get=""){
		$text = "";
		$id = (isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 0);
		$firstname = (isset($_SESSION["user_firstname"]) ? $_SESSION["user_firstname"] : "");
		$surname = (isset($_SESSION["user_surname"]) ? $_SESSION["user_surname"] : "");
		$name = (isset($_SESSION["user_name"]) ? $_SESSION["user_name"] : "");
		$level_id = (isset($_SESSION["user_levid"]) ? $_SESSION["user_levid"] : 0);
		$level_name = (isset($_SESSION["user_levname"]) ? $_SESSION["user_levname"] : "");
		$level_color = (isset($_SESSION["user_levcolor"]) ? $_SESSION["user_levcolor"] : "");
		$department_id = (isset($_SESSION["user_depid"]) ? $_SESSION["user_depid"] : "");
		$department_name = (isset($_SESSION["user_depname"]) ? $_SESSION["user_depname"] : "");
		$department_name_th = (isset($_SESSION["user_depname_th"]) ? $_SESSION["user_depname_th"] : "");
		$department_name_en = (isset($_SESSION["user_depname_en"]) ? $_SESSION["user_depname_en"] : "");
		$company_id = (isset($_SESSION["user_compid"]) ? $_SESSION["user_compid"] : "");
		$company_name = (isset($_SESSION["user_compname"]) ? $_SESSION["user_compname"] : "");
		$company_name_short = (isset($_SESSION["user_compname_short"]) ? $_SESSION["user_compname_short"] : "");
		
		if($get=="id"){
			$text = $id;
		}else if($get=="firstname"){
			$text = $firstname;
		}else if($get=="surname"){
			$text = $surname;
		}else if($get=="name"){
			$text = $name;
		}else if($get=="level_id"){		
			$text = $level_id;
		}else if($get=="level_name"){
			$text = $level_name;
		}else if($get=="level_color"){
			$text = $level_color;
		}else if($get=="department_id"){
			$text = $department_id;
		}else if($get=="department_name"){
			$text = $department_name;	
		}else if($get=="department_name_th"){
			$text = $department_name_th;	
		}else if($get=="department_name_en"){
			$text = $department_name_en;		
		}else if($get=="company_id"){
			$text = $company_id;
		}else if($get=="company_name"){
			$text = $company_name;			
		}else if($get=="company_name_short"){				
			$text = $company_name_short;	
		}
		
		return $text;	
	}
}
?>