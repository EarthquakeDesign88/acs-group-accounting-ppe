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

	function DateShortThai($strDate) {
		$strYear = substr(date("Y",strtotime($strDate))+543,-2);
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","01","02","03","04","05","06","07","08","09","10","11","12");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay/$strMonthThai/$strYear";
	}

	$str_sql = "SELECT * FROM receipt_tb AS r INNER JOIN company_tb AS c ON r.re_compid = c.comp_id INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id INNER JOIN department_tb AS d ON r.re_depid = d.dep_id INNER JOIN invoice_rcpt_tb AS i ON r.re_id = i.invrcpt_reid WHERE re_compid = '". $_POST['queryComp'] ."' AND re_month = '". $_POST['queryMonth'] ."' ";

	$str_sql .= " ORDER BY re_date ASC ";

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
							<th width="12%">วันที่</th>
							<th>รายการ</th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		$i = 1;
		$total = 0;

		foreach($obj_query as $obj_row) {

			$output .= '<tr>
							<td class="text-center">
								'. DateShortThai($obj_row['re_date']) .'
							</td>
							<td>
								<table width="100%">
									<tr>
										<td width="80%" style="border: none; padding: 0px;">
											
										</td>
										<td width="20%" style="border: none; padding: 0px; text-align: right;">
											<b>'. number_format($obj_row["re_grandtotal"],2) .'</b>
										</td>
									</tr>
								</table>
							</td>
						</tr>';

			$i++;
		}

	} else {

		$output .= '</tbody>
					<tbody>
						<tr width="100%">
							<td colspan="2" align="center">ไม่มีรายการรายรับเดือนนี้</td>
						</tr>
					</tbody>';

	}

		$str_sql_sum = "SELECT * FROM receipt_tb AS r INNER JOIN company_tb AS c ON r.re_compid = c.comp_id INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id INNER JOIN department_tb AS d ON r.re_depid = d.dep_id INNER JOIN invoice_rcpt_tb AS i ON r.re_id = i.invrcpt_reid WHERE re_compid = '". $_POST['queryComp'] ."' AND re_month = '". $_POST['queryMonth'] ."' ";
		$obj_rs_sum = mysqli_query($obj_con, $str_sql_sum);
		$Sumsub = 0;
		$Sumvat = 0;
		$Sumgrand = 0;
		while($obj_row_sum = mysqli_fetch_array($obj_rs_sum)) {
			$Sumsub = $obj_row_sum["re_subtotal"] + $Sumsub;
			$Sumvat = $obj_row_sum["re_vat"] + $obj_row_sum["re_differencevat"] + $Sumvat;
			$Sumgrand = $obj_row_sum["re_grandtotal"] + $obj_row_sum["re_differencevat"] + $obj_row_sum["re_differencegrandtotal"] + $Sumgrand;
		}

		$output .= '<tfoot>
						<tr>
							<td></td>
							<td style="color: #008800;">
								<table width="100%">
									<tr>
										<td width="80%" style="border: none; padding: 0px;">
											<b>ยอดรวมรายรับ : <b>
										</td>
										<td width="20%" style="border: none; padding: 0px; text-align: right;">
											<b>'. number_format($Sumgrand,2) .'</b>
										</td>
									</tr>
								</table>
							</td>
						<tr>
					</tfoot>';


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
						<b>จำนวนรายการรายรับ : </b>
						<span id="numINV">'.$total_data.'</span>
						<input class="form-control d-none" type="number" id="numINV" value="'.$total_data.'">
						<b>รายการ</b>
					</div>
				</div>';

	echo $output;

?>