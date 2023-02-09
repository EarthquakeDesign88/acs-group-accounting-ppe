<?php
include 'config/config.php'; 
__check_login();
__page_seldep2('ขอซื้อ/ขอจ้าง <i class="icofont-caret-right"></i> เลือกฝ่าย');
?>
<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
	
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

			<form method="POST" name="" id="" action="">
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12 pb-4">
						<h3 class="mb-0">
							<i class="icofont-file-document"></i>&nbsp;&nbsp;ขอซื้อ/ขอจ้าง
						</h3>
					</div>

					<input type="text" class="form-control d-none" name="compid" id="compid" value="<?=$cid;?>">

					<div class="col-md-12">
						<div class="row">
							<div class="col-md-2">
								<label class="mt-1">ฝ่าย</label>
								<div class="input-group">
									<?php
										$str_sql = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
										$obj_rs = mysqli_query($obj_con, $str_sql);
										$obj_row = mysqli_fetch_array($obj_rs);
									?>
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
											<input type="radio" name="SearchPRBy" id="PRpurcno" value="purc_no" checked="checked">
											<label for="PRpurcno"><span>เลขที่ขอซื้อ/ขอจ้าง</span></label>
										</div>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchPRBy" id="PRpayaname" value="paya_name">
											<label for="PRpayaname"><span>ชื่อบริษัทผู้ให้บริการ</span></label>
										</div>
									</div>
									<input type="text" class="form-control d-none" name="SearchPurch" id="SearchPurch" value="purc_no">
								</div>

								<div class="input-group">
									<input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกเลขที่ขอซื้อ/ขอจ้างที่ต้องการค้นหา" autocomplete="off">
									<div class="input-group-append">
										<a href="purchaseReq_add.php?cid=<?=$cid;?>&dep=<?=$dep;?>" class="btn btn-primary form-control" title="เพิ่ม / Add">
											<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มรายการขอซื้อ/ขอจ้าง
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="table-responsive" id="PurchaseReqTable"></div>
					</div>
				</div>
			</form>
			
		</div>
	</section>

	<!-- START VIEW APPROVE -->
	<div id="datapurchaseReq" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="datapurchaseReqLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">รายละเอียดขอซื้อขอจ้าง</h3>
					<button type="button" class="close" name="purc_cancel" id="purc_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body" id="purchaseReq_detail">
				</div>
			</div>
		</div>
	</div>
	<!-- END VIEW APPROVE -->

	<script type="text/javascript">
		$(document).ready(function(){

			load_data(1);
			function load_data(page, query = '', queryComp = '', queryDep = '', querySearch = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var querySearch = $('#SearchPurch').val();
				$.ajax({
					url:"fetch_purchaseReq.php",
					method:"POST",
					data:{page:page, query:query, queryComp:queryComp, queryDep:queryDep, querySearch:querySearch},
					success:function(data) {
						$('#PurchaseReqTable').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var querySearch = $('#SearchPurch').val();
				load_data(page, query, queryComp, queryDep, querySearch);
			});

			$('#search_box').keyup(function(){
				var query = $('#search_box').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var querySearch = $('#SearchPurch').val();
				load_data(1, query, queryComp, queryDep, querySearch);
			});

			$("input[name='SearchPRBy']").click(function(){
				$('#SearchPurch').val($("input[name='SearchPRBy']:checked").val());
				var query = $('#search_box').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var querySearch = $('#SearchPurch').val();
				load_data(1, query, queryComp, queryDep, querySearch);
			});

			//-- VIEW --//
			$(document).on('click', '.view_data', function(){
				var id = $(this).attr("id");
				if(id != '') {
					$.ajax({
						url:"v_purchaseReq.php",
						method:"POST",
						data:{id:id},
						success:function(data){
							$('#purchaseReq_detail').html(data);
							$('#datapurchaseReq').modal('show');
						}
					});
				}
			});
			//-- END VIEW --//

			//-- START DELETE --//
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
							url: "r_purchaseReq_delete.php",
							type: "POST",
							data: {del_id:del_id},
							success: function () {
								setTimeout(function () {
									swal({
										title: "ลบข้อมูลสำเร็จ!",
										text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
										type: 'success'
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
			//-- END DELETE --//

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>