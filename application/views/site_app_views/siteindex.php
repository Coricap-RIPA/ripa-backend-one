<?php  

	$refer 				=  $this->agent->referrer();
    $refer      		= (strlen($refer) >3 AND $refer <> 'https://localhost/') ? $refer : current_url();
    $refer 				= str_replace( site_url(),"", $refer); 
    $refer 				= str_replace( base_url(),"", $refer); 
    $referalFullLink    = $this->agent->referrer();
    $refer_add_card     = current_url();
    $refer_add_card 	= str_replace( site_url(),"", $refer_add_card); 
    $refer_add_card 	= str_replace( base_url(),"", $refer_add_card);
	$compter_panier 	= 0;
	$sous_total 		= 0;

	echo $header;
?>	

<style>
	#anniv{
		font-size: 4rem !important;
	}
	#babyshower{
		font-size: 4rem !important;
	}
</style>

<body>

<div class="page-wraper">

	<div id="loading-area" class="preloader-wrapper-1">
		<div>
			<span class="loader-2"></span>
			<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>logo.png" alt="/">
			<span class="loader"></span>
		</div>
	</div>
	
	<!-- Header -->
	<header class="site-header mo-left header style-2">		
		<!-- Main Header -->
		<div class="header-info-bar">
			<div class="container clearfix">
				<!-- Website Logo -->
				<div class="logo-header logo-dark">
					<a href="<?php echo site_url('AppIndex/siteindex/') ?>"><img src="<?php echo base_url('assets/site_app_assets/images/'); ?>logo.png" alt="logo"></a>
				</div>
				
				<!-- EXTRA NAV -->
				<div class="extra-nav d-md-flex d-none">
					<div class="extra-cell">
						<ul class="navbar-nav header-right">
							<li class="nav-item info-box pe-3 d-xl-flex d-none">
								<div class="nav-link">
									<div class="dz-icon">
										<i class="flaticon flaticon-ship"></i>
									</div>
									<div class="info-content">
										<span>Lubumbashi</span>
										<h6 class="title mb-0">La livraison</h6>
									</div>
								</div>
							</li>
							<li class="nav-item info-box ">
								<div class="nav-link">
									<div class="dz-icon">
										<i class="flaticon flaticon-call-center"></i>
									</div>
									<div class="info-content">
										<span>UN SUPPORT 24/7 </span>
										<h6 class="title mb-0">+243 810 274 370</h6>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				
				<!-- header search nav -->
				<div class="header-search-nav">
					<form class="header-item-search" method="post" action="<?php echo site_url('AppCouple/couple/'); ?>">
						<div class="input-group search-input">
							<select class="default-select">
								<option>Code du couple</option>
							<input  type="number" name="code_couple" class="form-control" aria-label="Code du couple" placeholder="Entrez le code du couple" required>
							<button class="btn" type="submit">
								<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
									<circle cx="10.0535" cy="10.5399" r="7.49047" stroke="#0D775E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M15.2632 16.1387L18.1999 19.0677" stroke="#0D775E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
						</div>
					</form>
				</div>

			</div>
		</div>
		<!-- Main Header End -->
		
		<!-- Main Header -->
		<div class="sticky-header main-bar-wraper navbar-expand-lg">
			<div class="main-bar dark clearfix">
				<div class="container clearfix">
					<!-- Website Logo -->
					<div class="logo-header logo-dark">
						<a href="<?php echo site_url('AppIndex/siteindex/'); ?>"><img src="<?php echo base_url('assets/site_app_assets/images/'); ?>logo.png" alt="logo"></a>
					</div>
					
					<!-- Nav Toggle Button -->
					<button class="navbar-toggler collapsed navicon justify-content-end" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
						<span></span>
						<span></span>
						<span></span>
					</button>
					
					<!-- EXTRA NAV -->
					<div class="extra-nav">
						<div class="extra-cell">							
							<ul class="header-right">
								<li class="nav-item login-link">
									<a class="nav-link" href="<?php echo site_url('AppCouple/login/'); ?>">
										CREEZ VOTRE LISTE ICI
									</a>
								</li>
								<li class="nav-item search-link">
									<a class="nav-link"  href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop">
										<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
											<circle cx="10.0535" cy="10.55" r="7.49047" stroke="var(--white)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M15.2632 16.1487L18.1999 19.0778" stroke="var(--white)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</a>
								</li>
								<li class="nav-item wishlist-link">
									<a class="nav-link" href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
										<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M2.64119 10.4097C1.74702 7.61808 2.79202 4.42724 5.72285 3.48308C7.26452 2.98558 8.96619 3.27891 10.2479 4.24308C11.4604 3.30558 13.2245 2.98891 14.7645 3.48308C17.6954 4.42724 18.747 7.61808 17.8537 10.4097C16.462 14.8347 10.2479 18.2431 10.2479 18.2431C10.2479 18.2431 4.07952 14.8864 2.64119 10.4097Z" stroke="var(--white)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M13.5813 6.32781C14.473 6.61614 15.103 7.41197 15.1788 8.34614" stroke="var(--white)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
										<span class="badge badge-circle">
											<?php 
												if (isset( $userAppData['wishs'] ) AND !empty($userAppData['wishs']) ) {
													echo count( $userAppData['wishs'] );
												}else{
													echo '0';
												}
											?>
										</span>
									</a>
								</li>
								<li class="nav-item cart-link">
									<a href="javascript:void(0);" class="nav-link cart-btn"  data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
										<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M1.08374 2.61947C1.08374 2.27429 1.36356 1.99447 1.70874 1.99447H3.29314C3.91727 1.99447 4.4722 2.39163 4.67352 2.98239L5.06379 4.1276H15.4584C17.6446 4.1276 19.4168 5.89981 19.4168 8.08593V11.5379C19.4168 13.7241 17.6446 15.4963 15.4584 15.4963H9.22182C7.30561 15.4963 5.66457 14.1237 5.32583 12.2377L4.00967 4.90953L3.49034 3.3856C3.46158 3.30121 3.3823 3.24447 3.29314 3.24447H1.70874C1.36356 3.24447 1.08374 2.96465 1.08374 2.61947ZM5.36374 5.3776L6.55614 12.0167C6.78791 13.3072 7.91073 14.2463 9.22182 14.2463H15.4584C16.9542 14.2463 18.1668 13.0337 18.1668 11.5379V8.08593C18.1668 6.59016 16.9542 5.3776 15.4584 5.3776H5.36374Z" fill="var(--white)"/>
											<path fill-rule="evenodd" clip-rule="evenodd" d="M8.16479 17.8278C8.16479 17.1374 8.72444 16.5778 9.4148 16.5778H9.42313C10.1135 16.5778 10.6731 17.1374 10.6731 17.8278C10.6731 18.5182 10.1135 19.0778 9.42313 19.0778H9.4148C8.72444 19.0778 8.16479 18.5182 8.16479 17.8278Z" fill="var(--white)"/>
											<path fill-rule="evenodd" clip-rule="evenodd" d="M14.8315 17.8278C14.8315 17.1374 15.3912 16.5778 16.0815 16.5778H16.0899C16.7802 16.5778 17.3399 17.1374 17.3399 17.8278C17.3399 18.5182 16.7802 19.0778 16.0899 19.0778H16.0815C15.3912 19.0778 14.8315 18.5182 14.8315 17.8278Z" fill="var(--white)"/>
										</svg>
										<span class="badge badge-circle" id="id_total_panier_2">
											<?php 
												if (isset($userAppData['commandes']) AND !empty($userAppData['commandes']) ) {
													echo count( $userAppData['commandes'] );
												}else{
													echo '0';
												}
											?>
										</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
					
					<!-- Main Nav -->
					<div class="header-nav navbar-collapse collapse justify-content-start" id="navbarNavDropdown">
						
						<div class="browse-category-menu">
							<a href="javascript:void(0);" class="category-btn">
								<svg class="me-3" width="21" height="13" viewBox="0 0 21 13" fill="none" xmlns="http://www.w3.org/2000/svg">
									<rect x="0.248047" y="12" width="20" height="1" fill="white"/>
									<rect x="0.248047" width="20" height="1" fill="white"/>
									<rect x="0.248047" y="6" width="20" height="1" fill="white"/>
								</svg>
								<span class="category-btn-title">
									Categories
								</span>
								<span class="toggle-arrow ms-auto">
									<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M6.24805 9L12.248 15L18.248 9" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</span>
							</a>
							<div class="category-menu-items" style="display: none;">
								<ul class="nav navbar-nav">
									<?php 
										if(!empty($categories)){
											foreach ($categories as $key => $categorie) {
												$show_icon_sous_categorie = (!empty($categorie['sous_categories']) AND is_array($categorie['sous_categories']) ) ? '' : 'hidden';
												echo '<li class="cate-drop">
														<a href="'.site_url('AppCategorie/index/'.$categorie['id_categorie'].'/').'">
															<svg class="me-3" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
																<g clip-path="url(#clip0_190_182)">
																<path d="M9.64305 2.11035L9.06095 2.76886L14.1811 7.2949H0.748047V8.17381H14.1811L9.06095 12.6999L9.64305 13.3584L15.748 7.96173V7.50698L9.64305 2.11035Z" fill="#0D775E"/>
																</g>
																<defs>
																<clipPath id="clip0_190_14822">
																<rect width="15" height="15" fill="white" transform="translate(0.748047 0.234375)"/>
																</clipPath>
																</defs>
															</svg>
															<span>'.$categorie['nom_categorie'].'</span>
															<span class="menu-icon" '.$show_icon_sous_categorie.'>
																<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<path d="M6 12L10 8L6 4" stroke="#24262B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
																</svg>
															</span>
														</a>';

												if(!empty($categorie['sous_categories'])){
													echo '<ul class="sub-menu">';
													foreach ($categorie['sous_categories'] as $key_sous_categorie => $sous_categorie) {
														//echo '<li><a href="'.site_url('AppCategorie/filter/'.$categorie['id_categorie'].'/'.$sous_categorie['id_sous_categorie'].'/').'">'.$sous_categorie['nom_sous_categorie'].'</a></li>';
														echo '<li><a href="#">'.$sous_categorie['nom_sous_categorie'].'</a></li>';
													}
													echo '</ul>';
												}
												echo '</li>';
											}
										}
									?>
								</ul>
							</div>
						</div>
						<ul class="nav navbar-nav dark-nav">
							<li class="has-mega-menu">
								<a href="<?php echo site_url('AppSection/filter/1/'); ?>"><span>Mariage</span></a>
							</li>
							<li class="has-mega-menu">
								<a href="<?php echo site_url('AppSection/filter/2/'); ?>"><span>Anniversaire</span></a>
							</li>
							<li class="sub-men">
								<a href="<?php echo site_url('AppSection/filter/3/'); ?>"><span>S<sup>t</sup> Valentin</span></a>
							</li>
							<li><a href="<?php echo site_url('AppSection/filter/4/'); ?>">Baby Shower</a></li>
						</ul>
						<div class="dz-social-icon">
							<ul>
								<li><a class="fab fa-facebook-f" target="_blank" href="javascript:void(0);"></a></li>
								<li><a class="fab fa-twitter" target="_blank" href="javascript:void(0);"></a></li>
								<li><a class="fab fa-linkedin-in" target="_blank" href="https://www.linkedin.com/showcase/3686700/admin/"></a></li>
								<li><a class="fab fa-instagram" target="_blank" href="javascript:void(0);"></a></li>
							</ul>
						</div>

					</div>
				</div>
			</div>
		</div>
		<!-- Main Header End -->
		
		<!-- SearchBar -->
		<div class="dz-search-area dz-offcanvas offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop">
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
				&times;
			</button>
			<div class="container">
				<form class="header-item-search" method="post" action="<?php echo site_url('AppSearch/siteindex/');?>">
					<div class="input-group search-input">
						<select name="id_foreign_categorie" id="id_foreign_categorie" style="width:35%;">
							<option value="toutes">Toutes les Categories</option>
							<?php 
								if(!empty($categories)){
									foreach ($categories as $key => $categorie) {
										if ( isset($_POST['id_foreign_categorie']) AND $categorie['id_categorie'] == $_POST['id_foreign_categorie']) {
											echo '<option value="'.$categorie['id_categorie'].'" selected>'.$categorie['nom_categorie'].'</option>';
										}else{
											echo '<option value="'.$categorie['id_categorie'].'">'.$categorie['nom_categorie'].'</option>';
										}
									}
								}
							?>
						</select>
						<input type="text" name="nom_article" class="form-control" aria-label="Text input with dropdown button" placeholder="Nom du cadeau ici ..." value="<?php if(isset($_POST['nom_article'])) echo $_POST['nom_article'] ?>" style="width:65%;">
						<button class="btn" type="submit">
							<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
								<circle cx="10.0535" cy="10.5399" r="7.49047" stroke="#0D775E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M15.2632 16.1387L18.1999 19.0677" stroke="#0D775E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</button>
					</div>
					<ul class="recent-tag">
						<li class="pe-0"><span>Recherche Rapide :</span></li>
						<li><a href="<?php echo site_url('AppSection/filter/1/'); ?>">Mariage</a></li>
						<li><a href="<?php echo site_url('AppSection/filter/2/'); ?>">Anniverssaire</a></li>
						<li><a href="<?php echo site_url('AppSection/filter/3/'); ?>">S<sup>t</sup> Valentin</a></li>
						<li><a href="<?php echo site_url('AppSection/filter/4/'); ?>">baby Shower</a></li>
					</ul>
				</form>
				<div class="row">
					<div class="col-xl-12">
						<h5 class="mb-3">Vous pouvez aussi aimer : </h5>
						<div class="swiper category-swiper2">
							<div class="swiper-wrapper">
								<?php 
									if(!empty($nineLastArticles)){
										// continue by here pascal 
										foreach ($nineLastArticles as $key => $ninelastarticle) {
											echo '  <div class="swiper-slide">
														<div class="shop-card">
															<div class="dz-media">
																<img src="'.base_url('assets/uploads/files/'.$ninelastarticle['photo_article']).'" alt="image">
															</div>
															<div class="dz-content">
																<h6 class="title"><a href="'.site_url('AppArticle/index/'.$ninelastarticle['id_article'].'/').'">'.$ninelastarticle['nom_article'].'</a></h6>
																<h6 class="price">'.number_format($ninelastarticle['prix_vente'], 0,'.',' ').'$</h6>
															</div>
														</div>
													</div>';
										}
									}
								
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- SearchBar -->
		
		<!-- Sidebar cart -->
		<div class="offcanvas dz-offcanvas offcanvas offcanvas-end " tabindex="-1" id="offcanvasRight">
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
				&times;
			</button>
			<div class="offcanvas-body">
				<div class="product-description">
					<div class="dz-tabs">
						<ul class="nav nav-tabs center" id="myTab" role="tablist">

							<li class="nav-item" role="presentation">
								<button class="nav-link active" id="shopping-cart" data-bs-toggle="tab" data-bs-target="#shopping-cart-pane" type="button" role="tab" aria-controls="shopping-cart-pane" aria-selected="true">Panier
									<span class="badge badge-light" id="id_total_panier">
										<?php 
											if (isset($userAppData['commandes']) AND !empty($userAppData['commandes']) ) {
												echo count( $userAppData['commandes'] );
											}else{
												echo '0';
											}
										?>
									</span>
								</button>
							</li>

							<li class="nav-item" role="presentation">
								<button class="nav-link" id="wishlist" data-bs-toggle="tab" data-bs-target="#wishlist-pane" type="button" role="tab" aria-controls="wishlist-pane" aria-selected="false">Wishlist
									<span class="badge badge-light">
										<?php 
											if (isset( $userAppData['wishs'] ) AND !empty($userAppData['wishs']) ) {
												echo count( $userAppData['wishs'] );
											}else{
												echo '0';
											}
										?>
									</span>
								</button>
							</li>

						</ul>
						<div class="tab-content pt-4" id="dz-shopcart-sidebar">
							<!-- Start By Here loop for order articles -->
							<div class="tab-pane fade show active" id="shopping-cart-pane" role="tabpanel" aria-labelledby="shopping-cart" tabindex="0">
								<div class="shop-sidebar-cart">
									<form action="<?php echo site_url('AppArticle/updatePanier/'); ?>" class="form_update_panier" name="form_update_panier">
										<ul class="sidebar-cart-list" id="list_commandes_container">
											<div class="row" id="list_commandes">
												<?php 
													if( isset($userAppData['commandes']) AND !empty($userAppData['commandes']) ){
														
														foreach ($userAppData['commandes'] as $key => $commande) {
															$compter_panier++;
															$sous_total  = $sous_total + ($commande['prix_vente'] * $commande['quantite']);
															echo '	<li>
																		<div class="cart-widget">
																			<div class="dz-media me-3">
																				<img src="'.base_url('assets/uploads/files/'.$commande['photo_article']).'" alt="">
																			</div>
																			<div class="cart-content">
																				<h6 class="title"><a href="'.site_url('AppArticle/index/'.$commande['id_article']).'">'.$commande['nom_article'].'</a></h6>
																				<div class="d-flex align-items-center">
																					<div class="btn-quantity light quantity-sm me-3">
																						<input type="number" value="'.$commande['quantite'].'" name="quantite_article_'.$compter_panier.'">
																						<input type="number" value="'.$commande['id_article'].'" name="id_article_'.$compter_panier.'" style="display:none;">
																					</div>
																					<h6 class="dz-price text-primary mb-0">'.number_format($commande['prix_vente'], 0, ',',' ').'$</h6>
																				</div>
																			</div>
																			<a class="dz-close" id_article="'.$commande['id_article'].'">
																				<i class="ti-close delete_article" id_article="'.$commande['id_article'].'"></i>
																			</a>
																		</div>
																	</li>';
														}
													}
												?>	
											</div>
											<input type="text"   value="<?php if($refer) echo $refer; ?>" name="refer" hidden>
											<input type="text"   value="<?php if($refer_add_card) echo $refer_add_card; ?>" name="refer_add_card" hidden>
											<input type="text"   value="<?php if($userAppData) echo $userAppData['usertel']; ?>" name="usertel" hidden>
											<input type="text"   value="<?php echo $compter_panier; ?>" name="total_article_panier" hidden>
										</ul>
										<div class="cart-total">
											<h5 class="mb-0">Sous total:</h5>
											<h5 class="mb-0" id="id_sous_total_panier"><?php echo  number_format($sous_total, 0,',', ' ') ?> $</h5>
										</div>
										<div class="mt-auto">
											<div class="shipping-time">													
												<div class="dz-icon">
													<i class="flaticon flaticon-ship"></i>
												</div>
												<div class="shipping-content">
													<h6 class="title pe-4">Nous Livrons à Lubumbashi à partir de 5000 Fc</h6>
													<div class="progress">
														<div class="progress-bar progress-animated border-0" style="width: 100%;" role="progressbar">
															<span class="sr-only">100% Complete</span>
														</div>
													</div>
												</div>
											</div>
											<a href="<?php echo site_url('AppCheckOut/index/'); ?>" class="btn btn-light btn-block m-b20">Commander</a>	
											<button type="submit" class="btn btn-outline-secondary btn-block m-b20">Mettre à jour</button>	
											<a href="<?php echo site_url('AppPanier/index/'); ?>" class="btn btn-secondary btn-block">Voir le Panier</a>	
										</div>	
									</form>
								</div>	
							</div>

							<div class="tab-pane fade" id="wishlist-pane" role="tabpanel" aria-labelledby="wishlist" tabindex="0">
								<div class="shop-sidebar-cart">
									<ul class="sidebar-cart-list">
										<?php
											if( isset($userAppData['wishs']) AND !empty($userAppData['wishs']) ){

												foreach ($userAppData['wishs'] as $key => $wish) {
													echo '	<li>
																<div class="cart-widget">
																	<div class="dz-media me-3">
																		<img src="'.base_url('assets/uploads/files/'.$wish['photo_article']).'" alt="">
																	</div>
																	<div class="cart-content">
																		<h6 class="title"><a href="'.site_url('AppArticle/index/'.$wish['id_article'].'/').'">'.$wish['nom_article'].'</a></h6>
																		<div class="d-flex align-items-center">
																			<h6 class="dz-price text-primary mb-0">'.number_format($wish['prix_vente'],0,',',' ').'</h6>
																		</div>
																	</div>
																	<a href="'.site_url('AppArticle/wishlistdel/'.$wish['id_article'].'/'.str_replace('/','_',$refer).'/').'" class="dz-close">
																		<i class="ti-close"></i>
																	</a>
																</div>
															</li>';
												}
											}
										?>
									</ul>
									<div class="mt-auto">
										<a href="<?php echo site_url('AppArticle/wishlist/'); ?>" class="btn btn-secondary btn-block">Voir mes favories</a>
									</div>	
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Sidebar cart -->

	</header>
	<!-- Header End -->
	
	<div class="page-content bg-white">
	
		<!--Swiper Banner Start -->
		<div class="main-slider style-2"> 
			<div class="main-swiper">
				<div class="swiper-wrapper">
					<div class="swiper-slide bg-light">
						<div class="container">
							<div class="banner-content">
								<div class="row">
									<div class="col-xl-6 col-md-6 col-sm-7 align-self-center">
										<div class="swiper-content">
											<div class="content-info">
												<h1 class="offer-title mb-0" data-swiper-parallax="-20">MARIAGE</h1>
												<h2 class="title mb-2" data-swiper-parallax="-20">Cadeau pour mariage</h2>
												<p class="sub-title mb-0" data-swiper-parallax="-40">Mariés, vous pouvez aidez vous invités à trouver le cadeau qui vous convient. Si vous êtes invités CADEAUMART est le meilleur endroit pour trouver le cadeau qu'il vous faut.</p>												
											</div>
											<div class="content-btn" data-swiper-parallax="-60">
												<a class="btn btn-secondary  me-3" href="https://wa.me/243810274370?text=salut" style="background-color:#0d775e !important;"><i class="fab fa-whatsapp" style="display:inline-block; margin-right: 5px;"></i> WHATSAPP </a>
												<a class="btn btn-outline-secondary " href="<?php echo site_url('AppSection/filter/1/'); ?>">CONNAITRE PLUS</a>
											</div>
										</div>
									</div>
									<div class="col-xl-6 col-md-6 col-sm-5">
										<div class="banner-media">
											<div class="img-preview" data-swiper-parallax="-100">
												<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>main-slider/slider2/pic3.jpeg" alt="banner-media" style="width:1200px; height:750px; object-fit:scale-down !important;">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="swiper-slide bg-light">
						<div class="container">
							<div class="banner-content">
								<div class="row">
									<div class="col-xl-6 col-md-6 col-sm-7 align-self-center">
										<div class="swiper-content">
											<div class="content-info">
												<h1 class="offer-title mb-0 " data-swiper-parallax="-20" id="anniv">ANNIVERSAIRE</h1>
												<h2 class="title mb-2" data-swiper-parallax="-20">Cadeau pour anniversaire</h2>
												<p class="sub-title mb-0" data-swiper-parallax="-40">Les anniversaires c'est chaque jour, vous ne saviez pas  quoi offrir à vos proches ? trouvez sur CADEAUMART la solution toute en un. Carte de voeu, accessoires hommes, accessoires femmes etc ...  </p>												
											</div>
											<div class="content-btn" data-swiper-parallax="-60">
												<a class="btn btn-secondary  me-3" href="https://wa.me/243810274370?text=salut" style="background-color:#0d775e !important;"><i class="fab fa-whatsapp" style="display:inline-block; margin-right: 5px;"></i> WHATSAPP </a>
												<a class="btn btn-outline-secondary " href="<?php echo site_url('AppSection/filter/2/'); ?>">CONNAITRE PLUS</a>
											</div>
										</div>
									</div>
									<div class="col-xl-6 col-md-6 col-sm-5">
										<div class="banner-media">
											<div class="img-preview" data-swiper-parallax="-100">
												<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>main-slider/slider2/pic4.jpeg" alt="banner-media" style="width:1200px; height:750px; object-fit:scale-down !important;">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="swiper-slide bg-light">
						<div class="container">
							<div class="banner-content">
								<div class="row">
									<div class="col-xl-6 col-md-6 col-sm-7 align-self-center">
										<div class="swiper-content">
											<div class="content-info">
												<h1 class="offer-title mb-0" data-swiper-parallax="-20">S<sup>t</suP> VALENTIN </h1>
												<h2 class="title mb-2" data-swiper-parallax="-20">Cadeau pour S<sup>t</sup> la valentin </h2>
												<p class="sub-title mb-0" data-swiper-parallax="-40">Sur CADEAUMART il y a les types de cadeaux pour elle ou pour lui. Les prix sont excessivement abordables, la qualité des produits et sans discutions pour la plaisir de votre bien aimé(e)</p>												
											</div>
											<div class="content-btn" data-swiper-parallax="-60">
												<a class="btn btn-secondary  me-3" href="https://wa.me/243810274370?text=salut" style="background-color:#0d775e !important;"><i class="fab fa-whatsapp" style="display:inline-block; margin-right: 5px;"></i> WHATSAPP </a>
												<a class="btn btn-outline-secondary " href="<?php echo site_url('AppSection/filter/3/'); ?>">CONNAITRE PLUS</a>
											</div>
										</div>
									</div>
									<div class="col-xl-6 col-md-6 col-sm-5">
										<div class="banner-media">
											<div class="img-preview" data-swiper-parallax="-100">
												<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>main-slider/slider2/pic5.jpeg" alt="banner-media" style="width:1200px; height:750px; object-fit:scale-down !important;">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="swiper-slide bg-light">
						<div class="container">
							<div class="banner-content">
								<div class="row">
									<div class="col-xl-6 col-md-6 col-sm-7 align-self-center">
										<div class="swiper-content">
											<div class="content-info">
												<h1 class="offer-title mb-0" data-swiper-parallax="-20" id="babyshower">BABY SHOWER</h1>
												<h2 class="title mb-2" data-swiper-parallax="-20">Cadeau pour les baby Shower</h2>
												<p class="sub-title mb-0" data-swiper-parallax="-40"> Les boutchous ne sont pas oubliés sur CADEAUMART, sur une variété d'article pour bébé faites votre sélection pour votre neveu, nièce, cousin, cousine et communiquez votre joie. </p>												
											</div>
											<div class="content-btn" data-swiper-parallax="-60">
												<a class="btn btn-secondary  me-3" href="https://wa.me/243810274370?text=salut" style="background-color:#0d775e !important;"><i class="fab fa-whatsapp" style="display:inline-block; margin-right: 5px;"></i> WHATSAPP </a>
												<a class="btn btn-outline-secondary " href="<?php echo site_url('AppSection/filter/4/'); ?>">CONNAITRE PLUS</a>
											</div>
										</div>
									</div>
									<div class="col-xl-6 col-md-6 col-sm-5">
										<div class="banner-media">
											<div class="img-preview" data-swiper-parallax="-100">
												<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>main-slider/slider2/pic6.jpeg" alt="banner-media" style="width:1200px; height:750px; object-fit:scale-down !important;">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="banner-social-media style-2 left">
					<ul>
						<li>
							<a href="javascript:void(0);" target="_blank">Instagram</a>
						</li>
						<li>
							<a href="javascript:void(0);" target="_blank">Facebook</a>
						</li>
						<li>
							<a href="javascript:void(0);" target="_blank">TikTok</a>
						</li>
					</ul>
				</div>
				<a href="https://wa.me/243810274370?text=salut" target="_blank" class="service-btn btn-dark fab fa-whatsapp" style="background-color:#0d775e !important;"> Whatsapp </a>
			</div>
		</div>		
		<!--Swiper Banner End-->
		
		<!-- Product Start-->
		<section class="content-inner-1 py-0 overlay-white-middle">
			<div class="container-fluid p-3">
				<div class="swiper swiper-product">
					<div class="swiper-wrapper product-style2">
						<?php 
							if(!empty($foursLastArticles)){
								foreach ($foursLastArticles as $key => $fourlastarticle) {
									echo '<div class="swiper-slide">
											<div class="product-box style-2 wow fadeInUp" data-wow-delay="0.1s" style="background-image: url(\' '.base_url('assets/uploads/files/').$fourlastarticle['photo_article'].' \');">
												<div class="product-content">
													<div class="main-content">
														<h2 class="product-name" style=""></h2>
														<span class="offer"></span>
													</div>
													<br><br><br>
													<form class="form_ajout_panier" method="POST"  name="form_add_article_' . $fourlastarticle['id_article'] . '" style="width: 50%;"> 
														<input type="number" value="1" name="quantite" hidden>
														<input type="number" value="' . $fourlastarticle['id_article'] . '" name="id_article" hidden>
														<input type="text"   value="' . $refer . '" name="refer" hidden>
														<input type="text"   value="'.$refer_add_card.'" name="refer_add_card" hidden>
														<input type="text"   value="' . $userAppData['usertel'] . '" name="usertel" hidden>                                                                                               
														<button class="btn btn-secondary"  style="width: 100%; padding-left: 2%; padding-right: 2%;"  id="ajout-panier" type="submit">Ajouter au Panier</button>
													</form>
													<a class="btn btn-secondary  me-3" href="https://wa.me/243810274370?text=salut" style="width: 50%; padding-left: 2%; padding-right: 2%; background-color:#0d775e !important; border-color: #0d775e;"><i class="fab fa-whatsapp" style="display:inline-block; margin-right: 5px;"></i> WHATSAPP </a>
												</div>
											</div>
										</div>';
								}
							}
						?>
					</div>
				</div>
			</div>
		</section>
		<!-- Product End-->

		<!-- Section recherche Par Code copuple -->
		<section class="content-inner overlay-white-middle">
			<div class="container">
				<div class="section-head style-2 wow fadeInUp" data-wow-delay="0.1s">
					<div class="left-content">
						<h2 class="title">Trouvez les cadeaux d'un couple ici</h2>
						<p>C'est simple introduisez le code du couple ici</p>
					</div>			
				</div>
				<div class="row gx-xl-4 g-3">
					<form method="post" action="<?php echo site_url('AppCouple/couple/'); ?>" style="margin-left:0 !important;">
						<div class="form-group">
							<label class="label-title">Couple Du couple *</label>
							<input  type="number" name="code_couple" class="full-width form-control" aria-label="Code du couple" placeholder="Entrez le code du couple" required style="width: 100%;"><br>
							<button type="submit"  class="btn btn-secondary w-50">RECHERCHER</button>
						</div>
					</form>
				</div>
			</div>
		</section>
		<!-- End Section recherche Par Code copuple -->	
			
		<!--Six recents cadeau Section Start-->
		<section class="content-inner overlay-white-middle">
			<div class="container">
				<div class="section-head style-2 wow fadeInUp" data-wow-delay="0.1s">
					<div class="left-content">
						<h2 class="title">Cadeaux récemments ajoutés</h2>
						<p>Découvrez les cadeaux les plus tendances de CadeauMart.</p>
					</div>
					<a href="<?php echo site_url('AppArticle/all/'); ?>" class="text-secondary font-14 d-flex align-items-center gap-1">Voir tous les produits 
						<i class="icon feather icon-chevron-right font-18"></i>
					</a>			
				</div>
				<div class="row gx-xl-4 g-3">
					<?php 
						if(!empty($sixLastArticles)){
							foreach ($sixLastArticles as $key => $sixlastarticle) {
								echo ' <div class="col-xl-4 col-lg-4 col-md-4 col-6 wow fadeInUp" data-wow-delay="0.2s">
											<div class="category-product left">
												<a href="'.site_url('AppArticle/index/'.$sixlastarticle['id_article'].'/').'">								
													<img src="'.base_url('assets/uploads/files/'.$sixlastarticle['photo_article']).'" alt="">
													<div class="category-badge">'.$sixlastarticle['nom_article'].'</div>
												</a>
											</div>
										</div>';
							}
						}
					?>
				</div>
			</div>
		</section>	
		<!--Six recents cadeau Section End-->
		
		<!-- icon-box1 -->
		<section class="content-inner-3 overlay-white-dark" style="background-image: url('<?php echo base_url('assets/site_app_assets/images/'); ?>background/bg1.jpg'); background-repeat: no-repeat; background-size: cover;">
			<div class="container">
				<div class="row justify-content-center gx-sm-1">
					<div class="col-lg-4 col-md-4 col-sm-4 p-b30 wow fadeInUp" data-wow-delay="0.1s">
						<div class="icon-bx-wraper style-1 text-center">
							<div class="icon-bx">
								<i class="flaticon flaticon-fast-delivery"></i>
							</div>
							<div class="icon-content">
								<h3 class="dz-title m-b0">LIVRAISON A LUBUMBASHI</h3>
								<div class="square"></div>
								<p class="font-20">
									Après Votre Commande, Votre Cadeau Peut Être Emballê Et Livré Au Lieu De Votre Choix.La Livraison En Fonctions De La Distance Et L'emballage En Fonction Du Type Et de la Grandeur Du Cadeau.
								</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 p-b30 wow fadeInUp" data-wow-delay="0.2s">
						<div class="icon-bx-wraper style-1 text-center">
							<div class="icon-bx">
								<i class="flaticon flaticon-message"></i>
							</div>
							<div class="icon-content">
								<h3 class="dz-title m-b0">24/7 SUPPORT</h3>
								<div class="square"></div>
								<p class="font-20">Le service client vous donnera une assistance de qualité pour toutes préocupations.</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 p-b30 wow fadeInUp" data-wow-delay="0.3s">
						<div class="icon-bx-wraper style-1 text-center">
							<div class="icon-bx">
								<i class="flaticon flaticon-money-back-guarantee"></i>
							</div>
							<div class="icon-content">
								<h3 class="dz-title m-b0">PAIEMENTS</h3>
								<div class="square"></div>
								<p class="font-20">Nous acceptons le paiement à distance M-pesa, Airtel Money, Orange Money, Vous êtes rembourssé si le cadeau n'est pas celui dont vous avez commandé.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- icon-box1 End-->
		
		<!-- Recents cadeaux Mariage Start-->
		<section class="content-inner-1 overlay-white-middle overflow-hidden">
			<div class="container">
				<div class="section-head style-2 wow fadeInUp" data-wow-delay="0.1s">
					<div class="left-content">
						<h2 class="title">Récents cadeaux pour Mariages</h2>
						<p>Découvrez les récents cadeaux pour une fête de Mariage</p>
					</div>
					<a href="<?php echo site_url('AppSection/filter/1/'); ?>" class="text-secondary font-14 d-flex align-items-center gap-1">Voir plus 
						<i class="icon feather icon-chevron-right font-18"></i>
					</a>			
				</div>
				<div class="swiper-btn-center-lr">
					<div class="swiper swiper-four">
						<div class="swiper-wrapper">
							<?php 
								if( !empty($fourLastMariageArticles) ){
									foreach ($fourLastMariageArticles as $key => $fourlastmariagearticle) {
										echo '	<div class="swiper-slide">
													<div class="shop-card wow fadeInUp" data-wow-delay="0.2s">
														<div class="dz-media">
															<img src="'.base_url('assets/uploads/files/'.$fourlastmariagearticle['photo_article']).'" alt="image">
															<div class="shop-meta">
																<a href="'.site_url('AppArticle/index/'.$fourlastmariagearticle['id_article'].'/').'" class="btn btn-secondary btn-icon" data-bs-toggle="modal" data-bs-target="#exampleModal">
																	<i class="fa-solid fa-eye"></i>
																	<span class="d-md-block d-none">Voir</span>
																</a>
																<a href="https://wa.me/243810274370?text='.$fourlastmariagearticle['nom_article'].'" class="btn btn-secondary btn-icon" style="background-color:#0d775e !important; border-color:#0d775e !important; ">
																	<i class="fab fa-whatsapp"></i>
																	<span class="d-md-block d-none">Whatsapp</span>
																</a>
															</div>
														</div>
														<div class="dz-content">
															<h5 class="title"><a href="'.site_url('AppArticle/index/'.$fourlastmariagearticle['id_article'].'/').'">'.$fourlastmariagearticle['nom_article'].'</a></h5>
															<ul class="star-rating">
																<li>
																	<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M7.24805 0.734375L9.22301 5.01608L13.9054 5.57126L10.4436 8.77267L11.3625 13.3975L7.24805 11.0944L3.13355 13.3975L4.0525 8.77267L0.590651 5.57126L5.27309 5.01608L7.24805 0.734375Z" fill="#FF8A00"></path>
																	</svg>
																</li>
																<li>
																	<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M7.24805 0.734375L9.22301 5.01608L13.9054 5.57126L10.4436 8.77267L11.3625 13.3975L7.24805 11.0944L3.13355 13.3975L4.0525 8.77267L0.590651 5.57126L5.27309 5.01608L7.24805 0.734375Z" fill="#FF8A00"></path>
																	</svg>
																</li>
																<li>
																	<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M7.24805 0.734375L9.22301 5.01608L13.9054 5.57126L10.4436 8.77267L11.3625 13.3975L7.24805 11.0944L3.13355 13.3975L4.0525 8.77267L0.590651 5.57126L5.27309 5.01608L7.24805 0.734375Z" fill="#FF8A00"></path>
																	</svg>
																</li>
																<li>
																	<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M7.24805 0.734375L9.22301 5.01608L13.9054 5.57126L10.4436 8.77267L11.3625 13.3975L7.24805 11.0944L3.13355 13.3975L4.0525 8.77267L0.590651 5.57126L5.27309 5.01608L7.24805 0.734375Z" fill="#FF8A00"></path>
																	</svg>
																</li>
																<li>
																	<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M7.24805 0.734375L9.22301 5.01608L13.9054 5.57126L10.4436 8.77267L11.3625 13.3975L7.24805 11.0944L3.13355 13.3975L4.0525 8.77267L0.590651 5.57126L5.27309 5.01608L7.24805 0.734375Z" fill="#E4E5E8"></path>
																	</svg>
																</li>
															</ul>
															<h6 class="price">
																<del>'.number_format($fourlastmariagearticle['prix_vente']+10,0,',',' ').'$</del>
																'.number_format($fourlastmariagearticle['prix_vente'],0,',',' ').'$
															</h6>
															<form class="form_ajout_panier" method="POST"  name="form_add_article_' . $fourlastmariagearticle['id_article'] . '" style="width: 100%;"> 
																<input type="number" value="1" name="quantite" hidden>
																<input type="number" value="' . $fourlastmariagearticle['id_article'] . '" name="id_article" hidden>
																<input type="text"   value="' . $refer . '" name="refer" hidden>
																<input type="text"   value="'.$refer_add_card.'" name="refer_add_card" hidden>
																<input type="text"   value="' . $userAppData['usertel'] . '" name="usertel" hidden>                                                                                               
																<button class="btn btn-secondary"  style="width: 100%; height:40px; padding-left: 2%; padding-right: 2%;"  id="ajout-panier" type="submit">Ajouter au Panier</button>
															</form>
														</div>
														<div class="product-tag">
															<span class="badge badge-secondary">En Vente</span>
															<span class="badge badge-primary">Pour Mariage</span>
														</div>
													</div>
												</div>';
									}
								}
							?>
						</div>
					</div>
					<div class="pagination-align">
						<div class="tranding-button-prev btn-prev">
							<i class="flaticon flaticon-left-chevron"></i>
						</div>
						<div class="tranding-button-next btn-next">
							<i class="flaticon flaticon-chevron"></i>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Recents cadeaux Mariage Stop-->
		
		<!-- Video Bx Start-->
		<section class="content-inner overlay-black-light" style="background-image: url('<?php echo base_url('assets/site_app_assets/images/'); ?>background/bg8.jpg'); background-repeat: no-repeat; background-size: cover;">
			<div class="container">
				<div class="row align-items-center dz-content-bx style-1">
					<div class="col-xl-6 col-lg-6 m-b30 wow fadeInLeft" data-wow-delay="0.1s">
						<div class="dz-media video-bx1 dz-img-overlay1">
							<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>video-img-mariage.png" alt="image">
							<a href="https://www.youtube.com/watch?v=IT94xC35u6k" class="popup-youtube video-btn">
								<svg width="26" height="34" viewBox="0 0 26 34" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M0.709318 2.07991C0.703817 0.454314 2.53706 -0.498418 3.86423 0.440304L24.3738 14.947C25.4952 15.7401 25.5017 17.4016 24.3866 18.2035L3.97574 32.8813C2.65517 33.8309 0.813587 32.8908 0.808083 31.2643L0.709318 2.07991Z" fill="white"/>
								</svg>
							</a>
						</div>
					</div>
					<div class="col-xl-6 col-lg-6 m-b30 wow fadeInRight" data-wow-delay="0.2s">
						<div class="inner-content">
							<div class="section-head">
								<h2 class="title">Mariés, inscrivez vos cadeaux pour vos invités</h2>
								<p class="max-w500">Faites votre mariage différenment, suivez la vidéo et enregsitrez votre liste des cadeaux que vous enverez aux invités pour votre soirée. </p>
							</div>
							<a href="<?php echo site_url('AppCouple/index/'); ?>" class="btn text-uppercase btn-outline-light me-3">Je crée ma liste</a>
							<a href="https://wa.me/243810274370?text=salut" class="btn text-uppercase btn-outline-light" style="background-color:#0d775e !important; border-color:#0d775e;"> <i class="fab fa-whatsapp" style="display:inline-block; margin-right:3px;"></i> Je le fais sur whatsapp</a>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Video Bx End-->
		
		<!-- Recents cadeaux anniversaires Section Start-->
		<section class="content-inner-1">
			<div class="container">
				<div class="row">
					<div class="col-xl-3">
						<div class="row">
							<?php 
								if(!empty($twoLastPublicites)){
									foreach ($twoLastPublicites as $key => $lastpublicite) {
										echo ' <div class="col-xl-12 col-md-6 col-sm-6 wow fadeInLeft" data-wow-delay="0.1s">
													<div class="offer-card text-center">
														<div class="dz-media">
															<img src="'.base_url('assets/uploads/files/'.$lastpublicite['image_publicite']).'" alt="" style="width:451px; height:600px; object-fit: cover;">
														</div>
														<div class="offer-content">
															<h3 class="title">'.$lastpublicite['nom_publicite'].'</h3>
															<span class="offer">Livraison et emballage de qualité </span>
															<a href="'.$lastpublicite['lien_publicite'].'" class="btn btn-secondary">Voir plus</a>
														</div>
													</div>
												</div>
										';
									}
								}
							?>
						</div>
					</div>
					<div class="col-xl-9">
						<div class="wow fadeInUp" data-wow-delay="0.3s">
							<h3 class="title">Récents cadeaux pour Anniversaires</h3>
							<div class="site-filters clearfix d-flex align-items-center">
								<ul class="filters" data-bs-toggle="buttons" hidden>
									<li class="btn active">
										<input type="radio">
										<a href="javascript:void(0);">Tout les cadeaux d'anniversaires <span>(20)</span></a> 
									</li>
									<li data-filter=".Bottle" class="btn">
										<input type="radio">
										<a href="javascript:void(0);">Bottle <span>(10)</span></a> 
									</li>
									<li data-filter=".Begs" class="btn">
										<input type="radio">
										<a href="javascript:void(0);">Begs <span>(02)</span></a> 
									</li>
									<li data-filter=".Toothbrushes" class="btn">
										<input type="radio">
										<a href="javascript:void(0);">Toothbrushes <span>(08)</span></a> 
									</li>
								</ul>
								<a href="<?php echo site_url('AppSection/filter/2/'); ?>" class="product-link text-secondary font-14 d-flex align-items-center gap-1 text-nowrap">Tout les cadeaux d'anniversaires
									<i class="icon feather icon-chevron-right font-18"></i>
								</a>
							</div>
						</div>
						<div class="clearfix">
							<ul id="masonry" class="row g-xl-4 g-3">
								<?php 
									if( !empty($sixLastAnniverssaireArticles) ){
										foreach ($sixLastAnniverssaireArticles as $key => $sixlastAnniverssairearticle) {
											echo '  <li class="card-container col-6 col-xl-4 col-lg-4 col-md-4 col-sm-6 Begs">
														<div class="shop-card">
															<div class="dz-media">
																<img src="'.base_url('assets/uploads/files/'.$sixlastAnniverssairearticle['photo_article']).'" alt="image">
																<div class="shop-meta">
																	<a href="'.site_url('AppArticle/index/'.$sixlastAnniverssairearticle['id_article'].'/').'" class="btn btn-secondary btn-icon">
																		<i class="fa-solid fa-eye"></i>
																		<span class="d-md-block d-none">Voir</span>
																	</a>
																	<a href="https://wa.me/243810274370?text=salut" class="btn btn-secondary btn-icon" style="background-color:#0d775e !important; border-color:#0d775e !important; ">
																		<i class="fab fa-whatsapp"></i>
																		<span class="d-md-block d-none">Whatsapp</span>
																	</a>
																</div>	
															</div>
															<div class="dz-content">
																<h5 class="title"><a href="'.site_url('AppArticle/index/'.$sixlastAnniverssairearticle['id_article'].'/').'">'.$sixlastAnniverssairearticle['nom_article'].'</a></h5>
																<ul class="star-rating">
																	<li>
																		<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path d="M7.24805 0.734375L9.22301 5.01608L13.9054 5.57126L10.4436 8.77267L11.3625 13.3975L7.24805 11.0944L3.13355 13.3975L4.0525 8.77267L0.590651 5.57126L5.27309 5.01608L7.24805 0.734375Z" fill="#FF8A00"/>
																		</svg>
																	</li>
																	<li>
																		<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path d="M7.24805 0.734375L9.22301 5.01608L13.9054 5.57126L10.4436 8.77267L11.3625 13.3975L7.24805 11.0944L3.13355 13.3975L4.0525 8.77267L0.590651 5.57126L5.27309 5.01608L7.24805 0.734375Z" fill="#FF8A00"/>
																		</svg>
																	</li>
																	<li>
																		<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path d="M7.24805 0.734375L9.22301 5.01608L13.9054 5.57126L10.4436 8.77267L11.3625 13.3975L7.24805 11.0944L3.13355 13.3975L4.0525 8.77267L0.590651 5.57126L5.27309 5.01608L7.24805 0.734375Z" fill="#FF8A00"/>
																		</svg>
																	</li>
																	<li>
																		<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path d="M7.24805 0.734375L9.22301 5.01608L13.9054 5.57126L10.4436 8.77267L11.3625 13.3975L7.24805 11.0944L3.13355 13.3975L4.0525 8.77267L0.590651 5.57126L5.27309 5.01608L7.24805 0.734375Z" fill="#FF8A00"/>
																		</svg>
																	</li>
																	<li>
																		<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path d="M7.24805 0.734375L9.22301 5.01608L13.9054 5.57126L10.4436 8.77267L11.3625 13.3975L7.24805 11.0944L3.13355 13.3975L4.0525 8.77267L0.590651 5.57126L5.27309 5.01608L7.24805 0.734375Z" fill="#E4E5E8"/>
																		</svg>
																	</li>
																</ul>
																<h6 class="price">
																	<del>'.number_format($sixlastAnniverssairearticle['prix_vente']+10,0,',',' ').'$</del>
																	'.number_format($sixlastAnniverssairearticle['prix_vente'],0,',',' ').'$
																</h6>
																<form class="form_ajout_panier" method="POST"  name="form_add_article_' . $sixlastAnniverssairearticle['id_article'] . '" style="width: 100%;"> 
																	<input type="number" value="1" name="quantite" hidden>
																	<input type="number" value="' . $sixlastAnniverssairearticle['id_article'] . '" name="id_article" hidden>
																	<input type="text"   value="' . $refer . '" name="refer" hidden>
																	<input type="text"   value="'.$refer_add_card.'" name="refer_add_card" hidden>
																	<input type="text"   value="' . $userAppData['usertel'] . '" name="usertel" hidden>                                                                                               
																	<button class="btn btn-secondary"  style="width: 100%; height:40px; padding-left: 2%; padding-right: 2%;"  id="ajout-panier" type="submit">Ajouter au Panier</button>
																</form>
															</div>
															<div class="product-tag">
															<span class="badge badge-secondary">En Vente</span>
															<span class="badge badge-primary">Pour Anniverssaire</span>
															</div>
														</div>
													</li>';
										}
									}
								?>
							</ul>
						</div>	
					</div>
				</div>
			</div>
		</section>
		<!-- Recents cadeaux anniversaires Section End-->
		
				
		<!-- Feature Box -->
		<div class="content-inner py-0 overlay-white-middle">
			<div class="container-fluid px-0">
				<div class="row gx-0">
					<div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-4 wow fadeIn" data-wow-delay="0.1s">
						<div class="insta-post dz-media dz-img-effect rotate">
							<a href="javascript:void(0);">
								<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>feature/pic1.jpeg" alt="">
							</a>
						</div>
					</div>
					<div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-4 wow fadeIn" data-wow-delay="0.2s">
						<div class="insta-post dz-media dz-img-effect rotate">
							<a href="javascript:void(0);">
								<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>feature/pic2.jpeg" alt="">
							</a>
						</div>
					</div>
					<div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-4 wow fadeIn" data-wow-delay="0.3s">
						<div class="insta-post dz-media dz-img-effect rotate">
							<a href="javascript:void(0);">
								<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>feature/pic3.jpeg" alt="">
							</a>
						</div>
					</div>
					<div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-4 wow fadeIn" data-wow-delay="0.4s">
						<div class="insta-post dz-media dz-img-effect rotate">
							<a href="javascript:void(0);">
								<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>feature/pic4.jpeg" alt="">
							</a>	
						</div>
					</div>
					<div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-4 wow fadeIn" data-wow-delay="0.5s">
						<div class="insta-post dz-media dz-img-effect rotate">
							<a href="javascript:void(0);">
								<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>feature/pic5.jpeg" alt="">
							</a>
						</div>
					</div>
					<div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-4 wow fadeIn" data-wow-delay="0.6s">
						<div class="insta-post dz-media dz-img-effect rotate">
							<a href="javascript:void(0);">
								<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>feature/pic7.jpeg" alt="">
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Feature Box End -->
		
		<!-- Icon Box Start -->
		<section class="content-inner py-0">
			<div class="container-fluid px-0">
				<div class="row gx-0">
					<div class="col-xl-3 col-lg-3 col-sm-6">
						<div class="icon-bx-wraper style-2 bg-light wow fadeInUp" data-wow-delay="0.1s">
							<div class="icon-bx">
								<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>svg/icon-bx/password-check.svg" alt="">
							</div>
							<div class="icon-content">
								<h5 class="dz-title">Rechercher & Filtrer</h5>
								<p>CadeauMart est un écosytème des cadeaux pour tout type d'évènement, Recherchez, filtrez et trouvez le cadeau qu'il vous faut </p>
							</div>
							<div class="data-text">01</div>
						</div>	
					</div>
					<div class="col-xl-3 col-lg-3 col-sm-6">
						<div class="icon-bx-wraper style-2 wow fadeInUp" data-wow-delay="0.2s">
							<div class="icon-bx">
								<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>svg/icon-bx/cart.svg" alt="">
							</div>
							<div class="icon-content">
								<h5 class="dz-title">Passer votre commande</h5>
								<p>Une fois votre cadeau trouvé, ajoutez le au panier ou commandez directement sur notre whatsapp</p>
							</div>
							<div class="data-text">02</div>
						</div>	
					</div>
					<div class="col-xl-3 col-lg-3 col-sm-6">
						<div class="icon-bx-wraper style-2 bg-light wow fadeInUp" data-wow-delay="0.3s">
							<div class="icon-bx">
								<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>svg/icon-bx/discovery.svg" alt="">
							</div>
							<div class="icon-content">
								<h5 class="dz-title">Livraison</h5>
								<p>Votre commande passé, CadeauMart emballera votre cadeau et le livrera votre cadeau à L'adresse de votre choix</p>
							</div>
							<div class="data-text">03</div>
						</div>	
					</div>
					<div class="col-xl-3 col-lg-3 col-sm-6">
						<div class="icon-bx-wraper style-2 wow fadeInUp" data-wow-delay="0.4s">
							<div class="icon-bx">
								<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>svg/icon-bx/box-tick.svg" alt="">
							</div>
							<div class="icon-content">
								<h5 class="dz-title">Donnez de la joie par votre cadeau</h5>
								<p>Regardez enfin votre Cadeau procurez la joie à qui vous l'avez offert.</p>
							</div>
							<div class="data-text">04</div>
						</div>	
					</div>
				</div>
			</div>
		</section>
		<!-- Icon Box End -->
	</div>
	
	<?php echo $footersection; ?>

