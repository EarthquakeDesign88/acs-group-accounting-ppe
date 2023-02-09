<?php
	
	// header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {
			include 'connect.php';

			$cid = $_POST["compid"];
			$dep = $_POST["depid"];
			$countChk = $_POST["CountChkAll"];

			$countSelChk = 0;
			$str_sql_paym = "SELECT * FROM payment_tb AS paym INNER JOIN invoice_tb AS i ON paym.paym_id = i.inv_paymid WHERE paym_reppid = '' AND inv_compid = '". $cid ."' AND inv_depid = '". $dep ."' GROUP BY inv_paymid";
			$obj_rs_paym = mysqli_query($obj_con, $str_sql_paym);
			while ($obj_row_paym = mysqli_fetch_array($obj_rs_paym)) {
				$countSelChk++;
			}

			if(isset($_POST["reppid"])) {
				$reppid = $_POST["reppid"];
			} else {
				$reppid = '';
			}

			if(isset($_POST["reppdno"])) {
				$reppdno = $_POST["reppdno"];
			} else {
				$reppdno = '';
			}

			for ($i = 1; $i <= $countChk; $i++) {

				$paymidTB = 'paymidTB'.$i;
				if(isset($_POST["$paymidTB"])){
					$paymidTB = $_POST["$paymidTB"];
				} else {
					$paymidTB = '';
				}

				$paymnoTB = 'paymnoTB'.$i;
				if(isset($_POST["$paymnoTB"])){
					$paymnoTB = $_POST["$paymnoTB"];
				} else {
					$paymnoTB = '';
				}

				$statrepidTB = 'statrepidTB'.$i;
				if(isset($_POST["$statrepidTB"])){
					$statrepidTB = $_POST["$statrepidTB"];
				} else {
					$statrepidTB = '';
				}

				if ($statrepidTB == 1) {

					$str_sql = "UPDATE payment_tb SET
					paym_statusRepid = '$statrepidTB',
					paym_reppid = '$reppid'
					WHERE paym_id = '$paymidTB'";
					$result = mysqli_query($obj_con, $str_sql);

				} 
				
			}

			if ($result) {
				echo json_encode(array('status' => '1','compid'=> $cid,'depid'=> $dep,'reppid'=> $reppid,'reppdno'=> $reppdno));
			} else {
				echo json_encode(array('status' => '0','message'=> $str_sql));
			}

		}

	}

?>