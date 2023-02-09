<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		if(!empty($_POST)) {

			include 'connect.php';

			$output = '';

			$code = "CS";
			$str_sql_cust = "SELECT MAX(cust_id) as last_id FROM customer_tb";
			$obj_rs_cust = mysqli_query($obj_con, $str_sql_cust);
			$obj_row_cust = mysqli_fetch_array($obj_rs_cust);
			$maxCId = substr($obj_row_cust['last_id'], -5);
			if ($maxCId== "") {
				$maxCId = "00001";
			} else {
				$maxCId = ($maxCId + 1);
				$maxCId = substr("00000".$maxCId, -5);
			}	

			$nextcustid = $code.$maxCId;

			$custname = mysqli_real_escape_string($obj_con, $_POST["custname"]);
			$custaddress = mysqli_real_escape_string($obj_con, $_POST["custaddress"]);
			$custtaxno = mysqli_real_escape_string($obj_con, $_POST["custtaxno"]);
			$custtel = mysqli_real_escape_string($obj_con, $_POST["custtel"]);
			$custfax = mysqli_real_escape_string($obj_con, $_POST["custfax"]);
			$custemail = mysqli_real_escape_string($obj_con, $_POST["custemail"]);
			$custwebsite = mysqli_real_escape_string($obj_con, $_POST["custwebsite"]);

			$str_sql = "INSERT INTO customer_tb (cust_id, cust_name, cust_address, cust_taxno, cust_tel, cust_fax, cust_email, cust_website) VALUES (";
			$str_sql .= "'" . $nextcustid . "',";
			$str_sql .= "'" . $custname . "',";
			$str_sql .= "'" . $custaddress . "',";
			$str_sql .= "'" . $custtaxno . "',";
			$str_sql .= "'" . $custtel . "',";
			$str_sql .= "'" . $custfax . "',";
			$str_sql .= "'" . $custemail . "',";
			$str_sql .= "'" . $custwebsite . "')";

			if(mysqli_query($obj_con, $str_sql)) {

				echo '<script type="text/javascript">
						function putValueCust(name,id) {
							$("#searchCustomer").val(name);
							$("#custid").val(id);
						}
					</script>

					<script src="assets/js/script_customer.js"></script>';
				
				$str_sql_selcust = "SELECT * FROM customer_tb WHERE cust_id = '". $nextcustid ."'";
				$obj_rs_selcust = mysqli_query($obj_con, $str_sql_selcust);
				$obj_row_selcust = mysqli_fetch_array($obj_rs_selcust);

				$output .= '<label for="searchCustomer" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทผู้รับบริการ</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<i class="input-group-text">
										<i class="icofont-building"></i>
									</i>
								</div>
								<input type="search" name="searchCustomer" id="searchCustomer" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="' . $obj_row_selcust["cust_name"] . '">

								<input type="text" class="form-control d-none" id="custid" name="custid" value="' . $obj_row_selcust["cust_id"] . '">

								<div class="input-group-append">
									<button type="button" class="btn btn-info" onclick="document.getElementById("searchCustomer").value = ""; document.getElementById("custid").value = "" ">
										<i class="icofont-close-circled"></i>&nbsp;&nbsp;Clear
									</button>
									<button type="button" class="btn btn-primary" data-toggle="modal" data-toggle="modal" data-target="#AddCustomer" data-controls-modal="AddCustomer" data-backdrop="static" data-keyboard="false">
										<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มบริษัท
									</button>
								</div>
							</div>
							<div class="list-group" id="show-listCust"></div>';

				//echo '<meta http-equiv="refresh" content="1;url=company.php?pageno=1">';

				//echo json_encode($nextcompid);

			}
			
			echo $output;

		}

		mysqli_close($obj_con);

	}

?>