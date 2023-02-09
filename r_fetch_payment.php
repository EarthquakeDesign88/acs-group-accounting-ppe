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
$paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
$paramurl_department_id = (isset($_GET["dep"])) ?$_GET["dep"] : 0;
$ck_login = (!empty($user_id)) ? true : false;
$ck_access = true;
switch ($action) {
    case "check" :
        $amount = 0;
        $id = (isset($_POST["id"])) ?$_POST["id"] : 0;
        $check = (isset($_POST["check"])) ?$_POST["check"] : "";
        $check_user = false;
        $text_check_error = "";
        
        if($ck_login){
            if($ck_access){
                if($paramurl_company_id !="" && $paramurl_department_id !="" &&  !empty($id) && $check!=""){
                    $sql_check = "";
                    
                    if(isset($_SESSION["payment"][$paramurl_company_id][$paramurl_department_id][$id])){
                        unset($_SESSION["payment"][$paramurl_company_id][$paramurl_department_id][$id]);
                    }
                    if($check==1){
                        if(empty($_SESSION["payment"][$paramurl_company_id][$paramurl_department_id])){
                            $check_user = true;
                        }else{
                            $arrayCheckedBefore = $_SESSION["payment"][$paramurl_company_id][$paramurl_department_id];
                            $text_checked_before = implode("','",$arrayCheckedBefore);
                            $sql_check = " SELECT COUNT(i.inv_id) AS count";
                            $sql_check .= __invoice_query_from();
                            $sql_check .= " WHERE  ((i.inv_statusMgr = 1 AND i.inv_apprMgrno <> '') AND inv_paymid = '' AND p.paya_id<>'')  
                            AND i.inv_compid = '". $paramurl_company_id ."' AND  i.inv_depid = '". $paramurl_department_id."'";
                            $sql_check .= " AND i.inv_id IN ('".$text_checked_before."') OR i.inv_id = '".$id."' ";
                            
                            $numrow_check_mgr  = $db->query( $sql_check." GROUP BY mgr.apprMgr_userid_create ")->num_rows();
                            $numrow_check_mdep  = $db->query( $sql_check." GROUP BY mdep.apprMdep_userid_create ")->num_rows();
                            $numrow_check_ceo  = $db->query( $sql_check." GROUP BY ceo.apprCEO_userid_create ")->num_rows();
                            
                            if($numrow_check_mgr<=1 && $numrow_check_mdep<=1 && $numrow_check_ceo<=1){
                                 $check_user = true;
                            }else{
                                $text_check_error = "";
                                
                                if($numrow_check_mgr >1){
                                     $text_check_error .= ", ผู้ตรวจจ่าย";
                                } 
                        
                                if($numrow_check_mdep >1){
                                    $text_check_error .= ", ฝ่ายอนุมัติ";
                                }
                                
                                if($numrow_check_ceo >1){
                                    $text_check_error .= ", ผู้อนุมัติ";          
                                }
                            }
                        }
                            
                        if($check_user){
                            $_SESSION["payment"][$paramurl_company_id][$paramurl_department_id][$id] = $id;
                        }
                    }else{
                        $check_user = true;
                    }
                    
                    if($check_user){
                        $data_checked = __invoice_payment_data_checked($paramurl_company_id,$paramurl_department_id);
                        $amount = $data_checked["amount"];
                        $response = "S";
                    }else{
                        $message = "กรุณาเลือกใบแจ้งหนี้ใหม่<br>รายการที่เลือกมีรายชื่อ <u>".trim($text_check_error,",")."</u> ไม่ตรงกัน";
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
        
        $json = array("amount"=>__price($amount),'response' =>$response,    'message' => $message, "error"=>$error);
        echo json_encode($json);
    break;
        
    case "reset" :
        if($ck_login){
                if($ck_access){
                if($paramurl_company_id !="" && $paramurl_department_id !=""){
                    if(isset($_SESSION["payment"][$paramurl_company_id][$paramurl_department_id])){
                        unset($_SESSION["payment"][$paramurl_company_id][$paramurl_department_id]);
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
        
        $json = array('response' =>$response,   'message' => $message, "error"=>$error);
        echo json_encode($json);
    break;
        
    case "view" :
        if($ck_login){
            if($ck_access){
                if($paramurl_company_id !="" && $paramurl_department_id !=""){
                    
                    $html = "";
                    if(isset($_SESSION["payment"][$paramurl_company_id][$paramurl_department_id])){
                        $arrayChecked = $_SESSION["payment"][$paramurl_company_id][$paramurl_department_id];
                        $text_checked = implode("','",$arrayChecked);
                        
                        $sql = __invoice_query_select();
                        $sql .= __invoice_query_from();
                        $sql .= " WHERE  i.inv_id IN ('".$text_checked."')";
                        $result = $db->query($sql)->result();
                    }else{
                        $result = array();
                    }   
                    
                    $btn_uncheck= '<button type="button" class="btn btn-danger form-control btn-block btn-check"  title="ไม่อนุมัติ / No Approve"  onclick="onCheck(this,0)"><i class="icofont-close  font-lg"></i></button>';
                    
                    $html = "";
                    $html .= '<div class="table-responsive">';
                        $html .= '<table class="table table-bordered mb-0">';
                            $html .= '<thead class="thead-light">';
                                $html .= '<tr>';
                                    $html .= '<th style="width: 70px;min-width: 70px;"></th>';
                                    $html .= '<th style="width: 70px;min-width: 70px;" class="text-center">ลำดับ</th>';
                                    $html .= '<th style="width: 140px;min-width: 140px;">เลขที่ใบแจ้งหนี้</th>';
                                    $html .= '<th style="min-width: 340px;">รายละเอียด</th>';
                                    $html .= '<th style="width: 140px;min-width: 140px;" class="text-center">วันที่ครบชำระ</th>';
                                    $html .= '<th style="width: 140px;min-width: 140px;" class="text-center">จำนวนเงิน</th>';
                                    $html .= '<th style="width: 70px;min-width: 70px;"> </th>';
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
                                 $html .= $html_payment_tr;
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
                                    $html .= '<td colspan="5" class="title-amount">ยอดรวมตรวจสอบ</td>';
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
        $data_checked = __invoice_payment_data_checked($paramurl_company_id,$paramurl_department_id);
        $count = $data_checked["count"];
        $amount  = $data_checked["amount"];
        
        $json = array('count' =>__number($count),'amount' => __price($amount));
        echo json_encode($json);
    break;
    
    case "preview_action" :
         if(!empty($_POST)){
             $paym_id =  (!empty($_POST["paym_id"])) ? $_POST["paym_id"] : 0;
             $edit = (!empty($paym_id)) ? true : false;
             
            $datalist = array();
             if($edit){
                $sql = __invoice_query_select();
                $sql .=__invoice_payment_query_from();
                $sql .= " WHERE pm.paym_id = '".$paym_id."'";
                $datalist = $db->query($sql." GROUP BY pm.paym_no ")->row();
                 $invoice_data = __invoice_data("",$paym_id);
             }else{
                $inv_id  = (isset($_POST["inv_id"])) ? $_POST["inv_id"]: array();
                $text_checked = implode("','", $inv_id);
                $sql = __invoice_query_select();
                $sql .= " ,  GROUP_CONCAT(CONCAT('''',i.inv_id, '''' )) AS inv_id_list ";
                $sql .=__invoice_query_from();
                $sql .= " WHERE i.inv_id IN ('".$text_checked."') ";
                $datalist =  $db->query($sql." LIMIT 0,1 ")->row();
                $text_checked = trim($datalist["inv_id_list"],"'");
                $invoice_data = __invoice_data($text_checked,"");
                
                $paym_date = (isset($_POST["paym_date"])) ? $_POST["paym_date"]: "";
                
                 $datalist["paym_date"] = date("Y-m-d");
                 $datalist["paym_no"] = "xxxxxxxxx"; 
             }
             
             $paym_typepay = (isset($_POST["paym_typepay"])) ? $_POST["paym_typepay"]: "";
             $paym_note = (isset($_POST["paym_note"])) ? $_POST["paym_note"] : "";
             
            if($paym_typepay=="2"){
                $cheq_no = (isset($_POST["cheq_no"])) ? $_POST["cheq_no"] : "";
                $cheq_date = (isset($_POST["cheq_date"])) ? $_POST["cheq_date"]: "";
                $cheq_bankid = (isset($_POST["cheq_bankid"])) ? $_POST["cheq_bankid"] : "";
                
                 $sql_bank  = "SELECT bank_name FROM bank_tb WHERE bank_id = '" . $cheq_bankid. "'";
                 $row_bank = $db->query($sql_bank)->row();
                 $bank_name =$row_bank["bank_name"];
            }else{
                $cheq_no = "";
                $cheq_date = "";
                $bank_name = "";
            }
               
            $datalist["paym_typepay"] = $paym_typepay;
            $datalist["cheq_no"] = $cheq_no;
            $datalist["cheq_date"] = $cheq_date;
            $datalist["bank_name"] = $bank_name;
            $datalist["paym_note"] = $paym_note; 
             
            $pdf = __pdf_payment($datalist,$invoice_data,"P");
            
            $pdf_preview = $pdf["preview_url"];
            $preview_path = $pdf["preview_path"];
            
            echo json_encode(array('preview_url' => $pdf_preview,'preview_path'=> $preview_path));
         }
    break;
    
     case "save_add" :  case "save_edit" :
        $paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
        $paramurl_department_id = (isset($_GET["dep"])) ?$_GET["dep"] : 0;
         $paym_id = (isset($_POST["paym_id"])) ? $_POST["paym_id"] : "";
         $inv_id = (isset($_POST["inv_id"])) ? $_POST["inv_id"] : array();
         $paym_paydate = (isset($_POST["paym_paydate"])) ? $_POST["paym_paydate"] : "";
         $paym_typepay = (isset($_POST["paym_typepay"])) ? $_POST["paym_typepay"] : "";
         $cheq_no = (isset($_POST["cheq_no"])) ? $_POST["cheq_no"] : "";
         $cheq_no = trim($cheq_no);
         $cheq_date = (isset($_POST["cheq_date"])) ? $_POST["cheq_date"] : "";
         $cheq_bankid = (isset($_POST["cheq_bankid"])) ? $_POST["cheq_bankid"] : "";
         $paym_note = (isset($_POST["paym_note"])) ? $_POST["paym_note"] : "";
         
         $paym_no = "error";
         $cheque_id = 0;
         $cheque_no = $cheq_no;
         
         $ck_id = false;
         $ck_data = false;
         $ck_dep = false;
         $ck_valid =false;
         $ck_valid_typepay2 = false;
         $ck_cheq_no  = false;
         
         if($action=="save_add"){
             $text = "ออกใบสำคัญจ่าย";
            $ck_id = true;
            $ck_data =  (!empty($inv_id)) ? true : false;
             $ck_dep = (!empty(__data_department($paramurl_department_id,"id"))) ? true : false;
         }else{
            $text =  "แก้ไขใบสำคัญจ่าย";
             $ck_id = (!empty($paym_id)) ? true : false;
             $ck_data = true;
             $ck_dep = true;
         }
         
            if($action=="save_edit"){
                $sql_cheq =  "SELECT paym_cheqid,paym_no FROM payment_tb WHERE paym_id = '". $paym_id ."'";
                $row_cheq = $db->query($sql_cheq)->row();
                $paym_cheqid = $row_cheq["paym_cheqid"];
                $paym_no = $row_cheq["paym_no"];
            }else{
                $paym_cheqid = 0;
            }
                                
         if(!empty($paym_typepay)){
             $ck_valid = true; 
             
             if($paym_typepay==2){
                if($cheq_no!="" && __validdate($cheq_date) && $cheq_bankid!=""){
                     $ck_valid_typepay2 = true;
                    
                    $sql_ck_cheq_no =  "SELECT COUNT(cheq_no)  AS count_data FROM cheque_tb WHERE cheq_no = '". $cheq_no ."'";
                    $sql_ck_cheq_no .= (!empty($paym_cheqid)) ? " AND cheq_id  != '".$paym_cheqid."'" : "";
                    
                    $row_ck_cheq_no = $db->query($sql_ck_cheq_no)->row();
                    $count_cheq_no = $row_ck_cheq_no["count_data"];
                   
                    if(empty($count_cheq_no)){
                       $ck_cheq_no = true;
                    }
                 }
             }else{
                 $ck_valid_typepay2 = true;
                 $ck_cheq_no = true;
             }
         }
         if($ck_login){
            if($ck_access){
               if($ck_id){
                     if($ck_data){
                           if($ck_dep){
                               if($ck_valid && $ck_valid_typepay2){
                                   if($ck_cheq_no){
                                        $dep_name = __data_department($paramurl_department_id,"name");
                                        $datetime = date('Y-m-d H:i:s');
                                        $date =  date('Y-m-d');
                                        $month = date("m");
                                        $year = date("Y");
                                        $year_th = $year+543;
                                        $year_th_short = substr($year_th, -2);     
                                        $text_checked_save = "";
                                        
                                               
                                        $con_save_paym = "";
                                        $new_nextstspaym  = "";
                                        $con_save_paym .= "paym_typepay = '". $paym_typepay ."',"; 
                                        $con_save_paym .= ($paym_typepay=="1") ? "paym_cheqid = ''," : "";
                                        $con_save_paym .= "paym_note = '". $paym_note ."',";
                                        
                                       if($action=="save_add"){
                                           $text_checked = implode("','", $inv_id);
                                            $sql = __invoice_query_select();
                                            $sql .= " ,  GROUP_CONCAT(CONCAT('''',i.inv_id, '''' )) AS inv_id_list ";
                                            $sql .=__invoice_query_from();
                                            $sql .= " WHERE i.inv_id IN ('".$text_checked."') ";
                                            $datalist =  $db->query($sql." LIMIT 0,1 ")->row();
                                            $text_checked_save = trim($datalist["inv_id_list"],"'");
                                            
                                            $sql_paym_no = " SELECT
                                                CONCAT('".$dep_name."', '".$year_th_short."', '".$month."',
                                                (CASE WHEN (MAX((SUBSTRING(paym_no,( LENGTH ('".$dep_name."')+ 5 ), 3 ))) >= 1) 
                                                THEN LPAD(MAX(SUBSTRING(paym_no,(LENGTH ('".$dep_name."')+5),3))+1,3,'0') ELSE '001'  END)
                                                ) AS new_id
                                            FROM  payment_tb
                                            WHERE paym_depid = '".$paramurl_department_id."' 
                                            AND paym_month= '". $month ."'
                                            AND paym_year= '". $year_th ."'";
                                            $row_paym_no = $db->query($sql_paym_no)->row();
                                            $new_paym_no = $row_paym_no["new_id"];
                                    
                                            $sql_nextstspaym = "SELECT
                                                CONCAT('".$year_th_short."',
                                                (CASE WHEN ( MAX(( SUBSTRING( inv_NostatusPaym,LENGTH ('".$year_th_short."'), 5 ))) >= 1 ) THEN
                                                  LPAD( MAX( SUBSTRING( inv_NostatusPaym,LENGTH ('".$year_th_short."'), 5 ))+ 1, 5, '0' ) ELSE '00001' END) 
                                                ) AS new_id
                                            FROM invoice_tb WHERE inv_statusPaym = '1'";
                                            $row_nextstspaym =  $db->query($sql_nextstspaym)->row();
                                            $new_nextstspaym = $row_nextstspaym["new_id"];
                                            
                                            $paym_paydate = ($paym_paydate!="") ? $paym_paydate : "0000-00-00";  
                                            $paym_no = $new_paym_no;
                                            $paym_date = $date;
                                            $paym_paydate = $paym_paydate;
                                            $paym_depid = $paramurl_department_id;
                                            $paym_year = $year_th;
                                            $paym_month = $month;
                                        
                                            $con_save_paym .= "paym_no = '". $paym_no ."',";
                                            $con_save_paym .= "paym_date = '". $paym_date ."',";
                                            $con_save_paym .= "paym_paydate = '". $paym_paydate."',";
                                            $con_save_paym .= "paym_depid = '". $paym_depid ."',";
                                            $con_save_paym .= "paym_year = '". $paym_year ."',";
                                            $con_save_paym .= "paym_month = '". $paym_month ."',";
                                            $con_save_paym .= "paym_statusRepid = '0',";
                                            $con_save_paym .= "paym_stsid = 'STS001',";
                                            
                                            $sql_save_paym = "INSERT IGNORE INTO payment_tb SET ".$con_save_paym."  paym_userid_create='".$user_id."',paym_createdate='".$datetime."'";
                                       }else{
                                           $sql_save_paym = "UPDATE payment_tb SET ".$con_save_paym." paym_userid_edit='".$user_id."',paym_editdate='".$datetime."'  WHERE paym_id = '".$paym_id."'";
                                       }
                                        
                                          $query_save_paym = $db->query($sql_save_paym);
                                        
                                         if(!$db->error(2)) {
                                           if($action=="save_add"){
                                               $paym_id = $db->insert_id();
                                               $sql_save_inv = "UPDATE invoice_tb SET inv_paymid = '".$paym_id."', inv_NostatusPaym = '".$new_nextstspaym."' WHERE inv_id IN ('".$text_checked_save."') ";
                                               $query_save_inv = $db->query($sql_save_inv);
                                           }
                                            
                                            if($paym_typepay=="1"){
                                                 $sql_delete_cheq = "DELETE FROM cheque_tb WHERE cheq_id = '".$paym_cheqid."'";
                                                 $query_delete_cheq = $db->query($sql_delete_cheq);
                                            }else if($paym_typepay=="2"){
                                               $con_save_cheq = "";
                                               $con_save_cheq .= "cheq_no = '". $cheq_no ."',";
                                               $con_save_cheq .= "cheq_date = '". $cheq_date ."',";
                                               $con_save_cheq .= "cheq_bankid = '". $cheq_bankid ."',";
                                               $con_save_cheq .= "cheq_year = '". $year_th ."',";
                                               $con_save_cheq .= "cheq_month = '". $month ."',";
                                               
                                                if(!empty($paym_cheqid)){
                                                    $sql_save_cheq = "UPDATE cheque_tb SET ".$con_save_cheq." cheq_userid_edit='".$user_id."',cheq_editdate='".$datetime."'   WHERE cheq_id = '".$paym_cheqid."'";
                                                    $query_save_cheq = $db->query($sql_save_cheq);
                                                    $cheque_id = $paym_cheqid;
                                                }else{
                                                    $con_save_cheq .= " cheq_stsid = 'STS002', ";
                                                    $sql_save_cheq = "INSERT IGNORE INTO cheque_tb SET ".$con_save_cheq."  cheq_userid_create='".$user_id."',cheq_createdate='".$datetime."'";
                                                    $query_save_cheq = $db->query($sql_save_cheq);
                                                  
                                                    $cheq_id = $db->insert_id();
                                                    $sql_update_cheq = "UPDATE payment_tb SET paym_cheqid='".$cheq_id."'  WHERE paym_id = '".$paym_id."'";
                                                    $query_update_cheq = $db->query($sql_update_cheq);
                                                    
                                                    $cheque_id = $cheq_id;
                                                }
                                            }
    
                                              if($action=="save_add"){
                                                if(isset($_SESSION["payment"][$paramurl_company_id][$paramurl_department_id])){
                                                    unset($_SESSION["payment"][$paramurl_company_id][$paramurl_department_id]);
                                                }
                                              }
                                            $response = "S";
                                        }else{
                                            $message = "ไม่สามารถ".$text."ได้ ระบบบันทึกข้อมูลผิดพลาด<br>".$message." (1)";
                                        }
                                   }else{
                                       $message = "เลขที่เช็ค <u>".$cheq_no."</u> ซ้ำ กรุณากรอกใหม่"; 
                                   }
                               }else{
                                    $message = "ไม่สามารถ".$text."ได้ กรุณากรอกข้อมูลให้ครบ";
                               }
                           }else{
                               $message = "ไม่สามารถ".$text."ได้ เนื่องจากไม่พบแผนก";
                           }
                      }else{
                          $message = "ไม่สามารถ".$text."ได้ เนื่องจากไม่มีข้อมูลใบแจ้งหนี้";
                      }
               }else{
                   $message = "ไม่สามารถ".$text."ได้ กรุณาระบุรหัสใบสำคัญจ่าย";
               }
            }else{
                $message = $text_error_access;
            }
        }else{
            $message = $text_error_login;
        } 
         
         if($action=="save_add"){
             $data_log = array("detail" =>$text);
             __log_action("SAVE_PAYMENT_ADD",$response,"",$data_log,"payment_add");
         }else{
              $data_log = array("detail" => $text." เลขที่ ".$paym_no);
             __log_action("SAVE_PAYMENT_EDIT",$response,"",$data_log,"payment_edit");
         }
                
        $json = array('response' =>$response,   'message' => $message, "error"=>$error,"paym_id"=>$paym_id,"paym_no"=>$paym_no, "cheque_id"=>$cheque_id, "cheque_no"=>$cheque_no);
        echo json_encode($json);
     break;
     
     
     case "change_paystatus" :
        $paym_id = (isset($_POST['paym_id'])) ? $_POST['paym_id'] : ""; 
        $paymentStatus = (isset($_POST['paymentStatus'])) ? $_POST['paymentStatus'] : ""; 
        $dateTime = date('Y-m-d H:i:s');

        if($paym_id != "" && $paymentStatus != "")  {
            if($paymentStatus == "paid") {
                $sql = "UPDATE payment_tb SET paym_paystatus = '$paymentStatus', 
                                              paym_updated_paidstatus = '$dateTime',
                                              paym_userid_updatedpaidstatus = '$user_id'
                                          WHERE paym_id = '$paym_id' ";
            }
            else {
                $sql = "UPDATE payment_tb SET paym_paystatus = '$paymentStatus', 
                                              paym_updated_paidstatus = null,
                                              paym_userid_updatedpaidstatus = null
                                          WHERE paym_id = '$paym_id' ";
            }

            $query = $db->query($sql);
            
            $response = [
                'status' => 'success',
                'message' => 'เปลี่ยนสถานะทำจ่ายเสร็จเรียบร้อยแล้ว'
            ];

            echo json_encode($response);
        }
        else {
            $response = [
                'status' => 'error',
                'message' => 'พบข้อผิดพลาดโปรดลองใหม่อีกครั้ง'
            ];
    
            echo json_encode($response);
        }

        $data_log = [
            "paymentID" =>  $paym_id,
            "paymentStatus" => $paymentStatus,
            "updated_at" => $dateTime,
            "user_updated" => $user_id
        ];

        __log_action("PAYMENT_CHANGESTATUS", $response['status'], "-", $data_log, "payment_changestatus");
        
     break;
     
    default;
        echo "no action required";
}
?>


