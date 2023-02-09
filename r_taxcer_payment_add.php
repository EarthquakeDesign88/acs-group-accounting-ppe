<?php

	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			$compid = $_POST["compid"];

			$depid = $_POST["depid"];

			$sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $depid ."'";
			$rs_dep = mysqli_query($obj_con, $sql_dep);
			$row_dep = mysqli_fetch_array($rs_dep);

			if ($compid == 'C001') {
				$code = "ACS";
			} else {
				$code = $row_dep["dep_name"];
			}

			$year = substr(date("Y")+543, -2);
			$yearTH = date("Y")+543;
			$month = date("m");

			if($month >= '01' && $year >= '65') {
				$str_sql_taxcno = "SELECT MAX(taxc_no) AS last_id FROM taxcertificate_tb WHERE taxc_compid = '".$compid."' AND taxc_year = '".$yearTH."' AND taxc_month = '".$month."'";
			} else {
				if ($compid == 'C014') {
					$str_sql_taxcno = "SELECT MAX(taxc_no) AS last_id FROM taxcertificate_tb WHERE taxc_compid = '".$compid."' AND taxc_year = '".$yearTH."' AND taxc_month = '".$month."'";
				} else {
					$str_sql_taxcno = "SELECT MAX(taxc_no) AS last_id FROM taxcertificate_tb WHERE taxc_compid = '".$compid."'";
				}
			}

			$obj_rs_taxcno = mysqli_query($obj_con, $str_sql_taxcno);
			$obj_row_taxcno = mysqli_fetch_array($obj_rs_taxcno);
			$maxId = substr($obj_row_taxcno['last_id'], -3);


			if ($maxId == "") {
				$maxId = "001";
			} else {
				$maxId = ($maxId + 1);
				$maxId = substr("000".$maxId, -3);
			}

			$nexttaxcno = $code.'-'.$year.$month.$maxId;

		
			$taxdate = $_POST["taxdate"];

			$taxincome = $_POST["taxincome"];

			if (isset($_POST["taxcTF"])) {
				$taxcTF = $_POST["taxcTF"];
			} else {
				$taxcTF = '';
			}

			if (isset($_POST["taxcPayer"])) {
				$taxcPayer = $_POST["taxcPayer"];
			} else {
				$taxcPayer = '';
			}

	

			$twhid = $_POST["twhid"];
			$taxctype = $_POST["taxctype"];


			if (isset($_POST["taxcrev"])) {
				$taxcrev = $_POST["taxcrev"];
			} else {
				$taxcrev = 0;
			}

			if (isset($_POST["taxcuseridcreate"])) {
				$taxcuseridcreate = $_POST["taxcuseridcreate"];
			} else {
				$taxcuseridcreate = '';
			}

			if (isset($_POST["taxcdatecreate"])) {
				if ($_POST["taxcdatecreate"] == NULL) {
					$taxcdatecreate = date('Y-m-d H:i:s');
				} else {
					$taxcdatecreate = $_POST["taxcdatecreate"];
				}
			}
			
			if (isset($_POST["taxcuseridedit"])) {
				$taxcuseridedit = $_POST["taxcuseridedit"];
			} else {
				$taxcuseridedit = '';
			}

			if (isset($_POST["taxcdateedit"])) {
				if ($_POST["taxcdateedit"] == NULL) {
					$taxcdateedit = "0000-00-00 00:00:00";
				} else {
					$taxcdateedit = $_POST["taxcdateedit"];
				}
			}

			$taxcyear = date('Y')+543;
			$taxcmonth = date('m');

			if (isset($_POST["taxcfile"])) {
				$taxcfile = '';
			}

			$str_sql = "INSERT INTO taxcertificate_tb (taxc_no, taxc_date, taxc_income, taxc_compid, taxc_twhid, taxc_type, taxc_depid, taxc_rev, taxc_tfid, taxc_tpid, taxc_year, taxc_month, taxc_file, taxc_userid_create, taxc_createdate, taxc_userid_edit, taxc_editdate) VALUES (";
			$str_sql .= "'" . $nexttaxcno . "',";
			$str_sql .= "'" . $taxdate . "',";
			$str_sql .= "'" . $taxincome . "',";
			$str_sql .= "'" . $compid . "',";
			$str_sql .= "'" . $twhid . "',";
			$str_sql .= "'" . $taxctype . "',";
			$str_sql .= "'" . $depid . "',";
			$str_sql .= "'" . $taxcrev . "',";
			$str_sql .= "'" . $taxcTF . "',";
			$str_sql .= "'" . $taxcPayer . "',";
			$str_sql .= "'" . $taxcyear . "',";
			$str_sql .= "'" . $taxcmonth . "',";
			$str_sql .= "'" . $taxcfile . "',";
			$str_sql .= "'" . $taxcuseridcreate . "',";
			$str_sql .= "'" . $taxcdatecreate . "',";
			$str_sql .= "'" . $taxcuseridedit . "',";
			$str_sql .= "'" . $taxcdateedit . "')";
			$query = mysqli_query($obj_con, $str_sql);

			$str_sql_seltax = "SELECT * FROM taxcertificate_tb WHERE taxc_no = '" . $nexttaxcno . "'";
			$obj_rs_seltax = mysqli_query($obj_con, $str_sql_seltax);
			$obj_row_seltax = mysqli_fetch_array($obj_rs_seltax);

			$paymtaxcid = $obj_row_seltax["taxc_id"];


				$paymid = $_POST["paymid"];

				$taxpaymcode = "TP";
				$str_sql_paymtwh = "SELECT MAX(paym_NostatusTaxcer) as last_id FROM payment_tb";
				$obj_rs_paymtwh = mysqli_query($obj_con, $str_sql_paymtwh);
				$obj_row_paymtwh = mysqli_fetch_array($obj_rs_paymtwh);
				$maxTpaymId = substr($obj_row_paymtwh['last_id'], -5);
				if ($maxTpaymId== "") {
					$maxTpaymId = "00001";
				} else {
					$maxTpaymId = ($maxTpaymId + 1);
					$maxTpaymId = substr("00000".$maxTpaymId, -5);
				}
				$nextPaymtwhid = $taxpaymcode.$maxTpaymId;

				$str_sql_paym = "UPDATE payment_tb SET
				paym_taxcid = '$paymtaxcid',
				paym_statusTaxcer = '1',
				paym_NostatusTaxcer = '$nextPaymtwhid'
				WHERE paym_id = '". $paymid ."'";
				$obj_rs_paym = mysqli_query($obj_con, $str_sql_paym);
			// }
			
			if($query) {

				$str_sql_taxc = "SELECT * FROM taxcertificate_tb WHERE taxc_no = '" . $nexttaxcno . "' AND taxc_rev = '" . $taxcrev . "'";
				$obj_rs_taxc = mysqli_query($obj_con, $str_sql_taxc);
				$obj_row_taxc = mysqli_fetch_array($obj_rs_taxc);

				$id = $obj_row_taxc["taxc_id"];
				$no = $obj_row_taxc["taxc_no"];
				$rev = $obj_row_taxc["taxc_rev"];

				// echo "Success " . $str_sql;
				echo json_encode(array('status' => '1','message'=> $no,'id'=> $id, 'rev'=> $rev,'compid'=> $compid,'depid'=> $depid));
			} else {
				// echo "Error " . $str_sql;
				echo json_encode(array('status' => '0','message'=> $str_sql));
			}

			mysqli_close($obj_con);

		}

	}

?>