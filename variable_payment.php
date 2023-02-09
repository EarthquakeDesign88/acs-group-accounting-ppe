<?php
if(!empty($datalist)){
    //payment_tb
    $paym_id = $datalist["paym_id"];
    $paym_no = $datalist["paym_no"];
    $paym_date = $datalist["paym_date"];
    $paym_paydate = $datalist["paym_paydate"];
    $paym_rev = $datalist["paym_rev"];
    $paym_depid = $datalist["paym_depid"];
    $paym_payeename = $datalist["paym_payeename"];
    $paym_payeedate = $datalist["paym_payeedate"];
    $paym_file = $datalist["paym_file"];
    $paym_year = $datalist["paym_year"];
    $paym_month = $datalist["paym_month"];
    $paym_typepay = $datalist["paym_typepay"];
    $paym_cheqid = $datalist["paym_cheqid"];
    $paym_chkRptpaym = $datalist["paym_chkRptpaym"];
    $paym_note = $datalist["paym_note"];
    $paym_taxcid = $datalist["paym_taxcid"];
    $paym_statusTaxcer = $datalist["paym_statusTaxcer"];
    $paym_NostatusTaxcer = $datalist["paym_NostatusTaxcer"];
    $paym_stsid = $datalist["paym_stsid"];
    $paym_statusRepid = $datalist["paym_statusRepid"];

    $paym_payStatus = $datalist["paym_paystatus"];
	$text_payStatus = "";
	$color_payStatus = "";
    if($paym_payStatus == "waiting to pay") {
        $text_payStatus = "รอจ่าย";
        $color_payStatus = "text-warning";
    }
    else  if($paym_payStatus == "paid") {
        $text_payStatus = "จ่ายแล้ว";
        $color_payStatus = "text-success";
    }

    $paym_updated_paidstatus = $datalist["paym_updated_paidstatus"];
    $paym_updated_paidstatus_th = __datetime($paym_updated_paidstatus);
    $paym_userid_updatedpaidstatus = $datalist["paym_userid_updatedpaidstatus"];
    $paym_updatedpaidstatus_fullname = $datalist["paym_updatedpaidstatus_fullname"];

    $paym_reppid = $datalist["paym_reppid"];
    $paym_userid_create = $datalist["paym_userid_create"];
    $paym_createdate = $datalist["paym_createdate"];
    $paym_userid_edit = $datalist["paym_userid_edit"];
    $paym_editdate = $datalist["paym_editdate"];
    
    $paym_date_datethai = __date($paym_date);
    $paym_date_datethai_full = __date($paym_date,"full");
    $paym_date_datethai_slash = __date($paym_date,"slash_short");
    
    //payment create
    $paym_create_firstname = $datalist["paym_create_firstname"];
    $paym_create_surname = $datalist["paym_create_surname"];
    $paym_create_fullname = $datalist["paym_create_fullname"];
    $paym_create_datetimethai = __datetime($paym_createdate);
    $paym_create_datethai = __date($paym_createdate);
    $paym_create_datethai_full = __date($paym_createdate,"full");
    $paym_create_datethai_slash = __date($paym_createdate,"slash_short");
    
    //payment  edit
    $paym_edit_firstname = $datalist["paym_edit_firstname"];
    $paym_edit_surname = $datalist["paym_edit_surname"];
    $paym_edit_fullname = $datalist["paym_edit_fullname"];
    $paym_edit_datetimethai = __datetime($paym_editdate);
    $paym_edit_datethai = __date($paym_editdate);
    $paym_edit_datethai_full = __date($paym_editdate,"full");
    $paym_edit_datethai_slash = __date($paym_editdate,"slash_short");
    
    $sts_id = $datalist["sts_id"];
    $sts_description = $datalist["sts_description"];
    
  
    //payable_tb
    $paya_id = $datalist["paya_id"];
    $paya_name = $datalist["paya_name"];
    $paya_address = $datalist["paya_address"];
    $paya_taxno = $datalist["paya_taxno"];
    $paya_tel = $datalist["paya_tel"];
    $paya_fax = $datalist["paya_fax"];
    $paya_email = $datalist["paya_email"];
    $paya_website = $datalist["paya_website"];
    
    
    //company_tb
    $comp_id = $datalist["comp_id"];
    $comp_name = $datalist["comp_name"];
    $comp_name_short = $datalist["comp_name_short"];
    $comp_address = $datalist["comp_address"];
    $comp_taxno = $datalist["comp_taxno"];
    $comp_tel = $datalist["comp_tel"];
    $comp_fax = $datalist["comp_fax"];
    $comp_email = $datalist["comp_email"];
    $comp_website = $datalist["comp_website"];
    $comp_count_dep = __data_company_count_department($comp_id);
    
    //department_tb
    $dep_id = $datalist["dep_id"];
    $dep_name = $datalist["dep_name"];
    $dep_compid = $datalist["dep_compid"];
    $dep_code = $datalist["dep_code"];
    $dep_mdep = $datalist["dep_mdep"];
    $dep_status = $datalist["dep_status"];
    $dep_name_th = $datalist["dep_name_th"];
    $dep_name_en = $datalist["dep_name_en"];
    $dep_color = $datalist["dep_color"];
    
    //cheque_tb
    $cheq_id = $datalist["cheq_id"];
    $cheq_no = $datalist["cheq_no"];
    $cheq_date = $datalist["cheq_date"];
    $cheq_bankid = $datalist["cheq_bankid"];
    $cheq_file = $datalist["cheq_file"];
    $cheq_year = $datalist["cheq_year"];
    $cheq_month = $datalist["cheq_month"];
    $cheq_userid_create = $datalist["cheq_userid_create"];
    $cheq_createdate = $datalist["cheq_createdate"];
    $cheq_userid_edit = $datalist["cheq_userid_edit"];
    $cheq_editdate = $datalist["cheq_editdate"];
    $cheq_stsid = $datalist["cheq_stsid"];
        
   //bank_tb   
    $bank_id = $datalist["bank_id"];
    $bank_name = $datalist["bank_name"];
    $bank_nameShort = $datalist["bank_nameShort"];
       
       
    $url_edit = "payment_action.php?cid=".$comp_id."&dep=".$dep_id."&paymid=".$paym_id."&action=edit";
    $url_pdf = 'export/payment_pdf.php?paymid='.$paym_id;
    
    $btn_action_edit = '<a class="btn btn-warning" href="'.$url_edit.'" name="edit" id="'. $paym_id .'" title="แก้ไข / Edit"><i class="icofont-edit"></i></a>';
    $btn_action_view = '<button class="btn btn-primary " type="button" name="view" id="'. $paym_id .'" title="ดู / View" data-toggle="modal" data-target="#modalDetailPayment_'.$paym_id.'" ><i class="icofont-search-document"></i></button>';
    $btn_action_changeStatus = '<button class="btn btn-success" type="button" name="change_status" title="เปลี่ยนสถานะ / Change status" data-toggle="modal" data-target="#modalChangeStatus_'.$paym_id.'"><i class="icofont-document-folder"></i></button>';
     
	 
	   //invoice_tb
   
    if(!empty($paym_id)){
    	 $invoice_data = __invoice_data("",$paym_id);
	    $html_td_no = (isset($a)) ? '<td class="text-center">'.__number($a).'</td>' : "";
	    $html_td_id ='<td class="text-center"><div class="truncate-id">'.$paym_no.'</div></td>';
	    $html_td_des ='<td>';
	        $html_td_des .='<div class="truncate-des">';
	        $html_td_des .='<b>บริษัท : </b>'.$invoice_data["text_paya_name_list"].'<br>';
	        $html_td_des .='<b>รายการ : </b>'.$invoice_data["text_description_list"];
	        $html_td_des .='</div>';
	    $html_td_des .='</td>';
	    $html_td_netamount ='<td><input type="text" class="form-control" style="text-align: right;"  value="'.$invoice_data["text_total_netamount"].'" readonly></td>';
	    $html_td_paystatus = '<td class="text-center '.$color_payStatus.'">'.$text_payStatus.'</td>';
	    $html_td_dep = '<td class="text-center">'.$dep_name.'</td>';
	      
	    $html_td = "";
	    $html_td .= $html_td_no;
	    $html_td .= $html_td_id;
	    $html_td .= $html_td_des;
	    $html_td .= $html_td_netamount;
	    $html_td .= $html_td_paystatus;
	    $html_td  .='<td class="text-center">
	                    <div class="btn-group">';
	                        $html_td .= $btn_action_edit;
	                        $html_td .= "&nbsp;";
	                        $html_td .= $btn_action_changeStatus;
	                        $html_td .= "&nbsp;";
	                        $html_td .= $btn_action_view;
	    $html_td  .='   </div>
	                </td>';
	    
	    $html_tr = "";
	    $html_tr .= '<tr data-row="'.$paym_id.'">';
	    $html_tr .= $html_td;
	    $html_tr .= "</tr>";
	    
	  //modal
	  $html_detail = '<div id="modalDetailPayment_'.$paym_id.'" class="modal fade modal-detail" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h3 class="modal-title py-2"><i class="icofont-search-document"></i> รายละเอียดใบสำคัญจ่าย (เลขที่ '.$paym_no.')</h3>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
	            </div>
	            <div class="modal-body" id="detailFormPayment">';  
	              $html_detail .= '<div class="row">';
	                  $html_detail .= '<div class="col-md-12">';
	                    $html_detail .= '<object data="'.$url_pdf.'#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="600" style="border: 1px solid"></object>';
	                 $html_detail .= '</div>';
	              $html_detail .= '</div>';
	             
	                $html_detail .= '<div class="row">';
	                    $html_detail .= '<div class="col-md-12">';
	                         $html_detail .= '<ul class="list-group ">';
	                          $html_detail .= '<li class="list-group-item list-group-item-secondary font-bold">ไฟล์เอกสารใบแจ้งหนี้</li>';
	                      
	                            $sql_file = "SELECT *  FROM invoice_file_tb 
								LEFT JOIN invoice_tb ON invoice_tb.inv_id = invoice_file_tb.invF_invid
								LEFT JOIN payment_tb ON payment_tb.paym_id = invoice_tb.inv_paymid
								WHERE payment_tb.paym_id = ".$paym_id."";
	                            $result_file = $db->query($sql_file)->result();
	                            if(!empty($result_file)){
	                              
	                                foreach ($result_file as $row_file) {
	                                    $html_detail .= '<a class="list-group-item list-group-item-action" href="' . $row_file['invF_filename'] . '" target="_blank">';
	                                    $html_detail .= '<span class="badge badge-primary badge-pill font-sm py-2">';
	                                    $html_detail .= '<i class="icofont-ui-file"></i> คลิกดูที่นี่</span> ' . $row_file['invF_filename'];
	                                    
	                                    
	                                    if($invoice_data["count_invoice"]>=2){
	                                        $html_detail .= ' **(เลขที่ใบแจ้งหนี้ '.$row_file['inv_no'].')';
	                                    }
	                                    
	                                    $html_detail .= '</a>';
	                                }   
	                            }else{
	                                 $html_detail .= '<li class="list-group-item">ไม่มีไฟล์เอกสาร</li>';
	                            }
	                        $html_detail .= '</ul>';
	                     $html_detail .= '</div>';
	                $html_detail .= '</div>';
	            
	              //logs payment
	              $html_detail .= '
	                <div class="col-lg-12 px-1" style="border:2px dashed #d6d8db;margin-top:10px;padding:8px;font-size:13px">
	                <div class="col-md-12">';
	                         $html_detail .= '<h5><b><i class="icofont-ui-clock"></i> ประวัติการทำรายการ (ใบสำคัญจ่าย)</b></h5>';
	                         $html_detail .= '<div class="row mt-2">';
                                $html_detail .= '<div class="col-md-12 media"><b><i class="icofont-ui-add"></i> จัดทำเอกสาร โดย : </b> '.$paym_create_fullname.' &nbsp;&nbsp;<b> วันเวลา : </b> '.$paym_create_datetimethai.'</div>';
                                $html_detail .= '<div class="col-sm-12"><b><i class="icofont-ui-edit"></i> แก้ไขเอกสาร โดย : </b> ';
                                $html_detail .= (!empty($paym_userid_edit)) ? $paym_edit_fullname.' &nbsp;&nbsp;<b> วันเวลา :</b> '.$paym_edit_datetimethai : "-";
                                $html_detail .= '</div>';
                                $html_detail .= '<div class="col-sm-12"><b><i class="icofont-document-folder"></i> ทำจ่ายเอกสาร โดย : </b> ';
                                $html_detail .=  (!empty($paym_userid_updatedpaidstatus)) ? $paym_updatedpaidstatus_fullname . ' &nbsp;&nbsp;<b> วันเวลา :</b> ' . $paym_updated_paidstatus_th : "-";
                                $html_detail .= '</div>';       
	                          $html_detail .= '</div>';
	                 $html_detail .= '
	                     </div>
	                 </div>';
	      $html_detail .= '</div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-dark" data-dismiss="modal" aria-hidden="true"><i class="icofont-close"></i> ปิด</button>
	             </div>
	        </div>
	     </div>
	</div>';
	

	//Change status Modal
	$html_detail .= '<div id="modalChangeStatus_'.$paym_id.'" class="modal fade modal-changestatus" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title py-2 '.$color_payStatus.'"><i class="icofont-document-folder"></i>        
                                <span>'.$text_payStatus.'</span> 
                                ใบสำคัญจ่าย (เลขที่ '.$paym_no.') 
                            </h3> 
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                        </div>
                        <div class="modal-body" style="padding-bottom:75px;">
                            <h4 for="Status" class="form-label mt-2 '.$color_payStatus.'">จำนวนเงิน <span>'.$invoice_data["text_total_netamount"].' บาท</span></h4>
                            <label for="Status" class="form-label">สถานะทำจ่าย</label>
                            <select class="form-select form-control" name="paym_paystatus" id="paym_paystatus'.$paym_id.'">
                                <option value="waiting to pay"'; $html_detail .= $paym_payStatus == "waiting to pay" ? 'selected' : '' ; $html_detail .= '>รอจ่าย</option>
                                <option value="paid"'; $html_detail .= $paym_payStatus == "paid" ? 'selected' : '' ; $html_detail .= '>จ่ายแล้ว</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal" aria-hidden="true"><i class="icofont-close"></i> ปิด</button>
                            <button type="button" class="btn btn-primary" onclick="changePayStatus('.$paym_id.')">บันทึก</button>
                        </div>
                    </div>
                </div>
            </div>';
  	}
}
?>