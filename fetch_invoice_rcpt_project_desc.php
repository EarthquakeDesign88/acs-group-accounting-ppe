<?php
	
	include 'connect.php';

	$limit = '10';
	$pageno = 1;

	if($_POST['page'] > 1) {

		$start = (($_POST['page'] - 1) * $limit);
		$pageno = $_POST['page'];

	} else {

		$start = 0;

	}

	// $str_sql_sub = "SELECT * FROM project_sub_tb WHERE projS_projid = 'P64020'";
	// $obj_rs_sub = mysqli_query($obj_con, $str_sql_sub);
	// while ($obj_row_sub = mysqli_fetch_array($obj_rs_sub)) {
	// 	echo $obj_row_sub["projS_id"] ."<br>";
	// }

	$str_sql = "SELECT * FROM invoice_rcpt_desc_tb AS ird INNER JOIN project_tb AS p ON ird.invrcptD_projid = p.proj_id INNER JOIN customer_tb AS c ON ird.invrcptD_custid = c.cust_id WHERE proj_compid = '". $_POST['queryComp'] ."' AND proj_depid = '". $_POST['queryDep'] ."' AND invrcptD_projid = '". $_POST['queryProj'] ."' ";

	if($_POST['query'] != '') {

		$str_sql .= ' AND ' . $_POST['querySearch'] . ' LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';

	}

	$str_sql .= ' ORDER BY invrcptD_lessonID ASC, invrcptD_projidSub ASC ';

	$filter_query = $str_sql . ' LIMIT '.$start.', '.$limit.'';

	// echo $filter_query;

	$obj_query = mysqli_query($obj_con, $str_sql);
	$total_data = mysqli_num_rows($obj_query);

	$obj_query = mysqli_query($obj_con, $filter_query);
	$obj_row = mysqli_fetch_array($obj_query);
	$total_filter_data = mysqli_num_rows($obj_query);

	$compid = $_POST["queryComp"];

	$output = '<table class="table mb-0">
					<thead class="thead-light">
						<tr>
							<th width="10%">งวด</th>
							<th width="45%">รายละเอียด</th>
							<th width="15%" class="text-center">จำนวนเงิน</th>
							<th width="15%" class="text-center">สถานะ</th>
							<th width="15%"></th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		$i = 1;
		$total = 0;

		foreach($obj_query as $obj_row) {

			if($obj_row["invrcptD_irid"] == '') {
				$text = 'display: block';
				$desctext = 'รอแจ้งหนี้';
				$invoiceNo = '';
				$sty = 'color: #FF9900; font-weight: 700;';
			} else {

				$str_sql_ir = "SELECT * FROM invoice_rcpt_tb AS ir INNER JOIN invoice_rcpt_desc_tb AS ird ON ir.invrcpt_id = ird.invrcptD_irid WHERE invrcptD_id = '". $obj_row["invrcptD_id"] ."'";
				$obj_rs_ir = mysqli_query($obj_con, $str_sql_ir);
				$obj_row_ir = mysqli_fetch_array($obj_rs_ir);
				
				// echo $str_sql_ir;
				
				if($obj_row_ir["invrcptD_irid"] == '') {
					$invoiceNo = '';
				} else {
					if($obj_row_ir["invrcpt_book"] == '') {
						$invoiceNo = '('. $obj_row_ir["invrcpt_no"] .')';
					} else {
						$invoiceNo = '('. $obj_row_ir["invrcpt_book"].'/'.$obj_row_ir["invrcpt_no"] .')';
					}
				}

				$text = 'display: none';
				$desctext = 'ออกใบแจ้งหนี้แล้ว <br> '. $invoiceNo;
				$sty = 'color: #008800;';

			}

			if($obj_row["invrcptD_irid"] == '') {
				$styEV = '';
				$styView = 'display: none';
			} else {
				$styEV = 'display: none';
				$styView = '';
			}

			// echo $obj_row["proj_id"] . "<br>";

			// if($obj_row["proj_part"] == 0) {

			// 	$str_sql_sub = "SELECT * FROM project_sub_tb AS prS INNER JOIN invoice_rcpt_desc_tb AS ird ON prS.projS_id = ird.invrcptD_projidSub WHERE projS_projid = '". $obj_row["proj_id"] ."'";
			// 	$obj_rs_sub = mysqli_query($obj_con, $str_sql_sub);
			// 	$sublesson = '';
			// 	$subid = '';
			// 	while ($obj_row_sub = mysqli_fetch_array($obj_rs_sub)) {
			// 		$subid = $obj_row_sub["projS_id"];
			// 		// echo $obj_row_sub["projS_lesson"] ."<br>";

			// 	}

			// 	// echo $subid;

			// 	$str_sql_lesson = "SELECT * FROM project_sub_tb WHERE projS_id = '". $subid ."'";
			// 	$obj_rs_lesson = mysqli_query($obj_con, $str_sql_lesson);
			// 	$obj_row_lesson = mysqli_fetch_array($obj_rs_lesson);

			// 	$lesson = $obj_row_lesson["projS_lesson"];
			// 	// $lesson = '';

			// } else {

			// 	$lesson = $obj_row["proj_lesson"];

			// }

			$selirDid = $obj_row["irDid"];

			if($obj_row['invrcptD_status'] == 1 && $obj_row["invrcptD_irid"] == '') {

				$invrcptDSelSTY = 'display: none; width: 100%';
				$invrcptDNoSelSTY = 'width: 100%';
				$chkbg = 'background-color: #DAFFCC';

			} else {

				$invrcptDSelSTY = 'width: 100%';
				$invrcptDNoSelSTY = 'display: none; width: 100%';
				$chkbg = 'background-color: #FFFFFF';

			}


			$output .= '<tr id="tr'. $selirDid .'" style="'. $chkbg .'">
							<td>
								'. $obj_row["invrcptD_lesson"] .'/'. $obj_row["proj_lesson"] .'
							</td>
							<td>
								<b>บริษัท : </b>'. $obj_row["cust_name"] .'<br>
								<b>รายการ : </b>'. $obj_row["invrcptD_description1"] .' '. $obj_row["invrcptD_description2"] .'
							</td>
							<td class="text-right">
								'. number_format($obj_row["invrcptD_subtotal"],2) .'
							</td>
							<td class="text-center" style="'. $sty .'">
								'. $desctext .'
							</td>
							<td>
								<div class="input-group mb-0" style="'.$text.'">
									<div class="input-group">
										<div id="invrcptSel'. $selirDid .'" style="'.$invrcptDSelSTY.'">
											<button type="button" class="btn btn-primary form-control my-1" title="เลือก / Select">
												<i class="icofont-plus-circle"></i>&nbsp;ออกใบแจ้งหนี้
												<label class="container-checkbox">
													<input type="checkbox" name="chkappr[]" id="chkappr'. $selirDid .'" OnClick="ClickValue(frmProject, this, '. $selirDid .', '. $obj_row['invrcptD_status'] .')" class="checkitem add-row">
													<span class="checkmark"></span>
												</label>
											</button>
										</div>

										<div id="invrcptNoSel'. $selirDid .'" style="'.$invrcptDNoSelSTY.'">
											<button type="button" class="btn btn-danger form-control my-1" title="ไม่เลือก / No Select">
												<i class="icofont-close-squared"></i>&nbsp;ออกใบแจ้งหนี้
												<label class="container-checkbox">
													<input type="checkbox" name="chkappr'. $selirDid .'" id="chkappr'. $selirDid .'" OnClick="ClickValue(frmProject, this, '. $selirDid .', 1)" class="checkitem delete-row">
													<span class="checkmark"></span>
												</label>
											</button>
										</div>

										<input type="text" class="form-control d-none" id="valnet'. $selirDid .'" value="'. $obj_row["invrcptD_grandtotal"] .'">
									</div>
								</div>

								<a href="invoice_rcpt_project_desc_copy.php?cid='. $_POST['queryComp'] .'&dep='.  $_POST['queryDep'] .'&projid='.  $_POST['queryProj'] .'&irDid='. $obj_row["invrcptD_id"] .'" class="btn btn-success form-control mb-1" style="'.$text.'">
									<i class="icofont-copy"></i>&nbsp;คัดลอก
								</a>

								<div class="btn-group btn-group-toggle mb-1" style="width: 100%; '.$styEV.'">
									<a href="invoice_rcpt_project_desc_edit.php?cid='. $_POST['queryComp'] .'&dep='. $_POST['queryDep'] .'&projid='.  $_POST['queryProj'] .'&irDid='. $obj_row["invrcptD_id"] .'" class="btn btn-warning edit_data" type="button" name="edit" title="แก้ไข / Edit">
										<i class="icofont-edit"></i>
									</a>

									<button type="button" class="btn btn-primary view_data" name="view" id="'. $obj_row["invrcptD_id"] .'" data-toggle="modal" data-target="#dataInvoice" data-backdrop="static" data-keyboard="false" title="ดู / View">
										<i class="icofont-eye-alt"></i>
									</button>

									<button class="btn btn-danger delete_data" type="button" name="delete" id="'. $obj_row['invrcptD_id'] .'" title="ลบ / Delete">
										<i class="icofont-ui-delete"></i>
									</button>
								</div>

								<div class="btn-group btn-group-toggle mb-1" style="width: 100%; '.$styView.'">';
									if($obj_row["invrcptD_irid"] == '') {
										$output .='<a href="invoice_rcpt_project_desc_edit.php?cid='. $_POST['queryComp'] .'&dep='. $_POST['queryDep'] .'&projid='.  $_POST['queryProj'] .'&irDid='. $obj_row["invrcptD_id"] .'" class="btn btn-warning edit_data" type="button" name="edit" title="แก้ไข / Edit">
														<i class="icofont-edit"></i>
												   </a>';
									}

									$output .='<button type="button" class="btn btn-primary view_data" name="view" id="'. $obj_row["invrcptD_id"] .'" data-toggle="modal" data-target="#dataInvoice" data-backdrop="static" data-keyboard="false" title="ดู / View">
										<i class="icofont-eye-alt"></i>
									</button>
								</div>								
							</td>
							<td class="d-none">
								<input type="text" class="form-control" name="invrcptstatus[]" id="invrcptstatus'. $selirDid .'" value="'. $obj_row["invrcptD_status"] .'">
							</td>
						</tr>';

			$i++;

		}

								// <a href="invoice_rcpt_refproject_add.php?cid='. $_POST['queryComp'] .'&dep='.  $_POST['queryDep'] .'&projid='.  $_POST['queryProj'] .'&irDid='. $obj_row["invrcptD_id"] .'" class="btn btn-primary form-control mb-1" style="'.$text.'">
								// 	<i class="icofont-plus-circle"></i>&nbsp;ออกใบแจ้งหนี้
								// </a>

								

								

		$str_sql_sumivDesc = "SELECT * FROM invoice_rcpt_desc_tb WHERE invrcptD_status = 0 AND invrcptD_irid = '' AND invrcptD_projid = '". $_POST['queryProj'] ."'";
		$obj_rs_sumivDesc = mysqli_query($obj_con, $str_sql_sumivDesc);
		$waitINV = 0;
		while ($obj_row_sumivDesc = mysqli_fetch_array($obj_rs_sumivDesc)) {
			$waitINV = $obj_row_sumivDesc["invrcptD_subtotal"] + $waitINV;
		}

		$str_sql_sumINVSub = "SELECT * FROM invoice_rcpt_desc_tb AS ird INNER JOIN invoice_rcpt_tb AS ir ON ird.invrcptD_irid = ir.invrcpt_id WHERE invrcptD_status = 1 AND invrcptD_irid <> '' AND invrcptD_projid = '". $_POST['queryProj'] ."'";
		$obj_rs_sumINVSub = mysqli_query($obj_con, $str_sql_sumINVSub);
		$sumINVSub = 0;
		while ($obj_row_sumINVSub = mysqli_fetch_array($obj_rs_sumINVSub)) {
			$sumINVSub = $obj_row_sumINVSub["invrcptD_subtotal"] + $sumINVSub;
		}

		$str_sql_sumre = "SELECT * FROM invoice_rcpt_tb AS ir INNER JOIN receipt_tb AS r ON ir.invrcpt_reid = r.re_id WHERE invrcpt_projid = '". $_POST['queryProj'] ."'";
		$obj_rs_sumre = mysqli_query($obj_con, $str_sql_sumre);
		$obj_row_sumre = mysqli_fetch_array($obj_rs_sumre);
		$sumSubRe = 0;
		$sumSubRe = $obj_row_sumre["invrcpt_subtotal"] - $obj_row_sumre["invrcpt_balancetotal"];

		

			$output .= '<tr>
							<td colspan="2" style="font-size: 18px; text-align: right;">
								<b>มูลค่าสัญญาทั้งหมด : </b>
							</td>
							<td style="font-size: 18px; text-align: right;">'. number_format($obj_row["proj_amount"],2) .'</td>
							<td><b>บาท</b></td>
						</tr>

						<tr>
							<td colspan="2" style="font-size: 18px; text-align: right; color: #FF9900;">
								<b>จำนวนเงินรอแจ้งหนี้ : </b>
							</td>
							<td style="font-size: 18px; text-align: right; color: #FF9900;">'. number_format($waitINV,2) .'</td>
							<td style="color: #FF9900;"><b>บาท</b></td>
						</tr>

						<tr>
							<td colspan="2" style="font-size: 18px; text-align: right; color: #F00;">
								<b>จำนวนเงินแจ้งหนี้แล้ว : </b>
							</td>
							<td style="font-size: 18px; text-align: right; color: #F00;">'. number_format($sumINVSub,2) .'</td>
							<td style="color: #F00;"><b>บาท</b></td>
						</tr>

						<tr>
							<td colspan="2" style="font-size: 18px; text-align: right; color: #008800;">
								<b>เก็บเงินแล้ว : </b>
							</td>
							<td style="font-size: 18px; text-align: right; color: #008800;">'. number_format($sumSubRe,2) .'</td>
							<td style="color: #008800;"><b>บาท</b></td>
						</tr>';

	} else {
				
		$output .= '</tbody>
					<tbody>
						<tr width="100%">
							<td colspan="4" align="center">ไม่มีข้อมูลโครงการ</td>
						</tr>
					</tbody>';

	}

	$total_links = ceil($total_data/$limit);
	$previous_link = '';
	$next_link = '';
	$page_link = '';

	$output .= '</table>




				<script type="text/javascript">

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
							return negativeSign + (j ? i.substr(0, j) + thousands : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");

						} catch (e) {
							console.log(e)
						}
					};

					function ClickValue(frmProject, chk, selirDid, sts) {						

						var check = document.getElementById("invrcptstatus"+selirDid).value;
						var x = document.getElementById("valnet"+selirDid).value;

						if (check == 0) {

							// alert("Select : 1");

							document.getElementById("tr"+selirDid).style.backgroundColor = "#DAFFCC";
							document.getElementById("invrcptstatus"+selirDid).value = 1;
							document.getElementById("invrcptDDB"+selirDid).value = 1;

							$("#invrcptSel"+selirDid).css("display","none");
							$("#invrcptNoSel"+selirDid).css("display","block");

							var count = 0;
							var countChk = 0;

							$("div").filter(":gt(0)").find(":checkbox").each(function() {
								if($("div:gt(0)")) {
									if($(this).is(":checked")) {
										var countChk = Number($("#CountChkAll").val());
										count = countChk + 1;
									}
								}
							});

							$("#CountChkAll").val(count);

							$.ajax({
								data: {selirDid:selirDid, sts:sts},
								type: "post",
								url: "r_fetch_invoice_rcpt_project_desc.php",
								success: function(data){
									// alert("Data Save:" + selirDid + sts);
								}
							});
							

						} else {

							// alert("No Select : 0");
							
							document.getElementById("tr"+selirDid).style.backgroundColor = "#FFFFFF";
							document.getElementById("invrcptstatus"+selirDid).value = 0;
							document.getElementById("invrcptDDB"+selirDid).value = 0;

							$("#invrcptSel"+selirDid).css("display","block");
							$("#invrcptNoSel"+selirDid).css("display","none");

							var count = 0;
							var countChk = 0;

							$("div").filter(":gt(0)").find(":checkbox").each(function() {
								if($("div:gt(0)")) {
									if($(this).is(":checked")) {
										var countChk = Number($("#CountChkAll").val());
										count = countChk - 1;
									}
								}
							});

							$("#CountChkAll").val(count);

							$.ajax({
								data: {selirDid:selirDid, sts:sts},
								type: "post",
								url: "r_fetch_invoice_rcpt_project_desc.php",
								success: function(data){
									// alert("Data Save:" + selirDid + sts);
								}
							});

						}

					}

				</script>





				<div class="row mx-0 my-0">
					<div class="col-md-6 px-0 pt-4 pb-2">
						<ul class="pagination">';

	$page_array = array();

	if($total_links > 4) {

		if($pageno < 5) {

			for($count = 1; $count <= 5; $count++) {

				$page_array[] = $count;

			}

			$page_array[] = '...';
			$page_array[] = $total_links;

		} else {

			$end_limit = $total_links - 5;

			if($pageno > $end_limit) {

				$page_array[] = 1;
				$page_array[] = '...';

				for($count = $end_limit; $count <= $total_links; $count++) {

					$page_array[] = $count;

				}

			} else {

				$page_array[] = 1;
				$page_array[] = '...';

				for($count = $pageno - 1; $count <= $pageno + 1; $count++) {

					$page_array[] = $count;

				}

				$page_array[] = '...';
				$page_array[] = $total_links;

			}

		}

	} else {

		for($count = 1; $count <= $total_links; $count++) {

			$page_array[] = $count;

		}

	}


	for($count = 0; $count < count($page_array); $count++) {

		if($pageno == $page_array[$count]) {

			$page_link .= '<li class="page-item active">
							<a class="page-link" href="#"> '.$page_array[$count].' <span class="sr-only">(current)</span></a>
						</li> ';

			$previous_id = $page_array[$count] - 1;

			if($previous_id > 0) {

				$previous_link = '<li class="page-item">
									<a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">
										<i class="icofont-rounded-left"></i>
									</a>
								</li>';

			} else {

				$previous_link = '<li class="page-item disabled">
									<a class="page-link" href="#">
										<i class="icofont-rounded-left"></i>
									</a>
								</li>';

			}

			$next_id = $page_array[$count] + 1;

			if($next_id > $total_links) {

				$next_link = '<li class="page-item disabled">
								<a class="page-link" href="#">
									<i class="icofont-rounded-right"></i>
								</a>
							</li>';

			} else {

				$next_link = '<li class="page-item">
								<a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">
									<i class="icofont-rounded-right"></i>
								</a>
							</li>';

			}

		} else {

			if($page_array[$count] == '...') {

				$page_link .= '<li class="page-item disabled">
								<a class="page-link" href="#">...</a>
							</li> ';

			} else {

				$page_link .= '<li class="page-item">
								<a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
							</li>';

			}

		}

	}

	$output .= $previous_link . $page_link . $next_link;
			$output .= '</ul>
					</div>';

					$str_sql_sum = "SELECT * FROM invoice_rcpt_desc_tb AS ird INNER JOIN project_tb AS p ON ird.invrcptD_projid = p.proj_id INNER JOIN customer_tb AS c ON ird.invrcptD_custid = c.cust_id WHERE proj_compid = '". $_POST['queryComp'] ."' AND proj_depid = '". $_POST['queryDep'] ."' AND invrcptD_projid = '". $_POST['queryProj'] ."' AND invrcptD_status = 1 AND invrcptD_irid = ''";
					$obj_rs_sum = mysqli_query($obj_con, $str_sql_sum);

					$sum = 0;
					$countChk = 0;
					$countRow = mysqli_num_rows($obj_rs_sum);

					while ($obj_row_sum = mysqli_fetch_array($obj_rs_sum)) {
						$sum += $obj_row_sum["invrcptD_grandtotal"];
						$countChk += $countChk;
					}


		$output .= '<div class="col-md-6 px-0 pt-4 pb-2 text-right">
						<b>รายละเอียดทั้งหมด : </b>
						<span id="numProj">'.$total_data.'</span>
						<input class="form-control d-none" type="number" id="numProj" value="'.$total_data.'">

						<input type="text" class="form-control text-right d-none" name="CountChkAll" id="CountChkAll" value="'. $countRow .'" readonly>
					</div>
				</div>';

	echo $output;

?>