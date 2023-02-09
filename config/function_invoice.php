<?php
if (!function_exists('__invoice_data')) {
    function __invoice_data($text="",$paymid=0){
         $db = new database();
         
        $arrData = array();
        
         $arrDescription = array();
         
        $arr_inv_vatpercent = array();
        $arr_inv_create = array();
        $arr_inv_createdate_max =  array();
		 
        $arr_paya_name = array();
         
        $text_description_list = "";
        $text_description_short_list = "";
        $text_inv_no_list = "";
        $text_inv_id_list = "";
        $text_create_list = "";
        $text_paya_name_list = "";
        
         $count_invoice = 0;//จำนวนรหัสใบแจ้งหนี้
         $cal_inv_subtotal = 0;
         
         $inv_vatpercent = 0;
         $inv_taxpercent1 =0;
         $inv_taxpercent2 = 0;
         $inv_taxpercent3 = 0;
         
         $sum_inv_subtotalNoVat = 0;
         $sum_inv_subtotal = 0;
         $sum_inv_vat = 0;
         $sum_inv_tax_all = 0;     
         $sum_inv_tax1 = 0;
         $sum_inv_tax2 = 0;
         $sum_inv_tax3 = 0;
         $sum_inv_taxtotal_all = 0;
         $sum_inv_taxtotal1 = 0;
         $sum_inv_taxtotal2 = 0;
         $sum_inv_taxtotal3 = 0;
         $sum_inv_grandtotal = 0;;
         $sum_inv_difference = 0;
         $sum_inv_netamount = 0;
         $sum_inv_count = 0;
            
       	$inv_id_main = 0;
        if($text!="" || !empty($paymid)){
            $sql = __invoice_query_select();
            $sql .= __invoice_query_from();
            
            if($text!=""){
                $sql .= " WHERE i.inv_id IN ('".$text."') ";
            }else{
                $sql .= " WHERE i.inv_paymid = '".$paymid."'";
            }
            
           $result =  $db->query($sql)->result();
            $n_invoice = 1;
            
            if(count($result)>=1){
                foreach ($result as $row) {
                    $arrDescription[$row["inv_id"]] = $row;
                    
                    $text_description_list .= $row["inv_description"] . " || ";
                    $text_description_short_list .= $row["inv_description_short"] . " || ";
                    $text_inv_id_list .= "&invid =" . $row["inv_id"];
                    $text_inv_no_list .= $row["inv_no"] . " || ";
                    $text_create = $row["inv_create_fullname"] ." (". __date($row["inv_createdate"],"slash_short").")";
                    
                    $arr_inv_create[$row["inv_userid_create"]] = $text_create;
					$arr_inv_createdate_max[strtotime($row["inv_createdate"])] = $row["inv_id"];
					
                    $arr_paya_name[$row["paya_id"]] = $row["paya_name"];
                    
                    $sum_inv_subtotalNoVat += $row["inv_subtotalNoVat"];
                     $sum_inv_subtotal += $row["inv_subtotal"];
                     $sum_inv_vat += $row["inv_vat"];
                     $sum_inv_tax_all += $row["inv_tax1"];
                     $sum_inv_tax_all += $row["inv_tax2"];
                     $sum_inv_tax_all += $row["inv_tax3"];
                     $sum_inv_tax1 += $row["inv_tax1"];
                     $sum_inv_tax2 += $row["inv_tax2"];
                     $sum_inv_tax3 += $row["inv_tax3"];
                     $sum_inv_taxtotal_all += $row["inv_taxtotal1"];
                     $sum_inv_taxtotal_all += $row["inv_taxtotal2"];
                     $sum_inv_taxtotal_all += $row["inv_taxtotal3"];
                     $sum_inv_taxtotal1 += $row["inv_taxtotal1"];
                     $sum_inv_taxtotal2 += $row["inv_taxtotal2"];
                     $sum_inv_taxtotal3 += $row["inv_taxtotal3"];
                     $sum_inv_grandtotal += $row["inv_grandtotal"];
                     $sum_inv_difference += $row["inv_difference"];
                     $sum_inv_netamount += $row["inv_netamount"];
                     $sum_inv_count += ((is_numeric($row["inv_count"]) && $row["inv_count"] != 0)) ?$row["inv_count"] : 0 ;
                     
                     $arr_inv_vatpercent[$row["inv_vatpercent"]] = $row["inv_vatpercent"];
                     
                     if($n_invoice==1){
                         $inv_taxpercent1 = $row["inv_taxpercent1"];
                         $inv_taxpercent2 = $row["inv_taxpercent2"];
                         $inv_taxpercent3 = $row["inv_taxpercent3"];
                     }
                     
                     $count_invoice += 1;
                     $n_invoice ++;
                }

                $inv_vatpercent = max($arr_inv_vatpercent);
                 $cal_inv_subtotal = ($sum_inv_subtotalNoVat + $sum_inv_subtotal);
            }
        }
        
        $text_description_list = trim($text_description_list," || ");
        $text_description_short_list = trim($text_description_short_list," || ");
        $text_inv_no_list = trim($text_inv_no_list," || ");
		
		$max_inv_createdate_max = max($arr_inv_createdate_max);
		$inv_id_main = $max_inv_createdate_max;
		if(count($arr_inv_create)==1){
	        $text_create_list = 	$arrDescription[$inv_id_main]["inv_create_fullname"] ." (". __date($arrDescription[$inv_id_main]["inv_createdate"],"slash_short").")";
		}else{
	        $text_create_list = implode(" || ", $arr_inv_create);
	        $text_create_list = trim($text_create_list," || ");
		}
       
        $text_paya_name_list = implode(" || ", $arr_paya_name);
        $text_paya_name_list = trim($text_paya_name_list," || ");
        
        $text_total_inv_grandtotal = __price($sum_inv_grandtotal);
        $text_total_inv_difference = __price($sum_inv_difference);
        
        $text_total_inv_subtotal_novat = __price($sum_inv_subtotalNoVat);
        
        //จำนวนเงิน
        $text_cal_inv_subtotal = "";
        if ((is_numeric($sum_inv_subtotalNoVat) && $sum_inv_subtotalNoVat != 0)  && (is_numeric($sum_inv_subtotal) && $sum_inv_subtotal != 0)) {
             $text_cal_inv_subtotal .= __price($sum_inv_subtotalNoVat);           
             $text_cal_inv_subtotal .= '&nbsp;+&nbsp;';
             $text_cal_inv_subtotal .= __price($sum_inv_subtotal);         
             $text_cal_inv_subtotal .= '&nbsp;&nbsp;=';  
         }
        $text_total_inv_subtotal = __price($cal_inv_subtotal);
        
       //ภาษีมูลค่าเพิ่ม 
       $text_total_inv_vat = ((is_numeric($sum_inv_vat) && $sum_inv_vat != 0)) ? __price($sum_inv_vat) : "";
       
       //หักภาษี ณ ที่จ่าย
       $text_inv_taxpercent1 = "";
       $text_inv_taxpercent2 = "";
       $text_inv_taxpercent3 = "";
       
        $text_totaltax_1_cal = "";
        $text_totaltax_2_cal = "";
        $text_totaltax_3_cal = "";
        if($count_invoice==1){
             $text_inv_taxpercent1 = (is_numeric($inv_taxpercent1) && $inv_taxpercent1 != 0) ? __number($inv_taxpercent1)."%" : "";
             $text_inv_taxpercent2 = (is_numeric($inv_taxpercent2) && $inv_taxpercent2 != 0) ? __number($inv_taxpercent2)."%" : "";
             $text_inv_taxpercent3 = (is_numeric($inv_taxpercent3) && $inv_taxpercent3 != 0) ? __number($inv_taxpercent3)."%" : "";
             
            $text_totaltax_1_cal .= (is_numeric($inv_taxpercent1) && $inv_taxpercent1 != 0) ? __price($sum_inv_tax1)."&nbsp;*&nbsp;".__number($inv_taxpercent1)."%" : "";
            $text_totaltax_2_cal .= (is_numeric($inv_taxpercent2) && $inv_taxpercent2 != 0) ? __price($sum_inv_tax2)."&nbsp;*&nbsp;".__number($inv_taxpercent2)."%" : "";
            $text_totaltax_3_cal .= (is_numeric($inv_taxpercent3) && $inv_taxpercent3 != 0) ? __price($sum_inv_tax3)."&nbsp;*&nbsp;".__number($inv_taxpercent3)."%" : "";
        }

        $text_total_inv_tax_all = ((is_numeric($sum_inv_taxtotal_all) && $sum_inv_taxtotal_all != 0)) ? __price($sum_inv_taxtotal_all) : "";
        $text_total_inv_tax1 = ((is_numeric($sum_inv_taxtotal1) && $sum_inv_taxtotal1 != 0)) ? __price($sum_inv_taxtotal1) : "";
        $text_total_inv_tax2 = ((is_numeric($sum_inv_taxtotal2) && $sum_inv_taxtotal2 != 0)) ? __price($sum_inv_taxtotal2) : "";
        $text_total_inv_tax3 = ((is_numeric($sum_inv_taxtotal3) && $sum_inv_taxtotal3 != 0)) ? __price($sum_inv_taxtotal3) : "";
        
        //ยอดชำระสุทธิ
        $text_cal_inv_netamount = (is_numeric($sum_inv_difference) && $sum_inv_difference != 0) ? __price($sum_inv_grandtotal)."&nbsp;+&nbsp;".__price($sum_inv_difference)." = ": "";
        $text_total_inv_netamount = __price($sum_inv_netamount);
        $text_bath_thai = __bahtthai($sum_inv_netamount);//บาทไทย
       
        $text_inv_vatpercent = (is_numeric($inv_vatpercent) && $inv_vatpercent != 0) ?  __number($inv_vatpercent)."%" : "";   
           
        $text_inv_count = __number($sum_inv_count);
           
         $arrData = array(
            "arrDescription" => $arrDescription,
            "count_invoice" => $count_invoice,
            
			"inv_id_main" => $inv_id_main,
            "text_id_list" => $text_inv_id_list,
            "text_no_list" => $text_inv_no_list,
            
            "text_paya_name_list" => $text_paya_name_list,
            
            "text_description_list" => $text_description_list,
            "text_description_short_list" => $text_description_short_list,
            
            "text_count" => $text_inv_count,
            
            "text_subtotal_novat" => $text_total_inv_subtotal_novat,
            
             "text_subtotal" => $text_total_inv_subtotal,
             "text_subtotal_cal" => $text_cal_inv_subtotal,
             
            "text_grandtotal" => $text_total_inv_grandtotal,
            "text_difference" => $text_total_inv_difference,
             
             "text_vatpercent" => $text_inv_vatpercent,
             "text_vat" => $text_total_inv_vat,
             
             "text_taxpercent_1" => $text_inv_taxpercent1,
             "text_taxpercent_2" => $text_inv_taxpercent2,
             "text_taxpercent_3" => $text_inv_taxpercent3,
          
             "text_totaltax_1_cal" => $text_totaltax_1_cal,
             "text_totaltax_2_cal" => $text_totaltax_2_cal,
             "text_totaltax_3_cal" =>$text_totaltax_3_cal,
          
            "text_totaltax_all" => $text_total_inv_tax_all,
            "text_totaltax_1" => $text_total_inv_tax1,
            "text_totaltax_2" => $text_total_inv_tax2,
            "text_totaltax_3" => $text_total_inv_tax3,
            
            "text_total_netamount_cal" => $text_cal_inv_netamount,
            "text_total_netamount" => $text_total_inv_netamount,
            
            "bath_thai" => $text_bath_thai,
            
            "text_create_list" => $text_create_list,
       );
            
        return $arrData;
    }
}



