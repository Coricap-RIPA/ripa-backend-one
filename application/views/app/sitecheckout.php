<?php 
	$total_commande = 0;
	$compter   		= 0;

    $refer 		=  $this->agent->referrer();
    $refer      = (strlen($refer) >3) ? $refer : current_url();
    $refer 		= str_replace( site_url(),"", $refer); 
    $refer 		= str_replace( base_url(),"", $refer); 
?>

<?php echo $header;?>

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
								<a href="<?php echo site_url('AppIndex/siteindex/'); ?>"><img alt="Logo" src="<?php echo base_url('assets/app_assets/');?>img/icon-logo.png" style="width: 174px; height: 43px; object-fit: scale-down;" /></a>
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
								<li><a class="animate-default" href="#"><i class="fa fa-list" aria-hidden="true"></i> <?php echo 'COMMANDE'; ?> </a></li>
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
							<li class="animate-default title-hover-red"><a href="<?php echo site_url('AppIndex/siteindex/');?>">Accueille</a></li>
							<li class="animate-default title-hover-red"><a href="#">Commande</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- End Breadcrumb -->
		<!-- Content Checkout -->
		<div class="relative container-web">
			<div class="container">
				<div class="row relative" style="display: <?php if(strlen($message ) > 5) echo "none"; else echo "block";  ?>">
					<form method="POST" action="<?php echo site_url('AppCheckOut/siteprocessed/') ?>"  name="form_checkout" >
						<!-- Content Shoping Cart -->
						<div class="col-md-8 col-sm-12 col-xs-12 relative left-content-shoping clear-padding-left">
							<p class="title-shoping-cart">Détails de Facturation</p>
							<div class="relative clearfix full-width">
								<div class="col-md-6 col-sm-6 col-xs-12 clearfix clear-padding-left clear-padding-480 relative form-input" >
									<label>Nom *</label>
									<input class="full-width" type="text" id="form-id-input-nom-client" name="nomclient" <?php echo  ($userAppData['username'] == "fromatter_default_user") ? "" : "value ='".$userAppData['username']."'   " ?> <?php if( $userAppData['username'] == "fromatter_default_user" )  echo "required"  ?> <?php if( $userAppData['username'] <> "fromatter_default_user" AND $userAppData['username'] <> "dee_express_default_user" )  echo "readonly"  ?> >
								</div>
								<div class="col-md-6 col-sm-6 col-xs-12 clearfix clear-padding-right clear-padding-480 relative form-input" >
									<label>Prénom *</label>
									<input class="full-width" type="text" id="form-id-input-prenom-client" name="prenomclient" value="<?php echo  ($userAppData['prenom'] == "fromatter_default_user") ? "" : $userAppData['prenom']; ?>" <?php if( $userAppData['prenom'] == "fromatter_default_user" )  echo "required"  ?> <?php if( $userAppData['prenom'] <> "fromatter_default_user" AND $userAppData['prenom'] <> "dee_express_default_user" )  echo "readonly"  ?>  >
								</div>
							</div>
							<div class="form-input full-width clearfix relative">
								<label>Tél Whatsapp *</label> 
								<input class="full-width" type="number" id="form-id-input-tel-client" name="telclient" placeholder="ex: 243970000000"  value="<?php echo  ($userAppData['username'] == "fromatter_default_user") ? "" : $userAppData['usertel']; ?>" <?php if( $userAppData['username'] == "fromatter_default_user" )  echo "required"  ?> <?php if( ($userAppData['username'] <> "fromatter_default_user") AND (trim($userAppData['username']) <> "dee_express_default_user") )  echo "readonly"  ?> >
							</div>
							<div class="form-input full-width clearfix relative">
								<label>Addresse de Livraison *</label>
								<input class="full-width" type="text" id="form-id-input-adresse-client" name="addresclient" value="<?php if( isset($userAppData['adresse']) ){ echo  (  $userAppData['adresse'] == "fromatter_default_user") ? "" : $userAppData['adresse']; } ?>" required>
							</div>
							<div class="form-input clearfix full-width relative">
								<label>Note de la commandes</label>
								<textarea placeholder="Ex: Autres détails de livraison " class="full-width" name="notecommandeclient" rows="4"></textarea>
							</div>
							<input value="<?php if( $userAppData ) echo $userAppData['usertel']; ?>" type="text" name="id_tel_user" hidden>
							<input value="<?php if( $refer) echo  $refer; ?>" type="text" name="refer" hidden>
						</div>
						<!-- End Content Shoping Cart -->
						<!-- Content Right -->
						<div class="col-md-4 col-sm-12 col-xs-12 right-content-shoping relative clear-padding-right">
							<p class="title-shoping-cart">Votre Commande</p>
							<div class="full-width relative overfollow-hidden">
								<?php 
									if ($userAppData) {
										$commandes = $userAppData['commandes'];
										if(count($commandes)>=1){
											foreach ($commandes as $key => $commande) {
												if(isset( $commande['prix_vente']) ){
													$total_commande = $total_commande + ($commande['prix_vente']*$commande['quantite']);
													
													echo'   <div class="relative clearfix full-width product-order-sidebar border no-border-t no-border-r no-border-l">
																<div class="image-product-order-sidebar center-vertical-image">
																	<img src="'.base_url('assets/uploads/files/').$commande['photo_article'].'" alt="" />
																</div>
																<div class="relative info-product-order-sidebar">
																	<p class="title-product top-margin-15-default animate-default title-hover-black"><a href="'.base_url('AppArticle/index/').$commande['id_article'].'">'.$commande['nom_article'].' (x'.$commande['quantite'].')</a></p>
																	<p class="price-product">'.number_format($commande['prix_vente'],0,'','.').' CDF</p>
																</div>
															</div>';
												}
											}
										}
									}
								?>
								
							</div>
							<p class="title-shoping-cart">Total </p>
							<div class="full-width relative cart-total bg-gray  clearfix">
								<div class="relative justify-content bottom-padding-15-default border no-border-t no-border-r no-border-l">
									<p>Subtotal</p>
									<p class="text-red price-shoping-cart"><?php echo number_format($total_commande,0,'','.')?> CDF</p>
								</div>
								<div class="relative border top-margin-15-default bottom-padding-15-default no-border-t no-border-r no-border-l">
									<p class="bottom-margin-15-default">Livraison</p>
									<div class="relative justify-content">
										<ul class="check-box-custom title-check-box-black clear-margin clear-margin">
											<li>
												<label>Frais de Livraison 
													<input type="radio" name="shipping" checked>
													<span class="checkmark"></span>
												</label>
											</li>
										</ul>
										<p class="price-gray-sidebar"> EN FONCTION DE LA DISTANCE  </p>
									</div>
								</div>
								<div class="relative justify-content top-margin-15-default">
									<p class="bold">Total</p>
									<p class="text-red price-shoping-cart"><?php echo number_format(($total_commande),0,'','.') ?> CDF</p>
								</div>
							</div>
							<button class="btn-proceed-checkout full-width top-margin-15-default">Valider ma commande</button>
						</div>
						<!-- End Content Right -->
					</form>	
				</div>
				<div class="row relative" style="display: <?php if(strlen($message ) > 5) echo "block"; else echo "none";  ?>">
					<br><br>
					<center>
						<?php echo $message; ?>
						<br><br>
						<a href="<?php echo site_url('AppIndex/siteindex/')?>">
							<button class="btn-proceed-checkout full-width top-margin-15-default">Aller à l'acceuil</button>
						</a>
					</center>
				</div>
			</div>
		</div>
		<!-- End Content Checkout -->
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