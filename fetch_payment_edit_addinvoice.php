<?php

	include 'connect.php';

	function MonthThaiShort($strDate) {
		$strYear = substr(date("Y",strtotime($strDate))+543,-2);
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai = $strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear";
	}

	$limit = '10';
	$pageno = 1;

	if($_POST['page'] > 1) {

		$start = (($_POST['page'] - 1) * $limit);
		$pageno = $_POST['page'];

	} else {

		$start = 0;

	}
	
	$str_sql = "SELECT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id INNER JOIN user_tb AS u ON i.inv_userid_create = u.user_id WHERE inv_statusMgr = 1 AND inv_apprMgrno <> '' AND inv_paymid = '' AND inv_compid = '". $_POST['queryComp'] ."' AND inv_depid = '". $_POST['queryDep'] ."' ";

	if($_POST['querySearch'] != '') {

		if($_POST['query'] != '') {

			$str_sql .= ' AND '. $_POST['querySearch'] .' LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';

		}

	}

	if($_POST['queryFil'] != '') {

		if($_POST['queryFilVal'] != '') {

			$str_sql .= ' ORDER BY '. $_POST["queryFil"] .' '. $_POST["queryFilVal"];

		}

	} else {

		if($_POST['queryFilVal'] != '') {

			$str_sql .= ' ORDER BY inv_id ' . $_POST["queryFilVal"];

		} else {

			$str_sql .= ' ORDER BY inv_id DESC ';

		}

	}

	$filter_query = $str_sql . ' LIMIT '.$start.', '.$limit.'';

	// echo $filter_query;

	$obj_query = mysqli_query($obj_con, $str_sql);
	$total_data = mysqli_num_rows($obj_query);

	$obj_query = mysqli_query($obj_con, $filter_query);
	$obj_row = mysqli_fetch_array($obj_query);
	$total_filter_data = mysqli_num_rows($obj_query);

	$output = '<table class="table mb-0">
					<thead class="thead-light">
						<tr>
							<th width="5%" class="text-center"></th>
							<th width="18%">เลขที่ใบแจ้งหนี้</th>
							<th width="32%">รายละเอียด</th>
							<th width="15%" class="text-center">วันครบชำระ</th>
							<th width="15%" class="text-center">จำนวนเงิน</th>
							<th width="8%" class="text-center">ฝ่าย</th>
							<th width="5%" class="text-center"></th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		$i = 1;
		$total = 0;

		foreach($obj_query as $obj_row) {

			$total = $total + $obj_row["inv_netamount"];

			if ($obj_row["inv_duedate"] < date('Y-m-d')) {
				$bg = 'color: #F00; text-align: center; font-weight: 700;';
				$text = 'เกินกำหนดชำระ';
			} else {
				$text = '';
				$bg = '';
			}

			$invid = $obj_row["inv_id"];
			$invstsPaym = $obj_row["inv_statusPaym"];
			

			if($obj_row['inv_statusPaym'] == 1) {

				$invSelpaym_style = 'display: none; width: 100%';
				$invNoSelpaym_style = 'width: 100%';
				$chkbg = 'background-color: #DAFFCC';

			} else {

				$invSelpaym_style = 'width: 100%';
				$invNoSelpaym_style = 'display: none; width: 100%';
				$chkbg = 'background-color: #FFFFFF';

			}

			$output .= '<tr id="tr'. $invid .'" style="'. $chkbg .'">
							<td>

								<div class="input-group mb-0">
									<div class="input-group">
										<div id="invSelpaym'. $invid .'" style="'.$invSelpaym_style.'">
											<button type="button" class="btn btn-success form-control my-1" title="เลือก / Select">
												<i class="icofont-checked"></i>
												<label class="container-checkbox">
													<input type="checkbox" name="chkappr[]" id="chkappr'. $invid .'" OnClick="ClickValue(frmPayment, this, '. $invid .', '. $obj_row['inv_statusPaym'] .')" value="" class="checkitem add-row">
													<span class="checkmark"></span>
												</label>
											</button>
										</div>

										<div id="invNoSelpaym'. $invid .'" style="'.$invNoSelpaym_style.'">
											<button type="button" class="btn btn-danger form-control my-1" title="ไม่เลือก / No Select">
												<i class="icofont-close-squared"></i>
												<label class="container-checkbox">
													<input type="checkbox" name="chkappr'. $invid .'" id="chkappr'. $invid .'" OnClick="ClickValue(frmPayment, this, '. $invid .', 1)" value="" class="checkitem delete-row">
													<span class="checkmark"></span>
												</label>
											</button>
										</div>

										<input type="text" class="form-control d-none" id="valnet'. $invid .'" value="'. $obj_row["inv_netamount"] .'">
									</div>
								</div>
								
							</td>
							<td>
								<div class="truncate-id">
									'. $obj_row['inv_no'] .'
								</div>
							</td>
							<td>
								<div class="truncate">
									<b>บริษัท : </b> '. $obj_row['paya_name'] .'<br>
									<b>รายการ : </b> '. $obj_row['inv_description'] .'
								</div>
							</td>
							<td class="text-center">
								'. MonthThaiShort($obj_row['inv_duedate']) .'
								<div style="'.$bg.'">
									'.$text.'
								</div>
							</td>
							<td class="text-right">
								<input type="text" class="form-control text-right numInvNet" value="'. number_format($obj_row['inv_netamount'],2) .'" readonly>
								<input type="text" class="form-control text-right d-none numInvNet" value="'. $obj_row['inv_netamount'] .'" readonly>
							</td>
							<td class="text-center">
								'. $obj_row['dep_name'] .'
							</td>
							<td>
								<div class="btn-group btn-group-toggle">
									<button type="button" class="btn btn-primary view_data" name="view" id="'. $obj_row['inv_id'] .'" data-toggle="modal" data-target="#dataInvoice" data-backdrop="static" data-keyboard="false" title="ดู / View">
										<i class="icofont-eye-alt"></i>
									</button>
								</div>
							</td>
							<td class="d-none">
								<input type="text" name="invstsPaym[]" id="invstsPaym'. $invid .'" value="'. $obj_row["inv_statusPaym"] .'">
							</td>
							<td class="d-none">
								<input type="text" name="invNostsPaym[]" id="invNostsPaym'. $invid .'" value="'. $obj_row["inv_NostatusPaym"] .'">
							</td>
							<td class="d-none">
								<input type="text" name="invid[]" id="invid'. $invid .'" value="'. $obj_row["inv_id"] .'">
							</td>
						</tr>
						<tr class="d-none">
							<td colspan="2"></td>
							<td class="text-right">
								<input type="text" class="form-control text-right" value="'. $total .'" readonly>
							</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>';

			$i++;
		}

	} else {

		$output .= '</tbody>
					<tbody>
						<tr>
							<td colspan="7" class="text-center">ไม่มีข้อมูลใบแจ้งหนี้</td>
						</tr>
					</tbody>';

	}

	$total_links = ceil($total_data/$limit);
	$previous_link = '';
	$next_link = '';
	$page_link = '';

	$output .= '</table>


				<script type="text/javascript">

					// $(document).ready(function() {
					// 	var i = 0;
					// 	$(".add-row").click(function() {
					// 		i++;
					// 		var name = $("#ivid"+i).val();
					// 		var markup = "TEST";
					// 		// $("table.test tbody").append(markup);
					// 		alert(markup);
					// 	});

					// 	$(".delete-row").click(function() {
					// 		$("table.test tbody").find("input[name="chkappr"]").each(function() {
					// 			if ($(this).is(":checked")) {
					// 				$(this).parents("table.test tbody tr").remove();
					// 			}
					// 		});
					// 	});
					// });

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

					function ClickValue(frmPayment, chk, invid, sts) {

						var check = document.getElementById("invstsPaym"+invid).value;
						var x = document.getElementById("valnet"+invid).value;
						var y = Number($("#numChk").val());

						if (check == 0) {

							// alert("Select : 1");

							document.getElementById("tr"+invid).style.backgroundColor = "#DAFFCC";
							document.getElementById("invstsPaym"+invid).value = 1;
							document.getElementById("stsPaym"+invid).value = 1;

							$("#invSelpaym"+invid).css("display","none");
							$("#invNoSelpaym"+invid).css("display","block");

							var sum = 0;
							var iNum = 0;

							var count = 0;
							var countChk = 0;

							$("div").filter(":gt(0)").find(":checkbox").each(function() {
								if($("div:gt(0)")) {
									if($(this).is(":checked")) {
										var iNum = Number($("#NetamountInvSelNum").val());
										sum = iNum + parseFloat(x);

										var countChk = Number($("#CountChkAll").val());
										count = countChk + 1;
									}
								}
							});

							$("#NetamountInvSel").val(formatMoney(sum));
							$("#NetamountInvSelNum").val(sum);

							$("#CountChkAll").val(count);

							$.ajax({
								data: {invid:invid, sts:sts},
								type: "post",
								url: "r_fetch_payment_edit_addinvoice.php",
								success: function(data){
									// alert("Data Save:" + invid + sts);
								}
							});

							

						} else {

							// alert("No Select : 0");
							
							document.getElementById("tr"+invid).style.backgroundColor = "#FFFFFF";
							document.getElementById("invstsPaym"+invid).value = 0;
							document.getElementById("stsPaym"+invid).value = 0;

							$("#invSelpaym"+invid).css("display","block");
							$("#invNoSelpaym"+invid).css("display","none");

							var sum = 0;
							var iNum = 0;

							var count = 0;
							var countChk = 0;

							$("div").filter(":gt(0)").find(":checkbox").each(function() {
								if($("div:gt(0)")) {
									if($(this).is(":checked")) {
										var iNum = Number($("#NetamountInvSelNum").val());
										sum = iNum - parseFloat(x);

										var countChk = Number($("#CountChkAll").val());
										count = countChk - 1;										
									}

								}
							});

							$("#NetamountInvSel").val(formatMoney(sum));
							$("#NetamountInvSelNum").val(sum);

							$("#CountChkAll").val(count);

							$.ajax({
								data: {invid:invid, sts:sts},
								type: "post",
								url: "r_fetch_payment_edit_addinvoice.php",
								success: function(data){
									// alert("Data Save:" + invid + sts);
								}
							});

						}

					}

				</script>';

	if ($total_filter_data > 0) {

	$output .= '<div class="row mx-0 my-0">
					<div class="col-md-6 px-0 py-4">
						<ul class="pagination">';

	//echo $total_links;
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
							<a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
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

					$str_sql_sum = "SELECT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id INNER JOIN user_tb AS u ON i.inv_userid_create = u.user_id WHERE inv_statusPaym = 1 AND inv_NostatusPaym = '' AND inv_compid = '". $_POST['queryComp'] ."' AND inv_depid = '". $_POST['queryDep'] . "'";
					$obj_rs_sum = mysqli_query($obj_con, $str_sql_sum);

					$sum = 0;
					$countChk = 0;
					$countsum = 1;

					while ($obj_row_sum = mysqli_fetch_array($obj_rs_sum)) {
						$sum += $obj_row_sum["inv_netamount"];
						$countChk += $countsum;
					}

		$output .= '<div class="col-md-6">
						<div class="row py-2">
							<div class="col-md-7 mr-auto pt-auto pb-auto text-right mt-auto mb-auto">
								<h4 class="mb-0"><b>ยอดรวม</b></h4>
							</div>
							<div class="col-md-5 pt-auto pb-auto mb-0 px-0">
								<input type="text" class="form-control text-right" id="NetamountInvSel" value="'. number_format($sum,2) .'" readonly style="font-size: 1.25rem; height: calc(1.5em + .75rem + 2px); padding: .375rem .75rem;">
								<input type="text" class="form-control d-none text-right" id="NetamountInvSelNum" value="'. $sum .'" readonly>

								<input type="text" class="form-control text-right d-none" name="CountChkAll" id="CountChkAll" value="'. $countChk .'" readonly>
							</div>
						</div>
					</div>
				</div>';

	}

	echo $output;

?>