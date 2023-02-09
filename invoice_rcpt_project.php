<?php
include 'config/config.php'; 
__check_login();
__page_seldep2('รายรับ <i class="icofont-caret-right"></i> ข้อมูลโครงการ <i class="icofont-caret-right"></i> เลือกฝ่าย');
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
							<i class="icofont-engineer"></i>
							&nbsp;&nbsp;โครงการทั้งหมด
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
							</div>
						</div>
					</div>

					<?php if($cid == 'C001') {?>
						<div class="col-md-12 mt-2">
							<div class="input-group">
								<div class="input-group-append">
									<a href="invoice_rcpt_total_project.php?cid=<?=$cid;?>&dep=<?=$dep;?>" class="btn btn-secondary float-right">
										<i class="icofont-paper"></i>&nbsp;&nbsp;รวมงวดงาน
									</a>
								</div>
							</div>
						</div>
					<?php }?>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="table-responsive" id="ProjectTable"></div>
					</div>
				</div>
				
			</form>
			
		</div>
	</section>

	<!-- START VIEW PROJECT -->
	<div id="dataProject" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dataInvoiceLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">รายละเอียดโครงการ</h3>
					<button type="button" class="close" name="id_cancel" id="id_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body" id="project_detail">
				</div>
			</div>
		</div>
	</div>
	<!-- END VIEW PROJECT -->

	<script type="text/javascript">
		$(document).ready(function() {
			load_data(1);
			function load_data(page, query = '', queryComp = '', queryDep = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				$.ajax({
					url:"fetch_invoice_rcpt_project.php",
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



			//--- VIEW ---//
			$(document).on('click', '.view_data', function(){
				var id = $(this).attr("id");
				if(id != '') {
					$.ajax({
						url:"v_project.php",
						method:"POST",
						data:{id:id},
						success:function(data){
							$('#project_detail').html(data);
							$('#dataProject').modal('show');
						}
					});
				}
			});
			//--- VIEW ---//

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>