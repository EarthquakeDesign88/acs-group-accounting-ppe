<style type="text/css">
	label {
		font-weight: 700;
	}
</style>
<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		if(isset($_POST["twh_id"])) {  
		
			$output = '';
			include 'connect.php';

			$str_sql = "SELECT * FROM taxwithheld_tb WHERE twh_id = '".$_POST["twh_id"]."'";  
			$obj_rs = mysqli_query($obj_con, $str_sql);  
			$output .= '<div class="table-responsive">  
							<table class="table table-bordered">';  
								while($obj_row = mysqli_fetch_array($obj_rs)) {
						$output .= ' <tr>
										<td width="30%"><label>รหัส</label></td>
										<td width="70%">'.$obj_row["twh_id"].'</td>
									</tr>
									<tr>
										<td width="30%"><label>ชื่อบริษัท</label></td>
										<td width="70%">'.$obj_row["twh_name"].'</td>
									</tr>  
									<tr>
										<td width="30%"><label>ที่อยู่บริษัท</label></td>
										<td width="70%">'.$obj_row["twh_address"].'</td>
									</tr>
									<tr>
										<td width="30%"><label>เลขประจำตัวผู้เสียภาษี</label></td>
										<td width="70%">'.$obj_row["twh_taxno"].'</td>
									</tr>
									<tr>
										<td width="30%"><label>เบอร์โทรศัพท์</label></td>
										<td width="70%">'.$obj_row["twh_tel"].'</td>
									</tr>
									<tr>
										<td width="30%"><label>เบอร์โทรสาร</label></td>
										<td width="70%">'.$obj_row["twh_fax"].'</td>
									</tr>
									<tr>
										<td width="30%"><label>อีเมล</label></td>
										<td width="70%">'.$obj_row["twh_email"].'</td>
									</tr>
									<tr>
										<td width="30%"><label>เว็บไซต์</label></td>
										<td width="70%">'.$obj_row["twh_website"].'</td>
									</tr>';
							}
				$output .= '</table>
						<div>';

			echo $output;

		}

	}

?>