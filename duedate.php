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

		$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $dep ."'";
		$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
		$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

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
		th>.truncate, td>.truncate{
			width: auto;
			min-width: 0;
			max-width: 500px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		th>.truncate-id, td>.truncate-id{
			width: auto;
			min-width: 0;
			max-width: 200px;
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

			<form method="POST" name="frmDuedate" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-6 pb-4">
						<h3 class="mb-0">
							<i class="icofont-papers"></i>&nbsp;&nbsp;ใบแจ้งหนี้เกินกำหนดชำระ
						</h3>
					</div>

					<div class="col-md-6 pb-4 text-right">
						<?php if ($_GET["cid"] == 'C001') { ?>
							<?php if ($obj_row_user["user_levid"] == 5 || $obj_row_user["user_levid"] == 6) { } else { ?>
							<a href="duedate_seldep.php?cid=<?=$cid;?>&dep=" type="button" class="btn btn-warning" name="btnBack" id="btnBack">
								<i class="icofont-history"></i>&nbsp;&nbsp;ย้อนกลับ
							</a>
						<?php } } ?>
					</div>

					<div class="col-md-12">
						<div class="row">
							<div class="col-md-2">
								<label class="mt-1">ฝ่าย</label>
								<input type="text" class="form-control" name="depname" id="depname" value="<?=$obj_row_dep["dep_name"];?>" readonly style="background-color: #FFF">
								<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$_GET["dep"];?>">
							</div>

							<div class="col-md-10">
								<label class="mt-1">ค้นหา : </label>
								<input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกเลขที่ใบแจ้งหนี้ที่ต้องการค้นหา" autocomplete="off">
							</div>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<?php if ($obj_row_user["user_levid"] == '2') { ?>
							<div class="table-responsive" id="duedateCEOShow"></div>
						<?php } else { ?>
							<div class="table-responsive" id="duedateShow"></div>
						<?php } ?>
					</div>
				</div>

			</form>

		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function(){

			load_data(1);
			function load_data(page, query = '', queryDep = '') {
				var queryDep = $('#depid').val();
				$.ajax({
					url:"fetch_duedate.php",
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep},
					success:function(data) {
						$('#duedateShow').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function(){
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				load_data(page, query, queryDep);
			});

			$('#search_box').keyup(function(){
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				load_data(1, query, queryDep);
			});




			load_dataCEO(1);
			function load_dataCEO(page, query = '', queryDep = '') {
				var queryDep = $('#depid').val();
				$.ajax({
					url:"fetch_duedate_CEO.php",
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep},
					success:function(data) {
						$('#duedateCEOShow').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function(){
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				load_dataCEO(page, query, queryDep);
			});

			$('#search_box').keyup(function(){
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				load_dataCEO(1, query, queryDep);
			});




			// load_dataMgrAcc(1);
			// function load_dataMgrAcc(page, query = '', queryDep = '') {
			// 	var queryDep = $('#depid').val();
			// 	$.ajax({
			// 		url:"fetch_duedate_Mgr_Acc.php",
			// 		method:"POST",
			// 		data:{page:page, query:query, queryDep:queryDep},
			// 		success:function(data) {
			// 			$('#duedateMgrAccShow').html(data);
			// 		}
			// 	});
			// }

			// $(document).on('click', '.page-link', function(){
			// 	var page = $(this).data('page_number');
			// 	var query = $('#search_box').val();
			// 	var queryDep = $('#depid').val();
			// 	load_dataMgrAcc(page, query, queryDep);
			// });

			// $('#search_box').keyup(function(){
			// 	var query = $('#search_box').val();
			// 	var queryDep = $('#depid').val();
			// 	load_dataMgrAcc(1, query, queryDep);
			// });




			// load_dataMgr(1);
			// function load_dataMgr(page, query = '', queryDep = '') {
			// 	var queryDep = $('#depid').val();
			// 	$.ajax({
			// 		url:"fetch_duedate_Mgr.php",
			// 		method:"POST",
			// 		data:{page:page, query:query, queryDep:queryDep},
			// 		success:function(data) {
			// 			$('#duedateMgrShow').html(data);
			// 		}
			// 	});
			// }

			// $(document).on('click', '.page-link', function(){
			// 	var page = $(this).data('page_number');
			// 	var query = $('#search_box').val();
			// 	var dep = $('#depid').val();
			// 	load_dataMgr(page, query, dep);
			// });

			// $('#search_box').keyup(function(){
			// 	var query = $('#search_box').val();
			// 	var queryDep = $('#depid').val();
			// 	load_dataMgr(1, query, queryDep);
			// });

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>