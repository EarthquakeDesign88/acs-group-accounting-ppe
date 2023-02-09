<?php

include 'connect.php';


function DateThai($strDate) {
    $strYear = date("Y",strtotime($strDate))+543;
    $strMonth= date("n",strtotime($strDate));
    $strDay= date("j",strtotime($strDate));
    $strHour= date("H",strtotime($strDate));
    $strMinute= date("i",strtotime($strDate));
    $strSeconds= date("s",strtotime($strDate));
    $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
    $strMonthThai=$strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear";
}

function DateMonthThai($strDate,$mode)
{
    if($mode === "head"){
        $strYear = date("Y", strtotime($strDate)) + 543;
    }else{
        $strYear = substr(date("Y", strtotime($strDate)) + 543,-2);
    }
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strHour = date("H", strtotime($strDate));
    $strMinute = date("i", strtotime($strDate));
    $strSeconds = date("s", strtotime($strDate));
    if($mode === "head"){
        $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
    }else{
        $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    }
    $strMonthThai = $strMonthCut[$strMonth];

    return array($strDay,$strMonthThai,$strYear);
}


$type = $_POST['SelectTax'];
$dep = $_GET['dep'];
$query_tax_month = isset($_POST['SelectMonth']) ? $_POST['SelectMonth'] : '';

// $str_sql = "SELECT * FROM taxcertificate_tb AS tc 
//             INNER JOIN taxfiling_tb AS tf ON tc.taxc_tfid = tf.tf_id 
//             INNER JOIN taxwithheld_tb AS twh ON tc.taxc_twhid = twh.twh_id 
// 			INNER JOIN payment_tb AS paym ON tc.taxc_id = paym.paym_taxcid 
// 			LEFT JOIN invoice_tb AS i ON paym.paym_id = i.inv_paymid 
//             WHERE taxc_compid = '". $_GET['cid'] ."' AND taxc_date BETWEEN '". $_GET['df'] ."' AND '". $_GET['dt'] ."' AND taxc_depid = '$dep'";

$str_sql = "SELECT * FROM taxcertificate_tb AS taxc 
            INNER JOIN taxwithheld_tb AS twh ON taxc.taxc_twhid = twh.twh_id 
            INNER JOIN payment_tb AS paym ON taxc.taxc_id = paym.paym_taxcid 
            LEFT JOIN invoice_tb AS i ON paym.paym_id = i.inv_paymid 
            INNER JOIN user_tb AS u ON taxc.taxc_userid_create = u.user_id
            INNER JOIN company_tb AS c ON taxc.taxc_compid = c.comp_id
            INNER JOIN taxfiling_tb AS tf ON taxc.taxc_tfid = tf.tf_id
            WHERE taxc_compid = '". $_GET['cid'] ."' AND taxc_date BETWEEN '". $_GET['df'] ."' AND '". $_GET['dt'] ."' AND taxc_depid = '$dep'"; 

if($type != ''){
    $str_sql .= " AND taxc_tfid = '$type'";
}

if($query_tax_month != ''){
    $str_sql .= " AND taxc.taxc_month = '$query_tax_month'";
}

$str_sql .= " GROUP BY paym_no ORDER BY taxc_id ASC ";
$obj_query = mysqli_query($obj_con, $str_sql);
$obj_query_head = mysqli_fetch_assoc($obj_query);

$filename = "รายงานภาษีหัก ณ ที่จ่าย วันที่" . $_GET['df'] . ' ถึง ' . $_GET['dt'];

$date_head = DateMonthThai($obj_query_head['taxc_date'],"head");

