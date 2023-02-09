<table>
	<tr class="row-title">
		 <th colspan="9" ><?=$title_view;?></th>
	</tr>
	<tr>
		<th style="width: 70px;min-width: 70px;" >No.</th>
		<th style="width: 100px;min-width: 100px;">User Id</th>
		<th style="width: 100px;min-width: 100px;">Firstname</th>
		<th style="width: 100px;min-width: 100px;">Surname</th>
		<th style="width: 100px;min-width: 100px;">Username</th>
		<th style="width: 80px;min-width: 80px;">Company ID</th>
		<th style="min-width: 200px;">Company Name</th>
		<th style="width: 80px;min-width: 80px;">Department ID</th>
		<th style="width: 100px;min-width: 200px;">Department Name</th>
	</tr>
	<?php
	$sql = "SELECT * FROM level_tb ORDER BY lev_id ASC";
	$result = $db->query($sql)->result();
	if(count($result)){
		foreach ($result as $row) {
		
			$sql2 = "SELECT * FROM user_tb AS u 
				INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
				INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
				INNER JOIN company_tb AS c ON d.dep_compid = c.comp_id 
				WHERE u.user_levid=".$row["lev_id"]."";
			$result2 = $db->query($sql2)->result();
			$count_user = count($result2);
			
	?>
	<tr>
		<td colspan="9" style="background-color: #f8f4bd;font-weight: bold"><?=$row["lev_id"]?> - <?=$row["lev_name"]?></td>
	</tr>
	
	<?php if($count_user>=1){ ?>
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
			<td><?=$row2["user_id"];?></td>
			<td><?=$row2["user_firstname"];?></td>
			<td><?=$row2["user_surname"];?></td>
			<td><?=$row2["user_name"];?></td>
			<td class="text-center"><?=$row2["comp_id"];?></td>
			<td><?=$row2["comp_name"];?></td>
			<td class="text-center"><?=$text_dep_code;?></td>
			<td><?=$text_dep_name;?></td>
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