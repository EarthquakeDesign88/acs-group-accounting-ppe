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
		$projid = $_GET["projid"];

		function DateMonthThai($strDate) {
			$strYear = date("Y",strtotime($strDate))+543;
			$strMonth = date("n",strtotime($strDate));
			$strDay = date("j",strtotime($strDate));
			$strHour = date("H",strtotime($strDate));
			$strMinute = date("i",strtotime($strDate));
			$strSeconds = date("s",strtotime($strDate));
			$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
			$strMonthThai = $strMonthCut[$strMonth];
			return "$strDay $strMonthThai $strYear";
		}

		$str_sql = "SELECT * FROM invoice_rcpt_desc_tb AS ird 
					INNER JOIN project_tb AS p ON ird.invrcptD_projid = p.proj_id 
					INNER JOIN customer_tb AS c ON ird.invrcptD_custid = c.cust_id 
					WHERE proj_compid = '". $cid ."' AND proj_depid = '". $dep ."' AND invrcptD_projid = '". $projid ."'";
		$obj_rs = mysqli_query($obj_con, $str_sql);
		$projid = '';
		while ($obj_row = mysqli_fetch_array($obj_rs)) {
			// echo $obj_row["invrcptD_id"]."<br>";
			$projid = $obj_row["proj_id"];
			$projname = $obj_row["proj_name"];
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
			'margin_top' => 25,
			'margin_bottom' => 5,
			'margin_left' => 15,
			'margin_right' => 5,
			'margin_header' => 5,     // 9 margin header
			'margin_footer' => 5,     // 9 margin footer
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

		$header = '<b style="font-size: 20pt; padding: 5px 3px;">'. $projname .'</b>
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
						<th colspan="3" class="text-center" style="border: 1px solid #000; padding: 6px 3px;">
							<b>รายการ</b>
						</th>
						<th width="20%" class="text-center" style="border: 1px solid #000; border-left: none; padding: 6px 3px;">
							<b>จำนวนเงิน (ไม่รวมภาษี)</b>
						</th>
					</tr>
				</table>';

		$filename = 'project/'.$projid.".pdf";

		ob_start(); // Start get HTML code

?>
<!DOCTYPE html>

<div style="width: 100%; height: 100%; position: absolute; left: 0px; top: 0px; background: rgba(33, 33, 33, 0.7);">

	<div class="loader"></div>
	<div style="z-index: 1;position: absolute; left: 42%; top: 53%; display: table-cell; vertical-align: middle; font-size: 24pt; color: #FFF; font-weight: 700">กรุณารอสักครู่...</div>

<head>
	
	<title>สรุปรายการ - <?=$filename;?></title>
	<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="plugins/fontawesome/css/all.min.css">
	<link rel="icon" href="image/acs.png" type="images/png" sizes="16x16">

	<style>
		body {
			font-family: 'Sarabun', sans-serif;
		}
		table {
			padding: 1px 0px;
			overflow: wrap;
			font-size: 15pt;
		}
		.loader {
			position: absolute;
			left: 50%;
			top: 50%;
			z-index: 1;
			width: 60px;
			height: 60px;
			margin: -76px 0 0 -76px;
			border: 16px solid #f3f3f3;
			border-radius: 50%;
			border-top: 16px solid #3498db;
			-webkit-animation: spin 2s linear infinite;
					animation: spin 2s linear infinite;
		}

		/* Safari */
		@-webkit-keyframes spin {
			0% { -webkit-transform: rotate(0deg); }
			100% { -webkit-transform: rotate(360deg); }
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}
	</style>

</head>
<body>


	<?php
		$str_sql_chkPart = "SELECT * FROM project_tb WHERE proj_id = '".$projid."'";
		$obj_rs_chkPart = mysqli_query($obj_con, $str_sql_chkPart);
		$obj_row_chkPart = mysqli_fetch_array($obj_rs_chkPart);

		$stsPart = $obj_row_chkPart["proj_part"];


		$str_sql_chkPartSub = "SELECT * FROM project_sub_tb WHERE projS_projid = '".$projid."'";
		$obj_rs_chkPartSub = mysqli_query($obj_con, $str_sql_chkPartSub);
		$obj_row_chkPartSub = mysqli_fetch_array($obj_rs_chkPartSub);

		if($stsPart == 0) {

	?>
		
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<?php 
				$str_sql_ird = "SELECT * FROM invoice_rcpt_desc_tb WHERE invrcptD_projidSub = '".$obj_row_chkPartSub["projS_id"]."'";
				$obj_rs_ird = mysqli_query($obj_con, $str_sql_ird);
				while($obj_row_ird = mysqli_fetch_array($obj_rs_ird)) { 
			?>
			<tr>
				<td colspan="2" style="border: 1px solid #000; border-top: none; padding: 5px 3px; border-right: none;">
					<b>งวดที่ : </b><?=$obj_row_ird["invrcptD_lesson"];?>
				</td>
				<td style="border: 1px solid #000; border-left: none; border-top: none; padding: 5px 3px;"></td>
				<td style="border: 1px solid #000; border-left: none; border-top: none; padding: 5px 3px; text-align: right;">
				</td>
			</tr>
				
				<?php
					// $str_sql_projSPart = "SELECT * FROM invoice_rcpt_desc_tb AS ird INNER JOIN project_sub_tb AS projS ON ird.invrcptD_projidSub = projS.projS_id INNER JOIN invoice_rcpt_tb AS ir ON ird.invrcptD_irid = ir.invrcpt_id WHERE projS_projid = '".$projid."' AND invrcptD_lesson = '".$obj_row_ird["invrcptD_lesson"]. "'";

					$str_sql_projSPart = "SELECT * FROM project_sub_tb AS projS INNER JOIN invoice_rcpt_desc_tb AS ird ON projS.projS_id = ird.invrcptD_projidSub WHERE projS_projid = '".$projid."' AND invrcptD_lesson = '".$obj_row_ird["invrcptD_lesson"]. "'";
					$obj_rs_projSPart = mysqli_query($obj_con, $str_sql_projSPart);
					while($obj_row_projSPart = mysqli_fetch_array($obj_rs_projSPart)) {
				?>
				<tr>
					<td width="5%" style="border: 1px solid #000; border-right: none; border-top: none;"></td>
					<td colspan="2" style="border: 1px solid #000; border-left: none; border-top: none; padding: 5px 3px;">
						<b><?=$obj_row_projSPart["invrcptD_id"];?></b>
						<b>ส่วนย่อย : </b> <?=$obj_row_projSPart["projS_description"];?>
					</td>
					<td width="20%" style="border: 1px solid #000; border-left: none; border-top: none; padding: 5px 3px; text-align: right;">
						<?=number_format($obj_row_projSPart["invrcptD_subtotal"],2);?>
					</td>
				</tr>
				<?php } ?>

			<?php } ?>


			<?php
				$str_sql_sum = "SELECT * FROM invoice_rcpt_desc_tb WHERE invrcptD_projid = '". $projid ."'";
				$obj_rs_sum = mysqli_query($obj_con, $str_sql_sum);
				$sum = 0;
				while($obj_row_sum = mysqli_fetch_array($obj_rs_sum)) {
					$sum = $obj_row_sum["invrcptD_subtotal"] + $sum;
				}
			?>
			<tr>
				<td colspan="3" style="border: 1px solid #000; text-align: right; padding: 6px 3px;">
					<b>รวมเงินทั้งหมด</b>
				</td>
				<td width="20%" style="border: 1px solid #000; border-left: none; text-align: right; padding: 6px 3px;">
					<b><?=number_format($sum,2);?></b>
				</td>
			</tr>
		</table>


	<?php } else { ?>

		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<?php
				$str_sql_ird = "SELECT * FROM invoice_rcpt_desc_tb WHERE invrcptD_projid = '".$projid."'";
				$obj_rs_ird = mysqli_query($obj_con, $str_sql_ird);
				while($obj_row_ird = mysqli_fetch_array($obj_rs_ird)) {
			?>
			<tr>
					<td width="5%" style="border: 1px solid #000; border-right: none; border-top: none;"></td>
					<td style="border: 1px solid #000; border-left: none; border-top: none; padding: 5px 3px; border-right: none;">
						<b><?=$obj_row_ird["invrcptD_id"];?></b>
						<b>งวดที่ : </b><?=$obj_row_ird["invrcptD_lesson"];?>
					</td>
					<td style="border: 1px solid #000; border-left: none; border-top: none; padding: 5px 3px;"></td>
					<td width="20%" style="border: 1px solid #000; border-left: none; border-top: none; padding: 5px 3px; text-align: right;">
						<?=number_format($obj_row_ird["invrcptD_subtotal"],2);?>
					</td>
				</tr>
			<?php } ?>

			<?php
				$str_sql_sum = "SELECT * FROM invoice_rcpt_desc_tb WHERE invrcptD_projid = '". $projid ."'";
				$obj_rs_sum = mysqli_query($obj_con, $str_sql_sum);
				$sum = 0;
				while($obj_row_sum = mysqli_fetch_array($obj_rs_sum)) {
					$sum = $obj_row_sum["invrcptD_subtotal"] + $sum;
				}
			?>
			<tr>
				<td colspan="3" style="border: 1px solid #000; text-align: right; padding: 6px 3px;">
					<b>รวมเงินทั้งหมด</b>
				</td>
				<td width="20%" style="border: 1px solid #000; border-left: none; text-align: right; padding: 6px 3px;">
					<b><?=number_format($sum,2);?></b>
				</td>
			</tr>
		</table>

	<?php } ?>

</body>

<?php

	$html = ob_get_contents();

	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($html);
	// $mpdf->SetHTMLFooter($footer);
	$mpdf->Output($filename);
	ob_end_flush();	

	mysqli_close($obj_con);

	// $link = 'invoice_rcpt_print_copy_acsd.php?cid='.$_GET["cid"].'&dep='.$_GET["dep"].'&m='.$month.'&irID='.$irID;

?>
<!-- ดาวโหลดรายงานในรูปแบบ PDF <a href="<?=$filename?>">คลิกที่นี้</a> -->

<script langquage="javascript">
	window.location="<?=$filename;?>";
</script>

</div>

<?php } ?>