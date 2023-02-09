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

		$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $dep ."'";
		$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
		$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

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
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmAddProject" id="frmAddProject" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มรายชื่อโครงการ
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-4">
						<label class="mb-1">รหัสโครงการ</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-building-alt"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="projid" id="projid" placeholder="เว้นว่างไว้เพื่อสร้างอัตโนมัติ" autocomplete="off" readonly>
						</div>
					</div>

					<div class="col-md-2">
						<label class="mb-1">ฝ่าย</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-building-alt"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="depname" id="depname" value="<?=$obj_row_dep["dep_name"];?>" readonly>
							<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$dep;?>" readonly>
						</div>
					</div>

					<div class="col-md-12">
						<label class="mb-1">ชื่อโครงการ</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-building-alt"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="projname" id="projname" placeholder="กรอกชื่อโครงการ" autocomplete="off">
						</div>
					</div>

					<div class="col-md-12">
						<label class="mb-1">ที่อยู่โครงการ</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-location-pin"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="projaddress" id="projaddress" placeholder="กรอกที่อยู่โครงการ" autocomplete="off">
						</div>
					</div>

					<div class="col-md-3">
						<div class="row">
							<div class="col-md-12 pt-1 pb-3">
								<label>มูลค่าสัญญา</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-baht"></i>
										</i>
									</div>
									<input type="text" class="form-control text-right" name="showValue" id="showValue" autocomplete="off" value="0.00">
									<input type="text" class="form-control text-right d-none" name="calValue" id="calValue" value="0.00">
								</div>
							</div>

							<div class="col-md-12 pt-1 pb-3">
								<label>จำนวนงวด</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<i class="input-group-text">
											<i class="icofont-listing-number"></i>
										</i>
									</div>
									<input type="text" class="form-control" name="projless" id="projless" autocomplete="off" placeholder="กรอกจำนวนงวดโครงการ">
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-9 pt-1 pb-3">
						<h4>ส่วนย่อยของโครงการ</h4>
						<div class="row">
							<div class="col-md-2 mb-0">
								<div class="checkbox">
									<input type="radio" name="PartProj" id="havePart" value="0" checked="checked">
									<label for="havePart"><span>มี</span></label>
								</div>
							</div>
							<div class="col-md-2 mb-0">
								<div class="checkbox">
									<input type="radio" name="PartProj" id="NothavePart" value="1">
									<label for="NothavePart"><span>ไม่มี</span></label>
								</div>
							</div>
							<div class="col-md-2 mb-0 d-none">
								<input type="text" class="form-control" name="PartVal" id="PartVal" value="0">
							</div>
						</div>

						<table class="table table-bordered" id="PartTable">
							<thead class="thead-light">
								<tr>
									<!-- <th width="20%">ส่วน</th> -->
									<th>รายละเอียด</th>
									<th width="15%">จำนวนงวด</th>
									<th width="20%">จำนวนเงิน</th>
									<th width="5%"></th>
								</tr>
							</thead>
							<tbody class="part" id="dynamicfieldPart">
								<tr id="part1">
									<td>
										<input type="text" class="form-control" name="partDesc1" id="partDesc1" autocomplete="off" placeholder="กรอกรายละเอียดส่วนย่อยโครงการ">
									</td>
									<td>
										<input type="text" class="form-control text-center" name="partlesson1" id="partlesson1" autocomplete="off" placeholder="กรอกงวด">
									</td>
									<td>
										<input type="text" class="form-control text-right" name="partShowValue1" id="partShowValue1" autocomplete="off" value="0.00">
										<input type="text" class="form-control text-right d-none" name="partCalValue1" id="partCalValue1" value="0.00">
									</td>
									<td class="d-none">
										<input type="text" class="form-control" name="Refprojid1" id="Refprojid1" autocomplete="off">
									</td>
									<td>
										<button type="button" name="addrow" id="addrow" class="btn btn-success">
											<i class="icofont-plus-circle"></i>
										</button>
									</td>
								</tr>
							</tbody>
							<span class="part d-none">Plus</span>
							<input type="text" class="form-control d-none" id="countPart" name="countPart" value="1">
						</table>
					</div>

				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 text-center pb-4">
						<input type="button" class="btn btn-success px-5 py-2 mx-1" name="btnInsert" id="btnInsert" value="บันทึก">
					</div>
				</div>
				
			</form>
			
		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function(){

			$("#btnInsert").click(function() {
				var formData = new FormData(this.form);
				if($('#projname').val() == '') {
					swal({
						title: "กรุณากรอกชื่อโครงการ",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmAddProject.projname.focus();
					});
				} else if($('#projaddress').val() == '') {
					swal({
						title: "กรุณากรอกที่อยู่โครงการ",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					}).then(function() {
						frmAddProject.projaddress.focus();
					});
				} else {
					if($('#PartVal').val() == 0) {
						if($('#partDesc1').val() == '') {
							swal({
								title: "กรุณากรอกรายละเอียดส่วนย่อยของโครงการ",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "warning",
								closeOnClickOutside: false
							}).then(function() {
								frmAddProject.partDesc1.focus();
							});
						} else if($('#partlesson1').val() == '') {
							swal({
								title: "กรุณากรอกจำนวนงวดส่วนย่อยของโครงการ",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "warning",
								closeOnClickOutside: false
							}).then(function() {
								frmAddProject.partDesc1.focus();
							});
						} else if($('#partlesson1').val() == '') {
							swal({
								title: "กรุณากรอกจำนวนเงินส่วนย่อยของโครงการ",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "warning",
								closeOnClickOutside: false
							}).then(function() {
								frmAddProject.partShowValue1.focus();
							});
						} else {
							$.ajax({
								type: "POST",
								url: "r_project_add.php",
								// data: $("#frmAddTaxcerPaym").serialize(),
								data: formData,
								dataType: 'json',
								contentType: false,
								cache: false,
								processData:false,
								success: function(result) {
									if(result.status == 1) {
										swal({
											title: "บันทึกข้อมูลสำเร็จ",
											text: "รหัสโครงการ " + result.projid,
											type: "success",
											closeOnClickOutside: false
										},function() {
											window.location.href = "invoice_rcpt_project.php?cid="+ result.compid + "&dep="+ result.depid;
										});
									} else {
										alert("Proj : " + result.message + "\nCont : " + result.messagePart);
									}
								}
							});
						}
					} else {
						$.ajax({
							type: "POST",
							url: "r_project_add.php",
							// data: $("#frmAddTaxcerPaym").serialize(),
							data: formData,
							dataType: 'json',
							contentType: false,
							cache: false,
							processData:false,
							success: function(result) {
								if(result.status == 1) {
									swal({
										title: "บันทึกข้อมูลสำเร็จ",
										text: "รหัสโครงการ " + result.projid,
										type: "success",
										closeOnClickOutside: false
									},function() {
										window.location.href = "invoice_rcpt_project.php?cid="+ result.compid + "&dep="+ result.depid;
									});
								} else {
									alert("Proj : " + result.message + "\nCont : " + result.messagePart);
								}
							}
						});
					}
				}
			});


			$("input[name='PartProj']").click(function(){
				$('#PartVal').val($("input[name='PartProj']:checked").val());

				if($('#PartVal').val() == 1) {
					$('#PartTable').hide();
				} else {
					$('#PartTable').show();
				}
			});

			var i = 1;
			$('#addrow').click(function(){
				i++;

				var n = $( '.part > tr' ).length + 1;
				$( 'span.part' ).text( "Part " + n);
				document.getElementById('countPart').value = n;

				$('#dynamicfieldPart').append('<tr id="part'+i+'"><td><input type="text" class="form-control" name="partDesc'+i+'" id="partDesc'+i+'" autocomplete="off" placeholder="กรอกรายละเอียดส่วนย่อยโครงการ"></td><td><input type="text" class="form-control text-center" name="partlesson'+i+'" id="partlesson'+i+'" autocomplete="off" placeholder="กรอกงวด"></td><td><input type="text" class="form-control text-right" name="partShowValue'+i+'" id="partShowValue'+i+'" autocomplete="off" value="0.00"><input type="text" class="form-control text-right d-none" name="partCalValue'+i+'" id="partCalValue'+i+'" value="0.00"></td><td class="d-none"><input type="text" class="form-control" name="Refprojid'+i+'" id="Refprojid'+i+'" autocomplete="off"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove"><i class="icofont-close"></i></button></td></tr><script>document.getElementById("partShowValue'+i+'").onblur = function (){ this.value = parseFloat(this.value.replace(/,/g, "")).toFixed(2).toString().replace(/\\B(?=(\\d{3})+(?!\\d))/g, ",");document.getElementById("partCalValue'+i+'").value = this.value.replace(/,/g, "");}<\/script>');

			});

			$(document).on('click', '.btn_remove', function(){
				var button_id = $(this).attr("id");
				$('#part'+ button_id +'').remove();
				var n = $( '.part > tr' ).length;
				$( 'span.part' ).text( "There are " + n + " divs." + "Click to add more.");
				document.getElementById('countPart').value = n;
			});

			function Comma(Num){
				Num += '';
				Num = Num.replace(/,/g, '');
				x = Num.split('.');
				x1 = x[0];
				x2 = x.length > 1 ? '.' + x[1] : '';
				var rgx = /(\d+)(\d{3})/;

				while (rgx.test(x1))
					x1 = x1.replace(rgx, '$1' + ',' + '$2');
				return x1 + x2;
			}

			document.getElementById("showValue").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("calValue").value = this.value.replace(/,/g, "");
			}

			document.getElementById("partShowValue1").onblur = function (){
				this.value = parseFloat(this.value.replace(/,/g, ""))
				.toFixed(2)
				.toString()
				.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				document.getElementById("partCalValue1").value = this.value.replace(/,/g, "");
			}

		});

	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>