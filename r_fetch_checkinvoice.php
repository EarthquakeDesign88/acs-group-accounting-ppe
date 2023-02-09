<?php
include 'config/config.php'; 
$user_id = __session_user("id");
$user_level_id = __session_user("level_id");
$user_department_id = __session_user("department_id");
				
$error = 0;
$response = "F";
$message = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";
$text_error_login = "กรุณาเข้าสู่ระบบ";
$text_error_access = "ขออภัย คุณไม่มีสิทธิ์เข้าถึงข้อมูล";
$text_not_data = "ไม่พบข้อมูล";
$action = (isset($_GET["action"])) ?$_GET["action"] : "";
$paramurl_step = (isset($_GET["step"])) ?$_GET["step"] : 0;
$paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
$paramurl_department_id = (isset($_GET["dep"])) ?$_GET["dep"] : 0;
$step_key = "checkinvoice_".$paramurl_step;
	
$invoice_step = __invoice_step_company_list($user_id,$paramurl_company_id,$paramurl_department_id);
$array_step = array();
$step_query_where = "";
if(!empty($invoice_step[$step_key])){
	$array_step = $invoice_step[$step_key];
	$step_query_where = $array_step["query_where"];
	$step_name  = $array_step["name"];
}else{
	$array_step = array();
	$step_query_where = "";
	$step_name = "";
}
$ck_login = (!empty($user_id)) ? true : false;
$ck_access =(!empty($array_step)) ? true : false;
switch ($action) {
	case "check" :
		$amount = 0;
		$id = (isset($_POST["id"])) ?$_POST["id"] : 0;
		$check = (isset($_POST["check"])) ?$_POST["check"] : "";
		
		if($ck_login){
			if($ck_access){
				if($paramurl_step!="" && $paramurl_company_id !="" && $paramurl_department_id !="" &&  !empty($id) && $check!=""){
					if(isset($_SESSION["checkinvoice"][$paramurl_step][$paramurl_company_id][$paramurl_department_id][$id])){
						unset($_SESSION["checkinvoice"][$paramurl_step][$paramurl_company_id][$paramurl_department_id][$id]);
					}
					
					if($check==1){
						$_SESSION["checkinvoice"][$paramurl_step][$paramurl_company_id][$paramurl_department_id][$id] = $id;
					}
					
                     $data_checked = __invoice_data_checked($paramurl_step,$paramurl_company_id,$paramurl_department_id);
                    $amount = $data_checked["amount"];
                    
					$response = "S";
				}else{
					$message = $text_not_data;
				}
			}else{
				$message = $text_error_access;
			}
		}else{
			$message = $text_error_login;
		}
		
		$json = array("amount"=>__price($amount),'response' =>$response,	'message' => $message, "error"=>$error);
		echo json_encode($json);
	break;
		
	case "reset" :
		if($ck_login){
				if($ck_access){
				if($paramurl_step!="" && $paramurl_company_id !="" && $paramurl_department_id !=""){
					if(isset($_SESSION["checkinvoice"][$paramurl_step][$paramurl_company_id][$paramurl_department_id])){
						unset($_SESSION["checkinvoice"][$paramurl_step][$paramurl_company_id][$paramurl_department_id]);
					}
					
					$response = "S";
				}else{
					$message = $text_not_data;	
				}
			}else{
				$message = $text_error_access;
			}
		}else{
			$message = $text_error_login;
		}
		
		$json = array('response' =>$response,	'message' => $message, "error"=>$error);
		echo json_encode($json);
	break;
		
	case "view" :
		if($ck_login){
			if($ck_access){
				if($paramurl_step!="" && $paramurl_company_id !="" && $paramurl_department_id !=""){
					
					$html = "";
					if(isset($_SESSION["checkinvoice"][$paramurl_step][$paramurl_company_id][$paramurl_department_id])){
						$arrayChecked = $_SESSION["checkinvoice"][$paramurl_step][$paramurl_company_id][$paramurl_department_id];
						$text_checked = implode("','",$arrayChecked);
						
                        $sql = __invoice_query_select();
						$sql .= __invoice_query_from();
						$sql .= " WHERE  i.inv_id IN ('".$text_checked."')";
						$result = $db->query($sql)->result();
					}else{
						$result = array();
					}	
					
					$btn_uncheck= '<button type="button" class="btn btn-danger form-control btn-block btn-check"  title="ไม่อนุมัติ / No Approve"  onclick="onCheck(this,0)"><i class="icofont-checked"></i> No Approve</button>';
					
					$html = "";
					$html .= '<div class="table-responsive">';
						$html .= '<table class="table table-bordered mb-0">';
							$html .= '<thead class="thead-light">';
								$html .= '<tr>';
									$html .= '<th style="width: 70px;min-width: 70px;" class="text-center">ลำดับ</th>';
									$html .= '<th style="width: 140px;min-width: 140px;">เลขที่ใบแจ้งหนี้</th>';
									$html .= '<th style="min-width: 340px;">รายละเอียด</th>';
									$html .= '<th style="width: 140px;min-width: 140px;" class="text-center">วันที่ครบชำระ</th>';
									$html .= '<th style="width: 140px;min-width: 140px;" class="text-center">จำนวนเงิน</th>';
									$html .= '<th style="width: 180px;min-width: 180px;">&nbsp;</th>';
								$html .= '</tr>';
							$html .= '</thead>';
							$html .= '<tbody>';
						
						$amount = 0;	
						$a = 1;
					    $html_modal_detail = "";
						if(count($result)>=1){
							foreach ($result as $datalist) {
								$btn = $btn_uncheck;
								include 'variable_invoice.php';	
								$amount+= $inv_netamount;
								 $html .= $html_tr;
								 $html_modal_detail .= $html_detail;
								 $a++;
								}
							}else {
								$html .= '<tr>';
									$html .= '<td colspan="6" align="center">ไม่มีข้อมูลที่เลือก</td>';
								$html .= '</tr>';
							} 
								$html .= '</tbody>';
								
								$html .= '<tfoot>';
								$html .= '<tr>';
									$html .= '<td colspan="4" class="title-amount">ยอดรวมตรวจสอบ</td>';
									$html .= '<td colspan="2"  class="amount">'.__price($amount).'</td>';
								$html .= '</tr>';
								$html .= '</tfoot>';
							
							$html .= '</table>';
						$html .= '</div>';
							
						echo $html;
                        echo $html_modal_detail;
				}else{
					echo $text_not_data;
				}
			}else{
				echo $text_error_access;
			}
		}else{
			echo  $text_error_login;
		}
	break;		
	
	 case "recheck" :
        $amount = 0;
        $count = 0;
        if($ck_login){
            if($ck_access){
                if($paramurl_company_id !="" && $paramurl_department_id !=""){
                    $data_checked = __invoice_payment_data_checked($paramurl_company_id,$paramurl_department_id);
                    $count = $data_checked["count"];
                    $amount  = $data_checked["amount"];
        
                    $response = "S";
                }else{
                    $message = $text_not_data;
                }
            }else{
            $message = $text_error_access;
            }
        }else{
            $message = $text_error_login;
        }
        
        $json = array('response' =>$response,   'message' => $message, "error"=>$error,"amount"=>$amount,"count"=>$count);
        echo json_encode($json);
    break;
	
	case "data_check" :
		$data_checked = __invoice_data_checked($paramurl_step,$paramurl_company_id,$paramurl_department_id);
		$count = $data_checked["count"];
		$amount  = $data_checked["amount"];
		
		$json = array('count' =>__number($count),'amount' => __price($amount));
		echo json_encode($json);
	break;
	
	case "save" :
		$check = (isset($_POST["check"])) ?$_POST["check"] : "";
		
		if($ck_login){
			if($ck_access){
				if($paramurl_step!="" && $paramurl_company_id !="" && $paramurl_department_id !=""){
					
					$data_checked = __invoice_data_checked($paramurl_step,$paramurl_company_id,$paramurl_department_id);
					$count_check = $data_checked["count"];
					
					if($count_check>=1){
						$arrayChecked = $_SESSION["checkinvoice"][$paramurl_step][$paramurl_company_id][$paramurl_department_id];
						$text_checked = implode("','",$arrayChecked);
						
						$dep_name = __data_department($paramurl_department_id,"name");
						$datetime = date('Y-m-d H:i:s');
						$date =  date('Y-m-d');
						$month = date("m");
						$year = date("Y");
						$year_th = $year+543;
						$year_th_short = substr($year_th, -2);
						
						$arrAppove = array();
						if(isset($array_step["db_appove1"])){
							$arrAppove[1] = $array_step["db_appove1"];
						}
						
						if(isset($array_step["db_appove2"])){
							$arrAppove[2] = $array_step["db_appove2"];
						}
				
				
						$sql_ck_flow = "SELECT COUNT(i.inv_id) AS count ".__invoice_query_from()." WHERE  i.inv_id IN ('".$text_checked."') AND ".$step_query_where;
						$row_ck_flow = $db->query($sql_ck_flow)->row();
									
						if($row_ck_flow["count"]==count($arrayChecked)){		
							$ck_save1 = 0;
							$ck_save2 = 0;
							if(!empty($arrAppove)){
								foreach ($arrAppove as $keyAppove => $valAppove) {
									$tb_approve = $valAppove["tb"];
									$col_approve = $valAppove["col"];
									$code_approve = $valAppove["code"];
									$col_inv_status = $valAppove["col_inv_status"];
									$col_inv_no = $valAppove["col_inv_no"];
									
									$sql_approve_no = "	SELECT
										CONCAT('".$code_approve."','".$dep_name."', '".$year_th_short."', '".$month."',
										(CASE WHEN (MAX((SUBSTRING(".$col_approve."no,( LENGTH ('".$code_approve.$dep_name."')+ 5 ), 3 ))) >= 1) 
										THEN LPAD(MAX(SUBSTRING(".$col_approve."no,(LENGTH ('".$code_approve.$dep_name."')+5),3))+1,3,'0') ELSE '001'	END)
										) AS new_id
									FROM ".$tb_approve." 
									WHERE ".$col_approve."depid = '".$paramurl_department_id."' 
									AND ".$col_approve."month = '". $month ."' AND ".$col_approve."year = '". $year_th ."'  ";
									$row_approve_no = $db->query($sql_approve_no)->row();
									$new_approve_no = $row_approve_no["new_id"];
									
									$con_approve = "";
									$con_approve .= $col_approve."no = '".$new_approve_no."',";
									$con_approve .= $col_approve."date = '".$date."',";
									$con_approve .= $col_approve."year = '".$year_th."',";
									$con_approve .= $col_approve."month = '".$month."',";
									$con_approve .= $col_approve."depid = '".$paramurl_department_id."',";
									
									$sql_action = "UPDATE invoice_tb i SET ".$col_inv_status." = '1',".$col_inv_no." = '".$new_approve_no."' WHERE  i.inv_id IN ('".$text_checked."')";
									$query_action = $db->query($sql_action);
									
									if(!$db->error(2)) {
										$ck_save1++;
									
										$sql_approve = "INSERT INTO  ".$tb_approve."  SET $con_approve ".$col_approve."userid_create ='".$user_id."', ".$col_approve."datecreate ='".$datetime."'";		
										$query_approve = $db->query($sql_approve);
									
										if(!$db->error(2)) {
											$ck_save2++;
										}
									}
									
									
								}
							}
							if($ck_save1>=1){
								if($ck_save2>=1){
									if(isset($_SESSION["checkinvoice"][$paramurl_step][$paramurl_company_id][$paramurl_department_id])){
										unset($_SESSION["checkinvoice"][$paramurl_step][$paramurl_company_id][$paramurl_department_id]);
									}
						
									$response = "S";
								}else{
									$error = "DB 2";
								}
							}else{
								$error = "DB 1";
							}
						}else{
							$message = "กรุณาเลือกใบแจ้งหนี้ใหม่ เนื่องจากใบแจ้งหนี้ที่เลือกไม่ได้อยู่ในขั้นตอน ";
						}
						
					}else{
						$message = "คุณยังไม่ได้เลือกใบแจ้งหนี้ กรุณาเลือกใบแจ้งหนี้ อย่างน้อย 1 รายการ";
					}
				}else{
					$message = $text_not_data;
				}
			}else{
				$message = $text_error_access;
			}
		}else{
			$message = $text_error_login;
		}
		
		$data_log = array("detail" => "อนุมัติใบแจ้งหนี้ - ".$step_name);
		        
		__log_action("SAVE_INVOICE_APPROVE_".strtoupper($paramurl_step),$response,"",$data_log,"invoice_approve");
		
		$json = array('response' =>$response,	'message' => $message, "error"=>$error);
		echo json_encode($json);
	break;
		
	default;
		echo "no action required";
}
?>