if (!function_exists('__invoice_payment_sign')) {
    function __invoice_payment_sign($inv_id=0){
        $db = new database();
        
        $arrData = array();
        $sql = __invoice_query_select();
        $sql .= __invoice_query_from();
        $sql .= " WHERE i.inv_id = '".$inv_id."' ";
        $row =  $db->query($sql)->row();
        
        if(!empty($row["inv_id"])){
             $inv_userid_create = $row["inv_userid_create"];
            $inv_createdate = $row["inv_createdate"];
            $inv_create_fullname = (!empty($inv_userid_create))  ? $row["inv_create_fullname"] : "&nbsp;";
            $inv_create_date = __date($inv_createdate,"slash_short");
            
            $apprMdep_userid_create = $row["apprMdep_userid_create"];
            $apprMdep_datecreate = $row["apprMdep_datecreate"];    
            $apprMdep_fullname = (!empty($apprMdep_userid_create))  ? $row["apprMdep_fullname"] : "&nbsp;";
            $apprMdep_date = __date($apprMdep_datecreate,"slash_short");
            
            $apprMgr_userid_create = $row["apprMgr_userid_create"];
            $apprMgr_datecreate = $row["apprMgr_datecreate"];    
            $apprMgr_fullname = (!empty($apprMgr_userid_create))  ? $row["apprMgr_fullname"] : "&nbsp;";
            $apprMgr_date = __date($apprMgr_datecreate,"slash_short");
            
            $apprCEO_userid_create = $row["apprCEO_userid_create"];
            $apprCEO_datecreate = $row["apprCEO_datecreate"];    
            $apprCEO_fullname = (!empty($apprCEO_userid_create))  ? $row["apprCEO_fullname"] : "&nbsp;";
            $apprCEO_date = __date($apprCEO_datecreate,"slash_short");
            
        }else{
            $inv_create_fullname = "&nbsp;";
            $inv_create_date ="&nbsp;";
            
            $apprMdep_fullname = "&nbsp;";
            $apprMdep_date = "&nbsp;";
            
            $apprMgr_fullname ="&nbsp;";
            $apprMgr_date = "&nbsp;";
            
            $apprCEO_fullname = "&nbsp;";
            $apprCEO_date = "&nbsp;";
        }
        
       $arrData = array(
            /*"create" => array("position"=>"ผู้จัดทำ","name"=>$inv_create_fullname,"date"=>$inv_create_date,"position_w"=>52),*/
            "mdep" => array("position"=>"ฝ่ายอนุมัติ","name"=> $apprMdep_fullname,"date"=>$apprMdep_date,"position_w"=>80),
            "mgr" => array("position"=>"ผู้ตรวจจ่าย","name"=>$apprMgr_fullname,"date"=>$apprMgr_date,"position_w"=>83),
            "ceo" => array("position"=>"ผู้อนุมัติ","name"=>$apprCEO_fullname,"date"=>$apprCEO_date,"position_w"=>65),
            "payee" => array("position"=>"ผู้รับเงิน","name"=>"&nbsp;","date"=>"&nbsp;","position_w"=> 66),
       );
       
       return $arrData;
    }
}

