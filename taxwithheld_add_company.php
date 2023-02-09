<?php

	//session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		include 'connect.php';

?>

	<div id="AddTaxwithheld" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="AddTaxwithheld" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content" style="background-color: #f5f5f5;">
				<form method="POST" id="insertTax_form" name="insertTax_form">
					
					<script type="text/javascript">
						function putValueTax(name,id) {
							$('#searchTaxwithheld').val(name);
							$('#twhid').val(id);
						}
					</script>
					
					<script type="text/javascript" src="js/script_taxwithheld.js"></script>

					<div class="modal-header">
						<h3 class="modal-title py-2">
							รายละเอียดบริษัทหักภาษี ณ ที่จ่าย
						</h3>
						<button type="button" class="close" name="taxid_cancel" id="taxid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
					</div>

					<div class="modal-body">
						<div class="row">
							<div class="col-md-3 col-sm-12 py-3" style="display: none;">
								<label for="txttaxid" class="mb-1">รหัสบริษัท</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-numbered"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="txttaxid" id="txttaxid" value="" readonly>
								</div>
							</div>

							<div class="col-md-12 col-sm-12 py-3">
								<label for="taxname" class="mb-1">ชื่อ นามสกุล/ชื่อบริษัท</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-building"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="taxname" id="taxname" placeholder="กรอกชื่อบริษัท" autocomplete="off">
								</div>
							</div>

							<div class="col-md-12 col-sm-12 py-3">
								<label for="taxaddress" class="mb-1">ที่อยู่บริษัท</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-location-pin"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="taxaddress" id="taxaddress" placeholder="กรอกที่อยู่บริษัท" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 py-3">
								<label for="taxtaxno" class="mb-1">เลขประจำตัวผู้เสียภาษี</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-id"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="taxtaxno" id="taxtaxno" placeholder="กรอกเลขประจำตัวผู้เสียภาษีบริษัท" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 py-3"></div>

							<div class="col-md-6 col-sm-12 py-3">
								<label for="taxtel" class="mb-1">เบอร์โทรศัพท์</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-phone"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="taxtel" id="taxtel" placeholder="กรอกเบอร์โทรศัพท์บริษัท (ถ้ามี)" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 py-3">
								<label for="taxfax" class="mb-1">เบอร์โทรสาร</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-fax"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="taxfax" id="taxfax" placeholder="กรอกเบอร์โทรสารบริษัท (ถ้ามี)" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 py-3">
								<label for="taxemail" class="mb-1">อีเมล</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-ui-email"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="taxemail" id="taxemail" placeholder="กรอกอีเมลบริษัท (ถ้ามี)" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 py-3">
								<label for="taxwebsite" class="mb-1">เว็บไซต์</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-web"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="taxwebsite" id="taxwebsite" placeholder="กรอกเว็บไซต์บริษัท (ถ้ามี)" autocomplete="off">
								</div>
							</div>

						</div>
					</div>

					<div class="modal-footer">
						<input type="hidden" name="taxid_edit" id="taxid_edit" value="" />
						<input type="submit" name="taxid_insert" id="taxid_insert" value="บันทึก" class="btn btn-success" />
						<input type="button" name="taxid_cancel" id="taxid_cancel" value="ยกเลิก" class="btn btn-danger" data-dismiss="modal">
					</div>

				</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">

		$(document).ready(function(){

			$("#AddTaxwithheld").on('shown.bs.modal', function() {
				$(this).find('#compname').focus();
				$("#insertTax_form")[0].reset();
			});

			//--- ADD-COMPANY ---//
			$("#taxid_insert").click(function (event) {
				event.preventDefault();
				var form = $('#insertTax_form')[0];
				var data = new FormData(form);
				if($('#taxname').val() == '') {
					swal({
						title: "กรุณากรอกชื่อบริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insertTax_form.taxname.focus();
					});
				} else if($('#taxaddress').val() == '') {
					swal({
						title: "กรุณากรอกที่อยู่บริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insertTax_form.taxaddress.focus();
					});
				} else if($('#taxtaxno').val() == '') {
					swal({
						title: "กรุณากรอกเลขประจำตัวผู้เสียภาษี",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insertTax_form.taxtaxno.focus();
					});
				} else {
					$.ajax({
						type: "POST",
						url: "r_taxwithheld_add_company.php",
						data: data,
						dataType: "text",
						processData: false,
						contentType: false,
						cache: false,
						success:function(data){
							$('#insertTax_form')[0].reset();
							$('#AddTaxwithheld').modal('hide');
							$('#showDataTWH').html(data);
							swal({
								title: "บันทึกข้อมูลสำเร็จ",
								text: "",
								type: "success",
								closeOnClickOutside: false,
								showConfirmButton: true
							}).then(function() {
								frmTaxcerPaymAdd.showDataTWH.focus();
							});
						}
					});
				}
			});
			//--- END ADD-COMPANY ---//

		});

	</script>

<?php } ?>