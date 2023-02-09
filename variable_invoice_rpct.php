<?php
if(!empty($datalist)){
    $invrcpt_id = $datalist["invrcpt_id"];
    $invrcpt_book = $datalist["invrcpt_book"];
    $invrcpt_no = $datalist["invrcpt_no"];
    $invrcpt_date = $datalist["invrcpt_date"];
    $invrcpt_compid = $datalist["invrcpt_compid"];
    $invrcpt_custid = $datalist["invrcpt_custid"];
    $invrcpt_depid = $datalist["invrcpt_depid"];
    $invrcpt_projid = $datalist["invrcpt_projid"];
    $invrcpt_description1 = $datalist["invrcpt_description1"];
    $invrcpt_description2 = $datalist["invrcpt_description2"];
    $invrcpt_description3 = $datalist["invrcpt_description3"];
    $invrcpt_description4 = $datalist["invrcpt_description4"];
    $invrcpt_description5 = $datalist["invrcpt_description5"];
    $invrcpt_description6 = $datalist["invrcpt_description6"];
    $invrcpt_description7 = $datalist["invrcpt_description7"];
    $invrcpt_description8 = $datalist["invrcpt_description8"];
    $invrcpt_description9 = $datalist["invrcpt_description9"];
    $invrcpt_description10 = $datalist["invrcpt_description10"];
    $invrcpt_description11 = $datalist["invrcpt_description11"];
    $invrcpt_sub_description1 = $datalist["invrcpt_sub_description1"];
    $invrcpt_sub_description2 = $datalist["invrcpt_sub_description2"];
    $invrcpt_sub_description3 = $datalist["invrcpt_sub_description3"];
    $invrcpt_sub_description4 = $datalist["invrcpt_sub_description4"];
    $invrcpt_sub_description5 = $datalist["invrcpt_sub_description5"];
    $invrcpt_sub_description6 = $datalist["invrcpt_sub_description6"];
    $invrcpt_sub_description7 = $datalist["invrcpt_sub_description7"];
    $invrcpt_sub_description8 = $datalist["invrcpt_sub_description8"];
    $invrcpt_sub_description9 = $datalist["invrcpt_sub_description9"];
    $invrcpt_amount1 = $datalist["invrcpt_amount1"];
    $invrcpt_amount2 = $datalist["invrcpt_amount2"];
    $invrcpt_amount3 = $datalist["invrcpt_amount3"];
    $invrcpt_amount4 = $datalist["invrcpt_amount4"];
    $invrcpt_amount5 = $datalist["invrcpt_amount5"];
    $invrcpt_amount6 = $datalist["invrcpt_amount6"];
    $invrcpt_amount7 = $datalist["invrcpt_amount7"];
    $invrcpt_amount8 = $datalist["invrcpt_amount8"];
    $invrcpt_subtotal = $datalist["invrcpt_subtotal"];
    $invrcpt_vatpercent = $datalist["invrcpt_vatpercent"];
    $invrcpt_vat = $datalist["invrcpt_vat"];
    $invrcpt_differencevat = $datalist["invrcpt_differencevat"];
    $invrcpt_grandtotal = $datalist["invrcpt_grandtotal"];
    $invrcpt_differencegrandtotal = $datalist["invrcpt_differencegrandtotal"];
    $invrcpt_balancetotal = $datalist["invrcpt_balancetotal"];
    $invrcpt_duedate = $datalist["invrcpt_duedate"];
    $invrcpt_year = $datalist["invrcpt_year"];
    $invrcpt_month = $datalist["invrcpt_month"];
    $invrcpt_file = $datalist["invrcpt_file"];
    $invrcpt_stsid = $datalist["invrcpt_stsid"];
    $invrcpt_userid_create = $datalist["invrcpt_userid_create"];
    $invrcpt_createdate = $datalist["invrcpt_createdate"];
    $invrcpt_userid_edit = $datalist["invrcpt_userid_edit"];
    $invrcpt_editdate = $datalist["invrcpt_editdate"];
    $invrcpt_reid = $datalist["invrcpt_reid"];
    
    
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
    
    $text_invrcpt_date = __date($invrcpt_date);
    $text_full_invrcpt_date= __date($invrcpt_date,"full");
    
    $text_invrcpt_duedate = __date($invrcpt_duedate);
    $text_full_invrcpt_duedate = __date($invrcpt_duedate,"full");
    
    $text_invrcpt_createdate = __date($invrcpt_createdate);
    $text_full_invrcpt_createdate = __date($invrcpt_createdate,"full");
    
    $text_invrcpt_editdate = __date($invrcpt_editdate);
    $text_full_invrcpt_editdate = __date($text_invrcpt_editdate,"full");
    
    $text_invrcpt_amount1 = (is_numeric($invrcpt_amount1) && $invrcpt_amount1 != 0) ? __price($invrcpt_amount1) : "";
    $text_invrcpt_amount2 = (is_numeric($invrcpt_amount2) && $invrcpt_amount2 != 0) ? __price($invrcpt_amount2) : "";
    $text_invrcpt_amount3 = (is_numeric($invrcpt_amount3) && $invrcpt_amount3 != 0) ? __price($invrcpt_amount3) : "";
    $text_invrcpt_amount4 = (is_numeric($invrcpt_amount4) && $invrcpt_amount4 != 0) ? __price($invrcpt_amount4) : "";
    $text_invrcpt_amount5 = (is_numeric($invrcpt_amount5) && $invrcpt_amount5 != 0) ? __price($invrcpt_amount5) : "";
    $text_invrcpt_amount6 = (is_numeric($invrcpt_amount6) && $invrcpt_amount6 != 0) ? __price($invrcpt_amount6) : "";
    $text_invrcpt_amount7 = (is_numeric($invrcpt_amount7) && $invrcpt_amount7 != 0) ? __price($invrcpt_amount7) : "";
    $text_invrcpt_amount8 = (is_numeric($invrcpt_amount8) && $invrcpt_amount8 != 0) ? __price($invrcpt_amount8) : "";
    
    $text_invrcpt_sub_description1 = $invrcpt_sub_description1;
    $text_invrcpt_sub_description2 = $invrcpt_sub_description2;
    $text_invrcpt_sub_description3 = (is_numeric($invrcpt_sub_description3) && $invrcpt_sub_description3 != 0) ? number_format($invrcpt_sub_description3,4) : "";
    $text_invrcpt_sub_description4 = (is_numeric($invrcpt_sub_description4) && $invrcpt_sub_description4 != 0) ? number_format($invrcpt_sub_description4,4) : "";
    $text_invrcpt_sub_description5 = (is_numeric($invrcpt_sub_description5) && $invrcpt_sub_description5 != 0) ? number_format($invrcpt_sub_description5,4) : "";
    $text_invrcpt_sub_description6 = (is_numeric($invrcpt_sub_description6) && $invrcpt_sub_description6 != 0) ? number_format($invrcpt_sub_description6,4) : "";
    $text_invrcpt_sub_description7 = (is_numeric($invrcpt_sub_description7) && $invrcpt_sub_description7 != 0) ? number_format($invrcpt_sub_description7,4) : "";
    $text_invrcpt_sub_description8 = (is_numeric($invrcpt_sub_description8) && $invrcpt_sub_description8 != 0) ? number_format($invrcpt_sub_description8,4) : "";
    $text_invrcpt_sub_description9 = (is_numeric($invrcpt_sub_description9) && $invrcpt_sub_description9 != 0) ? number_format($invrcpt_sub_description9,4) : "";
    
    $text_invrcpt_subtotal  = __price($invrcpt_subtotal);
    $text_invrcpt_vatpercent  = __number($invrcpt_vatpercent);//ภาษีมูลค่าเพิ่ม VAT | ก่อนบวก diff
    $text_invrcpt_vat = __price($invrcpt_vat);
    $text_invrcpt_differencevat =__price($invrcpt_differencevat);
    $text_invrcpt_grandtotal = __price($invrcpt_grandtotal);//จำนวนเงิน Grand Total | ก่อนบวก diff
    $text_invrcpt_differencegrandtotal = __price($invrcpt_differencegrandtotal);
    $text_invrcpt_balancetotal = __price($invrcpt_balancetotal);
    
    $cal_vat = $invrcpt_vat + $invrcpt_differencevat;
    $cal_grand_total = $invrcpt_grandtotal + $invrcpt_differencevat + $invrcpt_differencegrandtotal;
     
    $text_cal_vat =  __price($cal_vat);//ภาษีมูลค่าเพิ่ม VAT | หลังบวก diff
    $text_cal_grand_total =  __price($cal_grand_total);//จำนวนเงิน Grand Total  | หลังบวก diff
    $text_bath_thai = __bahtthai($cal_grand_total);//บาทไทย
    
    $arrayTableDes = array();
    $arrayTableDes[1] = array("description"=>$invrcpt_description1,"sub_description"=>$text_invrcpt_sub_description1,"text_amount"=>$text_invrcpt_amount1);
    $arrayTableDes[2] = array("description"=>$invrcpt_description2,"sub_description"=>$text_invrcpt_sub_description2,"text_amount"=>$text_invrcpt_amount2);
    $arrayTableDes[3] = array("description"=>$invrcpt_description3,"sub_description"=>$text_invrcpt_sub_description3,"text_amount"=>$text_invrcpt_amount3);
    $arrayTableDes[4] = array("description"=>$invrcpt_description4,"sub_description"=>$text_invrcpt_sub_description4,"text_amount"=>$text_invrcpt_amount4);
    $arrayTableDes[5] = array("description"=>$invrcpt_description5,"sub_description"=>$text_invrcpt_sub_description5,"text_amount"=>$text_invrcpt_amount5);
    $arrayTableDes[6] = array("description"=>$invrcpt_description6,"sub_description"=>$text_invrcpt_sub_description6,"text_amount"=>$text_invrcpt_amount6);
    $arrayTableDes[7] = array("description"=>$invrcpt_description7,"sub_description"=>$text_invrcpt_sub_description7,"text_amount"=>$text_invrcpt_amount7);
    $arrayTableDes[8] = array("description"=>$invrcpt_description8,"sub_description"=>$text_invrcpt_sub_description8,"text_amount"=>$text_invrcpt_amount8);
    $arrayTableDes[9] = array("description"=>$invrcpt_description9,"sub_description"=>$text_invrcpt_sub_description9,"text_amount"=>"");
    $arrayTableDes[10] = array("description"=>$invrcpt_description10,"sub_description"=>"","text_amount"=>"");
    $arrayTableDes[11] = array("description"=>$invrcpt_description11,"sub_description"=>"","text_amount"=>"");
}
?>