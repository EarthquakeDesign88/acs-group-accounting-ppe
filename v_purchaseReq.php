<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(isset($_POST["id"])) {

			$output = '';
			include 'connect.php';

			function DateMonthThai($strDate) {
				$strYear = date("Y",strtotime($strDate))+543;
				$strMonth= date("n",strtotime($strDate));
				$strDay= date("j",strtotime($strDate));
				$strHour= date("H",strtotime($strDate));
				$strMinute= date("i",strtotime($strDate));
				$strSeconds= date("s",strtotime($strDate));
				$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
				$strMonthThai=$strMonthCut[$strMonth];
				return "$strDay $strMonthThai $strYear";
			}

			function bahtText(float $amount): string {
				[$integer, $fraction] = explode('.', number_format(abs($amount), 2, '.', ''));

				$baht = convert($integer);
				$satang = convert($fraction);

				$output = $amount < 0 ? 'ลบ' : '';
				$output .= $baht ? $baht.'บาท' : '';
				$output .= $satang ? $satang.'สตางค์' : 'ถ้วน';

				return $baht.$satang === '' ? 'ศูนย์บาทถ้วน' : $output;
			}

			function convert(string $number): string {
				$values = ['', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];
				$places = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน'];
				$exceptions = ['หนึ่งสิบ' => 'สิบ', 'สองสิบ' => 'ยี่สิบ', 'สิบหนึ่ง' => 'สิบเอ็ด'];

				$output = '';

				foreach (str_split(strrev($number)) as $place => $value) {
					if ($place % 6 === 0 && $place > 0) {
						$output = $places[6].$output;
					}

					if ($value !== '0') {
						$output = $values[$value].$places[$place % 6].$output;
					}
				}

				foreach ($exceptions as $search => $replace) {
					$output = str_replace($search, $replace, $output);
				}

				return $output;
			}

			$str_sql = "SELECT * FROM purchasereq_tb AS pr INNER JOIN purchasereq_list_tb AS prlist ON pr.purc_no = prlist.purclist_purcno INNER JOIN company_tb AS c ON pr.purc_compid = c.comp_id INNER JOIN payable_tb AS p ON pr.purc_payaid = p.paya_id INNER JOIN department_tb AS d ON pr.purc_depid = d.dep_id WHERE purc_no = '". $_POST["id"] ."'";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);

			$output .= '<div class="container py-4 px-2">
							<div class="row pb-5">
								<div class="col-md-12 col-lg-8">
									<h3 class="mb-0">' . $obj_row['comp_name'] . '</h3>
									' . $obj_row['comp_address'] . '
								</div>
								<div class="col-md-12 col-lg-4"></div>
							</div>

							<div class="row py-1">
								<div class="col-md-12 col-lg-8">
									<div class="row">
										<div class="col-md-3 py-1">
											<b>บริษัทผู้ให้บริการ : </b>
										</div>
										<div class="col-md-9 py-1" style="border-bottom: 1px dotted #000;">
											' . $obj_row['paya_name'] . '
										</div>
										<div class="col-md-12 py-1" style="border-bottom: 1px dotted #000;">
											' . $obj_row['paya_address'] . '
										</div>
									</div>
								</div>
								<div class="col-md-12 col-lg-4">
									<div class="row">
										<div class="col-md-7 py-1 text-right">
											<b>เลขที่ใบขอซื้อ/ขอจ้าง : </b>
										</div>
										<div class="col-md-5 py-1 text-center" style="border-bottom: 1px dotted #000;">
											' . $obj_row['purc_no'] . '
										</div>
										<div class="col-md-7 py-1 text-right">
											<b>วันที่ใบขอซื้อ/ขอจ้าง : </b>
										</div>
										<div class="col-md-5 py-1 text-center" style="border-bottom: 1px dotted #000;">
											' . DateMonthThai($obj_row['purc_date']) . '
										</div>
									</div>
								</div>
							</div>

							<div class="row py-2">
								<div class="col-md-12 col-lg-12">
									<div class="table-responsive">
										<table class="table table-bordered">
											<thead class="thead-light">
												<tr>
													<th width="5%" class="text-center">No.</th>
													<th width="55%" class="text-center">รายการ</th>
													<th width="15%" class="text-center">ราคา/หน่วย</th>
													<th width="10%" class="text-center">จำนวน</th>
													<th width="15%" class="text-center">จำนวนเงิน</th>
												</tr>
											</thead>
											<tbody id="dynamicfieldPRDesc">';
												$str_sql_list = "SELECT * FROM purchasereq_list_tb WHERE purclist_purcno = '". $obj_row["purc_no"] ."'";
												$obj_rs_list = mysqli_query($obj_con, $str_sql_list);
												$i = 1;
												while ($obj_row_list = mysqli_fetch_array($obj_rs_list)) {
									$output .= '<tr id="PR'. $i .'">
													<td class="text-center">
														<b>'. $i .'.</b>
													</td>
													<td>
														'. $obj_row_list["purclist_description"] .'
													</td>
													<td class="text-right">
														'. number_format($obj_row_list["purclist_unitprice"],2) .'
													</td>
													<td class="text-right">
														'. number_format($obj_row_list["purclist_unit"],2) .'
													</td>
													<td class="text-right">
														'. number_format($obj_row_list["purclist_total"],2) .'
													</td>
												</tr>';
														$i++; 
													}
								$output .= '</tbody>
											<tfoot>
												<tr>
													<td colspan="2"></td>
													<td colspan="2" class="text-right">
														<b>รวมเงิน :</b>
													</td>
													<td class="text-right">
														'. number_format($obj_row["purc_subtotal"],2) .'
													</td>
												</tr>
												<tr>
													<td colspan="2"></td>
													<td colspan="2" class="text-right">
														<b>หักส่วนลด :</b>
													</td>
													<td class="text-right">
														'. number_format($obj_row["purc_discount"],2) .'
													</td>
												</tr>
												<tr>
													<td colspan="2"></td>
													<td colspan="2" class="text-right">
														<table>
															<tr style="border-bottom: none;">
																<td width="50%" style="border: none; padding: 0 .75rem">
																	<b>Vat :</b>
																</td>
																<td style="border: none; padding: 0 .75rem;">
																	'. number_format($obj_row["purc_vatpercent"],2) .'
																</td>
																<td width="5%" style="border: none; padding: 0 .75rem">
																	<b>%</b>
																</td>
															</tr>
														</table>
													</td>
													<td class="text-right">
														'. number_format($obj_row["purc_vat"],2) .'
													</td>
												</tr>
												<tr>
													<td colspan="2">
														<b>จำนวนเงิน : </b>&nbsp;&nbsp;&nbsp;&nbsp;
														'. bahtText($obj_row["purc_total"]) .'<span id="totalText"></span>
													</td>
													<td colspan="2" class="text-right">
														<b>จำนวนเงินรวมทั้งสิ้น :</b>
													</td>
													<td class="text-right">
														'. number_format($obj_row["purc_total"],2) .'
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
						</div>';

			echo $output;

		}

	}

?>