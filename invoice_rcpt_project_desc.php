<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$projid = $_GET["projid"];

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql = "SELECT * FROM project_tb WHERE proj_id = '". $projid ."'";
		$obj_rs = mysqli_query($obj_con, $str_sql);
		$obj_row = mysqli_fetch_array($obj_rs);

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<link rel="stylesheet" type="text/css" href="css/checkbox.css">

	<style type="text/css">
		.table .thead-light th {
			color: #000;
		}
		tr:nth-last-child(n) {
			border-bottom: 1px solid #dee2e6;
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
							<i class="icofont-document-folder"></i>&nbsp;&nbsp;<?=$obj_row["proj_name"];?>
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
						<input type="text" class="form-control" name="projid" id="projid" value="<?=$projid;?>">
					</div>

					<div class="col-md-10 text-right"></div>
					<div class="col-md-2 text-right">
						<a href="invoice_rcpt_project_desc_print.php?cid=<?=$cid;?>&dep=<?=$dep;?>&projid=<?=$projid;?>" target="_blank" type="button" class="btn btn-primary px-5">
							<i class="icofont-print"></i>&nbsp;Print
						</a>
					</div>

					<div class="col-md-12">
						<div class="row">
							<div class="col-auto">
								<label class="mt-1">ค้นหาโดย : </label>
							</div>
							<div class="col-md-2 mb-0">
								<div class="checkbox">
									<input type="radio" name="SearchBy" id="invrcptLesson" value="invrcptD_lesson" checked="checked">
									<label for="invrcptLesson"><span>งวดที่</span></label>
								</div>
							</div>
							<div class="col-md-2 mb-0">
								<div class="checkbox">
									<input type="radio" name="SearchBy" id="RunNumber" value="invrcptD_id">
									<label for="RunNumber"><span>Run Number</span></label>
								</div>
							</div>
							<div class="col-md-2 mb-0"></div>
							<div class="col-md-12 mb-0 d-none">
								<input type="text" class="form-control" name="SearchVal" id="SearchVal" value="invrcptD_lesson">
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="input-group">
							<input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกงวดที่ต้องการค้นหา" autocomplete="off">
							<div class="input-group-append">
								<a href="invoice_rcpt_project_desc_add.php?cid=<?=$cid;?>&dep=<?=$dep;?>&projid=<?=$projid;?>" class="btn btn-primary float-right">
									<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มรายละเอียด
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 d-none">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="thead-light">
									<tr>
										<th width="10%">งวด</th>
										<th width="45%">รายละเอียด</th>
										<th width="15%" class="text-center">จำนวนเงิน</th>
										<th width="15%" class="text-center">สถานะ</th>
										<th width="15%"></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$str_sql_desc = "SELECT * FROM invoice_rcpt_desc_tb AS ird INNER JOIN project_tb AS p ON ird.invrcptD_projid = p.proj_id INNER JOIN customer_tb AS c ON ird.invrcptD_custid = c.cust_id WHERE proj_compid = '$cid' AND proj_depid = '". $dep ."' AND invrcptD_projid = '". $projid ."'";
										$obj_rs_desc = mysqli_query($obj_con, $str_sql_desc);
										$i = 1;
										while ($obj_row_desc = mysqli_fetch_array($obj_rs_desc)) {
									?>
									<tr>
										<td>
											<?=$obj_row_desc["invrcptD_id"];?>
										</td>
										<td>
											<input type="text" class="form-control" name="irDid[]" id="irDid<?=$obj_row_desc["irDid"];?>" value="<?=$obj_row_desc["irDid"];?>">
										</td>
										<td>
											<input type="text" class="form-control" name="invrcptDDB<?=$i;?>" id="invrcptDDB<?=$obj_row_desc["irDid"];?>" value="<?=$obj_row_desc["invrcptD_status"];?>">
										</td>
										<td></td>
										<td></td>
									</tr>
									<?php $i++; } ?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="col-md-12">
						<?php // if($obj_row["proj_part"] == 1) { ?>
						<div class="table-responsive" id="ProjectTable"></div>
						<?php // } else { ?>
						<!-- <div class="table-responsive" id="ProjectPartTable"></div> -->
						<?php // } ?>
					</div>

					<div class="col-md-12 text-center pb-4">
						<input type="button" class="btn btn-success px-5 py-2" name="btnNext" id="btnNext" value="ถัดไป">
					</div>
				</div>


			</form>

			
		</div>
	</section>

	<!-- START VIEW -->
	<div id="dataInvoice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dataInvoiceLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">รายละเอียดงวดแจ้งหนี้</h3>
					<button type="button" class="close" name="invid_cancel" id="invid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body" id="invoice_detail">
				</div>
			</div>
		</div>
	</div>
	<!-- END VIEW -->

	<script type="text/javascript">
		$(document).ready(function() {

			//------ START DELETE INVOICE ------//
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
					closeOnCancel: false
				},
				function(isConfirm) {
					if (isConfirm) {
						$.ajax({
							url: "r_invoice_rcpt_project_desc_delete.php",
							type: "POST",
							data: {del_id:del_id},
							success: function () {
								setTimeout(function () {
									swal({
										title: "ลบข้อมูลสำเร็จ!",
										text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
										type: 'success'
									// }).then(function() {
									// 	frminvoiceRe.invoiceRevenueTable.focus();
									// 	location.reload();
									});
									$(element).closest('tr').fadeOut(800,function(){
										$(this).remove();
									});
								}, 1000);
							}
						});
					} else {
						swal({
							title: "ยกเลิก",
							text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
							type: 'error'
						});
					}
				});
			});
			//------ END DELETE INVOICE ------//

			// $("#btnPrint").click(function() {
			// 	var formData = new FormData(this.form);
			// 	$.ajax({
			// 		type: "POST",
			// 		url: "r_invoice_rcpt_project_desc_print.php",
			// 		// data: $("#frmPaymentPreview").serialize(),
			// 		data: formData,
			// 		dataType: 'json',
			// 		contentType: false,
			// 		cache: false,
			// 		processData:false,
			// 		success: function(result) {
			// 			if(result.status == 1) {
			// 				// swal({
			// 				// 	title: "บันทึกข้อมูลสำเร็จ",
			// 				// 	text: "เลขที่ใบแจ้งหนี้ " + result.message,
			// 				// 	type: "success",
			// 				// 	closeOnClickOutside: false
			// 				// },function() {
			// 					window.location.href = result.url;
			// 				// });
			// 			} else {
			// 				alert(result.message);
			// 			}
			// 		}
			// 	});
			// });


			//------ START NEXT ------//
			$("#btnNext").click(function() {
				var formData = new FormData(this.form);
				if($('#CountChkAll').val() == '0') {
					swal({
						title: "กรุณาเลือกรายการที่ต้องการออกใบแจ้งหนี้ \n อย่างน้อย 1 รายการ",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning"
					}).then(function() {
						formProject.ProjectTable.focus();
					});
				} else {
					$.ajax({
						type: "POST",
						url: "r_invoice_rcpt_project_desc.php",
						// data: $("#formProject").serialize(),
						data: formData,
						dataType: 'json',
						contentType: false,
						cache: false,
						processData:false,
						success: function(result) {
							if(result.status == 1) {
								window.location.href = result.url;
							} else if(result.status == 3) {
								swal({
									title: "รายการที่เลือก บริษัทไม่ตรงกัน\nกรุณาเลือกใหม่",
									text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
									type: "warning",
									closeOnClickOutside: false
								}).then(function() {
									formProject.ProjectTable.focus();
								});
							} else if(result.status == 2) {
								alert(result.message);
							}
						}
					});
				}
			});
			//------ END NEXT ------//


			load_data(1);
			function load_data(page, query = '', queryComp = '', queryDep = '', queryProj = '', querySearch = '') {
				var queryProj = $('#projid').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var querySearch = $('#SearchVal').val();
				$.ajax({
					url:"fetch_invoice_rcpt_project_desc.php",
					method:"POST",
					data:{page:page, query:query, queryComp:queryComp, queryDep:queryDep, queryProj:queryProj, querySearch:querySearch},
					success:function(data) {
						$('#ProjectTable').html(data);
					}
				});				
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryProj = $('#projid').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var querySearch = $('#SearchVal').val();
				load_data(page, query, queryComp, queryDep, queryProj, querySearch);
			});

			$('#search_box').keyup(function(){
				var query = $('#search_box').val();
				var queryProj = $('#projid').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var querySearch = $('#SearchVal').val();
				load_data(1, query, queryComp, queryDep, queryProj, querySearch);
			});

			$("input[name='SearchBy']").click(function(){
				$('#SearchVal').val($("input[name='SearchBy']:checked").val());
				var query = $('#search_box').val();
				var queryProj = $('#projid').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var querySearch = $('#SearchVal').val();
				load_data(1, query, queryComp, queryDep, queryProj, querySearch);
			});


			// load_dataPart(1);
			// function load_dataPart(page, query = '', queryComp = '', queryDep = '', queryProj = '') {
			// 	var queryProj = $('#projid').val();
			// 	var queryComp = $('#compid').val();
			// 	var queryDep = $('#depid').val();
			// 	$.ajax({
			// 		url:"fetch_invoice_rcpt_projectPart.php",
			// 		method:"POST",
			// 		data:{page:page, query:query, queryComp:queryComp, queryDep:queryDep, queryProj:queryProj},
			// 		success:function(data) {
			// 			$('#ProjectPartTable').html(data);
			// 		}
			// 	});				
			// }

			// $(document).on('click', '.page-link', function() {
			// 	var page = $(this).data('page_number');
			// 	var query = $('#search_box').val();
			// 	var queryProj = $('#projid').val();
			// 	var queryComp = $('#compid').val();
			// 	var queryDep = $('#depid').val();
			// 	load_dataPart(page, query, queryComp, queryDep, queryProj);
			// });

			// $('#search_box').keyup(function(){
			// 	var query = $('#search_box').val();
			// 	var queryProj = $('#projid').val();
			// 	var queryComp = $('#compid').val();
			// 	var queryDep = $('#depid').val();
			// 	load_dataPart(1, query, queryComp, queryDep, queryProj);
			// });


			//------ START VIEW ------//
			$(document).on('click', '.view_data', function(){
				var id = $(this).attr("id");
				if(id != '') {
					$.ajax({
						url:"v_invoice_rcpt_desc.php",
						method:"POST",
						data:{id:id},
						success:function(data){
							$('#invoice_detail').html(data);
							$('#dataView').modal('show');
						}
					});
				}
			});
			//------ END VIEW ------//

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>