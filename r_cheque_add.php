<?php
	
	// header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {
	
		if(!empty($_POST)) {

			include 'connect.php';

			if(isset($_POST["cheqNo"])) {
				$cheqNo = $_POST["cheqNo"];
			} else {
				$cheqNo = '';
			}

			// echo $cheqNo . "<br>";

			$str_sql_Chk = "SELECT * FROM cheque_tb WHERE cheq_no = '". $_POST["cheqNo"] ."'";
			$obj_rs_Chk = mysqli_query($obj_con, $str_sql_Chk);
			$obj_row_Chk = mysqli_fetch_array($obj_rs_Chk);

			if (mysqli_num_rows($obj_rs_Chk) > 0) {

				echo json_encode(array('status' => '2','message'=> $cheqNo));

			} else {

				if(isset($_POST["compid"])) {
					$cid = $_POST["compid"];
				} else {
					$cid = '';
				}
				// echo "Comp ID : " . $cid . "<br>";

				if(isset($_POST["depid"])) {
					$dep = $_POST["depid"];
				} else {
					$dep = '';
				}
				// echo "Dep ID : " . $dep . "<br>";

				if(isset($_POST["paymid"])) {
					$paymid = $_POST["paymid"];
				} else {
					$paymid = '';
				}
				// echo "Paym ID : " . $paymid . "<br>";

				if(isset($_POST["paymby"])) {
					$paymby = $_POST["paymby"];
				} else {
					$paymby = '';
				}
				// echo "Paym By : " . $paymby . "<br>";

				if ($paymby == 2) {

					if(isset($_POST["cheqDate"])) {
						if($_POST["cheqDate"] != '') {
							$cheqDate = $_POST["cheqDate"];
						} else {
							$cheqDate = '0000-00-00';
						}
					}
					// echo "Cheq Date : " . $cheqDate . "<br>";


					if(isset($_POST["cheqBankid"])) {
						$cheqBankid = $_POST["cheqBankid"];
					} else {
						$cheqBankid = '';
					}
					// echo "Cheq Bank id : " . $cheqBankid . "<br>";


					if(isset($_POST["chequseridcreate"])) {
						$chequseridcreate = $_POST["chequseridcreate"];
					} else {
						$chequseridcreate = '';
					}
					// echo "Cheq Create User : " . $chequseridcreate . "<br>";


					if(isset($_POST["cheqcreatedate"])) {
						if($_POST["cheqcreatedate"] == NULL) {
							$cheqcreatedate = date('Y-m-d H:i:s');
						} else {
							$cheqcreatedate = '0000-00-00';
						}
					}
					// echo "Cheq Create Date : " . $cheqcreatedate . "<br>";

					if(isset($_POST["chequseridedit"])) {
						$chequseridedit = $_POST["chequseridedit"];
					} else {
						$chequseridedit = '';
					}
					// echo "Cheq Edit User : " . $chequseridedit . "<br>";

					if(isset($_POST["cheqeditdate"])) {
						if($_POST["cheqeditdate"] != NULL) {
							$cheqeditdate = date('Y-m-d H:i:s');
						} else {
							$cheqeditdate = '0000-00-00 00:00:00';
						}
					}
					// echo "Cheq Edit Date : " . $cheqeditdate . "<br>";

					$cheqyear = date('Y')+543;
					$cheqmonth = date('m');

					// echo "Cheq Year : " . $cheqyear . "<br>";
					// echo "Cheq Month : " . $cheqmonth . "<br>";

				}

				$str_sql_cheq = "INSERT INTO cheque_tb (cheq_no, cheq_date, cheq_bankid, cheq_file, cheq_year, cheq_month, cheq_userid_create, cheq_createdate, cheq_userid_edit, cheq_editdate, cheq_stsid) VALUES (";
				$str_sql_cheq .= "'" . $cheqNo . "',";
				$str_sql_cheq .= "'" . $cheqDate . "',";
				$str_sql_cheq .= "'" . $cheqBankid . "',";
				$str_sql_cheq .= "'',";
				$str_sql_cheq .= "'" . $cheqyear . "',";
				$str_sql_cheq .= "'" . $cheqmonth . "',";
				$str_sql_cheq .= "'" . $chequseridcreate . "',";
				$str_sql_cheq .= "'" . $cheqcreatedate . "',";
				$str_sql_cheq .= "'" . $chequseridedit . "',";
				$str_sql_cheq .= "'" . $cheqeditdate . "',";
				$str_sql_cheq .= "'" . 'STS002' . "')";
				$result_cheq = mysqli_query($obj_con, $str_sql_cheq);

				// echo "Str Cheq : " . $str_sql_cheq . "<br>";

				$str_sql_cheqSel = "SELECT * FROM cheque_tb WHERE cheq_no = '". $cheqNo ."'";
				$obj_rs_cheqSel = mysqli_query($obj_con, $str_sql_cheqSel);
				$obj_row_cheqSel = mysqli_fetch_array($obj_rs_cheqSel);

				$chequeID = $obj_row_cheqSel["cheq_id"];

				$str_sql = "UPDATE payment_tb SET 
				paym_typepay = '$paymby',
				paym_cheqid = '$chequeID',
				paym_stsid = 'STS001'
				WHERE paym_id = '$paymid'";
				$result = mysqli_query($obj_con, $str_sql);

				// echo "Str Paym : " . $str_sql_cheq . "<br>";

				//BMA
				if ($result && $result_cheq && $cheqBankid == 'B001') {
					echo json_encode(array('status' => '1','message'=> $cheqNo,'compid'=> $cid,'depid'=> $dep,'paymid'=> $paymid,'chequeID'=> $chequeID));
				} 
				//SCB
				else if ($result && $result_cheq && $cheqBankid == 'B002') {
					echo json_encode(array('status' => '1','message'=> $cheqNo,'compid'=> $cid,'depid'=> $dep,'paymid'=> $paymid,'chequeID'=> $chequeID));
				} 
				//KBANK
				else if ($result && $result_cheq && $cheqBankid == 'B003') {
					echo json_encode(array('status' => '1','message'=> $cheqNo,'compid'=> $cid,'depid'=> $dep,'paymid'=> $paymid,'chequeID'=> $chequeID));
				} 
				//KBANK
				else if ($result && $result_cheq && $cheqBankid == 'B004') {
					echo json_encode(array('status' => '1','message'=> $cheqNo,'compid'=> $cid,'depid'=> $dep,'paymid'=> $paymid,'chequeID'=> $chequeID));
				} 
				else {
					echo json_encode(array('status' => '0','message'=> $str_sql));
						
				}

			}
			
		}

	}

?>
