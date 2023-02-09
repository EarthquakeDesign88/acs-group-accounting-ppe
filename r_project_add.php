<?php
	
	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			$code = "P";
			$year = substr(date("Y")+543,-2);
			$y = date("Y")+543;
			$str_sql_proj = "SELECT MAX(proj_id) AS last_id FROM project_tb WHERE proj_year = '".$y."'";
			$obj_rs_proj = mysqli_query($obj_con, $str_sql_proj);
			$obj_row_proj = mysqli_fetch_array($obj_rs_proj);

			$maxId = substr($obj_row_proj['last_id'], -3);

			if ($maxId== "") {
				$maxId = "001";
			} else {
				$maxId = ($maxId + 1);
				$maxId = substr("000".$maxId, -3);
			}
			$nextproj = $code.$year.$maxId;

			if(isset($_POST["compid"])) {
				$compid = $_POST["compid"];
			} else {
				$compid = '';
			}

			if(isset($_POST["depid"])) {
				$depid = $_POST["depid"];
			} else {
				$depid = '';
			}

			if(isset($_POST["projname"])) {
				$projname = $_POST["projname"];
			} else {
				$projname = '';
			}

			if(isset($_POST["projaddress"])) {
				$projaddress = $_POST["projaddress"];
			} else {
				$projaddress = '';
			}

			if(isset($_POST["calValue"])) {
				$calValue = $_POST["calValue"];
			} else {
				$calValue = '0.00';
			}

			if(isset($_POST["projless"])) {
				$projless = $_POST["projless"];
			} else {
				$projless = '';
			}

			if(isset($_POST["PartVal"])) {
				$PartVal = $_POST["PartVal"];
			} else {
				$PartVal = '';
			}

			$str_sql = "INSERT INTO project_tb (proj_id, proj_name, proj_address, proj_compid, proj_depid, proj_amount, proj_lesson, proj_part, proj_year) VALUES (";
			$str_sql .= "'" . $nextproj . "',";
			$str_sql .= "'" . $projname . "',";
			$str_sql .= "'" . $projaddress . "',";
			$str_sql .= "'" . $compid . "',";
			$str_sql .= "'" . $depid . "',";
			$str_sql .= "'" . $calValue . "',";
			$str_sql .= "'" . $projless . "',";
			$str_sql .= "'" . $PartVal . "',";
			$str_sql .= "'" . $y . "')";
			$result = mysqli_query($obj_con, $str_sql);

			if ($PartVal == 0) {

				if(isset($_POST["countPart"])) {
					$countPart = $_POST["countPart"];
				}

				for ($i = 1; $i <= $countPart; $i++) {

					$code = "PSub";
					$year = substr(date("Y")+543,-2);
					$str_sql_projS = "SELECT MAX(projS_id) AS last_id FROM project_sub_tb";
					$obj_rs_projS = mysqli_query($obj_con, $str_sql_projS);
					$obj_row_projS = mysqli_fetch_array($obj_rs_projS);

					$maxProjSId = substr($obj_row_projS['last_id'], -3);

					if ($maxProjSId== "") {
						$maxProjSId = "001";
					} else {
						$maxProjSId = ($maxProjSId + 1);
						$maxProjSId = substr("000".$maxProjSId, -3);
					}
					$nextprojS = $code.$year.$maxProjSId;

					$partDesc = 'partDesc' . $i;
					if(isset($_POST["$partDesc"])) {
						$partDesc = $_POST["$partDesc"];
					} else {
						$partDesc = '';
					}

					$partlesson = 'partlesson' . $i;
					if(isset($_POST["$partlesson"])) {
						$partlesson = $_POST["$partlesson"];
					} else {
						$partlesson = '';
					}

					$partCalValue = 'partCalValue' . $i;
					if(isset($_POST["$partCalValue"])) {
						$partCalValue = $_POST["$partCalValue"];
					} else {
						$partCalValue = '';
					}

					if($partDesc != '') {
						$str_sql_projS = "INSERT INTO project_sub_tb (projS_id, projS_description, projS_lesson, projS_amount, projS_projid) VALUES (";
						$str_sql_projS .= "'" . $nextprojS . "',";
						$str_sql_projS .= "'" . $partDesc . "',";
						$str_sql_projS .= "'" . $partlesson . "',";
						$str_sql_projS .= "'" . $partCalValue . "',";
						$str_sql_projS .= "'" . $nextproj . "')";

						$result_projS = mysqli_query($obj_con, $str_sql_projS);

					}
				}

			} else {

			}

			if($result) {
				echo json_encode(array('status' => '1','projid'=> $nextproj,'compid'=> $compid,'depid'=> $depid));
			} else {
				echo json_encode(array('status' => '0','message'=> $str_sql,'messagePart'=> $str_sql_projS));
			}

			mysqli_close($obj_con);

		}

	}

?>