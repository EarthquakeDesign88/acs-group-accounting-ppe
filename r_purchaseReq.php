<?php

	// header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			if(isset($_POST["cid"])) {
				$cid = $_POST["cid"];
			}else{
				$cid = '';
			}
			if(isset($_POST["dep"])) {
				$dep = $_POST["dep"];
			}else{
				$dep = '';
			}

			$code = "PR";
			$year = substr(date('Y')+543,-2);
			$str_sql_pr = "SELECT MAX(purc_no) AS last_id FROM purchasereq_tb";
			$obj_rs_pr = mysqli_query($obj_con, $str_sql_pr);
			$obj_row_pr = mysqli_fetch_array($obj_rs_pr);
			$maxId = substr($obj_row_pr['last_id'], -4);
			if ($maxId== "") {
				$maxId = "0001";
			} else {
				$maxId = ($maxId + 1);
				$maxId = substr("0000".$maxId, -4);
			}
			$nextprno = $code.$year.$maxId;

			if(isset($_POST["purcdate"])) {
				if($_POST["purcdate"] != NULL) {
					$purcdate = $_POST["purcdate"];
				} else {
					$purcdate = "0000-00-00";
				}
			}

			if(isset($_POST["purcdepid"])) {
				$purcdepid = $_POST["purcdepid"];
			} else {
				$purcdepid = "";
			}

			if(isset($_POST["invcompid"])) {
				$invcompid = $_POST["invcompid"];
			} else {
				$invcompid = "";
			}

			if(isset($_POST["invpayaid"])) {
				$invpayaid = $_POST["invpayaid"];
			} else {
				$invpayaid = "";
			}
			// รวมเงิน
			if(isset($_POST["purcsubtotalIns"])) {
				$purcsubtotalIns = $_POST["purcsubtotalIns"];
			} else {
				$purcsubtotalIns = "";
			}

			if(isset($_POST["purcdisIns"])) {
				$purcdisIns = $_POST["purcdisIns"];
			} else {
				$purcdisIns = "";
			}

			if(isset($_POST["purcvatperIns"])) {
				$purcvatperIns = $_POST["purcvatperIns"];
			} else {
				$purcvatperIns = "";
			}

			if(isset($_POST["purcvatIns"])) {
				$purcvatIns = $_POST["purcvatIns"];
			} else {
				$purcvatIns = "";
			}

			if(isset($_POST["purctotalIns"])) {
				$purctotalIns = $_POST["purctotalIns"];
			} else {
				$purctotalIns = "";
			}

			if(isset($_POST["purcuseridCreate"])) {
				$purcuseridCreate = $_POST["purcuseridCreate"];
			} else {
				$purcuseridCreate = "";
			}

			if(isset($_POST["purcdateCreate"])) {
				if($_POST["purcdateCreate"] == NULL) {
					$purcdateCreate = date('Y-m-d H:i:s');
				} else {
					$purcdateCreate = "0000-00-00 00:00:00";
				}
			}

			/*if(isset($_POST["pruseridEdit"])) {
				$pruseridEdit = $_POST["pruseridEdit"];
			} else {
				$pruseridEdit = "";
			}

			if(isset($_POST["prdateEdit"])) {
				if($_POST["prdateEdit"] == NULL) {
					$prdateEdit = "0000-00-00 00:00:00";
				} else {
					$prdateEdit = $_POST["prdateEdit"];
				}
			}*/

			if(isset($_POST["purcapprPRNo"])) {
				$purcapprPRNo = $_POST["purcapprPRNo"];
			} else {
				$purcapprPRNo = "";
			}

			if(isset($_POST["purcfile"])) {
				$purcfile = $_POST["purcfile"];
			} else {
				$purcfile = "";
			}

			$str_sql = "INSERT INTO purchasereq_tb (purc_no, purc_date, purc_depid, purc_compid, purc_payaid, purc_statusceo, purc_apprceono, purc_subtotal, purc_discount, purc_vatpercent, purc_vat, purc_total, purc_file, purc_userid_create, purc_datecreate, purc_userid_edit, purc_dateedit) VALUES (";
			$str_sql .= "'" . $nextprno . "',";
			$str_sql .= "'" . $purcdate . "',";
			$str_sql .= "'" . $purcdepid . "',";
			$str_sql .= "'" . $invcompid . "',";
			$str_sql .= "'" . $invpayaid . "',";
			$str_sql .= "'0',";
			$str_sql .= "'',";
			$str_sql .= "'" . $purcsubtotalIns . "',";
			$str_sql .= "'" . $purcdisIns . "',";
			$str_sql .= "'" . $purcvatperIns . "',";
			$str_sql .= "'" . $purcvatIns . "',";
			$str_sql .= "'" . $purctotalIns . "',";
			$str_sql .= "'',";
			$str_sql .= "'" . $purcuseridCreate . "',";
			$str_sql .= "'" . $purcdateCreate . "',";
			$str_sql .= "'',";
			$str_sql .= "'')";

			$result = mysqli_query($obj_con, $str_sql) or die ("Error in query: $str_sql " . mysqli_error($obj_con));

			for($i = 1; $i <= 10; $i++) {

				$purcdesc = "purcdesc" . $i;
				if(isset($_POST["$purcdesc"])) {
					$purcdesc = $_POST["$purcdesc"];
				} else {
					$purcdesc = "";
				}
				
				$purcunitpriceIns = "purcunitpriceIns" . $i;
				if(isset($_POST["$purcunitpriceIns"])) {
					$purcunitpriceIns = $_POST["$purcunitpriceIns"];
				} else {
					$purcunitpriceIns = "";
				}

				$purcunitIns = "purcunitIns" . $i;
				if(isset($_POST["$purcunitIns"])) {
					$purcunitIns = $_POST["$purcunitIns"];
				} else {
					$purcunitIns = "";
				}

				$purctotalIns = "purctotalIns" . $i;
				if(isset($_POST["$purctotalIns"])) {
					$purctotalIns = $_POST["$purctotalIns"];
				} else {
					$purctotalIns = "";
				}

				if($purcdesc <> ''){
					$purclist_id = $nextprno.'-'.$i;
					$str_sql_prlist = "INSERT INTO purchasereq_list_tb (purclist_id, purclist_purcno, purclist_description, purclist_unitprice, purclist_unit, purclist_total) VALUES (";
					$str_sql_prlist .= "'" . $purclist_id . "',";
					$str_sql_prlist .= "'" . $nextprno . "',";
					$str_sql_prlist .= "'" . $purcdesc . "',";
					$str_sql_prlist .= "'" . $purcunitpriceIns . "',";
					$str_sql_prlist .= "'" . $purcunitIns . "',";
					$str_sql_prlist .= "'" . $purctotalIns . "')";

					$result = mysqli_query($obj_con, $str_sql_prlist) or die ("Error in query: $str_sql_prlist " . mysqli_error($obj_con));
				}

			}

		}

	}


	if($result){
		// echo "<script type='text/javascript'>";
		// echo "alert('บันทึกสำเร็จ');";
		// // link back
		// echo "window.location = 'purchaseReq_seldep.php?cid=$cid&dep=$dep';";
		// echo "</script>";
		echo json_encode(array('status' => '1','compid'=> $cid,'depid'=> $dep,'purcno'=> $nextprno));
	}else{
		// echo "<script type='text/javascript'>";
		// echo "alert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');";
		// // link back
		// echo "window.location = 'purchaseReq.php?cid=$cid&dep=$dep';";
		// echo "</script>";
		echo json_encode(array('status' => '0','message'=> 'ERROR'));
	}
?>