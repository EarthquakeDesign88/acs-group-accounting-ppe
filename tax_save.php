<?php
	date_default_timezone_set("Asia/Bangkok");
	header('Content-Type: application/json');
	session_start();

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
            $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
        }else{
            $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        }
        $strMonthThai = $strMonthCut[$strMonth];

        return array($strDay,$strMonthThai,$strYear);
    }

	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {
        if(isset($_POST['action'])) {
            if($_POST['action'] === "create"){
                include 'connect.php';
            
                $cid = $_POST['comp_id'];
                $dep = $_POST['dep_id'];
                $date = $_POST['date'];
                $tax_no = $_POST['tax_no'];
                $data = $_POST['data'];
                $price_all = $_POST['price_all'];
                $vat_all = $_POST['vat_all'];
                $result_all = $_POST['result'];
                $data_count = count($data) / 25;
                
                $sql_query_d = "SELECT dep_name FROM department_tb WHERE dep_id = '$dep'";
                $obj_row_d = mysqli_query($obj_con,$sql_query_d);
                $obj_result_d = mysqli_fetch_assoc($obj_row_d);

                $sql_query_c = "SELECT comp_name FROM company_tb WHERE comp_id = '$cid'";
                $obj_row_c = mysqli_query($obj_con,$sql_query_c);
                $obj_result_c = mysqli_fetch_assoc($obj_row_c);
                                
                $code = "TAX";
                $year_head = substr(date("Y")+543,-2);
                $month_head = date("m",strtotime($date));
                $dep_check = $obj_result_d['dep_name'];
                $sql_query = "SELECT tax_id FROM taxpurchase_tb WHERE tax_dep_id = '$dep' ORDER BY tax_id DESC";
                $obj_row = mysqli_query($obj_con,$sql_query);
                $obj_result = mysqli_fetch_array($obj_row);

                if(mysqli_num_rows($obj_row) == 0){
                    $tax = $code . $obj_result_d['dep_name'] . $year_head. $month_head . "0001";  
                }else{
                    $num = $obj_result["tax_id"];
                    $txt = substr($num,-4);
                    $number = (int)$txt + 1;
                    if(strlen($number) == 1){
                        $max = "000" . $number;
                    }elseif(strlen($number) == 2){
                        $max = "00" . $number;
                    }elseif(strlen($number) == 3){
                        $max = "0" . $number;
                    }
                    $tax = $code . $obj_result_d['dep_name'] . $year_head. $month_head . $max; 
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

                $path = "receipt_taxpurchase/";
                !file_exists($path) && mkdir($path , 0777);
                $dep_name = $obj_result_d['dep_name'];
                $time  = strtotime($date);
                $month = date('m',$time);
                $year  = date('Y',$time) + 543;
                $pathDep = $path . $dep_name;

                $year_folder = $pathDep . '/' . $year;
                $month_folder = $year_folder . '/' . $month;

                !file_exists($pathDep) && mkdir($pathDep , 0777);
                !file_exists($year_folder) && mkdir($year_folder , 0777);
                !file_exists($month_folder) && mkdir($month_folder, 0777);

                $type = ".pdf";
                $newname = $tax .$type;
                $pathCopy = $month_folder . '/' . $newname;

                $filename = $pathCopy;
                $date_head = DateMonthThai($date,"head");
                $header = '
                <table cellspacing="0" cellpadding="0" border="0" width="100%">
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
                        <td width="20%" style="font-size: 12pt; padding: 3px 0px">
                            <b>ชื่อผู้ประกอบการ</b>
                        </td>
                        <td width="45%" style="font-size: 12pt; padding: 3px 0px">
                            <b>'. $obj_result_c['comp_name'] .'</b>
                        </td>
                        <td style="font-size: 12pt;">
                            <b>เลขประจำตัวผู้เสียภาษีอากร '. $tax_no .'</b>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" style="font-size: 12pt; padding: 3px 0px">
                            <b>ชื่อสถานประกอบการ</b>
                        </td>
                        <td width="45%" style="font-size: 12pt; padding: 3px 0px">
                            <b>'. $obj_result_c['comp_name'] .'</b>
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

                ob_start(); // Start get HTML code

                ?>
                <!DOCTYPE html>

                <head>
                    
                    <title>ใบภาษีซื้อ <?= $tax ?></title>
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
                    <table class="txtbody" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <thead>
                        <tr>
                            <th width="4%">ลำดับ</th>
                            <th width="8%">วันที่</th>
                            <th width="20%">เล่มที่/เลขที่</th>
                            <th width="40%">ชื่อผู้ซื้อ/ผู้ให้บริการ/รายการ</th>
                            <th width="10%">จำนวนเงิน</th>
                            <th width="9%">ภาษีมูลค่าเพิ่ม</th>
                            <th width="9%">จำนวนเงินรวม</th>
                        </tr>
                    </thead>
                    
                        <?php for($i = 0; $i < count($data); $i++) { ?>
                        <?php $date_check = DateMonthThai($data[$i]['date_input'],"body"); ?>
                        
                        <tr>
                            <td width="4%"><?= $i + 1; ?></td>
                            <td width="8%"><?= $date_check[0] . " " . $date_check[1] . " " .$date_check[2] ?></td>
                            <td width="20%"><?= $data[$i]['book_number_input']; ?></td>
                            <td width="40%" style="text-align: left;"><?= $data[$i]['company_input'] .' / '. $data[$i]['list_input']; ?></td>
                            <td width="10%" style="text-align: right;"><?= number_format($data[$i]['price_input'],2); ?></td>
                            <td width="9%" style="text-align: right;"><?= number_format($data[$i]['vat_input'],2); ?></td>
                            <td width="9%" style="text-align: right;"><?= number_format($data[$i]['result_input'],2); ?></td>
                        </tr>
                        <?php } ?>

                        <tr>
                            <td colspan="4" style="text-align: center;"><b>รวมยอด</b></td>
                            <td style="text-align: right;" class="tb"><b><?= number_format($price_all,2)?></b></td>
                            <td style="text-align: right;" class="tb"><b><?= number_format($vat_all,2)?></b></td>
                            <td style="text-align: right;" class="tb"><b><?= number_format($result_all,2)?></b></td>
                        </tr>
                    </table>
                </body>';
                <?php
                    $html = ob_get_contents();
                    $mpdf->SetHeader($header);
                    $mpdf->WriteHTML($html);
                    $mpdf->Output($filename);
                    ob_end_clean();
                
                $user_id = $_SESSION["user_id"];
                $date_created = date("Y-m-d h:i:s");
                $str_sql = "INSERT INTO taxpurchase_tb(tax_id,tax_number,tax_comp_id,tax_created_at,tax_dep_id,tax_file,tax_price,tax_vat,tax_result,user_created,created_at) 
                VALUES('$tax','$tax_no','$cid','$date','$dep','$newname','$price_all','$vat_all','$result_all','$user_id','$date_created')";
                $str_insert = mysqli_query($obj_con,$str_sql);
     
                if($str_insert){
                    for($i = 0; $i < count($data); $i++){
                        $list_no = $data[$i]['book_number_input'];
                        $list_paya = $data[$i]['paya_id'];
                        $list_input = $data[$i]['list_input'];
                        $price = $data[$i]['price_input'];
                        $vat = $data[$i]['vat_input'];
                        $result = $data[$i]['result_input'];
                        $date2 = $data[$i]['date_input'];
    
                        $str_sql2 = "INSERT INTO taxpurchaselist_tb(list_tax_id,list_no,list_paya_id,list_desc,list_price,list_vat,list_result,created_at) 
                        VALUES('$tax','$list_no','$list_paya','$list_input','$price','$vat','$result','$date2')";
                        mysqli_query($obj_con,$str_sql2);
                    }
                   
                }
                echo json_encode(['message'=>'success','tax_no'=>$tax]);
                mysqli_close($obj_con);

            }else if($_POST['action'] === "update"){
                include 'connect.php';

                $tax_id = $_POST['tax_id'];
                $tax_number = $_POST['tax_no'];
                $comp_id = $_POST['comp_id'];
                $date = $_POST['date'];
                $dep_id = $_POST['dep_id'];
                $price_all = $_POST['price_all'];
                $vat_all = $_POST['vat_all'];
                $result_all = $_POST['result_all'];
                $dep_name = $_POST['dep_name'];
                $del_list = isset($_POST['del_list']) ? $_POST['del_list'] : null;
                $data = $_POST['data'];
                
                if(!is_null($del_list)){
                    for($i = 0; $i < count($del_list); $i++){
                        $sql_del = "DELETE FROM taxpurchaselist_tb WHERE list_id = '" . $del_list[$i] . "'";
                        mysqli_query($obj_con, $sql_del);
                    }
                }
                
                $sql_query_c = "SELECT comp_name FROM company_tb WHERE comp_id = '$comp_id'";
                $obj_row_c = mysqli_query($obj_con,$sql_query_c);
                $obj_result_c = mysqli_fetch_assoc($obj_row_c);
                
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

                $path = "receipt_taxpurchase/";
                !file_exists($path) && mkdir($path , 0777);
                $dep_name = $dep_name;
                $time  = strtotime($date);
                $month = date('m',$time);
                $year  = date('Y',$time) + 543;
                $pathDep = $path . $dep_name;

                $year_folder = $pathDep . '/' . $year;
                $month_folder = $year_folder . '/' . $month;

                !file_exists($pathDep) && mkdir($pathDep , 0777);
                !file_exists($year_folder) && mkdir($year_folder , 0777);
                !file_exists($month_folder) && mkdir($month_folder, 0777);

                $type = ".pdf";
                $newname = $tax_id .$type;
                $pathCopy = $month_folder . '/' . $newname;

                $filename = $pathCopy;
                $date_head = DateMonthThai($date,"head");

                $header = '
                <table cellspacing="0" cellpadding="0" border="0" width="100%">
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
                        <td width="20%" style="font-size: 12pt; padding: 3px 0px">
                            <b>ชื่อผู้ประกอบการ</b>
                        </td>
                        <td width="45%" style="font-size: 12pt; padding: 3px 0px">
                            <b>'. $obj_result_c['comp_name'] .'</b>
                        </td>
                        <td style="font-size: 12pt;">
                            <b>เลขประจำตัวผู้เสียภาษีอากร '. $tax_number .'</b>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" style="font-size: 12pt; padding: 3px 0px">
                            <b>ชื่อสถานประกอบการ</b>
                        </td>
                        <td width="45%" style="font-size: 12pt; padding: 3px 0px">
                            <b>'. $obj_result_c['comp_name'] .'</b>
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
                </table>';

                ob_start(); // Start get HTML code

                ?>
                <!DOCTYPE html>

                <head>
                    
                    <title>ใบภาษีซื้อ <?= $tax_id ?></title>
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
                    <table class="txtbody" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <thead>
                        <tr>
                            <th width="4%">ลำดับ</th>
                            <th width="8%">วันที่</th>
                            <th width="20%">เล่มที่/เลขที่</th>
                            <th width="40%">ชื่อผู้ซื้อ/ผู้ให้บริการ/รายการ</th>
                            <th width="10%">จำนวนเงิน</th>
                            <th width="9%">ภาษีมูลค่าเพิ่ม</th>
                            <th width="9%">จำนวนเงินรวม</th>
                        </tr>
                    </thead>
                        
                        <?php for($i = 0; $i < count($data); $i++) { ?>
                        <?php $date_check = DateMonthThai($data[$i]['date_input'],"body"); ?>
                        <tr>
                            <td width="4%"><?= $i + 1; ?></td>
                            <td width="8%"><?= $date_check[0] . " " . $date_check[1] . " " .$date_check[2] ?></td>
                            <td width="20%"><?= $data[$i]['book_number_input'] ?></td>
                            <td width="40%" style="text-align: left;"><?= $data[$i]['company_input'] . ' / ' . $data[$i]['list_input']?></td>
                            <td width="10%" style="text-align: right;"><?= number_format($data[$i]['price_input'],2) ?></td>
                            <td width="9%" style="text-align: right;"><?= number_format($data[$i]['vat_input'],2) ?></td>
                            <td width="9%" style="text-align: right;"><?= number_format($data[$i]['result_input'],2) ?></td>
                        </tr>
                        <?php } ?>

                        <tr>
                            <td colspan="4" style="text-align: center;"><b>รวมยอด</b></td>
                            <td style="text-align: right;" class="tb"><b><?= number_format($price_all,2) ?></b></td>
                            <td style="text-align: right;" class="tb"><b><?= number_format($vat_all,2) ?></b></td>
                            <td style="text-align: right;" class="tb"><b><?= number_format($result_all,2) ?></b></td>
                        </tr>
                    </table>
                </body>';
                <?php
                    $html = ob_get_contents();
                    $mpdf->SetHeader($header);
                    $mpdf->WriteHTML($html);
                    $mpdf->Output($filename);
                    ob_end_clean();

                $user_id = $_SESSION['user_id'];
                $date_n = date("Y-m-d h:i:s");
                $sql_update = "UPDATE taxpurchase_tb SET tax_number='$tax_number',tax_comp_id='$comp_id',tax_created_at='$date',tax_dep_id='$dep_id',tax_price='$price_all',tax_vat='$vat_all',tax_result='$result_all',updated_at='$date_n',user_updated='$user_id' WHERE tax_id='$tax_id'";
                $sql_update_q = mysqli_query($obj_con,$sql_update);

                if($sql_update_q){
                    for($i = 0; $i < count($data); $i++){
                        $list_id = $data[$i]['id'];
                        $list_no = $data[$i]['book_number_input'];
                        $paya_id = $data[$i]['paya_id'];
                        $list_input = $data[$i]['list_input'];
                        $price = $data[$i]['price_input'];
                        $vat = $data[$i]['vat_input'];
                        $result = $data[$i]['result_input'];
                        $date2 = $data[$i]['date_input'];
    
                        $sql_query = "SELECT * FROM taxpurchaselist_tb WHERE list_tax_id = '$tax_id' AND CONVERT(list_id,CHAR) = '$list_id'";
                        $sql_result = mysqli_query($obj_con,$sql_query);
                        $count = mysqli_num_rows($sql_result);

                        if($count == 0){
                            $insert_sql = "INSERT INTO taxpurchaselist_tb(list_tax_id,list_no,list_paya_id,list_desc,list_price,list_vat,list_result,created_at) 
                            VALUES('$tax_id','$list_no','$paya_id','$list_input','$price','$vat','$result','$date2')";
                            mysqli_query($obj_con,$insert_sql);
                        }else{
                            $update_sql = "UPDATE taxpurchaselist_tb SET list_no='$list_no', list_paya_id='$paya_id', list_desc='$list_input', list_price='$price', list_vat='$vat', list_result='$result', created_at='$date2' WHERE list_tax_id ='$tax_id' AND list_id ='$list_id'";
                            mysqli_query($obj_con,$update_sql);
                        }
                    }
                }
                echo json_encode(['message'=>'success','tax_id'=>$tax_id]);
                mysqli_close($obj_con);
            }
        }
        
    }
?>
