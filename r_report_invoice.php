<?php

	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		if(!empty($_POST)) {

			include 'connect.php';

			if(isset($_POST["compid"])) {
				$compid = $_POST["compid"];
			}

			if(isset($_POST["depid"])) {
				$depid = $_POST["depid"];
			} else {
				$depid = '';
			}

			if(isset($_POST["datefrom"])) {
				$datefrom = $_POST["datefrom"];
			}

			if(isset($_POST["dateto"])) {
				$dateto = $_POST["dateto"];
			}

			if(isset($_POST["SelectSTS"])) {
				if($_POST["SelectSTS"] == '') {
					$SelectSTS = '';
				} else {
					$SelectSTS = $_POST["SelectSTS"];
				}
			}

			if($datefrom != '') { 
				echo json_encode(array('status' => '1','dateFrom'=> $datefrom,'dateTo'=> $dateto, 'compid'=> $compid,'depid'=> $depid, 'stsid'=> $SelectSTS));
			} else {
				echo json_encode(array('status' => '0','message'=> 'Error'));
			}

			// echo '<script>
			// 		window.location = "rpt_payment_desc.php?df='.$datefrom.'&dt='.$dateto.'";
			// 	</script>';

			mysqli_close($obj_con);

		}

	}

?>