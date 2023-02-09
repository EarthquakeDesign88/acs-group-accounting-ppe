<?php

	//session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		include 'connect.php';
		
?>

	<div id="AddBankBranch" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="AddBankBranch" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content" style="background-color: #f5f5f5;">

				<form method="POST" id="insertBankBranch_form" name="insertBankBranch_form">

					<div class="modal-header">
						<h3 class="modal-title py-2">
							ธนาคาร - สาขา
						</h3>
						<button type="button" class="close" name="id_cancel" id="id_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
					</div>

					<div class="modal-body">
						<div class="row">
							<div class="col-md-12 col-sm-12 py-3" id="BankSelect">
								<label for="SelBankBrc" class="mb-1">ธนาคาร</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-bank-alt"></i>
										</i>
									</div>
									<select class="custom-select form-control" name="SelBankBrc" id="SelBankBrc">
										<option value="">กรุณาเลือกธนาคาร...</option>
										<?php
											$str_sql_b = "SELECT * FROM bank_tb ORDER BY bank_name ASC";
											$obj_rs_b = mysqli_query($obj_con, $str_sql_b);
											while ($obj_row_b = mysqli_fetch_array($obj_rs_b)) {
										?>
										<option value="<?=$obj_row_b["bank_id"];?>">
											<?=$obj_row_b["bank_name"];?>
										</option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="col-md-12 col-sm-12 py-3">
								<label for="branchname" class="mb-1">สาขา</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-bank-alt"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="branchname" id="branchname" autocomplete="off">
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<input type="submit" name="id_insert" id="id_insert" value="บันทึก" class="btn btn-success">
						<input type="button" name="id_cancel" id="id_cancel" value="ยกเลิก" class="btn btn-danger" data-dismiss="modal">
					</div>

				</form>

			</div>
		</div>
	</div>

	<?php include 'bank_add.php'; ?>

	<script type="text/javascript">
		$(document).ready(function() {

			$("#AddBankBranch").on('shown.bs.modal', function() {
				$(this).find('#SelBank').focus();
			});

			$('#AddBankBranch').on('hidden.bs.modal', function () {
				$('#insertBankBranch_form')[0].reset();
			});

			$('#id_cancel').click(function () {
				$("#insertBankBranch_form").modal("hide");
			});

		});

		$(document).ready(function(){
			$('#insertBankBranch_form').on("submit", function(event){
				event.preventDefault();
				if($('#SelBankBrc').val() == '') {
						swal({
						title: "กรุณากรอกชื่อธนาคาร",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insertBankBranch_form.SelBankBrc.focus();
					});
				} else if($('#branchname').val() == '') {
					swal({
						title: "กรุณากรอกชื่อสาขา",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insertBankBranch_form.branchname.focus();
					});
				} else {
					$.ajax({
						url:"r_branch_add.php",
						method:"POST",
						data:$('#insertBankBranch_form').serialize(),
						success:function(data) {
							$('#insertBankBranch_form')[0].reset();
							$('#AddBankBranch').modal('hide');
							$('#SelectBranch').html(data);
							swal({
								title: "บันทึกข้อมูลสำเร็จ",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "success",
								closeOnClickOutside: false,
								showConfirmButton: true
							}).then(function() {
								frmReceipt.SelectBranch.focus();
							});
						}
					});
				}
			});
		});

	</script>

<?php } ?>