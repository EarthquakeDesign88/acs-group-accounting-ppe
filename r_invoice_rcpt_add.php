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

			if(isset($_POST["BackVal"])) {
				$BackVal = $_POST["BackVal"];
			}

			if(isset($_POST["invReno"])) {
				$invReno = $_POST["invReno"];
			} else {
				$invReno = '';
			}

			$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $depid ."'";
			$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
			$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

			$depname = $obj_row_dep["dep_name"];
			$depcode = $obj_row_dep["dep_code"];

			$year = substr(date("Y") + 543,-2);
			$y = date("Y") + 543;
			$month = $_POST["SelMonth"];

			if(isset($_POST["SelinvrcptYear"])) {
				$SelinvrcptYear = $_POST["SelinvrcptYear"];
			} else {
				$SelinvrcptYear = '';
			}

			if(isset($_POST["SelYear"])) {
				$SelYear = $_POST["SelYear"];
			} else {
				$SelYear = '';
			}

			if(isset($_POST["SelMonth"])) {
				$SelMonth = $_POST["SelMonth"];
			} else {
				$SelMonth = '';
			}

			if($invReno == '') {

				if($depid == 'D022') {

					$str_sql_invre = "SELECT MAX(invrcpt_no) AS last_id FROM invoice_rcpt_tb WHERE invrcpt_depid = '".$depid."'";
					$obj_rs_invre = mysqli_query($obj_con, $str_sql_invre);
					$obj_row_invre = mysqli_fetch_array($obj_rs_invre);
					$maxCId = substr($obj_row_invre['last_id'], -3);
					if ($maxCId== "") {
						$maxCId = "001";
					} else {
						$maxCId = ($maxCId + 1);
						$maxCId = substr("000".$maxCId, -3);
					}


				} else {

					$str_sql_invre = "SELECT MAX(invrcpt_no) AS last_id FROM invoice_rcpt_tb WHERE invrcpt_month = '".$SelMonth."' AND invrcpt_depid = '".$depid."' AND invrcpt_year = '".$SelinvrcptYear."'";
					$obj_rs_invre = mysqli_query($obj_con, $str_sql_invre);
					$obj_row_invre = mysqli_fetch_array($obj_rs_invre);
					$maxCId = substr($obj_row_invre['last_id'], -4);
					if ($maxCId== "") {
						$maxCId = "0001";
					} else {
						$maxCId = ($maxCId + 1);
						$maxCId = substr("0000".$maxCId, -4);
					}

				}

				if($compid == 'C001' || $compid == 'C014') {
					if($depid == 'D022') {
						$nextinvReno = "PMCBLE/RE.".$maxCId;
					} else {
						$nextinvReno = $SelYear.$SelMonth.$maxCId;
					}
				} else {
					$nextinvReno = $depname.$SelYear.$SelMonth.$maxCId;
				}

				include 'r_invoice_rcpt_add_sub.php';

				if($query) {
					echo json_encode(array('status' => '1','message'=> $nextinvReno,'bookNo' => $depcode,'compid'=> $compid,'irID'=> $irID,'depid'=> $depid,'projid'=> $invReprojid,'month'=> $month,'url'=> $url));
				} else {
					echo json_encode(array('status' => '0','message'=> $str_sql));
				}

			} else { 

				$invy = substr($invReno, 0, 2);
				$invm = substr($invReno, 2, 2);
				$invn = substr($invReno, 4, 4);

				$inv = $invy . $invm . $invn;

				$str_sql_chkNo = "SELECT * FROM invoice_rcpt_tb WHERE invrcpt_depid = '". $depid ."' AND invrcpt_no = '". $inv ."'";
				$obj_rs_chkNo = mysqli_query($obj_con, $str_sql_chkNo);
				$obj_row_chkNo = mysqli_fetch_array($obj_rs_chkNo);

				if(mysqli_num_rows($obj_rs_chkNo) > 0) {
					$SelInvno = $obj_row_chkNo["invrcpt_no"];
				} else {
					$SelInvno = '';
				}

				if($SelInvno == '') {

					$nextinvReno = $invReno;

					include 'r_invoice_rcpt_add_sub.php';

					if($query) {
						echo json_encode(array('status' => '1','message'=> $nextinvReno,'bookNo' => $depcode,'compid'=> $compid,'irID'=> $irID,'depid'=> $depid,'projid'=> $invReprojid,'month'=> $month,'url'=> $url));
					} else {
						echo json_encode(array('status' => '0','message'=> $str_sql));
					}

				} else {

					if($SelInvno == $invReno) {
						
						echo json_encode(array('status' => '2','message'=> 'ERROR'));

					} else {

						$nextinvReno = $invReno;

						include 'r_invoice_rcpt_add_sub.php';

						if($query) {
							echo json_encode(array('status' => '1','message'=> $nextinvReno,'bookNo' => $depcode,'compid'=> $compid,'irID'=> $irID,'depid'=> $depid,'projid'=> $invReprojid,'month'=> $month,'url'=> $url));
						} else {
							echo json_encode(array('status' => '0','message'=> $str_sql));
						}

					}

				}

			}

			mysqli_close($obj_con);

		}

	}

?>