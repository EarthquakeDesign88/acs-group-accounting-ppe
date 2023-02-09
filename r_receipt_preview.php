<?php

	header('Content-Type: application/json');
	
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			$output = "";
			
			$compid = $_POST["compid"];
			$depid = $_POST["depid"];
			$month = $_POST["month"];
			$projid = $_POST["projid"];
			$irID = $_POST["irID"];
			$Reid = $_POST["Reid"];
			$Rebookno = $_POST["Rebookno"];
			$Reno = $_POST["Reno"];

			if($compid == 'C001') {
				$output .= "receipt_print_acs.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C004') {
				$output .= "receipt_print_acsp.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C008') {
				$output .= "receipt_print_ttni.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C009') {
				$output .= "receipt_print_tbri.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C010') {
				$output .= "receipt_print_tppt.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C011') {
				$output .= "receipt_print_rpec.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C014') {
				$output .= "receipt_print_acsi.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C015') {
				$output .= "receipt_print_acsd.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID&Reid=$Reid";
			} else if($compid == 'C016') {
				$output .= "receipt_print_eptg.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID&Reid=$Reid";
			}
			
			$url = $output;

			if($_POST["Rebookno"] == '') {
				$ReceiptNo = $Reno;
			} else {
				$ReceiptNo = $_POST["Rebookno"] . "/" . $Reno;
			}
			
			echo json_encode(array('status' => '1','book'=> $Rebookno,'message'=> $ReceiptNo,'url'=> $url));

		} else {

			echo json_encode(array('status' => '0','message'=> 'ERROR'));

		}

		mysqli_close($obj_con);

	}

?>