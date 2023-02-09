<?php

	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';
			
			$cid = $_POST["compid"];
			$depid = $_POST["depid"];
			$paymno = $_POST["paymno"];
			$paymid = $_POST["paymid"];
			$paymrev = $_POST["paymrev"];
			$countChk = $_POST["countChk"];

			$output = "";
			$output .= "payment_print.php?cid=" . $cid . "&dep=" . $depid . "&";
			for ($i = 1; $i <= $countChk; $i++) {
				$invid = "invid" . $i;
				$invid = $_POST["$invid"];

				$output .= "invid" . $i . "=" . $invid . "&";
			}
			$output .= "countChk=" . $countChk . "&paymid=" . $paymid . "&paymrev=" . $paymrev;

			$url = $output;

			echo json_encode(array('status' => '1','message'=> $paymno,'url'=> $url));

		} else {

			echo json_encode(array('status' => '0','message'=> 'ERROR'));

		}

		mysqli_close($obj_con);

	}
?>