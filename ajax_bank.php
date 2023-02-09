<?php

	include 'connect.php';

	if (isset($_POST['SelBank']) && $_POST['SelBank'] == 'SelBank') {
		$id = $_POST['id'];
		$str_sql_brc = "SELECT * FROM branch_tb WHERE brc_bankid = '$id' ORDER BY brc_name ASC";
		$obj_rs_brc = mysqli_query($obj_con, $str_sql_brc);
		echo '<option value="" selected disabled>กรุณาเลือกสาขา...</option>';
		while ($obj_row_brc = mysqli_fetch_array($obj_rs_brc)) {
			echo '<option value="'.$obj_row_brc['brc_id'].'">'.$obj_row_brc['brc_name'].'</option>';
		}
	}

?>