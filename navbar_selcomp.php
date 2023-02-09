		<?php
		
			if (!$_SESSION["user_name"]) {  //check session

				Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

			} else {
			
		?>

		<style type="text/css">
			@media (max-width: 767px) {
				ul.top-info-box {
					display: block;
				}
			}
			.page-item.disabled .page-link{
				color:#6c757d;
				pointer-events:none;
				cursor:not-allowed;
				background-color:#fff;
				border-color:#dee2e6;
			}
			label {
				font-weight: 700;
			}
		</style>

		<!-- Header start -->
		<header id="header" class="header-one">
			<div class="bg-blue">
				<div class="container">
					<div class="logo-area">
						<div class="row align-items-center">
							<div class="logo col-xs-4 text-center px-2">
								<a class="d-block" href="">
									<img loading="lazy" src="image/acslogo.png" width="100%" alt="Constra">
								</a>
							</div><!-- logo end -->

							<div class="col-xs-8 ml-auto text-right px-2">
								<ul class="top-info-box">
									<li>
										<div class="info-box">
											<div class="info-box-content">
												<p class="info-box-title">
													<?=$_SESSION["user_firstname"]?> 
													<?=substr($_SESSION["user_surname"], 0, 1)?>.
													&nbsp;&nbsp;&nbsp;
													<a href="logout.php">
														<i class="icofont-power" style="color: #F00;"></i>
													</a>
												</p>
												<p class="info-box-subtitle"></p>
											</div>
										</div>
									</li>
								</ul><!-- Ul end -->
							</div><!-- header right end -->
						</div><!-- logo area end -->
					</div><!-- Row end -->
				</div><!-- Container end -->
			</div>

			<div class="site-navigation">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<nav class="navbar navbar-expand-lg navbar-dark p-0">
								<button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
									<span class="navbar-toggler-icon"></span>
								</button>

								<div id="navbar-collapse" class="collapse navbar-collapse">
									<ul class="nav navbar-nav ml-auto">
										<li class="nav-item">
											<a class="nav-link" href="change_password.php?cid=<?=$cid;?>">เปลี่ยนรหัสผ่าน</a>
										</li>
									</ul>
								</div>

							</nav>
						</div>
					</div>
				</div>
			</div>
			<!--/ Navigation end -->
		</header>
		<!--/ Header end -->

		<?php } ?>