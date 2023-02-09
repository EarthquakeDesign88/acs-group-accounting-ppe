<?php

	//session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		include 'connect.php';
		
?>
	<div id="AddProject" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="AddProject" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content" style="background-color: #f5f5f5;">
				<form method="POST" id="insertProj_form" name="insertProj_form">
					
					<script type="text/javascript">
						function putValueProj(name,id) {
							$('#searchProject').val(name);
							$('#invReprojid').val(id);
						}
					</script>
					
					<script type="text/javascript" src="js/script_project.js"></script>

					<div class="modal-header">
						<h3 class="modal-title py-2">
							รายละเอียดโครงการ
						</h3>
						<button type="button" class="close" name="projid_cancel" id="projid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>

						<input type="text" class="form-control d-none" name="compid" id="compid" value="<?=$cid;?>" readonly>
					</div>
					
					<div class="modal-body">
						<div class="row">
							<div class="col-md-4 col-sm-12 pt-1 pb-2">
								<label for="exampleInputEmail1" class="mb-1">รหัสบริษัท</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-numbered"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="projid" id="projid" placeholder="เว้นว่างไว้เพื่อสร้างอัตโนมัติ" readonly>
								</div>
							</div>

							<div class="col-md-3 col-sm-12 pt-1 pb-2">
								<label class="mb-1">ฝ่าย</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-building-alt"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="depname" id="depname" value="<?=$obj_row_dep["dep_name"];?>" readonly>
									<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$dep;?>" readonly>
								</div>
							</div>

							<div class="col-md-12 col-sm-12 pt-1 pb-2">
								<label class="mb-1">ชื่อโครงการ</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-building-alt"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="projname" id="projname" placeholder="กรอกชื่อโครงการ" autocomplete="off">
								</div>
							</div>

							<div class="col-md-12 col-sm-12 pt-1 pb-2">
								<label class="mb-1">ที่อยู่โครงการ</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-location-pin"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="projaddress" id="projaddress" placeholder="กรอกที่อยู่โครงการ" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 pt-1 pb-2">
								<label>มูลค่าสัญญา</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-baht"></i>
										</i>
									</div>
									<input type="text" class="form-control text-right" name="showValue" id="showValue" value="0.00">
									<input type="text" class="form-control text-right d-none" name="calValue" id="calValue" value="0.00">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 pt-1 pb-2">
								<label>จำนวนงวด</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-listing-number"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="projless" id="projless" autocomplete="off" placeholder="กรอกจำนวนงวดโครงการ">
								</div>
							</div>

						</div>
					</div>

					<div class="modal-footer">
						<input type="hidden" name="payaid_edit" id="payaid_edit" value="" />
						<input type="submit" name="payaid_insert" id="payaid_insert" value="บันทึก" class="btn btn-success">
						<input type="button" name="projid_cancel" id="projid_cancel" value="ยกเลิก" class="btn btn-danger" data-dismiss="modal">
					</div>

				</form>
			</div>
		</div>
	</div>


	<script type="text/javascript">

		$(document).ready(function(){
			
			$("#AddProject").on('shown.bs.modal', function() {
				$(this).find('#projname').focus();
			});

			$('#AddProject').on('hidden.bs.modal', function () {
				$('#insertProj_form')[0].reset();
			});

			$('#projid_cancel').click(function () {
				$("#insertProj_form").modal("hide");
			});

			//--- ADD-PAYABLE ---//
			$("#payaid_insert").click(function (event) {
				event.preventDefault();
				var form = $('#insertProj_form')[0];
				var data = new FormData(form);
				if($('#projname').val() == '') {
					swal({
						title: "กรุณากรอกชื่อบริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insertProj_form.projname.focus();
					});
				} else if($('#payaaddress').val() == '') {
					swal({
						title: "กรุณากรอกที่อยู่บริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insertProj_form.payaaddress.focus();
					});
				} else if($('#payataxno').val() == '') {
					swal({
						title: "กรุณากรอกเลขประจำตัวผู้เสียภาษี",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insertProj_form.payataxno.focus();
					});
				} else {
					$.ajax({
						type: "POST",
						url: "r_invoice_add_project.php",
						data: data,
						dataType: "text",
						processData: false,
						contentType: false,
						cache: false,
						// beforeSend:function() {
						// 	$('#payaid_insert').val("Inserting");
						// },
						success:function(data){
							$('#insertProj_form')[0].reset();
							$('#AddProject').modal('hide');
							$('#showDataProject').html(data);
							swal({
								title: "บันทึกข้อมูลสำเร็จ",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "success",
								closeOnClickOutside: false,
								showConfirmButton: true
							}).then(function() {
								frmAddInvoiceRe.showDataProject.focus();
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

		document.getElementById("showValue").onblur = function (){
			this.value = parseFloat(this.value.replace(/,/g, ""))
			.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			document.getElementById("calValue").value = this.value.replace(/,/g, "");
		}

	</script>


<?php } ?>