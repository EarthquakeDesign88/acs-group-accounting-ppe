<?php
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
    
    $download = isset($_GET['download']) ? $_GET['download'] : 0;
             
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
    
    $str_sql = "SELECT * FROM invoice_rcpt_tb AS ir 
                INNER JOIN company_tb AS c ON ir.invrcpt_compid = c.comp_id 
                INNER JOIN customer_tb AS cust ON ir.invrcpt_custid = cust.cust_id 
                INNER JOIN department_tb AS d ON ir.invrcpt_depid = d.dep_id 
                WHERE invrcpt_compid = '". $cid ."' AND invrcpt_date BETWEEN '". $df."' AND '". $dt."'";
    
    if($_GET['custid'] != ''){
        $str_sql .= " AND invrcpt_custid = '". $custid ."'";
    }
    
    if($status != ''){
        $str_sql .= " AND invrcpt_stsid = '$status'";
    }
    
    $str_sql .= " ORDER BY invrcpt_book ASC, invrcpt_no ASC ";
    $obj_query = mysqli_query($obj_con, $str_sql);
    
    $filename = "รายงานใบแจ้งหนี้(รายรับ) วันที่" . $df. ' ถึง ' . $dt;
    
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
            'format' => 'A4-L',
            // 'format' => 'A5',
            'margin_top' => 20,
            'margin_bottom' => 5,
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_header' => 5,     // 9 margin header
            'margin_footer' => 5,     // 9 margin footer
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
    
        $header = '<table cellspacing="0" cellpadding="0" border="0" width="100%" style="border: none;" >
            <tr>
                <td width="50%" style="border: none; font-size: 24pt;font-weight:bold">
                    <b>สรุปรายงานใบแจ้งหนี้วันที่ ' .DateThai($df) .' - '. DateThai($dt) .'</b>
                </td>
                <td width="50%" style="border: none; text-align: right; font-size: 14pt;">
                    หน้า {PAGENO}/{nb}
                </td>
            </tr>
        </table>';
        
     $footer = '<table cellspacing="0" cellpadding="0" border="0" width="100%" style="border: none;">
            <tr>
                <td width="33.33%" style="border: none;  font-size: 14pt;  ">'.$datefooter.'/'.$monthfooter.'/'.$yearfooter.'</td>
                <td width="33.33%" style="text-align: center; border: none;  font-size: 14pt;  "></td>
                <td width="33.33%" style="text-align: right; border: none;  font-size: 14pt;  ">รายงานใบแจ้งหนี้</td>
            </tr>
        </table>';
        
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
                    .table {
                        padding: 1px 0px;
                        overflow: wrap;
                        font-size: 14pt;
                        vertical-align: middle;
                        border: 1px solid #000;
                        border-collapse: collapse;
                    }
                    .table th , table td {
                        border: 1px solid #000;
                        border-collapse: collapse;
                    }
                    .table td {
                        padding: 2px 5px;
                    }
                    
                     .table th {
                        font-size: 14pt;  
                        text-align: center;
                    }
                </style>    
           <?php } ?>
    	</head>
    	<body>	
    		<table border="1" class="table">
    			<thead>
    				<tr>
                        <th style="padding: 8px 2px;" width="8%"><b>เลขที่<?php if($expprt_pdf){ ?><br><?php } ?>ใบแจ้งหนี้</b></th>
                        <th style="padding: 8px 2px;" width="9%"><b>วันที่<?php if($expprt_pdf){ ?><br><?php } ?>ใบแจ้งหนี้</b></th>
                        <th style="padding: 8px 2px; " width="9%"><b>เลขที่<?php if($expprt_pdf){ ?><br><?php } ?>ใบเสร็จรับเงิน</b></th>
                        <th style="padding: 8px 2px;" width="9%"><b>วันที่<?php if($expprt_pdf){ ?><br><?php } ?>ใบเสร็จรับเงิน</b></th>
                        <th style="padding: 8px 2px;"><b>ชื่อผู้ซื้อ/ผู้รับบริการ/รายการ</b></th>
                        <th style="padding: 8px 2px;" width="10%"><b>จำนวนเงิน</b></th>
                        <th style="padding: 8px 2px;" width="8%"><b>ภาษี<?php if($expprt_pdf){ ?><br><?php } ?>มูลค่าเพิ่ม</b></th>
                        <th style="padding: 8px 2px;" width="10%"><b>จำนวนเงินรวม</b></th>
                    </tr>
    			</thead>
    			<tbody>
                <?php while($row = mysqli_fetch_assoc($obj_query)) {
    
                    if($row["invrcpt_book"] == '') {
                        $invoiceno = $row["invrcpt_no"];
                    } else {
                        $invoiceno = $row["invrcpt_book"] . "/" . $row["invrcpt_no"];
                    }
    
                    $str_sql_rcpt = "SELECT * FROM receipt_tb WHERE re_id = '". $row["invrcpt_reid"] ."'";
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
    
                    $subtotal = $row['invrcpt_stsid'] != 'STS003' ? $row['invrcpt_subtotal'] : 0;
                    $vat = $row['invrcpt_stsid'] != 'STS003' ? $row['invrcpt_vat'] + $row['invrcpt_differencevat'] : 0;
                    $grandtotal = $row['invrcpt_stsid'] != 'STS003' ? $row['invrcpt_grandtotal'] + $row['invrcpt_differencevat'] + $row['invrcpt_differencegrandtotal'] : 0;
    
                    if($row['invrcpt_stsid'] == 'STS003'){
                        $sum_subtotal += 0;
                        $sum_vat += 0;
                        $sum_grandtotal += 0;
                    }else{
                        $sum_subtotal += $row['invrcpt_subtotal'];
                        $sum_vat += $row['invrcpt_vat'] + $row['invrcpt_differencevat'];
                        $sum_grandtotal += $row['invrcpt_grandtotal'] + $row['invrcpt_differencevat'] + $row['invrcpt_differencegrandtotal'];
                    }
                ?>
    
    				<tr>
    					<td  style="text-align: center;"><?= $invoiceno ?></td>
    					<td  style="text-align: center;"><?= DateThai($row['invrcpt_date']) ?></td>
    					<td  style="text-align: center;"><?= $receiptno ?></td>
    					<td  style="text-align: center;"><?= $receiptdate ?></td>
                        <td style="text-align: left;"><?= $row['invrcpt_stsid'] != 'STS003'? $row['cust_name'] : 'ยกเลิก'; ?></td>
    					<td style="text-align: right;"><?= $row['invrcpt_stsid'] != 'STS003'? number_format($subtotal,2) : 0; ?></td>
    					<td style="text-align: right;"><?= $row['invrcpt_stsid'] != 'STS003'? number_format($vat,2) : 0; ?></td>
    					<td style="text-align: right;"><?= $row['invrcpt_stsid'] != 'STS003'? number_format($grandtotal,2) : 0; ?></td>
    				</tr>
                <?php } ?>
    			</tbody>
                <tfoot>
                    
                    <tr>
                        <td colspan="4" style="border-rigth: none;"></td>
                        <td style="border-left: none; text-align: right;"><b>ยอดรวม</b></td>
                        <td style="text-align: right; color: #F00;"><?= number_format($sum_subtotal,2); ?></td>
                        <td style="text-align: right; color: #F00;"><?= number_format($sum_vat,2); ?></td>
                        <td style="text-align: right; color: #F00;"><?= number_format($sum_grandtotal,2); ?></td>
                    </tr>
                </tfoot>
    		</table>
    	</body>
    </html>
    
    <?php
    if($expprt_pdf){
       $filename = $filename.".pdf"; 
        $html = ob_get_clean();ob_end_clean(); 
        $mpdf->defaultheaderline = 0;
        $mpdf->defaultfooterline = 0;
        $mpdf->SetHeader($header);
        // $mpdf->SetFooter($footer);
        $mpdf->WriteHTML($html);
        

        if($download){
            $mpdf->Output($filename,"D");
        }else{
            $mpdf->Output($filename,"I");
        }
    }
    ?>
 <?php } ?>