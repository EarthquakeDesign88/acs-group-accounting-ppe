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

			$code = "REPD";
			$year = substr(date("Y")+543,-2);
			$str_sql_repdno = "SELECT MAX(repd_no) AS last_id FROM reportpay_desc_tb";
			$obj_rs_repdno = mysqli_query($obj_con, $str_sql_repdno);
			$obj_row_repdno = mysqli_fetch_array($obj_rs_repdno);
			$maxId = substr($obj_row_repdno['last_id'], -4);
			if ($maxId== "") {
				$maxId = "0001";
			} else {
				$maxId = ($maxId + 1);
				$maxId = substr("0000".$maxId, -4);
			}
			$nextrepdno = $code.$year.$maxId;

			if(isset($_POST["countPlus"])){
				$countPlus = $_POST["countPlus"];
			} else {
				$countPlus = '';
			}

			for ($i = 1; $i <= $countPlus; $i++) {
				$txtPlus = 'txtPlus'.$i;

				if(isset($_POST["$txtPlus"])){
					$txtPlus = $_POST["$txtPlus"];
				} else {
					$txtPlus = '';
				}

				$txtdescPlus = 'txtdescPlus'.$i;
				if(isset($_POST["$txtdescPlus"])){
					$txtdescPlus = $_POST["$txtdescPlus"];
				} else {
					$txtdescPlus = '';
				}

				$txttotalPlus = 'txttotalPlus'.$i;
				if(isset($_POST["$txttotalPlus"])){
					$txttotalPlus = $_POST["$txttotalPlus"];
				} else {
					$txttotalPlus = '';
				}

				$repid = '';

				$str_sql_plus = "INSERT INTO reportpay_desc_tb (repd_no, repd_type, repd_description, repd_amount, repd_repid) VALUES (";
				$str_sql_plus .= "'" . $nextrepdno . "',";
				$str_sql_plus .= "'" . $txtPlus . "',";
				$str_sql_plus .= "'" . $txtdescPlus . "',";
				$str_sql_plus .= "'" . $txttotalPlus . "',";
				$str_sql_plus .= "'" . $repid . "')";
				$result_plus = mysqli_query($obj_con, $str_sql_plus);


				// echo $txtPlus . "<br>";
				// echo $txtdescPlus . "<br>";
				// echo $txttotalPlus . "<br>";
				// echo $str_sql_plus . "<br>";

			}

			if(isset($_POST["countDis"])){
				$countDis = $_POST["countDis"];
			} else {
				$countDis = '';
			}

			for ($i = 1; $i <= $countDis; $i++) {
				$txtDis = 'txtDis'.$i;

				if(isset($_POST["$txtDis"])){
					$txtDis = $_POST["$txtDis"];
				} else {
					$txtDis = '';
				}

				$txtdescDis = 'txtdescDis'.$i;
				if(isset($_POST["$txtdescDis"])){
					$txtdescDis = $_POST["$txtdescDis"];
				} else {
					$txtdescDis = '';
				}


				$txttotalDis = 'txttotalDis'.$i;
				if(isset($_POST["$txttotalDis"])){
					$txttotalDis = $_POST["$txttotalDis"];
				} else {
					$txttotalDis = '';
				}


				$repid = '';


				$str_sql_dis = "INSERT INTO reportpay_desc_tb (repd_no, repd_type, repd_description, repd_amount, repd_repid) VALUES (";
				$str_sql_dis .= "'" . $nextrepdno . "',";
				$str_sql_dis .= "'" . $txtDis . "',";
				$str_sql_dis .= "'" . $txtdescDis . "',";
				$str_sql_dis .= "'" . $txttotalDis . "',";
				$str_sql_dis .= "'" . $repid . "')";
				$result_dis = mysqli_query($obj_con, $str_sql_dis);


				// echo $txtDis . "<br>";
				// echo $txtdescDis . "<br>";
				// echo $txttotalDis . "<br>";
				// echo $str_sql_dis . "<br>";

			}


			if($result_plus && $result_dis) {

				echo json_encode(array('status' => '1','repdno'=> $nextrepdno,'compid'=> $compid,'depid'=> $depid));

			} else {

				echo json_encode(array('status' => '0','messagePlus'=> $str_sql_plus,'messageDis'=> $str_sql_dis));

			}


		}

	}

?>