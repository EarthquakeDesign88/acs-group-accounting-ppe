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
	
	if(isset($_POST['queryCust'])) {
		$Cust = $_POST['queryCust'];
	} else {
		$Cust = '';
	}
	if($Cust == '') {
		if($_POST['queryComp'] == 'C009') {
			$str_sql = "SELECT * FROM receipt_tb AS r 
						INNER JOIN company_tb AS c ON r.re_compid = c.comp_id 
						INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
						INNER JOIN department_tb AS d ON r.re_depid = d.dep_id 
						INNER JOIN status_tb AS s ON r.re_stsid = s.sts_id
						WHERE re_compid = '". $_POST['queryComp'] ."' AND re_date BETWEEN '". $_POST['df'] ."' AND '". $_POST['dt'] ."' ";
		} else {
			$str_sql = "SELECT * FROM receipt_tb AS r 
						INNER JOIN company_tb AS c ON r.re_compid = c.comp_id 
						INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
						INNER JOIN department_tb AS d ON r.re_depid = d.dep_id 
						INNER JOIN status_tb AS s ON r.re_stsid = s.sts_id
						WHERE re_compid = '". $_POST['queryComp'] ."' AND re_date BETWEEN '". $_POST['df'] ."' AND '". $_POST['dt'] ."' ";
		}
	} else {
		if($_POST['queryComp'] == 'C009') {
			$str_sql = "SELECT * FROM receipt_tb AS r 
						INNER JOIN company_tb AS c ON r.re_compid = c.comp_id 
						INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
						INNER JOIN department_tb AS d ON r.re_depid = d.dep_id 
						INNER JOIN status_tb AS s ON r.re_stsid = s.sts_id
						WHERE re_compid = '". $_POST['queryComp'] ."' AND re_date BETWEEN '". $_POST['df'] ."' AND '". $_POST['dt'] ."' AND re_custid = '". $Cust ."' ";
		} else {
			$str_sql = "SELECT * FROM receipt_tb AS r 
						INNER JOIN company_tb AS c ON r.re_compid = c.comp_id 
						INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
						INNER JOIN department_tb AS d ON r.re_depid = d.dep_id 
						INNER JOIN status_tb AS s ON r.re_stsid = s.sts_id
						WHERE re_compid = '". $_POST['queryComp'] ."' AND re_date BETWEEN '". $_POST['df'] ."' AND '". $_POST['dt'] ."' AND re_custid = '". $Cust ."' ";
		}
		
	}
	$status = $_POST['queryStatus'];
	if($status != ''){
		$str_sql .= "AND re_stsid = '$status'";
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
							<th width="9%">เลขที่<br>ใบเสร็จรับเงิน</th>
							<th width="7%">วันที่<br>ใบเสร็จรับเงิน</th>
							<th width="9%">เลขที่<br>ใบแจ้งหนี้</th>
							<th>ชื่อผู้ซื้อ/ผู้รับบริการ/รายการ</th>
							<th width="8%">จำนวนเงิน</th>
							<th width="7%">ภาษี<br>มูลค่าเพิ่ม</th>
							<th width="8%">จำนวนเงินรวม</th>
							<th width="7%"><b>เลขที่เช็ค</b></th>
							<th width="8%"><b>ธนาคาร</b></th>
							<th width="7%"><b>วันที่ออกเช็ค</b></th>
							<th width="4%"><b>เงินสด</b></th>
						</tr>
					</thead>
					<tbody>';
	if($total_data > 0) {
		$i = 1;
		$total = 0;
		foreach($obj_query as $obj_row) {
			if ($obj_row["re_refinvrcpt"] == '0') {
				$str_sql_inv = "SELECT * FROM receipt_tb AS r 
						INNER JOIN invoice_rcpt_tb AS i ON r.re_invrcpt_id = i.invrcpt_id 
						WHERE re_id = '". $obj_row['re_id'] ."'";
				$obj_rs_inv = mysqli_query($obj_con, $str_sql_inv);
				$obj_row_inv = mysqli_fetch_array($obj_rs_inv);
				if($obj_row_inv["invrcpt_book"] == '') {
					$invoiceno = $obj_row_inv["invrcpt_no"];
				} else {
					$invoiceno = $obj_row_inv["invrcpt_book"] . "/" . $obj_row_inv["invrcpt_no"];
				}
			} else {
				$invoiceno = '';
			}
			
			
			if($obj_row["re_bookno"] == '') {
				$receiptno = $obj_row["re_no"];
			} else {
				$receiptno = $obj_row["re_bookno"] . "/" . $obj_row["re_no"];
			}
			if($obj_row["re_chequedate"] == '0000-00-00') {
				$chequedate = '';
			} else {
				$chequedate = DateThai($obj_row["re_chequedate"]);
			}
			// if($obj_row["invrcpt_book"] == '') {
			// 	$invoiceno = $obj_row["invrcpt_no"];
			// } else {
			// 	$invoiceno = $obj_row["invrcpt_book"] . "/" . $obj_row["invrcpt_no"];
			// }
			$subtotal = $obj_row['re_subtotal'];
			$vat = $obj_row['re_vat'] + $obj_row['re_differencevat'];
			$grandtotal = $obj_row['re_grandtotal'] + $obj_row['re_differencevat'] + $obj_row['re_differencegrandtotal'];
			$str_sql_b = "SELECT * FROM bank_tb WHERE bank_id = '". $obj_row["re_bankid"] ."'";
			$obj_rs_b = mysqli_query($obj_con, $str_sql_b);
			$obj_row_b = mysqli_fetch_array($obj_rs_b);
			if(mysqli_num_rows($obj_rs_b) > 0) {
				if($obj_row["re_bankid"] == '') {
					$bankname = '';
				} else {
					$bankname = $obj_row_b["bank_name"];
				}
			} else {
				$bankname = '';
			}
			$output .= '<tr>
							<td class="text-center text-nowrap">
								'. $receiptno .'
							</td>
							<td class="text-center">
								'. DateThai($obj_row['re_date']) .'
							</td>
							<td class="text-center text-nowrap">';
							if($obj_row['re_stsid'] != 'STS003'){ 
								$output .= $invoiceno;
							}else{
								$output .= "";
							}
			$output .= 		'</td>
							<td>';
							if($obj_row['re_stsid'] != 'STS003'){ 
								$output .= $obj_row['cust_name'];
							}else{
								$output .= $obj_row['re_note_cancel'];
							}
			$output .=	'</td>		
							<td class="text-right">';
							if($obj_row['re_stsid'] != 'STS003'){ 
								$output .= number_format($subtotal,2);
							}else{
								$output .= 0.00;
							}
			$output .=	'<input type="text" class="form-control text-right d-none numSub" value="'. $subtotal .'" readonly>
							</td>
							<td class="text-right">';
							if($obj_row['re_stsid'] != 'STS003'){ 
								$output .= number_format($vat,2);
							}else{
								$output .= 0.00;
							}
			$output .=	'<input type="text" class="form-control text-right d-none numVat" value="'. $vat .'" readonly>
							</td>
							<td class="text-right">';
							if($obj_row['re_stsid'] != 'STS003'){ 
								$output .= number_format($grandtotal,2);
							}else{
								$output .= 0.00;
							}
			$output .=	'<input type="text" class="form-control text-right d-none numInvNet" value="'. $grandtotal .'" readonly>
							</td>
							<td class="text-center">';
			$output .=		$obj_row['re_stsid'] != 'STS003' ? $obj_row['re_chequeno'] : '';
			$output .=		'</td>		
							<td class="text-center">';
			$output .=		$obj_row['re_stsid'] != 'STS003' ? $bankname : '';
			$output .=		'</td>
							<td class="text-center">';
			$output .=		$obj_row['re_stsid'] != 'STS003' ? $chequedate : '';
			$output .=		'</td>
							<td class="text-center">';
							if($obj_row['re_typepay'] == '1' && $obj_row['re_stsid'] != 'STS003') {
								$output .= '<img src="image/checkbox.jpg">';
							} else{
								$output .= '<img src="image/uncheckbox.jpg">';
							}
				$output .= '</td>
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
							<td colspan="12" align="center">ไม่มีใบเสร็จรับเงิน</td>
						</tr>
					</tbody>';
	}
		if($Cust == '') {
			$str_sql_sum = "SELECT * FROM receipt_tb WHERE re_compid = '". $_POST['queryComp'] ."' AND re_date BETWEEN '". $_POST['df'] ."' AND '". $_POST['dt'] ."'";
		} else {
			$str_sql_sum = "SELECT * FROM receipt_tb WHERE re_compid = '". $_POST['queryComp'] ."' AND re_date BETWEEN '". $_POST['df'] ."' AND '". $_POST['dt'] ."' AND re_custid = '". $Cust ."'";
		}
		if($status != ''){
			$str_sql_sum .= " AND re_stsid = '$status'";
		} else {
			$str_sql_sum .= " AND re_stsid <> 'STS003'";
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
				$Sumsub = $obj_row_sum["re_subtotal"] + $Sumsub;
				$Sumvat = $obj_row_sum["re_vat"] + $obj_row_sum["re_differencevat"] + $Sumvat;
				$Sumgrand = $obj_row_sum["re_grandtotal"] + $obj_row_sum["re_differencevat"] + $obj_row_sum["re_differencegrandtotal"] + $Sumgrand;
			}
		}
		$output .= '<tfoot>
						<tr>
							<td colspan="3" style="border-right: none;"></td>
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
							<td colspan="4"></td>
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