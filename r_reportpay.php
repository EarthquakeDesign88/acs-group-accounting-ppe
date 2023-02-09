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

			$code = "REPP";
			$year = substr(date("Y")+543,-2);
			$str_sql_repdno = "SELECT MAX(repp_id) AS last_id FROM reportpay_tb1";
			$obj_rs_repdno = mysqli_query($obj_con, $str_sql_repdno);
			$obj_row_repdno = mysqli_fetch_array($obj_rs_repdno);
			$maxId = substr($obj_row_repdno['last_id'], -4);
			if ($maxId== "") {
				$maxId = "0001";
			} else {
				$maxId = ($maxId + 1);
				$maxId = substr("0000".$maxId, -4);
			}
			$nextreppno = $code.$year.$maxId;

			if(isset($_POST["repdate"])) {
				if ($_POST["repdate"] != NULL) {
					$repdate = $_POST["repdate"];
				} else {
					$repdate = '0000-00-00';
				}
			}

			if(isset($_POST["reppdno"])) {
				$reppdno = $_POST["reppdno"];
			} else {
				$reppdno = '';
			}

			if(isset($_POST["txtsummarize"])) {
				$summ = substr($_POST["txtsummarize"], 0,4);
				$yearsumm = $summ +543;
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
				$bal = substr($_POST["txtbalance"], 0,4);
				$yearbal = $bal +543;
				$datebal = substr($_POST["txtbalance"], 8, 2);
				$monthbal = substr($_POST["txtbalance"], 5, 2);

				// echo "date : " . $datebal;
				// echo "month : " . $monthbal;
				// echo "year : " . $yearbal;
				$txtbalance = "ยอด ณ " . $datebal . "/" . $monthbal . "/" . $yearbal;
				// echo $txtbalance;
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
				if ($_POST["editDate"] != NULL) {
					$editDate = date('Y-m-d H:i:s');
				} else {
					$editDate = $_POST["editDate"];
				}
			}

			$reppaydate = "0000-00-00";
			$reppyear = date('Y')+543;
			$reppmonth = date('m');

			$reppfile = '';

			$str_sql = "INSERT INTO reportpay_tb1 (repp_id, repp_date, repp_paydate, repp_desc_summarize, repp_date_summarize, repp_desc_balance, repp_date_balance, repp_total_summarize, repp_total_balance, repp_file, repp_year, repp_month, repp_userid_create, repp_createdate, repp_userid_edit, repp_editdate) VALUES (";
			$str_sql .= "'" . $nextreppno . "',";
			$str_sql .= "'" . $repdate . "',";
			$str_sql .= "'" . $reppaydate . "',";
			$str_sql .= "'" . $txtsummarize . "',";
			$str_sql .= "'" . $_POST["txtsummarize"] . "',";
			$str_sql .= "'" . $txtbalance . "',";
			$str_sql .= "'" . $_POST["txtbalance"] . "',";
			$str_sql .= "'" . $txtTotalsummarizeHidden . "',";
			$str_sql .= "'" . $txtTotalbalanceHidden . "',";
			$str_sql .= "'" . $reppfile . "',";
			$str_sql .= "'" . $reppyear . "',";
			$str_sql .= "'" . $reppmonth . "',";
			$str_sql .= "'" . $useridCreate . "',";
			$str_sql .= "'" . $createDate . "',";
			$str_sql .= "'" . $useridEdit . "',";
			$str_sql .= "'" . $editDate . "')";
			$result = mysqli_query($obj_con, $str_sql);


			// $str_sql_selrepp = "SELECT MAX(rep_id) AS rep_id FROM reportpay_tb";
			// $obj_rs_selrepp = mysqli_query($obj_con, $str_sql_selrepp);
			// $obj_row_selrepp = mysqli_fetch_array($obj_rs_selrepp);
			// $repid = $obj_row_selrepp["rep_id"];


			$countPlus = 0;
			$str_sql_plus = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 1 AND reppd_no = '" . $reppdno . "'";
			$obj_rs_plus = mysqli_query($obj_con, $str_sql_plus);
			while ($obj_row_plus = mysqli_fetch_array($obj_rs_plus)) {
				$countPlus++;
			}
			if ($countPlus != '0') {
				for ($i = 1; $i <= $countPlus; $i++) {

					$repdidPlus = "repdidPlus" . $i;
					$repdidPlus = $_POST["$repdidPlus"];

					$str_sql_UpPlus = "UPDATE reportpay_desc_tb1 SET 
					reppd_reppid = '$nextreppno'
					WHERE reppd_id = '$repdidPlus'";
					$result_UpPlus = mysqli_query($obj_con, $str_sql_UpPlus);

				}
			}


			$countDis = 0;
			$str_sql_dis = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 2 AND reppd_no = '" . $reppdno . "'";
			$obj_rs_dis = mysqli_query($obj_con, $str_sql_dis);
			while ($obj_row_dis = mysqli_fetch_array($obj_rs_dis)) {
				$countDis++;
			}
			if ($countDis != '0') {
				for ($i = 1; $i <= $countDis; $i++) {

					$repdidDis = "repdidDis" . $i;
					$repdidDis = $_POST["$repdidDis"];

					$str_sql_UpDis = "UPDATE reportpay_desc_tb1 SET 
					reppd_reppid = '$nextreppno'
					WHERE reppd_id = '$repdidDis'";
					$result_UpDis = mysqli_query($obj_con, $str_sql_UpDis);

				}
			}

			if($result && $result_UpPlus && $result_UpDis) {
				echo json_encode(array('status'=> 1,'reppid'=> $nextreppno,'compid'=> $compid,'depid'=> $depid,'reppdno'=> $reppdno));
			} else {
				echo json_encode(array('status'=> 0,'messageResult'=> $str_sql,'messageUpPlus'=> $str_sql_UpPlus,'messageUpDis'=> $str_sql_UpDis));
			}

		}

	}

?>