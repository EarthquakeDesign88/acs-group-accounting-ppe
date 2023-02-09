<?php
	
	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			if(isset($_POST["invstsINV"])) {
				$invstsINV = $_POST["invstsINV"];
			} else {
				$invstsINV = '';
			}

			// echo $invstsINV . "<br>";

			if(isset($_POST["invdepid"])) {
				$invdepid = $_POST["invdepid"];
			} else {
				$invdepid = '';
			}

			// echo $invdepid . "<br>";
			
			if ($invstsINV == 1) {
				$code = "NoIV-";
				$year = substr(date("Y")+543,-2);
				$month = date("m");
				$str_sql_noinv = "SELECT MAX(inv_no) AS last_id FROM invoice_tb WHERE inv_type = 1 AND inv_depid = '".$invdepid."' AND inv_month = '". $month ."'";
				$obj_rs_noinv = mysqli_query($obj_con, $str_sql_noinv);
				$obj_row_noinv = mysqli_fetch_array($obj_rs_noinv);

				$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '".$invdepid."'";
				$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
				$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

				$maxId = substr($obj_row_noinv['last_id'], -3);

				if ($maxId== "") {
					$maxId = "001";
				} else {
					$maxId = ($maxId + 1);
					$maxId = substr("000".$maxId, -3);
				}

				$nextnoinv = $code.$obj_row_dep["dep_name"].$year.$month.$maxId;
				$invno = $nextnoinv;
			} else {
				$invno = $_POST["invno"];
			}

			// echo $invno . "<br>";

			if(isset($_POST["invcount"])) {
				$invcount = $_POST["invcount"];
			} else {
				$invcount = '';
			}

			// echo $invcount . "<br>";

			if(isset($_POST["invtypepcash"])) {
				$invtypepcash = $_POST["invtypepcash"];
			} else {
				$invtypepcash = '';
			}
			
			// echo $invtypepcash . "<br>";

			if(isset($_POST["invdate"])) {
				if($_POST["invdate"] != NULL) {
					$invdate = $_POST["invdate"];
				} else {
					$invdate = '0000-00-00';
				}
			}

			// echo $invdate . "<br>";
			
			if(isset($_POST["invduedate"])) {
				if($_POST["invduedate"] != NULL) {
					$invduedate = $_POST["invduedate"];
				} else {
					$invduedate = '0000-00-00';
				}
			}

			// echo $invduedate . "<br>";

			if(isset($_POST["invcompid"])) {
				$invcompid = $_POST["invcompid"];
			} else {
				$invcompid = '';
			}

			// echo $invcompid . "<br>";

			if(isset($_POST["invpayaid"])) {
				$invpayaid = $_POST["invpayaid"];
			} else {
				$invpayaid = '';
			}

			// echo $invpayaid . "<br>";

			if(isset($_POST["invdesc"])) {
				$invdesc = $_POST["invdesc"];
			} else {
				$invdesc = '';
			}

			// echo $invdesc . "<br>";
			
			if(isset($_POST["invdescShort"])) {
				$invdescShort = $_POST["invdescShort"];
			} else {
				$invdescShort = '';
			}

			// echo $invdescShort . "<br>";

			if(isset($_POST["invsubNoV"])) {
				$invsubNoV = $_POST["invsubNoV"];
			} else {
				$invsubNoV = '0.00';
			}

			// echo $invsubNoV . "<br>";

			if(isset($_POST["invsub"])) {
				$invsub = $_POST["invsub"];
			} else {
				$invsub = '0.00';
			}

			// echo $invsub . "<br>";

			if(isset($_POST["invperc"])) {
				$invperc =  $_POST["invperc"];
			} else {
				$invperc = '0.00';
			}

			// echo $invperc . "<br>";

			if(isset($_POST["invvat"])) {
				$invvat = $_POST["invvat"];
			} else {
				$invvat = '0.00';
			}

			// echo $invvat . "<br>";

			if(isset($_POST["invVatDiff"])) {
				$invVatDiff =  $_POST["invVatDiff"];
			} else {
				$invVatDiff = '0.00';
			}

			if(isset($_POST["invtax1"])) {
				$invtax1 = $_POST["invtax1"];
			} else {
				$invtax1 = '0.00';
			}

			// echo $invtax1 . "<br>";

			if(isset($_POST["invtaxpercent1"])) {
				$invtaxpercent1 = $_POST["invtaxpercent1"];
			} else {
				$invtaxpercent1 = '0.00';
			}

			// echo $invtaxpercent1 . "<br>";

			if(isset($_POST["invtaxtotal1"])) {
				$invtaxtotal1 = $_POST["invtaxtotal1"];
			} else {
				$invtaxtotal1 = '0.00';
			}

			// echo $invtaxtotal1 . "<br>";

			if(isset($_POST["invtaxDiff1"])) {
				$invtaxDiff1 = $_POST["invtaxDiff1"];
			} else {
				$invtaxDiff1 = '0.00';
			}

			// echo $invtaxDiff1 . "<br>";

			if(isset($_POST["invtax2"])) {
				$invtax2 = $_POST["invtax2"];
			} else {
				$invtax2 = '0.00';
			}

			// echo $invtax2 . "<br>";

			if(isset($_POST["invtaxpercent2"])) {
				$invtaxpercent2 = $_POST["invtaxpercent2"];
			} else {
				$invtaxpercent2 = '0.00';
			}

			// echo $invtaxpercent2 . "<br>";

			if(isset($_POST["invtaxtotal2"])) {
				$invtaxtotal2 = $_POST["invtaxtotal2"];
			} else {
				$invtaxtotal2 = '0.00';
			}

			// echo $invtaxtotal2 . "<br>";

			if(isset($_POST["invtaxDiff2"])) {
				$invtaxDiff2 = $_POST["invtaxDiff2"];
			} else {
				$invtaxDiff2 = '0.00';
			}

			// echo $invtaxDiff2 . "<br>";

			if(isset($_POST["invtax3"])) {
				$invtax3 = $_POST["invtax3"];
			} else {
				$invtax3 = '0.00';
			}

			// echo $invtax3 . "<br>";

			if(isset($_POST["invtaxpercent3"])) {
				$invtaxpercent3 = $_POST["invtaxpercent3"];
			} else {
				$invtaxpercent3 = '0.00';
			}

			// echo $invtaxpercent3 . "<br>";

			if(isset($_POST["invtaxtotal3"])) {
				$invtaxtotal3 = $_POST["invtaxtotal3"];
			} else {
				$invtaxtotal3 = '0.00';
			}

			// echo $invtaxtotal3 . "<br>";

			if(isset($_POST["invtaxDiff3"])) {
				$invtaxDiff3 = $_POST["invtaxDiff3"];
			} else {
				$invtaxDiff3 = '0.00';
			}

			// echo $invtaxDiff3 . "<br>";

			if(isset($_POST["invgrand"])) {
				$invgrand = $_POST["invgrand"];
			} else {
				$invgrand = '0.00';
			}

			// echo $invgrand . "<br>";

			if(isset($_POST["invdiff"])) {
				$invdiff = $_POST["invdiff"];
			} else {
				$invdiff = '0.00';
			}

			// echo $invdiff . "<br>";

			if(isset($_POST["invnet"])) {
				$invnet = $_POST["invnet"];
			} else {
				$invnet = '0.00';
			}

			// echo $invnet . "<br>";

			if(isset($_POST["invuseridCreate"])) {
				$invuseridCreate = $_POST["invuseridCreate"];
			} else {
				$invuseridCreate = '';
			}

			// echo $invuseridCreate . "<br>";

			if(isset($_POST["invCreateDate"])) {
				if($_POST["invCreateDate"] == NULL) {
					$invCreateDate = date('Y-m-d H:i:s');
				} else {
					$invCreateDate = $_POST["invCreateDate"];
				}
			}

			// echo $invCreateDate . "<br>";

			if(isset($_POST["invuseridEdit"])) {
				$invuseridEdit = $_POST["invuseridEdit"];
			} else {
				$invuseridEdit = '';
			}

			// echo $invuseridEdit . "<br>";

			if(isset($_POST["invEditDate"])) {
				if($_POST["invEditDate"] != NULL) {
					$invEditDate = date('Y-m-d H:i:s');
				} else {
					$invEditDate = '0000-00-00 00:00:00';
				}
			}

			// echo $invEditDate . "<br>";

			if(isset($_POST["invstatusMgr"])) {
				$invstatusMgr = '0';
			} else {
				$invstatusMgr = '';
			}

			// echo $invstatusMgr . "<br>";

			if(isset($_POST["invapprnoMgr"])) {
				$invapprnoMgr = $_POST["invapprnoMgr"];
			} else {
				$invapprnoMgr = '';
			}

			// echo $invapprnoMgr . "<br>";

			if(isset($_POST["invstatusCEO"])) {
				$invstatusCEO = '0';
			} else {
				$invstatusCEO = '';
			}

			// echo $invstatusCEO . "<br>";

			if(isset($_POST["invapprnoCEO"])) {
				$invapprnoCEO = $_POST["invapprnoCEO"];
			} else {
				$invapprnoCEO = '';
			}

			// echo $invapprnoCEO . "<br>";

			if(isset($_POST["invrev"])) {
				$invrev = $_POST["invrev"];
			} else {
				$invrev = '';
			}

			// echo $invrev . "<br>";

			if(isset($_POST["invsalary"])) {
				$invsalary = $_POST["invsalary"];
			} else {
				$invsalary = '';
			}

			// echo $invsalary . "<br>";

			if(isset($_POST["invtaxrefund"])) {
				$invtaxrefund = $_POST["invtaxrefund"];
			} else {
				$invtaxrefund = '';
			}

			// echo $invtaxrefund . "<br>";

			if(isset($_POST["invyear"])) {
				$invyear = date("Y")+543;
			} else {
				$invyear = '';
			}

			// echo $invyear . "<br>";

			if(isset($_POST["invmonth"])) {
				$invmonth = date("m");
			} else {
				$invmonth = '';
			}

			// echo $invmonth . "<br>";

			if(isset($_POST["invpaymid"])) {
				$invpaymid = $_POST["invpaymid"];
			} else {
				$invpaymid = '';
			}

			// echo $invpaymid . "<br>";

			if(isset($_POST["invstatusPaym"])) {
				$invstatusPaym = $_POST["invstatusPaym"];
			} else {
				$invstatusPaym = '';
			}

			// echo $invstatusPaym . "<br>";

			if(isset($_POST["invNostatusPaym"])) {
				$invNostatusPaym = $_POST["invNostatusPaym"];
			} else {
				$invNostatusPaym = '';
			}

			// echo $invNostatusPaym . "<br>";

			$RNcode = "RNIV";
			$year = substr(date("Y")+543,-2);
			$month = date("m");
			$str_sql_ivRN = "SELECT MAX(inv_runnumber) AS last_id FROM invoice_tb WHERE inv_month = '". $month ."'";
			$obj_rs_ivRN = mysqli_query($obj_con, $str_sql_ivRN);
			$obj_row_ivRN = mysqli_fetch_array($obj_rs_ivRN);
			$maxRNId = substr($obj_row_ivRN['last_id'], -3);
			if ($maxRNId== "") {
				$maxRNId = "001";
			} else {
				$maxRNId = ($maxRNId + 1);
				$maxRNId = substr("000".$maxRNId, -3);
			}
			$nextRN = $RNcode.$year.$month.$maxRNId;

			if(isset($_POST["invRunNumber"])) {
				$invRunNumber = $nextRN;
			} else {
				$invRunNumber = '';
			}

			// echo $invRunNumber . "<br>";

			$str_sql = "INSERT INTO invoice_tb (inv_no, inv_type, inv_typepcash, inv_count, inv_date, inv_duedate, inv_compid, inv_payaid, inv_description, inv_description_short, inv_subtotalNoVat, inv_subtotal, inv_vatpercent, inv_vat, inv_differencevat, inv_tax1, inv_taxpercent1, inv_taxtotal1, inv_differencetax1, inv_tax2, inv_taxpercent2, inv_taxtotal2, inv_differencetax2, inv_tax3, inv_taxpercent3, inv_taxtotal3, inv_differencetax3, inv_grandtotal, inv_difference, inv_netamount, inv_balancetotal, inv_rev, inv_salary, inv_taxrefund, inv_userid_create, inv_createdate, inv_userid_edit, inv_editdate, inv_statusMgr, inv_apprMgrno, inv_statusCEO, inv_apprCEOno, inv_year, inv_month, inv_depid, inv_paymid, inv_statusPaym, inv_NostatusPaym, inv_runnumber) VALUES (";
			$str_sql .= "'" . $invno . "',";
			$str_sql .= "'" . $invstsINV . "',";
			$str_sql .= "'" . $invtypepcash . "',";
			$str_sql .= "'" . $invcount . "',";
			$str_sql .= "'" . $invdate . "',";
			$str_sql .= "'" . $invduedate . "',";
			$str_sql .= "'" . $invcompid . "',";
			$str_sql .= "'" . $invpayaid . "',";
			$str_sql .= "'" . $invdesc . "',";
			$str_sql .= "'" . $invdescShort . "',";
			$str_sql .= "'" . $invsubNoV . "',";
			$str_sql .= "'" . $invsub . "',";
			$str_sql .= "'" . $invperc . "',";
			$str_sql .= "'" . $invvat . "',";
			$str_sql .= "'" . $invVatDiff . "',";
			$str_sql .= "'" . $invtax1 . "',";
			$str_sql .= "'" . $invtaxpercent1 . "',";
			$str_sql .= "'" . $invtaxtotal1 . "',";
			$str_sql .= "'" . $invtaxDiff1 . "',";
			$str_sql .= "'" . $invtax2 . "',";
			$str_sql .= "'" . $invtaxpercent2 . "',";
			$str_sql .= "'" . $invtaxtotal2 . "',";
			$str_sql .= "'" . $invtaxDiff2 . "',";
			$str_sql .= "'" . $invtax3 . "',";
			$str_sql .= "'" . $invtaxpercent3 . "',";
			$str_sql .= "'" . $invtaxtotal3 . "',";
			$str_sql .= "'" . $invtaxDiff3 . "',";
			$str_sql .= "'" . $invgrand . "',";
			$str_sql .= "'" . $invdiff . "',";
			$str_sql .= "'" . $invnet . "',";
			$str_sql .= "'" . $invnet . "',";
			$str_sql .= "'" . $invrev . "',";
			$str_sql .= "'" . $invsalary . "',";
			$str_sql .= "'" . $invtaxrefund . "',";
			$str_sql .= "'" . $invuseridCreate . "',";
			$str_sql .= "'" . $invCreateDate . "',";
			$str_sql .= "'" . $invuseridEdit . "',";
			$str_sql .= "'" . $invEditDate . "',";
			$str_sql .= "'" . $invstatusMgr . "',";
			$str_sql .= "'" . $invapprnoMgr . "',";
			$str_sql .= "'" . $invstatusCEO . "',";
			$str_sql .= "'" . $invapprnoCEO . "',";
			$str_sql .= "'" . $invyear . "',";
			$str_sql .= "'" . $invmonth . "',";
			$str_sql .= "'" . $invdepid . "',";
			$str_sql .= "'" . $invpaymid . "',";
			$str_sql .= "'" . $invstatusPaym . "',";
			$str_sql .= "'" . $invNostatusPaym . "',";
			$str_sql .= "'" . $invRunNumber . "')";
			$query = mysqli_query($obj_con, $str_sql);

			if ($_FILES['files']['name'] > 0) {

				$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '".$invdepid."'";
				$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
				$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

				$str_sql_iv = "SELECT * FROM invoice_tb WHERE inv_no = '".$invno."'";
				$obj_rs_iv = mysqli_query($obj_con, $str_sql_iv);
				$obj_row_iv = mysqli_fetch_array($obj_rs_iv);

				$invFinvid = $obj_row_iv["inv_id"];

				$path = "invoice/";
				$depname = $obj_row_dep["dep_name"];
				$numrand = (mt_rand());
				$date = date("Ymd");
				$total = count($_FILES['files']['name']);

				for ($i = 0 ; $i < $total; $i++) {
					if ($_FILES['files']['name'][$i] != '') {
						$pathDep = $path . $depname;

						$year_folder = $pathDep . '/' . (date("Y")+543);
						$month_folder = $year_folder . '/' . date("m");	

						!file_exists($pathDep) && mkdir($pathDep , 0777);
						!file_exists($year_folder) && mkdir($year_folder , 0777);
						!file_exists($month_folder) && mkdir($month_folder, 0777);

						$type = strrchr($_FILES['files']['name'][$i],".");
						// $type = strrchr($_FILES['files']['name'],".");
						$newname = $depname.$obj_row_iv["inv_id"].$numrand."-".$i;
						$pathimage = $month_folder;
						$pathCopy = $month_folder . '/' . $newname . $type;

						$result_moveFile = move_uploaded_file($_FILES['files']['tmp_name'][$i],$pathCopy);
						// $result_moveFile = move_uploaded_file($_FILES['files']['tmp_name'],$pathCopy);

						$str_sql_ivFile = "INSERT INTO invoice_file_tb (invF_filename, invF_invid) VALUES (";
						$str_sql_ivFile .= "'" . $pathCopy . "',";
						$str_sql_ivFile .= "'" . $invFinvid . "')";
						$result_ivFile = mysqli_query($obj_con, $str_sql_ivFile);
					}
				}
			}

			if($query) {

				$str_sql_ivid = "SELECT MAX(inv_id) AS inv_id FROM invoice_tb WHERE inv_no = '". $invno  ."'";
				$obj_rs_ivid = mysqli_query($obj_con, $str_sql_ivid);
				$obj_row_ivid = mysqli_fetch_array($obj_rs_ivid);

				$invid = $obj_row_ivid["inv_id"];

				echo json_encode(array('status' => '1','message'=> $invno,'compid'=> $invcompid,'invid'=> $invid,'dep'=> $invdepid,'RunNumber'=> $nextRN));

			} else {
				echo json_encode(array('status' => '0','message'=> $str_sql));
			}

			mysqli_close($obj_con);

		}

	}

?>