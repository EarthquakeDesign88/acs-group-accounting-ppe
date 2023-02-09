<?php
	
	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			if(isset($_POST["invid"])) {
				$invid = $_POST["invid"];
			} else {
				$invid = '';
			}

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
			
			if ($invstsINV == '1') {
				if ($_POST["invno"] == '') {
					$code = "NoIV-";
					$year = substr(date("Y")+543,-2);
					$month = date("m");
					$str_sql_noinv = "SELECT MAX(inv_no) AS last_id FROM invoice_tb WHERE inv_type = 1 AND inv_depid = '".$invdepid."'";
					$obj_rs_noinv = mysqli_query($obj_con, $str_sql_noinv);
					$obj_row_noinv = mysqli_fetch_array($obj_rs_noinv);

					// echo $invdepid;

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
					// echo $nextnoinv;
					$invno = $nextnoinv;
				} else {
					$invno = $_POST["invno"];
				}
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
				if($_POST["invEditDate"] == NULL) {
					$invEditDate = date('Y-m-d H:i:s');
				} else {
					$invEditDate = $_POST["invEditDate"];
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
				$invrev = $_POST["invrev"] + 1;
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
				$invyear = $_POST["invyear"];
			} else {
				$invyear = '';
			}

			// echo $invyear . "<br>";

			if(isset($_POST["invmonth"])) {
				$invmonth = $_POST["invmonth"];
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

			if(isset($_POST["invrunnumber"])) {
				$invrunnumber = $_POST["invrunnumber"];
			} else {
				$invrunnumber = '';
			}

			// echo $invRunNumber . "<br>";

			if(isset($_POST["invbalance"])) {
				$invbalance = $_POST["invbalance"];
			} else {
				$invbalance = '0.00';
			}

			$str_sql = "UPDATE invoice_tb SET
			inv_id = '$invid',
			inv_no = '$invno',
			inv_type = '$invstsINV',
			inv_typepcash = '$invtypepcash',
			inv_count = '$invcount', 
			inv_date = '$invdate', 
			inv_duedate = '$invduedate', 
			inv_compid = '$invcompid', 
			inv_payaid = '$invpayaid', 
			inv_description = '$invdesc', 
			inv_description_short = '$invdescShort', 
			inv_subtotalNoVat = '$invsubNoV', 
			inv_subtotal = '$invsub', 
			inv_vatpercent = '$invperc', 
			inv_vat = '$invvat', 
			inv_differencevat = '$invVatDiff', 
			inv_tax1 = '$invtax1', 
			inv_taxpercent1 = '$invtaxpercent1', 
			inv_taxtotal1 = '$invtaxtotal1', 
			inv_differencetax1 = '$invtaxDiff1',
			inv_tax2 = '$invtax2', 
			inv_taxpercent2 = '$invtaxpercent2', 
			inv_taxtotal2 = '$invtaxtotal2', 
			inv_differencetax2 = '$invtaxDiff2',
			inv_tax3 = '$invtax3', 
			inv_taxpercent3 = '$invtaxpercent3', 
			inv_taxtotal3 = '$invtaxtotal3', 
			inv_differencetax3 = '$invtaxDiff3',
			inv_grandtotal = '$invgrand', 
			inv_difference = '$invdiff', 
			inv_netamount = '$invnet', 
			inv_balancetotal = '$invbalance',
			inv_rev = '$invrev', 
			inv_salary = '$invsalary',
			inv_taxrefund = '$invtaxrefund', 
			inv_userid_create = '$invuseridCreate', 
			inv_createdate = '$invCreateDate',
			inv_userid_edit = '$invuseridEdit', 
			inv_editdate = '$invEditDate',  
			inv_statusMgr = '$invstatusMgr', 
			inv_apprMgrno = '$invapprnoMgr', 
			inv_statusCEO = '$invstatusCEO', 
			inv_apprCEOno = '$invapprnoCEO', 
			inv_year = '$invyear', 
			inv_month = '$invmonth', 
			inv_depid = '$invdepid', 
			inv_paymid = '$invpaymid',
			inv_statusPaym = '$invstatusPaym',
			inv_NostatusPaym = '$invNostatusPaym',
			inv_runnumber = '$invrunnumber'
			WHERE inv_id = '$invid'";
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
						$str_sql_ivFile .= "'" . $invid . "')";
						$result_ivFile = mysqli_query($obj_con, $str_sql_ivFile);

						// echo "File : " . $str_sql_ivFile;
					} else {

					}
				}

			} else {

			}

			if($query) {
				echo json_encode(array('status' => '1','message'=> $invno,'compid'=> $invcompid,'invid'=> $invid,'dep'=> $invdepid,'RunNumber'=> $invrunnumber));
			} else {
				echo json_encode(array('status' => '0','message'=> $str_sql));
			}

			mysqli_close($obj_con);

		}

	}

?>