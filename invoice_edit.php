<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$invid = $_GET["invid"];

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql = "SELECT DISTINCT * FROM invoice_tb AS i 
					INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id 
					INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
					INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
					WHERE inv_rev = (SELECT MAX(inv_rev) AS inv_rev FROM invoice_tb WHERE inv_runnumber = i.inv_runnumber) AND inv_id = '" . $invid . "'";
		$obj_rs = mysqli_query($obj_con, $str_sql);
		$obj_row = mysqli_fetch_array($obj_rs);

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<link rel="stylesheet" type="text/css" href="css/checkbox.css">
	<script type="text/javascript" src="js/calinvoice.js"></script>

	<style type="text/css">
		@media only screen and (max-width: 992px)  {
			.descbtn {
				display: none;
			}
		}
		@media (min-width: 576px) {
			.modal-dialog {
				/* max-width: 500px; */
				margin: 1.75rem auto;
			}
		}

		div#show-listComp , div#show-listPaya , div#show-listPurchase{
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

		@media (min-width: 992px) {
			.salary-tax {
				display: none;
			}
		}
		@media (min-width: 468.98px) {
			.salary-tax {
				display: block;
				padding-bottom: 0px!important;
			}
		}
		@media (min-width: 572px) {
			.salary-tax {
				display: block;
				padding-bottom: 0px!important;
			}
		}
		input[type=file] {
			padding: 0px!important;
		}

		input[type=file]::file-selector-button {
			border: none;
			border-right: 1px solid #dadada;
			padding: .55em;
			border-radius: 0em;
			transition: 1s;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmEditInvoice" id="frmEditInvoice" enctype="multipart/form-data" action="">
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มใบแจ้งหนี้
						</h3>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-lg-12 col-md-12 pt-1 pb-3 d-none">
						<label>INV ID</label>
						<div class="input-group">
							<input type="text" class="form-control" name="invid" id="invid" value="<?=$obj_row["inv_id"]?>">
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

					<div class="col-lg-12 col-md-12 pt-1 pb-3" id="showDataPaya">
						<label for="searchPayable" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทผู้ให้บริการ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div>
							<input type="text" name="searchPayable" id="searchPayable" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?=$obj_row["paya_name"];?>">

							<input type="text" class="form-control d-none" id="invpayaid" name="invpayaid" value="<?=$obj_row["paya_id"];?>">

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

					<div class="col-lg-12 col-md-12 pt-1 pb-3">
						<label for="invdesc" class="mb-1">รายการชำระ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-list"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="invdesc" id="invdesc" autocomplete="off" placeholder="กรอกรายการชำระใบแจ้งหนี้" value="<?=$obj_row["inv_description"];?>">
						</div>
					</div>

					<div class="col-lg-12 col-md-12 pt-1 pb-3">
						<label for="invdescShort" class="mb-1">Short Note รายงานจ่าย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-list"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="invdescShort" id="invdescShort" autocomplete="off" placeholder="กรอกShort Note รายงานจ่าย" value="<?=$obj_row["inv_description_short"];?>">
						</div>
					</div>

					<div class="col-lg-2 col-md-2 col-sm-12 pt-1 pb-3">
						<label class="salary-tax"></label>
						<div class="input-group-prepend">
							<div class="checkbox">
								<input type="checkbox" id="invstsINV" onclick="checkInv()" <?php if ($obj_row["inv_type"] == 1) echo "checked"; ?>>
								<label for="invstsINV"><span>ไม่มีใบแจ้งหนี้</span></label>
								<input type="text" class="form-control d-none" id="invChkINV" name="invstsINV" value="<?=$obj_row["inv_type"];?>">
							</div>
							<script type="text/javascript">
								function checkInv() {
									var checkbox = document.getElementById('invstsINV');
									var invno = document.getElementById('invno').value;
									if (checkbox.checked != true) {
										document.getElementById('invChkINV').value = "0";
										document.getElementById('invcount').value = "";
										$('#invno').prop('readonly', false);
										// $('#invno').prop('value', '');
										$('#invno').focus();
									} else {
										document.getElementById('invChkINV').value = "1";
										document.getElementById('invcount').value = "0";
										$('#invno').prop('readonly', true);
										// $('#invno').prop('value', '');
										document.getElementById('invno').value = document.getElementById('invnodis').value;
									}
								}
							</script>
						</div>
					</div>

					<?php 
						if ($obj_row_user["user_levid"] == '3' || $obj_row_user["user_levid"] == '4') {
							$displaySalary = "display: block";
						} else {
							$displaySalary = "display: none";
						} 
					?>

					<div class="col-lg-2 col-md-3 col-sm-12 pt-1 pb-3" style="<?= $displaySalary; ?>">
						<label class="salary-tax"></label>
						<div class="input-group-prepend">
							<div class="checkbox">
								<input type="checkbox" id="invsalary" onclick="checkSalary()" <?php if ($obj_row["inv_salary"] == 1) echo "checked"; ?>>
								<label for="invsalary"><span>เงินเดือน</span></label>
								<input type="text" class="form-control d-none" id="invsalaryChange" name="invsalary" value="<?=$obj_row["inv_salary"];?>">
							</div>
							<script type="text/javascript">
								function checkSalary() {
									var checkbox = document.getElementById('invsalary');
									if (checkbox.checked != true) {
										document.getElementById('invsalaryChange').value = "0";
									} else {
										document.getElementById('invsalaryChange').value = "1";
									}
								}
							</script>
						</div>
					</div>

					<div class="col-lg-2 col-md-3 col-sm-12 pt-1 pb-3">
						<label class="salary-tax"></label>
						<div class="input-group-prepend">
							<div class="checkbox">
								<input type="checkbox" id="invtaxrefund" onclick="checkTaxRefund()" <?php if ($obj_row["inv_taxrefund"] == 1) echo "checked"; ?>>
								<label for="invtaxrefund"><span>ขอคืนภาษี</span></label>
								<input type="text" class="form-control d-none" id="invtaxrefundChange" name="invtaxrefund" value="<?=$obj_row["inv_taxrefund"];?>">
							</div>
							<script type="text/javascript">
								function checkTaxRefund() {
									var checkbox = document.getElementById('invtaxrefund');
									if (checkbox.checked != true) {
										document.getElementById('invtaxrefundChange').value = "0";
									} else {
										document.getElementById('invtaxrefundChange').value = "1";
									}
								}
							</script>
						</div>
					</div>

					<div class="col-lg-2 col-md-3 col-sm-12 pt-1 pb-3">
						<label class="salary-tax"></label>
						<div class="input-group-prepend">
							<div class="checkbox">
								<input type="checkbox" id="invtypepcash" onclick="checkTypePcash()" <?php if ($obj_row["inv_typepcash"] == 1) echo "checked"; ?>>
								<label for="invtypepcash"><span>เบิกเงินสดย่อย</span></label>
								<input type="text" class="form-control d-none" id="invtypepcashChange" name="invtypepcash" value="<?=$obj_row["inv_typepcash"]?>">
							</div>
							<script type="text/javascript">
								function checkTypePcash() {
									var checkbox = document.getElementById('invtypepcash');
									if (checkbox.checked != true) {
										document.getElementById('invtypepcashChange').value = "0";
									} else {
										document.getElementById('invtypepcashChange').value = "1";
									}
								}
							</script>
						</div>
					</div>

					<?php 
						if ($obj_row["inv_type"] == 1) {
							$read = "readonly";
						} else {
							$read = "";
						}
					?>

					<div class="col-lg-12 col-md-12 mr-auto pt-1 pb-3">
						<label for="invno" class="mb-1">เลขที่ใบแจ้งหนี้</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-numbered"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="invno" id="invno" autocomplete="off" placeholder="กรอกเลขที่ใบแจ้งหนี้" value="<?=$obj_row["inv_no"]?>" <?=$read;?>>
							<input type="text" class="form-control d-none" name="invnodis" id="invnodis"value="<?=$obj_row["inv_no"];?>">
						</div>
					</div>

					<div class="col-lg-3 col-md-4 col-sm-12 pt-1 pb-3">
						<label for="invcount" class="mb-1">จำนวนใบแจ้งหนี้</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-numbered"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="invcount" id="invcount" autocomplete="off" placeholder="กรอกจำนวนใบแจ้งหนี้" value="<?=$obj_row["inv_count"];?>">
						</div>
					</div>

					<div class="col-lg-3 col-md-4 col-sm-12 pt-1 pb-3">
						<label for="invdate" class="mb-1">วันที่ใบแจ้งหนี้</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="invdate" id="invdate" autocomplete="off" value="<?=$obj_row["inv_date"];?>">
						</div>
					</div>

					<div class="col-lg-3 col-md-4 col-sm-12 pt-1 pb-3">
						<label for="invduedate" class="mb-1">วันที่ครบกำหนดชำระ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="invduedate" id="invduedate" autocomplete="off" value="<?=$obj_row["inv_duedate"];?>">
						</div>
					</div>

					<div class="col-lg-3 col-md-4 col-sm-12 pt-1 pb-3">
						<label for="SelectDep" class="mb-1">ฝ่าย</label>
						<select class="custom-select form-control" id="SelectDep" name="SelectDep" disabled style="background-color: #FFF; color: #000;">
							<option value="<?=$obj_row["inv_depid"]?>">
								<?=$obj_row["dep_name"]?>
							</option>
							<input type="text" class="form-control d-none" name="invdepid" id="invdepid" value="<?=$obj_row["inv_depid"];?>">
						</select>
					</div>

					<div class="col-lg-3 col-md-3 col-sm-12 pt-1 pb-3">
						<label for="invsubNoV" class="mb-1">จำนวนเงินก่อน VAT (ไม่มี VAT)</label>
						<div class="input-group">
							<input type="text" class="form-control text-right" id="showSubNoV" autocomplete="off" value="<?=number_format($obj_row["inv_subtotalNoVat"],2);?>">
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control d-none text-right numSubNoV" name="invsubNoV" id="calSubNoV" autocomplete="off" value="<?=$obj_row["inv_subtotalNoVat"];?>">
					</div>

					<div class="col-lg-3 col-md-3 col-sm-12 pt-1 pb-3">
						<label for="invsub" class="mb-1">จำนวนเงินก่อน VAT</label>
						<div class="input-group">
							<input type="text" class="form-control text-right" id="showSub" autocomplete="off" value="<?=number_format($obj_row["inv_subtotal"],2);?>">
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numSub" name="invsub" id="calSub" autocomplete="off" value="<?=$obj_row["inv_subtotal"];?>">
					</div>

					<div class="col-lg-2 col-md-2 col-sm-5 pt-1 pb-3">
						<label for="invperc" class="mb-1">%</label>
						<div class="input-group">
							<input type="text" class="form-control text-right" id="showPercent" autocomplete="off" value="<?=number_format($obj_row["inv_vatpercent"],2);?>">
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-sale-discount"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numPercent" name="invperc" id="calPercent" autocomplete="off" value="<?=$obj_row["inv_vatpercent"];?>">
					</div>

					<div class="col-lg-2 col-md-2 col-sm-7 pt-1 pb-3">
						<label for="invvat" class="mb-1">ภาษีมูลค่าเพิ่ม</label>
						<div class="input-group">
							<input type="text" class="form-control text-right" id="sumVatShow" autocomplete="off" value="<?=number_format($obj_row["inv_vat"],2);?>" readonly>
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numVat" name="invvat" id="sumVatHidden" autocomplete="off" value="<?=$obj_row["inv_vat"];?>">
					</div>

					<div class="col-lg-2 col-md-2 col-sm-7 pt-1 pb-3">
						<label for="invvat" class="mb-1">+ / -</label>
						<div class="input-group">
							<input type="text" class="form-control text-right" name="invVatDiff" id="showVatDiff" autocomplete="off" value="<?=$obj_row["inv_differencevat"];?>">
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numVatDiff" name="invvatDiff" id="calVatDiff" autocomplete="off" value="<?=$obj_row["inv_differencevat"];?>">
					</div>

					<div class="col-lg-3 col-md-3 col-sm-12 pt-1 pb-3"></div>

					<div class="col-lg-3 col-md-3 col-sm-12 pt-1">
						<label for="invtaxcer" class="mb-1">จำนวนเงินหัก ณ ที่จ่าย</label>
					</div>

					<div class="col-lg-2 col-md-2 col-sm-12 pt-1">
						<label for="invtaxcer" class="mb-1">%</label>
					</div>

					<div class="col-lg-2 col-md-2 col-sm-12 pt-1">
						<label for="invtaxcer" class="mb-1">หัก ณ ที่จ่าย</label>
					</div>

					<div class="col-lg-2 col-md-2 col-sm-12 pt-1">
						<label for="invtaxcer" class="mb-1">+ / -</label>
					</div>


					<!-- Tax 1 -->
					<div class="col-lg-3 col-md-3 col-sm-12 pt-1 pb-3"></div>

					<div class="col-lg-3 col-md-3 col-sm-7 col-xs-6 col-7 pt-1 pb-2">
						<div class="input-group">
							<input type="text" class="form-control text-right" id="showTax1" autocomplete="off" value="<?=number_format($obj_row["inv_tax1"],2);?>">
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numTax1" name="invtax1" id="calTax1" autocomplete="off" value="<?=$obj_row["inv_tax1"];?>">
					</div>

					<div class="col-lg-2 col-md-2 col-sm-5 col-xs-6 col-5 pt-1 pb-2">
						<div class="input-group">
							<input type="text" class="form-control text-right" id="showTaxPercent1" autocomplete="off" value="<?=number_format($obj_row["inv_taxpercent1"],2);?>">
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-sale-discount"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numTaxPercent1" name="invtaxpercent1" id="calTaxPercent1" autocomplete="off" value="<?=$obj_row["inv_taxpercent1"];?>">
					</div>

					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 mr-auto pt-1 pb-3">
						<div class="input-group">
							<input type="text" class="form-control text-right" id="sumTaxTotal1" autocomplete="off" value="<?=number_format($obj_row["inv_taxtotal1"],2);?>" readonly>
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numTaxTotal1" name="invtaxtotal1" id="sumTaxTotalHidden1" autocomplete="off" value="<?=$obj_row["inv_taxtotal1"];?>">
					</div>

					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 mr-auto pt-1 pb-3">
						<div class="input-group">
							<input type="text" class="form-control text-right" id="showTaxDiff1" autocomplete="off" value="<?=$obj_row["inv_differencetax1"];?>">
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numTaxDiff1" name="invtaxDiff1" id="calTaxDiff1" autocomplete="off" value="<?=$obj_row["inv_differencetax1"];?>" readonly>
					</div>
					<!-- END Tax 1 -->

					<!-- Tax 2 -->
					<div class="col-lg-3 col-md-3 col-sm-12 pt-1 pb-3"></div>

					<div class="col-lg-3 col-md-3 col-sm-7 col-xs-6 col-7 pt-1 pb-2">
						<div class="input-group">
							<input type="text" class="form-control text-right" id="showTax2" autocomplete="off" value="<?=number_format($obj_row["inv_tax2"],2);?>">
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numTax2" name="invtax2" id="calTax2" autocomplete="off" value="<?=$obj_row["inv_tax2"];?>">
					</div>

					<div class="col-lg-2 col-md-2 col-sm-5 col-xs-6 col-5 pt-1 pb-2">
						<div class="input-group">
							<input type="text" class="form-control text-right" id="showTaxPercent2" autocomplete="off" value="<?=number_format($obj_row["inv_taxpercent2"],2);?>">
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-sale-discount"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numTaxPercent2" name="invtaxpercent2" id="calTaxPercent2" autocomplete="off" value="<?=$obj_row["inv_taxpercent2"];?>">
					</div>

					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 mr-auto pt-1 pb-3">
						<div class="input-group">
							<input type="text" class="form-control text-right" id="sumTaxTotal2" autocomplete="off" value="<?=number_format($obj_row["inv_taxtotal2"],2);?>" readonly>
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numTaxTotal2" name="invtaxtotal2" id="sumTaxTotalHidden2" autocomplete="off" value="<?=$obj_row["inv_taxtotal2"];?>">
					</div>

					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 mr-auto pt-1 pb-3">
						<div class="input-group">
							<input type="text" class="form-control text-right" id="showTaxDiff2" autocomplete="off" value="<?=$obj_row["inv_differencetax2"];?>">
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numTaxDiff2" name="invtaxDiff2" id="calTaxDiff2" autocomplete="off" value="<?=$obj_row["inv_differencetax2"];?>" readonly>
					</div>
					<!-- END Tax 2 -->

					<!-- Tax 3 -->
					<div class="col-lg-3 col-md-3 col-sm-12 pt-1 pb-3"></div>

					<div class="col-lg-3 col-md-3 col-sm-7 col-xs-6 col-7 pt-1 pb-2">
						<div class="input-group">
							<input type="text" class="form-control text-right" id="showTax3" autocomplete="off" value="<?=number_format($obj_row["inv_tax3"],2);?>">
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numTax3" name="invtax3" id="calTax3" autocomplete="off" value="<?=$obj_row["inv_tax3"];?>">
					</div>

					<div class="col-lg-2 col-md-2 col-sm-5 col-xs-6 col-5 pt-1 pb-2">
						<div class="input-group">
							<input type="text" class="form-control text-right" id="showTaxPercent3" autocomplete="off" value="<?=number_format($obj_row["inv_taxpercent3"],2);?>">
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-sale-discount"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numTaxPercent3" name="invtaxpercent3" id="calTaxPercent3" autocomplete="off" value="<?=$obj_row["inv_taxpercent3"];?>">
					</div>

					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 mr-auto pt-1 pb-3">
						<div class="input-group">
							<input type="text" class="form-control text-right" id="sumTaxTotal3" autocomplete="off" value="<?=number_format($obj_row["inv_taxtotal3"],2);?>" readonly>
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numTaxTotal3" name="invtaxtotal3" id="sumTaxTotalHidden3" autocomplete="off" value="<?=$obj_row["inv_taxtotal3"];?>">
					</div>

					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 mr-auto pt-1 pb-3">
						<div class="input-group">
							<input type="text" class="form-control text-right" id="showTaxDiff3" autocomplete="off" value="<?=$obj_row["inv_differencetax3"];?>">
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numTaxDiff3" name="invtaxDiff3" id="calTaxDiff3" autocomplete="off" value="<?=$obj_row["inv_differencetax3"];?>" readonly>
					</div>
					<!-- END Tax 3 -->

					<div class="col-lg-4 col-md-4 col-sm-12 pt-1 pb-3"></div>

					<div class="col-lg-3 col-md-3 col-sm-7 col-xs-6 col-7 pt-1 pb-2">
						<label for="invgrand" class="mb-1">จำนวนเงินทั้งหมด</label>
						<div class="input-group">
							<input type="text" class="form-control text-right" id="sumGrand" autocomplete="off" value="<?=number_format($obj_row["inv_grandtotal"],2);?>" readonly>
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numGrand" name="invgrand" id="sumGrandHidden" autocomplete="off" value="<?=$obj_row["inv_grandtotal"];?>">
					</div>

					<div class="col-lg-2 col-md-2 col-sm-5 col-xs-6 col-5 pt-1 pb-2">
						<label for="invdiff" class="mb-1">+/-</label>
						<div class="input-group">
							<input type="text" class="form-control text-right" id="showDiff" autocomplete="off" value="<?=number_format($obj_row["inv_difference"],2);?>">
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numDiff" name="invdiff" id="calDiff" autocomplete="off" value="<?=$obj_row["inv_difference"];?>">
					</div>

					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 mr-auto pt-1 pb-3">
						<label for="invnet" class="mb-1">ยอดชำระสุทธิ</label>
						<div class="input-group">
							<input type="text" class="form-control text-right" id="sumNet" autocomplete="off" value="<?=number_format($obj_row["inv_netamount"],2);?>" readonly>
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
						<input type="text" class="form-control text-right d-none numNet" name="invnet" id="sumNetHidden" autocomplete="off" value="<?=$obj_row["inv_netamount"];?>">
					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mr-auto pt-1 pb-3">
						<div class="row">
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-6">
										<label style="padding: 8px 0px; font-size: 1.15rem; margin-bottom: 0px;">อัพโหลดไฟล์ใบแจ้งหนี้</label>
									</div>
									<div class="col-md-6 text-right px-0">
										<!-- <button type="button" class="btn btn-success add_field_button">
											<i class="icofont-plus"></i> เพิ่ม
										</button> -->
									</div>
								</div>
							</div>
						</div>
						<?php
							$str_sql_file = "SELECT * FROM invoice_file_tb WHERE invF_invid = '" . $invid . "'";
							$obj_rs_file = mysqli_query($obj_con, $str_sql_file);
							$countFile = mysqli_num_rows($obj_rs_file);
							$i = 1;
							while ($obj_row_file = mysqli_fetch_array($obj_rs_file)) {
								$type = substr($obj_row_file["invF_filename"],-4);
						?>
						<div class="fields_wrap" style="width: 50%">
							<div class="input-group my-1">
								
								<input type="text" class="form-control" name="filesname<?=$obj_row_file["invF_id"]?>" value="<?=$obj_row_file["invF_filename"];?>" readonly>
								<div class="input-group-append">
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ViewFile<?=$obj_row_file["invF_id"]?>" data-backdrop="static" data-keyboard="false" title="ดู / View">
										<i class="icofont-eye-alt"></i>
									</button>

									<div id="ViewFile<?=$obj_row_file["invF_id"]?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ViewFileLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg modal-dialog-centered">
											<div class="modal-content" style="background-color: #f5f5f5;">
													<div class="modal-header">
														<h3 class="modal-title py-2">
															ไฟล์เอกสารใบแจ้งหนี้
														</h3>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>

													<div class="modal-body">
														<?php if ($type == '.pdf') { ?>
															<embed src="<?=$obj_row_file["invF_filename"];?>" width="100%" height="600" type="application/pdf">
														<?php } else { ?>
															<img src="<?=$obj_row_file["invF_filename"];?>" width="100%">
														<?php } ?>
													</div>
											</div>
										</div>
									</div>

									<button type="button" class="btn btn-danger delete_file" id="<?=$obj_row_file["invF_id"];?>" title="ลบ / Delete" name="<?=$obj_row_file["invF_filename"];?>">
										<i class="icofont-ui-delete"></i>
									</button>
								</div>
							
							</div>
						</div>

						<?php } ?>

						<div class="input_fields_wrap">
							<div class="input-group my-1" id="fileINV1" style="width: 50%">
								<input type="file" class="form-control" name="files[]">
								<div class="input-group-append">
									<!-- <button type="button" class="btn btn-danger remove_field" id="1">
										<i class="icofont-close"></i>
									</button> -->
									<button type="button" class="btn btn-success add_field_button">
										<i class="icofont-plus"></i> เพิ่ม
									</button>
								</div>
							</div>
						</div>

						<input type="text" class="form-control d-none" name="countPart" id="countPart" value="1">

						<script type="text/javascript">
							$(document).ready(function() {

								var max_fields      = 10;
								var wrapper   		= $(".input_fields_wrap");
								var add_button      = $(".add_field_button");
								
								var x = 1;
								// $(add_button).click(function(e) {
								// 	e.preventDefault();
								// 	if(x < max_fields) {
								// 		x++;

								// 		document.getElementById('countPart').value = x;

								// 		$(wrapper).append('<div class="input-group my-1" id="fileINV'+x+'" style="width: 50%"><input type="file" class="form-control" name="files[]"><div class="input-group-append"><button type="button" class="btn btn-danger remove_field" id="'+x+'"><i class="icofont-close"></i></button></div></div>');
								// 	}
								// });

								$(add_button).click(function(){
									if(x < max_fields) {
										x++;
										document.getElementById('countPart').value = x;

										$(wrapper).append('<div class="input-group my-1" id="fileINV'+x+'" style="width: 50%"><input type="file" class="form-control" name="files[]"><div class="input-group-append"><button type="button" class="btn btn-danger remove_field" id="'+x+'"><i class="icofont-close"></i></button></div></div>');
									}

								});

								// $(document).on('click', '.remove_field', function () {
								// 	var button_id = $(this).attr("id");
								// 	$('.fileINV'+ button_id +'').remove();
								// 	// $(this).closest('.input-group').remove();
									
								// 	document.getElementById('countPart').value = x;
								// });

								$(document).on('click', '.remove_field', function(){
									var button_id = $(this).attr("id");
									$('#fileINV' + button_id).remove();
									// var n = $( '.part > tr' ).length;
									// $( 'span.part' ).text( "There are " + n + " divs." + "Click to add more.");
									document.getElementById('countPart').value = document.getElementById('countPart').value - 1;
								});
							});
						</script>
					</div>
				</div>

				<div class="row py-4 px-1 d-none" style="background-color: #FFFFFF">
					<div class="col-md-3 pt-1 pb-3">
						<label for="invuseridCreate" class="mb-1">User ID Create</label>
						<div class="input-group">
							<input type="text" class="form-control" name="invuseridCreate" id="invuseridCreate" autocomplete="off" value="<?=$obj_row["inv_userid_create"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="invCreateDate" class="mb-1">Date Create</label>
						<div class="input-group">
							<input type="datetime" class="form-control" name="invCreateDate" id="invCreateDate" autocomplete="off" value="<?=$obj_row["inv_createdate"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="invuseridEdit" class="mb-1">User ID Edit</label>
						<div class="input-group">
							<input type="text" class="form-control" name="invuseridEdit" id="invuseridEdit" autocomplete="off" value="<?=$obj_row_user["user_id"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="invEditDate" class="mb-1">Date Edit</label>
						<div class="input-group">
							<input type="datetime" class="form-control" name="invEditDate" id="invEditDate" autocomplete="off" value="" readonly>
						</div>
					</div>

					<!-- Manager -->
					<div class="col-md-3 pt-1 pb-3">
						<label for="invstatusMgr" class="mb-1">สถานะ Manager</label>
						<div class="input-group">
							<input type="text" class="form-control" name="invstatusMgr" id="invstatusMgr" autocomplete="off" value="<?=$obj_row["inv_statusMgr"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="invapprMgrno" class="mb-1">เลขที่ตรวจสอบ Manager</label>
						<div class="input-group">
							<input type="text" class="form-control" name="invapprMgrno" id="invapprMgrno" autocomplete="off" value="<?=$obj_row["inv_apprMgrno"]?>" readonly>
						</div>
					</div>
					<!-- Manager -->

					<!-- CEO -->
					<div class="col-md-3 pt-1 pb-3">
						<label for="invstatusCEO" class="mb-1">สถานะ CEO</label>
						<div class="input-group">
							<input type="text" class="form-control" name="invstatusCEO" id="invstatusCEO" autocomplete="off" value="<?=$obj_row["inv_statusCEO"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="invapprCEOno" class="mb-1">เลขที่อนุมัติ CEO</label>
						<div class="input-group">
							<input type="text" class="form-control" name="invapprCEOno" id="invapprCEOno" autocomplete="off" value="<?=$obj_row["inv_apprCEOno"]?>" readonly>
						</div>
					</div>
					<!-- CEO -->

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="invrev" class="mb-1">ครั้งที่</label>
						<div class="input-group">
							<input type="text" class="form-control" name="invrev" id="invrev" autocomplete="off" value="<?=$obj_row["inv_rev"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="invyear" class="mb-1">ปี</label>
						<div class="input-group">
							<input type="text" class="form-control" name="invyear" id="invyear" autocomplete="off" value="<?=$obj_row["inv_year"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="invmonth" class="mb-1">เดือน</label>
						<div class="input-group">
							<input type="text" class="form-control" name="invmonth" id="invmonth" autocomplete="off" value="<?=$obj_row["inv_month"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="invrunnumber" class="mb-1">Run Number</label>
						<div class="input-group">
							<input type="text" class="form-control" name="invrunnumber" id="invrunnumber" autocomplete="off" value="<?=$obj_row["inv_runnumber"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="invstatusPaym" class="mb-1">Status Payment</label>
						<div class="input-group">
							<input type="text" class="form-control" name="invstatusPaym" id="invstatusPaym" autocomplete="off" value="<?=$obj_row["inv_statusPaym"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="invNostatusPaym" class="mb-1">No Status Payment</label>
						<div class="input-group">
							<input type="text" class="form-control" name="invNostatusPaym" id="invNostatusPaym" autocomplete="off" value="<?=$obj_row["inv_NostatusPaym"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="invNostatusPaym" class="mb-1">Balance</label>
						<div class="input-group">
							<input type="text" class="form-control" name="invbalance" id="invbalance" autocomplete="off" value="<?=$obj_row["inv_balancetotal"]?>" readonly>
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

	<?php include 'invoice_add_payable.php'; ?>

	<script type="text/javascript">

		$(document).ready(function() {

			//--- START ADD INVOICE ---//
			$("#btnInsert").click(function() {
				var formData = new FormData(this.form);
				if($('#invcompid').val() == '') {
					swal({
						title: "กรุณากรอกชื่อ-นามสกุล / ชื่อบริษัทในเครือ",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmEditInvoice.searchCompany.focus();
					});
				} else if($('#invpayaid').val() == '') {
					swal({
						title: "กรุณากรอกชื่อ-นามสกุล / ชื่อบริษัทเจ้าหนี้",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmEditInvoice.searchPayable.focus();
					});
				} else if($('#invdesc').val() == '') {
					swal({
						title: "กรุณากรอกรายละเอียดใบแจ้งหนี้",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmEditInvoice.invdesc.focus();
					});
				} else if($('#invdescShort').val() == '') {
					swal({
						title: "กรุณากรอก Short Note รายงานจ่าย",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmEditInvoice.invdescShort.focus();
					});
				} else if($('#invdate').val() == '') {
					swal({
						title: "กรุณาเลือกวันที่",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmEditInvoice.invdate.focus();
					});
				} else if($('#invduedate').val() == '') {
					swal({
						title: "กรุณาเลือกวันที่ครบชำระ",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmEditInvoice.invduedate.focus();
					});
				} else if($('#invdepid').val() == '') {
					swal({
						title: "กรุณาเลือกฝ่าย",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmEditInvoice.SelectDep.focus();
					});
				} else {
					if($('#invChkINV').val() == '0') {
						if($('#invno').val() == '') {
							swal({
								title: "กรุณากรอกเลขที่ใบแจ้งหนี้",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "warning",
								closeOnClickOutside: false
							}).then(function() {
								frmEditInvoice.invno.focus();
							});
						} else if($('#invcount').val() == '') {
							swal({
								title: "กรุณาจำนวนใบแจ้งหนี้",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "warning",
								closeOnClickOutside: false
							}).then(function() {
								frmEditInvoice.invcount.focus();
							});
						} else {
							window.swal({
								title: "กำลังตรวจสอบข้อมูล...",
								text: "กรุณารอสักครู่",
								imageUrl: "image/loading.gif",
								showConfirmButton: false,
								allowOutsideClick: false
							});
							setTimeout(() => {
								$.ajax({
									type: "POST",
									url: "r_invoice_edit.php",
									// data: $("#frmEditInvoice").serialize(),
									data: formData,
									dataType: 'json',
									contentType: false,
									cache: false,
									processData:false,
									success: function(result) {
										if(result.status == 1) {
											window.swal({
												title: "บันทึกข้อมูลสำเร็จ",
												text: "เลขที่ใบแจ้งหนี้ : " + result.message + "\n\nRun Number : " + result.RunNumber,
												type: "success",
												closeOnClickOutside: false
											},function() {
												window.location.href = "invoice.php?cid=" + result.compid + "&dep=" + result.dep;
											});
										} else {
											alert(result.message);
										}
									}
								});
							}, 2000);
							
						}
					} else {
						window.swal({
							title: "กำลังตรวจสอบข้อมูล...",
							text: "กรุณารอสักครู่",
							imageUrl: "image/loading.gif",
							showConfirmButton: false,
							allowOutsideClick: false
						});
						setTimeout(() => {
							$.ajax({
								type: "POST",
								url: "r_invoice_edit.php",
								// data: $("#frmEditInvoice").serialize(),
								data: formData,
								dataType: 'json',
								contentType: false,
								cache: false,
								processData:false,
								success: function(result) {
									if(result.status == 1) {
										window.swal({
											title: "บันทึกข้อมูลสำเร็จ",
											text: "เลขที่ใบแจ้งหนี้ : " + result.message + "\n\nRun Number : " + result.RunNumber,
											type: "success",
											closeOnClickOutside: false
										},function() {
											window.location.href = "invoice.php?cid=" + result.compid + "&dep=" + result.dep;
										});
									} else {
										alert(result.message);
									}
								}
							});
						}, 2000);
						// $.ajax({
						// 	type: "POST",
						// 	url: "r_invoice_edit.php",
						// 	// data: $("#frmEditInvoice").serialize(),
						// 	data: formData,
						// 	dataType: 'json',
						// 	contentType: false,
						// 	cache: false,
						// 	processData:false,
						// 	success: function(result) {
						// 		if(result.status == 1) {
						// 			swal({
						// 				title: "บันทึกข้อมูลสำเร็จ",
						// 				text: "เลขที่ใบแจ้งหนี้ : " + result.message + "\n\nRun Number : " + result.RunNumber,
						// 				type: "success",
						// 				closeOnClickOutside: false
						// 			},function() {
						// 				window.location.href = "invoice.php?cid=" + result.compid + "&dep=" + result.dep;
						// 			});
						// 		} else {
						// 			alert(result.message);
						// 		}
						// 	}
						// });
					}
				}
			});
			//--- END ADD INVOICE ---//


			//--- START DELETE INVOICE ---//
			$(document).on('click', '.delete_file', function(){
				event.preventDefault();
				var del_id = $(this).attr("id");
				var element = this;
				swal({
					title: "ลบไฟล์นี้หรือไม่?",
					text: "เมื่อไฟล์นี้ถูกลบ คุณไม่สามารถกู้คืนได้!",
					type: "warning",
					showCancelButton: true,
					confirmButtonText: "ตกลง",
					cancelButtonText: "ยกเลิก",
					confirmButtonClass: 'btn btn-danger',
					cancelButtonClass: 'btn btn-secondary',
					closeOnConfirm: false,
					closeOnCancel: false
				},
				function(isConfirm) {
					if (isConfirm) {
						$.ajax({
							url: "r_invoice_delete_file.php",
							type: "POST",
							data: {del_id:del_id},
							success: function () {
								setTimeout(function () {
									swal({
										title: "ลบข้อมูลสำเร็จ!",
										text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
										type: 'success'
									});
									$(element).closest('.fields_wrap').fadeOut(800,function(){
										$(this).remove();
										
									});
								}, 1000);
							}
						});
					} else {
						swal({
							title: "ยกเลิก",
							text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
							type: 'error'
						});
					}
				});
			});
			//--- END DELETE INVOICE ---//
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

		document.getElementById("showSubNoV").onblur = function (){
			this.value = parseFloat(this.value.replace(/,/g, ""))
			.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			document.getElementById("calSubNoV").value = this.value.replace(/,/g, "");
		}

		document.getElementById("showSub").onblur = function (){
			this.value = parseFloat(this.value.replace(/,/g, ""))
			.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			document.getElementById("calSub").value = this.value.replace(/,/g, "");
		}

		document.getElementById("showPercent").onblur = function (){
			this.value = parseFloat(this.value.replace(/,/g, ""))
			.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			document.getElementById("calPercent").value = this.value.replace(/,/g, "");
		}

		document.getElementById("showVatDiff").onblur = function (){
			this.value = parseFloat(this.value.replace(/,/g, ""))
			//.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			document.getElementById("calVatDiff").value = this.value.replace(/,/g, "");
		}

		document.getElementById("showTax1").onblur = function (){
			this.value = parseFloat(this.value.replace(/,/g, ""))
			.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			document.getElementById("calTax1").value = this.value.replace(/,/g, "");
		}

		document.getElementById("showTaxPercent1").onblur = function (){
			this.value = parseFloat(this.value.replace(/,/g, ""))
			.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			document.getElementById("calTaxPercent1").value = this.value.replace(/,/g, "");
		}

		document.getElementById("showTaxDiff1").onblur = function (){
			this.value = parseFloat(this.value.replace(/,/g, ""))
			//.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			document.getElementById("calTaxDiff1").value = this.value.replace(/,/g, "");
		}

		document.getElementById("showTax2").onblur = function (){
			this.value = parseFloat(this.value.replace(/,/g, ""))
			.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			document.getElementById("calTax2").value = this.value.replace(/,/g, "");
		}

		document.getElementById("showTaxPercent2").onblur = function (){
			this.value = parseFloat(this.value.replace(/,/g, ""))
			.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			document.getElementById("calTaxPercent2").value = this.value.replace(/,/g, "");
		}

		document.getElementById("showTaxDiff2").onblur = function (){
			this.value = parseFloat(this.value.replace(/,/g, ""))
			//.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			document.getElementById("calTaxDiff2").value = this.value.replace(/,/g, "");
		}

		document.getElementById("showTax3").onblur = function (){
			this.value = parseFloat(this.value.replace(/,/g, ""))
			.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			document.getElementById("calTax3").value = this.value.replace(/,/g, "");
		}

		document.getElementById("showTaxPercent3").onblur = function (){
			this.value = parseFloat(this.value.replace(/,/g, ""))
			.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			document.getElementById("calTaxPercent3").value = this.value.replace(/,/g, "");
		}

		document.getElementById("showTaxDiff3").onblur = function (){
			this.value = parseFloat(this.value.replace(/,/g, ""))
			//.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			document.getElementById("calTaxDiff3").value = this.value.replace(/,/g, "");
		}

		document.getElementById("showDiff").onblur = function (){
			this.value = parseFloat(this.value.replace(/,/g, ""))
			//.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			document.getElementById("calDiff").value = this.value.replace(/,/g, "");
		}
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>