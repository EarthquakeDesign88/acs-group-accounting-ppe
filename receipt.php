<?php
include 'config/config.php'; 
__check_login();
__page_seldep2('รายรับ <i class="icofont-caret-right"></i> ใบเสร็จรับเงิน  <i class="icofont-caret-right"></i> เลือกฝ่าย');
?>
<?php
 	if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
 
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

		$str_sql = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
		$obj_rs = mysqli_query($obj_con, $str_sql);
		$obj_row = mysqli_fetch_array($obj_rs);

		$str_sql_status = "SELECT * FROM status_tb WHERE sts_id <> 'STS001'";
		$obj_rs_status = mysqli_query($obj_con, $str_sql_status);
		

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
			max-width: 400px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		th>.truncate-id, td>.truncate-id{
			width: auto;
			min-width: 0;
			max-width: 180px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		.stastus{
			font-size: 18px;
			font-weight: 600;
			padding: 5px;
			margin-right: 10px;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">

			<form method="POST" name="frmReceipt" id="frmReceipt" action="">
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12 pb-4">
						<h3 class="mb-0">
							<i class="icofont-papers"></i>&nbsp;&nbsp;ใบเสร็จรับเงิน - รายรับ
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="refir" id="refir" value="<?=$_GET["refir"];?>">

						<?php if($cid == 'C001') { ?>
							<input type="text" class="form-control d-none" name="viewComp" id="viewComp" value="ACS">
						<?php } 
						else if($cid == 'C004') { ?>
							<input type="text" class="form-control d-none" name="viewComp" id="viewComp" value="ACSP">
						<?php }
						else { ?>
							<input type="text" class="form-control d-none" name="viewComp" id="viewComp" value="<?=$obj_row["dep_name"];?>">
						<?php } ?>
					</div>

					<div class="col-md-12">
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
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchREBy" id="Reno" value="CONCAT_WS('/',re_bookno,re_no)" checked="checked">
											<label for="Reno"><span>เลขที่ใบเสร็จรับเงิน</span></label>
										</div>
									</div>
									<div class="col-md-3 mb-0">
										<div class="checkbox">
											<input type="radio" name="SearchREBy" id="Recustname" value="cust_name">
											<label for="Recustname"><span>ชื่อบริษัทผู้รับบริการ</span></label>
										</div>
									</div>
									<div class="col-md-3 mb-2">
										<div class="input-group">
                                            <span class="stastus">เลือกสถานะ</span><select class="status-select form-control" id="search_sts">
                                            	<option value="">ทั้งหมด</option>
												<?php while($obj_row_status = mysqli_fetch_array($obj_rs_status)){ ?>
                                                	<option value="<?= $obj_row_status['sts_id'] ?>"><?= $obj_row_status['sts_description'] ?></option>
												<?php } ?>
                                            </select>
                                        </div>
									</div>
									<div class="col-md-3 mb-0"></div>
									<input type="text" class="form-control d-none" name="SearchREVal" id="SearchREVal" value="CONCAT_WS('/',re_bookno,re_no)">
								</div>

								<div class="input-group">
									<input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกเลขที่ใบเสร็จรับเงินที่ต้องการค้นหา" autocomplete="off">
									<?php if ($cid == 'C001' || $cid == 'C004' || $cid == 'C014' || $cid == 'C015') { } else { ?> 
									<div class="input-group-append">
										<a href="receipt_noinvoice_add.php?cid=<?=$cid;?>&dep=<?=$dep;?>&projid=" class="btn btn-primary float-right">
											<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มใบเสร็จรับเงิน
										</a>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="table-responsive" id="receiptTable"></div>
					</div>
				</div>
			</form>

		</div>
	</section>

	<!-- START VIEW RECEIPT -->
	<!--<div id="dataReceipt" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dataReceiptLabel" aria-hidden="true">-->
	<!--	<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">-->
	<!--		<div class="modal-content">-->
	<!--			<div class="modal-header">-->
	<!--				<h3 class="modal-title py-2">รายละเอียดใบเสร็จรับเงิน</h3>-->
	<!--				<button type="button" class="close" name="reid_cancel" id="reid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>-->
	<!--			</div>-->
	<!--			<div class="modal-body" id="receipt_detail">-->
	<!--			</div>-->
	<!--		</div>-->
	<!--	</div>-->
	<!--</div>-->
	<!-- END VIEW RECEIPT -->
	
	<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body modal-body-action" >
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
				<button type="button" class="btn btn-primary" id="btn_save" data-dismiss="modal">ตกลง</button>
			</div>
			</div>
		</div>
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="js/script_receipt.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {

			$(document).on("click",'.selectpay',function(){
				let id = $(this).attr("id")
				$("#exampleModalLongTitle").html("<h2>เลือกวิธีการชำระเงิน</h2>")
				modalClear()
				modalContent(id)
				get_bank()
			})

			$(document).on("click",".check_pay",function(){
				let value = $(this).val()
				
				if(value == 1){
					// $("#chequeNo").attr("disabled", true);
					// $("#chequeNo").val("");
					// $("#SelBank").attr("disabled", true);
					// $("#SelBranch").attr("disabled", true);
					// $("#SelBranch").html('<option value="" selected disabled>กรุณาเลือกสาขา...</option>')
					// get_bank()
				}else if(value == 2){
					// $("#chequeNo").attr("disabled", false);
					// $("#SelBank").attr("disabled", true);
					// $("#SelBranch").attr("disabled", true);
					// $("#SelBranch").html('<option value="" selected disabled>กรุณาเลือกสาขา...</option>')
					// get_bank()

				}else if(value == 3){
					// $("#chequeNo").val("");
					// $("#SelBank").attr("disabled", false);
					// $("#chequeNo").attr("disabled", true);
				}
			})

			$(document).on('change', '#SelBank', function() {
				let value = $(this).val()
				get_branch(value)
			});

			$("#btn_save").click(function(){
			    let text_header = $("#exampleModalLongTitle").text()
			    if(text_header == "ยกเลิกใบเสร็จรับเงิน"){
			        savePay("cancel")
			    }else{
			        savePay("save_pay")
			    }
				
				load_data(1);
			})
			
			//------ START RECEIPT ------//
			load_data(1);
			function load_data(page, query = '', queryDep = '', queryComp = '', querySearch = '', queryRefir = '',queryStatus = '') {
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var querySearch = $('#SearchREVal').val();
				var queryRefir = $('#refir').val();
				var queryStatus = $("#search_sts").val();
				$.ajax({
					url:"fetch_receipt.php",
					method:"POST",
					data:{page:page, query:query, queryDep:queryDep, queryComp:queryComp, querySearch:querySearch, queryRefir:queryRefir,queryStatus},
					success:function(data) {
						$('#receiptTable').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var querySearch = $('#SearchREVal').val();
				var queryRefir = $('#refir').val();
				load_data(page, query, queryDep, queryComp, querySearch, queryRefir);
			});

			$(document).on('change', '.status-select', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var querySearch = $('#SearchREVal').val();
				var queryRefir = $('#refir').val();
				var queryStatus = $("#search_sts").val();
				load_data(page, query, queryDep, queryComp, querySearch, queryRefir,queryStatus);
			});

			$('#search_box').keyup(function() {
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var querySearch = $('#SearchREVal').val();
				var queryRefir = $('#refir').val();
				load_data(1, query, queryDep, queryComp, querySearch);
			});

			$("input[name='SearchREBy']").click(function(){
				$('#SearchREVal').val($("input[name='SearchREBy']:checked").val());
				var query = $('#search_box').val();
				var queryDep = $('#depid').val();
				var queryComp = $('#compid').val();
				var querySearch = $('#SearchREVal').val();
				var queryRefir = $('#refir').val();
				load_data(1, query, queryDep, queryComp, querySearch, queryRefir);
			});
			//------ END RECEIPT ------//


			//------ START VIEW RECEIPT ------//
			$(document).on('click', '.view_data', function(){
				var id = $(this).attr("id");
				var dep = document.getElementById("viewComp").value;
				if(id != '') {
					$.ajax({
						url:"v_receipt_"+ dep +".php",
						method:"POST",
						data:{id:id},
						success:function(data){
							$('#receipt_detail').html(data);
							$('#dataReceipt').modal('show');
						}
					});
				}
			});
			//------ END VIEW RECEIPT ------//
			

			//------ START DELETE RECEIPT ------//
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
					closeOnCancel: false
				}).then(function(isConfirm) {
					if (isConfirm) {
						$.ajax({
							url: "r_receipt_delete.php",
							type: "POST",
							data: {del_id:del_id},
							success: function () {
								// setTimeout(function () {
									swal({
										title: "ลบข้อมูลสำเร็จ!",
										text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
										type: 'success'
									}).then(function() {
										// frmReceipr.invoiceRevenueTable.focus();
										// location.reload();
										$("#receiptTable").empty();
										load_data(1);
									});
									// $(element).closest('tr').fadeOut(800,function(){
									// 	$(this).remove();
									// });
								// }, 1000);
							}
						});
					} else {
						swal({
							title: "ยกเลิก",
							text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
							type: 'error'
						});
					}
				});
			});
			//------ END DELETE RECEIPT ------//



			//------ CHECK MISSING RECEIPT FILE ------//
			$(document).on('click', '._cancel', function(e){
			    let _data = {}
				_data['re_no'] = $(this).attr("data-type");
				_data['ir_id'] = $(this).attr("data-type2");
				_data['re_id'] = $(this).attr("id");
				_data['_val'] = $(this).attr("data-type3");
				
				modalBodyCancel(_data)
				
			});
			
			function modalBodyCancel(_data){
			    modalClear()
			    $("#exampleModalLongTitle").html("<h2>ยกเลิกใบเสร็จรับเงิน</h2>")
			    html = '';
                html += `<div class="row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="ReNoteCancel" id="ReNoteCancel" rows="5" cols="70" placeholder="หมายเหตุ" autocomplete="off" ></textarea>
                                    </div>
                                    <input type="text" class="form-control d-none filter_pay" name="re_no" value="${_data['re_no']}">
                                    <input type="text" class="form-control d-none filter_pay" name="ir_id" value="${_data['ir_id']}">
                                    <input type="text" class="form-control d-none filter_pay" name="re_id" value="${_data['re_id']}">
                                    <input type="text" class="form-control d-none filter_pay" name="_val" value="${_data['_val']}">
                                </div>
                            </div>
                        </div>
                    </div>`			
                    
                $(".modal-body-action").append(html)
			    
			}

			//------ END CHECK MISSING RECEIPT FILE ------//

			function modalClear(){
				$(".modal-body-action").html("")
			}

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>