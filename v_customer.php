<style type="text/css">
	label {
		font-weight: 700;
	}
</style>
<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(isset($_POST["cust_id"])) {  
		
			$output = '';
			include 'connect.php';

			$str_sql = "SELECT * FROM customer_tb WHERE cust_id = '".$_POST["cust_id"]."'";  
			$obj_rs = mysqli_query($obj_con, $str_sql);  
			$output .= '<div class="table-responsive">  
							<table class="table table-bordered">';  
								while($obj_row = mysqli_fetch_array($obj_rs)) {
						$output .= ' <tr>
										<td width="30%"><label>รหัส</label></td>
										<td width="70%">'.$obj_row["cust_id"].'</td>
									</tr>
									<tr>
										<td width="30%"><label>ชื่อบริษัท</label></td>
										<td width="70%">'.$obj_row["cust_name"].'</td>
									</tr>  
									<tr>
										<td width="30%"><label>ที่อยู่บริษัท</label></td>
										<td width="70%">'.$obj_row["cust_address"].'</td>
									</tr>
									<tr>
										<td width="30%"><label>เลขประจำตัวผู้เสียภาษี</label></td>
										<td width="70%">'.$obj_row["cust_taxno"].'</td>
									</tr>
									<tr>
										<td width="30%"><label>เบอร์โทรศัพท์</label></td>
										<td width="70%">'.$obj_row["cust_tel"].'</td>
									</tr>
									<tr>
										<td width="30%"><label>เบอร์โทรสาร</label></td>
										<td width="70%">'.$obj_row["cust_fax"].'</td>
									</tr>
									<tr>
										<td width="30%"><label>อีเมล</label></td>
										<td width="70%">'.$obj_row["cust_email"].'</td>
									</tr>
									<tr>
										<td width="30%"><label>เว็บไซต์</label></td>
										<td width="70%">'.$obj_row["cust_website"].'</td>
									</tr>';
							}
				$output .= '</table>
						<div>';

			echo $output;

		}

	}

?>