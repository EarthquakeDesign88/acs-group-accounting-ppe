<?php
	
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {
	
		if(isset($_POST["del_id"])) {

			include 'connect.php';

			$str_sql = "DELETE FROM invoice_rcpt_tb WHERE invrcpt_id = '" . $_POST["del_id"] . "'";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);

			$str_sql_up = "UPDATE invoice_rcpt_desc_tb SET 
			invrcptD_status = '0',
			invrcptD_irid = ''
			WHERE invrcptD_irid = '". $_POST["del_id"] ."'"; 
			$result = mysqli_query($obj_con, $str_sql_up);

			echo json_encode($obj_row);

			mysqli_close($obj_con);

		}

	}

?>