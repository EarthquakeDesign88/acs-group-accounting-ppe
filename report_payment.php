<?php
include 'config/config.php'; 
__check_login();
$paramurl_view = (isset($_GET["view"])) ? $_GET["view"] : "";
$paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
$paramurl_department_id = (isset($_GET["dep"])) ? $_GET["dep"] : "";
$paramurl_df = (isset($_GET["df"])) ? $_GET["df"] : "";
$paramurl_dt = (isset($_GET["dt"])) ? $_GET["dt"] : "";
$paramurl_company_name = __data_company($paramurl_company_id,"name");
$paramurl_department_name = __data_department($paramurl_department_id,"name");
$paramurl_df_th = __date($paramurl_df);
$paramurl_dt_th = __date($paramurl_dt);
$ck_paramurl_date = (__validdate($paramurl_df) && __validdate($paramurl_dt)) ? true : false;
$title = 'รายงานใบสำคัญจ่าย';
$array_param = $_GET;
if(isset($array_param["view"])){unset($array_param["view"]);}
array_walk($array_param,function(&$value,$key){$value = $key."=".$value;});
$param = implode("&", $array_param);
$encode_param = base64_encode($param);
$url = "report_payment.php";
$url_back  = $url."?".$param;
$url_view  = $url."?view=1&cid=".$paramurl_company_id;
$url_fetch = "fetch_report_payment_desc.php";
$url_print = "report_payment_print.php";
$title2  = $title.'<i class="icofont-caret-right"></i> ระหว่างวันที่ '. $paramurl_df_th.' ถึง '. $paramurl_dt_th.' <i class="icofont-caret-right"></i> ';
if($paramurl_department_id==""){
	$dep_list = __implode_department($paramurl_company_id);
	$title2 .= "ฝ่าย ".$dep_list;
}else{
	$title2 .=  "ฝ่าย ".$paramurl_department_name;
}
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
			
<!-------------------1 หน้าเลือกวันที่-------------------------->						
<?php if($paramurl_view==""){ ?>
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
								window.location = "<?=$url_view;?>"  + "&dep=" + dep.val() + "&df=" + dateFrom.val() + "&dt="+ dateTo.val();
							}
						});
			
					});
				</script>
<!-------------------END 1 หน้าเลือกวันที่-------------------------->					
				
<!-------------------2 หน้ารายการ-------------------------->					
<?php }else { ?>
				<?php  if(!$ck_paramurl_date){ ?>
					<div class="  py-4 text-center">
						<h3 class="text-danger ">รูปแบบวันที่ไม่ถูกต้อง</h3>
						<a href="report_payment.php?<?=$param?>" class="btn btn-outline-danger">เลือกข้อมูลใหม่อีกครั้ง</a>
					</dv>
				<?php }else{ ?>
				<form method="POST" action="javascript:void(0)">
					<div class="row py-4 px-1" style="background-color: #e9ecef">
						<div class="col-md-10 col-sm-12">
							<h4 class="mb-0">
								<i class="icofont-ui-note"></i> <?=$title2;?>
							</h4>
						</div>
						<div class="col-md-2 col-sm-12 text-right">
							<a href="<?=$url_back;?>" class="btn btn-warning  mb-1"><i class="icofont-history"></i> ย้อนกลับ</a>
						</div>
					</div>
					<div class="row py-4 px-1" style="background-color: #FFFFFF">
						<div class="col-md-12">
							<div class="table-responsive" id="RptPaymentDesc"></div>
						</div>
					</div>
					<div class="row py-4 px-1" style="background-color: #FFFFFF">
						<div class="col-md-12 text-center">
							<a href="<?=$url_print;?>?tmp=<?=$encode_param;?>" class="btn btn-success px-5 py-2" name="btnPrint" id="btnPrint">Print</a>
						</div>
					</div>
					
				</form>
				<script type="text/javascript">
					$(document).ready(function() {
						load_data(1);
						function load_data(page, query = '', queryComp = '', queryDep = '', df = '', dt = '') {
							var queryComp = '<?=$paramurl_company_id;?>';
							var queryDep = '<?=$paramurl_department_id;?>';
							var df =  '<?=$paramurl_df;?>';
							var dt = '<?=$paramurl_dt;?>';
							
							var title = '<?=base64_encode($title2);?>';
							
							$.ajax({
								url:"<?=$url_fetch;?>",
								method:"POST",
								data:{page:page, query:query, queryComp:queryComp, queryDep:queryDep, df:df, dt:dt,title:title},
								success:function(data) {
									$('#RptPaymentDesc').html(data);
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
				<?php } ?>
<?php } ?>	
<!-------------------END 2 หน้ารายการ-------------------------->				
	
		</div>
	</section>
	<?php include 'footer.php'; ?>
	</body>
</html>