<?php
	
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {
	
		if(isset($_POST["del_id"])) {

			include 'connect.php';

			$sql_invrcpt = "SELECT * FROM invoice_rcpt_tb WHERE invrcpt_reid = '" . $_POST["del_id"] . "'";
			$rs_invrcpt = mysqli_query($obj_con, $sql_invrcpt);
			$row_invrcpt = mysqli_fetch_array($rs_invrcpt);

			$balance = $row_invrcpt["invrcpt_subtotal"];

			$sql_up = "UPDATE invoice_rcpt_tb SET 
			invrcpt_balancetotal = '$balance',
			invrcpt_stsid = 'STS001',
			invrcpt_reid = ''
			WHERE invrcpt_reid = '". $_POST["del_id"] ."'"; 
			$rs_up = mysqli_query($obj_con, $sql_up);

			$sql = "DELETE FROM receipt_tb WHERE re_id = '" . $_POST["del_id"] . "'";
			$rs = mysqli_query($obj_con, $sql);
			$row = mysqli_fetch_array($rs);

			echo json_encode($row);

			mysqli_close($obj_con);

		}

	}

?>