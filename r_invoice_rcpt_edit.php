<?php

	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			if(isset($_POST["irID"])) {
				$irID = $_POST["irID"];
			} else {
				$irID = '';
			}

			if(isset($_POST["irbook"])) {
				$irbook = $_POST["irbook"];
			} else {
				$irbook = '';
			}

			if(isset($_POST["irno"])) {
				$irno = $_POST["irno"];
			} else {
				$irno = '';
			}

			if(isset($_POST["irdate"])) {
				$irdate = $_POST["irdate"];
			} else {
				$irdate = '0000-00-00';
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

			if(isset($_POST["projid"])) {
				$projid = $_POST["projid"];
			} else {
				$projid = '';
			}

			if(isset($_POST["irDid"])) {
				$irDid = $_POST["irDid"];
			} else {
				$irDid = '';
			}

			if(isset($_POST["invredesc1"])) {
				$invredesc1 = $_POST["invredesc1"];
			} else {
				$invredesc1 = '';
			}
			// echo "Desc 1 : " . $invredesc1 . "<br>";

			if(isset($_POST["invredesc2"])) {
				$invredesc2 = $_POST["invredesc2"];
			} else {
				$invredesc2 = '';
			}
			// echo "Desc 2 : " . $invredesc2 . "<br>";

			if(isset($_POST["invredesc3"])) {
				$invredesc3 = $_POST["invredesc3"];
			} else {
				$invredesc3 = '';
			}
			// echo "Desc 3 : " . $invredesc3 . "<br>";

			if(isset($_POST["invredesc4"])) {
				$invredesc4 = $_POST["invredesc4"];
			} else {
				$invredesc4 = '';
			}
			// echo "Desc 4 : " . $invredesc4 . "<br>";

			if(isset($_POST["invredesc5"])) {
				$invredesc5 = $_POST["invredesc5"];
			} else {
				$invredesc5 = '';
			}
			// echo "Desc 5 : " . $invredesc5 . "<br>";

		
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
			// echo "Desc 8 : " . $invredesc8 . "<br>";
			

			if(isset($_POST["invredesc9"])) {
				$invredesc9 = $_POST["invredesc9"];
			} else {
				$invredesc9 = '';
			}
			// echo "Desc 9 : " . $invredesc9 . "<br>";

			if(isset($_POST["invredesc10"])) {
				$invredesc10 = $_POST["invredesc10"];
			} else {
				$invredesc10 = '';
			}
			// echo "Desc 10 : " . $invredesc10 . "<br>";

			if(isset($_POST["invredesc11"])) {
				$invredesc11 = $_POST["invredesc11"];
			} else {
				$invredesc11 = '';
			}
			// echo "Desc 11 : " . $invredesc11 . "<br>";

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

			if(isset($_POST["amountHidden1"])) {
				$amountHidden1 = $_POST["amountHidden1"];
			} else {
				$amountHidden1 = '0.00';
			}
			// echo "Amount 1 : " . $amountHidden1 . "<br>";

			if(isset($_POST["amountHidden2"])) {
				$amountHidden2 = $_POST["amountHidden2"];
			} else {
				$amountHidden2 = '0.00';
			}
			// echo "Amount 2 : " . $amountHidden2 . "<br>";

			if(isset($_POST["amountHidden3"])) {
				$amountHidden3 = $_POST["amountHidden3"];
			} else {
				$amountHidden3 = '0.00';
			}
			// echo "Amount 3 : " . $amountHidden3 . "<br>";

			if(isset($_POST["amountHidden4"])) {
				$amountHidden4 = $_POST["amountHidden4"];
			} else {
				$amountHidden4 = '0.00';
			}
			// echo "Amount 4 : " . $amountHidden4 . "<br>";

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

			if(isset($_POST["subtotalHidden"])) {
				$balanceHidden = $_POST["subtotalHidden"];
			} else {
				$balanceHidden = '0.00';
			}
			// echo "Balance : " . $balanceHidden . "<br>";

			if(isset($_POST["DiffGrandHidden"])) {
				if($_POST["DiffGrandHidden"] == '0') {
					$DiffGrandHidden = '0.00';
				} else {
					$DiffGrandHidden = $_POST["DiffGrandHidden"];
				}
			} else {
				$DiffGrandHidden = '0.00';
			}
			// echo "Diff Grand : " . $DiffGrandHidden . "<br>";

			if(isset($_POST["stsid"])) {
				$stsid = $_POST["stsid"];
			} else {
				$stsid = '';
			}
			// echo "Status : " . $stsid . "<br>";

			if(isset($_POST["iryear"])) {
				$iryear = $_POST["iryear"];
			} else {
				$iryear = '';
			}

			if(isset($_POST["irmonth"])) {
				$irmonth = $_POST["irmonth"];
			} else {
				$irmonth = '';
			}

			if(isset($_POST["irfile"])) {
				$irfile = $_POST["irfile"];
			} else {
				$irfile = '';
			}

			if(isset($_POST["iruseridCreate"])) {
				$iruseridCreate = $_POST["iruseridCreate"];
			} else {
				$iruseridCreate = '';
			}

			if(isset($_POST["irCreateDate"])) {
				if($_POST["irCreateDate"] != NULL) {
					$irCreateDate = $_POST["irCreateDate"];
				} else {
					$irCreateDate = '0000-00-00 00:00:00';
				}
			}

			if(isset($_POST["iruseridEdit"])) {
				$iruseridEdit = $_POST["iruseridEdit"];
			} else {
				$iruseridEdit = '';
			}

			if(isset($_POST["irEditDate"])) {
				if($_POST["irEditDate"] == NULL) {
					$irEditDate = date('Y-m-d H:i:s');
				} else {
					$irEditDate = '0000-00-00 00:00:00';
				}
			}

			if(isset($_POST["invrcptduedate"])) {
				if($_POST["invrcptduedate"] == NULL) {
					$invrcptduedate = '0000-00-00';
				} else {
					$invrcptduedate = $_POST["invrcptduedate"];
				}
			} else {
				$invrcptduedate = '0000-00-00';
			}

			$str_sql = "UPDATE invoice_rcpt_tb SET 
			invrcpt_book = '$irbook', 
			invrcpt_no = '$irno', 
			invrcpt_date = '$irdate', 
			invrcpt_compid = '$compid', 
			invrcpt_custid = '$custid', 
			invrcpt_depid = '$depid', 
			invrcpt_projid = '$projid',
			invrcpt_description1 = '$invredesc1', 
			invrcpt_description2 = '$invredesc2', 
			invrcpt_description3 = '$invredesc3', 
			invrcpt_description4 = '$invredesc4', 
			invrcpt_description5 = '$invredesc5', 
			invrcpt_description6 = '$invredesc6', 
			invrcpt_description7 = '$invredesc7', 
			invrcpt_description8 = '$invredesc8', 
			invrcpt_description9 = '$invredesc9', 
			invrcpt_description10 = '$invredesc10', 
			invrcpt_description11 = '$invredesc11', 
			invrcpt_sub_description1 = '$invresubdesc1',
			invrcpt_sub_description2 = '$invresubdesc2',
			invrcpt_sub_description3 = '$invresubdescHidden3',
			invrcpt_sub_description4 = '$invresubdescHidden4',
			invrcpt_sub_description5 = '$invresubdescHidden5',
			invrcpt_sub_description6 = '$invresubdescHidden6',
			invrcpt_sub_description7 = '$invresubdescHidden7',
			invrcpt_sub_description8 = '$invresubdescHidden8',
			invrcpt_sub_description9 = '$invresubdescHidden9',
			invrcpt_amount1 = '$amountHidden1', 
			invrcpt_amount2 = '$amountHidden2', 
			invrcpt_amount3 = '$amountHidden3', 
			invrcpt_amount4 = '$amountHidden4', 
			invrcpt_amount5 = '$amountHidden5', 
			invrcpt_amount6 = '$amountHidden6', 
			invrcpt_amount7 = '$amountHidden7', 
			invrcpt_amount8 = '$amountHidden8', 
			invrcpt_subtotal = '$subtotalHidden', 
			invrcpt_vatpercent = '$vatpercentHidden', 
			invrcpt_vat = '$vatHidden',  
			invrcpt_differencevat = '$DiffVatHidden',
			invrcpt_grandtotal = '$grandtotalHidden', 
			invrcpt_differencegrandtotal = '$DiffGrandHidden',
			invrcpt_balancetotal = '$balanceHidden', 
			invrcpt_duedate = '$invrcptduedate',
			invrcpt_year = '$iryear', 
			invrcpt_month = '$irmonth', 
			invrcpt_file = '$irfile', 
			invrcpt_stsid = '$stsid',
			invrcpt_userid_create = '$iruseridCreate',
			invrcpt_createdate = '$irCreateDate',
			invrcpt_userid_edit = '$iruseridEdit',
			invrcpt_editdate = '$irEditDate',
			invrcpt_reid = ''
			WHERE invrcpt_id = '$irID'";
			$query = mysqli_query($obj_con, $str_sql) or die ("Error in query: $str_sql " . mysqli_error());

			// if(isset($_POST["countChk"])) {
			// 	$countChk = $_POST["countChk"];
			// } else {
			// 	$countChk = '';
			// }

			// if($countChk == '') {
			// 	$irID = '';
			// } else {
			// 	for ($i = 1; $i <= $countChk ; $i++) {

			// 		$irDid = 'irDid' . $i;
			// 		$irDid = $_POST["$irDid"];

			// 		$str_sql_up = "UPDATE invoice_rcpt_desc_tb SET 
			// 		invrcptD_irid = '$irID'
			// 		WHERE invrcptD_id = '$irDid'";
			// 		$result = mysqli_query($obj_con, $str_sql_up);

			// 	}
			// }

			// if($compid == 'C001') {
			// 	$url = "invoice_rcpt_preview_acs.php?cid=$compid&dep=$depid&m=$irmonth&projid=$projid&irID=$irID";
			// } else if($compid == 'C004') {
			// 	$url = "invoice_rcpt_preview_acsp.php?cid=$compid&dep=$depid&m=$irmonth&projid=$projid&irID=$irID";
			// } else if($compid == 'C008') {
			// 	$url = "invoice_rcpt_preview_ttni.php?cid=$compid&dep=$depid&m=$irmonth&projid=$projid&irID=$irID";
			// } else if($compid == 'C010') {
			// 	$url = "invoice_rcpt_preview_tppt.php?cid=$compid&dep=$depid&m=$irmonth&projid=$projid&irID=$irID";
			// } else if($compid == 'C011') {
			// 	$url = "invoice_rcpt_preview_rpec.php?cid=$compid&dep=$depid&m=$irmonth&projid=$projid&irID=$irID";
			// } else if($compid == 'C014') {
			// 	$url = "invoice_rcpt_preview_acsi.php?cid=$compid&dep=$depid&m=$irmonth&projid=$projid&irID=$irID";
			// } else if($compid == 'C015') {
			// 	$url = "invoice_rcpt_preview_acsd.php?cid=$compid&dep=$depid&m=$irmonth&projid=$projid&irID=$irID";
			// } else if($compid == 'C016') {
			// 	$url = "invoice_rcpt_preview_eptg.php?cid=$compid&dep=$depid&m=$irmonth&projid=$projid&irID=$irID";
			// } else if($compid == 'C009') {
			// 	$url = "invoice_rcpt_preview_tbri.php?cid=$compid&dep=$depid&m=$month&projid=$invReprojid&irID=$irID";
			// }

			if($query) {
				echo json_encode(array('status' => '1','compid'=> $compid,'irID'=> $irID,'irno'=> $irno,'depid'=> $depid,'projid'=> $projid,'month'=> $irmonth));
			} else {
				echo json_encode(array('status' => '0','message'=> $str_sql));
			}

			mysqli_close($obj_con);

		}

	}

?>