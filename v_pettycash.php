<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		if(isset($_POST["id"])) {  
		
			$output = '';
			include 'connect.php';

			$str_sql_user = "SELECT * FROM user_tb AS u INNER JOIN level_tb AS l ON u.user_levid = l.lev_id INNER JOIN department_tb AS d ON u.user_depid = d.dep_id WHERE user_id = '". $_SESSION["user_id"] ."'";
			$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
			$obj_row_user = mysqli_fetch_array($obj_rs_user);

			$str_sql = "SELECT * FROM pettycash_tb AS pc INNER JOIN company_tb AS c ON pc.pcash_compid = c.comp_id INNER JOIN payable_tb AS p ON pc.pcash_payaid = p.paya_id INNER JOIN department_tb AS d ON pc.pcash_depid = d.dep_id INNER JOIN user_tb AS u ON pc.pcash_userid_create = u.user_id WHERE pcash_id = '". $_POST["id"] ."' GROUP BY pcash_no";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);

			$str_sql_organ = "SELECT * FROM user_tb WHERE user_id = '".$obj_row["user_id"]."'";
			$obj_rs_organ = mysqli_query($obj_con, $str_sql_organ);
			$obj_row_organ = mysqli_fetch_array($obj_rs_organ);

			// $str_sql_apprPC = "SELECT * FROM user_tb WHERE user_id = '".$obj_row["apprPC_userid_create"]."'";
			// $obj_rs_apprPC = mysqli_query($obj_con, $str_sql_apprPC);
			// $obj_row_apprPC = mysqli_fetch_array($obj_rs_apprPC);

			function DateThai($strDate) {
				$strYear = date("Y",strtotime($strDate))+543;
				$strMonth = date("n",strtotime($strDate));
				$strDay = date("j",strtotime($strDate));
				$strHour = date("H",strtotime($strDate));
				$strMinute = date("i",strtotime($strDate));
				$strSeconds = date("s",strtotime($strDate));
				$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
				$strMonthThai = $strMonthCut[$strMonth];
				return "$strDay $strMonthThai $strYear";
			}

			function DateShortThai($strDate) {
				$strYear = substr(date("Y",strtotime($strDate))+543,-2);
				$strMonth = date("n",strtotime($strDate));
				$strDay = date("j",strtotime($strDate));
				$strHour = date("H",strtotime($strDate));
				$strMinute = date("i",strtotime($strDate));
				$strSeconds = date("s",strtotime($strDate));
				$strMonthCut = Array("","01","02","03","04","05","06","07","08","09","10","11","12");
				$strMonthThai = $strMonthCut[$strMonth];
				return "$strDay/$strMonthThai/$strYear";
			}

			// $n = 1;
			$sumsub = 0; $sumvat = 0; $sumtax = 0; $sumgrand = 0; $sumdiff = 0; $sumnet = 0;

			$sumsub = $obj_row["pcash_subtotal1"] + $obj_row["pcash_subtotal2"] + $obj_row["pcash_subtotal3"] + $obj_row["pcash_subtotal4"] + $obj_row["pcash_subtotal5"];
			$sumvat = $obj_row["pcash_vat1"] + $obj_row["pcash_vat2"] + $obj_row["pcash_vat3"] + $obj_row["pcash_vat4"] + $obj_row["pcash_vat5"];
			$sumtax = $obj_row["pcash_taxtotal1"] + $obj_row["pcash_taxtotal2"] + $obj_row["pcash_taxtotal3"] + $obj_row["pcash_taxtotal4"] + $obj_row["pcash_taxtotal5"];
			$sumgrand = $obj_row["pcash_grandtotal1"] + $obj_row["pcash_grandtotal2"] + $obj_row["pcash_grandtotal3"] + $obj_row["pcash_grandtotal4"] + $obj_row["pcash_grandtotal5"];
			$sumdiff = $obj_row["pcash_difference1"] + $obj_row["pcash_difference2"] + $obj_row["pcash_difference3"] + $obj_row["pcash_difference4"] + $obj_row["pcash_difference5"];
			$sumnet = $obj_row["pcash_netamount1"] + $obj_row["pcash_netamount2"] + $obj_row["pcash_netamount3"] + $obj_row["pcash_netamount4"] + $obj_row["pcash_netamount5"];

			if ($obj_row["pcash_subtotal1"] != '0.00') {

				$subtotal1 = number_format($obj_row["pcash_subtotal1"],2);
				$vat1 = number_format($obj_row["pcash_vat1"],2);
				$taxtotal1 = number_format($obj_row["pcash_taxtotal1"],2);
				$grandtotal1 = number_format($obj_row["pcash_grandtotal1"],2);
				$diff1 = number_format($obj_row["pcash_difference1"],2);
				$netamount1 = number_format($obj_row["pcash_netamount1"],2);

			} else {

				$subtotal1 = '';
				$vat1 = '';
				$taxtotal1 = '';
				$grandtotal1 = '';
				$diff1 = '';
				$netamount1 = '';

			}


			if ($obj_row["pcash_subtotal2"] != '0.00') {

				$subtotal2 = number_format($obj_row["pcash_subtotal2"],2);
				$vat2 = number_format($obj_row["pcash_vat2"],2);
				$taxtotal2 = number_format($obj_row["pcash_taxtotal2"],2);
				$grandtotal2 = number_format($obj_row["pcash_grandtotal2"],2);
				$diff2 = number_format($obj_row["pcash_difference2"],2);
				$netamount2 = number_format($obj_row["pcash_netamount2"],2);

			} else {

				$subtotal2 = '';
				$vat2 = '';
				$taxtotal2 = '';
				$grandtotal2 = '';
				$diff2 = '';
				$netamount2 = '';

			}

			if ($obj_row["pcash_subtotal3"] != '0.00') {

				$subtotal3 = number_format($obj_row["pcash_subtotal3"],2);
				$vat3 = number_format($obj_row["pcash_vat3"],2);
				$taxtotal3 = number_format($obj_row["pcash_taxtotal3"],2);
				$grandtotal3 = number_format($obj_row["pcash_grandtotal3"],2);
				$diff3 = number_format($obj_row["pcash_difference3"],2);
				$netamount3 = number_format($obj_row["pcash_netamount3"],2);

			} else {

				$subtotal3 = '';
				$vat3 = '';
				$taxtotal3 = '';
				$grandtotal3 = '';
				$diff3 = '';
				$netamount3 = '';

			}

			if ($obj_row["pcash_subtotal4"] != '0.00') {

				$subtotal4 = number_format($obj_row["pcash_subtotal4"],2);
				$vat4 = number_format($obj_row["pcash_vat4"],2);
				$taxtotal4 = number_format($obj_row["pcash_taxtotal4"],2);
				$grandtotal4 = number_format($obj_row["pcash_grandtotal4"],2);
				$diff4 = number_format($obj_row["pcash_difference4"],2);
				$netamount4 = number_format($obj_row["pcash_netamount4"],2);

			} else {

				$subtotal4 = '';
				$vat4 = '';
				$taxtotal4 = '';
				$grandtotal4 = '';
				$diff4 = '';
				$netamount4 = '';

			}

			if ($obj_row["pcash_subtotal5"] != '0.00') {

				$subtotal5 = number_format($obj_row["pcash_subtotal5"],2);
				$vat5 = number_format($obj_row["pcash_vat5"],2);
				$taxtotal5 = number_format($obj_row["pcash_taxtotal5"],2);
				$grandtotal5 = number_format($obj_row["pcash_grandtotal5"],2);
				$diff5 = number_format($obj_row["pcash_difference5"],2);
				$netamount5 = number_format($obj_row["pcash_netamount5"],2);

			} else {

				$subtotal5 = '';
				$vat5 = '';
				$taxtotal5 = '';
				$grandtotal5 = '';
				$diff5 = '';
				$netamount5 = '';

			}

			$output .= '<div class="container py-4 px-4">
							<div class="row">
								<div class="col-lg-6 col-md-6 ml-auto px-1">
									<h3 class="mb-0">ใบจ่ายเงินสดย่อย<br>Petty Cash Payment</h3>
								</div>
								<div class="col-lg-6 col-md-6 ml-auto px-1">
									<div class="row py-1">
										<div class="col-xs-3 col-md-3 text-right px-1"></div>
										<div class="col-xs-4 col-md-4 text-right px-1">
											<b>เลขที่ใบจ่ายเงินสดย่อย</b>
										</div>
										<div class="col-xs-5 col-md-5 text-center" style="border-bottom: 1px dotted #333;">
											' . $obj_row['pcash_no'] . '
										</div>
									</div>
									<div class="row py-1">
										<div class="col-xs-3 col-md-3 text-right px-1"></div>
										<div class="col-xs-4 col-md-4 text-right px-1">
											<b>ฝ่าย</b>
										</div>
										<div class="col-xs-5 col-md-5 text-center" style="border-bottom: 1px dotted #333;">
											' . $obj_row['dep_name'] . '
										</div>
									</div>
									<div class="row py-1">
										<div class="col-xs-3 col-md-3 text-right px-1"></div>
										<div class="col-xs-4 col-md-4 text-right px-1">
											<b>วันที่</b>
										</div>
										<div class="col-xs-5 col-md-5 text-center" style="border-bottom: 1px dotted #333;">
											' . DateThai($obj_row['pcash_date']) . '
										</div>
									</div>
								</div>
							</div>
							
							<div class="row py-2">
								<div class="col-xs-1 col-md-1 px-1">
									<b>ชื่อผู้รับ</b>
								</div>
								<div class="col-xs-11 col-md-11" style="border-bottom: 1px dotted #333;">
									' . $obj_row['paya_name'] . '
								</div>
							</div>
							<div class="row pt-3">
								<table class="table table-bordered">
									<thead class="thead-light text-center">
										<tr>
											<th width="3%">ที่</th>
											<th width="37%">รายการ</th>
											<th width="11%">ค่าบริการ</th>
											<th width="8%">VAT</th>
											<th width="11%">TAX</th>
											<th width="11%">ยอดขำระ</th>
											<th width="8%">+/-</th>
											<th width="11%">ยอดชำระสุทธิ</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>1.</td>
											<td>
												'. $obj_row["pcash_description1"] .'
											</td>
											<td class="text-right">
												'. $subtotal1 .'
											</td>
											<td class="text-right">
												'. $vat1 .'
											</td>
											<td class="text-right">
												'. $taxtotal1 .'
											</td>
											<td class="text-right">
												'. $grandtotal1 .'
											</td>
											<td class="text-right">
												'. $diff1 .'
											</td>
											<td class="text-right">
												'. $netamount1 .'
											</td>
										</tr>
										<tr>
											<td>2.</td>
											<td>
												'. $obj_row["pcash_description2"] .'
											</td>
											<td class="text-right">
												'. $subtotal2 .'
											</td>
											<td class="text-right">
												'. $vat2 .'
											</td>
											<td class="text-right">
												'. $taxtotal2 .'
											</td>
											<td class="text-right">
												'. $grandtotal2 .'
											</td>
											<td class="text-right">
												'. $diff2 .'
											</td>
											<td class="text-right">
												'. $netamount2 .'
											</td>
										</tr>
										<tr>
											<td>3.</td>
											<td>
												'. $obj_row["pcash_description3"] .'
											</td>
											<td class="text-right">
												'. $subtotal3 .'
											</td>
											<td class="text-right">
												'. $vat3 .'
											</td>
											<td class="text-right">
												'. $taxtotal3 .'
											</td>
											<td class="text-right">
												'. $grandtotal3 .'
											</td>
											<td class="text-right">
												'. $diff3 .'
											</td>
											<td class="text-right">
												'. $netamount3 .'
											</td>
										</tr>
										<tr>
											<td>4.</td>
											<td>
												'. $obj_row["pcash_description4"] .'
											</td>
											<td class="text-right">
												'. $subtotal4 .'
											</td>
											<td class="text-right">
												'. $vat4 .'
											</td>
											<td class="text-right">
												'. $taxtotal4 .'
											</td>
											<td class="text-right">
												'. $grandtotal4 .'
											</td>
											<td class="text-right">
												'. $diff4 .'
											</td>
											<td class="text-right">
												'. $netamount4 .'
											</td>
										</tr>
										<tr>
											<td>5.</td>
											<td>
												'. $obj_row["pcash_description5"] .'
											</td>
											<td class="text-right">
												'. $subtotal5 .'
											</td>
											<td class="text-right">
												'. $vat5 .'
											</td>
											<td class="text-right">
												'. $taxtotal5 .'
											</td>
											<td class="text-right">
												'. $grandtotal5 .'
											</td>
											<td class="text-right">
												'. $diff5 .'
											</td>
											<td class="text-right">
												'. $netamount5 .'
											</td>
										</tr>';

						$output .= '</tbody>
									<tfoot>
										<tr>
											<td colspan="2" class="text-right"><b>รวมเป็นเงิน</b></td>
											<td class="text-right">'. number_format($sumsub,2) .'</td>
											<td class="text-right">'. number_format($sumvat,2) .'</td>
											<td class="text-right">'. number_format($sumtax,2) .'</td>
											<td class="text-right">'. number_format($sumgrand,2) .'</td>
											<td class="text-right">'. number_format($sumdiff,2) .'</td>
											<td class="text-right">'. number_format($sumnet,2) .'</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>';


			$output .= '<div class="row px-4">
							<div class="col-lg-12 col-md-12 pt-5">
								<div class="row">
									<div class="col-lg-1 col-md-12 mr-auto"></div>
									<div class="col-lg-3 col-md-12 px-4 mr-auto">
										<div class="row px-2">';
											if ($obj_row["pcash_apprmgrno"] != '') {
								$output .= '<div class="col-lg-12 col-md-12" style="border-bottom: 1px dashed #333; text-align: center;">
												'. $obj_row_apprPC["user_firstname"] .' '. $obj_row_apprPC["user_surname"] .'
											</div>';
											} else {
								$output .= '<div class="col-lg-12 col-md-12" style="border-bottom: 1px dashed #333; margin-top: 26px;">
													
											</div>';
											}
								$output .= '<div class="col-lg-12 col-md-12">
												<div class="row">
													<div class="col-lg-4 col-md-12 px-0">
														<b>ผู้อนุมัติ</b>
													</div>
													<div class="col-lg-8 col-md-12 text-center" style="border-bottom: 1px dashed #333;">
														
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-lg-3 col-md-12 px-4 mr-auto">
										<div class="row px-2">
											<div class="col-lg-12 col-md-12 px-0 text-center" style="border-bottom: 1px dashed #333;">
												'. $obj_row["user_firstname"] .' '. $obj_row["user_surname"] .'
											</div>
											<div class="col-lg-12 col-md-12">
												<div class="row">
													<div class="col-lg-4 col-md-12 px-0">
														<b>ผู้จัดทำ</b>
													</div>
													<div class="col-lg-8 col-md-12" style="border-bottom: 1px dashed #333; text-align: center;">
														'. DateShortThai($obj_row["pcash_date"]) .'
													</div>
												</div>
											</div>
										</div>
									</div>							

									<div class="col-lg-3 col-md-12 px-4 mr-auto">
										<div class="row px-2">';
											if ($obj_row["pcash_payeename"] != '') {
								$output .= '<div class="col-lg-12 col-md-12" style="border-bottom: 1px dashed #333;">
												'. $obj_row["pcash_payeename"] .'
											</div>';
											} else {
								$output .= '<div class="col-lg-12 col-md-12" style="border-bottom: 1px dashed #333; margin-top: 26px;">
													
											</div>';
											}
								$output .= '<div class="col-lg-12 col-md-12">
												<div class="row">
													<div class="col-lg-4 col-md-12 px-0">
														<b>ผู้รับเงิน</b>
													</div>
													<div class="col-lg-8 col-md-12 text-center" style="border-bottom: 1px dashed #333;">';
														if($obj_row["pcash_payeename"] != ''){
															echo $obj_row["pcash_payeedate"];
														} else {
														
														}
										$output .= '</div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-lg-1 col-md-12 mr-auto"></div>

								</div>
							</div>
						</div>';

			echo $output;

		}

	}

?>