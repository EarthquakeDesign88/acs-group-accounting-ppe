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

            }else if($_POST['action'] === "create_tbri"){
                include 'connect.php';

                $obj = array();

                foreach($_POST as $key=>$value){
                    $obj[$key] = $value;
                }

                $code = "TAX";
                $year_head = substr(date("Y")+543,-2);
                $month_head = date("m",strtotime($obj['date_head']));
                $dep_check = "TBRI";
                $sql_query = "SELECT tax_id FROM taxpurchase_tb WHERE tax_dep_id = 'D013' ORDER BY tax_id DESC";
                $obj_row = mysqli_query($obj_con,$sql_query);
                $obj_result = mysqli_fetch_array($obj_row);

                if(mysqli_num_rows($obj_row) == 0){
                    $tax = $code . $dep_check . $year_head. $month_head . "0001";  
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
                    $tax = $code . $dep_check . $year_head. $month_head . $max; 
                }

                $user_id = $_SESSION["user_id"];
                $date_created = date("Y-m-d h:i:s");
                $str_sql = "INSERT INTO taxpurchase_tb(tax_id,tax_number,tax_comp_id,tax_created_at,tax_dep_id,tax_file,tax_price,tax_vat,tax_result,user_created,created_at) 
                VALUES('$tax','0105538106461','C009','".$obj['date_head']."','D013','$newname',".$obj['price_all'].",".$obj['vat_all'].",".$obj['tax_result_all'].",'$user_id','$date_created')";
                $str_insert = mysqli_query($obj_con,$str_sql);

                $str_sql2 = "INSERT INTO taxpurchaselist_tb(list_tax_id,list_no,list_paya_id,list_desc,list_price,list_vat,list_result,created_at,list_avg_vat_percent) 
                VALUES";
                foreach($obj['data'] as $value){
                    $str_sql2 .= "('$tax','".$value['tax_no']."','".$value['paya_id']."','".$value['list']."','".$value['price']."','".$value['vat']."','".$value['result']."','".$value['date']."','".$value['avg_percent']."'),";
                }
                $str_sql2 = substr_replace($str_sql2,";",-1);
                mysqli_query($obj_con,$str_sql2);

                echo json_encode(['message'=>'success','tax_id'=>$tax]);
                mysqli_close($obj_con);
                
            }else if($_POST['action'] === "update_tbri"){
                include 'connect.php';
                $obj = array();

                foreach($_POST as $key=>$value){
                    $obj[$key] = $value;
                }
                                
                if(!is_null($obj['del_id'])){
                    $sql_del = "DELETE FROM taxpurchaselist_tb WHERE list_id IN (";
                    for($i = 0; $i < count($obj['del_id']); $i++){
                        $sql_del .= "'".$obj['del_id'][$i]."',";
                        
                    }
                    $sql_del = substr_replace($sql_del,")",-1);
                    mysqli_query($obj_con, $sql_del);
                }

                $user_id = $_SESSION['user_id'];
                $date_n = date("Y-m-d h:i:s");
                $sql_update = "UPDATE taxpurchase_tb SET tax_created_at='".$obj['date_head']."',tax_price=".$obj['price_all'].",tax_vat=".$obj['vat_all'].",tax_result=".$obj['tax_result_all'].",updated_at='$date_n',user_updated='$user_id' WHERE tax_id='".$obj['tax_id']."'";
                $sql_update_q = mysqli_query($obj_con,$sql_update);

                $sql_insert = "INSERT INTO taxpurchaselist_tb(list_id,list_tax_id,list_no,list_paya_id,list_desc,list_price,list_vat,list_result,created_at,list_avg_vat_percent) 
                VALUES";
                foreach($obj['data'] as $value){

                    $sql_insert .= "(CONVERT('".$value['id']."',CHAR),
                    '".$obj['tax_id']."',
                    '".$value['tax_no']."',
                    '".$value['paya_id']."',
                    '".$value['list']."',
                    '".$value['price']."',
                    '".$value['vat']."',
                    '".$value['result']."',
                    '".$value['date']."',
                    '".$value['avg_percent']."'),";                      
                }
                
                $sql_insert = substr_replace($sql_insert," ",-1);

                $sql_insert .= "ON DUPLICATE KEY UPDATE list_id = VALUES(list_id),
                list_tax_id = VALUES(list_tax_id),
                list_no = VALUES(list_no),
                list_paya_id = VALUES(list_paya_id),
                list_desc = VALUES(list_desc),
                list_price = VALUES(list_price),
                list_vat = VALUES(list_vat),
                list_result = VALUES(list_result),
                created_at = VALUES(created_at),
                list_avg_vat_percent = VALUES(list_avg_vat_percent);";  
               
                mysqli_query($obj_con,$sql_insert);
                
                echo json_encode(['message'=>'success','tax_id'=>$obj['tax_id']]);
                mysqli_close($obj_con);
            }
        }
        
    }
?>
