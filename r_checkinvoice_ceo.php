<?php

	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			$cid = $_POST["compid"];
			$dep = $_POST["depid"];

			$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
			$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
			$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

			$Acode = "APCEO-" . $obj_row_dep["dep_name"];
			$year = substr(date("Y")+543, -2);
			$month = date("m");
			$str_sql_apprid = "SELECT MAX(apprCEO_no) as last_id FROM approveceo_tb WHERE apprCEO_depid = '".$dep."' AND apprCEO_month = '". $month ."'";
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
			$str_sql_CEO .= "'" . $dep . "',";
			$str_sql_CEO .= "'" . $appruseridCEO . "',";
			$str_sql_CEO .= "'" . $apprdatecreate . "')";
			$result_CEO = mysqli_query($obj_con, $str_sql_CEO);

			$countChk = 0;
			$str_sql_Chk = "SELECT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id INNER JOIN user_tb AS u ON i.inv_userid_create = u.user_id WHERE inv_statusMgr = 1 AND inv_apprMgrno <> '' AND inv_apprCEOno = '' AND inv_depid = '". $dep ."' AND inv_compid = '". $cid ."' ORDER BY inv_id DESC";
			$obj_rs_Chk = mysqli_query($obj_con, $str_sql_Chk);
			while($obj_row_Chk = mysqli_fetch_array($obj_rs_Chk)) {
				$countChk++;
			}

			// echo $countChk . "<br>";

			if ($countChk != '0') {
				for ($n = 1; $n <= $countChk; $n++) {

					$id = 'id' . $n;
					if(isset($_POST["$id"])) {
						$id = mysqli_real_escape_string($obj_con, $_POST["$id"]);
						// echo " ID : " . $id . '<br>';
					}

					$sts = 'invstsCEO' . $n;

					// echo $sts . "<br>";

					if(isset($_POST["$sts"])) {
						if($_POST["$sts"] == 1) {

							$sts = mysqli_real_escape_string($obj_con, $_POST["$sts"]);
							$invapprCEOno = $nextapprid;

							// echo $sts . "<br>";

						} else {
							$sts = '';
							$invapprCEOno = '';
						}
					} else {
						$sts = '';
						$invapprCEOno = '';
					}
					
					
					$str_sql = "UPDATE invoice_tb SET inv_apprCEOno = '$invapprCEOno' WHERE inv_id = '". $id ."'";
					$result = mysqli_query($obj_con, $str_sql);

					// echo "SUCCESS UPDATE : " . $str_sql . "<br><br>";

				}


			}

			if($result_CEO && $result) {
				// if ($dep == 'D003' || $dep == 'D004') {

					// echo json_encode(array('status' => '2','message'=> $nextapprid,'compid'=> $cid,'dep'=> $dep));

				// } else {

					echo json_encode(array('status' => '1','message'=> $nextapprid,'compid'=> $cid,'dep'=> $dep));
					// echo "SUCCESS UPDATE : " . $str_sql . "<br>";
					// echo "SUCCESS INSERT : " . $str_sql_CEO . "<br>";

				// }

			} else {

				echo json_encode(array('status' => '0','message_Chk'=> $str_sql,'message_Mgr'=> $str_sql_CEO));
				// echo "ERROR UPDATE : " . $str_sql . "<br>";
				// echo "ERROR INSERT : " . $str_sql_CEO . "<br>";

			}

			mysqli_close($obj_con);

		} else {

			echo "ERROR";

		}

		
	
	}

?>