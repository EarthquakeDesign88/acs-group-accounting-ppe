<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];

		$str_sql_user = "SELECT * FROM user_tb AS u INNER JOIN level_tb AS l ON u.user_levid = l.lev_id INNER JOIN department_tb AS d ON u.user_depid = d.dep_id WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql_comp = "SELECT * FROM company_tb WHERE comp_id = '". $cid ."'";
		$obj_rs_comp = mysqli_query($obj_con, $str_sql_comp);
		$obj_row_comp = mysqli_fetch_array($obj_rs_comp);

?>
<!DOCTYPE html>
<html>
<head>

	<?php include 'head.php'; ?>

	<style type="text/css">
		.table .thead-light th {
			color: #000;
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
		ul.pagination li.disabled {
			cursor: not-allowed;
		}

		.checkbox {
			width: 100%;
			margin: 5px auto;
			position: relative;
			display: inline;
		}
		.checkbox label {
			position: relative;
			min-height: 34px;
			display: block;
			padding-left: 40px;
			margin-bottom: 0;
			font-weight: normal;
			cursor: pointer;
		}
		.checkbox label span {
			position: absolute;
			top: 50%;
			transform: translateY(-50%);
			font-weight: 700;
		}
		.checkbox label:before {
			content: "";
			position: absolute;
			left: 0;
			top: 0;
			margin: 4px;
			width: 22px;
			height: 22px;
			transition: transform 0.28s ease;
			border-radius: 3px;
			border: 2px solid #34c37a;
			background-color: #FFFFFF;
		}
		.checkbox label:after {
			content: "";
			display: block;
			width: 10px;
			height: 5px;
			border-bottom: 2px solid #34c37a;
			border-left: 2px solid #34c37a;
			transform: rotate(-45deg) scale(0);
			transition: transform ease 0.25s;
			position: absolute;
			top: 12px;
			left: 10px;
		}
		.checkbox input[type=radio] {
			width: auto;
			opacity: 1e-8;
			position: absolute;
			left: 0;
			margin-left: -20px;
		}
		.checkbox input[type=radio]:checked ~ label:before {
			border: 2px solid #34c37a;
		}
		.checkbox input[type=radio]:checked ~ label:after {
			transform: rotate(-45deg) scale(1);
		}
		.checkbox input[type=radio]:focus + label::before {
			outline: 0;
		}

		/* The container */
		.container-chk {
			display: block;
			position: relative;
			padding-left: 1.25rem;
			margin-bottom: 1.55rem;
			margin-left: 0.16rem;
			cursor: pointer;
			font-size: 1.5rem;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}
		/* Hide the browser's default checkbox */
		.container-chk input {
			position: absolute;
			opacity: 0;
			cursor: pointer;
			height: 0;
			width: 0;
		}
		.container-checkbox input[type=checkbox] {
			display: none;
		}
		/* Create a custom checkbox */
		.checkmark {
			position: absolute;
			top: 0;
			left: 0;
			height: 1.5625rem;
			width: 1.5625rem;
			/*background-color: #ccc;*/
			background-color: transparent;
			width: 100%;
			height: 100%;
			cursor: pointer;
		}
		/* On mouse-over, add a grey background color */
		.container-chk:hover input ~ .checkmark {
			background-color: #ddd;
		}
		/* When the checkbox is checked, add a blue background */
		.container-chk input:checked ~ .checkmark {
			background-color: #2196F3;
		}
		/* Create the checkmark/indicator (hidden when not checked) */
		.checkmark:after {
			content: "";
			position: absolute;
			display: none;
		}
		/* Show the checkmark when checked */
		.container-chk input:checked ~ .checkmark:after {
			display: block;
		}
		/* Style the checkmark/indicator */
		.container-chk .checkmark:after {
			left: 10px;
			top: 7px;
			width: 5px;
			height: 10px;
			border: solid white;
			border-width: 0 3px 3px 0;
			-webkit-transform: rotate(45deg);
				-ms-transform: rotate(45deg);
					transform: rotate(45deg);
		}
		.list-group-item {
			padding: .75rem 1rem!important;
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
							<i class="icofont-papers"></i>&nbsp;&nbsp;ใบแจ้งหนี้อนุมัติแล้ว
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
							<?php if ($obj_row_user["user_depid"] == 'D001') { ?>
							<div class="col-md-12 mb-4">
								<a href="confirm_seldep.php?cid=<?=$cid;?>&dep=" type="button" class="btn btn-warning" name="btnBack" id="btnBack">
									<i class="icofont-history"></i>&nbsp;&nbsp;ย้อนกลับ
								</a>
							</div>
							<?php } ?>
						</div>
					</div>

					<div class="col-md-12" id="SearchInv">
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
											<input type="radio" name="SearchINVBy" id="INVinvno" value="inv_no" checked="checked">
											<label for="INVinvno"><span>เลขที่ใบแจ้งหนี้</span></label>
										</div>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchINVBy" id="INVpayaname" value="paya_name">
											<label for="INVpayaname"><span>ชื่อบริษัทเจ้าหนี้</span></label>
										</div>
									</div>
									<input type="text" class="form-control d-none" name="SearchINVVal" id="SearchINVVal" value="inv_no">
								</div>

								<div class="input-group">
									<input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกเลขที่ใบแจ้งหนี้ที่ต้องการค้นหา" autocomplete="off">
								</div>
							</div>
						</div>
					</div>

				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 pb-4">

						<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$dep;?>">
						
						<div class="table-responsive" id="PaymentConfirmShow"></div>

					</div>
				</div>
				
			</form>
			
		</div>
	</section>

	<!-- VIEW-INVOICE -->
	<div id="dataInvoice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dataInvoiceLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
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
	<!-- END VIEW-INVOICE -->

	<script type="text/javascript">
		$(document).ready(function() {

			//--- SHOW ---//
			load_data(1);
			function load_data(page, query = '', queryDep = '', queryComp = '', queryFil = '', queryFilVal = '', querySearch = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var querySearch = $('#SearchINVVal').val();
				$.ajax({
					url:"fetch_confirm_ceo.php",
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep, queryComp:queryComp, queryFil:queryFil, queryFilVal:queryFilVal, querySearch:querySearch},
					success:function(data) {
						$('#PaymentConfirmShow').html(data);
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



			//-- VIEW-INVOICE --//
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
			//--- END VIEW-INVOICE ---//

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>