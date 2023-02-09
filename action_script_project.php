<?php

	include 'connect.php';

	if (isset($_POST['queryProj'])) {

		$output = '';
		$str_sql = "SELECT * FROM project_tb WHERE proj_compid = '". $_POST['queryComp'] ."' AND proj_name LIKE '%".$_POST['queryProj']."%' ORDER BY proj_name LIMIT 10";
		$obj_rs = mysqli_query($obj_con, $str_sql);

		$output = '<ul class="list-unstyled project">';
		if(mysqli_num_rows($obj_rs) > 0) {
			while ($obj_row = mysqli_fetch_array($obj_rs)) {
				$name_search = str_replace($_POST['queryProj'], '<b><font color="#417fe2">'.$_POST['queryProj'].'</font></b>',  $obj_row['proj_name']);
				$output .= '<li class="list-group-item list-group-item-action border-1 project" onclick="putValueProj(\''.str_replace("'", "\'", $obj_row['proj_name']).'\' , \''.str_replace("'", "\'", $obj_row['proj_id']).'\')"><i class="icofont-building pr-3"></i>' . $name_search . '</li>';
			}
		} else {
			$output .= '<li class="list-group-item border-1">
							<i class="icofont-building pr-3"></i> ไม่มีข้อมูลโครงการที่ต้องการค้นหา
						</li>';
		}
		$output .= '</ul>';
		echo $output;

	}

?>