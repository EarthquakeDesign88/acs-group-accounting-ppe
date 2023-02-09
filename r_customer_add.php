<?php
	
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

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

			$custidEdit = mysqli_real_escape_string($obj_con, $_POST["custidEdit"]);

			if($custidEdit != '') {

				$custid = mysqli_real_escape_string($obj_con, $_POST["custid"]);

				$str_sql = "UPDATE customer_tb SET 
				cust_id = '$custid', 
				cust_name = '$custname', 
				cust_address = '$custaddress', 
				cust_taxno = '$custtaxno',
				cust_tel = '$custtel',
				cust_fax = '$custfax',
				cust_email = '$coustmail',
				cust_website = '$custwebsite'
				WHERE cust_id = '".$custid."'";

			} else {

				$str_sql = "INSERT INTO customer_tb (cust_id, cust_name, cust_address, cust_taxno, cust_tel, cust_fax, cust_email, cust_website) VALUES (";
				$str_sql .= "'" . $nextcustid . "',";
				$str_sql .= "'" . $custname . "',";
				$str_sql .= "'" . $custaddress . "',";
				$str_sql .= "'" . $custtaxno . "',";
				$str_sql .= "'" . $custtel . "',";
				$str_sql .= "'" . $custfax . "',";
				$str_sql .= "'" . $custemail . "',";
				$str_sql .= "'" . $custwebsite . "')";

			}

			if(mysqli_query($obj_con, $str_sql)) {

				$output .= '<script type="text/javascript">
								$(document).ready(function(){
									load_data(1);
									function load_data(page, query = "") {
										$.ajax({
											url:"fetch_customer.php",
											method:"POST",
											data:{page:page, query:query},
											success:function(data) {
												$("#customer_table").html(data);
											}
										});
									}

									$(document).on("click", ".page-link", function(){
										var page = $(this).data("page_number");
										var query = $("#search_box").val();
										load_data(page, query);
									});

									$("#search_box").keyup(function(){
										var query = $("#search_box").val();
										load_data(1, query);
									});
								});
							</script>';

			}
			
			echo $output;

		}

		mysqli_close($obj_con);

	}

?>