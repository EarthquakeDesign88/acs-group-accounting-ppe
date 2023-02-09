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

		$str_sql = "SELECT * FROM project_tb AS proj INNER JOIN project_sub_tb AS projS ON proj.proj_id = projS.projS_projid WHERE proj_id = '". $projid ."'";
		$obj_rs = mysqli_query($obj_con, $str_sql);
		$obj_row = mysqli_fetch_array($obj_rs);

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
							<i class="icofont-document-folder"></i>&nbsp;&nbsp;<?=$obj_row["proj_name"];?> ( <?=$obj_row["projS_description"]?> )
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
						<input type="text" class="form-control" name="projid" id="projid" value="<?=$projid;?>">
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
					<div class="col-md-12">
						<div class="table-responsive" id="ProjectTable"></div>
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
			load_data(1);
			function load_data(page, query = '', queryComp = '', queryDep = '', queryProj = '') {
				var queryProj = $('#projid').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				$.ajax({
					url:"fetch_invoice_rcpt_project_desc.php",
					method:"POST",
					data:{page:page, query:query, queryComp:queryComp, queryDep:queryDep, queryProj:queryProj},
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
				load_data(page, query, queryComp, queryDep, queryProj);
			});

			$('#search_box').keyup(function(){
				var query = $('#search_box').val();
				var queryProj = $('#projid').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				load_data(1, query, queryComp, queryDep, queryProj);
			});


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