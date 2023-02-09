<?php

	// header('Content-Type: application/json');
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

			$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '".$depid."'";
			$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
			$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

			$Acode = "APCEO-" . $obj_row_dep["dep_name"];
			$year = substr(date("Y")+543, -2);
			$month = date("m");
			$str_sql_apprid = "SELECT MAX(apprCEO_no) as last_id FROM approveceo_tb WHERE apprCEO_depid = '".$depid."' AND apprCEO_month = '". $month ."'";
			$obj_rs_apprid = mysqli_query($obj_con, $str_sql_apprid);
			$obj_row_apprid = mysqli_fetch_array($obj_rs_apprid);
			$maxAId = substr($obj_row_apprid['last_id'], -3);

			if ($maxAId== "") {
				$maxAId = $year.$month."001";
			} else {
				$maxAId = ($maxAId + 1);
				$maxAId = $year.$month.substr("000".$maxAId, -3);
			}
			$nextapprid = $Acode.$maxAId;

			$appruseridCEO =  mysqli_real_escape_string($obj_con, $_POST["appruseridCEO"]);
			$apprdate = date('Y-m-d');
			$appryear = date('Y') + 543;
			$apprmonth = date('m');
			$apprdatecreate = date('Y-m-d H:i:s');

			$str_sql_CEO = "INSERT INTO approveceo_tb (apprCEO_no, apprCEO_date, apprCEO_year, apprCEO_month, apprCEO_depid, apprCEO_userid_create, apprCEO_datecreate) VALUES (";
			$str_sql_CEO .= "'" . $nextapprid . "',";
			$str_sql_CEO .= "'" . $apprdate . "',";
			$str_sql_CEO .= "'" . $appryear . "',";
			$str_sql_CEO .= "'" . $apprmonth . "',";
			$str_sql_CEO .= "'" . $depid . "',";
			$str_sql_CEO .= "'" . $appruseridCEO . "',";
			$str_sql_CEO .= "'" . $apprdatecreate . "')";
			$result_CEO = mysqli_query($obj_con, $str_sql_CEO);

			$countChk = 0;
			$str_sql_Chk = "SELECT * FROM purchasereq_tb WHERE purc_statusceo = 1 AND purc_apprceono = '' AND purc_compid = '". $compid ."' AND purc_depid = '". $depid . "'";
			$obj_rs_Chk = mysqli_query($obj_con, $str_sql_Chk);
			while($obj_row_Chk = mysqli_fetch_array($obj_rs_Chk)) {
				$countChk++;
			}

			if ($countChk != '0') {
				for ($n = 1; $n <= $countChk; $n++) {
					$purcid = 'purcid' . $n;
					if(isset($_POST["$purcid"])) {
						$purcid = $_POST["$purcid"];
					}

					$sts = 'purcstsceo' . $n;

					if(isset($_POST["$sts"])) {
						if($_POST["$sts"] == 1) {

							$sts = $_POST["$sts"];
							$purcapprceono = $nextapprid;

						} else {
							$sts = '0';
							$purcapprceono = '';
						}
					} else {
						$sts = '0';
						$purcapprceono = '';
					}
					
					
					$str_sql_up = "UPDATE purchasereq_tb SET purc_apprceono = '$purcapprceono' WHERE purc_id = '". $purcid ."'";
					$result_up = mysqli_query($obj_con, $str_sql_up);

				}
			}

			if($result_CEO) {

				if($compid == 'C001') {
					echo json_encode(array('status' => '1','message'=> $nextapprid,'compid'=> $compid,'depid'=> $depid));
				} else {
					echo json_encode(array('status' => '2','message'=> $nextapprid,'compid'=> $compid,'depid'=> $depid));
				}
			} else {
				echo json_encode(array('status' => '0','message_Chk'=> $str_sql,'message_CEO'=> $str_sql_CEO));
			}

			mysqli_close($obj_con);

		}

	}

?>