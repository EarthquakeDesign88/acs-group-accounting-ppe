<?php
include 'config/config.php'; 
__check_login();
__page_seldep2('รายงาน <i class="icofont-caret-right"></i> รายจ่าย  <i class="icofont-caret-right"></i> สรุปรายการทำจ่าย <i class="icofont-caret-right"></i> เลือกฝ่าย');
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

			<form method="POST" name="frmReportPay" id="frmReportPay" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12 pb-4">
						<h3 class="mb-0">
							<i class="icofont-papers"></i>
							&nbsp;&nbsp;สรุปรายการทำจ่าย
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
					</div>

					<div class="col-md-12" id="SearchInv">
						<div class="row">
							<div class="col-md-2">
								<label class="mb-1">ฝ่าย</label>
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
										<label class="mb-1">ค้นหา</label>
									</div>
								</div>

								<div class="input-group">
									<input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกวันที่สรุปทำจ่ายที่ต้องการค้นหา" autocomplete="off">
									<div class="input-group-append">
										<a href="reportpay_desc.php?cid=<?=$cid;?>&dep=<?=$dep;?>" class="btn btn-primary float-right">
											<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มสรุปรายการทำจ่าย
										</a>
									</div>
								</div>
							</div>
						</div>						
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="table-responsive" id="reportpayTable"></div>
					</div>
				</div>
				
			</form>
			
		</div>
	</section>

	<!-- START REPORTPAY -->
	<div id="dataReportpay" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dataReportpayLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">สรุปรายการทำจ่าย</h3>
					<button type="button" class="close" name="reppid_cancel" id="reppid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body" id="reportpay_detail">
				</div>
			</div>
		</div>
	</div>
	<!-- END REPORTPAY -->

	<script type="text/javascript">
		$(document).ready(function() {

			//------ START REPORTPAY ------//
			$(document).on('click', '.view_data', function(){
				var id = $(this).attr("id");
				if(id != '') {
					$.ajax({
						url:"v_reportpay.php",
						method:"POST",
						data:{id:id},
						success:function(data){
							$('#reportpay_detail').html(data);
							$('#dataReportpay').modal('show');
						}
					});
				}
			});
			//------ END REPORTPAY ------//

			load_data(1);
			function load_data(page, query = '', queryDep = '', queryComp = '', queryRepp = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var queryRepp = $('#reppid').val();
				$.ajax({
					url:"fetch_reportpay.php",
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep, queryComp:queryComp, queryRepp:queryRepp, },
					success:function(data) {
						$('#reportpayTable').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryRepp = $('#reppid').val();
				load_data(page, query, queryDep, queryComp, queryRepp);
			});

			$('#search_box').keyup(function() {
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryRepp = $('#reppid').val();
				load_data(1, query, queryDep, queryComp, queryRepp);
			});

			$("input[name='SearchPaymBy']").click(function(){
				$('#SearchPaymVal').val($("input[name='SearchPaymBy']:checked").val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryRepp = $('#reppid').val();
				load_data(1, query, queryDep, queryComp, queryRepp);
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>