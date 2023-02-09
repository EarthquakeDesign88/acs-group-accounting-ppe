<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];

		$str_sql_user = "SELECT * FROM user_tb AS u INNER JOIN level_tb AS l ON u.user_levid = l.lev_id INNER JOIN department_tb AS d ON u.user_depid = d.dep_id WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

		// echo $obj_row_user["lev_name"];

		$str_sql_comp = "SELECT * FROM company_tb WHERE comp_id = '". $cid ."'";
		$obj_rs_comp = mysqli_query($obj_con, $str_sql_comp);
		$obj_row_comp = mysqli_fetch_array($obj_rs_comp);

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
		.table td {
			vertical-align: middle;
		}
		tr:nth-last-child(n) {
			border-bottom: 1px solid #dee2e6;
		}
	</style>

</head>
<body onload="chkAccess();">

	<?php include 'navbar.php'; ?>

	<?php if ($obj_row_user["lev_id"] == '3' || $obj_row_user["lev_id"] == '4' || $obj_row_user["lev_id"] == '6' || $obj_row_user["lev_id"] == '7') {
	?>

			<script type="text/javascript">
				function chkAccess() {
					swal({
						title: 'ขออภัย คุณไม่มีสิทธิ์เข้าถึงข้อมูล!',
						text: 'กรุณากด ตกลง เพื่อดำเนินการต่อ',
						type: 'warning',
						closeOnClickOutside: false
					},function() {
						setTimeout(function(){
							window.history.back();
						});
					});
				}
			</script>

	<?php } else { ?>

	<section>
		<div class="container">

			<form method="POST" name="frmApprovePR" id="frmApprovePR">
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-6">
						<h3 class="mb-0">
							<i class="icofont-papers"></i>&nbsp;&nbsp;อนุมัติขอซื้อ/ขอจ้าง
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
						<input type="text" class="form-control" name="appruseridCEO" id="appruseridCEO" value="<?=$obj_row_user["user_id"];?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 d-none">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="thead-light">
									<tr>
										<th>NO.</th>
										<th>รายละเอียด</th>
										<th>จำนวนเงิน</th>
										<th>สถานะ</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$str_sql_pr = "SELECT * FROM purchasereq_tb AS pr INNER JOIN purchasereq_list_tb AS prl ON pr.purc_no = prl.purclist_purcno WHERE purc_apprceono = '' AND purc_compid = '". $cid ."' AND purc_depid = '". $dep ."' GROUP BY purclist_purcno";
										$obj_rs_pr = mysqli_query($obj_con, $str_sql_pr);

										if (mysqli_num_rows($obj_rs_pr) > 0) {
											$purcdesc = "";
											$i = 1;
										while ($obj_row_pr = mysqli_fetch_array($obj_rs_pr)) {
											$purcdesc = $obj_row_pr["purclist_description"];
									?>
									<tr>
										<td>
											<input type="text" class="form-control" name="purcno<?=$i;?>" id="purcno<?=$obj_row_pr["purc_id"];?>" value="<?= $obj_row_pr["purc_no"]; ?>" readonly>
											<input type="text" class="form-control d-none" name="purcid<?=$i;?>" id="purcid<?=$obj_row_pr["purc_id"];?>" value="<?= $obj_row_pr["purc_id"]; ?>">
										</td>
										<td>
											<input type="text" class="form-control" name="purcdesc<?=$i;?>" id="purcdesc<?=$obj_row_pr["purc_id"];?>" value="<?= $purcdesc; ?>" readonly>
										</td>
										<td>
											<input type="text" class="form-control" name="purctotal<?=$i;?>" id="purctotal<?=$obj_row_pr["purc_id"];?>" value="<?= $obj_row_pr["purc_total"]; ?>" readonly>
										</td>
										<td>
											<input type="text" class="form-control" name="purcstsceo<?=$i;?>" id="purcstsceo<?=$obj_row_pr["purc_id"];?>" value="<?= $obj_row_pr["purc_statusceo"]; ?>" readonly>
											<input type="text" class="form-control" name="purcapprceono<?=$i;?>" id="purcapprceono<?=$i;?>" value="<?= $obj_row_pr["purc_apprceono"]; ?>" readonly>
										</td>
									</tr>
									<?php $i++; } } ?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="col-md-12">
						<div class="table-responsive" id="PurchaseReqShow"></div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 text-center pb-5">
						<?php
							$str_sql_btn = "SELECT * FROM purchasereq_tb AS pr INNER JOIN purchasereq_list_tb AS prl ON pr.purc_no = prl.purclist_purcno WHERE purc_apprceono = '' AND purc_compid = '". $cid ."' AND purc_depid = '". $dep ."' GROUP BY purclist_purcno";
							$obj_rs_btn = mysqli_query($obj_con, $str_sql_btn);

							if (mysqli_num_rows($obj_rs_btn) > 0) {
						?>
						<input type="button" class="btn btn-success px-5 py-2" name="btnSave" id="btnSave" value="บันทึก">
						<?php } else { ?>
						<input action="action" onclick="window.history.go(-1); return false;" type="submit" class="btn btn-warning px-5 py-2" value="ย้อนกลับ" />
						<?php } ?>
					</div>
				</div>

			</form>

		</div>
	</section>


	<!-- START VIEW APPROVE -->
	<div id="datapurchaseReq" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="datapurchaseReqLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title py-2">รายละเอียดใบแจ้งหนี้</h3>
					<button type="button" class="close" name="purc_cancel" id="purc_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
				</div>
				<div class="modal-body" id="purchaseReq_detail">
				</div>
			</div>
		</div>
	</div>
	<!-- END VIEW APPROVE -->

	<script type="text/javascript">
		$(document).ready(function(){
			
			load_data(1);
			function load_data(page, query = '', queryDep = '', queryComp = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				$.ajax({
					url:"fetch_purchaseReq_ceo_approve.php",
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep, queryComp:queryComp},
					success:function(data) {
						$('#PurchaseReqShow').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function(){
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				load_data(page, query, queryDep, queryComp);
			});

			$('#search_box').keyup(function(){
				var query = $('#search_box').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				load_data(1, query, queryDep, queryComp);
			});

			//-- SAVE --//
			$("#btnSave").click(function() {
				var formData = new FormData(this.form);
				if($('#CountChkAll').val() == '0') {
					swal({
						title: "กรุณาเลือกใบแจ้งหนี้ที่ต้องการ\nอย่างน้อย 1 รายการ",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning"
					}).then(function() {
						frmApprovePR.PurchaseReqShow.focus();
					});
				} else {
					$.ajax({
						type: "POST",
						url: "r_purchaseReq_ceo_approve.php",
						// data: $("#frmApprovePR").serialize(),
						data: formData,
						dataType: 'json',
						contentType: false,
						cache: false,
						processData:false,
						success: function(result) {
							if(result.status == 1) {
								swal({
									title: "บันทึกข้อมูลสำเร็จ",
									text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
									type: "success",
									closeOnClickOutside: false
								},function() {
									window.location = "index.php?cid="+ result.compid +"&dep=";
								});
							} else if(result.status == 2) {
								swal({
									title: "บันทึกข้อมูลสำเร็จ",
									text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
									type: "success",
									closeOnClickOutside: false
								},function() {
									window.location = "index.php?cid="+ result.compid +"&dep=" + result.depid;
								});
							} else {
								alert(result.message);
							}
						}
					});
				}
			});
			//-- SAVE --//

			//-- VIEW --//
			$(document).on('click', '.view_data', function(){
				var id = $(this).attr("id");
				if(id != '') {
					$.ajax({
						url:"v_purchaseReq.php",
						method:"POST",
						data:{id:id},
						success:function(data){
							$('#purchaseReq_detail').html(data);
							$('#datapurchaseReq').modal('show');
						}
					});
				}
			});
			// END VIEW --//

		});
	</script>

	<?php } ?>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>