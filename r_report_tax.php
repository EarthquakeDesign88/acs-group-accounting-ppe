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
			}

			if(isset($_POST["depid"])) {
				$depid = $_POST["depid"];
			}

			if(isset($_POST["datefrom"])) {
				$datefrom = $_POST["datefrom"];
			}

			if(isset($_POST["dateto"])) {
				$dateto = $_POST["dateto"];
			}

			if(isset($_POST["inputGroupSelectDep"])) {
				$inputGroupSelectDep = $_POST["inputGroupSelectDep"];
			}


			if($datefrom != '') {
				echo json_encode(array('status' => '1','dateFrom'=> $datefrom,'dateTo'=> $dateto,'dep'=> $inputGroupSelectDep,'compid'=> $compid,'depid'=> $depid));
			} else {
				echo json_encode(array('status' => '0','message'=> 'Error'));
			}

			mysqli_close($obj_con);

		}

	}

?>