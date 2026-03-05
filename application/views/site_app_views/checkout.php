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
	$sous_total_panier  = 0;

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
															$sous_total  = $sous_total + $commande['prix_vente'];
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
		<div class="dz-bnr-inr" style="background-image:url(<?php echo base_url('assets/site_app_assets/images/'); ?>background/bg-shape.jpg);">
			<div class="container">
				<div class="dz-bnr-inr-entry">
					<h1>Commande</h1>
					<nav aria-label="breadcrumb" class="breadcrumb-row">
						<ul class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('AppIndex/index/');?>"> Acceuille </a></li>
							<li class="breadcrumb-item">Commande</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>

		<!-- inner page banner End-->
		<div class="content-inner-1">
			<div class="container">
				<div class="row shop-checkout">

					<form class="row" method="POST" action="<?php echo site_url('AppCheckOut/processed/') ?>"  name="form_checkout">
						
						<div class="col-xl-8">
							<h4 class="title m-b15">Détails de facturation</h4>
								<div class="col-md-12">
									<div class="form-group m-b25">
										<label class="label-title">Nom *</label>
										<input class="full-width form-control" type="text" id="form-id-input-nom-client" name="nomclient" <?php echo  ($userAppData['username'] == "cadeaumart_default_user") ? "" : "value ='".$userAppData['username']."'   " ?> <?php if( $userAppData['username'] == "cadeaumart_default_user" )  echo "required";  ?> <?php if( $userAppData['username'] <> "cadeaumart_default_user" )  echo "readonly"; ?> >
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group m-b25">
										<label class="label-title">Prenom *</label>
										<input class="full-width form-control" type="text" id="form-id-input-prenom-client" name="prenomclient" value="<?php echo  ($userAppData['prenom'] == "cadeaumart_default_user") ? "" : $userAppData['prenom']; ?>" <?php if( $userAppData['prenom'] == "cadeaumart_default_user" )  echo "required"  ?> <?php if( $userAppData['prenom'] <> "cadeaumart_default_user" )  echo "readonly"  ?>  >
									</div>
								</div>
								<div class="col-md-12">
									<div class="m-b25">
										<label class="label-title">Pays *</label>
										<div class="form-select">
											<select class="default-select w-100" id="form-id-input-pays" name="paysclient" required>
												<option value="RDC">RDC</option>
											</select>	
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="m-b25">
										<label class="label-title">Province *</label>
										<div class="form-select">
											<select class="default-select w-100" id="form-id-input-province" name="provinceclient" required>
												<option value="haut-katanga">Haut-Katanga</option>
											</select>	
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="m-b25">
										<label class="label-title">Ville*</label>
										<div class="form-select">
											<select class="default-select w-100" id="form-id-input-ville" name="villeclient" required>
												<option value="Lubumbashi">Lubumbashi</option>
											</select>	
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group m-b25">
										<label class="label-title">Numéro, Avenue et Commune </label>
										<input class="form-control m-b15" type="text" id="form-id-input-adresse-client" name="numberoavenueclient" value="<?php if( isset($userAppData['numbero_avenue_client']) ){ echo  (  $userAppData['numbero_avenue_client'] == "cadeaumart_default_user") ? "" : $userAppData['numbero_avenue_client']; } ?>" required="required">
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group m-b25">
										<label class="label-title">Tél Whatsapp *</label>
										<input class="full-width form-control" type="number" id="form-id-input-tel-client" name="telclient" placeholder="ex: 243970000000"  value="<?php echo  ($userAppData['username'] == "cadeaumart_default_user") ? "" : $userAppData['usertel']; ?>" <?php if( $userAppData['username'] == "cadeaumart_default_user" )  echo "required"  ?> <?php if( ($userAppData['username'] <> "cadeaumart_default_user") )  echo "readonly"  ?> >
									</div>
								</div>
								<div class="col-md-12 m-b25">
									<div class="form-group">
										<label class="label-title">Note de la commandes</label>
										<textarea id="comments" placeholder="Ex: Autres détails de livraison ..." class="form-control" name="notecommandeclient" cols="90" rows="5"></textarea>
									</div>
								</div>
						</div>
						

						<div class="col-xl-4 side-bar">
							<h4 class="title m-b15">Votre commande</h4>
							<div class="order-detail sticky-top">

								<?php 
									if (!empty($userAppData['commandes'])) {
										$sous_total 	= 0;
										$compter_panier = 0;
										foreach ( $userAppData['commandes'] as $key => $commande) {
											$compter_panier++;
											$sous_total  		= $sous_total + ($commande['prix_vente'] * $commande['quantite']);
											$sous_total_panier  = $sous_total_panier + $sous_total;

												'	<tr>
														<td class="product-item-img"><img src="'.base_url('assets/uploads/files/'.$commande['photo_article']).'" alt="/"></td>
														<td class="product-item-name">'.$commande['nom_article'].'</td>
														<td class="product-item-price">'.number_format($commande['prix_vente'], 0, ',',' ').'$</td>
														<td class="product-item-quantity">
															<div class="quantity btn-quantity style-1 me-3">
																<input type="number" value="'.$commande['quantite'].'" name="quantite_article_'.$compter_panier.'">
																<input type="number" value="'.$commande['id_article'].'" name="id_article_'.$compter_panier.'" style="display:none;">
															</div>
														</td>
														<td class="product-item-totle">'.number_format($sous_total, 0, ',',' ').'$</td>
														<td class="product-item-close">
															<a href="javascript:void(0);">
																<i class="ti-close delete_article" id_article="'.$commande['id_article'].'"></i>
															</a>
														</td>
													</tr>';

											
											echo '  <div class="cart-item style-1">
														<div class="dz-media">
															<img src="'.base_url('assets/uploads/files/'.$commande['photo_article']).'" alt="/">
														</div>
														<div class="dz-content">
															<h6 class="title mb-0">'.$commande['nom_article'].' ( x '.$commande['quantite'].')</h6>
															<span class="price">'.number_format($sous_total, 0, ',',' ').'$</span>
														</div>
													</div>';

											$sous_total = 0;
										}
									}
								?>
															
								<table>
									<tbody>
										<tr class="subtotal">
											<td>Sous Total </td>
											<td class="price"><?php echo number_format($sous_total_panier, 0, ',',' ').'$'?></td>
										</tr>
										<tr class="title">
											<td><h6 class="title font-weight-500">LIVRAISON</h6></td>
											<td></td>
										</tr>
										<tr class="shipping">
											<td colspan="2">
												<p class="font-weight-300">
												Nous Livrons à Lubumbashi à partir de 5000 FC, mais le prix de la livraison est fonction du poid, la grandeur de votre cadeau et de la distance du lieu de livraison
												</p>
											</td>
										</tr>
										<tr class="total">
											<td>Total</td>
											<td class="price"><?php echo number_format($sous_total_panier, 0, ',',' ').'$'?></td>
										</tr>
									</tbody>
								</table>
								
								<div class="accordion dz-accordion accordion-sm" id="accordionFaq1">
									<div class="accordion-item">
										<div class="accordion-header" id="heading2">
											<div class="accordion-button collapsed custom-control custom-checkbox" data-bs-toggle="collapse" data-bs-target="#collapse2" role="navigation" aria-expanded="true" aria-controls="collapse2">
												<input class="form-check-input radio" type="radio" name="flexRadioDefault" id="flexRadioDefault5" checked>
												<label class="form-check-label" for="flexRadioDefault5">
													Cash, Carte Bancaire ou Mobile Money
												</label>
											</div>
										</div>
										<div id="collapse2" class="accordion-collapse collapse" aria-labelledby="collapse2" data-bs-parent="#accordionFaq1">
											<div class="accordion-body">
												<p class="m-b0">Effectuez votre paiement directement sur notre compte bancaire, En Cash ou par mobile money. Veuillez utiliser votre numéro de commande comme référence de paiement. Votre commande ne sera pas expédiée tant que les fonds n'auront pas reçu les fonds.</p>
											</div>
										</div>
									</div>
								</div>

								<p class="text">Vos données personnelles seront utilisées pour traiter votre commande, pour améliorer votre expérience sur ce site Web et à d'autres fins décrites dans notre <a href="javascript:void(0);">politique de confidentialité.</a></p>
								
								<div class="form-group">
									<div class="custom-control custom-checkbox d-flex m-b15">
										<input type="checkbox" class="form-check-input" id="basic_checkbox_3" name="terme_condition" checked required>
										<label class="form-check-label" for="basic_checkbox_3">J'ai lu et j'accepte les termes et conditions du service </label>
									</div>
								</div>

								<button type="submit"  class="btn btn-secondary w-100">JE COMMANDE</button>

							</div>
						</div>

					</form>

				</div>
			</div>
		</div>

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

		var message_user ="<?php echo $message;?>";

		(function notify_client () {
			if(message_user !== 'null' && message_user.length > 4){
				swal(''+message_user);
				message_user = 'null';
			}
		})();
		
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