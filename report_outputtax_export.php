<?php
   include 'config/config.php'; 
   
    $expprt_pdf = false;
    $expprt_excel= false;
    if(isset($_GET['export'])){
        if($_GET['export']== 'excel'){
            $download = 0;
             $expprt_excel = true;
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
            'mode' => 'utf-8',
            'format' => 'A4',
            // 'format' => 'A5',
            'margin_top' => 40,
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
    }
    
    $cid = (isset($_GET["cid"])) ? $_GET["cid"] : "";
    $df = (isset($_GET["df"])) ? $_GET["df"] : "";
    $dt = (isset($_GET["dt"])) ? $_GET["dt"] : "";
    $vat = (isset($_GET["vat"])) ? $_GET["vat"] : "";
    
    $sql_comp = "SELECT * FROM company_tb WHERE comp_id = '". $cid ."'";
    $row_comp = $db->query($sql_comp)->row();
    
    $ck = false;
    $text_ck = "";
    if(!empty($row_comp)){
        if(!empty($df)){
           if(!empty($dt)){
                 if(__validdate($df)){
                     if(__validdate($dt)){
                           if (strtotime($df) <= strtotime($dt)) {
                                $ck = true;
                            } else {
                                $text_ck =' วันที่เริ่มต้น ต้องน้อยกว่า วันที่สิ้นสุด';
                            }
                     }else{
                          $text_ck = "รูปแบบวันที่ สิ้นสุด ไม่ถุกต้อง";
                     }
                 }else{
                     $text_ck = "รูปแบบวันที่ เริ่มต้น ";
                     if(!__validdate($dt)){
                         $text_ck .= " และ สิ้นสุด";
                     }
                     
                     $text_ck .= " ไม่ถุกต้อง";
                 }
           }else{
               $text_ck = "กรุณาระบุวันที่ สิ้นสุด";
           }
        }else{
            $text_ck = "กรุณาระบุวันที่ เริ่มต้น";
            if(empty($dt)){
                $text_ck .= " ถึง สิ้นสุด";
            }
        }
    }else{
      $text_ck = "ไม่พบบริษัท";
    }
    
    if($ck){
        
        $comp_name = $row_comp["comp_name"];
        $comp_taxno = $row_comp["comp_taxno"];
        
        $df_thai = __date($df,"full");
        $dt_thai = __date($dt,"full");
        
        $m_df_thai = __date_nomonth($df,"full");
        $m_dt_thai = __date_nomonth($dt,"full");
        $m_thai = ($m_df_thai==$m_dt_thai) ? $m_df_thai : $m_df_thai." - ".$m_dt_thai;
        
        if($vat=="V"){
           $title = "รายงานภาษีขาย";
        }else{
           $title = "รายงานรายได้ Non-Vat";
        }
        
         $filename = $title." - ".$df_thai." ถึง ".$dt_thai;
     
        $html_style = "<style>
            body {
                font-family: 'sarabun';
            }
            table {
                padding: 1px 0px;
                overflow: wrap;
                font-size: 10pt;
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
        </style>";
        
         $header = '<table cellspacing="0" cellpadding="0" border="0" width="100%" >';
                    if($expprt_pdf){
                          $header .= '<tr>
                            <td width="33%"></td>
                            <td width="33%" style="text-align: center; font-size: 18pt;">
                                <b>'.$title.'</b>
                            </td>
                            <td width="33%" style="text-align: right; font-size: 12pt;"><strong>หน้า {PAGENO}/{nb}</strong></td>
                          </tr>';
                    }else{
                        $header .= '<tr>
                            <td colspan="8" width="100%" style="text-align: center; font-size: 18pt;">
                                <b>'.$title.'</b>
                            </td>
                         </tr>';
                    }
                  $header .= '</table>
            
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td '.(($expprt_pdf) ? '': ' colspan="8" ').' style="text-align: center; font-size: 16pt;">
                        <b>เดือน '. $m_thai .'</b>
                    </td>
                </tr>
            </table>
            
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="height: 20px;"></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td width="5%"></td>
                    <td width="20%" style="font-size: 12pt; padding: 3px 0px">
                        <b>ชื่อผู้ประกอบการ</b>
                    </td>
                    <td  '.(($expprt_pdf) ? '': ' colspan="4" ').' width="45%" style="font-size: 12pt; padding: 3px 0px">
                        <b>'. $comp_name.'</b>
                    </td>
                    <td '.(($expprt_pdf) ? '': ' colspan="2" ').'  style="font-size: 12pt;">
                        <b>เลขประจำตัวผู้เสียภาษีอากร '. $comp_taxno .'</b>
                    </td>
                </tr>
                <tr>
                    <td width="5%"></td>
                    <td width="20%" style="font-size: 12pt; padding: 3px 0px">
                        <b>ชื่อสถานประกอบการ</b>
                    </td>
                    <td  '.(($expprt_pdf) ? '': ' colspan="4" ').' width="45%" style="font-size: 12pt; padding: 3px 0px">
                        <b>'. $comp_name .'</b>
                    </td>
                    <td '.(($expprt_pdf) ? '': ' colspan="2" ').'  style="font-size: 12pt; padding: 3px 0px">
                        <b>สำนักงานใหญ่</b>
                    </td>
                </tr>
            </table>';
            
       $sql = "SELECT * FROM department_tb d
       LEFT JOIN company_tb c ON c.comp_id = d.dep_compid
       WHERE d.dep_compid='".$cid."' AND d.dep_status=1";
       $result = $db->query($sql)->result();
       $html_all = "";
        foreach ($result as $row) {
            $sql_tax = "SELECT * FROM receipt_tb AS r 
            INNER JOIN company_tb AS c ON r.re_compid = c.comp_id 
            INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
            INNER JOIN department_tb AS d ON r.re_depid = d.dep_id 
            WHERE re_vatpercent <> 0 AND re_compid = '". $cid ."' AND re_depid = '".$row["dep_id"]."' AND re_date BETWEEN '". $df ."' AND '". $dt ."' ";
            $sql_tax .= ($vat=="V") ? " AND re_vatpercent <> 0  " : "";
            $sql_tax .= " ORDER BY re_bookno ASC, re_no ASC";
            $result_tax = $db->query($sql_tax)->result();
            
            
            $html_row = "";
            $sumSub = 0;
            $sumVat = 0;
            $sumGrand = 0;
            $n = 1;
            if(count($result_tax)>=1){
                foreach ($result_tax as $row_tax) {
                     if($row_tax["re_bookno"] == '') {
                        $Receiptno = $row_tax["re_no"];
                    } else {
                        $Receiptno = $row_tax["re_bookno"].'/'.$row_tax["re_no"];
                    }
                    
                    $Receiptno = (($vat=="V") ? "V-" : "N-").$Receiptno;
                    
                    $re_subtotal = 0;
                    $revat = 0;
                    $regrand = 0;
                    if($row_tax['re_stsid'] != 'STS003') {
                        $re_subtotal += $row_tax['re_subtotal'];
                        $revat += ($row_tax["re_vat"] + $row_tax["re_differencevat"]);
                        $regrand += ($row_tax["re_subtotal"] + $row_tax["re_vat"] + $row_tax["re_differencevat"] + $row_tax["re_differencegrandtotal"]);
                    }
                    
                    
                     if($row_tax['re_stsid'] != 'STS003') {
                          $sumSub += $row_tax["re_subtotal"];
                          $sumVat += ($row_tax["re_vat"] + $row_tax["re_differencevat"]);
                          $sumGrand +=  ($row_tax["re_grandtotal"] + $row_tax["re_differencevat"] + $row_tax["re_differencegrandtotal"]);
                     }
                    
                    $html_row .= "";
                      $html_row .= '<tr>';
                         $html_row .= '<td>'.$n.'</td>';
                         $html_row .= '<td>'.__date($row_tax["re_date"]).'</td>';
                         $html_row .= '<td>'.$Receiptno.'</td>';
                         $html_row .= '<td>'.(($row_tax['re_stsid'] != 'STS003') ? $row_tax["cust_taxno"]: "").' </td>';
                         $html_row .= '<td style="text-align: left;">'.(($row_tax['re_stsid'] != 'STS003') ? $row_tax["cust_name"] ."/". $row_tax["re_outputtax"]: $row_tax["re_note_cancel"]).'</td>';
                         $html_row .= '<td style="text-align: right;">'.__price($re_subtotal).'</td>';   
                         $html_row .= '<td style="text-align: right;">'.__price($revat).'</td>';
                         $html_row .= '<td style="text-align: right;">'.__price($regrand).'</td>';                     
                     $html_row .= '</tr>';
                     
                   $n++;   
                }
            }
            
           $html = '<table class="txtbody" cellspacing="0" cellpadding="0" border="0" width="100%">';
             $html .= '<thead class="thead-light">
                      <tr>
                        <th width="4%">ลำดับ</th>
                        <th width="8%">วันที่</th>
                        <th width="13%">เล่มที่/เลขที่</th>
                        <th width="10%">เลขประจำตัว</th>
                        <th>ชื่อผู้ซื้อ/ผู้รับบริการ/รายการ</th>
                        <th width="8%">จำนวนเงิน</th>
                        <th width="8%">ภาษีมูลค่าเพิ่ม</th>
                        <th width="8%">จำนวนเงินรวม</th>
                    </tr>
                   </thead>';
             $html .= "<tbody>";
                $html .= $html_row;
             $html .= "</tbody>";
             
              $html .= "<tfoot>";
                   $html .= '<tr>';
                    $html .= '<td colspan="5" style="text-align: right;"><b>ยอดรวมทั้งหมด</b></td>';
                    $html .= '<td style="text-align: right;">'.__price($sumSub).'</td>';
                    $html .= '<td style="text-align: right;">'.__price($sumVat).'</td>';
                    $html .= '<td style="text-align: right;">'.__price($sumGrand).'</td>';
                $html .= '</tr>';
             $html .= "</tfoot>";
                
           $html .= "</table>";
           
           $html_all .= "<hr>".$html;
           
          if($expprt_pdf){
            $mpdf->SetHTMLHeader($header);
            $mpdf->AddPage();
            $mpdf->WriteHTML($html_style.$html); 
          }          
            
        }
       if($expprt_excel){
           ob_end_clean();
           header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filename.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            ob_end_clean();
           echo $html_style;
           echo $header;
           echo $html_all;
           exit;
       }else   if($expprt_pdf){
            if($expprt_pdf){
                   $mpdf->SetTitle($filename);
                if($download){
                    $mpdf->Output($filename.".pdf","D");
                }else{
                    $mpdf->Output($filename.".pdf","I");
                }
           }
       }else{
            echo '<link href="https://fonts.googleapis.com/css?family=Sarabun&display=swap" rel="stylesheet">
            <meta name="viewport" content="width=device-width, initial-scale=1">';
            
           echo $html_style;
           echo $header;
           echo $html_all;
           exit;
       }
    }else{
         echo "<br><br><br><center><h2><font color='red'>".$text_ck."<font></h2></center>";
    }
?>
