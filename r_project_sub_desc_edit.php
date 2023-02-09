<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	} else {
	
		if(!empty($_POST)) {

			include 'connect.php';

			$output = '';

			if(isset($_POST["projSid"])) {
				$projSid = $_POST["projSid"];
			} else {
				$projSid = '';
			}

			if(isset($_POST["projSdesc"])) {
				$projSdesc = $_POST["projSdesc"];
			} else {
				$projSdesc = '';
			}

			if(isset($_POST["projSlesson"])) {
				$projSlesson = $_POST["projSlesson"];
			} else {
				$projSlesson = '';
			}

			if(isset($_POST["calprojSamount"])) {
				$calprojSamount = $_POST["calprojSamount"];
			} else {
				$calprojSamount = "0.00";
			}

			if(isset($_POST["projSprojid"])) {
				$projSprojid = $_POST["projSprojid"];
			} else {
				$projSprojid = '';
			}

			$str_sql = "UPDATE project_sub_tb SET 
			projS_id = '$projSid',
			projS_description = '$projSdesc',
			projS_lesson = '$projSlesson',
			projS_amount = '$calprojSamount',
			projS_projid = '$projSprojid'
			WHERE projS_id = '$projSid'";

			if(mysqli_query($obj_con, $str_sql)) {

				$output .= '<thead class="thead-light">
								<tr>
									<th>รายละเอียด</th>
									<th width="15%">จำนวนงวด</th>
									<th width="20%">จำนวนเงิน</th>
									<th width="5%"></th>
								</tr>
							</thead>
							<tbody class="part" id="dynamicfieldPart">';
								$str_sql_projS = "SELECT * FROM project_sub_tb WHERE projS_projid = '". $projSprojid ."'";
								$obj_rs_projS = mysqli_query($obj_con, $str_sql_projS);
								$i = 1;
								while ($obj_row_projS = mysqli_fetch_array($obj_rs_projS)) {
					$output .= '<tr>
									<td class="d-none">
										<input type="text" class="form-control" name="partDBID'. $i .'" id="partDBID'. $i .'" value="'. $obj_row_projS["projS_id"] .'" readonly>
									</td>
									<td>
										<input type="text" class="form-control" name="partDBDesc'. $i .'" id="partDBDesc'. $i .'" autocomplete="off" placeholder="กรอกรายละเอียดส่วนย่อยโครงการ" value="'. $obj_row_projS["projS_description"] .'" readonly>
									</td>
									<td>
										<input type="text" class="form-control text-center" name="partDBlesson'. $i .'" id="partDBlesson'. $i .'" autocomplete="off" placeholder="กรอกงวด" value="'. $obj_row_projS["projS_lesson"] .'" readonly>
									</td>
									<td>
										<input type="text" class="form-control text-right" name="partDBShowValue'. $i .'" id="partDBShowValue'. $i .'" autocomplete="off" value="'. number_format($obj_row_projS["projS_amount"],2) .'" readonly>
										<input type="text" class="form-control text-right d-none" name="partDBCalValue'. $i .'" id="partDBCalValue'. $i .'" value="'. $obj_row_projS["projS_amount"] .'">
									</td>
									<td class="d-none">
										<input type="text" class="form-control" name="DBRefprojid'. $i .'" id="DBRefprojid'. $i .'" autocomplete="off" value="'. $obj_row_projS["projS_projid"] .'" readonly>
									</td>
									<td>
										<button type="button" name="edit" id="'. $obj_row_projS["projS_id"] .'" class="btn btn-warning edit_data" title="แก้ไข / Edit">
											<i class="icofont-edit"></i>
										</button>
									</td>
								</tr>';
									$i++;
								}
					$output .= '<tr class="subpart" id="part1"> 
									<td>
										<input type="text" class="form-control" name="partDesc1" id="partDesc1" autocomplete="off" placeholder="กรอกรายละเอียดส่วนย่อยโครงการ">
									</td>
									<td>
										<input type="text" class="form-control text-center" name="partlesson1" id="partlesson1" autocomplete="off" placeholder="กรอกงวด">
									</td>
									<td>
										<input type="text" class="form-control text-right" name="partShowValue1" id="partShowValue1" autocomplete="off" value="0.00">
										<input type="text" class="form-control text-right d-none" name="partCalValue1" id="partCalValue1" value="0.00">
									</td>
									<td class="d-none">
										<input type="text" class="form-control" name="Refprojid1" id="Refprojid1" autocomplete="off">
									</td>
									<td>
										<button type="button" name="addrow" id="addrow" class="btn btn-success">
											<i class="icofont-plus-circle"></i>
										</button>
									</td>
								</tr>';
				$output .= '</tbody>
							<span class="part d-none">Plus</span>
							<input type="text" class="form-control" id="countPart" name="countPart" value="1">';

				$output .= '<script type="text/javascript">
								$(document).ready(function() {
									var i = 1;
									$("#addrow").click(function(){
										i++;

										var n = $( ".part > tr.subpart" ).length + 1;
										$( "span.part" ).text( "Part " + n);
										document.getElementById("countPart").value = n;

										$("#dynamicfieldPart").append("<tr class="subpart" id="part"><td><input type="text" class="form-control" name="partDesc" id="partDesc" autocomplete="off" placeholder="กรอกรายละเอียดส่วนย่อยโครงการ"></td><td><input type="text" class="form-control text-center" name="partlesson" id="partlesson" autocomplete="off" placeholder="กรอกงวด"></td><td><input type="text" class="form-control text-right" name="partShowValue" id="partShowValue" autocomplete="off" value="0.00"><input type="text" class="form-control text-right d-none" name="partCalValue" id="partCalValue" value="0.00"></td><td class="d-none"><input type="text" class="form-control" name="Refprojid" id="Refprojid" autocomplete="off"></td><td><button type="button" name="remove" id="" class="btn btn-danger btn_remove"><i class="icofont-close"></i></button></td></tr><script>document.getElementById("partShowValue").onblur = function (){ this.value = parseFloat(this.value.replace(/,/g, "")).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");document.getElementById("partCalValue").value = this.value.replace(/,/g, "");}</script>");

									});

									$(document).on("click", ".btn_remove", function(){
										var button_id = $(this).attr("id");
										$("#part"+ button_id +"").remove();
										var n = $( ".part > tr.subpart" ).length;
										$( "span.part" ).text( "There are " + n + " divs." + "Click to add more.");
										document.getElementById("countPart").value = n;
									});
								});
							</script>';

				echo $output;

			} else {

			}

		}

	}

?>