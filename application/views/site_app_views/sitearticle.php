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
	.para-text p {
		color : black;
	}
</style>

<body>
<div class="page-wraper">

	<!-- <div id="loading-area" class="preloader-wrapper-1">
		<div>
			<span class="loader-2"></span>
			<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>logo.png" alt="/">
			<span class="loader"></span>
		</div>
	</div> -->
	
	<!-- Header -->
	<header class="site-header mo-left header border-bottom">

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
		
		<div class="d-sm-flex justify-content-between container-fluid py-3">
			<nav aria-label="breadcrumb" class="breadcrumb-row">
				<ul class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo site_url('AppIndex/index/'); ?>"> Aceuille</a></li>
					<li class="breadcrumb-item"><?php echo strtoupper($article['nom_article']); ?></li>
				</ul>
			</nav>
		</div>
		
		<section class="content-inner py-0">
			<div class="container-fluid">
				<div class="row">

					<div class="col-xl-4 col-md-4">
						<div class="dz-product-detail sticky-top">
							<div class="swiper-btn-center-lr">
								<div class="swiper product-gallery-swiper2" >
									<div class="swiper-wrapper" id="lightgallery">

										<div class="swiper-slide">
											<div class="dz-media DZoomImage">
												<a class="mfp-link lg-item" href="<?php echo base_url('assets/uploads/files/'.$article['photo_article']); ?>" data-src="<?php echo base_url('assets/uploads/files/'.$article['photo_article']); ?>">
													<i class="feather icon-maximize dz-maximize top-left"></i>
												</a>
												<img src="<?php echo base_url('assets/uploads/files/'.$article['photo_article']); ?>" alt="image">
											</div>
										</div>

										<?php 
											for ($i=2; $i <=4 ; $i++) { 
												if(isset($article['photo_article_'.$i]) AND !empty( $article['photo_article_'.$i] ) ){
													echo '  <div class="swiper-slide">
																<div class="dz-media DZoomImage">
																	<a class="mfp-link lg-item" href="'.base_url('assets/uploads/files/'.$article['photo_article_'.$i]).'" data-src="'.base_url('assets/uploads/files/'.$article['photo_article_'.$i]).'">
																		<i class="feather icon-maximize dz-maximize top-left"></i>
																	</a>
																	<img src="'.base_url('assets/uploads/files/'.$article['photo_article_'.$i]).'" alt="image">
																</div>
															</div>';
												}
											}
										?>

									</div>
								</div>
								<div class="swiper product-gallery-swiper thumb-swiper-lg">
									<div class="swiper-wrapper">
										<div class="swiper-slide">
											<img src="<?php echo base_url('assets/uploads/files/'.$article['photo_article']); ?>" alt="image">
										</div>
										<?php 
											for ($i=2; $i <=4 ; $i++) { 
												if(isset($article['photo_article_'.$i]) AND !empty( $article['photo_article_'.$i] ) ){
													echo '  <div class="swiper-slide">
																<img src="'.base_url('assets/uploads/files/'.$article['photo_article_'.$i]).'" alt="image">
															</div>';
												}
											}
										?>
									</div>
								</div>
							</div>							
						</div>	
					</div>

					<div class="col-xl-8 col-md-8">
						<div class="row">
							<div class="col-xl-7">
								<div class="dz-product-detail style-2 p-t20 ps-0">
									<div class="dz-content">
										<div class="dz-content-footer">
											<div class="dz-content-start">
												<span class="badge bg-purple mb-2"> REDUCTION EN FONCTION DES NOMBRES DES PIECES </span>
												<h4 class="title mb-1"><a href="<?php echo site_url('AppArticle/index/'.$article['id_article'].'/'); ?>"><?php echo strtoupper($article['nom_article']); ?></a></h4>
												<div class="review-num">
													<ul class="dz-rating me-2">
														<li>
															<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M6.74805 0.234375L8.72301 4.51608L13.4054 5.07126L9.9436 8.27267L10.8625 12.8975L6.74805 10.5944L2.63355 12.8975L3.5525 8.27267L0.090651 5.07126L4.77309 4.51608L6.74805 0.234375Z" fill="#FF8A00"></path>
															</svg>
														</li>	
														<li>
															<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M6.74805 0.234375L8.72301 4.51608L13.4054 5.07126L9.9436 8.27267L10.8625 12.8975L6.74805 10.5944L2.63355 12.8975L3.5525 8.27267L0.090651 5.07126L4.77309 4.51608L6.74805 0.234375Z" fill="#FF8A00"></path>
															</svg>
														</li>
														<li>
															<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M6.74805 0.234375L8.72301 4.51608L13.4054 5.07126L9.9436 8.27267L10.8625 12.8975L6.74805 10.5944L2.63355 12.8975L3.5525 8.27267L0.090651 5.07126L4.77309 4.51608L6.74805 0.234375Z" fill="#FF8A00"></path>
															</svg>
														</li>
														<li>
															<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M6.74805 0.234375L8.72301 4.51608L13.4054 5.07126L9.9436 8.27267L10.8625 12.8975L6.74805 10.5944L2.63355 12.8975L3.5525 8.27267L0.090651 5.07126L4.77309 4.51608L6.74805 0.234375Z" fill="#FF8A00"></path>
															</svg>
														</li>
														<li>
															<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path opacity="0.2" d="M6.74805 0.234375L8.72301 4.51608L13.4054 5.07126L9.9436 8.27267L10.8625 12.8975L6.74805 10.5944L2.63355 12.8975L3.5525 8.27267L0.090651 5.07126L4.77309 4.51608L6.74805 0.234375Z" fill="#5E626F"></path>
															</svg>
														</li>	
													</ul>
													<span class="text-secondary me-2">4.7 Notation</span>
													<a href="javascript:void(0);">( Avis Clients )</a>
												</div>
											</div>
										</div>
										<div class="para-text">
											<?php echo $article['text_article']; ?>
										</div>
										<div class="meta-content m-b20 d-flex align-items-end">
											<div class="me-3">
												<span class="price-name">Prix</span>
												<span class="price-num"><?php echo number_format($article['prix_vente'],0,',',' '); ?>$<del> <?php echo number_format($article['prix_vente']+10,0,',',' '); ?> $</del></span>
											</div>
											<div class="btn-quantity quantity-sm light d-xl-none d-blcok d-sm-block">
												<label class="form-label">Quantity</label>
												<input  type="text" value="1" name="demo_vertical2">
											</div>
										</div>
										<div class="product-num">
											<div class="btn-quantity light d-xl-block d-sm-none d-none">
												<label class="form-label">Quantité</label>
												<input  type="text" value="1" name="demo_vertical2" id="quantite_article_2">
											</div>
										</div>
										<div class="dz-info">
											<ul>
												<li>
													<strong>CODE :</strong>
													<span><?php if(!empty($article['code']))  echo $article['code'] ; else echo 'N/A'; ?></span>
												</li>
												<li>
													<strong>Categorie:</strong>
													<span>
														<a href="<?php echo site_url('AppCategorie/index/'.$article['id_categorie'].'/'); ?>">
															<?php echo $article['nom_categorie']; ?>
														</a>
													</span>
												</li>
												<li>
													<strong>Section:</strong>
													<span>
														<a href="<?php echo site_url('AppSection/filter/'.$article['id_section_article'].'/'); ?>">
															<?php echo $article['nom_section_article']; ?>
														</a>
													</span>
												</li>
												<li>
													<strong>Partager sur :</strong>
													<span>											
														<a href="javascript:void(0);" target="_blank">
															<i class="fa-brands fa-facebook-f"></i>
														</a>
													</span>
													<span>											
														<a href="https://www.linkedin.com/showcase/3686700/admin/" target="_blank">
															<i class="fa-brands fa-linkedin-in"></i>
														</a>
													</span>
													<span>											
														<a href="javascript:void(0);" target="_blank">
															<i class="fa-brands fa-instagram"></i>
														</a>
													</span>
													<span>											
														<a href="javascript:void(0);" target="_blank">
															<i class="fa-brands fa-twitter"></i>
														</a>
													</span>
												</li>
											</ul>
										</div>
									</div>
									<div class="banner-social-media">
										<ul>
											<li>
												<a href="javascript:void(0);">Instagram</a>
											</li>
											<li>
												<a href="javascript:void(0);">Facebook</a>
											</li>
											<li>
												<a href="javascript:void(0);">twitter</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-xl-5">
								<div class="cart-detail">
									<a href="javascript:void(0);" class="btn btn-outline-primary w-100 m-b20">Nous acceptons les paiements par mobiles money</a>
									<div class="icon-bx-wraper style-4 m-b15">
										<div class="icon-bx">
											<i class="flaticon flaticon-ship"></i>
										</div>
										<div class="icon-content">
											<span class="text-primary font-14">Livraison</span>
											<h6 class="dz-title">A Lubumbashi</h6>
										</div>
									</div>
									<div class="icon-bx-wraper style-4 m-b30">
										<div class="icon-bx">
											<img src="<?php echo base_url('assets/site_app_assets/'); ?>images/shop/shop-cart/icon-box/pic2.png" alt="/">
										</div>
										<div class="icon-content">
											<h6 class="dz-title">Profitez avec CadeauMart</h6>
											<p>La référence en matière de service de cadeau</p>
										</div>
									</div>
									<div class="save-text">
										<i class="icon feather icon-check-circle"></i>
										<span class="m-l10">Vous pouvez procurer de la joie avec ce cadeau</span>
									</div>
									<table>
										<tbody>
											<tr class="total">
												<td>
													<h6 class="mb-0">PRIX</h6>
												</td>
												<td class="price">
													<?php echo number_format($article['prix_vente'],0,',',' '); ?>$
												</td>
											</tr>
										</tbody>
									</table>
									<a href="shop-wishlist.html" class="btn btn-white btn-icon m-b20" hidden>
										<svg width="19" height="17" viewBox="0 0 19 17" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M9.24805 16.9986C8.99179 16.9986 8.74474 16.9058 8.5522 16.7371C7.82504 16.1013 7.12398 15.5038 6.50545 14.9767L6.50229 14.974C4.68886 13.4286 3.12289 12.094 2.03333 10.7794C0.815353 9.30968 0.248047 7.9162 0.248047 6.39391C0.248047 4.91487 0.755203 3.55037 1.67599 2.55157C2.60777 1.54097 3.88631 0.984375 5.27649 0.984375C6.31552 0.984375 7.26707 1.31287 8.10464 1.96065C8.52734 2.28763 8.91049 2.68781 9.24805 3.15459C9.58574 2.68781 9.96875 2.28763 10.3916 1.96065C11.2292 1.31287 12.1807 0.984375 13.2197 0.984375C14.6098 0.984375 15.8885 1.54097 16.8202 2.55157C17.741 3.55037 18.248 4.91487 18.248 6.39391C18.248 7.9162 17.6809 9.30968 16.4629 10.7792C15.3733 12.094 13.8075 13.4285 11.9944 14.9737C11.3747 15.5016 10.6726 16.1001 9.94376 16.7374C9.75136 16.9058 9.50417 16.9986 9.24805 16.9986ZM5.27649 2.03879C4.18431 2.03879 3.18098 2.47467 2.45108 3.26624C1.71033 4.06975 1.30232 5.18047 1.30232 6.39391C1.30232 7.67422 1.77817 8.81927 2.84508 10.1066C3.87628 11.3509 5.41011 12.658 7.18605 14.1715L7.18935 14.1743C7.81021 14.7034 8.51402 15.3033 9.24654 15.9438C9.98344 15.302 10.6884 14.7012 11.3105 14.1713C13.0863 12.6578 14.6199 11.3509 15.6512 10.1066C16.7179 8.81927 17.1938 7.67422 17.1938 6.39391C17.1938 5.18047 16.7858 4.06975 16.045 3.26624C15.3152 2.47467 14.3118 2.03879 13.2197 2.03879C12.4197 2.03879 11.6851 2.29312 11.0365 2.79465C10.4585 3.24179 10.0558 3.80704 9.81975 4.20255C9.69835 4.40593 9.48466 4.52733 9.24805 4.52733C9.01143 4.52733 8.79774 4.40593 8.67635 4.20255C8.44041 3.80704 8.03777 3.24179 7.45961 2.79465C6.811 2.29312 6.07643 2.03879 5.27649 2.03879Z" fill="black"></path>
										</svg>
										Add To Wishlist
									</a>

									<form class="form_ajout_panier" method="POST" name="form_add_article_<?php echo $article['id_article'] ?>" style="width: 100%;"> 
										<input type="number" value="1" name="quantite" hidden id="quantite_article">
										<input type="number" value="<?php echo $article['id_article'] ?>" name="id_article" hidden>
										<input type="text"   value="<?php echo $refer ?>" name="refer" hidden>
										<input type="text"   value="<?php echo $refer_add_card ?>" name="refer_add_card" hidden>
										<input type="text"   value="<?php echo $userAppData['usertel'] ?>" name="usertel" hidden> 
										<a href="https://wa.me/243810274370?text=<?php echo $article['nom_article']?>" class="btn btn-secondary btn-icon" style="background-color:#0d775e !important; border-color:#0d775e !important; ">
											<i class="fab fa-whatsapp"></i>
											<span class="d-md-block d-none">Whatsapp</span>
										</a>  
										<br>                                                                                            
										<button class="btn btn-secondary"  style="width: 100%; padding-left: 2%; padding-right: 2%;"  id="ajout-panier" type="submit">Ajouter au Panier</button>
									</form>

								</div>	
							</div>
						</div>
					</div>

				</div>
			</div>
		</section>
		
		<section class="content-inner-3 pb-0"> 
			<div class="container">
				<div class="product-description">
					<div class="dz-tabs">					
						<ul class="nav nav-tabs center" id="myTab1" role="tablist">
							<li class="nav-item" role="presentation">
								<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Description</button>
							</li>
						</ul>
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
								<div class="detail-bx text-center">
									<h5 class="title"><?php echo strtoupper($article['nom_article']); ?></h5>
									<p class="para-text">
										<?php echo $article['text_article']; ?>
									</p>
									<ul class="feature-detail" style="padding: 0 0 0 24%;">
										<li>
											<i class="icon feather icon-check"></i>
											<h5>Bonne qualité</h5>
										</li>
										<li>
											<i class="icon feather icon-check"></i>
											<h5>Utile au quotidien</h5>
										</li>
										<li>
											<i class="icon feather icon-check"></i>
											<h5>Prix abordable</h5>
										</li>
									</ul>
								</div>
								<div class="row g-lg-4 g-3">
									<div class="col-xl-4 col-md-4 col-sm-4 col-6">
										<div class="related-img dz-media">
											<img src="<?php echo base_url('assets/uploads/files/'.$article['photo_article']); ?>" alt="/">
										</div>
									</div>
									<?php 
										for ($i=2; $i <=3; $i++) { 
											if(isset($article['photo_article_'.$i]) AND !empty( $article['photo_article_'.$i] ) ){
												echo '  <div class="col-xl-4 col-md-4 col-sm-4 col-6">
															<div class="related-img dz-media">
																<img src="'.base_url('assets/uploads/files/'.$article['photo_article_'.$i]).'" alt="image">
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
		</section>
		
		<section class="content-inner-1 overlay-white-middle overflow-hidden">
			<div class="container">
				<div class="section-head style-2">
					<div class="left-content">
						<h2 class="title mb-0">Produits similaires</h2>
					</div>
					<a href="<?php echo site_url('AppCategorie/index/'.$article['id_categorie'].'/');?>" class="text-secondary font-14 d-flex align-items-center gap-1">Voir plus
						<i class="icon feather icon-chevron-right font-18"></i>
					</a>			
				</div>
				<div class="swiper-btn-center-lr">
					<div class="swiper swiper-four">
						<div class="swiper-wrapper">
							<?php 
								foreach ($arcticles_similaires as $key => $arcticle_similaire) {
									echo '  <div class="swiper-slide">
												<div class="shop-card">
													<div class="dz-media">
														<img src="'.base_url('assets/uploads/files/'.$arcticle_similaire['photo_article']).'" alt="image">
														<div class="shop-meta">
															<a href="'.site_url('AppArticle/index/'.$arcticle_similaire['id_article'].'/').'" class="btn btn-secondary btn-icon">
																<i class="fa-solid fa-eye"></i>
																<span class="d-md-block d-none">Voir</span>
															</a>
															<a href="https://wa.me/243810274370?text='.$arcticle_similaire['nom_article'].'" class="btn btn-secondary btn-icon" style="background-color:#0d775e !important; border-color:#0d775e !important; ">
																<i class="fab fa-whatsapp"></i>
																<span class="d-md-block d-none">Whatsapp</span>
															</a>
														</div>
													</div>
													<div class="dz-content">
														<h5 class="title"><a href="'.site_url('AppArticle/index/'.$arcticle_similaire['id_article'].'/').'">'.$arcticle_similaire['nom_article'].'</a></h5>
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
															<del>'.number_format($arcticle_similaire['prix_vente']+10,0,',',' ').'$</del>
															'.number_format($arcticle_similaire['prix_vente'],0,',',' ').'$
														</h6>
													</div>
													<div class="product-tag">
														<span class="badge badge-secondary">En Vente</span>
														<span class="badge badge-primary">'.$arcticle_similaire['nom_categorie'].'</span>
													</div>
												</div>
											</div>';
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
	
	<!-- Footer -->
	<?php echo $footersection; ?>
	<!-- Footer End -->
	
	<button class="scroltop" type="button"><i class="fas fa-arrow-up"></i></button>

	<!-- Quick Modal Start -->
		<div class="modal quick-view-modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
						<i class="icon feather icon-x"></i>
					</button>
					<div class="modal-body">
						<div class="row g-xl-4 g-3">
							<div class="col-xl-6 col-md-6">
								<div class="dz-product-detail mb-0">
									<div class="swiper-btn-center-lr">
										<div class="swiper quick-modal-swiper2">
											<div class="swiper-wrapper" id="lightgallery2">
												<div class="swiper-slide">
													<div class="dz-media DZoomImage">
														<a class="mfp-link lg-item" href="<?php echo base_url('assets/site_app_assets/images/products/'); ?>baby-seat.png" data-src="<?php echo base_url('assets/site_app_assets/images/products/'); ?>baby-seat.png">
															<i class="feather icon-maximize dz-maximize top-right"></i>
														</a>
														<img src="<?php echo base_url('assets/site_app_assets/images/products/'); ?>baby-seat.png" alt="image">
													</div>
												</div>
												<div class="swiper-slide">
													<div class="dz-media DZoomImage">
														<a class="mfp-link lg-item" href="<?php echo base_url('assets/site_app_assets/images/products/'); ?>baby-seat2.png" data-src="<?php echo base_url('assets/site_app_assets/images/products/'); ?>baby-seat2.png">
															<i class="feather icon-maximize dz-maximize top-right"></i>
														</a>
														<img src="<?php echo base_url('assets/site_app_assets/images/products/'); ?>baby-seat2.png" alt="image">
													</div>
												</div>
												<div class="swiper-slide">
													<div class="dz-media DZoomImage">
														<a class="mfp-link lg-item" href="<?php echo base_url('assets/site_app_assets/images/products/'); ?>baby-seat3.png" data-src="<?php echo base_url('assets/site_app_assets/images/products/'); ?>baby-seat3.png">
															<i class="feather icon-maximize dz-maximize top-right"></i>
														</a>
														<img src="<?php echo base_url('assets/site_app_assets/images/products/'); ?>baby-seat3.png" alt="image">
													</div>
												</div>
												<div class="swiper-slide">
													<div class="dz-media DZoomImage">
														<a class="mfp-link lg-item" href="<?php echo base_url('assets/site_app_assets/images/products/'); ?>baby-seat.png" data-src="<?php echo base_url('assets/site_app_assets/images/products/'); ?>baby-seat.png">
															<i class="feather icon-maximize dz-maximize top-right"></i>
														</a>
														<img src="<?php echo base_url('assets/site_app_assets/images/products/'); ?>baby-seat.png" alt="image">
													</div>
												</div>
											</div>
										</div>
										<div class="swiper quick-modal-swiper thumb-swiper-lg thumb-sm swiper-vertical">
											<div class="swiper-wrapper">
												<div class="swiper-slide">
													<img src="<?php echo base_url('assets/site_app_assets/images/products/'); ?>thumb-img/seat1.png" alt="image">
												</div>
												<div class="swiper-slide">
													<img src="<?php echo base_url('assets/site_app_assets/images/products/'); ?>thumb-img/seat2.png" alt="image">
												</div>
												<div class="swiper-slide">
													<img src="<?php echo base_url('assets/site_app_assets/images/products/'); ?>thumb-img/seat3.png" alt="image">
												</div>
												<div class="swiper-slide">
													<img src="<?php echo base_url('assets/site_app_assets/images/products/'); ?>thumb-img/seat1.png" alt="image">
												</div>
											</div>
										</div>
									</div>	
								</div>	
							</div>
							<div class="col-xl-6 col-md-6">
								<div class="dz-product-detail style-2 ps-xl-3 ps-0 pt-2 mb-0">
									<div class="dz-content">
										<div class="dz-content-footer">
											<div class="dz-content-start">
												<span class="badge bg-purple mb-2">SALE 20% Off</span>
												<h4 class="title mb-1"><a href="shop-list.html">Baby Strollers</a></h4>
												<div class="review-num">
													<ul class="dz-rating me-2">
														<li>
															<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M6.74805 0.234375L8.72301 4.51608L13.4054 5.07126L9.9436 8.27267L10.8625 12.8975L6.74805 10.5944L2.63355 12.8975L3.5525 8.27267L0.090651 5.07126L4.77309 4.51608L6.74805 0.234375Z" fill="#FF8A00"></path>
															</svg>
														</li>	
														<li>
															<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M6.74805 0.234375L8.72301 4.51608L13.4054 5.07126L9.9436 8.27267L10.8625 12.8975L6.74805 10.5944L2.63355 12.8975L3.5525 8.27267L0.090651 5.07126L4.77309 4.51608L6.74805 0.234375Z" fill="#FF8A00"></path>
															</svg>
														</li>
														<li>
															<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M6.74805 0.234375L8.72301 4.51608L13.4054 5.07126L9.9436 8.27267L10.8625 12.8975L6.74805 10.5944L2.63355 12.8975L3.5525 8.27267L0.090651 5.07126L4.77309 4.51608L6.74805 0.234375Z" fill="#FF8A00"></path>
															</svg>
														</li>
														<li>
															<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path opacity="0.2" d="M6.74805 0.234375L8.72301 4.51608L13.4054 5.07126L9.9436 8.27267L10.8625 12.8975L6.74805 10.5944L2.63355 12.8975L3.5525 8.27267L0.090651 5.07126L4.77309 4.51608L6.74805 0.234375Z" fill="#5E626F"></path>
															</svg>

														</li>
														<li>
															<svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path opacity="0.2" d="M6.74805 0.234375L8.72301 4.51608L13.4054 5.07126L9.9436 8.27267L10.8625 12.8975L6.74805 10.5944L2.63355 12.8975L3.5525 8.27267L0.090651 5.07126L4.77309 4.51608L6.74805 0.234375Z" fill="#5E626F"></path>
															</svg>
														</li>	
													</ul>
													<span class="text-secondary me-2">4.7 Rating</span>
													<a href="javascript:void(0);">(5 customer reviews)</a>
												</div>
											</div>
										</div>
										<p class="para-text">
											Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has.
										</p>
										<div class="meta-content m-b20 d-flex align-items-end">
											<div class="me-3">
												<span class="form-label">Price</span>
												<span class="price-num">$125.75 <del>$132.17</del></span>
											</div>
											<div class="btn-quantity light me-0">
												<label class="form-label">Quantity</label>
												<input  type="text" value="1" name="demo_vertical2">
											</div>
										</div>
										<div class="btn-group cart-btn">
											<a href="shop-cart.html" class="btn btn-md btn-secondary text-uppercase">Add To Cart</a>
											<a href="shop-wishlist.html" class="btn btn-md btn-light btn-icon">
												<svg width="19" height="17" viewBox="0 0 19 17" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M9.24805 16.9986C8.99179 16.9986 8.74474 16.9058 8.5522 16.7371C7.82504 16.1013 7.12398 15.5038 6.50545 14.9767L6.50229 14.974C4.68886 13.4286 3.12289 12.094 2.03333 10.7794C0.815353 9.30968 0.248047 7.9162 0.248047 6.39391C0.248047 4.91487 0.755203 3.55037 1.67599 2.55157C2.60777 1.54097 3.88631 0.984375 5.27649 0.984375C6.31552 0.984375 7.26707 1.31287 8.10464 1.96065C8.52734 2.28763 8.91049 2.68781 9.24805 3.15459C9.58574 2.68781 9.96875 2.28763 10.3916 1.96065C11.2292 1.31287 12.1807 0.984375 13.2197 0.984375C14.6098 0.984375 15.8885 1.54097 16.8202 2.55157C17.741 3.55037 18.248 4.91487 18.248 6.39391C18.248 7.9162 17.6809 9.30968 16.4629 10.7792C15.3733 12.094 13.8075 13.4285 11.9944 14.9737C11.3747 15.5016 10.6726 16.1001 9.94376 16.7374C9.75136 16.9058 9.50417 16.9986 9.24805 16.9986ZM5.27649 2.03879C4.18431 2.03879 3.18098 2.47467 2.45108 3.26624C1.71033 4.06975 1.30232 5.18047 1.30232 6.39391C1.30232 7.67422 1.77817 8.81927 2.84508 10.1066C3.87628 11.3509 5.41011 12.658 7.18605 14.1715L7.18935 14.1743C7.81021 14.7034 8.51402 15.3033 9.24654 15.9438C9.98344 15.302 10.6884 14.7012 11.3105 14.1713C13.0863 12.6578 14.6199 11.3509 15.6512 10.1066C16.7179 8.81927 17.1938 7.67422 17.1938 6.39391C17.1938 5.18047 16.7858 4.06975 16.045 3.26624C15.3152 2.47467 14.3118 2.03879 13.2197 2.03879C12.4197 2.03879 11.6851 2.29312 11.0365 2.79465C10.4585 3.24179 10.0558 3.80704 9.81975 4.20255C9.69835 4.40593 9.48466 4.52733 9.24805 4.52733C9.01143 4.52733 8.79774 4.40593 8.67635 4.20255C8.44041 3.80704 8.03777 3.24179 7.45961 2.79465C6.811 2.29312 6.07643 2.03879 5.27649 2.03879Z" fill="black"></path>
												</svg>
												Add To Wishlist
											</a>
										</div>
										<div class="dz-info mb-0">
											<ul>
												<li>
													<strong>SKU:</strong>
													<span>PRT584E63A</span>
												</li>
												<li>
													<strong>Category:</strong>
													<span>Bottles,</span>
													<span>Accessories,</span>
													<span>Mats,</span>
													<span>Bottles,</span>
													<span>Trackers</span>
												</li>
												<li>
													<strong>Tags:</strong>
													<span>Trackers,</span>
													<span>Bags,</span>
													<span>Cup,</span>
													<span>Toothbrushes</span>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- Quick Modal End -->
	
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
						let input_article = $("#quantite_article_2").val();

						form_data = {
							'quantite'      : input_article,
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