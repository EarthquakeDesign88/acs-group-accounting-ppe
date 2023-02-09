<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		if(isset($_POST["id"])) {

			$output = '';
			include 'connect.php';

			$str_sql_user = "SELECT * FROM user_tb AS u INNER JOIN level_tb AS l ON u.user_levid = l.lev_id INNER JOIN department_tb AS d ON u.user_depid = d.dep_id WHERE user_id = '". $_SESSION["user_id"] ."'";
			$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
			$obj_row_user = mysqli_fetch_array($obj_rs_user);

			function DateThai($strDate) {
				$strYear = substr(date("Y",strtotime($strDate))+543,-2);
				$strMonth= date("n",strtotime($strDate));
				$strDay= date("j",strtotime($strDate));
				$strHour= date("H",strtotime($strDate));
				$strMinute= date("i",strtotime($strDate));
				$strSeconds= date("s",strtotime($strDate));
				$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
				$strMonthThai=$strMonthCut[$strMonth];
				return "$strDay $strMonthThai $strYear";
			}

			function Convert($amount_number) {
				$amount_number = number_format($amount_number, 2, ".","");
				$pt = strpos($amount_number , ".");
				$number = $fraction = "";
				if ($pt === false) {
					$number = $amount_number;
				} else {
					$number = substr($amount_number, 0, $pt);
					$fraction = substr($amount_number, $pt + 1);
				}
				$ret = "";
				$baht = ReadNumber ($number);
				if ($baht != ""){
					$ret .= $baht . "บาท";
				}
				$satang = ReadNumber($fraction);
				if ($satang != "") {
					$ret .=  $satang . "สตางค์";
				} else {
					$ret .= "ถ้วน";
				}
				return $ret;
			}

			function ReadNumber($number) {
				$position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
				$number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
				$number = $number + 0;
				$ret = "";
				if ($number == 0) return $ret;
				if ($number > 1000000) {
					$ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
					$number = intval(fmod($number, 1000000));
				}

				$divider = 100000;
				$pos = 0;
				while($number > 0) {
					$d = intval($number / $divider);
					$ret .= (($divider == 10) && ($d == 2)) ? "ยี่" :
						((($divider == 10) && ($d == 1)) ? "" :
						((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
					$ret .= ($d ? $position_call[$pos] : "");
					$number = $number % $divider;
					$divider = $divider / 10;
					$pos++;
				}
				return $ret;
			}

			$invpaymid = $_POST["id"];

			$countINV = 0;
			// $invno = "";
			$str_sql_count = "SELECT * FROM invoice_tb WHERE inv_paymid = '". $invpaymid ."'";
			$obj_rs_count = mysqli_query($obj_con, $str_sql_count);
			while ($obj_row_count = mysqli_fetch_array($obj_rs_count)) {
				$countINV++;
				// $invno = $obj_row_count["inv_no"] . " || " . $invno;
			}

			// echo $invno . "<br>";
			// echo $countINV . "<br>";

			$invdesc = "";
			$invno = "";

			$invsubNoVat = 0;
			$invsub = 0;
			$invsubtotal = 0;
			$invvatpercent = 0;
			$invvat = 0;

			$invtax1 = 0;
			$invtaxpercent1 = 0;
			$invtaxtotal1 = 0;

			$invtax2 = 0;
			$invtaxpercent2 = 0;
			$invtaxtotal2 = 0;

			$invtax3 = 0;
			$invtaxpercent3 = 0;
			$invtaxtotal3 = 0;

			$invgrand = 0;
			$invdiff = 0;
			$invnet = 0;
			$invcount = 0;
			$invapprnoMgr = "";

			$compname = "";
			$compaddress = "";
			$payaname = "";
			$payaaddress = "";

			$paymno = "";
			$paymdate = "";
			$paymtypepay = "";

			$cheqno = "";
			$cheqdate = "";
			$bankname = "";
			
			$payeename = "";
			$payeedate = "";

			// for ($i = 1; $i <= $countINV; $i++) {

				$str_sql = "SELECT * FROM invoice_tb AS i INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id LEFT JOIN cheque_tb AS cheq ON paym.paym_cheqid = cheq.cheq_id LEFT JOIN bank_tb AS b ON cheq.cheq_bankid = b.bank_id WHERE inv_paymid = '" . $invpaymid . "'";
				$obj_rs = mysqli_query($obj_con, $str_sql);
				while ($obj_row = mysqli_fetch_array($obj_rs)) {

				$invdesc = $obj_row["inv_description"] . " || " . $invdesc;

				if ($obj_row["inv_type"] == 0) {
					$invno = $obj_row["inv_no"] . " || " . $invno;
					$styinvno = "";
				} else {
					$invno = "";
					$styinvno = "padding-top: 26px;";
				}

				$invsubNoVat += $obj_row["inv_subtotalNoVat"];
				$invsub += $obj_row["inv_subtotal"];
				$invsubtotal += $obj_row["inv_subtotalNoVat"] + $obj_row["inv_subtotal"];
				$invvatpercent = $obj_row['inv_vatpercent'];
				$invvat += $obj_row["inv_vat"];

				$invtax1 += $obj_row["inv_tax1"];
				$invtaxpercent1 = $obj_row["inv_taxpercent1"];
				$invtaxtotal1 += $obj_row["inv_taxtotal1"];

				$invtax2 += $obj_row["inv_tax2"];
				$invtaxpercent2 = $obj_row["inv_taxpercent2"];
				$invtaxtotal2 += $obj_row["inv_taxtotal2"];

				$invtax3 += $obj_row["inv_tax3"];
				$invtaxpercent3 = $obj_row["inv_taxpercent3"];
				$invtaxtotal3 += $obj_row["inv_taxtotal3"];
				$invgrand += $obj_row["inv_grandtotal"];
				$invdiff += $obj_row["inv_difference"];
				$invnet = $obj_row["inv_netamount"] + $invnet;

				$invapprnoMgr = $obj_row["inv_apprCEOno"];

				$compname = $obj_row["comp_name"];
				$compaddress = $obj_row["comp_address"];

				$payaname = $obj_row["paya_name"];
				$payaaddress = $obj_row["paya_address"];

				$paymno = $obj_row["paym_no"];
				$paymdate = $obj_row["paym_date"];
				$paymtypepay = $obj_row["paym_typepay"];

				$cheqno = $obj_row["cheq_no"];
				$cheqdate = $obj_row["cheq_date"];
				$bankname = $obj_row["bank_name"];

				$payeename = $obj_row["paym_payeename"];
				$payeedate = $obj_row["paym_payeedate"];
			}

				// echo $invnet . "<br>";

				$str_sql_ivappr = "SELECT * FROM invoice_tb WHERE inv_paymid = '" . $invpaymid . "'";
				$obj_rs_ivappr = mysqli_query($obj_con, $str_sql_ivappr);
				$obj_row_ivappr = mysqli_fetch_array($obj_rs_ivappr);

				$apprCEOno = $obj_row_ivappr["inv_apprCEOno"];
				$apprMgrno = $obj_row_ivappr["inv_apprMgrno"];

				if ($invapprnoMgr == '') {

					$nameCEO = "";
					$dateCEO = "";
					$styCEO = "margin-top: 26px;";

				} else {
					$str_sql_ceo = "SELECT * FROM approveceo_tb AS aCEO INNER JOIN invoice_tb AS i ON aCEO.apprCEO_no = i.inv_apprCEOno INNER JOIN user_tb AS u ON aCEO.apprCEO_userid_create = u.user_id WHERE inv_paymid = '" . $invpaymid . "'";
					$obj_rs_ceo = mysqli_query($obj_con, $str_sql_ceo);
					$obj_row_ceo = mysqli_fetch_array($obj_rs_ceo);


					// echo "CEO INV ID : " . $obj_row_ceo["inv_id"] . "<br>";
					// echo "CEO Appr NO : " . $obj_row_ceo["inv_apprCEOno"] . "<br>";
					// echo "CEO UserID NO : " . $obj_row_ceo["apprCEO_userid_create"] . "<br>";

					if ($obj_row_ceo["inv_apprCEOno"] != '') {
						$nameCEO = $obj_row_ceo["user_firstname"] . " " . $obj_row_ceo["user_surname"];
						$dateCEO = DateThai($obj_row_ceo["apprCEO_date"]);
						$styCEO = "";
					} else {
						$nameCEO = "";
						$dateCEO = "";
						$styCEO = "margin-top: 26px;";
					}

				}

				// echo "CEO Name : " . $nameCEO . "<br>";

				$str_sql_mgr = "SELECT * FROM approvemgr_tb AS aMgr INNER JOIN invoice_tb AS i ON aMgr.apprMgr_no = i.inv_apprMgrno INNER JOIN user_tb AS u ON aMgr.apprMgr_userid_create = u.user_id WHERE inv_paymid = '" . $_POST['id'] . "'";
				$obj_rs_mgr = mysqli_query($obj_con, $str_sql_mgr);
				$obj_row_mgr = mysqli_fetch_array($obj_rs_mgr);

				// echo "Mgr INV ID : " . $obj_row_mgr["inv_id"] . "<br>";
				// echo "Mgr Appr NO : " . $obj_row_mgr["inv_apprMgrno"] . "<br>";
				// echo "Mgr UserID NO : " . $obj_row_mgr["apprMgr_userid_create"] . "<br>";

				if ($obj_row_mgr["inv_apprMgrno"] == '') {
					$nameMgr = '';
					$dateMgr = '';
					$styMgr = 'margin-top: 26px;';
				} else {
					$nameMgr = $obj_row_mgr["user_firstname"] . " " . $obj_row_mgr["user_surname"];
					$dateMgr = DateThai($obj_row_mgr["apprMgr_date"]);
					$styMgr = '';
				}

				// echo "Mgr Name : " . $nameMgr . "<br>";

			// }

			$str_sql_usercreate = "SELECT * FROM payment_tb AS paym INNER JOIN user_tb AS u ON paym.paym_userid_create = u.user_id WHERE paym_id = '" . $_POST['id'] . "'";
			$obj_rs_usercreate = mysqli_query($obj_con, $str_sql_usercreate);
			$obj_row_usercreate = mysqli_fetch_array($obj_rs_usercreate);

			$nameUCreate = $obj_row_usercreate["user_firstname"] . " " . $obj_row_usercreate["user_surname"];
			$dateUCreate = DateThai($obj_row_usercreate["paym_date"]);
			$styUCreate = '';

			$output .= '<div class="container">
							<div class="row py-2">
								<div class="col-lg-12 col-md-12">
									<div class="row px-0">

										<div class="col-lg-8 col-md-12">
											<div class="row">
												<div class="col-lg-12 col-md-12 my-1 pl-0">
													<b>' . $compname . '</b><br>
													' . $compaddress . '
												</div>
											</div>
										</div>

										<div class="col-lg-4 col-md-12">
											<div class="row">
												<div class="col-lg-6 col-md-12 my-1 px-0 text-right">
													<b>เลขที่ใบสำคัญจ่าย</b>
												</div>
												<div class="col-lg-6 col-md-12 my-1 text-center" style="border-bottom: 1px dashed #333;">
													' . $paymno . '
												</div>

												<div class="col-lg-6 col-md-12 my-1 px-0 text-right">
													<b>วันที่ใบสำคัญจ่าย</b>
												</div>
												<div class="col-lg-6 col-md-12 my-1 text-center" style="border-bottom: 1px dashed #333;">
													' . DateThai($paymdate) . '
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>

							<div class="row py-2">
								<div class="col-lg-12 col-md-12">
									<div class="row px-0">
										<div class="col-lg-12 col-md-12">
											<div class="row">
												<div class="col-lg-2 col-md-12 my-1 px-0">
													<b>ชื่อบริษัทเจ้าหนี้</b>
												</div>
												<div class="col-lg-10 col-md-12 my-1" style="border-bottom: 1px dashed #333;">
													' . $payaname . '
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row py-2">
								<div class="col-lg-12 col-md-12">
									<div class="row px-0">
										<div class="col-lg-12 col-md-12">
											<div class="row">
												<div class="col-lg-1 col-md-12 my-1 px-0">
													<b>ชำระค่า</b>
												</div>
												<div class="col-lg-11 col-md-12 my-1" style="border-bottom: 1px dashed #333;">
													' . $invdesc . '
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row py-2">
								<div class="col-lg-12 col-md-12">
									<div class="row px-0">
										<div class="col-lg-1 col-md-12 px-0 py-2">
											<b>จ่ายโดย</b>
										</div>
										<div class="col-lg-2 col-md-2">
											<div class="input-group-prepend">
												<div class="checkbox">';
													if ($paymtypepay == 1) {
										$output .= '<input type="radio" name="paybySel" id="paybyCash" value="' . $paymtypepay . '" checked="checked" disabled>';
													}
										$output .= '<label for="paybyCash" class="mb-1"><span>เงินสด</span></label>
												</div>
											</div>
										</div>

										<div class="col-lg-3 col-md-2">
											<div class="input-group-prepend">
												<div class="checkbox">';
													if ($paymtypepay == "2") {
										$output .= '<input type="radio" name="paybySel" id="paybyCheque" value="' . $paymtypepay . '" checked="checked" disabled>';
													}
										$output .= '<label for="paybyCheque" class="mb-1"><span>เช็ค</span></label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-lg-12 col-md-12">
									<div class="row px-0">
										<div class="col-lg-2 col-md-12 my-1 px-0 text-right">
											<b>เลขที่เช็ค</b>
										</div>
										<div class="col-lg-3 col-md-12 my-1 text-center" style="border-bottom: 1px dashed #333;">
											' . $cheqno . '
										</div>
										<div class="col-lg-1 col-md-12 my-1 px-0 text-center">
											<b>วันที่</b>
										</div>
										<div class="col-lg-2 col-md-12 my-1 text-center" style="border-bottom: 1px dashed #333;">
											' . $cheqdate . '
										</div>
										<div class="col-lg-1 col-md-12 my-1 px-0 text-center">
											<b>ธนาคาร</b>
										</div>
										<div class="col-lg-3 col-md-12 my-1 text-center" style="border-bottom: 1px dashed #333;">
											' . $bankname . '
										</div>
									</div>
								</div>
							</div>

							<div class="row py-2">
								<div class="col-lg-12 col-md-12">
									<div class="row">

										<div class="col-lg-5 col-md-12">
											<div class="row px-0">
												<div class="col-lg-12 col-md-12 my-1 px-0">
													<b>เลขที่ใบแจ้งหนี้</b>
												</div>
												<div class="col-lg-12 col-md-12 my-1" style="border-bottom: 1px dashed #333; '.$styinvno.'">
													' . $invno . '
												</div>
											</div>
										</div>

										<div class="col-lg-7 col-md-12">
											<div class="row px-0 py-1">
												<div class="col-lg-5 col-5 px-1 text-right total">
													<b>จำนวนเงินก่อน VAT (ไม่มี VAT) : </b>
												</div>
												<div class="col-lg-6 col-6 px-1 text-right" style="border-bottom: 1px dashed #333;">
													<span>
														' . number_format($invsubNoVat,2) .'
													</span>
												</div>
												<div class="col-lg-1 col-1 px-1 text-center">
													<b>บาท</b>
												</div>
											</div>

											<div class="row px-0 py-1">
												<div class="col-lg-5 col-5 px-1 text-right total">
													<b>จำนวนเงินก่อน VAT : </b>
												</div>
												<div class="col-lg-6 col-6 px-1 text-right" style="border-bottom: 1px dashed #333;">
													<span>
														' . number_format($invsub,2) .'
													</span>
												</div>
												<div class="col-lg-1 col-1 px-1 text-center">
													<b>บาท</b>
												</div>
											</div>

											<div class="row px-0 py-1">
												<div class="col-lg-5 col-5 px-1 text-right total">
													<b>ภาษีมูลค่าเพิ่ม : </b>
												</div>
												<div class="col-lg-6 col-6 text-right" style="border-bottom: 1px dashed #333;">
													<div class="row">
														<div class="col-lg-2 col-2 px-1 text-left">
															<span>
																' . number_format($invvatpercent,0) .' %
															</span>
														</div>

														<div class="col-lg-5 col-5 px-1 text-right"></div>

														<div class="col-lg-5 col-5 px-1 text-right">
															<span>
																' . number_format($invvat,2) .'
															</span>
														</div>
													</div>
												</div>
												<div class="col-lg-1 col-1 px-1 text-center">
													<b>บาท</b>
												</div>
											</div>

											<div class="row px-0 py-1">
												<div class="col-lg-5 col-5 px-1 text-right total">
													<b>หักภาษี ณ ที่จ่าย : </b>
												</div>
												<div class="col-lg-6 col-6 text-right" style="border-bottom: 1px dashed #333;">';
													if ($countINV > 1) {
											$output .= '<div class="row">
															<div class="col-lg-12 col-12 px-1 text-right">
																<span>
																	' . number_format($invtaxtotal1,2) .'
																</span>
															</div>
														</div>';
													} else {
														if ($invtaxpercent1 != '0') {
											$output .= '<div class="row">
															<div class="col-lg-2 col-2 px-1 text-left">
																<span>
																	' . number_format($invtaxpercent1,0) .' %
																</span>
															</div>

															<div class="col-lg-5 col-5 px-1 text-right">
																<span>
																	' . number_format($invtax1,2) .' * 
																	' . number_format($invtaxpercent1,0) .' %
																</span>
															</div>

															<div class="col-lg-5 col-5 px-1 text-right">
																<span>
																	' . number_format($invtaxtotal1,2) .'
																</span>
															</div>
														</div>';
														} 
													}
									$output .= '</div>
												<div class="col-lg-1 col-1 px-1 text-center">
													<b>บาท</b>
												</div>
											</div>

											<div class="row px-0 py-1">
												<div class="col-lg-5 col-5 px-1 text-right total">
													<b></b>
												</div>
												<div class="col-lg-6 col-6 text-right" style="border-bottom: 1px dashed #333;">';
													if ($countINV > 1) {
											$output .= '<div class="row">
															<div class="col-lg-12 col-12 px-1 text-right">
																<span>
																	' . number_format($invtaxtotal2,2) .'
																</span>
															</div>
														</div>';
													} else {
														if ($invtaxpercent2 != '0') {
											$output .= '<div class="row">
															<div class="col-lg-2 col-2 px-1 text-left">
																<span>
																	' . number_format($invtaxpercent2,0) .' %
																</span>
															</div>

															<div class="col-lg-5 col-5 px-1 text-right">
																<span>
																	' . number_format($invtax2,2) .' * 
																	' . number_format($invtaxpercent2,0) .' %
																</span>
															</div>

															<div class="col-lg-5 col-5 px-1 text-right">
																<span>
																	' . number_format($invtaxtotal2,2) .'
																</span>
															</div>
														</div>';
														}
													}
									$output .= '</div>
												<div class="col-lg-1 col-1 px-1 text-center">
													<b>บาท</b>
												</div>
											</div>

											<div class="row px-0 py-1">
												<div class="col-lg-5 col-5 px-1 text-right total">
													<b></b>
												</div>
												<div class="col-lg-6 col-6 text-right" style="border-bottom: 1px dashed #333;">';
													if ($countINV > 1) {
											$output .= '<div class="row">
															<div class="col-lg-12 col-12 px-1 text-right">
																<span>
																	' . number_format($invtaxtotal3,2) .'
																</span>
															</div>
														</div>';
													} else {
														if ($invtaxpercent3 != '0') {
											$output .= '<div class="row">
															<div class="col-lg-2 col-2 px-1 text-left">
																<span>
																	' . number_format($invtaxpercent3,0) .' %
																</span>
															</div>

															<div class="col-lg-5 col-5 px-1 text-right">
																<span>
																	' . number_format($invtax3,2) .' * 
																	' . number_format($invtaxpercent3,0) .' %
																</span>
															</div>

															<div class="col-lg-5 col-5 px-1 text-right">
																<span>
																	' . number_format($invtaxtotal3,2) .'
																</span>
															</div>
														</div>';
														}
													}
									$output .= '</div>
												<div class="col-lg-1 col-1 px-1 text-center">
													<b>บาท</b>
												</div>
											</div>

											<div class="row px-0 py-1">
												<div class="col-lg-5 col-5 px-1 text-right total">
													<b>ยอดชำระสุทธิ</b>
												</div>
												<div class="col-lg-6 col-6 text-right" style="border-bottom: 1px dashed #333;">
													<div class="row">
														<div class="col-lg-7 col-7 px-1 text-right">
															<span>
																' . number_format($invgrand,2) .' + 
																' . number_format($invdiff,2) .' =
															</span>
														</div>

														<div class="col-lg-5 col-5 px-1 text-right">
															<span>
																' . number_format($invnet,2) .'
															</span>
														</div>
													</div>
												</div>
												<div class="col-lg-1 col-1 px-1 text-center">
													<b>บาท</b>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row py-2">
								<div class="col-lg-12 col-md-12 pt-2">
									<div class="row">
										<div class="col-lg-3 col-md-12">
											<div class="row px-2">
												<div class="col-lg-12 col-md-12 px-0 text-center" style="border-bottom: 1px dashed #333;">
													'. $nameUCreate .'
												</div>
												<div class="col-lg-12 col-md-12">
													<div class="row">
														<div class="col-lg-4 col-md-12 px-0 text-right">
															<b>ผู้จัดทำ</b>
														</div>
														<div class="col-lg-8 col-md-12 text-center" style="border-bottom: 1px dashed #333;">
															'. $dateUCreate .'
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="col-lg-3 col-md-12">
											<div class="row px-2" style="'. $styMgr .'">
												<div class="col-lg-12 col-md-12 px-0 text-center" style="border-bottom: 1px dashed #333;">
													'. $nameMgr .'
												</div>
												<div class="col-lg-12 col-md-12">
													<div class="row">
														<div class="col-lg-6 col-md-12 px-0 text-right">
															<b>ผู้ตรวจสอบ</b>
														</div>
														<div class="col-lg-6 col-md-12 text-center" style="border-bottom: 1px dashed #333;">
															'. $dateMgr .'
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="col-lg-3 col-md-12">
											<div class="row px-2" style="'. $styCEO .'">
												<div class="col-lg-12 col-md-12 px-0 text-center" style="border-bottom: 1px dashed #333;">
													'. $nameCEO .'
												</div>
												<div class="col-lg-12 col-md-12">
													<div class="row">
														<div class="col-lg-4 col-md-12 px-0 text-right">
															<b>ผู้อนุมัติ</b>
														</div>
														<div class="col-lg-8 col-md-12 text-center" style="border-bottom: 1px dashed #333;">
															'. $dateCEO .'
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="col-lg-3 col-md-12">
											<div class="row px-2">';
												if ($payeename != '') {
									$output .= '<div class="col-lg-12 col-md-12" style="border-bottom: 1px dashed #333;">
													'. $payeename .'
												</div>';
												} else {
									$output .= '<div class="col-lg-12 col-md-12" style="border-bottom: 1px dashed #333; margin-top: 26px;">
													
												</div>';
												}
									$output .= '<div class="col-lg-12 col-md-12">
													<div class="row">
														<div class="col-lg-4 col-md-12 px-0 text-right">
															<b>ผู้รับเงิน</b>
														</div>
														<div class="col-lg-8 col-md-12 text-center" style="border-bottom: 1px dashed #333;">';
															if($payeename != ''){
																echo $payeedate;
															} else {

															}
											$output .= '</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>';

			echo $output;

		}

	}

?>