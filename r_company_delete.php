<?php
	
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {
	
		if(isset($_POST["del_id"])) {

			include 'connect.php';

			$str_sql = "DELETE FROM company_tb WHERE comp_id = '" . $_POST["del_id"] . "'";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);
			echo json_encode($obj_row);

		}

	}

?>