<?php
// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';

include 'connect.php';

$cid = $_GET["cid"];
$dep = $_GET["dep"];

$sql_comp = "SELECT comp_name FROM company_tb WHERE comp_id = '". $cid ."' ";
$obj_rs_comp = mysqli_query($obj_con, $sql_comp);
$obj_row_comp = mysqli_fetch_array($obj_rs_comp);

$sql_dep = "SELECT dep_name FROM department_tb WHERE dep_id = '". $dep ."' ";
$obj_rs_dep = mysqli_query($obj_con, $sql_dep);
$obj_row_dep = mysqli_fetch_array($obj_rs_dep);


$str_sql = "SELECT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
                                            INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
                                            INNER JOIN user_tb AS u ON i.inv_userid_create = u.user_id 
                                            WHERE inv_apprMgrno = '' AND inv_depid = '" . $dep . "' AND inv_compid = '" . $cid . "' ORDER BY inv_id DESC";
$obj_rs = mysqli_query($obj_con, $str_sql);

$totalsApproval = mysqli_num_rows($obj_rs);


//Fiunction
function MonthThaiShort($strDate) {
    $strYear = substr(date("Y",strtotime($strDate))+543,-2);
    $strMonth= date("n",strtotime($strDate));
    $strDay= date("j",strtotime($strDate));
    $strHour= date("H",strtotime($strDate));
    $strMinute= date("i",strtotime($strDate));
    $strSeconds= date("s",strtotime($strDate));
    $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
    $strMonthThai = $strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear";
}

// Add Font
$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];
$mpdf = new \Mpdf\Mpdf([
    'format' => 'A4',
    'margin_header' => 4,
    'margin_footer' => 4,
    'margin_bottom' => 15,
    'margin_left' => 6,
    'margin_right' => 6,
    'tempDir' => __DIR__ . '/tmp',
    'fontdata' => $fontData + [
        'sarabun' => [
            'R' => 'THSarabunNew.ttf',
            'I' => 'THSarabunNewItalic.ttf',
            'B' =>  'THSarabunNewBold.ttf',
            'BI' => "THSarabunNewBoldItalic.ttf",
        ]
    ],
    'default_font' => 'sarabun'
]);

ob_start(); 

$footer = '<h4 class="item-right">ออกรายงานวันที่ '.date("d/m/Y H:i:s").' </h4>';

?>


<!DOCTYPE html>
<html>
<head>
<title>รายงาน ใบแจ้งหนี้รอตรวจสอบ </title>
<link href="https://fonts.googleapis.com/css?family=Sarabun&display=swap" rel="stylesheet">
<style>
		body {
			font-family: "sarabun";
		}

        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        .item-left {
            text-align: left;
        }
        
        .item-right {
            text-align: right;
        }

        .item-center {
            text-align: center;
        }

        h3 {
            font-size: 23px;
        }

	</style>
</head>
<body>

<div class="container">
        <h3 class="item-center">รายงาน ใบแจ้งหนี้รอตรวจสอบ <small style="font-size:16px; color: #6c757d">(<?=$totalsApproval?> รายการ)</small></h3>
        <p class="item-center"><?= $obj_row_comp['comp_name']?> 
            <?php if($cid == 'C001') {?>
                <span>ฝ่าย <?= $obj_row_dep['dep_name']?></span>
            <?php }?>
        </p>
        <table>
        <thead>
            <tr>
                <th width="20%">เลขที่ใบแจ้งหนี้</th>
                <th width="38%">รายละเอียด</th>
                <th width="12%" class="item-center">วันที่ครบชำระ</th>
                <th width="10%" class="item-center">จำนวนเงิน</th>
            </tr>
        </thead>
            <tbody>
                <?php 
                    $obj_rs = mysqli_query($obj_con, $str_sql);
                    $i = 1;
                    $subtotal = 0;
                    $tax1 = 0;
                    $tax2 = 0;
                    $tax3 = 0;
                    $netamount = 0;

                    while ($obj_row = mysqli_fetch_array($obj_rs)) { 
                        $subtotal = $obj_row["inv_subtotal"] + $obj_row["inv_vat"] + $obj_row["inv_differencevat"];
                        $tax1 = (($obj_row["inv_tax1"] * $obj_row["inv_taxpercent1"]) / 100) + $obj_row["inv_differencetax1"];
                        $tax2 = (($obj_row["inv_tax2"] * $obj_row["inv_taxpercent2"]) / 100) + $obj_row["inv_differencetax2"];
                        $tax3 = (($obj_row["inv_tax3"] * $obj_row["inv_taxpercent3"]) / 100) + $obj_row["inv_differencetax3"];
                        $netamount = $obj_row["inv_subtotalNoVat"] + $subtotal + $obj_row["inv_difference"] - $tax1 - $tax2 - $tax3;
                    ?>
                        <tr>
                            <td> <?= $obj_row['inv_no'] ?></td>
                            <td>
                                <b>บริษัท : </b> <?= $obj_row['paya_name'] ?> <br>
                                <b>รายการ : </b> <?= $obj_row['inv_description'] ?>
                            </td>
                            <td><?= MonthThaiShort($obj_row['inv_duedate']) ?></td>
                            <td class="item-right"><?= number_format($netamount, 2) ?></td>
                        </tr>
                <?php $i++; }?>
            </tbody>
        </table>


    </div>

</body>
</html>

<?php
	mysqli_close($obj_con);
    ini_set('memory_limit', '512M');

    $mpdf->SetFooter($footer);
    $html = ob_get_contents();

    $fileName = "iv_pending_approval/รายงานใบแจ้งหนี้รอตรวจสอบ" . ".pdf";
    $mpdf->WriteHTML($html);

    $mpdf->Output($fileName);
    ob_end_flush()
?>


<script type="text/javascript">window.location = '<?=$fileName . "?v=" . microtime(true)?>' </script>

