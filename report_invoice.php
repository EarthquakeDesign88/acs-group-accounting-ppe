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

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmRptINV" id="frmRptINV" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-papers"></i>&nbsp;&nbsp;ออกรายงานใบแจ้งหนี้ ( รายจ่าย )
						</h3>
					</div>
				</div>

				<input type="text" class="form-control d-none" name="compid" value="<?=$cid;?>">
				<input type="text" class="form-control d-none" name="depid" value="<?=$dep;?>">

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

								<label>สถานะการชำระเงิน</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-company"></i>
										</i>
									</div>
									<select class="custom-select form-control" name="SelectSTS" id="SelectSTS">
										<option value="" selected disabled>เลือกสถานะ...</option>
										<?php
											$str_sql_sts = "SELECT * FROM status_tb";
											$obj_rs_sts = mysqli_query($obj_con, $str_sql_sts);
											while($obj_row_sts = mysqli_fetch_array($obj_rs_sts)) {
										?>
										<option value="<?=$obj_row_sts["sts_id"];?>">
											<?=$obj_row_sts["sts_description"];?>
										</option>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="card-footer text-center">
								<input type="button" class="btn btn-success form-control" name="btnSearch" id="btnSearch" value="ค้นหา" />
							</div>
						</div>
					</div>

					<div class="col-md-4 col-sm-12 mr-auto pt-1 pb-3"></div>
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
						frmRptINV.datefrom.focus();
					});
				} else if($('#dateto').val() == '') {
					swal({
						title: "กรุณาเลือกวันที่",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmRptINV.dateto.focus();
					});
				} else {
					$.ajax({
						type: "POST",
						url: "r_report_invoice.php",
						data: $("#frmRptINV").serialize(),
						success: function(result) {
							if(result.status == 1) {
								window.location = "report_invoice_desc.php?cid=" + result.compid + "&dep=" + result.depid + "&df=" + result.dateFrom + "&dt="+ result.dateTo;
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