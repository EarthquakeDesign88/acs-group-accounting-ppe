<?php
	
	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

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

			$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $depid ."'";
			$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
			$obj_row_dep = mysqli_fetch_array($obj_rs_dep);
			$depname = $obj_row_dep["dep_name"];

			$year = substr(date("Y")+543, -2);
			$month = date("m");
			$str_sql_paym = "SELECT MAX(paym_no) as last_id FROM payment_tb WHERE paym_depid = '". $depid ."' AND paym_month = '". $month ."'";
			$obj_rs_paym = mysqli_query($obj_con, $str_sql_paym);
			$obj_row_paym = mysqli_fetch_array($obj_rs_paym);
			$maxPId = substr($obj_row_paym['last_id'], -3);
			if ($maxPId == "") {
				$maxPId = "001";
			} else {
				$maxPId = ($maxPId + 1);
				$maxPId = substr("000".$maxPId, -3);
			}
			$nextpaymno = $depname.$year.$month.$maxPId;

			if (isset($_POST["paymdate"])) {
				$paymdate = $_POST["paymdate"];
			} else {
				$paymdate = '0000-00-00';
			}

			if (isset($_POST["paympaydate"])) {
				if ($_POST["paympaydate"] == NULL) {
					$paympaydate = "0000-00-00";
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
			
			if(isset($_POST["paymby"])) {
				$paymby = $_POST["paymby"];
			} else {
				$paymby = '';
			}

			if ($paymby == 2) {

				if(isset($_POST["chequeNo"])) {
					$chequeNo = $_POST["chequeNo"];
				} else {
					$chequeNo = '';
				}

				if(isset($_POST["chequeDate"])) {
					if($_POST["chequeDate"] != '') {
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

				$str_sql_cheq = "INSERT INTO cheque_tb (cheq_no, cheq_date, cheq_bankid) VALUES (";
				$str_sql_cheq .= "'" . $chequeNo . "',";
				$str_sql_cheq .= "'" . $chequeDate . "',";
				$str_sql_cheq .= "'" . $chequeBankid . "')";
				$result_cheq = mysqli_query($obj_con, $str_sql_cheq);

				$str_sql_cheqSel = "SELECT * FROM cheque_tb WHERE cheq_no = '". $chequeNo ."'";
				$obj_rs_cheqSel = mysqli_query($obj_con, $str_sql_cheqSel);
				$obj_row_cheqSel = mysqli_fetch_array($obj_rs_cheqSel);

				$chequeID = $obj_row_cheqSel["cheq_id"];

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

			if(isset($_POST["paymrev"])) {
				$paymrev = $_POST["paymrev"];
			} else {
				$paymrev = '';
			}

			if(isset($_POST["paymuseridcreate"])) {
				$paymuseridcreate = $_POST["paymuseridcreate"];
			} else {
				$paymuseridcreate = '';
			}

			if(isset($_POST["paymcreatedate"])) {
				if ($_POST["paymcreatedate"] == NULL) {
					$paymcreatedate = date('Y-m-d H:i:s');
				} else {
					$paymcreatedate = $_POST["paymcreatedate"];
				}
			}

			if(isset($_POST["paymuseridedit"])) {
				$paymuseridedit = $_POST["paymuseridedit"];
			} else {
				$paymuseridedit = '';
			}

			if(isset($_POST["paymeditdate"])) {
				if ($_POST["paymeditdate"] != NULL) {
					$paymeditdate = date('Y-m-d H:i:s');
				} else {
					$paymeditdate = "0000-00-00";
				}
			}

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
				$paymfile = $_POST["paymfile"];
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

			$paymtaxcid = "";
			$paymstsTaxcer = "";
			$paymNostsTaxcer = "";

			$paymstatusRepid = "0";
			$paymrepid = "";

			$str_sql = "INSERT INTO payment_tb (paym_no, paym_date, paym_paydate, paym_rev, paym_depid, paym_payeename, paym_payeedate, paym_file, paym_year, paym_month, paym_typepay, paym_cheqid, paym_chkRptpaym, paym_note, paym_taxcid, paym_statusTaxcer, paym_NostatusTaxcer, paym_stsid, paym_statusRepid, paym_reppid, paym_userid_create, paym_createdate, paym_userid_edit, paym_editdate) VALUES (";
			$str_sql .= "'" . $nextpaymno . "',";
			$str_sql .= "'" . $paymdate . "',";
			$str_sql .= "'" . $paympaydate . "',";
			$str_sql .= "'" . $paymrev . "',";
			$str_sql .= "'" . $depid . "',";
			$str_sql .= "'" . $paympayeename . "',";
			$str_sql .= "'" . $paympayeedate . "',";
			$str_sql .= "'" . $paymfile . "',";
			$str_sql .= "'" . $paymyear . "',";
			$str_sql .= "'" . $paymmonth . "',";
			$str_sql .= "'" . $paymby . "',";
			$str_sql .= "'" . $chequeID . "',";
			$str_sql .= "'" . $paymchkRptpaym . "',";
			$str_sql .= "'" . $paymNote . "',";
			$str_sql .= "'" . $paymtaxcid . "',";
			$str_sql .= "'" . $paymstsTaxcer . "',";
			$str_sql .= "'" . $paymNostsTaxcer . "',";
			$str_sql .= "'" . $paymstatusid . "',";
			$str_sql .= "'" . $paymstatusRepid . "',";
			$str_sql .= "'" . $paymrepid . "',";
			$str_sql .= "'" . $paymuseridcreate . "',";
			$str_sql .= "'" . $paymcreatedate . "',";
			$str_sql .= "'" . $paymuseridedit . "',";
			$str_sql .= "'" . $paymeditdate . "')";
			$result = mysqli_query($obj_con, $str_sql);

			// echo $str_sql . "<br>";

			$str_sql_selpaym = "SELECT * FROM payment_tb WHERE paym_no = '".$nextpaymno."'";
			$obj_rs_selpaym = mysqli_query($obj_con, $str_sql_selpaym);
			$obj_row_selpaym = mysqli_fetch_array($obj_rs_selpaym);

			$paymid = $obj_row_selpaym["paym_id"];

			$str_sql_niv = "SELECT MAX(inv_NostatusPaym) as last_id FROM invoice_tb WHERE inv_statusPaym = '1'";
			$obj_rs_niv = mysqli_query($obj_con, $str_sql_niv);
			$obj_row_niv = mysqli_fetch_array($obj_rs_niv);
			$maxStsId = substr($obj_row_niv['last_id'], -5);
			if ($maxStsId == "") {
				$maxStsId = "00001";
			} else {
				$maxStsId = ($maxStsId + 1);
				$maxStsId = substr("00000".$maxStsId, -5);
			}
			$nextstspaym = $year.$maxStsId;

			$output = "";
			$output .= "payment_preview.php?cid=" . $cid . "&dep=" . $depid . "&";
			for ($i = 1; $i <= $countChk; $i++) {
				$invid = "invid" . $i;
				$invid = $_POST["$invid"];
				// echo $invid;

				$str_sql_iv = "UPDATE invoice_tb SET
				inv_paymid = '$paymid',
				inv_NostatusPaym = '$nextstspaym'
				WHERE inv_id = '". $invid ."'";
				$obj_rs_iv = mysqli_query($obj_con, $str_sql_iv);

				$output .= "invid" . $i . "=" . $invid . "&";
			}
			$output .= "countChk=" . $countChk . "&paymid=" . $paymid . "&paymrev=" . $paymrev;

			if ($result) {

				echo json_encode(array('status' => '1','message'=> $nextpaymno,'url'=> $output));

			} else {

				echo json_encode(array('status' => '0','message'=> $str_sql));
				
			}

			mysqli_close($obj_con);

		}

	}

?>