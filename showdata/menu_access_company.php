<?php
$sql = "SELECT * FROM company_tb ORDER BY comp_id ASC";
$result = $db->query($sql)->result();

$html = "";
if($result>=1){
	foreach ($result as $row) {
		$html .= '<option value="'.$row["comp_id"].'">';
		$html .= $row["comp_id"];
		$html .= " | ";
		$html .= $row["comp_name_short"];
		$html .= "  ";
		$html .= $row["comp_name"];
		$html .= '</option>';
	}
}

$arrMenuList = __menu_list();
?>

<table>
	<tr class="row-title">
		 <th><?=$title_view;?></th>
	</tr>
	<tr>
		<td>
			<select name="comp_id" id="comp_id"  class="form-control input-form" onchange="onShow()">
				<option value="">- กรุณาเลือก บริษัท -</option>
				<?=$html;?>
			</select>
		</td>
	</tr>
</table>
<div id="divShow"></div>
<script>
	function onShow(){
		var input = $("#comp_id"); 
		var value = input.val(); 
		var divShow = $("#divShow");
		
		if(value!=""){
			input.prop("disabled",true);
			divShow.html("กำลังโหลด....");
			setTimeout(function() {
				$.ajax({
					type: "POST",
					url: "menu_access_company_content.php",
					data: "comp_id="+value,
					success: function(res) {
						input.prop("disabled",false);
						divShow.html(res);
					}
				});
			}, 1000);
		}else{
			divShow.html("!!กรุณาเลือกบริษัท");
			input.focus();
		}
	}
</script>