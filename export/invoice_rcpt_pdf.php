<?php$path = realpath(dirname(__FILE__). '/../');include $path.'/config/config.php';__check_login();$error = 0;$response = "F";$message = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";$ck_login = (!empty(__session_user("id"))) ? true : false;if($ck_login){    if(!empty($_GET["irID"])){        $irID = $_GET["irID"];        $download = (!empty( $_GET["download"] )) ? $_GET["download"] : "";        $preview = (!empty( $_GET["preview"] )) ? $_GET["preview"] : "";        $sql = "SELECT *         FROM invoice_rcpt_tb ir         LEFT JOIN company_tb c ON ir.invrcpt_compid = c.comp_id         LEFT JOIN customer_tb cust ON ir.invrcpt_custid = cust.cust_id         LEFT JOIN department_tb d ON ir.invrcpt_depid = d.dep_id         WHERE ir.invrcpt_id = '". $irID ."'";        $query = $db->query($sql);        $numrow = $query->num_rows();        $datalist = $query->row();                if($numrow==1){            if($download==1){                  $pdf = __pdf_invoice_revenue($datalist,"D");            }else{                  $pdf = __pdf_invoice_revenue($datalist,"I");            }                        $response = $pdf["response"];            $message = $pdf["message"];        }else{            $message = "ไม่พบข้อมูล";            $error = 3;        }    }else{        $message = "กรุณาระบุรหัสใบแจ้งหนี้ (รายรับ)";        $error = 2;    }}else{    $message = "กรุณาเข้าสู่ระบบ";    $error = 1;}if($response=="F"){    echo "<center><h1 style='color:red'><br><br>".$message."</h1></center>";}