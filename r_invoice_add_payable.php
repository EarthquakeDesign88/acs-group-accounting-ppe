<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		if(!empty($_POST)) {

			include 'connect.php';

			$output = '';

			// PAYABLE เจ้าหนี้		
			// $year = substr(date("Y"), -2);
			$Pcode = "P";
			$str_sql_payaid = "SELECT MAX(paya_id) as last_id FROM payable_tb";
			$obj_rs_payaid = mysqli_query($obj_con, $str_sql_payaid);
			$obj_row_payaid = mysqli_fetch_array($obj_rs_payaid);
			$maxPId = substr($obj_row_payaid['last_id'], -5);
			if ($maxPId== "") {
				$maxPId = "00001";
			} else {
				$maxPId = ($maxPId + 1);
				$maxPId = substr("00000".$maxPId, -5);
			}
			$nextpayaid = $Pcode.$maxPId;

			$payaid = $nextpayaid;
			$payaname = mysqli_real_escape_string($obj_con, $_POST["payaname"]);
			$payaaddress = mysqli_real_escape_string($obj_con, $_POST["payaaddress"]);
			$payataxno = mysqli_real_escape_string($obj_con, $_POST["payataxno"]);
			$payatel = mysqli_real_escape_string($obj_con, $_POST["payatel"]);
			$payafax = mysqli_real_escape_string($obj_con, $_POST["payafax"]);
			$payaemail = mysqli_real_escape_string($obj_con, $_POST["payaemail"]);
			$payawebsite = mysqli_real_escape_string($obj_con, $_POST["payawebsite"]);

			$str_sql = "INSERT INTO payable_tb (paya_id, paya_name, paya_address, paya_taxno, paya_tel, paya_fax, paya_email, paya_website) VALUES (";
			$str_sql .= "'" . $payaid . "',";
			$str_sql .= "'" . $payaname . "',";
			$str_sql .= "'" . $payaaddress . "',";
			$str_sql .= "'" . $payataxno . "',";
			$str_sql .= "'" . $payatel . "',";
			$str_sql .= "'" . $payafax . "',";
			$str_sql .= "'" . $payaemail . "',";
			$str_sql .= "'" . $payawebsite . "')";

			if(mysqli_query($obj_con, $str_sql)) {

				echo '<script type="text/javascript">
						function putValuePaya(name,id) {
							$("#searchPayable").val(name);
							$("#invpayaid").val(id);
						}
					</script>

					<script src="assets/js/script_payable.js"></script>';
				
				$str_sql_selpaya = "SELECT * FROM payable_tb WHERE paya_id = '". $payaid ."'";
				$obj_rs_selpaya = mysqli_query($obj_con, $str_sql_selpaya);
				$obj_row_selpaya = mysqli_fetch_array($obj_rs_selpaya);

				$output .= '<label for="searchPayable" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทผู้ให้บริการ</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<i class="input-group-text">
										<i class="icofont-building"></i>
									</i>
								</div>
								<input type="search" name="searchPayable" id="searchPayable" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="' . $obj_row_selpaya["paya_name"] . '">

								<input type="text" class="form-control d-none" id="invpayaid" name="invpayaid" value="' . $obj_row_selpaya["paya_id"] . '">

								<div class="input-group-append">
									<button type="button" class="btn btn-info" onclick="document.getElementById(\'searchPayable\').value = \'\'; document.getElementById(\'invpayaid\').value = \'\' ">
										<i class="icofont-close-circled"></i>&nbsp;&nbsp;Clear
									</button>
									<button type="button" class="btn btn-primary" data-toggle="modal" data-toggle="modal" data-target="#AddPayable" data-controls-modal="AddPayable" data-backdrop="static" data-keyboard="false">
										<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มบริษัท
									</button>
								</div>
							</div>
							<div class="list-group" id="show-listPaya"></div>';

				//echo '<meta http-equiv="refresh" content="1;url=company.php?pageno=1">';

				//echo json_encode($nextcompid);

			}
			
			echo $output;

		}

		mysqli_close($obj_con);

	}

?>