<?php

	include 'connect.php';

	if (isset($_POST['queryTax'])) {

		$output_t = '';
		$str_sql_t = "SELECT * FROM taxwithheld_tb WHERE twh_name LIKE '%".$_POST['queryTax']."%' ORDER BY twh_name LIMIT 10";
		$obj_rs_t = mysqli_query($obj_con, $str_sql_t);

		$output_t = '<ul class="list-unstyled tax">';
		if(mysqli_num_rows($obj_rs_t) > 0) {
			while ($obj_row_t = mysqli_fetch_array($obj_rs_t)) {
				$name_search_t = str_replace($_POST['queryTax'], '<b><font color="#417fe2">'.$_POST['queryTax'].'</font></b>',  $obj_row_t['twh_name']);
				$output_t .= '<li class="list-group-item list-group-item-action border-1 tax" onclick="putValueTax(\''.str_replace("'", "\'", $obj_row_t['twh_name']).'\' , \''.str_replace("'", "\'", $obj_row_t['twh_id']).'\')"><i class="icofont-building pr-3"></i>' . $name_search_t . '</li>';
			}
		} else {
			$output_t .= '<li class="list-group-item border-1">
							<i class="icofont-building pr-3"></i> ไม่มีข้อมูลบริษัทที่ต้องการค้นหา
						</li>';
		}
		$output_t .= '</ul>';
		echo $output_t; 

	}

?>