if (!function_exists('__invoice_query_select')) {
    function __invoice_query_select(){
        return  "SELECT i .*,pm.*,p.*,c.*,d.*,cheq.*,bank.*,s.*,mdep.*,mgr.*,ceo.*,
        u_appr_create.user_firstname AS inv_create_firstname,
        u_appr_create.user_surname AS inv_create_surname,
        CONCAT( u_appr_create.user_firstname,'&nbsp;&nbsp;',u_appr_create.user_surname) AS inv_create_fullname,
        
        u_appr_edit.user_firstname AS inv_edit_firstname,
        u_appr_edit.user_surname AS inv_edit_surname,
        CONCAT(u_appr_edit.user_firstname,'&nbsp;&nbsp;',u_appr_edit.user_surname) AS inv_edit_fullname, 
        
        u_appr_mgr.user_firstname AS apprMgr_firstname,
        u_appr_mgr.user_surname AS apprMgr_surname,
        CONCAT(u_appr_mgr.user_firstname,'&nbsp;&nbsp;',u_appr_mgr.user_surname) AS apprMgr_fullname ,
        
       u_appr_mdep.user_firstname AS apprMdep_firstname,
       u_appr_mdep.user_surname AS apprMdep_surname,
       CONCAT(u_appr_mdep.user_firstname,'&nbsp;&nbsp;',u_appr_mdep.user_surname) AS apprMdep_fullname,
       
       u_appr_ceo.user_firstname AS apprCEO_firstname,
       u_appr_ceo.user_surname AS apprCEO_surname,
       CONCAT(u_appr_ceo.user_firstname,'&nbsp;&nbsp;',u_appr_ceo.user_surname) AS apprCEO_fullname,
       
       u_paym_create.user_firstname AS paym_create_firstname,
       u_paym_create.user_surname AS paym_create_surname,
       CONCAT(u_paym_create.user_firstname,'&nbsp;&nbsp;',u_paym_create.user_surname) AS paym_create_fullname,
       
       u_paym_edit.user_firstname AS paym_edit_firstname,
       u_paym_edit.user_surname AS paym_edit_surname,
       CONCAT(u_paym_edit.user_firstname,'&nbsp;&nbsp;',u_paym_edit.user_surname) AS paym_edit_fullname,

       u_paym_updatedpaidstatus.user_firstname AS paym_updatedpaidstatus_firstname,
       u_paym_updatedpaidstatus.user_surname AS paym_updatedpaidstatus_surname,
       CONCAT(u_paym_updatedpaidstatus.user_firstname,'&nbsp;&nbsp;',u_paym_updatedpaidstatus.user_surname) AS paym_updatedpaidstatus_fullname
       ";
    
    }
}


