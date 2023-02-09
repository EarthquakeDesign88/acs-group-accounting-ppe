<?php
include '../config/config.php'; 

$user_id =  $_POST["user_id"];
$dep_id =  $_POST["dep_id"];
?>
<iframe src="view_navbar_page.php?user_id=<?=$user_id;?>&dep_id=<?=$dep_id;?>" title="description"  style="border:1px solid #000;height: 500px" width="100%" >กำลังโหลด...</iframe>
