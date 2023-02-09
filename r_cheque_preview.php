<?php

	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			// $output = "";

			if (isset($_POST["compid"])) {
				$cid = $_POST["compid"];
			} else {
				$cid = '';
			}

			if (isset($_POST["paymid"])) {
				$paymid = $_POST["paymid"];
			} else {
				$paymid = '';
			}

			if (isset($_POST["cheqid"])) {
				$cheqid = $_POST["cheqid"];
			} else {
				$cheqid = '';
			}

			if (isset($_POST["cheqno"])) {
				$cheqno = $_POST["cheqno"];
			} else {
				$cheqno = '';
			}

			if (isset($_POST["bankid"])) {
				if ($_POST["bankid"] == 'B001') {

					$url = "cheque_print_BMA.php?cid=".$cid."&paymid=".$paymid."&cheqid=".$cheqid;

				} else if ($_POST["bankid"] == 'B002') {

					$url = "cheque_print_SCB.php?cid=".$cid."&paymid=".$paymid."&cheqid=".$cheqid;

				} else if ($_POST["bankid"] == 'B003') {

					$url = "cheque_print_KBANK.php?cid=".$cid."&paymid=".$paymid."&cheqid=".$cheqid;

				} else if ($_POST["bankid"] == 'B004') {

					$url = "cheque_print_BBL.php?cid=".$cid."&paymid=".$paymid."&cheqid=".$cheqid;

				}
			}

			// $url = $output;

			echo json_encode(array('status' => '1','message'=> $cheqno,'url'=> $url));

		} else {

			echo json_encode(array('status' => '0','message'=> 'ERROR'));

		}

		// mysqli_close($obj_con);

	}
?>