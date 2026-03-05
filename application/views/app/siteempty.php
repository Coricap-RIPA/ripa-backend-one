<?php 
    $refer 			=  $this->agent->referrer();
    $refer      	= (strlen($refer) >3) ? $refer : current_url();
    $refer 			= str_replace( site_url(),"", $refer); 
    $refer 			= str_replace( base_url(),"", $refer); 
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
                                <li><a class="animate-default" href="<?php echo site_url('AppIndex/siteindex/'); ?>"><i class="fa fa-list" aria-hidden="true"></i> Accueille</a></li>
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
							<li class="animate-default title-hover-red"><a href="#">Non trouvé</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- End Breadcrumb -->
		<!-- Content 404 Page -->
		<div class="relative container-web">
			<div class="container">
				<div class="row relative">
					<div class=" relative content-404">
						<div class="title-404">
							<p class="clear-margin">4</p>
							<img src="<?php echo base_url('assets/app_assets/'); ?>img/icon_404-min.png" alt="Image 404" />
							<p class="clear-margin">4</p>
						</div>
						<p>Nous ne trouvons pas ce que vous recherchez </p>
						<div class="btn-back">
							<p> Veuillez essayer l'une des pages suivantes </p>
							<a href="<?php echo site_url('AppIndex/siteindex/'); ?>" class="animate-default button-hover-red">Accueille</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Content 404 Page -->
		<!-- Support -->
		<div class=" support-box full-width support_box_v2" style="margin-top: 15%; background:#28b4e8;">
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
	<!-- End Footer Box -->
	<div id="javascript-divider"></div>
	<?php echo $footer ?>

</body>
</html>