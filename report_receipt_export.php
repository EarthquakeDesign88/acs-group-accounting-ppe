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
    
    $str_sql = "SELECT * FROM receipt_tb AS r 
            INNER JOIN company_tb AS c ON r.re_compid = c.comp_id 
            INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
            INNER JOIN department_tb AS d ON r.re_depid = d.dep_id 
            WHERE re_compid = '". $_GET['cid'] ."' AND re_date BETWEEN '". $_GET['df'] ."' AND '". $_GET['dt'] ."'";
    
    if($custid != ''){
        $str_sql .= " AND re_custid = '$custid'";
    }
    
    if($status != ''){
        $str_sql .=  " AND re_stsid = '$status'";
    }
    
    $str_sql .= " ORDER BY re_bookno ASC, re_no ASC ";
    $obj_query = mysqli_query($obj_con, $str_sql);    
    
    $filename = "รายงานใบเสร็จรับเงิน วันที่" . $_GET['df'] . ' ถึง ' . $_GET['dt'];
    
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
							<b>สรุปรายงานใบเสร็จรับเงินวันที่ ' .DateThai($df) .' - '. DateThai($dt) .'</b>
						</td>
						<td width="50%" style="border: none; text-align: right;">
							หน้า {PAGENO}/{nb}
						</td>
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
                        font-size: 12pt;
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
                        font-size: 12pt;  
                        text-align: center;
                    }
                </style>    
           <?php } ?>
    	</head>
    	<body>	
    		<table border="1" class="table">
    			<thead>
					<tr>
						<th width="9%"><b>เลขที่<br>ใบเสร็จรับเงิน</b></th>
						<th width="8%"><b>วันที่<br>ใบเสร็จรับเงิน</b></th>
						<th width="9%"><b>เลขที่<br>ใบแจ้งหนี้</b></th>
						<th style="padding: 8px 5px;"><b>ชื่อผู้ซื้อ/ผู้รับบริการ/รายการ</b></th>
						<th width="8%"><b>จำนวนเงิน</b></th>
						<th width="8%"><b>ภาษีมูลค่าเพิ่ม</b></th>
						<th width="8%"><b>จำนวนเงินรวม</b></th>
						<th width="7%"><b>เลขที่เช็ค</b></th>
						<th width="8%"><b>ธนาคาร</b></th>
						<th width="7%"><b>วันที่<br>ออกเช็ค</b></th>
						<th width="4%"><b>เงินสด</b></th>
					</tr>
    			</thead>
    			<tbody>
    			         <?php while($row = mysqli_fetch_assoc($obj_query)) {
                            if ($row["re_refinvrcpt"] == '0') {
                                $str_sql_inv = "SELECT * FROM receipt_tb AS r 
                                        INNER JOIN invoice_rcpt_tb AS i ON r.re_invrcpt_id = i.invrcpt_id
                                        WHERE re_id = '". $row['re_id'] ."'";
                                $obj_rs_inv = mysqli_query($obj_con, $str_sql_inv);
                                $obj_row_inv = mysqli_fetch_array($obj_rs_inv);
                
                                if($obj_row_inv["invrcpt_book"] == '') {
                
                                    $invoiceno = $obj_row_inv["invrcpt_no"];
                
                                } else {
                
                                    $invoiceno = $obj_row_inv["invrcpt_book"] . "/" . $obj_row_inv["invrcpt_no"];
                
                                }
                            } else {
                                $invoiceno = '';
                            }
                            
                            if($row["re_bookno"] == '') {
                                $receiptno = $row["re_no"];
                            } else {
                                $receiptno = $row["re_bookno"] . "/" . $row["re_no"];
                            }
                            if($row["re_chequedate"] == '0000-00-00') {
                                $chequedate = '';
                            } else {
                                $chequedate = DateThai($row["re_chequedate"]);
                            }
                            $subtotal = $row['re_stsid'] != 'STS003' ? $row['re_subtotal'] : 0;
                            $vat = $row['re_stsid'] != 'STS003' ? $row['re_vat'] + $row['re_differencevat'] : 0;
                            $grandtotal = $row['re_stsid'] != 'STS003' ? $row['re_grandtotal'] + $row['re_differencevat'] + $row['re_differencegrandtotal'] : 0;
                            
                            if($row['re_stsid'] == 'STS003'){
                                $sum_subtotal += 0;
                                $sum_vat += 0;
                                $sum_grandtotal += 0;
                            }else{
                                $sum_subtotal += $row['re_subtotal'];
                                $sum_vat += $row['re_vat'] + $row['re_differencevat'];
                                $sum_grandtotal += $row['re_grandtotal'] + $row['re_differencevat'] + $row['re_differencegrandtotal'];
                            }
                            
                            $str_sql_b = "SELECT * FROM bank_tb WHERE bank_id = '". $row["re_bankid"] ."'";
                
                            $obj_rs_b = mysqli_query($obj_con, $str_sql_b);
                
                            $obj_row_b = mysqli_fetch_array($obj_rs_b);
                            if(mysqli_num_rows($obj_rs_b) > 0) {
                                if($row["re_bankid"] == '') {
                
                                    $bankname = '';
                
                                } else {
                
                                    $bankname = $obj_row_b["bank_name"];
                
                                }
                
                            } else {
                
                                $bankname = '';
                
                            }
                        ?>
                         	<tr>
								<td><?= $receiptno ?></td>
								<td><?= DateThai($row['re_date']) ?></td>
								<td>
                                    <?php if($row['re_stsid'] != 'STS003'){  ?> 
                                        <?= $invoiceno ?>
                                    <?php } else { ?>
                                        <?= "" ?>
                                    <?php } ?>
                                </td>
								<td>
                                    <?php if($row['re_stsid'] != 'STS003'){  ?> 
                                        <?= $row['cust_name'] ?>
                                    <?php } else { ?>
                                        <?= $row['re_note_cancel']; ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if($row['re_stsid'] != 'STS003'){  ?> 
                                        <?= number_format($subtotal,2) ?>
                                    <?php } else { ?>
                                        <?= 0 ?>
                                    <?php } ?>
                                </td>
								<td>
                                    <?php if($row['re_stsid'] != 'STS003'){  ?> 
                                        <?= number_format($vat,2) ?>
                                    <?php } else { ?>
                                        <?= 0 ?>
                                    <?php } ?>                                   
                                </td>
								<td>
                                    <?php if($row['re_stsid'] != 'STS003'){  ?> 
                                        <?= number_format($grandtotal,2) ?>
                                    <?php } else { ?>
                                        <?= 0 ?>
                                    <?php } ?>                                         
                                </td>
								<td><?= $row['re_stsid'] != 'STS003' ? $row['re_chequeno'] : '' ?></td>
                                <td><?= $row['re_stsid'] != 'STS003' ? $bankname : '' ?></td>
                                <td><?= $row['re_stsid'] != 'STS003' ? $chequedate : '' ?></td>
                                <td class="text-center">
                                    <?php if($row['re_typepay'] == '1' && $row['re_stsid'] != 'STS003') { ?>
                                        /
                                    <?php } else { ?>
                                        
                                    <?php } ?>
                                </td>
							</tr>
                        <?php } ?>
    			</tbody>
                <tfoot>
                    
                    <tr>
                        <td colspan="4" style="border-left: none; text-align: right;"><b>ยอดรวม</b></td>
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
        $mpdf->SetFooter($footer);
        $mpdf->WriteHTML($html);
        
        if($download){
            $mpdf->Output($filename,"D");
        }else{
            $mpdf->Output($filename,"I");
        }
    }
    ?>
 <?php } ?>