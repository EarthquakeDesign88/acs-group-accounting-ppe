<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(isset($_POST["id"])) {

			$output = '';
			include 'connect.php';

			$str_sql = "SELECT * FROM reportpay_tb1 AS repp INNER JOIN department_tb AS d ON repp.repp_depid = d.dep_id WHERE repp_paydate = '0000-00-00' ";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);

			$output .= '<div class="container py-4 px-4">
							<div class="row py-1">
								<div class="col-md-12 col-lg-8">
									<b>' . $obj_row['repp_desc_summarize'] . '</b>
								</div>
								<div class="col-md-12 col-lg-4 text-right">
									<b>' . number_format($obj_row['repp_total_summarize'],2) . '</b>
									<input type="text" class="form-control calSummarize" value="'. $obj_row['repp_total_summarize'] .'">
								</div>
							</div>

							<div class="row py-1">';
								$str_sql_reppd = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 1 AND reppd_reppid = '". $obj_row["repp_id"] ."'";
								$obj_rs_reppd = mysqli_query($obj_con, $str_sql_reppd);
								while ($obj_row_reppd = mysqli_fetch_array($obj_rs_reppd)) {
					$output .= '<div class="col-md-12 col-lg-1">
									<b>บวก</b>
								</div>
								<div class="col-md-12 col-lg-7">
									' . $obj_row_reppd['reppd_description'] . '
								</div>
								<div class="col-md-12 col-lg-4 text-right">
									' . number_format($obj_row_reppd['reppd_amount'],2) . '
									<input type="text" class="form-control calCash" value="'. $obj_row_reppd['reppd_amount'] .'">
								</div>';
								}
				$output .= '</div>
							<input type="text" class="form-control sumTotalCash" value="'. $obj_row_reppd['reppd_amount'] .'">

							<div class="row py-1">';
								$str_sql_reppd = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 2 AND reppd_reppid = '". $obj_row["repp_id"] ."'";
								$obj_rs_reppd = mysqli_query($obj_con, $str_sql_reppd);
								while ($obj_row_reppd = mysqli_fetch_array($obj_rs_reppd)) {
					$output .= '<div class="col-md-12 col-lg-1">
									<b>ลบ</b>
								</div>
								<div class="col-md-12 col-lg-7">
									' . $obj_row_reppd['reppd_description'] . '
								</div>
								<div class="col-md-12 col-lg-4 text-right">
									' . number_format($obj_row_reppd['reppd_amount'],2) . '
									<input type="text" class="form-control calCheque" value="'. $obj_row_reppd['reppd_amount'] .'">
								</div>';
								}
				$output .= '</div>
							<input type="text" class="form-control sumTotalCheque" value="0.00">

							<div class="row pt-4 py-1">
								<div class="col-md-12 col-lg-8">
									<b>' . $obj_row['repp_desc_balance'] . '</b>
								</div>
								<div class="col-md-12 col-lg-4 text-right">
									<b>' . number_format($obj_row['repp_total_balance'],2) . '</b>
								</div>
							</div>
							<input type="text" class="form-control calBalance" value="'. $obj_row['repp_total_balance'] .'">

							<div class="row py-1">';

							$str_sql_cash = "SELECT * FROM payment_tb WHERE paym_typepay = 1 AND paym_reppid = '". $obj_row["repp_id"] ."'";
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
					$output .= '<div class="col-md-12 col-lg-1">
									<b>เงินสด</b>
								</div>
								<div class="col-md-12 col-lg-7">
									'. $invdesc .'
								</div>
								<div class="col-md-12 col-lg-4 text-right">
									'. number_format($invnetamount,2) .'
								</div>';
								}
							}
				$output .= '</div>
						</div>';

			$output .= '<script>
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
									return negativeSign + (j ? i.substr(0, j) + thousands : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");

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

								$(".calCheque").each(function() {
									if(!isNaN(this.value) && this.value.length!=0) {
										sumamountCheque += parseFloat(this.value);
									}
								});
								document.getElementById("sumTotalCheque").innerHTML = "-"+formatMoney(sumamountCheque);

							}
						</script>';

			echo $output;

		}

	}

?>