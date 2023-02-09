<?php
	
	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{
	
		if(!empty($_POST)) {

			include 'connect.php';

			$output = '';

			if($_POST["sts"] == '0' ) {

				if(isset($_POST["sts"])) {
					$invrcptDstatus = '1';
				}

			}

			if($_POST["sts"] == '1' ) {

				if(isset($_POST["sts"])) {
					$invrcptDstatus = '0';
				}

			}
			
			$selirDid = $_POST["selirDid"];

			$str_sql = "UPDATE invoice_rcpt_desc_tb SET 
			invrcptD_status = '$invrcptDstatus'
			WHERE irDid = '". $selirDid ."'";

			if(mysqli_query($obj_con, $str_sql)) {

			} else {
				echo "Error " . $str_sql;
			}

			echo $output;

		}

	}

?>