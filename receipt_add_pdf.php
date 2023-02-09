<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		// $month = $_GET["m"];
		$projid = $_GET["projid"];
		$irID = $_GET["irID"];
		$Reid = $_GET["Reid"];

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		if($irID == '') {
			$str_sql = "SELECT * FROM receipt_tb AS r 
						INNER JOIN company_tb AS c ON r.re_compid = c.comp_id 
						INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
						INNER JOIN department_tb AS d ON r.re_depid = d.dep_id 
						WHERE re_id = '". $Reid ."'";
		} else {
			$str_sql = "SELECT * FROM receipt_tb AS r 
						INNER JOIN company_tb AS c ON r.re_compid = c.comp_id 
						INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
						INNER JOIN invoice_rcpt_tb AS i ON r.re_id = i.invrcpt_reid 
						INNER JOIN department_tb AS d ON r.re_depid = d.dep_id 
						WHERE re_id = '". $Reid ."'";
		}
		$obj_rs = mysqli_query($obj_con, $str_sql);
		$obj_row = mysqli_fetch_array($obj_rs);

		if($obj_row["re_bookno"] == '') {
			$receiptNo = $obj_row["re_no"];
		} else {
			$receiptNo = $obj_row["re_bookno"] . "/" . $obj_row["re_no"];
		}

		// if($obj_row["re_refinvrcpt"] == 0) {

		// 	$desc1 = $obj_row["invrcpt_description1"];
		// 	$desc2 = $obj_row["invrcpt_description2"];
		// 	$desc3 = $obj_row["invrcpt_description3"];
		// 	$desc4 = $obj_row["invrcpt_description4"];
		// 	$desc5 = $obj_row["invrcpt_description5"];
		// 	$desc6 = $obj_row["invrcpt_description6"];
		// 	$desc7 = $obj_row["invrcpt_description7"];
		// 	$desc8 = $obj_row["invrcpt_description8"];
		
		// if($obj_row["re_refinvrcpt"] == '0') {
		// 	if($obj_row["invrcpt_book"] != '') {
		// 		$invoiceNo = $obj_row["invrcpt_book"] . "/" . $obj_row["invrcpt_no"];
		// 	} else {
		// 		$invoiceNo = $obj_row["invrcpt_no"];
		// 	}
		// } else {
		// 	if($obj_row["invrcpt_book"] != '') {
		// 		$invoiceNo = $obj_row["invrcpt_book"] . "/" . $obj_row["invrcpt_no"];
		// 	} else {
		// 		$invoiceNo = $obj_row["invrcpt_no"];
		// 	}
		// }

		// } else {

			$desc1 = $obj_row["re_description1"];
			$desc2 = $obj_row["re_description2"];
			$desc3 = $obj_row["re_description3"];
			$desc4 = $obj_row["re_description4"];
			$desc5 = $obj_row["re_description5"];
			$desc6 = $obj_row["re_description6"];
			$desc7 = $obj_row["re_description7"];
			$desc8 = $obj_row["re_description8"];

			// $invoiceNo = '';
			
		// }

		$str_sql_inv = "SELECT * FROM invoice_rcpt_tb WHERE invrcpt_id = '". $irID ."'";
		$obj_rs_inv = mysqli_query($obj_con, $str_sql_inv);
		$obj_row_inv = mysqli_fetch_array($obj_rs_inv);

		if($obj_row["invrcpt_book"] != '') {
			$invoiceNo = $obj_row_inv["invrcpt_book"] . "/" . $obj_row_inv["invrcpt_no"];
		} else {
			$invoiceNo = $obj_row_inv["invrcpt_no"];
		}

		// echo " Test " . $invoiceNo;

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<link rel="stylesheet" type="text/css" href="css/checkbox.css">
	<!-- <script type="text/javascript" src="js/calinvoiceRevenue.js"></script> -->

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

			<form method="POST" name="frmReceipt" id="frmReceipt" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-edit"></i>
							&nbsp;&nbsp;สร้างไฟล์ PDF ใบเสร็จรับเงิน
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="useridCreate" id="useridCreate" value="<?=$obj_row["re_userid_create"];?>">
						<input type="datetime" class="form-control" name="CreateDate" id="CreateDate" value="<?=$obj_row["re_createdate"];?>">
						<input type="text" class="form-control" name="useridEdit" id="useridEdit" value="<?=$obj_row_user["user_id"];?>">
						<input type="date" class="form-control" name="EditDate" id="EditDate" value="">
						<input type="text" class="form-control" name="projid" id="projid" value="<?=$projid;?>">
						<input type="text" class="form-control" name="Rebookno" id="Rebookno" value="<?=$obj_row["re_bookno"];?>">
						<input type="text" class="form-control" name="Reno" id="Reno" value="<?=$obj_row["re_no"];?>">
						<input type="text" class="form-control" name="Reid" id="Reid" value="<?=$Reid;?>">
						<input type="text" class="form-control" name="Reyear" id="Reyear" value="<?=$obj_row["re_year"];?>">
						<input type="text" class="form-control" name="Remonth" id="Remonth" value="<?=$obj_row["re_month"];?>">
						<input type="text" class="form-control" name="Refile" id="Refile" value="<?=$obj_row["re_file"];?>">
						<input type="text" class="form-control" name="Restsid" id="Restsid" value="<?=$obj_row["re_stsid"];?>">
						<input type="text" class="form-control" name="page_status" id="page_status" value="add_pdf">

						<input type="text" class="form-control" name="Reref" id="Reref" value="<?=$obj_row["re_refinvrcpt"];?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 py-3">
						<h2>ใบเสร็จรับเงินฝ่าย&nbsp;&nbsp;<?=$obj_row["dep_name"];?></h2>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="Reno" class="mb-1">เลขที่ใบเสร็จรับเงิน</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-numbered"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="Receiptno" id="Receiptno" autocomplete="off" placeholder="เว้นว่างไว้เพื่อสร้างอัตโนมัติ" value="<?=$receiptNo;?>" tabindex="1" readonly>
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
							<input type="date" class="form-control" name="Redate" id="Redate" autocomplete="off" value="<?=$obj_row["re_date"];?>" tabindex="2" readonly>
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
							<select class="custom-select form-control" name="SelMonth" id="SelMonth" tabindex="3" disabled>
								<option value="">กรุณาเลือกเดือน...</option>
								<option value="01" <?php if($obj_row["re_month"] == '01') echo "selected"; ?>>มกราคม</option>
								<option value="02" <?php if($obj_row["re_month"] == '02') echo "selected"; ?>>กุมภาพันธ์</option>
								<option value="03" <?php if($obj_row["re_month"] == '03') echo "selected"; ?>>มีนาคม</option>
								<option value="04" <?php if($obj_row["re_month"] == '04') echo "selected"; ?>>เมษายน</option>
								<option value="05" <?php if($obj_row["re_month"] == '05') echo "selected"; ?>>พฤษภาคม</option>
								<option value="06" <?php if($obj_row["re_month"] == '06') echo "selected"; ?>>มิถุนายน</option>
								<option value="07" <?php if($obj_row["re_month"] == '07') echo "selected"; ?>>กรกฎาคม</option>
								<option value="08" <?php if($obj_row["re_month"] == '08') echo "selected"; ?>>สิงหาคม</option>
								<option value="09" <?php if($obj_row["re_month"] == '09') echo "selected"; ?>>กันยายน</option>
								<option value="10" <?php if($obj_row["re_month"] == '10') echo "selected"; ?>>ตุลาคม</option>
								<option value="11" <?php if($obj_row["re_month"] == '11') echo "selected"; ?>>พฤศจิกายน</option>
								<option value="12" <?php if($obj_row["re_month"] == '12') echo "selected"; ?>>ธันวาคม</option>
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
							<input type="text" class="form-control" name="depname" id="depname" value="<?=$obj_row["dep_name"];?>" readonly>
							<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$dep;?>">
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="datePay" class="mb-1">วันที่จ่ายเงิน</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="datePay" id="datePay" value="<?=$obj_row["re_datepay"];?>">
						</div>
					</div>

					<div class="col-md-9 pt-1 pb-3"></div>

					<div class="col-md-12 pt-1 pb-3 d-none">
						<label for="searchCompany" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทในเครือ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-building"></i>
								</i>
							</div>
							<input type="text" name="searchCompany" id="searchCompany" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?=$obj_row["comp_name"];?>">

							<input type="text" class="form-control d-none" id="compid" name="compid" value="<?=$obj_row["comp_id"];?>">
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
							<input type="text" name="searchCustomer" id="searchCustomer" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?=$obj_row["cust_name"];?>" tabindex="4" readonly>

							<input type="text" class="form-control d-none" id="custid" name="custid" value="<?=$obj_row["re_custid"];?>">

							<!-- <div class="input-group-append">
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
							</div> -->
						</div>
						<!-- <div class="list-group" id="show-listCust"></div> -->
					</div>

					<div class="col-md-12 pt-1 pb-3">
						<label for="outputTax" class="mb-1">รายละเอียดภาษีขาย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-file-document"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="outputTax" id="outputTax" autocomplete="off" placeholder="กรุณากรอกรายละเอียดภาษีขาย" value="<?=$obj_row["re_outputtax"];?>" tabindex="5" readonly>
						</div>
					</div>

					<?php if ($obj_row_comp["comp_name"] == "บริษัท ธรรมบุรี จำกัด") { ?>

						<!-- <script type="text/javascript" src="js/calinvoiceRevenue_TBRI.js"></script> -->

						<div class="col-md-12 pt-1 pb-3">
							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-7 pr-1">
									<div class="row">
										<div class="col-md-12">
											<div class="text-center" style="background-color: #E9ECEF; padding: 12px 0; border: 1px solid #000;">
												<b>รายละเอียด</b>
											</div>
											<div class="row">
												<div class="col-md-9 pr-1">
													<input type="text" class="form-control my-1" name="invredesc1" id="invredesc1" autocomplete="off" tabindex="6" value="<?=$obj_row["re_description1"];?>" placeholder="กรอกรายละเอียด" readonly>
													<input type="text" class="form-control my-1" name="invredesc2" id="invredesc2" autocomplete="off" tabindex="9" value="<?=$obj_row["re_description2"];?>" placeholder="กรอกรายละเอียด" readonly>
													<input type="text" class="form-control my-1" name="invredesc3" id="invredesc3" autocomplete="off" tabindex="12" value="<?=$obj_row["re_description3"];?>" placeholder="กรอกรายละเอียด" readonly>
													<input type="text" class="form-control my-1" name="invredesc4" id="invredesc4" autocomplete="off" tabindex="15" value="<?=$obj_row["re_description4"];?>" placeholder="กรอกรายละเอียด" readonly>
													<input type="text" class="form-control my-1" name="invredesc5" id="invredesc5" autocomplete="off" tabindex="18" value="<?=$obj_row["re_description5"];?>" placeholder="กรอกรายละเอียด" readonly>
													<div class="row px-0">
														<div class="col-md-9 pr-1">
															<input type="text" class="form-control" name="invredesc6" id="invredesc6" autocomplete="off" tabindex="21" value="<?=$obj_row["re_description6"];?>" placeholder="กรอกรายละเอียด" readonly>
														</div>
														<div class="col-md-3 pl-1">
															<input type="text" class="form-control text-right" name="invresubdesc6" id="invresubdesc6" autocomplete="off" tabindex="22" value="<?=$obj_row["re_sub_description6"];?>" placeholder="กรอกจำนวนหน่วยคูณ" readonly>
															<input type="text" class="form-control text-right d-none" name="invresubdescHidden6" id="invresubdescHidden6" value="<?=$obj_row["re_sub_description6"];?>" readonly>
														</div>
													</div>
													
													<input type="text" class="form-control my-1" name="invredesc7" id="invredesc7" autocomplete="off" tabindex="25" value="<?=$obj_row["re_description7"];?>" placeholder="กรอกรายละเอียด" readonly>
													<input type="text" class="form-control my-1" name="invredesc8" id="invredesc8" autocomplete="off" tabindex="28" value="<?=$obj_row["re_description8"];?>" placeholder="กรอกรายละเอียด" readonly>
												</div>
												<div class="col-md-3 pl-1">
													<input type="text" class="form-control my-1 text-center" name="invresubdesc1" id="invresubdesc1" tabindex="7" autocomplete="off" value="<?=$obj_row["re_sub_description1"];?>" placeholder="กรอกเฉพาะตัวอักษร" readonly>

													<input type="text" class="form-control my-1 text-center" name="invresubdesc2" id="invresubdesc2" tabindex="10" autocomplete="off" value="<?=$obj_row["re_sub_description2"];?>" placeholder="กรอกเฉพาะตัวอักษร" readonly>

													<input type="text" class="form-control my-1 text-right" name="invresubdesc3" id="invresubdesc3" tabindex="13" autocomplete="off" value="<?=number_format($obj_row["re_sub_description3"],4, '.', '');?>" placeholder="กรอกเฉพาะตัวเลข" readonly>
													<input type="text" class="form-control my-1 d-none" readonly name="invresubdescHidden3" id="invresubdescHidden3" autocomplete="off" value="<?=$obj_row["re_sub_description3"];?>">

													<input type="text" class="form-control my-1 text-right" name="invresubdesc4" id="invresubdesc4" tabindex="16" autocomplete="off" value="<?=number_format($obj_row["re_sub_description4"],4, '.', '');?>" placeholder="กรอกเฉพาะตัวเลข" readonly>
													<input type="text" class="form-control my-1 d-none" readonly name="invresubdescHidden4" id="invresubdescHidden4" autocomplete="off" value="<?=$obj_row["re_sub_description4"];?>">

													<input type="text" class="form-control my-1 text-right" name="invresubdesc5" id="invresubdesc5" tabindex="19" autocomplete="off" value="<?=number_format($obj_row["re_sub_description5"],4, '.', '');?>" placeholder="กรอกเฉพาะตัวเลข" readonly>
													<input type="text" class="form-control my-1 d-none" readonly name="invresubdescHidden5" id="invresubdescHidden5" autocomplete="off" value="<?=$obj_row["re_sub_description5"];?>">

													<input type="text" class="form-control my-1 text-right" name="invresubdesc7" id="invresubdesc7" tabindex="23" autocomplete="off" value="<?=number_format($obj_row["re_sub_description7"],4, '.', '');?>" placeholder="กรอกเฉพาะตัวเลข" readonly>
													<input type="text" class="form-control my-1 d-none" readonly name="invresubdescHidden7" id="invresubdescHidden7" autocomplete="off" value="<?=$obj_row["re_sub_description7"];?>">

													<input type="text" class="form-control my-1 text-right" name="invresubdesc8" id="invresubdesc8" tabindex="26" autocomplete="off" value="<?=number_format($obj_row["re_sub_description8"],4, '.', '');?>" placeholder="กรอกเฉพาะตัวเลข" readonly>
													<input type="text" class="form-control my-1 d-none" readonly name="invresubdescHidden8" id="invresubdescHidden8" autocomplete="off" value="<?=$obj_row["re_sub_description8"];?>">

													<input type="text" class="form-control my-1 text-right" name="invresubdesc9" id="invresubdesc9" tabindex="29" autocomplete="off" value="<?=number_format($obj_row["re_sub_description9"],4, '.', '');?>" placeholder="กรอกเฉพาะตัวเลข" readonly>
													<input type="text" class="form-control my-1 d-none" readonly name="invresubdescHidden9" id="invresubdescHidden9" autocomplete="off" value="<?=$obj_row["re_sub_description9"];?>">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3 pl-1">
									<div class="row">
										<div class="col-md-12">
											<div class="text-center" style="background-color: #E9ECEF; padding: 12px 0; border: 1px solid #000;">
												<b>จำนวนเงิน</b>
											</div>
											<input type="text" class="form-control my-1 text-right" name="amount1" id="amount1" autocomplete="off" tabindex="8" value="<?=number_format($obj_row["re_amount1"],2);?>" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden1" id="amountHidden1" value="<?=$obj_row["re_amount1"];?>" readonly>

											<input type="text" class="form-control my-1 text-right" name="amount2" id="amount2" autocomplete="off" tabindex="11" value="<?=number_format($obj_row["re_amount2"],2);?>" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden2" id="amountHidden2" value="<?=$obj_row["re_amount2"];?>" readonly>

											<input type="text" class="form-control my-1 text-right" name="amount3" id="amount3" autocomplete="off" tabindex="14" value="<?=number_format($obj_row["re_amount3"],2);?>" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden3" id="amountHidden3" value="<?=$obj_row["re_amount3"];?>" readonly>

											<input type="text" class="form-control my-1 text-right" name="amount4" id="amount4" autocomplete="off" tabindex="17" value="<?=number_format($obj_row["re_amount4"],2);?>" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden4" id="amountHidden4" value="<?=$obj_row["re_amount4"];?>" readonly>

											<input type="text" class="form-control my-1 text-right" name="amount5" id="amount5" autocomplete="off" tabindex="20" value="<?=number_format($obj_row["re_amount5"],2);?>" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden5" id="amountHidden5" value="<?=$obj_row["re_amount5"];?>" readonly>

											<input type="text" class="form-control my-1 text-right" name="amount6" id="amount6" autocomplete="off" tabindex="24" value="<?=number_format($obj_row["re_amount6"],2);?>" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden6" id="amountHidden6" value="<?=$obj_row["re_amount6"];?>" readonly>

											<input type="text" class="form-control my-1 text-right" name="amount7" id="amount7" autocomplete="off" tabindex="27" value="<?=number_format($obj_row["re_amount7"],2);?>" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden7" id="amountHidden7" value="<?=$obj_row["re_amount7"];?>" readonly>

											<input type="text" class="form-control my-1 text-right" name="amount8" id="amount8" autocomplete="off" tabindex="30" value="<?=number_format($obj_row["re_amount8"],2);?>" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden8" id="amountHidden8" value="<?=$obj_row["re_amount8"];?>" readonly>
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
															<input type="text" class="form-control my-1" name="invNo" id="invNo" autocomplete="off" value="<?=$invoiceNo;?>" tabindex="28" readonly>

															<input type="text" class="form-control d-none my-1" name="irID" id="irID" autocomplete="off" value="<?=$obj_row["invrcpt_id"];?>" readonly>
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
																	<input type="radio" name="bySelPay" id="byCash" value="1" tabindex="29" <?php if($obj_row["re_typepay"] == 1) echo "checked"; ?> disabled>
																	<label for="byCash" class="mb-1">
																		<span>เงินสด</span>
																	</label>
																</div>
															</div>
														</div>
														<div class="col-md-4">
															<div class="input-group">
																<div class="checkbox">
																	<input type="radio" name="bySelPay" id="byCheque" value="2" tabindex="30" <?php if($obj_row["re_typepay"] == 2) echo "checked"; ?> disabled>
																	<label for="byCheque" class="mb-1">
																		<span>เช็คเลขที่</span>
																	</label>
																</div>
															</div>
														</div>
														<div class="col-md-5">
															<input type="text" class="form-control my-1" name="chequeNo" id="chequeNo" autocomplete="off" tabindex="32" value="<?=$obj_row["re_chequeno"];?>" disabled>
														</div>
														<div class="col-md-5">
															<div class="input-group">
																<div class="checkbox">
																	<input type="radio" name="bySelPay" id="byTransfer" value="3" tabindex="31" <?php if($obj_row["re_typepay"] == 3) echo "checked"; ?> disabled>
																	<label for="byTransfer" class="mb-1">
																		<span>โอนเข้าบัญชี</span>
																	</label>
																</div>
															</div>
														</div> 
														<div class="col-md-6">
															<!-- <button type="button" class="btn btn-info" id="ClearRadio" title="Clear">
																<i class="icofont-close-circled"></i>
																<span class="descbtn">&nbsp;&nbsp;Clear การชำระเงิน</span> 
															</button> -->
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
																<select class="custom-select form-control" name="SelBank" id="SelBank" tabindex="33" disabled>
																	<option value="" selected disabled>
																		กรุณาเลือกธนาคาร...
																	</option>
																	<?php
																		$str_sql_b = "SELECT * FROM bank_tb ORDER BY bank_name ASC";
																		$obj_rs_b = mysqli_query($obj_con, $str_sql_b);
																		while ($obj_row_b = mysqli_fetch_array($obj_rs_b)) {
																	?>
																	<option value="<?=$obj_row_b["bank_id"];?>" <?php if($obj_row["re_bankid"] == $obj_row_b["bank_id"]) echo "selected"; ?>>
																		<?=$obj_row_b["bank_name"];?>
																	</option>
																	<?php } ?>
																</select>
																<!-- <div class="input-group-append">
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
																</div> -->
															</div>
														</div>
														<div class="col-md-12" id="SelectBranch">
															<label class="my-1">สาขา</label>
															<div class="input-group">
																<select class="custom-select form-control" name="SelBranch" id="SelBranch" tabindex="34" disabled>
																	<option value="" selected disabled>กรุณาเลือกสาขา...</option>
																	<?php 
																		if($obj_row["re_branchid"] != '') { 

																			$str_sql_brc = "SELECT * FROM branch_tb WHERE brc_bankid = '". $obj_row["re_bankid"] ."' ORDER BY brc_name ASC";
																			$obj_rs_brc = mysqli_query($obj_con, $str_sql_brc);
																			while ($obj_row_brc = mysqli_fetch_array($obj_rs_brc)) {
																	?>
																		<option value="<?=$obj_row_brc["brc_id"];?>" <?php if($obj_row["re_branchid"] == $obj_row_brc["brc_id"]) echo "selected"; ?>>
																			<?=$obj_row_brc["brc_name"];?>
																		</option>
																	<?php 
																			} 
																		} else {} 
																	?>
																</select>
																<!-- <div class="input-group-append">
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
																</div> -->
															</div>
															<input type="text" class="form-control d-none" name="brcval" id="brcval">
														</div>
														<div class="col-md-12">
															<label class="my-1">วันที่</label>
															<div class="input-group">
																<input type="date" class="form-control" name="chequeDate" id="chequeDate" value="<?=$obj_row["re_chequedate"];?>" tabindex="35" readonly>
															</div>
														</div>
														<div class="col-md-12">
															<label class="my-1">หมายเหตุ</label>
															<textarea class="form-control" name="ReNote" id="ReNote" rows="2" placeholder="หมายเหตุ" autocomplete="off" tabindex="36" readonly><?=$obj_row["re_note"];?></textarea>
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
															<input type="text" class="form-control my-1 text-right" name="vatpercent" id="showVatPercent" autocomplete="off" value="<?=number_format($obj_row["re_vatpercent"],2);?>" tabindex="23" readonly>
															<input type="text" class="form-control my-1 text-right d-none" name="vatpercentHidden" id="calVatPercent" value="<?=$obj_row["re_vatpercent"];?>" readonly>
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
											<input type="text" class="form-control my-1 text-right" name="subtotal" id="showSubtotal" autocomplete="off" value="<?=number_format($obj_row["re_subtotal"],2);?>" readonly tabindex="22">
											<input type="text" class="form-control my-1 text-right d-none" name="subtotalHidden" id="calSubtotal" value="<?=$obj_row["re_subtotal"];?>" readonly>
										
											<input type="text" class="form-control my-1 text-right" name="vat" id="showVat" autocomplete="off" value="<?=number_format($obj_row["re_vat"],2);?>" readonly tabindex="24">
											<input type="text" class="form-control my-1 text-right d-none" name="vatHidden" id="calVat" value="<?=$obj_row["re_vat"];?>" readonly>

											<input type="text" class="form-control text-right" name="grandtotal" id="showGrandtotal" autocomplete="off" value="<?=number_format($obj_row["re_grandtotal"],2);?>" readonly tabindex="26">
											<input type="text" class="form-control text-right d-none" name="grandtotalHidden" id="calGrandtotal" value="<?=$obj_row["re_grandtotal"];?>" readonly>

											<div class="form-group my-1 <?php if($obj_row["invrcpt_id"] != '') echo "d-none"; ?>">
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

											<input type="text" class="form-control my-1 text-right" name="diffvat" id="showDiffVat" autocomplete="off" value="<?=number_format($obj_row["re_differencevat"],2);?>" tabindex="25" readonly>
											<input type="text" class="form-control my-1 text-right d-none" name="DiffVatHidden" id="calDiffVat" autocomplete="off" value="<?=$obj_row["re_differencevat"];?>" readonly>

											<input type="text" class="form-control my-1 text-right" name="diffgrand" id="showDiffGrand" autocomplete="off" value="<?=number_format($obj_row["re_differencegrandtotal"],2);?>" tabindex="27" readonly>
											<input type="text" class="form-control my-1 text-right d-none" name="DiffGrandHidden" id="calDiffGrand" autocomplete="off" value="<?=$obj_row["re_differencegrandtotal"];?>" readonly>
										</div>
									</div>
								</div>
							</div>
						</div>

					<?php } else { ?>

						<!-- <script type="text/javascript" src="js/calinvoiceRevenue.js"></script> -->

						<div class="col-md-12 pt-1 pb-3">
							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-7">
									<div class="row">
										<div class="col-md-12">
											<div class="text-center" style="background-color: #E9ECEF; padding: 12px 0; border: 1px solid #000;">
												<b>รายละเอียด</b>
											</div>
											<input type="text" class="form-control my-1" name="invredesc1" id="invredesc1" autocomplete="off" value="<?=$desc1;?>" tabindex="6" readonly>
											<input type="text" class="form-control my-1" name="invredesc2" id="invredesc2" autocomplete="off" value="<?=$desc2;?>" tabindex="7" readonly>
											<input type="text" class="form-control my-1" name="invredesc3" id="invredesc3" autocomplete="off" value="<?=$desc3;?>" tabindex="8" readonly>
											<input type="text" class="form-control my-1" name="invredesc4" id="invredesc4" autocomplete="off" value="<?=$desc4;?>" tabindex="9" readonly>
											<input type="text" class="form-control my-1" name="invredesc5" id="invredesc5" autocomplete="off" value="<?=$desc5;?>" tabindex="10" readonly>

											<?php if($cid == 'C014' || $cid == 'C015') { ?>
												<input type="text" class="form-control my-1" name="invredesc6" id="invredesc6" autocomplete="off" value="<?=$desc6;?>" tabindex="11" readonly>
												<input type="text" class="form-control my-1" name="invredesc7" id="invredesc7" autocomplete="off" value="<?=$desc7;?>" tabindex="12" readonly>
												<input type="text" class="form-control my-1" name="invredesc8" id="invredesc8" autocomplete="off" value="<?=$desc8;?>" tabindex="13" readonly>
											<?php } else { ?>
												<input type="text" class="form-control my-1" name="invredesc6" id="invredesc6" autocomplete="off" value="" readonly>
												<input type="text" class="form-control my-1" name="invredesc7" id="invredesc7" autocomplete="off" value="" readonly>
												<input type="text" class="form-control my-1" name="invredesc8" id="invredesc8" autocomplete="off" value="" readonly>								
											<?php }?>


										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="row">
										<div class="col-md-12">
											<div class="text-center" style="background-color: #E9ECEF; padding: 12px 0; border: 1px solid #000;">
												<b>จำนวนเงิน</b>
											</div>
											<input type="text" class="form-control text-right my-1" name="amount1" id="amount1" autocomplete="off" value="<?=number_format($obj_row["re_amount1"],2);?>" tabindex="14" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden1" id="amountHidden1" value="<?=$obj_row["re_amount1"];?>">
											<input type="text" class="form-control text-right my-1" name="amount2" id="amount2" autocomplete="off" value="<?=number_format($obj_row["re_amount2"],2);?>" tabindex="15" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden2" id="amountHidden2" value="<?=$obj_row["re_amount2"];?>">
											<input type="text" class="form-control text-right my-1" name="amount3" id="amount3" autocomplete="off" value="<?=number_format($obj_row["re_amount3"],2);?>" tabindex="16" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden3" id="amountHidden3" value="<?=$obj_row["re_amount3"];?>">
											<input type="text" class="form-control text-right my-1" name="amount4" id="amount4" autocomplete="off" value="<?=number_format($obj_row["re_amount4"],2);?>" tabindex="17" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden4" id="amountHidden4" value="<?=$obj_row["re_amount4"];?>">
											<input type="text" class="form-control text-right my-1" name="amount5" id="amount5" autocomplete="off" value="<?=number_format($obj_row["re_amount5"],2);?>" tabindex="18" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden5" id="amountHidden5" value="<?=$obj_row["re_amount5"];?>">


											<?php if($cid == 'C014' || $cid == 'C015') { ?>
												<input type="text" class="form-control text-right my-1" name="amount6" id="amount6" autocomplete="off" value="<?=number_format($obj_row["re_amount6"],2);?>" tabindex="19" readonly>
												<input type="text" class="form-control text-right my-1 d-none" name="amountHidden6" id="amountHidden6" value="<?=$obj_row["re_amount6"];?>">
												<input type="text" class="form-control text-right my-1" name="amount7" id="amount7" autocomplete="off" value="<?=number_format($obj_row["re_amount7"],2);?>" tabindex="20" readonly>
												<input type="text" class="form-control text-right my-1 d-none" name="amountHidden7" id="amountHidden7" value="<?=$obj_row["re_amount7"];?>">
												<input type="text" class="form-control text-right my-1" name="amount8" id="amount8" autocomplete="off" value="<?=number_format($obj_row["re_amount8"],2);?>" tabindex="21" readonly>
												<input type="text" class="form-control text-right my-1 d-none" name="amountHidden8" id="amountHidden8" value="<?=$obj_row["re_amount8"];?>">
											<?php } else { ?>
												<input type="text" class="form-control text-right my-1" name="amount6" autocomplete="off" readonly>
												<input type="text" class="form-control text-right my-1 d-none" name="amountHidden6" id="amountHidden6" value="0.00">
												<input type="text" class="form-control text-right my-1" name="amount7" autocomplete="off" readonly>
												<input type="text" class="form-control text-right my-1 d-none" name="amountHidden7" id="amountHidden7" value="0.00">
												<input type="text" class="form-control text-right my-1" name="amount8" autocomplete="off" readonly>
												<input type="text" class="form-control text-right my-1 d-none" name="amountHidden8" id="amountHidden8" value="0.00">									
											<?php }?>

											
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
															<?php
																if($cid == 'C011') {
																	$roly = '';
																	$RefinvrcptNo = $row["re_invrcptNo_RPEC"];
																} else {
																	$roly = 'readonly';
																	$RefinvrcptNo = $invoiceNo;
																}
															?>
															<input type="text" class="form-control my-1" name="invNo" id="invNo" autocomplete="off" value="<?=$RefinvrcptNo;?>" <?=$roly;?> tabindex="28" readonly>

															<input type="text" class="form-control d-none my-1" name="irID" id="irID" autocomplete="off" value="<?=$obj_row["invrcpt_id"];?>" readonly>
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
																	<input type="radio" name="bySelPay" id="byCash" value="1" tabindex="29" <?php if($obj_row["re_typepay"] == 1) echo "checked"; ?> disabled>
																	<label for="byCash" class="mb-1">
																		<span>เงินสด</span>
																	</label>
																</div>
															</div>
														</div>
														<div class="col-md-4">
															<div class="input-group">
																<div class="checkbox">
																	<input type="radio" name="bySelPay" id="byCheque" value="2" tabindex="30" <?php if($obj_row["re_typepay"] == 2) echo "checked"; ?> disabled>
																	<label for="byCheque" class="mb-1">
																		<span>เช็คเลขที่</span>
																	</label>
																</div>
															</div>
														</div>
														<div class="col-md-5">
															<input type="text" class="form-control my-1" name="chequeNo" id="chequeNo" autocomplete="off" tabindex="32" value="<?=$obj_row["re_chequeno"];?>" readonly>
														</div>
														<div class="col-md-5">
															<div class="input-group">
																<div class="checkbox">
																	<input type="radio" name="bySelPay" id="byTransfer" value="3" tabindex="31" <?php if($obj_row["re_typepay"] == 3) echo "checked"; ?> disabled>
																	<label for="byTransfer" class="mb-1">
																		<span>โอนเข้าบัญชี</span>
																	</label>
																</div>
															</div>
														</div> 
														<div class="col-md-6">
															<!-- <button type="button" class="btn btn-info" id="ClearRadio" title="Clear">
																<i class="icofont-close-circled"></i>
																<span class="descbtn">&nbsp;&nbsp;Clear การชำระเงิน</span>
															</button> -->
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
																<select class="custom-select form-control" name="SelBank" id="SelBank" tabindex="33" disabled>
																	<option value="" selected disabled>
																		กรุณาเลือกธนาคาร...
																	</option>
																	<?php
																		$str_sql_b = "SELECT * FROM bank_tb ORDER BY bank_name ASC";
																		$obj_rs_b = mysqli_query($obj_con, $str_sql_b);
																		while ($obj_row_b = mysqli_fetch_array($obj_rs_b)) {
																	?>
																	<option value="<?=$obj_row_b["bank_id"];?>" <?php if($obj_row["re_bankid"] == $obj_row_b["bank_id"]) echo "selected"; ?>>
																		<?=$obj_row_b["bank_name"];?>
																	</option>
																	<?php } ?>
																</select>
																<!-- <div class="input-group-append">
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
																</div> -->
															</div>
														</div>
														<div class="col-md-12" id="SelectBranch">
															<label class="my-1">สาขา</label>
															<div class="input-group">
																<select class="custom-select form-control" name="SelBranch" id="SelBranch" tabindex="34" disabled>
																	<option value="" selected disabled>กรุณาเลือกสาขา...</option>
																	<?php 
																		if($obj_row["re_branchid"] != '') { 

																			$str_sql_brc = "SELECT * FROM branch_tb WHERE brc_bankid = '". $obj_row["re_bankid"] ."' ORDER BY brc_name ASC";
																			$obj_rs_brc = mysqli_query($obj_con, $str_sql_brc);
																			while ($obj_row_brc = mysqli_fetch_array($obj_rs_brc)) {
																	?>
																		<option value="<?=$obj_row_brc["brc_id"];?>" <?php if($obj_row["re_branchid"] == $obj_row_brc["brc_id"]) echo "selected"; ?>>
																			<?=$obj_row_brc["brc_name"];?>
																		</option>
																	<?php 
																			} 
																		} else {} 
																	?>
																</select>
																<!-- <div class="input-group-append">
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
																</div> -->
															</div>
															<input type="text" class="form-control d-none" name="brcval" id="brcval">
														</div>
														<div class="col-md-12">
															<label class="my-1">วันที่</label>
															<div class="input-group">
																<input type="date" class="form-control" name="chequeDate" id="chequeDate" value="<?=$obj_row["re_chequedate"];?>" tabindex="35" readonly>
															</div>
														</div>
														<div class="col-md-12">
															<label class="my-1">หมายเหตุ</label>
															<textarea class="form-control" name="ReNote" id="ReNote" rows="2" placeholder="หมายเหตุ" autocomplete="off" tabindex="36" readonly><?=$obj_row["re_note"];?></textarea>
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
															<input type="text" class="form-control my-1 text-right" name="vatpercent" id="showVatPercent" autocomplete="off" value="<?=number_format($obj_row["re_vatpercent"],2);?>" tabindex="23" readonly>
															<input type="text" class="form-control my-1 text-right d-none" name="vatpercentHidden" id="calVatPercent" value="<?=$obj_row["re_vatpercent"];?>" readonly>
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
											<input type="text" class="form-control my-1 text-right" name="subtotal" id="showSubtotal" autocomplete="off" value="<?=number_format($obj_row["re_subtotal"],2);?>" readonly tabindex="22">
											<input type="text" class="form-control my-1 text-right d-none" name="subtotalHidden" id="calSubtotal" value="<?=$obj_row["re_subtotal"];?>" readonly>
										
											<input type="text" class="form-control my-1 text-right" name="vat" id="showVat" autocomplete="off" value="<?=number_format($obj_row["re_vat"],2);?>" readonly tabindex="24">
											<input type="text" class="form-control my-1 text-right d-none" name="vatHidden" id="calVat" value="<?=$obj_row["re_vat"];?>" readonly>

											<input type="text" class="form-control text-right" name="grandtotal" id="showGrandtotal" autocomplete="off" value="<?=number_format($obj_row["re_grandtotal"],2);?>" readonly tabindex="26">
											<input type="text" class="form-control text-right d-none" name="grandtotalHidden" id="calGrandtotal" value="<?=$obj_row["re_grandtotal"];?>" readonly>

											<div class="form-group my-1 <?php if($obj_row["invrcpt_id"] != '') echo "d-none"; ?>">
												<div class="checkbox">
													<input type="checkbox" id="totalVat" onclick="checkInv()" disabled>
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

											<input type="text" class="form-control my-1 text-right" name="diffvat" id="showDiffVat" autocomplete="off" value="<?=number_format($obj_row["re_differencevat"],2);?>" tabindex="25" readonly>
											<input type="text" class="form-control my-1 text-right d-none" name="DiffVatHidden" id="calDiffVat" autocomplete="off" value="<?=$obj_row["re_differencevat"];?>" readonly>

											<input type="text" class="form-control my-1 text-right" name="diffgrand" id="showDiffGrand" autocomplete="off" value="<?=number_format($obj_row["re_differencegrandtotal"],2);?>" tabindex="27" readonly>
											<input type="text" class="form-control my-1 text-right d-none" name="DiffGrandHidden" id="calDiffGrand" autocomplete="off" value="<?=$obj_row["re_differencegrandtotal"];?>" readonly>
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
						
					<?php } ?>

				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12 pb-4 text-center">
						<input type="button" class="btn btn-success px-5 py-2 mx-1" name="btnInsert" id="btnInsert" value="บันทึก">
					</div>
				</div>

			</form>

		</div>
	</section>

	<?php 
		include 'bank_add.php';
		include 'branch_add.php'; 
		// include 'action_script_bank.php';
		include 'invoice_add_customer.php';
	?>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {

			// $("select[name=SelBranch]").change(function(){
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
			$("#btnInsert").click(function() {
				var formData = new FormData(this.form);
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
					$.ajax({
						type: "POST", 
						url: "r_receipt_edit.php",
						// data: $("#frmReceipt").serialize(),
						data: formData,
						dataType: 'json',
						contentType: false,
						cache: false,
						processData:false,
						success: function(result) {
							if(result.status == 1) {
								// swal({
								// 	title: "บันทึกข้อมูลสำเร็จ",
								// 	text: "เลขที่ใบเสร็จรับเงิน " + result.url,
								// 	type: "success",
								// 	closeOnClickOutside: false
								// },function() {
								// 	window.location.href = "receipt.php?cid="+ result.compid + "&dep="+ result.depid;
								// });
								window.location.href = result.url + '&page=' + result.page;
							} else {
								alert(result.message);
							}
						}
					});
				}
			});
			//------ START END INVOICE ------//


			$("input[name='bySelPay']").click(function(){
				var radioValue = $("input[id='byTransfer']:checked").val();
				if(radioValue) {
					$('#chequeNo').val('โอนเข้าบัญชี');
				} else {
					$('#chequeNo').val('');
				}

				// $('#ClearRadio').click(function() {
				// 	$("input:radio[name=bySelPay]:checked")[0].checked = false;
				// 	$('#chequeNo').val('');
				// });
			});

			$('#ClearRadio').click(function() {
				$("input:radio[name=bySelPay]:checked")[0].checked = false;
				$('#chequeNo').val('');
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

			// document.getElementById("amount1").onblur = function (){
			// 	this.value = parseFloat(this.value.replace(/,/g, ""))
			// 	.toFixed(2)
			// 	.toString()
			// 	.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			// 	document.getElementById("amountHidden1").value = this.value.replace(/,/g, "");
			// }

			// document.getElementById("amount2").onblur = function (){
			// 	this.value = parseFloat(this.value.replace(/,/g, ""))
			// 	.toFixed(2)
			// 	.toString()
			// 	.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			// 	document.getElementById("amountHidden2").value = this.value.replace(/,/g, "");
			// }

			// document.getElementById("amount3").onblur = function (){
			// 	this.value = parseFloat(this.value.replace(/,/g, ""))
			// 	.toFixed(2)
			// 	.toString()
			// 	.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			// 	document.getElementById("amountHidden3").value = this.value.replace(/,/g, "");
			// }

			// document.getElementById("amount4").onblur = function (){
			// 	this.value = parseFloat(this.value.replace(/,/g, ""))
			// 	.toFixed(2)
			// 	.toString()
			// 	.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			// 	document.getElementById("amountHidden4").value = this.value.replace(/,/g, "");
			// }

			// document.getElementById("amount5").onblur = function (){
			// 	this.value = parseFloat(this.value.replace(/,/g, ""))
			// 	.toFixed(2)
			// 	.toString()
			// 	.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			// 	document.getElementById("amountHidden5").value = this.value.replace(/,/g, "");
			// }

			// <?php if($cid == 'C014' || $cid == 'C015') { ?>	
			// 	document.getElementById("amount6").onblur = function (){
			// 		this.value = parseFloat(this.value.replace(/,/g, ""))
			// 		.toFixed(2)
			// 		.toString()
			// 		.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			// 		document.getElementById("amountHidden6").value = this.value.replace(/,/g, "");
			// 	}

			// 	document.getElementById("amount7").onblur = function (){
			// 		this.value = parseFloat(this.value.replace(/,/g, ""))
			// 		.toFixed(2)
			// 		.toString()
			// 		.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			// 		document.getElementById("amountHidden7").value = this.value.replace(/,/g, "");
			// 	}

			// 	document.getElementById("amount8").onblur = function (){
			// 		this.value = parseFloat(this.value.replace(/,/g, ""))
			// 		.toFixed(2)
			// 		.toString()
			// 		.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			// 		document.getElementById("amountHidden8").value = this.value.replace(/,/g, "");
			// 	}
			// <?php }?>

			// document.getElementById("showVatPercent").onblur = function (){
			// 	this.value = parseFloat(this.value.replace(/,/g, ""))
			// 	.toFixed(2)
			// 	.toString()
			// 	.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			// 	document.getElementById("calVatPercent").value = this.value.replace(/,/g, "");
			// }

			// document.getElementById("showDiffVat").onblur = function (){
			// 	this.value = parseFloat(this.value.replace(/,/g, ""))
			// 	// .toFixed(2)
			// 	.toString()
			// 	.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			// 	document.getElementById("calDiffVat").value = this.value.replace(/,/g, "");
			// }

			// document.getElementById("showGrandtotal").onblur = function (){
			// 	this.value = parseFloat(this.value.replace(/,/g, ""))
			// 	.toFixed(2)
			// 	.toString()
			// 	.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			// 	document.getElementById("calGrandtotal").value = this.value.replace(/,/g, "");
			// }

			// document.getElementById("showDiffGrand").onblur = function (){
			// 	this.value = parseFloat(this.value.replace(/,/g, ""))
			// 	// .toFixed(2)
			// 	.toString()
			// 	.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			// 	document.getElementById("calDiffGrand").value = this.value.replace(/,/g, "");
			// }
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>