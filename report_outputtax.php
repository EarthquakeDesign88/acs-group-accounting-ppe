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

			<form method="POST" name="frmOutputTax" id="frmOutputTax" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-prescription"></i>&nbsp;&nbsp;ออกรายงานภาษีขาย
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
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
									<input type="date" class="form-control" name="datefrom" id="datefrom" autocomplete="off" placeholder="กรอกวันที่ใบสำคัญจ่าย" autofocus>
								</div>

								<br>

								<label>ถึงวันที่</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-ui-calendar"></i>
										</i>
									</div>
									<input type="date" class="form-control" name="dateto" id="dateto" autocomplete="off" placeholder="กรอกวันที่ใบสำคัญจ่าย" autofocus>
								</div>

								<br>

								<div class="input-group">
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="checkVat" id="checkVat" value="vat" checked>
										<label class="form-check-label" for="inlineRadio1">VAT</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="checkVat" id="checkVat" value="non_vat">
										<label class="form-check-label" for="inlineRadio2">Non-VAT</label>
									</div>
								</div>

				

								<?php //if($cid == 'C001') { ?>

									<!-- <label>ฝ่าย</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<i class="input-group-text">
												<i class="icofont-company"></i>
											</i>
										</div>
										<select class="custom-select form-control" name="inputGroupSelectDep" id="inputGroupSelectDep">
											<option value="0" selected>เลือกฝ่าย</option>
											<?php
												$str_sql_dep = "SELECT * FROM department_tb AS d INNER JOIN company_tb AS c ON d.dep_compid = c.comp_id WHERE comp_id = '". $cid ."' AND dep_id <> 'D001' AND dep_id <> 'D008' AND dep_id <> 'D009' ORDER BY dep_name";
												$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
												while($obj_row_dep = mysqli_fetch_array($obj_rs_dep)) {
											?>
											<option value="<?=$obj_row_dep["dep_id"];?>">
												<?=$obj_row_dep["dep_name"];?>
											</option>
											<?php } ?>
										</select>
									</div> -->

								<?php //} else { ?>

									<input type="text" class="form-control d-none" name="inputGroupSelectDep" id="inputGroupSelectDep">

								<?php //} ?>
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
		$(document).ready(function () {

			$("#btnSearch").click(function() {
				if($('#datefrom').val() == '') {
					swal({
						title: "กรุณาเลือกวันที่",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmOutputTax.datefrom.focus();
					});
				} else if($('#dateto').val() == '') {
					swal({
						title: "กรุณาเลือกวันที่",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmOutputTax.dateto.focus();
					});
				} else {
					$.ajax({
						type: "POST",
						url: "r_report_outputtax.php",
						data: $("#frmOutputTax").serialize(),
						success: function(result) {
							if(result.status == 'isVat') {
								window.location = "report_outputtax_vat.php?cid=" + result.compid + "&dep=" + result.depid + "&df=" + result.dateFrom + "&dt="+ result.dateTo;
							} 
							else if(result.status == 'isNonVat') {
								window.location = "report_outputtax_nvat.php?cid=" + result.compid + "&dep=" + result.depid + "&df=" + result.dateFrom + "&dt="+ result.dateTo;
							}
							else {
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