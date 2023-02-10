<?php
			if(isset($_POST["invRedate"])) {
				$invRedate = $_POST["invRedate"];
			} else {
				$invRedate = '';
			}
			
			if(isset($_POST["custid"])) {
				$custid = $_POST["custid"];
			} else {
				$custid = '';
			}

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
			// echo "Desc 6 : " . $invredesc6 . "<br>";

			if(isset($_POST["invredesc7"])) {
				$invredesc7 = $_POST["invredesc7"];
			} else {
				$invredesc7 = '';
			}
			// echo "Desc 7 : " . $invredesc7 . "<br>";

			if(isset($_POST["invredesc8"])) {
				$invredesc8 = $_POST["invredesc8"];
			} else {
				$invredesc8 = '';
			}
	
			
			if(isset($_POST["invredesc9"])) {
				$invredesc9 = $_POST["invredesc9"];
			} else {
				$invredesc9 = '';
			}

			if(isset($_POST["invredesc10"])) {
				$invredesc10 = $_POST["invredesc10"];
			} else {
				$invredesc10 = '';
			}

			if(isset($_POST["invredesc11"])) {
				$invredesc11 = $_POST["invredesc11"];
			} else {
				$invredesc11 = '';
			}

			if(isset($_POST["amountHidden1"])) {
				$amountHidden1 = $_POST["amountHidden1"];
			} else {
				$amountHidden1 = '0.00';
			}

			if(isset($_POST["amountHidden2"])) {
				$amountHidden2 = $_POST["amountHidden2"];
			} else {
				$amountHidden2 = '0.00';
			}

			if(isset($_POST["amountHidden3"])) {
				$amountHidden3 = $_POST["amountHidden3"];
			} else {
				$amountHidden3 = '0.00';
			}
			if(isset($_POST["amountHidden4"])) {
				$amountHidden4 = $_POST["amountHidden4"];
			} else {
				$amountHidden4 = '0.00';
			}

			if(isset($_POST["amountHidden5"])) {
				$amountHidden5 = $_POST["amountHidden5"];
			} else {
				$amountHidden5 = '0.00';
			}

			if(isset($_POST["amountHidden6"])) {
				$amountHidden6 = $_POST["amountHidden6"];
			} else {
				$amountHidden6 = '0.00';
			}

			if(isset($_POST["amountHidden7"])) {
				$amountHidden7 = $_POST["amountHidden7"];
			} else {
				$amountHidden7 = '0.00';
			}

			if(isset($_POST["amountHidden8"])) {
				$amountHidden8 = $_POST["amountHidden8"];
			} else {
				$amountHidden8 = '0.00';
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
				$vatHidden = $_POST["vatHidden"] - $_POST["DiffVatHidden"];
			} else {
				$vatHidden = '0.00';
			}

			if(isset($_POST["DiffVatHidden"])) {
				if($_POST["DiffVatHidden"] == '0') {
					$DiffVatHidden = '0.00';
				} else {
					$DiffVatHidden = $_POST["DiffVatHidden"];
				}
			} else {
				$DiffVatHidden = '0.00';
			}

			if(isset($_POST["grandtotalHidden"])) {
				$grandtotalHidden = $_POST["grandtotalHidden"] - $_POST["DiffVatHidden"] - $_POST["DiffGrandHidden"];
			} else {
				$grandtotalHidden = '0.00';
			}

			$balanceHidden = $subtotalHidden;

			if(isset($_POST["DiffGrandHidden"])) {
				if($_POST["DiffGrandHidden"] == '0') {
					$DiffGrandHidden = '0.00';
				} else {
					$DiffGrandHidden = $_POST["DiffGrandHidden"];
				}
			} else {
				$DiffGrandHidden = '0.00';
			}

			if(isset($_POST["stsid"])) {
				$stsid = $_POST["stsid"];
			} else {
				$stsid = '';
			}

			if(isset($_POST["iruseridCreate"])) {
				$iruseridCreate = $_POST["iruseridCreate"];
			} else {
				$iruseridCreate = '';
			}

			if(isset($_POST["irCreateDate"])) {
				if($_POST["irCreateDate"] == NULL) {
					$irCreateDate = date('Y-m-d H:i:s');
				} else {
					$irCreateDate = $_POST["irCreateDate"];
				}
			}

			if(isset($_POST["iruseridEdit"])) {
				$iruseridEdit = $_POST["iruseridEdit"];
			} else {
				$iruseridEdit = '';
			}

			if(isset($_POST["irEditDate"])) {
				if($_POST["irEditDate"] != NULL) {
					$irEditDate = date('Y-m-d H:i:s');
				} else {
					$irEditDate = '0000-00-00 00:00:00';
				}
			}

			if(isset($_POST["invReprojid"])) {
				$invReprojid = $_POST["invReprojid"];
			} else {
				$invReprojid = '';
			}

			if(isset($_POST["invRelesson"])) {
				$invRelesson = $_POST["invRelesson"];
			} else {
				$invRelesson = '';
			}

			$invrcptFile = '';
			$invrcptyear = date("Y") + 543;
			$invRemonth = date("m");

			if(isset($_POST["SelinvrcptYear"])) {
				$SelinvrcptYear = $_POST["SelinvrcptYear"];
			} else {
				$SelinvrcptYear = '';
			}

			// if(isset($_POST["irDid"])) {
			// 	$irDid = $_POST["irDid"];
			// } else {
			// 	$irDid = '';
			// }

			if(isset($_POST["invresubdesc1"])) {
				$invresubdesc1 = $_POST["invresubdesc1"];
			} else {
				$invresubdesc1 = '';
			}

			if(isset($_POST["invresubdesc2"])) {
				$invresubdesc2 = $_POST["invresubdesc2"];
			} else {
				$invresubdesc2 = '';
			}

			if(isset($_POST["invresubdescHidden3"])) {
				$invresubdescHidden3 = $_POST["invresubdescHidden3"];
			} else {
				$invresubdescHidden3 = '0.00';
			}

			if(isset($_POST["invresubdescHidden4"])) {
				$invresubdescHidden4 = $_POST["invresubdescHidden4"];
			} else {
				$invresubdescHidden4 = '0.00';
			}

			if(isset($_POST["invresubdescHidden5"])) {
				$invresubdescHidden5 = $_POST["invresubdescHidden5"];
			} else {
				$invresubdescHidden5 = '0.00';
			}

			if(isset($_POST["invresubdescHidden6"])) {
				$invresubdescHidden6 = $_POST["invresubdescHidden6"];
			} else {
				$invresubdescHidden6 = '0.00';
			}

			if(isset($_POST["invresubdescHidden7"])) {
				$invresubdescHidden7 = $_POST["invresubdescHidden7"];
			} else {
				$invresubdescHidden7 = '0.00';
			}

			if(isset($_POST["invresubdescHidden8"])) {
				$invresubdescHidden8 = $_POST["invresubdescHidden8"];
			} else {
				$invresubdescHidden8 = '0.00';
			}

			if(isset($_POST["invresubdescHidden9"])) {
				$invresubdescHidden9 = $_POST["invresubdescHidden9"];
			} else {
				$invresubdescHidden9 = '0.00';
			}

			if(isset($_POST["invrcptduedate"])) {
				if($_POST["invrcptduedate"] == '') {
					$invrcptduedate = '0000-00-00';
				} else {
					$invrcptduedate = $_POST["invrcptduedate"];
				}
			} else {
				$invrcptduedate = '0000-00-00';
			}

			$_sql = "SELECT * FROM invoice_rcpt_desc_tb WHERE invrcptD_id = '".$_POST['irDid']."'";
			$_query = mysqli_query($obj_con,$_sql);
			$_obj = mysqli_fetch_assoc($_query);
			
			$_irID = $_obj['irDid'];
			
			$str_sql = "INSERT INTO invoice_rcpt_tb (invrcpt_book, invrcpt_no, invrcpt_date, invrcpt_compid, invrcpt_custid, invrcpt_depid, invrcpt_projid, invrcpt_description1, invrcpt_description2, invrcpt_description3, invrcpt_description4, invrcpt_description5, invrcpt_description6, invrcpt_description7, invrcpt_description8, invrcpt_description9, invrcpt_description10, invrcpt_description11, invrcpt_sub_description1, invrcpt_sub_description2, invrcpt_sub_description3, invrcpt_sub_description4, invrcpt_sub_description5, invrcpt_sub_description6, invrcpt_sub_description7, invrcpt_sub_description8, invrcpt_sub_description9, invrcpt_amount1, invrcpt_amount2, invrcpt_amount3, invrcpt_amount4, invrcpt_amount5, invrcpt_amount6, invrcpt_amount7, invrcpt_amount8, invrcpt_subtotal, invrcpt_vatpercent, invrcpt_vat, invrcpt_differencevat, invrcpt_grandtotal, invrcpt_differencegrandtotal, invrcpt_balancetotal, invrcpt_duedate, invrcpt_year, invrcpt_month, invrcpt_file, invrcpt_stsid, invrcpt_userid_create, invrcpt_createdate, invrcpt_userid_edit, invrcpt_editdate, invrcpt_reid, invrcpt_irDid) VALUES (";
			$str_sql .= "'" . $depcode . "',";
			$str_sql .= "'" . $nextinvReno . "',";
			$str_sql .= "'" . $invRedate . "',";
			$str_sql .= "'" . $compid . "',";
			$str_sql .= "'" . $custid . "',";
			$str_sql .= "'" . $depid . "',";
			$str_sql .= "'" . $invReprojid . "',";
			$str_sql .= "'" . $invredesc1 . "',";
			$str_sql .= "'" . $invredesc2 . "',";
			$str_sql .= "'" . $invredesc3 . "',";
			$str_sql .= "'" . $invredesc4 . "',";
			$str_sql .= "'" . $invredesc5 . "',";
			$str_sql .= "'" . $invredesc6 . "',";
			$str_sql .= "'" . $invredesc7 . "',";
			$str_sql .= "'" . $invredesc8 . "',";
			$str_sql .= "'" . $invredesc9 . "',";
			$str_sql .= "'" . $invredesc10 . "',";
			$str_sql .= "'" . $invredesc11 . "',";
			$str_sql .= "'" . $invresubdesc1 . "',";
			$str_sql .= "'" . $invresubdesc2 . "',";
			$str_sql .= "'" . $invresubdescHidden3 . "',";
			$str_sql .= "'" . $invresubdescHidden4 . "',";
			$str_sql .= "'" . $invresubdescHidden5 . "',";
			$str_sql .= "'" . $invresubdescHidden6 . "',";
			$str_sql .= "'" . $invresubdescHidden7 . "',";
			$str_sql .= "'" . $invresubdescHidden8 . "',";
			$str_sql .= "'" . $invresubdescHidden9 . "',";
			$str_sql .= "'" . $amountHidden1 . "',";
			$str_sql .= "'" . $amountHidden2 . "',";
			$str_sql .= "'" . $amountHidden3 . "',";
			$str_sql .= "'" . $amountHidden4 . "',";
			$str_sql .= "'" . $amountHidden5 . "',";
			$str_sql .= "'" . $amountHidden6 . "',";
			$str_sql .= "'" . $amountHidden7 . "',";
			$str_sql .= "'" . $amountHidden8 . "',";
			$str_sql .= "'" . $subtotalHidden . "',";
			$str_sql .= "'" . $vatpercentHidden . "',";
			$str_sql .= "'" . $vatHidden . "',";
			$str_sql .= "'" . $DiffVatHidden . "',";
			$str_sql .= "'" . $grandtotalHidden . "',";
			$str_sql .= "'" . $DiffGrandHidden . "',";
			$str_sql .= "'" . $balanceHidden . "',";
			$str_sql .= "'" . $invrcptduedate . "',";
			$str_sql .= "'" . $SelinvrcptYear . "',";
			$str_sql .= "'" . $month . "',";
			$str_sql .= "'" . $invrcptFile . "',";
			$str_sql .= "'" . $stsid . "',";
			$str_sql .= "'" . $iruseridCreate . "',";
			$str_sql .= "'" . $irCreateDate . "',";
			$str_sql .= "'" . $iruseridEdit . "',";
			$str_sql .= "'" . $irEditDate . "',";
			$str_sql .= "'',";			
			$str_sql .= "'" . $_irID . "'";
			$str_sql .= ")";

			$query = mysqli_query($obj_con, $str_sql) or die ("Error in query: $str_sql " . mysqli_error($obj_con));
			// echo $str_sql;

			$str_sql_id = "SELECT MAX(invrcpt_id) AS invrcpt_id FROM invoice_rcpt_tb WHERE invrcpt_depid = '". $depid ."' ORDER BY invrcpt_id ASC";
			$obj_rs_id = mysqli_query($obj_con, $str_sql_id);
			$obj_row_id = mysqli_fetch_array($obj_rs_id);

			$irID = $obj_row_id["invrcpt_id"];

			if(isset($_POST["countChk"])) {
				$countChk = $_POST["countChk"];
			} else {
				$countChk = '';
			}

			if($countChk == '') {
				$irID = $obj_row_id["invrcpt_id"];
			} else {
				for ($i = 1; $i <= $countChk ; $i++) {

					$irDid = 'irDid' . $i;
					$irDid = $_POST["$irDid"];

					$str_sql_up = "UPDATE invoice_rcpt_desc_tb SET 
					invrcptD_irid = '$irID'
					WHERE invrcptD_id = '$irDid'";
					$result = mysqli_query($obj_con, $str_sql_up);

				}
			}

			if($compid == 'C001') {
				$url = "invoice_rcpt_preview_acs.php?cid=$compid&dep=$depid&m=$month&projid=$invReprojid&irID=$irID";
			} else if($compid == 'C004') {
				$url = "invoice_rcpt_preview_acsp.php?cid=$compid&dep=$depid&m=$month&projid=$invReprojid&irID=$irID";
			} else if($compid == 'C008') {
				$url = "invoice_rcpt_preview_ttni.php?cid=$compid&dep=$depid&m=$month&projid=$invReprojid&irID=$irID";
			} else if($compid == 'C010') {
				$url = "invoice_rcpt_preview_tppt.php?cid=$compid&dep=$depid&m=$month&projid=$invReprojid&irID=$irID";
			} else if($compid == 'C011') {
				$url = "invoice_rcpt_preview_rpec.php?cid=$compid&dep=$depid&m=$month&projid=$invReprojid&irID=$irID";
			} else if($compid == 'C014') {
				$url = "invoice_rcpt_preview_acsi.php?cid=$compid&dep=$depid&m=$month&projid=$invReprojid&irID=$irID";
			} else if($compid == 'C015') {
				$url = "invoice_rcpt_preview_acsd.php?cid=$compid&dep=$depid&m=$month&projid=$invReprojid&irID=$irID";
			} else if($compid == 'C016') {
				$url = "invoice_rcpt_preview_eptg.php?cid=$compid&dep=$depid&m=$month&projid=$invReprojid&irID=$irID";
			} else if($compid == 'C009') {
				$url = "invoice_rcpt_preview_tbri.php?cid=$compid&dep=$depid&m=$month&projid=$invReprojid&irID=$irID";
			}

?>