<?php
include 'config/config.php'; 
__check_login();

$paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
$paramurl_department_id = (isset($_GET["dep"])) ? $_GET["dep"] : 0;
$paramurl_df = (isset($_GET["df"])) ? $_GET["df"] : "";
$paramurl_dt = (isset($_GET["dt"])) ? $_GET["dt"] : "";

$paramurl_company_name = __data_company($paramurl_company_id,"name");
$paramurl_department_name = __data_department($paramurl_department_id,"name");
$paramurl_df_th = __date($paramurl_df);
$paramurl_dt_th = __date($paramurl_dt);

$ck_paramurl_date = (__validdate($paramurl_df) && __validdate($paramurl_dt)) ? true : false;
$title = 'รายงานหัก ณ ที่จ่าย';
?>
<!DOCTYPE html>
<html>
<head>
	<?php include 'head.php'; ?>
</head>
<body>
	<?php include 'navbar.php'; ?>
	<section>
		<div class="container">
			
				<form method="POST" name="frmReportPaym" id="frmReportPaym" action="">
					<div class="row py-4 px-1" style="background-color: #e9ecef">
						<div class="col-md-12">
							<h3 class="mb-0">
								<i class="icofont-prescription"></i>&nbsp;&nbsp; <?=$title;?>
							</h3>
						</div>
					</div>
	
					<div class="row py-4 px-1" style="background-color: #FFFFFF">
						<div class="col-md-4 col-sm-12 mr-auto pt-1 pb-3"></div>
						<div class="col-md-4 col-sm-12 mr-auto pt-1 pb-3">
							<div class="card">
								<div class="card-header">
									<h5 class="mb-0">เลือกวันที่</h5>
								</div>
								<div class="card-body">
									<label>จากวันที่</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<i class="input-group-text">
												<i class="icofont-ui-calendar"></i>
											</i>
										</div>
										<input type="date" class="form-control" name="datefrom" id="datefrom" autocomplete="off" placeholder="กรอกวันที่ใบสำคัญจ่าย" value="<?=$paramurl_df;?>" autofocus>
									</div>
	
									<br>
	
									<label>ถึงวันที่</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<i class="input-group-text">
												<i class="icofont-ui-calendar"></i>
											</i>
										</div>
										<input type="date" class="form-control" name="dateto" id="dateto" autocomplete="off" placeholder="กรอกวันที่ใบสำคัญจ่าย" value="<?=$paramurl_dt;?>"  autofocus>
									</div>
	
									<br>
	
									<?= __html_select_dep($paramurl_company_id,"inputGroupSelectDep");  ?>
								</div>
	
								<div class="card-footer text-center">
									<input type="button" class="btn btn-success form-control" name="btnSearch" id="btnSearch" value="ค้นหา" />
								</div>
							</div>
						</div>
	
						<div class="col-md-4 col-sm-12 mr-auto pt-1 pb-3"></div>
					</div>
				</form>
				
				<script type="text/javascript">
					$(document).ready(function() {
						$("#btnSearch").click(function() {
							var dep =  $('#inputGroupSelectDep');
							var dateFrom = $('#datefrom');
							var dateTo = $('#dateto');
							
							if(dateFrom.val() == '') {
								swal({
									title: "กรุณาเลือกวันที่",
									text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
									type: "warning",
									closeOnClickOutside: false
								},function() {
									dateFrom.focus();
								});
							} else if(dateTo.val() == '') {
								swal({
									title: "กรุณาเลือกวันที่",
									text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
									type: "warning",
									closeOnClickOutside: false
								},function() {
									dateTo.focus();
								});
							} else {
								window.location = "report_taxcer_desc.php?cid=<?=$paramurl_company_id;?>"  + "&dep=" + dep.val() + "&df=" + dateFrom.val() + "&dt="+ dateTo.val();
							}
						});
					});
				</script>

	
		</div>
	</section>
	<?php include 'footer.php'; ?>
	</body>
</html>