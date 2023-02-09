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

			if(isset($_POST["reppdno"])) {
				$reppdno = $_POST["reppdno"];
			} else {
				$reppdno = '';
			}

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

				$txttotalPlus = 'txttotalPlusHidden'.$i;
				if(isset($_POST["$txttotalPlus"])){
					$txttotalPlus = $_POST["$txttotalPlus"];
				} else {
					$txttotalPlus = '';
				}				

				if($txtdescPlus != '') {
					$str_sql_plus = "INSERT INTO reportpay_desc_tb1 (reppd_no, reppd_type, reppd_description, reppd_amount, reppd_reppid) VALUES (";
					$str_sql_plus .= "'" . $reppdno . "',";
					$str_sql_plus .= "'" . $txtPlus . "',";
					$str_sql_plus .= "'" . $txtdescPlus . "',";
					$str_sql_plus .= "'" . $txttotalPlus . "',";
					$str_sql_plus .= "'" . $reppid . "')";
					$result_plus = mysqli_query($obj_con, $str_sql_plus);
				}

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

				$txttotalDis = 'txttotalDisHidden'.$i;
				if(isset($_POST["$txttotalDis"])){
					$txttotalDis = $_POST["$txttotalDis"];
				} else {
					$txttotalDis = '';
				}

				if ($txtdescDis != "") {
					$str_sql_dis = "INSERT INTO reportpay_desc_tb1 (reppd_no, reppd_type, reppd_description, reppd_amount, reppd_reppid) VALUES (";
					$str_sql_dis .= "'" . $reppdno . "',";
					$str_sql_dis .= "'" . $txtDis . "',";
					$str_sql_dis .= "'" . $txtdescDis . "',";
					$str_sql_dis .= "'" . $txttotalDis . "',";
					$str_sql_dis .= "'" . $reppid . "')";
					$result_dis = mysqli_query($obj_con, $str_sql_dis);
				}

			}


			if(isset($_POST["reppdate"])) {
				$reppdate = $_POST["reppdate"];
			} else {
				$reppdate = '0000-00-00';
			}

			if(isset($_POST["repppaydate"])) {
				$repppaydate = $_POST["repppaydate"];
			} else {
				$repppaydate = '0000-00-00';
			}

			if(isset($_POST["reppdno"])) {
				$reppdno = $_POST["reppdno"];
			} else {
				$reppdno = '';
			}

			if(isset($_POST["txtsummarize"])) {
				$summ = substr($_POST["txtsummarize"], 0, 4);
				$yearsumm = $summ + 543;
				$datesumm = substr($_POST["txtsummarize"], 8, 2);
				$monthsumm = substr($_POST["txtsummarize"], 5, 2);

				$txtsummarize = "สรุปยอด ณ " . $datesumm . "/" . $monthsumm . "/" . $yearsumm;
			} else {
				$txtsummarize = '';
			}

			if(isset($_POST["txtTotalsummarizeHidden"])) {
				$txtTotalsummarizeHidden = $_POST["txtTotalsummarizeHidden"];
			} else {
				$txtTotalsummarizeHidden = '0.00';
			}

			if(isset($_POST["txtbalance"])) {
				$bal = substr($_POST["txtbalance"], 0, 4);
				$yearbal = $bal + 543;
				$datebal = substr($_POST["txtbalance"], 8, 2);
				$monthbal = substr($_POST["txtbalance"], 5, 2);

				$txtbalance = "ยอด ณ " . $datebal . "/" . $monthbal . "/" . $yearbal;
			} else {
				$txtbalance = '';
			}

			if(isset($_POST["txtTotalbalanceHidden"])) {
				$txtTotalbalanceHidden = $_POST["txtTotalbalanceHidden"];
			} else {
				$txtTotalbalanceHidden = '0.00';
			}

			$paymid = '';

			if(isset($_POST["useridCreate"])) {
				$useridCreate = $_POST["useridCreate"];
			} else {
				$useridCreate = '';
			}

			if(isset($_POST["createDate"])) {
				if ($_POST["createDate"] == NULL) {
					$createDate = date('Y-m-d H:i:s');
				} else {
					$createDate = $_POST["createDate"];
				}
			}

			if(isset($_POST["useridEdit"])) {
				$useridEdit = $_POST["useridEdit"];
			} else {
				$useridEdit = '';
			}

			if(isset($_POST["editDate"])) {
				if ($_POST["editDate"] == NULL) {
					$editDate = date('Y-m-d H:i:s');
				} else {
					$editDate = $_POST["editDate"];
				}
			}

			$reppyear = $_POST["reppyear"];
			$reppmonth = $_POST["reppmonth"];
			$reppfile = $_POST["reppfile"];

			$str_sql = "UPDATE reportpay_tb1 SET 
			repp_id = '$reppid',
			repp_date = '$reppdate',
			repp_paydate = '$repppaydate',
			repp_desc_summarize = '$txtsummarize',
			repp_date_summarize = '".$_POST["txtsummarize"]."',
			repp_desc_balance = '$txtbalance',
			repp_date_balance = '".$_POST["txtbalance"]."',
			repp_total_summarize = '$txtTotalsummarizeHidden',
			repp_total_balance = '$txtTotalbalanceHidden',
			repp_file = '$reppfile',
			repp_year = '$reppyear',
			repp_month = '$reppmonth',
			repp_userid_create = '$useridCreate',
			repp_createdate = '$createDate',
			repp_userid_edit = '$useridEdit',
			repp_editdate = '$editDate'
			WHERE repp_id = '$reppid'";
			$obj_rs = mysqli_query($obj_con, $str_sql);

			// echo $str_sql;

			if ($obj_rs) {
				echo json_encode(array('status' => '1','reppid'=> $reppid,'compid'=> $compid,'depid'=> $depid,'reppdno'=> $reppdno));
			} else {
				echo json_encode(array('status'=> '0','messageResult'=> $str_sql,'messageUpPlus'=> $str_sql_plus,'messageUpDis'=> $str_sql_dis));
			}

			mysqli_close($obj_con);

		}

	}

?>