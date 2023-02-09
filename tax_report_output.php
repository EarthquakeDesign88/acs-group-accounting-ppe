<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$df = $_GET["df"];
		$dt = $_GET["dt"];

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		function DateThai($strDate) {
			$strYear = substr(date("Y",strtotime($strDate))+543,-2);
			$strMonth= date("n",strtotime($strDate));
			$strDay= date("j",strtotime($strDate));
			$strHour= date("H",strtotime($strDate));
			$strMinute= date("i",strtotime($strDate));
			$strSeconds= date("s",strtotime($strDate));
			$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
			$strMonthThai=$strMonthCut[$strMonth];
			return "$strDay $strMonthThai $strYear";
		}

		$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $dep ."'";
		$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
		$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

		if ($dep != 0) {
			$depN = 'ฝ่าย ' . $obj_row_dep["dep_name"];
		} else {
			$depN = '';
		}

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<style type="text/css">
		.table .thead-light th {
			color: #000;
			text-align: center;
			font-size: 13px;
			padding: 8px 0px;
		}
		.table td, .table th {
			font-size: 12px;
			padding: .25rem;
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
		.search-style{
			display: flex;
			background-color: #007bff;
			color: #fff;
			padding: 10px;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmPreview" id="frmPreview" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF;">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-ui-note"></i>&nbsp;&nbsp;รายงานภาษีซื้อ ระหว่างวันที่ <?=DateThai($df);?> ถึง <?=DateThai($dt);?>
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="df" id="df" value="<?=$df;?>">
						<input type="text" class="form-control" name="dt" id="dt" value="<?=$dt;?>">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
						<?php if($cid == 'C001' || $cid == 'C004' || $cid == 'C014' || $cid == 'C015') { ?>
							<input type="text" class="form-control" name="refINV" id="refINV" value="0">
						<?php } else { ?>
							<input type="text" class="form-control" name="refINV" id="refINV" value="1">
						<?php } ?>
					</div>
				</div>

				<?php if($cid == 'C001') { ?>
				<div class="row mt-2">
					<div class="col-md-3">
						<div class="input-group">
							<select class="custom-select form-control search_tax" id="SelectDep" name="SelectDep" style="background-color: #FFF; color: #000;">
								<option value="" selected>ทั้งหมด</option>
								<option value="D005">AR</option>
								<option value="D002">AD</option>
								<option value="D022">CI</option>
								<option value="D003">CM</option>
							</select>
						</div>
					</div>

					<div class="col-md-9">
						<div class="input-group">
						<i class="icofont-search-1 search-style"></i><input type="text" name="search_box" id="search_box" class="form-control search_tax" placeholder="กรอกเลขที่ภาษีซื้อที่ต้องการค้นหา" autocomplete="off">
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12">
						<div class="table-responsive" id="RptOutputTaxDesc"></div>
					</div>
				</div>

				<?php 

					$str_sql = "SELECT * FROM taxpurchase_tb AS r 
						INNER JOIN company_tb AS c ON r.tax_comp_id = c.comp_id 
						INNER JOIN department_tb AS d ON r.tax_dep_id = d.dep_id 
						WHERE tax_created_at BETWEEN '". $df ."' AND '". $dt ."' ";
					$obj_rs = mysqli_query($obj_con, $str_sql);
					$obj_row = mysqli_fetch_array($obj_rs);
					if(mysqli_num_rows($obj_rs) > 0) {
				?>
				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12 pb-4 text-center">
						<a href="tax_report.php?cid=<?=$cid;?>&dep=<?=$dep;?>" class="btn btn-warning px-5 py-2" type="button">ย้อนกลับ</a>
					</div>
				</div>
				<!-- <div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12 pb-4 text-center"> -->
						<!-- <input type="submit" class="btn btn-success px-5 py-2" name="btnPrint" id="btnPrint" value="Print"> -->
						<!-- <?php if ($cid == 'C001') { ?>
						    <a href="report_outputtax_print_vat_AD.php?cid=<?=$cid;?>&dep=&df=<?=$df;?>&dt=<?=$dt;?>" class="btn btn-success px-5 py-2" target="_blank">Print</a>
						<?php } else { ?> -->
						    <!-- <a href="tax_report_output_print.php?cid=<?=$cid;?>&dep=<?=$dep;?>&df=<?=$df;?>&dt=<?=$dt;?>" class="btn btn-success px-5 py-2" target="_blank">Print</a> -->
						<!-- <?php } ?>
					</div>
				</div>

				<?php } else { ?>

				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12 pb-4 text-center">
						<a href="tax_report.php?cid=<?=$cid;?>&dep=<?=$dep;?>" class="btn btn-warning px-5 py-2" type="button">ย้อนกลับ</a>
					</div>
				</div>

				<?php } ?>
				 -->

			</form>
			
		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function() {

			load_data(1);
			function load_data(page, query = '', queryComp = '', queryDep = '', queryRefINV = '', df = '', dt = '') {
				var queryRefINV = $('#refINV').val();
				var queryComp = $('#compid').val();
				// var queryDep = $('#depid').val();
				var df = $('#df').val();
				var dt = $('#dt').val();
				$.ajax({
					url:"fetch_tax_report_output.php",
					method:"POST",
					data:{page:page, query:query, queryComp:queryComp, queryDep:queryDep, queryRefINV:queryRefINV, df:df, dt:dt},
					success:function(data) {
						$('#RptOutputTaxDesc').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryRefINV = $('#refINV').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var df = $('#df').val();
				var dt = $('#dt').val();
				load_data(page, query, queryComp, queryDep, queryRefINV, df, dt);
			});

			$(document).on('change', '.search_tax', function() {
				var page = $('.page-link').data('page_number');
				var query = $('#search_box').val();
				var queryRefINV = $('#refINV').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#SelectDep').find(":selected").val();
				var df = $('#df').val();
				var dt = $('#dt').val();
				load_data(page, query, queryComp, queryDep, queryRefINV, df, dt);
			});

			$(document).on('keyup', '.search_tax', function() {
				var page = $('.page-link').data('page_number');
				var query = $('#search_box').val();
				var queryRefINV = $('#refINV').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#SelectDep').find(":selected").val();
				var df = $('#df').val();
				var dt = $('#dt').val();
				load_data(page, query, queryComp, queryDep, queryRefINV, df, dt);
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>