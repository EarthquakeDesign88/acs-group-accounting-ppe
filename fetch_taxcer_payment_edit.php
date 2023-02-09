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
	// if ($_POST['queryDep'] == 'D003' || $_POST['queryDep'] == 'D004' || $_POST['queryDep'] == 'D016') {
	// 	$str_sql = "SELECT DISTINCT * FROM invoice_tb AS i 
	// 				INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
	// 				INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id 
	// 				INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
	// 				INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id 
	// 				LEFT JOIN taxcertificate_tb AS taxc ON paym.paym_taxcid = taxc.taxc_id 
	// 				WHERE inv_rev = (SELECT MAX(inv_rev) AS inv_rev FROM invoice_tb WHERE inv_runnumber = i.inv_runnumber) AND inv_statusMgr = 0 AND inv_apprMgrno = '' AND inv_statusCEO = 1 AND inv_statusCEO <> '' AND paym_NostatusTaxcer <> '' AND inv_depid = '". $_POST["queryDep"] ."' ";
	// } else {
		
		$str_sql = "SELECT DISTINCT * FROM invoice_tb AS i 
					INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
					INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id 
					INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
					INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id 
					LEFT JOIN taxcertificate_tb AS taxc ON paym.paym_taxcid = taxc.taxc_id 
					WHERE inv_rev = (SELECT MAX(inv_rev) AS inv_rev FROM invoice_tb WHERE inv_runnumber = i.inv_runnumber) AND inv_statusMgr = 1 AND paym_statusTaxcer = 1 AND paym_NostatusTaxcer <> '' AND inv_depid = '". $_POST["queryDep"] ."' ";
	// }
	if($_POST['querySearchTaxc'] != '') {
		if($_POST['query'] != '') {
			$str_sql .= ' AND '. $_POST['querySearchTaxc'] .' LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';
		}
	}
	$str_sql .= ' GROUP BY taxc_no ORDER BY taxc_no DESC ';
	$filter_query = $str_sql . 'LIMIT '.$start.', '.$limit.'';
	// echo $str_sql;
	$obj_query = mysqli_query($obj_con, $str_sql);
	$total_data = mysqli_num_rows($obj_query);
	$obj_query = mysqli_query($obj_con, $filter_query);
	$obj_row = mysqli_fetch_array($obj_query);
	$total_filter_data = mysqli_num_rows($obj_query);
	// echo $str_sql;
	$output = '<table class="table">
					<thead class="thead-light">
						<tr>
							<th width="18%">เลขที่ใบสำคัญจ่าย</th>
							<th width="50%">รายละเอียด</th>
							<th width="15%" class="text-right">จำนวนเงิน</th>
							<th width="5%" class="text-center">ฝ่าย</th>
							<th width="12%"></th>
						</tr>
					</thead>
					<tbody>';
	if($total_data > 0) {
		$i = 1;
		foreach($obj_query as $obj_row) {
			$str_sql_iv = "SELECT * FROM invoice_tb WHERE inv_paymid = '". $obj_row["paym_id"] ."'";
			$obj_rs_iv = mysqli_query($obj_con, $str_sql_iv);
			$invdesc = "";
			$invnetamount = 0;
			while ($obj_row_iv = mysqli_fetch_array($obj_rs_iv)) {
				if (mysqli_num_rows($obj_rs_iv) > 1) {
					$invdesc = $obj_row_iv["inv_description"] . " || " . $invdesc;
				} else {
					$invdesc = $obj_row_iv["inv_description"];
				}
				$invnetamount += $obj_row_iv["inv_netamount"];
			}
			$output .= '<tr id="tr'. $i .'">
							<td>
								<div class="truncate-id text-nowrap">
									'. $obj_row["taxc_no"] .'
								</div>
							</td>
							<td>
								<div class="truncate">
									<b>บริษัท : </b>'. $obj_row["paya_name"] .'<br>
									<b>รายการ : </b>'. $invdesc .'
								</div>
							</td>
							<td class="text-right">
								'. number_format($invnetamount,2) .'
							</td>
							<td class="text-center text-nowrap">
								'. $obj_row["dep_name"] .'
							</td>
							<td class="text-center">
								<div class="btn-group btn-group-toggle"  style="width: 100%">
									<a href="taxcer_payment_edit.php?cid='. $obj_row['comp_id'] .'&dep='. $obj_row["dep_id"] .'&paymid='. $obj_row["paym_id"] .'&taxcid='. $obj_row["taxc_id"] .'&taxcT=1" class="btn btn-warning" type="button" name="edit" title="แก้ไข / Edit">
										<i class="icofont-edit"></i>
									</a>
								</div>
								
							</td>
						</tr>';
			$i++;
		}
	} else {
		$output .= '</tbody>
					<tbody>
						<tr>
							<td colspan="5" align="center">ไม่มีข้อมูลหักภาษี ณ ที่จ่าย</td>
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
						<b>จำนวนใบสำคัญจ่ายทั้งหมด : </b>
						<span id="numINV">'.$total_data.'</span>
						<input class="form-control d-none" type="number" id="numINV" value="'.$total_data.'">
					</div>
				</div>';
	echo $output;
?>