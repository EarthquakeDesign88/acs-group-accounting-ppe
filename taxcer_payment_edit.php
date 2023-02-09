<?php



	session_start();

	if (!$_SESSION["user_name"]){  //check session
		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
	} else {
		include 'connect.php';


		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		$paymid = $_GET["paymid"];
		$taxcid = $_GET["taxcid"];
		$taxcT = $_GET["taxcT"];


		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);



		// echo $obj_row_user["lev_name"];



		$str_sql = "SELECT * FROM taxcertificate_tb AS taxc 
					INNER JOIN payment_tb AS paym ON taxc.taxc_id = paym.paym_taxcid 
					INNER JOIN taxfiling_tb AS tf ON taxc.taxc_tfid = tf.tf_id 
					INNER JOIN taxpayer_tb AS tp ON taxc.taxc_tpid = tp.tp_id 
					INNER JOIN taxwithheld_tb AS twh ON taxc.taxc_twhid = twh.twh_id 
					INNER JOIN department_tb AS d ON taxc.taxc_depid = d.dep_id WHERE paym_taxcid = '". $taxcid ."'";

		$obj_rs = mysqli_query($obj_con, $str_sql);
		$obj_row = mysqli_fetch_array($obj_rs);



		$str_sql_paym = "SELECT * FROM payment_tb AS paym 
						INNER JOIN invoice_tb AS i ON paym.paym_id = i.inv_paymid 
						WHERE paym_id = '" . $obj_row["paym_id"] . "'";

		$obj_rs_paym = mysqli_query($obj_con, $str_sql_paym);

		$invdesc = "";
		$paymno = "";
		$invtax1 = 0;
		$invtax2 = 0;
		$invtax3 = 0;
		$invtaxtotal1 = 0;
		$invtaxtotal2 = 0;
		$invtaxtotal3 = 0;
		$inv_taxpercent1 = 0;
		$inv_taxpercent2 = 0;
		$inv_taxpercent3 = 0;
		$paympaydate = "0000-00-00";

		while ($obj_row_paym = mysqli_fetch_array($obj_rs_paym)) {
			if (mysqli_num_rows($obj_rs_paym) > 1) {
				$invdesc = $obj_row_paym["inv_description"] . " <span class='paymNO'></span> " . $invdesc;
				$paymno = $obj_row_paym["paym_no"] . " <span class='paymNO'></span> " . $paymno;

			} else {
				$invdesc = $obj_row_paym["inv_description"];
				$paymno = $obj_row_paym["paym_no"];
			}



			$invtax1 += $obj_row_paym["inv_tax1"];
			$invtax2 += $obj_row_paym["inv_tax2"];
			$invtax3 += $obj_row_paym["inv_tax3"];

			$invtaxtotal1 += $obj_row_paym["inv_taxtotal1"];
			$invtaxtotal2 += $obj_row_paym["inv_taxtotal2"];
			$invtaxtotal3 += $obj_row_paym["inv_taxtotal3"];

			$inv_taxpercent1 = $obj_row_paym["inv_taxpercent1"];
			$inv_taxpercent2 = $obj_row_paym["inv_taxpercent2"];
			$inv_taxpercent3 = $obj_row_paym["inv_taxpercent3"];

			$paympaydate = $obj_row_paym["paym_paydate"];

		}


?>

<!DOCTYPE html>

<html>

<head>

	

	<?php include 'head.php'; ?>


	<style type="text/css">
		@media only screen and (max-width: 992px)  {
			.descbtn {
				display: none;
			}
		}

		div#show-listTaxwithheld {
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

		

		span.paymNO { display: inline; }
		span.paymNO:before { content: " "; }
		span.paymNO:after { content: " || "; }
		span.paymNO:last-child:before { content: " "; }
		span.paymNO:last-child:after { content: " "; }
	</style>

</head>

