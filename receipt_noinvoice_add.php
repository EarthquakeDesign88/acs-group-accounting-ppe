<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $dep ."'";
		$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
		$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

		$str_sql_comp = "SELECT * FROM company_tb WHERE comp_id = '". $cid ."'";
		$obj_rs_comp = mysqli_query($obj_con, $str_sql_comp);
		$obj_row_comp = mysqli_fetch_array($obj_rs_comp);

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<link rel="stylesheet" type="text/css" href="css/checkbox.css">
	<script type="text/javascript" src="js/calinvoiceRevenue.js"></script>


	<style type="text/css">
		div#show-listCust {
			position: absolute;
			z-index: 99;
			width: 100%;
			margin-left: -15px!important;
		}
		.list-unstyled {
			position: relative;
			background-color:#FFFF;
			cursor:pointer;
			margin-left: 15px;
			margin-right: 15px;
			-webkit-box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
					box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
		}
		.list-group-item {
			font-family: 'Sarabun', sans-serif;
			cursor: pointer;
			border: 1px solid #eaeaea;
			list-style: none;
			top: 50%;
			padding: .75rem!important;
		}
		.list-group-item:hover {
			background-color: #f5f5f5;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmReceiptNoInvoice" id="frmReceiptNoInvoice" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-plus-circle"></i>
							&nbsp;&nbsp;เพิ่มเสร็จรับเงิน
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="useridCreate" id="useridCreate" value="<?=$obj_row_user["user_id"];?>">
						<input type="date" class="form-control" name="CreateDate" id="CreateDate" value="">
						<input type="text" class="form-control" name="useridEdit" id="useridEdit" value="">
						<input type="date" class="form-control" name="EditDate" id="EditDate" value="">
						<input type="text" class="form-control" name="page_status" id="page_status" value="edit">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 py-3">
						<h2>ใบเสร็จรับเงินฝ่าย&nbsp;&nbsp;<?=$obj_row_dep["dep_name"];?></h2>
					</div>

					<div class="col-md-12 pt-1 pb-3">
						<div class="row">
							<div class="col-md-4 mb-0">
								<div class="checkbox">
									<input type="checkbox" name="PartProj" id="havePart">
									<label for="havePart"><span>กรอกเลขที่ใบเสร็จรับเงินย้อนหลัง</span></label>
								</div>
							</div>
							<div class="col-md-3 mb-0 d-none">
								<input type="text" class="form-control" name="BackVal" id="BackVal">
							</div>
						</div>
						<script type="text/javascript">
							$(document).ready(function() {
								$("#havePart").change(function() {
									if (!this.checked) {
										// $("#BackVal").attr('disabled', 'disabled');
										$("#Reno").attr('readonly', 'true');
										$("#BackVal").val('1');
									} else {
										// $("#BackVal").removeAttr('disabled');
										$("#Reno").removeAttr('readonly');
										$("#BackVal").val('0');
									}
								});
								$("#havePart").trigger("change")
							});
						</script>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="Reno" class="mb-1">เลขที่ใบเสร็จรับเงิน</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-numbered"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="Reno" id="Reno" autocomplete="off" placeholder="เว้นว่างไว้เพื่อสร้างอัตโนมัติ" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="Redate" class="mb-1">วันที่ใบเสร็จรับเงิน</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="Redate" id="Redate" autocomplete="off" autofocus tabindex="1">
						</div>
						<div class="input-group d-none">
							<input type="text" class="form-control" name="SelReDate" id="SelReDate">
							<input type="text" class="form-control" name="SelReYear" id="SelReYear">
							<input type="text" class="form-control" name="SelYear" id="SelYear">
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="invRedate" class="mb-1">เดือน</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<select class="custom-select form-control" name="SelMonth" id="SelMonth" tabindex="2">
								<option value="">กรุณาเลือกเดือน...</option>
								<option value="01">มกราคม</option>
								<option value="02">กุมภาพันธ์</option>
								<option value="03">มีนาคม</option>
								<option value="04">เมษายน</option>
								<option value="05">พฤษภาคม</option>
								<option value="06">มิถุนายน</option>
								<option value="07">กรกฎาคม</option>
								<option value="08">สิงหาคม</option>
								<option value="09">กันยายน</option>
								<option value="10">ตุลาคม</option>
								<option value="11">พฤศจิกายน</option>
								<option value="12">ธันวาคม</option>
							</select>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="Redepid" class="mb-1">ฝ่าย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="depname" id="depname" value="<?=$obj_row_dep["dep_name"];?>" readonly>
							<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$dep;?>">
						</div>
					</div>

					<div class="col-md-12 pt-1 pb-3 d-none">
						<label for="searchCompany" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทในเครือ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-building"></i>
								</i>
							</div>
							<input type="text" name="searchCompany" id="searchCompany" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?=$obj_row_comp["comp_name"];?>">

							<input type="text" class="form-control d-none" id="compid" name="compid" value="<?=$obj_row_comp["comp_id"];?>">
						</div>
					</div>

					<div class="col-md-12 pt-1 pb-3" id="showDataCust">
						<label for="searchCustomer" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทผู้รับบริการ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-building"></i>
								</i>
							</div>
							<input type="text" name="searchCustomer" id="searchCustomer" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" tabindex="3" value="">

							<input type="text" class="form-control d-none" id="custid" name="custid" value="">

							<div class="input-group-append">
								<button type="button" class="btn btn-info" onclick="
								document.getElementById('searchCustomer').value = ''; 
								document.getElementById('custid').value = '';
								document.getElementById('searchCustomer').focus();
								document.getElementById('show-listCust').style.display = 'none';" title="Clear">
									<i class="icofont-close-circled"></i>
									<span class="descbtn">&nbsp;&nbsp;Clear</span>
								</button>
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddCustomer" data-backdrop="static" data-keyboard="false" title="เพิ่มบริษัท">
									<i class="icofont-plus-circle"></i>
									<span class="descbtn">&nbsp;&nbsp;เพิ่มบริษัท</span>
								</button>
							</div>
						</div>
						<div class="list-group" id="show-listCust"></div>
					</div>

					<div class="col-md-12 pt-1 pb-3">
						<label for="outputTax" class="mb-1">รายละเอียดภาษีขาย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-file-document"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="outputTax" id="outputTax" autocomplete="off" placeholder="กรุณากรอกรายละเอียดภาษีขาย" tabindex="4">
						</div>
					</div>

					<div class="col-md-12 pt-1 pb-3">
						<div class="row">
							<div class="col-md-1"></div>
							<div class="col-md-7">
								<div class="row">
									<div class="col-md-12">
										<div class="text-center" style="background-color: #E9ECEF; padding: 12px 0; border: 1px solid #000;">
											<b>รายละเอียด</b>
										</div>
										<input type="text" class="form-control my-1" name="invredesc1" id="invredesc1" autocomplete="off" tabindex="5">
										<input type="text" class="form-control my-1" name="invredesc2" id="invredesc2" autocomplete="off" tabindex="6">
										<input type="text" class="form-control my-1" name="invredesc3" id="invredesc3" autocomplete="off" tabindex="7">
										<input type="text" class="form-control my-1" name="invredesc4" id="invredesc4" autocomplete="off" tabindex="8">
										<input type="text" class="form-control my-1" name="invredesc5" id="invredesc5" autocomplete="off" tabindex="9">
										<input type="text" class="form-control my-1 <?php if($cid != 'C014') echo "d-none"; ?>" name="invredesc6" id="invredesc6" autocomplete="off" tabindex="10">
										<input type="text" class="form-control my-1 <?php if($cid != 'C014') echo "d-none"; ?>" name="invredesc7" id="invredesc7" autocomplete="off" tabindex="11">
										<input type="text" class="form-control my-1 <?php if($cid != 'C014') echo "d-none"; ?>" name="invredesc8" id="invredesc8" autocomplete="off" tabindex="12">
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="row">
									<div class="col-md-12">
										<div class="text-center" style="background-color: #E9ECEF; padding: 12px 0; border: 1px solid #000;">
											<b>จำนวนเงิน</b>
										</div>
										<input type="text" class="form-control text-right my-1" name="amount1" id="amount1" autocomplete="off" value="0.00" tabindex="13">
										<input type="text" class="form-control text-right d-none my-1" name="amountHidden1" id="amountHidden1" value="0.00" readonly>

										<input type="text" class="form-control text-right my-1" name="amount2" id="amount2" autocomplete="off" value="0.00" tabindex="14">
										<input type="text" class="form-control text-right d-none my-1" name="amountHidden2" id="amountHidden2" value="0.00" readonly>

										<input type="text" class="form-control text-right my-1" name="amount3" id="amount3" autocomplete="off" value="0.00" tabindex="15">
										<input type="text" class="form-control text-right d-none my-1" name="amountHidden3" id="amountHidden3" value="0.00" readonly>

										<input type="text" class="form-control text-right my-1" name="amount4" id="amount4" autocomplete="off" value="0.00" tabindex="16">
										<input type="text" class="form-control text-right d-none my-1" name="amountHidden4" id="amountHidden4" value="0.00" readonly>

										<input type="text" class="form-control text-right my-1" name="amount5" id="amount5" autocomplete="off" value="0.00" tabindex="17">
										<input type="text" class="form-control text-right d-none my-1" name="amountHidden5" id="amountHidden5" value="0.00" readonly>

										<input type="text" class="form-control text-right my-1 <?php if($cid != 'C014') echo "d-none"; ?>" name="amount6" id="amount6" autocomplete="off" value="0.00" tabindex="18">
										<input type="text" class="form-control text-right d-none my-1" name="amountHidden6" id="amountHidden6" value="0.00" readonly>

										<input type="text" class="form-control text-right my-1 <?php if($cid != 'C014') echo "d-none"; ?>" name="amount7" id="amount7" autocomplete="off" value="0.00" tabindex="19">
										<input type="text" class="form-control text-right d-none my-1" name="amountHidden7" id="amountHidden7" value="0.00" readonly>

										<input type="text" class="form-control text-right my-1 <?php if($cid != 'C014') echo "d-none"; ?>" name="amount8" id="amount8" autocomplete="off" value="0.00" tabindex="20">
										<input type="text" class="form-control text-right d-none my-1" name="amountHidden8" id="amountHidden8" value="0.00" readonly>
									</div>
								</div>
							</div>
							<div class="col-md-1"></div>
						</div>

						<div class="row">
							<div class="col-md-1"></div>
							<div class="col-md-7">
								<div class="row">
									<div class="col-md-8">
										<div class="row">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-5">
														<label class="col-form-label mt-1">ตามใบแจ้งหนี้เลขที่</label>
													</div>
													<div class="col-md-7">
														<input type="text" class="form-control my-1" name="invNo" id="invNo" autocomplete="off" readonly tabindex="28">
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-3">
														<div class="input-group">
															<div class="checkbox">
																<input type="radio" name="bySelPay" id="byCash" value="1" tabindex="29">
																<label for="byCash" class="mb-1">
																	<span>เงินสด</span>
																</label>
															</div>
														</div>
													</div>
													<div class="col-md-4">
														<div class="input-group">
															<div class="checkbox">
																<input type="radio" name="bySelPay" id="byCheque" value="2" tabindex="30">
																<label for="byCheque" class="mb-1">
																	<span>เช็คเลขที่</span>
																</label>
															</div>
														</div>
													</div>
													<div class="col-md-5">
														<input type="text" class="form-control my-1" name="chequeNo" id="chequeNo" autocomplete="off" tabindex="31">
													</div>
													<div class="col-md-5">
														<div class="input-group">
															<div class="checkbox">
																<input type="radio" name="bySelPay" id="byTransfer" value="3" tabindex="32">
																<label for="byTransfer" class="mb-1">
																	<span>โอนเข้าบัญชี</span>
																</label>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<button type="button" class="btn btn-info" id="ClearRadio" title="Clear">
															<i class="icofont-close-circled"></i>
															<span class="descbtn">&nbsp;&nbsp;Clear การชำระเงิน</span>
														</button>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-12" id="SelectBankBranch">
														<label class="my-1">ธนาคาร</label>
														<div class="input-group">
															<select class="custom-select form-control" name="SelBank" id="SelBank" tabindex="33">
																<option value="" selected disabled>
																	กรุณาเลือกธนาคาร...
																</option>
																<?php
																	$str_sql_b = "SELECT * FROM bank_tb ORDER BY bank_name ASC";
																	$obj_rs_b = mysqli_query($obj_con, $str_sql_b);
																	while ($obj_row_b = mysqli_fetch_array($obj_rs_b)) {
																?>
																<option value="<?=$obj_row_b["bank_id"];?>">
																	<?=$obj_row_b["bank_name"];?>
																</option>
																<?php } ?>
															</select>
															<div class="input-group-append">
																<button type="button" class="btn btn-info" onclick="
																	document.getElementById('SelBank').value = '';" title="Clear">
																	<i class="icofont-close-circled"></i>
																	<span class="descbtn">&nbsp;&nbsp;Clear</span>
																</button>
																<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddBank" data-backdrop="static" data-keyboard="false" title="เพิ่มธนาคาร">
																	<i class="icofont-plus-circle"></i>
																	<span class="descbtn">
																		&nbsp;&nbsp;เพิ่มธนาคาร
																	</span>
																</button>
															</div>
														</div>
													</div>
													<div class="col-md-12" id="SelectBranch">
														<label class="my-1">สาขา</label>
														<div class="input-group">
															<select class="custom-select form-control" name="SelBranch" id="SelBranch" tabindex="34">
																<option value="" selected disabled>กรุณาเลือกสาขา...</option>
															</select>

															<div class="input-group-append">
																<button type="button" class="btn btn-info" onclick="
																	document.getElementById('SelBranch').value = '';" title="Clear">
																	<i class="icofont-close-circled"></i>
																	<span class="descbtn">&nbsp;&nbsp;Clear</span>
																</button>
																<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddBankBranch" data-backdrop="static" data-keyboard="false" title="เพิ่มธนาคาร">
																	<i class="icofont-plus-circle"></i>
																	<span class="descbtn">
																		&nbsp;&nbsp;เพิ่มสาขา
																	</span>
																</button>
															</div>
														</div>
														<input type="text" class="form-control d-none" name="brcval" id="brcval">
													</div>
													<div class="col-md-12">
														<label class="my-1">วันที่</label>
														<div class="input-group">
															<input type="date" class="form-control" name="chequeDate" id="chequeDate" tabindex="35">
														</div>
													</div>
													<div class="col-md-12">
														<label class="my-1">หมายเหตุ</label>
														<textarea class="form-control" name="ReNote" id="ReNote" rows="2" placeholder="หมายเหตุ" autocomplete="off"></textarea tabindex="36">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="row">
											<label class="col-md-12 col-form-label px-0 my-1 text-right">จำนวนเงิน Sub Total</label>

											<div class="col-md-12">
												<div class="row">
													<label class="col-md-6 col-form-label px-0 text-right">ภาษีมูลค่าเพิ่ม</label>
													<div class="col-md-6">
														<input type="text" class="form-control my-1 text-right" name="vatpercent" id="showVatPercent" autocomplete="off" value="0.00" tabindex="23">
														<input type="text" class="form-control d-none my-1 text-right" name="vatpercentHidden" id="calVatPercent" value="0.00" readonly>
													</div>
												</div>
											</div>

											<label class="col-md-12 col-form-label px-0 text-right">จำนวนเงินรวม Grand Total</label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="row">
									<div class="col-md-12">
										<input type="text" class="form-control my-1 text-right" name="subtotal" id="showSubtotal" autocomplete="off" value="0.00" tabindex="22" readonly>
										<input type="text" class="form-control d-none my-1 text-right" name="subtotalHidden" id="calSubtotal" value="0.00" readonly>
									
										<input type="text" class="form-control my-1 text-right" name="vat" id="showVat" autocomplete="off" value="0.00" tabindex="24" readonly>
										<input type="text" class="form-control d-none my-1 text-right" name="vatHidden" id="calVat" value="0.00" readonly>

										<input type="text" class="form-control text-right" name="grandtotal" id="showGrandtotal" autocomplete="off" value="0.00" tabindex="26" readonly>
										<input type="text" class="form-control d-none text-right" name="grandtotalHidden" id="calGrandtotal" value="0.00" readonly>

										<div class="form-group my-1">
											<div class="checkbox">
												<input type="checkbox" id="totalVat" onclick="checkInv()">
												<label for="totalVat"><span>รวมภาษีมูลค่าเพิ่มแล้ว</span></label>
												<input type="text" class="form-control d-none" id="totalChkVat" name="" value="0">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-1">
								<div class="row">
									<div class="col-md-12" style="padding-left: 0px;">
										<label class="col-md-12 col-form-label px-0 mt-1 text-center">+ / -</label>

										<input type="text" class="form-control my-1 text-right" name="diffvat" id="showDiffVat" autocomplete="off" value="0.00" tabindex="25">
										<input type="text" class="form-control my-1 text-right d-none" name="DiffVatHidden" id="calDiffVat" autocomplete="off" value="0.00" readonly>

										<input type="text" class="form-control my-1 text-right" name="diffgrand" id="showDiffGrand" autocomplete="off" value="0.00" tabindex="27">
										<input type="text" class="form-control my-1 text-right d-none" name="DiffGrandHidden" id="calDiffGrand" autocomplete="off" value="0.00" readonly>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12 pt-1 pb-3 d-none">
							<input type="text" class="form-control my-1" name="invresubdesc1" id="invresubdesc1" value="">
							<input type="text" class="form-control my-1" name="invresubdesc2" id="invresubdesc2" value="">
							<input type="text" class="form-control my-1" name="invresubdescHidden3" id="invresubdescHidden3" value="">
							<input type="text" class="form-control my-1" name="invresubdescHidden4" id="invresubdescHidden4" value="">
							<input type="text" class="form-control my-1" name="invresubdescHidden5" id="invresubdescHidden5" value="">
							<input type="text" class="form-control my-1" name="invresubdescHidden6" id="invresubdescHidden6" value="">
							<input type="text" class="form-control my-1" name="invresubdescHidden7" id="invresubdescHidden7" value="">
							<input type="text" class="form-control my-1" name="invresubdescHidden8" id="invresubdescHidden8" value="">
							<input type="text" class="form-control my-1" name="invresubdescHidden9" id="invresubdescHidden9" value="">
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12 pb-4 text-center">
					    <button type="button" class="btn btn-success  btn-action btn-action-preview"><i class="icofont-save"></i> บันทึกข้อมูล</button>
					</div>
				</div>
				
			</form>
			
		</div>
	</section>
<div id="modalSaveForm" class="modal fade modal-detail" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" >
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title py-2"><i class="icofont-save"></i> คุณต้องการบันทึกข้อมูลใช่หรือไม่?</h3>
            </div>
            <div class="modal-body" id="saveForm">
         </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-close" data-dismiss="modal" aria-hidden="true"><i class="icofont-close"></i> ไม่บันทึกข้อมูล</button>
            <button type="button" class="btn btn-success  btn-action btn-action-save"><i class="icofont-save"></i> บันทึกข้อมูล</button>
         </div>
        </div>
    </div>
</div>
	<?php 
		include 'bank_add.php';
		include 'branch_add.php'; 
		// include 'action_script_bank.php';
		include 'invoice_add_customer.php';
	?>

	<script type="text/javascript">

		$(document).ready(function() {

			$("#Redate").on('change', function() {
				var selectedDate = $(this).val();
				// alert(selectedDate);
				document.getElementById("SelReDate").value = selectedDate.substring(5, 7);
				var selectedYear = selectedDate.substring(0, 4);
				var selYear = Number(selectedYear) + 543;
				document.getElementById("SelReYear").value = selYear;
				var yy = document.getElementById("SelReYear").value;
				document.getElementById("SelYear").value = yy.substring(2, 4);
			});

			// $("select[name=SelBranch]").change(function() {
			// 	var selectedBrc = $(this).children("option:selected").val();
			// 	// alert("You have selected the country - " + selectedCountry);
			// 	$('#brcval').val(selectedBrc);
			// });

			$('#SelBank').change(function() {

				var id_bank = $(this).val();
				$.ajax({
					type: "POST",
					url: "ajax_bank.php",
					data: {id:id_bank,SelBank:'SelBank'},
					success: function(data){
						$('#SelBranch').html(data);
					}
				});

			});

			//------ START ADD INVOICE ------//
			$(".btn-action").click(function() {
				var formData = new FormData(document.getElementById("frmReceiptNoInvoice"));
				if($('#Redate').val() == '') {
					swal({
						title: "กรุณาเลือกวันที่",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmReceipt.Redate.focus();
					});
				} else if($('#SelMonth').val() == '') {
					swal({
						title: "กรุณาเลือกเดือนออกใบเสร็จรับเงิน",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function () {
						frmReceipt.SelMonth.focus();
					});
				} else if($('#outputTax').val() == '') {
					swal({
						title: "กรุณากรอกรายละเอียดภาษีขาย",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function () {
						frmReceipt.outputTax.focus();
					});
				} else {
					if($(this).hasClass("btn-action-save")){
						$.ajax({
							type: "POST",
							url: "r_receipt_add.php",
							data: formData,
							dataType: 'json',
							contentType: false,
							cache: false,
							processData:false,
							success: function(result) {
								if(result.status == 1) {
									swal({
										title: "บันทึกข้อมูลสำเร็จ",
										text: "เลขที่ใบเสร็จรับเงิน " + result.message,
										type: "success",
										closeOnClickOutside: false
									},function() {
										window.location.href ="receipt.php?cid="+ result.cid +"&dep=" + result.dep
									});
								} else {
									alert(result.message);
								}
							}
						});
						
					}else{
						var modalSave = $("#modalSaveForm");
						var div = $("#saveForm");
						
						div.html("<br><div align='center'>กรุณารอสักครู่...<br><br><i class='icofont-spinner icofont-spin' style='font-size:24px'></i></div>");
						modalSave.modal('show');
						
						$.ajax({
							type: 'POST',
							url: "receipt_preview.php",
							data: formData,
							dataType: 'json',
							contentType: false,
							cache: false,
							processData:false,
							success:function(data){
								var preview_url = data.preview_url;
								var preview_path = data.preview_path;
								var content = '<object data="'+preview_url+'#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="500" style="border: 1px solid"></object>';
								div.html(content);
									
								setTimeout(function() {
									$.ajax({
										url: "receipt_preview.php?tmp="+preview_path
									}); 
								}, 500);
				
							}
						}); 
					}
				}
			});
			//------ END ADD INVOICE ------//

			
			$("input[name='bySelPay']").click(function(){
				var radioValue = $("input[id='byTransfer']:checked").val();
				if(radioValue) {
					$('#chequeNo').val('โอนเข้าบัญชี');
				} else {
					$('#chequeNo').val('');
				}

				$('#ClearRadio').click(function() {
					$("input:radio[name=bySelPay]:checked")[0].checked = false;
					$('#chequeNo').val('');
				});
			});

		});
		
		function Comma(Num){
			Num += '';
			Num = Num.replace(/,/g, '');
			x = Num.split('.');
			x1 = x[0];
			x2 = x.length > 1 ? '.' + x[1] : '';
			var rgx = /(\d+)(\d{3})/;

			while (rgx.test(x1))
				x1 = x1.replace(rgx, '$1' + ',' + '$2');
			return x1 + x2;
		}

			document.getElementById("amount1").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden1").value = this.value.replace(/,/g, "");
			}

			document.getElementById("amount2").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden2").value = this.value.replace(/,/g, "");
			}

			document.getElementById("amount3").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden3").value = this.value.replace(/,/g, "");
			}

			document.getElementById("amount4").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden4").value = this.value.replace(/,/g, "");
			}

			document.getElementById("amount5").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden5").value = this.value.replace(/,/g, "");
			}

			document.getElementById("amount6").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden6").value = this.value.replace(/,/g, "");
			}

			document.getElementById("amount7").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden7").value = this.value.replace(/,/g, "");
			}

			document.getElementById("amount8").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden8").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showVatPercent").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calVatPercent").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showDiffVat").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calDiffVat").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showGrandtotal").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calGrandtotal").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showDiffGrand").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calDiffGrand").value = this.value.replace(/,/g, "");
			}
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>