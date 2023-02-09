<?php
	
	session_start();
	if(!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {


		if(!empty($_POST)) {

			include 'connect.php';

			$output = '';

			// PAYABLE เจ้าหนี้		
			// $year = substr(date("Y"), -2);
			$code = "P";
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

			$nextpayaid = $code.$maxPId;

			// $payaid = mysqli_real_escape_string($obj_con, $_POST["txtpayaid"]);
			$payaname = mysqli_real_escape_string($obj_con, $_POST["payaname"]);
			$payaaddress = mysqli_real_escape_string($obj_con, $_POST["payaaddress"]);
			$payataxno = mysqli_real_escape_string($obj_con, $_POST["payataxno"]);
			$payatel = mysqli_real_escape_string($obj_con, $_POST["payatel"]);
			$payafax = mysqli_real_escape_string($obj_con, $_POST["payafax"]);
			$payaemail = mysqli_real_escape_string($obj_con, $_POST["payaemail"]);
			$payawebsite = mysqli_real_escape_string($obj_con, $_POST["payawebsite"]);

			$payaid_edit = mysqli_real_escape_string($obj_con, $_POST["payaid_edit"]);

			if($payaid_edit != '') {

				$payaid = mysqli_real_escape_string($obj_con, $_POST["txtpayaid"]);

				$str_sql = "UPDATE payable_tb SET 
				paya_id = '$payaid', 
				paya_name = '$payaname', 
				paya_address = '$payaaddress', 
				paya_taxno = '$payataxno',
				paya_tel = '$payatel',
				paya_fax = '$payafax',
				paya_email = '$payaemail',
				paya_website = '$payawebsite'
				WHERE paya_id = '".$payaid."'";

			} else {

				$str_sql = "INSERT INTO payable_tb (paya_id, paya_name, paya_address, paya_taxno, paya_tel, paya_fax, paya_email, paya_website) VALUES (";
				$str_sql .= "'" . $nextpayaid . "',";
				$str_sql .= "'" . $payaname . "',";
				$str_sql .= "'" . $payaaddress . "',";
				$str_sql .= "'" . $payataxno . "',";
				$str_sql .= "'" . $payatel . "',";
				$str_sql .= "'" . $payafax . "',";
				$str_sql .= "'" . $payaemail . "',";
				$str_sql .= "'" . $payawebsite . "')";

			}

			if(mysqli_query($obj_con, $str_sql)) {

				$output .= '<script type="text/javascript">
								$(document).ready(function(){
									load_data(1);
									function load_data(page, query = "") {
										$.ajax({
											url:"fetch_payable.php",
											method:"POST",
											data:{page:page, query:query},
											success:function(data) {
												$("#payable_table").html(data);
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

				// echo '<meta http-equiv="refresh" content="1;url=payable.php?pageno=1">';

				// echo json_encode($nextcompid);

			}
				
			echo $output;

		}

		mysqli_close($obj_con);

	}
	
?>