<?php
$path = realpath(dirname(__FILE__). '');
include $path.'/config/config.php';
__check_login();
if(isset($_GET["tmp"])){
    @unlink($_GET["tmp"]);
}else{
    if(!empty($_POST)){
          //edit
         $irID = (!empty($_POST["irID"])) ? $_POST["irID"] : 0;
         $edit = (!empty($irID)) ? true : false;
          
         $sql  = "SELECT * FROM invoice_rcpt_tb WHERE invrcpt_id = '" . $irID. "'";
         $row = $db->query($sql)->row();
            
        if(!empty($row)){
             foreach ($row as $key => $value) {
                $datalist[$key]  = $value;
                $datalist["invrcpt_subtotal"] = $_POST["subtotalHidden"];
                $datalist["invrcpt_vatpercent"] = $_POST["vatpercentHidden"];
                $datalist["invrcpt_vat"] = $_POST["vatHidden"];
                $datalist["invrcpt_differencevat"] = $_POST["DiffVatHidden"];
                $datalist["invrcpt_grandtotal"] = $_POST["grandtotalHidden"];
                $datalist["invrcpt_differencegrandtotal"] = $_POST["DiffGrandHidden"];
                $datalist["invrcpt_balancetotal"] =$_POST["subtotalHidden"];
    
            }
        }else{
             //add
            $datalist = array();
            $datalist["invrcpt_id"] = 0;
            $datalist["invrcpt_no"] = ($edit) ? $row["invrcpt_no"] : "xxxxxxxx";
            $datalist["invrcpt_date"]  = (($edit) ? $row["invrcpt_date"] : $_POST["invRedate"]) ;
            $datalist["invrcpt_compid"] = $_POST["compid"];
            $datalist["invrcpt_custid"] = $_POST["custid"];
            $datalist["invrcpt_depid"] = $_POST["depid"];
            $datalist["invrcpt_projid"] = "";
            $datalist["invrcpt_subtotal"] = $_POST["subtotalHidden"];
            $datalist["invrcpt_vatpercent"] = $_POST["vatpercentHidden"];
            $datalist["invrcpt_vat"] = $_POST["vatHidden"];
            $datalist["invrcpt_differencevat"] = $_POST["DiffVatHidden"];
            $datalist["invrcpt_grandtotal"] = $_POST["grandtotalHidden"];
            $datalist["invrcpt_differencegrandtotal"] = $_POST["DiffGrandHidden"];
            $datalist["invrcpt_balancetotal"] =$_POST["subtotalHidden"];
            $datalist["invrcpt_duedate"] = isset($_POST["invrcptduedate"]) ? $_POST["invrcptduedate"] :'';
            $datalist["invrcpt_year"] = $_POST["SelinvrcptYear"];
            $datalist["invrcpt_month"] = $_POST["SelMonth"];
            $datalist["invrcpt_file"] = "";
            $datalist["invrcpt_stsid"] = "";
            $datalist["invrcpt_userid_create"] = "";
            $datalist["invrcpt_createdate"] = "";
            $datalist["invrcpt_userid_edit"] = "";
            $datalist["invrcpt_editdate"] = "";
            $datalist["invrcpt_reid"] = "";
        }
        
    
        $count_description = 11;
        for($i=1;$i<=$count_description;$i++){
            $datalist["invrcpt_description".$i] = (isset($_POST["invredesc".$i])) ?  $_POST["invredesc".$i] : "";
        }
        
        $count_sub_description = 11;
        for($i=1;$i<=$count_sub_description;$i++){
            $datalist["invrcpt_sub_description".$i] = (isset($_POST["invresubdescHidden".$i])) ?  $_POST["invresubdescHidden".$i] : "";
        }
        
        $count_amount = 8;
        for($i=1;$i<=$count_amount;$i++){
            $datalist["invrcpt_amount".$i] = (isset($_POST["amountHidden".$i])) ?  $_POST["amountHidden".$i] : "";
        }
        
        $sql_company = "SELECT * FROM company_tb WHERE comp_id = '" . (($edit) ? $row["invrcpt_compid"] : $_POST["compid"]) . "'";
        $row_company = $db->query($sql_company)->row();
        foreach ($row_company as $key => $value) {
        	$datalist[$key]  = $value;
        }
        
        $sql_department = "SELECT * FROM department_tb WHERE dep_id = '" . (($edit) ? $row["invrcpt_depid"] : $_POST["depid"]) . "'";
        $row_department = $db->query($sql_department)->row();
        foreach ($row_department as $key => $value) {
            $datalist[$key]  = $value;
        }
        $datalist["invrcpt_book"] = $row_department["dep_code"];
        
        $sql_customer = "SELECT * FROM customer_tb WHERE cust_id = '" . $_POST["custid"] . "'";
        $row_customer = $db->query($sql_customer)->row();
        foreach ($row_customer as $key => $value) {
            $datalist[$key]  = $value;
        }
        
       $pdf = __pdf_invoice_revenue($datalist,"P");
       $pdf_preview = $pdf["preview_url"];
       $preview_path = $pdf["preview_path"];
       
       echo json_encode(array('preview_url' => $pdf_preview,'preview_path'=> $preview_path));
    }
 }
?>
