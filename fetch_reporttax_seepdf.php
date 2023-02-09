<?php
	
	include 'connect.php';

	$limit = '10';
	$pageno = 1;

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

    $str_sql = "SELECT d.dep_name,t.tax_number,t.tax_created_at,t.tax_id,t.tax_file,Year(t.tax_created_at) + 543 as tax_year,Month(t.tax_created_at) as tax_month 
                FROM taxpurchase_tb as t
                INNER JOIN company_tb as c ON t.tax_comp_id = c.comp_id
				INNER JOIN department_tb as d ON t.tax_dep_id = d.dep_id
                WHERE t.tax_comp_id = '$cid' AND t.tax_dep_id = '$dep'
                HAVING tax_year = '$y' AND tax_month = '$m'";

	// $str_sql = "SELECT * FROM reportpay_tb1 AS repp 
	// 			INNER JOIN payment_tb AS paym ON repp.repp_id = paym.paym_reppid 
	// 			INNER JOIN reportpay_desc_tb1 AS reppd ON repp.repp_id = reppd.reppd_reppid 
	// 			LEFT JOIN department_tb AS d ON paym.paym_depid = d.dep_id 
	// 			WHERE repp_year = '".$_POST["y"]."' AND repp_month = '".$_POST["m"]."' AND paym_depid = '".$_POST["dep"]."' ";

	// $str_sql .= ' GROUP BY paym_reppid ORDER BY repp_id ';

	$filter_query = $str_sql . ' LIMIT '.$start.', '.$limit.'';

	// echo $filter_query;

	$obj_query = mysqli_query($obj_con, $str_sql);
	$total_data = mysqli_num_rows($obj_query);

	$obj_query = mysqli_query($obj_con, $filter_query);
	$obj_row = mysqli_fetch_array($obj_query);

	$output = '<table class="table mb-0">
					<thead class="thead-light">
						<tr>
							<th width="30%">เลขที่ใบภาษีซื้อ</th>
							<th width="30%" class="text-center">เลขที่ประจำตัวผู้เสียภาษี</th>
							<th width="20%" class="text-center">วันที่</th>
							<th width="20%"></th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		$i = 1;
		$total = 0;



		foreach($obj_query as $obj_row) {
			$dep_name = $obj_row['dep_name'];
			$output .= '<tr>
                <td>'. $obj_row['tax_id'] .'</td>
                <td>'. $obj_row['tax_number'] .'</td>
                <td class="text-center">'. MonthThaiShort($obj_row['tax_created_at']) .'</td>
                <td>
								
                <a href="./receipt_taxpurchase/'.$dep_name.'/'.$y.'/'.$m.'/'.$obj_row['tax_file'].'?v='.microtime(true).'" target="blank" class="btn btn-primary view_data form-control mb-1" title="ดู / View">
                    <i class="icofont-eye-alt"></i>&nbsp;&nbsp;View
                </a>

                <a href="./receipt_taxpurchase/'.$dep_name.'/'.$y.'/'.$m.'/'.$obj_row['tax_file'].'" download class="btn btn-success form-control mb-1" target="_blank">
                    <i class="icofont-download-alt"></i>&nbsp;&nbsp;Downlaod
                </a>
            
                </td>
            </tr>';
			$i++;
		}


	} else {

		$output .= '</tbody>
					<tbody>
						<tr>
							<td colspan="5" align="center">ไม่มีข้อมูลใบภาษีซื้อ</td>
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