if(isset($_GET['export'])){
	if($_GET['export']== 'excel'){
		header("Content-Type: application/xls");
		header("Content-Disposition: attachment; filename=$filename.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">		
                    <h3 class="text-center"><b>รายงานภาษีหัก ณ ที่จ่าย</b></h3>
                    <h3 class="text-center"><b><?= $query_tax_month != '' ? 'เดือน ' . $date_head[1] . ' ' . $date_head[2] : ''; ?></b></h3>
                    <h3 class="text-center"><b>ชื่อผู้ประกอบการ</b>  <?= $obj_query_head['comp_name'] ?> <b>เลขประจำตัวผู้เสียภาษีอากร</b> <?= $obj_query_head['comp_taxno'] ?></h3>
                    <h3 class="text-center"><b>ชื่อสถานประกอบการ</b> <?= $obj_query_head['comp_name'] ?> <b>สำนักงานใหญ่</b> <b><?= $type != ''  ? 'ประเภท ' . $obj_query_head['tf_name'] : '' ?></b></h3>			
					<table border="1" class="table">
						<thead>
							<tr class="info">
								<th>ลำดับ</th>
								<th>วันที่</th>
								<th>เล่มที่/เลขที่หัก ณ ที่จ่าย</th>
								<th>รายละเอียด</th>
                                <th>จำนวนเงินที่จ่าย</th>
                                <th>ภาษีที่หักและนำส่งไว้</th>
                                <th>ยอดรวมสุทธิ</th>
							</tr>
						</thead>
                        <tbody>
                            <?php $i = 1; foreach($obj_query as $obj_row) { 
                                    $str_sql_paym = "SELECT * FROM payment_tb AS paym INNER JOIN invoice_tb AS i ON paym.paym_id = i.inv_paymid WHERE paym_taxcid = '". $obj_row["taxc_id"] ."'";
                                    $obj_rs_paym = mysqli_query($obj_con, $str_sql_paym);
                                    $invtax1  = 0;
                                    $invtax2  = 0;
                                    $invtax3  = 0;
                                    $invtaxtotal1 = 0;
                                    $invtaxtotal2 = 0;
                                    $invtaxtotal3 = 0;
                                    while ($obj_row_paym = mysqli_fetch_array($obj_rs_paym)) {
                                        $invtax1 += $obj_row_paym["inv_tax1"];
                                        $invtax2 += $obj_row_paym["inv_tax2"];
                                        $invtax3 += $obj_row_paym["inv_tax3"];
                                        $invtaxtotal1 += $obj_row_paym["inv_taxtotal1"];
                                        $invtaxtotal2 += $obj_row_paym["inv_taxtotal2"];
                                        $invtaxtotal3 += $obj_row_paym["inv_taxtotal3"];
                        
                                        $taxtotal1 = $invtaxtotal1;
                                        $taxtotal2 = $invtaxtotal2;
                                        $taxtotal3 = $invtaxtotal3;

                                        $total_tax = $invtax1 + $invtax2 + $invtax3;
                                        $tax_result = $taxtotal1 + $taxtotal2 + $taxtotal3;
                                        $result = $total_tax + $tax_result;
                                    }
                                ?>
                        	<tr>
								<td><?= $i++ ?></td>
								<td><?= DateThai($obj_row['taxc_date']) ?></td>
                                <td><?= $obj_row['taxc_no'] ?></td>
                                <td>
                                    <div class="truncate">
                                        <b>บริษัท : </b> <?=$obj_row['twh_name']?><br>
                                        <b>รายการ : </b> <?=$obj_row['inv_description']?>
                                    </div>
                                </td>	
                                <td><?= number_format($total_tax,2) ?></td>
								<td>
                                    <div><?= number_format($tax_result,2) ?></div>
                                </td>
                                <td class="text-center"><?= number_format($result,2) ?></td>
							</tr>
                            <?php
                                $sum_subtotal += $total_tax;
                                $sum_grandtotal += $tax_result;
                                $result_total = $sum_subtotal + $sum_grandtotal;
                            } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="border-right: none;"></td>
                                <td class="text-right" style="border-left: none;">
                                    <b>ยอดรวม</b>
                                </td>
                                <td class="text-right" style="color: #F00;">
                                    <b><?= number_format($sum_subtotal,2) ?></b>
                                </td>
                                <td class="text-right" style="color: #F00;">
                                    <b><?= number_format($sum_grandtotal,2) ?></b>
                                </td>
                                <td class="text-right" style="color: #F00;">
                                    <b><?= number_format($result_total,2) ?></b>
                                </td>
                            <tr>

                        </tfoot>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>