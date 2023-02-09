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

			// if(isset($_POST["invrcptLessonID"])) {
			// 	$invrcptLessonID = $_POST["invrcptLessonID"];
			// } else {
			// 	$invrcptLessonID = 0;
			// }

			if(isset($_POST["substrLesson"])) {
				$substrLesson = $_POST["substrLesson"];
			} 

			if($projSub == '') {
				if($substrLesson == '') {
					$invrcptLessonID = $invReDLesson;
				} else {
					$invrcptLessonID = $_POST["substrLesson"];
				}
			} else {
				$invrcptLessonID = $invReDLesson . substr($_POST["projSub"],-5);
			}		

			if(isset($_POST["invReDuseridCreate"])) {
				$invReDuseridCreate = $_POST["invReDuseridCreate"];
			} else {
				$invReDuseridCreate = '';
			}

			if(isset($_POST["invReDCreateDate"])) {
				if($_POST["invReDCreateDate"] != NULL) {
					$invReDCreateDate = $_POST["invReDCreateDate"];
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
				if($_POST["invReDEditDate"] == NULL) {
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

			if(isset($_POST["irDid"])) {
				$irDid = $_POST["irDid"];
			} else {
				$irDid = '';
			}

			if(isset($_POST["irid"])) {
				$irid = $_POST["irid"];
			} else {
				$irid = '';
			}

			$str_sql = "UPDATE invoice_rcpt_desc_tb SET 
			invrcptD_id = '$irDid', 
			invrcptD_compid = '$compid', 
			invrcptD_custid = '$custid', 
			invrcptD_depid = '$depid', 
			invrcptD_projid = '$projid', 
			invrcptD_projidSub = '$projSub', 
			invrcptD_lessonID = '$invrcptLessonID', 
			invrcptD_lesson = '$invReDLesson', 
			invrcptD_description1 = '$invredesc1', 
			invrcptD_description2 = '$invredesc2', 
			invrcptD_description3 = '$invredesc3', 
			invrcptD_description4 = '$invredesc4', 
			invrcptD_description5 = '$invredesc5', 
			invrcptD_description6 = '$invredesc6', 
			invrcptD_description7 = '$invredesc7', 
			invrcptD_description8 = '$invredesc8', 
			invrcptD_description9 = '$invredesc9', 
			invrcptD_description10 = '$invredesc10', 
			invrcptD_description11 = '$invredesc11', 
			invrcptD_amount1 = '$amountHidden1', 
			invrcptD_amount2 = '$amountHidden2', 
			invrcptD_amount3 = '$amountHidden3', 
			invrcptD_amount4 = '$amountHidden4', 
			invrcptD_amount5 = '$amountHidden5', 
			invrcptD_amount6 = '$amountHidden6', 
			invrcptD_amount7 = '$amountHidden7', 
			invrcptD_amount8 = '$amountHidden8', 
			invrcptD_subtotal = '$subtotalHidden', 
			invrcptD_vatpercent = '$vatpercentHidden', 
			invrcptD_vat = '$vatHidden', 
			invrcptD_grandtotal = '$grandtotalHidden', 
			invrcptD_balancetotal = '$balanceHidden', 
			invrcptD_userid_create = '$invReDuseridCreate', 
			invrcptD_createdate = '$invReDCreateDate', 
			invrcptD_userid_edit = '$invReDuseridEdit', 
			invrcptD_editdate = '$invReDEditDate',
			invrcptD_status = '$invrcptstatus',
			invrcptD_irid = '$irid' 
			WHERE invrcptD_id = '$irDid'";

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