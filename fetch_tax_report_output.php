<?php

	include 'connect.php';

	$limit = '25';
	$pageno = 1;
	$cid = $_POST['queryComp'];
	$query_dep = isset($_POST['queryDep']) ? $_POST['queryDep'] : '';
	$query_search = isset($_POST['query']) ? $_POST['query'] : '';

	if($_POST['page'] > 1) {

		$start = (($_POST['page'] - 1) * $limit);
		$pageno = $_POST['page'];

	} else {

		$start = 0;

	}

	function DateThai($strDate) {
		$strYear = substr(date("Y",strtotime($strDate))+543,-2);
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear";
	}

	$str_sql = "SELECT *,Year(r.tax_created_at) + 543 as tax_year,Month(r.tax_created_at) as tax_month FROM taxpurchase_tb AS r 
            INNER JOIN company_tb AS c ON r.tax_comp_id = c.comp_id 
            INNER JOIN department_tb AS d ON r.tax_dep_id = d.dep_id 
            WHERE r.tax_comp_id = '$cid' AND tax_created_at BETWEEN '". $_POST['df'] ."' AND '". $_POST['dt'] ."'";

	if($query_dep != ''){
		$str_sql .= " AND r.tax_dep_id = '$query_dep'";
	}

	if($query_search != '') {
		$str_sql .= ' AND ' . 'r.tax_id' . ' LIKE "%'.str_replace(' ', '%', $query_search).'%" ';
	}
	
	$filter_query = $str_sql . ' LIMIT '.$start.', '.$limit.'';
	$obj_query = mysqli_query($obj_con, $str_sql);
	$total_data = mysqli_num_rows($obj_query);

	$obj_query = mysqli_query($obj_con, $filter_query);
	$obj_row = mysqli_fetch_array($obj_query);
	$total_filter_data = mysqli_num_rows($obj_query);


	$output = '<table class="table table-bordered mb-0">
					<thead class="thead-light">
						<tr>
							<th width="5%">ลำดับที่</th>
							<th width="7%">วันที่</th>
							<th width="12%">เลขประจำตัวผู้เสียภาษี</th>
							<th width="25%">เลขที่ใบภาษีซื้อ</th>
							<th width="11%">จำนวนเงิน</th>
							<th width="10%">ภาษีมูลค่าเพิ่ม</th>
							<th width="11%">จำนวนเงินรวม</th>
							<th width="10%">ดาวน์โหลด PDF</th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		$i = 1;

		foreach($obj_query as $obj_row) {
			$dep_name = $obj_row['dep_name'];
			$y = $obj_row['tax_year'];
			$m = $obj_row['tax_month'];

			if(strlen($m) == 1){
				$m = '0' . $m;
			}
			
			$output .= '<tr>
							<td class="text-center">'. $i .'</td>
							<td class="text-center">'. DateThai($obj_row['tax_created_at']) .'</td>
							<td class="text-center">'. $obj_row['tax_number'] .'</td>
							<td class="text-center">
								'. $obj_row['tax_id'] .'
							</td>		
							<td class="text-right">
                                ' . number_format($obj_row['tax_price'],2) . '
							</td>

							<td class="text-right">
                                ' . number_format($obj_row['tax_vat'],2) . '
							</td>

							<td class="text-right">
								' . number_format($obj_row['tax_result'],2) . '
							</td>
							<td class="text-center">
								<a href="./receipt_taxpurchase/'.$dep_name.'/'.$y.'/'.$m.'/'.$obj_row['tax_file'].'" download class="btn btn-success" target="blank">
									<i class="icofont-download-alt"></i>&nbsp;&nbsp;Downlaod
								</a>
							</td>
						</tr>';

			$i++;
		}

	} else {

		$output .= '</tbody>
					<tbody>
						<tr width="100%">
							<td colspan="8" align="center">ไม่มีข้อมูลภาษีซื้อ</td>
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
					</div>
				</div>';

	echo $output;

?>