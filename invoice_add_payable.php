<?php

	//session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		include 'connect.php';
		
?>
	<div id="AddPayable" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="AddPayable" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content" style="background-color: #f5f5f5;">
				<form method="POST" id="insertPaya_form" name="insertPaya_form">
					
					<script type="text/javascript">
						function putValuePaya(name,id) {
							$('#searchPayable').val(name);
							$('#invpayaid').val(id);
						}
					</script>
					
					<script type="text/javascript" src="js/script_payable.js"></script>

					<div class="modal-header">
						<h3 class="modal-title py-2">
							รายละเอียดบริษัทผู้ให้บริการ
						</h3>
						<button type="button" class="close" name="payaid_cancel" id="payaid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
					</div>
					
					<div class="modal-body">
						<div class="row">
							<div class="col-md-3 col-sm-12 py-3" style="display: none;">
								<label for="exampleInputEmail1" class="mb-1">รหัสบริษัท</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-numbered"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="txtpayaid" id="txtpayaid" value="" readonly>
								</div>
							</div>

							<div class="col-md-12 col-sm-12 py-3">
								<label for="exampleInputEmail1" class="mb-1">ชื่อ นามสกุล/ชื่อบริษัท</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-building"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="payaname" id="payaname" placeholder="กรอกชื่อบริษัท" autocomplete="off">
								</div>
							</div>

							<div class="col-md-12 col-sm-12 py-3">
								<label for="exampleInputEmail1" class="mb-1">ที่อยู่บริษัท</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-location-pin"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="payaaddress" id="payaaddress" placeholder="กรอกที่อยู่บริษัท" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 py-3">
								<label for="exampleInputEmail1" class="mb-1">เลขประจำตัวผู้เสียภาษี</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-id"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="payataxno" id="payataxno" placeholder="กรอกเลขประจำตัวผู้เสียภาษีบริษัท" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 py-3"></div>

							<div class="col-md-6 col-sm-12 py-3">
								<label for="exampleInputEmail1" class="mb-1">เบอร์โทรศัพท์</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-phone"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="payatel" id="payatel" placeholder="กรอกเบอร์โทรศัพท์บริษัท (ถ้ามี)" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 py-3">
								<label for="exampleInputEmail1" class="mb-1">เบอร์โทรสาร</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-fax"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="payafax" id="payafax" placeholder="กรอกเบอร์โทรสารบริษัท (ถ้ามี)" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 py-3">
								<label for="exampleInputEmail1" class="mb-1">อีเมล</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-ui-email"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="payaemail" id="payaemail" placeholder="กรอกอีเมลบริษัท (ถ้ามี)" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 py-3">
								<label for="exampleInputEmail1" class="mb-1">เว็บไซต์</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-web"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="payawebsite" id="payawebsite" placeholder="กรอกเว็บไซต์บริษัท (ถ้ามี)" autocomplete="off">
								</div>
							</div>

						</div>
					</div>

					<div class="modal-footer">
						<input type="hidden" name="payaid_edit" id="payaid_edit" value="" />
						<input type="submit" name="payaid_insert" id="payaid_insert" value="บันทึก" class="btn btn-success">
						<input type="button" name="payaid_cancel" id="payaid_cancel" value="ยกเลิก" class="btn btn-danger" data-dismiss="modal">
					</div>

				</form>
			</div>
		</div>
	</div>


	<script type="text/javascript">

		$(document).ready(function(){
			
			$("#AddPayable").on('shown.bs.modal', function() {
				$(this).find('#payaname').focus();
			});

			$('#AddPayable').on('hidden.bs.modal', function () {
				$('#insertPaya_form')[0].reset();
			});

			$('#payaid_cancel').click(function () {
				$("#insertPaya_form").modal("hide");
			});

			//--- ADD-PAYABLE ---//
			$("#payaid_insert").click(function (event) {
				event.preventDefault();
				var form = $('#insertPaya_form')[0];
				var data = new FormData(form);
				if($('#payaname').val() == '') {
					swal({
						title: "กรุณากรอกชื่อบริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insertPaya_form.payaname.focus();
					});
				} else if($('#payaaddress').val() == '') {
					swal({
						title: "กรุณากรอกที่อยู่บริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insertPaya_form.payaaddress.focus();
					});
				} else if($('#payataxno').val() == '') {
					swal({
						title: "กรุณากรอกเลขประจำตัวผู้เสียภาษี",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insertPaya_form.payataxno.focus();
					});
				} else {
					$.ajax({
						type: "POST",
						url: "r_invoice_add_payable.php",
						data: data,
						dataType: "text",
						processData: false,
						contentType: false,
						cache: false,
						// beforeSend:function() {
						// 	$('#payaid_insert').val("Inserting");
						// },
						success:function(data){
							$('#insertPaya_form')[0].reset();
							$('#AddPayable').modal('hide');
							$('#showDataPaya').html(data);
							swal({
								title: "บันทึกข้อมูลสำเร็จ",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "success",
								closeOnClickOutside: false,
								showConfirmButton: true
							}).then(function() {
								frmAddInvoice.showDataPaya.focus();
							});
							// setTimeout(function(){// wait for 5 secs(2)
							// 	location.reload(); // then reload the page.(3)
							// }, 2000); 
						}
					});
				}
			});
			//--- END ADD-PAYABLE ---//

		});

	</script>


<?php } ?>