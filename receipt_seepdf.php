<?php
include 'config/config.php'; 
__check_login();
__page_seldep2('แฟ้มข้อมูล <i class="icofont-caret-right"></i> รายรับ <i class="icofont-caret-right"></i> ใบเสร็จรับเงิน  <i class="icofont-caret-right"></i> เลือกฝ่าย');
?>
<?php
	 if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	 
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$year = (isset($_GET["y"])) ? $_GET["y"] : date('Y')+543;
		$month = (isset($_GET["m"])) ? $_GET["m"] : date('m');
		if(isset($_GET["refir"])){
			$refir = $_GET["refir"];
		}else{
            $str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $dep ."'";
            $obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
            $obj_row_dep = mysqli_fetch_array($obj_rs_dep);
            
			if ($obj_row_dep["dep_name"] == "AD" || $obj_row_dep["dep_name"] == "ACSD" || $obj_row_dep["dep_name"] == "ACSI" || $obj_row_dep["dep_name"] == "ACSP") {
				$refir = 0;
			} else {
				$refir = 1;
			}
		}
		
		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $dep ."'";
		$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
		$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

		$str_sql = "SELECT * FROM receipt_tb AS r 
					INNER JOIN company_tb AS c ON r.re_compid = c.comp_id 
					INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
					INNER JOIN department_tb AS d ON r.re_depid = d.dep_id 
					WHERE re_depid = '". $dep ."' AND re_year = '". $year ."' AND re_month = '". $month ."'";
		$obj_rs = mysqli_query($obj_con, $str_sql);
		$obj_row = mysqli_fetch_array($obj_rs);

		switch ($month) {
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
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="" id="" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-file-pdf"></i>
							&nbsp;&nbsp;ใบเสร็จรับเงินทั้งหมดฝ่าย
							&nbsp;&nbsp;<?=$obj_row_dep["dep_name"]?>
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<?php if($cid == 'C001') { ?>
							<input type="text" class="form-control" name="viewComp" id="viewComp" value="ACS">
						<?php } 
						else if($cid == 'C004') { ?>
							<input type="text" class="form-control d-none" name="viewComp" id="viewComp" value="ACSP">
						<?php }
						else { ?>
							<input type="text" class="form-control" name="viewComp" id="viewComp" value="<?=$obj_row["dep_name"];?>">
						<?php } ?>
					</div>
					
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-2">
						<div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">

							<?php
								$str_sql_y = "SELECT DISTINCT * FROM receipt_tb WHERE re_year!= '' GROUP BY re_year LIMIT 6";
								$obj_rs_y = mysqli_query($obj_con, $str_sql_y);
								$i = 1;
								while ($obj_row_y = mysqli_fetch_array($obj_rs_y)) {
									$yEN = $obj_row_y["re_year"]-543;
							?>

							<a class="nav-link mb-3 p-3 shadow <?php if ($obj_row_y["re_year"] == $year) echo "active"; ?>" id="y<?=$obj_row_y["re_year"]?>-tab" href="receipt_seepdf.php?cid=<?=$cid?>&dep=<?=$dep?>&y=<?=$obj_row_y["re_year"];?>&m=01&refir=<?=$refir;?>" role="tab" aria-controls="v-pills-home" aria-selected="true">
								<i class="fa fa-user-circle-o mr-2"></i>
								<span class="font-weight-bold text-uppercase">
									พ.ศ. <?=$obj_row_y["re_year"]?>
								</span>
							</a>

							<?php } ?>

						</div>
					</div>

					<div class="col-md-10">

						<div class="d-none">
							<input type="text" name="compid" id="compid" value="<?=$_GET["cid"];?>">
							<input type="text" name="depid" id="depid" value="<?=$_GET["dep"];?>">
							<input type="text" name="year" id="year" value="<?=$year;?>">
							<input type="text" name="month" id="month" value="<?=$month;?>">
							<input type="text" name="refir" id="refir" value="<?=$refir;?>">
						</div>


						<div class="tab-content" id="v-pills-tabContent">
							<div class="fade shadow rounded bg-white show p-4">

								<h2>เดือน<?= $monthTHFULL; ?>&nbsp;&nbsp;<?= $year; ?></h2>

								<ul class="nav nav-tabs nav-fill" role="tablist">
									<?php 
										$start_month = 01;
										$end_month = 12;
										$start_year = date('Y');

										
										for($m = $start_month; $m <= 12; ++$m) {
											$yearTH = $year;									
											$monthN = date('M', mktime(0, 0, 0, $m, 1, $start_year));
											$monthNo = date('m', mktime(0, 0, 0, $m, 1, $start_year));

											if($start_month == 12 && $m == 12 && $end_month < 12) {
												$m = 0;
												// $start_year = $start_year+1;
											}
											// echo date('F Y', mktime(0, 0, 0, $m, 1, $start_year)).'<br>';
											if($m == 1 || $m == 2 || $m == 3 || $m == 4 || $m == 5 || $m == 6 || $m == 7 || $m == 8 || $m == 9) {
												$m = "0".$m;
											}

									?>
									<li class="nav-item">
										<a class="nav-link <?php if ($monthNo == $month) echo "active"; ?>" href="receipt_seepdf.php?cid=<?=$cid;?>&dep=<?=$dep;?>&y=<?=$yearTH;?>&m=<?=$m;?>&refir=<?=$refir;?>" style="width: 100%" title="<?=$monthNo;?>">
											<?php 
												switch ($monthN) {
													case 'Jan':
														$monthTH = 'ม.ค.';
														break;
													case 'Feb':
														$monthTH = 'ก.พ.';
														break;
													case 'Mar':
														$monthTH = 'มี.ค';
														break;
													case 'Apr':
														$monthTH = 'เม.ย.';
														break;
													case 'May':
														$monthTH = 'พ.ค.';
														break;
													case 'Jun':
														$monthTH = 'มิ.ย.';
														break;
													case 'Jul':
														$monthTH = 'ก.ค.';
														break;
													case 'Aug':
														$monthTH = 'ส.ค.';
														break;
													case 'Sep':
														$monthTH = 'ก.ย.';
														break;
													case 'Oct':
														$monthTH = 'ต.ค.';
														break;
													case 'Nov':
														$monthTH = 'พ.ย.';
														break;
													case 'Dec':
														$monthTH = 'ธ.ค';
														break;
													default:
														$monthTH = '-';
														break;
												};
											?>
											<?=$monthTH;?>
										</a>
									</li>
									<?php } ?>
								</ul>
								
								<div class="tab-content" id="v-pills-tabContent">
									<div class="tab-content py-4">
										<div class="tab-pane active" id="m<?=$year?><?=$month?>">
											<div class="table-responsive" id="seepdfTable"> </div>
										</div>
									</div>
								</div>

							</div>
						</div>

					</div>
				</div>
				
			</form>
			
		</div>
	</section>

	<!-- START VIEW RECEIPT -->
	<div id="dataReceipt" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dataReceiptLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">รายละเอียดใบเสร็จรับเงิน</h3>
					<button type="button" class="close" name="reid_cancel" id="reid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body" id="receipt_detail">
				</div>
			</div>
		</div>
	</div>
	<!-- END VIEW RECEIPT -->

	<script type="text/javascript">
		$(document).ready(function() {

			$('.tabmonth').click(function() {
				$('#monthid').val($('.tabmonth').val());
				var cid = $('#compid').val();
				var dep = $('#depid').val();
				var year = $('#year').val();
				var month = $('#month').val();
				var url = "receipt_seepdf.php?cid="+cid+"&dep="+dep+"&y="+year+"&m="+month;
				$(location).attr('href',url);
			});

		});


		$(document).ready(function() {

			load_data(1);
			function load_data(page, query = '', queryDep = '', queryYear = '', queryMonth = '', queryComp = '', queryRefir = '') {
				var queryDep = $('#depid').val();
				var queryYear = $('#year').val();
				var queryMonth = $('#month').val();
				var queryComp = $('#compid').val();
				var queryRefir = $('#refir').val();
				$.ajax({
					url:"fetch_receipt_seepdf.php",
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep, queryYear:queryYear, queryMonth:queryMonth, queryComp:queryComp, queryRefir:queryRefir},
					success:function(data) {
						$('#seepdfTable').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryYear = $('#year').val();
				var queryMonth = $('#month').val();
				var queryComp = $('#compid').val();
				var queryRefir = $('#refir').val();
				load_data(page, query, queryDep, queryYear, queryMonth, queryComp, queryRefir);
			});

			$('#search_box').keyup(function(){
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryYear = $('#year').val();
				var queryMonth = $('#month').val();
				var queryComp = $('#compid').val();
				var queryRefir = $('#refir').val();
				load_data(1, query, queryDep, queryYear, queryMonth, queryComp, queryRefir);
			});


			//------ START VIEW RECEIPT ------//
			$(document).on('click', '.view_data', function(){
				var id = $(this).attr("id");
				var dep = document.getElementById("viewComp").value;
				if(id != '') {
					$.ajax({
						url:"v_receipt_"+ dep +".php",
						method:"POST",
						data:{id:id},
						success:function(data){
							console.log(data);
							$('#receipt_detail').html(data);
							$('#dataReceipt').modal('show');
						}
					});
				}
			});
			//------ END VIEW RECEIPT ------//
		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>