<?php
	
	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			$str_sql = "SELECT * FROM user_tb WHERE user_id = '" . $_SESSION["user_id"] . "'";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);

			if (isset($_POST["userpassCurrent"])) {

				if ($_POST["userpassCurrent"] == $obj_row["user_password"]) {

					if($_POST["userpassNew"] == $_POST["userpassConfirm"] ) {

						// echo $_POST["userpassCurrent"] . "<br>";
						// echo $_POST["userpassNew"] . "<br>";
						// echo $_POST["userpassConfirm"];
						$compid = $_POST["compid"];
						$dep = $_POST["userdepid"];
						$userpassNew = $_POST["userpassNew"];

						$str_sql_update = "UPDATE user_tb SET
						user_password = '$userpassNew'
						WHERE user_id = '". $_SESSION["user_id"] ."'";
						$result_update = mysqli_query($obj_con, $str_sql_update);

						if ($dep == 5) {
							echo json_encode(array('status' => '2','message'=> 'Test','compid'=> $compid,'dep'=> ''));
						} else {
							echo json_encode(array('status' => '2','message'=> 'Test','compid'=> $compid,'dep'=> $dep));
						}

					} else {

						echo json_encode(array('status' => '1','message'=> 'รหัสผ่านใหม่ไม่ถูกต้อง','compid'=> $compid));

					}

				} else {

					echo json_encode(array('status' => '0','message'=> 'รหัสผ่านปัจจุบันไม่ถูกต้อง','compid'=> $compid));

				}

			}

		}

	}

?>