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

if(isset($_GET['custid'])) {

    $Cust = $_GET['custid'];

} else {

    $Cust = '';

}

$str_sql = "SELECT * FROM receipt_tb AS r 
            INNER JOIN company_tb AS c ON r.re_compid = c.comp_id 
            INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
            INNER JOIN department_tb AS d ON r.re_depid = d.dep_id 
            WHERE re_compid = '". $_GET['cid'] ."' AND re_date BETWEEN '". $_GET['df'] ."' AND '". $_GET['dt'] ."'";

if($Cust != ''){
    $str_sql .= " AND re_custid = '$Cust'";
}

$status = $_GET['sts'];

if($status != ''){
    $str_sql .=  " AND re_stsid = '$status'";
}

$str_sql .= " ORDER BY re_bookno ASC, re_no ASC ";
$obj_query = mysqli_query($obj_con, $str_sql);

$filename = "รายงานใบเสร็จรับเงิน วันที่" . $_GET['df'] . ' ถึง ' . $_GET['dt'];

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
								<th>เลขที่ใบเสร็จรับเงิน</th>
								<th>วันที่ใบเสร็จรับเงิน</th>
								<th>เลขที่ใบแจ้งหนี้</th>
								<th>ชื่อผู้ซื้อ/ผู้รับบริการ/รายการ</th>
                                <th>จำนวนเงิน</th>
                                <th>ภาษีมูลค่าเพิ่ม</th>
                                <th>จำนวนเงินรวม</th>
                                <th>เลขที่เช็ค</th>
                                <th>ธนาคาร</th>
                                <th>วันที่ออกเช็ค</th>
                                <th>เงินสด</th>
							</tr>
						</thead>
                        <tbody>
                        <?php while($row = mysqli_fetch_assoc($obj_query)) {
                            if ($row["re_refinvrcpt"] == '0') {
                                $str_sql_inv = "SELECT * FROM receipt_tb AS r 
                                        INNER JOIN invoice_rcpt_tb AS i ON r.re_invrcpt_id = i.invrcpt_id
                                        WHERE re_id = '". $row['re_id'] ."'";
                                $obj_rs_inv = mysqli_query($obj_con, $str_sql_inv);
                                $obj_row_inv = mysqli_fetch_array($obj_rs_inv);
                
                                if($obj_row_inv["invrcpt_book"] == '') {
                
                                    $invoiceno = $obj_row_inv["invrcpt_no"];
                
                                } else {
                
                                    $invoiceno = $obj_row_inv["invrcpt_book"] . "/" . $obj_row_inv["invrcpt_no"];
                
                                }
                            } else {
                                $invoiceno = '';
                            }

                            
                            if($row["re_bookno"] == '') {

                                $receiptno = $row["re_no"];

                            } else {

                                $receiptno = $row["re_bookno"] . "/" . $row["re_no"];

                            }



                            if($row["re_chequedate"] == '0000-00-00') {

                                $chequedate = '';

                            } else {

                                $chequedate = DateThai($row["re_chequedate"]);

                            }

                            $subtotal = $row['re_stsid'] != 'STS003' ? $row['re_subtotal'] : 0;
                            $vat = $row['re_stsid'] != 'STS003' ? $row['re_vat'] + $row['re_differencevat'] : 0;
                            $grandtotal = $row['re_stsid'] != 'STS003' ? $row['re_grandtotal'] + $row['re_differencevat'] + $row['re_differencegrandtotal'] : 0;
                            
                            if($row['re_stsid'] == 'STS003'){
                                $sum_subtotal += 0;
                                $sum_vat += 0;
                                $sum_grandtotal += 0;
                            }else{
                                $sum_subtotal += $row['re_subtotal'];
                                $sum_vat += $row['re_vat'] + $row['re_differencevat'];
                                $sum_grandtotal += $row['re_grandtotal'] + $row['re_differencevat'] + $row['re_differencegrandtotal'];
                            }
                            
                            $str_sql_b = "SELECT * FROM bank_tb WHERE bank_id = '". $row["re_bankid"] ."'";
                
                            $obj_rs_b = mysqli_query($obj_con, $str_sql_b);
                
                            $obj_row_b = mysqli_fetch_array($obj_rs_b);

                            if(mysqli_num_rows($obj_rs_b) > 0) {

                                if($row["re_bankid"] == '') {
                
                                    $bankname = '';
                
                                } else {
                
                                    $bankname = $obj_row_b["bank_name"];
                
                                }
                
                            } else {
                
                                $bankname = '';
                
                            }
                        ?>
                        	<tr>
								<td><?= $receiptno ?></td>
								<td><?= DateThai($row['re_date']) ?></td>
								<td><?= $invoiceno ?></td>
								<td>
                                    <?php if($row['re_stsid'] != 'STS003'){  ?> 
                                        <?= $row['cust_name'] ?>
                                    <?php } else { ?>
                                        <?= $row['re_note_cancel']; ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if($row['re_stsid'] != 'STS003'){  ?> 
                                        <?= number_format($subtotal,2) ?>
                                    <?php } else { ?>
                                        <?= 0 ?>
                                    <?php } ?>
                                </td>
								<td>
                                    <?php if($row['re_stsid'] != 'STS003'){  ?> 
                                        <?= number_format($vat,2) ?>
                                    <?php } else { ?>
                                        <?= 0 ?>
                                    <?php } ?>                                   
                                </td>
								<td>
                                    <?php if($row['re_stsid'] != 'STS003'){  ?> 
                                        <?= number_format($grandtotal,2) ?>
                                    <?php } else { ?>
                                        <?= 0 ?>
                                    <?php } ?>                                         
                                </td>
								<td><?= $row['re_chequeno'] ?></td>
                                <td><?= $bankname ?></td>
                                <td><?= $chequedate ?></td>
                                <td class="text-center">
                                    <?php if($row['re_typepay'] == '1') { ?>
                                        /
                                    <?php } else { ?>
                                        
                                    <?php } ?>

                                </td>
							</tr>
                        <?php } ?>
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
                                    <b><?= number_format($sum_vat,2) ?></b>
                                </td>
                                <td class="text-right" style="color: #F00;">
                                    <b><?= number_format($sum_grandtotal,2) ?></b>
                                </td>
                                <td colspan="4"></td>
                            <tr>

                        </tfoot>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>