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

	$y = $_POST["y"];
	$m = $_POST["m"];
	$dep = $_POST["dep"];
	$cid = $_POST["comp"];

	$str_sql = "SELECT * FROM reportpay_tb1 AS repp 
				INNER JOIN payment_tb AS paym ON repp.repp_id = paym.paym_reppid 
				INNER JOIN reportpay_desc_tb1 AS reppd ON repp.repp_id = reppd.reppd_reppid 
				LEFT JOIN department_tb AS d ON paym.paym_depid = d.dep_id 
				WHERE repp_year = '".$_POST["y"]."' AND repp_month = '".$_POST["m"]."' AND paym_depid = '".$_POST["dep"]."' ";

	$str_sql .= ' GROUP BY paym_reppid ORDER BY repp_id ';

	$filter_query = $str_sql . 'LIMIT '.$start.', '.$limit.'';

	// echo $filter_query;

	$obj_query = mysqli_query($obj_con, $str_sql);
	$total_data = mysqli_num_rows($obj_query);

	$obj_query = mysqli_query($obj_con, $filter_query);
	$obj_row = mysqli_fetch_array($obj_query);
	$total_filter_data = mysqli_num_rows($obj_query);

	$output = '<table class="table mb-0">
					<thead class="thead-light">
						<tr>
							<th width="15%"></th>
							<th width="55%">รายละเอียด</th>
							<th width="10%" class="text-center">ฝ่าย</th>
							<th width="20%"></th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		$i = 1;
		$total = 0;

		foreach($obj_query as $obj_row) {

			if ($obj_row["repp_paydate"] == '0000-00-00') {
				$textPay = "รออนุมัติจ่าย";
				$styPay = 'font-size: 14px; text-align: center; background-color: #FF9800; padding: 2px 6px; border-top-left-radius: .5rem; border-bottom-right-radius: .5rem; color: #FFF;';
			} else {
				$textPay = "อนุมัติจ่ายแล้ว";
				$styPay = 'font-size: 14px; text-align: center; background-color: #008800; padding: 2px 6px; border-top-left-radius: .5rem; border-bottom-right-radius: .5rem; color: #FFF;';
			}

			$output .= '<tr>
							<td>
								<div style="'. $styPay .'">
									'. $textPay .'
								</div>
							</td>
							<td>
								'. $obj_row["repp_desc_summarize"] .'
							</td>
							<td class="text-center">
								'.$obj_row["dep_name"].'
							</td>
							<td>
								<a href="reportpay_selpayment_print_seepdf.php?cid='. $_POST["comp"] .'&dep='. $_POST["dep"] .'&reppid='. $obj_row["repp_id"] .'&reppdno='. $obj_row["reppd_no"] .'" class="btn btn-primary mb-1" style="width: 100%;" target="blank">
									<i class="icofont-eye"></i> File
								</a>';
								if ($obj_row["repp_paydate"] == NULL) {
					$output .= '<a href="paydate.php?cid='. $_POST["comp"] .'&dep='. $_POST["dep"] .'&reppid='. $obj_row["repp_id"] .'&reppdno='. $obj_row["reppd_no"] .'" class="btn btn-warning mb-1" style="width: 100%;" name="">
									<i class="icofont-pay"></i> ลงวันทำจ่าย
								</a>';
								} else {}
				$output .= '</td>
						</tr>';
			$i++;
		}


	} else {

		$output .= '</tbody>
					<tbody>
						<tr>
							<td colspan="5" align="center">ไม่มีข้อมูลใบสำคัญจ่าย</td>
						</tr>
					</tbody>
					<div class="row">';
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
					</div>
				</div>';

	echo $output;
?>