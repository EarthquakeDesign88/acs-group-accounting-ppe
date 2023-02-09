<?php
include 'config/config.php'; 
__check_login();
__page_seldep2('แฟ้มข้อมูล <i class="icofont-caret-right"></i> รายจ่าย  <i class="icofont-caret-right"></i> เช็ค <i class="icofont-caret-right"></i> เลือกฝ่าย');
?>
<?php
	 if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	 
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {

		include 'connect.php';

		$cid = $_GET["cid"];
		$dep = $_GET["dep"];
		// $paymid = $_GET["paymid"];
		$bid = (isset($_GET["bid"])) ? $_GET["bid"] : "B001";

		// $str_sql = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id LEFT JOIN cheque_tb AS cheq ON paym.paym_cheqid = cheq.cheq_id LEFT JOIN bank_tb AS b ON cheq.cheq_bankid = b.bank_id WHERE bank_id = '".$bid."'";

		$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '".$dep."'";
		$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
		$obj_row_dep = mysqli_fetch_array($obj_rs_dep);

		$str_sql_getbank = "SELECT * FROM bank_tb WHERE bank_id = '".$bid."'";
		$obj_rs_getbank = mysqli_query($obj_con, $str_sql_getbank);
		$obj_row_getbank = mysqli_fetch_array($obj_rs_getbank);

		$str_sql = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id LEFT JOIN cheque_tb AS cheq ON paym.paym_cheqid = cheq.cheq_id LEFT JOIN bank_tb AS b ON cheq.cheq_bankid = b.bank_id WHERE inv_compid = '".$cid."' AND inv_depid = '".$dep."' AND paym_cheqid <> '' AND bank_id = '".$bid."'";
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
			max-width: 200px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		.nav-pills .nav-link.active {
			background-color: #28a7e9;
		}
		.nav-tabs .nav-link {
		    color: #000;
		    border: 0;
		    /*border-bottom: 1px solid grey;*/
		    padding: .75rem 0rem;
		    font-weight: 700;
		    background-color: #e9ecef;
		    border-top-left-radius: 0!important;
			border-top-right-radius: 0!important;
			width: 72px;
			height: 50px;
			text-align: center;
		}
		.nav-tabs .nav-link:hover {
		    /*border: 0;*/
		    /*border-bottom: 1px solid grey;*/
		}
		.nav-item.active {
			background-color: #e9ecef;
		}
		.nav-tabs .nav-link.active {
		    color: #000;
		    border: 0;
		    /*border-radius: 0;*/
		    border-bottom: 4px solid #28a7e9;
		    padding: .75rem 0rem;
		    background-color: #e9ecef;
		}
		.nav-tabs .nav-item {
			margin-bottom: -4px;
		}
	</style>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">
			
			<form method="POST" name="" id="" action="">

				<div class="row py-4 px-1" style="background-color: #e9ecef">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-file-pdf"></i>
							&nbsp;&nbsp;เช็ค
							&nbsp;&nbsp;ฝ่าย <?=$obj_row_dep["dep_name"]?>
						</h3>
					</div>
				</div>

				<!-- <div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-2">
						
						<div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">

							<?php
								$str_sql_bank = "SELECT * FROM bank_tb";
								$obj_rs_bank = mysqli_query($obj_con, $str_sql_bank);
								while ($obj_row_bank = mysqli_fetch_array($obj_rs_bank)) {
							?>

							<a class="nav-link mb-3 p-3 shadow <?php if ($obj_row_bank["bank_id"] == $bid) echo "active"; ?>" id="<?=$obj_row_bank["bank_nameShort"]?>-tab" href="cheque_file.php?cid=<?=$cid?>&dep=<?=$dep?>&bid=<?=$obj_row_bank["bank_id"]?>" role="tab" aria-controls="v-pills-home" aria-selected="true">
								<i class="icofont-bank-alt mr-2"></i>
								<span class="font-weight-bold  text-uppercase">
									<?=$obj_row_bank["bank_nameShort"]?>
								</span>
							</a>

							<?php } ?>

						</div>
						
					</div>

					<div class="col-md-10">

						<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$_GET["dep"]?>">
						<input type="text" class="form-control d-none" name="compid" id="compid" value="<?=$_GET["cid"]?>">
						<input type="text" class="form-control d-none" name="bankid" id="bankid" value="<?=$bid?>">

						<div class="tab-content" id="v-pills-tabContent">
							<div class="tab-pane fade shadow rounded bg-white show <?php if ($obj_row["bank_id"] == $bid) echo "active"; ?> p-4" id="<?=$obj_row["bank_nameShort"]?>" role="tabpanel" aria-labelledby="<?=$obj_row["bank_nameShort"]?>-tab">

								<h3 class="mb-0"><?= $bid; ?></h3>

								<div class="tab-content py-4">
									<div class="tab-pane active" id="">
										<div class="table-responsive" id="seepdfTable"></div>
									</div>
								</div>

							</div>
							
						</div>
						
					</div>
				</div> -->

				

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-2">
						
						<div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">

							<?php
								$str_sql_bank = "SELECT * FROM bank_tb";
								$obj_rs_bank = mysqli_query($obj_con, $str_sql_bank);
								while ($obj_row_bank = mysqli_fetch_array($obj_rs_bank)) {
							?>

							<a class="nav-link mb-3 p-3 shadow <?php if ($obj_row_bank["bank_id"] == $bid) echo "active"; ?>" id="<?=$obj_row_bank["bank_id"]?>-tab" href="cheque_seepdf.php?cid=<?=$cid?>&dep=<?=$dep?>&bid=<?=$obj_row_bank["bank_id"];?>" role="tab" aria-controls="v-pills-home" aria-selected="true">
								<i class="icofont-bank-alt"></i>&nbsp;
								<span class="font-weight-bold text-uppercase">
									<?=$obj_row_bank["bank_name"]?>
								</span>
							</a>

							<?php } ?>

						</div>
						
					</div>

					<div class="col-md-10">

						<input type="text" class="form-control d-none" name="depid" id="depid" value="<?=$_GET["dep"]?>">
						<input type="text" class="form-control d-none" name="compid" id="compid" value="<?=$_GET["cid"]?>">
						<input type="text" class="form-control d-none" name="bankid" id="bankid" value="<?=$bid?>">

						<div class="tab-content" id="v-pills-tabContent">
							<div class="tab-content">
								<div class="tab-pane shadow rounded bg-white p-4 active" id="<?=$obj_row_bank["bank_id"]?>-tab">
									<h3>ธนาคาร<?=$obj_row_getbank["bank_name"]?></h3>
									<div class="table-responsive" id="seepdfTable"></div>
								</div>
							</div>
						</div>
						
					</div>
				</div>

				
				
			</form>

		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function() {

			load_data(1);
			function load_data(page, query = '', dep = '', comp = '', bank = '') {
				var dep = $('#depid').val();
				var comp = $('#compid').val();
				var bank = $('#bankid').val();
				$.ajax({
					url:"fetch_cheque_seepdf.php",
					method:"POST",
					data:{page:page, query:query, dep:dep, comp:comp, bank:bank},
					success:function(data) {
						$('#seepdfTable').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var dep = $('#depid').val();
				var comp = $('#compid').val();
				var bank = $('#bankid').val();
				load_data(page, query, dep, comp, bank);
			});

			$('#search_box').keyup(function(){
				var query = $('#search_box').val();
				var dep = $('#depid').val();
				var comp = $('#compid').val();
				var bank = $('#bankid').val();
				load_data(1, query, dep, comp, bank);
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>