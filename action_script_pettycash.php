<?php

	include 'connect.php';

	if (isset($_POST['queryDep'])) {
	if (isset($_POST['queryPcash'])) {
		

		$output_pc = '';
		$str_sql_pc = "SELECT * FROM invoice_tb AS i 
						INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id 
						INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
						WHERE inv_typepcash = 1 AND inv_depid = '". $_POST['queryDep'] ."' AND paym_no LIKE '%".$_POST['queryPcash']."%' ORDER BY paym_no LIMIT 10";

		$obj_rs_pc = mysqli_query($obj_con, $str_sql_pc);

		$output_pc = '<ul class="list-unstyled pettycash">';
		if(mysqli_num_rows($obj_rs_pc) > 0) {

			while ($obj_row_pc = mysqli_fetch_array($obj_rs_pc)) {
				$name_search_pc = str_replace($_POST['queryPcash'], '<b><font color="#417fe2">'.$_POST['queryPcash'].'</font></b>',  $obj_row_pc['paym_no'] . "&nbsp;&nbsp;" . $obj_row_pc["paya_name"]);

				$output_pc .= '<li class="list-group-item list-group-item-action border-1 pettycash" onclick="putValuePcash(\''.str_replace("'", "\'", $obj_row_pc['paym_no']).'\' , \''.str_replace("'", "\'", $obj_row_pc['paym_id']).'\' , \''.str_replace("'", "\'", $obj_row_pc['inv_balancetotal']).'\' , \''.str_replace("'", "\'", number_format($obj_row_pc['inv_balancetotal'],2)).'\')"><i class="icofont-building pr-3"></i>' . $name_search_pc . '</li>';
			}

		} else {
			$output_pc .= '<li class="list-group-item border-1">
							<i class="icofont-building pr-3"></i> ไม่มีข้อมูลใบสำคัญจ่ายที่ต้องการค้นหา
						</li>';
		}
		$output_pc .= '</ul>';
		echo $output_pc;
	}

	}


?>