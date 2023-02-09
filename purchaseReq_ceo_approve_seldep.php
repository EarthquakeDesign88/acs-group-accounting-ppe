<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];

		$str_sql_user = "SELECT * FROM user_tb AS u INNER JOIN level_tb AS l ON u.user_levid = l.lev_id INNER JOIN department_tb AS d ON u.user_depid = d.dep_id WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		if ($obj_row_user["lev_id"] == '3' || $obj_row_user["lev_id"] == '4' || $obj_row_user["lev_id"] == '6' || $obj_row_user["lev_id"] == '7') {

?>

			<script type="text/javascript">
				function chkAccess() {
					swal({
						title: 'ขออภัย คุณไม่มีสิทธิ์เข้าถึงข้อมูล!',
						text: 'กรุณากด ตกลง เพื่อดำเนินการต่อ',
						type: 'warning',
						closeOnClickOutside: false
					},function() {
						setTimeout(function(){
							window.history.back();
						});
					});
				}
			</script>

<?php } else { ?>

			<script type="text/javascript">
				function chkAccess() {}
			</script>

<?php } ?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

</head>
<body onload="chkAccess();">

	<?php include 'navbar.php'; ?>

	<?php if ($obj_row_user["lev_id"] == '3' || $obj_row_user["lev_id"] == '4' || $obj_row_user["lev_id"] == '6' || $obj_row_user["lev_id"] == '7') { } else { ?>

	<section>
		<div class="container">
			
			<form method="POST" name="" id="" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-company"></i>&nbsp;&nbsp;เลือกฝ่าย ( ขอซื้อ/ขอจ้าง )
						</h3>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="cid" id="cid" value="<?=$cid;?>">
					</div>

					<?php
						$str_sql = "SELECT * FROM department_tb AS d INNER JOIN company_tb AS c ON d.dep_compid = c.comp_id WHERE comp_id = '". $cid ."' AND dep_id <> 'D001' AND dep_id <> 'D008' AND dep_id <> 'D009' ORDER BY dep_name";
						$obj_rs = mysqli_query($obj_con, $str_sql);
						while ($obj_row = mysqli_fetch_array($obj_rs)) {

							$str_sql_pr = "SELECT * FROM purchasereq_tb WHERE purc_depid = '". $obj_row["dep_id"] ."' AND purc_no = ''";
							$obj_rs_pr = mysqli_query($obj_con, $str_sql_pr);
							$obj_row_pr = mysqli_fetch_array($obj_rs_pr);
							$count = mysqli_num_rows($obj_rs_pr);

							if ($obj_row["dep_name"] == "AD") {
								$depname = "ฝ่ายบริหารกลาง";
							} else {
								$depname = "ฝ่าย " . $obj_row["dep_name"];
							}
					?>

					<div class="col-md-3 my-3">
						<a href="purchaseReq_ceo_approve.php?cid=<?=$cid;?>&dep=<?= $obj_row["dep_id"]; ?>">
							<div class="card-counter <?= $obj_row["dep_name"]; ?>">
								<i class="icofont-papers"></i>
								<span class="count-title"><?= $depname; ?></span>
								<span class="count-numbers"><?= $count; ?></span>
								<span class="count-name">รายการ</span>
							</div>
						</a>
					</div>

					<?php } ?>
				</div>
				
			</form>

		</div>
	</section>

	<?php } ?>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>