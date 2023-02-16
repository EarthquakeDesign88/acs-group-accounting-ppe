<?php
	include 'connect.php';
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
	$limit = '10';
	$pageno = 1;
	if($_POST['page'] > 1) {
		$start = (($_POST['page'] - 1) * $limit);
		$pageno = $_POST['page'];
	} else {
		$start = 0;
	}
	$dep = $_POST['queryDep'];
	$str_sql = "SELECT *,Year(t.tax_created_at) + 543 as tax_year,Month(t.tax_created_at) as tax_month 
				FROM taxpurchase_tb as t
				INNER JOIN department_tb as d ON t.tax_dep_id = d.dep_id
				WHERE t.tax_dep_id = '$dep'"; 
	if($_POST['querySearch'] != '') {
		if($_POST['query'] != '') {
			$str_sql .= ' AND ' . 'tax_id' . ' LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';
		}
	}
	// $str_sql .= ' GROUP BY inv_runnumber ';
	// if($_POST['queryFil'] != '') {
	// 	if($_POST['queryFilVal'] != '') {
	// 		$str_sql .= ' ORDER BY '. $_POST["queryFil"] .' '. $_POST["queryFilVal"];
	// 	}
	// } else {
	// 	if($_POST['queryFilVal'] != '') {
	// 		$str_sql .= ' ORDER BY inv_id ' . $_POST["queryFilVal"];
	// 	} else {
	// 		$str_sql .= ' ORDER BY inv_id DESC ';
	// 	}
	// }
	$filter_query = $str_sql . ' ORDER BY t.tax_created_at DESC LIMIT '.$start.', '.$limit.'';
	
	$obj_query = mysqli_query($obj_con, $str_sql);
	$total_data = mysqli_num_rows($obj_query);
	$obj_query = mysqli_query($obj_con, $filter_query);
	$obj_row = mysqli_fetch_array($obj_query);
	$total_filter_data = mysqli_num_rows($obj_query);
	$output = '<table class="table mb-0">
					<thead class="thead-light">
						<tr>
							<th class="text-center">ลำดับที่</th>
							<th width="" class="text-center">เลขที่ใบภาษีซื้อ</th>
							<th width="" class="text-center">วันที่</th>
							<th width="20%" class="text-center">จำนวนเงิน</th>
							<th width="25%" class="text-center">แก้ไข/ดู/ลบ</th>
						</tr>
					</thead>
					<tbody>';
	if($total_data > 0) {
		$i = 1;
		$total = 0;
		foreach($obj_query as $obj_row) {
			$file = $obj_row['tax_file'];
			$dep_name = $obj_row['dep_name'];
			$y = $obj_row['tax_year'];
			$m = $obj_row['tax_month'];

			if($obj_row['tax_comp_id'] == "C009"){
				$edit = "tax_edit_TBRI.php?cid=". $obj_row['tax_comp_id'] ."&dep=". $obj_row["dep_id"] ."&taxid=". $obj_row['tax_id'];
				$export_excel = "report_taxpurchase_export_TBRI.php?tax_id=". $obj_row['tax_id'] ."&export=excel";
				$export_pdf = "report_taxpurchase_export_TBRI.php?tax_id=". $obj_row['tax_id'] ."&export=pdf";
	
			}else{
				$edit = "tax_edit.php?cid=". $obj_row['tax_comp_id'] ."&dep=". $obj_row["dep_id"] ."&taxid=". $obj_row['tax_id'];
				$export_excel = "report_taxpurchase_export.php?tax_id=". $obj_row['tax_id'] ."&export=excel";
				$export_pdf = "report_taxpurchase_export.php?tax_id=". $obj_row['tax_id'] ."&export=pdf";
	
			}

			if(strlen($m) == 1){
				$m = '0' . $m;
			}
			$date = MonthThaiShort($obj_row["tax_created_at"]);
			$result_format = number_format($obj_row['tax_result'],2);
			$output .= '<tr>
							<td>
								<div class="truncate-id text-center">
									'.$i.'
								</div>
							</td>
							<td>
								<div class="truncate-id text-center">
									'. $obj_row['tax_id'] .'
								</div>
							</td>
							<td class="text-center">
                                '. $date .'
							</td>					
							<td class="text-right">
								<input type="text" class="form-control text-right numInvNet" id="result_db" value="'.$result_format.'" readonly>
							</td>
							<td class="text-right">
								<div class="d-flex btn-group btn-group-toggle">
									<a href="'.$edit.'" class="btn btn-warning edit_data" type="button" name="edit" title="แก้ไข / Edit">
										<i class="icofont-edit"></i>
									</a>
									<a href="'.$export_pdf.'" target="_blank" class="btn btn-primary view_data" name="view" title="ดู / View">
										<i class="icofont-eye-alt"></i>
									</a>
									<button class="btn btn-danger delete_data" type="button" id="'. $obj_row['tax_id'] .'" title="ลบ / Delete">
										<i class="icofont-ui-delete"></i>
									</button>
									<a href="'.$export_excel.'" class="btn btn-success">
										Export Excel
									</a>	
								</div>
							</td>
						</tr>
						<tr class="d-none">
							<td colspan="2"></td>
							<td class="text-right">
								<input type="text" class="form-control text-right" value="'. $total .'" readonly>
							</td>
							<td></td>
							<td></td>
						</tr>';
			$i++;
		}
	} else {
		$output .= '</tbody>
					<tbody>
						<tr width="100%">
							<td colspan="6" align="center">ไม่มีข้อมูลใบภาษีซื้อ</td>
						</tr>
					</tbody>';
	}
	$total_links = ceil($total_data/$limit);
	$previous_link = '';
	$next_link = '';
	$page_link = '';
    $output .= '</tbody>';
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
						<b>จำนวนใบแจ้งหนี้ทั้งหมด : </b>
						<span id="numINV">'.$total_data.'</span>
						<input class="form-control d-none" type="number" id="numINV" value="'.$total_data.'">
					</div>
				</div>';
	echo $output;
?>