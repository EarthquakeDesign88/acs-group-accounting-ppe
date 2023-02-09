<?php
	
	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		if(isset($_POST["del_id"])) {
			include 'connect.php';

			
			$str_query = "SELECT d.dep_name,t.tax_file,YEAR(t.tax_created_at) AS tax_year,Month(t.tax_created_at) AS tax_month 
							FROM taxpurchase_tb as t 
							INNER JOIN department_tb as d ON t.tax_dep_id = d.dep_id
							WHERE t.tax_id = '".$_POST['del_id']."'";
			$obj_query = mysqli_query($obj_con, $str_query);
			$obj_ass = mysqli_fetch_assoc($obj_query);

			$y = $obj_ass['tax_year'] + 543;
			$m = $obj_ass['tax_month'];
			$dep_name = $obj_ass['dep_name'];
			$file = $obj_ass['tax_file'];
			
			if(strlen($m) == 1){
				$m = '0' . $m;
			}

			$file_pointer = "./receipt_taxpurchase/$dep_name/$y/$m/$file";

			unlink($file_pointer);

			$str_sql = "DELETE FROM taxpurchase_tb WHERE tax_id = '" . $_POST["del_id"] . "'";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			
			if($obj_rs){
				$str_sql2 = "DELETE FROM taxpurchaselist_tb WHERE list_tax_id = '" . $_POST["del_id"] . "'";
				$obj_rs2 = mysqli_query($obj_con, $str_sql2);
				echo json_encode(['message'=>'success']);
			}

			mysqli_close($obj_con);

		}
		// else if(isset($_POST['del_id_edit'])){
		// 	include 'connect.php';

		// 	$str_sql2 = "DELETE FROM taxpurchaselist_tb WHERE list_id = '" . $_POST["del_id_edit"] . "'";
		// 	$obj_rs2 = mysqli_query($obj_con, $str_sql2);
		// 	echo json_encode(['message'=>'success']);

		// 	mysqli_close($obj_con);
		// }

	}
?>