if (!function_exists('__invoice_query_from')) {
    function __invoice_query_from(){
        $user_id = __session_user("id");
        
        return  " FROM invoice_tb i 
        LEFT JOIN payment_tb pm ON i.inv_paymid = pm.paym_id  AND i.inv_paymid  =''
        LEFT JOIN payable_tb p ON i.inv_payaid = p.paya_id 
        LEFT JOIN company_tb c ON i.inv_compid = c.comp_id
        LEFT JOIN department_tb d ON i.inv_depid = d.dep_id
       
        LEFT JOIN cheque_tb cheq ON  cheq.cheq_id = pm.paym_cheqid 
        LEFT JOIN bank_tb bank ON bank.bank_id = cheq.cheq_bankid
        LEFT JOIN status_tb s ON s.sts_id = pm.paym_stsid
        
        LEFT JOIN approvemdep_tb mdep ON mdep.apprMdep_no = i.inv_apprMdepno
        LEFT JOIN approvemgr_tb mgr ON mgr.apprMgr_no =  i.inv_apprMgrno
        LEFT JOIN approveceo_tb ceo ON ceo.apprCEO_no = i.inv_apprCEOno
        
        LEFT JOIN user_tb u ON u.user_id = '".$user_id."' 
        LEFT JOIN user_tb u_appr_create ON u_appr_create.user_id = i.inv_userid_create
        LEFT JOIN user_tb u_appr_edit ON u_appr_edit.user_id = i.inv_userid_edit 
        
        LEFT JOIN user_tb u_paym_create ON u_paym_create.user_id = pm.paym_userid_create
        LEFT JOIN user_tb u_paym_edit ON u_paym_edit.user_id = pm.paym_userid_edit 
        LEFT JOIN user_tb u_paym_updatedpaidstatus ON u_paym_updatedpaidstatus.user_id = pm.paym_userid_updatedpaidstatus
        
        LEFT JOIN user_tb u_appr_mgr  ON u_appr_mgr.user_id = mgr.apprMgr_userid_create
        LEFT JOIN user_tb u_appr_mdep ON u_appr_mdep.user_id = mdep.apprMdep_userid_create
        LEFT JOIN user_tb u_appr_ceo ON u_appr_ceo.user_id = ceo.apprCEO_userid_create
        ";
    }
}

if (!function_exists('__invoice_payment_query_from')) {
    function __invoice_payment_query_from(){
        $user_id = __session_user("id");
        
        return  " FROM  payment_tb pm
        LEFT JOIN  invoice_tb i ON pm.paym_id  = i.inv_paymid  AND i.inv_paymid <> ''
        LEFT JOIN payable_tb p ON i.inv_payaid = p.paya_id 
        LEFT JOIN company_tb c ON i.inv_compid = c.comp_id
        LEFT JOIN department_tb d ON i.inv_depid = d.dep_id
       
        LEFT JOIN cheque_tb cheq ON cheq.cheq_id = pm.paym_cheqid 
        LEFT JOIN bank_tb bank ON bank.bank_id  = cheq.cheq_bankid
        LEFT JOIN status_tb s ON s.sts_id = pm.paym_stsid
        
        LEFT JOIN approvemdep_tb mdep ON mdep.apprMdep_no = i.inv_apprMdepno
        LEFT JOIN approvemgr_tb mgr ON mgr.apprMgr_no =  i.inv_apprMgrno
        LEFT JOIN approveceo_tb ceo ON ceo.apprCEO_no = i.inv_apprCEOno
        
        LEFT JOIN user_tb u ON u.user_id = '".$user_id."' 
        LEFT JOIN user_tb u_appr_create ON u_appr_create.user_id = i.inv_userid_create
        LEFT JOIN user_tb u_appr_edit ON u_appr_edit.user_id = i.inv_userid_edit 
        
        LEFT JOIN user_tb u_paym_create ON u_paym_create.user_id = pm.paym_userid_create
        LEFT JOIN user_tb u_paym_edit ON u_paym_edit.user_id = pm.paym_userid_edit 
        LEFT JOIN user_tb u_paym_updatedpaidstatus ON u_paym_updatedpaidstatus.user_id = pm.paym_userid_updatedpaidstatus
        
        LEFT JOIN user_tb u_appr_mgr  ON u_appr_mgr.user_id = mgr.apprMgr_userid_create
        LEFT JOIN user_tb u_appr_mdep ON u_appr_mdep.user_id = mdep.apprMdep_userid_create
        LEFT JOIN user_tb u_appr_ceo ON u_appr_ceo.user_id = ceo.apprCEO_userid_create ";
    }
}


