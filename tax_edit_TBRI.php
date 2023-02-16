<?php
session_start();
if (!$_SESSION["user_name"]) {  //check session
	Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
} else {
	include 'connect.php';
	$cid = $_GET["cid"];
	$dep = $_GET["dep"];
	$tax_id = $_GET['taxid'];
	$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '" . $_SESSION["user_id"] . "'";
	$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
	$obj_row_user = mysqli_fetch_array($obj_rs_user);
	$sql_query = "SELECT *,tp.tax_result,tp.tax_vat,tp.tax_price,c.comp_name,d.dep_id,d.dep_name,tp.tax_number,tp.tax_created_at,(tpl.list_vat * tpl.list_avg_vat_percent / 100) as vat_avg FROM taxpurchase_tb as tp 
					INNER JOIN company_tb as c ON tp.tax_comp_id = c.comp_id
					INNER JOIN department_tb as d ON tp.tax_dep_id = d.dep_id
					INNER JOIN taxpurchaselist_tb as tpl ON tp.tax_id = tpl.list_tax_id
					INNER JOIN payable_tb as p ON tpl.list_paya_id = p.paya_id
					WHERE tp.tax_id = '$tax_id'";
    
	$sql_obj = mysqli_query($obj_con,$sql_query);
	$sql_obj_row = mysqli_fetch_array($sql_obj);
	
	} 
