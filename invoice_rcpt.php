<?php
include 'config/config.php'; 
__check_login();
__page_seldep2('รายรับ <i class="icofont-caret-right"></i> ใบแจ้งหนี้ (รายรับ) <i class="icofont-caret-right"></i> เลือกฝ่าย');
?>
<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
	
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$projid = !empty($_GET["projid"]) ? $_GET["projid"] : "";

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
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
		th>.truncate, td>.truncate{
			width: auto;
			min-width: 0;
			max-width: 400px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		th>.truncate-id, td>.truncate-id{
			width: auto;
			min-width: 0;
			max-width: 180px;
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

			<form method="POST" name="frminvoiceRe" id="frminvoiceRe" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12 pb-4">
						<h3 class="mb-0">
							<i class="icofont-papers"></i>&nbsp;&nbsp;ใบแจ้งหนี้ - รายรับ
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
						<input type="text" class="form-control" name="projid" id="projid" value="<?=$projid;?>">
					</div>

					<?php if($cid == 'C001') { ?>
						<input type="text" class="form-control d-none" name="viewComp" id="viewComp" value="ACS">
					<?php } 
					else if($cid == 'C004') { ?>
						<input type="text" class="form-control d-none" name="viewComp" id="viewComp" value="ACSP">
					<?php } 
					else { ?>
						<input type="text" class="form-control d-none" name="viewComp" id="viewComp" value="<?=$obj_row["dep_name"];?>">
					<?php } ?>

					<div class="col-md-12">
						<div class="row">
							<div class="col-md-2">
								<label class="mt-1">ฝ่าย</label>
								<div class="input-group">
									<input type="text" class="form-control" name="depname" id="depname" value="<?=$obj_row["dep_name"];?>" readonly style="background-color: #FFF">
									<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$dep;?>">
								</div>
							</div>

							<div class="col-md-10">
								<div class="row">
									<div class="col-auto">
										<label class="mt-1">ค้นหาโดย : </label>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchINVBy" id="INVinvreno" value="CONCAT_WS('/',invrcpt_book,invrcpt_no)" checked="checked">
											<label for="INVinvreno"><span>เลขที่ใบแจ้งหนี้</span></label>
										</div>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchINVBy" id="INVcustname" value="cust_name">
											<label for="INVcustname"><span>ชื่อบริษัทผู้รับบริการ</span></label>
										</div>
									</div>
									<div class="col-md-3 mb-0"></div>
									<input type="text" class="form-control d-none" name="SearchINVVal" id="SearchINVVal" value="CONCAT_WS('/',invrcpt_book,invrcpt_no)">
								</div>

								<div class="input-group">
									<input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกเลขที่ใบแจ้งหนี้ที่ต้องการค้นหา" autocomplete="off">
									<div class="input-group-append">
										<a href="invoice_rcpt_add.php?cid=<?=$cid;?>&dep=<?=$dep;?>&projid=&irDid=" class="btn btn-primary float-right">
											<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มใบแจ้งหนี้
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="table-responsive" id="invoiceRevenueTable"></div>
					</div>
				</div>

			</form>
			
		</div>
	</section>

	<!-- START VIEW INVOICE -->
	<div id="dataInvoice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dataInvoiceLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">รายละเอียดใบแจ้งหนี้</h3>
					<button type="button" class="close" name="invid_cancel" id="invid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body" id="invoice_detail">
				</div>
			</div>
		</div>
	</div>
	<!-- END VIEW INVOICE -->
<div id="modalShowForm" class="modal fade modal-detail" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" >
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title py-2"><i class="icofont-save"></i> คุณต้องการบันทึกข้อมูลใช่หรือไม่?</h3>
            </div>
            <div class="modal-body" id="showForm">
         </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-close" data-dismiss="modal" aria-hidden="true"><i class="icofont-close"></i> ไม่บันทึกข้อมูล</button>
            <button type="button" class="btn btn-success  btn-action btn-action-save"><i class="icofont-save"></i> บันทึกข้อมูล</button>
         </div>
        </div>
    </div>
</div>



	<script type="text/javascript">
		$(document).ready(function() {

			//------ START INVOICE ------//
			load_data(1);
			function load_data(page, query = '', queryDep = '', queryComp = '', queryProj = '', querySearch = '') {
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryProj = $('#projid').val();
				var querySearch = $('#SearchINVVal').val();
				$.ajax({
					url:"fetch_invoice_rcpt.php",
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep, queryComp:queryComp, queryProj:queryProj, querySearch:querySearch},
					success:function(data) {
						$('#invoiceRevenueTable').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryProj = $('#projid').val();
				var querySearch = $('#SearchINVVal').val();
				load_data(page, query, queryDep, queryComp, queryProj, querySearch);
			});

			$('#search_box').keyup(function() {
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryProj = $('#projid').val();
				var querySearch = $('#SearchINVVal').val();
				load_data(1, query, queryDep, queryComp, queryProj, querySearch);
			});

			$("input[name='SearchINVBy']").click(function(){
				$('#SearchINVVal').val($("input[name='SearchINVBy']:checked").val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryProj = $('#projid').val();
				var querySearch = $('#SearchINVVal').val();
				load_data(1, query, queryDep, queryComp, queryProj, querySearch);
			});
			//------ END INVOICE ------//


			//------ START VIEW INVOICE ------//
			$(document).on('click', '.view_data', function(){
				var id = $(this).attr("id");
				var dep = document.getElementById("viewComp").value;
				if(id != '') {
					$.ajax({
						url:"v_invoice_rcpt_"+ dep +".php",
						method:"POST",
						data:{id:id},
						success:function(data){
							//console.log(data);
							$('#invoice_detail').html(data);
							$('#dataView').modal('show');
						}
					});
				}
			});
			//------ END VIEW INVOICE ------//
			
			$(document).on('click', '._view', function(){
                let id = $(this).attr("id");
                let proj_id = $(this).attr("data-type");
				var modalSave = $("#modalShowForm");
				var div = $("#showForm");
				
				div.html("<br><div align='center'>กรุณารอสักครู่...<br><br><i class='icofont-spinner icofont-spin' style='font-size:24px'></i></div>");
				modalSave.modal('show');
				
				$.ajax({
					type: 'POST',
					url: "./export/invoice_rcpt_pdf.php?irID="+id+"&preview=1",
					dataType: 'json',
					contentType: false,
					cache: false,
					processData:false,
					success:function(data){
						var preview_url = data.preview_url;
						var preview_path = data.preview_path;
						var content = '<object data="'+preview_url+'#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="500" style="border: 1px solid"></object>';
						div.html(content);
						div.append(`
						<div class="row py-2">
    						<div class="col-md-12">
    						    <div class="col-lg-12 px-1" style="border:2px dashed #d6d8db;margin-top:10px;padding:8px;font-size:13px">
    						        <div class="col-md-12">
    						            <h5><b><i class="icofont-ui-clock"></i> ประวัติการทำรายการ</b></h5>
    						            <div class="row mt-2 mb-3">
    						                <div class="col-md-12"><b><i class="icofont-ui-add"></i> จัดทำเอกสาร โดย : ${data.user_create} </b> &nbsp;&nbsp;<b> วันเวลา : ${data.date_create}</b> </div>
    						                <div class="col-sm-12"><b><i class="icofont-ui-edit"></i> แก้ไขเอกสาร โดย : ${data.user_edit != "" ? data.user_edit : "-"} </b></div>
    						            </div>
    						        </div>
    						     </div>
                            </div>
                        </div>
						`)
		
					}
				}); 
			});
			

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
							url: "r_invoice_rcpt_delete.php",
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


			$(document).on('click', '._cancel', function(e){
				let irID = $(this).attr("data-type");
				let invrcpt_no = $(this).attr("id");
				function onCancel(){
				    $.ajax({
						url: "r_invoice_rcpt_cancel.php",
						type: "POST",
						data: "compid=<?=$cid;?>&depid=<?=$dep;?>&irID="+irID+"&irno="+invrcpt_no,
						success: function () {  
							swal_success({title:"ยกเลิกข้อมูลสำเร็จ",text:"กรุณารอสักครู่..."});
							load_data(1)
						}
					});
				}
				
				swal_confirm({title:"ยกเลิกเลขที่ใบแจ้งหนี้หรือไม่",text: "เมื่อรายการนี้ถูกยกเลิก คุณไม่สามารถกู้คืนข้อมูลได้",fn:onCancel});
				
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>