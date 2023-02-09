<table>
	<tr class="row-title">
		 <th colspan="9" ><?=$title_view;?></th>
	</tr>
	<tr>
		<th style="width: 70px;min-width: 70px;">No.</th>
		<th style="width: 70px;min-width: 70px;">Dep ID</th>
		<th style="width: 90px;min-width: 90px;">Name</th>
		<th style="min-width: 120px;" >Name TH</th>
		<th style="width: 120px;min-width: 120px;">Name EN</th>
		<th style="width: 90px;min-width: 90px;">Status</th>
	</tr> 
	<?php
	$sql = "SELECT * FROM company_tb ORDER BY comp_id ASC";
	$result = $db->query($sql)->result();
	if(count($result)){
		foreach ($result as $row) {
			$sql2 = "SELECT * FROM department_tb WHERE dep_compid='".$row["comp_id"]."' ORDER BY dep_id ASC";
			$result2 = $db->query($sql2)->result();
			$count_dep = count($result2);
			
	?>
	<tr>
		<td colspan="8" style="background-color: #f8f4bd;font-weight: bold"><?=$row["comp_id"]?> - <?=$row["comp_name_short"]?> - <?=$row["comp_name"]?></td>
	</tr>
	
	<?php if($count_dep>=1){ ?>
	<?php 
		$n = 1;
		foreach ($result2 as $row2) {
			$user_depid	 = $row2["user_depid"];
			$dep_status = $row2["dep_status"];
			$dep_type = $row2["dep_type"];
			$dep_name = $row2["dep_name"];
			$dep_name_th = $row2["dep_name_th"];
			$dep_name_en = $row2["dep_name_en"]; 
			
			$text_dep_code = "";
			$text_dep_name = "";
			
			if($dep_type=="sub"){
				$text_dep_code = $user_depid;
				$text_dep_name = $dep_name;
				$text_dep_name .= "-".$dep_name_th;
				$text_dep_name .= "-".$dep_name_en;
			}
	?>
	<tr>
		<td class="text-center"><?=$n;?></td>
		<td class="text-center"><?=$row2["dep_id"];?></td>
		<td class="text-center"><?=$row2["dep_name"];?></td>
		<td class="text-left"><?=$row2["dep_name_th"];?></td>
		<td class="text-left"><?=$row2["dep_name_en"];?></td>
		<td class="text-center"><?=$row2["dep_status"];?></td>
	</tr>
	<?php $n++;} ?>
	<?php }else{ ?>
		<tr>
			<td colspan="9"  class="font-red">No Data</td>
		</tr>
	<?php } ?>
	<?php
		}
	}					
	?>
</table>