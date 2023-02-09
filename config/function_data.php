<?php
if (!function_exists('__data_user')) {
	function __data_user($user_id=0,$get=""){
		$db = new database();
		$text = "";
		$str_sql = "SELECT u.*,l.lev_name AS user_levname ,l.lev_color AS user_levcolor,
		d.dep_name AS user_depname,d.dep_name_th AS user_depname_th,d.dep_name_en AS user_depname_en,
		c.comp_id AS user_compid,c.comp_name AS user_compname,c.comp_name_short AS user_compname_short FROM user_tb u
		LEFT JOIN department_tb d ON d.dep_id=u.user_depid
		LEFT JOIN company_tb c ON c.comp_id = d.dep_compid
		LEFT JOIN level_tb l ON l.lev_id = u.user_levid
		WHERE u.user_id = '".$user_id."'";
		$row = $db->query($str_sql)->row();
	
		$id = (isset($row["user_id"]) ? $row["user_id"] : "");
		$firstname = (isset($row["user_firstname"]) ? $row["user_firstname"] : "");
		$surname = (isset($row["user_surname"]) ? $row["user_surname"] : "");
		$name = (isset($row["user_name"]) ? $row["user_name"] : "");
		$level_id = (isset($row["user_levid"]) ? $row["user_levid"] : 0);
		$level_name = (isset($row["user_levname"]) ? $row["user_levname"] : "");
		$level_color = (isset($row["user_levcolor"]) ? $row["user_levcolor"] : "");
		$department_id = (isset($row["user_depid"]) ? $row["user_depid"] : "");
		$department_name = (isset($row["user_depname"]) ? $row["user_depname"] : "");
		$department_name_th = (isset($row["user_depname_th"]) ? $row["user_depname_th"] : "");
		$department_name_en = (isset($row["user_depname_en"]) ? $row["user_depname_en"] : "");
		$company_id = (isset($row["user_compid"]) ? $row["user_compid"] : "");
		$company_name = (isset($row["user_compname"]) ? $row["user_compname"] : "");
		$company_name_short = (isset($row["user_compname_short"]) ? $row["user_compname_short"] : "");
		
		$text = "";
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
		}else{
			$text = $row;
		}
		
		return $text;	
	}
}

if (!function_exists('__data_company')) {
	function __data_company($company_id=0,$get=""){
		$db = new database();	
		$sql = "SELECT * FROM company_tb WHERE comp_id = '" . $company_id . "'";
		$row = $db->query($sql)->row();
		
		$id = (isset($row["comp_id"]) ? $row["comp_id"] : "");
		$name = (isset($row["comp_name"]) ? $row["comp_name"] : "");
		$name_short = (isset($row["comp_name_short"]) ? $row["comp_name_short"] : "");
		$address = (isset($row["comp_address"]) ? $row["comp_address"] : "");
		$taxno = (isset($row["comp_taxno"]) ? $row["comp_taxno"] : "");
		$tel = (isset($row["comp_tel"]) ? $row["comp_tel"] : "");
		$fax = (isset($row["comp_fax"]) ? $row["comp_fax"] : "");
		$email = (isset($row["comp_email"]) ? $row["comp_email"] : "");
		$website = (isset($row["comp_website"]) ? $row["comp_website"] : "");
		
		$text = "";
		if($get=="id"){
			$text = $id;
		}else if($get=="name"){
			$text = $name;
		}else if($get=="name_short"){
			$text = $name_short;
		}else if($get=="address"){
			$text = $address;
		}else if($get=="taxno"){
			$text = $taxno;
		}else if($get=="tel"){
			$text = $tel;
		}else if($get=="fax"){	
			$text = $fax;
		}else if($get=="email"){
			$text = $email;
		}else if($get=="website"){
			$text = $website;
		}else{
			$text = $row;
		}
		
		return $text;	
	}
}

if (!function_exists('__data_load_company')) {
	function __data_load_company(){
		$db = new database();	
		$sql = "SELECT * FROM company_tb WHERE comp_id = '" . $company_id . "'";
		$result = $db->query($sql)->result();
		
		return $result;
	}
}

if (!function_exists('__data_company_count_department')) {
	function __data_company_count_department($company_id=0){
		$db = new database();	
		
		$sql = "SELECT COUNT(dep_id)  AS count_dep FROM department_tb WHERE dep_compid = '" . $company_id . "' AND dep_status=1";
		$row = $db->query($sql)->row();
		$count = $row["count_dep"];
		
		return ($count>=1)  ? $count: 0; 
	}
}


if (!function_exists('__data_load_department')) {
	function __data_load_department($company_id=0){
		$db = new database();	
		
		$sql = "SELECT * FROM department_tb WHERE dep_compid = '" . $company_id . "' AND dep_status=1";
		$result = $db->query($sql)->result();
		
		return $result; 
	}
}

if (!function_exists('__data_department')) {
	function __data_department($department_id=0,$get=""){
		$db = new database();	
		$sql = "SELECT c.comp_name ,d.* 
		FROM department_tb d 
		LEFT JOIN company_tb c ON c.comp_id = d.dep_compid
		WHERE d.dep_id = '" . $department_id . "'";
		$row = $db->query($sql)->row();
		
		$id = (isset($row["dep_id"]) ? $row["dep_id"] : "");
		$name = (isset($row["dep_name"]) ? $row["dep_name"] : "");
		$name_th = (isset($row["dep_name_th"]) ? $row["dep_name_th"] : "");
		$name_en = (isset($row["dep_name_en"]) ? $row["dep_name_en"] : "");
		$company_id = (isset($row["dep_compid"]) ? $row["dep_compid"] : "");
		$company_name = (isset($row["comp_name"]) ? $row["comp_name"] : "");
		$code = (isset($row["dep_code"]) ? $row["dep_code"] : "");
		$type = (isset($row["dep_type"]) ? $row["dep_type"] : "");
		$mdep = (isset($row["dep_mdep"]) ? $row["dep_mdep"] : "");
		
		$text = "";
		if($get=="id"){
			$text = $id;
		}else if($get=="name"){
			$text = $name;
		}else if($get=="name_th"){
			$text = $name_th;
		}else if($get=="name_en"){
			$text = $name_en;
		}else if($get=="company_id"){
			$text = $company_id;	
		}else if($get=="company_name"){
			$text = $company_name;	
		}else if($get=="code"){
			$text = $code;	
		}else if($get=="type"){
			$text = $type;	
		}else if($get=="mdep"){
			$text = $mdep;	
		}else{
			$text = $row;	
		}
			
		return $text;	
	}	
}
?>