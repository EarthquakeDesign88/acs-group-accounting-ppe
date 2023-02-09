<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$pcid = $_GET["pcid"];
		$pcrev = $_GET["pcrev"];

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql = "SELECT * FROM pettycash_tb AS pc 
					INNER JOIN company_tb AS c ON pc.pcash_compid = c.comp_id 
					INNER JOIN payable_tb AS p ON pc.pcash_payaid = p.paya_id 
					INNER JOIN department_tb AS d ON pc.pcash_depid = d.dep_id 
					INNER JOIN user_tb AS u ON pc.pcash_userid_create = u.user_id 
					WHERE pcash_id = '". $pcid ."' AND pcash_rev = '". $pcrev ."'";
		$obj_rs = mysqli_query($obj_con, $str_sql);
		$obj_row = mysqli_fetch_array($obj_rs);

		$sumsub = $obj_row["pcash_subtotal1"] + $obj_row["pcash_subtotal2"] + $obj_row["pcash_subtotal3"] + $obj_row["pcash_subtotal4"] + $obj_row["pcash_subtotal5"];
						$sumvat = $obj_row["pcash_vat1"] + $obj_row["pcash_vat2"] + $obj_row["pcash_vat3"] + $obj_row["pcash_vat4"] + $obj_row["pcash_vat5"];
						$sumtax = $obj_row["pcash_taxtotal1"] + $obj_row["pcash_taxtotal2"] + $obj_row["pcash_taxtotal3"] + $obj_row["pcash_taxtotal4"] + $obj_row["pcash_taxtotal5"];
						$sumgrand = $obj_row["pcash_grandtotal1"] + $obj_row["pcash_grandtotal2"] + $obj_row["pcash_grandtotal3"] + $obj_row["pcash_grandtotal4"] + $obj_row["pcash_grandtotal5"];
						$sumdiff = $obj_row["pcash_difference1"] + $obj_row["pcash_difference2"] + $obj_row["pcash_difference3"] + $obj_row["pcash_difference4"] + $obj_row["pcash_difference5"];
						$sumnet = $obj_row["pcash_netamount1"] + $obj_row["pcash_netamount2"] + $obj_row["pcash_netamount3"] + $obj_row["pcash_netamount4"] + $obj_row["pcash_netamount5"];

						if ($obj_row["pcash_subtotal1"] != '0.00') {

							$subtotal1 = number_format($obj_row["pcash_subtotal1"],2);
							$vat1 = number_format($obj_row["pcash_vat1"],2);
							$taxtotal1 = number_format($obj_row["pcash_taxtotal1"],2);
							$grandtotal1 = number_format($obj_row["pcash_grandtotal1"],2);
							$diff1 = number_format($obj_row["pcash_difference1"],2);
							$netamount1 = number_format($obj_row["pcash_netamount1"],2);

						} else {

							$subtotal1 = '';
							$vat1 = '';
							$taxtotal1 = '';
							$grandtotal1 = '';
							$diff1 = '';
							$netamount1 = '';

						}


						if ($obj_row["pcash_subtotal2"] != '0.00') {

							$subtotal2 = number_format($obj_row["pcash_subtotal2"],2);
							$vat2 = number_format($obj_row["pcash_vat2"],2);
							$taxtotal2 = number_format($obj_row["pcash_taxtotal2"],2);
							$grandtotal2 = number_format($obj_row["pcash_grandtotal2"],2);
							$diff2 = number_format($obj_row["pcash_difference2"],2);
							$netamount2 = number_format($obj_row["pcash_netamount2"],2);

						} else {

							$subtotal2 = '';
							$vat2 = '';
							$taxtotal2 = '';
							$grandtotal2 = '';
							$diff2 = '';
							$netamount2 = '';

						}

						if ($obj_row["pcash_subtotal3"] != '0.00') {

							$subtotal3 = number_format($obj_row["pcash_subtotal3"],2);
							$vat3 = number_format($obj_row["pcash_vat3"],2);
							$taxtotal3 = number_format($obj_row["pcash_taxtotal3"],2);
							$grandtotal3 = number_format($obj_row["pcash_grandtotal3"],2);
							$diff3 = number_format($obj_row["pcash_difference3"],2);
							$netamount3 = number_format($obj_row["pcash_netamount3"],2);

						} else {

							$subtotal3 = '';
							$vat3 = '';
							$taxtotal3 = '';
							$grandtotal3 = '';
							$diff3 = '';
							$netamount3 = '';

						}

						if ($obj_row["pcash_subtotal4"] != '0.00') {

							$subtotal4 = number_format($obj_row["pcash_subtotal4"],2);
							$vat4 = number_format($obj_row["pcash_vat4"],2);
							$taxtotal4 = number_format($obj_row["pcash_taxtotal4"],2);
							$grandtotal4 = number_format($obj_row["pcash_grandtotal4"],2);
							$diff4 = number_format($obj_row["pcash_difference4"],2);
							$netamount4 = number_format($obj_row["pcash_netamount4"],2);

						} else {

							$subtotal4 = '';
							$vat4 = '';
							$taxtotal4 = '';
							$grandtotal4 = '';
							$diff4 = '';
							$netamount4 = '';

						}

						if ($obj_row["pcash_subtotal5"] != '0.00') {

							$subtotal5 = number_format($obj_row["pcash_subtotal5"],2);
							$vat5 = number_format($obj_row["pcash_vat5"],2);
							$taxtotal5 = number_format($obj_row["pcash_taxtotal5"],2);
							$grandtotal5 = number_format($obj_row["pcash_grandtotal5"],2);
							$diff5 = number_format($obj_row["pcash_difference5"],2);
							$netamount5 = number_format($obj_row["pcash_netamount5"],2);

						} else {

							$subtotal5 = '';
							$vat5 = '';
							$taxtotal5 = '';
							$grandtotal5 = '';
							$diff5 = '';
							$netamount5 = '';

						}

		$str_sql_organ = "SELECT * FROM user_tb AS u INNER JOIN pettycash_tb AS pcash ON u.user_id = pcash.pcash_userid_create WHERE user_id = '".$obj_row["pcash_userid_create"]."'";
		$obj_rs_organ = mysqli_query($obj_con, $str_sql_organ);
		$obj_row_organ = mysqli_fetch_array($obj_rs_organ);

		function DateThai($strDate) {
			$strYear = date("Y",strtotime($strDate))+543;
			$strMonth= date("n",strtotime($strDate));
			$strDay= date("j",strtotime($strDate));
			$strHour= date("H",strtotime($strDate));
			$strMinute= date("i",strtotime($strDate));
			$strSeconds= date("s",strtotime($strDate));
			$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
			$strMonthThai=$strMonthCut[$strMonth];
			return "$strDay $strMonthThai $strYear";
		}

		function DateMonthThai($strDate) {
			$strYear = date("Y",strtotime($strDate))+543;
			$strMonth= date("n",strtotime($strDate));
			$strDay= date("j",strtotime($strDate));
			$strHour= date("H",strtotime($strDate));
			$strMinute= date("i",strtotime($strDate));
			$strSeconds= date("s",strtotime($strDate));
			$strMonthCut = Array ( "", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน","พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม","กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม" );
			$strMonthThai=$strMonthCut[$strMonth];
			return "$strDay $strMonthThai $strYear";
		}

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<style type="text/css">
		.table .thead-light th {
			color: #000;
			text-align: center;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmPreviewPettyCash" id="frmPreviewPettyCash" action="pettycash_print.php?cid=<?=$cid;?>&dep=<?=$dep;?>&pcid=<?=$pcid;?>&pcrev=<?=$pcrev;?>">
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-search-document"></i>&nbsp;&nbsp;Preview ใบจ่ายเงินสดย่อย
						</h3>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">

					<div class="col-md-6 ml-auto mb-3">
						<h3>
							<b>
								ใบจ่ายเงินสดย่อย<br>
								Petty Cash&nbsp;<?=$obj_row["dep_name"];?>
							</b>
						</h3>
					</div>

					<div class="col-md-6 ml-auto mb-3">
						<div class="row" style="margin-left: 0px; margin-right: 0px;">
							<div class="col-md-6 ml-auto my-1 text-right">
								<b>เลขที่ใบจ่ายเงินสดย่อย</b>
							</div>
							<div class="col-md-6 ml-auto my-1 text-center" style="border-bottom: 1px dotted #000">
								<?=$obj_row["pcash_no"];?>
								<input type="text" class="form-control d-none" name="pcashNo" value="<?=$obj_row["pcash_no"];?>">
							</div>

							<div class="col-md-6 ml-auto my-1 text-right">
								<b>ฝ่าย</b>
							</div>
							<div class="col-md-6 ml-auto my-1 text-center" style="border-bottom: 1px dotted #000">
								<?=$obj_row["dep_name"];?>
							</div>

							<div class="col-md-6 ml-auto my-1 text-right">
								<b>วันที่</b>
							</div>
							<div class="col-md-6 ml-auto my-1 text-center" style="border-bottom: 1px dotted #000">
								<?=DateMonthThai($obj_row["pcash_date"]);?>
							</div>

							<div class="col-md-6 ml-auto my-1 mt-1 text-right d-none">
								<b>Rev.</b>
							</div>
							<div class="col-md-6 ml-auto my-1 d-none">
								<?=$obj_row["pcash_rev"];?>
							</div>
						</div>
					</div>

					<div class="col-md-12 ml-auto mb-3">
						<div class="row" style="margin-left: 0px; margin-right: 0px;">
							<div class="col-md-2 mr-auto my-1">
								<b>ชื่อบริษัทในเครือ</b>
							</div>
							<div class="col-md-10 mr-auto my-1" style="border-bottom: 1px dotted #000">
								<?=$obj_row["comp_name"];?>
							</div>
						</div>
					</div>

					<div class="col-md-12 ml-auto mb-3">
						<div class="row" style="margin-left: 0px; margin-right: 0px;">
							<div class="col-md-1 mr-auto mb-3">
								<b>ชื่อผู้รับ</b>
							</div>
							<div class="col-md-11 mr-auto mb-3" style="border-bottom: 1px dotted #000">
								<?=$obj_row["paya_name"];?>
							</div>
						</div>
					</div>

					<div class="col-md-12 ml-auto mb-3">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="thead-light">
									<tr>
										<th width="3%">ที่</th>
										<th width="38%">รายการ</th>
										<th width="9%">ค่าบริการ</th>
										<th width="9%">VAT</th>
										<th width="11%">TAX</th>
										<th width="11%">ยอดขำระ</th>
										<th width="7%">+/-</th>
										<th width="12%">ยอดชำระสุทธิ</th>
									</tr>
								</thead>
								<tbody>
									<!-- START 1 -->
									<tr>
										<td>1.</td>
										<td><?=$obj_row["pcash_description1"]?></td>
										<td class="text-right"><?=$subtotal1?></td>
										<td class="text-right"><?=$vat1?></td>
										<td class="text-right"><?=$taxtotal1?></td>
										<td class="text-right"><?=$grandtotal1?></td>
										<td class="text-right"><?=$diff1?></td>
										<td class="text-right"><?=$netamount1?></td>
									</tr>
									<!-- END 1 -->

									<!-- START 2 -->
									<tr>
										<td>2.</td>
										<td><?=$obj_row["pcash_description2"]?></td>
										<td class="text-right"><?=$subtotal2?></td>
										<td class="text-right"><?=$vat2?></td>
										<td class="text-right"><?=$taxtotal2?></td>
										<td class="text-right"><?=$grandtotal2?></td>
										<td class="text-right"><?=$diff2?></td>
										<td class="text-right"><?=$netamount2?></td>
									</tr>
									<!-- END 2 -->

									<!-- START 3 -->
									<tr>
										<td>3.</td>
										<td><?=$obj_row["pcash_description3"]?></td>
										<td class="text-right"><?=$subtotal3?></td>
										<td class="text-right"><?=$vat3?></td>
										<td class="text-right"><?=$taxtotal3?></td>
										<td class="text-right"><?=$grandtotal3?></td>
										<td class="text-right"><?=$diff3?></td>
										<td class="text-right"><?=$netamount3?></td>
									</tr>
									<!-- END 3 -->

									<!-- START 4 -->
									<tr>
										<td>4.</td>
										<td><?=$obj_row["pcash_description4"]?></td>
										<td class="text-right"><?=$subtotal4?></td>
										<td class="text-right"><?=$vat4?></td>
										<td class="text-right"><?=$taxtotal4?></td>
										<td class="text-right"><?=$grandtotal4?></td>
										<td class="text-right"><?=$diff4?></td>
										<td class="text-right"><?=$netamount4?></td>
									</tr>
									<!-- END 4 -->

									<!-- START 5 -->
									<tr>
										<td>5.</td>
										<td><?=$obj_row["pcash_description5"]?></td>
										<td class="text-right"><?=$subtotal5?></td>
										<td class="text-right"><?=$vat5?></td>
										<td class="text-right"><?=$taxtotal5?></td>
										<td class="text-right"><?=$grandtotal5?></td>
										<td class="text-right"><?=$diff5?></td>
										<td class="text-right"><?=$netamount5?></td>
									</tr>
									<!-- END 5 -->
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2" class="text-right"><b>รวมเป็นเงิน</b></td>
										<td class="text-right" style="color: #F00; background-color: #e9ecef">
											<?=number_format($sumsub,2)?>
										</td>
										<td class="text-right" style="color: #F00; background-color: #e9ecef">
											<?=number_format($sumvat,2)?>
										</td>
										<td class="text-right" style="color: #F00; background-color: #e9ecef">
											<?=number_format($sumtax,2)?>
										</td>
										<td class="text-right" style="color: #F00; background-color: #e9ecef">
											<?=number_format($sumgrand,2)?>
										</td>
										<td class="text-right" style="color: #F00; background-color: #e9ecef">
											<?=number_format($sumdiff,2)?>
										</td>
										<td class="text-right" style="color: #F00; background-color: #e9ecef">
											<?=number_format($sumnet,2)?>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>

					<div class="col-md-12 ml-auto mb-3">
						<div class="row" style="margin-left: 0px; margin-right: 0px;">
							<div class="col-md-4 ml-auto mb-3 px-4">
								<div class="row">
									<div class="col-md-3 mb-3 px-0 text-right">
										<b>ผู้อนุมัติ</b>
									</div>
									<div class="col-md-9 mr-auto mb-3 text-center" style="border-bottom: 1px dotted #000;">

									</div>
								</div>

								<div class="row">
									<div class="col-md-2 mb-3 px-0 text-right">
										<b>วันที่</b>
									</div>
									<div class="col-md-10 mr-auto mb-3 text-center" style="border-bottom: 1px dotted #000;">

									</div>
								</div>
							</div>

							<div class="col-md-4 ml-auto mb-3 px-4">
								<div class="row">
									<div class="col-md-3 mb-3 px-0 text-right">
										<b>ผู้จ่ายเงิน</b>
									</div>
									<div class="col-md-9 mr-auto mb-3 text-center" style="border-bottom: 1px dotted #000;">
										<?= $obj_row_organ["user_firstname"] . ' ' . $obj_row_organ["user_surname"]; ?>
									</div>
								</div>

								<div class="row">
									<div class="col-md-2 mb-3 px-0 text-right">
										<b>วันที่</b>
									</div>
									<div class="col-md-10 mr-auto mb-3 text-center" style="border-bottom: 1px dotted #000;">
										<?= DateThai(date('Y-m-d', strtotime($obj_row_organ["pcash_createdate"]))); ?>
									</div>
								</div>
							</div>

							<div class="col-md-4 ml-auto mb-3 px-4">
								<div class="row">
									<div class="col-md-3 mb-3 px-0 text-right">
										<b>ผู้รับเงิน</b>
									</div>
									<div class="col-md-9 mr-auto mb-3 text-center" style="border-bottom: 1px dotted #000;">
										<?=$obj_row["pcash_payeename"]?>
									</div>
								</div>

								<div class="row">
									<div class="col-md-2 mb-3 px-0 text-right">
										<b>วันที่</b>
									</div>
									<div class="col-md-10 mr-auto mb-3 text-center" style="border-bottom: 1px dotted #000;">
										<?php
											if ($obj_row["pcash_payeedate"] != '') {
												DateThai($obj_row["pcash_payeedate"]);
											} else {

											}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>

				<div class="row pb-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 ml-auto mb-3 text-center">
						<a href="pettycash_edit.php?cid=<?=$cid;?>&dep=<?=$dep;?>&pcid=<?=$pcid;?>&pcrev=<?=$pcrev;?>" type="button" class="btn btn-warning py-2 px-5 mb-4 mx-2">ย้อนกลับ</a>

						<input type="submit" class="btn btn-success py-2 px-5 mb-4 mx-2" name="PettyCash_Print" id="PettyCash_Print" value="Print">
					</div>
				</div>
			</form>

		</div>
	</section>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>