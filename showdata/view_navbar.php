<?php
$sql = "SELECT * FROM level_tb ORDER BY lev_id ASC";
$result = $db->query($sql)->result();

$html_user_option = '';
if(count($result)>=1){
	foreach ($result as $row) {
		$sql2 = "SELECT * FROM user_tb WHERE user_levid=".$row["lev_id"]."";
		$result2 = $db->query($sql2)->result();
		$count_user = count($result2);		
		
		$html_user_option = "";
		if($count_user>=1){
			foreach ($result2 as $row2) {
				$html_user_option .= '<option value="'.$row2["user_id"].'">';
				$html_user_option .= "Level ".$row["lev_id"];
				$html_user_option .= " - ";
				$html_user_option .= $row2["user_firstname"];
				$html_user_option .= " ";
				$html_user_option .= $row2["user_surname"];
				$html_user_option .= '</option>';
			}
		}
			
		$html_user .= '<optgroup label="Level '.$row["lev_id"]." ".$row["lev_name"].'">';
		$html_user .= $html_user_option;
		$html_user .= '</optgroup>';
	}
}

$sql2 = "SELECT * FROM company_tb ORDER BY comp_id ASC";
$result2 = $db->query($sql2)->result();

$html_dep = "";
if($result2>=1){
	foreach ($result2 as $row2) {
		$html_option = "";
		
		$sql3= "SELECT * FROM department_tb WHERE dep_compid ='".$row2["comp_id"]."' ORDER BY dep_compid ASC";
		$result3 = $db->query($sql3)->result();
		
		if(count($result3)>=1){
			foreach ($result3 as $row3) {
				$html_option .= '<option value="'.$row3["dep_id"].'">';
				$html_option .= $row2["comp_id"];
				$html_option .= " ";
				$html_option .= $row2["comp_name"];
				$html_option .= " | ";
				$html_option .=  $row3["dep_id"];
				$html_option .= " ";
				$html_option .= $row3["dep_name"];
				$html_option .= '</option>';
			}
		}
		
		$html_dep .= '<optgroup label="'.$row2["comp_id"]." ".$row2["comp_name_short"]." ".$row2["comp_name"].'">';
		$html_dep .= $html_option;
		$html_dep .= '</optgroup>';
	}
}

?>

<table>
	<tr class="row-title">
		 <th><?=$title_view;?></th>
	</tr>
	<tr>
		<td>
			<select name="user_id" id="user_id" class="input-form form-control" onchange="onShow()">
				<option value="">- กรุณาเลือก User -</option>
				<?=$html_user;?>
			</select>
			<select name="dep_id" id="dep_id" class="input-form form-control" onchange="onShow()">
				<option value="">- กรุณาเลือก บริษัท/แผนก -</option>
				<?=$html_dep;?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<div id="divShow"></div>
		</td>
	</tr>
</table>

<script>
	function onShow(){
		var input_user = $("#user_id"); 
		var input_dep = $("#dep_id"); 
		var value_user = input_user.val(); 
		var value_dep = input_dep.val(); 
		var divShow = $("#divShow");
		var input = $(".input-form");
		
		if(value_user!="" && value_dep!=""){
			divShow.html("กำลังโหลด....");
			setTimeout(function() {
				$.ajax({
					type: "POST",
					url: "view_navbar_content.php",
					data: "user_id="+value_user+"&dep_id="+value_dep,
					success: function(res) {
						divShow.html(res);
					}
				});
			}, 1000);
		}else{
			if(value_user=="" && value_dep==""){
				divShow.html("!!กรุณาเลือก User และ บริษัท");
				input_user.focus();
			}else{
				if(value_user==""){
					divShow.html("!!กรุณาเลือก User");
					input_user.focus();
				}else if(value_dep==""){
					divShow.html("!!กรุณาเลือก บริษัท/แผนก");
					input_dep.focus();
				}
			}
		}
	}
</script>
