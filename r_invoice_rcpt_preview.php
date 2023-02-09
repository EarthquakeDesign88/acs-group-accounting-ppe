<?php

	header('Content-Type: application/json');
	
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';
			
			$compid = $_POST["compid"];
			$depid = $_POST["depid"];
			$month = $_POST["month"];
			$projid = $_POST["projid"];
			$irID = $_POST["irID"];
			$irbook = $_POST["irbook"];
			$irno = $_POST["irno"];

			$output = "";

			if($compid == 'C001') {
				$output .= "invoice_rcpt_print_acs.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID";
			} else if($compid == 'C004') {
				$output .= "invoice_rcpt_print_acsp.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID";
			} else if($compid == 'C008') {
				$output .= "invoice_rcpt_print_ttni.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID";
			} else if($compid == 'C010') {
				$output .= "invoice_rcpt_print_tppt.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID";
			} else if($compid == 'C011') {
				$output .= "invoice_rcpt_print_rpec.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID";
			} else if($compid == 'C014') {
				$output .= "invoice_rcpt_print_acsi.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID";
			} else if($compid == 'C015') {
				$output .= "invoice_rcpt_print_acsd.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID";
			} else if($compid == 'C016') {
				$output .= "invoice_rcpt_print_eptg.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID";
			} else if($compid == 'C009') {
				$output .= "invoice_rcpt_print_tbri.php?cid=$compid&dep=$depid&m=$month&projid=$projid&irID=$irID";
			}
			
			$url = $output; 

			if($compid == 'C001') {
				$invoiceNo = $irbook . "/" . $irno;
			} else {
				$invoiceNo = $irno;
			}
			
			echo json_encode(array('status' => '1','book'=> $irbook,'message'=> $invoiceNo,'url'=> $url));

		} else {

			echo json_encode(array('status' => '0','message'=> 'ERROR'));

		}

		mysqli_close($obj_con);

	}
?>