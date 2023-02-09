<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$countChk = $_GET["countChk"];
		$paymid = $_GET["paymid"];

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

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
			if ($number >= 1000000) {
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

		$invdesc = "";
		$invno = "";
		$invcount = 0;
		$invsubNoVat = 0;
		$invsubVat = 0;
		$invsubtotal = 0;
		$invvat = 0;
		$invtaxtotal1 = 0;
		$invtaxtotal2 = 0;
		$invtaxtotal3 = 0;
		$invgrand = 0;
		$invdiff = 0;
		$invnet = 0;

		$paymtype = 0;
		for ($i = 1; $i <= $countChk; $i++) { 
				
			$invid = "invid" . $i;
			$invid = $_GET["$invid"];

			$str_sql = "SELECT * FROM invoice_tb AS i 
						INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id 
						INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
						INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
						INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id 
						LEFT JOIN cheque_tb AS cheq ON paym.paym_cheqid = cheq.cheq_id 
						LEFT JOIN bank_tb AS b ON cheq.cheq_bankid = b.bank_id
						WHERE inv_id = '" . $invid . "'";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);

			// $invdesc = $obj_row["inv_description"] . " || " . $invdesc;
			// $invno = $obj_row["inv_no"] . " || " . $invno;

			if ($countChk > 1) {

				$invcount += $obj_row["inv_count"];
				$invsubNoVat += $obj_row["inv_subtotalNoVat"];
				$invsubVat += $obj_row["inv_subtotal"];
				$invsubtotal += $obj_row["inv_subtotalNoVat"] + $obj_row["inv_subtotal"];
				$invvat += $obj_row["inv_vat"];
				$invtaxtotal1 += $obj_row["inv_taxtotal1"];
				$invtaxtotal2 += $obj_row["inv_taxtotal2"];
				$invtaxtotal3 += $obj_row["inv_taxtotal3"];
				$invgrand += $obj_row["inv_grandtotal"];
				$invdiff += $obj_row["inv_difference"];
				$invnet += $obj_row["inv_netamount"];

				$paymtype = $obj_row["paym_typepay"];

				// $invno = $obj_row["inv_no"] . " || " . $invno;

			} else {

				$invdesc = $obj_row["inv_description"];
				$invcount = $obj_row["inv_count"];
				$invsubNoVat = $obj_row["inv_subtotalNoVat"];
				$invsubVat = $obj_row["inv_subtotal"];
				$invsubtotal = $invsubNoVat + $invsubVat;
				$invvat = $obj_row["inv_vat"];
				$invtaxtotal1 = $obj_row["inv_taxtotal1"];
				$invtaxtotal2 = $obj_row["inv_taxtotal2"];
				$invtaxtotal3 = $obj_row["inv_taxtotal3"];
				$invgrand = $obj_row["inv_grandtotal"];
				$invdiff = $obj_row["inv_difference"];
				$invnet = $obj_row["inv_netamount"];
				$invid = $obj_row["inv_id"];
				$invno = $obj_row["inv_no"];

				$paymtype = $obj_row["paym_typepay"];

			}

			$str_sql_ivappr = "SELECT * FROM invoice_tb WHERE inv_id = '" . $invid . "'";
			$obj_rs_ivappr = mysqli_query($obj_con, $str_sql_ivappr);
			$obj_row_ivappr = mysqli_fetch_array($obj_rs_ivappr);

			$apprCEOno = $obj_row_ivappr["inv_apprCEOno"];
			$apprMgrno = $obj_row_ivappr["inv_apprMgrno"];

			if ($obj_row["inv_statusCEO"] == 1) {

				$str_sql_ceo = "SELECT * FROM approveceo_tb AS aCEO 
								INNER JOIN invoice_tb AS i ON aCEO.apprCEO_no = i.inv_apprCEOno 
								INNER JOIN user_tb AS u ON aCEO.apprCEO_userid_create = u.user_id 
								WHERE i.inv_id = '" . $invid . "'";
				$obj_rs_ceo = mysqli_query($obj_con, $str_sql_ceo);
				$obj_row_ceo = mysqli_fetch_array($obj_rs_ceo);

				if ($obj_row_ceo["inv_apprCEOno"] != '') {
					$nameCEO = $obj_row_ceo["user_firstname"] . " " . $obj_row_ceo["user_surname"];
					$dateCEO = DateThai($obj_row_ceo["apprCEO_date"]);
				} else {
					$nameCEO = "";
					$dateCEO = "";
				}

			} else {
				$nameCEO = "";
				$dateCEO = "";
			}

					
			//Check department manager
			if ($obj_row["inv_statusMdep"] == 1) {
				$str_sql_mdep = "SELECT * FROM approvemdep_tb AS aMdep
								INNER JOIN invoice_tb AS i ON aMdep.apprMdep_no = i.inv_apprMdepno
								INNER JOIN user_tb AS u ON aMdep.apprMdep_userid_create = u.user_id 
								WHERE i.inv_id = '" . $invid . "'";
				$obj_rs_mdep = mysqli_query($obj_con, $str_sql_mdep);
				$obj_row_mdep = mysqli_fetch_array($obj_rs_mdep);

				if ($obj_row_mdep["inv_apprMdepno"] != '') {
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


			$str_sql_mgr = "SELECT * FROM approvemgr_tb AS aMgr 
							INNER JOIN invoice_tb AS i ON aMgr.apprMgr_no = i.inv_apprMgrno 
							INNER JOIN user_tb AS u ON aMgr.apprMgr_userid_create = u.user_id 
							WHERE inv_id = '" . $invid . "'";
			$obj_rs_mgr = mysqli_query($obj_con, $str_sql_mgr);
			$obj_row_mgr = mysqli_fetch_array($obj_rs_mgr);

			if ($obj_row_mgr["inv_apprMgrno"] == '') {
				$nameMgr = '';
				$dateMgr = '';
				$styMgr = 'margin-top: 26px;';
			} else {
				$nameMgr = $obj_row_mgr["user_firstname"] . " " . $obj_row_mgr["user_surname"];
				$dateMgr = DateThai($obj_row_mgr["apprMgr_date"]);
				$styMgr = '';
			}

		}

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<link rel="stylesheet" type="text/css" href="css/checkbox.css">

	<style type="text/css">
		.table .thead-light th {
			color: #000;
			border: 1px solid #000;
		}
		.table-bordered.border-black {
			border: 1px solid #000;
		}
		.table td.tdborder-black {
			padding: 1rem .75rem;
			border-right: 1px solid #000;
			border-bottom: 1px dotted #000;
		}
		.table td.tdborder-black.black-left {
			border-left: 1px solid #000;
		}
		.table td.tdborder-black.black-bottom {
			border-bottom: 2px dotted #000!important;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmPaymentPreview" id="frmPaymentPreview" action="">

				 <div class="row py-4 px-1" style="background-color: #e9ecef">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-search-document"></i>&nbsp;&nbsp;Preview ใบสำคัญจ่าย
						</h3>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-lg-6 col-md-6 pb-2">
						<b style="font-size: 1.15rem"><?=$obj_row["comp_name"];?></b>
						<?=$obj_row["comp_address"];?>
					</div>
										
					<div class="col-lg-6 col-md-6 pb-2 text-right">
						<b style="font-size: 1.15rem">ใบสำคัญจ่าย</b>
						<br>

						<b>วันที่</b>
						<span style="padding: 0px 12px; border-bottom: 1px dotted #000">	
							<?=DateThai($obj_row["paym_date"])?>
						</span>

						<b>ฝ่าย</b>
						<span style="padding: 0px 12px; border-bottom: 1px dotted #000">
							<?=$obj_row["dep_name"]?>
						</span>

						<b>เลขที่ใบสำคัญจ่าย</b>
						<span style="padding: 0px 12px; border-bottom: 1px dotted #000">
							<?=$obj_row["paym_no"]?>
						</span>
					</div>

					<div class="col-lg-12 col-md-12 pb-2 row">
						<div class="col-md-1 text-right">
							<b>ชื่อผู้รับ : </b>
						</div>
						<div class="col-md-11" style="border-bottom: 1px dotted #000">
							<?= $obj_row["paya_name"]; ?>
						</div>
					</div>

					<div class="col-lg-12 col-md-12 pb-2 row">
						<div class="col-md-1 text-right">
							<b>ชำระค่า : </b>
						</div>
						<div class="col-md-11">
							<div class="row">
								<style type="text/css">
									.menu li {
										display: inline;
									}
									.menu li::after {
										content: ' || ';
									}
									.menu li:last-child:after {
										display: none;
									}
								</style>
								<?php if ($countChk > 1) { ?>
								<div class="col-auto menu" style="border-bottom: 1px dotted #000;">
									<?php for ($i = 1; $i <= $countChk; $i++) { 
											$invid = "invid" . $i;
											$invid = $_GET["$invid"];
											$str_sql_desc = "SELECT * FROM invoice_tb WHERE inv_id = '" . $invid . "'";
											$obj_rs_desc = mysqli_query($obj_con, $str_sql_desc);
											$obj_row_desc = mysqli_fetch_array($obj_rs_desc);
									?>
											<?=$obj_row_desc["inv_description"]?>
											&nbsp;
											<li></li>
											&nbsp;
									<?php } ?>
								</div>
								<?php } else { ?>
								<div class="col-md-12" style="border-bottom: 1px dotted #000;">
									<?= $invdesc; ?>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pb-2 row">
						<div class="col-md-7 pr-0 mt-2">
							<b>จำนวนใบแจ้งหนี้ : </b>
						</div>
						<div class="col-md-3 text-center mt-2" style="border-bottom: 1px dotted #000;">
							<?php if ($countChk > 1) { ?>
								<?= $invcount ;?>
							<?php } else { ?>
								<?= $invcount ;?>
							<?php } ?>
						</div>
						<div class="col-md-1 pr-0 pl-0 mt-2">
							<b>ใบ</b>
						</div>
					</div>

					<div class="col-lg-9 col-md-9 pb-2 row">
						<div class="col-md-2">
							<div class="checkbox my-0">
								<input type="checkbox" name="paymbySel" id="paymbyCash" <?php if('1' == $paymtype) echo 'checked="checked"'; ?> disabled>
								<label for="paymbyCash" class="mt-1">
									<span>เงินสด</span>
								</label>
							</div>
						</div>
						<div class="col-md-2">
							<div class="checkbox my-0">
								<input type="checkbox" name="paymbySel" id="paymbyCheque" <?php if('2' == $paymtype) echo 'checked="checked"'; ?>  disabled>
								<label for="paymbyCheque" class="mt-1">
									<span>เช็คเลขที่</span>
								</label>
							</div>
						</div>
						<div class="col-md-2 text-center mt-2 px-0" style="border-bottom: 1px dotted #000;">
							<?= $obj_row["cheq_no"]; ?>
						</div>
						<div class="col-md-1 text-center mt-2 px-0">
							<b>ลงวันที่</b>
						</div>
						<div class="col-md-2 text-center mt-2 px-0" style="border-bottom: 1px dotted #000; margin-top: 9px;">
							<?php if ($obj_row["cheq_no"] != '') { 
									echo DateThai($obj_row["cheq_date"]);
								} else { 
								} 
							?>
						</div>
						<div class="col-md-1 text-center mt-2 px-0">
							<b>ธนาคาร</b>
						</div>
						<div class="col-md-2 text-center mt-2 px-0" style="border-bottom: 1px dotted #000;">
							<?= $obj_row["bank_name"]; ?>
						</div>
					</div>

					<div class="col-lg-12 col-md-12 pb-2 row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-lg-3 col-md-3 pr-0">
									<label for="invno" class="mb-1">เลขที่ใบแจ้งหนี้</label>
								</div>
								<div class="col-lg-9 col-md-9">
									<div class="row">
										<div class="col-md-12" style="border-bottom: 1px dotted #000; margin-top: 25.78px; position: absolute; "></div>
										<div class="col-md-12" style="border-bottom: 1px dotted #000; margin-top: 52.56px; position: absolute;"></div>
										<div class="col-md-12" style="border-bottom: 1px dotted #000; margin-top: 79.34px; position: absolute;"></div>
										<div class="col-md-12" style="border-bottom: 1px dotted #000; margin-top: 106.12px; position: absolute;"></div>
										<div class="col-md-12" style="border-bottom: 1px dotted #000; margin-top: 132.90px; position: absolute;"></div>
										<div class="col-md-12" style="border-bottom: 1px dotted #000; margin-top: 159.68px; position: absolute;"></div>

										<div class="col-md-12 px-0">
											<style type="text/css">
												span.invIVNO { display: inline;}
												span.invIVNO:after { content: " || "; }
												span.invIVNO:last-child:before { content: " "; }
												span.invIVNO:last-child:after { content: " "; }
											</style>
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
												<input type="text" class="form-control d-none" name="invno<?=$i;?>" id="invno<?=$i;?>" value="<?=$obj_row_ivno["inv_no"];?>">
												<input type="text" class="form-control d-none" name="invid<?=$i;?>" id="invid<?=$i;?>" value="<?=$obj_row_ivno["inv_id"];?>">
											
												<?= $invno; ?>
												&nbsp;
												<span class="invIVNO" style="<?= $displayinvno; ?>"></span>
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
													} else {
														$invno = "";
														$displayinvno = "display: none";
													}
											?>

												<input type="text" class="form-control d-none" name="invno<?=$countChk;?>" id="invno<?=$countChk;?>" value="<?=$invno;?>">
												<input type="text" class="form-control d-none" name="invid<?=$countChk;?>" id="invid<?=$countChk;?>" value="<?=$invid;?>">

												<?= $invno; ?>
												&nbsp;
												<span class="invIVNO"></span>
												&nbsp;
												

											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="row">
								<div class="col-lg-3 col-md-12 pl-0 text-right">
									<label for="invsub" class="mb-0">จำนวนเงิน :</label>
								</div>
								<div class="col-lg-8 col-md-12 text-right">
									<div class="row" style="border-bottom: 1px dotted #000">
										<?php if ($countChk > 1) { ?>
										<div class="col-lg-12 col-md-12 px-0 text-right">
											<?= number_format($invsubtotal,2); ?>
										</div>
										<?php } else { ?>
										<div class="col-lg-7 col-md-7 px-0 text-right">
											<?php if ($invsubNoVat == '0.00' && $invsubVat != '0.00') { ?>
											<?php } else if ($invsubNoVat != '0.00' && $invsubVat == '0.00') { ?>
											<?php } else if ($invsubNoVat != '0.00' && $invsubVat != '0.00') { ?>
												<?= number_format($invsubNoVat,2); ?>
												&nbsp;+&nbsp;
												<?= number_format($invsubVat,2); ?>
												&nbsp;=
											<?php } ?>
										</div>
										<div class="col-lg-5 col-md-5 px-0 text-right">
											<?= number_format($invsubtotal,2); ?>
										</div>
										<?php } ?>
									</div>
								</div>
								<div class="col-lg-1 col-md-12">
									<b>บาท</b>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-3 col-md-12 pl-0 text-right">
									<label for="invvat" class="mb-0">ภาษีมูลค่าเพิ่ม :</label>
								</div>
								<div class="col-lg-8 col-md-12 text-right">
									<div class="row" style="border-bottom: 1px dotted #000">
										<?php if ($countChk > 1) { ?>
											<div class="col-lg-12 col-md-12 px-0 text-right">
												<?= number_format($invvat,2); ?>
											</div>
										<?php } else { ?>
											<?php if ($obj_row["inv_vatpercent"] == '0.00') { ?>
												<div class="col-lg-12 col-md-12 px-0 text-right" style="height: 25.78px;"></div>
											<?php } else { ?>
												<div class="col-lg-7 col-md-7 px-0 text-left">
													<?= number_format($obj_row["inv_vatpercent"],2); ?>%
												</div>
												<div class="col-lg-5 col-md-5 px-0 text-right">
													<?= number_format($obj_row["inv_vat"],2); ?>
												</div>
											<?php } ?>
										<?php } ?>
									</div>
								</div>
								<div class="col-lg-1 col-md-12">
									<b>บาท</b>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-3 col-md-12 pl-0 text-right">
									<label for="invtax1" class="mb-0">หักภาษี ณ ที่จ่าย :</label>
								</div>
								<div class="col-lg-8 col-md-12 text-right">
									<div class="row" style="border-bottom: 1px dotted #000">
										<?php if ($countChk > 1) { ?>
											<?php if ($invtaxtotal1 != '0.00') { ?>
											<div class="col-lg-12 col-md-12 px-0 text-right">
												<?= number_format($invtaxtotal1,2); ?>
											</div>
											<?php } else { ?>
											<div class="col-lg-12 col-md-12 px-0 text-right" style="height: 25.78px;"></div>
											<?php } ?>
										<?php } else { ?>
											<?php if ($obj_row["inv_taxpercent1"] == '0.00') { ?>
												<div class="col-lg-12 col-md-12 px-0 text-right" style="height: 25.78px;"></div>
											<?php } else { ?>
												<div class="col-lg-7 col-md-7 px-0 text-left">
													<?= number_format($obj_row["inv_taxpercent1"],2); ?>%
												</div>
												<div class="col-lg-5 col-md-5 px-0 text-right">
													<?= number_format($obj_row["inv_taxtotal1"],2); ?>
												</div>
											<?php } ?>
										<?php } ?>
									</div>
								</div>
								<div class="col-lg-1 col-md-12">
									<b>บาท</b>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-3 col-md-12 pl-0 text-right">
									<label for="invtax2" class="mb-0" style="color: #FFF;">หักภาษี ณ ที่จ่าย :</label>
								</div>
								<div class="col-lg-8 col-md-12 text-right">
									<div class="row" style="border-bottom: 1px dotted #000">
										<?php if ($countChk > 1) { ?>
											<?php if ($invtaxtotal2 != '0.00') { ?>
											<div class="col-lg-12 col-md-12 px-0 text-right">
												<?= number_format($invtaxtotal2,2); ?>
											</div>
											<?php } else { ?>
											<div class="col-lg-12 col-md-12 px-0 text-right" style="height: 25.78px;"></div>
											<?php } ?>
										<?php } else { ?>
											<?php if ($obj_row["inv_taxpercent2"] == '0.00') { ?>
												<div class="col-lg-12 col-md-12 px-0 text-right" style="height: 25.78px;"></div>
											<?php } else { ?>
												<div class="col-lg-7 col-md-7 px-0 text-left">
													<?= number_format($obj_row["inv_taxpercent2"],2); ?>%
												</div>
												<div class="col-lg-5 col-md-5 px-0 text-right">
													<?= number_format($obj_row["inv_taxtotal2"],2); ?>
												</div>
											<?php } ?>
										<?php } ?>
									</div>
								</div>
								<div class="col-lg-1 col-md-12">
									<b>บาท</b>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-3 col-md-12 pl-0 text-right">
									<label for="invtax3" class="mb-0" style="color: #FFF;">หักภาษี ณ ที่จ่าย :</label>
								</div>
								<div class="col-lg-8 col-md-12 text-right">
									<div class="row" style="border-bottom: 1px dotted #000">
										<?php if ($countChk > 1) { ?>
											<?php if ($invtaxtotal3 != '0.00') { ?>
											<div class="col-lg-12 col-md-12 px-0 text-right">
												<?= number_format($invtaxtotal3,2); ?>
											</div>
											<?php } else { ?>
											<div class="col-lg-12 col-md-12 px-0 text-right" style="height: 25.78px;"></div>
											<?php } ?>
										<?php } else { ?>
											<?php if ($obj_row["inv_taxpercent3"] == '0.00') { ?>
												<div class="col-lg-12 col-md-12 px-0 text-right" style="height: 25.78px;"></div>
											<?php } else { ?>
												<div class="col-lg-7 col-md-7 px-0 text-left">
													<?= number_format($obj_row["inv_taxpercent3"],2); ?>%
												</div>
												<div class="col-lg-5 col-md-5 px-0 text-right">
													<?= number_format($obj_row["inv_taxtotal3"],2); ?>
												</div>
											<?php } ?>
										<?php } ?>
									</div>
								</div>
								<div class="col-lg-1 col-md-12">
									<b>บาท</b>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-3 col-md-12 pl-0 text-right">
									<label for="invtax1" class="mb-0">ยอดชำระสุทธิ :</label>
								</div>
								<div class="col-lg-8 col-md-12 text-right">
									<div class="row" style="border-bottom: 1px dotted #000">
										<?php if ($countChk > 1) { ?>
											<div class="col-lg-7 col-md-12 px-0 text-right">
												<?php if ($invdiff == '0.00') { ?>
													
												<?php } else { ?>
													<?= number_format($invgrand,2); ?>
													&nbsp;+&nbsp;
													<?= number_format($invdiff,2); ?>&nbsp;=
												<?php } ?>
											</div>
											<div class="col-lg-5 col-md-12 px-0 text-right">
												<?= number_format($invnet,2); ?>
											</div>
										<?php } else { ?>
											<div class="col-lg-7 col-md-12 px-0 text-right">
												<?php if ($obj_row["inv_difference"] == '0.00') { ?>
													
												<?php } else { ?>												
													<?= number_format($obj_row["inv_grandtotal"],2); ?>
													&nbsp;+&nbsp;
													<?= number_format($obj_row["inv_difference"],2); ?>&nbsp;=
												<?php } ?>
											</div>
											<div class="col-lg-5 col-md-12 px-0 text-right">
												<?= number_format($invnet,2); ?>
											</div>
										<?php } ?>
									</div>
								</div>
								<div class="col-lg-1 col-md-12">
									<b>บาท</b>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-12 col-md-12 pb-2 row" style="padding-right: 15px">
						<div class="col-md-1">
							<b>ตัวอักษร</b>
						</div>
						<div class="col-md-11 text-left" style="border-bottom: 1px dotted #000;">
							&nbsp;&nbsp;<?= Convert($invnet) ?>
						</div>
					</div>

					<div class="col-lg-12 col-md-12">
						<div class="table-responsive">
							<table class="table table-bordered border-black">
								<thead class="thead-light">
									<tr>
										<th class="text-center">รายการ</th>
										<th width="20%" class="text-center">เจ้าหนี้</th>
										<th width="20%" class="text-center">ลูกหนี้</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="tdborder-black black-left black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
									</tr>
									<tr>
										<td class="tdborder-black black-left black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
									</tr>
									<tr>
										<td class="tdborder-black black-left black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
									</tr>
									<tr>
										<td class="tdborder-black black-left black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
									</tr>
									<tr>
										<td class="tdborder-black black-left"></td>
										<td class="tdborder-black"></td>
										<td class="tdborder-black"></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="col-md-12 col-sm-12 pb-4 row">
						<div class="col-md-1">
							<b>หมายเหตุ</b>
						</div>
						<div class="col-md-11 text-left" style="border-bottom: 1px dotted #000;">
							<?= $obj_row["paym_note"]; ?>
						</div>
					</div>

					<div class="col-lg-12 col-md-12 pt-1 pb-3">
						<div class="row px-1">
							<div class="col-md-3 col-sm-12 px-4">
								<div class="row mb-2">
									<div class="col-md-12 px-4 text-center" style="border-bottom: 1px dotted #000;">
										<span><?= $nameMdep != '' ? $nameMdep : '&nbsp;'?></span>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 text-center">
										<b>ฝ่ายอนุมัติ วันที่</b>
									</div>
									<div class="col-md-6 col-sm-12 text-center" style="border-bottom: 1px dotted #000;">
										<span><?= $dateMdep != '' ? $dateMdep : '&nbsp;'?></span>
									</div>
								</div>
							</div>

							<div class="col-md-3 col-sm-12 px-4">
								<div class="row mb-2" style="<?=$styMgr;?>">
									<div class="col-md-12 px-4 text-center" style="border-bottom: 1px dotted #000;">
										<?= $nameMgr != '' ? $nameMgr : '&nbsp;'?>
										<input type="text" class="form-control d-none" name="nameMgr" id="nameMgr" value="<?=$nameMgr;?>">
									</div>
								</div>

								<div class="row">
									<div class="col-md-7 text-center">
										<b>ผู้ตรวจจ่าย วันที่</b>
									</div>
									<div class="col-md-5 col-sm-12 text-center" style="border-bottom: 1px dotted #000;">
										<?= $dateMgr != '' ? $dateMgr : '&nbsp;'?>
									</div>
								</div>
							</div>

							<div class="col-md-3 col-sm-12 px-4">
								<div class="row mb-2">
									<div class="col-md-12 px-4 text-center" style="border-bottom: 1px dotted #000;">
										<?= $nameCEO != '' ? $nameCEO : '&nbsp;'?>
										<input type="text" class="form-control d-none" name="nameCEO" id="nameCEO" value="<?=$nameCEO;?>">
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 text-center">
										<b>ผู้อนุมัติ วันที่</b>
									</div>
									<div class="col-md-6 col-sm-12 text-center" style="border-bottom: 1px dotted #000;">
										<?= $dateCEO != '' ? $dateCEO : '&nbsp;'?>
									</div>
								</div>
							</div>

							<div class="col-md-3 col-sm-12 px-4">
								<div class="row mb-2" style="margin-top: 25px;">
									<div class="col-md-12 px-4 text-center" style="border-bottom: 1px dotted #000;">
										
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 text-center">
										<b>ผู้รับเงิน วันที่</b>
									</div>
									<div class="col-md-6 col-sm-12 text-center" style="border-bottom: 1px dotted #000;">
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1 d-none" style="background-color: #FFFFFF">
					<input type="text" class="form-control" name="compid" value="<?=$obj_row["comp_id"];?>">
					<input type="text" class="form-control" name="depid" value="<?=$obj_row["dep_id"];?>">
					<input type="text" class="form-control" name="paymno" value="<?=$obj_row["paym_no"];?>">
					<input type="text" class="form-control" name="paymid" value="<?=$obj_row["paym_id"];?>">
					<input type="text" class="form-control" name="paymrev" value="<?=$obj_row["paym_rev"];?>">
					<input type="text" class="form-control" name="countChk" value="<?=$countChk;?>">
				</div>

				<?php
					$str_sql_selIV = "SELECT * FROM invoice_tb WHERE inv_paymid = '".$obj_row["paym_id"]."'";
					$obj_rs_selIV = mysqli_query($obj_con, $str_sql_selIV);
					$obj_num_selIV = mysqli_num_rows($obj_rs_selIV);

					$i = 1;
					$invid = "";
					while ($obj_row_selIV = mysqli_fetch_array($obj_rs_selIV)) {
						$invid = $invid . 'invid'.$i."=".$obj_row_selIV["inv_id"]."&";

						$i++;
					}
				?>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 pb-4 text-center">
						<a href="payment_edit.php?cid=<?=$cid?>&dep=<?=$dep?>&countChk=<?=$countChk;?>&<?=$invid?>paymid=<?=$obj_row["paym_id"];?>" type="button" class="btn btn-warning px-5 py-2 mx-1">ย้อนกลับ</a>
									
						<input type="button" class="btn btn-success px-5 py-2 mx-1" value="บันทึก" name="btnPrint" id="btnPrint">
					</div>
				</div>

			</form>

		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function() {

			$("#btnPrint").click(function() {
				var formData = new FormData(this.form);
				$.ajax({
					type: "POST",
					url: "r_payment_preview.php",
					// data: $("#frmPaymentPreview").serialize(),
					data: formData,
					dataType: 'json',
					contentType: false,
					cache: false,
					processData:false,
					success: function(result) {
						if(result.status == 1) {
							swal({
								title: "บันทึกข้อมูลสำเร็จ",
								text: "เลขที่ใบสำคัญจ่าย " + result.message,
								type: "success",
								closeOnClickOutside: false
							},function() {
								window.location.href = result.url;
							});
						} else {
							alert(result.message);
						}
					}
				});
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>