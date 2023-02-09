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

$status = $_GET['sts'];

$str_sql = "SELECT * FROM invoice_rcpt_tb AS ir 
            INNER JOIN company_tb AS c ON ir.invrcpt_compid = c.comp_id 
            INNER JOIN customer_tb AS cust ON ir.invrcpt_custid = cust.cust_id 
            INNER JOIN department_tb AS d ON ir.invrcpt_depid = d.dep_id 
            WHERE invrcpt_compid = '". $_GET['cid'] ."' AND invrcpt_date BETWEEN '". $_GET['df'] ."' AND '". $_GET['dt'] ."'";

if($_GET['custid'] != ''){
    $str_sql .= " AND invrcpt_custid = '". $_GET['custid'] ."'";
}

if($status != ''){
    $str_sql .= " AND invrcpt_stsid = '$status'";
}

$str_sql .= " ORDER BY invrcpt_book ASC, invrcpt_no ASC ";
$obj_query = mysqli_query($obj_con, $str_sql);

$filename = "รายงานใบแจ้งหนี้(รายรับ) วันที่" . $_GET['df'] . ' ถึง ' . $_GET['dt'];

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
					<table border="1" class="table">
						<thead>
							<tr class="info">
								<th>เลขที่ใบแจ้งหนี้</th>
								<th>วันที่</th>
								<th>เลขที่ใบเสร็จรับเงิน</th>
								<th>วันที่</th>
                                <th>ชื่อผู้ซื้อ/ผู้รับบริการ/รายการ</th>
                                <th>จำนวนเงิน</th>
                                <th>ภาษีมูลค่าเพิ่ม</th>
                                <th>จำนวนเงินรวม</th>
							</tr>
						</thead>
						<tbody>
                        <?php while($row = mysqli_fetch_assoc($obj_query)) {

                            if($row["invrcpt_book"] == '') {
                                $invoiceno = $row["invrcpt_no"];
                            } else {
                                $invoiceno = $row["invrcpt_book"] . "/" . $row["invrcpt_no"];
                            }

                            $str_sql_rcpt = "SELECT * FROM receipt_tb WHERE re_id = '". $row["invrcpt_reid"] ."'";
                            $obj_rs_rcpt = mysqli_query($obj_con, $str_sql_rcpt);
                            $obj_row_rcpt = mysqli_fetch_array($obj_rs_rcpt);

                            if(mysqli_num_rows($obj_rs_rcpt) > 0) {
                                if($obj_row_rcpt["re_bookno"] == '') {
                                    $receiptno = $obj_row_rcpt["re_no"];
                                } else {
                                    $receiptno = $obj_row_rcpt["re_bookno"] . "/" . $obj_row_rcpt["re_no"];
                                }
                
                                $receiptdate = DateThai($obj_row_rcpt["re_date"]);
                            } else {
                                $receiptno = '';
                                $receiptdate = '';
                            }

                            $subtotal = $row['invrcpt_stsid'] != 'STS003' ? $row['invrcpt_subtotal'] : 0;
                            $vat = $row['invrcpt_stsid'] != 'STS003' ? $row['invrcpt_vat'] + $row['invrcpt_differencevat'] : 0;
                            $grandtotal = $row['invrcpt_stsid'] != 'STS003' ? $row['invrcpt_grandtotal'] + $row['invrcpt_differencevat'] + $row['invrcpt_differencegrandtotal'] : 0;

                            if($row['invrcpt_stsid'] == 'STS003'){
                                $sum_subtotal += 0;
                                $sum_vat += 0;
                                $sum_grandtotal += 0;
                            }else{
                                $sum_subtotal += $row['invrcpt_subtotal'];
                                $sum_vat += $row['invrcpt_vat'] + $row['invrcpt_differencevat'];
                                $sum_grandtotal += $row['invrcpt_grandtotal'] + $row['invrcpt_differencevat'] + $row['invrcpt_differencegrandtotal'];
                            }
                        ?>

							<tr>
								<td><?= $invoiceno ?></td>
								<td><?= DateThai($row['invrcpt_date']) ?></td>
								<td><?= $receiptno ?></td>
								<td><?= $receiptdate ?></td>
                                <td><?= $row['invrcpt_stsid'] != 'STS003'? $row['cust_name'] : 'ยกเลิก'; ?></td>
								<td><?= $row['invrcpt_stsid'] != 'STS003'? number_format($subtotal,2) : 0; ?></td>
								<td><?= $row['invrcpt_stsid'] != 'STS003'? number_format($vat,2) : 0; ?></td>
								<td><?= $row['invrcpt_stsid'] != 'STS003'? number_format($grandtotal,2) : 0; ?></td>
							</tr>
                        <?php } ?>
						</tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="border-right: none;"></td>
                                <td class="text-right" style="border-left: none;">
                                    <b>ยอดรวม</b>
                                </td>
                                <td class="text-right" style="color: #F00;">
                                    <b><?= number_format($sum_subtotal,2) ?></b>
                                </td>
                                <td class="text-right" style="color: #F00;">
                                    <b><?= number_format($sum_vat,2) ?></b>
                                </td>
                                <td class="text-right" style="color: #F00;">
                                    <b><?= number_format($sum_grandtotal,2) ?></b>
                                </td>
                            <tr>
                        </tfoot>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>