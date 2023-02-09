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

	$str_sql = "SELECT * FROM invoice_tb AS i 
				INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id 
				INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
				INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id 
				INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
				LEFT JOIN cheque_tb AS cheq ON paym.paym_cheqid = cheq.cheq_id 
				LEFT JOIN bank_tb AS b ON cheq.cheq_bankid = b.bank_id 
				WHERE inv_compid = '".$_POST["comp"]."' AND inv_depid = '".$_POST["dep"]."' AND paym_cheqid <> '' ";

	if ($_POST["bank"] != '') {
		$str_sql .= " AND bank_id = '".$_POST["bank"]."' ";
	}

	$str_sql .= " GROUP BY inv_paymid ORDER BY cheq_id DESC ";

	$filter_query = $str_sql . 'LIMIT '.$start.', '.$limit.'';

	// echo $filter_query;

	$obj_query = mysqli_query($obj_con, $str_sql);
	$total_data = mysqli_num_rows($obj_query);

	$obj_query = mysqli_query($obj_con, $filter_query);
	$obj_row = mysqli_fetch_array($obj_query);
	$total_filter_data = mysqli_num_rows($obj_query);

	$output = '<table class="table mb-0">
					<thead class="thead-light">
						<tr>
							<th width="15%"></th>
							<th width="20%">เลขที่เช็ค</th>
							<th width="40%">รายละเอียด</th>
							<th width="10%" class="text-center">ฝ่าย</th>
							<th width="15%"></th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		$i = 1;
		$total = 0;

		foreach($obj_query as $obj_row) {

			if ($obj_row["inv_statusMgr"] == 1 && $obj_row["inv_statusCEO"] == 0) {
				$textSTS = 'ตรวจสอบแล้ว<br>รออนุมัติ';
				$stySTS = 'font-size: 14px; text-align: center; background-color: #FF9800; padding: 2px 6px; border-top-left-radius: .5rem; border-bottom-right-radius: .5rem; color: #FFF;';
			} else if ($obj_row["inv_statusMgr"] == 1 && $obj_row["inv_statusCEO"] == 1) {
				$textSTS = 'จ่ายแล้ว';
				$stySTS = 'font-size: 14px; text-align: center; background-color: #008800; padding: 2px 6px; border-top-left-radius: .5rem; border-bottom-right-radius: .5rem; color: #FFF;';
			}

			$year = $obj_row["cheq_year"];
			$month = $obj_row["cheq_month"];


			$str_sql_ivamount = "SELECT * FROM invoice_tb WHERE inv_paymid = '" . $obj_row["inv_paymid"] . "'";
			$obj_rs_ivamount = mysqli_query($obj_con, $str_sql_ivamount);

			$invdesc = "";
			$invnetamount = 0;
			while ($obj_row_ivamount = mysqli_fetch_array($obj_rs_ivamount)) {
				$invdesc = $obj_row_ivamount["inv_description"] . " || " . $invdesc;
				$invnetamount += $obj_row_ivamount["inv_netamount"];
			}

			$output .= '<tr>
							<td style="padding: .75rem .25rem;">
								<div style="'. $stySTS .'">
									'. $textSTS .'
								</div>
							</td>
							<td>
								<div class="truncate-id">
									'.$obj_row["cheq_no"].'
								</div>
							</td>
							<td>
								<div class="truncate">
									<b>บริษัท : </b> '. $obj_row['paya_name'] .'<br>
									<b>รายการ : </b> '. $invdesc .'
								</div>
							</td>
							<td class="text-center text-nowrap">
								'.$obj_row["dep_name"].'
							</td>
							<td>
								<div class="btn-group">
									<a href="export/cheque_pdf.php?payment='.$obj_row["paym_id"].'&cheque='.$obj_row["cheq_id"].'" class="btn btn-primary mr-2" target="blank" title="View">
										<i class="icofont-eye-alt"></i>
									</a>
									<a href="export/cheque_pdf.php?payment='.$obj_row["paym_id"].'&cheque='.$obj_row["cheq_id"].'&download=1" class="btn btn-success" target="blank" title="Download">
										<i class="icofont-download-alt"></i>
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
							<td colspan="5" align="center">ไม่มีข้อมูลเช็ค</td>
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
						<b>จำนวนเช็คทั้งหมด : </b><span id="numINV">'.$total_data.'</span>
						<input class="form-control d-none" type="number" id="numINV" value="'.$total_data.'">
					</div>
				</div>';

	echo $output;

?>