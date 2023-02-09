<?php
	include 'connect.php';
	$limit = '10';
	$pageno = 1;
	$query_tax_type = isset($_POST['queryTax']) ? $_POST['queryTax'] : '';
	$query_tax_month = isset($_POST['queryMonth']) ? $_POST['queryMonth'] : '';
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
        
	$str_sql = "SELECT * FROM taxcertificate_tb AS taxc 
				INNER JOIN taxwithheld_tb AS twh ON taxc.taxc_twhid = twh.twh_id 
				INNER JOIN payment_tb AS paym ON taxc.taxc_id = paym.paym_taxcid 
				LEFT JOIN invoice_tb AS i ON paym.paym_id = i.inv_paymid 
				INNER JOIN user_tb AS u ON taxc.taxc_userid_create = u.user_id
				WHERE taxc_compid = '". $_POST['queryComp'] ."' AND taxc_date BETWEEN '". $_POST['df'] ."' AND '". $_POST['dt'] ."'"; 
                
    if(!empty($_POST['queryDep'])){
        $str_sql .= " AND taxc_depid = '". $_POST['queryDep'] ."' ";
    }
    
	if($query_tax_type != ''){
		$str_sql .= "AND taxc.taxc_tfid = '$query_tax_type'";
	}
	if($query_tax_month != ''){
		$str_sql .= "AND taxc.taxc_month = '$query_tax_month'";
	}
	$str_sql .= " GROUP BY paym_no ORDER BY taxc_id ASC ";
	$filter_query = $str_sql . ' LIMIT '.$start.', '.$limit.'';
	// echo $filter_query;
	$obj_query_all = mysqli_query($obj_con, $str_sql);
	$total_data = mysqli_num_rows($obj_query_all);
	foreach($obj_query_all as $row){
		$sum_subtotal += $row["inv_tax1"] + $row["inv_tax2"] + $row["inv_tax3"];
		$sum_grandtotal += $row["inv_taxtotal1"] + $row["inv_taxtotal2"] +$row["inv_taxtotal3"];
	}
	$result_total = $sum_subtotal + $sum_grandtotal;
	$obj_query = mysqli_query($obj_con, $filter_query);
	$obj_row = mysqli_fetch_array($obj_query);
	$total_filter_data = mysqli_num_rows($obj_query);
	$output = '<table class="table table-bordered mb-0">
					<thead class="thead-light">
						<tr>
							<th width="15%">เล่มที่/เลขที่หัก ณ ที่จ่าย</th>
							<th width="10%">วันที่</th>
							<th width="38%">รายละเอียด</th>
							<th width="12%" class="text-center">จำนวนเงินที่จ่าย</th>
							<th width="10%" class="text-center">ภาษีที่หักและนำส่งไว้</th>
							<th width="15%" class="text-center">ยอดรวมสุทธิ</th>
						</tr>
					</thead>
					<tbody>';
	if($total_data > 0) {
		$i = 1;
		$total = 0;
		foreach($obj_query as $obj_row) {
			// if ($obj_row["paym_paydate"] == '0000-00-00') {
			// 	$sty = 'color: #F00;';
			// 	$text = 'รอทำจ่าย';
			// } else {
			// 	$sty = 'color:  #006600';
			// 	$text = 'ทำจ่ายแล้ว';
			// }
			$str_sql_paym = "SELECT * FROM payment_tb AS paym INNER JOIN invoice_tb AS i ON paym.paym_id = i.inv_paymid WHERE paym_taxcid = '". $obj_row["taxc_id"] ."'";
			$obj_rs_paym = mysqli_query($obj_con, $str_sql_paym);
			$invtax1  = 0;
			$invtax2  = 0;
			$invtax3  = 0;
			$invtaxtotal1 = 0;
			$invtaxtotal2 = 0;
			$invtaxtotal3 = 0;
			while ($obj_row_paym = mysqli_fetch_array($obj_rs_paym)) {
				$invtax1 += $obj_row_paym["inv_tax1"];
				$invtax2 += $obj_row_paym["inv_tax2"];
				$invtax3 += $obj_row_paym["inv_tax3"];
				$invtaxtotal1 += $obj_row_paym["inv_taxtotal1"];
				$invtaxtotal2 += $obj_row_paym["inv_taxtotal2"];
				$invtaxtotal3 += $obj_row_paym["inv_taxtotal3"];
				$taxtotal1 = $invtaxtotal1;
				$taxtotal2 = $invtaxtotal2;
				$taxtotal3 = $invtaxtotal3;
				$total_tax = $invtax1 + $invtax2 + $invtax3;
				$tax_result = $taxtotal1 + $taxtotal2 + $taxtotal3;
				$result = $total_tax + $tax_result;
			}
			// echo $str_sql_paym;
			$output .= '<tr>
							<td class="text-center">
								'. $obj_row['taxc_no'] .'
							</td>
							<td class="text-center">
								'. DateThai($obj_row['taxc_date']) .'
							</td>
							<td>
								<div class="truncate">
									<b>บริษัท : </b> '. $obj_row['twh_name'] .'<br>
									<b>รายการ : </b> '. $obj_row['inv_description'] .'
								</div>
							</td>		
							<td class="text-right">
								'. number_format($total_tax,2) .'
							</td>
							<td class="text-center">
								<div>'. number_format($tax_result,2) .'</div>
							</td>
							<td class="text-center">'. number_format($result,2) .'</td>
						</tr>';
					
			$i++;		
		}
		
		$output .= '<tfoot>
		<tr>
			<td colspan="2" style="border-right: none;"></td>
			<td class="text-right" style="border-left: none;">
				<b>ยอดรวม</b>
			</td>
			<td class="text-right" style="color: #F00;">
				<b>'. number_format($sum_subtotal,2) .' </b>
			</td>
			<td class="text-right" style="color: #F00;">
				<b>'. number_format($sum_grandtotal,2) .' </b>
			</td>
			<td class="text-right" style="color: #F00;">
				<b>'. number_format($result_total,2) .' </b>
			</td>
		<tr>
		</tfoot>';
	} else {
		$output .= '</tbody>
					<tbody>
						<tr width="100%">
							<td colspan="6" align="center">ไม่มีข้อมูลหนังสือรับรองหักภาษี ณ ที่จ่าย</td>
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
						<b>จำนวนหนังสือรับรองหัก ณ ที่จ่ายทั้งหมด : </b>
						<span id="numINV">'.$total_data.'</span>
						<input class="form-control d-none" type="number" id="numINV" value="'.$total_data.'">
					</div>
				</div>';
	echo $output;
?>