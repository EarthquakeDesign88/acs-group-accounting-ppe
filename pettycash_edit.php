<?php
	
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$pcid = $_GET["pcid"];

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql = "SELECT * FROM pettycash_tb AS pc 
					INNER JOIN payment_tb AS paym ON pc.pcash_paymid = paym.paym_id 
					LEFT JOIN invoice_tb AS i ON paym.paym_id = i.inv_paymid 
					INNER JOIN department_tb AS d ON pc.pcash_depid = d.dep_id 
					INNER JOIN company_tb AS c ON pc.pcash_compid = c.comp_id 
					INNER JOIN payable_tb AS p ON pc.pcash_payaid = p.paya_id 
					WHERE pcash_id = '".$pcid."'";
		$obj_rs = mysqli_query($obj_con, $str_sql);
		$obj_row = mysqli_fetch_array($obj_rs);

		$balance = $obj_row["inv_balancetotal"] + $obj_row["pcash_netamount1"] + $obj_row["pcash_netamount2"] + $obj_row["pcash_netamount3"] + $obj_row["pcash_netamount4"] + $obj_row["pcash_netamount5"];

		$str_sql_paym = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id WHERE paym_id = '". $obj_row["pcash_paymid"] ."'";
		$obj_rs_paym = mysqli_query($obj_con, $str_sql_paym);
		$obj_row_paym = mysqli_fetch_array($obj_rs_paym);

		$refPaym = $obj_row_paym["paym_no"] . "&nbsp;&nbsp;" . $obj_row_paym["paya_name"];

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<link rel="stylesheet" type="text/css" href="css/checkbox.css">

	<style type="text/css">
		@media only screen and (max-width: 992px)  {
			.descbtn {
				display: none;
			}
		}
		div#show-listPaya, div#show-listPettycash {
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
<body onload="calculateSum();">

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmEditPettyCash" id="frmEditPettyCash" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-edit"></i>&nbsp;&nbsp;แก้ไขใบจ่ายเงินสดย่อย
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="paymid" id="paymid" value="<?=$obj_row["pcash_paymid"];?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 pt-1 pb-3">
						<label for="searchPayment" class="mb-1">อ้างอิงเลขที่ใบสำคัญจ่าย</label>
						<div class="input-group mb-0">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-numbered"></i>
								</i>
							</div>
							<input type="text" name="searchPayment" id="searchPayment" class="form-control" placeholder="กรอกเลขที่ใบสำคัญจ่ายที่ต้องการค้นหา" autocomplete="off" value="<?=$refPaym;?>" readonly>

							<input type="text" class="form-control d-none" id="paymid" name="paymid" value="<?=$obj_row["pcash_paymid"];?>">
						</div>
					</div>

					<div class="col-md-9 pt-1 pb-3"></div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="invbalanceshow" class="mb-1">ยอดเงินใบสำคัญจ่าย</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control text-right" name="invbalanceshow" id="invbalanceshow" value="<?=number_format($balance,2);?>" readonly>
						</div>
						<input type="text" class="form-control d-none" name="invbalance" id="invbalance" value="<?=$balance;?>">
					</div>

					<div class="col-md-12" id="PCashRefPaym">
						<div class="row">
							<div class="col-lg-4 col-md-12 pt-1 pb-3">
								<label for="pcashNo" class="mb-1">เลขที่ใบจ่ายเงินสดย่อย</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-numbered"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="pcashNo" id="pcashNo" autocomplete="off" value="<?=$obj_row["pcash_no"];?>" readonly>
								</div>
							</div>

							<div class="col-lg-3 col-md-12 pt-1 pb-3">
								<label for="pcashDate" class="mb-1">วันที่ใบจ่ายเงินสดย่อย</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-ui-calendar"></i>
										</i>
									</div>
									<input type="date" class="form-control" name="pcashDate" id="pcashDate" autocomplete="off" value="<?=$obj_row["pcash_date"];?>" autofocus>
								</div>
							</div>

							<div class="col-lg-2 col-md-12 pt-1 pb-3">
								<label for="SelectDep" class="mb-1">ฝ่าย</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-company"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="depname" id="depname" value="<?=$obj_row["dep_name"];?>" readonly>
									<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$dep;?>">
								</div>
							</div>

							<div class="col-lg-3 col-md-12 mr-auto pt-1 pb-3">
								<div class="row">
									<div class="col-lg-6 col-md-3 col-sm-12 pb-3 pb-sm-1">
										<label class="salary-tax"></label>
										<div class="input-group-prepend">
											<div class="checkbox">
												<input type="checkbox" id="pcashtaxrefund" onclick="checkTaxRefund()" <?php if($obj_row["pcash_taxrefund"] == 1) echo "checked"; ?>>
												<label for="pcashtaxrefund"><span>ขอคืนภาษี</span></label>
												<input type="text" class="form-control d-none" id="pcashtaxrefundChange" name="pcashtaxrefund" value="<?=$obj_row["pcash_taxrefund"];?>">
											</div>
											<script type="text/javascript">
												function checkTaxRefund() {
													var checkbox = document.getElementById('pcashtaxrefund');
													if (checkbox.checked != true) {
														document.getElementById('pcashtaxrefundChange').value = "0";
													} else {
														document.getElementById('pcashtaxrefundChange').value = "1";
													}
												}
											</script>
										</div>
									</div>
								</div>
							</div>

							<div class="col-lg-12 col-md-12 mr-auto pt-1 pb-3">
								<div class="row">
									<div class="col-lg-1 col-md-2">
										<label for="payby" class="mb-1">จ่ายโดย</label>
										<div class="input-group-prepend">
											<div class="checkbox">
												<input type="radio" name="paybySel" id="paybyCash" value="<?=$obj_row["pcash_typepay"];?>" <?php if($obj_row["pcash_typepay"] == 1) echo "checked"; ?>>
												<label for="paybyCash" class="mb-1"><span>เงินสด</span></label>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-lg-12 col-md-12 pt-1 pb-3" id="showDataComp">
								<label for="searchCompany" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทในเครือ</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-company"></i>
										</i>
									</div>
									<input type="text" name="searchCompany" id="searchCompany" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?=$obj_row["comp_name"];?>" readonly>

									<input type="text" class="form-control d-none" id="invcompid" name="invcompid" value="<?=$obj_row["comp_id"];?>">
								</div>
							</div>

							<div class="col-lg-12 col-md-12 mr-auto pt-1 pb-3" id="showDataPaya">
								<label for="searchPayable" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทเจ้าหนี้</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-company"></i>
										</i>
									</div>
									<input type="text" name="searchPayable" id="searchPayable" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" value="<?=$obj_row["paya_name"];?>" autocomplete="off">

									<input type="text" class="form-control d-none" id="invpayaid" name="invpayaid" value="<?=$obj_row["pcash_payaid"];?>">

									<div class="input-group-append">
										<button type="button" class="btn btn-info" onclick="
										document.getElementById('searchPayable').value = ''; 
										document.getElementById('invpayaid').value = '';
										document.getElementById('searchPayable').focus();
										document.getElementById('show-listPaya').style.display = 'none';" title="Clear">
											<i class="icofont-close-circled"></i>
											<span class="descbtn">&nbsp;&nbsp;Clear</span>
										</button>
										<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddPayable" data-backdrop="static" data-keyboard="false" title="เพิ่มบริษัท">
											<i class="icofont-plus-circle"></i>
											<span class="descbtn">&nbsp;&nbsp;เพิ่มบริษัท</span>
										</button>
									</div>
								</div>
								<div class="list-group" id="show-listPaya"></div>
							</div>

							<div class="col-lg-12 col-md-12 mr-auto pt-1 pb-3">
								<table class="table table-bordered">
									<thead class="thead-light">
										<tr>
											<th width="3%"></th>
											<th width="97%">รายละเอียด</th>
										</tr>
									</thead>
									<tbody>
										<tr class="pettybottom">
											<td>1.</td>
											<td>
												<div class="row">
													<div class="col-md-7">
														<label for="pcashDesc1" class="mb-1">รายการ</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control" name="pcashDesc1" id="pcashDesc1" maxlength="255" autocomplete="off" value="<?=$obj_row["pcash_description1"]?>">
														</div>
													</div>
													<div class="col-md-2">
														<label for="pcashSub1" class="mb-1">ก่อน VAT</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showSub1" autocomplete="off" value="<?=number_format($obj_row["pcash_subtotal1"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashSub1" name="pcashSub1" id="calSub1" autocomplete="off" value="<?=$obj_row["pcash_subtotal1"]?>">
													</div>
													<div class="col-md-1">
														<label for="pcashVatP1" class="mb-1">VAT %</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showVatP1" autocomplete="off" value="<?=number_format($obj_row["pcash_vatpercent1"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashVatP1" name="pcashVatP1" id="calVatP1" autocomplete="off" value="<?=$obj_row["pcash_vatpercent1"]?>">
													</div>
													<div class="col-md-2">
														<label for="pcashVat1" class="mb-1">VAT</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashVat1" autocomplete="off" value="<?=number_format($obj_row["pcash_vat1"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none pcashVat1" name="pcashVatHidden1" id="pcashVatHidden1" autocomplete="off" value="<?=$obj_row["pcash_vat1"];?>" readonly>
													</div>
													<div class="col-md-7"></div>
													<div class="col-md-2">
														<label for="pcashTax1" class="mb-1">ก่อน TAX</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showTax1" autocomplete="off" value="<?=number_format($obj_row["pcash_tax1"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashTax1" name="pcashTax1" id="calTax1" autocomplete="off" value="<?=$obj_row["pcash_tax1"];?>">
													</div>
													<div class="col-md-1">
														<label for="pcashTaxP1" class="mb-1">TAX %</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showTaxP1" autocomplete="off" value="<?=number_format($obj_row["pcash_taxpercent1"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashTaxP1" name="pcashTaxP1" id="calTaxP1" autocomplete="off" value="<?=$obj_row["pcash_taxpercent1"];?>">
													</div>
													<div class="col-md-2">
														<label for="pcashTaxT1" class="mb-1">TAX</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashTaxT1" autocomplete="off" value="<?=number_format($obj_row["pcash_taxtotal1"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none pcashTaxT1" name="pcashTaxTHidden1" id="pcashTaxTHidden1" autocomplete="off" value="<?=$obj_row["pcash_taxtotal1"];?>" readonly>
													</div>
													<div class="col-md-4"></div>
													<div class="col-md-3">
														<label for="pcashGrand1" class="mb-1">เงินรวม</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashGrand1" autocomplete="off" value="<?=number_format($obj_row["pcash_grandtotal1"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none pcashGrand1" name="pcashGrandHidden1" id="pcashGrandHidden1" autocomplete="off" value="<?=$obj_row["pcash_grandtotal1"];?>" readonly>
													</div>
													<div class="col-md-2">
														<label for="pcashDiff1" class="mb-1">+/- ส่วนต่าง</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showDiff1" autocomplete="off" value="<?=number_format($obj_row["pcash_difference1"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashDiff1" name="pcashDiff1" id="calDiff1" autocomplete="off" value="<?=$obj_row["pcash_difference1"];?>">
													</div>
													<div class="col-md-3">
														<label for="pcashNet1" class="mb-1">ยอดชำระสุทธิ</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashNet1" autocomplete="off" value="<?=number_format($obj_row["pcash_netamount1"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none" name="pcashNetHidden1" id="pcashNetHidden1" autocomplete="off" value="<?=$obj_row["pcash_netamount1"];?>" readonly>
													</div>
												</div>
											</td>
										</tr>

										<tr class="pettybottom">
											<td>2.</td>
											<td>
												<div class="row">
													<div class="col-md-7">
														<label for="pcashDesc2" class="mb-1">รายการ</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control" name="pcashDesc2" id="pcashDesc2" maxlength="255" autocomplete="off" value="<?=$obj_row["pcash_description2"]?>">
														</div>
													</div>
													<div class="col-md-2">
														<label for="pcashSub2" class="mb-1">ก่อน VAT</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showSub2" autocomplete="off" value="<?=number_format($obj_row["pcash_subtotal2"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashSub2" name="pcashSub2" id="calSub2" autocomplete="off" value="<?=$obj_row["pcash_subtotal2"]?>">
													</div>
													<div class="col-md-1">
														<label for="pcashVatP2" class="mb-1">VAT %</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showVatP2" autocomplete="off" value="<?=number_format($obj_row["pcash_vatpercent2"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashVatP2" name="pcashVatP2" id="calVatP2" autocomplete="off" value="<?=$obj_row["pcash_vatpercent2"]?>">
													</div>
													<div class="col-md-2">
														<label for="pcashVat2" class="mb-1">VAT</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashVat2" autocomplete="off" value="<?=number_format($obj_row["pcash_vat2"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none pcashVat2" name="pcashVatHidden2" id="pcashVatHidden2" autocomplete="off" value="<?=$obj_row["pcash_vat2"];?>" readonly>
													</div>
													<div class="col-md-7"></div>
													<div class="col-md-2">
														<label for="pcashTax2" class="mb-1">ก่อน TAX</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showTax2" autocomplete="off" value="<?=number_format($obj_row["pcash_tax2"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashTax2" name="pcashTax2" id="calTax2" autocomplete="off" value="<?=$obj_row["pcash_tax2"];?>">
													</div>
													<div class="col-md-1">
														<label for="pcashTaxP2" class="mb-1">TAX %</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showTaxP2" autocomplete="off" value="<?=number_format($obj_row["pcash_taxpercent2"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashTaxP2" name="pcashTaxP2" id="calTaxP2" autocomplete="off" value="<?=$obj_row["pcash_taxpercent2"];?>">
													</div>
													<div class="col-md-2">
														<label for="pcashTaxT2" class="mb-1">TAX</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashTaxT2" autocomplete="off" value="<?=number_format($obj_row["pcash_taxtotal2"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none pcashTaxT2" name="pcashTaxTHidden2" id="pcashTaxTHidden2" autocomplete="off" value="<?=$obj_row["pcash_taxtotal2"];?>" readonly>
													</div>
													<div class="col-md-4"></div>
													<div class="col-md-3">
														<label for="pcashGrand2" class="mb-1">เงินรวม</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashGrand2" autocomplete="off" value="<?=number_format($obj_row["pcash_grandtotal2"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none pcashGrand2" name="pcashGrandHidden2" id="pcashGrandHidden2" autocomplete="off" value="<?=$obj_row["pcash_grandtotal2"];?>" readonly>
													</div>
													<div class="col-md-2">
														<label for="pcashDiff2" class="mb-1">+/- ส่วนต่าง</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showDiff2" autocomplete="off" value="<?=number_format($obj_row["pcash_difference2"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashDiff2" name="pcashDiff2" id="calDiff2" autocomplete="off" value="<?=$obj_row["pcash_difference2"];?>">
													</div>
													<div class="col-md-3">
														<label for="pcashNet2" class="mb-1">ยอดชำระสุทธิ</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashNet2" autocomplete="off" value="<?=number_format($obj_row["pcash_netamount2"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none" name="pcashNetHidden2" id="pcashNetHidden2" autocomplete="off" value="<?=$obj_row["pcash_netamount2"];?>" readonly>
													</div>
												</div>
											</td>
										</tr>

										<tr class="pettybottom">
											<td>3.</td>
											<td>
												<div class="row">
													<div class="col-md-7">
														<label for="pcashDesc3" class="mb-1">รายการ</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control" name="pcashDesc3" id="pcashDesc3" maxlength="255" autocomplete="off" value="<?=$obj_row["pcash_description3"]?>">
														</div>
													</div>
													<div class="col-md-2">
														<label for="pcashSub3" class="mb-1">ก่อน VAT</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showSub3" autocomplete="off" value="<?=number_format($obj_row["pcash_subtotal3"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashSub3" name="pcashSub3" id="calSub3" autocomplete="off" value="<?=$obj_row["pcash_subtotal3"]?>">
													</div>
													<div class="col-md-1">
														<label for="pcashVatP3" class="mb-1">VAT %</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showVatP3" autocomplete="off" value="<?=number_format($obj_row["pcash_vatpercent3"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashVatP3" name="pcashVatP3" id="calVatP3" autocomplete="off" value="<?=$obj_row["pcash_vatpercent3"]?>">
													</div>
													<div class="col-md-2">
														<label for="pcashVat3" class="mb-1">VAT</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashVat3" autocomplete="off" value="<?=number_format($obj_row["pcash_vat3"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none pcashVat3" name="pcashVatHidden3" id="pcashVatHidden3" autocomplete="off" value="<?=$obj_row["pcash_vat3"];?>" readonly>
													</div>
													<div class="col-md-7"></div>
													<div class="col-md-2">
														<label for="pcashTax3" class="mb-1">ก่อน TAX</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showTax3" autocomplete="off" value="<?=number_format($obj_row["pcash_tax3"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashTax3" name="pcashTax3" id="calTax3" autocomplete="off" value="<?=$obj_row["pcash_tax3"];?>">
													</div>
													<div class="col-md-1">
														<label for="pcashTaxP3" class="mb-1">TAX %</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showTaxP3" autocomplete="off" value="<?=number_format($obj_row["pcash_taxpercent3"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashTaxP3" name="pcashTaxP3" id="calTaxP3" autocomplete="off" value="<?=$obj_row["pcash_taxpercent3"];?>">
													</div>
													<div class="col-md-2">
														<label for="pcashTaxT3" class="mb-1">TAX</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashTaxT3" autocomplete="off" value="<?=number_format($obj_row["pcash_taxtotal3"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none pcashTaxT3" name="pcashTaxTHidden3" id="pcashTaxTHidden3" autocomplete="off" value="<?=$obj_row["pcash_taxtotal3"];?>" readonly>
													</div>
													<div class="col-md-4"></div>
													<div class="col-md-3">
														<label for="pcashGrand3" class="mb-1">เงินรวม</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashGrand3" autocomplete="off" value="<?=number_format($obj_row["pcash_grandtotal3"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none pcashGrand3" name="pcashGrandHidden3" id="pcashGrandHidden3" autocomplete="off" value="<?=$obj_row["pcash_grandtotal3"];?>" readonly>
													</div>
													<div class="col-md-2">
														<label for="pcashDiff3" class="mb-1">+/- ส่วนต่าง</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showDiff3" autocomplete="off" value="<?=number_format($obj_row["pcash_difference3"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashDiff3" name="pcashDiff3" id="calDiff3" autocomplete="off" value="<?=$obj_row["pcash_difference3"];?>">
													</div>
													<div class="col-md-3">
														<label for="pcashNet3" class="mb-1">ยอดชำระสุทธิ</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashNet3" autocomplete="off" value="<?=number_format($obj_row["pcash_netamount3"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none" name="pcashNetHidden3" id="pcashNetHidden3" autocomplete="off" value="<?=$obj_row["pcash_netamount3"];?>" readonly>
													</div>
												</div>
											</td>
										</tr>

										<tr class="pettybottom">
											<td>4.</td>
											<td>
												<div class="row">
													<div class="col-md-7">
														<label for="pcashDesc4" class="mb-1">รายการ</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control" name="pcashDesc4" id="pcashDesc4" maxlength="255" autocomplete="off" value="<?=$obj_row["pcash_description4"]?>">
														</div>
													</div>
													<div class="col-md-2">
														<label for="pcashSub4" class="mb-1">ก่อน VAT</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showSub4" autocomplete="off" value="<?=number_format($obj_row["pcash_subtotal4"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashSub4" name="pcashSub4" id="calSub4" autocomplete="off" value="<?=$obj_row["pcash_subtotal4"]?>">
													</div>
													<div class="col-md-1">
														<label for="pcashVatP4" class="mb-1">VAT %</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showVatP4" autocomplete="off" value="<?=number_format($obj_row["pcash_vatpercent4"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashVatP4" name="pcashVatP4" id="calVatP4" autocomplete="off" value="<?=$obj_row["pcash_vatpercent4"]?>">
													</div>
													<div class="col-md-2">
														<label for="pcashVat4" class="mb-1">VAT</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashVat4" autocomplete="off" value="<?=number_format($obj_row["pcash_vat4"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none pcashVat4" name="pcashVatHidden4" id="pcashVatHidden4" autocomplete="off" value="<?=$obj_row["pcash_vat4"];?>" readonly>
													</div>
													<div class="col-md-7"></div>
													<div class="col-md-2">
														<label for="pcashTax4" class="mb-1">ก่อน TAX</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showTax4" autocomplete="off" value="<?=number_format($obj_row["pcash_tax4"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashTax4" name="pcashTax4" id="calTax4" autocomplete="off" value="<?=$obj_row["pcash_tax4"];?>">
													</div>
													<div class="col-md-1">
														<label for="pcashTaxP4" class="mb-1">TAX %</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showTaxP4" autocomplete="off" value="<?=number_format($obj_row["pcash_taxpercent4"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashTaxP4" name="pcashTaxP4" id="calTaxP4" autocomplete="off" value="<?=$obj_row["pcash_taxpercent4"];?>">
													</div>
													<div class="col-md-2">
														<label for="pcashTaxT4" class="mb-1">TAX</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashTaxT4" autocomplete="off" value="<?=number_format($obj_row["pcash_taxtotal4"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none pcashTaxT4" name="pcashTaxTHidden4" id="pcashTaxTHidden4" autocomplete="off" value="<?=$obj_row["pcash_taxtotal4"];?>" readonly>
													</div>
													<div class="col-md-4"></div>
													<div class="col-md-3">
														<label for="pcashGrand4" class="mb-1">เงินรวม</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashGrand4" autocomplete="off" value="<?=number_format($obj_row["pcash_grandtotal4"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none pcashGrand4" name="pcashGrandHidden4" id="pcashGrandHidden4" autocomplete="off" value="<?=$obj_row["pcash_grandtotal4"];?>" readonly>
													</div>
													<div class="col-md-2">
														<label for="pcashDiff4" class="mb-1">+/- ส่วนต่าง</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showDiff4" autocomplete="off" value="<?=number_format($obj_row["pcash_difference4"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashDiff4" name="pcashDiff4" id="calDiff4" autocomplete="off" value="<?=$obj_row["pcash_difference4"];?>">
													</div>
													<div class="col-md-3">
														<label for="pcashNet4" class="mb-1">ยอดชำระสุทธิ</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashNet4" autocomplete="off" value="<?=number_format($obj_row["pcash_netamount4"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none" name="pcashNetHidden4" id="pcashNetHidden4" autocomplete="off" value="<?=$obj_row["pcash_netamount4"];?>" readonly>
													</div>
												</div>
											</td>
										</tr>

										<tr class="pettybottom">
											<td>5.</td>
											<td>
												<div class="row">
													<div class="col-md-7">
														<label for="pcashDesc5" class="mb-1">รายการ</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control" name="pcashDesc5" id="pcashDesc5" maxlength="255" autocomplete="off" value="<?=$obj_row["pcash_description5"]?>">
														</div>
													</div>
													<div class="col-md-2">
														<label for="pcashSub5" class="mb-1">ก่อน VAT</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showSub5" autocomplete="off" value="<?=number_format($obj_row["pcash_subtotal5"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashSub5" name="pcashSub5" id="calSub5" autocomplete="off" value="<?=$obj_row["pcash_subtotal5"]?>">
													</div>
													<div class="col-md-1">
														<label for="pcashVatP5" class="mb-1">VAT %</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showVatP5" autocomplete="off" value="<?=number_format($obj_row["pcash_vatpercent5"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashVatP5" name="pcashVatP5" id="calVatP5" autocomplete="off" value="<?=$obj_row["pcash_vatpercent5"]?>">
													</div>
													<div class="col-md-2">
														<label for="pcashVat5" class="mb-1">VAT</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashVat5" autocomplete="off" value="<?=number_format($obj_row["pcash_vat5"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none pcashVat5" name="pcashVatHidden5" id="pcashVatHidden5" autocomplete="off" value="<?=$obj_row["pcash_vat5"];?>" readonly>
													</div>
													<div class="col-md-7"></div>
													<div class="col-md-2">
														<label for="pcashTax5" class="mb-1">ก่อน TAX</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showTax5" autocomplete="off" value="<?=number_format($obj_row["pcash_tax5"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashTax5" name="pcashTax5" id="calTax5" autocomplete="off" value="<?=$obj_row["pcash_tax5"];?>">
													</div>
													<div class="col-md-1">
														<label for="pcashTaxP5" class="mb-1">TAX %</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showTaxP5" autocomplete="off" value="<?=number_format($obj_row["pcash_taxpercent5"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashTaxP5" name="pcashTaxP5" id="calTaxP5" autocomplete="off" value="<?=$obj_row["pcash_taxpercent5"];?>">
													</div>
													<div class="col-md-2">
														<label for="pcashTaxT5" class="mb-1">TAX</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashTaxT5" autocomplete="off" value="<?=number_format($obj_row["pcash_taxtotal5"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none pcashTaxT5" name="pcashTaxTHidden5" id="pcashTaxTHidden5" autocomplete="off" value="<?=$obj_row["pcash_taxtotal5"];?>" readonly>
													</div>
													<div class="col-md-4"></div>
													<div class="col-md-3">
														<label for="pcashGrand5" class="mb-1">เงินรวม</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashGrand5" autocomplete="off" value="<?=number_format($obj_row["pcash_grandtotal5"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none pcashGrand5" name="pcashGrandHidden5" id="pcashGrandHidden5" autocomplete="off" value="<?=$obj_row["pcash_grandtotal5"];?>" readonly>
													</div>
													<div class="col-md-2">
														<label for="pcashDiff5" class="mb-1">+/- ส่วนต่าง</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="showDiff5" autocomplete="off" value="<?=number_format($obj_row["pcash_difference5"],2);?>">
														</div>
														<input type="text" class="form-control text-right d-none pcashDiff5" name="pcashDiff5" id="calDiff5" autocomplete="off" value="<?=$obj_row["pcash_difference5"];?>">
													</div>
													<div class="col-md-3">
														<label for="pcashNet5" class="mb-1">ยอดชำระสุทธิ</label>
														<div class="input-group mb-2">
															<input type="text" class="form-control text-right" id="pcashNet5" autocomplete="off" value="<?=number_format($obj_row["pcash_netamount5"],2);?>" readonly>
														</div>
														<input type="text" class="form-control text-right d-none" name="pcashNetHidden5" id="pcashNetHidden5" autocomplete="off" value="<?=$obj_row["pcash_netamount5"];?>" readonly>
													</div>
												</div>
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr class="pettybottom">
											<td style="border-bottom: none;"></td>
											<td style="border-bottom: none;">
												<div class="row">
													<div class="col-md-4" style="margin: auto; width: 100%;">
														<h3 class="mb-0 mt-4 text-center">รวมเป็นเงิน</h3>
													</div>
													<div class="col-md-3">
														<label for="sumGrand">ยอดเงินรวม</label>
														<div class="input-group mb-3">
															<input type="text" class="form-control text-right" name="" id="sumGrand" value="0.00" style="color: #F00; font-size: 1.5rem; background-color: #FFF;" readonly>
														</div>
													</div>
													<div class="col-md-2">
														<label for="sumDiff">ยอดรวมส่วนต่าง</label>
														<div class="input-group mb-3">
															<input type="text" class="form-control text-right" name="" id="sumDiff" value="0.00" style="color: #F00; font-size: 1.5rem; background-color: #FFF;" readonly>
														</div>
													</div>
													<div class="col-md-3">
														<label for="sumNet">ยอดรวมสุทธิ</label>
														<div class="input-group mb-3">
															<input type="text" class="form-control text-right" name="" id="sumNet" value="0.00" style="color: #F00; font-size: 1.5rem; background-color: #FFF;" readonly>
															<input type="text" class="form-control text-right d-none" name="" id="sumNetHidden" value="0.00" readonly>
														</div>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td style="border-top: none;"></td>
											<td style="border-top: none;">
												<div class="row">
													<div class="col-md-9" style="margin: auto; width: 100%;">
														<h3 class="mb-0 text-right">ยอดใบสำคัญจ่ายคงเหลือ</h3>
													</div>
													<div class="col-md-3">
														<input type="text" class="form-control text-right" name="Totalbalance" id="Totalbalance" value="0.00" style="color: #F00; font-size: 1.5rem; background-color: #FFF;" readonly>
														<input type="text" class="form-control d-none" name="TotalbalanceHidden" id="TotalbalanceHidden" value="0.00">
													</div>
												</div>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>

						</div>
					</div>
				</div>

				<div class="row py-4 px-1 d-none" style="background-color: #FFFFFF">
					<div class="col-md-3 pt-1 pb-3">
						<label for="pcashuseridCreate" class="mb-1">Userid Create</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashuseridCreate" id="pcashuseridCreate" autocomplete="off" value="<?=$obj_row["pcash_userid_create"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="pcashCreateDate" class="mb-1">Create Date</label>
						<div class="input-group">
							<input type="datetime" class="form-control" name="pcashCreateDate" id="pcashCreateDate" autocomplete="off" value="<?=$obj_row["pcash_createdate"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="pcashuseridEdit" class="mb-1">Userid Edit</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashuseridEdit" id="pcashuseridEdit" autocomplete="off" value="<?=$obj_row_user["user_id"];?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="pcashEditDate" class="mb-1">Edit Date</label>
						<div class="input-group">
							<input type="date" class="form-control" name="pcashEditDate" id="pcashEditDate" autocomplete="off" value="" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="pcashstatusmgr" class="mb-1">สถานะ Manager</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashstatusmgr" id="pcashstatusmgr" autocomplete="off" value="<?=$obj_row["pcash_statusmgr"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="pcashapprmgrno" class="mb-1">เลขที่ตรวจสอบ Manager</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashapprmgrno" id="pcashapprmgrno" autocomplete="off" value="<?=$obj_row["pcash_apprmgrno"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="pcashrev" class="mb-1">ครั้งที่</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashrev" id="pcashrev" autocomplete="off" value="<?=$obj_row["pcash_rev"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="pcashyear" class="mb-1">ปี</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashyear" id="pcashyear" autocomplete="off" value="<?=$obj_row["pcash_year"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="pcashmonth" class="mb-1">เดือน</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashmonth" id="pcashmonth" autocomplete="off" value="<?=$obj_row["pcash_month"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="pcashPayeeName" class="mb-1">ชื่อผู้รับเงิน</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashPayeeName" id="pcashPayeeName" autocomplete="off" value="<?=$obj_row["pcash_payeename"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="pcashPayeeDate" class="mb-1">วันที่รับเงิน</label>
						<div class="input-group">
							<input type="date" class="form-control" name="pcashPayeeDate" id="pcashPayeeDate" autocomplete="off" value="<?=$obj_row["pcash_payeedate"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="pcashFile" class="mb-1">Petty Cash File</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashFile" id="pcashFile" autocomplete="off" value="<?=$obj_row["pcash_file"]?>" readonly>
						</div>
					</div>

					<div class="col-md-6 mr-auto pt-1 pb-3"></div>
				</div>
				
				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 text-center">
						<input type="button" class="btn btn-success px-5 py-2 mb-4" name="PettyCash_insert" id="PettyCash_insert" value="บันทึก">
					</div>
				</div>

			</form>
			
		</div>
	</section>

	<script type="text/javascript">

		$(document).ready(function(){

			$("#PettyCash_insert").click(function() { 

				if($('#pcashDate').val() == '') {
					swal({
						title: "กรุณากรอกวันที่ใบจ่ายเงินสดย่อย",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmPettyCash.pcashDate.setfocus();
					});
				} else if($('#invpayaid').val() == '') {
					swal({
						title: "กรุณาเลือกบริษัทเจ้าหนี้",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmPettyCash.searchPayable.setfocus();
					});
				} else if($('#pcashDesc1').val() == '') {
					swal({
						title: "กรุณากรอกรายละเอียด อย่างน้อย 1 รายการ",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmPettyCash.pcashDesc1.focus();
					});
				} else if($('#showSub1').val() == '') {
					swal({
						title: "กรุณากรอกรายละเอียด อย่างน้อย 1 รายการ",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmPettyCash.showSub1.focus();
					});
				} else {
					$.ajax({
						type: "POST",
						url: "r_pettycash_edit.php",
						data: $("#frmPettyCash").serialize(),
						success: function(result) {
							if(result.status == 1) {
								swal({
									title: "บันทึกข้อมูลสำเร็จ",
									text: "เลขที่ใบจ่ายเงินสดย่อย " + result.message,
									type: "success",
									closeOnClickOutside: false
								},function() {
									window.location.href = "pettycash_preview.php?pcid=" + result.id + "&pcrev=" + result.rev;
								});
								// alert(result.message);
							} else {
								alert(result.message);
							}
						}
					});
				}

			});

		});

		$(document).ready(function(){

			$(".form-control").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			document.getElementById("showSub1").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calSub1").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showSub2").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calSub2").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showSub3").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calSub3").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showSub4").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calSub4").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showSub5").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calSub5").value = this.value.replace(/,/g, "");
			}


			document.getElementById("showVatP1").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calVatP1").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showVatP2").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calVatP2").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showVatP3").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calVatP3").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showVatP4").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calVatP4").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showVatP5").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calVatP5").value = this.value.replace(/,/g, "");
			}


			document.getElementById("showTax1").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calTax1").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showTax2").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calTax2").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showTax3").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calTax3").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showTax4").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calTax4").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showTax5").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calTax5").value = this.value.replace(/,/g, "");
			}


			document.getElementById("showTaxP1").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calTaxP1").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showTaxP2").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calTaxP2").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showTaxP3").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calTaxP3").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showTaxP4").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calTaxP4").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showTaxP5").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calTaxP5").value = this.value.replace(/,/g, "");
			}


			document.getElementById("showDiff1").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calDiff1").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showDiff2").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calDiff2").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showDiff3").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calDiff3").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showDiff4").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calDiff4").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showDiff5").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calDiff5").value = this.value.replace(/,/g, "");
			}
		});

		function calculateSum() {
			//------ 1. ------//
			var pcashSub1 = parseFloat($('.pcashSub1').val());
			var pcashVatP1 = parseFloat($('.pcashVatP1').val());
			var totalsVat1 = parseFloat((pcashSub1 * pcashVatP1) / 100) || 0;
			$('#pcashVat1').val(formatMoney(totalsVat1));
			$('#pcashVatHidden1').val(totalsVat1.toFixed(2));

			var pcashTax1 = parseFloat($('.pcashTax1').val());
			var pcashTaxP1 = parseFloat($('.pcashTaxP1').val());
			var totalsTaxT1 = parseFloat((pcashTax1 * pcashTaxP1) / 100) || 0;
			$('#pcashTaxT1').val(formatMoney(totalsTaxT1));
			$('#pcashTaxTHidden1').val(totalsTaxT1.toFixed(2));

			var pcashVat1 = parseFloat($('.pcashVat1').val());
			var pcashTaxT1 = parseFloat($('.pcashTaxT1').val());
			var totalsGrand1 = parseFloat((pcashSub1 + pcashVat1) - pcashTaxT1) || 0;
			$('#pcashGrand1').val(formatMoney(totalsGrand1));
			$('#pcashGrandHidden1').val(totalsGrand1.toFixed(2));

			var pcashGrand1 = parseFloat($('.pcashGrand1').val());
			var pcashDiff1 = parseFloat($('.pcashDiff1').val());
			var totalsNet1 = parseFloat(pcashGrand1 + pcashDiff1) || 0;
			$('#pcashNet1').val(formatMoney(totalsNet1));
			$('#pcashNetHidden1').val(totalsNet1.toFixed(2));


			//------ 2. ------//
			var pcashSub2 = parseFloat($('.pcashSub2').val());
			var pcashVatP2 = parseFloat($('.pcashVatP2').val());
			var totalsVat2 = parseFloat((pcashSub2 * pcashVatP2) / 100) || 0;
			$('#pcashVat2').val(formatMoney(totalsVat2));
			$('#pcashVatHidden2').val(totalsVat2.toFixed(2));

			var pcashTax2 = parseFloat($('.pcashTax2').val());
			var pcashTaxP2 = parseFloat($('.pcashTaxP2').val());
			var totalsTaxT2 = parseFloat((pcashTax2 * pcashTaxP2) / 100) || 0;
			$('#pcashTaxT2').val(formatMoney(totalsTaxT2));
			$('#pcashTaxTHidden2').val(totalsTaxT2.toFixed(2));

			var pcashVat2 = parseFloat($('.pcashVat2').val());
			var pcashTaxT2 = parseFloat($('.pcashTaxT2').val());
			var totalsGrand2 = parseFloat((pcashSub2 + pcashVat2) - pcashTaxT2) || 0;
			$('#pcashGrand2').val(formatMoney(totalsGrand2));
			$('#pcashGrandHidden2').val(totalsGrand2.toFixed(2));

			var pcashGrand2 = parseFloat($('.pcashGrand2').val());
			var pcashDiff2 = parseFloat($('.pcashDiff2').val());
			var totalsNet2 = parseFloat(pcashGrand2 + pcashDiff2) || 0;
			$('#pcashNet2').val(formatMoney(totalsNet2));
			$('#pcashNetHidden2').val(totalsNet2.toFixed(2));


			//------ 3. ------//
			var pcashSub3 = parseFloat($('.pcashSub3').val());
			var pcashVatP3 = parseFloat($('.pcashVatP3').val());
			var totalsVat3 = parseFloat((pcashSub3 * pcashVatP3) / 100) || 0;
			$('#pcashVat3').val(formatMoney(totalsVat3));
			$('#pcashVatHidden3').val(totalsVat3.toFixed(2));

			var pcashTax3 = parseFloat($('.pcashTax3').val());
			var pcashTaxP3 = parseFloat($('.pcashTaxP3').val());
			var totalsTaxT3 = parseFloat((pcashTax3 * pcashTaxP3) / 100) || 0;
			$('#pcashTaxT3').val(formatMoney(totalsTaxT3));
			$('#pcashTaxTHidden3').val(totalsTaxT3.toFixed(2));

			var pcashVat3 = parseFloat($('.pcashVat3').val());
			var pcashTaxT3 = parseFloat($('.pcashTaxT3').val());
			var totalsGrand3 = parseFloat((pcashSub3 + pcashVat3) - pcashTaxT3) || 0;
			$('#pcashGrand3').val(formatMoney(totalsGrand3));
			$('#pcashGrandHidden3').val(totalsGrand3.toFixed(2));

			var pcashGrand3 = parseFloat($('.pcashGrand3').val());
			var pcashDiff3 = parseFloat($('.pcashDiff3').val());
			var totalsNet3 = parseFloat(pcashGrand3 + pcashDiff3) || 0;
			$('#pcashNet3').val(formatMoney(totalsNet3));
			$('#pcashNetHidden3').val(totalsNet3.toFixed(2));


			//------ 4. ------//
			var pcashSub4 = parseFloat($('.pcashSub4').val());
			var pcashVatP4 = parseFloat($('.pcashVatP4').val());
			var totalsVat4 = parseFloat((pcashSub4 * pcashVatP4) / 100) || 0;
			$('#pcashVat4').val(formatMoney(totalsVat4));
			$('#pcashVatHidden4').val(totalsVat4.toFixed(2));

			var pcashTax4 = parseFloat($('.pcashTax4').val());
			var pcashTaxP4 = parseFloat($('.pcashTaxP4').val());
			var totalsTaxT4 = parseFloat((pcashTax4 * pcashTaxP4) / 100) || 0;
			$('#pcashTaxT4').val(formatMoney(totalsTaxT4));
			$('#pcashTaxTHidden4').val(totalsTaxT4.toFixed(2));

			var pcashVat4 = parseFloat($('.pcashVat4').val());
			var pcashTaxT4 = parseFloat($('.pcashTaxT4').val());
			var totalsGrand4 = parseFloat((pcashSub4 + pcashVat4) - pcashTaxT4) || 0;
			$('#pcashGrand4').val(formatMoney(totalsGrand4));
			$('#pcashGrandHidden4').val(totalsGrand4.toFixed(2));

			var pcashGrand4 = parseFloat($('.pcashGrand4').val());
			var pcashDiff4 = parseFloat($('.pcashDiff4').val());
			var totalsNet4 = parseFloat(pcashGrand4 + pcashDiff4) || 0;
			$('#pcashNet4').val(formatMoney(totalsNet4));
			$('#pcashNetHidden4').val(totalsNet4.toFixed(2));


			//------ 5. ------//
			var pcashSub5 = parseFloat($('.pcashSub5').val());
			var pcashVatP5 = parseFloat($('.pcashVatP5').val());
			var totalsVat5 = parseFloat((pcashSub5 * pcashVatP5) / 100) || 0;
			$('#pcashVat5').val(formatMoney(totalsVat5));
			$('#pcashVatHidden5').val(totalsVat5.toFixed(2));

			var pcashTax5 = parseFloat($('.pcashTax5').val());
			var pcashTaxP5 = parseFloat($('.pcashTaxP5').val());
			var totalsTaxT5 = parseFloat((pcashTax5 * pcashTaxP5) / 100) || 0;
			$('#pcashTaxT5').val(formatMoney(totalsTaxT5));
			$('#pcashTaxTHidden5').val(totalsTaxT5.toFixed(2));

			var pcashVat5 = parseFloat($('.pcashVat5').val());
			var pcashTaxT5 = parseFloat($('.pcashTaxT5').val());
			var totalsGrand5 = parseFloat((pcashSub5 + pcashVat5) - pcashTaxT5) || 0;
			$('#pcashGrand5').val(formatMoney(totalsGrand5));
			$('#pcashGrandHidden5').val(totalsGrand5.toFixed(2));

			var pcashGrand5 = parseFloat($('.pcashGrand5').val());
			var pcashDiff5 = parseFloat($('.pcashDiff5').val());
			var totalsNet5 = parseFloat(pcashGrand5 + pcashDiff5) || 0;
			$('#pcashNet5').val(formatMoney(totalsNet5));
			$('#pcashNetHidden5').val(totalsNet5.toFixed(2));

			var sumtotalsGrand = parseFloat(totalsGrand1 + totalsGrand2 + totalsGrand3 + totalsGrand4 + totalsGrand5) || 0;
			$('#sumGrand').val(formatMoney(sumtotalsGrand));

			var sumtotalsDiff = parseFloat(pcashDiff1 + pcashDiff2 + pcashDiff3 + pcashDiff4 + pcashDiff5) || 0;
			$('#sumDiff').val(formatMoney(sumtotalsDiff));

			var sumtotalsNet = parseFloat(sumtotalsGrand + sumtotalsDiff) || 0;
			$('#sumNet').val(formatMoney(sumtotalsNet));
			$('#sumNetHidden').val(sumtotalsNet);

			var balTotal = parseFloat($('#invbalance').val());
			var sumNetHidden = parseFloat($('#sumNetHidden').val());
			var sumBal = parseFloat(balTotal - sumNetHidden) || 0;
			$('#Totalbalance').val(formatMoney(sumBal));
			$('#TotalbalanceHidden').val(sumBal.toFixed(2));
		}
		
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

		function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
			try {
				decimalCount = Math.abs(decimalCount);
				decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

				const negativeSign = amount < 0 ? "-" : "";
				let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
				let j = (i.length > 3) ? i.length % 3 : 0;
				return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");

			} catch (e) {
				console.log(e)
			}
		};
		
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>