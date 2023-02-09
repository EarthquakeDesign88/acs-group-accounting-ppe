<?php
	
	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {
			
			include 'connect.php';

			if(isset($_POST["compid"])) {
				$compid = $_POST["compid"];
			} else {
				$compid = '';
			}

			if(isset($_POST["depid"])) {
				$depid = $_POST["depid"];
			} else {
				$depid = '';
			}

			if(isset($_POST["reppid"])) {
				$reppid = $_POST["reppid"];
			} else {
				$reppid = '';
			}

			$countPaym = 0;
			$str_sql_paym = "SELECT * FROM payment_tb WHERE paym_reppid = '". $reppid ."'";
			$obj_rs_paym = mysqli_query($obj_con, $str_sql_paym);
			while ($obj_row_paym = mysqli_fetch_array($obj_rs_paym)) {
				$countPaym++;
			}

			// echo $countPaym;

			if(isset($_POST["repppaydate"])) {
				$repppaydate = $_POST["repppaydate"];
			} else {
				$repppaydate = '';
			}

			$str_sql_repp = "UPDATE reportpay_tb1 SET repp_paydate = '$repppaydate' WHERE repp_id = '$reppid'";
			$result_repp = mysqli_query($obj_con, $str_sql_repp);

			for ($i = 1; $i <= $countPaym; $i++) {

				$paymid = "paymid" . $i;
				$paymid = $_POST["$paymid"];

				$str_sql = "UPDATE payment_tb SET paym_paydate = '$repppaydate', paym_stsid = 'STS002' WHERE paym_id = '$paymid'";
				$result = mysqli_query($obj_con, $str_sql);

			}

			if($result && $result_repp) {
				echo json_encode(array('status'=> '1','compid'=> $compid,'depid'=> $depid));
			} else {
				echo json_encode(array('status'=> '0','messageResult'=> $str_sql,'messageRepp'=> $str_sql_repp));
			}

		}

	}

?>