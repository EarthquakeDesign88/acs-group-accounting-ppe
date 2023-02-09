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
        $strMonthCut = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
    }else{
        $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    }
    $strMonthThai = $strMonthCut[$strMonth-1];

    return array($strDay,$strMonthThai,$strYear);
}

$tax_id = isset($_GET['tax_id']) ? $_GET['tax_id'] : '';


$str_sql = "SELECT *,tl.created_at AS taxlist_date,t.created_at AS tax_date FROM taxpurchase_tb AS t
            INNER JOIN taxpurchaselist_tb AS tl ON t.tax_id = tl.list_tax_id
            INNER JOIN company_tb AS c ON t.tax_comp_id = c.comp_id
            INNER JOIN payable_tb as ct ON tl.list_paya_id = ct.paya_id
            WHERE t.tax_id = '$tax_id'";
            
$obj_query = mysqli_query($obj_con, $str_sql);
$obj_row_tax = mysqli_fetch_assoc($obj_query);

$filename = "รายงานใบภาษีซื้อรายเดือน" . $obj_row_tax['tax_id'];

$date_head = DateMonthThai($obj_row_tax['tax_date'],"head");

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
                    <h3 class="text-center"><b>รายงานภาษีซื้อ</b></h3>
                    <h3 class="text-center"><b>เดือน <?= $date_head[1] . ' ' . $date_head[2] ?></b></h3>
                    <h3 class="text-center"><b>ชื่อผู้ประกอบการ</b>  <?= $obj_row_tax['comp_name'] ?> <b>เลขประจำตัวผู้เสียภาษีอากร</b> <?= $obj_row_tax['comp_taxno'] ?></h3>
                    <h3 class="text-center"><b>ชื่อสถานประกอบการ</b> <?= $obj_row_tax['comp_name'] ?> <b>สำนักงานใหญ่</b></h3>
					<table border="1" class="table">
						<thead>
							<tr class="info">
								<th>ลำดับ</th>
								<th>วันที่</th>
								<th>เล่มที่/เลขที่</th>
								<th>ชื่อผู้ซื้อ/ผู้ให้บริการ/รายการ</th>
                                <th>จำนวนเงิน</th>
                                <th>ภาษีมูลค่าเพิ่ม</th>
                                <th>จำนวนเงินรวม</th>
							</tr>
						</thead>
                        <tbody>
                            <?php $i = 1; foreach($obj_query as $obj_row) { ?>
                        	<tr>
								<td><?= $i++ ?></td>
								<td><?= DateThai($obj_row['taxlist_date']) ?></td>
								<td><?= $obj_row['list_no'] ?></td>
								<td><?= $obj_row['paya_name'] . $obj_row['list_desc'] ?></td>
                                <td><?= number_format($obj_row['list_price'],2) ?></td>
								<td><?= number_format($obj_row['list_vat'],2) ?></td>
								<td><?= number_format($obj_row['list_result'],2) ?></td>

							</tr>
                            <?php
                                $sum_subtotal += $obj_row['list_price'];
                                $sum_vat += $obj_row['list_vat'];
                                $sum_grandtotal += $obj_row['list_result'];
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