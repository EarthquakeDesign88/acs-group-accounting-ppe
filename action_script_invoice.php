<?php

	include 'connect.php';

	if (isset($_POST['queryComp'])) {

		$output_c = '';
		$str_sql_c = "SELECT * FROM company_tb WHERE comp_name LIKE '%".$_POST['queryComp']."%' ORDER BY comp_name LIMIT 10";
		$obj_rs_c = mysqli_query($obj_con, $str_sql_c);

		$output_c = '<ul class="list-unstyled company">';
		if(mysqli_num_rows($obj_rs_c) > 0) {
			while ($obj_row_c = mysqli_fetch_array($obj_rs_c)) {
				$name_search_c = str_replace($_POST['queryComp'], '<b><font color="#417fe2">'.$_POST['queryComp'].'</font></b>',  $obj_row_c['comp_name']);
				$output_c .= '<li class="list-group-item list-group-item-action border-1 company" onclick="putValueComp(\''.str_replace("'", "\'", $obj_row_c['comp_name']).'\' , \''.str_replace("'", "\'", $obj_row_c['comp_id']).'\')"><i class="icofont-building pr-3"></i>' . $name_search_c . '</li>';
			}
		} else {
			$output_c .= '<li class="list-group-item border-1">
							<i class="icofont-building pr-3"></i> ไม่มีข้อมูลบริษัทที่ต้องการค้นหา
						</li>';
		}
		$output_c .= '</ul>';
		echo $output_c;

	} 

	if (isset($_POST['queryPaya'])) {

		$output_p = '';
		$str_sql_p = "SELECT * FROM payable_tb WHERE paya_name LIKE '%".$_POST['queryPaya']."%' ORDER BY paya_name LIMIT 10";
		$obj_rs_p = mysqli_query($obj_con, $str_sql_p);

		$output_p = '<ul class="list-unstyled payable">';
		if(mysqli_num_rows($obj_rs_p) > 0) {
			while ($obj_row_p = mysqli_fetch_array($obj_rs_p)) {
				$name_search_p = str_replace($_POST['queryPaya'], '<b><font color="#417fe2">'.$_POST['queryPaya'].'</font></b>',  $obj_row_p['paya_name']);
				$output_p .= '<li class="list-group-item list-group-item-action border-1 payable" onclick="putValuePaya(\''.str_replace("'", "\'", $obj_row_p['paya_name']).'\' , \''.str_replace("'", "\'", $obj_row_p['paya_id']).'\')"><i class="icofont-building pr-3"></i>' . $name_search_p . '</li>';
			}
		} else {
			$output_p .= '<li class="list-group-item border-1">
							<i class="icofont-building pr-3"></i> ไม่มีข้อมูลบริษัทที่ต้องการค้นหา
						</li>';
		}
		$output_p .= '</ul>';
		echo $output_p; 

	}

	if (isset($_POST['queryPurc'])) {

		$output_pr = '';
		$str_sql_pr = "SELECT * FROM purchasereq_tb WHERE purc_no LIKE '%".$_POST['queryPurc']."%' ORDER BY purc_no LIMIT 10";
		$obj_rs_pr = mysqli_query($obj_con, $str_sql_pr);

		$output_pr = '<ul class="list-unstyled purchase">';
		if(mysqli_num_rows($obj_rs_pr) > 0) {
			while ($obj_row_pr = mysqli_fetch_array($obj_rs_pr)) {
				$name_search_pr = str_replace($_POST['queryPurc'], '<b><font color="#417fe2">'.$_POST['queryPurc'].'</font></b>',  $obj_row_pr['purc_no']);
				$output_pr .= '<li class="list-group-item list-group-item-action border-1 purchase" onclick="putValuePurc(\''.str_replace("'", "\'", $obj_row_pr['purc_no']).'\')"><i class="icofont-listing-number pr-3"></i>' . $name_search_pr . '</li>';
			}
		} else {
			$output_pr .= '<li class="list-group-item border-1">
							<i class="icofont-listing-number pr-3"></i> ไม่มีข้อมูลขอซื้อ/ขอจ้างที่ต้องการค้นหา
						</li>';
		}
		$output_pr .= '</ul>';
		echo $output_pr; 

	}

?>