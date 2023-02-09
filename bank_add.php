<?php

	//session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		include 'connect.php';
		
?>
	<div id="AddBank" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="AddBank" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content" style="background-color: #f5f5f5;">

				<form method="POST" id="insertBank_form" name="insertBank_form">
					<div class="modal-header">
						<h3 class="modal-title py-2">
							ธนาคาร
						</h3>
						<button type="button" class="close" name="bankid_cancel" id="bankid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
					</div>

					<div class="modal-body">
						<div class="row">
							<div class="col-md-12 col-sm-12 py-3">
								<label for="bankname" class="mb-1">ชื่อธนาคาร</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-bank-alt"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="bankname" id="bankname" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 py-3">
								<label for="banknameShort" class="mb-1">ชื่อย่อธนาคาร</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-bank-alt"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="banknameShort" id="banknameShort" autocomplete="off">
								</div>
							</div>
						</div>						
					</div>

					<div class="modal-footer">
						<input type="submit" name="bankid_insert" id="bankid_insert" value="บันทึก" class="btn btn-success">
						<input type="button" name="bankid_cancel" id="bankid_cancel" value="ยกเลิก" class="btn btn-danger" data-dismiss="modal">
					</div>
				</form>

			</div>
		</div>		
	</div>

	<script type="text/javascript">
		$(document).ready(function() {

			$("#AddBank").on('shown.bs.modal', function() {
				$(this).find('#bankname').focus();
			});

			$('#AddBank').on('hidden.bs.modal', function () {
				$('#insertBank_form')[0].reset();
			});

			$('#bankid_cancel').click(function () {
				$("#insertBank_form").modal("hide");
			});

		});

		$(document).ready(function(){
			$('#insertBank_form').on("submit", function(event){
				event.preventDefault();
				if($('#bankname').val() == '') {
						swal({
						title: "กรุณากรอกชื่อธนาคาร",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insertBank_form.bankname.focus();
					});
				} else {
					$.ajax({
						url:"r_bank_add.php",
						method:"POST",
						data:$('#insertBank_form').serialize(),
						success:function(data) {
							$('#insertBank_form')[0].reset();
							$('#AddBank').modal('hide');
							$('#SelectBankBranch').html(data);
							swal({
								title: "บันทึกข้อมูลสำเร็จ",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "success",
								closeOnClickOutside: false,
								showConfirmButton: true
							}).then(function() {
								frmReceipt.SelectBankBranch.focus();
							});
						}
					});
				}
			});
		});
	</script>
	
<?php } ?>