if (!function_exists('__invoice_step_list')) {
    function __invoice_step_list(){
        $array = array(
            "invoice"=> array("name"=>"ใบแจ้งหนี้","class_box"=>"info","icon"=>'<i class="icofont-paper"></i>'),
                
            "checkinvoice_mgr_nodep"=> array("name"=>"รอตรวจจ่าย ","class_box"=>"warning","icon"=>'<i class="icofont-notepad"></i>'),//มีแผนกทั้งหมด
            "checkinvoice_ceo_nodep"=> array("name"=>"รอ CEO อนุมัติ ","class_box"=>"warning","icon"=>'<i class="icofont-notepad"></i>'),//มีแผนกทั้งหมด 
            
            "checkinvoice_mdep_multidep_ismdep"=> array("name"=>"รอฝ่ายอนุมัติ (หัวหน้าฝ่าย)","class_box"=>"info","icon"=>'<i class="icofont-notepad"></i>'),//มีแผนก+มีหัวหน้าแผนก
            "checkinvoice_mgr_multidep_ismdep"=> array("name"=>"รอตรวจจ่าย (หัวหน้าฝ่าย)","class_box"=>"info","icon"=>'<i class="icofont-notepad"></i>'),//มีแผนก+มีหัวหน้าแผนก
            
            "checkinvoice_mdepmgr_multidep_nomdep"=> array("name"=>"รอตรวจจ่าย","class_box"=>"warning","icon"=>'<i class="icofont-notepad"></i>'),//มีแผนก+ไม่มีหัวหน้า
            
            "checkinvoice_ceo_multidep"=> array("name"=>"รอ CEO อนุมัติ","class_box"=>"warning","icon"=>'<i class="icofont-notepad"></i>'),//มีแผนกทั้งหมด
            
             "payment"=> array("name"=>"กรรมการอนุมัติจ่าย","class_box"=>"success","icon"=>'<i class="icofont-checked"></i>'),
             
             "duedate_isceo"=> array("name"=>"เกินกำหนดชำระ","class_box"=>"danger","icon"=>'<i class="icofont-calendar"></i>'),
        );
        
        
        
        $mdep_status0 = "(i.inv_statusMdep = 0 AND i.inv_apprMdepno = '')";
        $mdep_status1 = "(i.inv_statusMdep = 1 AND i.inv_apprMdepno <>'')";
        
        $mgr_status0 = "(i.inv_statusMgr = 0 AND i.inv_apprMgrno = '')";
        $mgr_status1 = "(i.inv_statusMgr = 1 AND i.inv_apprMgrno <> '')";
        
        $ceo_status0 = "(i.inv_statusCEO = 0 AND i.inv_apprCEOno = '')";
        $ceo_status1 = "(i.inv_statusCEO = 1 AND i.inv_apprCEOno <> '')";
        
        $duedate = " i.inv_duedate < CURDATE() ";
        
        //$query_invoice = " (".$mdep_status0." AND ".$mgr_status0." AND ".$ceo_status0.") ";
        $query_invoice = " (i.inv_statusMgr = 0) ";
        
        $query_checkinvoice_mgr_nodep = " (".$mgr_status0.") ";
        $query_checkinvoice_mgr_nodep_noaction = $query_checkinvoice_mgr_nodep;
        $query_checkinvoice_ceo_nodep = " (".$mgr_status1." AND ".$ceo_status0." ) ";
        
        $query_checkinvoice_mdep_multidep_ismdep = " (".$mdep_status0." AND ".$mgr_status0." AND (dep_mdep<>'' OR  dep_mdep IS NOT NULL) AND (IF(u.user_levid = '1', d.dep_mdep, u.user_id)= d.dep_mdep))  ";
        $query_checkinvoice_mdep_multidep_ismdep_noaction = $query_checkinvoice_mdep_multidep_ismdep;
        
        $query_checkinvoice_mgr_multidep_ismdep = " (".$mdep_status1." AND ".$mgr_status0."  AND (dep_mdep<>'' OR  dep_mdep IS NOT NULL) AND  (IF(u.user_levid = '1', d.dep_mdep, u.user_id)= d.dep_mdep))";
        $query_checkinvoice_mgr_multidep_ismdep_noaction = $query_checkinvoice_mgr_multidep_ismdep;
        
        //$query_checkinvoice_mdepmgr_multidep_nomdep = " (".$mdep_status0." AND ".$mgr_status0." AND  (d.dep_mdep = '' OR d.dep_mdep IS NULL)) ";
        $query_checkinvoice_mdepmgr_multidep_nomdep = " (".$mgr_status0." AND  (d.dep_mdep = '' OR d.dep_mdep IS NULL)) ";
        $query_checkinvoice_mdepmgr_multidep_nomdep_noaction = $query_checkinvoice_mdepmgr_multidep_nomdep;
        
        $query_checkinvoice_ceo_multidep =  " (".$mdep_status1." AND ".$mgr_status1." AND ".$ceo_status0." ) ";
        $query_payment=  "((".$mgr_status1.") AND inv_paymid = '' AND p.paya_id<>'') ";
        $query_duedate = " ((".$mdep_status0." OR ".$mgr_status0." OR ".$ceo_status0.") AND ".$duedate.") ";
        
        $array["invoice"]["query_where"] =  $query_invoice; 
        $array["checkinvoice_mgr_nodep"]["query_where"] = $query_checkinvoice_mgr_nodep;    
        $array["checkinvoice_ceo_nodep"]["query_where"] = $query_checkinvoice_ceo_nodep;    
        $array["checkinvoice_mdep_multidep_ismdep"]["query_where"] = $query_checkinvoice_mdep_multidep_ismdep;  
        $array["checkinvoice_mgr_multidep_ismdep"]["query_where"] = $query_checkinvoice_mgr_multidep_ismdep;    
        $array["checkinvoice_mdepmgr_multidep_nomdep"]["query_where"] =  $query_checkinvoice_mdepmgr_multidep_nomdep;   
        $array["checkinvoice_ceo_multidep"]["query_where"] =  $query_checkinvoice_ceo_multidep; 
        $array["payment"]["query_where"] =  $query_payment; 
        $array["duedate_isceo"]["query_where"] = $query_duedate;    
        
        //set show index page
        $array["invoice"]["showindex"] =  false;    
        $array["checkinvoice_mgr_nodep"]["showindex"] = true;   
        $array["checkinvoice_ceo_nodep"]["showindex"] = true;   
        $array["checkinvoice_mdep_multidep_ismdep"]["showindex"] = true;    
        $array["checkinvoice_mgr_multidep_ismdep"]["showindex"] = true; 
        $array["checkinvoice_mdepmgr_multidep_nomdep"]["showindex"] =  true;    
        $array["checkinvoice_ceo_multidep"]["showindex"] =  true;   
        $array["payment"]["showindex"] =  false;    
        $array["duedate_isceo"]["showindex"] = true;    
        
        
        //getdep
        $array["invoice"]["getdep"] =  false;   
        $array["checkinvoice_mgr_nodep"]["getdep"] = "";    
        $array["checkinvoice_mgr_nodep_noaction"]["getdep"] = "";   
        $array["checkinvoice_ceo_nodep"]["getdep"] = "";
        $array["checkinvoice_mdep_multidep_ismdep"]["getdep"] ="is_mdep";
        $array["checkinvoice_mgr_multidep_ismdep"]["getdep"] = "is_mdep";
        $array["checkinvoice_mdepmgr_multidep_nomdep"]["getdep"] =   "no_mdep";
        $array["checkinvoice_ceo_multidep"]["getdep"] =  "";    
        $array["payment"]["getdep"] =  "";  
        $array["duedate_isceo"]["getdep"] = ""; 
        
        
        //set page
        $array["invoice"]["page"] =  "invoice.php";
        $array["checkinvoice_mgr_nodep"]["page"] = "checkinvoice.php?step=mgr_nodep";
        $array["checkinvoice_ceo_nodep"]["page"] =  "checkinvoice.php?step=ceo_nodep";
        $array["checkinvoice_mdep_multidep_ismdep"]["page"] =  "checkinvoice.php?step=mdep_multidep_ismdep";
        $array["checkinvoice_mgr_multidep_ismdep"]["page"] =  "checkinvoice.php?step=mgr_multidep_ismdep";
        $array["checkinvoice_mdepmgr_multidep_nomdep"]["page"] =   "checkinvoice.php?step=mdepmgr_multidep_nomdep";
        $array["checkinvoice_ceo_multidep"]["page"] = "checkinvoice.php?step=ceo_multidep"; 
        $array["payment"]["page"] = "payment.php";
        $array["duedate_isceo"]["page"] = "duedate.php";    
        
        /*replace*/
        $array["checkinvoice_mgr_nodep_noaction"] = $array["checkinvoice_mgr_nodep"];
        $array["checkinvoice_mdep_multidep_ismdep_noaction"] = $array["checkinvoice_mdep_multidep_ismdep"];
        $array["checkinvoice_mgr_multidep_ismdep_noaction"] = $array["checkinvoice_mgr_multidep_ismdep"];
        $array["checkinvoice_mdepmgr_multidep_nomdep_noaction"] = $array["checkinvoice_mdepmgr_multidep_nomdep"];
        $array["duedate_notceo"] = $array["duedate_isceo"];
        
        $array["checkinvoice_mgr_nodep_noaction"]["page"] = "javascript:void(0);";  
        $array["checkinvoice_mdep_multidep_ismdep_noaction"]["page"] = "javascript:void(0);";
        $array["checkinvoice_mgr_multidep_ismdep_noaction"]["page"] = "javascript:void(0);";
        $array["checkinvoice_mdepmgr_multidep_nomdep_noaction"]["page"] = "javascript:void(0);";
        $array["duedate_notceo"]["page"] = "javascript:void(0);";
        
        /*appove*/
        $array["checkinvoice_mgr_nodep"]["db_appove1"] =  array("tb"=>"approvemgr_tb","col"=>"apprMgr_","code"=>"APMgr-","col_inv_no"=>"i.inv_apprMgrno","col_inv_status"=>"i.inv_statusMgr");  
        $array["checkinvoice_ceo_nodep"]["db_appove1"] =  array("tb"=>"approveceo_tb","col"=>"apprCEO_","code"=>"APCEO-","col_inv_no"=>"i.inv_apprCEOno","col_inv_status"=>"i.inv_statusCEO");  
        $array["checkinvoice_mdep_multidep_ismdep"]["db_appove1"] = array("tb"=>"approvemdep_tb","col"=>"apprMdep_","code"=>"APMdep-","col_inv_no"=>"i.inv_apprMdepno","col_inv_status"=>"i.inv_statusMdep");   
        $array["checkinvoice_mgr_multidep_ismdep"]["db_appove1"] = array("tb"=>"approvemgr_tb","col"=>"apprMgr_","code"=>"APMgr-","col_inv_no"=>"i.inv_apprMgrno","col_inv_status"=>"i.inv_statusMgr"); 
        $array["checkinvoice_mdepmgr_multidep_nomdep"]["db_appove1"] = array("tb"=>"approvemgr_tb","col"=>"apprMgr_","code"=>"APMgr-","col_inv_no"=>"i.inv_apprMgrno","col_inv_status"=>"i.inv_statusMgr"); 
        //$array["checkinvoice_mdepmgr_multidep_nomdep"]["db_appove2"] =  array("tb"=>"approvemdep_tb","col"=>"apprMdep_","code"=>"APMdep-","col_inv_no"=>"i.inv_apprMdepno","col_inv_status"=>"i.inv_statusMdep"); 
        $array["checkinvoice_ceo_multidep"]["db_appove1"] =  array("tb"=>"approveceo_tb","col"=>"apprCEO_","code"=>"APCEO-","col_inv_no"=>"i.inv_apprCEOno","col_inv_status"=>"i.inv_statusCEO");   
        
        
        return $array;
    }
}
if (!function_exists('__invoice_step_company_list')) {
    function __invoice_step_company_list($user_id=0,$paramurl_company_id=0,$paramurl_department_id=0,$ck_showindex=false){
        $db = new database();
        
        $user_level_id = __data_user($user_id,"level_id");
        $count_dep = __data_company_count_department($paramurl_company_id);
        $mdep = __data_department($paramurl_department_id,"mdep");
        
        //จำนวน mdep ที่มี user_id
        $sql_count_ismdep = "SELECT COUNT(dep_id)  AS count FROM department_tb WHERE dep_compid = '" . $paramurl_company_id . "' AND dep_status=1 AND (dep_mdep<>'' OR  dep_mdep IS NOT NULL) ";
        $sql_count_ismdep .= (!empty($paramurl_department_id)) ? " AND dep_id = '" . $paramurl_department_id . "' " : "" ;
        $row_count_ismdep = $db->query($sql_count_ismdep)->row();
        $count_ismdep = $row_count_ismdep["count"];
        
        //จำนวน mdep ที่ไม่มี user_id
        $sql_count_nomdep = "SELECT COUNT(dep_id)  AS count FROM department_tb WHERE dep_compid = '" . $paramurl_company_id . "' AND dep_status=1 AND (dep_mdep = '' OR dep_mdep IS NULL) ";
        $sql_count_nomdep .= (!empty($paramurl_department_id)) ? " AND dep_id = '" . $paramurl_department_id . "' " : "" ;
        $row_count_nomdep = $db->query($sql_count_nomdep)->row();
        $count_nomdep = $row_count_nomdep["count"];
        
        //จำนวน mdep ที่ตรงกับ user_id
        $sql_count_usermdep = "SELECT COUNT(dep_id)  AS count FROM department_tb WHERE dep_compid = '" . $paramurl_company_id . "' AND dep_status=1 AND dep_mdep= '".$user_id."' ";
        $sql_count_usermdep .= (!empty($paramurl_department_id)) ? " AND dep_id = '" . $paramurl_department_id . "' " : "" ;
        $row_count_usermdep = $db->query($sql_count_usermdep)->row();
        $count_usermdep = $row_count_usermdep["count"];
        
        
        $array = array();
        if($count_dep==1){
            array_push($array,"invoice");
            ($user_level_id==1) ? array_push($array,"checkinvoice_mgr_nodep") : NULL;
            ($user_level_id==2) ? array_push($array,"checkinvoice_ceo_nodep") : NULL;
            if($user_level_id==5 || $user_level_id==6 || $user_level_id==7){
                 array_push($array,"checkinvoice_mgr_nodep_noaction");
            }
            
            array_push($array,"payment");
            ($user_level_id==2) ? array_push($array,"duedate_isceo") : NULL;
            ($user_level_id!=2) ? array_push($array,"duedate_notceo") : NULL;
        }else{
            array_push($array,"invoice");
            if($user_level_id==1){
                ($count_ismdep>=1) ? array_push($array,"checkinvoice_mdep_multidep_ismdep") : NULL;
                ($count_ismdep>=1) ? array_push($array,"checkinvoice_mgr_multidep_ismdep") : NULL;
                ($count_nomdep>=1) ? array_push($array,"checkinvoice_mdepmgr_multidep_nomdep") : NULL;
            }else {
                if(!empty($paramurl_department_id)){
                    if($mdep==$user_id){
                        array_push($array,"checkinvoice_mdep_multidep_ismdep");
                    }else{
                        //array_push($array,"checkinvoice_mdepmgr_multidep_nomdep_noaction");
                    }
                }else{
                    if($count_usermdep>=1){
                        array_push($array,"checkinvoice_mdep_multidep_ismdep");
                    }else{
                        //array_push($array,"checkinvoice_mdepmgr_multidep_nomdep_noaction");
                    }
                }
                
            }
            
            
            ($user_level_id==2) ? array_push($array,"checkinvoice_ceo_nodep") : NULL;
            array_push($array,"payment");
            ($user_level_id==2) ? array_push($array,"duedate_isceo") : NULL;
            ($user_level_id!=2) ? array_push($array,"duedate_notceo") : NULL;
        }
        
        $array_flip = array_flip($array);
        
        //print_r($array_flip);
        $step_list_all= __invoice_step_list();
        $list_new = array();    
        if(!empty($array_flip)){
            foreach ($array_flip as $key => $value) {
                
                if(!empty($step_list_all[$key])){
                    if($ck_showindex==true){
                        if(!empty($step_list_all[$key]["showindex"])){
                            $list_new[$key] = $step_list_all[$key];
                        }
                    }else{
                        $list_new[$key] = $step_list_all[$key];
                    }
                }
            }
        }   
        
        
        $invoice_query_new = "invoice";
        if(!empty($list_new["invoice"])){
            if($count_dep==1){
                if($user_level_id==1){
                    $invoice_query_new = "checkinvoice_mgr_nodep";
                }else if($user_level_id==2){
                    $invoice_query_new = "checkinvoice_ceo_nodep";
                }else{
                    $invoice_query_new = "checkinvoice_mgr_nodep_noaction";
                }
            }else{
                if($user_level_id==1){
                    $invoice_query_new = "invoice";
                }else if($user_level_id==2){    
                    $invoice_query_new = "checkinvoice_ceo_nodep";
                }else {
                    if($mdep==$user_id){
                        $invoice_query_new = "checkinvoice_mdep_multidep_ismdep";
                    }else{
                        $invoice_query_new = "checkinvoice_mdepmgr_multidep_nomdep";
                    }
                }
            }
            
            
            
            $list_new["invoice"]["query_where"] = $step_list_all[$invoice_query_new]["query_where"];
            $list_new["invoice"]["showindex"] = false;
        }
        
        
        return $list_new;
    }
}
if (!function_exists('__invoice_filterby_list')) {
    function __invoice_filterby_list(){
        $array = array(
            "1" => array("name"=>"ลำดับที่","db_column"=>"i.inv_id"),
            "2" =>array("name"=>"เลขที่ใบแจ้งหนี้","db_column"=>"i.inv_no"),
            "3" => array("name"=>"วันที่ครบชำระ","db_column"=>"i.inv_duedate"),
        );
        
        return $array;
    }
}

