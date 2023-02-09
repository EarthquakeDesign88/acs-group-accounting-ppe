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

			if(isset($_POST["checkVat"])) {
				$checkVat = $_POST["checkVat"];
			}

			//Validate value For Check vat
			if($datefrom != '' && $checkVat == 'vat') {
				echo json_encode(array('status' => 'isVat',
										'dateFrom'=> $datefrom,
										'dateTo'=> $dateto,
										'dep'=> $inputGroupSelectDep,
										'compid'=> $compid,
										'depid'=> $depid,
									    'checkVat' => $checkVat
								));
			} 
			else if($datefrom != '' && $checkVat == 'non_vat') {
				echo json_encode(array('status' => 'isNonVat',
										'dateFrom'=> $datefrom,
										'dateTo'=> $dateto,
										'dep'=> $inputGroupSelectDep,
										'compid'=> $compid,
										'depid'=> $depid,
									    'checkVat' => $checkVat
								));
			} 
			else {
				echo json_encode(array('status' => '0','message'=> 'Error'));
			}

			mysqli_close($obj_con);

		}

	}

?>