<?php

	session_start();
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

	<style type="text/css">
		div#show-listCust {
			position: absolute;
			z-index: 99;
			width: 100%;
			margin-left: -15px!important;
		}
		.list-unstyled {
			position: relative;
			background-color:#FFFF;
			cursor:pointer;
			margin-left: 15px;
			margin-right: 15px;
			-webkit-box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
					box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
		}
		.list-group-item {
			font-family: 'Sarabun', sans-serif;
			cursor: pointer;
			border: 1px solid #eaeaea;
			list-style: none;
			top: 50%;
			padding: .75rem!important;
		}
		.list-group-item:hover {
			background-color: #f5f5f5;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmRptReceipt" id="frmRptReceipt" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-papers"></i>&nbsp;&nbsp;ออกรายงานใบเสร็จรับเงิน ( รายรับ )
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" value="<?=$dep;?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-2 col-sm-12 mr-auto pt-1 pb-3"></div>

					<div class="col-md-8 col-sm-12 mr-auto pt-1 pb-3">
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
									<input type="date" class="form-control" name="datefrom" id="datefrom" autocomplete="off" autofocus>
								</div>

								<br>

								<label>ถึงวันที่</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-ui-calendar"></i>
										</i>
									</div>
									<input type="date" class="form-control" name="dateto" id="dateto" autocomplete="off" autofocus>
								</div>

								<br>

								<script type="text/javascript">
									function putValueCust(name,id) {
										$('#searchCustomer').val(name);
										$('#custid').val(id);
									}
								</script>
								
								<script type="text/javascript" src="js/script_customer.js"></script>

								<label>บริษัทผู้รับบริการ</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-building"></i>
										</i>
									</div>
									<input type="text" name="searchCustomer" id="searchCustomer" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" tabindex="3" value="">

									<input type="text" class="form-control d-none" id="custid" name="custid" value="">

									<div class="input-group-append">
										<button type="button" class="btn btn-info" onclick="
										document.getElementById('searchCustomer').value = ''; 
										document.getElementById('custid').value = '';
										document.getElementById('searchCustomer').focus();
										document.getElementById('show-listCust').style.display = 'none';" title="Clear">
											<i class="icofont-close-circled"></i>
											<span class="descbtn">&nbsp;&nbsp;Clear</span>
										</button>
										<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddCustomer" data-backdrop="static" data-keyboard="false" title="เพิ่มบริษัท">
											<i class="icofont-plus-circle"></i>
											<span class="descbtn">&nbsp;&nbsp;เพิ่มบริษัท</span>
										</button>
									</div>
								</div>
								<div class="list-group" id="show-listCust"></div>
							</div>

							<div class="card-footer text-center">
								<input type="button" class="btn btn-success form-control" name="btnSearch" id="btnSearch" value="ค้นหา" />
							</div>
						</div>
					</div>

					<div class="col-md-2 col-sm-12 mr-auto pt-1 pb-3"></div>
				</div>

			</form>

		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function() {

			$("#btnSearch").click(function() {
				if($('#datefrom').val() == '') {
					swal({
						title: "กรุณาเลือกวันที่",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmRptReceipt.datefrom.focus();
					});
				} else if($('#dateto').val() == '') {
					swal({
						title: "กรุณาเลือกวันที่",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmRptReceipt.dateto.focus();
					});
				} else {
					$.ajax({
						type: "POST",
						url: "r_report_receipt.php",
						data: $("#frmRptReceipt").serialize(),
						success: function(result) {
							if(result.status == 1) {
								window.location = "report_receipt_desc.php?cid=" + result.compid + "&dep=" + result.depid + "&df=" + result.dateFrom + "&dt="+ result.dateTo +  "&custid=" + result.custid;
							} else {
								alert(result.dateFrom); 
							}
						}
					});
				}
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>