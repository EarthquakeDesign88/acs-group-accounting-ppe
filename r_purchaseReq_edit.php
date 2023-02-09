<?php
	
	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			if(isset($_POST["purcno"])) {
				$purcno = $_POST["purcno"];
			} else {
				$purcno = '';
			}

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
				if($_POST["purcdateCreate"] != NULL) {
					$purcdateCreate = $_POST["purcdateCreate"];
				} else {
					$purcdateCreate = "0000-00-00 00:00:00";
				}
			}

			if(isset($_POST["pruseridEdit"])) {
				$pruseridEdit = $_POST["pruseridEdit"];
			} else {
				$pruseridEdit = "";
			}

			if(isset($_POST["purcdateEdit"])) {
				if($_POST["purcdateEdit"] == NULL) {
					$purcdateEdit = date('Y-m-d H:i:s');
				} else {
					$purcdateEdit = "0000-00-00 00:00:00";
				}
			}

			if(isset($_POST["purcstsPRNo"])) {
				$purcstsPRNo = $_POST["purcstsPRNo"];
			} else {
				$purcstsPRNo = "";
			}

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

			$str_sql = "UPDATE purchasereq_tb SET 
			purc_no = '$purcno', 
			purc_date = '$purcdate', 
			purc_depid = '$purcdepid', 
			purc_compid = '$invcompid', 
			purc_payaid = '$invpayaid', 
			purc_statusceo = '$purcstsPRNo', 
			purc_apprceono = '$purcapprPRNo', 
			purc_subtotal = '$purcsubtotalIns', 
			purc_discount = '$purcdisIns', 
			purc_vatpercent = '$purcvatperIns', 
			purc_vat = '$purcvatIns', 
			purc_total = '$purctotalIns', 
			purc_file = '$purcfile', 
			purc_userid_create = '$purcuseridCreate', 
			purc_datecreate = '$purcdateCreate', 
			purc_userid_edit = '$pruseridEdit', 
			purc_dateedit = '$purcdateEdit'
			WHERE purc_no = '$purcno'";
			$result = mysqli_query($obj_con, $str_sql) or die ("Error in query: $str_sql " . mysqli_error($obj_con));

			for($i = 1; $i <= 10; $i++) {

				$purclistid = "purclistid" . $i;
				if(isset($_POST["$purclistid"])) {
					$purclistid = $_POST["$purclistid"];
				} else {
					$purclistid = '';
				}

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

				if($purcdesc <> '') {
					if($purclistid <> '') {
						$str_sql_prlist = "UPDATE purchasereq_list_tb SET 
						purclist_id = '$purclistid',
						purclist_purcno = '$purcno',
						purclist_description = '$purcdesc',
						purclist_unitprice = '$purcunitpriceIns',
						purclist_unit = '$purcunitIns',
						purclist_total = '$purctotalIns' 
						WHERE purclist_id = '$purclistid'";
						$result = mysqli_query($obj_con, $str_sql_prlist) or die ("Error in query: $str_sql_prlist " . mysqli_error($obj_con));
					} else {
						$purclistid = $purcno.'-'.$i;
						$str_sql_prlist = "INSERT INTO purchasereq_list_tb (purclist_id, purclist_purcno, purclist_description, purclist_unitprice, purclist_unit, purclist_total) VALUES (";
						$str_sql_prlist .= "'" . $purclistid . "',";
						$str_sql_prlist .= "'" . $purcno . "',";
						$str_sql_prlist .= "'" . $purcdesc . "',";
						$str_sql_prlist .= "'" . $purcunitpriceIns . "',";
						$str_sql_prlist .= "'" . $purcunitIns . "',";
						$str_sql_prlist .= "'" . $purctotalIns . "')";

						$result = mysqli_query($obj_con, $str_sql_prlist) or die ("Error in query: $str_sql_prlist " . mysqli_error($obj_con));
					}
				}

			}

		}

	}

	if($result){
		echo json_encode(array('status' => '1','compid'=> $invcompid,'depid'=> $purcdepid,'purcno'=> $purcno));
	}else{
		echo json_encode(array('status' => '0','message'=> 'ERROR'));
	}

?>