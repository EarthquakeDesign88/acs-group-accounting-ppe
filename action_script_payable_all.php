<?php

	include 'connect.php';

	if (isset($_POST['query_payable'])) {

		$output_c = '';
		$str_sql_c = "SELECT * FROM payable_tb WHERE paya_name LIKE '%".$_POST['query_payable']."%' ORDER BY paya_name LIMIT 10";
		$obj_rs_c = mysqli_query($obj_con, $str_sql_c);

		$output_c = '<ul class="list-unstyled payable">';
		if(mysqli_num_rows($obj_rs_c) > 0) {
			while ($obj_row_c = mysqli_fetch_array($obj_rs_c)) {
				$id = $obj_row_c['paya_id'];
				$name_search_c = str_replace($_POST['query_payable'], '<b><font color="#417fe2">'.$_POST['query_payable'].'</font></b>',  $obj_row_c['paya_name']);
				$output_c .= '<li class="list-group-item list-group-item-action border-1 payable_row text-left" id="'. $id .'" onclick="putValueCust(\''.str_replace("'", "\'", $obj_row_c['paya_name']).'\' , \''.str_replace("'", "\'", $obj_row_c['paya_id']).'\')"><i class="icofont-building pr-3"></i>' . $name_search_c . '</li>';
			}
		} else {
			$output_c .= '<li class="list-group-item border-1">
							<i class="icofont-building pr-3"></i> ไม่มีข้อมูลบริษัทที่ต้องการค้นหา
						</li>';
		}
		$output_c .= '</ul>';
		echo $output_c;

	} 

?>