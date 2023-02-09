<?php
	
	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{
	
		if(!empty($_POST)) {

			include 'connect.php';

			$output = '';

			if($_POST["stsRepid"] == '0' ) {

				if(isset($_POST["stsRepid"])) {
					$stsRepid = '1';
					$reppid = '';
				}

			}

			if($_POST["stsRepid"] == '1' ) {

				if(isset($_POST["stsRepid"])) {
					$stsRepid = '0';
					$reppid = '';
				}

			}
			
			$paymid = $_POST["paymid"];

			$str_sql = "UPDATE payment_tb SET 
			paym_statusRepid = '$stsRepid',
			paym_reppid = '$reppid'
			WHERE paym_id = '". $paymid ."'";

			if(mysqli_query($obj_con, $str_sql)) {

			} else {
				echo "Error " . $str_sql;
			}

			echo $output;

		}

	}

?>