<?php

	//session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		include 'connect.php';
		
?>

	<div id="AddCustomer" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="AddCustomer" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content" style="background-color: #f5f5f5;">
				<form method="POST" id="insertCust_form" name="insertCust_form">

					<script type="text/javascript">
						function putValueCust(name,id) {
							$('#searchCustomer').val(name);
							$('#custid').val(id);
						}
					</script>
					
					<script type="text/javascript" src="js/script_customer.js"></script>

					<div class="modal-header">
						<h3 class="modal-title py-2">
							รายละเอียดบริษัทผู้รับบริการ
						</h3>
						<button type="button" class="close" name="custid_cancel" id="custid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
					</div>

					<div class="modal-body">
						<div class="row">
							<div class="col-md-3 col-sm-12 pt-1 pb-2" style="display: none;">
								<label for="custid" class="mb-1">รหัสบริษัท</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-numbered"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="custid" id="custid" value="" readonly>
								</div>
							</div>

							<div class="col-md-12 col-sm-12 pt-1 pb-2">
								<label for="custname" class="mb-1">ชื่อ นามสกุล/ชื่อบริษัท</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-building"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="custname" id="custname" placeholder="กรอกชื่อบริษัท" autocomplete="off">
								</div>
							</div>

							<div class="col-md-12 col-sm-12 pt-1 pb-2">
								<label for="custaddress" class="mb-1">ที่อยู่บริษัทผู้รับบริการ</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-location-pin"></i>
										</i>
									</div>
									<textarea class="form-control" name="custaddress" id="custaddress" rows="2" placeholder="กรอกที่อยู่บริษัทผู้รับบริการ" autocomplete="off"></textarea>
								</div>
							</div>

							<div class="col-md-6 col-sm-12 pt-1 pb-2">
								<label for="custtaxno" class="mb-1">เลขประจำตัวผู้เสียภาษี</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-id"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="custtaxno" id="custtaxno" placeholder="กรอกเลขประจำตัวผู้เสียภาษี" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 pt-1 pb-2"></div>

							<div class="col-md-6 col-sm-12 pt-1 pb-2">
								<label for="custtel" class="mb-1">เบอร์โทรศัพท์</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-phone"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="custtel" id="custtel" placeholder="กรอกเบอร์โทรศัพท์ (ถ้ามี)" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 pt-1 pb-2">
								<label for="custfax" class="mb-1">เบอร์โทรสาร</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-fax"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="custfax" id="custfax" placeholder="กรอกเบอร์โทรสาร (ถ้ามี)" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 pt-1 pb-2">
								<label for="custemail" class="mb-1">อีเมล</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-ui-email"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="custemail" id="custemail" placeholder="กรอกอีเมล (ถ้ามี)" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 pt-1 pb-2">
								<label for="custwebsite" class="mb-1">เว็บไซต์</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-web"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="custwebsite" id="custwebsite" placeholder="กรอกเว็บไซต์ (ถ้ามี)" autocomplete="off">
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<input type="hidden" name="custid_edit" id="custid_edit" value="" />
						<input type="submit" name="custid_insert" id="custid_insert" value="บันทึก" class="btn btn-success">
						<input type="button" name="custid_cancel" id="custid_cancel" value="ยกเลิก" class="btn btn-danger" data-dismiss="modal">
					</div>

				</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			
			$("#AddCustomer").on('shown.bs.modal', function() {
				$(this).find('#custname').focus();
			});

			$('#AddCustomer').on('hidden.bs.modal', function () {
				$('#insertCust_form')[0].reset();
			});

			$('#custid_cancel').click(function () {
				$("#insertCust_form").modal("hide");
			});

			//--- ADD-PAYABLE ---//
			$("#custid_insert").click(function (event) {
				event.preventDefault();
				var form = $('#insertCust_form')[0];
				var data = new FormData(form);
				if($('#custname').val() == '') {
					swal({
						title: "กรุณากรอกชื่อบริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insertCust_form.custname.focus();
					});
				} else if($('#custaddress').val() == '') {
					swal({
						title: "กรุณากรอกที่อยู่บริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insertCust_form.custaddress.focus();
					});
				} else if($('#custtaxno').val() == '') {
					swal({
						title: "กรุณากรอกเลขประจำตัวผู้เสียภาษี",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insertCust_form.custtaxno.focus();
					});
				} else {
					$.ajax({
						type: "POST",
						url: "r_invoice_add_customer.php",
						data: data,
						dataType: "text",
						processData: false,
						contentType: false,
						cache: false,
						// beforeSend:function() {
						// 	$('#payaid_insert').val("Inserting");
						// },
						success:function(data){
							$('#insertCust_form')[0].reset();
							$('#AddCustomer').modal('hide');
							$('#showDataCust').html(data);
							swal({
								title: "บันทึกข้อมูลสำเร็จ",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "success",
								closeOnClickOutside: false,
								showConfirmButton: true
							}).then(function() {
								frmAddInvoiceRe.showDataCust.focus();
							});
						}
					});
				}
			});
			//--- END ADD-PAYABLE ---//

		});
	</script>

<?php } ?>