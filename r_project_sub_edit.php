<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{
	
		if(isset($_POST["projSid"])) {

			include 'connect.php';

			$str_sql = "SELECT * FROM project_sub_tb WHERE projS_id = '".$_POST["projSid"]."'";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);
			echo json_encode($obj_row);

		}

	}

?>