<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		if(isset($_POST["reppdid"])) {

			include 'connect.php';

			$reppdid = $_POST['reppdid'];
			$reppdtype = $_POST['reppdtype'];
			$reppddesc = $_POST['reppddesc'];
			$reppdamount = $_POST['reppdamount'];

			$str_sql = "UPDATE reportpay_desc_tb1 SET reppd_type = '". $reppdtype ."', reppd_description = '". $reppddesc ."', reppd_amount = '". $reppdamount ."' WHERE reppd_id = '". $reppdid ."'";

			if (mysqli_query($obj_con, $str_sql)) {
				echo 1;
				// echo json_encode(array('status' => '1','reppdid'=> $reppdid));
			} else {
				echo 0;
				// echo json_encode(array('status' => '0','message'=> $str_sql));
			}

		}

	}

?>