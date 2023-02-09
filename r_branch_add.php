<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			$output = '';

			if($_POST["SelBankBrc"]) {
				$SelBankBrc = $_POST["SelBankBrc"];
			} else {
				$SelBankBrc = '';
			}

			if($_POST["branchname"]) {
				$branchname = $_POST["branchname"];
			} else {
				$branchname = '';
			}

			$str_sql_brc = "SELECT MAX(brc_id) AS last_id FROM branch_tb";
			$obj_rs_brc = mysqli_query($obj_con, $str_sql_brc);
			$obj_row_brc = mysqli_fetch_array($obj_rs_brc);
			$maxId = substr($obj_row_brc['last_id'], -5);
			if ($maxId== "") {
				$maxId = "00001";
			} else {
				$maxId = ($maxId + 1);
				$maxId = substr("0000".$maxId, -5);
			}	
			$nextbankid = "BRC".$maxId;

			$str_sql = "INSERT INTO branch_tb (brc_id, brc_name, brc_bankid) VALUES (";
			$str_sql .= "'" . $nextbankid . "',";
			$str_sql .= "'" . $branchname . "',";
			$str_sql .= "'" . $SelBankBrc . "')";

			if(mysqli_query($obj_con, $str_sql)) {

				$output .= '<label>สาขา</label>
							<div class="input-group">
								<select class="custom-select form-control" name="SelBranch" id="SelBranch">
									<option value="" selected disabled>กรุณาเลือกสาขา...</option>';
									$str_sql_brc = "SELECT * FROM branch_tb WHERE brc_bankid = '". $SelBankBrc ."' ORDER BY brc_name ASC";
									$obj_rs_brc = mysqli_query($obj_con, $str_sql_brc);
									while($obj_row_brc = mysqli_fetch_array($obj_rs_brc)) {
							$output .= '<option value="'.$obj_row_brc["brc_id"].'">'.$obj_row_brc["brc_name"].'</option>';
									}

				$output .= '</select>

								<div class="input-group-append">
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddBankBranch" data-backdrop="static" data-keyboard="false" title="เพิ่มธนาคาร">
										<i class="icofont-plus-circle"></i>
										<span class="descbtn">&nbsp;&nbsp;เพิ่มสาขา</span>
									</button>
								</div>
							</div>
							<input type="text" class="form-control d-none" name="brcval" id="brcval">';

							include 'action_script_bank.php';

			}

			echo $output;

		}

		mysqli_close($obj_con);

	}

?>