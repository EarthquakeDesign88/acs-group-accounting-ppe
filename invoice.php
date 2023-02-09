<?php
include 'config/config.php'; 
__check_login();

$user_id = __session_user("id");
$user_level_id = __session_user("level_id");
$user_department_id = __session_user("department_id");

$paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
$paramurl_department_id = (isset($_GET["dep"])) ?$_GET["dep"] : 0;

$authority_comp_count_dep = __authority_company_count_department($user_id,$paramurl_company_id);
$authority_dep_text_list = __authority_department_text_list($user_id,$paramurl_company_id);
$authority_dep_check = __authority_department_check($user_id,$paramurl_company_id,$paramurl_department_id);
$arrDepAll = __authority_department_list($user_id,$paramurl_company_id);

$invoice_step = __invoice_step_company_list($user_id,$paramurl_company_id,$paramurl_department_id);
$html_title = '<b>รายจ่าย</b><i class="icofont-caret-right"></i> ใบแจ้งหนี้ <i class="icofont-caret-right"></i> เลือกฝ่าย';
$arrInvoiceStep = $invoice_step;
$valueInvoiceStep = $arrInvoiceStep["invoice"];
$invoice_step_name = $valueInvoiceStep["name"];
$invoice_step_class_box = $valueInvoiceStep["class_box"];
$invoice_step_icon = $valueInvoiceStep["icon"];
$invoice_step_page = $valueInvoiceStep["page"];
$invoice_step_con_where = $valueInvoiceStep["query_where"];
$html_dep_box = __html_dep_box($html_title,$invoice_step_page,$invoice_step_icon," AND ".$invoice_step_con_where,$arrDepAll,str_replace("FROM", "", __invoice_query_from()),"i.inv_depid");

