<?php
include 'config/config.php'; 
__check_login();

//session user
$session_user_id = __session_user("id");
$session_user_firstname = __session_user("firstname");
$session_user_surname = __session_user("surname");
$session_user_level_id = __session_user("level_id");
$session_user_level_color = __session_user("level_color");
$session_user_menu_list = __session_user("menu_list");
$session_user_department_name = __session_user("department_name");

$session_user_head = $session_user_firstname." ";
$session_user_head .= substr($session_user_surname, 0, 1).".";
$session_user_head .= "&nbsp;&nbsp;&nbsp;"; 

//parameter url 
$paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
$paramurl_department_id = (isset($_GET["dep"])) ?$_GET["dep"] : 0;
$paramurl_company_name = __data_company($paramurl_company_id,"name");

$arrayMenuStyle = __menu_user_style();
$bg_div_navbar = (isset($arrayMenuStyle["bg"])) ? $arrayMenuStyle["bg"] : "";
$style_css_class = (isset($arrayMenuStyle["css"])) ? $arrayMenuStyle["css"] : "";

$menu_navbar = __menu_navbar($paramurl_company_id,$session_user_level_id,$session_user_id);
$menu_navbar_html = $menu_navbar["html"];
$menu_navbar_check_error = $menu_navbar["ck_all"];
$menu_navbar_text_error = $menu_navbar["text_error_all"];
$menu_check_company = $menu_navbar["menu_check_company"];
$check_company = $menu_navbar["check_company"];
$menu_company_name = $menu_navbar["company_name"];

$menu_check_department = $menu_navbar["menu_check_department"];
$check_department = $menu_navbar["check_department"];
$menu_department_name = $menu_navbar["department_name"];

$count_dep = __data_company_count_department($paramurl_company_id);

?>
<style type="text/css">
	.icon-nav:hover {
		color: #faf;
		
	}
	@media (max-width: 767px) {
		ul.top-info-box {
			display: block;
		}
	}
	.page-item.disabled .page-link{
		color: #6c757d;
		pointer-events: none;
		cursor: not-allowed;
		background-color: #fff;
		border-color: #dee2e6;
	}
	
	.page-title {
		height: calc(9vh + 1%);
		background-color: #f5f9fc;
		color: #000;
		overflow: hidden;
		-webkit-box-shadow: 0px 6px 6px 0px #ccc;
		-moz-box-shadow: 0px 6px 6px 0px #ccc;
		box-shadow: 0px 6px 6px 0px #ccc;
		padding: calc(1vh + 1%);
	}
	
	.user-level{
		background-color: <?=$session_user_level_color;?>;
	    color: #FFFFFF;
	    padding: 2px 6px 2px 6px;
	    font-size: 14px;
	    display: block;
	    text-align: center;
	    margin-top: 14px;
	    width: 140px;
	    border-radius: 5px;
	}
	
	/*ul.navbar-nav li.active1 a.nav-link{color:#ffdf43!important;}*/
	ul.navbar-nav li.active1{border-bottom:6px solid #28a7e9;}
	ul.navbar-nav li.active2{border-bottom:6px solid #28a7e9;}
	ul.navbar-nav li.active3{border-bottom:6px solid #28a7e9;}
</style>
<!-- Header start -->
<header id="header" class="header-one">
	<div class="bg-blue">
		<div class="container">
			<div class="logo-area">
				<div class="row align-items-center">
					<div class="logo col-xs-4 text-center px-2">
						<a class="d-block" href="">
							<img loading="ACS" src="image/acslogo.png" width="100%" alt="acslogo">
						    <h3 class="text-light">ระบบสำหรับทดสอบ</h3>
						</a>
					</div>

					<div class="col-xs-8 ml-auto text-right px-2">
						<ul class="top-info-box">
							<li>
								<div class="info-box">
									<div class="info-box-content">
										<p class="info-box-title">
											<i class="icofont-user"></i>
											<?=$session_user_head;?> 
											<a id="logout" type="button">
												<i class="icofont-power" style="color: #F00;"></i>
											</a>
											<div class='user-level'><i class="icofont-user"></i> Level <?=$session_user_level_id;?> | <?=$session_user_department_name;?></div>
										</p>
										<p class="info-box-subtitle"></p>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<style>
	<?=$style_css_class;?>
	</style>
	<div class="site-navigation" style="<?= ($bg_div_navbar!="" ? "background :".$bg_div_navbar."!important;": "");?>">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<nav class="navbar navbar-expand-lg navbar-dark p-0">
						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>

						<div id="navbar-collapse" class="collapse navbar-collapse">
							<ul class="nav navbar-nav mr-auto">
								
								<?= ($menu_check_company=="Y") ?  $menu_navbar_html : "" ;?>
							</ul>

							<ul class="nav navbar-nav ml-auto">									
								<li class="nav-item">
									<a class="nav-link" href="change_password.php?cid=<?=$paramurl_company_id;?>">เปลี่ยนรหัสผ่าน</a>
								</li>
							</ul>
						</div>
					</nav>
				</div>
			</div>
		</div>
		
		
	<?php if($menu_check_company=="Y" && $check_company){?>
		<div class="page-title">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<h3 class="mb-0">
								<?=$menu_company_name;?>
								
									<?php if($menu_check_department=="Y" && $check_department && $count_dep>1){?>
										<i class="icofont-caret-right"></i> <?=$menu_department_name;?>
									<?php } ?>
							</h3>
						</div>
					</div>
				</div>
			</div>
	<?php } ?>
	</div>
</header>
<!--/ Header end -->

<script type="text/javascript">
	$(document).on('click', '#logout', function(){
		event.preventDefault();
		var del_id = $(this).attr("id");
		var element = this;
		swal({
			title: "ต้องการออกจากระบบ  ใช่หรือไม่?",
			text: "",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "ตกลง",
			cancelButtonText: "ยกเลิก",
			confirmButtonClass: 'btn btn-danger',
			cancelButtonClass: 'btn btn-secondary',
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					url: "logout.php",
					type: "POST",
					closeOnClickOutside: false,
					success: function () {
						swal({
						 	title: 'ออกจากระบบสำเร็จ',
						 	text: 'กรุณารอสักครู่...',
						  	type: "success",
						  	timer: 800,
							showCancelButton: false,
							showConfirmButton: false,
							closeOnClickOutside: false
						},
						function() {
							window.location.href = "login.php";
				        }
					);
					}
				});
			} else {
				swal({
					title: "ยกเลิกการออกจากระบบ",
					text: "กรุณารอสักครู๋...",
					type: 'error',
					timer: 800,
					showCancelButton: false,
					showConfirmButton: false,
					closeOnClickOutside: false
				});
			}
		});
	});
</script>

<?php if(!$menu_navbar_check_error){ ?>
	<section>
		<div class="container">
				<div class="row py-4 px-1">
					<div class="col-md-12" style="color: red">
						<h3 class="mb-20">
							<center style="color: #FF0000"><?=$menu_navbar_text_error;?></center>
						</h3>
					</div>
				</div>
		</div>
	</section>
	<?php include 'footer.php'; ?>
	</body>
</html>
<?php exit;} ?>