<?php
include '../config/config.php'; 

$user_id =  $_POST["user_id"];
$sql = "SELECT u.*,c.comp_id, c.comp_name,d.dep_id,d.dep_name FROM user_tb AS u 
INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
INNER JOIN company_tb AS c ON d.dep_compid = c.comp_id 
WHERE u.user_id='".$user_id."'";
$row = $db->query($sql)->row();
$user_level = $row["user_levid"];

$icon_checked = '<font style="font-size:20px; color:green"><i class="icofont-checked"></i></font>';

$arrMenuList = __menu_list();
$arrMenu = $arrMenuList["arrayManu"];
$arrMenuSort1 = $arrMenuList["arraySort1"];
$arrMenuSort2 = $arrMenuList["arraySort2"];
$arrMenuSort3 = $arrMenuList["arraySort3"];
$arrMenuData = $arrMenuList["arrayMenuData"];

$arrAccess = __menu_list_access_user($user_id);
$arrAccessLevel = __menu_list_access_level($user_level);

$total_page = 0;
$total_access = 0;

$n=1;
$html = "";
if(!empty($arrMenuSort1)){
	foreach ($arrMenuSort1 as $keyMenu => $valMenu) {
		$valMenuData = $arrMenuData[$keyMenu];
		$menu_id = $valMenuData["menu_id"];
		$menu_name = $valMenuData["menu_name"];
		$menu_type = $valMenuData["menu_type"];
		$menu_sort = $valMenuData["menu_sort"];
		$menu_status = $valMenuData["menu_status"];
		$menu_file = $valMenuData["menu_file"];
		$menu_parent_id = $valMenuData["menu_parent_id"];
		$menu_parent_id = ($menu_parent_id==0) ? "-" : $menu_parent_id;
		$menu_remark = $valMenuData["menu_remark"];
		$arrMenuInParent1 = (!empty($arrMenuSort2[$menu_id])) ? $arrMenuSort2[$menu_id] : array();
		
		//page 1
		$check_page1 = ($menu_type=="page") ? 1 : 0;
		$count_page_inparent1 = $check_page1;

		//user 1
		$check_access_user1 = __menu_check_access($menu_id,$menu_type,$arrAccess);
		$count_access_user_inparent1 = $check_access_user1;
		
		//level 1
		$check_access_level1 = __menu_check_access($menu_id,$menu_type,$arrAccessLevel);
		$count_access_level_inparent1 = $check_access_level1;
		
		//input 1
		$check_input1 =  (!$check_access_level1) ? $check_page1 : 0;
		$count_input_inparent1 =  $check_input1;
		
		//access 1
		$check_access1 =  ($check_access_user1 ||$check_access_level1) ? 1 : 0;
		$count_access_inparent1 =  $check_access1;
		
		
		$show_manu1 = ( ($menu_type=="page") || ($menu_type=="dropdown" && count($arrMenuInParent1) >=1)) ? 1 : 0 ;
		
		 $n2 = 1;
		$html2= "";
		if(!empty($arrMenuInParent1)){
			foreach ($arrMenuInParent1 as $keyMenu2 => $valMenu2) {
				$valMenuData2 = $arrMenuData[$keyMenu2];
				$menu_id2 = $valMenuData2["menu_id"];
				$menu_name2 = $valMenuData2["menu_name"];
				$menu_type2 = $valMenuData2["menu_type"];
				$menu_sort2 = $valMenuData2["menu_sort"];
				$menu_status2 = $valMenuData2["menu_status"];
				$menu_file2 = $valMenuData2["menu_file"];
				$menu_parent_id2 = $valMenuData2["menu_parent_id"];
				$menu_parent_id2 = ($menu_parent_id2==0) ? "-" : $menu_parent_id2;
				$menu_remark2 = $valMenuData2["menu_remark"];
				$arrMenuInParent2 = (!empty($arrMenuSort3[$menu_id][$menu_id2])) ? $arrMenuSort3[$menu_id][$menu_id2] : array();
		
				//page 2
				$check_page2 = ($menu_type2=="page") ? 1 : 0;
				$count_page_inparent1 += $check_page2;
				$count_page_inparent2 = $check_page2;
				
				//user 2
				$check_access_user2 = __menu_check_access($menu_id2,$menu_type2,$arrAccess);
				$count_access_user_inparent1 += $check_access_user2;
				$count_access_user_inparent2 = $check_access_user2;
				
				//level 2
				$check_access_level2 = __menu_check_access($menu_id2,$menu_type2,$arrAccessLevel);
				$count_access_level_inparent1 += $check_access_level2;
				$count_access_level_inparent2 = $check_access_level2;
				
				//input 2
				$check_input2 =  (!$check_access_level2) ? $check_page2 : 0;
				$count_input_inparent1 +=  $check_input2;
				$count_input_inparent2 =  $check_input2;
				
				//access 2
				$check_access2 =  ($check_access_user2 || $check_access_level2) ? 1 : 0;
				$count_access_inparent1 +=  $check_access2;
				$count_access_inparent2 =  $check_access2;
				
				$show_manu2 = ( ($menu_type2=="page") || ($menu_type2=="dropdown" && count($arrMenuInParent2) >=1)) ? 1 : 0 ;
				
				$n3 = 1;
				$html3= "";
				if(!empty($arrMenuInParent2)){
					foreach ($arrMenuInParent2 as $keyMenu3 => $valMenu3) {
						$valMenuData3 = $arrMenuData[$keyMenu3];
						$menu_id3 = $valMenuData3["menu_id"];
						$menu_name3 = $valMenuData3["menu_name"];
						$menu_type3 = $valMenuData3["menu_type"];
						$menu_sort3 = $valMenuData3["menu_sort"];
						$menu_status3 = $valMenuData3["menu_status"];
						$menu_file3 = $valMenuData3["menu_file"];
						$menu_parent_id3 = $valMenuData3["menu_parent_id"];
						$menu_parent_id3 = ($menu_parent_id3==0) ? "-" : $menu_parent_id3;
						$menu_remark3 = $valMenuData3["menu_remark"];
						
						//page 3
						$check_page3 = ($menu_type3=="page") ? 1 : 0;
						$count_page_inparent1 += $check_page3;
						$count_page_inparent2 += $check_page3;
						
						//user 3
						$check_access_user3 = __menu_check_access($menu_id3,$menu_type3,$arrAccess);
						$count_access_user_inparent1 += $check_access_user3;
						$count_access_user_inparent2 += $check_access_user3;
						
						//level 3
						$check_access_level3 = __menu_check_access($menu_id3,$menu_type3,$arrAccessLevel);
						$count_access_level_inparent1 += $check_access_level3;
						$count_access_level_inparent2 += $check_access_level3;
						
						//input 3
						$check_input3 =  (!$check_access_level3) ? $check_page3 : 0;
						$count_input_inparent1 +=  $check_input3;
						$count_input_inparent2 +=  $check_input3;
						
						//access 3
						$check_access3 = ($check_access_user3 || $check_access_level3) ? 1 : 0;
						$count_access_inparent1 +=  $check_access3;
						$count_access_inparent2 +=  $check_access3;
						
						$show_manu3 = ($menu_type3=="page") ? 1 : 0;
						
						$checked3 = ($check_access3)  ?  " checked " : "";
						$class3 = "checkbox check_page check_page_3 check_parent_".$menu_id." check_parent_".$menu_id2;
						$data3 = ' data-id="'.$menu_id3.'" ';
						$data3 .= ' data-parent1="'.$menu_id.'" ';
						$data3 .= ' data-parent2="'.$menu_id2.'" ';
						$input3 = '<input type="checkbox" id="check_page_'.$menu_id3.'" name="check_page['.$menu_id3.']"  class="form-check '.$class3.'"  '.$data3.' value="1" '.$checked3.' > ';
						$title3 = $n.".".$n2.".".$n3." - ".$menu_name3;
						$col3 = ($check_access_level3)  ?  $icon_checked : $input3; 
						$col3 .= $title3;
						
						if($show_manu3){
							$html3 .= '<tr>';
								$html3 .= '<td style="width: 40px">&nbsp;</td>';
								$html3 .= '<td style="width: 40px">&nbsp;</td>';
								$html3 .= '<td>'.$col3.'</td>';
								$html3 .= '<td>'.$menu_file3.'</td>';
								$html3 .= '<td>'.nl2br($menu_remark).'</td>';
								$html3 .= '<td style="width: 60px;" class="text-center">'.$menu_id3.'</td>';
								$html3 .= '<td style="width: 60px;" class="text-center">'.$menu_sort3.'</td>';
								$html3 .= '<td style="width: 60px;" class="text-center">'.$menu_parent_id3.'</td>';
							$html3 .= '</tr>';
						}
						
						$n3++;
					}
				}
				
				
				$checked2 = ($count_page_inparent2==$count_access_inparent2)  ?  "checked " : "";
				$class2 = "checkbox check_page check_page_2 check_parent_".$menu_id." check_id_".$menu_id2;
				$data2 = 'data-id="'.$menu_id2.'"';
				$data2 .= ' data-parent1="'.$menu_id.'" ';
				$input2 = '<input type="checkbox" id="check_page_'.$menu_id2.'" name="check_page['.$menu_id2.']" class="form-check '.$class2.'" '.$data2.' value="1"   '.$checked2.' >';
				$title2 = $n.".".$n2." - " .$menu_name2;
				$col2 = ($count_page_inparent2==$count_access_level_inparent2)  ?  $icon_checked : $input2; 
				$col2 .= $title2;
				
				if($show_manu2){
					$html2 .= "<tr>";
					if($menu_type2=="page"){
						$html2 .= '<td>&nbsp;</td>';
						$html2 .= '<td colspan="2">'.$col2.'</td>';
						$html2 .= '<td>'.$menu_file2.'</td>';
						$html2 .= '<td>'.nl2br($menu_remark2).'</td>';
					}else if($menu_type2=="dropdown"){
						$html2 .= '<td>&nbsp;</td>';
						$html2 .= '<td colspan="4">'.$col2.'</td>';
					}
					
					$html2 .= '<td  class="text-center">'.$menu_id2.'</td>';
					$html2 .= '<td  class="text-center">'.$menu_sort2.'</td>';
					$html2 .= '<td  class="text-center">'.$menu_parent_id2.'</td>';
					$html2 .= $html3;
				}
				
				$n2++;
			}
		}
		
		$checked1 = ($count_page_inparent1==$count_access_inparent1)  ?  "checked " : "";
		$class1 = "checkbox check_page check_page_1"." check_id_".$menu_id; 
		$data1 = 'data-id="'.$menu_id.'"';
		$input1 = '<input type="checkbox" id="check_page_'.$menu_id.'" name="check_page['.$menu_id.']"  class="form-check '.$class1.'"  '.$data1.'  value="1"  '.$checked1.' >';
		$title1 = $n.' - '.$menu_name.'';
		$col1 = ($count_page_inparent1==$count_access_level_inparent1)  ?  $icon_checked : $input1; 
		$col1 .= $title1;
		
		if($show_manu1){
			$html1 = "<tr>";
			if($menu_type=="page"){
				$html1 .= '<td colspan="3">'.$col1.'</td>';
				$html1 .= '<td>'.$menu_file.'</td>';
				$html1 .= '<td>'.nl2br($menu_remark).'</td>';
			}else if($menu_type=="dropdown"){
				$html1 .= '<td colspan="5">'.$col1.'</td>';
			}
			
			$html1 .= '<td  class="text-center">'.$menu_id.'</td>';
			$html1 .= '<td  class="text-center">'.$menu_sort.'</td>';
			$html1 .= '<td  class="text-center">'.$menu_parent_id.'</td>';
			$html1 .= "</tr>";
			$html1 .= $html2;
		}
		
		$html .= $html1;
		
		$total_page += $count_page_inparent1;
		$total_access += $count_access_inparent1;
		
		$n++;
	}
}

