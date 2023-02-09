<?php 
	
	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			if(isset($_POST["Redate"])) {
				$Redate = $_POST["Redate"];
			} else {
				$Redate = '0000-00-00';
			}
			// echo "Redate : " . $Redate . "<br>";			

			if(isset($_POST["outputTax"])) {
				$outputTax = $_POST["outputTax"];
			} else {
				$outputTax = '';
			}
			// echo "outputTax : " . $outputTax . "<br>";

			if(isset($_POST["ReNote"])) {
				$ReNote = $_POST["ReNote"];
			} else {
				$ReNote = '';
			}
			// echo "ReNote : " . $ReNote . "<br>";

			if(isset($_POST["compid"])) {
				$compid = $_POST["compid"];
			} else {
				$compid = '';
			}
			// echo "Comid : " . $compid . "<br>";

			if(isset($_POST["custid"])) {
				$custid = $_POST["custid"];
			} else {
				$custid = '';
			}
			// echo "custid : " . $custid . "<br>";
			
			if(isset($_POST["depid"])) {
				$depid = $_POST["depid"];
			} else {
				$depid = '';
			}
			// echo "Depid : " . $depid . "<br>";

			$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $depid ."'";
			$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
			$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

			$depcode = $obj_row_dep["dep_code"];

			$yearTH = date("Y")+543;

			if($compid == 'C001' || $compid == 'C014' || $compid == 'C004' || $compid == 'C016' || $compid == 'C008') {

				$year = substr(date("Y")+543,-2);
				$month = $_POST["SelMonth"];
				$str_sql_re = "SELECT MAX(re_no) AS last_id FROM receipt_tb WHERE re_month = '". $month ."' AND re_depid = '". $depid ."'";
				$obj_rs_re = mysqli_query($obj_con, $str_sql_re);
				$obj_row_re = mysqli_fetch_array($obj_rs_re);
				$maxId = substr($obj_row_re['last_id'], -4);
				if ($maxId== "") {
					$maxId = "0001";
				} else {
					$maxId = ($maxId + 1);
					$maxId = substr("0000".$maxId, -4);
				}
				$nextReno = $year.$month.$maxId;

			} else {



			}

			if(isset($_POST["bySelPay"])) {
				$bySelPay = $_POST["bySelPay"];
			} else {
				$bySelPay = '';
			}
			// echo "bySelPay : " . $bySelPay . "<br>";

			if(isset($_POST["chequeNo"])) {
				$chequeNo = $_POST["chequeNo"];
			} else {
				$chequeNo = '';
			}
			// echo "Cheque No : " . $chequeNo . "<br>";

			if(isset($_POST["SelBank"])) {
				$SelBank = $_POST["SelBank"];
			} else {
				$SelBank = '';
			}
			// echo "SelBank : " . $SelBank . "<br>";

			if(isset($_POST["SelBranch"])) {
				$SelBranch = $_POST["SelBranch"];
			} else {
				$SelBranch = '';
			}
			// echo "SelBranch : " . $SelBranch . "<br>";

			if(isset($_POST["chequeDate"])) {
				if($_POST["chequeDate"] != NULL) {
					$chequeDate = $_POST["chequeDate"];
				} else {
					$chequeDate = '0000-00-00';
				}
			}
			// echo "chequeDate : " . $chequeDate . "<br>";

			if(isset($_POST["useridCreate"])) {
				$useridCreate = $_POST["useridCreate"];
			} else {
				$useridCreate = '';
			}
			// echo "useridCreate : " . $useridCreate . "<br>";

			if(isset($_POST["CreateDate"])) {
				if($_POST["CreateDate"] == NULL) {
					$CreateDate = date('Y-m-d H:i:s');
				} else {
					$CreateDate = '0000-00-00 00:00:00';
				}
			}
			// echo "CreateDate : " . $CreateDate . "<br>";

			if(isset($_POST["useridEdit"])) {
				$useridEdit = $_POST["useridEdit"];
			} else {
				$useridEdit = '';
			}
			// echo "useridEdit : " . $useridEdit . "<br>";

			if(isset($_POST["EditDate"])) {
				if($_POST["EditDate"] == NULL) {
					$EditDate = '0000-00-00 00:00:00';
				} else {
					$EditDate = date('Y-m-d H:i:s');
				}
			}
			// echo "EditDate : " . $EditDate . "<br>";

			if(isset($_POST["invredesc1"])) {
				$invredesc1 = $_POST["invredesc1"];
			} else {
				$invredesc1 = '';
			}

			if(isset($_POST["invredesc2"])) {
				$invredesc2 = $_POST["invredesc2"];
			} else {
				$invredesc2 = '';
			}

			if(isset($_POST["invredesc3"])) {
				$invredesc3 = $_POST["invredesc3"];
			} else {
				$invredesc3 = '';
			}

			if(isset($_POST["invredesc4"])) {
				$invredesc4 = $_POST["invredesc4"];
			} else {
				$invredesc4 = '';
			}

			if(isset($_POST["invredesc5"])) {
				$invredesc5 = $_POST["invredesc5"];
			} else {
				$invredesc5 = '';
			}

			if(isset($_POST["invredesc6"])) {
				$invredesc6 = $_POST["invredesc6"];
			} else {
				$invredesc6 = '';
			}

			if(isset($_POST["invredesc7"])) {
				$invredesc7 = $_POST["invredesc7"];
			} else {
				$invredesc7 = '';
			}

			if(isset($_POST["invredesc8"])) {
				$invredesc8 = $_POST["invredesc8"];
			} else {
				$invredesc8 = '';
			}

			if(isset($_POST["amountHidden1"])) {
				$amount1 = $_POST["amountHidden1"];
			} else {
				$amount1 = '0.00';
			}

			if(isset($_POST["amountHidden2"])) {
				$amount2 = $_POST["amountHidden2"];
			} else {
				$amount2 = '0.00';
			}

			if(isset($_POST["amountHidden3"])) {
				$amount3 = $_POST["amountHidden3"];
			} else {
				$amount3 = '0.00';
			}

			if(isset($_POST["amountHidden4"])) {
				$amount4 = $_POST["amountHidden4"];
			} else {
				$amount4 = '0.00';
			}

			if(isset($_POST["amountHidden5"])) {
				$amount5 = $_POST["amountHidden5"];
			} else {
				$amount5 = '0.00';
			}

			if(isset($_POST["amountHidden6"])) {
				$amount6 = $_POST["amountHidden6"];
			} else {
				$amount6 = '0.00';
			}

			if(isset($_POST["amountHidden7"])) {
				$amount7 = $_POST["amountHidden7"];
			} else {
				$amount7 = '0.00';
			}

			if(isset($_POST["amountHidden8"])) {
				$amount8 = $_POST["amountHidden8"];
			} else {
				$amount8 = '0.00';
			}

			if(isset($_POST["subtotalHidden"])) {
				$subtotalHidden = $_POST["subtotalHidden"];
			} else {
				$subtotalHidden = '0.00';
			}

			if(isset($_POST["vatpercentHidden"])) {
				$vatpercentHidden = $_POST["vatpercentHidden"];
			} else {
				$vatpercentHidden = '0.00';
			}

			if(isset($_POST["vatHidden"])) {
				$vatHidden = $_POST["vatHidden"];
			} else {
				$vatHidden = '0.00';
			}

			if(isset($_POST["grandtotalHidden"])) {
				$grandtotalHidden = $_POST["grandtotalHidden"];
			} else {
				$grandtotalHidden = '0.00';
			}

			$str_sql = "INSERT INTO receipt_tb (re_bookno, re_no, re_date, re_refinvrcpt, re_compid, re_custid, re_depid, re_outputtax, re_description1, re_description2, re_description3, re_description4, re_description5, re_description6, re_description7, re_description8, re_sub_description1, re_sub_description2, re_sub_description3, re_sub_description4, re_sub_description5, re_sub_description6, re_sub_description7, re_sub_description8, re_sub_description9, re_amount1, re_amount2, re_amount3, re_amount4, re_amount5, re_amount6, re_amount7, re_amount8, re_subtotal, re_vatpercent, re_vat, re_differencevat, re_grandtotal, re_differencegrandtotal, re_note, re_typepay, re_chequeno, re_bankid, re_branchid, re_chequedate, re_year, re_month, re_file, re_stsid, re_userid_create, re_createdate, re_userid_edit, re_editdate) VALUES (";
			$str_sql .= "'" . $depcode . "',";
			$str_sql .= "'" . $nextReno . "',";
			$str_sql .= "'" . $Redate . "',";
			$str_sql .= "'1',";
			$str_sql .= "'" . $invReid . "',";
			$str_sql .= "'" . $compid  . "',";
			$str_sql .= "'" . $custid . "',";
			$str_sql .= "'" . $depid . "',";
			$str_sql .= "'" . $outputTax . "',";
			$str_sql .= "'" . $invredesc1 . "',";
			$str_sql .= "'" . $invredesc2 . "',";
			$str_sql .= "'" . $invredesc3 . "',";
			$str_sql .= "'" . $invredesc4 . "',";
			$str_sql .= "'" . $invredesc5 . "',";
			$str_sql .= "'" . $invredesc6 . "',";
			$str_sql .= "'" . $invredesc7 . "',";
			$str_sql .= "'" . $invredesc8 . "',";
			$str_sql .= "'" . $invresubdesc1 . "',";
			$str_sql .= "'" . $invresubdesc2 . "',";
			$str_sql .= "'" . $invresubdescHidden3 . "',";
			$str_sql .= "'" . $invresubdescHidden4 . "',";
			$str_sql .= "'" . $invresubdescHidden5 . "',";
			$str_sql .= "'" . $invresubdescHidden6 . "',";
			$str_sql .= "'" . $invresubdescHidden7 . "',";
			$str_sql .= "'" . $invresubdescHidden8 . "',";
			$str_sql .= "'" . $invresubdescHidden9 . "',";
			$str_sql .= "'" . $amount1 . "',";
			$str_sql .= "'" . $amount2 . "',";
			$str_sql .= "'" . $amount3 . "',";
			$str_sql .= "'" . $amount4 . "',";
			$str_sql .= "'" . $amount5 . "',";
			$str_sql .= "'" . $amount6 . "',";
			$str_sql .= "'" . $amount7 . "',";
			$str_sql .= "'" . $amount8 . "',";
			$str_sql .= "'" . $subtotalHidden . "',";
			$str_sql .= "'" . $vatpercentHidden . "',";
			$str_sql .= "'" . $vatHidden . "',";
			$str_sql .= "'" . $grandtotalHidden . "',";
			$str_sql .= "'" . $ReNote . "',";
			$str_sql .= "'" . $bySelPay . "',";
			$str_sql .= "'" . $chequeNo . "',";
			$str_sql .= "'" . $SelBank . "',";
			$str_sql .= "'" . $SelBranch . "',";
			$str_sql .= "'" . $chequeDate . "',";
			$str_sql .= "'" . $yearTH . "',";
			$str_sql .= "'" . $_POST["SelMonth"] . "',";
			$str_sql .= "'',";
			$str_sql .= "'" . $useridCreate . "',";
			$str_sql .= "'" . $CreateDate . "',";
			$str_sql .= "'" . $useridEdit . "',";
			$str_sql .= "'" . $EditDate . "')";
			$result = mysqli_query($obj_con, $str_sql);

			if($compid == 'C001') {
				$url = "receipt_preview_acs.php?cid=$compid&dep=$depid&m=$month&projid=$projid&invReid=$invReid";
			} else if($compid == 'C004') {
				$url = "receipt_preview_acsp.php?cid=$compid&dep=$depid&m=$month&projid=$projid&invReid=$invReid";
			} else if($compid == 'C008') {
				$url = "receipt_preview_ttni.php?cid=$compid&dep=$depid&m=$month&projid=$projid&invReid=$invReid";
			} else if($compid == 'C010') {
				$url = "receipt_preview_tppt.php?cid=$compid&dep=$depid&m=$month&projid=$projid&invReid=$invReid";
			} else if($compid == 'C011') {
				$url = "receipt_preview_rpec.php?cid=$compid&dep=$depid&m=$month&projid=$projid&invReid=$invReid";
			} else if($compid == 'C014') {
				$url = "receipt_preview_acsi.php?cid=$compid&dep=$depid&m=$month&projid=$projid&invReid=$invReid";
			} else if($compid == 'C015') {
				$url = "receipt_preview_acsd.php?cid=$compid&dep=$depid&m=$month&projid=$projid&invReid=$invReid";
			} else if($compid == 'C016') {
				$url = "receipt_preview_eptg.php?cid=$compid&dep=$depid&m=$month&projid=$projid&invReid=$invReid";
			}

			if ($result) {

				if($depcode != '') {
					$reno = $depcode . "/" . $nextReno;
				} else {
					$reno = $nextReno;
				}

				echo json_encode(array('status' => '1','book'=> $depcode,'reno'=> $reno,'compid'=> $compid,'depid'=> $depid));

			} else {

				echo json_encode(array('status' => '0','message'=> $str_sql));

			}

		}

	}

?>