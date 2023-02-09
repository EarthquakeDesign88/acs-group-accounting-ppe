<?php
include '../config/config.php'; 

$user_id =  $_GET["user_id"];
$dep_id =  $_GET["dep_id"];

$level_id = __data_user($user_id,"level_id");
$comp_id = __data_department($dep_id,"company_id");

$html = __menu_navbar($comp_id,$level_id,$user_id);
?>

<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="../plugins/bootstrap/bootstrap.min.css">
	<link href="../plugins/icofont/icofont.min.css" rel="stylesheet">
	<link rel="stylesheet" href="../css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
</head>
<body>
<header id="header" class="header-one">
	<div class="site-navigation" >
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<nav class="navbar navbar-expand-lg navbar-dark p-0">
						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>

						<div id="navbar-collapse" class="collapse navbar-collapse">
							<ul class="nav navbar-nav mr-auto">
								<?=$html;?>
							</ul>
						</div>
					</nav>
				</div>
			</div>
		</div>
	</div>
</header>
</body>	