</div>
<?php echo $footer; ?>

<script>
	$(function(){

		function showSnackBar(message,duration) {
			Snackbar.show(
				{
					text        : message,
					actionText  : 'OK',
					pos         : 'bottom-left',
					duration    : duration
				}
			);
		}


		function sendFormApp() {

			$("form").each(function (index, element) {
				$(element).submit(function (e) { 

					var classForm   = $(element).attr('class');
					var nameForm    = $(element).attr('name');
					var form        = document.forms.namedItem(nameForm);
					var form_data   = {};
					
					if(classForm == 'form_ajout_panier'){

						e.preventDefault();
						showSnackBar("Ajout en cours ... ",5000);

						form_data = {
							'quantite'      : $(form).find("[name='quantite']").val(),
							'id_article'    : $(form).find("[name='id_article']").val(),
							'refer'         : $(form).find("[name='refer']").val(),
							'refer_add_card': $(form).find("[name='refer_add_card']").val(),
							'usertel'       : $(form).find("[name='usertel']").val(),
						};

						$.ajax({
							type: "POST",
							url: '<?php echo site_url('AppArticle/panier/'); ?>',
							data:  form_data,
							success: function (response) {
								//console.log(response);
								if (/__OK/.test(response)) {
									showSnackBar("Article bien ajouté au panier",5000);	
									refreshPanier(response);
								}else{
									console.log(response);
								}
							},
							error: function (response) { 
								showSnackBar("Une erreur est survenue , Contactez CadeauMart ou Actualisez la page ",5000);
							}
						});

					}

					if (classForm == 'form_update_panier') {

						e.preventDefault();
						showSnackBar("Mises à jour du panier  ... ",5000);
						var formData = new FormData(form);

						$.ajax({
							type: "POST",
							processData: false,
							contentType: false,
							url: '<?php echo site_url('AppArticle/updatePanier/'); ?>',
							data:  formData,
							success: function (response) {
								if (/__OK/.test(response)) {
									let tab_data_panier = response.split('__');
									$("#id_sous_total_panier").text(tab_data_panier[2].toLocaleString()+' $');
									showSnackBar("Mises à jour bien éffectuée ... ",5000);	
								}else{
									console.log(response);
								}
							},
							error: function (response) { 
								showSnackBar("Une erreur est survenue , Contactez CadeauMart ou Actualisez la page ",5000);
							}
						});

					}

				}); 
			});

		}
		sendFormApp();


		function deleteArticleFromCommande() { 

			$(".delete_article").each(function (index, element) {
				$(element).click(function (e) { 
					e.preventDefault();
					showSnackBar("Supression en cours ... ",5000);
					$.ajax({
						type: "post",
						url: "<?php echo site_url('AppArticle/panier/'); ?>"+$(element).attr('id_article')+"/",
						data: {'id_user_tel' : <?php echo $userAppData['usertel']; ?>},
						success: function (response) {
							if (/__OK/.test(response)) {
								showSnackBar("Suppression bien effectué ",5000);	
								let tab_data_panier = response.split("__");
								$("#id_total_panier").text(tab_data_panier[2]);
								$("#id_total_panier_2").text(tab_data_panier[2]);
								$("#id_sous_total_panier").text(tab_data_panier[3].toLocaleString()+' $');
								$(element).parent().parent().parent().remove();
							}
						}
					});
				});
				
			});

		}
		deleteArticleFromCommande();


		function refreshPanier(data_panier) {

			let tab_data_panier = data_panier.split('__');
			$("#id_total_panier").text(tab_data_panier[2]);
			$("#id_total_panier_2").text(tab_data_panier[2]);
			$("input[name='total_article_panier']").val(tab_data_panier[3]);
			$("#id_sous_total_panier").text(tab_data_panier[4].toLocaleString()+' $');
			$("#list_commandes").html(tab_data_panier[5]);
			deleteArticleFromCommande();

		}

	});
</script>