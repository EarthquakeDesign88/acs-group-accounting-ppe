<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$reppdno = $_GET["reppdno"];

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
		$obj_rs = mysqli_query($obj_con, $str_sql);
		$obj_row = mysqli_fetch_array($obj_rs);

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
			
			<form method="POST" name="frmReportPay" id="frmReportPay" action="">
				
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-papers"></i>
							&nbsp;&nbsp;สรุปรายการทำจ่าย ( ฝ่าย <?=$obj_row["dep_name"];?> )
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
						<input type="text" class="form-control" name="useridCreate" value="<?=$obj_row_user["user_id"];?>">
						<input type="date" class="form-control" name="createDate" value="">
						<input type="text" class="form-control" name="useridEdit" value="">
						<input type="date" class="form-control" name="editDate" value="">
						<input type="text" class="form-control" name="reppdno" id="reppdno" value="<?=$reppdno;?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-4 py-2">
						<label for="repdate" class="mb-1">วันที่จัดทำ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="repdate" id="repdate" autocomplete="off">
						</div>
					</div>

					<div class="col-md-12 py-1">
						<div class="table-responsive">
							<table class="table table-bordered">
								<tbody style="background-color: #FFF;">
									<tr>
										<td colspan="2">
											<label for="txtsummarize" class="mb-1">สรุปยอด ณ </label>
											<div class="input-group">
												<input type="date" name="txtsummarize" id="txtsummarize" class="form-control" placeholder="กรอกวันที่สรุปยอด" autocomplete="off">
											</div>
										</td>
										<td colspan="2">
											<label for="txtTotalsummarize" class="mb-1">จำนวนเงิน</label>
											<div class="input-group">
												<input type="text" class="form-control text-right" name="txtTotalsummarize" id="txtTotalsummarize" placeholder="กรอกจำนวนเงินสรุปยอด" value="0.00" autocomplete="off">
												<input type="text" class="form-control summarize text-right d-none" name="txtTotalsummarizeHidden" id="txtTotalsummarizeHidden" placeholder="กรอกจำนวนเงินสรุปยอด" value="0.00">
											</div>
										</td>
									</tr>
								</tbody>


								<!-- บวก -->
								<tbody class="plus" id="dynamicfieldPlus" style="border-top: none;">
									<?php
										$str_sql_countplus = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 1 AND reppd_no = '" . $_GET["reppdno"] . "'";
										$obj_rs_countplus = mysqli_query($obj_con, $str_sql_countplus);
										$countChkPlus = mysqli_num_rows($obj_rs_countplus);

										$str_sql_plus = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 1 AND reppd_no = '" . $_GET["reppdno"] . "'";
										$obj_rs_plus = mysqli_query($obj_con, $str_sql_plus);
										$i = 1;
										while ($obj_row_plus = mysqli_fetch_array($obj_rs_plus)) {
											if ($i > 1) {
												$displayPlus = "style='display:none;'";
											} else {
												$displayPlus = "style='display:block'";
											}
									?>
									<tr id="plus1<?=$i;?>">
										<td class="d-none">
											<input type="text" class="form-control" name="repdidPlus<?=$i;?>" id="repdidPlus<?=$i;?>" value="<?=$obj_row_plus["reppd_id"];?>">
										</td>
										<td width="5%" >
											<label for="txtPlus<?=$i;?>" class="mb-1" <?=$displayPlus;?>>บวก</label>
											<input type="text" class="form-control d-none" id="txtPlus<?=$i;?>" name="txtPlus<?=$i;?>" value="<?=$obj_row_plus["reppd_type"];?>">
										</td>
										<td width="60%">
											<input type="text" class="form-control" id="txtdescPlus<?=$i;?>" name="txtdescPlus<?=$i;?>" autocomplete="off" value="<?=$obj_row_plus["reppd_description"];?>" readonly>
										</td>
										<td>
											<input type="text" class="form-control text-right" id="txttotalPlus<?=$i;?>" name="txttotalPlus<?=$i;?>" value="<?=number_format($obj_row_plus["reppd_amount"],2);?>" autocomplete="off" readonly>
											<input type="text" class="form-control amountPlus d-none" id="txttotalPlusHidden<?=$i;?>" name="txttotalPlusHidden<?=$i;?>" value="<?=$obj_row_plus["reppd_amount"];?>" autocomplete="off">
										</td>
									</tr>
									<?php 
											$i++;
										} 
									?>
									<input type="text" class="form-control d-none sumPlus" name="sumPlus" id="sumPlus">
								</tbody>
								<!-- บวก -->


								<!-- จ่าย -->
								<tbody class="dis" id="dynamicfieldDis" style="border-top: none;">
									<?php
										$str_sql_dis = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 2 AND reppd_no = '" . $_GET["reppdno"] . "'";
										$obj_rs_dis = mysqli_query($obj_con, $str_sql_dis);
										$i = 1;
										while ($obj_row_dis = mysqli_fetch_array($obj_rs_dis)) {
											if ($i > 1) {
												$displayDis = "style='display:none;'";
											} else {
												$displayDis = "style='display:block'";
											}
									?>
									<tr id="dis2<?=$i;?>">
										<td class="d-none">
											<input type="text" class="form-control" name="repdidDis<?=$i;?>" id="repdidDis<?=$i;?>" value="<?=$obj_row_dis["reppd_id"];?>">
										</td>
										<td width="5%">
											<label for="txtDis<?=$i;?>" class="mb-1" <?=$displayDis;?>>จ่าย</label>
											<input type="text" class="form-control d-none" id="txtDis<?=$i;?>" name="txtDis<?=$i;?>" value="<?=$obj_row_dis["reppd_type"];?>">
										</td>
										<td width="60%">
											<input type="text" class="form-control" id="txtdescDis<?=$i;?>" name="txtdescDis<?=$i;?>" autocomplete="off" value="<?=$obj_row_dis["reppd_description"];?>" readonly>
										</td>
										<td>
											<input type="text" class="form-control text-right" id="txttotalDis<?=$i;?>" name="txttotalDis<?=$i;?>" value="<?=number_format($obj_row_dis["reppd_amount"],2);?>" autocomplete="off" readonly>
											<input type="text" class="form-control amountDis d-none" id="txttotalDisHidden<?=$i;?>" name="txttotalDisHidden<?=$i;?>" value="<?=$obj_row_dis["reppd_amount"];?>" autocomplete="off">
										</td>
									</tr>
									<?php 
											$i++;
										} 
									?>
									<input type="text" class="form-control d-none sumDis" name="sumDis" id="sumDis">
								</tbody>
								<!-- จ่าย -->


								<tbody style="background-color: #FFF; border-top: none;">
									<tr>
										<td colspan="2">
											<label for="txtbalance" class="mb-1">ยอด ณ</label>
											<div class="input-group">
												<input type="date" name="txtbalance" id="txtbalance" class="form-control" placeholder="กรอกวันที่ยอดเหลือ" autocomplete="off">
											</div>
										</td>
										<td colspan="2">
											<label for="txtTotalbalance" class="mb-1">จำนวนเงิน</label>
											<div class="input-group">
												<input type="text" class="form-control text-right" name="txtTotalbalance" id="txtTotalbalance" placeholder="จำนวนเงิน" readonly>
												<input type="text" class="form-control text-right d-none" name="txtTotalbalanceHidden" id="txtTotalbalanceHidden" placeholder="จำนวนเงิน" readonly>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 pb-4 text-center">
						<input type="button" class="btn btn-success px-5 py-2" name="btnNext" id="btnNext" value="ถัดไป">
					</div>
				</div>


				<!-- <div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>เลขที่ใบสำคัญจ่าย</th>
										<th>รายละเอียด</th>
										<th>จำนวนเงิน</th>
										<th>สถานะ</th>
									</tr>
								</thead>
								<tbody>

									<?php
										$str_sql = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id WHERE inv_depid = '". $dep ."' GROUP BY inv_paymid";
										$obj_rs = mysqli_query($obj_con, $str_sql);
										$invnetamount = 0;
										while ($obj_row = mysqli_fetch_array($obj_rs)) {

											$str_sql_invpaym = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id WHERE inv_depid = '". $dep ."' AND inv_paymid = '" . $obj_row["inv_paymid"] . "'";
											$obj_rs_invpaym = mysqli_query($obj_con, $str_sql_invpaym);
											$invnetamount = 0;
											while ($obj_row_invpaym = mysqli_fetch_array($obj_rs_invpaym)) {
												$invnetamount = $obj_row_invpaym["inv_netamount"] + $invnetamount;
											}
									?>

									<tr>
										<td>
											<input type="text" class="form-control" name="" value="<?=$obj_row["paym_no"]?>">
										</td>
										<td>
											<input type="text" class="form-control" name="" value="<?=$obj_row["inv_description"]?>">
										</td>
										<td>
											<input type="text" class="form-control" name="netamount" id="netamount" value="<?=number_format($invnetamount,2);?>">
											<input type="text" class="form-control d-none" name="netamountHidden" id="netamountHidden" value="<?=$invnetamount;?>">
										</td>
										<td>
											<input type="text" class="form-control" name="">
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div> -->


			</form>

		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function(){

			$("#btnNext").click(function() {
				var formData = new FormData(this.form);
				$.ajax({
					type: "POST",
					url: "r_reportpay_add.php",
					// data: $("#frmReportPay").serialize(),
					data: formData,
					dataType: 'json',
					contentType: false,
					cache: false,
					processData:false,
					success: function(result) {
						if(result.status == 1) {
							swal({
								title: "บันทึกข้อมูลสำเร็จ",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ ",
								type: "success",
								closeOnClickOutside: false
							},function() {
								window.location.href = "reportpay_selpayment.php?cid=" + result.compid + "&dep=" + result.depid + "&reppdno=" + result.reppdno + "&reppid=" + result.reppid;
							}); 
							// alert(result.message);
						} else {
							alert("Result : " + result.messageResult + "\nPlus : " + result.messageUpPlus + "\nDis : " + result.messageUpDis);
						}
					}
				});
			});

			document.getElementById("txtTotalsummarize").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				document.getElementById("txtTotalsummarizeHidden").value = this.value.replace(/,/g, "");
			}

			$(".form-control").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

		});

		function chkNum(ele) {
			var num = parseFloat(ele.value);
			ele.value = num.toFixed(2);
		}

		function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
			try {
				decimalCount = Math.abs(decimalCount);
				decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

				const negativeSign = amount < 0 ? "-" : "";
				let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
				let j = (i.length > 3) ? i.length % 3 : 0;
				return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");

			} catch (e) {
				console.log(e)
			}
		};

		function calculateSum() {

			var sumamountPlus = 0;
			var sumamountDis = 0;

			$(".amountPlus").each(function() {
				if(!isNaN(this.value) && this.value.length!=0) {
					sumamountPlus += parseFloat(this.value);
				}
			});
			document.getElementById('sumPlus').value = sumamountPlus.toFixed(2);


			$(".amountDis").each(function() {
				if(!isNaN(this.value) && this.value.length!=0) {
					sumamountDis += parseFloat(this.value);
				}
			});
			document.getElementById('sumDis').value = sumamountDis.toFixed(2);


			var summarize = parseFloat($('.summarize').val());
			var sumPlus = parseFloat($('.sumPlus').val());
			var sumDis = parseFloat($('.sumDis').val());
			var totalsBalance = parseFloat(summarize + sumPlus + sumDis) || 0;
			$('#txtTotalbalance').val(formatMoney(totalsBalance));
			$('#txtTotalbalanceHidden').val(totalsBalance.toFixed(2));

		}
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>