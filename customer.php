<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];

		$str_sql_user = "SELECT * FROM user_tb AS u INNER JOIN level_tb AS l ON u.user_levid = l.lev_id INNER JOIN department_tb AS d ON u.user_depid = d.dep_id WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql_comp = "SELECT * FROM company_tb WHERE comp_id = '". $cid ."'";
		$obj_rs_comp = mysqli_query($obj_con, $str_sql_comp);
		$obj_row_comp = mysqli_fetch_array($obj_rs_comp);

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
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmCustomer" id="frmCustomer">
				<div class="row py-4 px-1" style="background-color: #e9ecef">
					<div class="col-md-12 pb-4">
						<h3 class="mb-0">
							<i class="icofont-company"></i>&nbsp;&nbsp;บริษัทผู้รับบริการ
						</h3>
					</div>
					<div class="col-md-12">
						<div class="input-group">
							<input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกชื่อ-นามสกุล / ชื่อบริษัทผู้รับบริการ" autocomplete="off" />
								
							<div class="input-group-append">
								<button type="button" name="addCustomer" id="addCustomer" class="btn btn-primary" data-toggle="modal" data-target="#addDataCustomer" data-controls-modal="addDataCustomer" data-backdrop="static" data-keyboard="false">
									<i class="icofont-plus-circle"></i>&nbsp;&nbsp;บริษัทผู้รับบริการ
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="table-responsive" id="customer_table"></div>
					</div>
				</div>
			</form>
			
		</div>
	</section>


	<!-- ADD-EDIT CUSTOMER -->
	<div id="addDataCustomer" class="modal fade">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">

				<form method="POST" id="insert_form" name="insert_form">

					<div class="modal-header">
						<h3 class="modal-title py-2">
							รายละเอียดบริษัทผู้รับบริการ
						</h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
					</div>
					
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6 col-sm-12 pt-1 pb-2">
								<label for="custid" class="mb-1">รหัส</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-numbered"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="custid" id="custid" placeholder="กรุณาเว้นว่างไว้เพื่อสร้างอัตโนมัติ" value="" readonly>
								</div>
							</div>

							<div class="col-md-12 col-sm-12 pt-1 pb-2">
								<label for="custname" class="mb-1">ชื่อ - นามสกุล/ชื่อบริษัทผู้รับบริการ</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-building"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="custname" id="custname" placeholder="กรอกชื่อ - นามสกุล/ชื่อบริษัทผู้รับบริการ" autocomplete="off">
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
						<input type="text" class="form-control d-none" name="custidEdit" id="custidEdit" value="">
						<input type="submit" class="btn btn-success px-4 py-2" name="insert" id="insert" value="บันทึก">
						<input type="button" class="btn btn-danger px-4 py-2" name="custidCancel" id="custidCancel" value="ยกเลิก" data-dismiss="modal">
					</div>

				</form>
			</div>
		</div>
	</div>
	<!-- END ADD-EDIT CUSTOMER -->


	<!-- VIEW -->
	<div id="dataCustomer" class="modal fade">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">รายละเอียดบริษัทผู้รับบริการ</h3>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="customer_detail"></div>
			</div>
		</div>
	</div>
	<!-- END VIEW -->

	<script type="text/javascript">
		$(document).ready(function(){
			
			load_data(1);
			function load_data(page, query = '') {
				$.ajax({
					url:"fetch_customer.php",
					method:"POST",
					data:{page:page, query:query},
					success:function(data) {
						$('#customer_table').html(data);
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
			

			$("#addDataCustomer").on('shown.bs.modal', function() {
				$(this).find('#custname').focus();
			});

			$('#addCustomer').click(function(){
				$('#insert').val("บันทึก");
				$('#insert_form')[0].reset();
			});

			$(document).on('click', '#custid_cancel', function(){
				document.getElementById("insert_form").reset();
			});

			//--- ADD ---//
			$('#insert_form').on("submit", function(event) {
				event.preventDefault();
				var comp_id = $(this).attr("id");
				if($('#custname').val() == '') {
					swal({
						title: "กรุณากรอกชื่อบริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insert_form.custname.focus();
					});
				} else if($('#custaddress').val() == '') {
					swal({
						title: "กรุณากรอกที่อยู่บริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insert_form.custaddress.focus();
					});
				} else if($('#custtaxno').val() == '') {
					swal({
						title: "กรุณากรอกเลขประจำตัวผู้เสียภาษี",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						insert_form.custtaxno.focus();
					});
				} else {
					$.ajax({
						url:"r_customer_add.php",
						type:"POST",
						data:$('#insert_form').serialize(),
						dataType:"Text",
						beforeSend:function() {
							$('#inset').val("Inserting");
						},
						success:function(data){
							$('#insert_form')[0].reset();
							$('#addDataCustomer').modal('hide');
							$('#customer_table').html(data);
							$(".modal-backdrop").remove();
							swal({
								title: "บันทึกข้อมูลสำเร็จ",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "success",
								closeOnClickOutside: false
								// showConfirmButton: false
								// timer: 3000
							}).then(function() {
								frmCustomer.customer_table.focus();
								location.reload();
							});
						}
					});
				}
			});
			//--- END ADD ---//

			//--- EDIT ---//
			$(document).on('click', '.edit_data', function(){
				var cust_id = $(this).attr("id");
				$.ajax({
					url:"r_customer_edit.php",
					method:"POST",
					data:{cust_id:cust_id},
					dataType:"json",
					success:function(data){
						$('#custid').val(data.cust_id);
						$('#custname').val(data.cust_name);
						$('#custaddress').val(data.cust_address);
						$('#custtaxno').val(data.cust_taxno);
						$('#custtel').val(data.cust_tel);
						$('#custidEdit').val(data.cust_id);
						$('#insert').val("บันทึก");
						$('#addDataCustomer').modal('show');
					}
				});
			});
			//--- END EDIT ---//

			//-- VIEW --//
			$(document).on('click', '.view_data', function(){
				var cust_id = $(this).attr("id");
				if(cust_id != '') {
					$.ajax({
						url:"v_customer.php",
						method:"POST",
						data:{cust_id:cust_id},
						success:function(data){
							$('#customer_detail').html(data);
							$('#dataCustomer').modal('show');
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
							url: "r_customer_delete.php",
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