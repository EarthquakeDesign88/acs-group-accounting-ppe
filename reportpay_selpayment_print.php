<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$reppid = $_GET["reppid"];
		$reppdno = $_GET["reppdno"];

		$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $dep ."'";
		$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
		$obj_row_dep = mysqli_fetch_array($obj_rs_dep);


		$str_sql_repp = "SELECT * FROM reportpay_tb1 WHERE repp_id = '". $reppid ."'";
		$obj_rs_repp = mysqli_query($obj_con, $str_sql_repp);
		$obj_row_repp = mysqli_fetch_array($obj_rs_repp);


		function DateThai($strDate) {
			$strYear = substr(date("Y",strtotime($strDate))+543,-2);
			$strMonth= date("n",strtotime($strDate));
			$strDay= date("j",strtotime($strDate));
			$strHour= date("H",strtotime($strDate));
			$strMinute= date("i",strtotime($strDate));
			$strSeconds= date("s",strtotime($strDate));
			$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
			$strMonthThai=$strMonthCut[$strMonth];
			return "$strDay $strMonthThai $strYear";
		}

		function DateThaiFile($strDate) {
			$strYear = substr(date("Y",strtotime($strDate))+543,-2);
			$strMonth= date("n",strtotime($strDate));
			$strDay= date("j",strtotime($strDate));
			$strHour= date("H",strtotime($strDate));
			$strMinute= date("i",strtotime($strDate));
			$strSeconds= date("s",strtotime($strDate));
			$strMonthCut = Array("","01","02","03","04","05","06","07","08","09","10","11","12");
			$strMonthThai=$strMonthCut[$strMonth];
			return "$strDay"."-"."$strMonthThai"."-"."$strYear";
		}


		// Require composer autoload
		require_once __DIR__ . '/vendor/autoload.php';

		$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
		$fontDirs = $defaultConfig['fontDir'];

		$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
		$fontData = $defaultFontConfig['fontdata'];


		$mpdf = new \Mpdf\Mpdf([
			'useActiveForms' => true,
			'mode' => 'utf-8',
			'format' => 'A4-P',
			// 'format' => 'A5',
			'margin_top' => 15,
			'margin_bottom' => 10,
			'margin_left' => 10,
			'margin_right' => 10,
			'margin_header' => 3,     // 9 margin header
			'margin_footer' => 3,     // 9 margin footer
			'tempDir' => __DIR__ . '/tmp/',
			// 'default_font_size' => 14,
			'fontdata' => $fontData + [
				'sarabun' => [
					'R' => 'THSarabunNew.ttf',
					'I' => 'THSarabunNewItalic.ttf',
					'B' =>  'THSarabunNewBold.ttf',
					'BI' => "THSarabunNewBoldItalic.ttf",
				]
			],
		]);

		$path = "report/paysummary/";
		$dep = $obj_row_dep["dep_name"];

		// if ($_FILES['inputFile']['name'] != '') {

			$pathDep = $path . $dep;

			$year_folder = $pathDep . '/' . (date("Y")+543);
			$month_folder = $year_folder . '/' . date("m");	

			!file_exists($pathDep) && mkdir($pathDep , 0777);
			!file_exists($year_folder) && mkdir($year_folder , 0777);
			!file_exists($month_folder) && mkdir($month_folder, 0777);

			$type = ".pdf";
			$newname = "สรุปจ่ายฝ่าย" . $obj_row_dep["dep_name"] . "(วันที่" . DateThaiFile($obj_row_repp["repp_date"]).")" .$type;
			$pathimage = $month_folder;
			$pathCopy = $month_folder . '/' . $newname;

			// move_uploaded_file($_FILES["inputFile"]["tmp_name"], $pathCopy);

		// }

		$name = $pathCopy;
		$year = $obj_row_repp["repp_year"];
		$month = $obj_row_repp["repp_month"];

		$str_sql_UpRepp = "UPDATE reportpay_tb1 SET
		repp_file = '$name'
		WHERE repp_id = '".$_GET["reppid"]."'";
		$result_UpRepp = mysqli_query($obj_con, $str_sql_UpRepp);

		ob_start(); // Start get HTML code

?>
<!DOCTYPE html>

	<div align="center" style="font-size: 18px;">กรุณารอสักครู่...</div>

<head>
	<title>สรุปรายการทำจ่าย ฝ่าย <?=$obj_row_dep["dep_name"];?> วันที่ <?=$obj_row_repp["repp_date"]?></title>
	<link href="https://fonts.googleapis.com/css?family=Sarabun&display=swap" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="plugins/fontawesome/css/all.min.css">

	<style>
		body {
			font-family: "sarabun";
		}
		table {
			padding: 1px 0px;
			overflow: wrap;
			font-size: 14pt;
		}
	</style>

