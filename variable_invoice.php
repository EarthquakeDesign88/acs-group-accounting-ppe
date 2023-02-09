<?php
if(!empty($datalist)){
    $inv_id = $datalist["inv_id"];
    $inv_no = $datalist["inv_no"];
    $inv_type = $datalist["inv_type"];
    $inv_typepcash = $datalist["inv_typepcash"];
    $inv_count = $datalist["inv_count"];
    $inv_date = $datalist["inv_date"];
    $inv_duedate = $datalist["inv_duedate"];
    $inv_compid = $datalist["inv_compid"];
    $inv_payaid = $datalist["inv_payaid"];
    $inv_description = $datalist["inv_description"];
    $inv_description_short = $datalist["inv_description_short"];
    $inv_subtotalNoVat = $datalist["inv_subtotalNoVat"];
    $inv_subtotal = $datalist["inv_subtotal"];
    $inv_vatpercent = $datalist["inv_vatpercent"];
    $inv_vat = $datalist["inv_vat"];
    $inv_differencevat = $datalist["inv_differencevat"];
    $inv_tax1 = $datalist["inv_tax1"];
    $inv_taxpercent1 = $datalist["inv_taxpercent1"];
    $inv_taxtotal1 = $datalist["inv_taxtotal1"];
    $inv_differencetax1 = $datalist["inv_differencetax1"];
    $inv_tax2 = $datalist["inv_tax2"];
    $inv_taxpercent2 = $datalist["inv_taxpercent2"];
    $inv_taxtotal2 = $datalist["inv_taxtotal2"];
    $inv_differencetax2 = $datalist["inv_differencetax2"];
    $inv_tax3 = $datalist["inv_tax3"];
    $inv_taxpercent3 = $datalist["inv_taxpercent3"];
    $inv_taxtotal3 = $datalist["inv_taxtotal3"];
    $inv_differencetax3 = $datalist["inv_differencetax3"];
    $inv_grandtotal = $datalist["inv_grandtotal"];
    $inv_difference = $datalist["inv_difference"];
    $inv_netamount = $datalist["inv_netamount"];
    $inv_balancetotal = $datalist["inv_balancetotal"];
    $inv_rev = $datalist["inv_rev"];
    $inv_salary = $datalist["inv_salary"];
    $inv_taxrefund = $datalist["inv_taxrefund"];
    $inv_userid_create = $datalist["inv_userid_create"];
    $inv_createdate = $datalist["inv_createdate"];
    $inv_userid_edit = $datalist["inv_userid_edit"];
    $inv_editdate = $datalist["inv_editdate"];
    $inv_statusMgr = $datalist["inv_statusMgr"];
    $inv_apprMgrno = $datalist["inv_apprMgrno"];
    $inv_statusMdep = $datalist["inv_statusMdep"];
    $inv_apprMdepno = $datalist["inv_apprMdepno"];
    $inv_statusCEO = $datalist["inv_statusCEO"];
    $inv_apprCEOno = $datalist["inv_apprCEOno"];
    $inv_year = $datalist["inv_year"];
    $inv_month = $datalist["inv_month"];
    $inv_depid = $datalist["inv_depid"];
    $inv_paymid = $datalist["inv_paymid"];
    $inv_statusPaym = $datalist["inv_statusPaym"];
    $inv_NostatusPaym = $datalist["inv_NostatusPaym"];
    $inv_runnumber = $datalist["inv_runnumber"];
    
    //invoice create
    $inv_create_firstname = $datalist["inv_create_firstname"];
    $inv_create_surname = $datalist["inv_create_surname"];
    $inv_create_fullname = $datalist["inv_create_fullname"];
    $inv_create_datetimethai = __datetime($inv_createdate);
    $inv_create_datethai = __date($inv_createdate);
    $inv_create_datethai_full = __date($inv_createdate,"full");
    $inv_create_datethai_slash = __date($inv_createdate,"slash_short");
    
    //invoice  edit
    $inv_edit_firstname = $datalist["inv_edit_firstname"];
    $inv_edit_surname = $datalist["inv_edit_surname"];
    $inv_edit_fullname = $datalist["inv_edit_fullname"];
    $inv_edit_datetimethai = __datetime($inv_editdate);
    $inv_edit_datethai = __date($inv_editdate);
    $inv_edit_datethai_full = __date($inv_editdate,"full");
    $inv_edit_datethai_slash = __date($inv_editdate,"slash_short");
    
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
    
    //status
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
        
    //approvemdep_tb
    $apprMdep_no = $datalist["apprMdep_no"];
    $apprMdep_date = $datalist["apprMdep_date"];
    $apprMdep_year = $datalist["apprMdep_year"];
    $apprMdep_month = $datalist["apprMdep_month"];
    $apprMdep_depid = $datalist["apprMdep_depid"];
    $apprMdep_userid_create = $datalist["apprMdep_userid_create"];
    $apprMdep_datecreate = $datalist["apprMdep_datecreate"];
    $apprMdep_firstname = $datalist["apprMdep_firstname"];
    $apprMdep_surname = $datalist["apprMdep_surname"];
    $apprMdep_fullname = $datalist["apprMdep_fullname"];
    $apprMdep_datetimethai = __datetime($apprMdep_datecreate);
    $apprMdep_datethai = __date($apprMdep_datecreate);
    $apprMdep_datethai_full = __date($apprMdep_datecreate,"full");
    $apprMdep_datethai_slash = __date($apprMdep_datecreate,"slash_short");
    
    //approvemgr_tb
    $apprMgr_no = $datalist["apprMgr_no"];
    $apprMgr_date = $datalist["apprMgr_date"];
    $apprMgr_year = $datalist["apprMgr_year"];
    $apprMgr_month = $datalist["apprMgr_month"];
    $apprMgr_depid = $datalist["apprMgr_depid"];
    $apprMgr_userid_create = $datalist["apprMgr_userid_create"];
    $apprMgr_datecreate = $datalist["apprMgr_datecreate"];
    $apprMgr_firstname = $datalist["apprMgr_firstname"];
    $apprMgr_surname = $datalist["apprMgr_surname"];
    $apprMgr_fullname = $datalist["apprMgr_fullname"];
    $apprMgr_datetimethai = __datetime($apprMgr_datecreate);
    $apprMgr_datethai = __date($apprMgr_datecreate);
    $apprMgr_datethai_full = __date($apprMgr_datecreate,"full");
    $apprMgr_datethai_slash = __date($apprMgr_datecreate,"slash_short");
    
    //approveceo_tb
    $apprCEO_no = $datalist["apprCEO_no"];
    $apprCEO_date = $datalist["apprCEO_date"];
    $apprCEO_year = $datalist["apprCEO_year"];
    $apprCEO_month = $datalist["apprCEO_month"];
    $apprCEO_depid = $datalist["apprCEO_depid"];
    $apprCEO_userid_create = $datalist["apprCEO_userid_create"];
    $apprCEO_datecreate = $datalist["apprCEO_datecreate"];
    $apprCEO_firstname = $datalist["apprCEO_firstname"];
    $apprCEO_surname = $datalist["apprCEO_surname"];
    $apprCEO_fullname = $datalist["apprCEO_fullname"];
    $apprCEO_datetimethai = __datetime($apprCEO_datecreate);
    $apprCEO_datethai = __date($apprCEO_datecreate);
    $apprCEO_datethai_full = __date($apprCEO_datecreate,"full");
    $apprCEO_datethai_slash = __date($apprCEO_datecreate,"slash_short");
    
    $invoice_data = __invoice_data($inv_id);
    
    $ck_duedate = ($inv_duedate <= date('Y-m-d')) ? false : true;
    $text_duedate = "";
    if(!$ck_duedate){
        $text_duedate = '<br><font style="color: #F00; text-align: center; margin-top: 10px; font-size: 15px;font-weight: 700;">เกินกำหนดชำระ</font>';
    }
    
    $btn_action_check = "";
    $class_row = " row-".$inv_id ;
    if(!empty($arrayChecked[$inv_id])){
        $btn_action_check =  (!empty($btn_uncheck)) ? $btn_uncheck : "";
        $class_row .= " row-checked";
    }else{
        $btn_action_check = (!empty($btn_check)) ? $btn_check : "";
        $class_row .= "";
    }
    
   $btn_action_view ='<button type="button" class="btn btn-primary btn-block" title="ดู / View" data-toggle="modal" data-target="#modalDetailInvoice_'.$inv_id.'"><i class="icofont-search-document"></i> View Detail</button>';
   $btn_action_view_short ='<button type="button" class="btn btn-primary btn-block" title="ดู / View" data-toggle="modal" data-target="#modalDetailInvoice_'.$inv_id.'"><i class="icofont-search-document"></i></button>';
    
    $html_td_no = (isset($a)) ? '<td class="text-center">'.__number($a).'</td>' : "";
    $html_td_id ='<td class="text-center"><div class="truncate-id">'.$inv_no.'</div></td>';
    $html_td_des ='<td>';
        $html_td_des .='<div class="truncate-des">';
        $html_td_des .='<b>บริษัท : </b>'.$paya_name.'<br>';
        $html_td_des .='<b>รายการ : </b>'.$inv_description;
        $html_td_des .='</div>';
    $html_td_des .='</td>';
    $html_td_duedate ='<td  class="text-center">'.__date($inv_duedate).$text_duedate.'</td>';
    $html_td_netamount ='<td><input type="text" class="form-control" style="text-align: right;"  value="'.__price($inv_netamount).'" readonly></td>';
    
    $html_td = "";
    $html_td .= $html_td_no;
    $html_td .= $html_td_id;
    $html_td .= $html_td_des;
    $html_td .= $html_td_duedate;
    $html_td .= $html_td_netamount;
    $html_td  .='<td>';
            $html_td .= $btn_action_view;
            $html_td  .='<div class="div-btn mt-1">';
            $html_td .= $btn_action_check;
         $html_td  .='</div>';
    $html_td  .='</td>';
    
    $html_tr = "";
    $html_tr .= '<tr data-row="'.$inv_id.'" class="'.$class_row.'">';
    $html_tr .= $html_td;
    $html_tr .= "</tr>";
    
    $html_tr_nobtn = "";
    $html_tr_nobtn .= '<tr data-row="'.$inv_id.'" class="'.$class_row.'">';
    $html_tr_nobtn .= $html_td_no;
    $html_tr_nobtn .= $html_td_id;
    $html_tr_nobtn .= $html_td_des;
    $html_tr_nobtn .= $html_td_duedate;
    $html_tr_nobtn .= $html_td_netamount;
    $html_tr_nobtn .= "</tr>";
    
    //หน้า ใบสำคัญจ่าย
    $html_payment_td = "";
    $html_payment_td  .='<td>';
     $html_payment_td  .='<div class="div-btn mt-1">';
        $html_payment_td .= $btn_action_check;
     $html_payment_td  .='</div>';
    $html_payment_td  .='</td>';
    $html_payment_td .= $html_td_no;
    $html_payment_td .= $html_td_id;
    $html_payment_td .= $html_td_des;
    $html_payment_td .= $html_td_duedate;
    $html_payment_td .= $html_td_netamount;
    $html_payment_td .= "<td>".$btn_action_view_short."</td>";
    
    $html_payment_tr = "";
    $html_payment_tr .= '<tr data-row="'.$inv_id.'" class="'.$class_row.'">';
    $html_payment_tr .= $html_payment_td;
    $html_payment_tr .= "</tr>";
        
    //modal
    $html_detail = "<style>.col-detail{border-bottom: 1px dashed #333;}</style>";  
    $html_detail .= '<div id="modalDetailInvoice_'.$inv_id.'" class="modal fade modal-detail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title py-2"><i class="icofont-search-document"></i> รายละเอียดใบแจ้งหนี้</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body" id="detailForm">';
        $html_detail .= '<div class="col-md-12 py-4 px-4">
                    <div class="row py-2">
                        <div class="col-md-12 col-lg-8 px-1"></div>
                        <div class="col-md-12 col-lg-2 px-1">
                            <b>Run Number :</b>
                        </div>
                        <div class="col-md-12 col-lg-2 col-detail">
                            ' .$inv_runnumber . '
                        </div>
                    </div>
        
                    <div class="row py-2">
        
                        <div class="col-lg-9 col-md-12 px-1">
                            <div class="row">
                                <div class="col-lg-3 col-md-12">
                                    <b>เลขที่ใบแจ้งหนี้</b>
                                </div>
                                <div class="col-lg-9 col-md-12 col-detail">
                                    ' . $inv_no . '
                                </div>
                            </div>
                        </div>
        
                        <div class="col-lg-3 col-md-3">
                            <div class="row">
                                <div class="col-lg-4 col-md-4">
                                    <b>ฝ่าย</b>
                                </div>
                                <div class="col-lg-8 col-md-8 text-center col-detail">
                                    ' . $dep_name . '
                                </div>
                            </div>
                        </div>
        
                    </div>
        
                    <div class="row py-2">
                        <div class="col-md-12 col-lg-1 px-1">
                            <b>บริษัท</b>
                        </div>
                        <div class="col-md-12 col-lg-11 col-detail">
                            ' . $comp_name . '
                        </div>
                    </div>
        
                    <div class="row py-2">
                        <div class="col-md-12 col-lg-1 px-1">
                            <b>จ่ายให้</b>
                        </div>
                        <div class="col-md-12 col-lg-11 col-detail">
                            ' . $paya_name . '
                        </div>
                    </div>
        
                    <div class="row py-2">
                        <div class="col-md-12 col-lg-1 px-1">
                            <b>ที่อยู่</b>
                        </div>
                        <div class="col-md-12 col-lg-11 col-detail">
                            ' . $paya_address. '
                        </div>
                    </div>
        
                    <div class="row py-2">
                        <div class="col-md-12 col-lg-2 px-1">
                            <b>รายละเอียด</b>
                        </div>
                        <div class="col-md-12 col-lg-10 col-detail">
                            ' . $inv_description .'
                        </div>
                    </div>
        
                    <div class="row py-2">
                        <div class="col-md-12 col-lg-2 px-1">
                            <b>เลขที่ใบแจ้งหนี้</b>
                        </div>
                        <div class="col-md-12 col-lg-10 col-detail">
                            ' . $inv_no .'
                        </div>
                    </div>
        
                    <div class="row py-2">
                        <div class="col-md-6 col-lg-6 row py-2 mr-1">
                            <div class="col-md-12 col-lg-4 px-1">
                                <b>วันที่ใบแจ้งหนี้</b>
                            </div>
                            <div class="col-md-12 col-lg-8 text-center col-detail">
                                '. __date($inv_date,"full") .'
                            </div>
                        </div>
        
                        <div class="col-md-6 col-lg-6 row py-2 ml-1">
                            <div class="col-md-12 col-lg-5 px-1">
                                <b>วันที่กำหนดชำระ</b>
                            </div>
                            <div class="col-md-12 col-lg-7 pr-0 pl-0 text-center col-detail">
                                '. __date($inv_duedate,"full") .'
                            </div>
                        </div>
                    </div>
        
                    <div class="row py-2">
                        <div class="col-lg-1 col-0 px-1"></div>
                        <div class="col-lg-4 col-4 px-1 text-right total">
                            <b>จำนวนเงินก่อน VAT (ไม่มี VAT) : </b>
                        </div>
                        <div class="col-lg-6 col-7 px-1 text-right col-detail">
                            <span>
                                ' .$invoice_data["text_subtotal_novat"] .'
                            </span>
                        </div>
                        <div class="col-lg-1 col-1 px-1 text-center">
                            <b>บาท</b>
                        </div>
                    </div>
        
                    <div class="row py-2">
                        <div class="col-lg-2 col-0 px-1"></div>
                        <div class="col-lg-3 col-4 px-1 text-right total">
                            <b>จำนวนเงินก่อน VAT : </b>
                        </div>
                        <div class="col-lg-6 col-7 px-1 text-right col-detail">
                            <span>
                            ' . $invoice_data["text_subtotal"] .'
                            </span>
                        </div>
                        <div class="col-lg-1 col-1 px-1 text-center">
                            <b>บาท</b>
                        </div>
                    </div>
        
                    <div class="row py-2">
                        <div class="col-lg-2 col-0 px-1"></div>
                        <div class="col-lg-3 col-4 px-1 text-right total">
                            <b>ภาษีมูลค่าเพิ่ม : </b>
                        </div>
                        <div class="col-lg-6 col-7 text-right col-detail">
                            <div class="row">
                            <div class="col-lg-2 col-2 px-1 text-left">
                                    <span>
                                    </span>
                                </div>
                                
                                <div class="col-lg-5 col-2 px-1 text-right">
                                    <span>
                                        ' . $invoice_data["text_vatpercent"] .'
                                    </span>
                                </div>
        
                                <div class="col-lg-5 col-5 px-1 text-right">
                                    <span>
                                        ' . $invoice_data["text_vat"] .'
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-1 px-1 text-center">
                            <b>บาท</b>
                        </div>
                    </div>
        
                    <div class="row py-2">
                        <div class="col-lg-2 col-0 px-1"></div>
                        <div class="col-lg-3 col-4 px-1 text-right total">
                            <b>หักภาษี ณ ที่จ่าย : </b>
                        </div>
                        <div class="col-lg-6 col-7 text-right" style="border-bottom: 1px dashed #333;">';
                $html_detail .= '<div class="row">
                                <div class="col-lg-2 col-2 px-1 text-left">
                                    <span>
                                        ' . $invoice_data["text_taxpercent_1"].'
                                    </span>
                                </div>
        
                                <div class="col-lg-5 col-5 px-1 text-right">
                                    <span>
                                        ' .$invoice_data["text_totaltax_1_cal"] .' 
                                    </span>
                                </div>
        
                                <div class="col-lg-5 col-5 px-1 text-right">
                                    <span>
                                        ' .$invoice_data["text_totaltax_1"] .'
                                    </span>
                                </div>
                            </div>';
            $html_detail .= '</div>
                        <div class="col-lg-1 col-1 px-1 text-center">
                            <b>บาท</b>
                        </div>
                    </div>
        
                    <div class="row py-2">
                        <div class="col-lg-2 col-0 px-1"></div>
                        <div class="col-lg-3 col-4 px-1 text-right total">
                            <b></b>
                        </div>
                        <div class="col-lg-6 col-7 text-right" style="border-bottom: 1px dashed #333;">';
                $html_detail .= '<div class="row">
                                <div class="col-lg-2 col-2 px-1 text-left">
                                    <span>
                                        ' .$invoice_data["text_taxpercent_2"] .'
                                    </span>
                                </div>
        
                                <div class="col-lg-5 col-5 px-1 text-right">
                                    <span>
                                        ' . $invoice_data["text_totaltax_2_cal"] .'
                                    </span>
                                </div>
        
                                <div class="col-lg-5 col-5 px-1 text-right">
                                    <span>
                                        ' .$invoice_data["text_totaltax_2"].'
                                    </span>
                                </div>
                            </div>';
            $html_detail .= '</div>
                        <div class="col-lg-1 col-1 px-1 text-center">
                            <b>บาท</b>
                        </div>
                    </div>
        
                    <div class="row py-2">
                        <div class="col-lg-2 col-0 px-1"></div>
                        <div class="col-lg-3 col-4 px-1 text-right total">
                            <b></b>
                        </div>
                        <div class="col-lg-6 col-7 text-right" style="border-bottom: 1px dashed #333;">';
                $html_detail .= '<div class="row">
                                <div class="col-lg-2 col-2 px-1 text-left">
                                    <span>
                                        ' .$invoice_data["text_taxpercent_3"] .'
                                    </span>
                                </div>
        
                                <div class="col-lg-5 col-5 px-1 text-right">
                                    <span>
                                      ' .$invoice_data["text_totaltax_3_cal"].'
                                    </span>
                                </div>
        
                                <div class="col-lg-5 col-5 px-1 text-right">
                                    <span>
                                        ' .$invoice_data["text_totaltax_3"].'
                                    </span>
                                </div>
                            </div>';
            $html_detail .= '</div>
                        <div class="col-lg-1 col-1 px-1 text-center">
                            <b>บาท</b>
                        </div>
                    </div>
                    
                    
                    <div class="row py-2">
                        <div class="col-lg-1 col-0 px-1"></div>
                        <div class="col-lg-4 col-4 px-1 text-right total">
                            <b>ส่วนต่าง + / -</b>
                        </div>
                        <div class="col-lg-6 col-7 px-1 text-right col-detail">
                            <span>
                                ' . $invoice_data["text_difference"] .' 
                            </span>
                        </div>
                        <div class="col-lg-1 col-1 px-1 text-center">
                            <b>บาท</b>
                        </div>
                    </div>
                    
                    
                    <div class="row py-2">
                        <div class="col-lg-2 col-0 px-1"></div>
                        <div class="col-lg-3 col-4 px-1 text-right total">
                            <b>ยอดชำระสุทธิ</b>
                        </div>
                        <div class="col-lg-6 col-7 text-right" style="border-bottom: 1px dashed #333;">
                            <div class="row">
                                <div class="col-lg-2 col-2 px-1 text-left">
                                        <span>
                                        </span>
                                    </div>
                                    
                                <div class="col-lg-5 col-2 px-1 text-right">
                                    <span>
                                        ' .$invoice_data["text_total_netamount_cal"].'
                                    </span>
                                </div>
        
                                 <div class="col-lg-5 col-5 px-1 text-right">
                                    <span>
                                        ' . $invoice_data["text_total_netamount"] .' 
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-1 px-1 text-center">
                            <b>บาท</b>
                        </div>
                    </div> ';
        
                       
                    $html_detail .= '<div class="row mt-4">';
                       $html_detail .= '<div class="col-md-12">';
                            $html_detail .= '<ul class="list-group">';
                                $html_detail .= '<li class="list-group-item list-group-item-secondary font-bold">ไฟล์เอกสารใบแจ้งหนี้</li>';
                                $sql_file = "SELECT * FROM invoice_file_tb WHERE invF_invid = ".$inv_id."";
                                $result_file = $db->query($sql_file)->result();
                                $arrFile = array();
                                if(!empty($result_file)){
                                    foreach ($result_file as $row_file) {
                                        $html_detail .= '<a class="list-group-item list-group-item-action" href="' . $row_file['invF_filename'] . '" target="_blank">
                                             <span class="badge badge-primary badge-pill font-sm py-2"><i class="icofont-ui-file"></i> คลิกดูที่นี่</span> ' . $row_file['invF_filename'] . '
                                        </a>';
                                    }   
                                }else{
                                     $html_detail .= '<li class="list-group-item">ไม่มีไฟล์เอกสาร</li>';
                                }
                    
                        $html_detail .= '</ul>';
                     $html_detail .= '</div>';
                $html_detail .= '</div>';
                    
                    //logs
                      $html_detail .= '<div class="row py-2">
                      <div class="col-md-12">
                        <div class="col-lg-12 px-1" style="border:2px dashed #d6d8db;margin-top:10px;padding:8px;font-size:13px">
                        <div class="col-md-12">';
                                
                                $html_detail .= '<h5><b><i class="icofont-ui-clock"></i> ประวัติการทำรายการ</b></h5>';
                                
                                $html_detail .= '<div class="row mt-2 mb-3">';
                                    $html_detail .= '<div class="col-md-12"><b><i class="icofont-ui-add"></i> จัดทำเอกสาร โดย: </b> '.$inv_create_fullname.' &nbsp;&nbsp;<b> วันเวลา :</b> '.$inv_create_datetimethai.'</div>';
                                    
                                   $html_detail .= '<div class="col-sm-12"><b><i class="icofont-ui-edit"></i> แก้ไขเอกสาร โดย : </b> ';
                                   $html_detail .= (!empty($inv_userid_edit)) ? $inv_edit_fullname.' &nbsp;&nbsp;<b> วันเวลา :</b> '.$inv_edit_datetimethai: "-";
                                   $html_detail .= '</div>';
                                    
                                   $html_detail .= '<div class="col-sm-12"><b><i class="icofont-checked"></i> ฝ่ายอนุมัติ โดย : </b> ';
                                   $html_detail .= (!empty($apprMdep_userid_create)) ? $apprMdep_fullname.' &nbsp;&nbsp;<b> วันเวลา :</b> '.$apprMdep_datetimethai : "-";
                                   $html_detail .= '</div>';
                                   
                                   $html_detail .= '<div class="col-sm-12"><b><i class="icofont-checked"></i> ตรวจจ่าย โดย : </b> ';
                                   $html_detail .= (!empty($apprMgr_userid_create)) ? $apprMgr_fullname.' &nbsp;&nbsp;<b> วันเวลา :</b> '.$apprMgr_datetimethai : "-";
                                   $html_detail .= '</div>';
                                   
                                   $html_detail .= '<div class="col-sm-12"><b><i class="icofont-checked"></i> อนุมัติ โดย : </b> ';
                                   $html_detail .= (!empty($apprCEO_userid_create)) ? $apprCEO_fullname.' &nbsp;&nbsp;<b> วันเวลา :</b> '.$apprCEO_datetimethai : "-";
                                   $html_detail .= '</div>';
                              $html_detail .= '</div>';
    
                         $html_detail .= '
                                    </div>
                             </div>
                         </div>
                    </div>';
                    //end logs
                 $html_detail .= '</div>';
      $html_detail .= '</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal" aria-hidden="true"><i class="icofont-close"></i> ปิด</button>
             </div>
        </div>
     </div>
</div>';          
                 
}
?>