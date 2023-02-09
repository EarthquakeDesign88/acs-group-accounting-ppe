<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$purcno = $_GET["purcno"];

		$str_sql_user = "SELECT * FROM user_tb AS u INNER JOIN level_tb AS l ON u.user_levid = l.lev_id INNER JOIN department_tb AS d ON u.user_depid = d.dep_id WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
		$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
		$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

		$str_sql_comp = "SELECT * FROM company_tb WHERE comp_id = '".$cid."'";
		$obj_rs_comp = mysqli_query($obj_con, $str_sql_comp);
		$obj_row_comp = mysqli_fetch_array($obj_rs_comp);

		$str_sql_purc = "SELECT * FROM purchasereq_tb AS pr INNER JOIN company_tb AS c ON pr.purc_compid = c.comp_id INNER JOIN payable_tb AS p ON pr.purc_payaid = p.paya_id INNER JOIN department_tb AS d ON pr.purc_depid = d.dep_id WHERE purc_no = '". $purcno ."'";
		$obj_rs_purc = mysqli_query($obj_con, $str_sql_purc);
		$obj_row_purc = mysqli_fetch_array($obj_rs_purc);

		function bahtText(float $amount): string {
			[$integer, $fraction] = explode('.', number_format(abs($amount), 2, '.', ''));

			$baht = convert($integer);
			$satang = convert($fraction);

			$output = $amount < 0 ? 'ลบ' : '';
			$output .= $baht ? $baht.'บาท' : '';
			$output .= $satang ? $satang.'สตางค์' : 'ถ้วน';
	
			return $baht.$satang === '' ? 'ศูนย์บาทถ้วน' : $output;
		}

		function convert(string $number): string {
			$values = ['', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];
			$places = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน'];
			$exceptions = ['หนึ่งสิบ' => 'สิบ', 'สองสิบ' => 'ยี่สิบ', 'สิบหนึ่ง' => 'สิบเอ็ด'];

			$output = '';

			foreach (str_split(strrev($number)) as $place => $value) {
				if ($place % 6 === 0 && $place > 0) {
					$output = $places[6].$output;
				}

				if ($value !== '0') {
					$output = $values[$value].$places[$place % 6].$output;
				}
			}

			foreach ($exceptions as $search => $replace) {
				$output = str_replace($search, $replace, $output);
			}

			return $output;
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
		.table td {
			vertical-align: middle;
		}
		@media only screen and (max-width: 992px)  {
			.descbtn {
				display: none;
			}
		}
		@media (min-width: 576px) {
			.modal-dialog {
				/* max-width: 500px; */
				margin: 1.75rem auto;
			}
		}

		div#show-listComp , div#show-listPaya{
			position: absolute;
			z-index: 99;
			width: 100%;
			margin-left: -15px!important;
		}
		.list-unstyled {
			position: relative;
			background-color:#FFFF;
			cursor:pointer;
			margin-left: 15px;
			margin-right: 15px;
			-webkit-box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
					box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
		}
		.list-group-item {
			font-family: 'Sarabun', sans-serif;
			cursor: pointer;
			border: 1px solid #eaeaea;
			list-style: none;
			top: 50%;
			padding: .75rem!important;
		}
		.list-group-item:hover {
			background-color: #f5f5f5;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">
			
			<form method="POST" name="frmEditPurchase" id="frmEditPurchase" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-papers"></i>&nbsp;&nbsp;ขอซื้อ/ขอจ้าง
						</h3>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-4 pt-1 pb-3">
						<label for="purcno" class="mb-1">เลขที่ขอซื้อ/ขอจ้าง</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-numbered"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="purcno" id="purcno" autocomplete="off" placeholder="เว้นว่างไว้เพื่อสร้างอัตโนมัติ" value="<?= $obj_row_purc["purc_no"]; ?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label for="purcdate" class="mb-1">วันที่ขอซื้อ/ขอจ้าง</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="purcdate" id="purcdate" placeholder="กรอกเลขที่ใบแจ้งหนี้" value="<?= $obj_row_purc["purc_date"]; ?>" readonly>
						</div>
					</div>

					<div class="col-md-2 pt-1 pb-3">
						<label for="purcdepname" class="mb-1">ฝ่าย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="purcdepname" id="purcdepname" value="<?= $obj_row_purc["dep_name"]; ?>" readonly>
							<input type="text" class="form-control d-none" name="purcdepid" id="purcdepid" value="<?= $obj_row_purc["purc_depid"]; ?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3"></div>

					<div class="col-md-12 pt-1 pb-3">
						<label for="searchCompany" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทในเครือ</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div>
							<input type="text" name="searchCompany" id="searchCompany" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?= $obj_row_purc["comp_name"]; ?>" readonly>

							<input type="text" class="form-control d-none" id="invcompid" name="invcompid" value="<?= $obj_row_purc["purc_compid"]; ?>">
						</div>
					</div>

					<div class="col-md-12 pt-1 pb-3" id="showDataPaya">
						<label for="searchPayable" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทเจ้าหนี้</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div>
							<input type="text" name="searchPayable" id="searchPayable" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?= $obj_row_purc["paya_name"]; ?>">

							<input type="text" class="form-control d-none" id="invpayaid" name="invpayaid" value="<?= $obj_row_purc["purc_payaid"]; ?>">

							<div class="input-group-append">
								<button type="button" class="btn btn-info" onclick="
								document.getElementById('searchPayable').value = ''; 
								document.getElementById('invpayaid').value = '';
								document.getElementById('searchPayable').focus();
								document.getElementById('show-listPaya').style.display = 'none';" title="Clear">
									<i class="icofont-close-circled"></i>
									<span class="descbtn">&nbsp;&nbsp;Clear</span>
								</button>
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddPayable" data-backdrop="static" data-keyboard="false" title="เพิ่มบริษัท">
									<i class="icofont-plus-circle"></i>
									<span class="descbtn">&nbsp;&nbsp;เพิ่มบริษัท</span>
								</button>
							</div>
						</div>
						<div class="list-group" id="show-listPaya"></div>
					</div>

					<input type="button" class="btn btn-success px-5 py-2 d-none" name="addPRDesc" id="addPRDesc" value="เพิ่ม">

					<div class="col-md-12 pt-1 pb-3">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="thead-light">
									<tr>
										<th width="5%">No.</th>
										<th width="55%">รายการ</th>
										<th width="15%">ราคา/หน่วย</th>
										<th width="10%">จำนวน</th>
										<th width="15%">จำนวนเงิน</th>
									</tr>
								</thead>
								<tbody id="dynamicfieldPRDesc">
									<?php
										$str_sql_listcount = "SELECT * FROM purchasereq_list_tb WHERE purclist_purcno = '". $obj_row_purc["purc_no"] ."'";
										$obj_rs_listcount = mysqli_query($obj_con, $str_sql_listcount);
										$countlist = mysqli_num_rows($obj_rs_listcount);
										$rowlist = $countlist + 1;
									?>
										<input type="text" class="form-control d-none" id="prlistcount" value="<?= $rowlist; ?>">
									<?php
										$str_sql_list = "SELECT * FROM purchasereq_list_tb WHERE purclist_purcno = '". $obj_row_purc["purc_no"] ."'";
										$obj_rs_list = mysqli_query($obj_con, $str_sql_list);
										$i = 1;
										while ($obj_row_list = mysqli_fetch_array($obj_rs_list)) {
									?>
									<tr id="PR<?=$i;?>">
										<td class="text-center">
											<b><?=$i;?>.</b>
											<input type="text" class="form-control d-none" name="purclistid<?=$i;?>" id="purclistid<?=$i;?>" value="<?=$obj_row_list["purclist_purcno"]."-".$i;?>">
										</td>
										<td>
											<input type="text" class="form-control" name="purcdesc<?=$i;?>" id="purcdesc<?=$i;?>" value="<?=$obj_row_list["purclist_description"];?>">
										</td>
										<td>
											<input type="text" class="form-control text-right" name="purcunitprice<?=$i;?>" id="purcunitprice<?=$i;?>" value="<?=number_format($obj_row_list["purclist_unitprice"],2);?>">
											<input type="text" class="form-control text-right d-none" name="purcunitpriceIns<?=$i;?>" id="purcunitpriceIns<?=$i;?>" value="<?=$obj_row_list["purclist_unitprice"];?>">
										</td>
										<td>
											<input type="text" class="form-control text-right" name="purcunit<?=$i;?>" id="purcunit<?=$i;?>" value="<?=number_format($obj_row_list["purclist_unit"],2);?>">
											<input type="text" class="form-control text-right d-none" name="purcunitIns<?=$i;?>" id="purcunitIns<?=$i;?>" value="<?=$obj_row_list["purclist_unit"];?>">
										</td>
										<td>
											<input type="text" class="form-control text-right" name="purctotal<?=$i;?>" id="purctotal<?=$i;?>" value="<?=number_format($obj_row_list["purclist_total"],2);?>" readonly>
											<input type="text" class="form-control text-right d-none sumsubTotal" name="purctotalIns<?=$i;?>" id="purctotalIns<?=$i;?>" value="<?=number_format($obj_row_list["purclist_total"],2);?>" readonly>
										</td>
									</tr>
									<?php	
										$i++; }
									?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2"></td>
										<td colspan="2" class="text-right">
											<b>รวมเงิน :</b>
										</td>
										<td>
											<input type="text" class="form-control text-right" name="purcsubtotal" id="purcsubtotal" value="<?=number_format($obj_row_purc["purc_subtotal"],2);?>" readonly>
											<input type="text" class="form-control text-right d-none" name="purcsubtotalIns" id="purcsubtotalIns" value="<?=$obj_row_purc["purc_subtotal"];?>" readonly>
										</td>
									</tr>
									<tr>
										<td colspan="2"></td>
										<td colspan="2" class="text-right">
											<b>หักส่วนลด :</b>
										</td>
										<td>
											<input type="text" class="form-control text-right" name="purcdis" id="purcdis" value="<?=number_format($obj_row_purc["purc_discount"],2);?>">
											<input type="text" class="form-control text-right d-none" name="purcdisIns" id="purcdisIns" value="<?=$obj_row_purc["purc_discount"];?>">
										</td>
									</tr>
									<tr>
										<td colspan="2"></td>
										<td colspan="2" class="text-right">
											<table>
												<tr>
													<td width="50%" style="border: none; padding: 0 .75rem">
														<b>Vat :</b>
													</td>
													<td style="border: none; padding: 0 .75rem;">
														<input type="text" class="form-control text-right" name="purcvatper" id="purcvatper" value="<?=number_format($obj_row_purc["purc_vatpercent"],2);?>">
														<input type="text" class="form-control text-right d-none" name="purcvatperIns" id="purcvatperIns" value="<?=$obj_row_purc["purc_vatpercent"];?>">
													</td>
													<td width="5%" style="border: none; padding: 0 .75rem">
														<b>%</b>
													</td>
												</tr>
											</table>
										</td>
										<td>
											<input type="text" class="form-control text-right" name="purcvat" id="purcvat" value="<?=number_format($obj_row_purc["purc_vat"],2);?>" readonly>
											<input type="text" class="form-control text-right d-none" name="purcvatIns" id="purcvatIns" value="<?=$obj_row_purc["purc_vat"];?>" readonly>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<b>จำนวนเงิน : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="totalText"></span>
											<?=bahtText($obj_row_purc["purc_total"]);?>
										</td>
										<td colspan="2" class="text-right">
											<b>จำนวนเงินรวมทั้งสิ้น :</b>
										</td>
										<td>
											<input type="text" class="form-control text-right" name="purctotal" id="purctotal" value="<?=number_format($obj_row_purc["purc_total"],2);?>0" readonly>
											<input type="text" class="form-control text-right d-none" name="purctotalIns" id="purctotalIns" value="<?=$obj_row_purc["purc_total"];?>" readonly>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1 d-none" style="background-color: #FFFFFF">
					<div class="col-md-3 pt-1 pb-3">
						<label>User Create</label>
						<div class="input-group">
							<input type="text" class="form-control" name="purcuseridCreate" id="purcuseridCreate" value="<?=$obj_row_purc["purc_userid_create"];?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label>Date Create</label>
						<div class="input-group">
							<input type="datetime" class="form-control" name="purcdateCreate" id="purcdateCreate" value="<?=$obj_row_purc["purc_datecreate"];?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label>User Edit</label>
						<div class="input-group">
							<input type="text" class="form-control" name="purcuseridEdit" id="purcuseridEdit" value="<?=$obj_row_user["user_id"];?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label>Date Edit</label>
						<div class="input-group">
							<input type="datetime" class="form-control" name="purcdateEdit" id="purcdateEdit" value="" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label>Status Approve PR No</label>
						<div class="input-group">
							<input type="text" class="form-control" name="purcstsPRNo" id="purcstsPRNo" value="<?=$obj_row_purc["purc_statusceo"];?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label>Approve PR No</label>
						<div class="input-group">
							<input type="text" class="form-control" name="purcapprPRNo" id="purcapprPRNo" value="<?=$obj_row_purc["purc_apprceono"];?>" readonly>
						</div>
					</div>

					<div class="col-md-3 pt-1 pb-3">
						<label>PR file</label>
						<div class="input-group">
							<input type="text" class="form-control" name="purcfile" id="purcfile" value="<?=$obj_row_purc["purc_file"];?>" readonly>
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

	<?php include 'invoice_add_payable.php'; ?>

	<script type="text/javascript">

		$(document).ready(function(){
			$("#btnInsert").click(function() {
				var formData = new FormData(this.form);
				if($('#invpayaid').val() == '') {
					swal({
						title: "กรุณากรอกชื่อ-นามสกุล / ชื่อบริษัทเจ้าหนี้",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmEditPurchase.invpayaid.focus();
					});
				} else {
					$.ajax({
						type: "POST",
						url: "r_purchaseReq_edit.php",
						data: formData,
						dataType: 'json',
						contentType: false,
						cache: false,
						processData:false,
						success: function(result) {
							if(result.status == 1) {
								swal({
									title: "บันทึกข้อมูลสำเร็จ",
									text: "เลขที่ใบขอซื้อ/ขอจ้าง : " + result.purcno,
									type: "success",
									closeOnClickOutside: false
								},function() {
									window.location.href = "purchaseReq.php?cid=" + result.compid + "&dep=" + result.depid;
								});
							} else {
								alert(result.message);
							}
						}
					});
				}
			});
		});

		$(document).ready(function(){
			var list = document.getElementById("prlistcount").value;
			var purcno = document.getElementById("purcno").value;
			for (var i = list; i <= 10; i++) {
				$('#dynamicfieldPRDesc').append('<tr id="PR'+i+'"><td class="text-center"><b>'+i+'.<input type="text" class="form-control d-none" name="purclistid'+i+'" id="purclistid'+i+'" value=""></b></td><td><input type="text" class="form-control" name="purcdesc'+i+'" id="purcdesc'+i+'"></td><td><input type="text" class="form-control text-right" name="purcunitprice'+i+'" id="purcunitprice'+i+'" value="0.00"><input type="text" class="form-control text-right d-none" name="purcunitpriceIns'+i+'" id="purcunitpriceIns'+i+'" value="0.00"></td><td><input type="text" class="form-control text-right" name="purcunit'+i+'" id="purcunit'+i+'" value="0.00"><input type="text" class="form-control text-right d-none" name="purcunitIns'+i+'" id="purcunitIns'+i+'" value="0.00"></td><td><input type="text" class="form-control text-right" name="purctotal'+i+'" id="purctotal'+i+'" value="0.00" readonly><input type="text" class="form-control text-right d-none sumsubTotal" name="purctotalIns'+i+'" id="purctotalIns'+i+'" value="0.00" readonly></td></tr>');
			}


			$(".form-control").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			$("input").each(function() {
				$(this).keyup(function(){
					calculateSum();
				});
			});

			document.getElementById("purcunitprice1").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitpriceIns1").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunitprice2").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitpriceIns2").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunitprice3").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitpriceIns3").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunitprice4").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitpriceIns4").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunitprice5").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitpriceIns5").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunitprice6").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitpriceIns6").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunitprice7").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitpriceIns7").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunitprice8").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitpriceIns8").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunitprice9").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitpriceIns9").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunitprice10").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitpriceIns10").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunit1").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitIns1").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunit2").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitIns2").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunit3").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitIns3").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunit4").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitIns4").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunit5").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitIns5").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunit6").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitIns6").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunit7").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitIns7").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunit8").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitIns8").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunit9").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitIns9").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcunit10").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcunitIns10").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcvatper").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcvatperIns").value = this.value.replace(/,/g, "");
			}

			document.getElementById("purcdis").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("purcdisIns").value = this.value.replace(/,/g, "");
			}
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
			
			for (var n = 1; n <= 10; n++) {

				var unitPrice = parseFloat($('#purcunitpriceIns'+n).val());
				var unit = parseFloat($('#purcunitIns'+n).val());
				var total = parseFloat(unitPrice * unit) || 0;
				$('#purctotal'+n).val(formatMoney(total));
				$('#purctotalIns'+n).val(total.toFixed(2));

			}

			var subTotal = 0;

			$(".sumsubTotal").each(function() {
				if(!isNaN(this.value) && this.value.length!=0) {
					subTotal += parseFloat(this.value);
				}
			});
			$('#purcsubtotal').val(formatMoney(subTotal));
			$('#purcsubtotalIns').val(subTotal.toFixed(2));

			
			var prsubTotal = parseFloat($('#purcsubtotalIns').val());
			var dis = parseFloat($('#purcdisIns').val());
			var vatper = parseFloat($('#purcvatperIns').val());
			var vat = parseFloat(((prsubTotal - dis) * vatper) / 100) || 0;
			$('#purcvat').val(formatMoney(vat));
			$('#purcvatIns').val(vat.toFixed(2));

			var vatvalue = parseFloat($('#purcvatIns').val());
			var total = parseFloat(((prsubTotal - dis) + vat)) || 0;
			$('#purctotal').val(formatMoney(total));
			$('#purctotalIns').val(total.toFixed(2));

			// var thaibath = bahtText(total);
			// $('#totalText').html(thaibath);

		}

	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>