$checked_all = ($total_page==$total_access)  ?  " checked " : "";
?>
<form action="" id="form" name="form" class="form-horizontal validation_basic" method="post">
<table>
	<tr>
		 <td>
		 	<b>Firstname:</b> <?=$row["user_firstname"];?>
		 </td>
	</tr>
	<tr>
		 <td>
		 	<b>Surname:</b> <?=$row["user_surname"];?>
		 </td>
	</tr>
	<tr>
		 <td>
		 	<b>Username:</b> <?=$row["user_name"];?>
		 </td>
	</tr>
	<tr>
		 <td>
		 	<b>Level:</b> <?=$row["user_levid"];?>
		 </td>
	</tr>
	<tr>
		 <td>
		 	<b>Company:</b> <?=$row["comp_name"];?> - <?=$row["comp_name"];?>
		 </td>
	</tr>
	<tr>
		 <td>
		 	<b>Department:</b> <?=$row["dep_id"];?> - <?=$row["dep_name"];?>
		 </td>
	</tr>
	
  <tr>
		<td>
			<table>
			<tr>
				<th colspan="3" style="text-align: left">
					<input type="checkbox" id="check_all" name="check_all" class="form-check check_all checkbox" value="1"  <?=$checked_all;?>> เลือกทั้งหมด
				</th>
				<th>File</th>
				<th>Remark</th>
				<th>ID</th>
				<th>Sort</th>
				<th>Parent ID</th>
			</tr>
			<?=$html;?>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<div id="divAlert"></div>
		</td>
	</tr>
	<tr>
		<th>
			<div id="divAlert"></div>
			<button type="button" onclick="onSave()" class="btn btn-dark nput-form ">บันทึกข้อมูล</button>
		</th>
	</tr>