if (!function_exists('__invoice_payment_filterby_list')) {
    function __invoice_payment_filterby_list(){
        $array = array(
            "1" => array("name"=>"เลขที่ใบสำคัญจ่าย","db_column"=>"pm.paym_no"),
        );
        
        return $array;
    }
}

if (!function_exists('__invoice_filterval_list')) {
    function __invoice_filterval_list(){
        $array = array(
            "1" => array("name"=>"มากไปน้อย","db_orderby"=>"DESC"),
            "2" => array("name"=>"น้อยไปมาก","db_orderby"=>"ASC"),
        );
        
        return $array;
    }
}
if (!function_exists('__invoice_searchby_list')) {
    function __invoice_searchby_list(){
        $array = array(
            "1" => array("name"=>"เลขที่ใบแจ้งหนี้","db_column"=>"i.inv_no"),
            "2" => array("name"=>"ชื่อบริษัทผู้ให้บริการ","db_column"=>"p.paya_name"),
            "3" => array("name"=>"Run Number","db_column"=>"i.inv_runnumber"),
        );
        
        return $array;
    }
}

if (!function_exists('__invoice_payment_searchby_list')) {
    function __invoice_payment_searchby_list(){
        $array = array(
            "1" => array("name"=>"เลขที่ใบสำคัญจ่าย","db_column"=>"pm.paym_no"),
            "2" => array("name"=>"ชื่อบริษัทผู้ให้บริการ","db_column"=>"p.paya_name"),
        );
        
        return $array;
    }
}

