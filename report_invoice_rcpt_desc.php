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
		$custid = $_GET["custid"];
		// $stsid = $_GET["stsid"];
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
		$str_sql_status = "SELECT * FROM status_tb WHERE sts_id <> 'STS004'";
		$obj_rs_status = mysqli_query($obj_con, $str_sql_status);
?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>
	<style type="text/css">
		.table .thead-light th {
			color: #000;
			text-align: center;
			font-size: 12px;
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
			<form method="POST" name="" id="" action="">
				<div class="row py-4 px-1" style="background-color: #E9ECEF;">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-ui-note"></i>&nbsp;&nbsp;รายงานใบแจ้งหนี้ ระหว่างวันที่ <?=DateThai($df);?> ถึง <?=DateThai($dt);?> <?=$depN;?>
						</h3>
					</div>
					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="df" id="df" value="<?=$df;?>">
						<input type="text" class="form-control" name="dt" id="dt" value="<?=$dt;?>">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
						<input type="text" class="form-control" name="custid" id="custid" value="<?=$custid;?>">
						<input type="text" class="form-control" name="stsid" id="stsid" value="<?=$stsid;?>">
					</div>
				</div>
				
				<div class="row mt-2">
					<div class="col-md-3">
						<div class="input-group">
							<select class="custom-select form-control search_status" id="SelectStatus" name="SelectStatus" style="background-color: #FFF; color: #000;">
								<option value="">ทั้งหมด</option>
								<?php 
									while($obj_row_status = mysqli_fetch_array($obj_rs_status)) { ?>
										<option value="<?= $obj_row_status['sts_id']; ?>"><?= $obj_row_status['sts_description']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12">
						
						<div class="table-responsive" id="RptInvRcptDesc"></div>
					</div>
				</div>
				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12 pb-4 text-center">
						<!-- <input type="submit" class="btn btn-success px-5 py-2" name="btnPrint" id="btnPrint" value="Print"> -->
						<a href="report_invoice_rcpt_export.php?cid=<?=$cid;?>&dep=<?=$dep;?>&df=<?=$df;?>&dt=<?=$dt;?>&custid=<?=$custid;?>&export=pdf" class="btn btn-danger px-4 py-2 pdf" target="_blank">Export Pdf</a>
						<a href="report_invoice_rcpt_export.php?cid=<?=$cid;?>&dep=<?=$dep;?>&df=<?=$df;?>&dt=<?=$dt;?>&custid=<?=$custid;?>&export=excel" class="btn btn-success px-4 py-2 excel" target="_blank">Export Excel</a>
					</div>
				</div>
				
			</form>
			
		</div>
	</section>
	<script type="text/javascript">
		$(document).ready(function() {
			var f_pdf = "report_invoice_rcpt_print.php?cid=<?=$cid;?>&dep=<?=$dep;?>&df=<?=$df;?>&dt=<?=$dt;?>&custid=<?=$custid;?>"
			var f_excel = "report_invoice_rcpt_excel.php?cid=<?=$cid;?>&dep=<?=$dep;?>&df=<?=$df;?>&dt=<?=$dt;?>&custid=<?=$custid;?>&export=excel"
			load_data(1);
			function load_data(page, query = '', queryComp = '', queryDep = '', queryCust = '', df = '', dt = '', queryStatus = '') {
				var queryCust = $('#custid').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var queryStatus = $('#SelectStatus').find(":selected").val();
				var df = $('#df').val();
				var dt = $('#dt').val();
				$.ajax({
					url:"fetch_report_invoice_rcpt_desc.php",
					method:"POST",
					data:{page:page, query:query, queryComp:queryComp, queryDep:queryDep, queryCust:queryCust, df:df, dt:dt, queryStatus},
					success:function(data) {
						$('#RptInvRcptDesc').html(data);
					}
				});
			}
			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryCust = $('#custid').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var queryStatus = $('#SelectStatus').find(":selected").val();
				var df = $('#df').val();
				var dt = $('#dt').val();
				load_data(page, query, queryComp, queryDep, queryCust, df, dt, queryStatus);
			});
			$(document).on('change', '.search_status', function() {
				var page = $('.page-link').data('page_number');
				var query = $('#search_box').val();
				var queryCust = $('#custid').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var queryStatus = $('#SelectStatus').find(":selected").val();
				var query_pdf =  "&sts=" + queryStatus
				var query_excel = "&sts=" + queryStatus
				var str_excel = f_excel + query_excel
				var str_pdf = f_pdf + query_pdf
				$(".excel").attr("href", str_excel)
				$(".pdf").attr("href", str_pdf)
				var df = $('#df').val();
				var dt = $('#dt').val();
				load_data(page, query, queryComp, queryDep, df, dt,'', queryStatus);
			});
		});
	</script>
	<?php include 'footer.php'; ?>
</body>
</html>
<?php } ?>