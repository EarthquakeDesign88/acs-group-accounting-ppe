<?php
include 'config/config.php'; 
__check_login();
__page_seldep2('<b>รายจ่าย</b><i class="icofont-caret-right"></i> หัก ณ ที่จ่าย  <i class="icofont-caret-right"></i> ใบสำคัญจ่าย <i class="icofont-caret-right"></i> เลือกฝ่าย');
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

		th>.truncate-id, td>.truncate-id {
			width: auto;
			min-width: 0;
			max-width: 180px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		th>.truncate, td>.truncate {
			width: auto;
			min-width: 0;
			max-width: 500px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}

		button:focus,
		input:focus,
		textarea:focus,
		select:focus {
			outline: none; 
		}
		.tabs {
			display: block;
			display: -webkit-flex;
			display: -moz-flex;
			display: flex;
			-webkit-flex-wrap: wrap;
			   -moz-flex-wrap: wrap;
			   		flex-wrap: wrap;
			margin: 0;
			overflow: hidden;
			width: 100%;
		}
		.tabs [class^="tab"] label.title,
		.tabs [class*=" tab"] label.title {
			color: #000;
			cursor: pointer;
			display: block;
			font-size: 1.25em;
			font-weight: 700;
			line-height: 1em;
			padding: 1.25rem 0;
			text-align: center;
			margin-bottom: 0;
			background-color: #e9ecef;
		}
		.tabs [class^="tab"] [type="radio"],
		.tabs [class*=" tab"] [type="radio"] {
			border-bottom: 1px solid rgba(204, 204, 204, 1);
			cursor: pointer;
			-webkit-appearance: none;
			   -moz-appearance: none;
					appearance: none;
			display: block;
			width: 100%;
			-webkit-transition: all 0.3s ease-in-out;
			   -moz-transition: all 0.3s ease-in-out;
				 -o-transition: all 0.3s ease-in-out;
					transition: all 0.3s ease-in-out; 
		}
		.tabs [class^="tab"] [type="radio"]:hover, 
		.tabs [class^="tab"] [type="radio"]:focus,
		.tabs [class*=" tab"] [type="radio"]:hover,
		.tabs [class*=" tab"] [type="radio"]:focus {
			border-bottom: 1px solid #28a7e9;
		}
		.tabs [class^="tab"] [type="radio"]:checked,
		.tabs [class*=" tab"] [type="radio"]:checked {
			margin-top: -2px;
			border-bottom: 3px solid #28a7e9;
		}
		.tabs [class^="tab"] [type="radio"]:checked + div,
		.tabs [class*=" tab"] [type="radio"]:checked + div {
			opacity: 1;
			border: 1px solid #CCC;
			padding: 1rem;
			border-top: none;
		}
		.tabs [class^="tab"] [type="radio"] + div,
		.tabs [class*=" tab"] [type="radio"] + div {
			display: block;
			opacity: 0;
			padding: 2rem 0;
			width: 90%;
			-webkit-transition: all 0.3s ease-in-out;
			   -moz-transition: all 0.3s ease-in-out;
				 -o-transition: all 0.3s ease-in-out;
					transition: all 0.3s ease-in-out;
		}
		.tabs .tab-2 {
			width: 50%; 
		}
		.tabs .tab-2 [type="radio"] + div {
			width: 200%;
			margin-left: 200%; 
		}
		.tabs .tab-2 [type="radio"]:checked + div {
			margin-left: 0;
		}
		.tabs .tab-2:last-child [type="radio"] + div {
			margin-left: 100%;
		}
		.tabs .tab-2:last-child [type="radio"]:checked + div {
			margin-left: -100%;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmAddTaxcerPaym" id="frmAddTaxcerPaym" action="">

				<div class="row py-4 px-1" style="background-color: #e9ecef">
					<div class="col-md-6 mb-4">
						<h3 class="mb-0">
							<i class="icofont-ui-note"></i>&nbsp;&nbsp;หนังสือรับรองการหักภาษี ณ ที่จ่าย
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
					</div>

					<div class="col-md-6">
						<div class="row text-right">
							<?php if ($obj_row_user["user_depid"] == 'D001') { ?>
							<div class="col-md-12">
								<a href="taxcer_payment_seldep.php?cid=<?=$cid;?>&dep=" type="button" class="btn btn-warning" name="btnBack" id="btnBack">
									<i class="icofont-history"></i>&nbsp;&nbsp;ย้อนกลับ
								</a>
							</div>
							<?php } ?>
						</div>
					</div>

					<div class="col-md-12" id="SearchAddTaxc">
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
									<div class="col-md-3">
										<div class="checkbox">
											<input type="radio" name="SearchPaymBy" id="Paymno" value="paym_no" checked="checked">
											<label for="Paymno"><span>เลขที่ใบสำคัญจ่าย</span></label>
										</div>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchPaymBy" id="Paympayaname" value="paya_name">
											<label for="Paympayaname"><span>ชื่อบริษัทผู้ให้บริการ</span></label>
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

					<div class="col-md-12" id="SearchEditTaxc" style="display: none;">
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
									<div class="col-md-3">
										<div class="checkbox">
											<input type="radio" name="SearchTaxcBy" id="Taxcno" value="taxc_no" checked="checked">
											<label for="Taxcno"><span>เล่มที่/เลขที่หัก ณ ที่จ่าย</span></label>
										</div>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchTaxcBy" id="Taxcpayaname" value="taxc_name">
											<label for="Taxcpayaname"><span>ชื่อบริษัทผู้ให้บริการ</span></label>
										</div>
									</div>
									<input type="text" class="form-control d-none" name="SearchTaxcVal" id="SearchTaxcVal" value="taxc_no">
								</div>

								<div class="input-group">
									<input type="text" name="searchTaxc_box" id="searchTaxc_box" class="form-control" placeholder="กรอกเล่มที่/เลขที่หัก ณ ที่จ่ายที่ต้องการค้นหา" autocomplete="off">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="tabs">
							<div class="tab-2">
								<label class="title" for="tab2-1">เพิ่ม</label>
								<input id="tab2-1" name="tabs-two" type="radio" checked="checked">
								<div>

									<div class="table-responsive d-none">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>ลำดับที่</th>
													<th>INV ID</th>
													<th>เลขที่ใบแจ้งหนี้</th>
													<th>สถานะ Taxcer</th>
													<th>รหัสสถานะ Taxcer</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$str_sql_add = "SELECT DISTINCT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id INNER JOIN user_tb AS u ON i.inv_userid_create = u.user_id INNER JOIN payment_tb AS paym ON paym.paym_id = i.inv_paymid WHERE inv_rev = (SELECT MAX(inv_rev) AS inv_rev FROM invoice_tb WHERE inv_runnumber = i.inv_runnumber) AND paym_NostatusTaxcer = '' AND inv_compid = '". $cid ."' AND inv_depid = '".$dep."' GROUP BY inv_paymid ORDER BY paym_id DESC";
													$obj_rs_add = mysqli_query($obj_con, $str_sql_add);
													$i = 1;
													while ($obj_row_add = mysqli_fetch_array($obj_rs_add)) {
												?>
												<tr>
													<td>
														<input type="text" class="form-control" name="id<?=$i;?>" id="id<?=$i;?>" value="<?=$i;?>">
													</td>
													<td>
														<input type="text" class="form-control" name="paymid[]" id="paymid<?=$obj_row_add["paym_id"];?>" value="<?=$obj_row_add["paym_id"];?>">
													</td>
													<td>
														<input type="text" class="form-control" name="paymid<?=$i;?>" id="paymid<?=$obj_row_add["paym_id"];?>" value="<?=$obj_row_add["paym_no"];?>">
													</td>
													<td>
														<input type="text" class="form-control" name="stsPaym[]" id="stsPaym<?=$obj_row_add["paym_id"];?>" value="<?=$obj_row_add["paym_statusTaxcer"];?>">
													</td>
													<td>
														<input type="text" class="form-control" name="NostsPaym" id="NostsPaym<?=$obj_row_add["paym_id"];?>" value="<?=$obj_row_add["paym_NostatusTaxcer"];?>">
													</td>
												</tr>
												<?php $i++; } ?>
											</tbody>
										</table>
									</div>

									<div class="table-responsive" id="TaxcerPayment"></div>

									<div class="row py-4 px-1" style="background-color: #FFFFFF">
										<div class="col-md-12 text-center">
											<input type="button" class="btn btn-success px-5 py-2 mx-1" name="btnNextPaym" id="btnNextPaym" value="ถัดไป">
										</div>
									</div>
								</div>
							</div>

							<div class="tab-2">
								<label class="title" for="tab2-2">แก้ไข</label>
								<input id="tab2-2" name="tabs-two" type="radio">
								<div>

									<div class="table-responsive" id="TaxcerEditPayment"></div>

								</div>
							</div>
						</div>
					</div>
				</div>
				
			</form>
			
		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function(){

			$( "#tab2-1" ).click(function() {
				$("#SearchAddTaxc").css("display","block");
				$("#SearchEditTaxc").css("display","none");
			});

			$( "#tab2-2" ).click(function() {
				$("#SearchAddTaxc").css("display","none");
				$("#SearchEditTaxc").css("display","block");
			});

			$("#btnNextPaym").click(function() {
				var formData = new FormData(this.form);
				if($('#CountChkAll').val() == '0') {
					swal({
						title: "กรุณาเลือกใบสำคัญจ่ายที่ต้องการ \n อย่างน้อย 1 รายการ",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning"
					}).then(function() {
						frmAddTaxcerPaym.TaxcerPayment.focus();
					});
				} else {
					$.ajax({
						type: "POST",
						url: "r_taxcer_payment.php",
						// data: $("#frmAddTaxcerPaym").serialize(),
						data: formData,
						dataType: 'json',
						contentType: false,
						cache: false,
						processData:false,
						success: function(result) {
							if(result.status == 1) {
								window.location.href = result.url;
							} else {
								alert(result.message);
							}
						}
					});
				}
			});


			
			load_data(1);
			function load_data(page, query = '', queryDep = '', queryComp = '', querySearch = '') {
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var querySearch = $('#SearchPaymVal').val();
				$.ajax({
					url:"fetch_taxcer_payment.php",
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep, queryComp:queryComp, querySearch:querySearch},
					success:function(data) {
						$('#TaxcerPayment').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function(){
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var querySearch = $('#SearchPaymVal').val();
				load_data(page, query, queryDep, queryComp, querySearch);
			});

			$('#search_box').keyup(function(){
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var querySearch = $('#SearchPaymVal').val();
				load_data(1, query, queryDep, queryComp, querySearch);
			});

			$("input[name='SearchPaymBy']").click(function(){
				$('#SearchPaymVal').val($("input[name='SearchPaymBy']:checked").val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var querySearch = $("#SearchPaymVal").val();
				load_data(1, query, queryDep, queryComp, querySearch);
			});






			load_dataEdit(1);
			function load_dataEdit(page, query = '', queryDep = '', queryComp = '', querySearchTaxc = '') {
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var querySearchTaxc = $('#SearchTaxcVal').val();
				$.ajax({
					url:"fetch_taxcer_payment_edit.php",
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep, queryComp:queryComp, querySearchTaxc:querySearchTaxc},
					success:function(data) {
						$('#TaxcerEditPayment').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function(){
				var page = $(this).data('page_number');
				var query = $('#searchTaxc_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var querySearchTaxc = $('#SearchTaxcVal').val();
				load_dataEdit(page, query, queryDep, queryComp, querySearchTaxc);
			});

			$('#searchTaxc_box').keyup(function(){
				var query = $('#searchTaxc_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var querySearchTaxc = $('#SearchTaxcVal').val();
				load_dataEdit(1, query, queryDep, queryComp, querySearchTaxc);
			});

			$("input[name='SearchTaxcBy']").click(function(){
				$('#SearchTaxcVal').val($("input[name='SearchTaxcBy']:checked").val());
				var query = $('#searchTaxc_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var querySearchTaxc = $("#SearchTaxcVal").val();
				load_dataEdit(1, query, queryDep, queryComp, querySearchTaxc);
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>