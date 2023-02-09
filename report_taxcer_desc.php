<?php
	include 'config/config.php'; 
	__check_login();
	
	 if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
	
	if (!$_SESSION["user_name"]){  //check session
		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	}else{
		include 'connect.php';
		$cid = $_GET["cid"];
		$dep = (isset($_GET["dep"])) ? $_GET["dep"] : "";
		$df = (isset($_GET["df"])) ? $_GET["df"] : "";
		$dt =(isset($_GET["dt"])) ? $_GET["dt"] : "";
		
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
		$str_sql_tax_type = "SELECT * FROM taxfiling_tb";
		$obj_rs_tax_type = mysqli_query($obj_con, $str_sql_tax_type);
        
          
       $url_back = "report_taxcer.php?cid=".$cid."&dep=".$dep."&df=".$df."&dt=".$dt;
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
		ul.pagination li.disabled {
			cursor: not-allowed;
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
                
                if(empty($dep)){
                    $dep_list = __implode_department($cid);
                    $depN = "ฝ่าย ".$dep_list;
                }else{
                    $depN =  "ฝ่าย ".__data_department($dep,"name");;
                }
			?>
			
			<form method="POST" action="report_taxcer_print_excel.php?cid=<?=$cid;?>&dep=<?=$dep?>&df=<?=$df;?>&dt=<?=$dt;?>&export=excel">
				<div class="row py-4 px-1" style="background-color: #e9ecef">
					<div class="col-md-9 my-auto">
						<h4 class="mb-0">
							<i class="icofont-ui-note"></i>&nbsp;&nbsp;รายงานหักภาษี ณ ที่จ่าย ระหว่างวันที่ <?=DateThai($df);?> ถึง <?=DateThai($dt);?> <?=$depN;?>
						</h4>
					</div>
					<div class="col-md-3 text-right">
					    <a href="<?=$url_back;?>" class="btn btn-warning  mb-1"><i class="icofont-history"></i> ย้อนกลับ</a>
					</div>
					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="df" id="df" value="<?=$df;?>">
						<input type="text" class="form-control" name="dt" id="dt" value="<?=$dt;?>">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
					</div>
				</div>
				<div class="row mt-2">
					<div class="col-md-3">
						<div class="input-group">
							<select class="custom-select form-control search_tax_type" id="SelectTax" name="SelectTax" style="background-color: #FFF; color: #000;">
								<option value="" selected>ทั้งหมด</option>
								<?php 
									while($obj_row_tax_type = mysqli_fetch_array($obj_rs_tax_type)) { ?>
										<option value="<?= $obj_row_tax_type['tf_id']; ?>"><?= $obj_row_tax_type['tf_name']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<!-- <div class="col-md-3">
						<div class="input-group">
							<select class="custom-select form-control search_tax_month" id="SelectMonth" name="SelectMonth" style="background-color: #FFF; color: #000;">
								<option value="" selected>เดือน</option>
								<option value="01">มกราคม</option>
								<option value="02">กุมภาพันธ์</option>
								<option value="03">มีนาคม</option>
								<option value="04">เมษายน</option>
								<option value="05">พฤษภาคม</option>
								<option value="06">มิถุนายน</option>
								<option value="07">กรกฎาคม</option>
								<option value="08">สิงหาคม</option>
								<option value="09">กันยายน</option>
								<option value="10">ตุลาคม</option>
								<option value="11">พฤศจิกายน</option>
								<option value="12">ธันวาคม</option>
							</select>
						</div>
					</div> -->
				</div>
				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						
						<div class="table-responsive" id="RptTaxcerDesc"></div>
					</div>
				</div>
				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 text-center">
						<button type="submit" class="btn btn-success">Export Excel</button>
					</div>
				</div>
				
			</form>
		</div>
	</section>
	<script type="text/javascript">
		$(document).ready(function() {
			load_data(1);
			function load_data(page, query = '', queryComp = '', queryDep = '', df = '', dt = '', queryTax = '', queryMonth = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var df = $('#df').val();
				var dt = $('#dt').val();
				$.ajax({
					url:"fetch_report_taxcer_desc.php",
					method:"POST",
					data:{page:page, query:query, queryComp:queryComp, queryDep:queryDep, df:df, dt:dt, queryTax:queryTax, queryMonth:queryMonth},
					success:function(data) {
						$('#RptTaxcerDesc').html(data);
					}
				});
			}
			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var queryTax = $('#SelectTax').find(":selected").val();
				var queryMonth = $('#SelectMonth').find(":selected").val();
				var df = $('#df').val();
				var dt = $('#dt').val();
				load_data(page, query, queryComp, queryDep, df, dt, queryTax, queryMonth);
			});
			$(document).on('change', '.search_tax_type', function() {
				var page = $('.page-link').data('page_number');
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryTax = $('#SelectTax').find(":selected").val();
				var queryMonth = $('#SelectMonth').find(":selected").val();
				var df = $('#df').val();
				var dt = $('#dt').val();
				load_data(page, query, queryComp, queryDep, df, dt, queryTax, queryMonth);
			});
			$(document).on('change', '.search_tax_month', function() {
				var page = $('.page-link').data('page_number');
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryTax = $('#SelectTax').find(":selected").val();
				var queryMonth = $('#SelectMonth').find(":selected").val();
				var df = $('#df').val();
				var dt = $('#dt').val();
				load_data(page, query, queryComp, queryDep, df, dt, queryTax, queryMonth);
			});
		});
	</script>
	<?php include 'footer.php'; ?>
</body>
</html>
<?php } ?>