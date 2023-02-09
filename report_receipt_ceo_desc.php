<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$m = $_GET["m"];

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		switch ($m) {
			case '01':
				$monthEN = 'Jan';
				$monthTHFULL = 'มกราคม';
				break;
			case '02':
				$monthEN = 'Feb';
				$monthTHFULL = 'กุมภาพันธ์';
				break;
			case '03':
				$monthEN = 'Mar';
				$monthTHFULL = 'มีนาคม';
				break;
			case '04':
				$monthEN = 'Apr';
				$monthTHFULL = 'เมษายน';
				break;
			case '05':
				$monthEN = 'May';
				$monthTHFULL = 'พฤษภาคม';
				break;
			case '06':
				$monthEN = 'Jun';
				$monthTHFULL = 'มิถุนายน';
				break;
			case '07':
				$monthEN = 'Jul';
				$monthTHFULL = 'กรกฎาคม';
				break;
			case '08':
				$monthEN = 'Aug';
				$monthTHFULL = 'สิงหาคม';
				break;
			case '09':
				$monthEN = 'Sep';
				$monthTHFULL = 'กันยายน';
				break;
			case '10':
				$monthEN = 'Oct';
				$monthTHFULL = 'ตุลาคม';
				break;
			case '11':
				$monthEN = 'Nov';
				$monthTHFULL = 'พฤศจิกายน';
				break;
			case '12':
				$monthEN = 'Dec';
				$monthTHFULL = 'ธันวาคม';
				break;
			default:
				$monthEN = '-';
				$monthTHFULL = '-';
				break;
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
			padding: 8px 0px;
			vertical-align: middle;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="" id="" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF;">
					<div class="col-md-6 my-auto">
						<h3 class="mb-0">
							<i class="icofont-ui-note"></i>&nbsp;&nbsp;รายงานรายรับ เดือน<?=$monthTHFULL;?>
						</h3>
					</div>

					<div class="col-md-6 text-right">
						<a href="report_receipt_ceo.php?cid=<?=$cid;?>&dep=" type="button" class="btn btn-warning d-none" name="btnBack" id="btnBack">
							<i class="icofont-history"></i>&nbsp;&nbsp;ย้อนกลับ
						</a>
						<input action="action" onclick="window.history.go(-1); return false;" type="submit" class="btn btn-warning px-5 py-2" value="ย้อนกลับ" />
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
						<input type="text" class="form-control" name="month" id="month" value="<?=$m;?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12">
						
						<div class="table-responsive" id="RptReceiptCEODesc"></div>

					</div>
				</div>

				<div class="row py-4 px-1 d-none" style="background-color: #FFFFFF;">
					<div class="col-md-12 pb-4 text-center">
						<input type="submit" class="btn btn-success px-5 py-2" name="btnPrint" id="btnPrint" value="Print">
					</div>
				</div>

			</form>
			
		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function() {

			load_data(1);
			function load_data(page, query = '', queryComp = '', queryDep = '', queryMonth = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var queryMonth = $('#month').val();
				$.ajax({
					url:"fetch_report_receipt_ceo_desc.php",
					method:"POST",
					data:{page:page, query:query, queryComp:queryComp, queryDep:queryDep, queryMonth:queryMonth},
					success:function(data) {
						$('#RptReceiptCEODesc').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var queryMonth = $('#month').val();
				load_data(page, query, queryComp, queryDep, queryMonth);
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>