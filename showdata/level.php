<table>
	<tr class="row-title">
		 <th colspan="2"><?=$title_view;?></th>
	</tr>
	<tr>
		<th style="width: 70px;min-width: 70px;">Level</th>
		<th style="min-width: 200px;">Name</th>
	</tr>
	<?php
	$sql = "SELECT * FROM level_tb ORDER BY lev_id ASC";
	$result = $db->query($sql)->result();
	if(count($result)){
		foreach ($result as $row) {
	?>
	<tr>
		<td class="text-center"><?=$row["lev_id"]?></td>
		<td class="text-left"><?=$row["lev_name"]?></td>
	</tr>
	<?php
		}
	}					
	?>
</table>