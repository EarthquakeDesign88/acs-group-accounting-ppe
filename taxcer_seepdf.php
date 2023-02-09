<?php
include 'config/config.php'; 
__check_login();
__page_seldep2('แฟ้มข้อมูล <i class="icofont-caret-right"></i> รายจ่าย  <i class="icofont-caret-right"></i> หัก ณ ที่จ่าย <i class="icofont-caret-right"></i> เลือกฝ่าย');
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
		$y = (isset($_GET["y"])) ? $_GET["y"] : date('Y')+543;
		$m_para = (isset($_GET["m"])) ? $_GET["m"] : date('m');

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						INNER JOIN company_tb AS c ON d.dep_compid = c.comp_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql_seldep = "SELECT DISTINCT * FROM taxcertificate_tb AS taxc 
							INNER JOIN department_tb AS d ON taxc.taxc_depid = d.dep_id 
							WHERE taxc_rev = (SELECT MAX(taxc_rev) AS taxc_rev FROM taxcertificate_tb WHERE taxc_no = taxc.taxc_no) AND taxc_depid = '". $dep ."'";
		$obj_rs_seldep = mysqli_query($obj_con, $str_sql_seldep);
		$obj_row_seldep = mysqli_fetch_array($obj_rs_seldep);

		$str_sql = "SELECT DISTINCT * FROM taxcertificate_tb AS taxc 
					INNER JOIN department_tb AS d ON taxc.taxc_depid = d.dep_id 
					WHERE taxc_rev = (SELECT MAX(taxc_rev) AS taxc_rev FROM taxcertificate_tb WHERE taxc_no = taxc.taxc_no) AND taxc_depid = '". $dep ."' AND taxc_year = '".$y."'";
		$obj_rs = mysqli_query($obj_con, $str_sql);
		$obj_row = mysqli_fetch_array($obj_rs);

		$sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $dep ."'";
		$rs_dep = mysqli_query($obj_con, $sql_dep);
		$row_dep = mysqli_fetch_array($rs_dep);

		// function MonthThai($strDate) {
		// 	$strYear = substr(date("Y",strtotime($strDate))+543,-2);
		// 	$strMonth= date("n",strtotime($strDate));
		// 	$strDay= date("j",strtotime($strDate));
		// 	$strHour= date("H",strtotime($strDate));
		// 	$strMinute= date("i",strtotime($strDate));
		// 	$strSeconds= date("s",strtotime($strDate));
		// 	$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		// 	$strMonthThai = $strMonthCut[$strMonth];
		// 	return "$strMonthThai";
		// }

		// function MonthThaiShort($strDate) {
		// 	$strYear = substr(date("Y",strtotime($strDate))+543,-2);
		// 	$strMonth= date("n",strtotime($strDate));
		// 	$strDay= date("j",strtotime($strDate));
		// 	$strHour= date("H",strtotime($strDate));
		// 	$strMinute= date("i",strtotime($strDate));
		// 	$strSeconds= date("s",strtotime($strDate));
		// 	$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		// 	$strMonthThai = $strMonthCut[$strMonth];
		// 	return "$strMonthThai";
		// }


		switch ($m_para) {
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

			<form method="POST" name="frmTaxcPDF" id="frmTaxcPDF">

				<div class="row py-4 px-1" style="background-color: #e9ecef">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-file-pdf"></i>
							&nbsp;&nbsp;หนังสือรับรองการหักภาษี ณ ที่จ่ายทั้งหมดฝ่าย
							&nbsp;&nbsp;<?=$row_dep["dep_name"]?>
						</h3>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-2">
						
						<div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">

							<?php
								$str_sql_y = "SELECT DISTINCT * FROM taxcertificate_tb GROUP BY taxc_year LIMIT 6";
								$obj_rs_y = mysqli_query($obj_con, $str_sql_y);
								$i = 1;
								$count = mysqli_num_rows($obj_rs_y);
								$ck_yearNow = 0;
								if($count > 0) {
									while ($obj_row_y = mysqli_fetch_array($obj_rs_y)) {
										$yEN = $obj_row_y["taxc_year"]-543;
										if($yEN==date('Y')){
											$ck_yearNow+=1;
										}
							?>

								<a class="nav-link mb-3 p-3 shadow <?php if ($obj_row_y["taxc_year"] == $y) echo "active"; ?>" id="y<?=$obj_row_y["taxc_year"]?>-tab" href="taxcer_seepdf.php?cid=<?=$cid?>&dep=<?=$dep?>&y=<?=$obj_row_y["taxc_year"];?>&m=01" role="tab" aria-controls="v-pills-home" aria-selected="true">
									<i class="fa fa-user-circle-o mr-2"></i>
									<span class="font-weight-bold text-uppercase">
										พ.ศ. <?=$obj_row_y["taxc_year"]?>
									</span>
								</a>

							<?php 
									}
								} 
									
								$yearNow = date('Y') + 543;
								if($ck_yearNow<1){
							?>

								<a class="nav-link mb-3 p-3 shadow <?php if ($yearNow == $y) echo "active"; ?>" id="y<?=$yearNow?>-tab" href="taxcer_seepdf.php?cid=<?=$cid?>&dep=<?=$dep?>&y=<?=$yearNow;?>&m=01" role="tab" aria-controls="v-pills-home" aria-selected="true">
									<i class="fa fa-user-circle-o mr-2"></i>
									<span class="font-weight-bold text-uppercase">
										พ.ศ. <?=$yearNow;?>
									</span>
								</a>

							<?php } ?>

						</div>
						
					</div>

					<div class="col-md-10">

						<div class="d-none">
							<input type="text" name="compid" id="compid" value="<?=$_GET["cid"];?>">
							<input type="text" name="depid" id="depid" value="<?=$_GET["dep"];?>">
							<input type="text" name="yt" id="yt" value="<?=$y;?>">
							<input type="text" name="mt" id="mt" value="<?=$m_para;?>">
						</div>

						<div class="tab-content" id="v-pills-tabContent">
							<div class="fade shadow rounded bg-white show p-4">

								<h2>เดือน<?= $monthTHFULL; ?>&nbsp;&nbsp;<?= $y; ?></h2>

								<ul class="nav nav-tabs nav-fill" role="tablist">
									<?php 
										$start_month = 01;
										$end_month = 12;
										$start_year = date('Y');

										
										for($m = $start_month; $m <= 12; ++$m) {
											$yearTH = $y;									
											$month = date('M', mktime(0, 0, 0, $m, 1, $start_year));
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
										<a class="nav-link <?php if ($monthNo == $m_para) echo "active"; ?>" href="taxcer_seepdf.php?cid=<?=$cid;?>&dep=<?=$dep;?>&y=<?=$yearTH;?>&m=<?=$m;?>" style="width: 100%" title="<?=$monthNo;?>">
											<?php 
												switch ($month) {
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

	<script type="text/javascript">
		$(document).ready(function() {

			load_data(1);
			function load_data(page, query = '', dep = '', y = '', m = '', comp = '') {
				var dep = $('#depid').val();
				var y = $('#yt').val();
				var m = $('#mt').val();
				var comp = $('#compid').val();
				$.ajax({
					url:"fetch_taxcer_seepdf.php",
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