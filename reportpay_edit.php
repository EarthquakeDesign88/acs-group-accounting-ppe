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

		$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
		$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
		$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

		$str_sql = "SELECT * FROM reportpay_tb1 WHERE repp_id = '". $reppid ."'";
		$obj_rs = mysqli_query($obj_con, $str_sql);
		$obj_row = mysqli_fetch_array($obj_rs);

		$str_sql_reppd = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_reppid = '". $reppid ."'";
		$obj_rs_reppd = mysqli_query($obj_con, $str_sql_reppd);
		$obj_row_reppd = mysqli_fetch_array($obj_rs_reppd);

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

			<form method="POST" name="frmUpdateReport" id="frmUpdateReport" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-papers"></i>
							&nbsp;&nbsp;สรุปรายการทำจ่าย ( ฝ่าย <?=$obj_row_dep["dep_name"];?> )
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
						<input type="text" class="form-control" name="useridCreate" value="<?=$obj_row["repp_userid_create"];?>">
						<input type="datetime" class="form-control" name="createDate" value="<?=$obj_row["repp_createdate"];?>">
						<input type="text" class="form-control" name="useridEdit" value="<?=$obj_row_user["user_id"];?>">
						<input type="date" class="form-control" name="editDate" value="">
						<input type="text" class="form-control" name="reppdno" id="reppdno" value="<?=$obj_row_reppd["reppd_no"];?>">
						<input type="text" class="form-control" name="reppid" id="reppid" value="<?=$reppid;?>">

						<input type="text" class="form-control" name="reppdate" id="reppdate" value="<?=$obj_row["repp_date"];?>">
						<input type="text" class="form-control" name="repppaydate" id="repppaydate" value="<?=$obj_row["repp_paydate"];?>">
						<input type="text" class="form-control" name="reppyear" id="reppyear" value="<?=$obj_row["repp_year"];?>">
						<input type="text" class="form-control" name="reppmonth" id="reppmonth" value="<?=$obj_row["repp_month"];?>">
						<input type="text" class="form-control" name="reppfile" id="reppfile" value="<?=$obj_row["repp_file"];?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-bordered">
								<tbody style="background-color: #FFF;">
									<tr>
										<td colspan="2" width="70%">
											<label for="txtsummarize" class="mb-1">สรุปยอด ณ </label>
											<div class="input-group">
												<input type="date" name="txtsummarize" id="txtsummarize" class="form-control" placeholder="กรอกวันที่สรุปยอด" value="<?=$obj_row["repp_date_summarize"];?>" readonly>
											</div>
										</td>
										<td colspan="2">
											<label for="txtTotalsummarize" class="mb-1">จำนวนเงิน</label>
											<div class="input-group">
												<input type="text" class="form-control text-right" name="txtTotalsummarize" id="txtTotalsummarize" placeholder="กรอกจำนวนเงินสรุปยอด" value="<?=number_format($obj_row["repp_total_summarize"],2);?>" readonly>
												<input type="text" class="form-control summarize text-right d-none" name="txtTotalsummarizeHidden" id="txtTotalsummarizeHidden" placeholder="กรอกจำนวนเงินสรุปยอด" value="<?=$obj_row["repp_total_summarize"];?>">
											</div>
										</td>
									</tr>
								</tbody>
							</table>

							<table class="table table-bordered">
								<!-- บวก -->
								<tbody style="border-top: none; background-color: #DAFFCC;">
									<tr>
										<td colspan="3">
											<label for="txtPlus" class="mb-0">บวก</label>
										</td>
									</tr>
								</tbody>
								<tbody class="plus" id="dynamicfieldPlus" style="border-top: none;">
									<?php
										$str_sql_plus = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 1 AND reppd_reppid = '" . $_GET["reppid"] . "'";
										$obj_rs_plus = mysqli_query($obj_con, $str_sql_plus);
										$i = 1;
										while ($obj_row_plus = mysqli_fetch_array($obj_rs_plus)) {
									?>
									<tr id="<?=$obj_row_plus["reppd_id"];?>" style="border-bottom: none;">
										<td class="d-none" data-target="reppdid">
											<b class="d-none"><?=$obj_row_plus["reppd_id"];?></b>
											<input type="text" class="form-control" name="repdidPlus<?=$i;?>" id="repdidPlus<?=$i;?>" value="<?=$obj_row_plus["reppd_id"];?>">
										</td>
										<td width="5%" class="d-none" style="border-top: none;" data-target="reppdtype">
											<b class="d-none"><?=$obj_row_plus["reppd_type"];?></b>
											<label for="selPlus<?=$i;?>" class="mb-1">บวก</label>
											<input type="text" class="form-control d-none" id="selPlus<?=$i;?>" name="selPlus<?=$i;?>" value="<?=$obj_row_plus["reppd_type"];?>">
										</td>
										<td width="70%" style="border-top: none;" data-target="reppddesc">
											<b class="d-none"><?=$obj_row_plus["reppd_description"];?></b>
											<input type="text" class="form-control" id="descPlus<?=$i;?>" name="descPlus<?=$i;?>" autocomplete="off" value="<?=$obj_row_plus["reppd_description"];?>" readonly>
										</td>
										<td style="border-top: none; text-align: right;" data-target="reppdamount">
											<b class="d-none"><?=$obj_row_plus["reppd_amount"];?></b>
											<input type="text" class="form-control text-right" id="totalPlus<?=$i;?>" name="totalPlus<?=$i;?>" value="<?=number_format($obj_row_plus["reppd_amount"],2);?>" autocomplete="off" readonly>
											<input type="text" class="form-control amountPlus d-none" id="totalPlusHidden<?=$i;?>" name="totalPlusHidden<?=$i;?>" value="<?=$obj_row_plus["reppd_amount"];?>" autocomplete="off">
										</td>
										<td>
											<!-- <a href="#" type="button" class="btn btn-warning" data-role="update" data-id="<?=$obj_row_plus["reppd_id"];?>">
												<i class="icofont-edit"></i>
											</a> -->
											<button type="button" name="edit" id="<?=$obj_row_plus["reppd_id"];?>" class="btn btn-warning edit_data" title="แก้ไข / Edit">
												<i class="icofont-edit"></i>
											</button>
										</td>
									</tr>
									<?php 
											$i++;
										} 
									?>
									<tr id="plus11">
										<td width="5%" class="d-none">
											<input type="text" class="form-control d-none" id="txtPlus1" name="txtPlus1" value="1">
										</td>
										<td width="55%">
											<input type="text" class="form-control" id="txtdescPlus1" name="txtdescPlus1" autocomplete="off">
										</td>
										<td>
											<input type="text" class="form-control text-right" id="txttotalPlus1" name="txttotalPlus1" value="0.00" autocomplete="off">
											<input type="text" class="form-control text-right amountPlus d-none" id="txttotalPlusHidden1" name="txttotalPlusHidden1" value="0.00" autocomplete="off">
										</td>
										<td class="d-none">
											<input type="text" class="form-control" id="txtPayPlus1" name="txtPayPlus1">
										</td>
										<td>
											<button type="button" name="addrowPlus" id="addrowPlus" class="btn btn-success">
												<i class="icofont-plus"></i>
											</button>
										</td>
									</tr>
									<input type="text" class="form-control d-none sumPlus" name="sumPlus" id="sumPlus">

									<span class="plus d-none">Plus</span>
									<input type="text" class="form-control d-none" id="countPlus" name="countPlus" value="1">
								</tbody>
								<!-- บวก -->

								<!-- จ่าย -->
								<tbody style="border-top: none; background-color: #ffe0e3;">
									<tr>
										<td colspan="3">
											<label for="txtDis" class="mb-0">จ่าย</label>
										</td>
									</tr>
								</tbody>
								<tbody class="dis" id="dynamicfieldDis" style="border-top: none;">
									<?php
										$str_sql_dis = "SELECT * FROM reportpay_desc_tb1 WHERE reppd_type = 2 AND reppd_reppid = '" . $_GET["reppid"] . "'";
										$obj_rs_dis = mysqli_query($obj_con, $str_sql_dis);
										$d = 1;
										while ($obj_row_dis = mysqli_fetch_array($obj_rs_dis)) {
									?>
									<tr id="dis2<?=$d;?>" style="border-bottom: none;">
										<td class="d-none" style="border-top: none;">
											<input type="text" class="form-control" name="repdidDis<?=$d;?>" id="repdidDis<?=$d;?>" value="<?=$obj_row_dis["reppd_id"];?>">
										</td>
										<td width="5%" class="d-none" style="border-top: none;">
											<label for="selDis<?=$d;?>" class="mb-1">จ่าย</label>
											<input type="text" class="form-control d-none" id="selDis<?=$d;?>" name="selDis<?=$d;?>" value="<?=$obj_row_dis["reppd_type"];?>">
										</td>
										<td width="70%" style="border-top: none;">
											<input type="text" class="form-control" id="descDis<?=$d;?>" name="descDis<?=$d;?>" autocomplete="off" value="<?=$obj_row_dis["reppd_description"];?>" readonly>
										</td>
										<td style="border-top: none; text-align: right;">
											<input type="text" class="form-control text-right" id="totalDis<?=$d;?>" name="totalDis<?=$d;?>" value="<?=number_format($obj_row_dis["reppd_amount"],2);?>" autocomplete="off" readonly>
											<input type="text" class="form-control amountDis d-none" id="totalDisHidden<?=$d;?>" name="totalDisHidden<?=$d;?>" value="<?=$obj_row_dis["reppd_amount"];?>" autocomplete="off">
										</td>
										<td>
											<button type="button" class="btn btn-warning">
												<i class="icofont-edit"></i>
											</button>
										</td>
									</tr>
									<?php 
											$d++;
										} 
									?>

									<tr id="dis21">
										<td width="5%" class="d-none">
											<input type="text" class="form-control d-none" id="txtDis1" name="txtDis1" value="2">
										</td>
										<td width="55%">
											<input type="text" class="form-control" id="txtdescDis1" name="txtdescDis1" autocomplete="off">
										</td>
										<td>
											<input type="text" class="form-control text-right" id="txttotalDis1" name="txttotalDis1" value="0.00" autocomplete="off">
											<input type="text" class="form-control text-right amountDis d-none" id="txttotalDisHidden1" name="txttotalDisHidden1" value="0.00" autocomplete="off">
										</td>
										<td class="d-none">
											<input type="text" class="form-control" id="txtPayDis1" name="txtPayDis1">
										</td>
										<td width="5%">
											<button type="button" name="addrowDis" id="addrowDis" class="btn btn-success">
												<i class="icofont-plus"></i>
											</button>
										</td>
									</tr>

									<input type="text" class="form-control d-none sumDis" name="sumDis" id="sumDis">

									<span class="dis d-none">Dis</span>
									<input type="text" class="form-control d-none" id="countDis" name="countDis" value="1">
								</tbody>
								<!-- จ่าย -->
							</table>

							<table class="table table-bordered">
								<tbody style="background-color: #FFF; border-top: none;">
									<tr>
										<td colspan="2" width="70%">
											<label for="txtbalance" class="mb-1">ยอด ณ</label>
											<div class="input-group">
												<input type="date" name="txtbalance" id="txtbalance" class="form-control" placeholder="กรอกวันที่ยอดเหลือ" value="<?=$obj_row["repp_date_balance"];?>" readonly>
											</div>
										</td>
										<td colspan="2">
											<label for="txtTotalbalance" class="mb-1">จำนวนเงิน</label>
											<div class="input-group">
												<input type="text" class="form-control text-right" name="txtTotalbalance" id="txtTotalbalance" placeholder="จำนวนเงิน" value="<?=number_format($obj_row["repp_total_balance"],2);?>" readonly>
												<input type="text" class="form-control text-right d-none" name="txtTotalbalanceHidden" id="txtTotalbalanceHidden" placeholder="จำนวนเงิน" value="<?=$obj_row["repp_total_balance"];?>" readonly>
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
						<input type="button" class="btn btn-success px-5 py-2" name="btnInsert" id="btnInsert" value="บันทึก">
					</div>
				</div>
				
			</form>
			
		</div>
	</section>

	<!-- Modal -->
	<div id="ModalUpdatePlus" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg modal-dialog-centered">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">แก้ไขรายละเอียด</h3>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					
				</div>
				<div class="modal-body">
					<div class="form-group d-none">
						<label></label>
						<input type="text" class="form-control" name="reppdid" id="reppdid">
					</div>

					<div class="form-group d-none">
						<label></label>
						<input type="text" class="form-control" name="reppdtype" id="reppdtype">
					</div>

					<div class="form-group">
						<label>รายละเอียด</label>
						<input type="text" class="form-control" name="reppddesc" id="reppddesc">
					</div>

					<div class="form-group">
						<label>จำนวนเงิน</label>
						<input type="text" class="form-control" name="reppdamount" id="reppdamount">
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-success" name="btnUpReppd" id="btnUpReppd" value="บันทึก">
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">

		$(document).ready(function(){

			$("#btnInsert").click(function() {
				var formData = new FormData(this.form);
				$.ajax({
					type: "POST",
					url: "r_reportpay_edit_update.php",
					// data: $("#frmReportPay").serialize(),
					data: formData,
					dataType: 'json',
					contentType: false,
					cache: false,
					processData:false,
					success: function(rsSuccess) {
						if(rsSuccess.status == 1) {
							// swal({
							// 	title: "บันทึกข้อมูลสำเร็จ",
							// 	text: "กรุณากด ตกลง เพื่อดำเนินการต่อ ",
							// 	type: "success",
							// 	closeOnClickOutside: false
							// },function() {
								window.location.href = "reportpay_selpayment_edit.php?cid=" + rsSuccess.compid + "&dep=" + rsSuccess.depid + "&reppdno=" + rsSuccess.reppdno + "&reppid=" + rsSuccess.reppid;
							// }); 
							// alert(rsSuccess.message);
						} else {
							alert("Result : " + rsSuccess.messageResult + "\nPlus : " + rsSuccess.messageUpPlus + "\nDis : " + rsSuccess.messageUpDis);
						}
					}
				});
			});
			

			$(document).on('click', '.edit_data', function(){
				var reppdid = $(this).attr("id");
				$.ajax({
					url:"r_reportpay_desc_edit.php",
					method:"POST",
					data:{reppdid:reppdid},
					dataType:"json",
					success:function(data){
						$('#reppdid').val(data.reppd_id);
						$('#reppdtype').val(data.reppd_type);
						$('#reppddesc').val(data.reppd_description);
						$('#reppdamount').val(data.reppd_amount);
						$('#ModalUpdatePlus').modal('show');
					}
				}); 
			});


			$(document).on('click', '#btnUpReppd', function(){
				var reppdid = $("#reppdid").val();
				var reppdtype = $("#reppdtype").val();
				var reppddesc = $("#reppddesc").val();
				var reppdamount = $("#reppdamount").val();
				$.ajax({
					url:"r_reportpay_edit.php",
					type:"POST",
					cache:false,
					data:{reppdid:reppdid,reppdtype:reppdtype,reppddesc:reppddesc,reppdamount:reppdamount},
					success:function(data){
						if (data ==1) {
							swal({
								title: "บันทึกข้อมูลสำเร็จ",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ ",
								type: "success",
								closeOnClickOutside: false
							},function() {
								$('#ModalUpdatePlus').modal('hide');
								window.location.reload();
							});
						}else{
							alert("Some thing went wrong");
						}
					}
				});
			});


			

			var i = 1;
			var h = 1;
			$('#addrowPlus').click(function(){
				i++;
				var n = $( '.plus > tr' ).length;
				$( 'span.plus' ).text( "Plus " + n);
				document.getElementById('countPlus').value = n;

				$('#dynamicfieldPlus').append('<tr id="plus'+h+i+'"><td width="5%" class="d-none"><input type="text" class="form-control d-none" id="txtPlus'+i+'" name="txtPlus'+i+'" value="1"></td><td width="55%"><input type="text" class="form-control" id="txtdescPlus'+i+'" name="txtdescPlus'+i+'" autocomplete="off"></td><td><input type="text" class="form-control text-right" id="txttotalPlus'+i+'" name="txttotalPlus'+i+'" value="0.00" autocomplete="off"><input type="text" class="form-control text-right amountPlus d-none" id="txttotalPlusHidden'+i+'" name="txttotalPlusHidden'+i+'" value="0.00" autocomplete="off"></td><td class="d-none"><input type="text" class="form-control" id="txtPayPlus'+i+'" name="txtPayPlus'+i+'"></td><td><button type="button" name="remove" id="'+h+i+'" class="btn btn-danger btn_remove"><i class="icofont-close"></i></button></td></tr><script>document.getElementById("txttotalPlus'+i+'").onblur = function (){ this.value = parseFloat(this.value.replace(/,/g, "")).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");document.getElementById("txttotalPlusHidden'+i+'").value = this.value.replace(/,/g, "");}<\/script>');

			});

			document.getElementById("txttotalPlus1").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
					.toFixed(2)
					.toString()
					.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				document.getElementById("txttotalPlusHidden1").value = this.value.replace(/,/g, "");
			}


			$(document).on('click', '.btn_remove', function(){
				var button_id = $(this).attr("id");
				$('#plus'+ button_id +'').remove();
				var n = $( '.plus > tr' ).length;
				$( 'span.plus' ).text( "Plus " + n);
				document.getElementById('countPlus').value = n;
			});





			var x = 1;
			var z = 2;
			$('#addrowDis').click(function(){
				x++;

				var y = $( '.dis > tr' ).length;
				$( 'span.dis' ).text( "Dis " + y );
				document.getElementById('countDis').value = y;

				$('#dynamicfieldDis').append('<tr id="dis'+z+x+'"><td width="5%"><input type="text" class="form-control d-none" id="txtDis'+x+'" name="txtDis'+x+'" value="2"></td><td width="55%"><input type="text" class="form-control" id="txtdescDis'+x+'" name="txtdescDis'+x+'" autocomplete="off"></td><td><input type="text" class="form-control text-right" id="txttotalDis'+x+'" name="txttotalDis'+x+'" value="0.00" autocomplete="off"><input type="text" class="form-control text-right amountDis d-none" id="txttotalDisHidden'+x+'" name="txttotalDisHidden'+x+'" value="0.00" autocomplete="off"></td><td class="d-none"><input type="text" class="form-control" id="txtPayDis'+x+'" name="txtPayDis'+x+'"></td><td><button type="button" name="remove" id="'+z+x+'" class="btn btn-danger btn_remove"><i class="icofont-close"></i></button></td></tr><script>document.getElementById("txttotalDis'+x+'").onblur = function (){ this.value = parseFloat(this.value.replace(/,/g, "")).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");document.getElementById("txttotalDisHidden'+x+'").value = this.value.replace(/,/g, "");}<\/script>');
			});

			document.getElementById("txttotalDis1").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
					.toFixed(2)
					.toString()
					.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				document.getElementById("txttotalDisHidden1").value = this.value.replace(/,/g, "");
			}

			$(document).on('click', '.btn_remove', function(){
				var btn_id = $(this).attr("id");
				$('#dis'+ btn_id +'').remove();
				var y = $( '.dis > tr' ).length;
				$( 'span.dis' ).text( "There are " + y + " divs." + "Click to add more.");
				document.getElementById('countDis').value = y;
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

		$(document).ready(function(){

			$("body").each(function() {
				$(this).click(function(){
					calculateSum();
				});
			});

			$(".form-control").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$(".amountPlus").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$(".amountDis").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

		});

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