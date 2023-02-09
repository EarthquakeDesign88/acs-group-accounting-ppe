<?php
if (!function_exists('__purchasereq_step_list')) {	
	function __purchasereq_step_list(){
		$purchaseReq_ceo_approve = " purc_apprceono = '' AND purc_compid = '". $paramurl_company_id ."' ";
		$purchaseReq_confirm = " purc_statusceo = 1 AND purc_apprceono <> '' AND purc_compid = '". $paramurl_company_id ."'";
		$purchaseReq_ceo_approve2 = " purc_statusceo = 0 AND purc_apprceono = '' AND purc_compid = '". $paramurl_company_id ."'";
		$purchaseReq_confirm2 = " purc_statusceo = 1 AND purc_apprceono  <> '' AND purc_compid = '". $paramurl_company_id ."'";
		
		$array = array(
			"purchaseReq_ceo_approve" => array("page"=>"purchaseReq_ceo_approve.php","name"=>"รออนุมัติ","class_box"=>"warning","icon"=>'<i class="icofont-notepad"></i>',"query_where"=>$purchaseReq_ceo_approve),
			"purchaseReq_confirm" => array("page"=>"purchaseReq_confirm.php","name"=>"กรรมการอนุมัติจ่าย","class_box"=>"success","icon"=>'<i class="icofont-notepad"></i>',"query_where"=>$purchaseReq_confirm),
			"purchaseReq_ceo_approve2" => array("page"=>"","name"=>"รออนุมัติ","class_box"=>"warning","icon"=>'<i class="icofont-notepad"></i>',"query_where"=>$purchaseReq_ceo_approve2),
			"purchaseReq_confirm2" => array("page"=>"","name"=>"อนุมัติแล้ว","class_box"=>"success","icon"=>'<i class="icofont-notepad"></i>',"query_where"=>$purchaseReq_confirm2),
		);
		
		$array["purchaseReq_ceo_approve"]["page"] ="purchaseReq_ceo_approve.php";	
		$array["purchaseReq_confirm"]["page"] ="";
		$array["purchaseReq_ceo_approve2"]["page"] = "";
		$array["purchaseReq_confirm2"]["page"] = "";
		
		return $array;
	}
}


if (!function_exists('__purchasereq_level_approve_list')) {
	function __purchasereq_level_approve_list(){
		$array = array(
			"purchaseReq_ceo_approve" =>array(2),
			"purchaseReq_confirm" =>array(2),
			"purchaseReq_ceo_approve2" =>array(7),
			"purchaseReq_confirm2" =>array(7),
		);
		
		return $array;
	}
}


if (!function_exists('__purchasereq_step')) {
	function __purchasereq_step($level_id=0){
		$list = __purchasereq_step_list();
		$level_approve_list = __purchasereq_level_approve_list();
		
		$list_new = array();	
		if(!empty($list)){
			foreach ($list as $key => $value) {
				if(!empty($level_approve_list[$key])){
					$level_list = $level_approve_list[$key];
					if(in_array($level_id, $level_list)){
						$list_new[$key] = $list[$key];
					}
				}
			}
		}	
		
		return $list_new;
	}
}
?>