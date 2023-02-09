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

	$str_sql = "SELECT * FROM invoice_tb AS i 
				INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id 
				INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
				INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id 
				INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
				WHERE inv_compid = '". $_POST['queryComp'] ."' AND inv_depid = '". $_POST['queryDep'] ."' AND paym_typepay = 1 ";

	if($_POST['querySearch'] != '') {

		if($_POST['query'] != '') {

			$str_sql .= ' AND '. $_POST['querySearch'] .' LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';

		}

	}

	$str_sql .= ' GROUP BY paym_no ';

	if($_POST['queryFilPaym'] != '') {

		if($_POST['queryFilPaymVal'] != '') {

			$str_sql .= ' ORDER BY '. $_POST["queryFilPaym"] .' '. $_POST["queryFilPaymVal"];

		}

	} else {

		if($_POST['queryFilPaymVal'] != '') {

			$str_sql .= ' ORDER BY inv_id ' . $_POST["queryFilPaymVal"];

		} else {

			$str_sql .= ' ORDER BY inv_id DESC ';

		}

	}

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
							<th width="15%">เลขที่ใบสำคัญจ่าย</th>
							<th width="38%">รายละเอียด</th>
							<th width="12%" class="text-center">วันครบชำระ</th>
							<th width="15%" class="text-center">จำนวนเงิน</th>
							<th width="8%" class="text-center">ฝ่าย</th>
							<th width="12%" class="text-center"></th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		$i = 1;
		$total = 0;
		$invdesc = "";

		foreach($obj_query as $obj_row) {

			$str_sql_iv = "SELECT * FROM invoice_tb WHERE inv_paymid = '".$obj_row["inv_paymid"]."'";
			$obj_rs_iv = mysqli_query($obj_con, $str_sql_iv);
			$obj_row_iv = mysqli_fetch_array($obj_rs_iv);
			$obj_num = mysqli_num_rows($obj_rs_iv);

			$total = $total + $obj_row["inv_netamount"];

			if ($obj_row["inv_duedate"] < date('Y-m-d')) {
				$bg = 'color: #F00; text-align: center; font-size: 15px;font-weight: 700;';
				$text = 'เกินกำหนดชำระ';
			} else {
				$text = '';
				$bg = '';
			}

			// $invdesc = $obj_row['inv_description'] . " || " . $invdesc;

			$str_sql_iv = "SELECT * FROM invoice_tb WHERE inv_paymid = '" . $obj_row["inv_paymid"] . "'";
			$obj_rs_iv = mysqli_query($obj_con, $str_sql_iv);
			$invdesc = "";
			$invnetamount = 0;
			while ($obj_row_iv = mysqli_fetch_array($obj_rs_iv)) {
				$invdesc = $obj_row_iv["inv_description"] . " || " . $invdesc;
				$invnetamount += $obj_row_iv["inv_netamount"];
			}

			$output .= '<tr>
							<td>
								<div class="truncate-id">
									'. $obj_row['paym_no'] .'
								</div>
							</td>
							<td>
								<div class="truncate">
									<b>บริษัท : </b> '. $obj_row['paya_name'] .'<br>
									<b>รายการ : </b> '. $invdesc .'
								</div>
							</td>
							<td class="text-center">
								'. MonthThaiShort($obj_row['inv_duedate']) .'
								<div style="'.$bg.'">
									'.$text.'
								</div>
							</td>					
							<td class="text-right">
								<input type="text" class="form-control text-right numInvNet" value="'. number_format($invnetamount,2) .'" readonly>
								<input type="text" class="form-control text-right d-none numInvNet" value="'. $invnetamount .'" readonly>
							</td>
							<td class="text-center">
								'. $obj_row['dep_name'] .'
							</td>
							<td class="text-center">
								<a href="cheque_add.php?cid='.$obj_row['inv_compid'].'&dep='.$obj_row['inv_depid'].'&';

									$str_sql_iv = "SELECT * FROM invoice_tb WHERE inv_paymid = '". $obj_row["paym_id"] ."'";
									$obj_rs_iv = mysqli_query($obj_con, $str_sql_iv);
									$countiv = mysqli_num_rows($obj_rs_iv);

									$n = 1;
									while ($obj_row_iv = mysqli_fetch_array($obj_rs_iv)) {

										$invid = "invid" . $n . "=" . $obj_row_iv["inv_id"] . "&";	

							$output .= $invid;

										$n++;
									}


							$output .= 'countChk='.$obj_num.'&paymid='.$obj_row['paym_id'].'&paymrev='.$obj_row['paym_rev'].'" class="btn btn-primary">
									<i class="icofont-plus-circle"></i>&nbsp;ออกเช็ค
								</a>
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

		// $output .= '<tr>
		// 				<td width="70%" colspan="3" class="text-right" style="vertical-align: middle">
		// 					<b>รวมเงิน</b>
		// 				</td>
		// 				<td width="15%">
		// 					<input type="text" class="form-control text-right" value="'. number_format($total,2) .'" readonly>
		// 				</td>
		// 				<td></td>
		// 				<td></td>
		// 			</tr>';

	} else {

		$output .= '</tbody>
					<tbody>
						<tr width="100%">
							<td colspan="6" align="center">ไม่มีข้อมูลใบสำคัญจ่าย</td>
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