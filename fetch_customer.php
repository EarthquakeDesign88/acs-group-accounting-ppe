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

	$str_sql = "SELECT * FROM customer_tb "; 

	if($_POST['query'] != '') {

		$str_sql .= 'WHERE cust_name LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"  ';

	}

	$str_sql .= 'ORDER BY cust_id DESC ';

	$filter_query = $str_sql . 'LIMIT '.$start.', '.$limit.'';

	$obj_query = mysqli_query($obj_con, $str_sql);
	$total_data = mysqli_num_rows($obj_query);

	$obj_query = mysqli_query($obj_con, $filter_query);
	$obj_row = mysqli_fetch_array($obj_query);
	$total_filter_data = mysqli_num_rows($obj_query);

	$output = '<table class="table">
					<thead class="thead-light">
						<tr>
							<th width="35%">ชื่อบริษัท</th>
							<th width="55%">ที่อยู่บริษัท</th>
							<th width="10%"></th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {
		foreach($obj_query as $obj_row) {

			$output .= '<tr>
							<td>
								<div class="truncate">
									'.$obj_row["cust_name"].'
								</div>
							</td>
							<td>
								<div class="truncate-address">
									'.$obj_row["cust_address"].'
								</div>
							</td>
							<td>
								<div class="btn-group btn-group-toggle">
									<button type="button" name="edit" id="'.$obj_row["cust_id"].'" class="btn btn-warning edit_data" title="แก้ไข / Edit">
										<i class="icofont-edit"></i>
									</button>

									<button type="button" name="view" id="'.$obj_row["cust_id"].'" class="btn btn-primary view_data" title="ดู / View">
										<i class="icofont-eye-alt"></i>
									</button>

									<button type="button" name="delete" id="'.$obj_row["cust_id"].'" class="btn btn-danger delete_data" title="ลบ / Delete">
										<i class="icofont-ui-delete"></i>
									</button>
								</div>
							</td>
						</tr>';

		}

	} else {

		$output .= '</tbody>
					<tbody>
						<tr>
							<td colspan="3" align="center">ไม่มีข้อมูลชื่อ-นามสกุล / ชื่อบริษัทผู้รับบริการ</td>
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
						<b>จำนวนรายชื่อบริษัท : </b><span id="numINV">'.$total_data.'</span>
					</div>
				</div>';

	echo $output;

?>