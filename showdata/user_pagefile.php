<?php
$sql = "SELECT * FROM level_tb ORDER BY lev_id ASC";
$result = $db->query($sql)->result();

$html_option = '';
if(count($result)>=1){
	foreach ($result as $row) {
		$sql2 = "SELECT * FROM user_tb WHERE user_levid=".$row["lev_id"]."";
		$result2 = $db->query($sql2)->result();
		$count_user = count($result2);		
		
		$html_option = "";
		if($count_user>=1){
			foreach ($result2 as $row2) {
				$html_option .= '<option value="'.$row2["user_id"].'">';
				$html_option .= "Level ".$row["lev_id"];
				$html_option .= " - ";
				$html_option .= $row2["user_firstname"];
				$html_option .= " ";
				$html_option .= $row2["user_surname"];
				$html_option .= '</option>';
			}
		}
			
		$html .= '<optgroup label="Level '.$row["lev_id"]." ".$row["lev_name"].'">';
		$html .= $html_option;
		$html .= '</optgroup>';
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
				<?=$html;?>
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
		var input = $("#user_id"); 
		var value = input.val(); 
		var divShow = $("#divShow");
		
			if(value!=""){
			input.prop("disabled",true);
			divShow.html("กำลังโหลด....");
			setTimeout(function() {
				$.ajax({
					type: "POST",
					url: "user_pagefile_content.php",
					data: "user_id="+value,
					success: function(res) {
						input.prop("disabled",false);
						divShow.html(res);
					}
				});
			}, 1000);
		}else{
			divShow.html("!!กรุณาเลือก User");
			input.focus();
		}
	}
</script>
