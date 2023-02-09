<?php
if (!function_exists('__check_login')) {
    function __check_login(){
        if(empty($_SESSION["user_name"])) { 
            header("location: login.php");
            exit;
        }    
    }
}
if (!function_exists('__check_index')) {
    function __check_index(){
         if(!empty($_SESSION["user_name"])) { 
            header("location: index.php");
            exit;
        }    
    }
}
if (!function_exists('__sort_array_keepkey')) {
    function __sort_array_keepkey($array=array(),$sort="ASC") {
        
        $array_new = array();
        if(!empty($array)){
            if($sort=="DESC"){
                 $array_new = $array;
                  uasort($array_new, function($a, $b) {
                     if ($a == $b) {        
                        return 0;
                    } else{
                         return ($a > $b) ? -1 : 1; 
                    }  
                });
            }else{
                  $array_new = $array;
                  uasort($array_new, function($a, $b) {
                     if ($a == $b) {        
                        return 0;
                    }else{
                       return ($a < $b) ? -1 : 1; 
                    }
                });
            }
        }
        
        return $array_new;  
    }  
}
//purchasereq
if (!function_exists('__box_style')) {
    function __box_style($page="",$class_box="",$name="",$icon="",$count=0,$style=""){
        $html = "";
        $page = ($page!="") ? $page : "javascript:void(0)" ;
        $class_box = ($class_box!="") ? $class_box : "default" ;
        $icon  = ($icon!="") ? $icon : '<i class="icofont-paper"></i>' ;
        
        $html .= '<div class="col-lg-3 col-md-6 col-sm-12">';
            $html .= '<a href="'.$page.'">';
                $html .= '<div class="card-counter custom '.$class_box.'" style="'.$style.'">';
                    $html .= '<span class="count-title">'.$name.'</span>';
                    $html .= '<div class="count-box1"> ';
                        $html .= '<span class="count-icon">'.$icon.'</span>';
                        $html .= '<span class="count-number">'.$count.'</span>';
                        $html .= '<span class="count-sub">รายการ</span>';
                    $html .= '</div>';
                $html .= '</div>';
            $html .= '</a>';
        $html .= '</div>';
        
        
        return $html;
    }
}
if (!function_exists('__html_dep_box')) {
    function __html_dep_box($title="",$page="",$icon="",$con_where="",$arrDep=array(),$db_table_name="",$db_column_key="",$distinct=""){
        $db = new database();   
        $paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
        
        $html = "";
        $html_dep = "";
        if(count($arrDep)>=1){
            foreach ($arrDep as $keyDep => $valueDep) {
                $dep_id = $valueDep["dep_id"];
                $dep_name = $valueDep["dep_name"];
                $dep_name_th = $valueDep["dep_name_th"];
                $dep_name_new = ($dep_name_th!="") ? $dep_name." - ".$dep_name_th : "ฝ่าย ".$dep_name;
                
                if (strpos($page, "?") !== false) {
                    $dep_page = $page."&cid=".$paramurl_company_id."&dep=".$dep_id;
                }else{
                    $dep_page = $page."?cid=".$paramurl_company_id."&dep=".$dep_id;
                }
                
                $dep_color = $valueDep["dep_color"];
                $dep_style = "border:4px solid".$dep_color."!important;background-color:#FFFFFF;color:".$dep_color;
                
                if($con_where!=""){
                    $sql_count = " SELECT COUNT(";
                    $sql_count .= ($distinct=="") ? $db_column_key : " DISTINCT(".$distinct.") ";
                    $sql_count .= ") AS count FROM ".$db_table_name." WHERE ".$db_column_key." = '".$keyDep."'";
                    $sql_count .= $con_where;
                    $row_count= $db->query($sql_count)->row();
                    $count = $row_count["count"];
                }else{
                    $count = 0;
                }
                    
                            
                $html_dep .= __box_style($dep_page,"",$dep_name_new,$icon,$count,$dep_style);
            }   
            
                
            $html .= '<div class="row py-4 px-1" style="background-color: #E9ECEF">';
                $html .= '<div class="col-md-12 mr-auto">';
                    $html .= '<h3 class="mb-0">'.$title.'</h3>';
                $html .= '</div>';
            $html .= '</div>';
            
             $html .= '<div class="row py-4 px-1" style="background-color: #FFFFFF">';
                $html .=  $html_dep;
              $html .= '</div>';
          $html .= '</div>';
          
        }   
        
        return $html;
    
    }
}
if (!function_exists('__html_dep_box2')) {
    function __html_dep_box2($title="",$page=""){
        $db = new database();   
        $user_id = __session_user("id");
        $paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
        $arrDep = __authority_department_list($user_id,$paramurl_company_id);
        
        $html = "";
        $html_dep = "";
        if(count($arrDep)>=1){
            foreach ($arrDep as $keyDep => $valueDep) {
                $dep_id = $valueDep["dep_id"];
                $dep_name = $valueDep["dep_name"];
                $dep_name_th = $valueDep["dep_name_th"];
                $dep_name_new = ($dep_name_th!="") ? $dep_name." - ".$dep_name_th : "ฝ่าย ".$dep_name;
                
                if (strpos($page, "?") !== false) {
                    $dep_page = $page."&cid=".$paramurl_company_id."&dep=".$dep_id;
                }else{
                    $dep_page = $page."?cid=".$paramurl_company_id."&dep=".$dep_id;
                }
                
                $dep_color = $valueDep["dep_color"];
                $dep_style = "border:4px solid ".$dep_color."!important;background-color:#FFFFFF;color:".$dep_color;
                
                
                $html_dep .= '<div class="col-lg-3 col-md-6 col-sm-12">';
                    $html_dep .= '<a href="'.$dep_page.'">';
                        $html_dep .= '<div class="card-counter card-counter-curve custom default" style="'.$dep_style.'">';
                            $html_dep .= '<span class="count-title">'.$dep_name_new.'</span>';
                        $html_dep .= '<div class="count-box-curve">';
                        
                        
                        $html_dep .= '<svg width="100%" height="100px" viewBox="0 0 1400 130">';
                                $html_dep .= ' <defs>
                                        <linearGradient id="grad'.$keyDep.'"  x1="30%"   y2="100%">
                                          <stop offset="0%" stop-color="'.$dep_color.'"  style="stop-color:'.$dep_color.';stop-opacity:0.4" />
                                          <stop offset="95%" style="stop-color:#fff;stop-opacity:0.2" />
                                        </linearGradient>
                                      </defs>';
                                      
                                  $html_dep .= '<polyline  stroke-width="10"  fill="url('."'".'#grad'.$keyDep."'".')" 
                                            points="150,500
                                            150,375 150,325 250,325 250,375
                                            350,375 350,250 450,250 450,375
                                            550,375 550,175 650,175 650,375
                                            750,375 750,100 850,100 850,375
                                            950,375 950,25 1050,25 1050,375
                                            1150,375 1150,5 1250,5 1250,375
                                            1450,375 1450,1450 " />';
                                                                            
                            $html_dep .= '</svg>';
                            $html_dep .= '</div>';
                        $html_dep .= '</div>';
                    $html_dep .= '</a>';
                $html_dep .= '</div>';
            
            }   
            
            $html .= '<div class="row py-4 px-1" style="background-color: #E9ECEF">';
                $html .= '<div class="col-md-12 mr-auto">';
                    $html .= '<h3 class="mb-0">'.$title.'</h3>';
                $html .= '</div>';
            $html .= '</div>';
            
             $html .= '<div class="row py-4 px-1" style="background-color: #FFFFFF">';
                $html .=  $html_dep;
              $html .= '</div>';
          $html .= '</div>';
          
        }   
        
        return $html;
    }
}
//number
if (!function_exists('__number')) {                 
    function __number($text="") {
        return number_format($text, 0, "", ",");
    }
}
if (!function_exists('__price')) {
    function __price($number="") {
        if(isset($number)){
            if($number==0){
                return "0.00";
            }else{
                return number_format($number, 2, ".", ",");
            }
        }
    }
}
if (!function_exists('__date')) {                   
    function __date($str = "", $type = "") {
        if ($str == "0000-00-00" || $str == "") {
            return "";
        } else {
            $time = strtotime($str);
             if ($type == "full") {
                $thai_month_arr = array(
                    "0" => "",
                    "1" => "มกราคม",
                    "2" => "กุมภาพันธ์",
                    "3" => "มีนาคม",
                    "4" => "เมษายน",
                    "5" => "พฤษภาคม",
                    "6" => "มิถุนายน",
                    "7" => "กรกฎาคม",
                    "8" => "สิงหาคม",
                    "9" => "กันยายน",
                    "10" => "ตุลาคม",
                    "11" => "พฤศจิกายน",
                    "12" => "ธันวาคม"
                );
                $date_return = "" . date("j", $time);
                $date_return .= " " . $thai_month_arr[date("n", $time)];
                $date_return .= " " . (date("Y", $time) + 543);
                
             }else if ($type == "slash") {
                $date_return = "" . date("j", $time);
                $date_return .= "/" . date("m", $time);
                $date_return .= "/" . (date("Y", $time) + 543);
                
              }else if ($type == "slash_short") {
                $date_return = "" . date("j", $time);
                $date_return .= "/" . date("m", $time);
                $date_return .= "/" . substr(date("Y",$time)+543,-2);
                
             }else{
                $thai_month_arr = array(
                    "0" => "",
                    "1" => "ม.ค.",
                    "2" => "ก.พ.",
                    "3" => "มี.ค.",
                    "4" => "เม.ย.",
                    "5" => "พ.ค.",
                    "6" => "มิ.ย.",
                    "7" => "ก.ค.",
                    "8" => "ส.ค.",
                    "9" => "ก.ย.",
                    "10" => "ต.ค.",
                    "11" => "พ.ย.",
                    "12" => "ธ.ค."
                );
                $date_return = "" . date("j", $time);
                $date_return .= " " . $thai_month_arr[date("n", $time)];
                $date_return .= " " . substr(date("Y",$time)+543,-2);
             }
            
            
            return $date_return;
        }
    }
}


