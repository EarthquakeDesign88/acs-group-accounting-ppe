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

$html_title = '<b>รายจ่าย</b><i class="icofont-caret-right"></i> ใบจ่ายเงินสดย่อย <i class="icofont-caret-right"></i> เลือกฝ่าย';
$icon = '<i class="icofont-paper"></i>';
$sql = " pettycash_tb AS pcash ";
$con_where = " AND  pcash_statusmgr = 0 AND pcash_apprmgrno = ''  ";
$html_dep_box = __html_dep_box($html_title,'pettycash.php',$icon,$con_where,$arrDepAll,$sql,"pcash.pcash_depid");
	
__page_seldep($html_dep_box);
?>
<?php
	 if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
	 
	if (!$_SESSION["user_name"]){  //check session

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
							<i class="icofont-papers"></i>&nbsp;&nbsp;ใบจ่ายเงินสดย่อย
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
					</div>

					<div class="col-md-6 text-right" id="FilterInv">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-3 col-form-label text-left">
										เรียงลำดับตาม : 
									</label>
									<div class="col-sm-5">
										<select class="custom-select form-control" id="FilterBy">
											<option value="pcash_id" selected>ลำดับที่</option>
											<option value="pcash_no">เลขที่ใบจ่ายเงินสดย่อย</option>
											<option value="pcash_date">วันที่</option>
										</select>
										<input type="text" class="form-control d-none" name="FilBy" id="FilBy" value="pcash_id">
										
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
							<?php if ($_GET["cid"] == 'C001') { ?>
							<div class="col-md-12 mb-4">
								<a href="pettycash_seldep.php?cid=<?=$cid;?>&dep=" type="button" class="btn btn-warning" name="btnBack" id="btnBack">
									<i class="icofont-history"></i>&nbsp;&nbsp;ย้อนกลับ
								</a>
							</div>
							<?php } ?>
						</div>
					</div>

					<div class="col-md-12">
						<div class="row">
							<div class="col-md-2 mb-0">
								<label style="margin-top: .25rem">ฝ่าย</label>
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
									<div class="col-auto mb-0">
										<label style="margin-top: .25rem">ค้นหาโดย : </label>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchPCBy" id="PCpcashno" value="pcash_no" checked="checked">
											<label for="PCpcashno"><span>เลขที่ใบจ่ายเงินสดย่อย</span></label>
										</div>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchPCBy" id="PCpayaname" value="paya_name">
											<label for="PCpayaname"><span>ชื่อบริษัทเจ้าหนี้</span></label>
										</div>
									</div>
									<input type="text" class="form-control d-none" name="SearchPettyCash" id="SearchPettyCash" value="pcash_no">
								</div>

								<div class="input-group">
									<input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกเลขที่ใบจ่ายเงินสดย่อยที่ต้องการค้นหา" autocomplete="off">
									<div class="input-group-append">
										<a href="pettycash_add.php?cid=<?=$cid;?>&dep=<?=$dep;?>" class="btn btn-primary form-control" title="เพิ่ม / Add">
											<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มใบจ่ายเงินสดย่อย
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="table-responsive" id="PettyCashShow"></div>
					</div>
				</div>

			</form>

		</div>
	</section>

	<div id="dataPettyCash" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dataPettyCashLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">รายละเอียดใบแจ้งหนี้</h3>
					<button type="button" class="close" name="pcid_cancel" id="pcid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body" id="pettycash_detail">
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function() {

			//------ START VIEW INVOICE ------//
			$(document).on('click', '.view_data', function(){
				var id = $(this).attr("id");
				if(id != '') {
					$.ajax({
						url:"v_pettycash.php",
						method:"POST",
						data:{id:id},
						success:function(data){
							$('#pettycash_detail').html(data);
							$('#dataPettyCash').modal('show');
						}
					});
				}
			});
			//------ END VIEW INVOICE ------//

			//--- SHOW ADD ---//
			load_dataAdd(1);
			function load_dataAdd(page, query = '', queryDep = '', queryComp = '', queryFil = '', queryFilVal = '', querySearch = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var querySearch = $('#SearchPettyCash').val();

				$.ajax({
					url:"fetch_pettycash.php", 
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep, queryComp:queryComp, queryFil:queryFil, queryFilVal:queryFilVal, querySearch:querySearch},
					success:function(data) {
						$('#PettyCashShow').html(data);
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
				var querySearch = $('#SearchPettyCash').val();
				load_dataAdd(page, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

			$('#search_box').keyup(function() {
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchPettyCash').val();
				load_dataAdd(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

			$('#FilterBy').change(function(){
				$('#FilBy').val($('#FilterBy').val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchPettyCash').val();
				load_dataAdd(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

			$('#FilterVal').change(function(){
				$('#FilVal').val($('#FilterVal').val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchPettyCash').val();
				load_dataAdd(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

			$("input[name='SearchPCBy']").click(function(){
				$('#SearchPettyCash').val($("input[name='SearchPCBy']:checked").val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryFil = $('#FilBy').val();
				var queryFilVal = $('#FilVal').val();
				var querySearch = $('#SearchPettyCash').val();
				load_dataAdd(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>