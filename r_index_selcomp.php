<?php
	
	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			$cid = $_POST['compid'];
			$lev = $_POST['levid'];

			$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
			$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
			$obj_row_user = mysqli_fetch_array($obj_rs_user);

			$str_sql = "SELECT * FROM department_tb WHERE dep_compid = '". $cid ."'";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);

			if ($_POST['compid'] == 'C001') {
				if ($lev == '5' || $lev == '6') {
					echo json_encode(array('status' => '1','compid'=> $cid,'depid'=> $obj_row_user["user_depid"]));
				} else {
					echo json_encode(array('status' => '2','compid'=> $cid));
				}
			} else {
				echo json_encode(array('status' => '3','compid'=> $cid,'depid'=> $obj_row["dep_id"]));
			}

		}

	}

?>