</head>
<body>

	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td style="font-size: 18pt">
				<b><?=$obj_row_dep["dep_name"];?> จัดทำ <?=DateThai($obj_row_repp["repp_date"]);?></b>
			</td>
			<?php if ($obj_row_repp["repp_paydate"] != '') { ?>
			<td>
				วันที่ทำจ่าย <?=DateThai($obj_row_repp["repp_paydate"]);?>
			</td>
			<?php } ?>
		</tr>
	</table>

	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td colspan="3" style="text-align: right;">
				<b>บาท</b>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<b><?=$obj_row_repp["repp_desc_summarize"]?></b>
			</td>
			<td width="15%" style="text-align: right;">
				<b><?=number_format($obj_row_repp["repp_total_summarize"],2)?></b>
			</td>
		</tr>

		<?php
			$str_sql_plus = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 1 AND reppd_no = '" . $_GET["reppdno"] . "'";
			$obj_rs_plus = mysqli_query($obj_con, $str_sql_plus);
			$i = 1;
			while ($obj_row_plus = mysqli_fetch_array($obj_rs_plus)) {
		?>
		<tr>
			<td width="5%"><b>บวก</b></td>
			<td width="80%"><?=$obj_row_plus["reppd_description"];?></td>
			<td width="15%" style="text-align: right;">
				<?=number_format($obj_row_plus["reppd_amount"],2);?>
			</td>
		</tr>
		<?php } ?>

		<?php
			$str_sql_dis = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 2 AND reppd_no = '" . $_GET["reppdno"] . "'";
			$obj_rs_dis = mysqli_query($obj_con, $str_sql_dis);
			$i = 1;
			while ($obj_row_dis = mysqli_fetch_array($obj_rs_dis)) {
		?>
		<tr>
			<td width="5%"><b>ลบ</b></td>
			<td width="80%">
				<?=$obj_row_dis["reppd_description"];?>
			</td>
			<td width="15%" style="text-align: right;">
				<?=number_format($obj_row_dis["reppd_amount"],2);?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="2"></td>
			<td width="15%" style="border-top: 1px solid #000;"></td>
		</tr>

		<tr>
			<td colspan="2">
				<b><?=$obj_row_repp["repp_desc_balance"]?></b>
			</td>
			<td width="15%" style="text-align: right;">
				<?=number_format($obj_row_repp["repp_total_balance"],2)?>
			</td>
		</tr>
	</table>

	<br>
	<br>

	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td colspan="4">ส่ง PV.ลงนาม ครั้งนี้ <?=DateThai($obj_row_repp["repp_date"]);?></td>
		</tr>

		<?php
			$str_sql_recash = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id WHERE paym_typepay = 1 AND paym_reppid = '". $reppid ."'";
			$obj_rs_recash = mysqli_query($obj_con, $str_sql_recash);
			if (mysqli_num_rows($obj_rs_recash) >= 1) {

				$str_sql_cash = "SELECT * FROM payment_tb WHERE paym_typepay = 1 AND paym_reppid = '". $reppid ."'";
				$obj_rs_cash = mysqli_query($obj_con, $str_sql_cash);
				while ($obj_row_cash = mysqli_fetch_array($obj_rs_cash)) {
					$str_sql_paymcash = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id WHERE inv_paymid = '". $obj_row_cash["paym_id"] ."'";
					$obj_rs_paymcash = mysqli_query($obj_con, $str_sql_paymcash);
					$invdesc = "";
					$invnetamount = 0;
					while ($obj_row_paymcash = mysqli_fetch_array($obj_rs_paymcash)) {
						if (mysqli_num_rows($obj_rs_paymcash) > 1) {
							$invdesc = $obj_row_paymcash["inv_description_short"] . " / " . $invdesc;
						} else {
							 $invdesc = $obj_row_paymcash["inv_description_short"];
						}
						$invnetamount += "-".$obj_row_paymcash["inv_netamount"];
					}

					$str_sql_sumcash = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id WHERE paym_typepay = 1 AND paym_reppid = '". $reppid ."'";
					$obj_rs_sumcash = mysqli_query($obj_con, $str_sql_sumcash);
					$sumnet = 0;
					while ($obj_row_sumcash = mysqli_fetch_array($obj_rs_sumcash)) {
						if(mysqli_num_rows($obj_rs_sumcash) >= 1) {
							$sumnet += "-".$obj_row_sumcash["inv_netamount"];
						} else {
							$sumnet = "0.00";
						}
					}
		?>
		<tr>
			<td width="8%">
				<b>เงินสด</b>
			</td>
			<td>
				<?= $invdesc; ?>
			</td>
			<td width="15%" style="text-align: right;">
				<?= number_format($invnetamount,2); ?>
			</td>
			<td width="15%" style="text-align: right;"></td>
		</tr>
			<?php } ?>
		<tr>
			<td colspan="2"></td>
			<td style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000;">
				<b>รวมเงินสด</b>
			</td>
			<td width="15%" style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000;">
				<b id="sumtotalCash"><?=number_format($sumnet,2);?></b>
			</td>
		</tr>
		<?php } else { $sumnet = "0.00"; } ?>




		<?php
			$str_sql_reccheque = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id WHERE paym_typepay = 2 AND paym_reppid = '". $reppid ."'";
			$obj_rs_reccheque = mysqli_query($obj_con, $str_sql_reccheque);

			if (mysqli_num_rows($obj_rs_reccheque) > 0) {
			
				$str_sql_cheq = "SELECT * FROM payment_tb WHERE paym_typepay = 2 AND paym_reppid = '". $reppid ."'";
				$obj_rs_cheq = mysqli_query($obj_con, $str_sql_cheq);
				while ($obj_row_cheq = mysqli_fetch_array($obj_rs_cheq)) {
					$str_sql_paymcheq = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id WHERE inv_paymid = '". $obj_row_cheq["paym_id"] ."'";
					$obj_rs_paymcheq = mysqli_query($obj_con, $str_sql_paymcheq);
					$invdesc = "";
					$invnetamount = 0;
					while ($obj_row_paymcheq = mysqli_fetch_array($obj_rs_paymcheq)) {
						if (mysqli_num_rows($obj_rs_paymcheq) >= 1) {
							$invdesc = $obj_row_paymcheq["inv_description_short"] . " / " . $invdesc;
						} else {
							$invdesc = $obj_row_paymcheq["inv_description_short"];
						}
						$invnetamount += "-".$obj_row_paymcheq["inv_netamount"];
					}

					$str_sql_sumcheque = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id WHERE paym_typepay = 2 AND paym_reppid = '". $reppid ."'";
					$obj_rs_sumcheque = mysqli_query($obj_con, $str_sql_sumcheque);
					$sumnetcheque = 0;
					while ($obj_row_sumcheque = mysqli_fetch_array($obj_rs_sumcheque)) {
						if(mysqli_num_rows($obj_rs_sumcheque) > 0) {
							$sumnetcheque += "-".$obj_row_sumcheque["inv_netamount"];
						} else {
							$sumnetcheque = "0.00";
						}
					}

		?>
		<tr>
			<td width="8%">
				<b>เช็ค</b>
			</td>
			<td>
				<?= $invdesc; ?>
			</td>
			<td width="15%" style="text-align: right;">
				<?= number_format($invnetamount,2); ?>
			</td>
			<td width="15%" style="text-align: right;"></td>
		</tr>
			<?php } ?>
		<tr>
			<td colspan="2"></td>
			<td style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000;">
				<b>รวมเช็ค</b>
			</td>
			<td width="15%" style="text-align: right; border-top: 1px solid #000; border-bottom: 1px solid #000;">
				<b id="sumtotalCheque"><?=number_format($sumnetcheque,2);?></b>
			</td>
		</tr>
		<?php } else { 
				$sumnetcheque = "0.00";
		} ?>

		<tr>
			<td colspan="3">
				<b>คงเหลือ</b>
			</td>
			<td style="text-align: right; border-bottom: 2px double #000;">
				<b><?=number_format($obj_row_repp["repp_total_balance"] + $sumnet + $sumnetcheque,2);?></b>
			</td>
		</tr>
	</table>

</body>

<?php

	$html = ob_get_contents();

	$mpdf->WriteHTML($html);
	$mpdf->Output($name);

	ob_end_flush();	

	mysqli_close($obj_con);

	$link = 'reportpay_seepdf.php?cid='.$_GET["cid"].'&dep='.$_GET["dep"].'&y='.$year.'&m='.$month;

?>

<!-- ดาวโหลดรายงานในรูปแบบ PDF <a href="<?=$link;?>">คลิกที่นี้</a> -->

<script langquage="javascript">
	window.location="<?=$link?>";
</script>

<?php } ?>