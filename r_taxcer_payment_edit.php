<?php

	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			if (isset($_POST["compid"])) {
				$compid = $_POST["compid"];
			} else {
				$compid = '';
			}

			if (isset($_POST["depid"])) {
				$depid = $_POST["depid"];
			} else {
				$depid = '';
			}

			if (isset($_POST["paymid"])) {
				$paymid = $_POST["paymid"];
			} else {
				$paymid = '';
			}

			if (isset($_POST["taxcid"])) {
				$taxcid = $_POST["taxcid"];
			} else {
				$taxcid = '';
			}

			if (isset($_POST["taxcno"])) {
				$taxcno = $_POST["taxcno"];
			} else {
				$taxcno = '';
			}

			if (isset($_POST["taxcT"])) {
				$taxcT = $_POST["taxcT"];
			} else {
				$taxcT = '';
			}

			if (isset($_POST["taxcdate"])) {
				$taxcdate = $_POST["taxcdate"];
			} else {
				$taxcdate = '';
			}

			if (isset($_POST["taxcincome"])) {
				$taxcincome = $_POST["taxcincome"];
			} else {
				$taxcincome = '';
			}

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

			if (isset($_POST["twhid"])) {
				$twhid = $_POST["twhid"];
			} else {
				$twhid = '';
			}

			if (isset($_POST["taxcrev"])) {
				$taxcrev = $_POST["taxcrev"] + 1;
			} else {
				$taxcrev = '0';
			}

			if (isset($_POST["taxcyear"])) {
				$taxcyear = $_POST["taxcyear"];
			} else {
				$taxcyear = '';
			}

			if (isset($_POST["taxcmonth"])) {
				$taxcmonth = $_POST["taxcmonth"];
			} else {
				$taxcmonth = '';
			}

			if (isset($_POST["taxcfile"])) {
				$taxcfile = $_POST["taxcfile"];
			} else {
				$taxcfile = '';
			}

			if (isset($_POST["taxcuseridcreate"])) {
				$taxcuseridcreate = $_POST["taxcuseridcreate"];
			} else {
				$taxcuseridcreate = '';
			}

			if (isset($_POST["taxcdatecreate"])) {
				if ($_POST["taxcdateedit"] == NULL) {
					$taxcdateedit = "0000-00-00 00:00:00";
				} else {
					$taxcdateedit = $_POST["taxcdateedit"];
				}
			}

			if (isset($_POST["taxcuseridedit"])) {
				$taxcuseridedit = $_POST["taxcuseridedit"];
			} else {
				$taxcuseridedit = '';
			}

			if (isset($_POST["taxcdateedit"])) {
				if ($_POST["taxcdateedit"] == NULL) {
					$taxcdateedit = date('Y-m-d H:i:s');
				} else {
					$taxcdateedit = $_POST["taxcdateedit"];
				}
			}

			$str_sql = "UPDATE taxcertificate_tb SET 
			taxc_no = '$taxcno',
			taxc_date = '$taxcdate',
			taxc_income = '$taxcincome',
			taxc_compid = '$compid',
			taxc_twhid = '$twhid',
			taxc_type = '$taxcT',
			taxc_depid = '$depid',
			taxc_rev = '$taxcrev',
			taxc_tfid = '$taxcTF',
			taxc_tpid = '$taxcPayer',
			taxc_year = '$taxcyear',
			taxc_month = '$taxcmonth',
			taxc_file = '$taxcfile',
			taxc_userid_create = '$taxcuseridcreate',
			taxc_createdate = '$taxcdateedit',
			taxc_userid_edit = '$taxcuseridedit',
			taxc_editdate = '$taxcdateedit' 
			WHERE taxc_id = '$taxcid'";
			$query = mysqli_query($obj_con, $str_sql);

			if($query) {
				echo json_encode(array('status' => '1','message'=> $taxcno,'id'=> $taxcid, 'rev'=> $taxcrev,'compid'=> $compid,'depid'=> $depid,'paymid'=> $paymid,'taxcT'=> $taxcT));
			} else {
				echo json_encode(array('status' => '0','message'=> $str_sql));
			}

			mysqli_close($obj_con);

		}

	}

?>