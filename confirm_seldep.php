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

	<style type="text/css">
		article {
			max-width: 100%;
			overflow: hidden;
			margin: 0 auto;
		}
		.subtitle {
			margin: 0 0 2em 0;
		}
		.fancy span {
			display: inline-block;
			position: relative;
			font-size: 1.75rem;
			font-weight: 600;
			padding: 12px 8px;
		}
		.fancy span:after {
			content: "";
			position: absolute;
			height: 3px;
			background-color: #195f5f;
			border-top: 2px solid #195f5f;
			top: 50%;
			width: 1366px;
		}
		.fancy span:after {
			left: 100%;
			margin-left: 15px;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section class="home">
		<div class="container">

			<div class="row">
				<div class="col-md-12 mr-auto mb-5">
					<article>
						<p class="subtitle fancy">
							<span>ใบแจ้งหนี้ ( อนุมัติแล้ว )</span>
						</p>
					</article>

					<div class="row">

						<?php
							$str_sql = "SELECT * FROM department_tb AS d INNER JOIN company_tb AS c ON d.dep_compid = c.comp_id WHERE comp_id = '". $cid ."' AND dep_id <> 'D001' AND dep_id <> 'D008' AND dep_id <> 'D009' ORDER BY dep_name";
							$obj_rs = mysqli_query($obj_con, $str_sql);
							while ($obj_row = mysqli_fetch_array($obj_rs)) {

								$str_sql_iv = "SELECT * FROM invoice_tb WHERE inv_depid = '". $obj_row["dep_id"] ."' AND inv_statusMgr = 1 AND inv_apprMgrno <> '' AND inv_statusCEO = 1 AND inv_apprCEOno <> ''";
								$obj_rs_iv = mysqli_query($obj_con, $str_sql_iv);
								$obj_row_iv = mysqli_fetch_array($obj_rs_iv);
								$count = mysqli_num_rows($obj_rs_iv);

								if ($obj_row["dep_name"] == "AD") {
									$depname = "ฝ่ายบริหารกลาง";
								} else {
									$depname = "ฝ่าย " . $obj_row["dep_name"];
								}

						?>

						<div class="col-md-3 my-3">
							<a href="confirm_ceo.php?cid=<?=$cid;?>&dep=<?= $obj_row["dep_id"]; ?>">
								<div class="card-counter <?= $obj_row["dep_name"]; ?>">
									<i class="icofont-paper"></i>
									<span class="count-title"><?= $depname; ?></span>
									<span class="count-numbers"><?= $count; ?></span>
									<span class="count-name">รายการ</span>
								</div>
							</a>
						</div>

						<?php } ?>

					</div>

				</div>
			</div>

		</div>
	</section>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>