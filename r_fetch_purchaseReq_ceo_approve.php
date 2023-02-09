<?php
	
	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {
	
		if(!empty($_POST)) {

			include 'connect.php';

			$output = '';

			if($_POST["sts"] == '0' ) {

				if(isset($_POST["sts"])) {
					$purcstsceo = '1';
				}

			}

			if($_POST["sts"] == '1' ) {

				if(isset($_POST["sts"])) {
					$purcstsceo = '0';
				}

			}
			
			$purcid = mysqli_real_escape_string($obj_con, $_POST["purcid"]);

			$str_sql = "UPDATE purchasereq_tb SET 
			purc_statusceo = '$purcstsceo'
			WHERE purc_id = '".$purcid."'";

			if(mysqli_query($obj_con, $str_sql)) {

			} else {
				echo "Error " . $str_sql;
			}

			echo $output;

		}

	}

?>