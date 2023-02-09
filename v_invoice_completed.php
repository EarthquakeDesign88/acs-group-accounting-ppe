<?php

	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		if(isset($_POST["id"])) {

			$output = '';
			$paymid = $_POST["id"];

			include 'connect.php';

			$sql_invoice = "SELECT * FROM invoice_tb AS i 
            	        INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id 
						INNER JOIN payable_tb as p ON i.inv_payaid = p.paya_id 
						INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id 
						INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
						INNER JOIN user_tb AS u ON i.inv_userid_create = u.user_id 
						WHERE inv_paymid = '".$paymid."'
						ORDER BY inv_duedate ASC";  					
			$result_invoice = mysqli_query($obj_con, $sql_invoice);
			$obj_row = mysqli_fetch_array($result_invoice);

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

            
            if($obj_row['paym_date'] != '0000-00-00') {
                $paymentDate = DateThai($obj_row['paym_date']);
            } else {
                $paymentDate = '';
            }


			if ($obj_row['inv_duedate'] != '0000-00-00') {
				$duedate = DateThai($obj_row['inv_duedate']);
			} else {
				$duedate = '';
			}

		
		
			$sql_total = "SELECT * FROM invoice_tb AS i 
							INNER JOIN payment_tb AS paym ON i.inv_paymid = paym.paym_id 
							INNER JOIN payable_tb as p ON i.inv_payaid = p.paya_id 
							INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id 
							INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
							INNER JOIN user_tb AS u ON i.inv_userid_create = u.user_id 
							WHERE inv_paymid = '".$paymid."' 
							ORDER BY inv_duedate ASC";  
			$result_total = mysqli_query($obj_con, $sql_total);


			$count_inv = mysqli_num_rows($result_total );


			$inv_desc = "";
			$inv_no = "";
			$inv_subTotal = 0;
			$inv_vatTotal = 0;
			$inv_taxTotal = 0;
			$inv_grandtotal = 0;
			$inv_files = [];


			while ($obj_row_total = mysqli_fetch_array($result_total)) {
				$inv_desc .= '<div class="col-10" style="border-bottom: 1px dashed #333;">
				' . $obj_row_total['inv_description'] . '
				</div><div class="col-2"></div>';
				$inv_no .= '<div class="col-10" style="border-bottom: 1px dashed #333;">
				' . $obj_row_total['inv_no'] . '
				</div><div class="col-2"></div>';
				
				$inv_subTotal += $obj_row_total["inv_subtotalNoVat"] + $obj_row_total["inv_subtotal"];		
				$inv_vatTotal += $obj_row_total["inv_vat"];
				$inv_taxTotal += $obj_row_total["inv_taxtotal1"] + $obj_row_total["inv_taxtotal2"] + $obj_row_total["inv_taxtotal3"];
				$inv_grandtotal += $obj_row_total["inv_grandtotal"];

				array_push($inv_files, $obj_row_total["inv_id"]);
			
			};


			$count_invFiles = count($inv_files);


			$output .= '<div class="container py-4 px-4">
							<div class="row py-2">
								<div class="col-md-12 col-lg-8 px-1">
									<b>จำนวนใบแจ้งหนี้ <span>'.$count_inv.'</span> </b>
								</div>
								<div class="col-md-12 col-lg-2 px-1">
									<b>Run Number :</b>
								</div>
								<div class="col-md-12 col-lg-2" style="border-bottom: 1px dashed #333;">
									' . $obj_row['inv_paymid'] . '
								</div>
							</div>

							<div class="row py-2">

								<div class="col-lg-9 col-md-12 px-1">
									<div class="row">
										<div class="col-lg-4 col-md-12">
											<b>เลขที่ใบสำคัญจ่าย</b>
										</div>
										<div class="col-lg-8 col-md-12" style="border-bottom: 1px dashed #333;">
											' . $obj_row['paym_no'] . '
										</div>
									</div>
								</div>

								<div class="col-lg-3 col-md-3">
									<div class="row">
										<div class="col-lg-4 col-md-4">
											<b>ฝ่าย</b>
										</div>
										<div class="col-lg-8 col-md-8 text-center" style="border-bottom: 1px dashed #333;">
											' . $obj_row['dep_name'] . '
										</div>
									</div>
								</div>

							</div>

							<div class="row py-2">
								<div class="col-md-12 col-lg-1 px-1">
									<b>บริษัท</b>
								</div>
								<div class="col-md-12 col-lg-11" style="border-bottom: 1px dashed #333;">
									' . $obj_row['comp_name'] . '
								</div>
							</div>

							<div class="row py-2">
								<div class="col-md-12 col-lg-1 px-1">
									<b>จ่ายให้</b>
								</div>
								<div class="col-md-12 col-lg-11" style="border-bottom: 1px dashed #333;">
									' . $obj_row['paya_name'] . '
								</div>
							</div>

							<div class="row py-2">
								<div class="col-md-12 col-lg-1 px-1">
									<b>ที่อยู่</b>
								</div>
								<div class="col-md-12 col-lg-11" style="border-bottom: 1px dashed #333;">
									' . $obj_row['paya_address'] . '
								</div>
							</div>

							<div class="row py-2">
								<div class="col-md-12 col-lg-2 px-1">
									<b>รายละเอียด</b>
								</div>
					
								' . $inv_desc . '
							
							</div>

							<div class="row py-2">
								<div class="col-md-12 col-lg-2 px-1">
									<b>เลขที่ใบแจ้งหนี้</b>
								</div>
							
								' . $inv_no .'
							
							</div>

							<div class="row py-2">
								<div class="col-md-6 col-lg-6 row py-2 mr-1">
									<div class="col-md-12 col-lg-5 px-1">
										<b>วันที่ใบสำคัญจ่าย</b>
									</div>
									<div class="col-md-12 col-lg-7 text-center" style="border-bottom: 1px dashed #333;">
										'. $paymentDate .'
									</div>
								</div>

								<div class="col-md-6 col-lg-6 row py-2 ml-1">
									<div class="col-md-12 col-lg-5 px-1">
										<b>วันที่กำหนดชำระ</b>
									</div>
									<div class="col-md-12 col-lg-7 pr-0 pl-0 text-center" style="border-bottom: 1px dashed #333;">
										'. $duedate .'
									</div>
								</div>
							</div>

							<div class="row py-2">
								<div class="col-lg-1 col-0 px-1"></div>
								<div class="col-lg-4 col-4 px-1 text-right total">
									<b>จำนวนเงิน : </b>
								</div>
								<div class="col-lg-6 col-7 px-1 text-right" style="border-bottom: 1px dashed #333;">
									<span>
										' . number_format($inv_subTotal, 2) .'
									</span>
								</div>
								<div class="col-lg-1 col-1 px-1 text-center">
									<b>บาท</b>
								</div>
							</div>


							<div class="row py-2">
								<div class="col-lg-2 col-0 px-1"></div>
								<div class="col-lg-3 col-4 px-1 text-right total">
									<b>ภาษีมูลค่าเพิ่ม : </b>
								</div>
								<div class="col-lg-6 col-7 text-right" style="border-bottom: 1px dashed #333;">
									<div class="row">
										<div class="col-lg-2 col-2 px-1 text-left">
											<span>
						
											</span>
										</div>

										<div class="col-lg-5 col-5 px-1 text-right"></div>

										<div class="col-lg-5 col-5 px-1 text-right">
											<span>
												' . number_format($inv_vatTotal, 2) .'
											</span>
										</div>
									</div>
								</div>
								<div class="col-lg-1 col-1 px-1 text-center">
									<b>บาท</b>
								</div>
							</div>

							<div class="row py-2">
								<div class="col-lg-2 col-0 px-1"></div>
								<div class="col-lg-3 col-4 px-1 text-right total">
									<b>หักภาษี ณ ที่จ่าย : </b>
								</div>
								<div class="col-lg-6 col-7 text-right" style="border-bottom: 1px dashed #333;">';

						$output .= '<div class="row">
										<div class="col-lg-2 col-2 px-1 text-left">
											<span>
											
											</span>
										</div>

										<div class="col-lg-5 col-5 px-1 text-right">
											<span>
											
											</span>
										</div>

										<div class="col-lg-5 col-5 px-1 text-right">
											<span>
												' . number_format($inv_taxTotal, 2) .'
											</span>
										</div>
									</div>';
								
					$output .= '</div>
								<div class="col-lg-1 col-1 px-1 text-center">
									<b>บาท</b>
								</div>
							</div>

							<div class="row py-2">
								<div class="col-lg-2 col-0 px-1"></div>
								<div class="col-lg-3 col-4 px-1 text-right total">
									<b>ยอดชำระสุทธิ : </b>
								</div>
								<div class="col-lg-6 col-7 text-right" style="border-bottom: 1px dashed #333;">
									<div class="row">
										<div class="col-lg-6 col-6 px-1 text-center">
											<span>
											
											</span>
										</div>

										<div class="col-lg-6 col-6 px-1 text-right">
											<span>
												' . number_format($inv_grandtotal, 2) .'
											</span>
										</div>
									</div>
								</div>
								<div class="col-lg-1 col-1 px-1 text-center">
									<b>บาท</b>
								</div>
							</div>

							<div class="row py-2">
								<div class="col-lg-2 col-2 px-1">
									<b>ผู้จัดทำเอกสาร :</b>
								</div>
								<div class="col-lg-10 col-10 px-1" style="border-bottom: 1px dashed #000;">
									'. $obj_row['user_firstname'] .'&nbsp;&nbsp;'. $obj_row['user_surname'] .'
								</div>
							</div>

							<div class="row py-2">
								<div class="col-xs-3 col-sm-3 col-lg-3 px-1">
									<b>ไฟล์เอกสารใบแจ้งหนี้</b>
								</div>
								<div class="col-xs-9 col-sm-9 col-lg-9">
									<div class="row">';
										for($i=0; $i< $count_invFiles; $i++) {						
											$str_sql_file = "SELECT * FROM invoice_file_tb WHERE invF_invid = '".$inv_files[$i]."'";
											$obj_rs_file = mysqli_query($obj_con, $str_sql_file);
											while ($obj_row_file = mysqli_fetch_array($obj_rs_file)) {
												$output .= '<div class="col-md-12 py-1">
																<a href="' . $obj_row_file['invF_filename'] . '" target="_blank" class="btn btn-primary">คลิกดูที่นี่</a>
																' . $obj_row_file['invF_filename'] . '
															</div>';
											}
										}

						$output .= '<div>
								</div>
								</div>
							</div>
						</div>';
						
			echo $output;
		}

	}

?>