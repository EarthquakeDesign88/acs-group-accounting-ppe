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

	$str_sql = "SELECT * FROM reportpay_tb1 AS repp INNER JOIN department_tb AS d ON repp.repp_depid = d.dep_id WHERE repp_paydate = '0000-00-00' ";

	// $str_sql = "SELECT * FROM invoice_tb AS i 
	// 			INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id 
	// 			INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
	// 			INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
	// 			WHERE paym_paydate = '0000-00-00' AND paym_reppid = '' AND inv_compid = '" . $_POST['queryComp'] . "' AND inv_depid = '". $_POST['queryDep'] ."' ";

	// if($_POST['querySearchPaym'] != '') {

		if($_POST['query'] != '') {

			$str_sql .= ' AND repp_desc_summarize LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';

		}

	// }

	$str_sql .= " ORDER BY repp_id ";

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
							<th width="42%">วันที่สรุปยอดทำรายการจ่าย</th>
							<th width="10%" class="text-center">ฝ่าย</th>
							<th width="18%" class="text-center"></th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		$i = 1;
		$total = 0;

		foreach($obj_query as $obj_row) {

			$output .= '<tr>
							<td>
								&nbsp;&nbsp;&nbsp;&nbsp;
								'. $obj_row['repp_desc_summarize'] .'
							</td>
							<td class="text-center">
								'. $obj_row['dep_name'] .'
							</td>
							<td class="text-center">
								<div class="btn-group btn-group-toggle">
									<a href="reportpay_edit.php?cid='. $_POST['queryComp'] .'&dep='. $obj_row['dep_id'] .'&reppid='. $obj_row['repp_id'] .'" class="btn btn-warning" type="button">
										<i class="icofont-edit"></i> แก้ไข
									</a>

									<button type="button" class="btn btn-primary view_data" name="view" id="'. $obj_row['repp_id'] .'" data-toggle="modal" data-target="#dataPayment" data-backdrop="static" data-keyboard="false" title="ดู / View">
										<i class="icofont-eye-alt"></i> View
									</button>

									<a href="reportpay_paydate.php?cid='. $_POST['queryComp'] .'&dep='. $obj_row['dep_id'] .'&reppid='. $obj_row['repp_id'] .'" class="btn btn-success" type="button">
										<i class="icofont-pay"></i> ตัดจ่าย
									</a>
								</div>
							</td>
						</tr>';

		}

	} else {

		$output .= '</tbody>
					<tbody>
						<tr>
							<td colspan="3" class="text-center">ไม่มีข้อมูลสรุปรายการทำจ่าย</td>
						</tr>
					</tbody>';
	}

	$total_links = ceil($total_data/$limit);
	$previous_link = '';
	$next_link = '';
	$page_link = '';

	$output .= '</table>';

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
					</div>
					<div class="col-md-6 px-0 py-4 text-right">
						<b>สรุปรายการทำจ่ายทั้งหมด : </b>
						<span id="numRepp">'.$total_data.'</span>
						<input class="form-control d-none" type="number" id="numRepp" value="'.$total_data.'">
					</div>
				</div>';

	}

	echo $output;

?>