if (!function_exists('__invoice_data_checked')) {
    function __invoice_data_checked($step="",$company_id="",$department_id=""){
        $db = new database();
        
        $invoice_step_all = __invoice_step_list();
        $step_key = "checkinvoice_".$step;
    
        $count = 0;
        $amount = 0;
        $arrayCheckedNew= array();
        if(!empty($invoice_step_all[$step_key])){
            $array_step = $invoice_step_all[$step_key];
            $step_query_where = $array_step["query_where"];
                
            if(isset($_SESSION["checkinvoice"][$step][$company_id][$department_id])){
                $arrayChecked = $_SESSION["checkinvoice"][$step][$company_id][$department_id];
                $text_checked = implode("','",$arrayChecked);
                
                $sql_from = __invoice_query_from();
                $sql_where = " WHERE  ".$step_query_where ." AND  i.inv_compid = '". $company_id ."' AND  i.inv_depid = '". $department_id."'";
                
                $sql = " SELECT GROUP_CONCAT(i.inv_id) AS inv_id_list, count(inv_id) AS count, SUM(inv_netamount) AS amount";
                $sql .= $sql_from;
                $sql .= $sql_where;
                $sql .= " AND i.inv_id IN ('".$text_checked."')";
                
                $row = $db->query($sql)->row();
                
                $count+=$row["count"];
                $amount+=$row["amount"];
                
                if(!empty($row["inv_id_list"])){
                    $v = explode(',', $row["inv_id_list"]);
                    foreach ($v as $key => $value) {
                         $arrayCheckedNew[$value] = $value;
                    }
                }
            }
        }
        return array("count"=>$count,"amount"=>$amount,"arrayChecked"=>$arrayCheckedNew);
    }
}

