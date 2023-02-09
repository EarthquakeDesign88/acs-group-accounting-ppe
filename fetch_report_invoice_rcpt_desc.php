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

	if(isset($_POST['queryCust'])) {
		$Cust = $_POST['queryCust'];
	} else {
		$Cust = '';
	}

	if($_POST['queryCust'] == '') {

		$str_sql = "SELECT * FROM invoice_rcpt_tb AS ir 
					INNER JOIN company_tb AS c ON ir.invrcpt_compid = c.comp_id 
					INNER JOIN customer_tb AS cust ON ir.invrcpt_custid = cust.cust_id 
					INNER JOIN department_tb AS d ON ir.invrcpt_depid = d.dep_id 
					WHERE invrcpt_compid = '". $_POST['queryComp'] ."' AND invrcpt_date BETWEEN '". $_POST['df'] ."' AND '". $_POST['dt'] ."' ";

	} else {

		$str_sql = "SELECT * FROM invoice_rcpt_tb AS ir 
					INNER JOIN company_tb AS c ON ir.invrcpt_compid = c.comp_id 
					INNER JOIN customer_tb AS cust ON ir.invrcpt_custid = cust.cust_id 
					INNER JOIN department_tb AS d ON ir.invrcpt_depid = d.dep_id 
					WHERE invrcpt_compid = '". $_POST['queryComp'] ."' AND invrcpt_custid = '". $_POST['queryCust'] ."' AND invrcpt_date BETWEEN '". $_POST['df'] ."' AND '". $_POST['dt'] ."' ";

	}

	$status = $_POST['queryStatus'];
	if($status != ''){
		$str_sql .= "AND invrcpt_stsid = '$status'";
	}

	$str_sql .= " ORDER BY invrcpt_book ASC, invrcpt_no ASC ";

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
							<th width="10%">เลขที่ใบแจ้งหนี้</th>
							<th width="10%">วันที่</th>
							<th width="10%">เลขที่ใบเสร็จรับเงิน</th>
							<th width="9%">วันที่</th>
							<th>ชื่อผู้ซื้อ/ผู้รับบริการ/รายการ</th>
							<th width="12%">จำนวนเงิน</th>
							<th width="9%">ภาษีมูลค่าเพิ่ม</th>
							<th width="12%">จำนวนเงินรวม</th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		$i = 1;
		$total = 0;
		
		foreach($obj_query as $obj_row) {
			if($obj_row["invrcpt_book"] == '') {
				$invoiceno = $obj_row["invrcpt_no"];
			} else {
				$invoiceno = $obj_row["invrcpt_book"] . "/" . $obj_row["invrcpt_no"];
			}


			$str_sql_rcpt = "SELECT * FROM receipt_tb WHERE re_id = '". $obj_row["invrcpt_reid"] ."'";
			$obj_rs_rcpt = mysqli_query($obj_con, $str_sql_rcpt);
			$obj_row_rcpt = mysqli_fetch_array($obj_rs_rcpt);

			// if($obj_row_rcpt["re_invrcptid"] == '') {
				// $receiptno = '';
			// } else {
			if(mysqli_num_rows($obj_rs_rcpt) > 0) {
				if($obj_row_rcpt["re_bookno"] == '') {
					$receiptno = $obj_row_rcpt["re_no"];
				} else {
					$receiptno = $obj_row_rcpt["re_bookno"] . "/" . $obj_row_rcpt["re_no"];
				}

				$receiptdate = DateThai($obj_row_rcpt["re_date"]);
			} else {
				$receiptno = '';
				$receiptdate = '';
			}
			// }

			$subtotal = $obj_row['invrcpt_subtotal'];
			$vat = $obj_row['invrcpt_vat'] + $obj_row['invrcpt_differencevat'];
			$grandtotal = $obj_row['invrcpt_grandtotal'] + $obj_row['invrcpt_differencevat'] + $obj_row['invrcpt_differencegrandtotal'];

			$output .= '<tr>
							<td class="text-center">
								'. $invoiceno .'
							</td>
							<td class="text-center">
								'. DateThai($obj_row['invrcpt_date']) .'
							</td>
							<td class="text-center">';
			$output .=		$obj_row['invrcpt_stsid'] != 'STS003'? $receiptno : ''; 
			$output .=		'</td>
							<td class="text-center">';
			$output .= 		$obj_row['invrcpt_stsid'] != 'STS003'? $receiptdate : ''; 
			$output .=		'</td>
							<td>
								<div class="truncate">';
								$output .= $obj_row['invrcpt_stsid'] != 'STS003'? $obj_row['cust_name'] : 'ยกเลิก';
				$output .=	'</div>
							</td>		
							<td class="text-right">';
								$output .= $obj_row['invrcpt_stsid'] != 'STS003'? number_format($subtotal,2) : 0;
				$output .=	'<input type="text" class="form-control text-right d-none numSub" value="'. $subtotal .'" readonly>
							</td>
							<td class="text-right">';
								$output .= $obj_row['invrcpt_stsid'] != 'STS003'? number_format($vat,2) : 0;
				$output .=	'<input type="text" class="form-control text-right d-none numVat" value="'. $vat .'" readonly>
							</td>
							<td class="text-right">';
								$output .= $obj_row['invrcpt_stsid'] != 'STS003'? number_format($grandtotal,2) : 0;
				$output .=	'<input type="text" class="form-control text-right d-none numInvNet" value="'. $grandtotal .'" readonly>
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
							<td colspan="8" align="center">ไม่มีข้อมูลใบแจ้งหนี้</td>
						</tr>
					</tbody>';

	}

		$str_sql_sum = "SELECT * FROM invoice_rcpt_tb WHERE invrcpt_compid = '". $_POST['queryComp'] ."' AND invrcpt_date BETWEEN '". $_POST['df'] ."' AND '". $_POST['dt'] ."'";
		if($Cust != ''){
			$str_sql_sum .= " AND invrcpt_custid = '$Cust'";
		}
		if($status != ''){
			$str_sql_sum .= " AND invrcpt_stsid = '$status'";
		} else {
			$str_sql_sum .= " AND invrcpt_stsid <> 'STS003'";
		}
		$obj_rs_sum = mysqli_query($obj_con, $str_sql_sum);
		$Sumsub = 0;
		$Sumvat = 0;
		$Sumgrand = 0;
		while($obj_row_sum = mysqli_fetch_array($obj_rs_sum)) {
			if($status == 'STS003'){
				$Sumsub = 0;
				$Sumvat = 0;
				$Sumgrand = 0;
			}else{
				$Sumsub = $obj_row_sum["invrcpt_subtotal"] + $Sumsub;
				$Sumvat = $obj_row_sum["invrcpt_vat"] + $obj_row_sum["invrcpt_differencevat"] + $Sumvat;
				$Sumgrand = $obj_row_sum["invrcpt_grandtotal"] + $obj_row_sum["invrcpt_differencevat"] + $obj_row_sum["invrcpt_differencegrandtotal"] + $Sumgrand;
			}
		}

		$output .= '<tfoot>
						<tr>
							<td colspan="4" style="border-right: none;"></td>
							<td class="text-right" style="border-left: none;">
								<b>ยอดรวม</b>
							</td>
							<td class="text-right" style="color: #F00;">
								<b>'. number_format($Sumsub,2) .'</b>
							</td>
							<td class="text-right" style="color: #F00;">
								<b>'. number_format($Sumvat,2) .'</b>
							</td>
							<td class="text-right" style="color: #F00;">
								<b>'. number_format($Sumgrand,2) .'</b>
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
					</div>
				</div>';

	echo $output;
?>