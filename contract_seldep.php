<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$projid = $_GET["projid"];

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

			<form method="POST" name="" id="" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-company"></i>&nbsp;&nbsp;เลือกฝ่าย ( ใบสำคัญจ่าย )
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">

					<?php
						$str_sql = "SELECT * FROM department_tb AS d INNER JOIN company_tb AS c ON d.dep_compid = c.comp_id WHERE comp_id = '". $cid ."' AND dep_id <> 'D001' AND dep_id <> 'D008' AND dep_id <> 'D009' ORDER BY dep_name";
						$obj_rs = mysqli_query($obj_con, $str_sql);
						while ($obj_row = mysqli_fetch_array($obj_rs)) {

							if ($obj_row["dep_name"] == "AD") {
								$depname = "ฝ่ายบริหารกลาง";
							} else {
								$depname = "ฝ่าย " . $obj_row["dep_name"];
							}
					?>

					<div class="col-md-3 my-3">
						<a href="contract.php?cid=<?=$cid;?>&dep=<?= $obj_row["dep_id"]; ?>&projid=<?=$projid;?>">
							<div class="card-counter <?= $obj_row["dep_name"]; ?>">
								<i class="icofont-paper"></i>
								<span class="count-title"><?= $depname; ?></span>
							</div>
						</a>
					</div>

					<?php } ?>

				</div>

			</form>

		</div>
	</section>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>