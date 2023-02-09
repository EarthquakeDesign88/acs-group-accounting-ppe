<?php
$sql = "SELECT * FROM level_tb ORDER BY lev_id ASC";
$result = $db->query($sql)->result();

$html = "";
if($result>=1){
	foreach ($result as $row) {
		$html .= '<option value="'.$row["lev_id"].'">';
		$html .= "Level ".$row["lev_id"];
		$html .= " | ";
		$html .= $row["lev_name"];
		$html .= '</option>';
	}
}
?>

<table>
	<tr class="row-title">
		 <th><?=$title_view;?></th>
	</tr>
	<tr>
		<td>
			<select name="level_id" id="level_id"  class="form-control input-form" onchange="onShow()">
				<option value="">- กรุณาเลือก Level -</option>
				<?=$html;?>
			</select>
		</td>
	</tr>
</table>
<div id="divShow"></div>
<script>
	function onShow(){
		var input = $("#level_id"); 
		var value = input.val(); 
		var divShow = $("#divShow");
		
		if(value!=""){
			input.prop("disabled",true);
			divShow.html("กำลังโหลด....");
			setTimeout(function() {
				$.ajax({
					type: "POST",
					url: "menu_access_level_content.php",
					data: "level_id="+value,
					success: function(res) {
						input.prop("disabled",false);
						divShow.html(res);
					}
				});
			}, 1000);
		}else{
			divShow.html("!!กรุณาเลือก Level");
			input.focus();
		}
	}
</script>