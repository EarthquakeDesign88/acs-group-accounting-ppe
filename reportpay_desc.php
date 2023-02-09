<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

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

		$str_sql = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
		$obj_rs = mysqli_query($obj_con, $str_sql);
		$obj_row = mysqli_fetch_array($obj_rs);

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
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmReportPayDesc" id="frmReportPayDesc" action="">

				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-papers"></i>
							&nbsp;&nbsp;สรุปรายการทำจ่าย ( ฝ่าย <?=$obj_row["dep_name"];?> )
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 pt-1 pb-3">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="thead-light">
									<tr>
										<th>รายละเอียด</th>
										<th>จำนวนเงิน</th>
										<th></th>
									</tr>
								</thead>

								<!-- บวก -->
								<tbody class="plus" style="border-top: none; background-color: #DAFFCC;">
									<tr>
										<td colspan="4">
											<label for="txtPlus" class="mb-0">บวก</label>
										</td>
									</tr>
								</tbody>
								<tbody class="plus" id="dynamicfieldPlus" style="border-top: none;">
									<tr id="plus11">
										<td width="5%" class="d-none">
											<label for="txtPlus1" class="mb-1">บวก</label>
											<input type="text" class="form-control d-none" id="txtPlus1" name="txtPlus1" value="1">
										</td>
										<td width="55%">
											<input type="text" class="form-control" id="txtdescPlus1" name="txtdescPlus1" autocomplete="off">
										</td>
										<td>
											<input type="text" class="form-control text-right" id="txttotalPlus1" name="txttotalPlus1" value="0.00" autocomplete="off">
											<input type="text" class="form-control text-right d-none" id="txttotalPlusHidden1" name="txttotalPlusHidden1" value="0.00" autocomplete="off">
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
								</tbody>										
								<!-- บวก -->
								<span class="plus d-none">Plus</span>
								<input type="text" class="form-control d-none" id="countPlus" name="countPlus" value="1">



								<!-- จ่าย -->
								<tbody class="dis" style="border-top: none; background-color: #ffe0e3;">
									<tr>
										<td colspan="4">
											<label for="txtDis" class="mb-0">จ่าย</label>
										</td>
									</tr>
								</tbody>
								<tbody class="dis" id="dynamicfieldDis" style="border-top: none;">
									<tr id="dis21">
										<td width="5%" class="d-none">
											<label for="txtDis1" class="mb-1">จ่าย</label>
											<input type="text" class="form-control d-none" id="txtDis1" name="txtDis1" value="2">
										</td>
										<td width="55%">
											<input type="text" class="form-control" id="txtdescDis1" name="txtdescDis1" autocomplete="off">
										</td>
										<td>
											<input type="text" class="form-control text-right" id="txttotalDis1" name="txttotalDis1" value="0.00" autocomplete="off">
											<input type="text" class="form-control text-right d-none" id="txttotalDisHidden1" name="txttotalDisHidden1" value="0.00" autocomplete="off">
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
								</tbody>
								<!-- จ่าย -->
								<span class="dis d-none">Dis</span>
								<input type="text" class="form-control d-none" id="countDis" name="countDis" value="1">

							</table>	
						</div>
					</div>

				</div>

				<input type="text" class="form-control d-none" name="sumPlus" id="sumPlus">

				<input type="text" class="form-control d-none" name="sumDis" id="sumDis">

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 pb-4 text-center">
						<input type="button" class="btn btn-success px-5 py-2" name="btnNext" id="btnNext" value="ถัดไป">
					</div>
				</div>
				
			</form>
			
		</div>
	</section>

	<script type="text/javascript">

		$(document).ready(function(){
			$("#btnNext").click(function() {
				var formData = new FormData(this.form);
				$.ajax({
					type: "POST",
					url: "r_reportpay_desc.php",
					// data: $("#frmReportPayDesc").serialize(),
					data: formData,
					dataType: 'json',
					contentType: false,
					cache: false,
					processData:false,
					success: function(result) {
						if(result.status == 1) {
							// swal({
							// 	title: "บันทึกข้อมูลสำเร็จ",
							// 	text: "กรุณากด ตกลง เพื่อดำเนินการต่อ ",
							// 	type: "success",
							// 	closeOnClickOutside: false
							// },function() {
								window.location.href = "reportpay_add.php?cid=" + result.compid + "&dep=" + result.depid + "&reppdno=" + result.reppdno;
							// });
							// alert(result.message);
						} else {
							alert("Plus : " + result.messagePlus + "\nDis : " + result.messageDis);
						}
					}
				});
			});
		});

		$(document).ready(function(){

			var i = 1;
			var h = 1;
			$('#addrowPlus').click(function(){
				i++;
				var n = $( '.plus > tr' ).length;
				$( 'span.plus' ).text( "Plus " + n);
				document.getElementById('countPlus').value = n;

				$('#dynamicfieldPlus').append('<tr id="plus'+h+i+'"><td width="5%" class="d-none"><input type="text" class="form-control d-none" id="txtPlus'+i+'" name="txtPlus'+i+'" value="1"></td><td width="55%"><input type="text" class="form-control" id="txtdescPlus'+i+'" name="txtdescPlus'+i+'" autocomplete="off"></td><td><input type="text" class="form-control text-right" id="txttotalPlus'+i+'" name="txttotalPlus'+i+'" value="0.00" autocomplete="off"><input type="text" class="form-control text-right d-none" id="txttotalPlusHidden'+i+'" name="txttotalPlusHidden'+i+'" value="0.00" autocomplete="off"></td><td class="d-none"><input type="text" class="form-control" id="txtPayPlus'+i+'" name="txtPayPlus'+i+'"></td><td><button type="button" name="remove" id="'+h+i+'" class="btn btn-danger btn_remove"><i class="icofont-close"></i></button></td></tr><script>document.getElementById("txttotalPlus'+i+'").onblur = function (){ this.value = parseFloat(this.value.replace(/,/g, "")).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");document.getElementById("txttotalPlusHidden'+i+'").value = this.value.replace(/,/g, "");}<\/script>');

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
				$( 'span.plus' ).text( "There are " + n + " divs." + "Click to add more.");
				document.getElementById('countPlus').value = n;
			});






			var x = 1;
			var z = 2;
			$('#addrowDis').click(function(){
				x++;

				var y = $( '.dis > tr' ).length;
				$( 'span.dis' ).text( "Dis " + y );
				document.getElementById('countDis').value = y;

				$('#dynamicfieldDis').append('<tr id="dis'+z+x+'"><td width="5%" class="d-none"><input type="text" class="form-control d-none" id="txtDis'+x+'" name="txtDis'+x+'" value="2"></td><td width="55%"><input type="text" class="form-control" id="txtdescDis'+x+'" name="txtdescDis'+x+'" autocomplete="off"></td><td><input type="text" class="form-control text-right" id="txttotalDis'+x+'" name="txttotalDis'+x+'" value="0.00" autocomplete="off"><input type="text" class="form-control text-right d-none" id="txttotalDisHidden'+x+'" name="txttotalDisHidden'+x+'" value="0.00" autocomplete="off"></td><td class="d-none"><input type="text" class="form-control" id="txtPayDis'+x+'" name="txtPayDis'+x+'"></td><td><button type="button" name="remove" id="'+z+x+'" class="btn btn-danger btn_remove"><i class="icofont-close"></i></button></td></tr><script>document.getElementById("txttotalDis'+x+'").onblur = function (){ this.value = parseFloat(this.value.replace(/,/g, "")).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");document.getElementById("txttotalDisHidden'+x+'").value = this.value.replace(/,/g, "");}<\/script>');
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




		// function calculateSum() {

		// 	var totalbalance = 0;
		// 	var summarize = parseFloat($('#txtTotalsummarizeHidden').val());
		// 	var totalPlus = 0;
		// 	var sumPlus = 0;

		// 	for (i = 1; i <= document.frmReportPay.countPlus.value; i++) {

		// 		totalPlus = parseFloat(eval("document.frmReportPay.txttotalPlus"+i+".value"));
		// 		sumPlus = totalPlus + sumPlus;
		// 		document.getElementById('sumPlus').value = sumPlus;

		// 	}

		// 	var totalDis = 0;
		// 	var sumDis = 0;

		// 	for (n = 1; n <= document.frmReportPay.countDis.value; n++) {

		// 		totalDis = parseFloat(eval("document.frmReportPay.txttotalDis"+n+".value"));
		// 		sumDis = totalDis + sumDis;
		// 		document.getElementById('sumDis').value = sumDis;

		// 	}

		// 	totalbalance = summarize + sumPlus + sumDis;

		// 	$('#txtTotalbalance').val(formatMoney(totalbalance));
		// 	$('#txtTotalbalanceHidden').val(totalbalance.toFixed(2));

		// }
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>