if (!function_exists('__datetime')) {                   
    function __datetime($str="", $lang = "th") {
        if ($str == "0000-00-00 00:00:00" || $str == "") {
            return "";
        } else {
            $time = strtotime($str);
            if ($lang == "en") {
                $date_return = date("j F Y, H:i:s", $time);
            } else if ($lang == "th") {
                $thai_month_arr = array(
                    "0" => "",
                    "1" => "ม.ค.",
                    "2" => "ก.พ.",
                    "3" => "มี.ค.",
                    "4" => "เม.ย.",
                    "5" => "พ.ค.",
                    "6" => "มิ.ย.",
                    "7" => "ก.ค.",
                    "8" => "ส.ค.",
                    "9" => "ก.ย.",
                    "10" => "ต.ค.",
                    "11" => "พ.ย.",
                    "12" => "ธ.ค."
                );
                $date_return = "" . date("j", $time);
                $date_return .= " " . $thai_month_arr[date("n", $time)];
                $date_return .= " " . (date("Y", $time) + 543);
                $date_return .= " " . date("H:i:s", $time) . "";
            } else if ($lang == "th_full") {
                $thai_month_arr = array(
                    "0" => "",
                    "1" => "มกราคม",
                    "2" => "กุมภาพันธ์",
                    "3" => "มีนาคม",
                    "4" => "เมษายน",
                    "5" => "พฤษภาคม",
                    "6" => "มิถุนายน",
                    "7" => "กรกฎาคม",
                    "8" => "สิงหาคม",
                    "9" => "กันยายน",
                    "10" => "ตุลาคม",
                    "11" => "พฤศจิกายน",
                    "12" => "ธันวาคม"
                );
                $date_return = "" . date("j", $time);
                $date_return .= " " . $thai_month_arr[date("n", $time)];
                $date_return .= " " . (date("Y", $time) + 543);
                $date_return .= " " . date("H:i:s", $time) . "";
            }
            return $date_return;
        }
    }
}

