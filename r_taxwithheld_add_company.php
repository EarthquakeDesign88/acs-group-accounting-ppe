<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		if(!empty($_POST)) {

			include 'connect.php';

			$output = '';

			$code = "T";
			$str_sql_twh = "SELECT MAX(twh_id) as last_id FROM taxwithheld_tb";
			$obj_rs_twh = mysqli_query($obj_con, $str_sql_twh);
			$obj_row_twh = mysqli_fetch_array($obj_rs_twh);
			$maxTId = substr($obj_row_twh['last_id'], -5);
			if ($maxTId== "") {
				$maxTId = "00001";
			} else {
				$maxTId = ($maxTId + 1);
				$maxTId = substr("00000".$maxTId, -5);
			}
			$nexttwhid = $code.$maxTId;

			$taxname = mysqli_real_escape_string($obj_con, $_POST["taxname"]);
			$taxaddress = mysqli_real_escape_string($obj_con, $_POST["taxaddress"]);
			$taxtaxno = mysqli_real_escape_string($obj_con, $_POST["taxtaxno"]);
			$taxtel = mysqli_real_escape_string($obj_con, $_POST["taxtel"]);
			$taxfax = mysqli_real_escape_string($obj_con, $_POST["taxfax"]);
			$taxemail = mysqli_real_escape_string($obj_con, $_POST["taxemail"]);
			$taxwebsite = mysqli_real_escape_string($obj_con, $_POST["taxwebsite"]);

			$str_sql = "INSERT INTO taxwithheld_tb (twh_id, twh_name, twh_address, twh_taxno, twh_tel, twh_fax, twh_email, twh_website) VALUES (";
			$str_sql .= "'" . $nexttwhid . "',";
			$str_sql .= "'" . $taxname . "',";
			$str_sql .= "'" . $taxaddress . "',";
			$str_sql .= "'" . $taxtaxno . "',";
			$str_sql .= "'" . $taxtel . "',";
			$str_sql .= "'" . $taxfax . "',";
			$str_sql .= "'" . $taxemail . "',";
			$str_sql .= "'" . $taxwebsite . "')";

			if(mysqli_query($obj_con, $str_sql)) {

				echo '<script type="text/javascript">
						function putValueTax(name,id) {
							$("#searchTaxwithheld").val(name);
							$("#twhid").val(id);
						}
					</script>

					<script type="text/javascript" src="js/script_taxwithheld.js"></script>';
				
				$str_sql_seltwh = "SELECT * FROM taxwithheld_tb WHERE twh_id = '". $nexttwhid ."'";
				$obj_rs_seltwh = mysqli_query($obj_con, $str_sql_seltwh);
				$obj_row_seltwh = mysqli_fetch_array($obj_rs_seltwh);

				$output .= '<label for="searchTaxwithheld" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทหักภาษี ณ ที่จ่าย</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<i class="input-group-text">
										<i class="icofont-company"></i>
									</i>
								</div>
								<input type="search" name="searchTaxwithheld" id="searchTaxwithheld" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="' . $obj_row_seltwh["twh_name"] . '">

								<input type="text" class="form-control d-none" id="twhid" name="twhid" value="' . $obj_row_seltwh["twh_id"] . '">

								<div class="input-group-append">
									<button type="button" class="btn btn-info" onclick="document.getElementById("searchTaxwithheld").value = ""; document.getElementById("twhid").value = "" ">
										<i class="icofont-close-circled"></i>&nbsp;&nbsp;Clear
									</button>
									<button type="button" class="btn btn-primary" data-toggle="modal" data-toggle="modal" data-target="#AddTaxwithheld" data-controls-modal="AddTaxwithheld" data-backdrop="static" data-keyboard="false">
										<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มบริษัท
									</button>
								</div>
							</div>
							<div class="list-group" id="show-listTaxwithheld"></div>';

				//echo '<meta http-equiv="refresh" content="1;url=company.php?pageno=1">';

				//echo json_encode($nextcompid);

			}
			
			echo $output;

		}

		mysqli_close($obj_con);

	}

?>