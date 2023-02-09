<?php

	session_start();
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

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmSelMonth" id="frmSelMonth" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-file-document"></i>&nbsp;&nbsp;เลือกออกใบแจ้งหนี้
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 pt-3 pb-5 text-center">
						<h3 class="text-center">อ้างอิงจากโครงการ</h3>
						<?php if ($_GET["cid"] == 'C001') { ?>
							<a href="project_seldep.php?cid=<?=$cid;?>&dep=<?=$dep;?>" class="btn btn-success mx-2" style="font-size: 20px; padding: 8px 50px;">ใช่</a>
							<a href="invoice_rcpt_seldep.php?cid=<?=$cid;?>&dep=<?=$dep;?>&projid=" class="btn btn-danger mx-2" style="font-size: 20px; padding: 8px 50px;">ไม่</a>
						<?php } else { ?>
							<a href="invoice_rcpt_project.php?cid=<?=$cid;?>&dep=<?=$dep;?>" class="btn btn-success mx-2" style="font-size: 20px; padding: 8px 50px;">ใช่</a>
							<a href="invoice_rcpt.php?cid=<?=$cid;?>&dep=<?=$dep;?>&projid=" class="btn btn-danger mx-2" style="font-size: 20px; padding: 8px 50px;">ไม่</a>
						<?php } ?>
					</div>
				</div>

			</form>

		</div>
	</section>

	<script type="text/javascript">
		// $('input[type="checkbox"]').on('change', function() {
		// 	$(this).siblings('input[type="checkbox"]').not(this).prop('checked', false);
		// });
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>