<body>

	<?php include 'navbar.php'; ?>


	<section>
		<div class="container">
			<form method="POST" name="frmEditTaxcer" id="frmEditTaxcer" action="">
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-edit"></i>&nbsp;&nbsp;แก้ไขหนังสือรับรองการหักภาษี ณ ที่จ่าย
						</h3>
					</div>



					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?= $cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?= $dep;?>">
						<input type="text" class="form-control" name="taxcT" id="taxcT" value="<?= $taxcT; ?>">
						<input type="text" class="form-control" name="taxcid" id="taxcid" value="<?= $taxcid; ?>">
						<input type="text" class="form-control" name="taxcyear" id="taxcyear" value="<?= $obj_row["taxc_year"]; ?>">
						<input type="text" class="form-control" name="taxcmonth" id="taxcmonth" value="<?= $obj_row["taxc_month"]; ?>">
					</div>
				</div>



				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-4 col-sm-12 mr-auto pt-1 pb-3">
						<label for="taxcno" class="mb-1">เล่ม/เลขที่หักภาษี ณ ที่จ่าย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-numbered"></i>
								</i>
							</div>

							<input type="text" class="form-control" name="taxcno" id="taxcno" autocomplete="off" placeholder="เว้นว่างไว้เพื่อสร้างอัตโนมัติ" value="<?=$obj_row["taxc_no"];?>" readonly>
						</div>
					</div>



					<div class="col-md-4 col-sm-12 mr-auto pt-1 pb-3">
						<label for="taxcdate" class="mb-1">วันที่หักภาษี ณ ที่จ่าย</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-numbered"></i>
								</i>
							</div>

							<input type="date" class="form-control" name="taxcdate" id="taxcdate" autocomplete="off" placeholder="กรอกกรอกวันที่หักภาษี ณ ที่จ่าย" value="<?=$obj_row["taxc_date"];?>">
						</div>
					</div>



					<div class="col-md-4 col-sm-12 mr-auto pt-1 pb-3">
						<label for="taxcincome" class="mb-1">ประเภทเงินได้</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-list"></i>
								</i>
							</div>

							<input type="text" class="form-control" name="taxcincome" id="taxcincome" autocomplete="off" placeholder="กรอกประเภทเงินได้" value="<?=$obj_row["taxc_income"];?>">
						</div>
					</div>



					<div class="col-md-6 col-sm-12 mr-auto pt-1 pb-3">
						<label for="inputGroupSelectTF" class="mb-1">แบบยื่นภาษี</label>
						<div class="input-group">
							<select class="custom-select form-control" id="inputGroupSelectTF" name="inputGroupSelectTF">
								<option value="" selected>กรุณาเลือกแบบยื่นภาษี...</option>
								<?php 
									$str_sql_tf = "SELECT * FROM taxfiling_tb";
									$obj_rs_tf = mysqli_query($obj_con, $str_sql_tf);
									while ($obj_row_tf = mysqli_fetch_array($obj_rs_tf)) {

								?>

								<option value="<?=$obj_row_tf["tf_id"]?>" <?php if($obj_row["taxc_tfid"] == $obj_row_tf["tf_id"]) echo "selected"; ?>>
									<?=$obj_row_tf["tf_name"]?>
								</option>

								<?php } ?>

							</select>


							<input type="text" class="form-control d-none" name="taxcTF" id="taxcTF" value="<?=$obj_row["taxc_tfid"]?>">


							<script type="text/javascript">
								$(document).ready(function() {
									$('#inputGroupSelectTF').change(function(){
										$('#taxcTF').val($('#inputGroupSelectTF').val());
									});

								});
							</script>
						</div>
					</div>



					<div class="col-md-6 col-sm-12 mr-auto pt-1 pb-3">
						<label for="inputGroupSelectPayer" class="mb-1">ผู้จ่ายเงิน</label>
						<div class="input-group">
							<select class="custom-select form-control" id="inputGroupSelectPayer" name="inputGroupSelectPayer">
								<option value="" selected>กรุณาเลือกผู้จ่ายเงิน...</option>
								<?php 
									$str_sql_tp = "SELECT * FROM taxpayer_tb";
									$obj_rs_tp = mysqli_query($obj_con, $str_sql_tp);
									while ($obj_row_tp = mysqli_fetch_array($obj_rs_tp)) {
								?>

								<option value="<?=$obj_row_tp["tp_id"]?>" <?php if($obj_row["taxc_tpid"] == $obj_row_tp["tp_id"]) echo "selected"; ?>>
									<?=$obj_row_tp["tp_name"]?>
								</option>

								<?php } ?>

							</select>



							<input type="text" class="form-control d-none" name="taxcPayer" id="taxcPayer" value="<?=$obj_row["taxc_tpid"];?>">



							<script type="text/javascript">
								$(document).ready(function() {
									$('#inputGroupSelectPayer').change(function(){
										$('#taxcPayer').val($('#inputGroupSelectPayer').val());
									});
								});
							</script>
						</div>

					</div>



					<script type="text/javascript">
						function putValueTax(name,id) {
							$('#searchTaxwithheld').val(name);
							$('#twhid').val(id);

						}	

					</script>



					<script type="text/javascript" src="js/script_taxwithheld.js"></script>



					<div id="showDataTWH" class="col-md-12 mr-auto pt-1 pb-3">

						<label for="searchTaxwithheld" class="mb-1">ชื่อ-นามสกุล / ชื่อบริษัทหักภาษี ณ ที่จ่าย</label>

						<div class="input-group">

							<div class="input-group-prepend">

								<i class="input-group-text">

									<i class="icofont-company"></i>

								</i>

							</div>

							<input type="text" name="searchTaxwithheld" id="searchTaxwithheld" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" value="<?=$obj_row["twh_name"];?>" autocomplete="off">



							<input type="text" class="form-control d-none" id="twhid" name="twhid" value="<?=$obj_row["taxc_twhid"];?>">



							<div class="input-group-append">

								<button type="button" class="btn btn-info" onclick="document.getElementById('searchTaxwithheld').value = ''; document.getElementById('twhid').value = ''">

									<i class="icofont-close-circled"></i>

									<span class="descbtn">&nbsp;&nbsp;Clear</span>

								</button>

								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddTaxwithheld" data-backdrop="static" data-keyboard="false">

									<i class="icofont-plus-circle"></i>

									<span class="descbtn">&nbsp;&nbsp;เพิ่มบริษัท</span>

								</button>

							</div>

						</div>

						<div class="list-group" id="show-listTaxwithheld"></div>

					</div>



					<div class="col-md-12">
						<div class="row">
							<div class="col-md-6 col-sm-12 mr-auto pt-1 pb-3">
								<label for="paymid" class="mb-1">เลขที่ใบสำคัญจ่าย</label>
								<div style="border-bottom: 1px dotted #000;">
									<?= $paymno; ?>
								</div>
							</div>



							<div class="col-md-2 col-sm-12 mr-auto pt-1 pb-3">
								<label for="paymDep" class="mb-1">ฝ่าย</label>
								<div style="border-bottom: 1px dotted #000;">
									&nbsp;&nbsp;&nbsp;<?= $obj_row["dep_name"]; ?>
								</div>
							</div>



							<div class="col-md-4 col-sm-12 mr-auto pt-1 pb-3"></div>

						</div>

					</div>



					<div class="col-md-9 col-sm-9 mr-auto pt-1 pb-3">

						<label for="paymDate" class="mb-1">รายละเอียด</label>

						<div style="border-bottom: 1px dotted #000;">

							<?= $invdesc; ?>

						</div>

					</div>



					<div class="col-md-3 col-sm-3 mr-auto">
						<div class="col-md-12 col-sm-12 mr-auto pt-1 pb-3">
							<label for="payDate" class="mb-1">จำนวนเงินที่จ่าย</label>
							<div class="row">
								<div class="col-md-10" style="border-bottom: 1px dotted #000; text-align: right;">
									<?= number_format(($invtax1 + $invtax2 + $invtax3),2); ?>
								</div>

								<div class="col-md-2">
									<b>บาท</b>
								</div>
							</div>
						</div>



						<?php if($invtaxtotal1 && $inv_taxpercent1 > 0){ ?>
							<div class="col-md-12 col-sm-12 mr-auto pt-1 pb-3">
								<label for="payDate" class="mb-1">หักภาษี ณ ที่จ่าย <?= number_format($inv_taxpercent1, 0) ?> %</label>
								<div class="row">
									<div class="col-md-10" style="border-bottom: 1px dotted #000; text-align: right;">
										<?= number_format($invtaxtotal1,2); ?>
									</div>
									<div class="col-md-2">
										<b>บาท</b>
									</div>
								</div>
							</div>
						<?php }?>

						
						<?php if($invtaxtotal2 && $inv_taxpercent2 > 0){ ?>
						<div class="col-md-12 col-sm-12 mr-auto pt-1 pb-3">
							<label for="payDate" class="mb-1">หักภาษี ณ ที่จ่าย <?= number_format($inv_taxpercent2, 0) ?> %</label>
							<div class="row">
								<div class="col-md-10" style="border-bottom: 1px dotted #000; text-align: right;">
									<?= number_format($invtaxtotal2, 2); ?>
								</div>
								<div class="col-md-2">
									<b>บาท</b>
								</div>
							</div>
						</div>
						<?php }?>


						<?php if($invtaxtotal3 && $inv_taxpercent3 > 0){ ?>
						<div class="col-md-12 col-sm-12 mr-auto pt-1 pb-3">
								<label for="payDate" class="mb-1">หักภาษี ณ ที่จ่าย <?= number_format($inv_taxpercent3, 0) ?> %</label>
								<div class="row">
									<div class="col-md-10" style="border-bottom: 1px dotted #000; text-align: right;">
										<?= number_format($invtaxtotal3, 2); ?>
									</div>
									<div class="col-md-2">
										<b>บาท</b>
									</div>
								</div>
							</div>
						<?php }?>

					</div>

				</div>



				<div class="row py-4 px-1 d-none" style="background-color: #FFFFFF">

					<div class="col-md-3 pt-1 pb-3">

						<label class="mb-1">User Create</label>

						<div class="input-group">

							<input type="text" class="form-control" name="taxcuseridcreate" id="taxcuseridcreate" value="<?= $obj_row["taxc_userid_create"] ?>">

						</div>

					</div>



					<div class="col-md-3 pt-1 pb-3">

						<label class="mb-1">Date Create</label>

						<div class="input-group">

							<input type="datetime" class="form-control" name="taxcdatecreate" id="taxcdatecreate" value="<?= $obj_row["taxc_createdate"]; ?>">

						</div>

					</div>



					<div class="col-md-3 pt-1 pb-3">

						<label class="mb-1">User Edit</label>

						<div class="input-group">

							<input type="text" class="form-control" name="taxcuseridedit" id="taxcuseridedit" value="<?= $obj_row_user["user_id"] ?>">

						</div>

					</div>



					<div class="col-md-3 pt-1 pb-3">

						<label class="mb-1">Date Edit</label>

						<div class="input-group">

							<input type="date" class="form-control" name="taxcdateedit" id="taxcdateedit" value="">

						</div>

					</div>



					<div class="col-md-3 pt-1 pb-3">

						<label class="mb-1">Taxcer Rev</label>

						<div class="input-group">

							<input type="text" class="form-control" name="taxcrev" id="taxcrev" value="<?= $obj_row["taxc_rev"]; ?>">

						</div>

					</div>



					<div class="col-md-3 pt-1 pb-3">

						<label class="mb-1">Taxcer File</label>

						<div class="input-group">

							<input type="text" class="form-control" name="taxcfile" id="taxcfile" value="<?= $obj_row["taxc_file"]; ?>">

						</div>

					</div>

				</div>



			
				<div class="row py-4 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12 pb-4 text-center">
						<button type="button" class="btn btn-success btn-action btn-action-preview"><i class="icofont-save"></i> บันทึกข้อมูล</button>
					</div>
				</div>
			</form>

			

		</div>

	</section>

    <div id="modalEditForm" class="modal fade modal-detail" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2"><i class="icofont-save"></i> คุณต้องการบันทึกข้อมูลใช่หรือไม่?</h3>
				</div>

				<div class="modal-body" id="editForm">

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-danger btn-close" data-dismiss="modal" aria-hidden="true"><i class="icofont-close"></i> ไม่บันทึกข้อมูล</button>
					<button type="button" class="btn btn-success  btn-action btn-action-save"><i class="icofont-save"></i> บันทึกข้อมูล</button>
				</div>
			</div>
		</div>
	</div>



	<?php include 'taxwithheld_add_company.php'; ?>



	<script type="text/javascript">

		$(document).ready(function(){
			//--- START ADD TAX ---//
			$(".btn-action").click(function() {
                var formData = new FormData(document.getElementById("frmEditTaxcer"));
				if($('#taxcdate').val() == '') {
					swal({
						title: "กรุณากรอกวันที่หักภาษี ณ ที่จ่าย",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmEditTaxcer.taxcdate.focus();
					});

				} else if($('#taxcincome').val() == '') {
					swal({
						title: "กรุณากรอกประเภทเงินได้",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmEditTaxcer.taxcincome.focus();
					});
				} else if($('#inputGroupSelectTF').val() == '') {
					swal({
						title: "กรุณาเลือกแบบยื่นภาษี",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmEditTaxcer.inputGroupSelectTF.focus();
					});

				} else if($('#inputGroupSelectPayer').val() == '') {
					swal({
						title: "กรุณาเลือกผู้จ่ายเงิน",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmEditTaxcer.inputGroupSelectPayer.focus();

					});
				} else if($('#twhid').val() == '') {
					swal({
						title: "กรุณาเลือกบริษัทหักภาษี ณ ที่จ่าย",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmEditTaxcer.twhid.focus();

					});

				} else {
					if($(this).hasClass("btn-action-save")){
							$.ajax({
								type: "POST",
						        url: "r_taxcer_payment_edit.php",
								data: formData,
								dataType: 'json',
								contentType: false,
								cache: false,
								processData:false,
								success: function(result) {
									if(result.status == 1) {
										swal({
											title: "บันทึกข้อมูลสำเร็จ",
											text: "เล่ม / เลขที่หักภาษี ณ ที่จ่าย " + result.message,
											type: "success",
											closeOnClickOutside: false
										},function() {
											window.open("export/taxcer_payment_pdf.php?taxcID=" + result.id, '_blank');
											window.location.href ="taxcer_payment.php?cid=<?= $cid; ?>&dep=<?= $dep; ?>";
										});
									} else {
										alert(result.message);
									}
								}
							});
							
						}else{
							var modalSave = $("#modalEditForm");
							var div = $("#editForm");
							
							div.html("<br><div align='center'>กรุณารอสักครู่...<br><br><i class='icofont-spinner icofont-spin' style='font-size:24px'></i></div>");
							modalSave.modal('show');
							
							$.ajax({
								type: 'POST',
								url: "taxcer_payment_preview.php",
								data: formData,
								dataType: 'json',
								contentType: false,
								cache: false,
								processData:false,
								success:function(data){
									var preview_url = data.preview_url;
									var preview_path = data.preview_path;
									var content = '<object data="'+preview_url+'#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="500" style="border: 1px solid"></object>';
									div.html(content);
								}
							}); 
						}
				}

			});



		});



	</script>



	<?php include 'footer.php'; ?>



</body>

</html>

<?php } ?>