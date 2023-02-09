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

	<link rel="stylesheet" type="text/css" href="css/checkbox.css">

</head>
<body onclick="checkInv();">

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">
			
			<form method="POST" name="" id="" action="">
				
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-listing-number"></i>&nbsp;&nbsp;รายละเอียดใบแจ้งหนี้โครงการ
						</h3>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-3 pt-1 pb-3">
						<label>งวด</label>
						<input type="text" class="form-control" name="" id="" value="งวดที่ 1" readonly>
					</div>

					<div class="col-md-9 pt-1 pb-3"></div>

					<div class="col-md-9">
						<div class="row">
							<div class="col-md-12">
								<div class="text-center">
									<b>รายละเอียด</b>
								</div>
								<input type="text" class="form-control my-1" name="" id="" value="">
								<input type="text" class="form-control my-1" name="" id="" value="">
								<input type="text" class="form-control my-1" name="" id="" value="">
								<input type="text" class="form-control my-1" name="" id="" value="">
								<input type="text" class="form-control my-1" name="" id="" value="">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="row">
							<div class="col-md-12">
								<div class="text-center">
									<b>จำนวนเงิน</b>
								</div>
								<input type="text" class="form-control text-right my-1" name="amount1" id="amount1" value="0.00">
								<input type="text" class="form-control text-right my-1 d-none" name="amountHidden1" id="amountHidden1" value="0.00">

								<input type="text" class="form-control text-right my-1" name="amount2" id="amount2" value="0.00">
								<input type="text" class="form-control text-right my-1 d-none" name="amountHidden2" id="amountHidden2" value="0.00">

								<input type="text" class="form-control text-right my-1" name="amount3" id="amount3" value="0.00">
								<input type="text" class="form-control text-right my-1 d-none" name="amountHidden3" id="amountHidden3" value="0.00">

								<input type="text" class="form-control text-right my-1" name="amount4" id="amount4" value="0.00">
								<input type="text" class="form-control text-right my-1 d-none" name="amountHidden4" id="amountHidden4" value="0.00">

								<input type="text" class="form-control text-right my-1" name="amount5" id="amount5" value="0.00">
								<input type="text" class="form-control text-right my-1 d-none" name="amountHidden5" id="amountHidden5" value="0.00">
							</div>
						</div>
					</div>
					<div class="col-md-9">
						<div class="row">
							<div class="col-md-7">
								<input type="text" class="form-control my-1" name="" id="" value="">
								<input type="text" class="form-control my-1" name="" id="" value="">
								<input type="text" class="form-control my-1" name="" id="" value="">
								<input type="text" class="form-control my-1" name="" id="" value="">
							</div>
							<div class="col-md-5"></div>
						</div>
					</div>

					<div class="col-md-6"></div>

					<div class="col-md-6">
						<div class="form-group">
							<div class="checkbox">
								<input type="checkbox" id="totalVat" onclick="checkInv()">
								<label for="totalVat"><span>รวมภาษีมูลค่าเพิ่มแล้ว</span></label>
								<input type="text" class="form-control d-none" id="totalChkVat" name="" value="0">
							</div>
						</div>

						<div class="form-group row mb-0 my-1">
							<label class="col-md-5 col-form-label">จำนวนเงิน Sub Total</label>
							<div class="col-md-7">
								<div class="input-group">
									<input type="text" class="form-control text-right" name="subtotal" id="showSubtotal" value="0.00" readonly>
									<input type="text" class="form-control text-right d-none" name="subtotalHidden" id="calSubtotal" value="0.00" readonly>
									<div class="input-group-append">
										<i class="input-group-text">
											<i class="icofont-baht"></i>
										</i>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group row mb-0 my-1">
							<label class="col-md-5 col-form-label">
								ภาษีมูลค่าเพิ่ม %
							</label>
							<div class="col-md-7">
								<div class="input-group">
									<input type="text" class="form-control text-right" name="vatpercent" id="showVatPercent" value="0.00">
									<input type="text" class="form-control text-right d-none" name="vatpercentHidden" id="calVatPercent" value="0.00" readonly>
									<div class="input-group-append">
										<i class="input-group-text">
											<i class="icofont-sale-discount"></i>
										</i>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group row mb-0 my-1">
							<label class="col-md-5 col-form-label">
								
							</label>
							<div class="col-md-7">
								<div class="input-group">
									<input type="text" class="form-control text-right" name="vat" id="showVat" value="0.00" readonly>
									<input type="text" class="form-control text-right d-none" name="vatHidden" id="calVat" value="0.00" readonly>
									<div class="input-group-append">
										<i class="input-group-text">
											<i class="icofont-baht"></i>
										</i>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group row mb-0 my-1">
							<label class="col-md-5 col-form-label">จำนวนเงินรวม Grand Total</label>
							<div class="col-md-7">
								<div class="input-group">
									<input type="text" class="form-control text-right" name="grandtotal" id="showGrandtotal" value="0.00" readonly>
									<input type="text" class="form-control text-right d-none" name="grandtotal" id="calGrandtotal" value="0.00" readonly>
									<div class="input-group-append">
										<i class="input-group-text">
											<i class="icofont-baht"></i>
										</i>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12 pb-4 text-center">
						<input type="button" class="btn btn-success px-5 py-2 mx-1" name="btnInsert" id="btnInsert" value="บันทึก">
					</div>
				</div>

			</form>

		</div>
	</section>

	<script type="text/javascript">

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

		$(document).ready(function(){

			$(".form-control").each(function() {
				$(this).keyup(function(){
					checkInv();
				});
			});

			document.getElementById("amount1").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden1").value = this.value.replace(/,/g, "");
			}

			document.getElementById("amount2").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden2").value = this.value.replace(/,/g, "");
			}

			document.getElementById("amount3").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden3").value = this.value.replace(/,/g, "");
			}

			document.getElementById("amount4").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden4").value = this.value.replace(/,/g, "");
			}

			document.getElementById("amount5").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("amountHidden5").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showVatPercent").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calVatPercent").value = this.value.replace(/,/g, "");
			}

			document.getElementById("showGrandtotal").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calGrandtotal").value = this.value.replace(/,/g, "");
			}

		});

		function checkInv() {
			var checkbox = document.getElementById('totalVat');
			if (checkbox.checked != true) {

				document.getElementById('totalChkVat').value = "0";
				$('#showGrandtotal').prop('readonly', true);
				$('#showVatPercent').prop('readonly', false);
				// $('#showVatPercent').prop('value', '0.00');
				// $('#calVatPercent').prop('value', '0.00');

				$('#amount1').prop('readonly', false);
				$('#amount2').prop('readonly', false);
				$('#amount3').prop('readonly', false);
				$('#amount4').prop('readonly', false);
				$('#amount5').prop('readonly', false);

				var numAmount1 = parseFloat($('#amountHidden1').val());
				var numAmount2 = parseFloat($('#amountHidden2').val());
				var numAmount3 = parseFloat($('#amountHidden3').val());
				var numAmount4 = parseFloat($('#amountHidden4').val());
				var numAmount5 = parseFloat($('#amountHidden5').val());
				var numPercent = parseFloat($('#calVatPercent').val());
				var totalSubT = parseFloat(numAmount1 + numAmount2 + numAmount3 + numAmount4 + numAmount5) || 0;
				var totalVat = parseFloat((totalSubT * numPercent) / 100) || 0;
				var totalGrandT = parseFloat(totalSubT + totalVat) || 0;
				$('#showSubtotal').val(formatMoney(totalSubT));
				$('#showVat').val(formatMoney(totalVat));
				$('#showGrandtotal').val(formatMoney(totalGrandT));

				$('#calSubtotal').val(totalSubT.toFixed(2));
				$('#calVat').val(totalVat.toFixed(2));
				$('#calGrandtotal').val(totalGrandT.toFixed(2));

			} else {

				document.getElementById('totalChkVat').value = "1";
				$('#showGrandtotal').prop('readonly', false);
				$('#showVatPercent').prop('value', '7.00');
				$('#calVatPercent').prop('value', '7.00');
				$('#showVatPercent').prop('readonly', true);

				$('#amount1').prop('readonly', true);
				$('#amount2').prop('readonly', true);
				$('#amount3').prop('readonly', true);
				$('#amount4').prop('readonly', true);
				$('#amount5').prop('readonly', true);

				var numGrand = parseFloat($('#calGrandtotal').val());
				var numPercent = parseFloat($('#calVatPercent').val());
				var totalsSubT = parseFloat((numGrand * 100) / (100 + numPercent)) || 0;
				var totalVat = parseFloat((numGrand * numPercent) / (100 + numPercent)) || 0;
				$('#amount1').val(formatMoney(totalsSubT));
				$('#showVat').val(formatMoney(totalVat));
				$('#showSubtotal').val(formatMoney(totalsSubT));

				$('#amountHidden1').val(totalsSubT.toFixed(2));
				$('#calVat').val(totalVat.toFixed(2));
				$('#calSubtotal').val(totalsSubT.toFixed(2));

			}
		}
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>