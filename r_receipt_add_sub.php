<?php
			if(isset($_POST["datePay"])) {
				if($_POST["datePay"] == NULL) {
					$datePay = '0000-00-00';
				} else {
					$datePay = $_POST["datePay"];
				}
			}

			if(isset($_POST["custid"])) {
				$custid = $_POST["custid"];
			} else {
				$custid = '';
			}
			
			if(isset($_POST["Redate"])) {
				$Redate = $_POST["Redate"];
			} else {
				$Redate = '0000-00-00';
			}

			if(isset($_POST["outputTax"])) {
				$outputTax = $_POST["outputTax"];
			} else {
				$outputTax = '';
			}

			if(isset($_POST["ReNote"])) {
				$ReNote = $_POST["ReNote"];
			} else {
				$ReNote = '';
			}

			if(isset($_POST["bySelPay"])) {
				$bySelPay = $_POST["bySelPay"];
			} else {
				$bySelPay = '';
			}

			if(isset($_POST["chequeNo"])) {
				$chequeNo = $_POST["chequeNo"];
			} else {
				$chequeNo = '';
			}

			if(isset($_POST["SelBank"])) {
				$SelBank = $_POST["SelBank"];
			} else {
				$SelBank = '';
			}

			if(isset($_POST["SelBranch"])) {
				$SelBranch = $_POST["SelBranch"];
			} else {
				$SelBranch = '';
			}

			// echo $SelBranch;

			if(isset($_POST["chequeDate"])) {
				if($_POST["chequeDate"] != NULL) {
					$chequeDate = $_POST["chequeDate"];
				} else {
					$chequeDate = '0000-00-00';
				}
			}

			if(isset($_POST["useridCreate"])) {
				$useridCreate = $_POST["useridCreate"];
			} else {
				$useridCreate = '';
			}

			if(isset($_POST["CreateDate"])) {
				if($_POST["CreateDate"] == NULL) {
					$CreateDate = date('Y-m-d H:i:s');
				} else {
					$CreateDate = '0000-00-00 00:00:00';
				}
			}

			if(isset($_POST["useridEdit"])) {
				$useridEdit = $_POST["useridEdit"];
			} else {
				$useridEdit = '';
			}

			if(isset($_POST["EditDate"])) {
				if($_POST["EditDate"] == NULL) {
					$EditDate = '0000-00-00 00:00:00';
				} else {
					$EditDate = $_POST["EditDate"];
				}
			}

			if(isset($_POST["irID"])) {
				$irID = $_POST["irID"];
			} else {
				$irID = '';
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

			if($compid == 'C014' || $compid == 'C015') {
				if(isset($_POST["invredescACSI6"])) {
					$invredesc6 = $_POST["invredescACSI6"];
				} else {
					$invredesc6 = '';
				}

				if(isset($_POST["invredescACSI7"])) {
					$invredesc7 = $_POST["invredescACSI7"];
				} else {
					$invredesc7 = '';
				}

				if(isset($_POST["invredescACSI8"])) {
					$invredesc8 = $_POST["invredescACSI8"];
				} else {
					$invredesc8 = '';
				}
			} else {
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

			if(isset($_POST["DiffGrandHidden"])) {
				if($_POST["DiffGrandHidden"] == '0') {
					$DiffGrandHidden = '0.00';
				} else {
					$DiffGrandHidden = $_POST["DiffGrandHidden"];
				}
			} else {
				$DiffGrandHidden = '0.00';
			}

			if(isset($_POST["projid"])) {
				$projid = $_POST["projid"];
			} else {
				$projid = '';
			}

			$str_sql = "INSERT INTO receipt_tb (re_bookno, re_no, re_date, re_datepay, re_refinvrcpt, re_compid, re_custid, re_depid, re_outputtax, re_description1, re_description2, re_description3, re_description4, re_description5, re_description6, re_description7, re_description8, re_sub_description1, re_sub_description2, re_sub_description3, re_sub_description4, re_sub_description5, re_sub_description6, re_sub_description7, re_sub_description8, re_sub_description9, re_amount1, re_amount2, re_amount3, re_amount4, re_amount5, re_amount6, re_amount7, re_amount8, re_subtotal, re_vatpercent, re_vat, re_differencevat, re_grandtotal, re_differencegrandtotal, re_note, re_typepay, re_chequeno, re_bankid, re_branchid, re_chequedate, re_year, re_month, re_file, re_stsid, re_userid_create, re_createdate, re_userid_edit, re_editdate) VALUES (";
			$str_sql .= "'" . $depcode . "',";
			$str_sql .= "'" . $nextReno . "',";
			$str_sql .= "'" . $Redate . "',";
			$str_sql .= "'" . $datePay . "',";
			if($irID == '') {
				$str_sql .= "'1',";
			} else {
				$str_sql .= "'0',";
			}
			$str_sql .= "'" . $compid  . "',";
			$str_sql .= "'" . $custid . "',";
			$str_sql .= "'" . $depid . "',";
			$str_sql .= "'" . $outputTax . "',";
			// if($irID == '') {
				$str_sql .= "'" . $invredesc1 . "',";
				$str_sql .= "'" . $invredesc2 . "',";
				$str_sql .= "'" . $invredesc3 . "',";
				$str_sql .= "'" . $invredesc4 . "',";
				$str_sql .= "'" . $invredesc5 . "',";
				$str_sql .= "'" . $invredesc6 . "',";
				$str_sql .= "'" . $invredesc7 . "',";
				$str_sql .= "'" . $invredesc8 . "',";
			// } else {
			// 	$str_sql .= "'',";
			// 	$str_sql .= "'',";
			// 	$str_sql .= "'',";
			// 	$str_sql .= "'',";
			// 	$str_sql .= "'',";
			// 	$str_sql .= "'',";
			// 	$str_sql .= "'',";
			// 	$str_sql .= "'',";
			// }
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
			$str_sql .= "'" . $ReNote . "',";
			$str_sql .= "'" . $bySelPay . "',";
			$str_sql .= "'" . $chequeNo . "',";
			$str_sql .= "'" . $SelBank . "',";
			$str_sql .= "'" . $SelBranch . "',";
			$str_sql .= "'" . $chequeDate . "',";
			$str_sql .= "'" . $SelreYear . "',";
			$str_sql .= "'" . $_POST["SelMonth"] . "',";
			$str_sql .= "'',";
			$str_sql .= "'STS002',";
			$str_sql .= "'" . $useridCreate . "',";
			$str_sql .= "'" . $CreateDate . "',";
			$str_sql .= "'" . $useridEdit . "',";
			$str_sql .= "'" . $EditDate . "')";
			$query = mysqli_query($obj_con, $str_sql) or die ("Error in query: $str_sql " . mysqli_error($obj_con));


			$str_sql_id = "SELECT MAX(re_id) AS re_id FROM receipt_tb WHERE re_depid = '". $depid ."' ORDER BY re_id";
			$obj_rs_id = mysqli_query($obj_con, $str_sql_id);
			$obj_row_id = mysqli_fetch_array($obj_rs_id);

			$Reid = $obj_row_id["re_id"];

			if(isset($_POST["invResubtotalHidden"])) {
				$invResubtotalHidden = $_POST["invResubtotalHidden"];
			} else {
				$invResubtotalHidden = '0.00';
			}

			if($invResubtotalHidden == $subtotalHidden) {

				$str_sql_upinv = "UPDATE invoice_rcpt_tb SET 
				invrcpt_balancetotal = '0.00',
				invrcpt_stsid = 'STS002',
				invrcpt_reid = '$Reid'
				WHERE invrcpt_id = '$irID'";

			} else {

				$balance = $invResubtotalHidden - $subtotalHidden;

				$balanceTotal = number_format($balance, 2, '.', '');

				$str_sql_upinv = "UPDATE invoice_rcpt_tb SET 
				invrcpt_balancetotal = '$balanceTotal',
				invrcpt_stsid = 'STS001',
				invrcpt_reid = '$Reid'
				WHERE invrcpt_id = '$irID'";

			}
			$result_upiv = mysqli_query($obj_con, $str_sql_upinv);

