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
    $strMonthThai = $strMonthCut[$strMonth];

    return array($strDay,$strMonthThai,$strYear);
}

$tax_id = isset($_GET['tax_id']) ? $_GET['tax_id'] : '';
$download = isset($_GET['download']) ? $_GET['download'] : 0;

$str_sql = "SELECT *,tl.created_at AS taxlist_date,t.tax_created_at AS tax_date FROM taxpurchase_tb AS t
            INNER JOIN taxpurchaselist_tb AS tl ON t.tax_id = tl.list_tax_id
            INNER JOIN company_tb AS c ON t.tax_comp_id = c.comp_id
            INNER JOIN payable_tb as ct ON tl.list_paya_id = ct.paya_id
            WHERE t.tax_id = '$tax_id'";
            
$obj_query = mysqli_query($obj_con, $str_sql);
$obj_row_tax = mysqli_fetch_assoc($obj_query);

$filename = "รายงานใบภาษีซื้อรายเดือน" . $obj_row_tax['tax_id'];

$date_head = DateMonthThai($obj_row_tax['tax_date'],"head");

$expprt_pdf = false;
if(isset($_GET['export'])){
	if($_GET['export']== 'excel'){
		header("Content-Type: application/xls");
		header("Content-Disposition: attachment; filename=$filename.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
        
        $download = 0;
	}else if($_GET['export']== 'pdf'){
	    $expprt_pdf = true;
        $filename = "รายงานใบภาษีซื้อรายเดือน" . $obj_row_tax['tax_id'] . ".pdf";
	}
}

if($expprt_pdf){
   require_once __DIR__ . '/vendor/autoload.php';
    
    $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];
    $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];
                
    $mpdf = new \Mpdf\Mpdf([
        'useActiveForms' => true,
        'mode' => 'utf-8',
        'format' => 'A4',
        // 'format' => 'A5',
        'margin_top' => 46,
        'margin_bottom' => 5,
        'margin_left' => 5,
        'margin_right' => 2,
        // 'margin_header' => 5,    
        // 'margin_footer' => 5,     
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
    
    $header = '
        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="tb_head">
            <tr>
                <td width="33%"></td>
                <td width="33%" style="text-align: center; font-size: 18pt;">
                    <b>รายงานภาษีซื้อ</b>
                </td>
                <td width="33%" style="text-align: right; font-size: 12pt;"><strong>หน้า {PAGENO}/{nb}</strong></td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="tb_head">
            <tr>
                <td style="text-align: center; font-size: 16pt;">
                    <b>เดือน '. $date_head[1] . ' ' . $date_head[2] .'</b>
                </td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="tb_head">
            <tr>
                <td width="20%" style="font-size: 12pt; padding: 3px 0px">
                    <b>ชื่อผู้ประกอบการ</b>
                </td>
                <td width="45%" style="font-size: 12pt; padding: 3px 0px">
                    <b>'. $obj_row_tax['comp_name'] .'</b>
                </td>
                <td style="font-size: 12pt;">
                    <b>เลขประจำตัวผู้เสียภาษีอากร '. $obj_row_tax['comp_taxno'] .'</b>
                </td>
            </tr>
            <tr>
                <td width="20%" style="font-size: 12pt; padding: 3px 0px">
                    <b>ชื่อสถานประกอบการ</b>
                </td>
                <td width="45%" style="font-size: 12pt; padding: 3px 0px">
                    <b>'. $obj_row_tax['comp_name'] .'</b>
                </td>
                <td style="font-size: 12pt; padding: 3px 0px">
                    <b>สำนักงานใหญ่</b>
                </td>
            </tr>
        </table>

        <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td style="height: 10px"></td>
            </tr>
        </table>
        ';
    ob_start();  
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        
        <?php if($expprt_pdf){ ?>
        <style>
                body {
                    font-family: 'Sarabun', sans-serif;
                }
                table {
                    padding: 1px 0px;
                    overflow: wrap;
                    font-size: 11pt;
                    border-collapse: collapse;
                }
             
                 table.txtbody th {
                    text-align: center;
                }
                
                table.txtbody td {
                    text-align: center;
                    vertical-align: top;
                }
                table.txtbody td, table.txtbody th {
                    border: 1px solid #000;
                    padding: 3px 2px;
                }
                
                .tb_head td{
                    font-weight: bold;
                 }
                 
                 .txtbody tfoot td{
                     font-weight: bold;
                 }
        </style>	
        <?php } ?>
        
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
				     <?php if(!$expprt_pdf){ ?>
                    <h3 class="text-center"><b>รายงานภาษีซื้อ</b></h3>
                    <h3 class="text-center"><b>เดือน <?= $date_head[1] . ' ' . $date_head[2] ?></b></h3>
                    <h3 class="text-center"><b>ชื่อผู้ประกอบการ</b>  <?= $obj_row_tax['comp_name'] ?> <b>เลขประจำตัวผู้เสียภาษีอากร</b> <?= $obj_row_tax['comp_taxno'] ?></h3>
                    <h3 class="text-center"><b>ชื่อสถานประกอบการ</b> <?= $obj_row_tax['comp_name'] ?> <b>สำนักงานใหญ่</b></h3>
				      <?php } ?>
					<table border="1" class="table txtbody">
						<thead>
							<tr class="info">
								<th <?= ($expprt_pdf) ? 'width="4%"': "";?>>ลำดับ</th>
								<th <?= ($expprt_pdf) ? 'width="10%"': "";?>>วันที่</th>
								<th <?= ($expprt_pdf) ? 'width="20%"': "";?>>เล่มที่/เลขที่</th>
								<th <?= ($expprt_pdf) ? 'width="38%"': "";?>>ชื่อผู้ซื้อ/ผู้ให้บริการ/รายการ</th>
                                <th <?= ($expprt_pdf) ? 'width="10%"': "";?>>จำนวนเงิน</th>
                                <th <?= ($expprt_pdf) ? 'width="9%"': "";?>>ภาษีมูลค่าเพิ่ม</th>
                                <th <?= ($expprt_pdf) ? 'width="9%"': "";?>>จำนวนเงินรวม</th>
							</tr>
						</thead>
                        <tbody>
                            <?php $i = 1; foreach($obj_query as $obj_row) { ?>
                        	<tr>
								<td><?= $i++ ?></td>
								<td><?= DateThai($obj_row['taxlist_date']) ?></td>
								<td><?= $obj_row['list_no'] ?></td>
								<td style="text-align: left;"><?= $obj_row['paya_name'] . " / " . $obj_row['list_desc'] ?></td>
                                <td style="text-align: right;"><?= number_format($obj_row['list_price'],2) ?></td>
								<td style="text-align: right;"><?= number_format($obj_row['list_vat'],2) ?></td>
								<td style="text-align: right;"><?= number_format($obj_row['list_result'],2) ?></td>

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
                                <td style="text-align: right;<?php if(!$expprt_pdf){ ?>color: #F00;"<?php } ?>;">
                                    <b><?= number_format($sum_subtotal,2) ?></b>
                                </td>
                                <td style="text-align: right;<?php if(!$expprt_pdf){ ?>color: #F00<?php } ?>;">
                                    <b><?= number_format($sum_vat,2) ?></b>
                                </td>
                                <td  style="text-align: right;<?php if(!$expprt_pdf){ ?>color: #F00;"<?php } ?>;">
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
<?php
if($expprt_pdf){
    $html = ob_get_clean();ob_end_clean(); 
    $mpdf->SetHeader($header);
    $mpdf->WriteHTML($html);
    
    if($download){
        $mpdf->Output($filename,"D");
    }else{
        $mpdf->Output($filename,"I");
    }
}
?>
