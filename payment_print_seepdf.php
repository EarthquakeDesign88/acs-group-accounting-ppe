<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	}else{

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$paymid = $_GET["paymid"];
		$countChk = $_GET["countChk"];
		$paymrev = $_GET["paymrev"];

		function DateThai($strDate) {
			$strYear = substr(date("Y",strtotime($strDate))+543,-2);
			$strMonth= date("n",strtotime($strDate));
			$strDay= date("j",strtotime($strDate));
			$strHour= date("H",strtotime($strDate));
			$strMinute= date("i",strtotime($strDate));
			$strSeconds= date("s",strtotime($strDate));
			$strMonthCut = Array("","01","02","03","04","05","06","07","08","09","10","11","12");
			$strMonthThai=$strMonthCut[$strMonth];
			return "$strDay/$strMonthThai/$strYear";
		}

		function ThDate($strDate) {
			//วันภาษาไทย
			$ThDay = array ("อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัส", "ศุกร์", "เสาร์");
			//เดือนภาษาไทย
			// $ThMonth = array ( "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน","พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม","กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม" );
			$ThMonth = array ("01", "02", "03", "04","05", "06", "07", "08","09", "10", "11", "12");

			//กำหนดคุณสมบัติ
			$week = date("w"); // ค่าวันในสัปดาห์ (0-6)
			$months = date("m")-1; // ค่าเดือน (1-12)
			$day = date("d"); // ค่าวันที่(1-31)
			$years = substr(date("Y")+543,-2); // ค่า ค.ศ.บวก 543 ทำให้เป็น ค.ศ.

			// return "วัน$ThDay[$week] 
			// 	ที่ $day  
			// 	เดือน $ThMonth[$months] 
			// 	พ.ศ. $years";

			return "$day/$ThMonth[$months]/$years";

		}

		function bahtText(float $amount): string {
			[$integer, $fraction] = explode('.', number_format(abs($amount), 2, '.', ''));

			$baht = convert($integer);
			$satang = convert($fraction);

			$output = $amount < 0 ? 'ลบ' : '';
			$output .= $baht ? $baht.'บาท' : '';
			$output .= $satang ? $satang.'สตางค์' : 'ถ้วน';

			return $baht.$satang === '' ? 'ศูนย์บาทถ้วน' : $output;
		}

		function convert(string $number): string {
			$values = ['', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];
			$places = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน'];
			$exceptions = ['หนึ่งสิบ' => 'สิบ', 'สองสิบ' => 'ยี่สิบ', 'สิบหนึ่ง' => 'สิบเอ็ด'];

			$output = '';

			foreach (str_split(strrev($number)) as $place => $value) {
				if ($place % 6 === 0 && $place > 0) {
					$output = $places[6].$output;
				}

				if ($value !== '0') {
					$output = $values[$value].$places[$place % 6].$output;
				}
			}

			foreach ($exceptions as $search => $replace) {
				$output = str_replace($search, $replace, $output);
			}

			return $output;
		}

		
		$invdesc = "";
		$invdescShort = "";
		$invno = "";
		$invsubNoVat = 0;
		$invsubVat = 0;
		$invsubtotal = 0;
		$invvatpercent = 0;
		$invvat = 0;
		$invtax1 = 0;
		$invtax2 = 0;
		$invtax3 = 0;
		$invtaxpercent1 = 0;
		$invtaxpercent2 = 0;
		$invtaxpercent3 = 0;
		$invtaxtotal1 = 0;
		$invtaxtotal2 = 0;
		$invtaxtotal3 = 0;
		$invgrand = 0;
		$invdiff = 0;
		$invnet = 0;
		$invcount = 0;
		$paymyear = "";
		$paymmonth = "";
		$paymfile = "";
		if ($countChk > 1) {
			for ($i = 1; $i <= $countChk; $i++) { 
				
				$invid = "invid" . $i;
				$invid = $_GET["$invid"];

				$str_sql_iv = "SELECT * FROM invoice_tb AS i INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id LEFT JOIN cheque_tb AS cheq ON paym.paym_cheqid = cheq.cheq_id LEFT JOIN bank_tb AS b ON cheq.cheq_bankid = b.bank_id WHERE inv_id = '" . $invid . "'";
				$obj_rs_iv = mysqli_query($obj_con, $str_sql_iv);
				$obj_row = mysqli_fetch_array($obj_rs_iv);

				$invdesc = $obj_row["inv_description"] . " || " . $invdesc;
				$invdescShort = $obj_row["inv_description_short"] . " || " . $invdescShort;
				$invno = $obj_row["inv_no"] . " || " . $invno;
				$invsubNoVat += $obj_row["inv_subtotalNoVat"];
				$invsubVat += $obj_row["inv_subtotal"];
				$invsubtotal += $obj_row["inv_subtotalNoVat"] + $obj_row["inv_subtotal"];
				$invvatpercent = $obj_row["inv_vatpercent"];
				$invvat += $obj_row["inv_vat"];
				$invtax1 += $obj_row["inv_tax1"];
				$invtax2 += $obj_row["inv_tax2"];
				$invtax3 += $obj_row["inv_tax3"];
				$invtaxtotal1 += $obj_row["inv_taxtotal1"];
				$invtaxtotal2 += $obj_row["inv_taxtotal2"];
				$invtaxtotal3 += $obj_row["inv_taxtotal3"];
				$invgrand += $obj_row["inv_grandtotal"];
				$invdiff += $obj_row["inv_difference"];
				$invnet += $obj_row["inv_netamount"];
				$invcount += $obj_row["inv_count"];
				
				$paymyear = $obj_row["paym_year"];
				$paymmonth = $obj_row["paym_month"];
				$paymfile = $obj_row["paym_file"];

				$str_sql_ivpaya = "SELECT DISTINCT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id WHERE inv_paymid = '" . $paymid . "'";
				$obj_rs_ivpaya = mysqli_query($obj_con, $str_sql_ivpaya);
				$obj_row_ivpaya = mysqli_fetch_array($obj_rs_ivpaya);

				$invpaya = $obj_row_ivpaya["paya_name"];

				$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id
						 WHERE user_id = '". $obj_row["paym_userid_create"] ."'";
				$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
				$obj_row_user = mysqli_fetch_array($obj_rs_user);

				//Check invoice only
				$str_sql_iv = "SELECT * FROM invoice_tb WHERE inv_id = '" . $invid . "'";
				$obj_rs_iv = mysqli_query($obj_con, $str_sql_iv);
				$obj_row_iv= mysqli_fetch_array($obj_rs_iv);

				$apprMdepNo = $obj_row_iv["inv_apprMdepno"];
				$apprMgrNo = $obj_row_iv["inv_apprMgrno"];
				$apprCEONo = $obj_row_iv["inv_apprCEOno"];

				//Check Department manager approve
				$str_sql_mdep = "SELECT * FROM approvemdep_tb AS aMdep INNER JOIN invoice_tb AS i ON aMdep.apprMdep_no = i.inv_apprMdepno 
								 INNER JOIN user_tb AS u ON aMdep.apprMdep_userid_create = u.user_id WHERE apprMdep_no = '" . $apprMdepNo . "'";
				$obj_rs_mdep = mysqli_query($obj_con, $str_sql_mdep);
				$obj_row_mdep = mysqli_fetch_array($obj_rs_mdep);

				if ($apprMdepNo != '') {
					$nameMdep = $obj_row_mdep["user_firstname"] . " " . $obj_row_mdep["user_surname"];
					$dateMdep = DateThai($obj_row_mdep["apprMdep_date"]);
					$styMdep = "";
				} else {
					$nameMdep = "";
					$dateMdep = "";
					$styMdep = "margin-top: 25px;";
				}


				//Check CEO approve
				$str_sql_ceo = "SELECT * FROM approveceo_tb AS aCEO INNER JOIN invoice_tb AS i ON aCEO.apprCEO_no = i.inv_apprCEOno 
								INNER JOIN user_tb AS u ON aCEO.apprCEO_userid_create = u.user_id WHERE apprCEO_no = '" . $apprCEONo . "'";
				$obj_rs_ceo = mysqli_query($obj_con, $str_sql_ceo);
				$obj_row_ceo = mysqli_fetch_array($obj_rs_ceo);

				if ($apprCEONo != '') {
					$nameCEO = $obj_row_ceo["user_firstname"] . " " . $obj_row_ceo["user_surname"];
					$dateCEO = DateThai($obj_row_ceo["apprCEO_date"]);
					$styCEO = "";
				} else {
					$nameCEO = "";
					$dateCEO = "";
					$styCEO = "margin-top: 25px;";
				}


				//Check Manager approve
				$str_sql_mgr = "SELECT * FROM approvemgr_tb AS aMgr INNER JOIN invoice_tb AS i ON aMgr.apprMgr_no = i.inv_apprMgrno 
								INNER JOIN user_tb AS u ON aMgr.apprMgr_userid_create = u.user_id WHERE apprMgr_no = '" . $apprMgrNo . "'";
				$obj_rs_mgr = mysqli_query($obj_con, $str_sql_mgr);
				$obj_row_mgr = mysqli_fetch_array($obj_rs_mgr);

				if ($apprMgrNo != '') {
					$nameMgr = $obj_row_mgr["user_firstname"] . " " . $obj_row_mgr["user_surname"];
					$dateMgr = DateThai($obj_row_mgr["apprMgr_date"]);
					$styMgr = '';
				} else {
					$nameMgr = "";
					$dateMgr = "";
					$styMgr = "margin-top: 25px;";
				}

			}

		} else {
			for ($i = 1; $i <= $countChk; $i++) { 
				
				$invid = "invid" . $i;
				$invid = $_GET["$invid"];
				
				$str_sql_iv = "SELECT * FROM invoice_tb AS i INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id LEFT JOIN cheque_tb AS cheq ON paym.paym_cheqid = cheq.cheq_id LEFT JOIN bank_tb AS b ON cheq.cheq_bankid = b.bank_id WHERE inv_id = '" . $invid . "'";
				$obj_rs_iv = mysqli_query($obj_con, $str_sql_iv);
				$obj_row = mysqli_fetch_array($obj_rs_iv);

				$invpaya = $obj_row["paya_name"];
				$invdesc = $obj_row["inv_description"];
				$invdescShort = $obj_row["inv_description_short"];
				$invsubNoVat = $obj_row["inv_subtotalNoVat"];
				$invsubVat = $obj_row["inv_subtotal"];
				$invsubtotal = $invsubNoVat + $invsubVat;
				$invvatpercent = $obj_row["inv_vatpercent"];
				$invvat = $obj_row["inv_vat"];
				$invid = $obj_row["inv_id"];
				$invno = $obj_row["inv_no"];
				$invtax1 = $obj_row["inv_tax1"];
				$invtax2 = $obj_row["inv_tax2"];
				$invtax3 = $obj_row["inv_tax3"];
				$invtaxpercent1 = $obj_row["inv_taxpercent1"];
				$invtaxpercent2 = $obj_row["inv_taxpercent2"];
				$invtaxpercent3 = $obj_row["inv_taxpercent3"];
				$invtaxtotal1 = $obj_row["inv_taxtotal1"];
				$invtaxtotal2 = $obj_row["inv_taxtotal2"];
				$invtaxtotal3 = $obj_row["inv_taxtotal3"];
				$invgrand = $obj_row["inv_grandtotal"];
				$invdiff = $obj_row["inv_difference"];
				$invnet = $obj_row["inv_netamount"];
				$invcount = $obj_row["inv_count"];

				$paymyear = $obj_row["paym_year"];
				$paymmonth = $obj_row["paym_month"];
				$paymfile = $obj_row["paym_file"];

				$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id
						 WHERE user_id = '". $obj_row["paym_userid_create"] ."'";
				$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
				$obj_row_user = mysqli_fetch_array($obj_rs_user);


			    //Check CEO
				$str_sql_ivceo = "SELECT * FROM invoice_tb WHERE inv_id = '" . $invid . "'";
				$obj_rs_ivceo = mysqli_query($obj_con, $str_sql_ivceo);
				$obj_row_ivceo = mysqli_fetch_array($obj_rs_ivceo);

				$apprCEONo = $obj_row_ivceo["inv_apprCEOno"];

				if ($obj_row["inv_statusCEO"] == 1) {

					$str_sql_ceo = "SELECT * FROM approveceo_tb AS aCEO INNER JOIN invoice_tb AS i ON aCEO.apprCEO_no = i.inv_apprCEOno INNER JOIN user_tb AS u ON aCEO.apprCEO_userid_create = u.user_id WHERE apprCEO_no = '" . $apprCEONo . "'";
					$obj_rs_ceo = mysqli_query($obj_con, $str_sql_ceo);
					$obj_row_ceo = mysqli_fetch_array($obj_rs_ceo);

					if ($apprCEONo != '') {
						$nameCEO = $obj_row_ceo["user_firstname"] . " " . $obj_row_ceo["user_surname"];
						$dateCEO = DateThai($obj_row_ceo["apprCEO_date"]);
						$styCEO = "";
					} else {
						$nameCEO = "";
						$dateCEO = "";
						$styCEO = "margin-top: 25px;";
					}

				} else {

					$nameCEO = "";
					$dateCEO = "";
					$styCEO = "margin-top: 25px;";

				}


				//Check Department manager
				$str_sql_ivmdep = "SELECT * FROM invoice_tb WHERE inv_id = '" . $invid . "'";
				$obj_rs_ivmdep = mysqli_query($obj_con, $str_sql_ivmdep);
				$obj_row_ivmdep = mysqli_fetch_array($obj_rs_ivmdep);

				$apprMdepNo = $obj_row_ivmdep["inv_apprMdepno"];

				if ($obj_row["inv_statusMdep"] == 1) {
					$str_sql_mdep = "SELECT * FROM approvemdep_tb AS aMdep INNER JOIN invoice_tb AS i ON aMdep.apprMdep_no = i.inv_apprMdepno 
								    INNER JOIN user_tb AS u ON aMdep.apprMdep_userid_create = u.user_id WHERE apprMdep_no = '" . $apprMdepNo . "'";
					$obj_rs_mdep = mysqli_query($obj_con, $str_sql_mdep);
					$obj_row_mdep = mysqli_fetch_array($obj_rs_mdep);

					if ($apprMdepNo != '') {
						$nameMdep = $obj_row_mdep["user_firstname"] . " " . $obj_row_mdep["user_surname"];
						$dateMdep = DateThai($obj_row_mdep["apprMdep_date"]);
						$styMdep = "";
					} else {
						$nameMdep = "";
						$dateMdep = "";
						$styMdep = "margin-top: 25px;";
					}

				} else {
					$nameMdep = "";
					$dateMdep = "";
					$styMdep = "margin-top: 25px;";

				}


				//Check Manager
				$str_sql_ivmgr = "SELECT * FROM invoice_tb WHERE inv_id = '" . $invid . "'";
				$obj_rs_ivmgr = mysqli_query($obj_con, $str_sql_ivmgr);
				$obj_row_ivmgr = mysqli_fetch_array($obj_rs_ivmgr);

				$apprMgrNo = $obj_row_ivmgr["inv_apprMgrno"];

				if ($obj_row["inv_statusMgr"] == 1) {

					$str_sql_mgr = "SELECT * FROM approvemgr_tb AS aMgr INNER JOIN invoice_tb AS i ON aMgr.apprMgr_no = i.inv_apprMgrno INNER JOIN user_tb AS u ON aMgr.apprMgr_userid_create = u.user_id WHERE apprMgr_no = '" . $apprMgrNo . "'";
					$obj_rs_mgr = mysqli_query($obj_con, $str_sql_mgr);
					$obj_row_mgr = mysqli_fetch_array($obj_rs_mgr);

					if ($apprMgrNo == '') {
						$nameMgr = '';
						$dateMgr = '';
						$styMgr = 'margin-top: 25px;';
					} else {
						$nameMgr = $obj_row_mgr["user_firstname"] . " " . $obj_row_mgr["user_surname"];
						$dateMgr = DateThai($obj_row_mgr["apprMgr_date"]);
						$styMgr = '';
					}

				} else {

					$nameMgr = '';
					$dateMgr = '';
					$styMgr = 'margin-top: 25px;';

				}					

			}	

		}

		// Require composer autoload
		require_once __DIR__ . '/vendor/autoload.php';

		$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
		$fontDirs = $defaultConfig['fontDir'];

		$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
		$fontData = $defaultFontConfig['fontdata'];


		$mpdf = new \Mpdf\Mpdf([
			'useActiveForms' => true,
			'mode' => 'utf-8',
			'format' => 'A5-L',
			// 'format' => 'A5',
			'margin_top' => 14,
			'margin_bottom' => 2,
			'margin_left' => 2,
			'margin_right' => 2,
			'margin_header' => 3,     // 9 margin header
			'margin_footer' => 3,     // 9 margin footer
			'tempDir' => __DIR__ . '/tmp/',
			// 'default_font_size' => 14,
			'fontdata' => $fontData + [
				'sarabun' => [
					'R' => 'THSarabunNew.ttf',
					'I' => 'THSarabunNewItalic.ttf',
					'B' =>  'THSarabunNewBold.ttf',
					'BI' => "THSarabunNewBoldItalic.ttf",
				]
			],
		]);

		$header = '<table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 0px">
					<tr>
						<td width="55%">
							<table cellspacing="0" cellpadding="0" class="table_bordered" width="100%">
								 <tr>
									<td style="font-size: 12pt">
										<b style="font-size: 14pt">
											'. $obj_row["comp_name"] .'
										</b>
									</td>
								</tr>
							</table>
						</td>
						<td width="45%" style="text-align: right;">
							<b style="font-size: 14pt">ใบสำคัญจ่าย</b>
							
						</td>
					</tr>
				</table>
				<table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top: -8px">
					<tr>
						<td width="55%">
							<table cellspacing="0" cellpadding="0" class="table_bordered" width="100%">
								 <tr>
									<td style="font-size: 12pt">
										'. $obj_row["comp_address"] .'
									</td>
								</tr>
							</table>
						</td>
						<td width="45%" style="text-align: right;">
							<table cellspacing="0" cellpadding="0" class="table_bordered" width="100%">
								<tr>
									<td width="8%"><b style="font-size: 12pt">วันที่</b></td>
									<td width="20%" style="border-bottom: 1px dotted #000; text-align: center;">
										'. ThDate(date("d/m/Y")) .'
									</td>
									<td width="8%"><b style="font-size: 12pt">ฝ่าย</b></td>
									<td width="12%" style="border-bottom: 1px dotted #000; text-align: center;">';
										if($cid == 'C001') {
									$header .= $obj_row["dep_name"];
											} else {
									$header .= '-';
											}
							$header .= '</b>
									</td>
									<td width="25%"><b style="font-size: 12pt">เลขที่ใบสำคัญจ่าย</b></td>
									<td width="27%" style="border-bottom: 1px dotted #000; text-align: center;">
										'. $obj_row["paym_no"] .'
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';

		
		$footer = '<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td style="text-align: center; width:22%; border-bottom: 1px dotted #000;">
							'. $nameMdep .'

						</td>
						<td style="text-align: center; width:4%;"></td>
						<td style="text-align: center; width:22%; border-bottom: 1px dotted #000;"> 
							'. $nameMgr .'
							
						</td>
						<td style="text-align: center; width:4%;"></td>
						<td style="text-align: center; width:22%; border-bottom: 1px dotted #000;">
							'. 	$nameCEO .'
						</td>';
			$footer .= '<td style="text-align: center; width:4%;"></td>
						<td style="text-align: center; width:22%; border-bottom: 1px dotted #000;">
							'. $obj_row["paym_payeename"] .'
						</td>
					</tr>
				
					<tr>
						<td style="text-align: center; width:22%;">
							<table cellspacing="0" cellpadding="0" border="0" width="100%">
								<tr>
									<td><b>ฝ่ายอนุมัติ วันที่</b></td>
									<td style="border-bottom: 1px dotted #000; width: 50%">
										'. $dateMdep .'
									</td>
								</tr>
							</table>
						</td>
						<td style="text-align: center; width:4%;"></td>
						<td style="text-align: center; width:22%;">
							<table cellspacing="0" cellpadding="0" border="0" width="100%">
								<tr>
									<td><b>ผู้ตรวจจ่าย วันที่</b></td>
									<td style="border-bottom: 1px dotted #000; width: 40%">
										'. $dateMgr .'
									</td>
								</tr>
							</table>
						</td>
						<td style="text-align: center; width:4%;"></td>
						<td style="text-align: center; width:22%;">
							<table cellspacing="0" cellpadding="0" border="0" width="100%">
								<tr>
									<td><b>ผู้อนุมัติ วันที่</b></td>
									<td style="border-bottom: 1px dotted #000; width: 50%">
										'. $dateCEO .'					
									</td>
								</tr>
							</table>
						</td>
						<td style="text-align: center; width:4%;"></td>
						<td style="text-align: center; width:22%;">
							<table cellspacing="0" cellpadding="0" border="0" width="100%">
								<tr>
									<td><b>ผู้รับเงิน วันที่</b></td>
									<td style="border-bottom: 1px dotted #000; width: 50%">

									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';

		$path = "payment/";
		$dep = $obj_row["dep_name"];

		$year = $obj_row["paym_year"];
		$month = $obj_row["paym_month"];

		// if ($_FILES['inputFile']['name'] != '') {

			$pathDep = $path . $dep;

			$year_folder = $pathDep . '/' . $year;
			$month_folder = $year_folder . '/' . $month;	

			!file_exists($pathDep) && mkdir($pathDep , 0777);
			!file_exists($year_folder) && mkdir($year_folder , 0777);
			!file_exists($month_folder) && mkdir($month_folder, 0777);

			$type = ".pdf";
			if ($paymrev == 0) {
				$newname = $obj_row["paym_no"].$type;
			} else {
				$newname = $obj_row["paym_no"].'-'.$paymrev.$type;
			}
			$pathimage = $month_folder;
			$pathCopy = $month_folder . '/' . $newname;

			// move_uploaded_file($_FILES["inputFile"]["tmp_name"], $pathCopy);

		// }

		$name = $pathCopy;
		
		$str_sql = "UPDATE payment_tb SET
		paym_file = '$name'
		WHERE paym_id = '".$_GET["paymid"]."'";
		$result = mysqli_query($obj_con, $str_sql);

		ob_start(); // Start get HTML code

?>

<!DOCTYPE html>

<div style="width: 100%; height: 100%; position: absolute; left: 0px; top: 0px; background: rgba(33, 33, 33, 0.7);">

	<div class="loader"></div>
	<div style="z-index: 1;position: absolute; left: 42%; top: 53%; display: table-cell; vertical-align: middle; font-size: 24pt; color: #FFF; font-weight: 700">กรุณารอสักครู่...</div>

<head>
	<title>ใบสำคัญจ่าย - <?=$obj_row["paym_no"]?></title>
	<link href="https://fonts.googleapis.com/css?family=Sarabun&display=swap" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="plugins/fontawesome/css/all.min.css">

	<style>
		body {
			font-family: "sarabun";
		}
		table {
			padding: 1px 0px;
			overflow: wrap;
			font-size: 14pt;
		}
		.loader {
			position: absolute;
			left: 50%;
			top: 50%;
			z-index: 1;
			width: 60px;
			height: 60px;
			margin: -76px 0 0 -76px;
			border: 16px solid #f3f3f3;
			border-radius: 50%;
			border-top: 16px solid #3498db;
			-webkit-animation: spin 2s linear infinite;
					animation: spin 2s linear infinite;
		}

		/* Safari */
		@-webkit-keyframes spin {
			0% { -webkit-transform: rotate(0deg); }
			100% { -webkit-transform: rotate(360deg); }
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}
	</style>

</head>
<body>

	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="8%">
				<b style="font-size: 12pt">ชื่อผู้รับเงิน : </b>
			</td>
			<td style="border-bottom: 1px dotted #000;">
				<?=$obj_row["paya_name"]?>
			</td>
		</tr>
	</table>

	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="8%" style="vertical-align: top; padding-top: 5px;">
				<b style="font-size: 12pt;">ชำระค่า : </b>
			</td>
			<td>
				<table cellspacing="0" cellpadding="0" class="table_bordered" width="100%">
					<?php if ($countChk > 1) { ?>
					<tr>
						<td style="border-bottom: 1px dotted #000; font-size: 12pt;">
							<?php
								for ($i = 1; $i <= $countChk; $i++) { 

									$invid = "invid" . $i;
									$invid = $_GET["$invid"];
									
									$str_sql_ivdesc = "SELECT * FROM invoice_tb AS i INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id LEFT JOIN cheque_tb AS cheq ON paym.paym_cheqid = cheq.cheq_id LEFT JOIN bank_tb AS b ON cheq.cheq_bankid = b.bank_id WHERE inv_id = '" . $invid . "'";
									$obj_rs_ivdesc = mysqli_query($obj_con, $str_sql_ivdesc);
									$obj_row_ivdesc = mysqli_fetch_array($obj_rs_ivdesc);
									
							?>

								<?=$obj_row_ivdesc["inv_description"]?>
								&nbsp;
								<span class="invDesc">||</span>
								&nbsp;

							<?php } ?>
						</td>
					</tr>
					<?php } else { ?>
					<tr>
						<td style="border-bottom: 1px dotted #000;">
							<?=$invdesc;?>
						</td>
					</tr>
					<?php } ?>
				</table>
			</td>
		</tr>
	</table>

	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="20%">
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td width="10%">
							<b style="font-size: 12pt">จำนวนใบแจ้งหนี้ : </b>
						</td>
						<td width="30%" style="border-bottom: 1px dotted #000; text-align: center;">
							<?= $invcount; ?>
						</td>
						<td width="5%">
							<b style="font-size: 12pt">ใบ</b>
						</td>
					</tr>
				</table>
			</td>
			<td width="10%"></td>
			<td width="70%">
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td width="10%">
							<b style="font-size: 12pt">จ่ายโดย : </b>
						</td>
						<td width="10%">
							<?php if($obj_row["paym_typepay"] == '1') { ?>
								<img src="image/checkbox.png">
							<?php } else { ?>
								<img src="image/uncheckbox.png">
							<?php } ?>
							<b style="font-size: 12pt">เงินสด</b>
						</td>
						<td width="12%">
							<?php if($obj_row["paym_typepay"] == '2') { ?>
								<img src="image/checkbox.png">
							<?php } else { ?>
								<img src="image/uncheckbox.png">
							<?php } ?>
							<b style="font-size: 12pt">เช็คเลขที่</b>
						</td>
						<td width="16%" style="border-bottom: 1px dotted #000; text-align: center;">
							<?=$obj_row["cheq_no"]?>
						</td>
						<td width="9%" style="padding: 0px 6px; text-align: center;">
							<b style="font-size: 12pt">ลงวันที่</b>
						</td>
						<td width="14%" style="border-bottom: 1px dotted #000; text-align: center;">
							<?php 
								// if ($obj_row["cheq_no"] != '') {
								// 	echo DateThai($obj_row["cheq_date"]);
								// } else { 
									
								// }
							?>
						</td>
						<td width="10%" style="padding: 0px 6px; text-align: center;">
							<b style="font-size: 12pt">ธนาคาร</b>
						</td>
						<td width="19%" style="border-bottom: 1px dotted #000; text-align: center;">
							<?=$obj_row["bank_name"]?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="50%" style="vertical-align: top">
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td width="20%" style="vertical-align: top">
							<b style="font-size: 12pt">เลขที่ใบแจ้งหนี้ : </b>
						</td>
						<td width="80%" style="border-bottom: 1px dotted #000;">
							<?php 
								if ($countChk > 1) {
												
									for ($i = 1; $i <= $countChk; $i++) { 
										$invid = "invid" . $i;
										$invid = $_GET["$invid"];

										$str_sql_ivno = "SELECT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id WHERE inv_id = '" . $invid . "'";
										$obj_rs_ivno = mysqli_query($obj_con, $str_sql_ivno);
										$obj_row_ivno = mysqli_fetch_array($obj_rs_ivno);
														
										if ($obj_row_ivno["inv_type"] == 0) {
											$invno = $obj_row_ivno["inv_no"];
											$displayinvno = "display: block";
										} else {
											$invno = "";
											$displayinvno = "display: none";
										}
							?>
										<?= $invno; ?>
										&nbsp;
										<span class="invIVNO" style="<?= $displayinvno; ?>">||</span>
										&nbsp;
							<?php 
									} 
								} else {

									$invid = $_GET["invid1"];

									$str_sql_ivno = "SELECT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id WHERE inv_id = '" . $invid . "'";
									$obj_rs_ivno = mysqli_query($obj_con, $str_sql_ivno);
									$obj_row_ivno = mysqli_fetch_array($obj_rs_ivno);
														
									if ($obj_row_ivno["inv_type"] == 0) {
										$invno = $obj_row_ivno["inv_no"];
										$displayinvno = "display: block";
										$linebottom = "";
									} else {
										$invno = "";
										$displayinvno = "display: none";
										$linebottom = '
									<tr>
										<td></td>
										<td style="border-bottom: 1px dotted #000; height: 25px;"></td>
									</tr>
									<tr>
										<td></td>
										<td style="border-bottom: 1px dotted #000; height: 25px;"></td>
									</tr>
									<tr>
										<td></td>
										<td style="border-bottom: 1px dotted #000; height: 25px;"></td>
									</tr>
									<tr>
										<td></td>
										<td style="border-bottom: 1px dotted #000; height: 25px;"></td>
									</tr>
									<tr>
										<td></td>
										<td style="border-bottom: 1px dotted #000; height: 25px;"></td>
									</tr>';
									}
							?>
									<?= $linebottom; ?>
									<?= $invno; ?>
									&nbsp;
									<span class="invIVNO" style="<?= $displayinvno; ?>">||</span>
									&nbsp;
												
							<?php } ?>
						</td>
					</tr>
				</table>
			</td>
			<td width="50%" style="vertical-align: top">
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td align="right" width="24%">
							<b style="font-size: 12pt">จำนวนเงิน : </b>
						</td>
						<?php if ($countChk > 1) { ?>
							<td align="right" width="38%" style="border-bottom: 1px dotted #000;">
								<?php 
									if ($invsubNoVat == '0.00' && $invsubVat != '0.00') {
									} else if ($invsubNoVat != '0.00' && $invsubVat == '0.00') {
									} else if ($invsubNoVat != '0.00' && $invsubVat != '0.00') { 
								?>
									<?= number_format($invsubNoVat,2); ?>
									&nbsp;+&nbsp;
									<?= number_format($invsubVat,2); ?>
									&nbsp;=
								<?php } ?>
							</td>
							<td align="right" width="30%" style="border-bottom: 1px dotted #000;">
								<?=number_format($invsubtotal,2)?>
							</td>
						<?php } else { ?>
							<td align="right" width="38%" style="border-bottom: 1px dotted #000;">
								<?php 
									if ($invsubNoVat == '0.00' && $invsubVat != '0.00') { 
									} else if ($invsubNoVat != '0.00' && $invsubVat == '0.00') {
									} else if ($invsubNoVat != '0.00' && $invsubVat != '0.00') { 
								?>
									<?= number_format($invsubNoVat,2); ?>
									&nbsp;+&nbsp;
									<?= number_format($invsubVat,2); ?>
									&nbsp;=
								<?php } ?>
							</td>
							<td align="right" width="30%" style="border-bottom: 1px dotted #000;">
								<?=number_format($invsubtotal,2)?>
							</td>
						<?php } ?>
						<td align="center" width="8%">
							<b style="font-size: 12pt">บาท</b>
						</td>
					</tr>

					<tr>
						<td align="right" width="24%">
							<b style="font-size: 12pt">ภาษีมูลค่าเพิ่ม : </b>
						</td>
						<?php if ($countChk > 1) { ?>
							<td align="right" width="38%" style="border-bottom: 1px dotted #000;">
								<?php 
									if ($invvatpercent == '0.00') { 
									} else if ($invvatpercent != '0.00') {
								?>
									<?= number_format($invvatpercent,2); ?>%
								<?php } ?>
							</td>
							<?php if ($invvat == '0.00') { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000; height: 24px;">
								</td>
							<?php } else { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000;">
									<?=number_format($invvat,2)?>
								</td>
							<?php } ?>
						<?php } else { ?>
							<td align="right" width="38%" style="border-bottom: 1px dotted #000;">
								<?php 
									if ($invvatpercent == '0.00') { 
									} else if ($invvatpercent != '0.00') {
								?>
									<?= number_format($invvatpercent,2); ?>%
								<?php } ?>
							</td>
							<?php if ($invvat == '0.00') { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000; height: 24px;">
								</td>
							<?php } else { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000;">
									<?=number_format($invvat,2)?>
								</td>
							<?php } ?>
						<?php } ?>
						<td align="center" width="8%">
							<b style="font-size: 12pt">บาท</b>
						</td>
					</tr>

					<tr>
						<td align="right" width="24%">
							<b style="font-size: 12pt">หักภาษี ณ ที่จ่าย : </b>
						</td>
						<?php if ($countChk > 1) { ?>
							<td align="right" width="38%" style="border-bottom: 1px dotted #000;">
							</td>
							<?php if ($invtax1 == '0.00') { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000; height: 24px;">
								</td>
							<?php } else { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000;">
									<?=number_format($invtaxtotal1,2)?>
								</td>
							<?php } ?>
						<?php } else { ?>
							<td align="right" width="38%" style="border-bottom: 1px dotted #000;">
								<?php 
									if ($invtaxpercent1 == '0.00') {
									} else if ($invtaxpercent1 != '0.00') {
								?>
									<?= number_format($invtax1,2); ?> * 
									<?= number_format($invtaxpercent1,2); ?>%
								<?php } ?>
							</td>
							<?php if ($invtax1 == '0.00') { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000; height: 24px;">
								</td>
							<?php } else { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000;">
									<?=number_format($invtaxtotal1,2)?>
								</td>
							<?php } ?>
						<?php } ?>
						<td align="center" width="8%">
							<b style="font-size: 12pt">บาท</b>
						</td>
					</tr>

					<tr>
						<td align="right" width="24%">
							<b style="font-size: 12pt"></b>
						</td>
						<?php if ($countChk > 1) { ?>
							<td align="right" width="38%" style="border-bottom: 1px dotted #000;">
								
							</td>
							<?php if ($invtax2 == '0.00') { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000; height: 24px;">
								</td>
							<?php } else { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000;">
									<?=number_format($invtaxtotal2,2)?>
								</td>
							<?php } ?>
						<?php } else { ?>
							<td align="right" width="38%" style="border-bottom: 1px dotted #000;">
								<?php 
									if ($invtaxpercent2 == '0.00') {
									} else if ($invtaxpercent2 != '0.00') {
								?>
									<?= number_format($invtax2,2); ?> * 
									<?= number_format($invtaxpercent2,2); ?>%
								<?php } ?>
							</td>
							<?php if ($invtax2 == '0.00') { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000; height: 24px;">
								</td>
							<?php } else { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000;">
									<?=number_format($invtaxtotal2,2)?>
								</td>
							<?php } ?>
						<?php } ?>
						<td align="center" width="8%">
							<b style="font-size: 12pt">บาท</b>
						</td>
					</tr>

					<tr>
						<td align="right" width="24%">
							<b style="font-size: 12pt"></b>
						</td>
						<?php if ($countChk > 1) { ?>
							<td align="right" width="38%" style="border-bottom: 1px dotted #000;">
								
							</td>
							<?php if ($invtax3 == '0.00') { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000; height: 24px;">
								</td>
							<?php } else { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000;">
									<?=number_format($invtaxtotal3,2)?>
								</td>
							<?php } ?>
						<?php } else { ?>
							<td align="right" width="38%" style="border-bottom: 1px dotted #000;">
								<?php 
									if ($invtaxpercent3 == '0.00') {
									} else if ($invtaxpercent3 != '0.00') {
								?>
									<?= number_format($invtax3,2); ?> * 
									<?= number_format($invtaxpercent3,2); ?>%
								<?php } ?>
							</td>
							<?php if ($invtax3 == '0.00') { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000; height: 24px;">
								</td>
							<?php } else { ?>
								<td align="right" width="30%" style="border-bottom: 1px dotted #000;">
									<?=number_format($invtaxtotal3,2)?>
								</td>
							<?php } ?>
						<?php } ?>
						<td align="center" width="8%">
							<b style="font-size: 12pt">บาท</b>
						</td>
					</tr>

					<tr>
						<td align="right" width="24%">
							<b style="font-size: 12pt">ยอดชำระสุทธิ : </b>
						</td>
						<td align="right" width="38%" style="border-bottom: 1px dotted #000;">
							<?php 
								if ($invdiff == '0.00') {
								} else if ($invdiff != '0.00') {
							?>
								<?=number_format($invgrand,2)?>
								&nbsp;+&nbsp;
								<?=number_format($invdiff,2)?>&nbsp;=
							<?php } ?>
						</td>
						<td align="right" width="30%" style="border-bottom: 1px dotted #000;">
							<?=number_format($invnet,2)?>
						</td>
						<td align="center" width="8%">
							<b style="font-size: 12pt">บาท</b>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<table cellspacing="0" cellpadding="0" border="0" width="100%" style="border: 1px solid #000; padding: 2px 3px;">
		<tr>
			<td width="50%" style="vertical-align: top;">
				<b style="font-size: 12pt">ตัวอักษร&nbsp;&nbsp;&nbsp;:</b>&nbsp;&nbsp;<?= bahtText($invnet) ?>
			</td>
		</tr>
	</table>

	<style type="text/css">
		table tr.de-credit th {
			border: 1px solid #000;
			padding: 3px 0px;
		}
		table tr.de-credit td {
			border-left: 1px solid #000;
			border-bottom: 1px dotted #000;
			padding: 10px 0px;
		}
	</style>


	<table cellspacing="0" cellpadding="0" width="100%" style="margin-top: 3px; border: 1px solid #000; border-collapse:collapse;">
		<tr class="de-credit">
			<th style="border-bottom: 1px solid #000;">รายการ</th>
			<th style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" width="15%">รหัสบัญชี</th>
			<th style="border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;" width="20%">ลูกหนี้</th>
			<th style="border-bottom: 1px solid #000;" width="20%">เจ้าหนี้</th>
		</tr>
		<tr class="de-credit">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr class="de-credit">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr class="de-credit">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr class="de-credit">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr class="de-credit">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table>

	<table cellspacing="0" cellpadding="0" border="0" width="100%" style="padding: 8px 3px;">
		<tr>
			<td>
				<b style="font-size: 12pt">หมายเหตุ</b>
			</td>
			<td width="92%" style="vertical-align: top; border-bottom: 1px dotted #000;">
				<?=$obj_row["paym_note"];?>
			</td>
		</tr>
	</table>

</body>

<?php

	$html = ob_get_contents();

	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($html);
	$mpdf->SetHTMLFooter($footer);
	$mpdf->Output($name);

	ob_end_flush();	

	mysqli_close($obj_con);

	// $link = 'payment_seepdf.php?cid='.$_GET["cid"].'&dep='.$_GET["dep"].'&y='.$paymyear.'&m='.$paymmonth;
	$link = "download.php?filename=".$obj_row["paym_file"];

?>

<!-- ดาวโหลดรายงานในรูปแบบ PDF <a href="<?=$name?>">คลิกที่นี้</a> -->

<script langquage="javascript">
	window.location="<?=$link;?>";
</script>

</div>

<?php } ?>