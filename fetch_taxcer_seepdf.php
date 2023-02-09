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
	$y = $_POST["y"];
	$m = $_POST["m"];
	$dep = $_POST["dep"];
	$str_sql = "SELECT DISTINCT * FROM taxcertificate_tb AS taxc 
				INNER JOIN department_tb AS d ON taxc.taxc_depid = d.dep_id 
				WHERE taxc_rev = (SELECT MAX(taxc_rev) AS taxc_rev FROM taxcertificate_tb WHERE taxc_no = taxc.taxc_no) AND taxc_depid = '".$dep."' AND taxc_year = '".$y."' AND taxc_month = '".$m."'";
	$str_sql .= ' GROUP BY taxc_no ORDER BY taxc_id DESC ';
	$filter_query = $str_sql . 'LIMIT '.$start.', '.$limit.'';
	$obj_query = mysqli_query($obj_con, $str_sql);
	$total_data = mysqli_num_rows($obj_query);
	$obj_query = mysqli_query($obj_con, $filter_query);
	$obj_row = mysqli_fetch_array($obj_query);
	$total_filter_data = mysqli_num_rows($obj_query);
	$output = '<table class="table mb-0">
					<thead class="thead-light">
						<tr>
							<th width="25%">เลขที่/เล่มที่</th>
							<th width="50%">รายละเอียด</th>
							<th width="25%"></th>
						</tr>
					</thead>
					<tbody>';
	if($total_data > 0) {
		$i = 1;
		$total = 0;
		$desc = '';
		$desPayment = '';
		$desPettyCash = '';
		
		$str_sql_pc = "SELECT DISTINCT * FROM taxcertificate_tb AS taxc 
						INNER JOIN department_tb AS d ON taxc.taxc_depid = d.dep_id 
						INNER JOIN pettycash_tb AS pc ON taxc.taxc_id = pc.pcash_taxcid 
						WHERE taxc_rev = (SELECT MAX(taxc_rev) AS taxc_rev FROM taxcertificate_tb WHERE taxc_no = taxc.taxc_no) AND taxc_depid = '".$dep."'";
		$obj_rs_pc = mysqli_query($obj_con, $str_sql_pc);
		$obj_row_pc = mysqli_fetch_array($obj_rs_pc);
		if (mysqli_num_rows($obj_rs_pc) > 0) {
			$desPettyCash = $obj_row_pc["pcash_description1"] . ' ' . $obj_row_pc["pcash_description2"] . ' ' . $obj_row_pc["pcash_description3"] . ' ' . $obj_row_pc["pcash_description4"] . ' ' . $obj_row_pc["pcash_description5"];
		}
		foreach($obj_query as $obj_row) {
			if ($obj_row["taxc_id"] != 0) {
				$desc = $desPayment;
			} else {
				$desc = $desPettyCash;
			}
			$str_sql_paym = "SELECT * FROM payment_tb AS paym INNER JOIN invoice_tb AS i ON paym.paym_id = i.inv_paymid WHERE paym_taxcid = '". $obj_row["taxc_id"] ."'";
			$obj_rs_paym = mysqli_query($obj_con, $str_sql_paym);
			$desPayment = "";
			$paymid = "";
			$n = 1;
			while ($obj_row_paym = mysqli_fetch_array($obj_rs_paym)) {
				if (mysqli_num_rows($obj_rs_paym) > 1) {
					$desPayment = $obj_row_paym["inv_description"] . " || " . $desPayment;
					$paymid = "&paymid=" . $obj_row_paym["paym_id"];
				} else {
					$desPayment = $obj_row_paym["inv_description"];
					$paymid = "&paymid=" . $obj_row_paym["paym_id"];
				}
				$n++;
			}
			// echo $str_sql_paym;
			$output .= '<tr>
							<td>
								<div class="truncate-id">
									'.$obj_row["taxc_no"].'
								</div>
							</td>
							<td>
								<div class="truncate">
									'.$desPayment.'
								</div>
							</td>
							<td>
                                <a href="export/taxcer_payment_pdf.php?taxcID='.$obj_row["taxc_id"].'" class="btn btn-primary form-control py-2 mb-1" target="blank">
                                    <i class="icofont-eye-alt"></i>&nbsp;&nbsp;View
                                </a>
                                <a  href="export/taxcer_payment_pdf.php?taxcID='.$obj_row["taxc_id"].'&download=1" class="btn btn-success form-control py-2 mb-1" target="blank">
                                    <i class="icofont-download-alt"></i>&nbsp;&nbsp;Downlaod
                                </a>
							</td>
						</tr>';
			$i++;
		}
	} else {
		$output .= '</tbody>
					<tbody>
						<tr>
							<td colspan="5" align="center">ไม่มีข้อมูลหนังสือรับรองการหักภาษี ณ ที่จ่าย</td>
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
						<b>จำนวนหักภาษี ณ ที่จ่ายทั้งหมด : </b><span id="numINV">'.$total_data.'</span>
						<input class="form-control d-none" type="number" id="numINV" value="'.$total_data.'">
					</div>
				</div>';
	echo $output;
?>