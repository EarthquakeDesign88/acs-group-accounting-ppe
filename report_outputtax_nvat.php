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
							<i class="icofont-ui-note"></i>&nbsp;&nbsp;รายงานรายได้ (NON-VAT) ระหว่างวันที่ <?=DateThai($df);?> ถึง <?=DateThai($dt);?> <?=$depN;?>
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
				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12">
						<div class="table-responsive" id="RptOutputTaxDesc"></div>
					</div>
				</div>
				<?php 
					if($cid == 'C001' || $cid == 'C004' || $cid == 'C014' || $cid == 'C015') {
						$str_sql = "SELECT * FROM receipt_tb AS r 
									LEFT JOIN company_tb AS c ON r.re_compid = c.comp_id 
									LEFT JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
									LEFT JOIN department_tb AS d ON r.re_depid = d.dep_id 
									INNER JOIN invoice_rcpt_tb AS i ON r.re_id = i.invrcpt_reid  
									WHERE re_compid = '". $cid ."' AND re_date BETWEEN '". $df ."' AND '". $dt ."'";
					} else {
						$str_sql = "SELECT * FROM receipt_tb AS r 
									LEFT JOIN company_tb AS c ON r.re_compid = c.comp_id 
									LEFT JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
									LEFT JOIN department_tb AS d ON r.re_depid = d.dep_id 
									WHERE re_compid = '". $cid ."' AND re_date BETWEEN '". $df ."' AND '". $dt ."'";
					}
					$obj_rs = mysqli_query($obj_con, $str_sql);
					$obj_row = mysqli_fetch_array($obj_rs);
					if(mysqli_num_rows($obj_rs) > 0) {
				?>
				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12 pb-4 text-center">
						<a href="report_outputtax_export.php?cid=<?=$cid;?>&df=<?=$df;?>&dt=<?=$dt;?>&vat=NV&export=pdf" class="btn btn-success px-5 py-2" target="_blank">Print</a>
					</div>
				</div>
				<?php } else { ?>
				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12 pb-4 text-center">
						<a href="report_outputtax.php?cid=<?=$cid;?>&dep=<?=$dep;?>" class="btn btn-warning px-5 py-2" type="button">ย้อนกลับ</a>
					</div>
				</div>
				<?php } ?>
				
			</form>
			
		</div>
	</section>
	<script type="text/javascript">
		$(document).ready(function() {
			load_data(1);
			function load_data(page, query = '', queryComp = '', queryDep = '', queryRefINV = '', df = '', dt = '') {
				var queryRefINV = $('#refINV').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var df = $('#df').val();
				var dt = $('#dt').val();
				$.ajax({
					url:"fetch_report_outputtax_nvat.php",
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
		});
	</script>
	<?php include 'footer.php'; ?>
</body>
</html>
<?php } ?>