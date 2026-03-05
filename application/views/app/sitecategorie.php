<?php 
    $refer 			=  $this->agent->referrer();
    $refer      	= (strlen($refer) >3) ? $refer : current_url();
    $refer 			= str_replace( site_url(),"", $refer); 
    $refer 			= str_replace( base_url(),"", $refer); 

	$index_article = count($articles) - 1;
	$index_article = mt_rand(0, $index_article);

	$referalFullLink        = $this->agent->referrer();

	$refer_add_card         = current_url();
    $refer_add_card 		= str_replace( site_url(),"", $refer_add_card); 
    $refer_add_card 		= str_replace( base_url(),"", $refer_add_card);
?>

<?php echo $header; ?>
<body style="background-color: #f5f5f4 !important;">

	<!-- Header Box -->
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
                                <li><a class="animate-default" href="#"><i class="fa fa-list" aria-hidden="true"></i> <?php echo strtoupper( $categorie_article['nom_categorie'] ); ?> </a></li>
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
							<li class="animate-default title-hover-red"><a><?php echo $categorie_article['nom_categorie']; ?></a></li>
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
									if(!empty( $sous_categories )){
										foreach( $sous_categories as $sous_categorie){
											if(!empty($sous_categorie)){
												echo '<li><a href="'.site_url('AppCategorie/sitefilter/').$categorie_article['id_categorie'].'/'.$sous_categorie['id_sous_categorie'].'">'.$sous_categorie['nom_sous_categorie'].'</a></li>';
											}
										}
									}
								?>
							</ul>
						</div>
					</div>
					<!-- End Sider Bar Box -->
					<!-- Content Category -->
					<div class="col-md-9 relative clear-padding">
						<div class="banner-top-category-page bottom-margin-default effect-bubba zoom-image-hover overfollow-hidden relative full-width">
							<img src="<?php echo base_url('assets/uploads/files/'.$articles[$index_article]['photo_article']); ?>" alt="" style="width:875px; height:385px; object-fit: cover;"/>
							<a href="#"></a>
						</div>
						<div class="bar-category bottom-margin-default border no-border-r no-border-l no-border-t">
							<div class="row">
								<div class="col-md-5 col-sm-5 col-xs-4">
									<p class="title-category-page clear-margin"><?php echo $categorie_article['nom_categorie']; ?></p>
								</div>
							</div>
						</div>
						<!-- Product Content Category -->
						<div class="row">

							<?php 
								if( !empty($articles) ){
									foreach ($articles as $key => $article) {

										$displayForm 	=  ($article['id_foreign_categorie'] == 11) ? 'none'  : 'block'; 
										$displayWhat 	=  ($article['id_foreign_categorie'] == 11) ? 'block' : 'none'; 
										$message 		=  "Bonjour je suis interressé par votre véhicule  [ ".$article['nom_article']." ] ID système: [ ".$article['id_article']." ]";

										if( is_int($key)){
											echo '  <div class="col-md-4 col-sm-4 col-xs-12 product-category relative effect-hover-boxshadow animate-default" style="margin-bottom:4%;">
														<div class="image-product relative overfollow-hidden">
															<div class="center-vertical-image">
																<img src="'.base_url('assets/uploads/files/').$article['photo_article'].'" alt="'.$article['nom_article'].'" style="width:270px; height:270px; object-fit:cover;" >
															</div>
															<a href="'.site_url('AppArticle/siteindex/').$article['id_article'].'"></a>
														</div>
														<h3 class="title-product clearfix full-width title-hover-black"><a href="'.site_url('AppArticle/siteindex/').$article['id_article'].'">'.$article['nom_article'].'</a></h3>
														<p class="clearfix price-product"><span class="price-old">'.number_format(($article['prix_vente']+500),0,'','.').' CDF</span> '.number_format(($article['prix_vente']),0,'','.').' CDF</p>
														<center>
															<button style="border-radius: 5px; border: none; color:#fff; background-color: #4bca5a; height: 33px; padding-left: 5px; padding-right: 5px; display:'.$displayWhat.';" class="animate-default" id="ajout-panier" >
																<a target="_blank" style="color: #fff;" href="https://api.whatsapp.com/send?phone=243858810668&text='.$message.'"> <i class="fa fa-whatsapp"></i>  Whatsapp </a>    
															</button>
															<form class="form_ajout_panier" method="POST" action="'.site_url('AppArticle/sitepanier/').'" name="form_add_article_'.$article['id_article'].'" style="display:'.$displayForm.';"> 
																<input type="number" value="1" name="quantite" hidden>
																<input type="number" value="'.$article['id_article'].'" name="id_article" hidden>
																<input type="text"   value="'.$refer.'" name="refer" hidden>
																<input type="text"   value="'.$refer_add_card.'" name="refer_add_card" hidden>
																<input type="text"   value="'.$userAppData['usertel'].'" name="usertel" hidden>                                                                                               
																<button style="width:80%; border-radius: 5px; border: none; color:#fff; background-color: #2196f3; height: 33px; padding-left: 5px; padding-right: 5px;" class="animate-default" id="ajout-panier" type="submit">Ajouter au Panier</button>
															</form>
														</center>
													</div>';
										}
									}
								}
							?>

						</div>
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
							<p>Livraison </p>
							<p>En fonction de la distance</p>
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


	<div id="javascript-divider"></div>
	<?php echo $footer ?>

</body>
</html>