</table>
</form>

<script>
	$(".check_all").click(function(){
	    $('.checkbox').not(this).prop('checked', this.checked);
	});

	$(".check_page_1").click(function(){
		var id = $(this).attr("data-id");
	    $('.check_parent_'+id).not(this).prop('checked', this.checked);
	});
	
	$(".check_page_2").click(function(){
		var id = $(this).attr("data-id");
	    $('.check_parent_'+id).not(this).prop('checked', this.checked);
	});
	
	$(".check_page").click(function(){
		var parent1 = $(this).attr("data-parent1");
		var parent2 = $(this).attr("data-parent2");
		
		onCheckPage(parent1,parent2)
	});
	
	
	function onCheckPage(parent1=0,parent2=0){
		var input_all = $(".check_all ");
		
		var input_3 = $(".check_page_3.check_parent_"+parent2);
		var input_checked_3 = $(".check_page_3.check_parent_"+parent2+":checked");
		var count_input_3 = input_3.length;
		var count_input_checked_3 = input_checked_3.length;		
		var count_input_unchecked_3 = (count_input_3 - count_input_checked_3);		
		
		var input_this_1 = $(".check_id_"+parent1);
		var input_this_2 = $(".check_id_"+parent2);
		
		if(count_input_unchecked_3>=1){
			 input_this_2.prop('checked', false);
		}else{
			input_this_2.prop('checked', true);
		}
		
		var input_2 = $(".check_page_2.check_parent_"+parent1);
		var input_checked_2 = $(".check_page_2.check_parent_"+parent1+":checked");
		var count_input_2 = $(".check_page_2.check_parent_"+parent1).length;
		var count_input_checked_2 = input_checked_2.length;		
		var count_input_unchecked_2 = (count_input_2 - count_input_checked_2);		
		
		if(count_input_unchecked_2>=1){
			 input_this_1.prop('checked', false);
		}else{
			input_this_1.prop('checked', true);
		}
		
		
		var input_1 = $(".check_page_1 ");
		var input_checked_1 = $(".check_page_1:checked");
		var count_input_1 = $(".check_page_1").length;
		var count_input_checked_1 = input_checked_1.length;		
		var count_input_unchecked_1 = (count_input_1 - count_input_checked_1);		
		
		if(count_input_unchecked_1>=1){
			 input_all.prop('checked', false);
		}else{
			input_all.prop('checked', true);
		}
	}
	
	function onSave(){
		var divAlert = $("#divAlert");
		var form     = $('#form');
		var formData = new FormData(document.forms.namedItem("form"));
		var input = $(".input-form");
		
		if(confirm("คุณต้องการบันทึกข้อมูล ใช่หรือไม่?")){
			input.prop("disabled",true);
			divAlert.html('<label class="alert alert-loading"></label>');
			
			formData.append('action', "save_menu_access_user");
			formData.append('user_id', "<?=$user_id?>");
			
			$.ajax({
				type: "POST",
				url: "action.php",
				data: formData,
            	cache:false,
            	contentType: false,
            	processData: false,
				success: function(jsondata) {
					if(IsJsonString(jsondata)==true){
						var res = JSON.parse(jsondata);  
						if($.trim(res.response)=="S"){
							input.prop("disabled",true);
							divAlert.html('<label class="alert alert-success-loader">บันทึกข้อมูลสำเร็จ กรุณารอสักครู่...</label>');
							
							setTimeout(function() {
								input.prop("disabled",false);
								onShow()
							}, 1000);
							
						}else{
							input.prop("disabled",false);
							divAlert.html('<label class="alert alert-error"> '+$.trim(res.message)+'</label>');
						} 
					}else{
						input.prop("disabled",false);
						divAlert.html('<label class="alert alert-error">กรุณาลองใหม่อีกครั้ง</label>');
					}
				}
			});
		}
	}
</script>