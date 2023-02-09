<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{
	
		if(isset($_POST["reppdid"])) {

			include 'connect.php';

			$str_sql = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_id = '".$_POST["reppdid"]."'";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);
			echo json_encode($obj_row);

		}

	}

?>