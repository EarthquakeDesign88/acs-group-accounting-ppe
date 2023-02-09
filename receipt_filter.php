<?php
	session_start();
    include 'connect.php';
    include 'function_receipt.php';
	if (!$_SESSION["user_name"]){  //check session
		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {
        if(isset($_POST)){
            $obj = array();
            foreach($_POST as $key=>$value){
                $obj[$key] = $value;
            }
            if(isset($_POST['action'])){
                $action = $_POST['action'];
                if($action == "bank"){
                    $data = get_bank();
       
                }else if($action == "branch"){
                    $data = get_branch($obj);
                }else if($action == "save_pay"){
                    $data = save_pay($obj);
                }
                echo json_encode($data);
            }
        }
    }
?>