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

	$str_sql = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id WHERE paym_paydate = '0000-00-00' AND paym_reppid = '' AND inv_compid = '" . $_POST['queryComp'] . "' AND inv_depid = '". $_POST['queryDep'] ."' ";

	if($_POST['querySearchPaym'] != '') {

		if($_POST['query'] != '') {

			$str_sql .= ' AND '. $_POST['querySearchPaym'] .' LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';

		}

	}

	$str_sql .= " GROUP BY inv_paymid ";

	$filter_query = $str_sql . ' LIMIT '.$start.', '.$limit.'';

	// echo $filter_query;

	$obj_query = mysqli_query($obj_con, $str_sql);
	$total_data = mysqli_num_rows($obj_query);

	$obj_query = mysqli_query($obj_con, $filter_query);
	$obj_row = mysqli_fetch_array($obj_query);
	$total_filter_data = mysqli_num_rows($obj_query);



	$output = '<input type="text" class="form-control d-none" name="repidFetch" id="repidFetch" value="'. $_POST['queryRepp'] .'">';

	$str_sql_repp = "SELECT * FROM reportpay_tb WHERE rep_id = '". $_POST['queryRepp'] ."'";
	$obj_rs_repp = mysqli_query($obj_con, $str_sql_repp);
	$obj_row_repp = mysqli_fetch_array($obj_rs_repp);

	$output .= '<table class="table mb-0">
					<thead class="thead-light">
						<tr>
							<th width="8%" class="text-center"></th>
							<th width="18%">เลขที่ใบสำคัญจ่าย</th>
							<th width="42%">รายละเอียด</th>
							<th width="15%" class="text-center">จำนวนเงิน</th>
							<th width="10%" class="text-center">ฝ่าย</th>
							<th width="5%" class="text-center"></th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		$i = 1;
		$total = 0;

		foreach($obj_query as $obj_row) {

			$str_sql_netamount = "SELECT * FROM invoice_tb AS i INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id WHERE inv_depid = '". $obj_row["inv_depid"] ."' AND inv_paymid = '" . $obj_row["inv_paymid"] . "'";
			$obj_rs_netamount = mysqli_query($obj_con, $str_sql_netamount);
			$invdesc = "";
			$invnetamount = 0;
			foreach($obj_rs_netamount as $obj_row_netamount) {
				$invdesc = $obj_row["inv_description"] . " || " . $invdesc;
				$invnetamount = $invnetamount + $obj_row_netamount["inv_netamount"];
			}

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
			$paymid = $obj_row["paym_id"];

			if($obj_row['paym_statusRepid'] == 1) {

				$invSelpaym_style = 'display: none; width: 100%';
				$invNoSelpaym_style = 'width: 100%';
				$chkbg = 'background-color: #DAFFCC';

			} else {

				$invSelpaym_style = 'width: 100%';
				$invNoSelpaym_style = 'display: none; width: 100%';
				$chkbg = 'background-color: #FFFFFF';

			}



			$output .= '<tr id="tr'. $paymid .'" style="'. $chkbg .'">
							<td>

								<div class="input-group mb-0">
									<div class="input-group">
										<div id="invSelpaym'. $paymid .'" style="'.$invSelpaym_style.'">
											<button type="button" class="btn btn-success form-control my-1" title="เลือก / Select">
												<i class="icofont-checked"></i>
												<label class="container-checkbox">
													<input type="checkbox" name="chkappr[]" id="chkappr'. $paymid .'" OnClick="ClickValue(frmReportPaySelPaym, this, '. $paymid .', '. $obj_row["paym_statusRepid"] .')" value="" class="checkitem add-row">
													<span class="checkmark"></span>
												</label>
											</button>
										</div>

										<div id="invNoSelpaym'. $paymid .'" style="'.$invNoSelpaym_style.'">
											<button type="button" class="btn btn-danger form-control my-1" title="ไม่เลือก / No Select">
												<i class="icofont-close-squared"></i>
												<label class="container-checkbox">
													<input type="checkbox" name="chkappr'. $paymid .'" id="chkappr'. $paymid .'" OnClick="ClickValue(frmReportPaySelPaym, this, '. $paymid .', 1)" value="" class="checkitem delete-row">
													<span class="checkmark"></span>
												</label>
											</button>
										</div>

										<input type="text" class="form-control d-none" id="valnet'. $paymid .'" value="'. $invnetamount .'">
									</div>
								</div>
								
							</td>
							<td>
								'. $obj_row["paym_no"] .'
							</td>
							<td>
								<div class="truncate">
									<b>บริษัท : </b> '. $obj_row['paya_name'] .'<br>
									<b>รายการ : </b> '. $invdesc .'
								</div>
							</td>
							<td class="text-right">
								<input type="text" class="form-control text-right numInvNet" value="'. number_format($invnetamount,2) .'" readonly>
								<input type="text" class="form-control text-right d-none numInvNet" value="'. $invnetamount .'" readonly>
							</td>
							<td class="text-center">
								'. $obj_row['dep_name'] .'
							</td>
							<td>
								<div class="btn-group btn-group-toggle">
									<button type="button" class="btn btn-primary view_data" name="view" id="'. $obj_row['inv_paymid'] .'" data-toggle="modal" data-target="#dataPayment" data-backdrop="static" data-keyboard="false" title="ดู / View">
										<i class="icofont-eye-alt"></i>
									</button>
								</div>
							</td>
							<td class="d-none">
								<input type="text" name="paymid[]" id="paymid'. $paymid .'" value="'. $obj_row["paym_id"] .'">
							</td>
							<td class="d-none">
								<input type="text" name="paymstsrepid[]" id="paymstsrepid'. $paymid .'" value="'. $obj_row["paym_statusRepid"] .'">
							</td>
							<td class="d-none">
								<input type="text" name="paymrepid[]" id="paymrepid'. $paymid .'" value="">
							</td>
						</tr>';

		}

	} else {

		$output .= '</tbody>
					<tbody>
						<tr>
							<td colspan="7" class="text-center">ไม่มีข้อมูลใบสำคัญจ่าย</td>
						</tr>
					</tbody>';
	}

	$total_links = ceil($total_data/$limit);
	$previous_link = '';
	$next_link = '';
	$page_link = '';

	$output .= '</table>';

	$output .= '<script type="text/javascript">

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

					function ClickValue(frmReportPaySelPaym, chk, paymid, stsRepid) {

						var check = document.getElementById("paymstsrepid"+paymid).value;
						var x = document.getElementById("valnet"+paymid).value;
						var y = Number($("#numChk").val());

						if (check == 0) {

							// alert("Select : 1");

							document.getElementById("tr"+paymid).style.backgroundColor = "#DAFFCC";
							document.getElementById("paymstsrepid"+paymid).value = 1;
							document.getElementById("statrepidTB"+paymid).value = 1;

							$("#invSelpaym"+paymid).css("display","none");
							$("#invNoSelpaym"+paymid).css("display","block");

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
								data: {paymid:paymid, stsRepid:stsRepid},
								type: "post",
								url: "r_fetch_reportpay_selpayment.php",
								success: function(data){
									// alert("Data Save:" + paymid + stsRepid);
								}
							});

						} else {

							// alert("Select : 0");

							document.getElementById("tr"+paymid).style.backgroundColor = "#FFFFFF";
							document.getElementById("paymstsrepid"+paymid).value = 0;
							document.getElementById("statrepidTB"+paymid).value = 0;

							$("#invSelpaym"+paymid).css("display","block");
							$("#invNoSelpaym"+paymid).css("display","none");

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
								data: {paymid:paymid, stsRepid:stsRepid},
								type: "post",
								url: "r_fetch_reportpay_selpayment.php",
								success: function(data){
									// alert("Data Save:" + paymid + stsRepid);
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

					$str_sql_sum = "SELECT * FROM payment_tb AS paym INNER JOIN invoice_tb AS i ON paym.paym_id = i.inv_paymid WHERE paym_statusRepid = '1' AND paym_reppid = '' AND inv_depid = '". $_POST['queryDep'] ."' AND inv_compid = '". $_POST["queryComp"] ."' GROUP BY inv_paymid";
					$obj_rs_sum = mysqli_query($obj_con, $str_sql_sum);

					$sum = 0;
					$countChk = 0;
					$countRow = mysqli_num_rows($obj_rs_sum);

					while ($obj_row_sum = mysqli_fetch_array($obj_rs_sum)) {

						$str_sql_net = "SELECT * FROM invoice_tb WHERE inv_paymid = '". $obj_row_sum["paym_id"] ."'";
						$obj_rs_net = mysqli_query($obj_con, $str_sql_net);
						$net = 0;
						while ($obj_row_net = mysqli_fetch_array($obj_rs_net)) {
							$net += $obj_row_net["inv_netamount"];
						}

						$sum += $net;
						$countChk += $countChk;
					}

		$output .= '<div class="col-md-6">
						<div class="row py-2">
							<div class="col-md-7 mr-auto pt-auto pb-auto text-right mt-auto mb-auto">
								<h4 class="mb-0"><b>ยอดรวม</b></h4>
							</div>
							<div class="col-md-5 pt-auto pb-auto mb-0 px-0">
								<input type="text" class="form-control text-right" id="NetamountInvSel" value="'. number_format($sum,2) .'" readonly style="font-size: 1.25rem; height: calc(1.5em + .75rem + 2px); padding: .375rem .75rem;">
								<input type="text" class="form-control d-none text-right" id="NetamountInvSelNum" value="'. $sum .'" readonly>

								<input type="text" class="form-control text-right d-none" name="CountChkAll" id="CountChkAll" value="'. $countRow .'" readonly>
							</div>
						</div>
					</div>
				</div>';

	}

	echo $output;

?>