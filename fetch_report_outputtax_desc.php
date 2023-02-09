<?php

	include 'connect.php';

	$limit = '25';
	$pageno = 1;

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

	if ($_POST['queryRefINV'] == 0) { 

		$str_sql = "SELECT * FROM receipt_tb AS r 
					INNER JOIN company_tb AS c ON r.re_compid = c.comp_id 
					INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
					INNER JOIN department_tb AS d ON r.re_depid = d.dep_id 
					INNER JOIN invoice_rcpt_tb AS i ON r.re_id = i.invrcpt_reid 
					WHERE re_vatpercent <> 0 AND re_compid = '". $_POST['queryComp'] ."' AND re_date BETWEEN '". $_POST['df'] ."' AND '". $_POST['dt'] ."' ";

	} else { 

		$str_sql = "SELECT * FROM receipt_tb AS r 
					INNER JOIN company_tb AS c ON r.re_compid = c.comp_id 
					INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
					INNER JOIN department_tb AS d ON r.re_depid = d.dep_id 
					WHERE re_vatpercent <> 0 AND re_compid = '". $_POST['queryComp'] ."' AND re_date BETWEEN '". $_POST['df'] ."' AND '". $_POST['dt'] ."' ";

	}

	$str_sql .= " ORDER BY re_bookno ASC, re_no ASC ";

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
							<th width="5%">ลำดับที่</th>
							<th width="7%">วันที่</th>
							<th width="9%">เล่มที่/เลขที่</th>
							<th width="12%">เลขประจำตัวผู้เสียภาษี</th>
							<th width="35%">ชื่อผู้ซื้อ/ผู้รับบริการ/รายการ</th>
							<th width="11%">จำนวนเงิน</th>
							<th width="10%">ภาษีมูลค่าเพิ่ม</th>
							<th width="11%">จำนวนเงินรวม</th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		$i = 1;
		$total = 0;

		foreach($obj_query as $obj_row) {
			
			if ($obj_row["re_bookno"] == '') {
				$receiptno = $obj_row["re_no"];
			} else {
				$receiptno = $obj_row["re_bookno"] . "/" . $obj_row["re_no"];
			}

			$revat = $obj_row["re_vat"] + $obj_row["re_differencevat"];
			$regrand = $obj_row["re_subtotal"] + $obj_row["re_vat"] + $obj_row["re_differencevat"] + $obj_row["re_differencegrandtotal"];

			$output .= '<tr>
							<td class="text-center">'. $i .'.</td>
							<td class="text-center">'. DateThai($obj_row['re_date']) .'</td>
							<td class="text-center">
								'. $receiptno .'
							</td>
							<td class="text-center">'. $obj_row['cust_taxno'] .'</td>
							<td>
								<div class="truncate">
									'. $obj_row['cust_name'] .' / '. $obj_row["re_outputtax"] .'
								</div>
							</td>		
							<td class="text-right">
								'. number_format($obj_row['re_subtotal'],2) .'
								<input type="text" class="form-control text-right d-none numInvNet" value="'. $total .'" readonly>
							</td>
							<td class="text-right">
								'. number_format($revat,2) .'
								<input type="text" class="form-control text-right d-none numInvNet" value="'. $total .'" readonly>
							</td>
							<td class="text-right">
								'. number_format($regrand,2) .'
								<input type="text" class="form-control text-right d-none numInvNet" value="'. $total .'" readonly>
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
						</tr>';

			$i++;
		}

	} else {

		$output .= '</tbody>
					<tbody>
						<tr width="100%">
							<td colspan="8" align="center">ไม่มีข้อมูลภาษีขาย</td>
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