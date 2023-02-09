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
					$invstatusMgr = '1';
				}

			}

			if($_POST["sts"] == '1' ) {

				if(isset($_POST["sts"])) {
					$invstatusMgr = '0';
				}

			}
			
			$invid = mysqli_real_escape_string($obj_con, $_POST["invid"]);

			$str_sql = "UPDATE invoice_tb SET 
			inv_statusMgr = '$invstatusMgr'
			WHERE inv_id = '".$invid."'";

			if(mysqli_query($obj_con, $str_sql)) {

			} else {
				echo "Error " . $str_sql;
			}

			echo $output;

		}

	}

?>