if (!function_exists('__date_nomonth')) {                   
    function __date_nomonth($str = "", $type = "") {
        if ($str == "0000-00-00" || $str == "") {
            return "";
        } else {
            $time = strtotime($str);
             if ($type == "full") {
                $thai_month_arr = array(
                    "0" => "",
                    "1" => "มกราคม",
                    "2" => "กุมภาพันธ์",
                    "3" => "มีนาคม",
                    "4" => "เมษายน",
                    "5" => "พฤษภาคม",
                    "6" => "มิถุนายน",
                    "7" => "กรกฎาคม",
                    "8" => "สิงหาคม",
                    "9" => "กันยายน",
                    "10" => "ตุลาคม",
                    "11" => "พฤศจิกายน",
                    "12" => "ธันวาคม"
                );
                $date_return = $thai_month_arr[date("n", $time)];
                $date_return .= " " . (date("Y", $time) + 543);
                
             }else if ($type == "slash") {
                $date_return = date("m", $time);
                $date_return .= "/" . (date("Y", $time) + 543);
                
              }else if ($type == "slash_short") {
                $date_return = date("m", $time);
                $date_return .= "/" . substr(date("Y",$time)+543,-2);
                
             }else{
                $thai_month_arr = array(
                    "0" => "",
                    "1" => "ม.ค.",
                    "2" => "ก.พ.",
                    "3" => "มี.ค.",
                    "4" => "เม.ย.",
                    "5" => "พ.ค.",
                    "6" => "มิ.ย.",
                    "7" => "ก.ค.",
                    "8" => "ส.ค.",
                    "9" => "ก.ย.",
                    "10" => "ต.ค.",
                    "11" => "พ.ย.",
                    "12" => "ธ.ค."
                );
                $date_return =  $thai_month_arr[date("n", $time)];
                $date_return .= " " . substr(date("Y",$time)+543,-2);
             }
            
            
            return $date_return;
        }
    }
}


