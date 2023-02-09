<?php
	
	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			$output = '';

			$code = "P";
			$year = substr(date("Y")+543,-2);
			$str_sql_proj = "SELECT MAX(proj_id) AS last_id FROM project_tb";
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

			$str_sql = "INSERT INTO project_tb (proj_id, proj_name, proj_address, proj_compid, proj_depid, proj_amount, proj_lesson) VALUES (";
			$str_sql .= "'" . $nextproj . "',";
			$str_sql .= "'" . $projname . "',";
			$str_sql .= "'" . $projaddress . "',";
			$str_sql .= "'" . $compid . "',";
			$str_sql .= "'" . $depid . "',";
			$str_sql .= "'" . $calValue . "',";
			$str_sql .= "'" . $projless . "')";
			$result = mysqli_query($obj_con, $str_sql);

			if($result) {
				echo '<script type="text/javascript">
						function putValueProj(name,id) {
							$("#searchProject").val(name);
							$("#invReprojid").val(id);
						}
					</script>
					
					<script type="text/javascript" src="js/script_project.js"></script>';
				
				$str_sql_selproj = "SELECT * FROM project_tb WHERE proj_id = '". $nextproj ."'";
				$obj_rs_selproj = mysqli_query($obj_con, $str_sql_selproj);
				$obj_row_selproj = mysqli_fetch_array($obj_rs_selproj);

				$output .= '<label for="searchProject" class="mb-1">ชื่อโครงการ</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<i class="input-group-text">
										<i class="icofont-building-alt"></i>
									</i>
								</div>
								<input type="text" class="form-control" name="searchProject" id="searchProject" placeholder="กรอกชื่อโครงการ" value="'. $obj_row_selproj["proj_name"] .'">
								<input type="text" class="form-control d-none" name="invReprojid" id="invReprojid"  value="'. $obj_row_selproj["proj_id"] .'" readonly>
								<div class="input-group-append">
									<button type="button" class="btn btn-info" title="Clear">
										<i class="icofont-close-circled"></i>
										<span class="descbtn">&nbsp;&nbsp;Clear</span>
									</button>
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddProject" data-backdrop="static" data-keyboard="false" title="เพิ่มโครงการ">
										<i class="icofont-plus-circle"></i>
										<span class="descbtn">&nbsp;&nbsp;เพิ่มโครงการ</span>
									</button>
								</div>
							</div>
							<div class="list-group" id="show-listProj"></div>';
			}
			
			echo $output;

		}

		mysqli_close($obj_con);

	}

?>