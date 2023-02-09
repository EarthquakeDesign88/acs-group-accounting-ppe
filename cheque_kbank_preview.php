<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$paymid = $_GET["paymid"];

		$str_sql_user = "SELECT * FROM user_tb AS u INNER JOIN level_tb AS l ON u.user_levid = l.lev_id INNER JOIN department_tb AS d ON u.user_depid = d.dep_id WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		$str_sql_paym = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id LEFT JOIN cheque_tb AS cheq ON paym.paym_cheqid = cheq.cheq_id LEFT JOIN bank_tb AS b ON cheq.cheq_bankid = b.bank_id WHERE paym_id = '". $paymid ."'";
		$obj_rs_paym = mysqli_query($obj_con, $str_sql_paym);
		$obj_row_paym = mysqli_fetch_array($obj_rs_paym);

		function Convert($amount_number) {
			$amount_number = number_format($amount_number, 2, ".","");
			$pt = strpos($amount_number , ".");
			$number = $fraction = "";
			if ($pt === false) {
				$number = $amount_number;
			} else {
				$number = substr($amount_number, 0, $pt);
				$fraction = substr($amount_number, $pt + 1);
			}
			$ret = "";
			$baht = ReadNumber ($number);
			if ($baht != ""){
				$ret .= $baht . "บาท";
			}
			$satang = ReadNumber($fraction);
			if ($satang != "") {
				$ret .=  $satang . "สตางค์";
			} else {
				$ret .= "ถ้วน";
			}
			return $ret;
		}

		function ReadNumber($number) {
			$position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
			$number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
			$number = $number + 0;
			$ret = "";
			if ($number == 0) return $ret;
			if ($number >= 1000000) {
				$ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
				$number = intval(fmod($number, 1000000));
			}

			$divider = 100000;
			$pos = 0;
			while($number > 0) {
				$d = intval($number / $divider);
				$ret .= (($divider == 10) && ($d == 2)) ? "ยี่" :
					((($divider == 10) && ($d == 1)) ? "" :
					((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
				$ret .= ($d ? $position_call[$pos] : "");
				$number = $number % $divider;
				$divider = $divider / 10;
				$pos++;
			}
			return $ret;
		}

		$str_sql_ivamount = "SELECT * FROM invoice_tb WHERE inv_paymid = '" . $_GET["paymid"] . "'";
		$obj_rs_ivamount = mysqli_query($obj_con, $str_sql_ivamount);
		$invnetamount = 0;
		while ($obj_row_ivamount = mysqli_fetch_array($obj_rs_ivamount)) {
			$invnetamount += $obj_row_ivamount["inv_netamount"];
		}

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
			
			<form method="POST" name="frmChequePreview" id="frmChequePreview" action="">

				<div class="row py-4 px-1" style="background-color: #e9ecef">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-search-document"></i>&nbsp;&nbsp;Preview เช็ค
						</h3>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-6 mb-2">
						<h4><?= $obj_row_paym["comp_name"]; ?></h4>
						<input type="text" class="form-control d-none" name="compid" id="compid" value="<?= $obj_row_paym["comp_id"]; ?>">
						<input type="text" class="form-control d-none" name="paymid" id="paymid" value="<?= $obj_row_paym["paym_id"]; ?>">
						<input type="text" class="form-control d-none" name="cheqid" id="cheqid" value="<?= $obj_row_paym["cheq_id"]; ?>">
					</div>
					<div class="col-md-6 mb-2"></div>

					<div class="col-md-12">
						<div class="row mx-0">
							<div class="col-md-1">
							</div>
							<div class="col-md-2" style="border-bottom: 1px dotted #000; padding-top: 20px;">
                                จ่าย &nbsp 付给 &nbsp Pay
                            </div>

                            <div class="col-md-7" style="border-bottom: 1px dotted #000; padding-top: 20px;">
                                <b><?= $obj_row_paym["paya_name"]; ?></b>
                            </div>

                            <div class="col-md-2 text-right" style="border-bottom: 1px dotted #000; line-height: 15px; padding-top: 10px; font-size: .9rem;">
                                หรือผู้ถือ<br>
                                或来人 or bearer
                            </div>
						</div>
					</div>

					<div class="col-md-12 pb-2">
						<div class="row mx-0">
							<div class="col-md-1">
							</div>
							<div class="col-md-2" style="border-bottom: 1px dotted #000; padding-top: 20px;">
								บาท &nbsp 泰铢 &nbsp Baht
							</div>

							<div class="col-md-9" style="border-bottom: 1px dotted #000; padding-top: 20px;">
								<b><?= Convert($invnetamount); ?></b>
							</div>
						</div>
					</div>

					<div class="col-md-12 py-2">
						<div class="row mx-0">
							<div class="col-md-1"></div>
							<div class="col-md-7" style="border-bottom: 1px dotted #000; padding-top: 20px; top: -13px;">
							</div>
							<div class="col-md-4" style="border: 1px solid #000; padding: 8px 5px;">
								<div class="row">
									<div class="col-md-1" style="font-size: 1.5rem">
										<i class="icofont-baht"></i>
									</div>
									<div class="col-md-11">
										<b>- <?= number_format($invnetamount,2); ?> -</b>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="row mx-0">
							<div class="col-md-1"></div>
							<div class="col-md-7" style="padding-top: 45px;">
								<b>ธนาคาร <?= $obj_row_paym["bank_name"] ?></b>
								<input type="text" class="form-control d-none" name="bankid" id="bankid" value="<?= $obj_row_paym["bank_id"]; ?>">
								<br>
								<b>เช็คเลขที่ <?= $obj_row_paym["cheq_no"] ?></b>
								<input type="text" class="form-control d-none" name="cheqno" id="cheqno" value="<?= $obj_row_paym["cheq_no"]; ?>">
							</div>

							<div class="col-md-4" style="border-bottom: 1px dotted #000; padding-top: 20px;">
								<b></b>
							</div>

						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 text-center mb-4">
						<input type="button" class="btn btn-success px-5 py-2 mx-1" name="btnInsert" id="btnInsert" value="บันทึก">
					</div>
				</div>
				
			</form>

		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function() {

			$("#btnInsert").click(function() {
				$.ajax({
					type: "POST",
					url: "r_cheque_preview.php",
					data: $("#frmChequePreview").serialize(),
					success: function(result) {
						if(result.status == 1) {
							swal({
								title: "บันทึกข้อมูลสำเร็จ",
								text: "เช็คเลขที่ " + result.message,
								type: "success",
								closeOnClickOutside: false
							},function() {
								window.location.href = result.url;
							});
							// alert(result.message);
						} else {
							alert(result.message);
						}
					}
				});
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>