<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$reppid = $_GET["reppid"];
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

		$str_sql_repp = "SELECT * FROM reportpay_tb1 WHERE repp_id = '". $reppid ."'";
		$obj_rs_repp = mysqli_query($obj_con, $str_sql_repp);
		$obj_row_repp = mysqli_fetch_array($obj_rs_repp);

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

?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>

	<link rel="stylesheet" type="text/css" href="css/checkbox.css">

	<style type="text/css">
		.table .thead-light th {
			color: #000;
		}
		tr:nth-last-child(n) {
			border-bottom: 1px solid #dee2e6;
		}
		th>.truncate, td>.truncate{
			width: auto;
			min-width: 0;
			max-width: 420px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmReportPaySelPaym" id="frmReportPaySelPaym" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12 pb-4">
						<h3 class="mb-0">
							<i class="icofont-papers"></i>
							&nbsp;&nbsp;แก้ไขสรุปรายการทำจ่าย ( ฝ่าย <?=$obj_row["dep_name"];?> )
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
						<input type="text" class="form-control" name="reppid" id="reppid" value="<?=$reppid;?>">
						<input type="text" class="form-control" name="reppdno" id="reppdno" value="<?=$reppdno;?>">
					</div>

					<div class="col-md-12" id="SearchPaym">
						<div class="row">
							<div class="col-md-2">
								<label class="mt-1">ฝ่าย</label>
								<div class="input-group">
									<?php
										$str_sql = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
										$obj_rs = mysqli_query($obj_con, $str_sql);
										$obj_row = mysqli_fetch_array($obj_rs);
									?>
									<input type="text" class="form-control" name="depname" id="depname" value="<?=$obj_row["dep_name"];?>" readonly style="background-color: #FFF">
									<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$dep;?>">
								</div>
							</div>

							<div class="col-md-10">
								<div class="row">
									<div class="col-auto">
										<label class="mt-1">ค้นหาโดย : </label>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<input type="radio" name="SearchPaymBy" id="Paympaymno" value="paym_no" checked="checked">
											<label for="Paympaymno"><span>เลขที่ใบสำคัญจ่าย</span></label>
										</div>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchPaymBy" id="Paympayaname" value="paya_name">
											<label for="Paympayaname"><span>ชื่อบริษัทเจ้าหนี้</span></label>
										</div>
									</div>
									<input type="text" class="form-control d-none" name="SearchPaymVal" id="SearchPaymVal" value="paym_no">
								</div>

								<div class="input-group">
									<input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกเลขที่ใบสำคัญจ่ายที่ต้องการค้นหา" autocomplete="off">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-4 py-2">
						<label for="repdate" class="mb-1">วันที่จัดทำ <?=DateThai($obj_row_repp["repp_date"]);?></label>
						<div class="input-group d-none">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="repdate" id="repdate" autocomplete="off" value="<?=$obj_row_repp["repp_date"]?>" readonly>
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
										$str_sql_plus = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 1 AND reppd_no = '" . $_GET["reppdno"] . "'";
										$obj_rs_plus = mysqli_query($obj_con, $str_sql_plus);
										$p = 1;
										while ($obj_row_plus = mysqli_fetch_array($obj_rs_plus)) {
									?>
									<tr id="plus1<?=$p;?>" style="border-bottom: none;">
										<td class="d-none" style="border-top: none; padding: .25rem;">
											<input type="text" class="form-control" name="repdidPlus<?=$p;?>" id="repdidPlus<?=$p;?>" value="<?=$obj_row_plus["reppd_id"];?>">
										</td>
										<td width="5%" style="border-top: none; padding: .25rem;">
											<label for="txtPlus<?=$p;?>" class="mb-1">บวก</label>
											<input type="text" class="form-control d-none" id="txtPlus<?=$p;?>" name="txtPlus<?=$p;?>" value="<?=$obj_row_plus["reppd_type"];?>">
										</td>
										<td width="70%" style="border-top: none; padding: .25rem;">
											<input type="text" class="form-control d-none" id="txtdescPlus<?=$p;?>" name="txtdescPlus<?=$p;?>" autocomplete="off" value="<?=$obj_row_plus["repd_description"];?>" readonly>
											<b><?=$obj_row_plus["reppd_description"];?></b>
										</td>
										<td style="border-top: none; padding: .25rem 1.25rem; text-align: right;">
											<input type="text" class="form-control text-right d-none" id="txttotalPlus<?=$p;?>" name="txttotalPlus<?=$p;?>" value="<?=number_format($obj_row_plus["reppd_amount"],2);?>" autocomplete="off" readonly>
											<input type="text" class="form-control amountPlus d-none" id="txttotalPlusHidden<?=$p;?>" name="txttotalPlusHidden<?=$p;?>" value="<?=$obj_row_plus["reppd_amount"];?>" autocomplete="off">
											<b><?=number_format($obj_row_plus["reppd_amount"],2);?></b>
										</td>
									</tr>
									<?php 
											$p++;
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
										$d = 1;
										while ($obj_row_dis = mysqli_fetch_array($obj_rs_dis)) {
									?>
									<tr id="dis2<?=$d;?>" style="border-bottom: none;">
										<td class="d-none" style="border-top: none; padding: .25rem;">
											<input type="text" class="form-control" name="repdidDis<?=$d;?>" id="repdidDis<?=$d;?>" value="<?=$obj_row_dis["reppd_id"];?>">
										</td>
										<td width="5%" style="border-top: none; padding: .25rem;">
											<label for="txtDis<?=$d;?>" class="mb-1">จ่าย</label>
											<input type="text" class="form-control d-none" id="txtDis<?=$d;?>" name="txtDis<?=$d;?>" value="<?=$obj_row_dis["reppd_type"];?>">
										</td>
										<td width="70%" style="border-top: none; padding: .25rem;">
											<input type="text" class="form-control d-none" id="txtdescDis<?=$d;?>" name="txtdescDis<?=$d;?>" autocomplete="off" value="<?=$obj_row_dis["reppd_description"];?>" readonly>
											<b><?=$obj_row_dis["reppd_description"];?></b>
										</td>
										<td style="border-top: none; padding: .25rem 1.25rem; text-align: right;">
											<input type="text" class="form-control text-right d-none" id="txttotalDis<?=$d;?>" name="txttotalDis<?=$d;?>" value="<?=number_format($obj_row_dis["reppd_amount"],2);?>" autocomplete="off" readonly>
											<input type="text" class="form-control amountDis d-none" id="txttotalDisHidden<?=$d;?>" name="txttotalDisHidden<?=$d;?>" value="<?=$obj_row_dis["reppd_amount"];?>" autocomplete="off">
											<b><?=number_format($obj_row_dis["reppd_amount"],2);?></b>
										</td>
									</tr>
									<?php 
											$d++;
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
					<?php 
						$str_sql_chkrow = "SELECT * FROM invoice_tb AS i 
											INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id 
											INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id
											WHERE paym_reppid = '". $reppid ."' AND inv_compid = '". $cid ."' AND inv_depid = '". $dep ."' GROUP BY inv_paymid";
						$obj_rs_chkrow = mysqli_query($obj_con, $str_sql_chkrow);
						if (mysqli_num_rows($obj_rs_chkrow) > 0) {
					?>
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-bordered">
								
								<thead class="thead-light">
									<tr>
										<th width="18%">เลขที่ใบสำคัญจ่าย</th>
										<th width="42%">รายละเอียด</th>
										<th width="15%" class="text-center">จำนวนเงิน</th>
										<th width="10%" class="text-center">ฝ่าย</th>
										<th width="13%"></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$str_sql_edit = "SELECT * FROM invoice_tb AS i 
														INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id 
														INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id
														WHERE paym_reppid = '". $reppid ."' AND inv_compid = '". $cid ."' AND inv_depid = '". $dep ."' GROUP BY inv_paymid";
										$obj_rs_edit = mysqli_query($obj_con, $str_sql_edit);
										$i = 1;
										$invnetamount = 0;
										while ($obj_row_edit = mysqli_fetch_array($obj_rs_edit)) {

											$str_sql_editinvpaym = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id WHERE inv_depid = '". $dep ."' AND inv_paymid = '" . $obj_row_edit["inv_paymid"] . "'";
											$obj_rs_editinvpaym = mysqli_query($obj_con, $str_sql_editinvpaym);
											$invnetamount = 0;
											while ($obj_row_editinvpaym = mysqli_fetch_array($obj_rs_editinvpaym)) {
												$invnetamount = $obj_row_editinvpaym["inv_netamount"] + $invnetamount;
											}
									?>
									<tr>
										<td>
											<?=$obj_row_edit["paym_no"]?>
											<input type="text" class="form-control d-none" name="editpaymidTB<?=$i;?>" value="<?=$obj_row_edit["paym_id"]?>">
											<input type="text" class="form-control d-none" name="" value="<?=$obj_row_edit["paym_no"]?>">
										</td>
										<td>
											<b>บริษัท : </b><?=$obj_row_edit["paya_name"]?><br>
											<b>รายการ : </b><?=$obj_row_edit["inv_description"]?>
											<input type="text" class="form-control d-none" name="" value="<?=$obj_row_edit["inv_description"]?>">
										</td>
										<td class="text-right">
											<?=number_format($invnetamount,2);?>
											<input type="text" class="form-control d-none" name="editnetamount" id="editnetamount" value="<?=number_format($invnetamount,2);?>">
											<input type="text" class="form-control d-none" name="editnetamountHidden" id="editnetamountHidden" value="<?=$invnetamount;?>">
										</td>
										<td class="text-center">
											<?=$obj_row_edit["dep_name"]?>
										</td>
										<td>
											<button class="btn btn-danger form-control delete_data" name="" id="<?=$obj_row_edit["paym_id"]?>">
												<i class="icofont-ui-delete"></i>&nbsp;&nbsp;ลบ
											</button>
										</td>
										<td class="d-none">
											<input type="text" class="form-control" name="editstatrepidTB<?=$i;?>" id="editstatrepidTB<?=$obj_row_edit["paym_id"];?>" value="<?=$obj_row_edit["paym_statusRepid"];?>">
										</td>
										<td class="d-none">
											<input type="text" class="form-control" name="editrepidTB<?=$i;?>" id="editrepidTB<?=$obj_row_edit["paym_id"];?>" value="<?=$obj_row_edit["paym_reppid"];?>">
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					<?php } ?>


					<div class="col-md-12">
						<div class="table-responsive d-none">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>เลขที่ใบสำคัญจ่าย</th>
										<th>รายละเอียด</th>
										<th>จำนวนเงิน</th>
										<th>Status REP</th>
										<th>REP ID</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$str_sql = "SELECT * FROM payment_tb AS paym INNER JOIN invoice_tb AS i ON paym.paym_id = i.inv_paymid WHERE paym_reppid = '' AND inv_compid = '". $cid ."' AND inv_depid = '". $dep ."' GROUP BY inv_paymid";
										$obj_rs = mysqli_query($obj_con, $str_sql);
										$x = 1;
										$invnetamount = 0;
										$countChk = 0;
										$countRow = mysqli_num_rows($obj_rs);
										// echo $countRow;
										while ($obj_row = mysqli_fetch_array($obj_rs)) {

											$str_sql_invpaym = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id WHERE inv_depid = '". $dep ."' AND inv_paymid = '" . $obj_row["inv_paymid"] . "'";
											$obj_rs_invpaym = mysqli_query($obj_con, $str_sql_invpaym);
											$invdesc = "";
											$invnetamount = 0;
											while ($obj_row_invpaym = mysqli_fetch_array($obj_rs_invpaym)) {
												$invdesc = $obj_row_invpaym["inv_description"] . " || " . $invdesc;
												$invnetamount = $obj_row_invpaym["inv_netamount"] + $invnetamount;
											}
											$sum += $net;
											$countChk += $countChk;
									?>
									<tr>
										<td>
											<input type="text" class="form-control" name="paymidTB<?=$x;?>" id="paymidTB<?=$obj_row["paym_id"];?>" value="<?=$obj_row["paym_id"]?>">
											<input type="text" class="form-control" name="" value="<?=$obj_row["paym_no"]?>">
										</td>
										<td>
											<input type="text" class="form-control" name="" value="<?=$obj_row["inv_description"]?>">
										</td>
										<td>
											<input type="text" class="form-control" name="netamount<?=$x;?>" id="netamount<?=$obj_row["paym_id"];?>" value="<?=number_format($invnetamount,2);?>">
											<input type="text" class="form-control" name="netamountHidden<?=$x;?>" id="netamountHidden<?=$obj_row["paym_id"];?>" value="<?=$invnetamount;?>">
										</td>
										<td>
											<input type="text" class="form-control" name="statrepidTB<?=$x;?>" id="statrepidTB<?=$obj_row["paym_id"];?>" value="<?=$obj_row["paym_statusRepid"];?>">
										</td>
										<td>
											<input type="text" class="form-control" name="repidTB<?=$x;?>" id="repidTB<?=$obj_row["paym_id"];?>" value="">
										</td>
									</tr>
									<?php $x++; } ?>
								</tbody>
							</table>
							<input type="text" class="form-control d-none" name="countDBAll" id="countDBAll" value="<?=$countRow;?>">
						</div>

						<div class="table-responsive" id="ReportPay_SelPayment"></div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 pb-4 text-center">
						<a href="reportpay_edit.php?cid=<?=$cid;?>&dep=<?=$dep;?>&reppid=<?=$reppid?>" class="btn btn-warning px-5 py-2">ย้อนกลับ</a>

						<input type="button" class="btn btn-success px-5 py-2" name="btnInsert" id="btnInsert" value="บันทึก">
					</div>
				</div>
				
			</form>
			
		</div>
	</section>

	<!-- START VIEW PAYMENT -->
	<div id="dataPayment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dataPaymentLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">รายละเอียดใบสำคัญจ่าย</h3>
					<button type="button" class="close" name="ยฟัทid_cancel" id="ยฟัทid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body" id="payment_detail">
				</div>
			</div>
		</div>
	</div>
	<!-- END VIEW PAYMENT -->

	<script type="text/javascript">
		$(document).ready(function() {

			load_data(1);
			function load_data(page, query = '', queryDep = '', queryComp = '', queryRepp = '', querySearchPaym = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				var queryRepp = $('#reppid').val();
				var querySearchPaym = $("#SearchPaymVal").val();
				$.ajax({
					url:"fetch_reportpay_selpayment_edit.php",
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep, queryComp:queryComp, queryRepp:queryRepp, querySearchPaym:querySearchPaym},
					success:function(data) {
						$('#ReportPay_SelPayment').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryRepp = $('#reppid').val();
				var querySearchPaym = $("#SearchPaymVal").val();
				load_data(page, query, queryDep, queryComp, queryRepp, querySearchPaym);
			});

			$('#search_box').keyup(function() {
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryRepp = $('#reppid').val();
				var querySearchPaym = $("#SearchPaymVal").val();
				load_data(1, query, queryDep, queryComp, queryRepp, querySearchPaym);
			});

			$("input[name='SearchPaymBy']").click(function(){
				$('#SearchPaymVal').val($("input[name='SearchPaymBy']:checked").val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var queryRepp = $('#reppid').val();
				var querySearchPaym = $("#SearchPaymVal").val();
				load_data(1, query, queryDep, queryComp, queryRepp, querySearchPaym);
			});



			//------ START INSERT ------//
			$("#btnInsert").click(function() {
				var formData = new FormData(this.form);
				var compid = $('#compid').val();
				var depid = $('#depid').val();
				var reppid = $('#reppid').val();
				var reppdno = $('#reppdno').val();
				if($('#countDBAll').val() == '0') {
					window.location.href = "reportpay_selpayment_preview.php?cid="+ compid +"&dep="+ depid +"&reppid="+ reppid +"&reppdno=" + reppdno;
				} else {
					if($('#CountChkAll').val() == '0') { 
						// swal({
						// 	title: "กรุณาเลือกใบสำคัญจ่ายที่ต้องการ \n อย่างน้อย 1 รายการ",
						// 	text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						// 	type: "warning"
						// }).then(function() {
						// 	frmReportPaySelPaym.ReportPay_SelPayment.focus();
						// });
						window.location.href = "reportpay_selpayment_preview.php?cid="+ compid +"&dep="+ depid +"&reppid="+ reppid +"&reppdno=" + reppdno;
					} else {
						$.ajax({
							type: "POST",
							url: "r_reportpay_selpayment.php",
							// data: $("#frmReportPaySelPaym").serialize(),
							data: formData,
							dataType: 'json',
							contentType: false,
							cache: false,
							processData:false,
							success: function(result) {
								if(result.status == 1) {
									window.location.href = "reportpay_selpayment_preview.php?cid="+ result.compid +"&dep="+ result.depid +"&reppid="+ result.reppid +"&reppdno=" + result.reppdno;
								} else {
									alert(result.message);
								}
							}
						});
					}
				}
			});
			//------ END INSERT ------//




			//------ START VIEW PAYMENT ------//
			$(document).on('click', '.view_data', function(){
				var id = $(this).attr("id");
				if(id != '') {
					$.ajax({
						url:"v_payment.php",
						method:"POST",
						data:{id:id},
						success:function(data){
							$('#payment_detail').html(data);
							$('#dataPayment').modal('show');
						}
					});
				}
			});
			//------ END VIEW PAYMENT ------//



			//--- DELETE ---//
			$(document).on('click', '.delete_data', function(){
				event.preventDefault();
				var del_id = $(this).attr("id");
				var element = this;
				swal({
					title: "ลบรายการนี้หรือไม่?",
					text: "เมื่อรายการนี้ถูกลบ คุณไม่สามารถกู้คืนได้!",
					type: "warning",
					showCancelButton: true,
					confirmButtonText: "ตกลง",
					cancelButtonText: "ยกเลิก",
					confirmButtonClass: 'btn btn-danger',
					cancelButtonClass: 'btn btn-secondary',
					closeOnConfirm: false,
					closeOnCancel: false,
					dangerMode: true,
				},
				function(isConfirm) {
					if (isConfirm) {
						$.ajax({
							url: "r_reportpay_delete.php",
							type: "POST",
							data: {del_id:del_id},
							success: function () {
								// setTimeout(function () {
								// 	swal("ลบข้อมูลสำเร็จ!", "กรุณากด ตกลง เพื่อดำเนินการต่อ", "success");
								// 	$(element).closest('tr').fadeOut(800,function(){
								// 		$(this).remove();
								// 	});
								// }, 1000);

								// swal({
								// 	title: "Success!",
								// 	text:  "You are now following",
								// 	type: "success",
								// 	timer: 5000,
								// 	showConfirmButton: false
								// });
								window.setTimeout(function(){ } ,3000);
								location.reload();
							}

						});
					} else {
						swal("ยกเลิก", "กรุณากด ตกลง เพื่อดำเนินการต่อ", "error");
					}
				});
			});
			//--- END DELETE ---//

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>