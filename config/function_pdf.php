<?php
if (!function_exists('__pdf_setup')) {
     function __pdf_setup($config=array()){
         $path = realpath(dirname(__FILE__). '/../');
         require_once $path. '/vendor/autoload.php';
        
         if(!empty($config)){
            $format = (isset($config[0])) ? $config[0] : 0;
            $mt = (isset($config[1])) ? $config[1] : 0;
            $mb = (isset($config[2])) ? $config[2] : 0;
            $ml = (isset($config[3])) ? $config[3] : 0;
            $mr = (isset($config[4])) ? $config[4] : 0;
            $mh = (isset($config[5])) ? $config[5] : 0;
            $mf = (isset($config[6])) ? $config[6] : 0;
            
            $defaultFontConfig = new Mpdf\Config\FontVariables;
            $defaultFontConfig = $defaultFontConfig->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];
        
             $mpdf = new \Mpdf\Mpdf(array(
                'mode' => 'th',
                'format' =>$format,
                'margin_top' => $mt,
                'margin_bottom' => $mb,
                'margin_left' => $ml,
                'margin_right' => $mr,
                'margin_header' => $mh, 
                'margin_footer' => $mf,  
                'fontdata' => $fontData + array(
                    'sarabun' => array(
                        'R' => 'THSarabunNew.ttf',
                        'I' => 'THSarabunNewItalic.ttf',
                        'B' =>  'THSarabunNewBold.ttf',
                        'BI' => "THSarabunNewBoldItalic.ttf",
                    )
                ),
             ));
         }else{
             $mpdf = new \Mpdf\Mpdf();
         }
         
      return $mpdf;;  
    }
}
if (!function_exists('__pdf_box')) {
    function __pdf_box($text="",$config=array()){
        $top = (isset($config[0])) ? $config[0] : 0;
        $left =(isset($config[1])) ? $config[1] : 0;
        $width =(isset($config[2])) ? $config[2] : 0;
        $fontsize = (isset($config[3])) ? $config[3] : 28;
        $textalign = (isset($config[4])) ? $config[4] : "LEFT";
        $addstyle = (isset($config[5])) ? $config[5] : null;
    
        $text_style= '';
        $text_style .= "top:".$top."px;";
        $text_style .= "left:".$left."px;";
        $text_style .= "width:".(($width!="") ? $width."px;" : "auto;");
        $text_style .= "font-size:".$fontsize."px;";
        $text_style .= "text-align:".$textalign.";";
        $text_style .= $addstyle;
        
        $text = ($text=="") ? "&nbsp;" : $text;
        $html = "<div style='".$text_style."' class='box-pdf'>".$text."</div>";
        
        return $html;
    }
}
if (!function_exists('__pdf_invoice_revenue')) {
    function __pdf_invoice_revenue($datalist=array(),$type="I"){
        $response = "F";
        $message = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";
        $content_pdf= "";
        $preview_url = "";
        $preview_path = "";
        
        if(!empty($datalist)){
            $path = realpath(dirname(__FILE__). '/../');
            $db = new database();
            
            if(!empty($datalist)){
                include $path.'/variable_invoice_rpct.php';
                
                //$template_pdf_draft = $path."/template_pdf/invoice_revenue/draft/draft-".$comp_name_short.".pdf";
                $template_pdf = $path."/template_pdf/invoice_revenue/".$comp_name_short.".pdf";
                $template_pdf = $template_pdf;
                
                if(file_exists($template_pdf)){
                    $file_name  = "inv".$comp_name_short."-".$invrcpt_no.".pdf";
                    
                    //template
                    $mpdf_template = __pdf_setup();
                    $template_pagecount = $mpdf_template->setSourceFile($template_pdf);
                    $template_import_page = $mpdf_template->importPage($template_pagecount);
                    $template_size = $mpdf_template->getTemplateSize($template_import_page);
                    $template_size_width = $template_size["width"];
                    $template_size_height= $template_size["height"];
                    
                     $count_page_copy = 0;//1
                     $array_setup_page = 0;//2
                     $array_setup_titlepage = 0;//3
                     
                    $html_header = "";//4
                    $html_body = "";//5
                    $html_footer = "";//6
                    //7
                    $html_style = '<style>
                        body{
                            font-family: "sarabun", sans-serif;
                        }
                       .box-pdf{
                            font-weight:bold;
                            position: absolute;
                            display:block;
                            color:#000000;
                        }
                     </style>'; 
                     
                      switch ($comp_name_short) {
                         //อรุณ / เอซีเอส โปรเจคท์
                        case "ACS" :  case "ACSP" :
                            //setup
                            
                            if($comp_name_short=="ACS"){
                                $count_page_copy = 2;//1
                                $array_setup_page = array("A5-L", 69.6, 44, 4, 2, 2, 10, 10);//2
                                $array_setup_titlepage = array(110, 0, 0, 27, "center", "width:100%;");//3
                            }else if($comp_name_short=="ACSP"){
                                $count_page_copy = 2;
                                $array_setup_page = array("A5-L", 74, 44, 4, 2, 2, 10, 10);//2
                                  $array_setup_titlepage = array(66, 0, 735, 30, "right", "width:100%;margin-right:9px");//3
                            }
              
                            //4 header
                             if($comp_name_short=="ACS"){
                                $html_header .= __pdf_box($invrcpt_book,array(72,685,120,24,"left",null));//เลมที่
                                $html_header .= __pdf_box($invrcpt_no,array(105,685,120,24,"left"));//เลขที่
                                 $tbhead_top =  140;
                             }else  if($comp_name_short=="ACSP" || $comp_name_short=="TTNI"){
                                 $html_header .= __pdf_box($invrcpt_no,array(105,634,145,24,"left",null));//เลขที่
                                 $tbhead_top =  147;
                             }
                             
                            $html_header .= __pdf_box($text_full_invrcpt_date,array($tbhead_top,665,120,20,"left",null));//วันที่
                            $html_header .= __pdf_box($cust_name,array($tbhead_top+2,185,400,20,"left",null));//นามผู้รับบริการ
                            $html_header .= __pdf_box($cust_address,array($tbhead_top+26,92,690,20,"left","line-height:1.2;"));//ที่อยู่
                            
                           //5 body
                            $tbbody_detail = "";
                            $tbbody_detail2 = "";
                            $content_detail2 = "";
                            for ($i=1; $i <=11; $i++) {
                               $text_col1 = $arrayTableDes[$i]["description"];
                               $text_col2 = $arrayTableDes[$i]["text_amount"];   
                                
                                if($i<=5){
                                    $tbbody_detail .= '<tr>';
                                        $tbbody_detail .= '<td class="col-detail col-1" style="text-align:left;width: 560px;">'.$text_col1.'</td>';
                                        $tbbody_detail .= '<td class="col-detail col-2" style="text-align:right;width: 150px;" >'.$text_col2.'</td>';
                                    $tbbody_detail .= '</tr>';
                                }
                                
                                 if($i>=9 && $i<=11){
                                    $tbbody_detail2 .= '<tr>';
                                        $tbbody_detail2 .= '<td style="text-align:left;width:100%;">'.$text_col1.'</td>';
                                    $tbbody_detail2 .= '</tr>';
                                 }
                            }
    
                            $div_detail2 = '<table class="table-description2" cellpadding="0" cellspacing="0" border="0"  autosize="0">';
                                $div_detail2 .= "<tbody>";
                                $div_detail2 .= $tbbody_detail2;
                                $div_detail2 .= "</tbody>";
                            $div_detail2 .= '</table>';
                                
                            $tbfoot_top = ($comp_name_short=="ACS") ? 392 : 404;
                             $tbfoot_left = 635;
                             $tbfoot_width = 145;
                             $tbfoot_fontsize = 20;
                             $tbfoot_textalign = "right";
                             
                            $html_body .= '<table class="table-description" cellpadding="0" cellspacing="0" border="0"  autosize="0">';
                                $html_body .= "<tbody>";
                                $html_body .= $tbbody_detail;
                                $html_body .= "</tbody>";
                            $html_body .= '</table>';
                            
                             $html_footer .= __pdf_box($div_detail2,array($tbfoot_top,9,360,$tbfoot_fontsize,"left"," overflow:hidden;height:100px;line-height:1.42;"));
                            
                             $html_footer .= __pdf_box($text_invrcpt_subtotal,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//จำนวนเงินรวม
                             $tbfoot_top += 25;
                             $html_footer .= __pdf_box($text_invrcpt_vatpercent."&nbsp;%",array($tbfoot_top,593,35,$tbfoot_fontsize,$tbfoot_textalign,null));//ภาษีมูลค่าเพิ่ม xx %
                             $html_footer .= __pdf_box($text_cal_vat,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//ภาษีมูลค่าเพิ่ม
                             $tbfoot_top += 25;
                             $html_footer .= __pdf_box($text_cal_grand_total,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//จำนวนเงินรวมทั้งสิ้น
                            $tbfoot_top += 27;
                            $html_footer .= __pdf_box($text_bath_thai,array($tbfoot_top,430,350,18,"left",null));
                           
                            //7 style
                            $html_style .= '<style>';
                            $html_style .= '
                                .table-description{
                                    overflow:wrap;
                                    width:770px;
                                }
                                
                                .table-description2{
                                    overflow:wrap;
                                     width:100%;
                                }
                                
                                .table-description td,
                                .table-description2 td{
                                    font-size:20px;
                                    font-weight:bold;
                                    border:none;
                                    vertical-align: top;
                                    padding-right:3px;
                                    line-height:1.3;
                                } ';
                                $html_style .= '</style>';   
                        break;
                        
                        //เอซีเอส ดีไซน์ สตูดิโอ / เอ ซี เอส อินเตอร์
                        case "ACSD" :  case "ACSI" :
                            //setup
                            if($comp_name_short=="ACSD"){
                                $count_page_copy = 2;
                                $array_setup_page = array("A4-P", 103, 115, 11, 6, 16, 10, 10);
                                $array_setup_titlepage = array(160, 0, 0, 34, "center", "width:100%;padding-left:10px");
                            }else{
                                $count_page_copy = 2;
                                $array_setup_page = array("A4-P", 106.5, 111.6, 11, 6, 16, 10, 10);
                                $array_setup_titlepage = array(82, 0, 0, 30, "center", "width:100%;padding-left:11px");
                            }
                            
                            //4 header
                            $tbhead_top = ($comp_name_short=="ACSD") ? 143 : 155;
                            $html_header .= __pdf_box($invrcpt_no,array($tbhead_top,612,150,24,"left",null));//เลมที่
                            $html_header .= __pdf_box($text_full_invrcpt_date,array($tbhead_top+26,611,160,24,"left",null));//วันที่
                            
                            $cus_detail = "<div class='cus-detail'><div class='cus-detail-1' style='width:225px;'>นามผู้รับบริการ Customer's Name</div><div class='cus-detail-2'>".$cust_name."</div></div>";
                            $cus_detail .= "<div class='cus-detail'><div class='cus-detail-1' style='width:110px;'>ที่อยู่ Address</div><div class='cus-detail-2'>".$cust_address."</div></div>";
                            $cus_detail .= "<div class='cus-detail'><div class='cus-detail-1' style='width:150px;'>เลขประจำตัวผู้เสียภาษี</div><div class='cus-detail-2'>".$cust_taxno."</div></div>";
                            $html_header .= __pdf_box($cus_detail,array($tbhead_top+70,40,730,20,"left","line-height:1.2;"));//cust_name
                            
                           //5 body
                            $tbbody_detail = "";
                            $tbbody_detail2 = "";
                            $content_detail2 = "";
                            for ($i=1; $i <=11; $i++) {
                               $text_col1 = $arrayTableDes[$i]["description"];
                               $text_col2 = $arrayTableDes[$i]["text_amount"];   
                                
                                if($i<=8){
                                    $tbbody_detail .= '<tr>';
                                         $tbbody_detail .= '<td class="col-detail col-1" style="text-align:left;width: 53px;">&nbsp;</td>';
                                        $tbbody_detail .= '<td class="col-detail col-1" style="text-align:left;width: 560px;">'.$text_col1.'</td>';
                                         $tbbody_detail .= '<td class="col-detail col-1" style="text-align:left;width: 10px;">&nbsp;</td>';
                                        $tbbody_detail .= '<td class="col-detail col-2" style="text-align:right;width: 143px;" >'.$text_col2.'</td>';
                                    $tbbody_detail .= '</tr>';
                                }
                                
                                 if($i>=9 && $i<=11){
                                    $tbbody_detail2 .= '<tr>';
                                        $tbbody_detail2 .= '<td style="text-align:left;width:100%;">'.$text_col1.'</td>';
                                    $tbbody_detail2 .= '</tr>';
                                 }
                            }
                            
                            $html_body .= '<table class="table-description" cellpadding="0" cellspacing="0" border="0"  autosize="0">';
                                $html_body .= "<tbody>";
                                $html_body .= $tbbody_detail;
                                $html_body .= "</tbody>";
                            $html_body .= '</table>';
                   
                           //$html_body .= '<div style="height:100%;width:100%;background:red">x</div>';
                            
                            //6 footer
                            $tbfoot_top = ($comp_name_short=="ACSD") ? 719 : 710;
                            $tbfoot_left = 632;
                            $tbfoot_width = 137;
                            $tbfoot_fontsize = 20;
                            $tbfoot_textalign = "right";
                             
                            $div_detail2 = '<table class="table-description2" cellpadding="0" cellspacing="0" border="0"  autosize="0">';
                                $div_detail2 .= "<tbody>";
                                $div_detail2 .= $tbbody_detail2;
                                $div_detail2 .= "</tbody>";
                            $div_detail2 .= '</table>';
                             
                             $html_footer .= __pdf_box($div_detail2,array($tbfoot_top,43,417,$tbfoot_fontsize,"left"," overflow:hidden;height:82px;"));
                             
                             $html_footer .= __pdf_box($text_invrcpt_subtotal,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//จำนวนเงิน
                             $tbfoot_top += 30.2;
                             $html_footer .= __pdf_box($text_invrcpt_vatpercent."&nbsp;%",array(($tbfoot_top-3),587,35,$tbfoot_fontsize,$tbfoot_textalign,null));//ภาษีมูลค่าเพิ่ม xx %
                             $html_footer .= __pdf_box($text_cal_vat,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//ภาษีมูลค่าเพิ่ม
                             $tbfoot_top += 30.2;
                             $html_footer .= __pdf_box($text_cal_grand_total,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//จำนวนเงินรวมทั้งสิ้น
                            
                             //บาทไทย
                             $html_footer .= __pdf_box("(".$text_bath_thai.")",array($tbfoot_top+33,130,380,20,"left",null));  
                             
                            $html_style .= '<style>';
                            $html_style .= '
                            .cus-detail{ }
                            .cus-detail-1{float: left;}
                            .cus-detail-2{float: right;}
                            
                            .table-description{
                                overflow:wrap;
                                width:469px;
                            }
                            
                            .table-description2{
                                overflow:wrap;
                                 width:100%;
                            }
                             
                            .table-description2 td,
                            .table-description td{
                                font-size:20px;
                                font-weight:bold;
                                border:1px solid red;
                                border:none;
                                vertical-align: top;
                                padding-right:3px;
                            }
                            
                             .table-description2 td{
                                 line-height:1.0;
                             } ';
                            
                            $html_style .= '</style>';   
                        break;
                        
                        //ธรรมบุรี 
                        case "TBRI" :
                            //setup
                            $count_page_copy = 1;//1
                            $array_setup_page = array("A4-P", 102, 130, 17, 16, 16, 10, 10);//2
                            $array_setup_titlepage = array(52, 0, 735, 36, "right", null);//3
                
                            //4 header
                            $cus_detail = $cust_name;
                            $cus_detail .= "<div>".$cust_address."</div>";
                            $cus_detail .= "<div>เลขประจำตัวผู้เสียภาษี&nbsp;&nbsp;".$cust_taxno."</div>";
                            $html_header .= __pdf_box($invrcpt_no,array(222,589,150,24,"left",null));//เลขที่
                            $html_header .= __pdf_box($text_full_invrcpt_date,array(269,589,150,24,"left",null));//วันที่
                            $html_header .= __pdf_box($cus_detail,array(221,90,380,20,"left","line-height:1.2;"));
                            
                            //5 body
                            $tbbody_detail = "";
                            $check_sub_description = 0;        
                            for ($i=1; $i <=8; $i++) {
                               $tbbody_textalign_col2 = ($i<=2) ? "center":"right";
                               $text_col1 = $arrayTableDes[$i]["description"];
                               $text_col1 .= ($i==6) ? " ".$arrayTableDes[$i]["sub_description"] : "";
                               $text_col2 = ($i<=5) ?  $arrayTableDes[$i]["sub_description"] : $arrayTableDes[$i+1]["sub_description"];   
                               $text_col3 = $arrayTableDes[$i]["text_amount"];   
                               
                               if($text_col2!=""){
                                   $check_sub_description +=1;
                               }
                               
                                 $tbbody_detail .= '<tr>';
                                     $tbbody_detail .= '<td class="col-detail col-1" style="text-align:left">'.$text_col1.'</td>';
                                     $tbbody_detail .= '<td class="col-detail col-2" style="text-align:'.$tbbody_textalign_col2.'" >'.$text_col2.'</td>';
                                     $tbbody_detail .= '<td class="col-detail col-3" style="text-align:right">'.$text_col3.'</td>';
                                 $tbbody_detail .= '</tr>';
                            }
                            
                            $html_body .= '<table class="table-description" cellpadding="0" cellspacing="0" border="0"  autosize="0">';
                                $html_body .= "<tbody>";
                                $html_body .= $tbbody_detail;
                                $html_body .= "</tbody>";
                            $html_body .= '</table>';
                            
                            //6 footer
                             $tbfoot_top = 615;
                             $tbfoot_left = 574;
                             $tbfoot_width = 156;
                             $tbfoot_fontsize = 20;
                             $tbfoot_textalign = "right";
                             $html_footer .= __pdf_box($text_invrcpt_subtotal,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//จำนวนเงิน
                             $tbfoot_top += 30;
                             $html_footer .= __pdf_box($text_invrcpt_vatpercent."% &nbsp;",array($tbfoot_top,535,35,$tbfoot_fontsize,$tbfoot_textalign,null));//ภาษีมูลค่าเพิ่ม xx %
                             $html_footer .= __pdf_box($text_cal_vat,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//ภาษีมูลค่าเพิ่ม
                             $tbfoot_top += 30;
                             $html_footer .= __pdf_box($text_cal_grand_total,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//จำนวนเงินรวมทั้งสิ้น
                            
                             //บาทไทย
                             $html_footer .= __pdf_box("<div class='baththai'>".$text_bath_thai."</div>",array(750,357,380,20,"center",null));  
                             //โปรดชำระเงินภายในวันที่
                             $html_footer .= __pdf_box($text_full_invrcpt_duedate,array(821,210,380,20,"left",null));
                           
                           //7 style
                            $html_style .= '<style>';
                            $html_style .= '
                                .table-description{
                                    overflow:wrap;
                                    width:666px;
                                }
                                .table-description td{
                                    font-size:20px;
                                    font-weight:bold;
                                    border:1px solid red;
                                    border:none;
                                    vertical-align: top;
                                    padding-right:3px;
                                }
                                .baththai{
                                    border: 2px solid #000; 
                                    background-color: #F2F2F2; 
                                    padding: 2px; 
                                    padding-top: 3px;
                                }';
                                
                               if($check_sub_description>=1){
                                   $html_header .= __pdf_box('<div style="height:246px;overflow:hidden;display:block;border-left:1.5px solid #000000">&nbsp;</div>' ,array(383,445,670,24,"left",null));
                                   $html_style .= 'td.col-1{width: 384px;}td.col-2{width: 120px;}td.col-3{width: 166px;}';
                               }else{
                                   $html_style .= 'td.col-1{width: 500px;}td.col-2{width: 4px;}td.col-3{width: 166px;}';
                               }
    
                               $html_style .= '</style>';   
                            break;
                        case "TPPT" : case "RPEC" :  case "EPTG" : case "TTNI" :
                            $count_page_copy = 1;//1
                            $array_setup_titlepage = array(52, 0, 735, 36, "right", null);//3
                            
                            if($comp_name_short=="TPPT"){
                                $array_setup_page = array("A4-P", 112, 10, 13, 0, 0, 0);//2
                                $html_header .= __pdf_box($invrcpt_no,array(233,520,150,24,"left",null));//เลขที่
                                $html_header .= __pdf_box($text_full_invrcpt_date,array(293,520,150,24,"left",null));//วันที่
                                $html_header .= __pdf_box($cust_name,array(233,80,400,24,"left",null));//customer_name
                                $html_header .= __pdf_box($cust_address,array(260,80,400,24,"left",null));//customer_address
                            }else if($comp_name_short=="EPTG" || $comp_name_short=="RPEC"){
                                $array_setup_page = array("A4-P", 92, 10, 13, 0, 0, 0);//2
                                $html_header .= __pdf_box($invrcpt_no,array(159,520,150,24,"left",null));//เลขที่
                                $html_header .= __pdf_box($text_full_invrcpt_date,array(219,520,150,24,"left",null));//วันที่
                                $html_header .= __pdf_box($cust_name,array(159,80,400,24,"left",null));//customer_name
                                $html_header .= __pdf_box($cust_address,array(186,80,380,24,"left",null));//customer_address
                            }else if($comp_name_short=="TTNI"){
                                $array_setup_page = array("A4-P", 121, 10, 13, 0, 0, 0);//2
                                $array_setup_titlepage = array(52, 30, 735, 36, "left", null);//3
                                $html_header .= __pdf_box($invrcpt_no,array(272,520,150,24,"left",null));//เลขที่
                                $html_header .= __pdf_box($text_full_invrcpt_date,array(332,520,150,24,"left",null));//วันที่
                                $html_header .= __pdf_box($cust_name,array(272,80,400,24,"left",null));//customer_name
                                $html_header .= __pdf_box($cust_address,array(299,80,380,24,"left",null));//customer_address
                            }
                            
                            $tbbody_detail = "";
                            $check_sub_description = 0;        
                            for ($i=1; $i <=8; $i++) {
                               $tbbody_textalign_col2 = ($i<=2) ? "center":"right";
                               $text_col1 = $arrayTableDes[$i]["description"];
                               $text_col1 .= ($i==6) ? " ".$arrayTableDes[$i]["sub_description"] : "";
                               $text_col2 = ($i<=5) ?  $arrayTableDes[$i]["sub_description"] : $arrayTableDes[$i+1]["sub_description"];   
                               $text_col3 = $arrayTableDes[$i]["text_amount"];   
                               
                               if($text_col2!=""){
                                   $check_sub_description +=1;
                               }
                               
                                 $tbbody_detail .= '<tr>';
                                     $tbbody_detail .= '<td class="col-detail col-1" style="text-align:left">'.$text_col1.'</td>';
                                     $tbbody_detail .= '<td class="col-detail col-2" style="text-align:'.$tbbody_textalign_col2.'" >'.$text_col2.'</td>';
                                     $tbbody_detail .= '<td class="col-detail col-3" style="text-align:right">'.$text_col3.'</td>';
                                 $tbbody_detail .= '</tr>';
                            }
                            
                            $html_body .= '<table class="table-description" cellpadding="0" cellspacing="0" border="0"  autosize="0">';
                            $html_body .= "<tbody>";
                            $html_body .= $tbbody_detail;
                            $html_body .= "</tbody>";
                            $html_body .= '</table>';
                            
                            $html_style .= '<style>';
                            $html_style .= '
                                .table-description{
                                    overflow:wrap;
                                    width:695px;
                                }
                                .table-description td{
                                    font-size:20px;
                                    font-weight:bold;
                                    border:1px solid red;
                                    border:none;
                                    vertical-align: top;
                                    padding-right:3px;
                                }
                                .baththai{
                                    border: 2px solid #000; 
                                    background-color: #F2F2F2; 
                                    padding: 2px; 
                                    padding-top: 3px;
                                }';

                               $html_style .= '</style>';   
                                if($comp_name_short=="EPTG" || $comp_name_short=="RPEC"){
                                    $tbfoot_top = 660;

                                }else if($comp_name_short=="TPPT"){
                                    $tbfoot_top = 613;

                                }else if($comp_name_short=="TTNI"){
                                    $tbfoot_top = 680;

                                }
                                
                                $tbfoot_left = 585;
                                $tbfoot_width = 156;
                                $tbfoot_fontsize = 20;
                                $tbfoot_textalign = "right";

                                 $html_footer .= __pdf_box($text_invrcpt_subtotal,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//จำนวนเงิน
                                 $tbfoot_top += 30;
                                 $html_footer .= __pdf_box($text_cal_vat,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//ภาษีมูลค่าเพิ่ม
                                 $tbfoot_top += 30;
                                 $html_footer .= __pdf_box($text_cal_grand_total,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//จำนวนเงินรวมทั้งสิ้น
                                
                                 //บาทไทย
                                if($comp_name_short=="EPTG" || $comp_name_short=="RPEC"){
                                    $baththai = 755;
                                }else if($comp_name_short=="TPPT"){
                                    $baththai = 705;
                                }else if($comp_name_short=="TTNI"){
                                    $baththai = 770;
                                }
                                $html_footer .= __pdf_box($text_bath_thai,array($baththai,55,380,25,"center",null));                                      
                                 
                            break;
                    }
                    if($type=="I" || $type=="D" || $type=="P"){
                        $mpdf =  __pdf_setup($array_setup_page);
                        $mpdf->SetDocTemplate($template_pdf,true);
                        $mpdf->useFixedNormalLineHeight = true;
                        $mpdf->packTableData = true;
                        $mpdf->keep_table_proportions  = true;
                        $mpdf->shrink_tables_to_fit=0;
                        
                        if($type=="P"){
                            $mpdf->SetWatermarkText('Preview');
                            $mpdf->watermarkTextAlpha = 0.1;
                            $mpdf->showWatermarkText = true;
                        }

                        if($invrcpt_stsid == "STS003"){
                            $mpdf->SetWatermarkText('Cancel');
                            $mpdf->watermarkTextAlpha = 0.1;
                            $mpdf->showWatermarkText = true;                            
                        }

                        $mpdf->SetHTMLHeader( __pdf_box("ต้นฉบับ",$array_setup_titlepage).$html_header);
                        $mpdf->SetHTMLFooter($html_footer);
                        $mpdf->AddPage();
                        $mpdf->WriteHTML($html_style.$html_body);
                         
                         if($count_page_copy>=1){
                            $mpdf->SetHTMLHeader( __pdf_box("สำเนา",$array_setup_titlepage).$html_header);
                            $mpdf->SetHTMLFooter($html_footer);
                             
                             for ($i=1; $i <=$count_page_copy ; $i++) {
                                $mpdf->AddPage();
                                $mpdf->WriteHTML($html_body);
                             }
                         }
                         
                          if($type=="P"){
                              $path_temp = $path."/tmp/";
                              if (!file_exists($path_temp)) {
                                    mkdir($path_temp, 0777, true);
                                }
                              
                              $file_temp = "invoice_rcpt_add".__encode(tempnam(sys_get_temp_dir(), 'Pre_')).".pdf";
                              
                              $mpdf->Output($path_temp.$file_temp,'F');  
                              $preview_url = "tmp/".$file_temp;
                              $preview_path = $path_temp.$file_temp;
                          }else{
                            $mpdf->Output($file_name,$type);
                          }
                            
                    }else if($type=="HTML"){
                        $content_pdf .=  '<div>
                            <iframe style="width:100%;height:100%;border:3px solid #000000;" sandbox srcdoc="<body>'.htmlentities($html).'</body>"></iframe>
                        </div>';
                    }
                    
                    $response = "S";
                }else{
                    $message = "ไม่มี Template PDF";
                }
            }else{
                 $message = "ไม่พบข้อมูล";
            }
        }else{
            $message = "กรุณาระบุรหัส";
        }
        
        return array('response' =>$response,    'message' => $message,"content"=>$content_pdf,"preview_url"=>$preview_url,"preview_path"=>$preview_path);
    }
}

if (!function_exists('__pdf_receipt_revenue')) {
    function __pdf_receipt_revenue($datalist=array(),$type="I"){
        $response = "F";
        $message = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";
        $content_pdf= "";
        $preview_url = "";
        $preview_path = "";
        
        if(!empty($datalist)){
            $path = realpath(dirname(__FILE__). '/../');
            $db = new database();
            
            if(!empty($datalist)){
                include $path.'/variable_receipt.php';
                
                $template_pdf = $path."/template_pdf/receipt_revenue/".$comp_name_short.".pdf";
                $template_pdf = $template_pdf;
                
                if(file_exists($template_pdf)){

                    $file_name  =  "receipt".$comp_name_short."-".$receipt_Reno.".pdf";
                    
                    
                    //template
                    $mpdf_template = __pdf_setup();
                    $template_pagecount = $mpdf_template->setSourceFile($template_pdf);
                    $template_import_page = $mpdf_template->importPage($template_pagecount);
                    $template_size = $mpdf_template->getTemplateSize($template_import_page);
                    $template_size_width = $template_size["width"];
                    $template_size_height= $template_size["height"];
                    
                     $count_page_copy = 0;//1
                     $array_setup_page = 0;//2
                     $array_setup_titlepage = 0;//3
                     
                    $html_header = "";//4
                    $html_body = "";//5
                    $html_footer = "";//6
                    //7
                    $html_style = '<style>
                        body{
                            font-family: "sarabun", sans-serif;
                        }
                      .box-pdf{
                            font-weight:bold;
                            position: absolute;
                            display:block;
                            color:#000000;
                        }
                     </style>'; 
                    switch ($comp_name_short) {
                         //อรุณ / เอซีเอส โปรเจคท์
                        case "ACS" : case "ACSP" : case "EPTG" : case "RPEC" : case "TPPT" : case "TTNI" : 
                            
                            $count_page_copy = 3;//1
                            $_format = "A5-L"; $_mb = 2; $_ml = 5; $_mr = 5; $_mh = 2; $_mf = 2;
                            $header_t = 110; $header_l = 80;
                            $_t = 110; $_l = 650; $_w = 160; $_f = 24;
                            $_ct = 140; $_cl = 180; $_cw = 400; $_cf = 20;
                            $_cdt = 165; $cdl = 90; $cdw = 700; $cdf = 20; 
                            $_dt = 140; $_dl = 650; $_dw = 120; $_df = 20;
                            
                            $tbfoot_top = 386; $tbfoot_left = 675; $tbfoot_width = 100; $tbfoot_fontsize = 20; $tbfoot_textalign = "right";
                            $_tbath = 465; $_lbath = 370; $_wbath = 380; $_fbath = 20;
                            $_tinv = 385; $_linv = 35; $_winv = 380; $_finv = 20;
                            $_tdp = 464; $_ldp = 70; $_wdp = 200; $_fdp = 20;
                            $_tcheque = 410; $_lcheque = 280; $_wcheque = 200; $_fcheque = 20; 
                            $_tnote = 490; $_lnote = 20; $_wnote = 350; $_fnote = 20;
                            $_tbank = 436; $_lbank = 90; $_wbank = 350; $_fbank = 20; 
                            $_tbrc = 436; $_lbrc = 320; $_wbrc = 350; $_fbrc = 20; 
                            $_tc = 407; $_lc = 18; $_wc = 200; $_fc = 20; 
                            $_tcn = 407; $_lcn = 131; $_wcn = 200; $_fcn = 20;
                            $tbfoot_l = 520;
                            
                            if($comp_name_short == "ACS"){
                                $_mt = 69;
                                $html_header .= __pdf_box($receipt_Bookno,array(73,650,100,24,"left",null));//
                                $html_footer .= __pdf_box($receipt_invNo,array($_tinv,$_linv,$_winv,$_finv,"center",null));// เลขที่ใบแจ้งหนี้                                      

                            }else if($comp_name_short == "ACSP"){
                                $_mt = 71; $_mf = 3;
                                $_l = 630;
                                $_ct = 148;
                                $_cdt = 173;
                                $_dt = 148; $_dl = 655;
                                $tbfoot_top = 394;
                                $_tinv = 391; $_tc = 415; $_tcn = 415; $_tcheque = 418; $_tbank = 444; $_tbrc = 444; $_tdp = 471; $_tbath = 471; $_tnote = 493;
                                $html_footer .= __pdf_box($receipt_invNo,array($_tinv,$_linv,$_winv,$_finv,"center",null));// เลขที่ใบแจ้งหนี้                                      

                            }else if($comp_name_short == "EPTG"){
                                $_mt = 70;
                                $_ct = 145; $_cl = 105;
                                $cdl = 105; $_cdt = 171;
                                $_dt = 145;
                                $tbfoot_top = 390; $_tinv = 388; $_tc = 415; $_tcn = 415; $_lcn = 128; $_tcheque = 415; $_tbank = 442; $_tbrc = 442; $_tdp = 469; $_tbath = 469;
                                $html_footer .= __pdf_box($receipt_useridCreate,array(492,250,$_wbrc,$_fbrc,"left",null));                                                               
                                $html_footer .= __pdf_box($receipt_invNo,array($_tinv,$_linv,$_winv,$_finv,"center",null));// เลขที่ใบแจ้งหนี้                                      

                            }else if($comp_name_short == "TPPT"){
                                $count_page_copy = 2;
                                $_mt = 70;
                                $header_t = 115;
                                $_t = 117;
                                $_cl = 105; $_ct = 150;
                                $_cdt = 177; $cdl = 105;
                                $_dt = 150;
                                $tbfoot_top = 388; $_tc = 490; $_lc = 18; $_tcn = 490; $_lcn = 89; $_tcheque = 493; $_lcheque = 175; $_tbank = 493; $_lbank = 330; $_tbrc = 493; $_lbrc = 500;
                                $_tdp = 493; $_ldp = 665; $_tbath = 465; $_lbath = 70;    
                                $html_footer .= __pdf_box($receipt_useridCreate,array(525,250,$_wbrc,$_fbrc,"left",null));                                                               

                            }else if($comp_name_short == "TTNI"){
                                $_mt = 67;
                                $header_t = 65; $header_l = 350;
                                $_t = 99;
                                $_ct = 135; $_cl = 105;
                                $_cdt = 160; $cdl = 105;
                                $_dt = 135;
                                $tbfoot_top = 380; $_tbath = 457; $_tinv = 377; $_tdp = 457; $_tcheque = 403; $_tbank = 430; $_tbrc = 430; $_tc = 402; $_tcn = 402; $_lcn = 129;
                                $html_footer .= __pdf_box($receipt_useridCreate,array(492,250,$_wbrc,$_fbrc,"left",null));                                                               
                                $html_footer .= __pdf_box($receipt_invNo,array($_tinv,$_linv,$_winv,$_finv,"center",null));// เลขที่ใบแจ้งหนี้                                      

                            }else if($comp_name_short == "RPEC"){
                                $count_page_copy = 2;
                                $_mt = 68;
                                $header_t = 105;
                                $_cl = 105;
                                $_cdt = 168; $cdl = 105;
                                $tbfoot_top = 381; $_tc = 483; $_lc = 13; $_tcn = 483; $_lcn = 82; $_tcheque = 485; $_lcheque = 160; $_tbank = 485; $_lbank = 340; $_tbrc = 485; $_lbrc = 510;
                                $_tdp = 485; $_ldp = 670; $_tbath = 457; $_lbath = 70;
                                $html_footer .= __pdf_box($receipt_useridCreate. "&nbsp;(แทน)",array(525,290,$_wbrc,$_fbrc,"left",null));                                                               

                            }
                            
                            $array_setup_page = array($_format, $_mt, $_mb, $_ml, $_mr, $_mh, $_mf);//2
                            
                            //header
                            $array_setup_titlepage = array($header_t, $header_l, 0, 27, "center", "width:100%;");//3
                            $html_header .= __pdf_box($receipt_Reno,array($_t,$_l,$_w,$_f,"left",null));//เลขที่
                            $html_header .= __pdf_box($cust_name,array($_ct,$_cl,$_cw,$_cf,"left",null));//ชื่อลูกค้า
                            $html_header .= __pdf_box($cust_address,array($_cdt,$cdl,$cdw,$cdf,"left",null));//ที่อยู่
                            $html_header .= __pdf_box(__date($receipt_Redate,"full") ,array($_dt,$_dl,$_dw,$_df,"left",null));//วันที่
                            
                            //body
                            $tbody = '';
                            for($i = 1; $i <= 8; $i++){
                                
                                if(${"receipt_description" . $i} == "") continue;
                                
                                $tbody .= '<tr>';
                                $tbody .= '<td style="text-align: left; font-size: 20px; line-height: 1.23"><b>' . ${"receipt_description" . $i} .'</b></td>';
                                
                                if($i<=5){
                                    $tbody .= '<td style="text-align: right; font-size: 20px;"><b>'. ${"receipt_amount" . $i} .'</b></td>';
                                }
                                $tbody .= '</tr>';
                            }
                            
                            $html_body .= '<table class="table-description" cellpadding="0" cellspacing="0" border="0"  autosize="0" width="100%">';
                            $html_body .= "<tbody>";
                            $html_body .= $tbody;
                            $html_body .= "</tbody>";
                            $html_body .= '</table>';
                            
                            //footer
                            $html_footer .= __pdf_box($receipt_subtotal,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//จำนวนเงิน
                            $tbfoot_top += 25;
                            $html_footer .= __pdf_box($vatpercentHidden,array($tbfoot_top,$tbfoot_l,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));
                            $html_footer .= __pdf_box($receipt_vat,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//ภาษีมูลค่าเพิ่ม
                            $tbfoot_top += 25;
                            $html_footer .= __pdf_box($receipt_grandtotal,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//จำนวนเงินรวมทั้งสิ้น
                            $html_footer .= __pdf_box($text_bath_thai,array($_tbath,$_lbath,$_wbath,$_fbath,"left",null));// ตัวอักษร                                   
                            $html_footer .= __pdf_box(__date($receipt_datePay,"full"),array($_tdp,$_ldp,$_wdp,$_fdp,"left",null));  
                            
                            if($receipt_Selpay == 1){
                                $html_footer .= __pdf_box("/",array($_tc,$_lc,$_wc,$_fc,"left",null));                   

                            }else if($receipt_Selpay ==2){
                                $html_footer .= __pdf_box("/",array($_tcn,$_lcn,$_wcn,$_fcn,"left",null));                               

                            }
                            $html_footer .= __pdf_box($receipt_chequeNo,array($_tcheque,$_lcheque,$_wcheque,$_fcheque,"left",null));                                    
                            $html_footer .= __pdf_box($receipt_ReNote,array($_tnote,$_lnote,$_wnote,$_fnote,"left"));                                     
                            $html_footer .= __pdf_box($bank_name,array($_tbank,$_lbank,$_wbank,$_fbank,"left",null));                            
                            $html_footer .= __pdf_box($brc_name,array($_tbrc,$_lbrc,$_wbrc,$_fbrc,"left",null)); 

                        break;
                        
                        case "ACSD" : case "ACSI" : case "TBRI" : 
                            $count_page_copy = 3;//1
                            $_format = "A4-P"; $_mt = 97; $_mb = 2; $_ml = 25; $_mr = 9; $_mh = 2; $_mf = 2;
                            $_t = 140; $_l = 645; $_w = 120; $_f = 24;
                            $_dt = 170; $_dl = 645; $_dw = 120; $_df = 20;
                            $_ct = 215; $_cl = 250; $_cw = 400; $_cf = 20;
                            $_cdt = 240; $cdl = 130; $cdw = 700; $cdf = 20; 
                            
                            $header_t = 160; $header_l = 0; $head_align = "center";
                            
                            $tbfoot_top = 610; $tbfoot_left = 660; $tbfoot_width = 100; $tbfoot_fontsize = 20; $tbfoot_textalign = "right";
                            $_tbath = 700; $_lbath = 110; $_wbath = 380; $_fbath = 20;
                            $_tinv = 605; $_linv = 90; $_winv = 380; $_finv = 20;
                            $_tdp = 733; $_ldp = 635; $_wdp = 200; $_fdp = 20;
                            $_tcheque = 637; $_lcheque = 180; $_wcheque = 200; $_fcheque = 20; 
                            $_tnote = 490; $_lnote = 20; $_wnote = 350; $_fnote = 20;
                            $_tbank = 733; $_lbank = 135; $_wbank = 350; $_fbank = 20; 
                            $_tbrc = 733; $_lbrc = 355; $_wbrc = 350; $_fbrc = 20; 
                            $tbfoot_l = 480;

                            if($comp_name_short == "ACSD"){
                                $html_footer .= __pdf_box($receipt_useridCreate,array(862,200,$_wbrc,$_fbrc,"left",null));                                                               

                            }else if($comp_name_short == "ACSI"){
                                $_mt = 100;
                                $header_t = 180; $header_l = 0;
                                $_t = 152;
                                $_dt = 182;
                                $_ct = 226;
                                $_cdt = 252;
                                $tbfoot_top = 620;
                                $_tbath = 711;
                                $_tdp = 745;
                                $_tcheque = 648;
                                $_tbank = 745;
                                $_tbrc = 745;
                                $_tinv = 617; $_linv = 80;
                                $html_footer .= __pdf_box($receipt_useridCreate,array(874,200,$_wbrc,$_fbrc,"left",null));                                                               

                            }else if($comp_name_short == "TBRI"){
                                $_mt = 107; $_ml = 18; $_mr = 19;
                                $header_t = 50; $header_l = -50; $head_align = "right";
                                $_t = 230; $_l = 590; 
                                $_dt = 280; $_dl = 590;
                                $_ct = 229; $_cl = 90;
                                $_cdt = 255; $cdl = 90; $cdw = 400;
                                $tbfoot_top = 630; $tbfoot_left = 620;
                                $_tbath = 720; $_lbath = 120;
                                $_tdp = 750; $_ldp = 590;
                                $_tcheque = 659; $_lcheque = 204;
                                $_tbank = 750; $_lbank = 155;
                                $_tbrc = 750; $_lbrc = 380;
                                $_tinv = 628; $_linv = 40;
                                $tbfoot_l = 450;
                                $html_footer .= __pdf_box($receipt_useridCreate,array(855,200,$_wbrc,$_fbrc,"left",null));                                                               

                            }
                            
                            $array_setup_page = array($_format, $_mt, $_mb, $_ml, $_mr, $_mh, $_mf);//2
                            
                            //header
                            $array_setup_titlepage = array($header_t, $header_l, 0, 27, $head_align, "width:100%;");//3
                            $html_header .= __pdf_box($receipt_Reno,array($_t,$_l,$_w,$_f,"left",null));//เลขที่
                            $html_header .= __pdf_box(__date($receipt_Redate,"full") ,array($_dt,$_dl,$_dw,$_df,"left",null));//วันที่
                            $html_header .= __pdf_box($cust_name,array($_ct,$_cl,$_cw,$_cf,"left",null));//ชื่อลูกค้า
                            $html_header .= __pdf_box($cust_address,array($_cdt,$cdl,$cdw,$cdf,"left",null));//ที่อยู่
                            
                            //body
                            $tbody = '';
                            for($i = 1; $i <= 8; $i++){
                                
                                if(${"receipt_description" . $i} == "") continue;
                                
                                $tbody .= '<tr>';
                                $tbody .= '<td style="text-align: left; font-size: 20px; line-height: 1.23"><b>' . ${"receipt_description" . $i} .'</b></td>';
                                
                                if($i<=5){
                                    $tbody .= '<td style="text-align: right; font-size: 20px;"><b>'. ${"receipt_amount" . $i} .'</b></td>';
                                }
                                $tbody .= '</tr>';
                            }
                            
                            $html_body .= '<table class="table-description" cellpadding="0" cellspacing="0" border="0"  autosize="0" width="100%">';
                            $html_body .= "<tbody>";
                            $html_body .= $tbody;
                            $html_body .= "</tbody>";
                            $html_body .= '</table>';
                            
                            
                            
                            
                            //footer
                            $html_footer .= __pdf_box($receipt_subtotal,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//จำนวนเงิน
                            $tbfoot_top += 30;
                            $html_footer .= __pdf_box($vatpercentHidden,array($tbfoot_top,$tbfoot_l,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));
                            $html_footer .= __pdf_box($receipt_vat,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//ภาษีมูลค่าเพิ่ม
                            $tbfoot_top += 30;
                            $html_footer .= __pdf_box($receipt_grandtotal,array($tbfoot_top,$tbfoot_left,$tbfoot_width,$tbfoot_fontsize,$tbfoot_textalign,null));//จำนวนเงินรวมทั้งสิ้น
                            $html_footer .= __pdf_box($text_bath_thai,array($_tbath,$_lbath,$_wbath,$_fbath,"left",null));// ตัวอักษร                                   
                            $html_footer .= __pdf_box($receipt_invNo,array($_tinv,$_linv,$_winv,$_finv,"center",null));// เลขที่ใบแจ้งหนี้                                      
                            $html_footer .= __pdf_box(__date($receipt_datePay,"full"),array($_tdp,$_ldp,$_wdp,$_fdp,"left",null));    
                            $html_footer .= __pdf_box($receipt_chequeNo,array($_tcheque,$_lcheque,$_wcheque,$_fcheque,"left",null));                                    
                            $html_footer .= __pdf_box($receipt_ReNote,array($_tnote,$_lnote,$_wnote,$_fnote,"left"));                                     
                            $html_footer .= __pdf_box($bank_name,array($_tbank,$_lbank,$_wbank,$_fbank,"left",null));                            
                            $html_footer .= __pdf_box($brc_name,array($_tbrc,$_lbrc,$_wbrc,$_fbrc,"left",null));                                

                        break;
                        

                    }
                    if($type=="I" || $type=="D" || $type=="P"){
                        $mpdf =  __pdf_setup($array_setup_page);
                        $mpdf->SetDocTemplate($template_pdf,true);
                        $mpdf->useFixedNormalLineHeight = true;
                        $mpdf->packTableData = true;
                        $mpdf->keep_table_proportions  = true;
                        $mpdf->shrink_tables_to_fit=0;
                        
                        if($type=="P"){
                            $mpdf->SetWatermarkText('Preview');
                            $mpdf->watermarkTextAlpha = 0.1;
                            $mpdf->showWatermarkText = true;
                        }

                        if($re_stsid == "STS003"){
                            $mpdf->SetWatermarkText('Cancel');
                            $mpdf->watermarkTextAlpha = 0.1;
                            $mpdf->showWatermarkText = true;
                        }

                        $mpdf->SetHTMLHeader( __pdf_box("ต้นฉบับ",$array_setup_titlepage).$html_header);
                        $mpdf->SetHTMLFooter($html_footer);
                        $mpdf->AddPage();
                        $mpdf->WriteHTML($html_style.$html_body);
                         
                         if($count_page_copy>=1){
                            $mpdf->SetHTMLHeader( __pdf_box("สำเนา",$array_setup_titlepage).$html_header);
                            $mpdf->SetHTMLFooter($html_footer);
                             
                             for ($i=1; $i <=$count_page_copy ; $i++) {
                                $mpdf->AddPage();
                                $mpdf->WriteHTML($html_body);
                             }
                         }
                         
                          if($type=="P"){
                              $path_temp = $path."/tmp/";
                              if (!file_exists($path_temp)) {
                                    mkdir($path_temp, 0777, true);
                                }
                              
                              $file_temp = "receipt_add".__encode(tempnam(sys_get_temp_dir(), 'Pre_')).".pdf";

                              $mpdf->Output($path_temp.$file_temp,'F');  
                              $preview_url = "tmp/".$file_temp;
                              $preview_path = $path_temp.$file_temp;
                          }else{
                            $mpdf->Output($file_name,$type);
                          }
                            
                    }else if($type=="HTML"){
                        $content_pdf .=  '<div>
                            <iframe style="width:100%;height:100%;border:3px solid #000000;" sandbox srcdoc="<body>'.htmlentities($html).'</body>"></iframe>
                        </div>';
                    }
                    
                    $response = "S";
                }else{
                    $message = "ไม่มี Template PDF";
                }
            }else{
                 $message = "ไม่พบข้อมูล";
            }
        }else{
            $message = "กรุณาระบุรหัส";
        }
        
        return array('response' =>$response,    'message' => $message,"content"=>$content_pdf,"preview_url"=>$preview_url,"preview_path"=>$preview_path);
    }
}

if (!function_exists('__pdf_payment')) {
    function __pdf_payment($datalist=array(),$invoice_data_pdf=array(),$type="I"){
        $response = "F";
        $message = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";
        $content_pdf= "";
        $preview_url = "";
        $preview_path = "";
        
        if(!empty($datalist)){
            $path = realpath(dirname(__FILE__). '/../');
            $db = new database();
            
            if(!empty($datalist)){
                include $path.'/variable_payment.php';
                
                $template_pdf = $path."/template_pdf/payment.pdf";
                
                if(file_exists($template_pdf)){
                        $file_name  = "ใบสำคัญจ่าย"."-".$paym_no.".pdf";
                        
                        $html_header  = "";
                        $html_body = "";//5
                        $html_footer = "";//6
                        //7
                        $html_style = '<style>
                            body{
                                font-family: "sarabun", sans-serif;
                            }
                            
                           .box-pdf{
                                font-weight:normal;
                                position: absolute;
                                display:block;
                                color:#000000;
                                overflow:hidden;
								/*border-left:1px solid red;
								 border-right:1px solid pink;*/

                            }
                                       
                            .text-center{
                                text-align:center;
                            }
                            
                            .table-sign td{
                                 line-height:0.6;
                                 vertical-align: bottom;
								 font-size:18px;
                            }

                            .table-sign td.line-dotted{
                                border-bottom: 1px dotted #000;
                                font-weight: normal;
                            }
                         </style>'; 
                         
                       
						 //header
	                    $header_top_line1 = 10;
	                    $header_top_line2 = 33;
	                    $header_left_line2 = 430;
	                    $header_margin_line2 = 1.5;
	                    
						$body_fontsize = 17;
							
                    	$html_header .= __pdf_box("<b>ใบสำคัญจ่าย</b>",array($header_top_line1,680,100,20,"right",null));
                    	$html_header .= __pdf_box("<b>".$comp_name."</b>",array($header_top_line1,8,600,20,"left",null));
                    	$html_header .= __pdf_box($comp_address,array($header_top_line2,8,420, $body_fontsize,"left",null));
					   
					   	//วันที่
					    $header_width_line2 = 23;
                    	$header_left_line2 += ($header_width_line2+ $header_margin_line2);
                    	$header_width_line2 = 57;   
                    	$html_header .= __pdf_box($paym_date_datethai_slash,array($header_top_line2, $header_left_line2,$header_width_line2, $body_fontsize,"center",null));
                     	$header_left_line2 += ($header_width_line2+ $header_margin_line2);
						
						//ฝ่าย
                    	$header_width_line2 = 20;   
                     	$header_left_line2 += ($header_width_line2+ $header_margin_line2);
                    	$header_width_line2 = 65;     
                    	$text_dep_name = ($comp_count_dep>=2) ? $dep_name : "-";
                    	$html_header .= __pdf_box($text_dep_name,array($header_top_line2, $header_left_line2,$header_width_line2, $body_fontsize,"center",null));
                     	$header_left_line2 += ($header_width_line2+ $header_margin_line2);					 
                        
						//เลขที่ใบสำคัญจ่าย
                    	$header_width_line2 = 85; 
                    	$header_left_line2 += ($header_width_line2+ $header_margin_line2);
                   	 	$header_width_line2 = 94; 
                    	$html_header .= __pdf_box($paym_no,array($header_top_line2, $header_left_line2,$header_width_line2,16,"center",null));
						
						//ชื่อผู้รับเงิน
                    	$body_top = 58.5;
                    	$body_left = 12;
                    	$body_margin = 2;
                    	$html_body .= __pdf_box($invoice_data_pdf["text_paya_name_list"],array($body_top, ($body_left+59),713, $body_fontsize,"left","height:35px;line-height:1.2;"));
                   		$body_top += 20;
						
						//ชำระค่า
	                    $body_top += 24;
	                    $html_body .= __pdf_box($invoice_data_pdf["text_description_list"],array($body_top, ($body_left+45),727, $body_fontsize,"left","height:56px;line-height:1.2;"));
	                    $body_top += 20;
	                    $body_top += 20;
						
						//จำนวนใบแจ้งหนี้
                    	$body_left = 8;
                    	$body_top = 165.5;
                    	$body_width = 85;     
                    	$body_left += ($body_width+ $body_margin);
                    	$body_width = 50;     
                    	$html_body .= __pdf_box($invoice_data_pdf["text_count"],array($body_top, $body_left,$body_width, $body_fontsize,"center",null));
                   	 	$body_left += ($body_width+ $body_margin);
						$body_width = 70;     
						$body_left += ($body_width+ $body_margin);
						$body_width = 50; 
						$body_left += ($body_width+ $body_margin);
						$body_width = 22;
						$body_left += ($body_width+ $body_margin);
						$body_width = 42;     
						$body_left += ($body_width+ $body_margin);
						$body_width = 22; 
						$body_left += ($body_width+ $body_margin);
						
						//จ่ายโดย
	                     if($paym_typepay==1){
	                         $html_body .= __pdf_box("<b>/</b>",array(($body_top-7),273,23,30,"left",null));
	                    }else  if($paym_typepay==2){
	                        $html_body .= __pdf_box("<b>/</b>",array(($body_top-7),342,23,30,"left",null));
							
							//เช็คเลขที่
		                   $body_width = 45;     
		                   $body_left += ($body_width+ $body_margin);
		                   $body_width = 81;     
		                   $html_body .= __pdf_box($cheq_no,array($body_top, $body_left,$body_width, $body_fontsize,"center",null));
		                   $body_left += ($body_width+ $body_margin);
		                   
						   //ลงวันที่
		                   $body_width = 40;     
		                   $body_left += ($body_width+ $body_margin);
		                   $body_width = 73;     
		                   $html_body .= __pdf_box("&nbsp;",array($body_top, $body_left,$body_width, $body_fontsize,"center",null));
		                   $body_left += ($body_width+ $body_margin);
		                   
						   //ธนาคาร
		                   $body_width = 39;     
		                   $body_left += ($body_width+ $body_margin);
		                   $body_width = 128;
		                   $html_body .= __pdf_box($bank_name,array($body_top, $body_left,$body_width, $body_fontsize,"center",null));
	                    }  
						
						//เลขที่ใบแจ้งหนี้
						 $body_top += 27;
	                    $body_top_left = $body_top;
	                    $body_left = 95;
	                    $body_width = 300;
	                    $html_body .= __pdf_box($invoice_data_pdf["text_no_list"],array($body_top, $body_left,$body_width, $body_fontsize,"left","height:56px;line-height:1.2;"));
	                    $body_top += 20;
	                    $body_top += 20;
	                    $body_top += 20;
						
						//ผู้จัดทำ
                    	$body_left = 60;
                    	$body_width = 336;
                    	$html_body .= __pdf_box($invoice_data_pdf["text_create_list"],array($body_top, $body_left,$body_width, $body_fontsize,"left","height:56px;line-height:1.2;"));
                    	$body_top += 20;
                    	$body_top += 20;
						
						$body_left = 460;
	                    $body_margin = 3;
	                    
	                    $body_width_t = 92;
	                    $body_left_t = 400;
	                    
	                    $body_left_cal = $body_left_t+$body_width_t+$body_margin;
	                    $body_width_cal = 155;
	                    
	                    $body_left_number = $body_left_cal+$body_width_cal+$body_margin;
	                    $body_width_number = 100;
	                    
	                    $body_left_bath = $body_left_number+$body_width_number+$body_margin+4;
	                    $body_width_bath = 24;
	                    
	                    $body_left_line = $body_left_cal;
	                    $body_width_line = $body_width_cal+$body_width_number+$body_margin+7;
	                    
	                    //จำนวนเงิน
						$css_height_17 = "height:17px;";
	                    $html_body .= __pdf_box($invoice_data_pdf["text_subtotal_cal"],array($body_top_left, $body_left_cal,$body_width_cal, $body_fontsize,"right",$css_height_17));
	                    $html_body .= __pdf_box($invoice_data_pdf["text_subtotal"],array($body_top_left, $body_left_number,$body_width_number, $body_fontsize,"right",$css_height_17));
	                    
						//ภาษีมูลค่าเพิ่ม
	                    $body_top_left += 20;
	                    $html_body .= __pdf_box($invoice_data_pdf["text_vatpercent"],array($body_top_left, $body_left_cal,$body_width_cal, $body_fontsize,"right",$css_height_17));
	                    $html_body .= __pdf_box($invoice_data_pdf["text_vat"],array($body_top_left, $body_left_number,$body_width_number, $body_fontsize,"right",$css_height_17));
						
						//หักภาษี ณ ที่จ่าย 1
	                    $body_top_left += 20;
	                    $html_body .= __pdf_box($invoice_data_pdf["text_totaltax_1_cal"],array($body_top_left, $body_left_cal,$body_width_cal, $body_fontsize,"right",$css_height_17));
	                    $html_body .= __pdf_box($invoice_data_pdf["text_totaltax_1"],array($body_top_left, $body_left_number,$body_width_number, $body_fontsize,"right",$css_height_17));
	                    
						//หักภาษี ณ ที่จ่าย 2
						$body_top_left += 20;
	                    $html_body .= __pdf_box($invoice_data_pdf["text_totaltax_2_cal"],array($body_top_left, $body_left_cal,$body_width_cal, $body_fontsize,"right",$css_height_17));
	                    $html_body .= __pdf_box($invoice_data_pdf["text_totaltax_2"],array($body_top_left, $body_left_number,$body_width_number, $body_fontsize,"right",$css_height_17));
						
						//หักภาษี ณ ที่จ่าย 3
						$body_top_left += 20;
	                    $html_body .= __pdf_box($invoice_data_pdf["text_totaltax_3_cal"],array($body_top_left, $body_left_cal,$body_width_cal, $body_fontsize,"right",$css_height_17));
	                    $html_body .= __pdf_box($invoice_data_pdf["text_totaltax_3"],array($body_top_left, $body_left_number,$body_width_number, $body_fontsize,"right",$css_height_17));
						
						//ยอดชำระสุทธิ
	                    $body_top_left += 20;
	                    $html_body .= __pdf_box($invoice_data_pdf["text_total_netamount_cal"],array($body_top_left, $body_left_cal,$body_width_cal, $body_fontsize,"right",$css_height_17));
	                    $html_body .= __pdf_box($invoice_data_pdf["text_total_netamount"],array($body_top_left, $body_left_number,$body_width_number, $body_fontsize,"right",$css_height_17));
					
						//ตัวอักษร
	                    $body_top = $body_top_left+24;
						$html_body .= __pdf_box($invoice_data_pdf["bath_thai"],array($body_top, 66,720, $body_fontsize,"left",null));
						
                        //footer sing
                        $arrSign = __invoice_payment_sign($invoice_data_pdf["inv_id_main"]);
                        $count_sign = count($arrSign);
                        $footer_top = 510;
                        $footer_left = 14;
                        $footer_margin = 20;
                        $footer_fontsize = 20;
                        $footer_width = (705/$count_sign);   
                        foreach ($arrSign as $keySign => $valSign) {
                            $table_sign = "<table border='0' style='width:100%' class='table-sign' cellpadding='0' cellspacing='0' >";
                                $table_sign .= "<tr><td colspan='2' class='text-center line-dotted'>".$valSign["name"]."</td><tr>";
                                 $table_sign .= "<tr><td style='width:".$valSign["position_w"]."px;'><br><b>".$valSign["position"]." วันที่</b></td><td class='line-dotted text-center'><div style='width:100%;'>".$valSign["date"]."</div></td><tr>";
                            	$table_sign .= "<tr><td colspan='2' >&nbsp;</td><tr>";
                            $table_sign .= "</table>";
                            
                           $html_footer .= __pdf_box($table_sign,array($footer_top, $footer_left,$footer_width, $footer_fontsize,"center","line-height:1.0;"));
                           $footer_left += ($footer_width+ $footer_margin);
                        }
                                                 
                        if($type=="I" || $type=="D" || $type=="P"){
                            $array_setup_page = array("A5-L", 14, 4, 2, 2, 2, 3, 3);
                            $mpdf =  __pdf_setup($array_setup_page);
                            $mpdf->SetDocTemplate($template_pdf,true);
                            $mpdf->useFixedNormalLineHeight = true;
                            $mpdf->packTableData = true;
                            $mpdf->keep_table_proportions  = true;
                            $mpdf->shrink_tables_to_fit=0;
                            
                            if($type=="P"){
                                $mpdf->SetWatermarkText('Preview');
                                $mpdf->watermarkTextAlpha = 0.1;
                                $mpdf->showWatermarkText = true;
                            }
    
                            $mpdf->SetHTMLHeader($html_header);
                            $mpdf->SetHTMLFooter($html_footer);
                            $mpdf->WriteHTML($html_style.$html_body);
                             
                          $mpdf->SetTitle($file_name);
                         
                          if($type=="P"){
                              $path_temp = $path."/tmp/";
                              if (!file_exists($path_temp)) {
                                    mkdir($path_temp, 0777, true);
                                }
                              
                              $file_temp = "payment_preview".__encode(tempnam(sys_get_temp_dir(), 'Pre_')).".pdf";
                              
                              $mpdf->Output($path_temp.$file_temp,'F');  
                              $preview_url = "tmp/".$file_temp;
                              $preview_path = $path_temp.$file_temp;
                          }else{
                            $mpdf->Output($file_name,$type);
                          }
                            
                        }else if($type=="HTML"){
                            $html = "";
                        
                            $content_pdf .=  '<div>
                                <iframe style="width:100%;height:100%;border:3px solid #000000;" sandbox srcdoc="<body>'.htmlentities($html).'</body>"></iframe>
                            </div>';
                        }
                        
                        $response = "S";
                 }else{
                     $message = "ไม่มี Template PDF";
                 }
            }else{
                 $message = "ไม่พบข้อมูล";
            }
        }else{
            $message = "กรุณาระบุรหัส";
        }
        
        return array('response' =>$response,    'message' => $message,"content"=>$content_pdf,"preview_url"=>$preview_url,"preview_path"=>$preview_path);
    }
}


if (!function_exists('__pdf_taxcer_payment')) {
    function __pdf_taxcer_payment($datalist=array(), $type="I"){
        $response = "F";
        $message = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";
        $content_pdf= "";
        $preview_url = "";
        $preview_path = "";
        
        if(!empty($datalist)){
            $path = realpath(dirname(__FILE__). '/../');
            $db = new database();
            
            if(!empty($datalist)){
                include $path.'/variable_taxcer_payment.php';

                
                $template_pdf = $path."/template_pdf/taxcer_payment.pdf";
                $template_pdf = $template_pdf;
                
                if(file_exists($template_pdf)){
                    $file_name  = "หนังสือรับรองการหักภาษี ณ ที่จ่าย-".$taxc_no.".pdf";
                    
                    //template
                    $mpdf_template = __pdf_setup();
                    $template_pagecount = $mpdf_template->setSourceFile($template_pdf);
                    $template_import_page = $mpdf_template->importPage($template_pagecount);
                    $template_size = $mpdf_template->getTemplateSize($template_import_page);
                    $template_size_width = $template_size["width"];
                    $template_size_height= $template_size["height"];
                    
                    $count_page_copy = 0;//1
                    $array_setup_page = 0;//2
                    $array_setup_titlepage = 0;//3
                     
                    $html_header = "";//4
                    $html_body = "";//5

                    $path = realpath(dirname(__FILE__). '/../');

                    //7
                    $html_style =  '<style>
                                        body {
                                            font-family: "sarabun";
                                            font-size: 12pt;
                                            line-height: 1;
                                        }
                                        table {
                                            overflow: wrap;
                                            width: 100%;
                                        }
                                        .set-text {
                                            font-size: 16px
                                        }
                                        
                                       .box-pdf{
                                            font-weight:normal;
                                            position: absolute;
                                            display:block;
                                            color:#000000;
                                        }
                                    </style>'; 


                    $count_page_copy = 3;//1
                    $array_setup_page = array("A4", 3, 3, 3, 3, 3, 3, 3);//2
                    $array_setup_titlepage = array(0, 0, 0, 14, "left", "");//3
                           

          
                    if($type=="I" || $type=="D" || $type=="P"){
                        $mpdf =  __pdf_setup($array_setup_page);
                        $mpdf->SetDocTemplate($template_pdf,true);
                        $mpdf->useFixedNormalLineHeight = true;
                        $mpdf->packTableData = true;
                        $mpdf->keep_table_proportions  = true;
                        $mpdf->shrink_tables_to_fit=0;
                        
                        if($type=="P"){
                            $mpdf->SetWatermarkText('Preview');
                            $mpdf->watermarkTextAlpha = 0.1;
                            $mpdf->showWatermarkText = true;
                        }

                        //Set Header
                        $html_body = '
                            <table class="table" cellspacing="0" style="margin-bottom: 0px">
                                <tr>
                                    <td width="55%"></td>
                                    <td width="45%" style="text-align: right;"></td>
                                </tr>
                            </table>

                            <section>
                                <p style="margin:33px 0px 0px 670px;">'.$taxc_no.'</p>
                                <p style="margin:43px 0px 0px 670px">'.$comp_taxno.'</p>
                                <p style="margin:4px 0px 0px 60px">'.$comp_name.'</p>
                                <p style="margin:4px 0px 0px 60px">'.$comp_address.'</p>
                            </section>

                            <section>
                                <p style="margin:18px 0px 0px 670px">'.$twh_taxno.'</p>
                                <p style="margin:4px 0px 0px 60px">'.$twh_name.'</p>
                                <p style="margin:4px 0px 0px 60px">'.$twh_address.'</p>
                            </section>
                        ';

                    if($taxc_tfid == 'TF001') { 
                        $html_body .= '<img src="'.$path.'/image/checkbox.jpg" style="margin:15px 0px 0px 266px;">';
                    }
                    else if($taxc_tfid == 'TF002') { 
                        $html_body .= '<img src="'.$path.'/image/checkbox.jpg" style="margin:15px 0px 0px 381px;">';
                    } 
                    else if($taxc_tfid == 'TF003') { 
                        $html_body .= '<img src="'.$path.'/image/checkbox.jpg" style="margin:15px 0px 0px 523px;">';
                    } 
                    else if($taxc_tfid == 'TF004') { 
                        $html_body .= '<img src="'.$path.'/image/checkbox.jpg" style="margin:15px 0px 0px 638px;">';
                    } 
                    else if($taxc_tfid == 'TF005') { 
                        $html_body .= '<img src="'.$path.'/image/checkbox.jpg" style="margin:40px 0px 0px 265px;">';
                    } 
                    else if($taxc_tfid == 'TF006') { 
                        $html_body .= '<img src="'.$path.'/image/checkbox.jpg" style="margin:40px 0px 0px 381px;">';
                    } 
                    else if($taxc_tfid == 'TF007') { 
                        $html_body .= '<img src="'.$path.'/image/checkbox.jpg" style="margin:40px 0px 0px 523px;">';
                    } 
         

                    
                    $body_fontsize = 16;
                    $body_top = 715;
                    
                    $body_width = 109; 
                    
                    $body_left1 = 419;
                    $body_left2 = ($body_left1+$body_width+7);
                    $body_left3 = ($body_left2+$body_width+7);
                    

                    if($invtax3 != '0.00') {
                        $html_body .= __pdf_box(__price($invtax3),array($body_top, $body_left2,$body_width, $body_fontsize,"right",null));
                    }
                    if($invtaxtotal3 != '0.00') {
                        $html_body .= __pdf_box(__price($invtaxtotal3),array($body_top, $body_left3,$body_width, $body_fontsize,"right",null));
                    }
                    

                    $body_top += 24;
                    if($invtax2 != '0.00') {
                        $html_body .= __pdf_box(__price($invtax2),array($body_top, $body_left2,$body_width, $body_fontsize,"right",null));
                    }
                    if($invtaxtotal2 != '0.00') { 
                        $html_body .= __pdf_box(__price($invtaxtotal2),array($body_top, $body_left3,$body_width, $body_fontsize,"right",null));
                    }


                    $body_top += 24;
                    $html_body .= __pdf_box(__date($taxc_date,"slash"),array($body_top, $body_left1,$body_width, $body_fontsize,"center",null));
                    if($invtax1 != '0.00') {
                        $html_body .= __pdf_box(__price($invtax1),array($body_top, $body_left2,$body_width, $body_fontsize,"right",null));
                    }
                    if($invtaxtotal1 != '0.00') { 
                        $html_body .= __pdf_box(__price($invtaxtotal1),array($body_top, $body_left3,$body_width, $body_fontsize,"right",null));
                    }
        
                    $body_top += 20;
                    $html_body .= __pdf_box($taxc_income,array($body_top, 49,$body_width, $body_fontsize,"left",null));
                    
                    $body_top += 44;
                    $html_body .= __pdf_box(__price($invtax),array($body_top, $body_left2,$body_width, $body_fontsize,"right",null));
                    $html_body .= __pdf_box(__price($taxtotal),array($body_top, $body_left3,$body_width, $body_fontsize,"right",null));
                    
                    $body_top += 24;
                    $html_body .= __pdf_box(__bahtthai($taxtotal),array($body_top, $body_left1,346, $body_fontsize,"center",null));

                    $icon_checkbox = '<img src="'.$path.'/image/checkbox.jpg">' ;
                    $checkbox_top = 907;
                    $checkbox_left = 187;
                    $checkbox_width = 112;
                    $html_body .= ($taxc_tpid == 'TP001') ?  __pdf_box($icon_checkbox,array($checkbox_top, $checkbox_left,$checkbox_width, 0,"left",null)): '';
                    
                    $checkbox_left += $checkbox_width;
                    $checkbox_width = 120;
                    $html_body .= ($taxc_tpid == 'TP002') ?  __pdf_box($icon_checkbox,array($checkbox_top, $checkbox_left,$checkbox_width, 0,"left",null)): '';
                    
                    $checkbox_left += $checkbox_width;
                    $checkbox_width = 137;
                    $html_body .= ($taxc_tpid == 'TP003') ?  __pdf_box($icon_checkbox,array($checkbox_top, $checkbox_left,$checkbox_width, 0,"left",null)): '';
                    
                    $checkbox_left += $checkbox_width;
                     $checkbox_width = 119;
                    $html_body .= ($taxc_tpid == 'TP004') ?  __pdf_box($icon_checkbox,array($checkbox_top, $checkbox_left,$checkbox_width, 0,"left",null)): '';
                    
                    
                    $html_body .= __pdf_box(__date($taxc_date,"full"),array(1013, 290,220, 16,"center",null));
                    
                    
                    $html_header = '
                        <table class="table table-bordered" cellspacing="0" style="margin-bottom: 0px">
                            <tr>
                                <td width="55%">
                                    <b>
                                        ฉบับที่ 1 (สำหรับผู้ถูกหักภาษี ณ ที่จ่าย ใช้แนบพร้อมกับแบบแสดงรายการภาษี)
                                    </b>
                                </td>
                                <td width="45%" style="text-align: right;">
                                    <b>ต้นฉบับ</b>
                                </td>
                            </tr>
                        </table>';
                    
                    $mpdf->SetHTMLHeader($html_header);
                    $mpdf->AddPage();
                    $mpdf->WriteHTML($html_style.$html_body);
                         
                        if($count_page_copy>=1){
                            for ($i=1; $i <=$count_page_copy ; $i++) {
                                if($i == 1) {
                                    $html_header = '
                                        <table class="table table-bordered" cellspacing="0" style="margin-bottom: 0px">
                                            <tr>
                                                <td width="55%">
                                                    <b>
                                                        ฉบับที่ 2 (สำหรับผู้ถูกหักภาษี ณ ที่จ่าย เก็บไว้เป็นหลักฐาน)
                                                    </b>
                                                </td>
                                                <td width="45%" style="text-align: right;">
                                                    <b>สำเนา</b>
                                                </td>
                                            </tr>
                                        </table>
                                    ';   
                                }
                                else if($i == 2) {
                                    $html_header = '
                                        <table class="table table-bordered" cellspacing="0" style="margin-bottom: 0px">
                                            <tr>
                                                <td width="55%">
                                                    <b>
                                                        ฉบับที่ 3 (สำหรับผู้หักภาษี ณ ที่จ่าย ใช้แนบแสดงรายการภาษี)
                                                    </b>
                                                </td>
                                                <td width="45%" style="text-align: right;">
                                                    <b>สำเนา</b>
                                                </td>
                                            </tr>
                                        </table>
                                    ';
                                    
                                }
                                else {
                                    $html_header = '
                                        <table class="table table-bordered" cellspacing="0" style="margin-bottom: 0px">
                                            <tr>
                                                <td width="55%">
                                                    <b>
                                                        ฉบับที่ 4 (สำเนาติดเล่ม สำหรับผู้หักภาษี ณ ที่จ่าย เก็บไว้เป็นหลักฐาน)
                                                    </b>
                                                </td>
                                                <td width="45%" style="text-align: right;">
                                                    <b>สำเนา</b>
                                                </td>
                                            </tr>
                                        </table>
                                    ';
                                }
                                $mpdf->SetHTMLHeader($html_header);
                                $mpdf->AddPage();
                                $mpdf->WriteHTML($html_style.$html_body);

                            }
                         }
                         
                          if($type=="P"){
                              $path_temp = $path."/tmp/";
                              if (!file_exists($path_temp)) {
                                    mkdir($path_temp, 0777, true);
                                }
                              
                              $file_temp = "taxcer_preview".__encode(tempnam(sys_get_temp_dir(), 'Pre_')).".pdf";
                              
                              $mpdf->Output($path_temp.$file_temp,'F');  
                              $preview_url = "tmp/".$file_temp;
                              $preview_path = $path_temp.$file_temp;
                          }else{
                            $mpdf->Output($file_name,$type);
                          }
                            
                    }else if($type=="HTML"){
                        $content_pdf .=  '<div>
                            <iframe style="width:100%;height:100%;border:3px solid #000000;" sandbox srcdoc="<body>'.htmlentities($html).'</body>"></iframe>
                        </div>';
                    }
                    
                    $response = "S";
                }else{
                    $message = "ไม่มี Template PDF";
                }
            }else{
                 $message = "ไม่พบข้อมูล";
            }
        }else{
            $message = "กรุณาระบุรหัส";
        }
        
        return array('response' =>$response,    'message' => $message,"content"=>$content_pdf,"preview_url"=>$preview_url,"preview_path"=>$preview_path);
    }
}

if (!function_exists('__pdf_cheque_revenue')) {
    function __pdf_cheque_revenue($datalist=array(),$type="I"){
        $response = "F";
        $message = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";
        $content_pdf= "";
        $preview_url = "";
        $preview_path = "";
        
        if(!empty($datalist)){
            $path = realpath(dirname(__FILE__). '/../');
            $db = new database();
            
            if(!empty($datalist)){
                include $path.'/variable_cheque.php';
                
                    $file_name  = "cheque-". $cheque_no .".pdf";
                    
                    $count_page_copy = 0;//1
                    $array_setup_page = 0;//2
                    $array_setup_titlepage = 0;//3
                    $html_body = "";
                    
                    $html_style = '	<style>
                                		body {
                                			font-family: "sarabun";
                                		}
                                		table {
                                			overflow: wrap;
                                			font-size: 16pt;
                                			border-collapse: collapse;
                                			width: 100%;
                                		}
                                	</style>'; 
                    
                    switch($bank_nameShort){
                        case "KBANK" :
                            $array_setup_page = array([190.8, 88.9],5,5,2,2,5,5);
                            $html_body .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    		<tr>
                                    			<td height="31px" width="115px"></td>
                                    			<td height="31px"></td>
                                    			<td height="31px"></td>
                                    		</tr>
                                    		<tr>
                                    			<td></td>
                                    			<td width="555px">'.$paya_name.'</td>
                                    			<td style="font-size: 28pt;"><b>//</b></td>
                                    		</tr>
                                    		<tr>
                                    			<td></td>
                                    			<td style="margin-bottom: 1px;">( '.$text_bath.' )</td>
                                    			<td></td>
                                    		</tr>
                                    	</table>
                                    	<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    		<tr>
                                    			<td height="5px"></td>
                                    			<td height="5px"></td>
                                    			<td height="5px"></td>
                                    		</tr>
                                    	</table>
                                    	<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    		<tr>
                                    			<td width="465px"></td>
                                    			<td><b>- '.$cheque_invnet.' -</b></td>
                                    		</tr>
                                    	</table>';
                            break;
                            
                        case "BBL" :
                            $array_setup_page = array([190.8, 88.9],5,5,2,2,5,5);
                            $html_body .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    		<tr>
                                    			<td height="32px" width="90px"></td>
                                    			<td height="32px"></td>
                                    			<td height="32px"></td>
                                    		</tr>
                                    		<tr>
                                    			<td></td>
                                    			<td width="580px">'.$paya_name.'</td>
                                    			<td style="font-size: 28pt;"><b>//</b></td>
                                    		</tr>
                                    		<tr>
                                    			<td></td>
                                    			<td style="margin-bottom: 1px;">( '.$text_bath.' )</td>
                                    			<td></td>
                                    		</tr>
                                    	</table>
                                    	<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    		<tr>
                                    			<td height="5px"></td>
                                    			<td height="5px"></td>
                                    			<td height="5px"></td>
                                    		</tr>
                                    	</table>
                                    	<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    		<tr>
                                    			<td width="440px"></td>
                                    			<td><b>- '.$cheque_invnet.' -</b></td>
                                    		</tr>
                                    	</table>';                            
                            break;
                            
                        case "BMA" :
                            $array_setup_page = array([177.8, 88.9],5,5,2,2,5,5);
                            $html_body .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    		<tr>
                                    			<td height="1px" width="122px"></td>
                                    			<td></td>
                                    			<td></td>
                                    		</tr>
                                    		<tr>
                                    			<td height="34px"></td>
                                    			<td></td>
                                    			<td></td>
                                    		</tr>
                                    		<tr>
                                    			<td></td>
                                    			<td width="490px">'.$paya_name.'</td>
                                    			<td style="font-size: 28pt;"><b>//</b></td>
                                    		</tr>
                                    		<tr>
                                    			<td height="1px"></td>
                                    			<td></td>
                                    			<td></td>
                                    		</tr>
                                    		<tr>
                                    			<td></td>
                                    			<td>( '.$text_bath.' )</td>
                                    			<td></td>
                                    		</tr>
                                    	</table>
                                    	<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    		<tr>
                                    			<td height="10px" width="130px"></td>
                                    			<td></td>
                                    			<td></td>
                                    		</tr>
                                    	</table>
                                    	<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    		<tr>
                                    			<td width="430px"></td>
                                    			<td><b>- '.$cheque_invnet.' -</b></td>
                                    		</tr>
                                    	</table>';                            
                            break;
                            
                        case "SCB" :
                            $array_setup_page = array([177.8, 88.9],5,5,2,2,5,5);
                            $html_body .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    		<tr>
                                    			<td height="40px" width="70px"></td>
                                    			<td></td>
                                    			<td></td>
                                    		</tr>
                                    		<tr>
                                    			<td></td>
                                    			<td width="590px">'.$paya_name.'</td>
                                    			<td></td>
                                    		</tr>
                                    	</table>
                                    
                                    	<table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top: -35px;">
                                    		<tr>
                                    			<td></td>
                                    			<td width="590px"></td>
                                    			<td style="font-size: 28pt;"><b>//</b></td>
                                    		</tr>
                                    	</table>
                                    	<table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top: -5px;">
                                    		<tr>
                                    			<td width="150px"></td>
                                    			<td width="540px">( '.$text_bath.' )</td>
                                    			<td></td>
                                    		</tr>
                                    	</table>
                                    	<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    		<tr>
                                    			<td height="10px" width="130px"></td>
                                    			<td></td>
                                    			<td></td>
                                    		</tr>
                                    	</table>
                                    	<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    		<tr>
                                    			<td width="430px"></td>
                                    			<td><b>- '.$cheque_invnet.' -</b></td>
                                    		</tr>
                                    	</table>';                            
                            break;
                            
                        default:
                            $array_setup_page = array([177.8, 88.9],5,5,2,2,5,5);
                            $html_body .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    		<tr>
                                    			<td height="1px" width="100px"></td>
                                    			<td></td>
                                    			<td></td>
                                    		</tr>
                                    		<tr>
                                    			<td height="30px" width="100px"></td>
                                    			<td></td>
                                    			<td></td>
                                    		<tr>
                                    		</tr>
                                    			<td></td>
                                    			<td width="515px">'.$paya_name.'</td>
                                    			<td style="font-size: 28pt;"><b>//</b></td>
                                    		</tr>
                                    		<tr>
                                    			<td height="2px" width="120px"></td>
                                    			<td></td>
                                    			<td></td>
                                    		</tr>
                                    		<tr>
                                    			<td></td>
                                    			<td>( '.$text_bath.' )</td>
                                    			<td></td>
                                    		</tr>
                                    	</table>
                                    	<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    		<tr>
                                    			<td height="10px" width="130px"></td>
                                    			<td></td>
                                    			<td></td>
                                    		</tr>
                                    	</table>
                                    	<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    		<tr>
                                    			<td width="430px"></td>
                                    			<td><b>- '.$cheque_invnet.' -</b></td>
                                    		</tr>
                                    	</table>';                            
                            break;
                    }

                    if($type=="I" || $type=="D" || $type=="P"){
                        $mpdf =  __pdf_setup($array_setup_page);
                        $mpdf->useFixedNormalLineHeight = true;
                        $mpdf->packTableData = true;
                        $mpdf->keep_table_proportions  = true;
                        $mpdf->shrink_tables_to_fit=0;
                        
                        if($type=="P"){
                            $mpdf->SetWatermarkText('Preview');
                            $mpdf->watermarkTextAlpha = 0.1;
                            $mpdf->showWatermarkText = true;
                        }

                        $mpdf->AddPage();
                        $mpdf->WriteHTML($html_style.$html_body);
                         
                          if($type=="P"){
                              $path_temp = $path."/tmp/";
                              if (!file_exists($path_temp)) {
                                    mkdir($path_temp, 0777, true);
                                }
                              
                              $file_temp = "receipt_add".__encode(tempnam(sys_get_temp_dir(), 'Pre_')).".pdf";

                              $mpdf->Output($path_temp.$file_temp,'F');  
                              $preview_url = "tmp/".$file_temp;
                              $preview_path = $path_temp.$file_temp;
                          }else{
                            $mpdf->Output($file_name,$type);
                          }
                            
                    }else if($type=="HTML"){
                        $content_pdf .=  '<div>
                            <iframe style="width:100%;height:100%;border:3px solid #000000;" sandbox srcdoc="<body>'.htmlentities($html).'</body>"></iframe>
                        </div>';
                    }
                    
                    $response = "S";

            }else{
                 $message = "ไม่พบข้อมูล";
            }
        }else{
            $message = "กรุณาระบุรหัส";
        }
        
        return array('response' =>$response,    'message' => $message,"content"=>$content_pdf,"preview_url"=>$preview_url,"preview_path"=>$preview_path);
    }
}


if (!function_exists('__pdf_cleartmp')) {
     function __pdf_cleartmp(){
       $path = realpath(dirname(__FILE__). '/../');
        $files = glob($path.'/tmp/*');
        
        $date_unlink = strtotime("-5 minutes");
        foreach($files as $filename){
          if(is_file($filename)) {
              $file_date = filemtime($filename);
              
              if($file_date <= $date_unlink){
                  @unlink($filename);
              }
          }
        }
     }  
}

?>