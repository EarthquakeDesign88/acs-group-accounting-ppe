<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	}else{

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

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<style type="text/css">
		.table .thead-light th {
			color: #000;
			text-align: center;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<?php

				$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $dep ."'";
				$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
				$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

				if ($dep != 0) {
					$depN = 'ฝ่าย ' . $obj_row_dep["dep_name"];
				} else {
					$depN = '';
				}
				
			?>
			
			<form method="POST" action="report_invoice_print.php?cid=<?=$cid;?>&dep=<?=$dep?>&df=<?=$df;?>&dt=<?=$dt;?>">
				<div class="row py-4 px-1" style="background-color: #e9ecef">
					<div class="col-md-9 my-auto">
						<h3 class="mb-0">
							<i class="icofont-ui-note"></i>&nbsp;&nbsp;รายงานใบแจ้งหนี้ ระหว่างวันที่ <?=DateThai($df);?> ถึง <?=DateThai($dt);?> <?=$depN;?>
						</h3>
					</div>

					<div class="col-md-3 text-right">
						<input action="action" onclick="window.history.go(-1); return false;" type="submit" class="btn btn-warning px-5 py-2" value="ย้อนกลับ" />
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="df" id="df" value="<?=$df;?>">
						<input type="text" class="form-control" name="dt" id="dt" value="<?=$dt;?>">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						
						<div class="table-responsive" id="RptInvoiceDesc"></div>

					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 text-center">
						<input type="submit" class="btn btn-success px-5 py-2" name="btnPrint" id="btnPrint" value="Print">
					</div>
				</div>
				
			</form>

		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function() {

			load_data(1);
			function load_data(page, query = '', queryComp = '', queryDep = '', df = '', dt = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var df = $('#df').val();
				var dt = $('#dt').val();
				$.ajax({
					url:"fetch_report_invoice_desc.php", 
					method:"POST",
					data:{page:page, query:query, queryComp:queryComp, queryDep:queryDep, df:df, dt:dt},
					success:function(data) {
						$('#RptInvoiceDesc').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var df = $('#df').val();
				var dt = $('#dt').val();
				load_data(page, query, queryComp, queryDep, df, dt);
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>