<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			$output = '';

			if($_POST["bankname"]) {
				$bankname = $_POST["bankname"];
			} else {
				$bankname = '';
			}

			if($_POST["banknameShort"]) {
				$banknameShort = $_POST["banknameShort"];
			} else {
				$banknameShort = '';
			}

			$str_sql_bank = "SELECT MAX(bank_id) AS last_id FROM bank_tb";
			$obj_rs_bank = mysqli_query($obj_con, $str_sql_bank);
			$obj_row_bank = mysqli_fetch_array($obj_rs_bank);
			$maxId = substr($obj_row_bank['last_id'], -3);
			if ($maxId== "") {
				$maxId = "001";
			} else {
				$maxId = ($maxId + 1);
				$maxId = substr("000".$maxId, -3);
			}	
			$nextbankid = "B".$maxId;

			$str_sql = "INSERT INTO bank_tb (bank_id, bank_name, bank_nameShort) VALUES (";
			$str_sql .= "'" . $nextbankid . "',";
			$str_sql .= "'" . $bankname . "',";
			$str_sql .= "'" . $banknameShort . "')";

			if(mysqli_query($obj_con, $str_sql)) {

				$output .= '<label>ธนาคาร</label>
							<div class="input-group">
								<select class="custom-select form-control" name="SelBank" id="SelBank">
									<option value="" selected disabled>
										กรุณาเลือกธนาคาร...
									</option>';
									$str_sql_b = "SELECT * FROM bank_tb ORDER BY bank_name ASC";
									$obj_rs_b = mysqli_query($obj_con, $str_sql_b);
									while ($obj_row_b = mysqli_fetch_array($obj_rs_b)) {
						$output .= '<option value="'. $obj_row_b["bank_id"] .'">
										'. $obj_row_b["bank_name"] .'
									</option>';
									}
					$output .= '</select>
								<div class="input-group-append">
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddBank" data-backdrop="static" data-keyboard="false" title="เพิ่มธนาคาร">
										<i class="icofont-plus-circle"></i>
										<span class="descbtn">&nbsp;&nbsp;เพิ่มธนาคาร</span>
									</button>
								</div>
							</div>';

							include 'action_script_bank.php';

			}

			echo $output;

		}

		// mysqli_close($obj_con);

	}

?>