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
			$projid = $_POST["projid"];
			$CountChkAll = $_POST["CountChkAll"];

			// $irDid = $_POST["invrcptD_id"];
		
			// "SELECT * FROM invoice_rcpt_desc_tb AS ird INNER JOIN project_tb AS p ON ird.invrcptD_projid = p.proj_id 
			// 										   INNER JOIN customer_tb AS c ON ird.invrcptD_custid = c.cust_id 
			// 										   WHERE proj_compid = '$cid' AND proj_depid = '". $dep ."' AND invrcptD_projid = '". $projid ."' ";

			$str_sql = "SELECT * FROM invoice_rcpt_desc_tb WHERE invrcptD_status = 1
						AND invrcptD_compid = '$compid' AND invrcptD_depid = '". $depid ."' AND invrcptD_projid = '". $projid ."'";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$i = 1;
			$ouput = "";
			$ouput .= "invoice_rcpt_refproject_add.php?cid=" . $compid . "&dep=" . $depid . "&projid=" . $projid . "&countChk=" . $CountChkAll;
			while ($obj_row = mysqli_fetch_array($obj_rs)) {
				$ouput .= "&irDid".$i."=" . $obj_row["invrcptD_id"];
				$i++;
			}

			$url = $ouput;

			if ($obj_rs) {
				echo json_encode(array('status' => '1','url'=> $url));
			} else {
				echo json_encode(array('status' => '0','message'=> $str_sql));
			}

			mysqli_close($obj_con);

		}

	}

?>