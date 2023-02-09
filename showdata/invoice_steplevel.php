<?php
function html_user_level($result=array()){
	$html= "<ul>";
	if(count($result)>=1){
		foreach ($result as $row) {
			$html .= "<li>";
			$html .= $row["user_firstname"]." ".$row["user_surname"];
			$html .= "</li>";
		}
	}
	
	$html .= "</ul>";
	
	return $html;
}

$array_step = __array_invoice_step();
?>
<table>
	<thead>
		<tr class="row-title">
			 <th colspan="4"><?=$title_view;?></th>
		</tr>
			<tr>
			 <th style="width: 70px;min-width: 70px;">Step No.</th>
			 <th style="width: 100px;min-width: 100px;">Step Name</th>
			 <th style="width: 300px;min-width: 300px;">View </th>
			 <th style="min-width: 300px;">Approve </th>
		</tr>
		</th>
	</thead>
	<tbody>
		<?php 
		foreach ($array_step as $key => $val) {
		?>
		<tr>
			<td colspan="4"><h2>Case: <?=$key;?> - <?=$val["name"];?></h2></td>
		</tr>
			<?php
			foreach ($val["step"] as $key2 => $val2) {
			?>
			<tr>
				<td class="text-center"><?=$key2;?></td>
				<td><?=$val2["step_name"];?></td>
				<td>
				<?php
				$html_user_view = "";
				foreach ($val2["user_level_view"] as $val3) {
					$html_user_view .= "<b>Level ".$val3.": ".__level_name($val3)."</b>";
					$html_user_view .= "<br>";
					//$html_user_view .= html_user_level(__load_user_level($val3["user_level"]));
					//$html_user_view .= "<br>";
				}
				
				echo $html_user_view;
				?>
				</td>
				<td>
				<?php
				$html_user_approve = "";
				foreach ($val2["user_level_approve"] as $val4) {
					$html_user_approve .= "<b>Level ".$val4.": ".__level_name($val4)."</b>";
					$html_user_approve .= "<br>";
					//$html_user_approve .= html_user_level(__load_user_level($val4["user_level"]));
					//$html_user_approve .= "<br>";
				}
				
				echo $html_user_approve;
				?>
				</td>
			</tr>
			<?php } ?>
		<?php } ?>
	</tbody>
</table>