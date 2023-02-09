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

		$str_sql = "SELECT * FROM project_tb AS pj INNER JOIN department_tb AS d ON pj.proj_depid = d.dep_id WHERE proj_id = '". $projid ."'";
		$obj_rs = mysqli_query($obj_con, $str_sql);
		$obj_row = mysqli_fetch_array($obj_rs);

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<style type="text/css">
		.table .thead-light th {
			color: #000;
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
							<i class="icofont-file-alt"></i>&nbsp;&nbsp;สัญญาโครงการ
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">

					<div class="col-md-10 pt-1 pb-3">
						<label>ชื่อโครงการ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-building-alt"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="projname" id="projname" value="<?=$obj_row["proj_name"];?>" readonly>
							<input type="text" class="form-control d-none" name="projid" id="projid" value="<?=$projid;?>">
						</div>
					</div>

					<div class="col-md-2 pt-1 pb-3">
						<label>ฝ่าย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-building-alt"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="depname" id="depname" value="<?=$obj_row["dep_name"];?>" readonly>
							<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$dep;?>">
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label>มูลค่าสัญญา</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-baht"></i>
								</i>
							</div>
							<input type="text" class="form-control text-right" name="" id="showValue" value="0.00">
							<input type="text" class="form-control text-right d-none" name="" id="calValue" value="0.00">
						</div>
					</div>

					<?php if ($obj_row["dep_name"] == 'CM') { ?>

					<div class="col-md-9 pt-1 pb-3">
						<table class="table table-bordered">
							<thead class="thead-light">
								<tr>
									<th>ส่วน</th>
									<th>รายละเอียด</th>
									<th>จำนวนเงิน</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td width="15%">
										<input type="text" class="form-control text-center" name="partNo1" id="partNo1" value="1" readonly>
									</td>
									<td>
										<input type="text" class="form-control" name="partDesc1" id="partDesc1" value="">
									</td>
									<td width="20%">
										<input type="text" class="form-control text-right" name="partShowValue1" id="partShowValue1" value="0.00">
										<input type="text" class="form-control text-right d-none" name="partCalValue1" id="partCalValue1" value="0.00">
									</td>
								</tr>
								<tr>
									<td width="15%">
										<input type="text" class="form-control text-center" name="partNo2" id="partNo2" value="2" readonly>
									</td>
									<td>
										<input type="text" class="form-control" name="partDesc2" id="partDesc2" value="">
									</td>
									<td width="20%">
										<input type="text" class="form-control text-right" name="partShowValue2" id="partShowValue2" value="0.00">
										<input type="text" class="form-control text-right d-none" name="partCalValue2" id="partCalValue2" value="0.00">
									</td>
								</tr>
								<tr>
									<td width="15%">
										<input type="text" class="form-control text-center" name="partNo3" id="partNo3" value="3" readonly>
									</td>
									<td>
										<input type="text" class="form-control" name="partDesc3" id="partDesc3" value="">
									</td>
									<td width="20%">
										<input type="text" class="form-control text-right" name="partShowValue3" id="partShowValue3" value="0.00">
										<input type="text" class="form-control text-right d-none" name="partCalValue3" id="partCalValue3" value="0.00">
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<?php } else if ($obj_row["dep_name"] == 'ACSI') { ?>

					<div class="col-md-9 pt-1 pb-3">

					</div>

					<?php } ?>
					
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 text-center pb-4">
						<input type="button" class="btn btn-success px-5 py-2 mx-1" name="btnInsert" id="btnInsert" value="บันทึก">
					</div>
				</div>
				
			</form>

		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function() {

			function Comma(Num){
				Num += '';
				Num = Num.replace(/,/g, '');
				x = Num.split('.');
				x1 = x[0];
				x2 = x.length > 1 ? '.' + x[1] : '';
				var rgx = /(\d+)(\d{3})/;

				while (rgx.test(x1))
					x1 = x1.replace(rgx, '$1' + ',' + '$2');
				return x1 + x2;
			}

			document.getElementById("showValue").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calValue").value = this.value.replace(/,/g, "");
			}

			document.getElementById("partShowValue1").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("partCalValue1").value = this.value.replace(/,/g, "");
			}

			document.getElementById("partShowValue2").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("partCalValue2").value = this.value.replace(/,/g, "");
			}

			document.getElementById("partShowValue3").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("partCalValue3").value = this.value.replace(/,/g, "");
			}

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>