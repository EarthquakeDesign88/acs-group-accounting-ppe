<?php

	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			if(isset($_POST["page_status"])){
				$page = $_POST["page_status"];
			}

			if(isset($_POST["Reid"])) {
				$Reid = $_POST["Reid"];
			} else {
				$Reid = '';
			}

			if(isset($_POST["Rebookno"])) {
				$Rebookno = $_POST["Rebookno"];
			} else {
				$Rebookno = '';
			}

			if(isset($_POST["Reno"])) {
				$Reno = $_POST["Reno"];
			} else {
				$Reno = '';
			}

			if(isset($_POST["Redate"])) {
				$Redate = $_POST["Redate"];
			} else {
				$Redate = '0000-00-00';
			}

			if(isset($_POST["datePay"])) {
				if($_POST["datePay"] == NULL) {
					$datePay = '0000-00-00';
				} else {
					$datePay = $_POST["datePay"];
				}
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

			if(isset($_POST["compid"])) {
				$compid = $_POST["compid"];
			} else {
				$compid = '';
			}

			if(isset($_POST["custid"])) {
				$custid = $_POST["custid"];
			} else {
				$custid = '';
			}

			if(isset($_POST["depid"])) {
				$depid = $_POST["depid"];
			} else {
				$depid = '';
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
					$CreateDate = $_POST["CreateDate"];
				}
			}

			if(isset($_POST["useridEdit"])) {
				$useridEdit = $_POST["useridEdit"];
			} else {
				$useridEdit = '';
			}

			if(isset($_POST["EditDate"])) {
				if($_POST["EditDate"] == NULL) {
					$EditDate = date('Y-m-d H:i:s');
				} else {
					$EditDate = '0000-00-00 00:00:00';
				}
			}

			if(isset($_POST["irID"])) {
				$irID = $_POST["irID"];
			} else {
				$irID = '';
			}

			if(isset($_POST["projid"])) {
				$projid = $_POST["projid"];
			} else {
				$projid = '';
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

			if($compid == 'C014') {
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

			if(isset($_POST["Reyear"])) {
				$Reyear = $_POST["Reyear"];
			} else {
				$Reyaer = '';
			}

			if(isset($_POST["Remonth"])) {
				$Remonth = $_POST["Remonth"];
			} else {
				$Remonth = '';
			}

			if(isset($_POST["Refile"])) {
				$Refile = $_POST["Refile"];
			} else { 
				$Refile = '';
			}

			if(isset($_POST["Restsid"])) {
				$Restsid = $_POST["Restsid"];
			} else {
				$Restsid = '';
			}

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

			$Reref = $_POST["Reref"];

			if($compid == 'C011') {
				$RefinvrcptNo = $_POST["invNo"];
			} else {
				$RefinvrcptNo = '';
			}
			

			if($irID == '') {

				$str_sql = "UPDATE receipt_tb SET 
				re_bookno = '$Rebookno', 
				re_no = '$Reno', 
				re_date = '$Redate', 
				re_datepay = '$datePay', 
				re_refinvrcpt = '$Reref', 
				re_compid = '$compid', 
				re_custid = '$custid', 
				re_depid = '$depid', 
				re_outputtax = '$outputTax', 
				re_description1 = '$invredesc1', 
				re_description2 = '$invredesc2', 
				re_description3 = '$invredesc3', 
				re_description4 = '$invredesc4', 
				re_description5 = '$invredesc5', 
				re_description6 = '$invredesc6', 
				re_description7 = '$invredesc7', 
				re_description8 = '$invredesc8', 
				re_sub_description1 = '$invresubdesc1',
				re_sub_description2 = '$invresubdesc2',
				re_sub_description3 = '$invresubdescHidden3',
				re_sub_description4 = '$invresubdescHidden4',
				re_sub_description5 = '$invresubdescHidden5',
				re_sub_description6 = '$invresubdescHidden6',
				re_sub_description7 = '$invresubdescHidden7',
				re_sub_description8 = '$invresubdescHidden8',
				re_sub_description9 = '$invresubdescHidden9',
				re_amount1 = '$amountHidden1', 
				re_amount2 = '$amountHidden2', 
				re_amount3 = '$amountHidden3', 
				re_amount4 = '$amountHidden4', 
				re_amount5 = '$amountHidden5', 
				re_amount6 = '$amountHidden6', 
				re_amount7 = '$amountHidden7', 
				re_amount8 = '$amountHidden8', 
				re_subtotal = '$subtotalHidden', 
				re_vatpercent = '$vatpercentHidden', 
				re_vat = '$vatHidden', 
				re_differencevat = '$DiffVatHidden',
				re_grandtotal = '$grandtotalHidden', 
				re_differencegrandtotal = '$DiffGrandHidden', 
				re_note = '$ReNote', 
				re_invrcptNo_RPEC = '$RefinvrcptNo',
				re_typepay = '$bySelPay', 
				re_chequeno = '$chequeNo', 
				re_bankid = '$SelBank', 
				re_branchid = '$SelBranch', 
				re_chequedate = '$chequeDate', 
				re_year = '$Reyear', 
				re_month = '$Remonth', 
				re_file = '$Refile', 
				re_stsid = '$Restsid',
				re_userid_create = '$useridCreate', 
				re_createdate = '$CreateDate', 
				re_userid_edit = '$useridEdit', 
				re_editdate = '$EditDate'
				WHERE re_id = '".$Reid."'";

			} else {

				$str_sql = "UPDATE receipt_tb SET 
				re_bookno = '$Rebookno', 
				re_no = '$Reno', 
				re_date = '$Redate', 
				re_datepay = '$datePay',
				re_refinvrcpt = '$Reref', 
				re_compid = '$compid', 
				re_custid = '$custid', 
				re_depid = '$depid', 
				re_outputtax = '$outputTax', 
				re_description1 = '$invredesc1', 
				re_description2 = '$invredesc2', 
				re_description3 = '$invredesc3', 
				re_description4 = '$invredesc4', 
				re_description5 = '$invredesc5', 
				re_description6 = '$invredesc6', 
				re_description7 = '$invredesc7', 
				re_description8 = '$invredesc8', 
				re_sub_description1 = '$invresubdesc1',
				re_sub_description2 = '$invresubdesc2',
				re_sub_description3 = '$invresubdescHidden3',
				re_sub_description4 = '$invresubdescHidden4',
				re_sub_description5 = '$invresubdescHidden5',
				re_sub_description6 = '$invresubdescHidden6',
				re_sub_description7 = '$invresubdescHidden7',
				re_sub_description8 = '$invresubdescHidden8',
				re_sub_description9 = '$invresubdescHidden9',
				re_amount1 = '$amountHidden1', 
				re_amount2 = '$amountHidden2', 
				re_amount3 = '$amountHidden3', 
				re_amount4 = '$amountHidden4', 
				re_amount5 = '$amountHidden5', 
				re_amount6 = '$amountHidden6', 
				re_amount7 = '$amountHidden7', 
				re_amount8 = '$amountHidden8', 
				re_subtotal = '$subtotalHidden', 
				re_vatpercent = '$vatpercentHidden', 
				re_vat = '$vatHidden', 
				re_differencevat = '$DiffVatHidden',
				re_grandtotal = '$grandtotalHidden', 
				re_differencegrandtotal = '$DiffGrandHidden', 
				re_note = '$ReNote', 
				re_typepay = '$bySelPay', 
				re_chequeno = '$chequeNo', 
				re_bankid = '$SelBank', 
				re_branchid = '$SelBranch', 
				re_chequedate = '$chequeDate', 
				re_year = '$Reyear', 
				re_month = '$Remonth', 
				re_file = '$Refile', 
				re_stsid = '$Restsid', 
				re_userid_create = '$useridCreate', 
				re_createdate = '$CreateDate', 
				re_userid_edit = '$useridEdit', 
				re_editdate = '$EditDate'
				WHERE re_id = '".$Reid."'";

			}
			$query = mysqli_query($obj_con, $str_sql) or die ("Error in query: $str_sql " . mysqli_error($obj_con));


			if($compid == 'C001') {
				$url = "receipt_preview_acs.php?cid=$compid&dep=$depid&m=$Remonth&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C004') {
				$url = "receipt_preview_acsp.php?cid=$compid&dep=$depid&m=$Remonth&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C008') {
				$url = "receipt_preview_ttni.php?cid=$compid&dep=$depid&m=$Remonth&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C009') {
				$url = "receipt_preview_tbri.php?cid=$compid&dep=$depid&m=$Remonth&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C010') {
				$url = "receipt_preview_tppt.php?cid=$compid&dep=$depid&m=$Remonth&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C011') {
				$url = "receipt_preview_rpec.php?cid=$compid&dep=$depid&m=$Remonth&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C014') {
				$url = "receipt_preview_acsi.php?cid=$compid&dep=$depid&m=$Remonth&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C015') {
				$url = "receipt_preview_acsd.php?cid=$compid&dep=$depid&m=$Remonth&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C016') {
				$url = "receipt_preview_eptg.php?cid=$compid&dep=$depid&m=$Remonth&projid=$projid&irID=$irID&Reid=$Reid";
			}

			if ($query) {

				echo json_encode(array('status' => '1','url'=> $url,'page'=>$page));

			} else {

				echo json_encode(array('status' => '0','message'=> $str_sql));

			}

		}

	}

?>