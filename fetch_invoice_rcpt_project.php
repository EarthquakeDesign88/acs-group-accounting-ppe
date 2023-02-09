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

	$str_sql = "SELECT * FROM project_tb WHERE proj_compid = '". $_POST['queryComp'] ."' AND proj_depid = '". $_POST['queryDep'] ."'";

	if($_POST['query'] != '') {

		$str_sql .= ' AND proj_name LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';

	}

	$str_sql .= ' ORDER BY proj_id DESC ';

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
							<th width="10%">รหัสโครงการ</th>
							<th width="70%">ชื่อโครงการ</th>
							<th width="20%"></th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		$i = 1;
		$total = 0;

		foreach($obj_query as $obj_row) {

			$output .= '<tr>
							<td>
								'. $obj_row["proj_id"] .'
							</td>
							<td>
								'. $obj_row["proj_name"] .'
							</td>
							<td>
								<a href="invoice_rcpt_project_desc.php?cid='. $compid .'&dep='. $_POST['queryDep'] .'&projid='. $obj_row["proj_id"] .'" type="button" class="btn btn-success form-control mb-1">
									รายละเอียดจำนวนงวด
								</a>

								<div class="btn-group btn-group-toggle" style="width: 100%">
									<a href="project_edit.php?cid='. $compid .'&dep='. $_POST['queryDep'] .'&projid='. $obj_row["proj_id"] .'" class="btn btn-warning">
										&nbsp;&nbsp;<i class="icofont-edit"></i>&nbsp;&nbsp;
									</a>

									<button type="button" class="btn btn-primary view_data" name="view" id="'. $obj_row["proj_id"] .'" data-toggle="modal" data-target="#dataProject" data-backdrop="static" data-keyboard="false" title="ดู / View">
										&nbsp;&nbsp;<i class="icofont-eye-alt"></i>&nbsp;&nbsp;
									</button>
								</div>
							</td>
						</tr>';

			$i++;

		}

									// <button type="button" class="btn btn-danger delete_data" name="delete" id="'. $obj_row["proj_id"] .'" title="ลบ / Delete">
									// 	&nbsp;&nbsp;<i class="icofont-ui-delete"></i>&nbsp;&nbsp;
									// </button>

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
						<b>รายชื่อโครงการทั้งหมด : </b>
						<span id="numProj">'.$total_data.'</span>
						<input class="form-control d-none" type="number" id="numProj" value="'.$total_data.'">
					</div>
				</div>';

	echo $output;

?>