<?php
	
	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			if(isset($_POST["projid"])) {
				$projid = $_POST["projid"];
			} else {
				$projid = '';
			}

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
				$projless = '0';
			}

			if(isset($_POST["projPart"])) {
				$projPart = $_POST["projPart"];
			} else {
				$projPart = '';
			}

			if(isset($_POST["projyear"])) {
				$projyear = $_POST["projyear"];
			} else {
				$projyear = '';
			}

			$str_sql = "UPDATE project_tb SET 
			proj_id = '$projid', 
			proj_name = '$projname', 
			proj_address = '$projaddress', 
			proj_compid = '$compid',
			proj_depid = '$depid', 
			proj_amount = '$calValue',
			proj_lesson = '$projless',
			proj_part = '$projPart',
			proj_year = '$projyear'
			WHERE proj_id = '$projid'";

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
						$str_sql_projS .= "'" . $projid . "')";

						$result_projS = mysqli_query($obj_con, $str_sql_projS);
					}
				}

			} else {



			}

			if(mysqli_query($obj_con, $str_sql)) {
				echo json_encode(array('status' => '1','projid'=> $projid,'compid'=> $compid,'depid'=> $depid));
			} else {
				echo json_encode(array('status' => '0','message'=> $str_sql));
			}

		}

	}

?>