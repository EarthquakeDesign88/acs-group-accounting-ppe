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

			<form method="POST" name="frmInvoiceRcptCEO" id="frmInvoiceRcptCEO" action="">
				
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-money-bag"></i>&nbsp;&nbsp;สรุปรายจ่าย
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
							<!-- <div class="card-header">
								<h5 class="mb-0">เดือน</h5>
							</div> -->
							<div class="card-body">
								<label>เดือน</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-ui-calendar"></i>
										</i>
									</div>
									<select class="custom-select form-control" name="SelMonth" id="SelMonth">
										<option value="">กรุณาเลือกเดือน...</option>
										<option value="01">มกราคม</option>
										<option value="02">กุมภาพันธ์</option>
										<option value="03">มีนาคม</option>
										<option value="04">เมษายน</option>
										<option value="05">พฤษภาคม</option>
										<option value="06">มิถุนายน</option>
										<option value="07">กรกฎาคม</option>
										<option value="08">สิงหาคม</option>
										<option value="09">กันยายน</option>
										<option value="10">ตุลาคม</option>
										<option value="11">พฤศจิกายน</option>
										<option value="12">ธันวาคม</option>
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
				if($('#SelMonth').val() == '') {
					swal({
						title: "กรุณาเลือกเดือน",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmInvoiceRcptCEO.SelMonth.focus();
					});
				} else {
					$.ajax({
						type: "POST",
						url: "r_report_invoice_rcpt_ceo.php",
						data: $("#frmInvoiceRcptCEO").serialize(),
						success: function(result) {
							if(result.status == 1) {
								window.location = "report_invoice_rcpt_ceo_desc.php?cid=" + result.compid + "&dep=" + result.depid + "&m=" + result.SelMonth;
							} else {
								alert(result.SelMonth);
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