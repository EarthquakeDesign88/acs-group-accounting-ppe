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

	$str_sql = "SELECT * FROM purchasereq_tb AS pr INNER JOIN purchasereq_list_tb AS prlist ON pr.purc_no = prlist.purclist_purcno INNER JOIN payable_tb AS p ON pr.purc_payaid = p.paya_id INNER JOIN department_tb AS d ON pr.purc_depid = d.dep_id WHERE purc_compid = '". $_POST['queryComp'] ."' AND purc_depid = '". $_POST['queryDep'] ."' ";

	if($_POST['querySearch'] != '') {

		if($_POST['query'] != '') {

			$str_sql .= ' AND '. $_POST['querySearch'] .' LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';

		}

	}

	$str_sql .= ' GROUP BY purc_no ORDER BY purc_no DESC ';

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
							<th width="18%">เลขที่ขอซื้อ/ขอจ้าง</th>
							<th width="45%">รายละเอียด</th>
							<th width="15%" class="text-center">จำนวนเงิน</th>
							<th width="8%" class="text-center">ฝ่าย</th>
							<th></th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		$i = 1;
		$total = 0;

		foreach($obj_query as $obj_row) {

			$str_sql_list = "SELECT * FROM purchasereq_list_tb WHERE purclist_purcno = '". $obj_row["purc_no"] ."'";
			$obj_rs_list = mysqli_query($obj_con, $str_sql_list);
			$listDesc = "";
			while ($obj_row_list = mysqli_fetch_array($obj_rs_list)) {
				if (mysqli_num_rows($obj_rs_list) > 1) {
					$listDesc = $obj_row_list["purclist_description"] . " || " . $listDesc;
				} else {
					$listDesc = $obj_row_list["purclist_description"];
				}
			}

			$output .= '<tr>
							<td>
								'. $obj_row["purc_no"] .'
							</td>
							<td>
								<div class="truncate-address">
									<b>บริษัท : </b> '. $obj_row["paya_name"] .' <br>
									<b>รายการ : </b> '. $listDesc .'
								</div>
							</td>
							<td class="text-right">
								'. number_format($obj_row["purc_total"],2) .'
							</td>
							<td class="text-center">
								'. $obj_row["dep_name"] .'
							</td>
							<td>
								<div class="btn-group btn-group-toggle">
									<a href="purchaseReq_edit.php?cid='. $_POST['queryComp'] .'&dep='. $_POST['queryDep'] .'&purcno='. $obj_row["purc_no"] .'" class="btn btn-warning">
										<i class="icofont-edit"></i>
									</a>

									<button type="button" class="btn btn-primary view_data" name="view" id="'. $obj_row['purc_no'] .'" data-toggle="modal" data-target="#datapurchaseReq" data-backdrop="static" data-keyboard="false" title="ดู / View">
										<i class="icofont-eye-alt"></i>
									</button>

									<button type="button" class="btn btn-danger delete_data" name="delete" id="'. $obj_row["purc_no"] .'" title="ลบ / Delete">
										<i class="icofont-ui-delete"></i>
									</button>
								</div>
							</td>
						</tr>';

			$i++;

		}

	} else {

		$output .= '</tbody>
					<tbody>
						<tr width="100%">
							<td colspan="5" align="center">ไม่มีข้อมูลขอซื้อ/ขอจ้าง</td>
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
						<b>รายการขอซื้อ/ขอจ้างทั้งหมด : </b>
						<span id="numProj">'.$total_data.'</span>
						<input class="form-control d-none" type="number" id="numProj" value="'.$total_data.'">
					</div>
				</div>';

	echo $output;

?>