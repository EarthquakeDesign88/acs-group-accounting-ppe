<?php
	
	session_start();
	if(!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {


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

			if (isset($_POST["twhname"])) {
				$twhname = $_POST["twhname"];
			} else {
				$twhname = '';
			}

			if (isset($_POST["twhaddress"])) {
				$twhaddress = $_POST["twhaddress"];
			} else {
				$twhaddress = '';
			}

			if (isset($_POST["twhtaxno"])) {
				$twhtaxno = $_POST["twhtaxno"];
			} else {
				$twhtaxno = '';
			}

			if (isset($_POST["twhtel"])) {
				$twhtel = $_POST["twhtel"];
			} else {
				$twhtel = '';
			}

			if (isset($_POST["twhfax"])) {
				$twhfax = $_POST["twhfax"];
			} else {
				$twhfax = '';
			}

			if (isset($_POST["twhemail"])) {
				$twhemail = $_POST["twhemail"];
			} else {
				$twhemail = '';
			}

			if (isset($_POST["twhwebsite"])) {
				$twhwebsite = $_POST["twhwebsite"];
			} else {
				$twhwebsite = '';
			}

			if (isset($_POST["twhid_edit"])) {
				$twhid_edit = $_POST["twhid_edit"];
			} else {
				$twhid_edit = '';
			}

			if($twhid_edit != '') {

				if (isset($_POST["twhid"])) {
					$twhid = $_POST["twhid"];
				} else {
					$twhid = '';
				}

				$str_sql = "UPDATE taxwithheld_tb SET 
				twh_id = '$twhid', 
				twh_name = '$twhname', 
				twh_address = '$twhaddress', 
				twh_taxno = '$twhtaxno',
				twh_tel = '$twhtel',
				twh_fax = '$twhfax',
				twh_email = '$twhemail',
				twh_website = '$twhwebsite'
				WHERE twh_id = '".$twhid."'";

			} else {

				$str_sql = "INSERT INTO taxwithheld_tb (twh_id, twh_name, twh_address, twh_taxno, twh_tel, twh_fax, twh_email, twh_website) VALUES (";
				$str_sql .= "'" . $nexttwhid . "',";
				$str_sql .= "'" . $twhname . "',";
				$str_sql .= "'" . $twhaddress . "',";
				$str_sql .= "'" . $twhtaxno . "',";
				$str_sql .= "'" . $twhtel . "',";
				$str_sql .= "'" . $twhfax . "',";
				$str_sql .= "'" . $twhemail . "',";
				$str_sql .= "'" . $twhwebsite . "')";

			}

			if(mysqli_query($obj_con, $str_sql)) {

				$output .= '<script type="text/javascript">
								$(document).ready(function(){			
									load_data(1);
									function load_data(page, query = "") {
										$.ajax({
											url:"fetch_taxwithheld.php",
											method:"POST",
											data:{page:page, query:query},
											success:function(data) {
												$("#taxwithheld_table").html(data);
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