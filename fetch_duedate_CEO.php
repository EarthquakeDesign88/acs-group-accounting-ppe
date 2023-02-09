<?php
	include 'connect.php';

	/*function get_total_row($connect)
	{
	  $query = "
	  SELECT * FROM tbl_webslesson_post
	  ";
	  $statement = $connect->prepare($query);
	  $statement->execute();
	  return $statement->rowCount();
	}

	$total_record = get_total_row($connect);*/

	function DateThai($strDate) {
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
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

	$str_sql = "SELECT DISTINCT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id WHERE inv_rev = (SELECT MAX(inv_rev) AS inv_rev FROM invoice_tb WHERE inv_runnumber = i.inv_runnumber) AND inv_statusMgr = 1 AND inv_apprMgrno <> '' AND inv_statusCEO = 0 AND inv_apprCEOno = '' AND inv_depid = '". $_POST['queryDep'] ."' AND Now() > inv_duedate ";

	if($_POST['query'] != '') {

		$str_sql .= ' AND inv_no LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';

	}

	$str_sql .= ' GROUP BY inv_runnumber ORDER BY inv_id DESC ';

	$filter_query = $str_sql . 'LIMIT '.$start.', '.$limit.'';

	// echo $str_sql;

	$obj_query = mysqli_query($obj_con, $str_sql);
	$total_data = mysqli_num_rows($obj_query);

	$obj_query = mysqli_query($obj_con, $filter_query);
	$obj_row = mysqli_fetch_array($obj_query);
	$total_filter_data = mysqli_num_rows($obj_query);

	$output = '<table class="table">
					<thead class="thead-light">
						<tr>
							<th width="20%">เลขที่ใบแจ้งหนี้</th>
							<th width="50%">รายละเอียด</th>
							<th width="15%" class="text-center">วันครบชำระ</th>
							<th width="15%" class="text-center">จำนวนเงิน</th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		foreach($obj_query as $obj_row) {

			if($obj_row['inv_duedate'] < date('Y-m-d')) {
				$text = 'เกินกำหนดชำระ';
				$bg = 'color: #F00; padding:10px 12px; text-align: center';
			} else {
				$text = '';
				$bg = '';
			}

			$output .= '<tr>
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
								'. DateThai($obj_row['inv_duedate']) .'<br>
								<b style="'.$bg.'">
									'.$text.'
								</b>
							</td>
							<td class="text-right">
								'. number_format($obj_row['inv_netamount'],2) .'
							</td>
						</tr>';

		}

	} else {

		$output .= '</tbody>
					<tbody>
						<tr>
							<td colspan="5" align="center">ไม่มีข้อมูลใบแจ้งหนี้ที่เกินกำหนดชำระ</td>
						</tr>
					</tbody>';

	}

	$total_links = ceil($total_data/$limit);
	$previous_link = '';
	$next_link = '';
	$page_link = '';

	$output .= '</table>
				<br />
				<div class="row" style="margin:0px 5px;">
					<div class="col-md-6" style="padding-left:0px; padding-right:0px">
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
					<div class="col-md-6" style="padding-left:0px; padding-right:0px; text-align: right">
						<b>จำนวนใบแจ้งหนี้ทั้งหมด : </b><span id="numINV">'.$total_data.'</span>
						<input class="form-control d-none" type="number" id="numINV" value="'.$total_data.'">
					</div>
				</div>';

	echo $output;

?>