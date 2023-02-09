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

$html_title = '<b>รายจ่าย</b><i class="icofont-caret-right"></i> ออกเช็ค <i class="icofont-caret-right"></i> เลือกฝ่าย';
$icon = '<i class="icofont-paper"></i>';
$sql = " invoice_tb AS i 
INNER JOIN company_tb c ON i.inv_compid = c.comp_id 
INNER JOIN payable_tb p ON i.inv_payaid = p.paya_id 
INNER JOIN payment_tb paym ON i.inv_paymid = paym.paym_id 
INNER JOIN department_tb d ON i.inv_depid = d.dep_id ";
$con_where = "  paym.paym_typepay = 1 ";

$html_dep_box = __html_dep_box($html_title,"cheque.php",$icon," AND ".$con_where,$arrDepAll,$sql,"paym.paym_depid"," paym.paym_no");

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
			text-align: center;
		}
		tr:nth-last-child(n) {
			border-bottom: 1px solid #dee2e6;
		}
		th>.truncate, td>.truncate{
			width: auto;
			min-width: 0;
			max-width: 380px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		th>.truncate-id, td>.truncate-id{
			width: auto;
			min-width: 0;
			max-width: 150px;
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

				<div class="row py-4 px-1" style="background-color: #e9ecef">
					<div class="col-md-12 pb-4">
						<h3 class="mb-0">
							<i class="icofont-plus-circle"></i>&nbsp;&nbsp;ออกเช็ค
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$_GET["cid"];?>">
					</div>

					<div class="col-md-6 text-right" id="FilterPaym">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-3 col-form-label text-right">
										เรียงลำดับตาม : 
									</label>
									<div class="col-sm-5">
										<select class="custom-select form-control" id="FilterPaymBy">
											<option value="paym_id" selected>ลำดับที่</option>
											<option value="paym_no">เลขที่ใบสำคัญจ่าย</option>
										</select>
										<input type="text" class="form-control d-none" name="FilPaymBy" id="FilPaymBy" value="paym_id">
										
									</div>
									<div class="col-sm-4">
										<div class="input-group">
											<select class="custom-select form-control" id="FilterPaymVal">
												<option value="DESC" selected>มากไปน้อย</option>
												<option value="ASC">น้อยไปมาก</option>
											</select>
											<input type="text" class="form-control d-none" name="FilPaymVal" id="FilPaymVal" value="DESC">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="row text-right">
							<?php if ($obj_row_user["user_depid"] == 'D001') { ?>
							<div class="col-md-12 mb-4">
								<a href="cheque_seldep.php?cid=<?=$cid;?>&dep=" type="button" class="btn btn-warning" name="btnBack" id="btnBack">
									<i class="icofont-history"></i>&nbsp;&nbsp;ย้อนกลับ
								</a>
							</div>
							<?php } ?>
						</div>
					</div>

					<div class="col-md-12" id="SearchPaym">
						<div class="row">
							<div class="col-md-2">
								<label>ฝ่าย</label>
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
										<label>ค้นหาโดย : </label>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<input type="radio" name="SearchPaymBy" id="Paympaymno" value="paym_no" checked="checked">
											<label for="Paympaymno"><span>เลขที่ใบสำคัญจ่าย</span></label>
										</div>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchPaymBy" id="Paympayaname" value="paya_name">
											<label for="Paympayaname"><span>ชื่อบริษัทเจ้าหนี้</span></label>
										</div>
									</div>
									<input type="text" class="form-control d-none" name="SearchPaymVal" id="SearchPaymVal" value="paym_no">
								</div>

								<div class="input-group">
									<input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกเลขที่ใบสำคัญจ่ายที่ต้องการค้นหา" autocomplete="off">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="table-responsive" id="chequeShow"></div>
					</div>
				</div>

			</form>

		</div>
	</section>

	<!-- VIEW-PAYMENT -->
	<div id="dataPayment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dataPaymentLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">รายละเอียดใบสำคัญจ่าย</h3>
					<button type="button" class="close" name="paymid_cancel" id="paymid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body" id="payment_detail">
				</div>
			</div>
		</div>
	</div>
	<!-- END VIEW-PAYMENT -->

	<script type="text/javascript">
		$(document).ready(function() {

			//-- VIEW-INVOICE --//
			$(document).on('click', '.view_data', function(){
				var id = $(this).attr("id");
				if(id != '') {
					$.ajax({
						url:"v_payment.php",
						method:"POST",
						data:{id:id},
						success:function(data){
							$('#payment_detail').html(data);
							$('#dataPayment').modal('show');
						}
					});
				}
			});
			//--- END VIEW-INVOICE ---//

			load_data(1);
			function load_data(page, query = '', queryDep = '', queryComp = '', queryFilPaym = '', queryFilPaymVal = '', querySearch = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var querySearch = $('#SearchPaymVal').val();
				$.ajax({
					url:"fetch_cheque.php",
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep, queryComp:queryComp, queryFilPaym:queryFilPaym, queryFilPaymVal:queryFilPaymVal, querySearch:querySearch},
					success:function(data) {
						$('#chequeShow').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFilPaym = $('#FilPaymBy').val();
				var queryFilPaymVal = $('#FilPaymVal').val();
				var querySearchPaym = $("#SearchPaymVal").val();
				load_data(page, query, queryDep, queryComp, queryFilPaym, queryFilPaymVal, querySearchPaym);
			});

			$('#search_box').keyup(function() {
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFilPaym = $('#FilPaymBy').val();
				var queryFilPaymVal = $('#FilPaymVal').val();
				var querySearchPaym = $("#SearchPaymVal").val();
				load_data(1, query, queryDep, queryComp, queryFilPaym, queryFilPaymVal, querySearchPaym);
			});

			$('#SelectDep').change(function() {
				$('#depid').val($('#SelectDep').val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFilPaym = $('#FilPaymBy').val();
				var queryFilPaymVal = $('#FilPaymVal').val();
				var querySearchPaym = $("#SearchPaymVal").val();
				load_data(1, query, queryDep, queryComp, queryFilPaym, queryFilPaymVal, querySearchPaym);
			});

			$('#FilterPaymBy').change(function(){
				$('#FilPaymBy').val($('#FilterPaymBy').val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFilPaym = $('#FilPaymBy').val();
				var queryFilPaymVal = $('#FilPaymVal').val();
				var querySearchPaym = $("#SearchPaymVal").val();
				load_data(1, query, queryDep, queryComp, queryFilPaym, queryFilPaymVal, querySearchPaym);
			});

			$('#FilterPaymVal').change(function(){
				$('#FilPaymVal').val($('#FilterPaymVal').val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFilPaym = $('#FilPaymBy').val();
				var queryFilPaymVal = $('#FilPaymVal').val();
				var querySearchPaym = $("#SearchPaymVal").val();
				load_data(1, query, queryDep, queryComp, queryFilPaym, queryFilPaymVal, querySearchPaym);
			});

			$("input[name='SearchPaymBy']").click(function(){
				$('#SearchPaymVal').val($("input[name='SearchPaymBy']:checked").val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFilPaym = $('#FilPaymBy').val();
				var queryFilPaymVal = $('#FilPaymVal').val();
				var querySearchPaym = $("#SearchPaymVal").val();
				load_data(1, query, queryDep, queryComp, queryFilPaym, queryFilPaymVal, querySearchPaym);
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>