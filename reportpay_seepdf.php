<?php
include 'config/config.php'; 
__check_login();
__page_seldep2('แฟ้มข้อมูล <i class="icofont-caret-right"></i> รายจ่าย  <i class="icofont-caret-right"></i> สรุปรายการทำจ่าย <i class="icofont-caret-right"></i> เลือกฝ่าย');
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
		$y = (isset($_GET["y"])) ? $_GET["y"] : date('Y')+543;
		$m = (isset($_GET["m"])) ? $_GET["m"] : date('m');
		
		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
		$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
		$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

		$str_sql = "SELECT * FROM reportpay_tb1 AS repp INNER JOIN payment_tb AS paym ON repp.repp_id = paym.paym_reppid WHERE paym_depid = '". $dep ."'";
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
		.nav-pills .nav-link.active {
			background-color: #28a7e9;
		}
		.nav-tabs .nav-link {
		    color: #000;
		    border: 0;
		    /*border-bottom: 1px solid grey;*/
		    padding: .75rem 0rem;
		    font-weight: 700;
		    background-color: #e9ecef;
		    border-top-left-radius: 0!important;
			border-top-right-radius: 0!important;
			width: 72px;
			height: 50px;
			text-align: center;
		}
		.nav-tabs .nav-link:hover {
		    /*border: 0;*/
		    /*border-bottom: 1px solid grey;*/
		}
		.nav-item.active {
			background-color: #e9ecef;
		}
		.nav-tabs .nav-link.active {
		    color: #000;
		    border: 0;
		    /*border-radius: 0;*/
		    border-bottom: 4px solid #28a7e9;
		    padding: .75rem 0rem;
		    background-color: #e9ecef;
		}
		.nav-tabs .nav-item {
			margin-bottom: -4px;
		}
		th>.truncate, td>.truncate{
			width: auto;
			min-width: 0;
			max-width: 450px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		th>.truncate-id, td>.truncate-id{
			width: auto;
			min-width: 0;
			max-width: 250px;
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
			
			<form method="POST">
				<div class="row py-4 px-1" style="background-color: #e9ecef">
					<div class="col-md-8">
						<h3 class="mb-0">
							<i class="icofont-file-pdf"></i>
							&nbsp;&nbsp;สรุปรายการทำจ่ายทั้งหมดฝ่าย
							&nbsp;&nbsp;<?=$obj_row_dep["dep_name"]?>
						</h3>
					</div>

					<?php if(__authority_company_count_department(__session_user("id"),((isset($_GET["cid"])) ?$_GET["cid"] : 0)) >=2){ ?>
					<div class="col-md-4">
						<div class="row text-right">
							<div class="col-md-12">
								<a href="reportpay_seepdf.php?cid=<?=$cid;?>" type="button" class="btn btn-warning" name="btnBack" id="btnBack">
									<i class="icofont-history"></i>&nbsp;&nbsp;ย้อนกลับ
								</a>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

				<?php if (mysqli_num_rows($obj_rs) > 0) { ?>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-2">
						
						<div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">

							<?php
								$str_sql_y = "SELECT DISTINCT * FROM reportpay_tb1 GROUP BY repp_year LIMIT 6";
								$obj_rs_y = mysqli_query($obj_con, $str_sql_y);
								$i = 1;
								while ($obj_row_y = mysqli_fetch_array($obj_rs_y)) {
									$yearTH = $obj_row_y["repp_year"];
							?>

							<a class="nav-link mb-3 p-3 shadow <?php if ($obj_row["repp_year"] == $y) echo "active"; ?>" id="y<?=$obj_row_y["repp_year"]?>-tab" href="reportpay_seepdf.php?cid=<?=$cid?>&dep=<?=$dep?>&y=<?=$yearTH?>&m=<?=$m?>" role="tab" aria-controls="v-pills-home" aria-selected="true">
								<i class="fa fa-user-circle-o mr-2"></i>
								<span class="font-weight-bold  text-uppercase">
									พ.ศ. <?=$obj_row_y["repp_year"]?>
								</span>
							</a>

							<?php } ?>

						</div>
						
					</div>

					<div class="col-md-10">
						<div class="col-md-12 d-none">
							<input type="text" class="form-control" name="yt" id="yt" value="<?=$y?>">
							<input type="text" class="form-control" name="mt" id="mt" value="<?=$m?>">
							<input type="text" class="form-control" name="depid" id="depid" value="<?=$_GET["dep"]?>">
							<input type="text" class="form-control" name="compid" id="compid" value="<?=$_GET["cid"]?>">
						</div>

						<!-- Tabs content -->
						<div class="tab-content" id="v-pills-tabContent">
							<div class="tab-pane fade shadow rounded bg-white show <?php if ($obj_row["repp_year"] == $y) echo "active"; ?> p-4" id="Y<?=$obj_row["repp_year"]?>" role="tabpanel" aria-labelledby="Y<?=$obj_row["repp_year"]?>-tab">

								<h3><?= $m; ?>/<?= $y; ?></h3>

								<ul class="nav nav-tabs" id="myTab" role="tablist">

									<?php for ($i = 1; $i <= 12; $i++){
										$year = $obj_row["repp_year"] - 543;
										$yearTH = $obj_row["repp_year"];
										$month_name = date('M', mktime(0, 0, 0, $i, 1, $year));
										$month = date('m', mktime(0, 0, 0, $i, 1, $year));
									?>
									<li class="nav-item">
										<a class="nav-link <?php if ($month == $m) echo "active"; ?>" href="reportpay_seepdf.php?cid=<?=$cid?>&dep=<?=$dep?>&y=<?=$yearTH?>&m=<?=$month?>" role="tab" title="<?=$month;?>">
											<?= $month_name; ?>
										</a>
									</li>
									<?php } ?>

								</ul>

								<div class="tab-content py-4">
									<div class="tab-pane active" id="m<?=$yearTH?><?=$month?>">
										<div class="table-responsive" id="seepdfTable"> </div>
									</div>
								</div>

							</div>
							
						</div>
						
					</div>
				</div>

				<?php } else { ?>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 text-center">
						<h4 class="mb-0">
							ไม่มีไฟล์สรุปจ่าย
						</h4>
					</div>
				</div>

				<?php } ?>
			</form>

		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function() {

			load_data(1);
			function load_data(page, query = '', dep = '', y = '', m = '', comp = '') {
				var dep = $('#depid').val();
				var y = $('#yt').val();
				var m = $('#mt').val();
				var comp = $('#compid').val();
				$.ajax({
					url:"fetch_reportpay_seepdf.php",
					method:"POST",
					data:{page:page, query:query, dep:dep, y:y, m:m, comp:comp},
					success:function(data) {
						$('#seepdfTable').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var dep = $('#depid').val();
				var y = $('#yt').val();
				var m = $('#mt').val();
				var comp = $('#compid').val();
				load_data(page, query, dep, y, m, comp);
			});

			$('#search_box').keyup(function(){
				var query = $('#search_box').val();
				var dep = $('#depid').val();
				var y = $('#yt').val();
				var m = $('#mt').val();
				var comp = $('#compid').val();
				load_data(1, query, dep, y, m, comp);
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>