<?php
$path = realpath(dirname(__FILE__). '/../');
include $path.'/config/config.php';
__check_login();
$error = 0;
$response = "F";
$message = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";
$ck_login = (!empty(__session_user("id"))) ? true : false;
if($ck_login){
    if(!empty($_GET["cheque"])){
        $cheque_id = $_GET["cheque"];
        $download = (!empty( $_GET["download"] )) ? $_GET["download"] : "";
        $sql = "SELECT *,SUM(i.inv_netamount) as sum_total FROM invoice_tb AS i 
				INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id 
				INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
				INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id 
				INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
				LEFT JOIN cheque_tb AS cheq ON paym.paym_cheqid = cheq.cheq_id 
				LEFT JOIN bank_tb AS b ON cheq.cheq_bankid = b.bank_id 
                WHERE cheq.cheq_id = '$cheque_id'";
        $query = $db->query($sql);
        $numrow = $query->num_rows();
        $datalist = $query->row();
        
        if($numrow==1){
            if($download==1){
                  $pdf = __pdf_cheque_revenue($datalist,"D");
            }else{
                  $pdf = __pdf_cheque_revenue($datalist,"I");
            }
            
            $response = $pdf["response"];
            $message = $pdf["message"];
        }else{
            $message = "ไม่พบข้อมูล";
            $error = 3;
        }
        
    }else{
        $message = "กรุณาระบุรหัสเช็ค";
        $error = 2;
    }
}else{
    $message = "กรุณาเข้าสู่ระบบ";
    $error = 1;
}
if($response=="F"){
    echo "<center><h1 style='color:red'><br><br>".$message."</h1></center>";
}
