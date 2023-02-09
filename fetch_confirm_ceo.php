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

	$str_sql = "SELECT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id WHERE inv_statusCEO = 1 AND inv_apprCEOno <> '' AND inv_compid = '". $_POST["queryComp"] ."' AND inv_depid = '". $_POST['queryDep'] ."' ";

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

	$output = '<table class="table table-bordered mb-0">
					<thead class="thead-light">
						<tr>
							<th width="5%" class="text-center d-none"></th>
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
				// $chkbg = 'background-color: #DAFFCC';

			} else {

				$invSelpaym_style = 'width: 100%';
				$invNoSelpaym_style = 'display: none; width: 100%';
				// $chkbg = 'background-color: #FFFFFF';
				
			}

			$output .= '<tr id="tr'. $invid .'">
							<td class="d-none">

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

		}

	} else {

		$output .= '</tbody>
					<tbody>
						<tr width="100%">
							<td colspan="6" align="center">ไม่มีข้อมูลใบแจ้งหนี้</td>
						</tr>
					</tbody>';

	}

	$total_links = ceil($total_data/$limit);
	$previous_link = '';
	$next_link = '';
	$page_link = '';

	$output .= '</table>

					<div class="row mx-0 my-0">
					<div class="col-md-6 px-0 py-4">
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
					</div>
					<div class="col-md-6 px-0 py-4 text-right">
						<b>จำนวนใบแจ้งหนี้ทั้งหมด : </b>
						<span id="numINV">'.$total_data.'</span>
						<input class="form-control d-none" type="number" id="numINV" value="'.$total_data.'">
					</div>
				</div>';

	echo $output;

?>