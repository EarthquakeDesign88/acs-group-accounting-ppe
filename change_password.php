<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		// $dep = $_GET["dep"];

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

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">
			
			<form method="POST" name="frmChangePass" id="frmChangePass" action="">

				<div class="row py-4 px-1" style="background-color: #e9ecef">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-lock"></i>&nbsp;&nbsp;เปลี่ยนแปลงรหัสผ่าน
						</h3>
					</div>
				</div>
				
				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="row">

							<input type="text" class="form-control d-none" name="userdepid" id="userdepid" value="<?=$obj_row_user["user_depid"];?>">
							<input type="text" class="form-control d-none" name="compid" id="compid" value="<?=$cid;?>">

							<div class="col-lg-3 col-md-3 pt-1 pb-3"></div>

							<div class="col-lg-6 col-md-6 pt-1 pb-3 row">
								<div class="col-lg-12 col-md-12 pt-1 pb-3">
									<label for="userid" class="mb-1">user id</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<i class="input-group-text">
												<i class="icofont-user"></i>
											</i>
										</div>
										<input type="text" class="form-control" name="userid" id="userid" autocomplete="off" value="<?=$_SESSION["user_id"];?>" readonly>
									</div>
								</div>

								<div class="col-lg-6 col-md-6 pt-1 pb-3">
									<label for="userpassCurrent" class="mb-1">รหัสผ่านปัจจุบัน</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<i class="input-group-text">
												<i class="icofont-lock"></i>
											</i>
										</div>
										<input type="password" class="form-control" name="userpassCurrent" id="userpassCurrent" autocomplete="off" placeholder="Current Password">
									</div>
								</div>

								<div class="col-lg-6 col-md-6 pt-1 pb-3"></div>

								<div class="col-lg-6 col-md-6 pt-1 pb-3">
									<label for="userpassNew" class="mb-1">รหัสผ่านใหม่</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<i class="input-group-text">
												<i class="icofont-lock"></i>
											</i>
										</div>
										<input type="password" class="form-control" name="userpassNew" id="userpassNew" autocomplete="off" placeholder="New Password">
									</div>
								</div>

								<div class="col-lg-6 col-md-6 pt-1 pb-3">
									<label for="userpassConfirm" class="mb-1">ยืนยันรหัสผ่านใหม่</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<i class="input-group-text">
												<i class="icofont-lock"></i>
											</i>
										</div>
										<input type="password" class="form-control" name="userpassConfirm" id="userpassConfirm" autocomplete="off" placeholder="Confirm New Password">
									</div>
								</div>

								<div class="col-lg-12 col-md-12" id="divalt" style="display: none;">
									<div id="alt" style="background-color: #d43f3a; color: #FFF; padding: 6px 12px; text-align: center; width: 100%">
									</div>
								</div>

							</div>

							<div class="col-lg-3 col-md-3 pt-1 pb-3"></div>
						</div>
					</div>
				</div>

				<div class="row pb-5 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 text-center">
						<input type="button" class="btn btn-success px-5 py-2 mx-1" title="บันทึก / Save" name="btnSave" id="btnSave" value="บันทึก">
					</div>
				</div>

			</form>

		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function() {

			$("#btnSave").click(function() {
				var formData = new FormData(this.form);
				// alert("OK");
				if($('#userpassCurrent').val() == '') {
					swal({
						title: "กรุณารหัสผ่านปัจจุบัน",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmChangePass.userpassCurrent.focus();
					});
				} else if($('#userpassNew').val() == '') {
					swal({
						title: "กรุณารหัสผ่านใหม่",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmChangePass.userpassNew.focus();
					});
				} else if($('#userpassConfirm').val() == '') {
					swal({
						title: "กรุณายืนยันรหัสผ่านใหม่",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmChangePass.userpassConfirm.focus();
					});
				} else {

					$.ajax({
						type: "POST",
						url: "r_change_password.php",
						// data: $("#frmChangePass").serialize(),
						data: formData,
						dataType: 'json',
						contentType: false,
						cache: false,
						processData:false,
						success: function(result) {
							if(result.status == 2) {
								swal({
									title: "บันทึกข้อมูลสำเร็จ",
									text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
									type: "success",
									closeOnClickOutside: false
								},function() {
									window.location.href = "logout.php";
								});
								// alert(result.message);
							} else if(result.status == 1) {
								// alert(result.message);
								document.getElementById('divalt').style.display = "block";
								document.getElementById('alt').innerHTML = result.message;
							} else {
								// alert(result.message);
								document.getElementById('divalt').style.display = "block";
								document.getElementById('alt').innerHTML = result.message;
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