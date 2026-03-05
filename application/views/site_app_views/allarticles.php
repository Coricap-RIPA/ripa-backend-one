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
	.lds-circle {
	display: inline-block;
	transform: translateZ(1px);
	}
	.lds-circle > div {
	display: inline-block;
	width: 64px;
	height: 64px;
	margin: 8px;
	border-radius: 50%;
	background: #f31173;
	animation: lds-circle 2.4s cubic-bezier(0, 0.2, 0.8, 1) infinite;
	}
	@keyframes lds-circle {
	0%, 100% {
		animation-timing-function: cubic-bezier(0.5, 0, 1, 0.5);
	}
	0% {
		transform: rotateY(0deg);
	}
	50% {
		transform: rotateY(1800deg);
		animation-timing-function: cubic-bezier(0, 0.5, 0.5, 1);
	}
	100% {
		transform: rotateY(3600deg);
	}
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
	<header class="site-header mo-left header header-transparent">			
		<!-- Main Header -->
		<div class="sticky-header main-bar-wraper navbar-expand-lg">
			<div class="main-bar clearfix">
				<div class="container-fluid clearfix">
					<!-- Website Logo -->
					<div class="logo-header logo-dark me-md-5">
						<a href="<?php echo site_url('AppIndex'); ?>"><img src="<?php echo base_url('assets/site_app_assets/images/'); ?>logo.png" alt="logo"></a>
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
										COMPTE
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
						<div class="logo-header">
							<a href="<?php echo site_url('AppIndex'); ?>"><img src="<?php echo base_url('assets/site_app_assets/images/'); ?>logo.png" alt="/"></a>
						</div>
						<ul class="nav navbar-nav dark-nav">
							<li class="has-mega-menu">
								<a href="<?php echo site_url('AppIndex/siteindex/'); ?>"><span>Acceuille</span></a>
							</li>
							<li class="has-mega-menu">
								<a href="<?php echo site_url('AppSection/filter/1/'); ?>"><span>Mariage</span></a>
							</li>
							<li class="has-mega-menu">
                                <a href="<?php echo site_url('AppSection/filter/2/'); ?>"><span>Anniversaire</span></a>
							</li>
							<li class="sub-menu">
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
															$sous_total  =$sous_total + $commande['prix_vente'];
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
	
	<div class="page-content">
		<!--banner-->
		<div class="dz-bnr-inr style-1" style="background-image:url(images/background/bg-shape.jpg);">
			<div class="container">
				<div class="dz-bnr-inr-entry">
					<h1>Tous les cadeaux</h1>
					<nav aria-label="breadcrumb" class="breadcrumb-row">
						<ul class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('AppIndex/sinteindex/'); ?>"> Accueille</a></li>
							<li class="breadcrumb-item">Tous les cadeaux</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
		
		<section class="content-inner-1 pt-3 z-index-unset">
			<div class="container-fluid">
				<div class="row">

					<div class="col-20 col-xl-3">
						<div class="sticky-xl-top">
							<a href="javascript:void(0);" class="panel-close-btn">																	
								<svg width="35" height="35" viewBox="0 0 51 50" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M37.748 12.5L12.748 37.5" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M12.748 12.5L37.748 37.5" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</a>
							<div class="shop-filter mt-xl-2 mt-0">
								<aside>
									<div class="d-flex align-items-center justify-content-between m-b30">
										<h6 class="title mb-0 fw-normal">
											<svg class="me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25" width="20" height="20"><g id="Layer_29" data-name="Layer 29"><path d="M2.54,5H15v.5A1.5,1.5,0,0,0,16.5,7h2A1.5,1.5,0,0,0,20,5.5V5h2.33a.5.5,0,0,0,0-1H20V3.5A1.5,1.5,0,0,0,18.5,2h-2A1.5,1.5,0,0,0,15,3.5V4H2.54a.5.5,0,0,0,0,1ZM16,3.5a.5.5,0,0,1,.5-.5h2a.5.5,0,0,1,.5.5v2a.5.5,0,0,1-.5.5h-2a.5.5,0,0,1-.5-.5Z"></path><path d="M22.4,20H18v-.5A1.5,1.5,0,0,0,16.5,18h-2A1.5,1.5,0,0,0,13,19.5V20H2.55a.5.5,0,0,0,0,1H13v.5A1.5,1.5,0,0,0,14.5,23h2A1.5,1.5,0,0,0,18,21.5V21h4.4a.5.5,0,0,0,0-1ZM17,21.5a.5.5,0,0,1-.5.5h-2a.5.5,0,0,1-.5-.5v-2a.5.5,0,0,1,.5-.5h2a.5.5,0,0,1,.5.5Z"></path><path d="M8.5,15h2A1.5,1.5,0,0,0,12,13.5V13H22.45a.5.5,0,1,0,0-1H12v-.5A1.5,1.5,0,0,0,10.5,10h-2A1.5,1.5,0,0,0,7,11.5V12H2.6a.5.5,0,1,0,0,1H7v.5A1.5,1.5,0,0,0,8.5,15ZM8,11.5a.5.5,0,0,1,.5-.5h2a.5.5,0,0,1,.5.5v2a.5.5,0,0,1-.5.5h-2a.5.5,0,0,1-.5-.5Z"></path></g></svg>
											Filtre
										</h6>
									</div>
									<div class="widget widget_search">
										<form action="<?php echo site_url('AppSearch/index/'); ?>" style="width: 100%;" method="post">
											<div class="form-group">
												<div class="input-group">
													<input name="nom_article"  type="search" class="form-control" placeholder="Entrez le nom d'un produit ici ...">
												</div>
											</div>
											<div class="input-group">
												<input name="prix_min_article"  type="number" class="form-control" placeholder="Prix minimun en $...">
											</div>
											<div class="input-group">
												<input name="prix_max_article"  type="number" class="form-control" placeholder="Prix maximun en $...">
											</div>
											<div class="form-group">
												<div class="input-group">
													<div class="input-group-addon">
														<button name="submit" value="Submit" type="submit" class="btn">
															<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M9.16667 15.8333C12.8486 15.8333 15.8333 12.8486 15.8333 9.16667C15.8333 5.48477 12.8486 2.5 9.16667 2.5C5.48477 2.5 2.5 5.48477 2.5 9.16667C2.5 12.8486 5.48477 15.8333 9.16667 15.8333Z" stroke="#0D775E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
																<path d="M17.5 17.5L13.875 13.875" stroke="#0D775E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
															</svg>
														</button>
													</div>
												</div>
											</div>
											<button type="submit" class="btn btn-sm font-14 btn-sharp"  style="background-color: #f31173; color:white;">ENVOYER</button>
											<button type="reset" class="btn btn-sm font-14 btn-primary btn-sharp" style="background-color: #000; color:white;">ANNULER</button>
										</form>
									</div>
									
									
									
									<div class="widget widget_categories">
										<h6 class="widget-title">Categories</h6>
										<ul>
											<?php 
												if($categories_articles){
													foreach ($categories_articles as $key => $categorie_article) {
														echo '<li class="cat-item cat-item-'.$key.'"><a href="'.site_url('AppCategorie/index/'.$categorie_article['id_categorie'].'').'">'.$categorie_article['nom_categorie'].'</a> ('.$categorie_article['qte'].')</li>';
													}
												}
											?>
										</ul>
									</div>
									
									<div class="widget widget_tag_cloud">
										<h6 class="widget-title">Tags</h6>
										<div class="tagcloud"> 
											<a href="<?php echo site_url('AppSection/filter/1/'); ?>">MARIAGE </a>
											<a href="<?php echo site_url('AppSection/filter/2/'); ?>">ANNIVERSAIRE</a>
											<a href="<?php echo site_url('AppSection/filter/3/'); ?>">ST VALENTIN</a>
											<a href="<?php echo site_url('AppSection/filter/4/'); ?>">Baby SHOWER</a>
										</div>
								</aside>
							</div>
						</div>
					</div>

					<div class="col-80 col-xl-9">

						<div class="filter-wrapper">
							<div class="filter-left-area">								
								<ul class="filter-tag">
									
									<li>
										<a href="<?php echo site_url('AppSection/filter/1/'); ?>" class="tag-btn">MARIAGE
											<i class="icon feather icon-x tag-close"></i>
										</a>
									</li>
									<li>
										<a href="<?php echo site_url('AppSection/filter/2/'); ?>" class="tag-btn">ANNIVERSAIRE
											<i class="icon feather icon-x tag-close"></i>
										</a>
									</li>
									<li>
										<a href="<?php echo site_url('AppSection/filter/3/'); ?>" class="tag-btn">ST VALENTIN 
											<i class="icon feather icon-x tag-close"></i>
										</a>
									</li>
									<li>
										<a href="<?php echo site_url('AppSection/filter/4/'); ?>" class="tag-btn">Baby SHOWER 
											<i class="icon feather icon-x tag-close"></i>
										</a>
									</li>
								</ul>
							</div>
							<div class="filter-right-area">
								<span>Affichage par plage de 12 sur <?php echo number_format($total_article_number,0,'.', ' '); ?> résultats</span>
							</div>
						</div>
						
						<div class="row">
							<div class="col-12 tab-content shop-" id="pills-tabContent">

								<div class="tab-pane fade active show" id="tab-list-grid" role="tabpanel" aria-labelledby="tab-list-grid-btn">
									<div id="content-cadeaux-div" class="row gx-xl-4 g-3">

										<?php 
											if(!empty($twelves_last_articles)){
												foreach ($twelves_last_articles as $key => $twelve_last_article) {
													echo '  <div class="article_container col-6 col-xl-3 col-lg-4 col-md-4 col-sm-6 m-md-b15 m-b30" idDatabase="'.($key+1).'">
																<div class="shop-card">
																	<div class="dz-media">
																		<img src="'.base_url('assets/uploads/files/'.$twelve_last_article['photo_article']).'" alt="image">										
																		<div class="shop-meta">
					
																			<a href="'.site_url('AppArticle/index/'.$twelve_last_article['id_article'].'/').'" class="btn btn-secondary btn-icon" style="background-color: #f31173; border-color:#f31173;">
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
																		<h5 class="title"><a href="'.site_url('AppArticle/index/'.$twelve_last_article['id_article'].'/').'">'.$twelve_last_article['nom_article'].'</a></h5>
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
																			<del>'.number_format($twelve_last_article['prix_vente']+50,0,',',' ').'$</del>
																			'.number_format($twelve_last_article['prix_vente'],0,',',' ').'$
																		</h6>
																		<form class="form_ajout_panier" method="POST"  name="form_add_article_' . $twelve_last_article['id_article'] . '" style="width: 100%; margin-top:4%;"> 
																			<input type="number" value="1" name="quantite" hidden>
																			<input type="number" value="' . $twelve_last_article['id_article'] . '" name="id_article" hidden>
																			<input type="text"   value="' . $refer . '" name="refer" hidden>
																			<input type="text"   value="'.$refer_add_card.'" name="refer_add_card" hidden>
																			<input type="text"   value="' . $userAppData['usertel'] . '" name="usertel" hidden>                                                                                               
																			<button class="btn btn-secondary"  style="width: 100%; height:30px; padding-left: 2%; padding-right: 2%;font-size: 12px;"  id="ajout-panier" type="submit">Ajouter au Panier</button>
																		</form>
																	</div>
																	<div class="product-tag">
																		<span class="badge badge-secondary" style="font-size: 10px;">EN VENTE</span>
																		<span class="badge badge-primary" style="background-color: #f31173; font-size: 10px;">'.$twelve_last_article['id_section_article'].'</span>
																	</div>
																</div>	
															</div>';
												}
											}
										?>		

									</div>
									<center>
										<div class="lds-circle" id="lds-circle" style="display:none;"><div></div></div>
									</center>
								</div>
								
							</div>
						</div>
						
						<div class="row page mt-0">
							<div class="col-md-6">
								<p class="page-text">Affichage par plage de  12 sur <?php echo number_format($total_article_number,0,'.', ' '); ?> résultats</p>
							</div>
							<div class="col-md-6">
								<nav aria-label="Blog Pagination">
									<button class="btn btn-sm font-14 btn-sharp" id="id-button-load-more"  style="background-color: #f31173; color:white;"> VOIR PLUS </button>
								</nav>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</section>
		
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
	
	<button class="scroltop" type="button"><i class="fas fa-arrow-up"></i></button>


</div>

<?php echo $footer; ?>

<script>
	$(function(){

		$("#id-button-load-more").click(function (e) { 
			e.preventDefault();
			$("#lds-circle").show();
			let last_index_date_base = $(".article_container:last").attr('idDatabase');
			$.ajax({
				type: "get",
				url: "<?php echo site_url('AppArticle/loadMoreArticles/'); ?>",
				data: {'index_limit_bdd':last_index_date_base},
				success: function (response) {
					if (/idDatabase/.test(response)) {
						$("#content-cadeaux-div").append(response);
						sendFormApp();
					}
					$("#lds-circle").hide();
				}
			});
		});

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