?>
	<!DOCTYPE html>
	<html>

	<head>

		<?php include 'head.php'; ?>

		<style type="text/css">
			@media only screen and (max-width: 992px) {
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

			td{
				word-wrap: break-word !important;
			}

			body {
            font-family: 'Sarabun', sans-serif;
        }
        table {
            padding: 1px 0px;
            overflow: wrap;
            font-size: 11pt;
            border-collapse: collapse;
        }

        table.txtbody td {
            text-align: center;
            vertical-align: top;
        }
        table.txtbody td, table.txtbody th {
            border: 1px solid #000;
            padding: 3px 2px;
        }

		th{
			padding: 10px;
		}
		
		td{
			padding: 5px;
		}

        .loader {
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 1;
            width: 60px;
            height: 60px;
            margin: -76px 0 0 -76px;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            -webkit-animation: spin 2s linear infinite;
                    animation: spin 2s linear infinite;
        }

        .b{
            padding: 8px;
            border: 1px solid #000;
			text-align: center;
        }

        .tb{
            border: 1px solid #000;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

			div#show-listComp,
			div#show-listPaya,
			div#show-listCust,
			div#show-listPurchase {
				position: absolute;
				z-index: 99;
				width: 100%;
				margin-left: -15px !important;
			}

			.list-unstyled {
				position: absolute;
				z-index: 100;
				background-color: #FFFF;
				cursor: pointer;
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
				padding: .75rem !important;
			}

			.list-group-item:hover {
				background-color: #f5f5f5;
			}

			@media (min-width: 992px) {
				.salary-tax {
					display: none;
				}
			}

			@media (min-width: 468.98px) {
				.salary-tax {
					display: block;
					padding-bottom: 0px !important;
				}
			}

			@media (min-width: 572px) {
				.salary-tax {
					display: block;
					padding-bottom: 0px !important;
				}
			}

		</style>

	</head>

	<body>

		<?php include 'navbar.php'; ?>

		<section id="tax_data" class="p-3 mt-5">
			<div class="container-fluid">
				<input type="hidden" id="tax_id" value="<?= $tax_id ?>"/>
				<form method="POST" name="frmAddInvoice" id="frmAddInvoice" enctype="multipart/form-data">
					<div class="row py-4 px-1" style="background-color: #E9ECEF">
						<div class="col-md-12">
							<h3 class="mb-0">
								<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มใบภาษีซื้อ
							</h3>
						</div>
					</div>

					<div class="row py-4 px-1" style="background-color: #FFFFFF;" id="formAdd">
						<div class="col-lg-6 col-md-6 pt-1 pb-3" id="showDataComp">
							<label for="searchCompany" class="mb-1">ชื่อผู้ประกอบการ</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<i class="input-group-text">
										<i class="icofont-company"></i>
									</i>
								</div>
								<input type="text" name="searchCompany" id="searchCompany2" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?= $sql_obj_row["comp_name"]; ?>" readonly>
							</div>
						</div>

						<div class="col-lg-6 col-md-6 pt-1 pb-3" id="showDataPaya">
							<label for="searchPayable" class="mb-1">ชื่อสถานประกอบการ</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<i class="input-group-text">
										<i class="icofont-company"></i>
									</i>
								</div>
								<input type="text" name="searchCompany" id="searchCompany" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?= $sql_obj_row["comp_name"]; ?>" readonly>

							</div>
						</div>

						<div class="col-lg-3 col-md-4 col-sm-12 pt-1 pb-3">
							<label for="SelectDep" class="mb-1">ฝ่าย</label>
							<select class="custom-select form-control" id="SelectDep" name="SelectDep" disabled style="background-color: #FFF; color: #000;">
								<option value="<?= $sql_obj_row["dep_id"] ?>" selected>
									<?= $sql_obj_row["dep_name"] ?>
								</option>
							</select>
						</div>

						<div class="col-lg-3 col-md-4 col-sm-12 pt-1 pb-3">
							<label for="invdate" class="mb-1">วันที่ใบภาษีซื้อ</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<i class="input-group-text">
										<i class="icofont-ui-calendar"></i>
									</i>
								</div>
								<input type="date" class="form-control" name="date_head" id="date_head" autocomplete="off" value="<?= $sql_obj_row["tax_created_at"] ?>">
							</div>
							<span style="color: #F00;padding-top: 2px" id="altinvdate"></span>
						</div>

						<div class="col-lg-6 col-md-4 col-sm-12 pt-1 pb-3" id="showDataComp">
							<label for="searchCompany" class="mb-1">เลขประจำตัวผู้เสียภาษีอากร</label>
							<div class="input-group">
								<input type="text" name="searchCompany" id="searchTax" class="form-control" placeholder="กรอกบางส่วนของชื่อ-นามสกุล/ชื่อบริษัท" autocomplete="off" value="<?= $sql_obj_row["comp_taxno"]; ?>" readonly>
							</div>
						</div>
					</div>

					<div class="row py-4 px-1" style="background-color: #FFFFFF">
						<div class="col-md-12">
						<table class="text-center" cellspacing="0" cellpadding="0" border="0" width="100%">
							<thead style="background-color: #e9ecef;">
								<tr>
									<th rowspan="2" width="3%">ลำดับ</th>
									<th colspan="2" width="20%">ใบกำกับภาษี</th>
									<th rowspan="2" width="14%">เลขประจำตัว<br>ผู้เสียภาษี</th>
									<th rowspan="2" width="12%">ชื่อผู้ซื้อ/ผู้ให้บริการ</th>
									<th rowspan="2" width="15%">รายการ</th>
									<th rowspan="2" width="10%">มูลค่าสินค้า<br>หรือบริการ</th>
									<th rowspan="2" width="10%">จำนวนเงิน<br>ภาษีมูลค่าเพิ่ม</th>
									<th rowspan="2" width="10%">จำนวนเงิน<br>ภาษีที่เฉลี่ย</th>
									<th rowspan="2" width="3%">หมายเหตุ<br> % เฉลี่ย</th>
									<th rowspan="2" width="3%"></th>
									<th rowspan="2"></th>
								</tr>
								<tr>
									<th width="8%">วัน/เดือน/ปี</th>
									<th width="12%">เล่มที่/เลขที่</th>
								</tr>
							</thead>
							<tbody id="multi_input">
                                <?php $i = 1; $avg_vat_all = 0; foreach($sql_obj as $row){ $avg_vat_all += sprintf('%0.2f', $row['vat_avg']); ?>
                                    <tr id="<?= $row['list_id'] ?>">
                                        <td><?= $i++ ?></td>
                                        <td>
                                            <input type="date" class="form-control _input" name="date" value="<?= $row['created_at'] ?>"/>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control _input" name="tax_no" autocomplete="off" value="<?= $row['list_no'] ?>"/>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control _input tax_no" name="tax_card" autocomplete="off" value="<?= $row['paya_taxno'] ?>" readonly/>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control _input search_paya_row" name="payable" value="<?= $row['paya_name'] ?>" autocomplete="off"/>
                                            <div class="list-group"></div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control _input search_list_desc" name="list" value="<?= $row['list_desc'] ?>" autocomplete="off"/>
                                            <div class="list-group"></div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-right _input _result" name="price" value="<?= $row['list_price'] ?>"/>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-right _input _result" name="vat" value="<?= $row['list_vat'] ?>" readonly/>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-right _input _result" name="avg_vat" readonly value="<?= sprintf('%0.2f', $row['vat_avg']) ?>"/>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-right _input _result" name="avg_percent" readonly value="<?= $row['list_avg_vat_percent'] ?>"/>
                                        </td>
                                        <td>
                                            <a class="btn btn-danger del_input" onclick="del_data(<?= $row['list_id'] ?>)"><i class="icofont-ui-delete"></i></a>
                                        </td>
                                        <td>
                                            <input type="hidden" class="form-control _input paya_id" name="paya_id" value="<?= $row['paya_id'] ?>"/>
                                        </td>	
                                    </tr>
                                <?php } ?>
							</tbody>
						</table>
                        
						<table class="result_add">
							<tr>
								<td width="5%"></td>
								<td width="10%"></td>
								<td width="10%"></td>
								<td width="15%"></td>
								<td width="15%">รวมยอด</td>
								<td width="8%">
									<input type="text" class="form-control text-right" autocomplete="off" id="price_all" value="<?= $row['tax_price'] ?>" readonly>	

								</td>
								<td width="10%">
									<input type="text" class="form-control text-right" autocomplete="off" id="vat_all" value="<?= $row['tax_vat'] ?>" readonly>	

								</td>
								<td width="8%">
									
									<input type="text" class="form-control text-right" autocomplete="off" id="avg_vat_all" value="<?= sprintf('%0.2f', $avg_vat_all) ?>" readonly>	
								</td>
							</tr>
						</table>
						<a class="btn btn-primary" id="add_tax_input" onclick="addInput()"><i class="icofont-plus-circle"></i></a>
						</div>
                	</div>
					

					<div class="row py-4 px-1" style="background-color: #FFFFFF">
						<div class="col-md-12 pb-4 text-center">
							<input type="button" class="btn btn-success px-5 py-2 mx-1" id="preview_add" onclick="data_input('update')" value="preview" disabled>
						</div>
					</div>

				</form>
			</div>
		</section>
		<div id="tax_preview">

		</div>
    <script src="./js/script_taxpurchase_TBRI.js"></script>
	<script>
		$(document).ready(function(){
			let row_edit = $("#multi_input tr")
			number = row_edit.length + 1

			$("#preview_add").removeAttr("disabled")
		})
	</script>
	<?php include 'footer.php'; ?>
						
	</body>
	</html>
