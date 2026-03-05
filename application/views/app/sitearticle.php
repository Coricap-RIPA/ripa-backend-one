<?php 
    //$refer 		=  $this->agent->referrer();
    $refer 		=  '';
    $refer      = (strlen($refer) >3) ? $refer : current_url();
    $refer 		= str_replace( site_url(),"", $refer); 
    $refer 		= str_replace( base_url(),"", $refer); 

    $displayForm 	=  ($article['id_foreign_categorie'] == 11) ? 'none'  : 'block'; 
    $displayWhat 	=  ($article['id_foreign_categorie'] == 11) ? 'block' : 'none'; 
    $message        =  "Bonjour je suis interressé par votre véhicule  [ ".$article['nom_article']." ] ID système: [ ".$article['id_article']." ]";

    $refer_add_card         = current_url();
    $refer_add_card 		= str_replace( site_url(),"", $refer_add_card); 
    $refer_add_card 		= str_replace( base_url(),"", $refer_add_card);

    $scroll_top             = (isset( $_SESSION['scrolltop'] )) ? $_SESSION['scrolltop'] : 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>TedExpress</title>
	<meta name="format-detection" content="telephone=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/dore_assets/');?>img/tedlogofavicon.png"/>
	<link href="https://fonts.googleapis.com/css?family=Montserrat%7CRoboto:100,300,400,500,700,900%7CRoboto+Condensed:100,300,400,500,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/icon-font-linea.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/multirange.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/themify-icons.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/effect.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/product.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/home.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/slick.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/slick-theme.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/category.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/owl.theme.default.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/owl.carousel.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/responsive.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app_assets/');?>css/index.css">
