<!DOCTYPE html>
<html lang="en">
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
										<span class="badge badge-circle">
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
									<span class="badge badge-light">
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
									<form action="<?php echo site_url('AppArticle/updatePanier/'); ?>">
										<ul class="sidebar-cart-list">
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
																		<a href="'.site_url('AppArticle/panier/').$commande['id_article'].'/'.str_replace('/','_',$refer).'" class="dz-close">
																			<i class="ti-close"></i>
																		</a>
																	</div>
																</li>';
													}
												}
											?>	
											<input type="text"   value="<?php if($refer) echo $refer; ?>" name="refer" hidden>
											<input type="text"   value="<?php if($refer_add_card) echo $refer_add_card; ?>" name="refer_add_card" hidden>
											<input type="text"   value="<?php if($userAppData) echo $userAppData['usertel']; ?>" name="usertel" hidden>
											<input type="text"   value="<?php echo $compter_panier; ?>" name="total_article_panier" hidden>
										</ul>
										<div class="cart-total">
											<h5 class="mb-0">Sous total:</h5>
											<h5 class="mb-0"><?php echo  number_format($sous_total, 0,',', ' ') ?> $</h5>
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

		<section class="content-inner-1">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-xl-8 col-lg-10 col-md-12">
						<div class="error-page style-1">
							<div class="dz-error-media">
								<img src="<?php echo base_url('assets/site_app_assets/images/'); ?>pic-404.png" alt="">
							</div>
							<div class="error-inner">
								<h1 class="dz_error">404</h1>
								<p class="error-head">Oh, non! Nous avons trouvé aucun élément qui correspond à votre recherche</p>
								<a href="<?php echo site_url('AppIndex/index/');?>" class="btn btn-secondary  text-uppercase">Retourner à la page d'acceuille</a>
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
	
	<!-- Footer -->
	<?php echo $footersection; ?>
	<!-- Footer End -->
	
	<button class="scroltop" type="button"><i class="fas fa-arrow-up"></i></button>

	
</div>

<?php echo $footer; ?>
