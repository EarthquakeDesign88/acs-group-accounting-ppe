<?php

	include 'connect.php';

	if (isset($_POST['query_list'])) {

		$output_c = '';
		$str_sql_c = "SELECT DISTINCT(list_desc) FROM taxpurchaselist_tb WHERE list_desc LIKE '%".$_POST['query_list']."%' ORDER BY list_desc LIMIT 10";
		$obj_rs_c = mysqli_query($obj_con, $str_sql_c);

		$output_c = '<ul class="list-unstyled list_desc">';
		if(mysqli_num_rows($obj_rs_c) > 0) {
			while ($obj_row_c = mysqli_fetch_array($obj_rs_c)) {
				$name_search_c = str_replace($_POST['query_list'], '<b><font color="#417fe2">'.$_POST['query_list'].'</font></b>',  $obj_row_c['list_desc']);
				$output_c .= '<li class="list-group-item list-group-item-action border-1 list_desc_row text-left"><i class="icofont-building pr-3"></i>' . $name_search_c . '</li>';
			}
		} 

		$output_c .= '</ul>';
		echo $output_c;

	} 

?>