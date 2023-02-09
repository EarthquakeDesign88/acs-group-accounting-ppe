<?php

	include 'connect.php';

	if (isset($_POST['queryCust'])) {

		$output_c = '';
		$str_sql_c = "SELECT * FROM customer_tb WHERE cust_name LIKE '%".$_POST['queryCust']."%' ORDER BY cust_name LIMIT 10";
		$obj_rs_c = mysqli_query($obj_con, $str_sql_c);

		$output_c = '<ul class="list-unstyled customer">';
		if(mysqli_num_rows($obj_rs_c) > 0) {
			while ($obj_row_c = mysqli_fetch_array($obj_rs_c)) {
				$name_search_c = str_replace($_POST['queryCust'], '<b><font color="#417fe2">'.$_POST['queryCust'].'</font></b>',  $obj_row_c['cust_name']);
				$output_c .= '<li class="list-group-item list-group-item-action border-1 customer" onclick="putValueCust(\''.str_replace("'", "\'", $obj_row_c['cust_name']).'\' , \''.str_replace("'", "\'", $obj_row_c['cust_id']).'\')"><i class="icofont-building pr-3"></i>' . $name_search_c . '</li>';
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