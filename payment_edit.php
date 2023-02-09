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

		$invdesc = "";
		$invno = "";
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

		for ($i = 1; $i <= $countChk; $i++) { 
				
			$invid = "invid" . $i;
			$invid = $_GET["$invid"];

			$str_sql = "SELECT * FROM invoice_tb AS i 
						INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id 
						INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
						INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
						INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id 
						LEFT JOIN status_tb AS s ON paym.paym_stsid = s.sts_id 
						LEFT JOIN cheque_tb AS cheq ON paym.paym_cheqid = cheq.cheq_id 
						LEFT JOIN bank_tb AS b ON cheq.cheq_bankid = b.bank_id
						WHERE inv_id = '" . $invid . "'";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);

			$invdesc = $obj_row["inv_description"] . " || " . $invdesc;
			$invno = $obj_row["inv_no"] . " || " . $invno;

			if ($countChk > 1) {

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

			} else {

				$invsubNoVat = $obj_row["inv_subtotalNoVat"];
				$invsubVat = $obj_row["inv_subtotal"];
				$invsubtotal = $invsubNoVat + $invsubVat;
				$invid = $obj_row["inv_id"];
				$invno = $obj_row["inv_no"];

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
				} else {
					$nameMdep = "";
					$dateMdep = "";
				}

			} else {
				$nameMdep = "";
				$dateMdep = "";

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
			} else {
				$nameMgr = $obj_row_mgr["user_firstname"] . " " . $obj_row_mgr["user_surname"];
				$dateMgr = DateThai($obj_row_mgr["apprMgr_date"]);
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
		}
		tr:nth-last-child(n) {
			border-bottom: 1px solid #dee2e6;
		}
		.table-bordered.border-black,
		.table-bordered.border-black th {
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

		th>.truncate-id, td>.truncate-id {
			width: auto;
			min-width: 0;
			max-width: 180px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		th>.truncate, td>.truncate {
			width: auto;
			min-width: 0;
			max-width: 320px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		.modal:nth-of-type(even) {
			z-index: 1052 !important;
		}
		.modal-backdrop.show:nth-of-type(even) {
			z-index: 1051 !important;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmEditPayment" id="frmEditPayment" action="">
				
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-edit"></i>&nbsp;&nbsp;แก้ไขใบสำคัญจ่าย
						</h3>
					</div>

					<input type="text" class="form-control d-none" name="paymid" id="paymid" value="<?=$paymid;?>" readonly>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymno" class="mb-1">เลขที่ใบสำคัญจ่าย</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-numbered"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="paymno" id="paymno" value="<?=$obj_row["paym_no"];?>" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymdate" class="mb-1">วันที่ใบสำคัญจ่าย</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="paymdate" id="paymdate" value="<?=$obj_row["paym_date"];?>" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="depname" class="mb-1">ฝ่าย</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="depname" id="depname" value="<?=$obj_row["dep_name"]?>" readonly>
						</div>
						<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$dep?>" readonly>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymstatus" class="mb-1">สถานะ</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-pay"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="paymstatus" id="paymstatus" value="<?=$obj_row["sts_description"];?>" readonly style="background-color:#F00; color: #FFF; text-align: center; font-weight: 700;">
							<input type="text" class="form-control d-none" name="paymstatusid" id="paymstatusid" value="<?=$obj_row["paym_status"];?>" readonly>
						</div>
					</div>

					<div class="col-lg-12 col-md-12 pt-1 pb-3" id="displayPay">
						<div class="row">
							<div class="col-md-4 col-sm-4">
								<label for="pay" class="mb-1"><span>เลือกการชำระเงิน</span></label>
								<div class="row">
									<div class="col-lg-5 col-md-5">
										<div class="input-group-prepend">
											<div class="checkbox">
												<input type="radio" name="paymbySel" id="paymbyCash" value="1" <?php if ($obj_row["paym_typepay"] == 1) echo "checked"; ?>>
												<label for="paymbyCash" class="mb-1"><span>เงินสด</span></label>
											</div>
										</div>
									</div>

									<div class="col-lg-5 col-md-5">
										<div class="input-group-prepend">
											<div class="checkbox">
												<input type="radio" name="paymbySel" id="paymbyCheque" value="2" <?php if ($obj_row["paym_typepay"] == 2) echo "checked"; ?>>
												<label for="paymbyCheque" class="mb-1"><span>เช็ค</span></label>
											</div>
										</div>
									</div>
								</div>
							</div>

							<?php 
								if ($obj_row["paym_typepay"] == 2) {
									$displayPayBy = "display: block";
								} else {
									$displayPayBy = "display: none";
								}
							?>

							<div class="col-md-8 col-sm-12" id="detailCheque" style="<?= $displayPayBy; ?>">
								<div class="row">
									<div class="col-md-4 col-sm-12 d-none">
										<label for="chequeID" class="mb-1">ลำดับที่เช็ค</label>
										<div class="input-group mb-2">
											<input type="text" class="form-control" name="chequeID" id="chequeID"  placeholder="กรุณากรอกเลขที่เช็ค" autocomplete="off" value="<?= $obj_row["cheq_id"]; ?>">
										</div>
									</div>
									<div class="col-md-4 col-sm-12">
										<label for="chequeNo" class="mb-1">เลขที่เช็ค</label>
										<div class="input-group mb-2">
											<input type="text" class="form-control" name="chequeNo" id="chequeNo"  placeholder="กรุณากรอกเลขที่เช็ค" autocomplete="off" value="<?= $obj_row["cheq_no"]; ?>">
										</div>
									</div>
									<div class="col-md-4 col-sm-12">
										<label for="chequeDate" class="mb-1">ลงวันที่</label>
										<div class="input-group mb-2">
											<input type="date" class="form-control" name="chequeDate" id="chequeDate" autocomplete="off" value="<?= $obj_row["cheq_date"]; ?>">
										</div>
									</div>
									<div class="col-md-4 col-sm-12">
										<label for="SelectBank" class="mb-1">ธนาคาร</label>
										<div class="input-group mb-2">
											<select class="form-control custom-select" name="SelectBank" id="SelectBank">
												<option value="" selected>กรุณาเลือกธนาคาร</option>
												<?php
													$str_sql_b = "SELECT * FROM bank_tb";
													$obj_rs_b = mysqli_query($obj_con, $str_sql_b);
													while ($obj_row_b = mysqli_fetch_array($obj_rs_b)) {
														$chequebank = $obj_row["cheq_bankid"];
												?>
												<option value="<?=$obj_row_b["bank_id"]?>" <?php if ($chequebank == $obj_row_b["bank_id"]) echo "selected" ?>>
													<?=$obj_row_b["bank_name"]?>
												</option>
												<?php } ?>
												<input type="text" class="form-control d-none" name="chequeBankid" id="chequeBankid" value="<?= $obj_row["cheq_bankid"]; ?>">
											</select>
										</div>
									</div>
								</div>
							</div>

							<input type="text" class="form-control d-none" name="paymby" id="paymby" value="<?=$obj_row["paym_typepay"];?>">

							<script type="text/javascript">
								$(document).ready(function(){
									$('input[type=radio][name=paymbySel]').click(function() {
										if (this.value == '2') {
											document.getElementById("detailCheque").style.display = "block";
											document.getElementById("paymby").value = '2';
										} else if (this.value == '1') {
											document.getElementById("detailCheque").style.display = "none";
											document.getElementById("paymPayBy").value = '1';
										}
									});
								});

								$('#SelectBank').change(function(){
									$('#chequeBankid').val($('#SelectBank').val());
								});
							</script>
						</div>
					</div>

					<div class="col-lg-6 col-md-12 pt-1 pb-3">
						<label for="compname" class="mb-1">ชื่อบริษัทในเครือ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="compname" id="compname" value="<?=$obj_row["comp_name"]?>" readonly>
							<input type="text" class="form-control d-none" name="compid" id="compid" value="<?=$obj_row["comp_id"]?>" readonly>
						</div>
					</div>

					<div class="col-lg-6 col-md-12 pt-1 pb-3">
						<label for="payaname" class="mb-1">ชื่อบริษัทผู้ให้บริการ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="payaname" id="payaname" value="<?=$obj_row["paya_name"]?>" readonly>
							<input type="text" class="form-control d-none" name="payaid" id="payaid" value="<?=$obj_row["paya_id"]?>" readonly>
						</div>
					</div>

					<div class="col-lg-12 col-md-12 pt-1 pb-1" id="invTB">
						<div class="table-responsive">
							<table class="table table-bordered mb-0">
								<thead class="thead-light">
									<tr class="text-center">
										<th width="25%" style="vertical-align: middle;">เลขที่ใบแจ้งหนี้</th>
										<th width="45%" style="vertical-align: middle;">รายละเอียด</th>
										<th width="15%" style="vertical-align: middle;">จำนวนเงิน</th>
										<th width="15%">
											<button type="button" class="btn btn-primary form-control" title="เพิ่ม / Add" data-toggle="modal" data-target="#AddInvoice" data-controls-modal="AddInvoice" data-backdrop="static" data-keyboard="false">
												<i class="icofont-plus-circle"></i>&nbsp;เพิ่มใบแจ้งหนี้
											</button>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$str_sql_iv = "SELECT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id WHERE inv_paymid = '". $paymid ."'";
										$obj_rs_iv = mysqli_query($obj_con, $str_sql_iv);
										$i = 1;
										while ($obj_row_iv = mysqli_fetch_array($obj_rs_iv)) {
											$countChk = mysqli_num_rows($obj_rs_iv);
									?>
									<tr>
										<td>
											<?=$obj_row_iv["inv_no"]?>
											<input type="text" class="form-control d-none" name="invid<?=$i;?>" id="invid<?=$i;?>" value="<?=$obj_row_iv["inv_id"]?>">
										</td>
										<td>
											<b>บริษัท : </b><?=$obj_row_iv["paya_name"]?> <br>
											<b>รายการ : </b><?=$obj_row_iv["inv_description"]?>
										</td>
										<td class="text-right">
											<?=number_format($obj_row_iv["inv_netamount"],2)?>
										</td>
										<td>
											<button type="button" class="btn btn-danger form-control delete_data" name="btnDeleteSelect" id="<?=$obj_row_iv["inv_id"]?>" title="ลบ / Delete">
												<i class="icofont-ui-delete"></i>&nbsp;ลบ
											</button>
										</td>
									</tr>
									<?php $i++; } ?>
								</tbody>
							</table>
						</div>
					
						<?php
							$str_sql_sum = "SELECT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id WHERE inv_paymid = '". $paymid ."'";
							$obj_rs_sum = mysqli_query($obj_con, $str_sql_sum);

							$invsubtotal = 0;
							$invvatpercent = 0;
							$invvat = 0;
							$invtax1 = 0;
							$invtaxtotal1 = 0;
							$invtax2 = 0;
							$invtaxtotal2 = 0;
							$invtax3 = 0;
							$invtaxtotal3 = 0;
							$invtax = 0;
							$invtaxtotal = 0;
							$invgrandtotal = 0;
							$invdifference = 0;
							$invnetamount = 0;

							while ($obj_row_sum = mysqli_fetch_array($obj_rs_sum)) {
								$invsubtotal += $obj_row_sum["inv_subtotalNoVat"] + $obj_row_sum["inv_subtotal"];
								$invvatpercent = $obj_row_sum["inv_vatpercent"];
								$invvat += $obj_row_sum["inv_vat"];
								$invtax1 += $obj_row_sum["inv_tax1"];
								$invtaxtotal1 += $obj_row_sum["inv_taxtotal1"];
								$invtax2 += $obj_row_sum["inv_tax2"];
								$invtaxtotal2 += $obj_row_sum["inv_taxtotal2"];
								$invtax3 += $obj_row_sum["inv_tax3"];
								$invtaxtotal3 += $obj_row_sum["inv_taxtotal3"];
								$invtax = $invtax1 + $invtax2 + $invtax3;
								$invtaxtotal = $invtaxtotal1 + $invtaxtotal2 + $invtaxtotal3;
								$invgrandtotal += $obj_row_sum["inv_grandtotal"];
								$invdifference += $obj_row_sum["inv_difference"];
								$invnetamount += $obj_row_sum["inv_netamount"];
							}
						?>

						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="thead-light">
									<tr>
										<th class="text-right" colspan="3">
											<b>รวมเงินทั้งหมด</b>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="text-right" style="vertical-align: middle; padding: .25rem" colspan="2">
											<b>จำนวนเงิน : </b>
										</td>
										<td width="20%" style="padding: .25rem">
											<input type="text" class="form-control text-right" name="" value="<?= number_format($invsubtotal,2); ?>" readonly>
										</td>
									</tr>
									<tr>
										<td class="text-right" style="vertical-align: middle;">
											<b>ภาษีมูลค่าเพิ่ม : </b>
										</td>
										<td width="10%" style="padding: .25rem">
											<input type="text" class="form-control text-right" name="" value="<?= $invvatpercent; ?>%" readonly>
										</td>
										<td width="20%" style="padding: .25rem">
											<input type="text" class="form-control text-right" name="" value="<?= number_format($invvat,2); ?>" readonly>
										</td>
									</tr>
									<tr>
										<td class="text-right" style="vertical-align: middle;" colspan="2">
											<b>หักภาษี ณ ที่จ่าย : </b>
										</td>
										<td width="20%" style="padding: .25rem">
											<input type="text" class="form-control text-right" name="" value="<?= number_format($invtaxtotal,2); ?>" readonly>
										</td>
									</tr>
									<tr>
										<td class="text-right" colspan="2" style="vertical-align: middle; padding: .25rem">
											<b>จำนวนเงินรวม :</b><br><br>
											<b></b><br>
											<b>ส่วนต่าง + / - :</b>
										</td>
										<td style="text-align: center; padding: .25rem">
											<input type="text" class="form-control text-right" name="" value="<?= number_format($invgrandtotal,2); ?>" readonly>
											<b >+</b>
											<input type="text" class="form-control text-right" name="" value="<?= number_format($invdifference,2); ?>" readonly>
										</td>
									</tr>
									<tr>
										<td class="text-right" style="vertical-align: middle; padding: .25rem" colspan="2">
											<b>ยอดชำระสุทธิ : </b>
										</td>
										<td width="20%" style="padding: .25rem">
											<input type="text" class="form-control text-right" name="" value="<?= number_format($invnetamount,2); ?>" readonly>
										</td>
									</tr>
								</tbody>
							</table>
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
									<tr style="border-bottom: 1px solid #000!important;">
										<td class="tdborder-black black-left"></td>
										<td class="tdborder-black"></td>
										<td class="tdborder-black"></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="col-md-12 col-sm-12 pb-4">
						<div class="table-responsive">
							<table class="table" style="background-color: #e9ecef;">
								<tbody>
									<tr>
										<td width="50px" style="border-top: none; vertical-align: middle;"><b>หมายเหตุ</b></td>
										<td style="border-top: none;">
											<input type="text" class="form-control" name="paymNote" id="paymNote" autocomplete="off" value="<?=$obj_row["paym_note"]?>">
										</td>
									</tr>
								</tbody>
							</table>
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
								<div class="row mb-2">
									<div class="col-md-12 px-4 text-center" style="border-bottom: 1px dotted #000;">
										<span><?= $nameMgr != '' ? $nameMgr : '&nbsp;'?></span>
									</div>
								</div>

								<div class="row">
									<div class="col-md-7 text-center">
										<b>ผู้ตรวจจ่าย วันที่</b>
									</div>
									<div class="col-md-5 col-sm-12 text-center" style="border-bottom: 1px dotted #000;">
										<span><?= $dateMgr != '' ? $dateMgr : '&nbsp;'?></span>
									</div>
								</div>
							</div>

							<div class="col-md-3 col-sm-12 px-4">
								<div class="row mb-2">
									<div class="col-md-12 px-4 text-center" style="border-bottom: 1px dotted #000;">
										<span><?= $nameCEO != '' ? $nameCEO : '&nbsp;'?></span>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 text-center">
										<b>ผู้อนุมัติ วันที่</b>
									</div>
									<div class="col-md-6 col-sm-12 text-center" style="border-bottom: 1px dotted #000;">
										<span><?= $dateCEO != '' ? $dateCEO : '&nbsp;'?></span>
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
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paympaydate" class="mb-1">วันทำจ่าย</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paympaydate" id="paympaydate" value="<?=$obj_row["paym_paydate"]?>" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymuseridcreate" class="mb-1">User ID Create</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paymuseridcreate" id="paymuseridcreate" value="<?=$obj_row["paym_userid_create"];?>" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymcreatedate" class="mb-1">Create Date</label>
						<div class="input-group mb-2">
							<input type="datetime" class="form-control" name="paymcreatedate" id="paymcreatedate" value="<?=$obj_row["paym_createdate"]?>" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymuseridedit" class="mb-1">User ID Edit</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paymuseridedit" id="paymuseridedit" value="<?=$obj_row_user["user_id"]?>" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymeditdate" class="mb-1">Edit Date</label>
						<div class="input-group mb-2">
							<input type="datetime" class="form-control" name="paymeditdate" id="paymeditdate" value="" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paympayeename" class="mb-1">ชื่อผู้รับเงิน</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paympayeename" id="paympayeename" value="<?=$obj_row["paym_payeename"]?>" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paympayeedate" class="mb-1">วันที่รับเงิน</label>
						<div class="input-group mb-2">
							<input type="date" class="form-control" name="paympayeedate" id="paympayeedate" value="<?=$obj_row["paym_payeedate"]?>" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymyear" class="mb-1">Year</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paymyear" id="paymyear" value="<?=$obj_row["paym_year"]?>" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymmonth" class="mb-1">Month</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paymmonth" id="paymmonth" value="<?=$obj_row["paym_month"]?>" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymrev" class="mb-1">Rev</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paymrev" id="paymrev" value="<?=$obj_row["paym_rev"]?>" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymfile" class="mb-1">ไฟล์</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paymfile" id="paymfile" value="<?=$obj_row["paym_file"]?>" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymchkRptpaym" class="mb-1">ChkReport</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paymchkRptpaym" id="paymchkRptpaym" value="<?=$obj_row["paym_chkRptpaym"]?>" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="countChk" class="mb-1">Record ที่เลือก</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="countChk" id="countChk" value="<?=$countChk;?>" readonly>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 pb-4 text-center">
						<input type="button" class="btn btn-success px-5 py-2 mx-1" name="btnInsert" id="btnInsert" value="บันทึก">
					</div>
				</div>

			</form>

		</div>
	</section>

	<!-- START ADD INVOICE -->
	<div class="modal fade" id="AddInvoice" tabindex="-1" role="dialog" aria-labelledby="AddInvoiceTitle" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title" id="AddInvoiceTitle">เพิ่มใบแจ้งหนี้</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="POST" name="frmPayment" id="frmPayment" action="">
					<div class="modal-body mx-3">
						<div class="row py-4 px-1" style="background-color: #e9ecef">
							<div class="col-md-2">
								<label style="margin-top: .5rem; margin-bottom: .65rem;">ฝ่าย</label>
								<div class="input-group">
									<?php
										$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
										$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
										$obj_row_dep = mysqli_fetch_array($obj_rs_dep);
									?>
									<input type="text" class="form-control" name="depname" id="depname" value="<?=$obj_row_dep["dep_name"];?>" readonly style="background-color: #FFF">
									<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$dep;?>">
								</div>
							</div>

							<div class="col-md-10">
								<div class="row">
									<div class="col-auto">
										<label style="margin-top: .5rem; margin-bottom: .65rem;">ค้นหาโดย : </label>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<input type="radio" name="SearchINVBy" id="INVinvno" value="inv_no" checked="checked">
											<label for="INVinvno"><span>เลขที่ใบแจ้งหนี้</span></label>
										</div>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchINVBy" id="INVpayaname" value="paya_name">
											<label for="INVpayaname"><span>ชื่อบริษัทเจ้าหนี้</span></label>
										</div>
									</div>
									<input type="text" class="form-control d-none" name="SearchINVVal" id="SearchINVVal" value="inv_no">
								</div>

								<div class="input-group">
									<input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกเลขที่ใบแจ้งหนี้ที่ต้องการค้นหา" autocomplete="off">
								</div>
							</div>

							<div class="col-md-12 d-none">
								<input type="text" name="edit_compid" value="<?=$cid;?>">
								<input type="text" name="edit_depid" value="<?=$dep;?>">
								<input type="text" name="edit_paymid" value="<?=$paymid;?>">
								<input type="text" name="edit_paymno" value="<?=$obj_row["paym_no"];?>">
								<input type="text" name="countChk" id="countChk" value="<?=$countChk;?>" readonly>
							</div>
						</div>

						<div class="row d-none" style="background-color: #FFFFFF">
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>ลำดับที่</th>
											<th>เลขที่ใบแจ้งหนี้</th>
											<th>สถานะ</th>
											<th>รหัสสถานะ</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
											$str_sql_add = "SELECT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id INNER JOIN user_tb AS u ON i.inv_userid_create = u.user_id WHERE inv_statusMgr = 1 AND inv_apprMgrno <> '' AND inv_paymid = '' AND inv_compid = '". $cid ."' AND inv_depid = '". $dep ."' AND inv_paymid = '' ORDER BY inv_id DESC";
											$obj_rs_add = mysqli_query($obj_con, $str_sql_add);
											$i = 1;
											while ($obj_row_add = mysqli_fetch_array($obj_rs_add)) {
										?>
										<tr>
											<td>
												<input type="text" class="form-control" name="id<?=$i;?>" id="id<?=$i;?>" value="<?=$i;?>" readonly>
											</td>
											<td>
												<input type="text" class="form-control" name="invid<?=$i;?>" id="invid<?=$obj_row_add["inv_id"];?>" value="<?=$obj_row_add["inv_id"];?>" readonly>
											</td>
											<td>
												<input type="text" class="form-control" name="invno<?=$i;?>" id="invno<?=$obj_row_add["inv_id"];?>" value="<?=$obj_row_add["inv_no"];?>" readonly>
											</td>
											<td>
												<input type="text" class="form-control" name="stsPaym<?=$i;?>" id="stsPaym<?=$obj_row_add["inv_id"];?>" value="<?=$obj_row_add["inv_statusPaym"];?>" readonly>
											</td>
											<td>
												<input type="text" class="form-control" name="NostsPaym<?=$i;?>" id="NostsPaym<?=$obj_row_add["inv_id"];?>" value="<?=$obj_row_add["inv_NostatusPaym"];?>" readonly>
											</td>
										</tr>
										<?php $i++; } ?>
									</tbody>
								</table>
							</div>
						</div>

						<div class="row" style="background-color: #FFFFFF">
							<div class="table-responsive" id="PaymentShow"></div>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger px-4 py-2" data-dismiss="modal">ปิด</button>
						<button type="button" class="btn btn-primary px-4 py-2" title="เลือก / Select" name="btnSelect" id="btnSelect">เลือก</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- END ADD INVOICE -->

	<!-- START VIEW INVOICE -->
	<div id="dataInvoice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dataInvoiceLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">รายละเอียดใบแจ้งหนี้</h3>
					<button type="button" class="close" name="invid_cancel" id="invid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body" id="invoice_detail">
				</div>
			</div>
		</div>
	</div>
	<!-- END VIEW INVOICE -->

	<script type="text/javascript">
		$(document).ready(function() {

			$("#btnInsert").click(function() {
				var formData = new FormData(this.form);
				$.ajax({
					type: "POST",
					url: "r_payment_edit.php",
					// data: $("#frmEditPayment").serialize(),
					data: formData,
					dataType: 'json',
					contentType: false,
					cache: false,
					processData:false,
					success: function(result) {
						if(result.status == 1) {
							window.location.href = result.url;
						} else {
							alert(result.message);
						}
					}
				});
			});





			$("#btnSelect").click(function() {
				var formData = new FormData(this.form);
				if($('#CountChkAll').val() == '0') {
					swal({
						title: "กรุณาเลือกใบแจ้งหนี้ที่ต้องการออกใบสำคัญจ่าย \n อย่างน้อย 1 รายการ",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning"
					}).then(function() {
						frmPayment.PaymentShow.focus();
					});
				} else {
					$.ajax({
						// url:"r_payment_edit_addinvoice.php",
						url:"r_payment_edit_selectinvoice.php",
						type:"POST",
						data:$('#frmPayment').serialize(),
						dataType:"Text",
						success:function(data){
							$('#frmPayment')[0].reset();
							$('#AddInvoice').modal('hide');
							$('#invTB').html(data);
							$(".modal-backdrop").remove();
							// swal({
							// 	title: "บันทึกข้อมูลสำเร็จ",
							// 	text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
							// 	type: "success",
							// 	closeOnClickOutside: false
							// 	// showConfirmButton: false
							// 	// timer: 3000
							// }).then(function() {
								frmPayment.invTB.focus();
								location.reload();
							// });
						}
					});
				}
			});



			//------ START VIEW INVOICE ------//
			$(document).on('click', '.view_data', function(){
				var id = $(this).attr("id");
				if(id != '') {
					$.ajax({
						url:"v_invoice.php",
						method:"POST",
						data:{id:id},
						success:function(data){
							$('#invoice_detail').html(data);
							$('#dataInvoice').modal('show');
						}
					});
				}
			});
			//------ END VIEW INVOICE ------//



			//--- DELECT ---//
			$(document).on('click', '.delete_data', function(){
				event.preventDefault();
				var inv_id = $(this).attr("id");
				var element = this;
				swal({
					title: "ลบรายการใบแจ้งหนี้นี้หรือไม่?",
					text: "",
					type: "warning",
					showCancelButton: true,
					confirmButtonText: "ตกลง",
					cancelButtonText: "ยกเลิก",
					confirmButtonClass: 'btn btn-danger',
					cancelButtonClass: 'btn btn-secondary',
					closeOnConfirm: false,
					closeOnCancel: false,
					dangerMode: true,
				},
				function(isConfirm) {
					if (isConfirm) {
						$.ajax({
							url: "r_payment_edit_selectinvoice_delete.php",
							type: "POST",
							data: {inv_id:inv_id},
							success: function () {
								setTimeout(function () {
									swal("ลบข้อมูลสำเร็จ!", "กรุณากด ตกลง เพื่อดำเนินการต่อ", "success");
									$(element).closest('tr').fadeOut(800,function(){
										$(this).remove();
										
									});

									// setInterval('location.reload()', 3000);
								}, 1000);
								location.reload();
							}
						});
					} else {
						swal("ยกเลิก", "กรุณากด ตกลง เพื่อดำเนินการต่อ", "error");
					}
				});
			});
			//--- END DELECT ---//





			load_dataAdd(1);
			function load_dataAdd(page, query = '', queryDep = '', queryComp = '', queryFil = '', queryFilVal = '', querySearch = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var querySearch = $('#SearchINVVal').val();
				$.ajax({
					url:"fetch_payment_edit_addinvoice.php",
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep, queryComp:queryComp, queryFil:queryFil, queryFilVal:queryFilVal, querySearch:querySearch},
					success:function(data) {
						$('#PaymentShow').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchINVVal').val();
				load_dataAdd(page, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

			$('#search_box').keyup(function() {
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchINVVal').val();
				load_dataAdd(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

			$('#SelectDep').change(function() {
				$('#depid').val($('#SelectDep').val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchINVVal').val();
				load_dataAdd(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>