<?php
	
	// header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {
	
		if(!empty($_POST)) {

			include 'connect.php';

			$str_sql_Chkcheq = "SELECT * FROM cheque_tb WHERE cheq_no = '". $_POST["cheqNo"] ."'";
			$obj_rs_Chkcheq = mysqli_query($obj_con, $str_sql_Chkcheq);
			$obj_row_Chkcheq = mysqli_fetch_array($obj_rs_Chkcheq);

			if ($obj_row_Chkcheq["cheq_no"] == $_POST["cheqNo"]) {
				echo json_encode(array('status' => '2','message'=> $_POST["cheqNo"]));
			} else {

				// $depid = $_POST["depid"];

				if(isset($_POST["compid"])) {
					$cid = $_POST["compid"];
				} else {
					$cid = '';
				}

				if(isset($_POST["paymid"])) {
					$paymid = $_POST["paymid"];
				} else {
					$paymid = '';
				}

				if(isset($_POST["paymby"])) {
					$paymby = $_POST["paymby"];
				} else {
					$paymby = '';
				}

				if ($paymby == 2) {

					if(isset($_POST["cheqNo"])) {
						$cheqNo = $_POST["cheqNo"];
					} else {
						$cheqNo = '';
					}

					if(isset($_POST["cheqDate"])) {
						if($_POST["cheqDate"] != '') {
							$cheqDate = $_POST["cheqDate"];
						} else {
							$cheqDate = '0000-00-00';
						}
					}

					if(isset($_POST["cheqBankid"])) {
						$cheqBankid = $_POST["cheqBankid"];
					} else {
						$cheqBankid = '';
					}

					if(isset($_POST["chequseridcreate"])) {
						$chequseridcreate = $_POST["chequseridcreate"];
					} else {
						$chequseridcreate = '';
					}

					if(isset($_POST["cheqcreatedate"])) {
						if($_POST["cheqcreatedate"] == NULL) {
							$cheqcreatedate = date('Y-m-d H:i:s');
						} else {
							$cheqcreatedate = '0000-00-00';
						}
					}

					if(isset($_POST["chequseridedit"])) {
						$chequseridedit = $_POST["chequseridedit"];
					} else {
						$chequseridedit = '';
					}

					if(isset($_POST["cheqeditdate"])) {
						if($_POST["cheqeditdate"] != NULL) {
							$cheqeditdate = date('Y-m-d H:i:s');
						} else {
							$cheqeditdate = '0000-00-00 00:00:00';
						}
					}

					$str_sql_cheq = "INSERT INTO cheque_tb (cheq_no, cheq_date, cheq_bankid, cheq_file, cheq_userid_create, cheq_createdate, cheq_userid_edit, cheq_editdate, cheq_stsid) VALUES (";
					$str_sql_cheq .= "'" . $cheqNo . "',";
					$str_sql_cheq .= "'" . $cheqDate . "',";
					$str_sql_cheq .= "'" . $cheqBankid . "',";
					$str_sql_cheq .= "'',";
					$str_sql_cheq .= "'" . $chequseridcreate . "',";
					$str_sql_cheq .= "'" . $cheqcreatedate . "',";
					$str_sql_cheq .= "'" . $chequseridedit . "',";
					$str_sql_cheq .= "'" . $cheqeditdate . "',";
					$str_sql_cheq .= "'" . 'STS002' . "')";
					$result_cheq = mysqli_query($obj_con, $str_sql_cheq);

					$str_sql_cheqSel = "SELECT * FROM cheque_tb WHERE cheq_no = '". $cheqNo ."'";
					$obj_rs_cheqSel = mysqli_query($obj_con, $str_sql_cheqSel);
					$obj_row_cheqSel = mysqli_fetch_array($obj_rs_cheqSel);

					$chequeID = $obj_row_cheqSel["cheq_id"];

					$str_sql = "UPDATE payment_tb SET 
					paym_typepay = '2',
					paym_cheqid = '$chequeID',
					paym_stsid = 'STS001'
					WHERE paym_id = '$paymid'";
					$result = mysqli_query($obj_con, $str_sql);

					$output = "cheque_preview.php?cid=".$cid."&paymid=".$paymid."&cheqid=".$chequeID;

					if ($result && $result_cheq) {

						echo json_encode(array('status' => '1','message'=> $obj_row_cheqSel["cheq_no"],'url'=> $output));

					} else {

						echo json_encode(array('status' => '0','message'=> $str_sql));
						
					}

				} else {

					$chequeID = '';
					$chequeNo = '';

					if($_POST["chequeDate"] != '') {
						$chequeDate = $_POST["chequeDate"];
					} else {
						$chequeDate = '0000-00-00';
					}

					$chequeBankid = '';

				}
			}
		}
	}

?>