<?php
 include 'config/config.php';
__check_login();

$download = (isset($_GET["download"])) ? $_GET["download"]: 0;
if($download==1){
	$pdf_string = (isset($_POST["pdf_string"])) ? $_POST["pdf_string"]: "";
	$pdf_string = base64_decode($pdf_string);
	
	$pdf_filename = (isset($_POST["pdf_filename"])) ? $_POST["pdf_filename"]: "";
	$pdf_filename = base64_decode($pdf_filename);
	
	if($pdf_string!=""){
		header('Content-Type: text/html; charset=utf-8');
		if (headers_sent()){
			die('Some data has already been output to browser, can\'t send PDF file');
		}
			
		header('Content-Description: File Transfer');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: public, must-revalidate, max-age=0');
		header('Pragma: public');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Content-Type: application/force-download');
		header('Content-Type: application/octet-stream', false);
		header('Content-Type: application/download', false);
		header('Content-Type: application/pdf', false);
		if (!isset($_SERVER['HTTP_ACCEPT_ENCODING']) OR empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
			header('Content-Length: '.strlen($pdf_string));
		}
		
		header('Content-disposition: attachment; filename="'.$pdf_filename.'"');
		echo $pdf_string;
	}else{
		echo "<center><br><br><h1 style='color:red'>ไม่มีข้อมูล</h1></center>";
	}
 }else{
 	$tmp = (isset($_GET["tmp"])) ? $_GET["tmp"]: "";
	$session_data = (isset($_SESSION["session_report_payment_print"])) ? $_SESSION["session_report_payment_print"] : array();
	$url_back = 'report_payment.php?view=1&'. base64_decode($tmp);
	
	if(empty($session_data)){
		header('Location:'. $url_back);
	}

	$obj_query = (isset($session_data["result"])) ? $session_data["result"]: array();
	$title = (isset($session_data["title"])) ? $session_data["title"]: array();
	$title_pdf = str_replace('<i class="icofont-caret-right"></i>', " ", $title);
	$filename =   str_replace(',', "_", $title_pdf);
	$filename =   str_replace(' ', "_", $filename).".pdf";
	
	$n_cash = 1;
	$n_cheque = 1;
	$count_row_cash = 0;
	$count_row_cheque = 0;
	$sum_cash = 0;
	$sum_cheque = 0;
	$html_row_cash  = "";
	$html_row_cheque  = "";
	
	$icon_checkbox = '<img src="image/checkbox.jpg" style="width:17px;height:17px;" align="left">';
	$icon_uncheckbox = '<img src="image/uncheckbox.jpg" style="width:17px;height:17px;" align="left">';
	
	if(count($obj_query) >=1){
		foreach($obj_query as $obj_row) {
			$paym_typepay = $obj_row["paym_typepay"];
			$paym_no = $obj_row["paym_no"];
			$paya_name = $obj_row["paya_name"];
			$inv_description = $obj_row["inv_description"];
			$inv_netamount = $obj_row["inv_netamount"];
				
				$html_col = '<td width="13%">'.$paym_no.'</td>';
				$html_col .= '<td width="28%">'.$paya_name.'</td>';
				$html_col .= '<td width="40%">'.$inv_description.'</td>';
				$html_col .= '<td width="15%" class="text-right">'.__price($inv_netamount).'</td>';
				
			if($paym_typepay==1){
				$html_row_cash .= '<tr>';
					$html_row_cash .= '<td width="3%" class="text-center">'.$n_cash.'.</td>';
					$html_row_cash  .= $html_col;
				$html_row_cash .= '</tr>';
				
				$sum_cash+=$inv_netamount;
				$n_cash++;
				$count_row_cash++;
			}else if($paym_typepay==2){
				
				$html_row_cheque .= '<tr>';
					$html_row_cheque .= '<td width="3%" class="text-center">'.$n_cheque.'.</td>';
					$html_row_cheque  .= $html_col;
				$html_row_cheque .= '</tr>';
				
				$sum_cheque+=$inv_netamount;
				$n_cheque++;
				$count_row_cheque++;
			}
		}
	}
	
	$html_header = "";
	$html_body = "";
	$html_footer= "";
	
	$html_header .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td>
				<b style="font-size: 1.25rem; padding: 0px 0px 8px 0px;">'. $title_pdf .'</b>
			</td>
		</tr>
	</table>';
	
	//body
	$html_body .= '<style>
		body {
			font-family: "sarabun";
		}
		table {
			padding: 1px 0px;
			overflow: wrap;
			font-size: 14pt;
			border-collapse: collapse;
		}
		.table {
			width: 100%;
		}
		.table-bordered td, .table-bordered th {
		    border: 1px solid #000 !important
		}
		.table td, .table th {
			padding: .15rem;
			vertical-align: top;
		}
		.table .thead-light th {
			background-color: #e9ecef;
		}
		.text-right {
			text-align: right;
		}
		.text-left {
			text-align: left;
		}
		.text-center {
			text-align: center;
		}
		
		table.table-checkbox td{
			font-weight:bold;
			font-size: 1.15rem;
			 vertical-align: baseline;	
			 padding-top:14px;
		}
	</style>';	
	
	$check_data = 0;
	if($count_row_cash>=1 || $count_row_cheque>=1){
		$check_data = 1;
		$html_row_head = '<thead class="thead-light">
			<tr>
				<th width="35">ที่</th>
				<th width="100">เลขที่ใบสำคัญจ่าย</th>
				<th width="28%">บริษัทเจ้าหนี้</th>
				<th width="40%">รายละเอียด</th>
				<th width="15%">ยอดชำระสุทธิ</th>
			</tr>
		</thead>';
		if($count_row_cash>=1){
			$html_body .= '<table cellspacing="0" cellpadding="0" border="0" width="100%"  class="table-checkbox">
				<tr>
					<td style="width:20px">'.$icon_checkbox. '</td><td style="width:80px">เงินสด</td>
					<td style="width:20px">'.$icon_uncheckbox. '</td><td>เช็ค</td>
				</tr>
			</table>';
			
				$html_body .= '<table class="table table-bordered">';
				$html_body .= $html_row_head;
				$html_body .= '<tbody>';
					$html_body .= $html_row_cash;
				$html_body .= '</tbody>';
				
				$html_body .= '<tfoot>
					<tr>
						<td class="text-right" style="font-size: 1.15rem;" colspan="4"><b>รวมทั้งหมด</b></td>
						<td class="text-right" style="font-size: 1.15rem;"><b>'.__price($sum_cash).'</b></td>
					</tr>
				</tfoot>';
			$html_body .= '</table>';
		}
	
		
		if($count_row_cash>=1 && $count_row_cheque>=1){
			$html_body .= '<pagebreak />';
		}
	
		if($count_row_cheque>=1){
			$html_body .= '<table cellspacing="0" cellpadding="0" border="0" width="100%"  class="table-checkbox">
				<tr>
					<td style="width:20px">'.$icon_uncheckbox. '</td><td style="width:80px">เงินสด</td>
					<td style="width:20px">'.$icon_checkbox. '</td><td>เช็ค</td>
				</tr>
			</table>';
			
			$html_body .= '<table class="table table-bordered">';
			$html_body .= $html_row_head;
				
			$html_body .= '<tbody>';
				$html_body .= $html_row_cheque;
			$html_body .= '</tbody>';
			
			$html_body .= '<tfoot>
					<tr>
						<td class="text-right" style="font-size: 1.15rem;" colspan="4"><b>รวมทั้งหมด</b></td>
						<td class="text-right" style="font-size: 1.15rem;"><b>'.__price($sum_cheque).'</b></td>
					</tr>
				</tfoot>';
			$html_body .= '</table>';
		}
	}else{
			$html_body .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">';
			$html_body .= '<tbody>';
				$html_body .= '<tr><td style="text-align:center">ไม่มีข้อมูล</td></tr>';
			$html_body .= '</tbody>';
			
			$html_body .= '</table>';
	}
	
	//footer
	$html_footer .= '<table width="100%">
		<tr>
			<td width="33%">{DATE j-m-Y}</td>
			<td width="33%" align="center">{PAGENO}/{nbpg}</td>
			<td width="33%" style="text-align: right;">ใบสำคัญจ่าย</td>
		</tr>
	</table>';
			
	
	require_once __DIR__ . '/vendor/autoload.php';
	
	$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
	$fontDirs = $defaultConfig['fontDir'];
	
	$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
	$fontData = $defaultFontConfig['fontdata'];
	
	$mpdf = new \Mpdf\Mpdf([
		'mode' => 'utf-8',
		'format' => 'A4',
		// 'format' => 'A5',
		'margin_top' => 12,
		'margin_bottom' => 5,
		'margin_left' => 5,
		'margin_right' => 2,
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
	
	$mpdf->SetTitle($filename);
	$mpdf->SetHTMLHeader($html_header);
	$mpdf->SetHTMLFooter($html_footer);
	$mpdf->WriteHTML($html_body);
	$pdf = $mpdf->Output("","S");
	
	if(!empty($session_data)){
		foreach ($session_data as $key => $value) {
			unset($_SESSION["session_report_payment_print"][$key]);
		}
	}
?>

<head>
	<meta charset="utf-8">
	<title><?=$title_pdf;?></title>
	<?php include 'head.php'; ?>
	<style>
		body,html{
			overflow: hidden !important;
		}
		
		.print-content {
			display: block;
			height: calc(100vh - 140px);
			width: 100%;
			padding-bottom: 20px;
			overflow-y: scroll !important;
			background-color: #FFFFFF;
			padding-top: 16px;
			padding-bottom: 16px;
			box-shadow: inset 0px 11px 8px -10px #CCC, inset 0px -11px 8px -10px #CCC;
		}
	</style>
</head>
<body class="loadwindow" <?php if($check_data){ ?>onload="document.getElementById('form').submit();" <?php } ?>>
	<div class="container-fluid">
		<div class="row mt-4 mb-1">
			<div class="col-md-9 col-xs-12">
				<h3><?=$title;?></h3>
			</div>
			<div class="col-md-3 col-xs-12 text-right">
				<form action="?download=1" name="form" id="form"   method="post" >
					<textarea name="pdf_string" id="pdf_string" class="d-none" /><?=base64_encode($pdf);?></textarea>
					<input name="pdf_filename" id="pdf_filename" class="d-none" value="<?=base64_encode($filename);?>" />
						
					<a href="<?=$url_back;?>" class="btn btn-warning btn-sm mb-1"><i class="icofont-history"></i> ย้อนกลับ</a>
					<?php if($check_data){ ?>
					<button type="submit" class="btn btn-info btn-sm mb-1"><i class="icofont-download"></i> ดาวน์โหลดไฟล์ PDF</button>
					<?php } ?>
				</form>
			</div>
		</div>
	</div>
	
	<div class="print-content">
			<div class="col-md-12">
			<?=$html_body;?>
		</div>
	</div>
</body>
<?php } ?>