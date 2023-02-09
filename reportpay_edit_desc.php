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

		$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
		$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
		$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<style type="text/css">
		.table .thead-light th {
			color: #000;
		}
		tr:nth-last-child(n) {
			border-bottom: 1px solid #dee2e6;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">
			
			<form method="POST" name="" id="" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-papers"></i>
							&nbsp;&nbsp;แก้ไขสรุปรายการทำจ่าย ( ฝ่าย <?=$obj_row_dep["dep_name"];?> )
						</h3>
					</div>

					<div class="col-md-12">
						
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table">
								<thead class="thead-light">
									<tr>
										<th>วันที่สรุปยอดทำรายการจ่าย</th>
										<th class="text-center" width="10%">สถานะ</th>
										<th class="text-center" width="15%"></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$str_sql = "SELECT * FROM reportpay_tb1 WHERE repp_paydate = '0000-00-00' ORDER BY repp_id DESC";
										$obj_rs = mysqli_query($obj_con, $str_sql);
										while ($obj_row = mysqli_fetch_array($obj_rs)) {
											$reppid = $obj_row["repp_id"];
											if ($obj_row["repp_paydate"] == '0000-00-00') {
												$textPay = "รอตัดจ่าย";
												$styPay = "color: #F00; text-align: center; padding: 6px 0px; border-radius: .25rem; font-weight: 700;";
											}
									?>
									<tr>
										<td style="vertical-align: middle;">
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<?=$obj_row["repp_desc_summarize"];?>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										</td>
										<td>
											<div style="<?= $styPay; ?>">
												<?= $textPay; ?>
											</div>
										</td>
										<td>
											<div class="btn-group btn-group-justified" style="padding: 0px 12px; width: 100%;">
												<a href="reportpay_edit.php?cid=<?=$cid;?>&dep=<?=$dep;?>&reppid=<?=$reppid;?>" class="btn btn-warning" style="width: 100%;">
													<i class="icofont-edit"></i>&nbsp;&nbsp;แก้ไข
												</a>
												<!-- <button class="btn btn-primary">View</button> -->
											</div>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
			</form>

		</div>
	</section>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>