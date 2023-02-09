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
							<i class="icofont-papers"></i>&nbsp;&nbsp;สรุปรายการทำจ่าย ( ฝ่าย <?=$obj_row["dep_name"];?> )
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
										$str_sql_plus = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 1 AND reppd_no = '" . $_GET["reppdno"] . "'";
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
										$str_sql_dis = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 2 AND reppd_no = '" . $_GET["reppdno"] . "'";
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
										while ($obj_row = mysqli_fetch_array($obj_rs)) {

											$str_sql_invpaym = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id WHERE inv_depid = '". $dep ."' AND inv_paymid = '" . $obj_row["inv_paymid"] . "'";
											$obj_rs_invpaym = mysqli_query($obj_con, $str_sql_invpaym);
											$invdesc = "";
											$invnetamount = 0;
											while ($obj_row_invpaym = mysqli_fetch_array($obj_rs_invpaym)) {
												$invdesc = $obj_row_invpaym["inv_description"] . " || " . $invdesc;
												$invnetamount = $obj_row_invpaym["inv_netamount"] + $invnetamount;
											}
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
						</div>

						<div class="table-responsive" id="ReportPay_SelPayment"></div>
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
					url:"fetch_reportpay_selpayment.php",
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
				if($('#CountChkAll').val() == '0') {
					swal({
						title: "กรุณาเลือกใบสำคัญจ่ายที่ต้องการ \n อย่างน้อย 1 รายการ",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning"
					}).then(function() {
						frmReportPaySelPaym.ReportPay_SelPayment.focus();
					});
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

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>