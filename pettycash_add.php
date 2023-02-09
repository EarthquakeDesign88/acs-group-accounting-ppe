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

		$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
		$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
		$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

		$str_sql_comp = "SELECT * FROM company_tb WHERE comp_id = '".$cid."'";
		$obj_rs_comp = mysqli_query($obj_con, $str_sql_comp);
		$obj_row_comp = mysqli_fetch_array($obj_rs_comp);

		$str_sql_paym = "SELECT * FROM payment_tb AS paym INNER JOIN invoice_tb AS i ON paym.paym_id = i.inv_paymid WHERE inv_typepcash = 1";
		$obj_rs_paym = mysqli_query($obj_con, $str_sql_paym);
		$obj_row_paym = mysqli_fetch_array($obj_rs_paym);

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

			<form method="POST" name="frmAddPettyCash" id="frmAddPettyCash" action="">
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มใบจ่ายเงินสดย่อย
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="dep" id="dep" value="<?=$dep;?>">
					</div>
				</div>

				<script type="text/javascript">
					function putValuePcash(name,id,balTotal,balTotalShow) {
						$('#searchPayment').val(name);
						$('#paymid').val(id);
						$('#invbalance').val(balTotal);
						$('#invbalanceshow').val(balTotalShow);
					}
				</script>
					
				<script type="text/javascript" src="js/script_pettycash.js"></script>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 pt-1 pb-3">
						<label for="searchPayment" class="mb-1">อ้างอิงเลขที่ใบสำคัญจ่าย</label>
						<div class="input-group mb-0">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-numbered"></i>
								</i>
							</div>
							<input type="text" name="searchPayment" id="searchPayment" class="form-control" placeholder="กรอกเลขที่ใบสำคัญจ่ายที่ต้องการค้นหา" autocomplete="off" value="">

							<input type="text" class="form-control d-none" id="paymid" name="paymid" value="">

							<div class="input-group-append">
								<button type="button" class="btn btn-info" onclick="
								document.getElementById('searchPayment').value = ''; 
								document.getElementById('paymid').value = '';
								document.getElementById('searchPayment').focus();
								document.getElementById('PCashRefPaym').style = 'display: none;';
								document.getElementById('invbalanceshow').value = '0.00';
								document.getElementById('invbalance').value = '0.00';
								document.getElementById('show-listPettycash').style.display = 'none';" title="Clear">
									<i class="icofont-close-circled"></i>
									<span class="descbtn">&nbsp;&nbsp;Clear</span>
								</button>
							</div>
						</div>
						<div class="list-group" id="show-listPettycash"></div>
					</div>

					<div class="col-md-9 pt-1 pb-3"></div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="invbalanceshow" class="mb-1">ยอดเงินใบสำคัญจ่าย</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control text-right" name="invbalanceshow" id="invbalanceshow" value="0.00" readonly>
						</div>
						<input type="text" class="form-control d-none" name="invbalance" id="invbalance" value="0.00">
					</div>

					<div class="col-md-12" id="PCashRefPaym" style="display: none;">
						<div class="row">
							<div class="col-lg-4 col-md-12 pt-1 pb-3">
								<label for="pcashNo" class="mb-1">เลขที่ใบจ่ายเงินสดย่อย</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-numbered"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="pcashNo" id="pcashNo" autocomplete="off" placeholder="เว้นว่างไว้เพื่อสร้างอัตโนมัติ" readonly>
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
									<input type="date" class="form-control" name="pcashDate" id="pcashDate" autocomplete="off" autofocus>
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
									<input type="text" class="form-control" name="depname" id="depname" value="<?=$obj_row_dep["dep_name"];?>" readonly>
									<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$dep;?>">
								</div>
							</div>

							<div class="col-lg-3 col-md-12 mr-auto pt-1 pb-3">
								<div class="row">
									<div class="col-lg-6 col-md-3 col-sm-12 pb-3 pb-sm-1">
										<label class="salary-tax"></label>
										<div class="input-group-prepend">
											<div class="checkbox">
												<input type="checkbox" id="pcashtaxrefund" onclick="checkTaxRefund()">
												<label for="pcashtaxrefund"><span>ขอคืนภาษี</span></label>
												<input type="text" class="form-control d-none" id="pcashtaxrefundChange" name="pcashtaxrefund" value="0">
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
												<input type="radio" name="paybySel" id="paybyCash" value="1" checked>
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
									<input type="text" name="searchCompany" id="searchCompany" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?=$obj_row_comp["comp_name"];?>" readonly>

									<input type="text" class="form-control d-none" id="invcompid" name="invcompid" value="<?=$obj_row_comp["comp_id"];?>">
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
									<input type="text" name="searchPayable" id="searchPayable" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off">

									<input type="text" class="form-control d-none" id="invpayaid" name="invpayaid">

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
									<tbody id="dynamic_fieldPettyCash"></tbody>
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
							<input type="text" class="form-control" name="pcashuseridCreate" id="pcashuseridCreate" autocomplete="off" value="<?=$obj_row_user["user_id"]?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="pcashCreateDate" class="mb-1">Create Date</label>
						<div class="input-group">
							<input type="date" class="form-control" name="pcashCreateDate" id="pcashCreateDate" autocomplete="off" value="" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="pcashuseridEdit" class="mb-1">Userid Edit</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashuseridEdit" id="pcashuseridEdit" autocomplete="off" value="" readonly>
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
							<input type="text" class="form-control" name="pcashstatusmgr" id="pcashstatusmgr" autocomplete="off" value="0" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="pcashapprmgrno" class="mb-1">เลขที่ตรวจสอบ Manager</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashapprmgrno" id="pcashapprmgrno" autocomplete="off" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="pcashrev" class="mb-1">ครั้งที่</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashrev" id="pcashrev" autocomplete="off" value="0" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="pcashyear" class="mb-1">ปี</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashyear" id="pcashyear" autocomplete="off" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="pcashmonth" class="mb-1">เดือน</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashmonth" id="pcashmonth" autocomplete="off" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="pcashPayeeName" class="mb-1">ชื่อผู้รับเงิน</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashPayeeName" id="pcashPayeeName" autocomplete="off" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="pcashPayeeDate" class="mb-1">วันที่รับเงิน</label>
						<div class="input-group">
							<input type="date" class="form-control" name="pcashPayeeDate" id="pcashPayeeDate" autocomplete="off" readonly>
						</div>
					</div>

					<div class="col-md-3 mr-auto pt-1 pb-3">
						<label for="pcashFile" class="mb-1">Petty Cash File</label>
						<div class="input-group">
							<input type="text" class="form-control" name="pcashFile" id="pcashFile" autocomplete="off" readonly>
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

	<?php include 'invoice_add_payable.php'; ?>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#searchPayment').change(function(){
				$('#paymid').val($('#searchPayment').val());
				if($('#paymid').val() == '') {
					document.getElementById("PCashRefPaym").style = "display: none;";
				} else {
					document.getElementById("PCashRefPaym").style = "display: block;";
				}
			});

			$("#PettyCash_insert").click(function() {

				if($('#pcashDate').val() == '') {
					swal({
						title: "กรุณากรอกวันที่ใบจ่ายเงินสดย่อย",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmAddPettyCash.pcashDate.setfocus();
					});
				} else if($('#invpayaid').val() == '') {
					swal({
						title: "กรุณาเลือกบริษัทเจ้าหนี้",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmAddPettyCash.searchPayable.setfocus();
					});
				} else if($('#pcashDesc1').val() == '') {
					swal({
						title: "กรุณากรอกรายละเอียด อย่างน้อย 1 รายการ",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmAddPettyCash.pcashDesc1.focus();
					});
				} else if($('#showSub1').val() == '') {
					swal({
						title: "กรุณากรอกรายละเอียด อย่างน้อย 1 รายการ",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmAddPettyCash.showSub1.focus();
					});
				} else {
					$.ajax({
						type: "POST",
						url: "r_pettycash_add.php",
						data: $("#frmAddPettyCash").serialize(),
						success: function(result) {
							if(result.status == 1) {
								swal({
									title: "บันทึกข้อมูลสำเร็จ",
									text: "เลขที่ใบจ่ายเงินสดย่อย " + result.pcno,
									type: "success",
									closeOnClickOutside: false
								},function() {
									window.location.href = "pettycash_preview.php?cid=" + result.compid + "&dep=" + result.depid + "&pcid=" + result.pcid + "&pcrev=" + result.pcrev;
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

			for (var i = 1; i <= 5; i++) {
				$('#dynamic_fieldPettyCash').append('<tr class="pettybottom" id="rowPCash'+i+'"><td>'+i+'.<input type="text" class="form-control d-none" name="pettycash" id="pettycash'+i+'" value="'+i+'"></td><td><div class="row"><div class="col-md-7"><label for="pcashDesc'+i+'" class="mb-1">รายการ</label><div class="input-group mb-2"><input type="text" class="form-control" name="pcashDesc'+i+'" id="pcashDesc'+i+'" maxlength="255" autocomplete="off"></div></div><div class="col-md-2"><label for="pcashSub'+i+'" class="mb-1">ก่อน VAT</label><div class="input-group mb-2"><input type="text" class="form-control text-right" id="showSub'+i+'" autocomplete="off" value="0.00"></div><input type="text" class="form-control d-none text-right pcashSub'+i+'" name="pcashSub'+i+'" id="calSub'+i+'" autocomplete="off" value="0.00"></div><div class="col-md-1"><label for="pcashVatP'+i+'" class="mb-1">VAT %</label><div class="input-group mb-2"><input type="text" class="form-control text-right" id="showVatP'+i+'" autocomplete="off" value="0.00"></div><input type="text" class="form-control text-right d-none pcashVatP'+i+'" name="pcashVatP'+i+'" id="calVatP'+i+'" autocomplete="off" value="0.00"></div><div class="col-md-2"><label for="pcashVat'+i+'" class="mb-1">VAT</label><div class="input-group mb-2"><input type="text" class="form-control text-right" id="pcashVat'+i+'" autocomplete="off" value="0.00" readonly></div><input type="text" class="form-control text-right d-none pcashVat'+i+'" name="pcashVatHidden'+i+'" id="pcashVatHidden'+i+'" autocomplete="off" value="0.00" readonly></div><div class="col-md-7"></div><div class="col-md-2"><label for="pcashTax'+i+'" class="mb-1">ก่อน TAX</label><div class="input-group mb-2"><input type="text" class="form-control text-right" id="showTax'+i+'" autocomplete="off" value="0.00"></div><input type="text" class="form-control text-right d-none pcashTax'+i+'" name="pcashTax'+i+'" id="calTax'+i+'" autocomplete="off" value="0.00"></div><div class="col-md-1"><label for="pcashTaxP'+i+'" class="mb-1">TAX %</label><div class="input-group mb-2"><input type="text" class="form-control text-right" id="showTaxP'+i+'" autocomplete="off" value="0.00"></div><input type="text" class="form-control d-none text-right pcashTaxP'+i+'" name="pcashTaxP'+i+'" id="calTaxP'+i+'" autocomplete="off" value="0.00"></div><div class="col-md-2"><label for="pcashTaxT'+i+'" class="mb-1">TAX</label><div class="input-group mb-2"><input type="text" class="form-control text-right" id="pcashTaxT'+i+'" autocomplete="off" value="0.00" readonly></div><input type="text" class="form-control text-right d-none pcashTaxT'+i+'" name="pcashTaxTHidden'+i+'" id="pcashTaxTHidden'+i+'" autocomplete="off" value="0.00" readonly></div><div class="col-md-4"></div><div class="col-md-3"><label for="pcashGrand'+i+'" class="mb-1">เงินรวม</label><div class="input-group mb-2"><input type="text" class="form-control text-right" id="pcashGrand'+i+'" autocomplete="off" value="0.00" readonly></div><input type="text" class="form-control text-right d-none pcashGrand'+i+'" name="pcashGrandHidden'+i+'" id="pcashGrandHidden'+i+'" autocomplete="off" value="0.00" readonly></div><div class="col-md-2"><label for="pcashDiff'+i+'" class="mb-1">+/- ส่วนต่าง</label><div class="input-group mb-2"><input type="text" class="form-control text-right" id="showDiff'+i+'" autocomplete="off" value="0.00"></div><input type="text" class="form-control text-right d-none pcashDiff'+i+'" name="pcashDiff'+i+'" id="calDiff'+i+'" autocomplete="off" value="0.00"></div><div class="col-md-3"><label for="pcashNet'+i+'" class="mb-1">ยอดชำระสุทธิ</label><div class="input-group mb-2"><input type="text" class="form-control text-right" id="pcashNet'+i+'" autocomplete="off" value="0.00" readonly></div><input type="text" class="form-control text-right d-none" name="pcashNetHidden'+i+'" id="pcashNetHidden'+i+'" autocomplete="off" value="0.00" readonly></div></div></td></tr>');
			}

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