if (!function_exists('__validdate')) {
    function __validdate($date="") {
        return date('Y-m-d', strtotime($date)) === $date;
    }
}
        
if (!function_exists('__bahtthai')) {
    function __bahtthai($amount="") {
        list($integer, $fraction) = explode('.', number_format(abs($amount), 2, '.', ''));
    
        $baht = __bahtthai_cv($integer);
        $satang = __bahtthai_cv($fraction);
    
        $output = $amount < 0 ? 'ลบ' : '';
        $output .= $baht ? $baht.'บาท' : '';
        $output .= $satang ? $satang.'สตางค์' : 'ถ้วน';
    
        return $baht.$satang === '' ? 'ศูนย์บาทถ้วน' : $output;
    }
}
if (!function_exists('__bahtthai_cv')) {
    function __bahtthai_cv($number=0){
        $values =array('', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า');
        $places = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');
        $exceptions = array('หนึ่งสิบ' => 'สิบ', 'สองสิบ' => 'ยี่สิบ', 'สิบหนึ่ง' => 'สิบเอ็ด');
        $output = '';
        foreach (str_split(strrev($number)) as $place => $value) {
            if ($place % 6 === 0 && $place > 0) {
                $output = $places[6].$output;
            }
            if ($value !== '0') {
                $output = $values[$value].$places[$place % 6].$output;
            }
        }
        foreach ($exceptions as $search => $replace) {
            $output = str_replace($search, $replace, $output);
        }
        return $output;
    }
}
if (!function_exists('__pagination')) {
    function __pagination($total_row=0,$limit_row=10,$pagenumber=1,$arrData=array(),$function="loadPage") {
        $total_links = ceil($total_row/$limit_row);
        $previous_link = '';
        $next_link = '';
        $page_link = '';
        $page_array = array();
        
        $data = "";
        if(count($arrData)>=1){
            $data = implode('\',\'',$arrData);
        }
        if($data!=""){
            $data = ", '".$data."'";
        }
    
        if($total_links > 5) {
            if($pagenumber < 5) {
                for($count = 1; $count <= 5; $count++) {
                    $page_array[] = $count;
                }
                
                $page_array[] = '...';
                $page_array[] = $total_links;
            }else{
                $end_limit = $total_links - 5;
                if($pagenumber > $end_limit) {
                    $page_array[] = 1;
                    $page_array[] = '...';
    
                    for($count = $end_limit; $count <= $total_links; $count++) {
                        $page_array[] = $count;
                    }
    
                } else {
                    $page_array[] = 1;
                    $page_array[] = '...';
    
                    for($count = $pagenumber - 1; $count <= $pagenumber + 1; $count++) {
                        $page_array[] = $count;
                    }
    
                    $page_array[] = '...';
                    $page_array[] = $total_links;
    
                }
            }
        }else{
            for($count = 1; $count <= $total_links; $count++) {
                $page_array[] = $count;
            }
        }
        
        
        for($count = 0; $count < count($page_array); $count++) {
            if($pagenumber == $page_array[$count]) {
                $onclick = '    ';
                $disabled_link = ' disabled ';
                
            
                $page_link .= '<li class="page-item active '.$disabled_link.'">
                    <a class="page-link " '.$onclick.' href="javascript:void(0);">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
                </li> ';
                            
                $previous_id = $page_array[$count] - 1;
                
                if($previous_id > 0) {
                    $onclick = '    onclick="'.$function.'(\'' . 0 . '\'' .$data.')"';
                    
                    $previous_link = '<li class="page-item">
                                        <a class="page-link" '.$onclick.'  href="javascript:void(0)" data-page_number="'.$previous_id.'">
                                            <i class="icofont-rounded-left"></i>
                                        </a>
                                    </li>';
                }else{
                    $previous_link = '<li class="page-item disabled">
                                        <a class="page-link" href="#">
                                            <i class="icofont-rounded-left"></i>
                                        </a>
                                    </li>';
                }
                
                $next_id = $page_array[$count] + 1;
                if($next_id > $total_links) {
                    $next_link = '<li class="page-item disabled">
                                    <a class="page-link" href="#">
                                        <i class="icofont-rounded-right"></i>
                                    </a>
                                </li>';
                } else {
                    $onclick = '    onclick="'.$function.'(\'' . $next_id . '\'' .$data.')"';
                    
                    $next_link = '<li class="page-item">
                                    <a class="page-link"  '.$onclick.' href="javascript:void(0)" data-page_number="'.$next_id.'">
                                        <i class="icofont-rounded-right"></i>
                                    </a>
                                </li>';
    
                }
            } else {
                if($page_array[$count] == '...') {
                    $page_link .= '<li class="page-item disabled">
                                    <a class="page-link" href="#">...</a>
                                </li> ';
                }else{
                    $onclick = '    onclick="'.$function.'(\'' . $page_array[$count] . '\'' .$data.')"';
                    $page_link .= '<li class="page-item">
                                <a class="page-link" '.$onclick.' href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
                            </li>';
                }
    
            }
        }
        $html = '';
        
        $html .= '<div class="row  mt-3">';
            $html .= '<div class="col-md-6">';
                $html .= '<ul class="pagination">';
                $html .= $previous_link;
                $html .= $page_link;
                $html .= $next_link;
                $html .= '</ul>';
            $html .= '</div>';
            
            $html .= '<div class="col-md-6">';
                $html .= '<div class="div-block">';
                $html .= '<div class="text-right mt-1" style="color:#606060;font-size:14px">';
                    if($total_links<=1){
                        $html .= 'ทั้งหมด '.__number($total_row).' รายการ';
                    }else{
                        $row_start = 0;
                        $row_end = 0;
                        if($pagenumber==1){
                            $row_start = 1;
                        }else{
                            $row_start = ($limit_row*($pagenumber-1)) + 1;
                        }
                        
                        if($pagenumber==$total_links){
                            $row_end = $total_row;
                        }else{
                            $row_end = ($limit_row*($pagenumber-1))+$limit_row;
                        }
                        
                        $html .= 'รายการที่ <b>'.__number($row_start).'-'.__number($row_end).'</b> จากทั้งหมด '.__number($total_row).' รายการ';
                    }
                    
                
                $html .= '</div>';
                
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    
    }
}   
if (!function_exists('__encode')) {
    function __encode($text = "") {
        $string = $text;
        $hash = "";
        $j = 0;
        $secureKey = "eptg_acsc_acc";
        $key = sha1($secureKey);
        $strLen = strlen($string);
        $keyLen = strlen($key);
        for ($i = 0; $i < $strLen; $i++) {
            $ordStr = ord(substr($string, $i, 1));
            if ($j == $keyLen) { $j = 0;
            }
            $ordKey = ord(substr($key, $j, 1));
            $j++;
            $hash .= strrev(base_convert(dechex($ordStr + $ordKey), 16, 36));
        }
        $hash = base64_encode(base64_encode($hash));
        return $hash;
    }
}
if (!function_exists('__decode')) {
    function __decode($text="") {
        $string = $text;
        $hash = "";
        $j = 0;
        $string = base64_decode(base64_decode($string));
        $secureKey = "eptg_acsc_acc";
        $key = sha1($secureKey);
        $strLen = strlen($string);
        $keyLen = strlen($key);
        for ($i = 0; $i < $strLen; $i += 2) {
            $ordStr = hexdec(base_convert(strrev(substr($string, $i, 2)), 36, 16));
            if ($j == $keyLen) { $j = 0;
            }
            $ordKey = ord(substr($key, $j, 1));
            $j++;
            $hash .= chr($ordStr - $ordKey);
        }
        return $hash;
    }
}   
if (!function_exists('__page_seldep')) {
    function __page_seldep($html_dep_box="") {
        $file_current1 = basename($_SERVER["SCRIPT_FILENAME"], '.php');
        $file_current = $file_current1.".php";
        
        $user_id = __session_user("id");
        $user_level_id = __session_user("level_id");
        $user_department_id = __session_user("department_id");
        
        $paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
        $paramurl_department_id = (isset($_GET["dep"])) ?$_GET["dep"] : 0;
        
        $authority_comp_count_dep = __authority_company_count_department($user_id,$paramurl_company_id);
        $authority_dep_text_list = __authority_department_text_list($user_id,$paramurl_company_id);
        $authority_dep_check = __authority_department_check($user_id,$paramurl_company_id,$paramurl_department_id);
        $arrDepAll = __authority_department_list($user_id,$paramurl_company_id);
        
        $pagethis = $file_current;
        if($authority_comp_count_dep==1){
             if(empty($paramurl_department_id) || !$authority_dep_check){
                header("Location: ".$pagethis."?cid=".$paramurl_company_id."&dep=".$authority_dep_text_list);
                exit;
            }
        }
        
         if(!empty($paramurl_department_id) && $authority_dep_check){
         }else{
             echo '<!DOCTYPE html>';
             echo '<html>';
             echo '<head>';
                include 'head.php'; 
                echo '<link rel="stylesheet" type="text/css" href="css/checkbox.css">';
            echo '</head>';
                echo  '<body>';
                     include 'navbar.php';
                    echo '<section>';
                        echo '<div class="container">';
                            echo $html_dep_box;
                        echo  '</div>';
                    echo  '</section>';
            
                echo '</body>';
            echo  '</html>';
            exit;
         }
                 
    }
}
if (!function_exists('__page_seldep2')) {
    function __page_seldep2($html_title="") {
        $file_current1 = basename($_SERVER["SCRIPT_FILENAME"], '.php');
        $file_current = $file_current1.".php";
        
        $html_title = ($html_title!="") ? $html_title : '<i class="icofont-caret-right"></i> เลือกฝ่าย';
        
        $user_id = __session_user("id");
        $user_level_id = __session_user("level_id");
        $user_department_id = __session_user("department_id");
        
        $paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
        $paramurl_department_id = (isset($_GET["dep"])) ?$_GET["dep"] : 0;
        
        $authority_comp_count_dep = __authority_company_count_department($user_id,$paramurl_company_id);
        $authority_dep_text_list = __authority_department_text_list($user_id,$paramurl_company_id);
        $authority_dep_check = __authority_department_check($user_id,$paramurl_company_id,$paramurl_department_id);
        $arrDepAll = __authority_department_list($user_id,$paramurl_company_id);
        
        $pagethis = $file_current;
        if($authority_comp_count_dep==1){
             if(empty($paramurl_department_id) || !$authority_dep_check){
                header("Location: ".$pagethis."?cid=".$paramurl_company_id."&dep=".$authority_dep_text_list);
                exit;
            }
        }
        
         if(!empty($paramurl_department_id) && $authority_dep_check){
         }else{
             echo '<!DOCTYPE html>';
             echo '<html>';
             echo '<head>';
                include 'head.php'; 
                echo '<link rel="stylesheet" type="text/css" href="css/checkbox.css">';
            echo '</head>';
                echo  '<body>';
                     include 'navbar.php';
                    echo '<section>';
                        echo '<div class="container">';
                            echo __html_dep_box2($html_title,$file_current);
                        echo  '</div>';
                    echo  '</section>';
            
                echo '</body>';
            echo  '</html>';
            exit;
         }
    }
}
if (!function_exists('__page_noseldep')) {
    function __page_noseldep() {
        $file_current1 = basename($_SERVER["SCRIPT_FILENAME"], '.php');
        $file_current = $file_current1.".php";
        
        $user_id = __session_user("id");
        $user_level_id = __session_user("level_id");
        $user_department_id = __session_user("department_id");
        
        $paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
        $paramurl_department_id = (isset($_GET["dep"])) ?$_GET["dep"] : 0;
        
        $authority_comp_count_dep = __authority_company_count_department($user_id,$paramurl_company_id);
        $authority_dep_text_list = __authority_department_text_list($user_id,$paramurl_company_id);
        $authority_dep_check = __authority_department_check($user_id,$paramurl_company_id,$paramurl_department_id);
        $arrDepAll = __authority_department_list($user_id,$paramurl_company_id);
        
        $pagethis = $file_current;
        if($authority_comp_count_dep==1){
             if(empty($paramurl_department_id)){
                header("Location: ".$pagethis."?cid=".$paramurl_company_id."&dep=".$authority_dep_text_list);
                exit;
            }
        }else{
             if(!empty($paramurl_department_id)){
                header("Location: ".$pagethis."?cid=".$paramurl_company_id);
                exit;
            }
        }
    }
}
if (!function_exists('__implode_department')) {
    function __implode_department($company_id=0,$sting=",",$authority=1){
        $db = new database();
        
        $user_id = __session_user("id");
        $paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
        $paramurl_department_id = (isset($_GET["dep"])) ? $_GET["dep"] : 0;
        
        $con = "";
        if($authority==1){
            $authority_dep_text_list = __authority_department_text_list($user_id,$paramurl_company_id);
            $con = " AND dep_id IN  ('".$authority_dep_text_list."') ";
        }
        
        $sql = "    SELECT * FROM   department_tb WHERE dep_compid = '". $paramurl_company_id ."' ".$con." AND dep_status = 1";
        $list = $db->query($sql)->result();
        
        $text = "";
        if(count($list)>=1){
            $column_name = array_column($list, "dep_name");
            $text = implode($sting, $column_name);
        }
        
        return $text; 
    }
}
if (!function_exists('__html_select_dep')) {
    function __html_select_dep($company_id=0,$inputname="inputGroupSelectDep",$authority=1){
        $db = new database();
        $user_id = __session_user("id");
        
        $con = "";
        if($authority==1){
            $authority_dep_text_list = __authority_department_text_list($user_id,$company_id);
            $con = " AND dep_id IN  ('".$authority_dep_text_list."') ";
        }
        
        $sql = "    SELECT * FROM   department_tb WHERE dep_compid = '". $company_id ."' ".$con." AND dep_status = 1 ORDER BY dep_name";
        $result = $db->query($sql)->result();
            
        $html    = "";
        $html .= '<label>ฝ่าย</label>';
        if(count($result)>=2){
            $option = "";
            foreach ($result as $row) {
                $option .= '<option value="'.$row["dep_id"].'">'.$row["dep_name"].'</option>';
            }
                    
            $html .= '<div class="input-group">';
                $html .= '<div class="input-group-prepend">
                    <i class="input-group-text">
                        <i class="icofont-company"></i>
                    </i>
                </div>';
                $html .= '<select class="custom-select form-control" name="'.$inputname.'" id="'.$inputname.'">';
                    $html .= '<option value="" selected>เลือกฝ่าย</option>';
                    $html .= $option;
                $html .= '</select>';
            $html .= '</div>';
        }else{
            $data = $db->query($sql)->row();
            $html .= '<input type="text" class="form-control"  value="'.$data["dep_name"].'" disabled>';
            $html .= '<input type="text" class="form-contro d-none" name="'.$inputname.'" id="'.$inputname.'" value="'.$data["dep_id"].'">';
        }
        
        return $html;
    }
}
?>