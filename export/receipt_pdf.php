<?php
$path = realpath(dirname(__FILE__). '/../');
include $path.'/config/config.php';

__check_login();

$error = 0;
$response = "F";
$message = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";
$ck_login = (!empty(__session_user("id"))) ? true : false;
if($ck_login){
    if(!empty($_GET["ReID"])){
        $ReID = $_GET["ReID"];
        $download = (!empty( $_GET["download"] )) ? $_GET["download"] : "";
    
        $sql = "SELECT * 
        FROM receipt_tb as r 
        LEFT JOIN company_tb c ON r.re_compid = c.comp_id 
        LEFT JOIN customer_tb cust ON r.re_custid = cust.cust_id 
        LEFT JOIN department_tb d ON r.re_depid = d.dep_id 
        LEFT JOIN bank_tb b ON r.re_bankid = b.bank_id
        LEFT JOIN branch_tb brc ON r.re_branchid = brc.brc_id
        LEFT JOIN invoice_rcpt_tb inv ON r.re_invrcpt_id = inv.invrcpt_id
        WHERE r.re_id = '". $ReID ."'";
        $query = $db->query($sql);
        $numrow = $query->num_rows();
        $datalist = $query->row();

        if($numrow==1){      
            if($download==1){
                  $pdf = __pdf_receipt_revenue($datalist,"D");
            }else{
                $pdf = __pdf_receipt_revenue($datalist,"I");
            }
            
            $response = $pdf["response"];
            $message = $pdf["message"];
        }else{
            $message = "ไม่พบข้อมูล";
            $error = 3;
        }
    }else{
        $message = "กรุณาระบุรหัสใบแจ้งหนี้ (รายรับ)";
        $error = 2;
    }
}else{
    $message = "กรุณาเข้าสู่ระบบ";
    $error = 1;
}
if($response=="F"){
    echo "<center><h1 style='color:red'><br><br>".$message."</h1></center>";
}
