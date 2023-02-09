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
			// echo "Amount 5 : " . $amountHidden5 . "<br>";

			if(isset($_POST["amountHidden6"])) {
				$amountHidden6 = $_POST["amountHidden6"];
			} else {
				$amountHidden6 = '0.00';
			}
			// echo "Amount 6 : " . $amountHidden6 . "<br>";

			if(isset($_POST["amountHidden7"])) {
				$amountHidden7 = $_POST["amountHidden7"];
			} else {
				$amountHidden7 = '0.00';
			}
			// echo "Amount 7 : " . $amountHidden7 . "<br>";

			if(isset($_POST["amountHidden8"])) {
				$amountHidden8 = $_POST["amountHidden8"];
			} else {
				$amountHidden8 = '0.00';
			}
			// echo "Amount 8 : " . $amountHidden8 . "<br>";

			if(isset($_POST["subtotalHidden"])) {
				$subtotalHidden = $_POST["subtotalHidden"];
			} else {
				$subtotalHidden = '0.00';
			}
			// echo "Subtotal : " . $subtotalHidden . "<br>";

			if(isset($_POST["vatpercentHidden"])) {
				$vatpercentHidden = $_POST["vatpercentHidden"];
			} else {
				$vatpercentHidden = '0.00';
			}
			// echo "Vat Percent : " . $vatpercentHidden . "<br>";

			if(isset($_POST["vatHidden"])) {
				$vatHidden = $_POST["vatHidden"];
			} else {
				$vatHidden = '0.00';
			}
			// echo "Vat : " . $vatHidden . "<br>";

			if(isset($_POST["grandtotalHidden"])) {
				$grandtotalHidden = $_POST["grandtotalHidden"];
			} else {
				$grandtotalHidden = '0.00';
			}
			// echo "Grandtotal : " . $grandtotalHidden . "<br>";

			$balanceHidden = $grandtotalHidden;
			// echo "Balance : " . $balanceHidden . "<br>";

			if(isset($_POST["stsid"])) {
				$stsid = $_POST["stsid"];
			} else {
				$stsid = '';
			}
			// echo "Status : " . $stsid . "<br>";

			if(isset($_POST["invReDLesson"])) {
				$invReDLesson = $_POST["invReDLesson"];
			} else {
				$invReDLesson = '';
			}

			if(isset($_POST["projid"])) {
				$projid = $_POST["projid"];
			} else {
				$projid = '';
			}

			if(isset($_POST["projSub"])) {
				$projSub = $_POST["projSub"];
			} else {
				$projSub = '';
			}

			if($projSub == '') {
				$invReDLessonID = $_POST["invReDLesson"];
			} else {
				$invReDLessonID = $_POST["invReDLesson"] . substr($_POST["projSub"],-5);
			}

			if(isset($_POST["invReDuseridCreate"])) {
				$invReDuseridCreate = $_POST["invReDuseridCreate"];
			} else {
				$invReDuseridCreate = '';
			}

			if(isset($_POST["invReDCreateDate"])) {
				if($_POST["invReDCreateDate"] == NULL) {
					$invReDCreateDate = date('Y-m-d H:i:s');
				} else {
					$invReDCreateDate = $_POST["invReDCreateDate"];
				}
			}

			if(isset($_POST["invReDuseridEdit"])) {
				$invReDuseridEdit = $_POST["invReDuseridEdit"];
			} else {
				$invReDuseridEdit = '';
			}

			if(isset($_POST["invReDEditDate"])) {
				if($_POST["invReDEditDate"] != NULL) {
					$invReDEditDate = date('Y-m-d H:i:s');
				} else {
					$invReDEditDate = '0000-00-00 00:00:00';
				}
			}

			if(isset($_POST["invrcptstatus"])) {
				$invrcptstatus = $_POST["invrcptstatus"];
			} else {
				$invrcptstatus = '';
			}

			$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $depid ."'";
			$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
			$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

			$depname = $obj_row_dep["dep_name"];
			$depcode = $obj_row_dep["dep_code"];

			$str_sql_invreD = "SELECT MAX(invrcptD_id) AS last_id FROM invoice_rcpt_desc_tb WHERE invrcptD_depid = '".$depid."'";
			$obj_rs_invreD = mysqli_query($obj_con, $str_sql_invreD);
			$obj_row_invreD = mysqli_fetch_array($obj_rs_invreD);
			$maxCId = substr($obj_row_invreD['last_id'], -6);
			if ($maxCId== "") {
				$maxCId = "000001";
			} else {
				$maxCId = ($maxCId + 1);
				$maxCId = substr("000000".$maxCId, -6);
			}
			$nextinvReDno = "L".$depname.$maxCId;

			$str_sql = "INSERT INTO invoice_rcpt_desc_tb (invrcptD_id, invrcptD_compid, invrcptD_custid, invrcptD_depid, invrcptD_projid, invrcptD_projidSub, invrcptD_lessonID, invrcptD_lesson, invrcptD_description1, invrcptD_description2, invrcptD_description3, invrcptD_description4, invrcptD_description5, invrcptD_description6, invrcptD_description7, invrcptD_description8, invrcptD_description9, invrcptD_description10, invrcptD_description11, invrcptD_amount1, invrcptD_amount2, invrcptD_amount3, invrcptD_amount4, invrcptD_amount5, invrcptD_amount6, invrcptD_amount7, invrcptD_amount8, invrcptD_subtotal, invrcptD_vatpercent, invrcptD_vat, invrcptD_grandtotal, invrcptD_balancetotal, invrcptD_userid_create, invrcptD_createdate, invrcptD_userid_edit, invrcptD_editdate, invrcptD_status, invrcptD_irid) VALUES (";
			$str_sql .= "'" . $nextinvReDno . "',";
			$str_sql .= "'" . $compid . "',";
			$str_sql .= "'" . $custid . "',";
			$str_sql .= "'" . $depid . "',";
			$str_sql .= "'" . $projid . "',";
			$str_sql .= "'" . $projSub . "',";
			$str_sql .= "'" . $invReDLessonID . "',";
			$str_sql .= "'" . $invReDLesson . "',";
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
			$str_sql .= "'" . $grandtotalHidden . "',";
			$str_sql .= "'" . $balanceHidden . "',";
			$str_sql .= "'" . $invReDuseridCreate . "',";
			$str_sql .= "'" . $invReDCreateDate . "',";
			$str_sql .= "'" . $invReDuseridEdit . "',";
			$str_sql .= "'" . $invReDEditDate . "',";
			$str_sql .= "'" . $invrcptstatus . "',";
			$str_sql .= "'')";

			$query = mysqli_query($obj_con, $str_sql) or die ("Error in query: $str_sql " . mysqli_error());

			$url = "invoice_rcpt_project_desc.php?cid=".$compid."&dep=".$depid."&projid=".$projid;

			if($query) {
				echo json_encode(array('status' => '1','compid'=> $compid,'depid'=> $depid,'projid'=> $projid,'url'=> $url));
			} else {
				echo json_encode(array('status' => '0','message'=> $str_sql));
			}

			mysqli_close($obj_con);

		}

	}

?>