</head>
<body style="background-color: #f5f5f4 !important;">

	<!-- Header Box -->
	<div class="wrappage">
		<div class="wrappage">
        <header class="relative full-width">
            <div class=" container-web relative">
                <div class=" container">
					<div class="row">
						<div class=" header-top">
                            <p class="contact_us_header col-md-4 col-xs-12 col-sm-3 clear-margin">
                                <img src="<?php echo base_url('assets/app_assets/'); ?>img/icon_phone_top.png" alt="Icon Phone Top Header" /> Contactez-nous <span class="text-red bold"> +243 999 857 746 </span>
                            </p>
                            <div class="clear-padding menu-header-top text-right col-md-8 col-xs-12 col-sm-6">
                                <ul class="clear-margin">
                                    <li class="relative"><a href="<?php echo site_url('AppCheckOut/siteindex/'); ?>">Mon compte</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="clearfix header-content full-width relative">
                            <div class="clearfix icon-menu-bar">
                                <i class="data-icon data-icon-arrows icon-arrows-hamburger-2 icon-pushmenu js-push-menu" aria-hidden="true"></i>
                            </div>
                            <div class="clearfix logo">
                                <a href="<?php echo site_url('AppIndex/siteindex/'); ?>"><img alt="Logo" src="<?php echo base_url('assets/app_assets/');?>img/icon-logo.png" style="width: 200px; height: 43px; object-fit: cover;" /></a>
                            </div>
                            <div class="clearfix search-box relative float-left">
                                <form method="POST" action="<?php echo site_url('AppSearch/siteindex/');?>" class="">
                                    <div class="clearfix category-box relative">
                                        <select name="categorie_search">
                                            <option value="toutes">Toutes</option>
                                            <?php 
                                                if(!empty( $categories )){
                                                    foreach( $categories as $categorie){
                                                        echo '<option value="'.$categorie['id_categorie'].'">'.$categorie['nom_categorie'].'</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <input type="text" name="nom_article" placeholder="Entrez le nom de  l'article ici  . . .">
                                    <button type="submit" class="animate-default button-hover-red">SEARCH</button>
                                </form>
                            </div>
                            <div class="clearfix cart-website absolute" onclick="showCartBoxDetail()">
                                <img alt="Icon Cart" src=" <?php echo base_url('assets/app_assets/'); ?>img/icon_cart.png" />
								<?php
									if ($userAppData) {
										if( count( $userAppData['commandes'] ) > 0){
											echo '<p class="count-total-shopping absolute ">'.count( $userAppData['commandes']).'</p>';
										}
									}
								?>
                            </div>
                            <div class="cart-detail-header border">
								<?php 
									if( $userAppData ){
										if( count( $userAppData['commandes'] ) > 0 ){
											$total = 0;
											$commandes = $userAppData['commandes'];
											echo '  <div class="relative">';
											foreach ($commandes as $commande) {
												if (isset( $commande['prix_vente'])) {
													$total = $total + ($commande['prix_vente']*$commande['quantite']);
													echo'<div class="product-cart-son">
																<div class="image-product-cart float-left center-vertical-image">
																	<a href="#"><img src="'.base_url('assets/uploads/files/').$commande['photo_article'].'" alt="" /></a>
																</div>
																<div class="info-product-cart float-left">
																	<p class="title-product title-hover-black"><a class="animate-default">'.$commande['nom_article'].'</a></p>
																	<p class="clearfix price-product">'.number_format($commande['prix_vente'],0,'','.').' CDF<span class="total-product-cart-son">(x'.$commande['quantite'].')</span></p>
																</div>
															</div>';
												}
											}
														
											echo '  </div>
													<div class="relative border no-border-l no-border-r total-cart-header">
														<p class="bold clear-margin" style="color: #000;">Total: '.number_format($total,0,'','.').' CDF</p>
													</div>
													<div class="relative btn-cart-header">
														<a href="'.site_url('AppPanier/siteindex/').'" class="uppercase bold animate-default">Voir </a>
														<a href="'.site_url('AppCheckOut/siteindex/null/').'" class="uppercase bold button-hover-red animate-default">Commander</a>
													</div>';

										}else{
											echo '<div class="relative"> <p class="bold clear-margin" style="color: #000;"> Votre panier est vide ! </p></div> ';
										}
									}
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="menu-header-v3 hidden-ipx">
                <div class="container">
                    <div class="row">
                        <!-- Menu Page -->
                        <div class="menu-header full-width">
                            <ul class="clear-margin">
                                <li><a class="animate-default" href="#"><i class="fa fa-list" aria-hidden="true"></i> ARTICLE </a></li>
                            </ul>
                        </div>
                        <!-- End Menu Page -->
                    </div>
                </div>
            </div>
            
        </header>
		<!-- End Header Box -->
		<!-- Content Box -->
		<div class="relative full-width">
			<!-- Breadcrumb -->
			<div class="container-web relative">
				<div class="container">
					<div class="row">
						<div class="breadcrumb-web">
							<ul class="clear-margin">
								<li class="animate-default title-hover-red"><a href="<?php echo site_url('AppIndex/siteindex/'); ?>">Accueille</a></li>
								<li class="animate-default title-hover-red"><a href="#"><?php echo $article['nom_article']; ?></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- End Breadcrumb -->
			<!-- Content Category -->
			<div class="relative container-web">
				<div class="container">
					<div class="row ">
						<!-- Sider Bar -->
						<div class="col-md-3 relative right-padding-default clear-padding" id="slide-bar-category">
							<div class="col-md-12 col-sm-12 col-xs-12 sider-bar-category border bottom-margin-default">
								<p class="title-siderbar bold">CATEGORIES</p>
								<ul class="clear-margin list-siderbar">
									<?php 
										if(!empty( $categories )){
											foreach( $categories as $categorie){
												echo '<li><a href="'.site_url('AppCategorie/siteindex/').$categorie['id_categorie'].'">'.$categorie['nom_categorie'].'</a></li>';
											}
										}
									?>
								</ul>
							</div>
						</div>
						<!-- End Sider Bar Box -->
						<!-- Content Category -->
						<div class="col-md-9 relative clear-padding">
							<!-- Product Content Detail -->
							<div class="top-product-detail relative ">
								<div class="row">
									<!-- Slide Product Detail -->
									<div class="col-md-7 relative col-sm-12 col-xs-12">
										<div id="owl-big-slide" class="relative sync-owl-big-image">
											<?php 
												if($article){
													echo '	<div class="item center-vertical-image">
																<img  src=" '.base_url('assets/uploads/files/').$article['photo_article'].'" alt="Image Big Slide">
															</div>';
													for ($i=2; $i <=4 ; $i++) { 
														if (strlen( $article['photo_article_'.$i]) >=4 ) {
															echo'<div class="item center-vertical-image">
																	<img  src="'.base_url('assets/uploads/files/').$article['photo_article_'.$i].'" alt="Image Big Slide">
																</div>';
														}
													}
												}
											?>
										</div>
										<div class="relative thumbnail-slide-detail">
											<div id="owl-thumbnail-slide" class="sync-owl-thumbnail-image" data-items="3,4,3,2">
												<?php 
                                                    if($article){
                                                        echo '<div class="item center-vertical-image">
                                                                    <img  src=" '.base_url('assets/uploads/files/').$article['photo_article'].'" alt="Image Big Slide">
                                                            </div>';
                                                        for ($i=2; $i <=4 ; $i++) { 
                                                            if (strlen( $article['photo_article_'.$i]) >=4 ) {
                                                                echo'<div class="item center-vertical-image">
                                                                    <img  src="'.base_url('assets/uploads/files/').$article['photo_article_'.$i].'" alt="Image Big Slide">
                                                                </div>';
                                                            }
                                                        }
                                                    }
                                                ?>
											</div>
											<div class="relative nav-prev-detail btn-slide-detail"></div>
											<div class="relative nav-next-detail btn-slide-detail"></div>
										</div>
									</div>
									<!-- Info Top Product -->
									<div class="col-md-5 col-sm-12 col-xs-12">
										<div class="name-ranking-product relative bottom-padding-15-default bottom-margin-15-default border no-border-r no-border-t no-border-l">
											<h1 class="name-product"><?php if(!empty($article)) echo $article['nom_article'];?></h1>
											<div class=" ranking-color ">
												<i class="fa fa-star" aria-hidden="true"></i>
												<i class="fa fa-star" aria-hidden="true"></i>
												<i class="fa fa-star" aria-hidden="true"></i>
												<i class="fa fa-star-half" aria-hidden="true"></i>
												<i class="fa fa-star-o" aria-hidden="true"></i>
											</div>
											<p class="clearfix price-product">
												<span class="price-old"> <?php echo number_format(($article['prix_vente']+500),0,'','.');?> CDF</span> <?php echo number_format(($article['prix_vente']),0,'','.') ;?> CDF
											</p>
											<div class="product-code clearfix full-width">
												<p class="float-left relative">Code: <?php echo  $article['code']; ?></p>
												<p class="float-left clear-margin">Disponibilté: <span class="text-green">En stock</span></p>
											</div>
										</div>
										<div class="relative intro-product-detail bottom-margin-15-default bottom-padding-15-default border no-border-r no-border-t no-border-l">
											<p class="clear-margin">
												<h4> Description : </h4> <?php echo $article['text_article'];?>
											</p>										
										</div>

										<form method="POST"  action="<?php echo site_url('AppArticle/sitepanier/') ?>" name="form_add_article">
											<div class="relative option-product-detail bottom-padding-15-default border no-border-r no-border-t no-border-l">
												<p class="bold clear-margin bottom-margin-15-default">Options Disponibles:</p>
												
												<div class="relative option-product-2 clearfix">
													<div class="option-product-son float-left right-margin-default">
														<p class="float-left">Qté:</p>
														<input type="number" class="left-margin-15-default" min="01" step="1" max="1000" value="1" name="quantite" id="quantite-article">
														<input type="number" value="<?php if($article) echo $article['id_article']; ?>" name="id_article" hidden>
														<input type="text"   value="<?php if($refer) echo $refer; ?>" name="refer" hidden>
														<input type="text"   value="<?php if($refer_add_card) echo $refer_add_card; ?>" name="refer_add_card" hidden>
														<input type="text"   value="<?php if($userAppData) echo $userAppData['usertel']; ?>" name="usertel" hidden>
													</div>
												</div>
											</div>
											<div class="relative button-product-list clearfix full-width clear-margin">
												<ul class="clear-margin top-margin-default clearfix bottom-margin-default full-width">
													<li class="full-width">
														<center>
															<button style="display:<?php echo $displayWhat; ?>; border-radius: 5px; border: none; color:#fff; background-color: #4bca5a; height: 40px; padding-left: 5px; padding-right: 5px;" class="animate-default">
																<a target="_blank" style="color: #fff; background-color: #4bca5a;" href="https://api.whatsapp.com/send?phone=243858810668&text=<?php echo $message?>"> <i class="fa fa-whatsapp"></i>  Whatsapp </a>    
															</button>
															<button style="display:<?php echo $displayForm; ?>; border: none; border-radius: 5px; color:#fff; background-color: #2196f3; height: 40px; padding-left: 5px; padding-right: 5px;" class="animate-default full-width" id="ajout-panier" type="submit">Ajouter au Panier</button>
														</center>
													</li>
												</ul>
											</div>
										</form>

									</div>
								</div>
							</div>
							
							<div class="slide-product-bottom relative">
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12 relative bottom slide-related-product">
										<p class="bold title-slide-product-bottom">ARTICLES SIMILAIRES 
											<a href="<?php echo site_url('AppCategorie/siteindex/').$arcticles_similaires[0]['id_foreign_categorie'];?>">
												<span style="float: right !important;"> PLUS >></span>
											</a>
										</p>
										<div class="owl-theme owl-carousel" data-items="1,2,3">
											<!-- Start by here Pascal Show other product -->
											<?php 
												if($arcticles_similaires){
													foreach ($arcticles_similaires as $key => $arcticles_similaire) {
														if( $key<=5 AND ($arcticles_similaires[$key]['id_article'] <> $article['id_article']) ){
															
															$displayForm 	=  ($article['id_foreign_categorie'] == 11) ? 'none'  : 'block'; 
															$displayWhat 	=  ($article['id_foreign_categorie'] == 11) ? 'block' : 'none'; 
															$message 		=  "Bonjour je suis interressé par votre véhicule  [ ".$article['nom_article']." ] ID système: [ ".$article['id_article']." ]";
					
															echo ' <div class="items">
																		<div class="full-width product-category relative">
																			<div class="image-product  relative overfollow-hidden">
																				<div class="center-vertical-image">
																					<img style="height: 270px !important; width:270px !important; object-fit: scale-down;" src="'.base_url('assets/uploads/files/').$arcticles_similaire['photo_article'].'" alt="'.$arcticles_similaire['nom_article'].'">
																				</div>
																			</div>
																			<h3 class="title-product animate-default title-hover-black clearfix full-width"><a href="'.site_url('AppArticle/siteindex/').$arcticles_similaire['id_article'].'">'.$arcticles_similaire['nom_article'].'</a></h3>
																			<p class="clearfix price-product"><span class="price-old">'.number_format(($arcticles_similaire['prix_vente']+500),2,',',' ').' CDF</span> '.number_format(($arcticles_similaire['prix_vente']),2,',',' ').' CDF</p>
					
																			<center>
																				<button style="border-radius: 5px; border: none; color:#fff; background-color: #4bca5a; height: 33px; padding-left: 5px; padding-right: 5px; display:'.$displayWhat.';" class="animate-default" id="ajout-panier" >
																					<a target="_blank" style="color: #fff;" href="https://api.whatsapp.com/send?phone=243858810668&text='.$message.'"> <i class="fa fa-whatsapp"></i>  Whatsapp </a>    
																				</button>
																				<form class="form_ajout_panier" method="POST" action="'.site_url('AppArticle/sitepanier/').'" name="form_add_article_'.$arcticles_similaire['id_article'].'" style="display:'.$displayForm.';"> 
																					<input type="number" value="1" name="quantite" hidden>
																					<input type="number" value="'.$arcticles_similaire['id_article'].'" name="id_article" hidden>
																					<input type="text"   value="'.$refer.'" name="refer" hidden>
																					<input type="text"   value="'.$refer_add_card.'" name="refer_add_card" hidden>
																					<input type="text"   value="'.$userAppData['usertel'].'" name="usertel" hidden>                                                                                               
																					<button style="width:80%; border-radius: 5px; border: none; color:#fff; background-color: #2196f3; height: 33px; padding-left: 5px; padding-right: 5px;" class="animate-default" id="ajout-panier" type="submit">Ajouter au Panier</button>
																				</form>
																			</center>
																		</div>
																	</div>';
															
															' <div class="clearfix relative product-no-ranking border-collapsed-element percent-content-2">
																		<div class="effect-hover-zoom center-vertical-image">
																			<img style="height: 145px !important; object-fit: scale-down;" src="'.base_url('assets/uploads/files/').$arcticles_similaire['photo_article'].'" alt="'.$arcticles_similaire['nom_article'].'">
																		</div>
																		<div class="clearfix absolute name-product-no-ranking">
																			<p style="font-size: 25px; " class="title-product clearfix full-width title-hover-black"><a href="'.site_url('AppArticle/index/').$arcticles_similaire['id_article'].'" style="color: #000; text-shadow: 2px 2px 3px #fff;">'.$arcticles_similaire['nom_article'].'</a></p>
																			<p style="margin: -2px !important; z-index:100 !important; " class="clearfix price-product">
																				<br>
																				<span style="color:red;"><a href="'.site_url('AppArticle/index/').$arcticles_similaire['id_article'].'" style="color: #000; text-shadow: 2px 2px 3px #fff;">'.number_format($arcticles_similaire['prix_vente'],0,'','.').' CDF</a><span>
																			</p>
																			<p>
																				<center>
																					<button style="border-radius: 5px; border: none; color:#fff; background-color: #4bca5a; height: 33px; padding-left: 5px; padding-right: 5px; display:'.$displayWhat.';" class="animate-default" id="ajout-panier" >
																						<a target="_blank" style="color: #fff;" href="https://api.whatsapp.com/send?phone=243858810668&text='.$message.'"> <i class="fa fa-whatsapp"></i>  Whatsapp </a>    
																					</button>
																					<form class="form_ajout_panier" method="POST" action="'.site_url('AppArticle/panier').'" name="form_add_article_'.$arcticles_similaire['id_article'].'" style="display:'.$displayForm.';"> 
																						<input type="number" value="1" name="quantite" hidden>
																						<input type="number" value="'.$arcticles_similaire['id_article'].'" name="id_article" hidden>
																						<input type="text"   value="'.$refer.'" name="refer" hidden>
																						<input type="text"   value="'.$refer_add_card.'" name="refer_add_card" hidden>
																						<input type="text"   value="'.$userAppData['usertel'].'" name="usertel" hidden>                                                                                               
																						<button style="border-radius: 5px; border: none; color:#fff; background-color: #2196f3; height: 33px; padding-left: 5px; padding-right: 5px;" class="animate-default" id="ajout-panier" type="submit">Ajouter au Panier</button>
																					</form>
																				</center>
																			</p>
																		</div>
																	</div>';
														}
													}
												}
											?>
										</div>
									</div>
								</div>
							</div>
							<!-- End Product Content Category -->
						</div>
					</div>
				</div>
			</div>
			<!-- End Sider Bar -->
			<!-- Support -->
			<div class=" support-box full-width bg-red support_box_v2" style="margin-top: 15%;">
				<div class="container-web">
					<div class=" container">
						<div class="row">
							<div class=" support-box-info relative col-md-3 col-sm-3 col-xs-6">
								<img src="<?php echo base_url('assets/app_assets/') ?>img//icon_free_ship_white-min.png" alt="Icon Free Ship" class="absolute" />
								<p>Livraison à</p>
								<p>5000 CDF</p>
							</div>
							<div class=" support-box-info relative col-md-3 col-sm-3 col-xs-6">
								<img src="<?php echo base_url('assets/app_assets/') ?>img/icon_support_white-min.png" alt="Icon Supports" class="absolute">
								<p>Support</p>
								<p>24/7</p>
							</div>
							<div class=" support-box-info relative col-md-3 col-sm-3 col-xs-6">
								<img src="<?php echo base_url('assets/app_assets/') ?>img/icon_patner_white-min.png" alt="Icon partner" class="absolute">
								<p>Travaillez avec nous</p>
								<p>Devenez partenaire de TedExpress</p>
							</div>
							<div class=" support-box-info relative col-md-3 col-sm-3 col-xs-6">
								<img src="<?php echo base_url('assets/app_assets/') ?>img/icon_phone_table_white-min.png" alt="Icon Phone Tablet" class="absolute">
									<p>Contactez nous</p>
									<p>+243 999 857 746</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- End Content Box -->
		<!-- Footer Box -->
		<footer class="relative full-width">
			<div class="clearfix container-web relative">
				<div class=" container clear-padding">
					<div class="row">
						<div class="clearfix col-md-3 col-sm-6 col-xs-12 text-footer">
							<p>Mon compte</p>
							<ul class="list-footer">
								<li><a href="<?php echo site_url('AppCheckOut/siteindex/'); ?>">Mon compte</a></li>
							</ul>
						</div>
						<div class="clearfix col-md-3 col-sm-6 col-xs-12 text-footer">
							<p>Informations</p>
							<ul class="list-footer">
								<li><a href="#">Termes & Conditions</a></li>
								<li><a href="#">Comment Acheter sur TedExpress</a></li>
								<li><a href="#">Comment Payer Sur TedExpress</a></li>
								<li><a href="#">Comment Devenir Partenaire </a></li>
							</ul>
						</div>
						<div class="clearfix col-md-3 col-sm-6 col-xs-12 text-footer">
							<p>Nos services</p>
							<ul class="list-footer">
								<li><a href="#">Vente ligne</a></li>
								<li><a href="#">Location des maisons</a></li>
								<li><a href="#">Vente des véhicules</a></li>
								<li><a href="#">Commandes en chine</a></li>
							</ul>
						</div>
						<div class="clearfix col-md-3 col-sm-6 col-xs-12 text-footer">
							<p>Contactez nous</p>
							<ul class="icon-footer">
								<li><i class="fa fa-home" aria-hidden="true"></i> Texaco Bel-air, Lubumbashi RDC,</li>
								<li>
									<a href="mailto:business@TedExpress.com" target="__blank">
										<i class="fa fa-envelope" aria-hidden="true"></i> business@TedExpress.com</li>
									</a>
								<li>
									<a href="tel:243858810668" target="__blank">
										<i class="fa fa-phone" aria-hidden="true"></i> +243 999 857 746</li>
									</a>
								<li><i class="fa fa-clock-o" aria-hidden="true"></i> 08:00 - 21:00</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class=" bottom-footer full-width">
				<div class="clearfix container-web">
					<div class=" container">
						<div class="row">
							<div class="clearfix col-md-7 clear-padding copyright">
								<p>Copyright © <?php echo date('Y') ?> by TedExpress. Tous droits reservés.</p>
							</div>
							<div class="clearfix footer-icon-bottom col-md-5 float-right clear-padding">
								<div class="icon_logo_footer float-right">
									<img src="<?php echo base_url('assets/app_assets/') ?>img/image_payment_footer-min.png" alt="">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</footer>

	</div>
	</div>
	<!-- End Footer Box -->
    <div id="javascript-divider"></div>

	<script src="<?php echo base_url('assets/app_assets/');?>js/jquery-3.6.0.js"></script>
	<script src="<?php echo base_url('assets/app_assets/');?>js/bootstrap.min.js" defer></script>
	<script src="<?php echo base_url('assets/app_assets/');?>js/multirange.js" defer></script>
	<script src="<?php echo base_url('assets/app_assets/');?>js/slick.min.js" defer></script>
	<script src="<?php echo base_url('assets/app_assets/');?>js/owl.carousel.min.js" defer></script>
	<script src="<?php echo base_url('assets/app_assets/');?>js/scripts.js" defer></script>
    <script>

        var scrollAt = "<?php echo $scroll_top; ?>";
        scrollAt     = parseInt(scrollAt) - 100;

        function scrollAtPosition(position) {

            setTimeout( function ()  {
                $('html,body').animate({scrollTop: position }, 1000);
                setTimeout( function () {
                    "<?php $_SESSION['scrolltop'] = 0; ?>";
                }, 1000);
            }, 2000);

        }scrollAtPosition(scrollAt);

        function sendFormApp() {
            $("form").each(function (index, element) {
                $(element).submit(function (e) { 

                    var url_to_send     = "<?php echo site_url('AppIndex/setScrollTop/'); ?>";
                    var scrollTop       = $(window).scrollTop();

                    $.ajax({
                        type: "POST",
                        url: url_to_send,
                        data:  {'scrolltop': scrollTop},
                        success: function (response) {
                            console.log('session scrolltop good setted '+response);
                        },
                        error: function (response) { 
                            alert('Problème de connexion');
                        }
                    });    
                }); 
            });
        }sendFormApp();

        function showSearchBox() {
            $("#searchBoxContainer").css('bottom','0');
            $("#searchBoxHider").css('bottom','0');
        }

        function hideSearchBox() {
            $("#searchBoxHider").click(function (e) { 
                $("#searchBoxContainer").css('bottom','-100%');
                $("#searchBoxHider").css('bottom','-100%');
            });
        }

    </script>
</body>
</html>