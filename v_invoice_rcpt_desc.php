<?php 
	
	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {

		if(isset($_POST["id"])) {

			$output = '';
			include 'connect.php';

			$str_sql = "SELECT * FROM invoice_rcpt_desc_tb AS ird INNER JOIN project_tb AS p ON ird.invrcptD_projid = p.proj_id INNER JOIN customer_tb AS c ON ird.invrcptD_custid = c.cust_id WHERE invrcptD_id = '".$_POST["id"]."'";  
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);
			
			//Get compid for check company
			$cid = $obj_row["invrcptD_compid"];

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

			$output .= '<div class="container py-4 px-4">
							<div class="row py-2">
								<div class="col-md-1">
									<b>งวดที่ :</b>
								</div>
								<div class="col-md-3" style="border-bottom: 1px dashed #333;">
									' . $obj_row['invrcptD_lesson'] . '
								</div>
							</div>

							<div class="row py-2">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-bordered">
											<thead class="thead-light">
												<tr>
													<th colspan="3" class="text-center">รายการ</th>
													<th width="20%" class="text-center">จำนวนเงิน(บาท)</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td colspan="3">'. $obj_row["invrcptD_description1"] .'</td>
													<td class="text-right">'. number_format($obj_row["invrcptD_amount1"],2) .'</td>
												</tr>
												<tr>
													<td colspan="3">'. $obj_row["invrcptD_description2"] .'</td>
													<td class="text-right">'. number_format($obj_row["invrcptD_amount2"],2) .'</td>
												</tr>
												<tr>
													<td colspan="3">'. $obj_row["invrcptD_description3"] .'</td>
													<td class="text-right">'. number_format($obj_row["invrcptD_amount3"],2) .'</td>
												</tr>
												<tr>
													<td colspan="3">'. $obj_row["invrcptD_description4"] .'</td>
													<td class="text-right">'. number_format($obj_row["invrcptD_amount4"],2) .'</td>
												</tr>
												<tr>
													<td colspan="3">'. $obj_row["invrcptD_description5"] .'</td>
													<td class="text-right">'. number_format($obj_row["invrcptD_amount5"],2) .'</td>
												</tr>';
												
												if($cid == 'C014' || $cid == 'C015'){
													$output .= '	
													<tr>
														<td colspan="3">'. $obj_row["invrcptD_description6"] .'</td>
														<td class="text-right">'. number_format($obj_row["invrcptD_amount6"],2) .'</td>
													</tr>
													<tr>
														<td colspan="3">'. $obj_row["invrcptD_description7"] .'</td>
														<td class="text-right">'. number_format($obj_row["invrcptD_amount7"],2) .'</td>
													</tr>
													<tr>
														<td colspan="3">'. $obj_row["invrcptD_description8"] .'</td>
														<td class="text-right">'. number_format($obj_row["invrcptD_amount8"],2) .'</td>
													</tr>';
												}
											
										$output .= '
												<tr>
													<td>'. $obj_row["invrcptD_description9"] .'</td>
													<td colspan="2" width="25%">
														<b>จำนวนเงิน Sub Total</b>
													</td>
													<td class="text-right">
														'. number_format($obj_row["invrcptD_subtotal"],2) .'
													</td>
												</tr>
												<tr>';
						
										$output .= '
											<tr>
												<td>'. $obj_row["invrcptD_description10"] .'</td>
												<td colspan="2" width="25%">
													<b>ภาษีมูลค่าเพิ่ม</b>
												</td>
												<td class="text-right">
													'. number_format($obj_row["invrcptD_vat"],2) .'
												</td>
											</tr>';

										$output .= '
										<tr>
											<td>'. $obj_row["invrcptD_description11"] .'</td>
											<td colspan="2" width="25%">
												<b>จำนวนเงินรวม Grand Total</b>
											</td>
											<td class="text-right">
											'. number_format($obj_row["invrcptD_grandtotal"],2) .'
											</td>
										</tr>
											
										<tr>
											<td colspan="100%">
												<b>ตัวอักษร</b>
												'. bahtText($obj_row["invrcptD_grandtotal"],2) .'
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>';

			echo $output;

		}

	}

?>