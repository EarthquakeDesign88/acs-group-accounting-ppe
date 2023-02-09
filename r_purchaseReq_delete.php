<?php
	
	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{
	
		if(isset($_POST["del_id"])) {

			include 'connect.php';

			$str_sql = "DELETE FROM purchasereq_tb WHERE purc_no = '" . $_POST["del_id"] . "'";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);

			$str_sql_list = "DELETE FROM purchasereq_list_tb WHERE purclist_purcno = '" . $_POST["del_id"] . "'";
			$obj_rs_list = mysqli_query($obj_con, $str_sql_list);
			$obj_row_list = mysqli_fetch_array($obj_rs_list);
			echo json_encode($obj_row, $obj_row_list);

			mysqli_close($obj_con);

		}

	}

?>