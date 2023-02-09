<?php 
	
	header('Content-Type: application/json');
	
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';
			
			$compid = $_POST["compid"];
			$depid = $_POST["depid"];
			$projid = $_POST["projid"];

			$url = "invoice_rcpt_project_desc_print.php?cid=$compid&dep=$depid&projid=$projid";

			echo json_encode(array('status' => '1','url'=> $url));

		} else {

			echo json_encode(array('status' => '0','message'=> 'ERROR'));

		}

	}

?>