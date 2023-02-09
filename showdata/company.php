<table>
	<tr class="row-title">
		 <th colspan="7"><?=$title_view;?></th>
	</tr>
	<tr>
		<th style="width: 70px;min-width: 70px;" class="text-center">No.</th>
		<th style="width: 70px;min-width: 70px;" class="text-center">Comp Id</th>
		<th style="width: 90px;min-width: 90px;" class="text-center">Name Short</th>
		<th style="min-width: 200px;" class="text-center">Name</th>
		<th style="width: 90px;min-width: 90px;" class="text-center"> Department ทั้งหมด</th>
		<th style="width: 90px;min-width: 90px;" class="text-center"> Department <br>ใช้งาน</th>
		<th style="width: 90px;min-width: 90px;" class="text-center"> Department <br>ปิดใช้งาน</th>
	</tr>
	<?php
	$sql = "SELECT 
	COUNT(*) AS count_dep_all,
	SUM(CASE WHEN d.dep_status=1 THEN 1 ELSE 0 END) AS count_dep_1,
	SUM(CASE WHEN d.dep_status=0 THEN 1 ELSE 0 END) AS count_dep_0,
	c.*
	FROM company_tb c
	LEFT JOIN department_tb d ON d.dep_compid = c.comp_id 
	 GROUP BY c.comp_id ORDER BY c.comp_id ASC";
	 
	$result = $db->query($sql)->result();
	$n = 1;
	if(count($result)){
		foreach ($result as $row) {
			$count_dep_all =$row["count_dep_all"];
			$count_dep_1 =$row["count_dep_1"];
			$count_dep_0 =$row["count_dep_0"];
	?>
	<tr>
		<td class="text-center"><?=$n;?></td>
		<td class="text-center"><?=$row["comp_id"];?></td>
		<td class="text-center"><?=$row["comp_name_short"];?></td>
		<td class="text-left"><?=$row["comp_name"];?></td>
		<td class="text-center"><?=($count_dep_all>=1) ? $count_dep_all: "0";?></td>
		<td class="text-center"><?=($count_dep_1>=1) ? $count_dep_1: "0";?></td>
		<td class="text-center"><?=($count_dep_0>=1) ? $count_dep_0: "0";?></td>
	</tr>
	<?php
		$n++;
		}
	}					
	?>
</table>