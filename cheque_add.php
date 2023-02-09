<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$paymid = $_GET["paymid"];
		$paymrev = $_GET["paymrev"];
		$countChk = $_GET["countChk"];

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql_paym = "SELECT * FROM invoice_tb AS i 
						INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id 
						INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
						INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id 
						INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
						WHERE inv_compid = '". $cid ."' AND inv_depid = '". $dep ."' AND inv_paymid = '". $paymid ."'";
		$obj_rs_paym = mysqli_query($obj_con, $str_sql_paym);
		$obj_row_paym = mysqli_fetch_array($obj_rs_paym);

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

		$str_sql_ivamount = "SELECT * FROM invoice_tb WHERE inv_paymid = '" . $_GET["paymid"] . "'";
		$obj_rs_ivamount = mysqli_query($obj_con, $str_sql_ivamount);
		$invdesc = "";
		$invnetamount = 0;
		if (mysqli_num_rows($obj_rs_ivamount) > 1) { 
			while ($obj_row_ivamount = mysqli_fetch_array($obj_rs_ivamount)) {
				$invdesc = $obj_row_ivamount["inv_description"] . " || "  . $invdesc;
				$invnetamount += $obj_row_ivamount["inv_netamount"];
			}
		} else {

			$obj_row_ivamount = mysqli_fetch_array($obj_rs_ivamount);
			$invdesc = $obj_row_ivamount["inv_description"];
			$invnetamount = $obj_row_ivamount["inv_netamount"];

		}

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<link rel="stylesheet" type="text/css" href="css/checkbox.css">

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">
			
			<form method="POST" name="frmAddCheque" id="frmAddCheque" action="">
				
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-plus-circle"></i>&nbsp;&nbsp;ออกเช็ค
						</h3>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymno" class="mb-1">เลขที่ใบสำคัญจ่าย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-listing-number"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="paymno" id="paymno" value="<?= $obj_row_paym["paym_no"]; ?>" readonly>
							<input type="text" class="form-control d-none" name="paymid" id="paymid" value="<?= $obj_row_paym["paym_id"]; ?>" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymdate" class="mb-1">วันที่ใบสำคัญจ่าย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="paymdate" id="paymdate" value="<?= $obj_row_paym["paym_date"]; ?>" readonly>
						</div>
					</div>

					<div class="col-lg-2 col-md-2 pt-1 pb-3">
						<label for="invdepid" class="mb-1">ฝ่าย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="depname" id="depname" value="<?= $obj_row_paym["dep_name"]; ?>" readonly>
							<input type="text" class="form-control d-none" name="depid" id="depid" value="<?= $dep; ?>" readonly>
						</div>
					</div>

					<div class="col-lg-4 col-md-4 pt-1 pb-3"></div>

					<div class="col-lg-12 col-md-12 pt-4 pb-3">
						<div class="row">
							<div class="col-md-3 col-sm-3">
								<label>ชำระโดย</label>
								<div class="row">
									<div class="col-lg-5 col-md-5">
										<div class="input-group-prepend">
											<div class="checkbox">
												<input type="radio" name="paymbySel" id="paymbyCash" value="1" disabled>
												<label for="paymbyCash" class="mb-1"><span>เงินสด</span></label>
											</div>
										</div>
									</div>

									<div class="col-lg-5 col-md-5">
										<div class="input-group-prepend">
											<div class="checkbox">
												<input type="radio" name="paymbySel" id="paymbyCheque" value="2" checked="checked" disabled>
												<label for="paymbyCheque" class="mb-1"><span>เช็ค</span></label>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-lg-3 col-md-3 pt-1 pb-3">
								<label for="cheqNo" class="mb-1">เลขที่เช็ค</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-listing-number"></i>
										</i>
									</div>
									<input type="text" name="cheqNo" id="cheqNo" class="form-control" placeholder="กรอกเลขที่เช็ค" autocomplete="off" value="">
								</div>
							</div>

							<div class="col-lg-3 col-md-3 pt-1 pb-3">
								<label for="cheqDate" class="mb-1">วันที่ออกเช็ค</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-ui-calendar"></i>
										</i>
									</div>
									<input type="date" name="cheqDate" id="cheqDate" class="form-control" placeholder="กรอกวันที่ออกเช็ค" autocomplete="off" value="">
								</div>
							</div>

							<div class="col-lg-3 col-md-3 pt-1 pb-3">
								<label for="SelectBank" class="mb-1">ธนาคาร</label>
								<div class="input-group mb-2">
									<select class="form-control custom-select" name="SelectBank" id="SelectBank">
										<option value="" selected>กรุณาเลือกธนาคาร</option>
										<?php
											$str_sql_b = "SELECT * FROM bank_tb";
											$obj_rs_b = mysqli_query($obj_con, $str_sql_b);
											while ($obj_row_b = mysqli_fetch_array($obj_rs_b)) {
										?>
										<option value="<?=$obj_row_b["bank_id"]?>"><?=$obj_row_b["bank_name"]?></option>
										<?php } ?>
										<input type="text" class="form-control d-none" name="cheqBankid" id="cheqBankid" value="">
									</select>

									<script type="text/javascript">

										$('#SelectBank').change(function(){
											$('#cheqBankid').val($('#SelectBank').val());
											// var queryDep = $('#cheqBankid').val();
										});

									</script>
								</div>
							</div>

							<input type="text" class="form-control d-none" name="paymby" id="paymby" value="2">

							<script type="text/javascript">

								$(document).ready(function(){
									$('input[type=radio][name=paymbySel]').click(function() {
										if (this.value == '2') {
											// document.getElementById("detailCheque").style.display = "block";
											document.getElementById("paymby").value = '2';
										} else if (this.value == '1') {
											// document.getElementById("detailCheque").style.display = "none";
											document.getElementById("paymby").value = '1';
										}
									});
								});

								$('#SelectBank').change(function(){
									$('#chequeBankid').val($('#SelectBank').val());
									var queryDep = $('#chequeBankid').val();
								});

							</script>

						</div>
					</div>

					<div class="col-lg-12 col-md-12 pt-1 pb-3">
						<label for="searchCompany" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทในเครือ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div>
							<input type="text" name="searchCompany" id="searchCompany" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?= $obj_row_paym["comp_name"]; ?>" readonly>

							<input type="text" class="form-control d-none" id="compid" name="compid" value="<?= $obj_row_paym["comp_id"]; ?>">
						</div>
					</div>

					<div class="col-lg-12 col-md-12 pt-1 pb-3">
						<label for="searchPayable" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทผู้ให้บริการ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div>
							<input type="text" name="searchPayable" id="searchPayable" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัทผู้ให้บริการ" autocomplete="off" value="<?= $obj_row_paym["paya_name"]; ?>" readonly>

							<input type="text" class="form-control d-none" id="invpayaid" name="invpayaid" value="<?= $obj_row_paym["paya_id"]; ?>">
						</div>
					</div>

					<div class="col-lg-12 col-md-12 pt-1 pb-3">
						<div class="row">
							<div class="col-md-1 pb-0">
								<label for="invdesc" class="mb-1">ชำระค่า</label>
							</div>
							<div class="col-lg-11 col-md-12">
								<div class="row">
									<div class="col-md-12" style="border-bottom: 1px dotted #000;">
										<style type="text/css">
											span.invdesc { display: inline;}
											span.invdesc:after { content: " || "; }
											span.invdesc:last-child:before { content: " "; }
											span.invdesc:last-child:after { content: " "; }
										</style>
									<?php 
										if ($countChk > 1) {
											for ($i = 1; $i <= $countChk; $i++) {
												$invid = "invid" . $i;
												$invid = $_GET["$invid"];

												$str_sql_desc = "SELECT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id WHERE inv_id = '" . $invid . "'";
												$obj_rs_desc = mysqli_query($obj_con, $str_sql_desc);
												$obj_row_desc = mysqli_fetch_array($obj_rs_desc);
												$invdesc = $obj_row_desc["inv_description"];
									?>
												<?= $invdesc; ?> 
												&nbsp;
												<span class="invdesc"></span>
												&nbsp;

									<?php 	
											} 
										} else { 
									?>
										
											<?= $invdesc; ?>

									<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-9 col-md-9 pt-1 pb-3"></div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="invnet" class="mb-1">ยอดชำระสุทธิ</label>
						<div class="input-group">

							

							<input type="text" class="form-control text-right" name="invnet" id="invnet" value="<?= number_format($invnetamount,2); ?>" readonly>
							<div class="input-group-append">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
						</div>
					</div>

					<div class="col-lg-12 col-md-12 pt-1 pb-3">
						<label for="invnetText" class="mb-1">ตัวอักษร</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
							<input type="text" name="invnetText" id="invnetText" class="form-control" value="<?= Convert($invnetamount); ?>" readonly>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1 d-none" style="background-color: #FFFFFF;">
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="cheqfile" class="mb-1">File</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="cheqfile" id="cheqfile" value="" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="chequseridcreate" class="mb-1">User ID Create</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="chequseridcreate" id="chequseridcreate" value="<?=$obj_row_user["user_id"];?>" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="cheqcreatedate" class="mb-1">Create Date</label>
						<div class="input-group mb-2">
							<input type="date" class="form-control" name="cheqcreatedate" id="cheqcreatedate" value="" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="chequseridedit" class="mb-1">User ID Edit</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="chequseridedit" id="chequseridedit" value="" readonly>
						</div>
					</div>

					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="cheqeditdate" class="mb-1">Edit Date</label>
						<div class="input-group mb-2">
							<input type="date" class="form-control" name="cheqeditdate" id="cheqeditdate" value="" readonly>
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

	<script type="text/javascript">
		$(document).ready(function() {

// 			$("#btnInsert").click(function() {
// 				var formData = new FormData(this.form);
// 				if ($("#cheqNo").val() == '') {
// 					swal({
// 						title: "กรุณากรอกเลขที่เช็ค",
// 						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
// 						type: "warning"
// 					}).then(function() {
// 						frmAddCheque.cheqNo.focus();
// 					});
// 				} else if ($("#cheqDate").val() == '') {
// 					swal({
// 						title: "กรุณากรอกวันที่เช็ค",
// 						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
// 						type: "warning"
// 					}).then(function() {
// 						frmAddCheque.cheqDate.focus();
// 					});
// 				} else if ($("#cheqBankid").val() == '') {
// 					swal({
// 						title: "กรุณาเลือกธนาคาร",
// 						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
// 						type: "warning"
// 					}).then(function() {
// 						frmAddCheque.cheqBankid.focus();
// 					});
// 				} else {
// 					$.ajax({
// 						type: "POST",
// 						url: "r_cheque_add.php",
// 						// data: $("#frmAddCheque").serialize(),
// 						data: formData,
// 						dataType: 'json',
// 						contentType: false,
// 						cache: false,
// 						processData:false,
// 						success: function(result) {
// 							if(result.status == 1) {
// 								window.location.href = "cheque_preview.php?cid=" + result.compid + "&dep=" + result.depid + "&paymid=" + result.paymid + "&cheqid=" + result.chequeID;
// 							} 
// 							else if(result.status == 'kbank') {
// 								window.location.href = "cheque_kbank_preview.php?cid=" + result.compid + "&dep=" + result.depid + "&paymid=" + result.paymid + "&cheqid=" + result.chequeID;
// 							} 
// 							else if(result.status == 'bbl') {
// 								window.location.href = "cheque_bbl_preview.php?cid=" + result.compid + "&dep=" + result.depid + "&paymid=" + result.paymid + "&cheqid=" + result.chequeID;
// 							} 						
// 							else if(result.status == 2) {
// 								swal({
// 									title: "เลขที่เช็คซ้ำ กรุณากรอกใหม่",
// 									text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
// 									type: "warning",
// 									closeOnClickOutside: false
// 								});
// 							} else {
// 								alert("Cheq : " + result.messageCheq + "\nPaym : " + result.messagePaym);
// 							}
// 						}
// 					});
// 				}
// 			});


			$(".btn-action").click(function() {
				var formData = new FormData(document.getElementById("frmAddCheque"));
    				if ($("#cheqNo").val() == '') {
    					swal({
    						title: "กรุณากรอกเลขที่เช็ค",
    						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
    						type: "warning"
    					}).then(function() {
    						frmAddCheque.cheqNo.focus();
    					});
    				} else if ($("#cheqDate").val() == '') {
    					swal({
    						title: "กรุณากรอกวันที่เช็ค",
    						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
    						type: "warning"
    					}).then(function() {
    						frmAddCheque.cheqDate.focus();
    					});
    				} else if ($("#cheqBankid").val() == '') {
    					swal({
    						title: "กรุณาเลือกธนาคาร",
    						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
    						type: "warning"
    					}).then(function() {
    						frmAddCheque.cheqBankid.focus();
    					});
    				} else {
						if($(this).hasClass("btn-action-save")){
							$.ajax({
								type: "POST",
								url: "r_cheque_add.php",
								data: formData,
								dataType: 'json',
								contentType: false,
								cache: false,
								processData:false,
								success: function(result) {
									if(result.status == 1) {
										swal({
											title: "บันทึกข้อมูลสำเร็จ",
											text: "เลขที่เช็ค " + result.message,
											type: "success",
											closeOnClickOutside: false
										},function() {
											window.location.href ="cheque_seepdf.php?cid="+ result.compid +"&dep=" + result.depid
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
								url: "cheque_preview_pdf.php",
								data: formData,
								dataType: 'json',
								contentType: false,
								cache: false,
								processData:false,
								success:function(data){
								    console.log(data)
									var preview_url = data.preview_url;
									var preview_path = data.preview_path;
									var content = '<object data="'+preview_url+'#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="500" style="border: 1px solid"></object>';
									div.html(content);
										
									setTimeout(function() {
										$.ajax({
											url: "cheque_preview_pdf.php?tmp="+preview_path
										}); 
									}, 500);
					
								}
							}); 
						}
					}
			});	

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>