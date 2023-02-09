<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$reppid = $_GET["reppid"];

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		function DateThai($strDate) {
			$strYear = substr(date("Y",strtotime($strDate))+543,-2);
			$strMonth= date("n",strtotime($strDate));
			$strDay= date("j",strtotime($strDate));
			$strHour= date("H",strtotime($strDate));
			$strMinute= date("i",strtotime($strDate));
			$strSeconds= date("s",strtotime($strDate));
			$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
			$strMonthThai=$strMonthCut[$strMonth];
			return "$strDay $strMonthThai $strYear";
		}

		$str_sql_repp = "SELECT * FROM reportpay_tb1 AS repp INNER JOIN reportpay_desc_tb1 AS reppd ON repp.repp_id = reppd.reppd_reppid WHERE repp_id = '". $reppid ."'";
		$obj_rs_repp = mysqli_query($obj_con, $str_sql_repp);
		$obj_row_repp = mysqli_fetch_array($obj_rs_repp);

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
<body onload="calculateSum();">

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmReportPayDate" id="frmReportPayDate" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-papers"></i>
							&nbsp;&nbsp;สรุปรายการทำจ่าย
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
						<input type="text" class="form-control" name="reppid" id="reppid" value="<?=$reppid;?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">

					<div class="col-md-4 py-2 pb-4">
						<label for="repppaydate" class="mb-1" style="font-size: 1.25rem;">วันที่ทำจ่าย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="repppaydate" id="repppaydate" autocomplete="off">
						</div>
					</div>

					<div class="col-md-12 py-2">
						<label for="repdate" class="mb-1">วันที่จัดทำ <?=DateThai($obj_row_repp["repp_date"]);?></label>
						<div class="input-group d-none">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="repdate" id="repdate" autocomplete="off" value="<?=$obj_row_repp["rep_date"]?>" readonly>
						</div>
					</div>

					<div class="col-md-12 pt-1 pb-3">
						<div class="table-responsive">
							<table class="table">
								<tbody style="background-color: #FFF;">
									<tr style="border-bottom: none;">
										<td colspan="2" style="border-top: none; padding: .25rem;">
											<div class="input-group">
												<input type="text" name="txtsummarize" id="txtsummarize" class="form-control d-none" placeholder="กรอกวันที่สรุปยอด" value="<?=$obj_row_repp["repp_desc_summarize"]?>" autocomplete="off" readonly>
												<b><?=$obj_row_repp["repp_desc_summarize"]?></b>
											</div>
										</td>
										<td colspan="2" style="border-top: none; padding: .25rem 1.25rem; text-align: right!important;">
											<div class="input-group">
												<input type="text" class="form-control text-right d-none" name="txtTotalsummarize" id="txtTotalsummarize" value="<?=number_format($obj_row_repp["repp_total_summarize"],2);?>" autocomplete="off" readonly>
												<input type="text" class="form-control summarize text-right d-none" name="txtTotalsummarizeHidden" id="txtTotalsummarizeHidden" value="<?=$obj_row_repp["repp_total_summarize"];?>" readonly>
											</div>
											<b><?=number_format($obj_row_repp["repp_total_summarize"],2);?></b>
										</td>
									</tr>
								</tbody>


								<!-- บวก -->
								<tbody class="plus" id="dynamicfieldPlus" style="border-top: none;">
									<?php
										$str_sql_plus = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 1 AND reppd_no = '" . $obj_row_repp["reppd_no"] . "'";
										$obj_rs_plus = mysqli_query($obj_con, $str_sql_plus);
										$i = 1;
										while ($obj_row_plus = mysqli_fetch_array($obj_rs_plus)) {
									?>
									<tr id="plus1<?=$i;?>" style="border-bottom: none;">
										<td class="d-none" style="border-top: none; padding: .25rem;">
											<input type="text" class="form-control" name="repdidPlus<?=$i;?>" id="repdidPlus<?=$i;?>" value="<?=$obj_row_plus["reppd_id"];?>">
										</td>
										<td width="5%" style="border-top: none; padding: .25rem;">
											<label for="txtPlus<?=$i;?>" class="mb-1">บวก</label>
											<input type="text" class="form-control d-none" id="txtPlus<?=$i;?>" name="txtPlus<?=$i;?>" value="<?=$obj_row_plus["reppd_type"];?>">
										</td>
										<td width="70%" style="border-top: none; padding: .25rem;">
											<input type="text" class="form-control d-none" id="txtdescPlus<?=$i;?>" name="txtdescPlus<?=$i;?>" autocomplete="off" value="<?=$obj_row_plus["reppd_description"];?>" readonly>
											<b><?=$obj_row_plus["reppd_description"];?></b>
										</td>
										<td style="border-top: none; padding: .25rem 1.25rem; text-align: right;">
											<input type="text" class="form-control text-right d-none" id="txttotalPlus<?=$i;?>" name="txttotalPlus<?=$i;?>" value="<?=number_format($obj_row_plus["reppd_amount"],2);?>" autocomplete="off" readonly>
											<input type="text" class="form-control amountPlus d-none" id="txttotalPlusHidden<?=$i;?>" name="txttotalPlusHidden<?=$i;?>" value="<?=$obj_row_plus["reppd_amount"];?>" autocomplete="off">
											<b><?=number_format($obj_row_plus["reppd_amount"],2);?></b>
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
										$str_sql_dis = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 2 AND reppd_no = '" . $obj_row_repp["reppd_no"] . "'";
										$obj_rs_dis = mysqli_query($obj_con, $str_sql_dis);
										$i = 1;
										while ($obj_row_dis = mysqli_fetch_array($obj_rs_dis)) {
									?>
									<tr id="dis2<?=$i;?>" style="border-bottom: none;">
										<td class="d-none" style="border-top: none; padding: .25rem;">
											<input type="text" class="form-control" name="repdidDis<?=$i;?>" id="repdidDis<?=$i;?>" value="<?=$obj_row_dis["reppd_id"];?>">
										</td>
										<td width="5%" style="border-top: none; padding: .25rem;">
											<label for="txtDis<?=$i;?>" class="mb-1">จ่าย</label>
											<input type="text" class="form-control d-none" id="txtDis<?=$i;?>" name="txtDis<?=$i;?>" value="<?=$obj_row_dis["reppd_type"];?>">
										</td>
										<td width="70%" style="border-top: none; padding: .25rem;">
											<input type="text" class="form-control d-none" id="txtdescDis<?=$i;?>" name="txtdescDis<?=$i;?>" autocomplete="off" value="<?=$obj_row_dis["reppd_description"];?>" readonly>
											<b><?=$obj_row_dis["reppd_description"];?></b>
										</td>
										<td style="border-top: none; padding: .25rem 1.25rem; text-align: right;">
											<input type="text" class="form-control text-right d-none" id="txttotalDis<?=$i;?>" name="txttotalDis<?=$i;?>" value="<?=number_format($obj_row_dis["reppd_amount"],2);?>" autocomplete="off" readonly>
											<input type="text" class="form-control amountDis d-none" id="txttotalDisHidden<?=$i;?>" name="txttotalDisHidden<?=$i;?>" value="<?=$obj_row_dis["reppd_amount"];?>" autocomplete="off">
											<b><?=number_format($obj_row_dis["reppd_amount"],2);?></b>
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
									<tr style="border-bottom: none!important;">
										<td colspan="2" style="border-top: none; padding: .25rem;">
											<div class="input-group">
												<input type="text" name="txtbalance" id="txtbalance" class="form-control d-none" placeholder="กรอกวันที่ยอดเหลือ" autocomplete="off" value="<?=$obj_row_repp["repp_desc_balance"]?>" readonly>
												<b><?=$obj_row_repp["repp_desc_balance"]?></b>
											</div>
										</td>
										<td colspan="2" style="padding: .25rem 1.25rem;  border-top: 1px solid #000; text-align: right; border-bottom-style: double;">
											<div class="input-group">
												<input type="text" class="form-control text-right d-none" name="txtTotalbalance" id="txtTotalbalance" value="<?=number_format($obj_row_repp["repp_total_balance"],2);?>" readonly>
												<input type="text" class="form-control text-right d-none" name="txtTotalbalanceHidden" id="txtTotalbalanceHidden" value="<?=$obj_row_repp["repp_total_balance"];?>" readonly>
											</div>
											<b><?=number_format($obj_row_repp["repp_total_balance"],2);?></b>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 pt-1 pb-3">
						<div class="table-responsive">
							<table class="table">

								<!-- CASH -->
								<tbody>
									<?php
										$str_sql_cash = "SELECT * FROM payment_tb WHERE paym_typepay = 1 AND paym_reppid = '". $reppid ."'";
										$obj_rs_cash = mysqli_query($obj_con, $str_sql_cash);
										if (mysqli_num_rows($obj_rs_cash) > 0) {
										while ($obj_row_cash = mysqli_fetch_array($obj_rs_cash)) {
											$str_sql_paymcash = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id WHERE inv_paymid = '". $obj_row_cash["paym_id"] ."'";
											$obj_rs_paymcash = mysqli_query($obj_con, $str_sql_paymcash);
											$invdesc = "";
											$invnetamount = 0;
											while ($obj_row_paymcash = mysqli_fetch_array($obj_rs_paymcash)) {
												if (mysqli_num_rows($obj_rs_paymcash) > 1) {
													$invdesc = $obj_row_paymcash["inv_description_short"] . " / " . $invdesc;
												} else {
													$invdesc = $obj_row_paymcash["inv_description_short"];
												}
												$invnetamount += "-".$obj_row_paymcash["inv_netamount"];
											}
									?>
									<tr>
										<td width="5%" style="border-top: none; padding: .25rem">
											<b>เงินสด</b>
										</td>
										<td style="border-top: none; padding: .25rem">
											<?= $invdesc; ?>
										</td>
										<td width="15%" class="text-right" style="border-top: none; padding: .25rem">
											<?= number_format($invnetamount,2); ?>
											<input type="text" class="form-control d-none sumcash" name="" id="" value="<?=$invnetamount;?>">
										</td>
										<td width="15%" class="text-right" style="border-top: none; padding: .25rem">
										</td>
									</tr>
									<?php } ?>
									<tr>
										<td colspan="2" style="border-top: none; padding: .25rem"></td>
										<td style="padding: .25rem; text-align: right;">
											<b>รวมเงินสด</b>
										</td>
										<td width="15%" class="text-right" style="padding: .25rem">
											<b id="sumtotalCash">0.00</b>
											<input type="text" class="form-control d-none caltotalCash" name="" id="caltotalCash">
										</td>
									</tr>
									<tr>
										<td colspan="4"></td>
									</tr>
									<?php } else { ?>
										<b class="d-none" id="sumtotalCash">0.00</b>
										<input type="text" class="form-control d-none caltotalCash" name="" id="caltotalCash" value="0.00">
									<?php } ?>
								</tbody>
								<!-- CASH -->

								<!-- CHEQUE -->
								<tbody style="border-top: none;">
									<?php
										$str_sql_cheq = "SELECT * FROM payment_tb WHERE paym_typepay = 2 AND paym_reppid = '". $reppid ."'";
										$obj_rs_cheq = mysqli_query($obj_con, $str_sql_cheq);
										if (mysqli_num_rows($obj_rs_cheq) > 0) {
										while ($obj_row_cheq = mysqli_fetch_array($obj_rs_cheq)) {
											$str_sql_paymcheq = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id WHERE inv_paymid = '". $obj_row_cheq["paym_id"] ."'";
											$obj_rs_paymcheq = mysqli_query($obj_con, $str_sql_paymcheq);
											$invdesc = "";
											$invnetamount = 0;
											while ($obj_row_paymcheq = mysqli_fetch_array($obj_rs_paymcheq)) {
												if (mysqli_num_rows($obj_rs_paymcheq) > 1) {
													$invdesc = $obj_row_paymcheq["inv_description_short"] . " / " . $invdesc;
												} else {
													$invdesc = $obj_row_paymcheq["inv_description_short"];
												}
												$invnetamount += $obj_row_paymcheq["inv_netamount"];
											}
									?>
									<tr>
										<td width="5%" style="border-top: none; padding: .25rem">
											<b>เช็ค</b>
										</td>
										<td style="border-top: none; padding: .25rem">
											<?= $invdesc; ?>
										</td>
										<td width="15%" class="text-right" style="border-top: none; padding: .25rem">
											-<?= number_format($invnetamount,2); ?>
											<input type="text" class="form-control d-none sumcheque" name="" id="" value="<?=$invnetamount;?>">
										</td>
										<td width="15%" class="text-right" style="border-top: none; padding: .25rem">
										</td>
									</tr>
									<?php } ?>
									<tr>
										<td colspan="2" style="border-top: none; padding: .25rem"></td>
										<td style="padding: .25rem; text-align: right;">
											<b>รวมเช็ค</b>
										</td>
										<td width="15%" class="text-right" style="padding: .25rem">
											<b id="sumtotalCheque">0.00</b>
											<input type="text" class="form-control d-none caltotalCheque" name="" id="caltotalCheque">
										</td>
									</tr>
									<tr>
										<td colspan="4"></td>
									</tr>
									<?php } else { ?>
										<b class="d-none" id="sumtotalCheque">0.00</b>
										<input type="text" class="form-control d-none caltotalCheque" name="" id="caltotalCheque">
									<?php } ?>
								</tbody>
								<!-- CHEQUE -->

								<tbody style="border-top: none;">
									<tr>
										<td colspan="3" style="border-top: none; padding: .25rem">
											<b>คงเหลือ</b>
										</td>
										<td style="border-top: none; padding: .25rem; text-align: right; border-bottom-style: double;">
											<b id="sumTotalBalance">0.00</b>
											<input type="text" class="form-control d-none" name="" id="sumTotalBalanceHidden" value="">
										</td>
									</tr>
								</tbody>

							</table>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1 d-none" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<?php
							$str_sql_paym = "SELECT * FROM payment_tb WHERE paym_reppid = '". $reppid ."'";
							$obj_rs_paym = mysqli_query($obj_con, $str_sql_paym);
							$paym = 1;
							while ($obj_row_paym = mysqli_fetch_array($obj_rs_paym)) {
						?>
							<input type="text" class="form-control" name="paymid<?=$paym;?>" id="paymid<?=$paym;?>" value="<?=$obj_row_paym["paym_id"];?>">
							<input type="text" class="form-control" name="paymno<?=$paym;?>" id="paymno<?=$paym;?>" value="<?=$obj_row_paym["paym_no"];?>">
						<?php $paym++; } ?>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 pb-4 text-center">
						<input type="button" class="btn btn-success px-5 py-2" name="btnInsert" id="btnInsert" value="บันทึก">
					</div>
				</div>
				
			</form>
			
		</div>
	</section>

	<script type="text/javascript">

		$(document).ready(function() {

			$("#btnInsert").click(function() {
				var formData = new FormData(this.form);
				if($('#repppaydate').val() == '') {
					swal({
						title: "กรุณากรอกวันที่ทำจ่าย",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning"
					}).then(function() {
						frmReportPayDate.repppaydate.focus();
					});
				} else {
					$.ajax({
						type: "POST",
						url: "r_reportpay_paydate.php",
						// data: $("#frmReportPayDate").serialize(),
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
									window.location.href = "reportpay.php?cid=" + result.compid + "&dep=" + result.depid;
								}); 
								// alert(result.message);
							} else {
								alert("Result : " + result.messageResult + "\nRepp : " + result.messageRepp);
							}
						}
					});
				}
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

			var sumamountCash = 0;
			var sumamountCheque = 0;

			$(".sumcash").each(function() {
				if(!isNaN(this.value) && this.value.length!=0) {
					sumamountCash += parseFloat(this.value);
				}
			});
			document.getElementById("sumtotalCash").innerHTML = formatMoney(sumamountCash);
			document.getElementById("caltotalCash").value = sumamountCash.toFixed(2);


			$(".sumcheque").each(function() {
				if(!isNaN(this.value) && this.value.length!=0) {
					sumamountCheque += parseFloat(this.value);
				}
			});
			document.getElementById("sumtotalCheque").innerHTML = "-"+formatMoney(sumamountCheque);
			document.getElementById("caltotalCheque").value = "-"+sumamountCheque.toFixed(2);

			var caltotalCash = parseFloat($(".caltotalCash").val());
			var caltotalCheque = parseFloat($(".caltotalCheque").val());
			var txtTotalbalanceHidden = parseFloat($("#txtTotalbalanceHidden").val());
			var caltotalsBalance = parseFloat(txtTotalbalanceHidden + (caltotalCash + caltotalCheque)) || 0;
			document.getElementById("sumTotalBalance").innerHTML = formatMoney(caltotalsBalance);
			$('#sumTotalBalanceHidden').val(caltotalsBalance.toFixed(2));
		}
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>