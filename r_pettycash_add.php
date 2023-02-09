<?php

	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(!empty($_POST)) {

			include 'connect.php';

			if(isset($_POST["depid"])) {
				$depid = $_POST["depid"];
			} else {
				$depid = '';
			}

			$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '".$depid."'";
			$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
			$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

			// PETTYCASH	
			$year = substr(date("Y")+543, -2);
			$month = date("m");
			$Pcode = $obj_row_dep["dep_name"];
			$str_sql_pcash = "SELECT MAX(pcash_no) as last_id FROM pettycash_tb WHERE pcash_depid = '". $depid ."'";
			$obj_rs_pcash = mysqli_query($obj_con, $str_sql_pcash);
			$obj_row_pcash = mysqli_fetch_array($obj_rs_pcash);
			$maxPId = substr($obj_row_pcash['last_id'], -3);
			if ($maxPId == "") {
				$maxPId = "001";
			} else {
				$maxPId = ($maxPId + 1);
				$maxPId = substr("000".$maxPId, -3);
			}
			$nextpcash = "Cash-".$Pcode."-".$year.$month.$maxPId;

			if(isset($_POST["paymid"])) {
				$paymid = $_POST["paymid"];
			} else {
				$paymid = '';
			}

			if(isset($_POST["pcashDate"])) {
				if($_POST["pcashDate"] != NULL) {
					$pcashDate = $_POST["pcashDate"];
				} else {
					$pcashDate = '0000-00-00';
				}
			}

			if(isset($_POST["invcompid"])) {
				$pcashcompid = $_POST["invcompid"];
			} else {
				$pcashcompid = '';
			}

			if(isset($_POST["invpayaid"])) {
				$pcashpayaid = $_POST["invpayaid"];
			} else {
				$pcashpayaid = '';
			}

			if(isset($_POST["pcashDesc1"])) {
				$pcashDesc1 = $_POST["pcashDesc1"];
				$pcashSub1 = $_POST["pcashSub1"];
				$pcashVatP1 = $_POST["pcashVatP1"];
				$pcashVatHidden1 = $_POST["pcashVatHidden1"];
				$pcashTax1 = $_POST["pcashTax1"];
				$pcashTaxP1 = $_POST["pcashTaxP1"];
				$pcashTaxTHidden1 = $_POST["pcashTaxTHidden1"];
				$pcashGrandHidden1 = $_POST["pcashGrandHidden1"];
				$pcashDiff1 = $_POST["pcashDiff1"];
				$pcashNetHidden1 = $_POST["pcashNetHidden1"];
			} else {
				$pcashDesc1 = '';
				$pcashSub1 = '';
				$pcashVatP1 = '';
				$pcashVatHidden1 = '';
				$pcashTax1 = '';
				$pcashTaxP1 = '';
				$pcashTaxTHidden1 = '';
				$pcashGrandHidden1 = '';
				$pcashDiff1 = '';
				$pcashNetHidden1 = '';
			}

			if(isset($_POST["pcashDesc2"])) {
				$pcashDesc2 = $_POST["pcashDesc2"];
				$pcashSub2 = $_POST["pcashSub2"];
				$pcashVatP2 = $_POST["pcashVatP2"];
				$pcashVatHidden2 = $_POST["pcashVatHidden2"];
				$pcashTax2 = $_POST["pcashTax2"];
				$pcashTaxP2 = $_POST["pcashTaxP2"];
				$pcashTaxTHidden2 = $_POST["pcashTaxTHidden2"];
				$pcashGrandHidden2 = $_POST["pcashGrandHidden2"];
				$pcashDiff2 = $_POST["pcashDiff2"];
				$pcashNetHidden2 = $_POST["pcashNetHidden2"];
			} else {
				$pcashDesc2 = '';
				$pcashSub2 = '';
				$pcashVatP2 = '';
				$pcashVatHidden2 = '';
				$pcashTax2 = '';
				$pcashTaxP2 = '';
				$pcashTaxTHidden2 = '';
				$pcashGrandHidden2 = '';
				$pcashDiff2 = '';
				$pcashNetHidden2 = '';
			}

			if(isset($_POST["pcashDesc3"])) {
				$pcashDesc3 = $_POST["pcashDesc3"];
				$pcashSub3 = $_POST["pcashSub3"];
				$pcashVatP3 = $_POST["pcashVatP3"];
				$pcashVatHidden3 = $_POST["pcashVatHidden3"];
				$pcashTax3 = $_POST["pcashTax3"];
				$pcashTaxP3 = $_POST["pcashTaxP3"];
				$pcashTaxTHidden3 = $_POST["pcashTaxTHidden3"];
				$pcashGrandHidden3 = $_POST["pcashGrandHidden3"];
				$pcashDiff3 = $_POST["pcashDiff3"];
				$pcashNetHidden3 = $_POST["pcashNetHidden3"];
			} else {
				$pcashDesc3 = '';
				$pcashSub3 = '';
				$pcashVatP3 = '';
				$pcashVatHidden3 = '';
				$pcashTax3 = '';
				$pcashTaxP3 = '';
				$pcashTaxTHidden3 = '';
				$pcashGrandHidden3 = '';
				$pcashDiff3 = '';
				$pcashNetHidden3 = '';
			}

			if(isset($_POST["pcashDesc4"])) {
				$pcashDesc4 = $_POST["pcashDesc4"];
				$pcashSub4 = $_POST["pcashSub4"];
				$pcashVatP4 = $_POST["pcashVatP4"];
				$pcashVatHidden4 = $_POST["pcashVatHidden4"];
				$pcashTax4 = $_POST["pcashTax4"];
				$pcashTaxP4 = $_POST["pcashTaxP4"];
				$pcashTaxTHidden4 = $_POST["pcashTaxTHidden4"];
				$pcashGrandHidden4 = $_POST["pcashGrandHidden4"];
				$pcashDiff4 = $_POST["pcashDiff4"];
				$pcashNetHidden4 = $_POST["pcashNetHidden4"];
			} else {
				$pcashDesc4 = '';
				$pcashSub4 = '';
				$pcashVatP4 = '';
				$pcashVatHidden4 = '';
				$pcashTax4 = '';
				$pcashTaxP4 = '';
				$pcashTaxTHidden4 = '';
				$pcashGrandHidden4 = '';
				$pcashDiff4 = '';
				$pcashNetHidden4 = '';
			}

			if(isset($_POST["pcashDesc5"])) {
				$pcashDesc5 = $_POST["pcashDesc5"];
				$pcashSub5 = $_POST["pcashSub5"];
				$pcashVatP5 = $_POST["pcashVatP5"];
				$pcashVatHidden5 = $_POST["pcashVatHidden5"];
				$pcashTax5 = $_POST["pcashTax5"];
				$pcashTaxP5 = $_POST["pcashTaxP5"];
				$pcashTaxTHidden5 = $_POST["pcashTaxTHidden5"];
				$pcashGrandHidden5 = $_POST["pcashGrandHidden5"];
				$pcashDiff5 = $_POST["pcashDiff5"];
				$pcashNetHidden5 = $_POST["pcashNetHidden5"];
			} else {
				$pcashDesc5 = '';
				$pcashSub5 = '';
				$pcashVatP5 = '';
				$pcashVatHidden5 = '';
				$pcashTax5 = '';
				$pcashTaxP5 = '';
				$pcashTaxTHidden5 = '';
				$pcashGrandHidden5 = '';
				$pcashDiff5 = '';
				$pcashNetHidden5 = '';
			}

			if(isset($_POST["pcashuseridCreate"])) {
				$pcashuseridCreate = $_POST["pcashuseridCreate"];
			} else {
				$pcashuseridCreate = '';
			}

			if(isset($_POST["pcashCreateDate"])) {
				if($_POST["pcashCreateDate"] == NULL) {
					$pcashCreateDate = date('Y-m-d H:i:s');
				} else {
					$pcashCreateDate = '0000-00-00 00:00:00';
				}
			}

			if(isset($_POST["pcashuseridEdit"])) {
				$pcashuseridEdit = $_POST["pcashuseridEdit"];
			} else {
				$pcashuseridEdit = '';
			}

			if(isset($_POST["pcashEditDate"])) {
				if($_POST["pcashEditDate"] != NULL) {
					$pcashEditDate = date('Y-m-d H:i:s');
				} else {
					$pcashEditDate = '0000-00-00 00:00:00';
				}
			}

			if(isset($_POST["pcashstatusmgr"])) {
				$pcashstatusmgr = $_POST["pcashstatusmgr"];
			} else {
				$pcashstatusmgr = '';
			}
			
			if(isset($_POST["pcashapprmgrno"])) {
				$pcashapprmgrno = $_POST["pcashapprmgrno"];
			} else {
				$pcashapprmgrno = '';
			}

			if(isset($_POST["pcashrev"])) {
				$pcashrev = $_POST["pcashrev"];
			} else {
				$pcashrev = '';
			}

			if(isset($_POST["pcashtaxrefund"])) {
				$pcashtaxrefund = $_POST["pcashtaxrefund"];
			} else {
				$pcashtaxrefund = '';
			}

			if(isset($_POST["pcashyear"])) {
				$pcashyear = date("Y")+543;
			} else {
				$pcashyear = '';
			}
			
			if(isset($_POST["pcashmonth"])) {
				$pcashmonth = date("m");
			} else {
				$pcashmonth = '';
			}

			if(isset($_POST["pcashPayeeName"])) {
				$pcashPayeeName = $_POST["pcashPayeeName"];
			} else {
				$pcashPayeeName = '';
			}

			if(isset($_POST["pcashPayeeDate"])) {
				if($_POST["pcashPayeeDate"] != NULL) {
					$pcashPayeeDate = $_POST["pcashPayeeDate"];
				} else {
					$pcashPayeeDate = '0000-00-00';
				}
			}
			
			if(isset($_POST["pcashFile"])) {
				$pcashFile = $_POST["pcashFile"];
			} else {
				$pcashFile = '';
			}

			$pcashTaxcid = '';
			$pcashstsTaxc = '';
			$pcashNostsTaxc = '';

			$pcashpurcid = '';
			$pcashstsid = 'STS001';

			$str_sql = "INSERT INTO pettycash_tb (pcash_no, pcash_paymid, pcash_typepay, pcash_date, pcash_compid, pcash_payaid, pcash_depid, pcash_description1, pcash_subtotal1, pcash_vatpercent1, pcash_vat1, pcash_tax1, pcash_taxpercent1, pcash_taxtotal1, pcash_grandtotal1, pcash_difference1, pcash_netamount1, pcash_description2, pcash_subtotal2, pcash_vatpercent2, pcash_vat2, pcash_tax2, pcash_taxpercent2, pcash_taxtotal2, pcash_grandtotal2, pcash_difference2, pcash_netamount2, pcash_description3, pcash_subtotal3, pcash_vatpercent3, pcash_vat3, pcash_tax3, pcash_taxpercent3, pcash_taxtotal3, pcash_grandtotal3, pcash_difference3, pcash_netamount3, pcash_description4, pcash_subtotal4, pcash_vatpercent4, pcash_vat4, pcash_tax4, pcash_taxpercent4, pcash_taxtotal4, pcash_grandtotal4, pcash_difference4, pcash_netamount4, pcash_description5, pcash_subtotal5, pcash_vatpercent5, pcash_vat5, pcash_tax5, pcash_taxpercent5, pcash_taxtotal5, pcash_grandtotal5, pcash_difference5, pcash_netamount5, pcash_userid_create, pcash_createdate, pcash_userid_edit, pcash_editdate, pcash_statusmgr, pcash_apprmgrno, pcash_payeename, pcash_payeedate, pcash_taxrefund, pcash_rev, pcash_file, pcash_year, pcash_month, pcash_taxcid, pcash_statusTaxcer, pcash_NostatusTaxcer, pcash_purcid, pcash_stsid) VALUES (";
			$str_sql .= "'" . $nextpcash . "',";
			$str_sql .= "'" . $paymid . "',";
			$str_sql .= "'1',";
			$str_sql .= "'" . $pcashDate . "',";
			$str_sql .= "'" . $pcashcompid . "',";
			$str_sql .= "'" . $pcashpayaid . "',";
			$str_sql .= "'" . $depid . "',";
			$str_sql .= "'" . $pcashDesc1 . "',";
			$str_sql .= "'" . $pcashSub1 . "',";
			$str_sql .= "'" . $pcashVatP1 . "',";
			$str_sql .= "'" . $pcashVatHidden1 . "',";
			$str_sql .= "'" . $pcashTax1 . "',";
			$str_sql .= "'" . $pcashTaxP1 . "',";
			$str_sql .= "'" . $pcashTaxTHidden1 . "',";
			$str_sql .= "'" . $pcashGrandHidden1 . "',";
			$str_sql .= "'" . $pcashDiff1 . "',";
			$str_sql .= "'" . $pcashNetHidden1 . "',";
			$str_sql .= "'" . $pcashDesc2 . "',";
			$str_sql .= "'" . $pcashSub2 . "',";
			$str_sql .= "'" . $pcashVatP2 . "',";
			$str_sql .= "'" . $pcashVatHidden2 . "',";
			$str_sql .= "'" . $pcashTax2 . "',";
			$str_sql .= "'" . $pcashTaxP2 . "',";
			$str_sql .= "'" . $pcashTaxTHidden2 . "',";
			$str_sql .= "'" . $pcashGrandHidden2 . "',";
			$str_sql .= "'" . $pcashDiff2 . "',";
			$str_sql .= "'" . $pcashNetHidden2 . "',";
			$str_sql .= "'" . $pcashDesc3 . "',";
			$str_sql .= "'" . $pcashSub3 . "',";
			$str_sql .= "'" . $pcashVatP3 . "',";
			$str_sql .= "'" . $pcashVatHidden3 . "',";
			$str_sql .= "'" . $pcashTax3 . "',";
			$str_sql .= "'" . $pcashTaxP3 . "',";
			$str_sql .= "'" . $pcashTaxTHidden3 . "',";
			$str_sql .= "'" . $pcashGrandHidden3 . "',";
			$str_sql .= "'" . $pcashDiff3 . "',";
			$str_sql .= "'" . $pcashNetHidden3 . "',";
			$str_sql .= "'" . $pcashDesc4 . "',";
			$str_sql .= "'" . $pcashSub4 . "',";
			$str_sql .= "'" . $pcashVatP4 . "',";
			$str_sql .= "'" . $pcashVatHidden4 . "',";
			$str_sql .= "'" . $pcashTax4 . "',";
			$str_sql .= "'" . $pcashTaxP4 . "',";
			$str_sql .= "'" . $pcashTaxTHidden4 . "',";
			$str_sql .= "'" . $pcashGrandHidden4 . "',";
			$str_sql .= "'" . $pcashDiff4 . "',";
			$str_sql .= "'" . $pcashNetHidden4 . "',";
			$str_sql .= "'" . $pcashDesc5 . "',";
			$str_sql .= "'" . $pcashSub5 . "',";
			$str_sql .= "'" . $pcashVatP5 . "',";
			$str_sql .= "'" . $pcashVatHidden5 . "',";
			$str_sql .= "'" . $pcashTax5 . "',";
			$str_sql .= "'" . $pcashTaxP5 . "',";
			$str_sql .= "'" . $pcashTaxTHidden5 . "',";
			$str_sql .= "'" . $pcashGrandHidden5 . "',";
			$str_sql .= "'" . $pcashDiff5 . "',";
			$str_sql .= "'" . $pcashNetHidden5 . "',";
			$str_sql .= "'" . $pcashuseridCreate . "',";
			$str_sql .= "'" . $pcashCreateDate . "',";
			$str_sql .= "'" . $pcashuseridEdit . "',";
			$str_sql .= "'" . $pcashEditDate . "',";
			$str_sql .= "'" . $pcashstatusmgr . "',";
			$str_sql .= "'" . $pcashapprmgrno . "',";
			$str_sql .= "'" . $pcashPayeeName . "',";
			$str_sql .= "'" . $pcashPayeeDate . "',";
			$str_sql .= "'" . $pcashtaxrefund . "',";
			$str_sql .= "'" . $pcashrev . "',";
			$str_sql .= "'" . $pcashFile . "',";
			$str_sql .= "'" . $pcashyear . "',";
			$str_sql .= "'" . $pcashmonth . "',";
			$str_sql .= "'" . $pcashTaxcid . "',";
			$str_sql .= "'" . $pcashstsTaxc . "',";
			$str_sql .= "'" . $pcashNostsTaxc . "',";
			$str_sql .= "'" . $pcashpurcid . "',";
			$str_sql .= "'" . $pcashstsid . "')";

			if(isset($_POST["TotalbalanceHidden"])) {
				$invbalance = $_POST["TotalbalanceHidden"];
			} else {
				$invbalance = '0.00';
			}

			$str_sql_upinv = "UPDATE invoice_tb SET inv_balancetotal = '". $invbalance ."' WHERE inv_paymid = '". $paymid ."'";

			if(mysqli_query($obj_con, $str_sql) && mysqli_query($obj_con, $str_sql_upinv)) {

				$str_sql_pc = "SELECT * FROM pettycash_tb WHERE pcash_no = '" . $nextpcash . "' AND pcash_rev = '" . $pcashrev . "'";
				$obj_rs_pc = mysqli_query($obj_con, $str_sql_pc);
				$obj_row_pc = mysqli_fetch_array($obj_rs_pc);

				$pcid = $obj_row_pc["pcash_id"];
				$pcno = $obj_row_pc["pcash_no"];
				$pcrev = $obj_row_pc["pcash_rev"];

				echo json_encode(array('status' => '1','compid' => $pcashcompid,'depid' => $depid ,'pcno' => $pcno,'pcid' => $pcid,'pcrev' => $pcrev));

				// echo "Success : " . $str_sql;

			} else {
				// echo "Error : " . $str_sql;
				echo json_encode(array('status' => '0','message' => $str_sql,'messageUpInv' => $str_sql_upinv));
			}

			mysqli_close($obj_con);

		}

	}

?>