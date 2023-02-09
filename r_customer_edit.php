<?php
	
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {
	
		if(isset($_POST["cust_id"])) {

			include 'connect.php';

			$str_sql = "SELECT * FROM customer_tb WHERE cust_id = '".$_POST["cust_id"]."'";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);
			echo json_encode($obj_row);

		}

	}

?>