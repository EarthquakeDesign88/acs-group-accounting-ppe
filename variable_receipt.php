<?php
if(!empty($datalist)){
    $receipt_useridCreate = isset($datalist['useridCreate']) ? $datalist['useridCreate'] : (isset($datalist['re_userid_create']) ? $datalist['re_userid_create'] : '');
    $receipt_CreateDate = isset($datalist['CreateDate']) ? $datalist['CreateDate'] : "";
    $receipt_useridEdit = isset($datalist['useridEdit']) ? $datalist['useridEdit'] : "";
    $receipt_EditDate = isset($datalist['EditDate']) ? $datalist['EditDate'] : "";
    $receipt_projid = isset($datalist['projid']) ? $datalist['projid'] : "";
    $receipt_invrcpt_balancetotal = isset($datalist['invrcpt_balancetotal']) ? $datalist['invrcpt_balancetotal'] : "";
    $receipt_BackVal = isset($datalist['BackVal']) ? $datalist['BackVal'] : "";
    $receipt_Reno = isset($datalist['re_no']) ? $datalist['re_no'] : "xxxxxxxx";
    
    $receipt_Bookno = isset($datalist['re_bookno']) ? $datalist['re_bookno'] : "xx";
    
    $receipt_Redate = isset($datalist['Redate']) ? $datalist['Redate'] : (isset($datalist['re_date']) ? $datalist['re_date'] : '');
    $vatpercentHidden = isset($datalist['vatpercentHidden']) ? $datalist['vatpercentHidden'] : (isset($datalist['re_vatpercent']) ? $datalist['re_vatpercent'] : '');
    $receipt_SelReDate = isset($datalist['SelReDate']) ? $datalist['SelReDate'] : "";
    $receipt_SelReYear = isset($datalist['SelReYear']) ? $datalist['SelReYear'] : "";
    $receipt_SelYear = isset($datalist['SelYear']) ? $datalist['SelYear'] : "";
    $receipt_SelMonth = isset($datalist['SelMonth']) ? $datalist['SelMonth'] : "";
    $receipt_depname = isset($datalist['depname']) ? $datalist['depname'] : "";
    $receipt_depid = isset($datalist['depid']) ? $datalist['depid'] : "";
    $receipt_datePay = isset($datalist['datePay']) ? $datalist['datePay'] : (isset($datalist['re_datepay']) ? $datalist['re_datepay'] : '');
    $receipt_searchCompany = isset($datalist['searchCompany']) ? $datalist['searchCompany'] : "";
    $receipt_compid = isset($datalist['compid']) ? $datalist['compid'] : "";
    $receipt_searchCustomer = isset($datalist['searchCustomer']) ? $datalist['searchCustomer'] : "";
    $receipt_custid = isset($datalist['custid']) ? $datalist['custid'] : "";
    $receipt_outputTax = isset($datalist['outputTax']) ? $datalist['outputTax'] : "";
    $receipt_invNo = isset($datalist['invNo']) ? $datalist['invNo'] : (empty($datalist['invrcpt_book']) ? $datalist['invrcpt_no'] : $datalist['invrcpt_book'] . '/' .$datalist['invrcpt_no']);
    $receipt_irID = isset($datalist['irID']) ? $datalist['irID'] : "";
    $receipt_chequeNo = isset($datalist['re_chequeno']) ? $datalist['re_chequeno'] : (isset($datalist['chequeNo']) ? $datalist['chequeNo'] : '');
    $receipt_brcval = isset($datalist['brcval']) ? $datalist['brcval'] : "";
    $receipt_chequeDate = isset($datalist['chequeDate']) ? $datalist['chequeDate'] : "";
    $receipt_ReNote = isset($datalist['ReNote']) ? $datalist['ReNote'] : "";
    $receipt_vatHidden = isset($datalist['vatHidden']) ? $datalist['vatHidden'] : (isset($datalist['re_vat']) ? $datalist['re_vat'] : '');
    $receipt_subtotalHidden = isset($datalist['subtotalHidden']) ? $datalist['subtotalHidden'] : (isset($datalist['re_subtotal']) ? $datalist['re_subtotal'] : '');
    $receipt_grandtotalHidden = isset($datalist['grandtotalHidden']) ? $datalist['grandtotalHidden'] : (isset($datalist['re_grandtotal']) ? $datalist['re_grandtotal'] : '');
    $receipt_DiffVatHidden = isset($datalist['DiffVatHidden']) ? $datalist['DiffVatHidden'] : "";
    $receipt_Selpay = isset($datalist['bySelPay']) ? $datalist['bySelPay'] : (isset($datalist['re_typepay']) ? $datalist['re_typepay'] : '');
    $receipt_description1 = isset($datalist['invredesc1']) ? $datalist['invredesc1'] : (isset($datalist['re_description1']) ? $datalist['re_description1'] : '');
    $receipt_description2 = isset($datalist['invredesc2']) ? $datalist['invredesc2'] : (isset($datalist['re_description2']) ? $datalist['re_description2'] : '');
    $receipt_description3 = isset($datalist['invredesc3']) ? $datalist['invredesc3'] : (isset($datalist['re_description3']) ? $datalist['re_description3'] : '');   
    $receipt_description4 = isset($datalist['invredesc4']) ? $datalist['invredesc4'] : (isset($datalist['re_description4']) ? $datalist['re_description4'] : '');
    $receipt_description5 = isset($datalist['invredesc5']) ? $datalist['invredesc5'] : (isset($datalist['re_description5']) ? $datalist['re_description5'] : '');
    $receipt_description6 = isset($datalist['invredesc6']) ? $datalist['invredesc6'] : (isset($datalist['re_description6']) ? $datalist['re_description6'] : '');
    $receipt_description7 = isset($datalist['invredesc7']) ? $datalist['invredesc7'] : (isset($datalist['re_description7']) ? $datalist['re_description7'] : '');
    $receipt_description8 = isset($datalist['invredesc8']) ? $datalist['invredesc8'] : (isset($datalist['re_description8']) ? $datalist['re_description8'] : '');
    
    $receipt_sub_description1 = isset($datalist['invresubdescHidden1']) ? $datalist['invresubdescHidden1'] :(isset($datalist['re_sub_description1']) ? $datalist['re_sub_description1'] : '');
    $receipt_sub_description2 = isset($datalist['invresubdescHidden2']) ? $datalist['invresubdescHidden2'] :(isset($datalist['re_sub_description2']) ? $datalist['re_sub_description2'] : '');
    $receipt_sub_description3 = isset($datalist['invresubdescHidden3']) ? $datalist['invresubdescHidden3'] :(isset($datalist['re_sub_description3']) ? $datalist['re_sub_description3'] : '');
    $receipt_sub_description4 = isset($datalist['invresubdescHidden4']) ? $datalist['invresubdescHidden4'] :(isset($datalist['re_sub_description4']) ? $datalist['re_sub_description4'] : '');
    $receipt_sub_description5 = isset($datalist['invresubdescHidden5']) ? $datalist['invresubdescHidden5'] :(isset($datalist['re_sub_description5']) ? $datalist['re_sub_description5'] : '');
    $receipt_sub_description6 = isset($datalist['invresubdescHidden6']) ? $datalist['invresubdescHidden6'] :(isset($datalist['re_sub_description6']) ? $datalist['re_sub_description6'] : ''); 
    $receipt_sub_description7 = isset($datalist['invresubdescHidden7']) ? $datalist['invresubdescHidden7'] :(isset($datalist['re_sub_description7']) ? $datalist['re_sub_description7'] : '');
    $receipt_sub_description8 = isset($datalist['invresubdescHidden8']) ? $datalist['invresubdescHidden8'] :(isset($datalist['re_sub_description8']) ? $datalist['re_sub_description8'] : '');
    $receipt_sub_description9 = isset($datalist['invresubdescHidden9']) ? $datalist['invresubdescHidden9'] :(isset($datalist['re_sub_description9']) ? $datalist['re_sub_description9'] : '');
    
    $receipt_amount1 = isset($datalist['amountHidden1']) ? __price($datalist['amountHidden1']) :(isset($datalist['re_amount1']) ? __price($datalist['re_amount1']) : '');
    $receipt_amount2 = isset($datalist['amountHidden2']) ? __price($datalist['amountHidden2']) :(isset($datalist['re_amount2']) ? __price($datalist['re_amount2']) : '');
    $receipt_amount3 = isset($datalist['amountHidden3']) ? __price($datalist['amountHidden3']) :(isset($datalist['re_amount3']) ? __price($datalist['re_amount3']) : '');
    $receipt_amount4 = isset($datalist['amountHidden4']) ? __price($datalist['amountHidden4']) :(isset($datalist['re_amount4']) ? __price($datalist['re_amount4']) : '');
    $receipt_amount5 = isset($datalist['amountHidden5']) ? __price($datalist['amountHidden5']) :(isset($datalist['re_amount5']) ? __price($datalist['re_amount5']) : '');
    $receipt_amount6 = isset($datalist['amountHidden6']) ? __price($datalist['amountHidden6']):(isset($datalist['re_amount6']) ? __price($datalist['re_amount6']) : '');
    $receipt_amount7 = isset($datalist['amountHidden7']) ? __price($datalist['amountHidden7']) :(isset($datalist['re_amount7']) ? __price($datalist['re_amount7']) : '');
    $receipt_amount8 = isset($datalist['amountHidden8']) ? __price($datalist['amountHidden8']) :(isset($datalist['re_amount8']) ? __price($datalist['re_amount8']) : '');
    
    $comp_id = $datalist["comp_id"];
    $comp_name = $datalist["comp_name"];
    $comp_name_short = $datalist["comp_name_short"];
    $comp_address = $datalist["comp_address"];
    $comp_taxno = $datalist["comp_taxno"];
    $comp_tel = $datalist["comp_tel"];
    $comp_fax = $datalist["comp_fax"];
    $comp_email = $datalist["comp_email"];
    $comp_website = $datalist["comp_website"];
        
    $dep_id = $datalist["dep_id"];
    $dep_name = $datalist["dep_name"];
    $dep_compid = $datalist["dep_compid"];
    $dep_code = $datalist["dep_code"];
    $dep_mdep = $datalist["dep_mdep"];
    $dep_status = $datalist["dep_status"];
    $dep_name_th = $datalist["dep_name_th"];
    $dep_name_en = $datalist["dep_name_en"];
    $dep_color = $datalist["dep_color"];
    
    $cust_id = $datalist["cust_id"];
    $cust_name = $datalist["cust_name"];
    $cust_address = $datalist["cust_address"];
    $cust_taxno = $datalist["cust_taxno"];
    $cust_tel = $datalist["cust_tel"];
    $cust_fax = $datalist["cust_fax"];
    $cust_email = $datalist["cust_email"];
    $cust_website = $datalist["cust_website"];   
    $bank_id = isset($datalist["bank_id"]) ? $datalist["bank_id"] : "";
    $bank_name = isset($datalist["bank_name"]) ? $datalist["bank_name"] : "";
    $brc_id = isset($datalist["brc_id"]) ? $datalist["brc_id"] : "";
    $brc_name = isset($datalist["brc_name"]) ? $datalist["brc_name"] : "";
    
    $re_stsid = isset($datalist["re_stsid"]) ? $datalist['re_stsid'] : "";
    $receipt_subtotal = __price($receipt_subtotalHidden);
    $receipt_vat = __price($receipt_vatHidden);
    $receipt_grandtotal = __price($receipt_grandtotalHidden);
    $text_bath_thai = __bahtthai($receipt_grandtotalHidden);
}
?>