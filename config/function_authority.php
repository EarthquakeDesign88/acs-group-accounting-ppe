<?php
if (!function_exists('__authority_level_all_list')) {
	function __authority_level_all_list(){
		$array[1] = 1;
		$array[2] = 2;
		$array[3] = 3;
		$array[4] = 4;
		
		return $array;
	}
}

if (!function_exists('__authority_company_list')) {
	function __authority_company_list($user_id=0){
		$db = new database();
		
		$user_level = __data_user($user_id,"level_id");
		$arrayLevelAll = __authority_level_all_list();
		
		if(!empty($arrayLevelAll[$user_level])){
			$sql = "SELECT c.*
			FROM company_tb c
			LEFT JOIN  department_tb d ON d.dep_compid = c.comp_id
			WHERE d.dep_status = 1
			GROUP BY d.dep_compid
			ORDER BY c.comp_name ASC";
		}else{
			$sql = "SELECT c.* FROM company_tb c
			LEFT JOIN user_authority_tb aut ON aut.uaut_compid = c.comp_id
			LEFT JOIN department_tb d ON d.dep_compid = c.comp_id
			LEFT JOIN user_tb u ON u.user_depid = d.dep_id
			WHERE (aut.uaut_userid =  '".$user_id."' AND aut.uaut_compid=d.dep_compid)
			OR (u.user_id = '".$user_id."' AND u.user_depid = d.dep_id)
			AND d.dep_status = 1
			GROUP BY c.comp_id ORDER BY comp_name ASC  ";
		}
		
		$result = $db->query($sql)->result();
		
		$array = array();
		if(count($result)){
			foreach ($result as $row) {
				$company_id =$row["comp_id"];
				$array[$company_id] = $row;
			}
		}
		return $array;
		
	}
}



if (!function_exists('__authority_department_list')) {
	function __authority_department_list($user_id=0,$company_id=0,$getdep="all"){
		$db = new database();
		
		$user_level = __data_user($user_id,"level_id");
		$user_dep = __data_user($user_id,"department_id");
		$arrayLevelAll = __authority_level_all_list();
		
		$con = "";
		if($getdep=="no_mdep"){
			$con .= "AND (d.dep_mdep = '' OR d.dep_mdep IS NULL) ";
		}else if($getdep=="is_mdep"){
			$con .= "AND (d.dep_mdep<>'' AND  d.dep_mdep IS NOT NULL) ";
		}
		
		if(!empty($arrayLevelAll[$user_level])){
			$sql = "SELECT d.* FROM department_tb d WHERE d.dep_status = 1 AND d.dep_compid = '".$company_id."' ".$con."  ORDER BY d.dep_name ASC";
			$row2 = "";
		}else{
			$sql = " SELECT d.* 
			FROM department_tb d
			LEFT JOIN user_tb u ON u.user_depid = d.dep_id
			LEFT JOIN user_authority_tb aut ON aut.uaut_depid = d.dep_id
		    WHERE (aut.uaut_userid = '".$user_id."' AND aut.uaut_compid=d.dep_compid AND aut.uaut_compid =  '".$company_id."' AND d.dep_compid =  '".$company_id."'  AND d.dep_status = 1 )
		    OR (u.user_id =  '".$user_id."' AND u.user_depid=d.dep_compid AND d.dep_compid =  '".$company_id."' AND d.dep_compid =  '".$company_id."'  AND d.dep_status = 1 ) 
			".$con."
			GROUP BY d.dep_id
			ORDER BY d.dep_name ASC";
			
			$sql2 = "SELECT d.* FROM department_tb d WHERE d.dep_status = 1 AND d.dep_compid = '".$company_id."' AND d.dep_id = '".$user_dep."' ".$con."  ORDER BY d.dep_name ASC";
		    $row2 = $db->query($sql2)->row();
		}
		
		
		$result = $db->query($sql)->result();
		$array = array();
		if(!empty($result)){
			foreach ($result as $row) {
				$dep_id = $row["dep_id"];
				$array[$dep_id] = $row;
			}
		}
		
		
		if(!empty($row2)){
			$array[$row2["dep_id"]] = $row2;
		}
				
		return $array;
		
	}
}



if (!function_exists('__authority_company_count_department')) {
	function __authority_company_count_department($user_id=0,$company_id=0,$getdep=""){
		$array = __authority_department_list($user_id,$company_id,$getdep);
		$count = 0;
		if(count($array)>=1){
			$count+= count($array);
		}
		
		return $count;	
	
	}
}


if (!function_exists('__authority_company_department_main')) {
	function __authority_company_department_main($user_id=0,$company_id=0,$getdep=""){
		$array = __authority_department_list($user_id,$company_id,$getdep);
		
		if(!empty($array)){
			$xx = end($array);
			return $xx["dep_id"];
		}
	}
}



if (!function_exists('__authority_department_text_list')) {
	function __authority_department_text_list($user_id=0,$company_id=0,$getdep=""){
		$array = __authority_department_list($user_id,$company_id,$getdep);
		$dep_list = "'";
		if(count($array)>=1){
			$dep_list = implode("','", array_keys($array));
		}
		
		return $dep_list;
	}
}


if (!function_exists('__authority_department_check')) {
	function __authority_department_check($user_id=0,$company_id=0,$department_id=0,$getdep=""){
		
		$array = __authority_department_list($user_id,$company_id,$getdep);
		
		if(!empty($array[$department_id])){
			return true;
		}
		
	}
}


?>