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
		$df = $_GET["df"];
		$dt = $_GET["dt"];
		$custid = $_GET["custid"];
		$status = $_GET['sts'];

		function DateThai($strDate) {
			$strYear = substr(date("Y",strtotime($strDate))+543,-2);
			$strMonth = date("n",strtotime($strDate));
			$strDay = date("j",strtotime($strDate));
			$strHour = date("H",strtotime($strDate));
			$strMinute = date("i",strtotime($strDate));
			$strSeconds = date("s",strtotime($strDate));
			$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
			$strMonthThai = $strMonthCut[$strMonth];
			return "$strDay $strMonthThai $strYear";
		}

		$str_sql_comp = "SELECT * FROM invoice_rcpt_tb AS ir 
						INNER JOIN company_tb AS c ON ir.invrcpt_compid = c.comp_id 
						INNER JOIN customer_tb AS cust ON ir.invrcpt_custid = cust.cust_id 
						WHERE invrcpt_compid = '". $cid ."' AND invrcpt_date BETWEEN '". $df ."' AND '". $dt ."' ORDER BY invrcpt_no ASC ";
		$obj_rs_comp = mysqli_query($obj_con, $str_sql_comp);
		$obj_row_comp = mysqli_fetch_array($obj_rs_comp);

		// Require composer autoload
		require_once __DIR__ . '/vendor/autoload.php';

		$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
		$fontDirs = $defaultConfig['fontDir'];

		$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
		$fontData = $defaultFontConfig['fontdata'];

		$mpdf = new \Mpdf\Mpdf([
			'useActiveForms' => true,
			'mode' => 'utf-8',
			'format' => 'A4-L',
			// 'format' => 'A5',
			'margin_top' => 32.5,
			'margin_bottom' => 5,
			'margin_left' => 5,
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

		$datefooter = date('d');
		$monthfooter = date('m');
		$yearfooter = date('Y') + 543;

		$header = '<table cellspacing="0" cellpadding="0" border="0" width="100%" style="border: none;">
					<tr>
						<td width="50%" style="border: none; font-size: 24pt;">
							<b>สรุปรายงานใบแจ้งหนี้วันที่ ' .DateThai($df) .' - '. DateThai($dt) .'</b>
						</td>
						<td width="50%" style="border: none; text-align: right;">
							หน้า {PAGENO}/{nb}
						</td>
					</tr>
				</table>
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<th style="padding: 8px 2px; font-size: 14pt;" width="9%"><b>เลขที่<br>ใบแจ้งหนี้</b></th>
						<th style="padding: 8px 2px; font-size: 14pt;" width="7%"><b>วันที่<br>ใบแจ้งหนี้</b></th>
						<th style="padding: 8px 2px; font-size: 14pt;" width="9%"><b>เลขที่<br>ใบเสร็จรับเงิน</b></th>
						<th style="padding: 8px 2px; font-size: 14pt;" width="7%"><b>วันที่<br>ใบเสร็จรับเงิน</b></th>
						<th style="padding: 8px 2px; font-size: 14pt;"><b>ชื่อผู้ซื้อ/ผู้รับบริการ/รายการ</b></th>
						<th style="padding: 8px 2px; font-size: 14pt;" width="10%"><b>จำนวนเงิน</b></th>
						<th style="padding: 8px 2px; font-size: 14pt;" width="8%"><b>ภาษี<br>มูลค่าเพิ่ม</b></th>
						<th style="padding: 8px 2px; font-size: 14pt;" width="10%"><b>จำนวนเงินรวม</b></th>
					</tr>
				</table>';

		$footer = '<table cellspacing="0" cellpadding="0" border="0" width="100%" style="border: none;">
					<tr>
						<td width="33.33%" style="border: none;">'.$datefooter.'/'.$monthfooter.'/'.$yearfooter.'</td>
						<td width="33.33%" style="text-align: center; border: none;"></td>
						<td width="33.33%" style="text-align: right; border: none;">รายงานใบแจ้งหนี้</td>
					</tr>
				</table>';

		$path = "report/revenue/invoice/";
		$compname = $obj_row_comp["comp_name"];

		// if ($_FILES['inputFile']['name'] != '') {

			$pathComp = $path . $compname;

			$year_folder = $pathComp . '/' . (date("Y")+543);
			// $month_folder = $year_folder . '/' . date("m");	

			!file_exists($pathComp) && mkdir($pathComp , 0777);
			!file_exists($year_folder) && mkdir($year_folder , 0777);
			// !file_exists($month_folder) && mkdir($month_folder, 0777);

			$type = ".pdf";
			$newname = 'รายงานใบแจ้งหนี้ ระหว่างวันที่ '. DateThai($df) .' ถึง '. DateThai($dt) .''.$type;
			$pathimage = $year_folder;
			$pathCopy = $year_folder . '/' . $newname;

			// move_uploaded_file($_FILES["inputFile"]["tmp_name"], $pathCopy);

		// }

		$name = $pathCopy;

		// $filename = 'invoice/revenue/Report/รายงานใบแจ้งหนี้ วันที่ '.DateThai($df).' - '.DateThai($dt).'.pdf';

		ob_start(); // Start get HTML code

?>

<!DOCTYPE html>

<div style="width: 100%; height: 100%; position: absolute; left: 0px; top: 0px; background: rgba(33, 33, 33, 0.7);">

	<div class="loader"></div>
	<div style="z-index: 1;position: absolute; left: 42%; top: 53%; display: table-cell; vertical-align: middle; font-size: 24pt; color: #FFF; font-weight: 700">กรุณารอสักครู่...</div>

<head>

	<title>สรุปรายงานใบเสร็จรับเงินวันที่ <?=DateThai($df);?> - <?=DateThai($dt);?></title>
	<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="plugins/fontawesome/css/all.min.css">

	<style>
		body {
			font-family: 'Sarabun', sans-serif;
		}
		table {
			padding: 1px 0px;
			overflow: wrap;
			font-size: 14pt;
			vertical-align: middle;
			border: 1px solid #000;
			border-collapse: collapse;
		}
		table th , table td {
			border: 1px solid #000;
			border-collapse: collapse;
		}
		table td {
			padding: 2px 5px;
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

	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<?php
			if($custid == '') {

				$str_sql = "SELECT * FROM invoice_rcpt_tb AS ir 
							INNER JOIN company_tb AS c ON ir.invrcpt_compid = c.comp_id 
							INNER JOIN customer_tb AS cust ON ir.invrcpt_custid = cust.cust_id 
							INNER JOIN department_tb AS d ON ir.invrcpt_depid = d.dep_id 
							WHERE invrcpt_compid = '". $cid ."' AND invrcpt_date BETWEEN '". $df ."' AND '". $dt ."'";
				
			} else {

				$str_sql = "SELECT * FROM invoice_rcpt_tb AS ir 
							INNER JOIN company_tb AS c ON ir.invrcpt_compid = c.comp_id 
							INNER JOIN customer_tb AS cust ON ir.invrcpt_custid = cust.cust_id 
							INNER JOIN department_tb AS d ON ir.invrcpt_depid = d.dep_id 
							WHERE invrcpt_compid = '". $cid ."' AND invrcpt_custid = '". $custid ."' AND invrcpt_stsid = '$status' AND invrcpt_date BETWEEN '". $df ."' AND '". $dt ."' ";

			}
			
			if($status != ""){
				$str_sql .= " AND invrcpt_stsid = '$status'";
			}

			$obj_rs = mysqli_query($obj_con, $str_sql);
			
			while ($obj_row = mysqli_fetch_array($obj_rs)) {

				if($obj_row["invrcpt_book"] == '') {
					$invoiceno = $obj_row["invrcpt_no"];
				} else {
					$invoiceno = $obj_row["invrcpt_book"] . "/" . $obj_row["invrcpt_no"];
				}

				$str_sql_rcpt = "SELECT * FROM receipt_tb WHERE re_id = '". $obj_row["invrcpt_reid"] ."'";
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

				$subtotal = $obj_row['invrcpt_stsid'] != 'STS003' ? $obj_row['invrcpt_subtotal'] : 0;
				$vat = $obj_row['invrcpt_stsid'] != 'STS003' ? $obj_row['invrcpt_vat'] + $obj_row['invrcpt_differencevat'] : 0;
				$grandtotal = $obj_row['invrcpt_stsid'] != 'STS003' ? $obj_row['invrcpt_grandtotal'] + $obj_row['invrcpt_differencevat'] + $obj_row['invrcpt_differencegrandtotal'] : 0;

		?>
		<tr>
			<td width="9%" style="text-align: center;">
				<?= $invoiceno; ?>
			</td>
			<td width="7%" style="text-align: center;">
				<?= DateThai($obj_row['invrcpt_date']); ?>
			</td>
			<td width="9%" style="text-align: center;">
				<?= $receiptno; ?>
			</td>
			<td width="7%" style="text-align: center;">
				<?= $receiptdate; ?>
			</td>
			<td>
				<?= $obj_row['invrcpt_stsid'] != 'STS003'? $obj_row['cust_name'] : 'ยกเลิก'; ?>
			</td>
			<td width="10%" style="text-align: right;">
				<?= $obj_row['invrcpt_stsid'] != 'STS003'? number_format($subtotal,2) : 0; ?>
			</td>
			<td width="8%" style="text-align: right;">
				<?= $obj_row['invrcpt_stsid'] != 'STS003'? number_format($vat,2) : 0; ?>
			</td>
			<td width="10%" style="text-align: right;">
				<?= $obj_row['invrcpt_stsid'] != 'STS003'? number_format($grandtotal,2) : 0; ?>
			</td>
		</tr>
		<?php } ?>
		<?php
			if($custid == '') {
				$str_sql_sum = "SELECT * FROM invoice_rcpt_tb WHERE invrcpt_compid = '". $cid ."' AND invrcpt_date BETWEEN '". $df ."' AND '". $dt ."'";
			} else {
				$str_sql_sum = "SELECT * FROM invoice_rcpt_tb WHERE invrcpt_compid = '". $cid ."' AND invrcpt_date BETWEEN '". $df ."' AND '". $dt ."' AND invrcpt_custid = '". $custid ."'";
			}

			if($status != ''){
				$str_sql_sum .= " AND invrcpt_stsid = '$status'";
			} else {
				$str_sql_sum .= " AND invrcpt_stsid <> 'STS003'";
			}

			$obj_rs_sum = mysqli_query($obj_con, $str_sql_sum);
			$Sumsub = 0;
			$Sumvat = 0;
			$Sumgrand = 0;
			while($obj_row_sum = mysqli_fetch_array($obj_rs_sum)) {
				if($status == 'STS003'){
					$Sumsub = 0;
					$Sumvat = 0;
					$Sumgrand = 0;
				}else{
					$Sumsub = $obj_row_sum["invrcpt_subtotal"] + $Sumsub;
					$Sumvat = $obj_row_sum["invrcpt_vat"] + $obj_row_sum["invrcpt_differencevat"] + $Sumvat;
					$Sumgrand = $obj_row_sum["invrcpt_grandtotal"] + $obj_row_sum["invrcpt_differencevat"] + $obj_row_sum["invrcpt_differencegrandtotal"] + $Sumgrand;
				}
			}
		?>
		<tr>
			<td colspan="4" style="border-rigth: none;"></td>
			<td style="border-left: none; text-align: right;"><b>ยอดรวม</b></td>
			<td style="text-align: right; color: #F00;"><?= number_format($Sumsub,2); ?></td>
			<td style="text-align: right; color: #F00;"><?= number_format($Sumvat,2); ?></td>
			<td style="text-align: right; color: #F00;"><?= number_format($Sumgrand,2); ?></td>
		</tr>
	</table>

</body>

<?php 

	$html = ob_get_contents();

	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($html);
	$mpdf->SetHTMLFooter($footer);
	$mpdf->Output($name);
	ob_end_flush();	

	mysqli_close($obj_con);

	// $link = 'download.php?filename='. $name;

?>

<!-- ดาวโหลดรายงานในรูปแบบ PDF <a href="<?=$name?>">คลิกที่นี้</a> -->

<script langquage="javascript">
	window.location="<?=$name . '?v=' . microtime(true) ?>";
</script>

</div>

<?php } ?>