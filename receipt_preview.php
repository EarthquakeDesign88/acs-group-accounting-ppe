<?php
$path = realpath(dirname(__FILE__). '');
include $path.'/config/config.php';
if(isset($_GET["tmp"])){
    @unlink($_GET["tmp"]);
}else{
    if(!empty($_POST)){
        $datalist = array();
        $irID = (!empty($_POST["irID"])) ? $_POST["irID"] : 0;
        $edit = (!empty($irID)) ? true : false;
         
        $sql  = "SELECT * FROM invoice_rcpt_tb WHERE invrcpt_id = '" . $irID. "'";
        $row = $db->query($sql)->row();
        foreach ($_POST as $key => $value) {
            $datalist[$key]  = $value;
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
        $sql_customer = "SELECT * FROM customer_tb WHERE cust_id = '" . $_POST["custid"] . "'";
        $row_customer = $db->query($sql_customer)->row();
        foreach ($row_customer as $key => $value) {
            $datalist[$key]  = $value;
        }
        if(isset($_POST["SelBank"])){
            $sql_customer = "SELECT * FROM bank_tb as b INNER JOIN branch_tb as brc ON b.bank_id = brc.brc_bankid  WHERE bank_id = '" . $_POST["SelBank"] . "' AND brc_id = '" . $_POST["SelBranch"] . "'";
            $row_customer = $db->query($sql_customer)->row();
    
            foreach ($row_customer as $key => $value) {
    
                $datalist[$key]  = $value;
    
            }
        }
        $pdf = __pdf_receipt_revenue($datalist,"P");
        $pdf_preview = $pdf["preview_url"];
        $preview_path = $pdf["preview_path"];
           
        echo json_encode(array('preview_url' => $pdf_preview,'preview_path'=> $preview_path));
    }
 }
?>