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
        'margin_top' => 64,
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

    $header = '<table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td width="33%"></td>
                <td width="33%" style="text-align: center; font-size: 18pt;">
                    <b>รายงานภาษีซื้อ</b>
                </td>
                <td width="33%" style="text-align: right; font-size: 12pt;"><strong>หน้า {PAGENO}/{nb}</strong></td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td style="text-align: center; font-size: 16pt;">
                    <b>เดือน '. $date_head[1] . ' ' . $date_head[2] .'</b>
                </td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td style="font-size: 12pt; padding: 3px 0px;width: 100%">
                    <b>ชื่อผู้ประกอบการ &nbsp;&nbsp;&nbsp;&nbsp; บริษัท ธรรมบุรี จำกัด </b>
                </td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" border="0" width="70%">
            <tr>
                <td width="68%"></td>
                <td class="b">0</td>
                <td class="b">1</td>
                <td class="b">0</td>
                <td class="b">5</td>
                <td class="b">5</td>
                <td class="b">3</td>
                <td class="b">8</td>
                <td class="b">1</td>
                <td class="b">0</td>
                <td class="b">6</td>
                <td class="b">4</td>
                <td class="b">6</td>
                <td class="b">1</td>
            </tr>
        </table>


        <table cellspacing="0" cellpadding="0" border="0" width="90%" style="margin-top: 10px">
            <tr>
                <td width="62%"></td>
                <td class="b">&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;</td>
                <td>สำนักงานใหญ่</td>
                <td>&nbsp;&nbsp;</td>
                <td class="b">/</td>
                <td>&nbsp;&nbsp;</td>
                <td>สาขาที่</td>
                <td>&nbsp;&nbsp;</td>
                <td></td>
                <td class="b">0</td>
                <td class="b">0</td>
                <td class="b">0</td>
                <td class="b">1</td>
            </tr> 
        </table>   
        <table>
            <tr>
                <td width="100%" style="font-size: 12pt; padding: 3px 0px">
                    <b>ชื่อสถานประกอบการ &nbsp;&nbsp;&nbsp;&nbsp; บริษัท ธรรมบุรี จำกัด </b>
                </td>
            </tr>
        </table>

        <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td style="height: 10px"></td>
            </tr>
        </table>';
        ob_start(); 
}
        ?>

        <!DOCTYPE html>

        <head>
            
            <title></title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

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

                table.txtbody td {
                    text-align: center;
                    vertical-align: top;
                }
                table.txtbody td, table.txtbody th {
                    border: 1px solid #000;
                    padding: 3px 2px;
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

                .b{
                    padding: 8px;
                    border: 1px solid #000;
                }

                .tb{
                    border: 1px solid #000;
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
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

            <?php if(!$expprt_pdf){ ?>
                <h3 class="text-center"><b>รายงานภาษีซื้อ</b></h3>
                <h3 class="text-center"><b>เดือน <?= $date_head[1] . ' ' . $date_head[2] ?></b></h3>
                <h3 class="text-center"><b>ชื่อผู้ประกอบการ</b>  <?= $obj_row_tax['comp_name'] ?> </h3>
                <h3 class="text-center"><b>เลขประจำตัวผู้เสียภาษีอากร</b> <?= $obj_row_tax['comp_taxno'] ?></h3>
                <h3 class="text-center"><b>สาขาที่ 0001</b></h3>
                <h3 class="text-center"><b>ชื่อสถานประกอบการ</b> <?= $obj_row_tax['comp_name'] ?></h3>
            <?php } ?>


            <table class="txtbody" cellspacing="0" cellpadding="0" border="0" width="100%">
            <thead style="text-align: center">
                <tr>
                    <th rowspan="2" class="text-center" width="5%">ลำดับ</th>
                    <th colspan="2" class="text-center" width="20%">ใบกำกับภาษี</th>
                    <th rowspan="2" class="text-center" width="12%">เลขประจำตัว<br>ผู้เสียภาษี</th>
                    <th rowspan="2" class="text-center" width="26%">ชื่อผู้ซื้อ/ผู้ให้บริการ/รายการ</th>
                    <th rowspan="2" class="text-center" width="10%">มูลค่าสินค้า<br>หรือบริการ</th>
                    <th rowspan="2" class="text-center" width="10%">จำนวนเงิน<br>ภาษีมูลค่าเพิ่ม</th>
                    <th rowspan="2" class="text-center" width="10%">จำนวนเงิน<br>ภาษีที่เฉลี่ย</th>
                    <th rowspan="2" class="text-center" width="7%">หมายเหตุ<br> % เฉลี่ย</th>
                </tr>
                <tr>
                    <th class="text-center" width="10%">วัน/เดือน/ปี</th>
                    <th class="text-center" width="10%">เล่มที่/เลขที่</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach($obj_query as $obj_row) { 

                    $vat_avg = $obj_row['list_vat'] * $obj_row['list_avg_vat_percent'] / 100;            
                    $vat_avg_all += sprintf('%0.2f', $vat_avg);
                ?>
                    
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= DateThai($obj_row['taxlist_date']) ?></td>
                    <td><?= $obj_row['list_no'] ?></td>
                    <td><?= $obj_row['paya_taxno'] ?></td>
                    <td style="text-align:left"><?= $obj_row['paya_name'] ?></td>
                    <td style="text-align:right"><?= number_format($obj_row['list_price'],2) ?></td>
                    <td style="text-align:right"><?= number_format($obj_row['list_vat'],2) ?></td>
                    <td style="text-align:right"><?= number_format($vat_avg,2) ?></td>
                    <td style="text-align:right"><?= $obj_row['list_avg_vat_percent'] ?>%</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align:left"><?= $obj_row['list_desc'] ?></td>
                    <td></td> 
                    <td></td> 
                    <td></td> 
                    <td></td>       
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="5" style="border: none;"></td>
                    <td style="text-align: right;"><b><?= number_format($obj_row['tax_price'],2) ?></b></td>
                    <td style="text-align: right;"><b><?= number_format($obj_row['tax_vat'],2) ?></b></td>
                    <td style="text-align: right;"><b><?= number_format($vat_avg_all,2) ?></b></td>
                    <td></td>
                </tr>
            </tbody>
            </table>
        </body>
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

