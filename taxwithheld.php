<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];

		$str_sql_user = "SELECT * FROM user_tb AS u INNER JOIN level_tb AS l ON u.user_levid = l.lev_id INNER JOIN department_tb AS d ON u.user_depid = d.dep_id WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<style type="text/css">
		.table .thead-light th {
			color: #000;
		}
		.table td {
			vertical-align: middle;
		}
		tr:nth-last-child(n) {
			border-bottom: 1px solid #dee2e6;
		}
		th>.truncate, td>.truncate{
			width: auto;
			min-width: 0;
			max-width: 350px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		th>.truncate-address, td>.truncate-address{
			width: auto;
			min-width: 0;
			max-width: 550px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		ul.pagination li.disabled {
			cursor: not-allowed;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form name="formTaxwithheld" id="formTaxwithheld">
				<div class="row py-4 px-1" style="background-color: #e9ecef">
					<div class="col-md-12 pb-4">
						<h3 class="mb-0">
							<i class="icofont-company"></i>&nbsp;&nbsp;บริษัทหักภาษี ณ ที่จ่าย
						</h3>
					</div>
					<div class="col-md-12">
						<div class="input-group">
							<input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกชื่อ-นามสกุล / ชื่อบริษัทหักภาษี ณ ที่จ่าย" autocomplete="off" />
								
							<div class="input-group-append">
								<button type="button" name="addTaxwithheld" id="addTaxwithheld" class="btn btn-primary" data-toggle="modal" data-target="#add_data_Taxwithheld" data-controls-modal="add_data_Taxwithheld" data-backdrop="static" data-keyboard="false">
									<i class="icofont-plus-circle"></i>&nbsp;&nbsp;บริษัทหักภาษี ณ ที่จ่าย
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="table-responsive" id="taxwithheld_table"></div>
					</div>
				</div>

			</form>

		</div>
	</section>

	<!-- ADD-EDIT COMPANY -->
	<div id="add_data_Taxwithheld" class="modal fade">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">

				<form method="POST" id="insert_form" name="insert_form">

					<div class="modal-header">
						<h3 class="modal-title py-2">
							รายละเอียดบริษัท
						</h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
					</div>
					
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6 col-sm-12 pt-1 pb-2">
								<label for="twhid" class="mb-1">รหัส</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-numbered"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="twhid" id="twhid" placeholder="กรุณาเว้นว่างไว้เพื่อสร้างอัตโนมัติ" value="" readonly>
								</div>
							</div>

							<div class="col-md-12 col-sm-12 pt-1 pb-2">
								<label for="twhname" class="mb-1">ชื่อ นามสกุล/ชื่อบริษัท</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-building"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="twhname" id="twhname" placeholder="กรอกชื่อบริษัท" autocomplete="off">
								</div>
							</div>

							<div class="col-md-12 col-sm-12 pt-1 pb-2">
								<label for="twhaddress" class="mb-1">ที่อยู่บริษัท</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-location-pin"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="twhaddress" id="twhaddress" placeholder="กรอกที่อยู่บริษัท" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 pt-1 pb-2">
								<label for="twhtaxno" class="mb-1">เลขประจำตัวผู้เสียภาษี</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-id"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="twhtaxno" id="twhtaxno" placeholder="กรอกเลขประจำตัวผู้เสียภาษีบริษัท" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 pt-1 pb-2"></div>

							<div class="col-md-6 col-sm-12 pt-1 pb-2">
								<label for="twhtel" class="mb-1">เบอร์โทรศัพท์</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-phone"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="twhtel" id="twhtel" placeholder="กรอกเบอร์โทรศัพท์บริษัท (ถ้ามี)" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 pt-1 pb-2">
								<label for="twhfax" class="mb-1">เบอร์โทรสาร</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-fax"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="twhfax" id="twhfax" placeholder="กรอกเบอร์โทรสารบริษัท (ถ้ามี)" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 pt-1 pb-2">
								<label for="twhemail" class="mb-1">อีเมล</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-ui-email"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="twhemail" id="twhemail" placeholder="กรอกอีเมลบริษัท (ถ้ามี)" autocomplete="off">
								</div>
							</div>

							<div class="col-md-6 col-sm-12 pt-1 pb-2">
								<label for="twhwebsite" class="mb-1">เว็บไซต์</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-web"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="twhwebsite" id="twhwebsite" placeholder="กรอกเว็บไซต์บริษัท (ถ้ามี)" autocomplete="off">
								</div>
							</div>

						</div>
					</div>

					<div class="modal-footer">
						<input type="hidden" name="twhid_edit" id="twhid_edit" value="" />
						<input type="submit" class="btn btn-success px-4 py-2" name="insert" id="insert" value="บันทึก" />
						<input type="button" class="btn btn-danger px-4 py-2" name="twhid_cancel" id="twhid_cancel" value="ยกเลิก" data-dismiss="modal">
					</div>

				</form>
			</div>
		</div>
	</div>
	<!-- END ADD-EDIT COMPANY -->


	<!-- VIEW -->
	<div id="dataTaxwithheld" class="modal fade">
		<div class="modal-dialog modal-xl modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">รายละเอียดบริษัท</h3>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="taxwithheld_detail"></div>
			</div>
		</div>
	</div>
	<!-- END VIEW -->

	<script type="text/javascript">

		$(document).ready(function(){
			
			load_data(1);
			function load_data(page, query = '') {
				$.ajax({
					url:"fetch_taxwithheld.php",
					method:"POST",
					data:{page:page, query:query},
					success:function(data) {
						$('#taxwithheld_table').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function(){
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				load_data(page, query);
			});

			$('#search_box').keyup(function(){
				var query = $('#search_box').val();
				load_data(1, query);
			});
			

			$("#add_data_Taxwithheld").on('shown.bs.modal', function() {
				$(this).find('#twhname').focus();
			});

			$('#addTaxwithheld').click(function(){
				$('#insert').val("บันทึก");
				$('#insert_form')[0].reset();
			});

			$(document).on('click', '#twhid_cancel', function(){
				document.getElementById("insert_form").reset();
			});

			//--- ADD ---//
			$('#insert_form').on("submit", function(event) {
				event.preventDefault();
				var twh_id = $(this).attr("id");
				if($('#twhname').val() == '') {
					swal({
						title: "กรุณากรอกชื่อบริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insert_form.twhname.focus();
					});
				} else if($('#twhaddress').val() == '') {
					swal({
						title: "กรุณากรอกที่อยู่บริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insert_form.twhaddress.focus();
					});
				} else if($('#twhtaxno').val() == '') {
					swal({
						title: "กรุณากรอกเลขประจำตัวผู้เสียภาษี",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insert_form.twhtaxno.focus();
					});
				} else {
					$.ajax({
						url:"r_taxwithheld_add.php",
						type:"POST",
						data:$('#insert_form').serialize(),
						dataType:"Text",
						beforeSend:function() {
							$('#inset').val("Inserting");
						},
						success:function(data){
							$('#insert_form')[0].reset();
							$('#add_data_Taxwithheld').modal('hide');
							$('#taxwithheld_table').html(data);
							$(".modal-backdrop").remove();
							swal({
								title: "บันทึกข้อมูลสำเร็จ",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "success",
								closeOnClickOutside: false
								// showConfirmButton: false
								// timer: 3000
							}).then(function() {
								formTaxwithheld.taxwithheld_table.focus();
								location.reload();
							});
						}
					});
				}
			});
			//--- END ADD ---//

			//--- EDIT ---//
			$(document).on('click', '.edit_data', function(){
				var twh_id = $(this).attr("id");
				$.ajax({
					url:"r_taxwithheld_edit.php",
					method:"POST",
					data:{twh_id:twh_id},
					dataType:"json",
					success:function(data){
						$('#twhid').val(data.twh_id);
						$('#twhname').val(data.twh_name);
						$('#twhaddress').val(data.twh_address);
						$('#twhtaxno').val(data.twh_taxno);
						$('#twhtel').val(data.twh_tel);
						$('#twhid_edit').val(data.twh_id);
						$('#txtcompid').val(data.twh_id);
						$('#insert').val("บันทึก");
						$('#add_data_Taxwithheld').modal('show');
					}
				});
			});
			//--- END EDIT ---//

			//-- VIEW --//
			$(document).on('click', '.view_data', function(){
				var twh_id = $(this).attr("id");
				if(twh_id != '') {
					$.ajax({
						url:"v_taxwithheld.php",
						method:"POST",
						data:{twh_id:twh_id},
						success:function(data){
							$('#taxwithheld_detail').html(data);
							$('#dataTaxwithheld').modal('show');
						}
					});
				}
			});
			// END VIEW --//

			//--- DELECT ---//
			$(document).on('click', '.delete_data', function(){
				event.preventDefault();
				var del_id = $(this).attr("id");
				var element = this;
				swal({
					title: "ลบรายการนี้หรือไม่?",
					text: "เมื่อรายการนี้ถูกลบ คุณไม่สามารถกู้คืนได้!",
					type: "warning",
					showCancelButton: true,
					confirmButtonText: "ตกลง",
					cancelButtonText: "ยกเลิก",
					confirmButtonClass: 'btn btn-danger',
					cancelButtonClass: 'btn btn-secondary',
					closeOnConfirm: false,
					closeOnCancel: false,
					dangerMode: true,
				},
				function(isConfirm) {
					if (isConfirm) {
						$.ajax({
							url: "r_taxwithheld_delete.php",
							type: "POST",
							data: {del_id:del_id},
							success: function () {
								setTimeout(function () {
									swal("ลบข้อมูลสำเร็จ!", "กรุณากด ตกลง เพื่อดำเนินการต่อ", "success");
									$(element).closest('tr').fadeOut(800,function(){
										$(this).remove();
									});
								}, 1000);
							}
						});
					} else {
						swal("ยกเลิก", "กรุณากด ตกลง เพื่อดำเนินการต่อ", "error");
					}
				});
			});
			//--- END DELECT ---//

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>