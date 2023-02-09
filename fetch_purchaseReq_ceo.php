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

	$str_sql = "SELECT * FROM purchasereq_tb AS pr INNER JOIN purchasereq_list_tb AS prlist ON pr.purc_no = prlist.purclist_purcno INNER JOIN payable_tb AS p ON pr.purc_payaid = p.paya_id INNER JOIN department_tb AS d ON pr.purc_depid = d.dep_id WHERE purc_compid = '". $_POST['queryComp'] ."' AND purc_depid = '". $_POST['queryDep'] ."' ";

	if($_POST['query'] != '') {

		$str_sql .= ' AND purc_no LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';

	}

	$str_sql .= ' GROUP BY purc_no ORDER BY purc_no DESC ';

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
							<th width="18%">เลขที่ขอซื้อ/ขอจ้าง</th>
							<th width="45%">รายละเอียด</th>
							<th width="15%" class="text-center">จำนวนเงิน</th>
							<th width="8%" class="text-center">ฝ่าย</th>
							<th></th>
						</tr>
					</thead>
					<tbody>';

	if($total_data > 0) {

		$i = 1;
		$total = 0;

		foreach($obj_query as $obj_row) {

			$str_sql_list = "SELECT * FROM purchasereq_list_tb WHERE purclist_purcno = '". $obj_row["purc_no"] ."'";
			$obj_rs_list = mysqli_query($obj_con, $str_sql_list);
			$listDesc = "";
			while ($obj_row_list = mysqli_fetch_array($obj_rs_list)) {
				if (mysqli_num_rows($obj_rs_list) > 1) {
					$listDesc = $obj_row_list["purclist_description"] . " || " . $listDesc;
				} else {
					$listDesc = $obj_row_list["purclist_description"];
				}
			}

			if($obj_row['purc_statusceo'] == 1) {

				$displayApprove = 'display: none; width: 100%';
				$displayNoApprove = 'width: 100%';
				$chkbg = 'background-color: #DAFFCC';

			} else {

				$displayApprove = 'width: 100%';
				$displayNoApprove = 'display: none; width: 100%';
				$chkbg = 'background-color: #FFFFFF';

			}

			$output .= '<tr id="tr'. $pageno . $i .'" style="'.$chkbg.'">
							<td>
								'. $obj_row["purc_no"] .'
							</td>
							<td>
								<div class="truncate">
									<b>บริษัท : </b> '. $obj_row["paya_name"] .' <br>
									<b>รายการ : </b> '. $listDesc .'
								</div>
							</td>
							<td class="text-right">
								'. number_format($obj_row["purc_total"],2) .'
							</td>
							<td class="text-center">
								'. $obj_row["dep_name"] .'
							</td>
							<td>
								<div class="input-group mb-0">
									<div class="input-group">
										<button type="button" class="btn btn-primary form-control view_data my-1" name="view" id="'. $obj_row["purc_no"] .'" title="ดู / View">
											<i class="icofont-eye-alt"></i> View Detail
										</button>
									</div>
								</div>

								<div class="input-group mb-0">
									<div class="input-group">
										<div id="divconfirm_data'. $pageno . $i .'" style="'.$displayApprove.'">
											<button type="button" class="btn btn-success form-control my-1 confirm_data" name="confirm" title="อนุมัติ / Approve" id="confirm_data'. $pageno . $i .'">
												<i class="icofont-checked"></i> Approve
												<label class="container-checkbox">
													<input type="checkbox" name="chkappr'. $pageno . $i .'" id="chkappr'. $pageno . $i .'" OnClick="ClickValue(frmApprovePR, this, '. $pageno . $i .', '. $obj_row['purc_id'] .', '. $obj_row['purc_statusceo'] .')" value="'. $obj_row["purc_total"] .'" class="checkitem confirm_data">
													<span class="checkmark"></span>
												</label>
											</button>

											<input type="text" class="form-control d-none" id="valnet'. $pageno .$i .'" value="'. $obj_row["purc_total"] .'">
										</div>

										<div id="divnoconfirm_data'. $pageno . $i .'" style="'.$displayNoApprove.'">
											<button type="button" class="btn btn-danger form-control my-1 noconfirm_data" name="noconfirm" title="ไม่อนุมัติ / No Approve" id="noconfirm_data'. $pageno . $i .'">
												<i class="icofont-checked"></i> No Approve
												<label class="container-checkbox">
													<input type="checkbox" name="chkappr'. $pageno . $i .'" id="chkappr'. $pageno . $i .'" OnClick="ClickValue(frmApprovePR, this, '. $pageno . $i .', '. $obj_row['purc_id'] .', 1)" value="'. $obj_row["purc_total"] .'" class="checkitem confirm_data">
													<span class="checkmark"></span>
												</label>
											</button>

											<input type="text" class="form-control d-none" id="valnet'. $pageno .$i .'" value="'. $obj_row["purc_total"] .'">
										</div>
									</div>
								</div>
							</td>
							<td class="d-none">
								<input type="text" name="purcstatusceo" id="purcstatusceo'. $pageno . $i .'" value="'. $obj_row["purc_statusceo"] .'">
								<input type="text" name="purcapprceono" id="purcapprceono'. $pageno . $i .'" value="'. $obj_row["purc_apprceono"] .'">
							</td>
						</tr>';

			$i++;

		}

	} else {

		$output .= '</tbody>
					<tbody>
						<tr width="100%">
							<td colspan="5" align="center">ไม่มีข้อมูลขอซื้อ/ขอจ้าง</td>
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
					</div>';

					$str_sql_sum = "SELECT * FROM purchasereq_tb AS pr INNER JOIN payable_tb AS p ON pr.purc_payaid = p.paya_id INNER JOIN department_tb AS d ON pr.purc_depid = d.dep_id INNER JOIN user_tb AS u ON pr.purc_userid_create = u.user_id WHERE purc_statusceo = 1 AND purc_apprceono = '' AND purc_compid = '". $_POST['queryComp'] ."' AND purc_depid = '". $_POST["queryDep"] . "'";
					$obj_rs_sum = mysqli_query($obj_con, $str_sql_sum);

					$sum = 0;
					$countChk = 0;
					$countsum = 1;

					while ($obj_row_sum = mysqli_fetch_array($obj_rs_sum)) {
						$sum += $obj_row_sum["purc_total"];
						$countChk += $countsum;
					}

		$output .= '<div class="col-md-6">
						<div class="row py-2">
							<div class="col-md-7 mr-auto pt-auto pb-auto text-right mt-auto mb-auto">
								<h4 class="mb-0"><b>ยอดรวมอนุมัติ</b></h4>
							</div>
							<div class="col-md-5 pt-auto pb-auto mb-0 px-0">
								<input type="text" class="form-control text-right" id="NetamountSel" value="'. number_format($sum,2) .'" readonly style="font-size: 1.25rem; height: calc(1.5em + .75rem + 2px); padding: .375rem .75rem;">
								<input type="text" class="form-control d-none text-right" id="NetamountSelNum" value="'. $sum .'" readonly>

								<input type="text" class="form-control d-none text-right" name="CountChkAll" id="CountChkAll" value="'. $countChk .'" readonly>
							</div>
						</div>
					</div>
				</div>


				<script type="text/javascript">

					function chkNum(ele) {
						var num = parseFloat(ele.value);
						ele.value = num.toFixed(2);
					}

					function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
						try {
							decimalCount = Math.abs(decimalCount);
							decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

							const negativeSign = amount < 0 ? "-" : "";
							let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
							let j = (i.length > 3) ? i.length % 3 : 0;
							return negativeSign + (j ? i.substr(0, j) + thousands : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");

						} catch (e) {
							console.log(e)
						}
					};

					function ClickValue(frmApprovePR, chk, index, purcid, sts) {
						var date = new Date();
						var day = date.getDate(),
						month = date.getMonth() + 1,
						year = date.getFullYear();

						month = (month < 10 ? "0" : "") + month;
						day = (day < 10 ? "0" : "") + day;
						var today = year + "-" + month + "-" + day;
						var check = document.getElementById("purcstatusceo"+index).value;

						var x = document.getElementById("valnet"+index).value;

						var y = Number($("#numChk").val());

						if (check == 0) {
								
							// alert("xxx");
							document.getElementById("tr"+index).style.backgroundColor = "#DAFFCC";
							document.getElementById("purcstatusceo"+index).value = 1;

							document.getElementById("purcstsceo"+purcid).value = 1;

							$("#divconfirm_data"+index).css("display","none");
							$("#divnoconfirm_data"+index).css("display","block");

							var sum = 0;
							var iNum = 0;

							var count = 0;
							var countChk = 0;

							$("div").filter(":gt(0)").find(":checkbox").each(function() {
								if($("div:gt(0)")) {
									if($(this).is(":checked")) {
										var iNum = Number($("#NetamountSelNum").val());
										sum = iNum + parseFloat(x);

										var countChk = Number($("#CountChkAll").val());
										count = countChk + 1;
									}
								}
							});



							$("#NetamountSel").val(formatMoney(sum));
							$("#NetamountSelNum").val(sum);

							$("#CountChkAll").val(count);

							$.ajax({
								data: {purcid:purcid, sts:sts},
								type: "post",
								url: "r_fetch_purchaseReq_ceo.php",
								success: function(data){
									// alert("Data Save:" + data);
								}
							});

						} else {
								
							// alert("yyy");
							document.getElementById("tr"+index).style.backgroundColor = "#FFFFFF";
							document.getElementById("purcstatusceo"+index).value = 0;

							document.getElementById("purcstsceo"+purcid).value = 0;

							$("#divconfirm_data"+index).css("display","block");
							$("#divnoconfirm_data"+index).css("display","none");

							var sum = 0;
							var iNum = 0;

							var count = 0;
							var countChk = 0;

							$("div").filter(":gt(0)").find(":checkbox").each(function() {
								if($("div:gt(0)")) {
									if($(this).is(":checked")) {
										var iNum = Number($("#NetamountSelNum").val());
										sum = iNum - parseFloat(x);

										var countChk = Number($("#CountChkAll").val());
										count = countChk - 1;
									}
								}
							});

							$("#NetamountSel").val(formatMoney(sum));
							$("#NetamountSelNum").val(sum);

							$("#CountChkAll").val(count);

							$.ajax({
								data: {purcid:purcid, sts:sts},
								type: "post",
								url: "r_fetch_purchaseReq_ceo.php",
								success: function(data){
									// alert("Data Save: " + data);
								}
							});

						}

					}
				</script>';

	echo $output;

?>