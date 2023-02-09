<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
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
		tr:nth-last-child(n) {
			border-bottom: 1px solid #dee2e6;
		}
		th>.truncate-name, td>.truncate-name {
			width: auto;
			min-width: 0;
			max-width: 280px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		th>.truncate-address, td>.truncate-address {
			width: auto;
			min-width: 0;
			max-width: 400px;
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

			<form method="POST" name="frmProject" id="frmProject" action="">
				
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12 pb-4">
						<h3 class="mb-0">
							<i class="icofont-building-alt"></i>
							&nbsp;&nbsp;รายชื่อโครงการ
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
					</div>

					<div class="col-md-12">
						<div class="input-group">
							<input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกชื่อโครงการที่ต้องการค้นหา" autocomplete="off">
							<div class="input-group-append">
								<a href="project_add.php?cid=<?=$cid;?>&dep=<?=$dep;?>" class="btn btn-primary float-right">
									<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มโครงการ
								</a>

								<!-- <button type="button" name="addProject" id="addProject" class="btn btn-primary" data-toggle="modal" data-target="#AddDataProject" data-controls-modal="AddDataProject" data-backdrop="static" data-keyboard="false">
									<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มโครงการ
								</button> -->
							</div>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="table-responsive" id="ProjectTable"></div>
					</div>
				</div>

			</form>
			
		</div>
	</section>

	<!-- ADD-EDIT PROJECT -->
	<!-- <div id="AddDataProject" class="modal fade">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">

				<form method="POST" id="insertProject" name="insertProject">

					<div class="modal-header">
						<h3 class="modal-title py-2">
							รายละเอียดโครงการ
						</h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
					</div>
					
					<div class="modal-body">
						<div class="row">
							<div class="col-md-4">
								<label class="mb-1">รหัสโครงการ</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-building-alt"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="projid" id="projid" placeholder="เว้นว่างไว้เพื่อสร้างอัตโนมัติ" autocomplete="off" readonly>
								</div>
							</div>

							<div class="col-md-8">
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

							<div class="col-md-12">
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

							<div class="col-md-12">
								<div class="row">
									<div class="col-auto">
										<label class="mt-1">ค้นหาโดย : </label>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchINVBy" id="INVinvno" value="inv_no" checked="checked">
											<label for="INVinvno"><span>เลขที่ใบแจ้งหนี้</span></label>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<label class="mb-1">Consultant</label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-listine-dots"></i>
										</i>
									</div>
									<select class="custom-select form-control">
										<option>PM</option>
										<option>QS</option>
										<option>PM & QS</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<input type="text" class="form-control d-none" name="projidedit" id="projidedit" value="" />
						<input type="submit" class="btn btn-success px-4 py-2" name="insert" id="insert" value="บันทึก" />
						<input type="button" class="btn btn-danger px-4 py-2" name="projidcancel" id="projidcancel" value="ยกเลิก" data-dismiss="modal">
					</div>

				</form>
			</div>
		</div>
	</div> -->
	<!-- END ADD-EDIT PROJECT -->

	<!-- START VIEW -->
	<div id="dataProject" class="modal fade">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">รายละเอียดโครงการ</h3>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body" id="projrct_detail"></div>
			</div>
		</div>
	</div>
	<!-- END VIEW -->

	<script type="text/javascript">
		$(document).ready(function() {

			//---------- START INSERT ----------//
			// $('#insertProject').on("submit", function(event) {
			// 	event.preventDefault();
			// 	if($('#projname').val() == '') {
			// 		swal({
			// 			title: "กรุณากรอกชื่อโครงการ",
			// 			text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
			// 			type: "warning",
			// 			closeOnClickOutside: false
			// 		}).then(function() {
			// 			insertProject.projname.focus();
			// 		});
			// 	} else if($('#projaddress').val() == '') {
			// 		swal({
			// 			title: "กรุณากรอกที่อยู่โครงการ",
			// 			text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
			// 			type: "warning",
			// 			closeOnClickOutside: false
			// 		}).then(function() {
			// 			insertProject.projaddress.focus();
			// 		});
			// 	} else {
			// 		$.ajax({
			// 			url:"r_project_add.php",
			// 			type:"POST",
			// 			data:$('#insertProject').serialize(),
			// 			dataType:"Text",
			// 			success:function(data){
			// 				$('#insertProject')[0].reset();
			// 				$('#AddDataProject').modal('hide');
			// 				$('#ProjectTable').html(data);
			// 				$(".modal-backdrop").remove();
			// 				swal({
			// 					title: "บันทึกข้อมูลสำเร็จ",
			// 					text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
			// 					type: "success",
			// 					closeOnClickOutside: false
			// 					// showConfirmButton: false
			// 					// timer: 3000
			// 				}).then(function() {
			// 					frmProject.ProjectTable.focus();
			// 					location.reload();
			// 				});
			// 			}
			// 		});
			// 	}
			// });
			//---------- END INSERT ----------//


			//---------- START EDIT ----------//
			$(document).on('click', '.edit_data', function(){
				var proj_id = $(this).attr("id");
				$.ajax({
					url:"r_project_edit.php",
					method:"POST",
					data:{proj_id:proj_id},
					dataType:"json",
					success:function(data){
						$('#projid').val(data.proj_id);
						$('#projname').val(data.proj_name);
						$('#projaddress').val(data.proj_address);
						$('#projidedit').val(data.proj_id);
						$('#insert').val("บันทึก");
						$('#AddDataProject').modal('show');
					}
				});
			});
			//---------- END EDIT ----------//


			//---------- START DELETE ----------//
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
							url: "r_project_delete.php",
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
			//---------- END DELETE ----------//


			//---------- START VIEW ----------//
			$(document).on('click', '.view_data', function(){
				var id = $(this).attr("id");
				if(id != '') {
					$.ajax({
						url:"v_project.php",
						method:"POST",
						data:{id:id},
						success:function(data){
							$('#projrct_detail').html(data);
							$('#dataProject').modal('show');
						}
					});
				}
			});
				
			//---------- END VIEW ----------//


			load_data(1);
			function load_data(page, query = '', queryComp = '', queryDep = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				$.ajax({
					url:"fetch_project.php",
					method:"POST",
					data:{page:page, query:query, queryComp:queryComp, queryDep:queryDep},
					success:function(data) {
						$('#ProjectTable').html(data);
					}
				});				
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				load_data(page, query, queryComp, queryDep);
			});

			$('#search_box').keyup(function(){
				var query = $('#search_box').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				load_data(1, query, queryComp, queryDep);
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>