if (!function_exists('__invoice_payment_data_checked')) {
    function __invoice_payment_data_checked($company_id="",$department_id=""){
        $db = new database();
        
        $count = 0;
        $amount = 0;
        $arrayCheckedNew= array();
        if(isset($_SESSION["payment"][$company_id][$department_id])){
            $arrayChecked = $_SESSION["payment"][$company_id][$department_id];
            $text_checked = implode("','",$arrayChecked);
            
            $sql_from = __invoice_query_from();
            $sql_where = " WHERE  ((i.inv_statusMgr = 1 AND i.inv_apprMgrno <> '') AND inv_paymid = '' AND p.paya_id<>'')  AND  i.inv_compid = '". $company_id ."' AND  i.inv_depid = '". $department_id."'";
            $sql = " SELECT GROUP_CONCAT(i.inv_id) AS inv_id_list, count(inv_id) AS count, SUM(inv_netamount) AS amount";
            $sql .= $sql_from;
            $sql .= $sql_where;
            $sql .= " AND i.inv_id IN ('".$text_checked."')";
            $row = $db->query($sql)->row();
            $count+=$row["count"];
            $amount+=$row["amount"];
            
            if(!empty($row["inv_id_list"])){
                $v = explode(',', $row["inv_id_list"]);
                foreach ($v as $key => $value) {
                     $arrayCheckedNew[$value] = $value;
                }
            }
            
        }
        
       
        
        return array("count"=>$count,"amount"=>$amount,"arrayChecked"=>$arrayCheckedNew);
    }
}

?>