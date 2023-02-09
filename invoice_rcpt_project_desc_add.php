<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$projid = $_GET["projid"];

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

		$str_sql_les = "SELECT invrcptD_lesson FROM invoice_rcpt_desc_tb WHERE invrcptD_projid = '". $_GET["projid"] ."'";
		$obj_rs_les = mysqli_query($obj_con, $str_sql_les);
		$lesson = '';
		while ($obj_row_les = mysqli_fetch_array($obj_rs_les)) {
			$lesson = $obj_row_les["invrcptD_lesson"] + 1;
		}

		$str_sql = "SELECT * FROM project_tb WHERE proj_id = '". $projid ."'";
		$obj_rs = mysqli_query($obj_con, $str_sql);
		$obj_row = mysqli_fetch_array($obj_rs);

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

			<form method="POST" name="frmInvoiceProject" id="frmInvoiceProject" action="">
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-plus-circle"></i>
							&nbsp;&nbsp;เพิ่มรายละเอียด ( รายรับ )
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
						<input type="text" class="form-control" name="projid" id="projid" value="<?=$projid;?>">
						<input type="text" class="form-control" name="stsid" id="stsid" value="STS001">
						<input type="text" class="form-control" name="invReDuseridCreate" id="invReDuseridCreate" value="<?=$obj_row_user["user_id"];?>">
						<input type="date" class="form-control" name="invReDCreateDate" id="invReDCreateDate" value="">
						<input type="text" class="form-control" name="invReDuseridEdit" id="invReDuseridEdit" value="">
						<input type="date" class="form-control" name="invReDEditDate" id="invReDEditDate" value="">
						<input type="text" class="form-control" name="invrcptstatus" id="invrcptstatus" value="0">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-2 pt-1 pb-3">
						<label for="invRedepid" class="mb-1">ฝ่าย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="invRedepname" id="invRedepname" value="<?=$obj_row_dep["dep_name"];?>" readonly>
							<input type="text" class="form-control d-none" name="invRedepid" id="invRedepid" value="<?=$dep;?>">
						</div>
					</div>

					<div class="col-md-2 pt-1 pb-3">
						<label for="invReDLesson" class="mb-1">งวด</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-listing-number"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="invReDLesson" id="invReDLesson" placeholder="กรอกงวดใบแจ้งหนี้" autocomplete="off" value="<?= $lesson; ?>">
						</div>
					</div>

					<input type="text" class="d-none" name="substrLesson" id="substrLesson" value="">
					<script>
						let text = document.getElementById("invReDLesson").value;
						let result = text.slice(0, -2);
						document.getElementById("substrLesson").value = result;
					</script>

					<input type="text" class="form-control d-none" name="projPart" id="projPart" value="<?=$obj_row["proj_part"];?>">

					<?php if($obj_row["proj_part"] == 0) { ?>
					<div class="col-md-3 pt-1 pb-3">
						<label for="invRef" class="mb-1">อ้างอิงส่วนย่อย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-sub-listing"></i>
								</i>
							</div>
							<select class="custom-select form-control" name="projSub" id="projSub">
								<option value="">กรุณาเลือก...</option>
								<?php
									$str_sql_projS = "SELECT * FROM project_sub_tb WHERE projS_projid = '". $projid ."'"; 
									$obj_rs_projS = mysqli_query($obj_con, $str_sql_projS);
									while ($obj_row_projS = mysqli_fetch_array($obj_rs_projS)) {
								?>
								<option value="<?=$obj_row_projS["projS_id"];?>">
									<?=$obj_row_projS["projS_description"];?>
								</option>
								<?php } ?>
							</select>
						</div>
					</div>
					<?php } else { ?>
					<div class="col-md-3 pt-1 pb-3 d-none">
						<input type="text" class="form-control" name="projSub" id="projSub">
					</div>
					<?php } ?>

					<div class="col-md-12 pt-1 pb-3" id="showDataCust">
						<label for="searchCustomer" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทผู้รับบริการ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-building"></i>
								</i>
							</div>
							<input type="text" name="searchCustomer" id="searchCustomer" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" tabindex="3">

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
						<div class="row">
							<div class="col-md-1"></div>
							<div class="col-md-7">
								<div class="row">
									<div class="col-md-12">
										<div class="text-center" style="background-color: #E9ECEF; padding: 12px 0; border: 1px solid #000;">
											<b>รายละเอียด</b>
										</div>
										<input type="text" class="form-control my-1" name="invredesc1" id="invredesc1" autocomplete="off" tabindex="4" value="">
										<input type="text" class="form-control my-1" name="invredesc2" id="invredesc2" autocomplete="off" tabindex="5" value="">
										<input type="text" class="form-control my-1" name="invredesc3" id="invredesc3" autocomplete="off" tabindex="6" value="">
										<input type="text" class="form-control my-1" name="invredesc4" id="invredesc4" autocomplete="off" tabindex="7" value="">
										<input type="text" class="form-control my-1" name="invredesc5" id="invredesc5" autocomplete="off" tabindex="8" value="">

										<?php if($cid == 'C014' || $cid == 'C015') { ?>
											<input type="text" class="form-control my-1" name="invredesc6" id="invredesc6" autocomplete="off" tabindex="9" value="">
											<input type="text" class="form-control my-1" name="invredesc7" id="invredesc7" autocomplete="off" tabindex="10" value="">
											<input type="text" class="form-control my-1" name="invredesc8" id="invredesc8" autocomplete="off" tabindex="11" value="">
										<?php } else { ?>
											<input type="text" class="form-control my-1" name="invredesc6" id="invredesc6" autocomplete="off" tabindex="9" value="" readonly>
											<input type="text" class="form-control my-1" name="invredesc7" id="invredesc7" autocomplete="off" tabindex="10" value="" readonly>
											<input type="text" class="form-control my-1" name="invredesc8" id="invredesc8" autocomplete="off" tabindex="11" value="" readonly>								
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
										<input type="text" class="form-control text-right my-1" name="amount1" id="amount1" autocomplete="off" tabindex="15" value="0.00">
										<input type="text" class="form-control text-right my-1 d-none" name="amountHidden1" id="amountHidden1" value="0.00">
										<input type="text" class="form-control text-right my-1" name="amount2" id="amount2" autocomplete="off" tabindex="16" value="0.00">
										<input type="text" class="form-control text-right my-1 d-none" name="amountHidden2" id="amountHidden2" value="0.00">
										<input type="text" class="form-control text-right my-1" name="amount3" id="amount3" autocomplete="off" tabindex="17" value="0.00">
										<input type="text" class="form-control text-right my-1 d-none" name="amountHidden3" id="amountHidden3" value="0.00">
										<input type="text" class="form-control text-right my-1" name="amount4" id="amount4" autocomplete="off" tabindex="18" value="0.00">
										<input type="text" class="form-control text-right my-1 d-none" name="amountHidden4" id="amountHidden4" value="0.00">
										<input type="text" class="form-control text-right my-1" name="amount5" id="amount5" autocomplete="off" tabindex="19" value="0.00">
										<input type="text" class="form-control text-right my-1 d-none" name="amountHidden5" id="amountHidden5" value="0.00">

										<?php if($cid == 'C014' || $cid == 'C015') { ?>	
											<input type="text" class="form-control text-right my-1" name="amount6" id="amount6" autocomplete="off" tabindex="20" value="0.00">
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden6" id="amountHidden6" value="0.00">
											<input type="text" class="form-control text-right my-1" name="amount7" id="amount7" autocomplete="off" tabindex="21" value="0.00">
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden7" id="amountHidden7" value="0.00">
											<input type="text" class="form-control text-right my-1" name="amount8" id="amount8" autocomplete="off" tabindex="22" value="0.00">
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden8" id="amountHidden8" value="0.00">
										<?php } else { ?>
											<input type="text" class="form-control text-right my-1" name="amount6" autocomplete="off" tabindex="20" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden6" id="amountHidden6" value="0.00">
											<input type="text" class="form-control text-right my-1" name="amount7" autocomplete="off" tabindex="21" readonly>
											<input type="text" class="form-control text-right my-1 d-none" name="amountHidden7" id="amountHidden7" value="0.00">
											<input type="text" class="form-control text-right my-1" name="amount8" autocomplete="off" tabindex="22" readonly>
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
										<!-- <input type="text" class="form-control my-1" name="invredesc6" id="invredesc6" autocomplete="off" tabindex="9" value="">
										<input type="text" class="form-control my-1" name="invredesc7" id="invredesc7" autocomplete="off" tabindex="10" value="">
										<input type="text" class="form-control my-1" name="invredesc8" id="invredesc8" autocomplete="off" tabindex="11" value=""> -->
										<input type="text" class="form-control my-1" name="invredesc9" id="invredesc9" autocomplete="off" tabindex="12" value="">
										<input type="text" class="form-control my-1" name="invredesc10" id="invredesc10" autocomplete="off" tabindex="13" value="">
										<input type="text" class="form-control my-1" name="invredesc11" id="invredesc11" autocomplete="off" tabindex="14" value="">
									</div>
									<div class="col-md-4">
										<div class="row">
											<label class="col-md-12 col-form-label px-0 my-1 text-right">จำนวนเงิน Sub Total</label>

											<div class="col-md-12">
												<div class="row">
													<label class="col-md-6 col-form-label px-0 text-right">ภาษีมูลค่าเพิ่ม</label>
													<div class="col-md-6">
														<input type="text" class="form-control my-1 text-right" name="vatpercent" id="showVatPercent" autocomplete="off" tabindex="24" value="0.00">
														<input type="text" class="form-control my-1 text-right d-none" name="vatpercentHidden" id="calVatPercent" value="0.00" readonly>
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
										<input type="text" class="form-control my-1 text-right" name="subtotal" id="showSubtotal" autocomplete="off" tabindex="23" value="0.00" readonly>
										<input type="text" class="form-control my-1 text-right d-none" name="subtotalHidden" id="calSubtotal" value="0.00" readonly>
									
										<input type="text" class="form-control my-1 text-right" name="vat" id="showVat" autocomplete="off" tabindex="25" value="0.00" readonly>
										<input type="text" class="form-control my-1 text-right d-none" name="vatHidden" id="calVat" value="0.00" readonly>

										<input type="text" class="form-control text-right" name="grandtotal" id="showGrandtotal" autocomplete="off" tabindex="27" value="0.00" readonly>
										<input type="text" class="form-control text-right d-none" name="grandtotalHidden" id="calGrandtotal" value="0.00" readonly style="color: #F00;">

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

										<input type="text" class="form-control my-1 text-right" name="diffvat" id="showDiffVat" autocomplete="off" tabindex="26" value="0.00">
										<input type="text" class="form-control my-1 text-right d-none" name="DiffVatHidden" id="calDiffVat" autocomplete="off" value="0.00" readonly>

										<input type="text" class="form-control my-1 text-right" name="diffgrand" id="showDiffGrand" autocomplete="off" tabindex="28" value="0.00">
										<input type="text" class="form-control my-1 text-right d-none" name="DiffGrandHidden" id="calDiffGrand" autocomplete="off" value="0.00" readonly>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12 pb-4 text-center">
						<input type="button" class="btn btn-success px-5 py-2 mx-1" name="btnInsert" id="btnInsert" value="บันทึก">
					</div>
				</div>
			</form>
			
		</div>
	</section>

	<?php include 'invoice_add_customer.php'; ?>

	<script type="text/javascript">
		$(document).ready(function() {

			//------ START ADD DESCRIPTION ------//
			$("#btnInsert").click(function() {
				var formData = new FormData(this.form);
				if($('#invReDLesson').val() == '') {
					swal({
						title: "กรุณากรอกงวดที่",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmInvoiceProject.invReDLesson.focus();
					});
				} else if($('#custid').val() == '') {
					swal({
						title: "กรุณากรอกเลือกบริษัทลูกค้า",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmInvoiceProject.custid.focus();
					});
				} else if($('#invredesc1').val() == '') {
					swal({
						title: "กรุณากรอกรายละเอียด อย่างน้อย 1 บรรทัด",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmInvoiceProject.invredesc1.focus();
					});
				} else {
					if($('#projPart').val() == 0) {
						if($('#projSub').val() == '') {
							swal({
								title: "กรุณากรอกเลือกอ้างอิงส่วนย่อย",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "warning",
								closeOnClickOutside: false
							}).then(function() {
								frmInvoiceProject.projSub.focus();
							});
						} else {
							$.ajax({
								type: 'POST',
								url: 'r_invoice_rcpt_project_desc_add.php', 
								// data: $("#frmInvoiceProject").serialize(),
								data: formData,
								dataType: 'json',
								contentType: false,
								cache: false,
								processData:false,
								success: function(result) {
									if(result.status == 1) {
										swal({
											title: "บันทึกข้อมูลสำเร็จ",
											text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
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
						}
					} else {
						$.ajax({
							type: 'POST',
							url: 'r_invoice_rcpt_project_desc_add.php',
							// data: $("#frmInvoiceProject").serialize(),
							data: formData,
							dataType: 'json',
							contentType: false,
							cache: false,
							processData:false,
							success: function(result) {
								if(result.status == 1) {
									swal({
										title: "บันทึกข้อมูลสำเร็จ",
										text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
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
					}
				}
			});
			//------ END ADD DESCRIPTION ------//

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


			<?php if($cid == 'C014' || $cid == 'C015') { ?>	
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
			<?php }?>

			document.getElementById("showVatPercent").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calVatPercent").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showDiffVat").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				// .toFixed(2)
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
				// .toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calDiffGrand").value = this.value.replace(/,/g, "");
			}
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>