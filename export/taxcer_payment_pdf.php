<?php
$path = realpath(dirname(__FILE__). '/../');
include $path.'/config/config.php';

__check_login();

$error = 0;
$response = "F";
$message = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";
$ck_login = (!empty(__session_user("id"))) ? true : false;

if($ck_login){
    if(!empty($_GET["taxcID"])){
        $taxcID = $_GET["taxcID"];
        $download = (!empty( $_GET["download"] )) ? $_GET["download"] : "";
        $datalist = array();
        $invtax1 = 0;
        $invtax2 = 0;
        $invtax3 = 0;
        $invtaxtotal1 = 0;
        $invtaxtotal2 = 0;
        $invtaxtotal3 = 0;
 
        $sql = "SELECT taxc_id, taxc_no, comp_taxno, comp_name, comp_address, twh_taxno, twh_name, twh_address, taxc_income, taxc_tfid, taxc_tpid, taxc_date,
                inv_tax1, inv_tax2, inv_tax3, inv_taxtotal1, inv_taxtotal2, inv_taxtotal3
                FROM taxcertificate_tb as t
                INNER JOIN payment_tb AS p ON t.taxc_id = p.paym_taxcid
                INNER JOIN invoice_tb AS i ON p.paym_id = i.inv_paymid
                INNER JOIN taxfiling_tb AS tf ON t.taxc_tfid = tf.tf_id
                INNER JOIN taxpayer_tb AS tp ON t.taxc_tpid = tp.tp_id
                INNER JOIN taxwithheld_tb AS tw ON t.taxc_twhid = tw.twh_id
                INNER JOIN company_tb AS c ON t.taxc_compid = c.comp_id
                INNER JOIN department_tb AS d ON t.taxc_depid = d.dep_id
                WHERE taxc_id  = '" . $taxcID . "' AND taxc_type = 1";

        $query = $db->query($sql);
        $numrow = $query->num_rows();
        $row = $db->query($sql)->result();
        
        for ($i=0; $i<count($row); $i++) {
            foreach ($row as $key => $value) {
                $datalist[$key]  = $value;
            }
            
            $invtax1 += $datalist[$i]["inv_tax1"];
            $invtax2 += $datalist[$i]["inv_tax2"];
            $invtax3 += $datalist[$i]["inv_tax3"];
            $invtaxtotal1 += $datalist[$i]["inv_taxtotal1"];
            $invtaxtotal2 += $datalist[$i]["inv_taxtotal2"];
            $invtaxtotal3 += $datalist[$i]["inv_taxtotal3"];
        }
        
        $tax = $invtax1 + $invtax2 + $invtax3;
        $taxtotal = $invtaxtotal1 + $invtaxtotal2 + $invtaxtotal3;
        
        $datalist["invtax1"] = $invtax1;
        $datalist["invtax2"] = $invtax2;
        $datalist["invtax3"] = $invtax3;
        $datalist["tax"] = $tax;
        $datalist["invtaxtotal1"] = $invtaxtotal1;
        $datalist["invtaxtotal2"] = $invtaxtotal2;
        $datalist["invtaxtotal3"] = $invtaxtotal3;
        $datalist["taxtotal"] = $taxtotal;

        $datalist["taxc_no"] = $datalist[0]["taxc_no"];
        $datalist["comp_taxno"] = $datalist[0]["comp_taxno"];
        $datalist["comp_name"] = $datalist[0]["comp_name"];
        $datalist["comp_address"] = $datalist[0]["comp_address"];
        $datalist["twh_taxno"] =  $datalist[0]["twh_taxno"];
        $datalist["twh_name"] = $datalist[0]["twh_name"];
        $datalist["twh_address"] = $datalist[0]["twh_address"];
        $datalist["taxc_income"] = $datalist[0]["taxc_income"];
        $datalist["taxc_tfid"] = $datalist[0]["taxc_tfid"];
        $datalist["taxc_tpid"] = $datalist[0]["taxc_tpid"];
        $datalist["taxc_date"] = $datalist[0]["taxc_date"];


        if($numrow>=1){
            if($download==1){
                  $pdf = __pdf_taxcer_payment($datalist,"D");
            }else{
                  $pdf = __pdf_taxcer_payment($datalist,"I");
            }
            
            $response = $pdf["response"];
            $message = $pdf["message"];
        }else{
            $message = "ไม่พบข้อมูล";
            $error = 3;
        }
    }else{
        $message = "กรุณาระบุรหัสใบหัก ณ ที่จ่าย";
        $error = 2;
    }
}else{
    $message = "กรุณาเข้าสู่ระบบ";
    $error = 1;
}
if($response=="F"){
    echo "<center><h1 style='color:red'><br><br>".$message."</h1></center>";
}
