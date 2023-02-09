<?php
	
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			$output = '';

			// COMPANY บริษัทในเครือ	
			// $year = substr(date("Y"), -2);
			$code = "C";
			$str_sql_compid = "SELECT MAX(comp_id) as last_id FROM company_tb";
			$obj_rs_compid = mysqli_query($obj_con, $str_sql_compid);
			$obj_row_compid = mysqli_fetch_array($obj_rs_compid);
			$maxCId = substr($obj_row_compid['last_id'], -3);
			if ($maxCId== "") {
				$maxCId = "001";
			} else {
				$maxCId = ($maxCId + 1);
				$maxCId = substr("000".$maxCId, -3);
			}	

			$nextcompid = $code.$maxCId;

			$compname = mysqli_real_escape_string($obj_con, $_POST["compname"]);
			$compaddress = mysqli_real_escape_string($obj_con, $_POST["compaddress"]);
			$comptaxno = mysqli_real_escape_string($obj_con, $_POST["comptaxno"]);
			$comptel = mysqli_real_escape_string($obj_con, $_POST["comptel"]);
			$compfax = mysqli_real_escape_string($obj_con, $_POST["compfax"]);
			$compemail = mysqli_real_escape_string($obj_con, $_POST["compemail"]);
			$compwebsite = mysqli_real_escape_string($obj_con, $_POST["compwebsite"]);

			$compid_edit = mysqli_real_escape_string($obj_con, $_POST["compid_edit"]);

			if($compid_edit != '') {

				$compid = mysqli_real_escape_string($obj_con, $_POST["txtcompid"]);

				$str_sql = "UPDATE company_tb SET 
				comp_id = '$compid', 
				comp_name = '$compname', 
				comp_address = '$compaddress', 
				comp_taxno = '$comptaxno',
				comp_tel = '$comptel',
				comp_fax = '$compfax',
				comp_email = '$compemail',
				comp_website = '$compwebsite'
				WHERE comp_id = '".$compid."'";

			} else {

				$str_sql = "INSERT INTO company_tb (comp_id, comp_name, comp_address, comp_taxno, comp_tel, comp_fax, comp_email, comp_website) VALUES (";
				$str_sql .= "'" . $nextcompid . "',";
				$str_sql .= "'" . $compname . "',";
				$str_sql .= "'" . $compaddress . "',";
				$str_sql .= "'" . $comptaxno . "',";
				$str_sql .= "'" . $comptel . "',";
				$str_sql .= "'" . $compfax . "',";
				$str_sql .= "'" . $compemail . "',";
				$str_sql .= "'" . $compwebsite . "')";

			}

			if(mysqli_query($obj_con, $str_sql)) {

				$output .= '<script type="text/javascript">
								$(document).ready(function(){
									load_data(1);
									function load_data(page, query = "") {
										$.ajax({
											url:"fetch_company.php",
											method:"POST",
											data:{page:page, query:query},
											success:function(data) {
												$("#company_table").html(data);
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