__page_seldep($html_dep_box);
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

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<link rel="stylesheet" type="text/css" href="css/checkbox.css">

	<style type="text/css">
		.table .thead-light th {
			color: #000;
		}
		tr:nth-last-child(n) {
			border-bottom: 1px solid #dee2e6;
		}
		th>.truncate, td>.truncate{
			width: auto;
			min-width: 0;
			max-width: 360px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		th>.truncate-id, td>.truncate-id{
			width: auto;
			min-width: 0;
			max-width: 170px;
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
					<div class="col-md-12 pb-4">
						<h3 class="mb-0">
							<i class="icofont-papers"></i>&nbsp;&nbsp;ใบแจ้งหนี้
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
					</div>

					<div class="col-md-6 text-right">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-3 col-form-label text-right">
										เรียงลำดับตาม : 
									</label>
									<div class="col-sm-5">
										<select class="custom-select form-control" id="FilterBy">
											<option value="inv_id" selected>ลำดับที่</option>
											<option value="inv_no">เลขที่ใบแจ้งหนี้</option>
											<option value="inv_duedate">วันที่ครบชำระ</option>
										</select>
										<input type="text" class="form-control d-none" name="FilBy" id="FilBy" value="inv_id">
										
									</div>
									<div class="col-sm-4">
										<div class="input-group">
											<select class="custom-select form-control" id="FilterVal">
												<option value="DESC" selected>มากไปน้อย</option>
												<option value="ASC">น้อยไปมาก</option>
											</select>
											<input type="text" class="form-control d-none" name="FilVal" id="FilVal" value="DESC">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="row text-right">
							<?php if ($authority_comp_count_dep>=2) { ?>
							<div class="col-md-12 mb-4">
								<a href="invoice.php?cid=<?=$cid;?>" type="button" class="btn btn-warning" name="btnBack" id="btnBack">
									<i class="icofont-history"></i>&nbsp;&nbsp;ย้อนกลับ
								</a>
							</div>
							<?php  } ?>
						</div>
					</div>

					<div class="col-md-12" id="SearchInv">
						<div class="row">
							<div class="col-md-2">
								<label class="mt-1">ฝ่าย</label>
								<div class="input-group">
									<?php
										$str_sql = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
										$obj_rs = mysqli_query($obj_con, $str_sql);
										$obj_row = mysqli_fetch_array($obj_rs);
									?>
									<input type="text" class="form-control" name="depname" id="depname" value="<?=$obj_row["dep_name"];?>" readonly style="background-color: #FFF">
									<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$dep;?>">
								</div>
							</div>

							<div class="col-md-10">
								<div class="row">
									<div class="col-auto">
										<label class="mt-1">ค้นหาโดย : </label>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchINVBy" id="INVinvno" value="inv_no" checked="checked">
											<label for="INVinvno"><span>เลขที่ใบแจ้งหนี้</span></label>
										</div>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchINVBy" id="INVpayaname" value="paya_name">
											<label for="INVpayaname"><span>ชื่อบริษัทผู้ให้บริการ</span></label>
										</div>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchINVBy" id="INVRunNumber" value="inv_runnumber">
											<label for="INVRunNumber"><span>Run Number</span></label>
										</div>
									</div>
									<div class="col-md-3 mb-0"></div>
									<input type="text" class="form-control d-none" name="SearchINVVal" id="SearchINVVal" value="inv_no">
								</div>

								<div class="input-group">
									<input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกเลขที่ใบแจ้งหนี้ที่ต้องการค้นหา" autocomplete="off">
									<div class="input-group-append">
										<a href="invoice_add.php?cid=<?=$cid;?>&dep=<?=$dep;?>" class="btn btn-primary float-right">
											<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มใบแจ้งหนี้
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<?php if ($obj_row_user["user_levid"] == '5' || $obj_row_user["user_levid"] == '6') { ?>
						<div class="table-responsive" id="invoiceShow"></div>
						<?php } else { ?>
						<div class="table-responsive" id="invoiceCEOACCShow"></div>
						<?php } ?>
					</div>
				</div>

			</form>
			
		</div>
	</section>

	<!-- START VIEW INVOICE -->
	<div id="dataInvoice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dataInvoiceLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">รายละเอียดใบแจ้งหนี้</h3>
					<button type="button" class="close" name="invid_cancel" id="invid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body" id="invoice_detail">
				</div>
			</div>
		</div>
	</div>
	<!-- END VIEW INVOICE -->

	<script type="text/javascript">
		$(document).ready(function() {

			//------ START INVOICE ------//
			load_data(1);
			function load_data(page, query = '', queryDep = '', queryComp = '', queryFil = '', queryFilVal = '', querySearch = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var querySearch = $('#SearchINVVal').val();
				$.ajax({
					url:"fetch_invoice.php",
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep, queryComp:queryComp, queryFil:queryFil, queryFilVal:queryFilVal, querySearch:querySearch},
					success:function(data) {
						$('#invoiceShow').html(data);
					}
				});				
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchINVVal').val();
				load_data(page, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

			$('#search_box').keyup(function(){
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchINVVal').val();
				load_data(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

			$('#FilterBy').change(function(){
				$('#FilBy').val($('#FilterBy').val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchINVVal').val();
				load_data(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

			$('#FilterVal').change(function(){
				$('#FilVal').val($('#FilterVal').val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchINVVal').val();
				load_data(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

			$("input[name='SearchINVBy']").click(function(){
				$('#SearchINVVal').val($("input[name='SearchINVBy']:checked").val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchINVVal').val();
				load_data(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});
			//------ END INVOICE ------//




			//------ START INVOICE CEO-ACC ------//
			load_dataCEOACC(1);
			function load_dataCEOACC(page, query = '', queryDep = '', queryComp = '', queryFil = '', queryFilVal = '', querySearch = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var querySearch = $('#SearchINVVal').val();
				$.ajax({
					url:"fetch_invoice_ceo_acc.php",
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep, queryComp:queryComp, queryFil:queryFil, queryFilVal:queryFilVal, querySearch:querySearch},
					success:function(data) {
						$('#invoiceCEOACCShow').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchINVVal').val();
				load_dataCEOACC(page, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

			$('#search_box').keyup(function() {
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchINVVal').val();
				load_dataCEOACC(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

			$('#SelectDep').change(function() {
				$('#depid').val($('#SelectDep').val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchINVVal').val();
				load_dataCEOACC(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

			$('#FilterBy').change(function(){
				$('#FilBy').val($('#FilterBy').val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchINVVal').val();
				load_dataCEOACC(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

			$('#FilterVal').change(function(){
				$('#FilVal').val($('#FilterVal').val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchINVVal').val();
				load_dataCEOACC(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

			$("input[name='SearchINVBy']").click(function(){
				$('#SearchINVVal').val($("input[name='SearchINVBy']:checked").val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchINVVal').val();
				load_dataCEOACC(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});
			//------ END INVOICE CEO-ACC ------//




			//------ START VIEW INVOICE ------//
			$(document).on('click', '.view_data', function(){
				var id = $(this).attr("id");
				if(id != '') {
					$.ajax({
						url:"v_invoice.php",
						method:"POST",
						data:{id:id},
						success:function(data){
							$('#invoice_detail').html(data);
							$('#dataInvoice').modal('show');
						}
					});
				}
			});
			//------ END VIEW INVOICE ------//





			//------ START DELETE INVOICE ------//
			$(document).on('click', '.delete_data', function(){
				event.preventDefault();
				var del_id = $(this).attr("id");
				var element = this;
				swal({
					title: "ลบรายการนี้หรือไม่?",
					text: "เมื่อรายการนี้ถูกลบ คุณไม่สามารถกู้คืนได้!",
					type: "warning",
					showCancelButton: true,
					confirmButtonText: "ตกลง",
					cancelButtonText: "ยกเลิก",
					confirmButtonClass: 'btn btn-danger',
					cancelButtonClass: 'btn btn-secondary',
					closeOnConfirm: false,
					closeOnCancel: false
				},
				function(isConfirm) {
					if (isConfirm) {
						$.ajax({
							url: "r_invoice_delete.php",
							type: "POST",
							data: {del_id:del_id},
							success: function () {
								setTimeout(function () {
									swal({
										title: "ลบข้อมูลสำเร็จ!",
										text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
										type: 'success'
									});
									$(element).closest('tr').fadeOut(800,function(){
										$(this).remove();
										
									});
								}, 1000);
							}
						});
					} else {
						swal({
							title: "ยกเลิก",
							text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
							type: 'error'
						});
					}
				});
			});
			//------ END DELETE INVOICE ------//
		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>