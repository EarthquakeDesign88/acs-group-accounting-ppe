<?php
	
	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {
	
		if(!empty($_POST)) {

			include 'connect.php';

			if (isset($_POST["compid"])) {
				$cid = $_POST["compid"];
			} else {
				$cid = '';
			}

			if (isset($_POST["depid"])) {
				$depid = $_POST["depid"];
			} else {
				$depid = '';
			}

			if (isset($_POST["paymid"])) {
				$paymid = $_POST["paymid"];
			} else {
				$paymid = '';
			}

			if (isset($_POST["paymno"])) {
				$paymno = $_POST["paymno"];
			} else {
				$paymno = '';
			}

			if (isset($_POST["paymdate"])) {
				$paymdate = $_POST["paymdate"];
			} else {
				$paymdate = '0000-00-00';
			}

			if (isset($_POST["paympaydate"])) {
				if ($_POST["paympaydate"] == NULL) {
					$paympaydate = $_POST["paympaydate"];
				} else {
					$paympaydate = $_POST["paympaydate"];
				}
			}

			if (isset($_POST["compid"])) {
				$compid = $_POST["compid"];
			} else {
				$compid = '';
			}

			if (isset($_POST["payaid"])) {
				$payaid = $_POST["payaid"];
			} else {
				$payaid = '';
			}
			
			if(isset($_POST["paymbySel"])) {
				$paymbySel = $_POST["paymbySel"];
			} else {
				$paymbySel = '';
			}

			if ($_POST["paymbySel"] == '2') {

				if(isset($_POST["chequeNo"])) {
					$chequeNo = $_POST["chequeNo"];
				} else {
					$chequeNo = '';
				}

				if(isset($_POST["chequeDate"])) {
					if($_POST["chequeDate"] != NULL) {
						$chequeDate = $_POST["chequeDate"];
					} else {
						$chequeDate = '0000-00-00';
					}
				}

				if(isset($_POST["chequeBankid"])) {
					$chequeBankid = $_POST["chequeBankid"];
				} else {
					$chequeBankid = '';
				}			

				if(isset($_POST["chequeID"])) {
					if ($_POST["chequeID"] != '') {

						$chequeID = $_POST["chequeID"];

						$str_sql_cheq = "UPDATE cheque_tb SET
						cheq_no = '$chequeNo',
						cheq_date = '$chequeDate',
						cheq_bankid = '$chequeBankid'
						WHERE cheq_id = '$chequeID'";
						$result_cheq = mysqli_query($obj_con, $str_sql_cheq);

						$str_sql_paym = "UPDATE payment_tb SET
						paym_cheqid = '$chequeID'
						WHERE paym_id = '$paymid'";
						$result_paym = mysqli_query($obj_con, $str_sql_paym);

					} else {

						$chequeID = $_POST["chequeID"];

						$str_sql_cheq = "INSERT INTO cheque_tb (cheq_no, cheq_date, cheq_bankid) VALUES (";
						$str_sql_cheq .= "'" . $chequeNo . "',";
						$str_sql_cheq .= "'" . $chequeDate . "',";
						$str_sql_cheq .= "'" . $chequeBankid . "')";
						$result_cheq = mysqli_query($obj_con, $str_sql_cheq);

						$str_sql_cheqSel = "SELECT * FROM cheque_tb WHERE cheq_no = '". $chequeNo ."'";
						$obj_rs_cheqSel = mysqli_query($obj_con, $str_sql_cheqSel);
						$obj_row_cheqSel = mysqli_fetch_array($obj_rs_cheqSel);

						$str_sql_paym = "UPDATE payment_tb SET
						paym_cheqid = '$chequeID'
						WHERE paym_id = '$paymid'";
						$result_paym = mysqli_query($obj_con, $str_sql_paym);

					}
				}

			} else {

				$chequeID = '';
				$chequeNo = '';

				if($_POST["chequeDate"] != NULL) {
					$chequeDate = $_POST["chequeDate"];
				} else {
					$chequeDate = '0000-00-00';
				}

				$chequeBankid = '';

			}

			if(isset($_POST["paymrev"])) {
				$paymrev = $_POST["paymrev"] + 1;
			} else {
				$paymrev = '';
			}

			if(isset($_POST["paymuseridcreate"])) {
				$paymuseridcreate = $_POST["paymuseridcreate"];
			} else {
				$paymuseridcreate = '';
			}

			if(isset($_POST["paymcreatedate"])) {
				$paymcreatedate = $_POST["paymcreatedate"];
			} else {
				$paymcreatedate = '0000-00-00 00:00:00';
			}

			if(isset($_POST["paymuseridedit"])) {
				$paymuseridedit = $_POST["paymuseridedit"];
			} else {
				$paymuseridedit = '';
			}

			if(isset($_POST["paymeditdate"])) {
				if ($_POST["paymeditdate"] == '') {
					$paymeditdate = date('Y-m-d H:i:s');
				} else {
					$paymeditdate = $_POST["paymeditdate"];
				}
			}

			// echo $paymeditdate;

			if(isset($_POST["paympayeename"])) {
				$paympayeename = $_POST["paympayeename"];
			} else {
				$paympayeename = '';
			}

			if(isset($_POST["paympayeedate"])) {
				if($_POST["paympayeedate"] != NULL) {
					$paympayeedate = $_POST["paympayeedate"];
				} else {
					$paympayeedate = '0000-00-00';
				}
			}

			if(isset($_POST["paymfile"])) {
				// payment/AD/2564/06/AD6406003.pdf
				$paymfile = "payment/AD/2564/06/" . $_POST["paymno"] . "-" . $_POST["paymrev"] . ".pdf";
			} else {
				$paymfile = '';
			}

			if(isset($_POST["paymyear"])) {
				$paymyear = date('Y') + 543;
			} else {
				$paymyear = '';
			}

			if(isset($_POST["paymmonth"])) {
				$paymmonth = date('m');
			} else {
				$paymmonth = '';
			}

			if(isset($_POST["paymchkRptpaym"])) {
				$paymchkRptpaym = $_POST["paymchkRptpaym"];
			} else {
				$paymchkRptpaym = '';
			}

			if(isset($_POST["paymNote"])) {
				$paymNote = $_POST["paymNote"];
			} else {
				$paymNote = '';
			}

			if(isset($_POST["countChk"])) {
				$countChk = $_POST["countChk"];
			} else {
				$countChk = '';
			}

			if(isset($_POST["paymstatusid"])) {
				$paymstatusid = $_POST["paymstatusid"];
			} else {
				$paymstatusid = '';
			}

			$str_sql = "UPDATE payment_tb SET
			paym_paydate = '$paympaydate',
			paym_rev = '$paymrev',
			paym_typepay = '$paymbySel',
			paym_cheqid = '$chequeID',
			paym_userid_edit = '$paymuseridedit',
			paym_editdate = '$paymeditdate',
			paym_note = '$paymNote'
			WHERE paym_id = '$paymid'";
			$result = mysqli_query($obj_con, $str_sql);

			// echo $str_sql . "<br>";

			$output = "";
			$output .= "payment_preview.php?cid=" . $compid . "&dep=" . $depid . "&";
			for ($i = 1; $i <= $countChk; $i++) {
				$invid = "invid" . $i;
				$invid = $_POST["$invid"];
				
				$output .= "invid" . $i . "=" . $invid . "&";
			}
			$output .= "countChk=" . $countChk . "&paymid=" . $paymid . "&paymrev=" . $paymrev;

			if ($result) {
				echo json_encode(array('status' => '1','message'=> $paymno,'compid'=> $compid,'dep'=> $depid,'url'=> $output));
			} else {
				echo json_encode(array('status' => '0','message'=> $str_sql));
			}

			mysqli_close($obj_con);

		}

	}

?>