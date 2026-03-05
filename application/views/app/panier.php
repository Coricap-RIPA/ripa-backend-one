<?php 
	$total_commande = 0;
	$compter   		= 0;	
    //$refer 			=  $this->agent->referrer();
    $refer 			= current_url();
    $refer      	= (strlen($refer) >3) ? $refer : current_url();
    $refer 			= str_replace( site_url(),"", $refer); 
    $refer 			= str_replace( base_url(),"", $refer); 

	$refer_add_card         = current_url();
    $refer_add_card 		= str_replace( site_url(),"", $refer_add_card); 
    $refer_add_card 		= str_replace( base_url(),"", $refer_add_card);
?>

<?php echo $header ?>

<body style="background-color: #f5f5f4 !important;"> 
	<!-- push menu-->
    <div class="pushmenu menu-home5">
        <div class="menu-push">
            <span class="close-left js-close"><i class="fa fa-times f-20"></i></span>
            <div class="clearfix"></div>
            <ul class="nav-home5 js-menubar">
                <br>
                <?php 
                    if($categories){
                        foreach ($categories as $key => $cat) {
                            echo '<li class="level1 active dropdown"><a href="'.site_url('AppCategorie/index/').$cat['id_categorie'].'"><i class="fa fa-'.$cat['icon_categorie'].'"></i> '.$cat['nom_categorie'].'</a>';
                                if (!empty($cat['sous_categories'])) {
                                   echo '<span class="icon-sub-menu"></span>
                                        <div class="menu-level1 js-open-menu">
                                            <ul class="level1">';
                                                foreach ($cat['sous_categories'] as $key => $sous_cat) {
                                                    echo '<li class="level2">
                                                            <a href="'.site_url('AppCategorie/filter/').$cat['id_categorie'].'/'.$sous_cat['id_sous_categorie'].'" title="'.$sous_cat['nom_sous_categorie'].'">
                                                                <i class="fa fa-'.$sous_cat['icon_sous_categorie'].'"></i>
                                                                '.$sous_cat['nom_sous_categorie'].' 
                                                            </a>
                                                          </li>
                                                        ';
                                                }
                                    echo    '</ul>
                                            <div class="clearfix"></div>
                                        </div>';
                                }
                            echo '</li>';
                        }
                    }

                    if(!$pharmacies){
                        echo '<li class="level1 active dropdown"><a href="'.site_url('AppFournisseur/index/').$pharmacies['id_categorie'].'"><i class="fa fa-hospital-o"></i> Santé </a>
                                <span class="icon-sub-menu"></span>
                                <div class="menu-level1 js-open-menu">
                                    <ul class="level1">';
                        foreach ($pharmacies as $key => $pharmacie) {
                                if (is_array( $pharmacie )) {
                                   echo '<li class="level2">
                                            <a href="'.site_url('AppFournisseur/filter/').$pharmacie['id_fournisseur'].'/'.$pharmacies['id_categorie'].'" title="'.$pharmacie['nom_fournisseur'].'">
                                                <i class="fa fa-circle "></i>
                                                '.$pharmacie['nom_fournisseur'].' 
                                            </a>
                                        </li>';
                                }
                        }
                        echo '      </ul>
                                    <div class="clearfix"></div>
                                </div>
                            </li>';
                    }

                    if(!$restaurants){
                        echo '<li class="level1 active dropdown"><a href="'.site_url('AppFournisseur/index/').$restaurants['id_categorie'].'"><i class="fa fa-cutlery"></i> Restauration </a>
                                <span class="icon-sub-menu"></span>
                                <div class="menu-level1 js-open-menu">
                                    <ul class="level1">';
                        foreach ($restaurants as $key => $restaurant) {
                                if (is_array( $restaurant )) {
                                   echo '<li class="level2">
                                            <a href="'.site_url('AppFournisseur/filter/').$restaurant['id_fournisseur'].'/'.$restaurants['id_categorie'].'" title="'.$restaurant['nom_fournisseur'].'">
                                                <i class="fa fa-circle "></i>
                                                '.$restaurant['nom_fournisseur'].' 
                                            </a>
                                        </li>';
                                }
                        }
                        echo '      </ul>
                                    <div class="clearfix"></div>
                                </div>
                            </li>';
                    }

                    if(!$supermarchets){
                        echo '<li class="level1 active dropdown"><a href="'.site_url('AppFournisseur/index/').$supermarchets['id_categorie'].'"><i class="fa fa-shopping-cart"></i> Super Marchés </a>
                                <span class="icon-sub-menu"></span>
                                <div class="menu-level1 js-open-menu">
                                    <ul class="level1">';
                        foreach ($supermarchets as $key => $supermarchet) {
                                if (is_array( $supermarchet )) {
                                   echo '<li class="level2">
                                            <a href="'.site_url('AppFournisseur/filter/').$supermarchet['id_fournisseur'].'/'.$supermarchets['id_categorie'].'" title="'.$supermarchet['nom_fournisseur'].'">
                                                <i class="fa fa-circle "></i>
                                                '.$supermarchet['nom_fournisseur'].' 
                                            </a>
                                        </li>';
                                }
                        }
                        echo '      </ul>
                                    <div class="clearfix"></div>
                                </div>
                            </li>';
                    }
                    
                ?>
            </ul>
        </div>
    </div>
    <!-- end push menu-->
	<!-- Header Box -->
	<div class="wrappage">
        <header class="relative full-width box-shadow">
			<div class="clearfix container-web relative" style="color: #fff; background-color: #240054;">
				<div class=" container">
					<div class="row">
						<div class=" header-top">
							<p class="contact_us_header col-md-4 col-xs-12 col-sm-3 clear-margin" style="color: #fff;">
								<i class="fa fa-phone-square"> </i>  Contactez-nous <span class="text-white bold"> +243 999 857 746 / +243 896 299 558</span>
							</p>
							<div class="clear-padding menu-header-top text-right col-md-8 col-xs-12 col-sm-6">
								<ul class="clear-margin">
									<li class="relative"><a href="https://web.facebook.com/TedExpress.business/" target="_blank" style="color: #fff;"> <i class="fa fa-facebook-square"></i> Facebook</a></li>
									<li class="relative"><a href="https://www.instagram.com/TedExpressbusiness/" target="_blank" style="color: #fff;"> <i class="fa fa-instagram"></i> Instagram</a></li>
									<li class="relative"><a href="https://www.youtube.com/channel/UCPlSMOFKYSXeIV7l85GxIQQ" target="_blank" style="color: #fff;"><i class="fa fa-youtube-square"></i> Youtube</a></li>
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
								<a href="<?php echo site_url('AppIndex/index/')?>"><img alt="Logo" src="<?php echo base_url('assets/app_assets/');?>img/icon-logo.png" class="logo-index" style="left: -20px;" /></a>
							</div>

							<div class="clearfix icon-search-mobile absolute" style="right: 41px !important; top: 48% !important;">
								<i onclick="showSearchBox()" class="data-icon data-icon-basic icon-basic-magnifier" ></i>
							</div>

							<div class="clearfix cart-website absolute" onclick="showCartBoxDetail()">
								<i class="fa fa-shopping-cart fa-2x shooping-icon"></i>
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
																	<p class="clearfix price-product">'.number_format($commande['prix_vente'],2).' CDF<span class="total-product-cart-son">(x'.$commande['quantite'].')</span></p>
																</div>
															</div>';
												}
											}
														
											echo '  </div>
													<div class="relative border no-border-l no-border-r total-cart-header">
														<p class="bold clear-margin" style="color: #000;">Total: '.number_format($total,2).' CDF</p>
													</div>
													<div class="relative btn-cart-header">
														<a href="'.site_url('AppPanier/index/').'" class="uppercase bold animate-default">Voir </a>
														<a href="'.site_url('AppCheckOut/index/null/').'" class="uppercase bold button-hover-red animate-default">Commander</a>
													</div>';

										}else{
											echo '<div class="relative"> <p class="bold clear-margin" style="color: #000;"> Votre panier est vide ! </p></div> ';
										}
									}
								?>
							</div>

						</div>

					</div>					
					<div class="row">
						<a class="menu-vertical hidden-md hidden-lg blue">
							<span class="animate-default"><i class="fa fa-circle" aria-hidden="true"></i>Panier</span>
							<i id="exitAppIcon" style="float: right; color: white; font-size:30px; margin-top: -6px;" class="fa fa-power-off" aria-hidden="true"></i>
						</a>
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
						</div>
					</div>
				</div>
			</div>
			<!-- End Breadcrumb -->
			<!-- Content Shoping Cart -->
			<div class="relative container-web">
				<div class="container">
					<div class="row relative">
						<!-- Content Shoping Cart -->
						<div class="col-md-8 col-sm-12 col-xs-12 relative left-content-shoping clear-padding-left">
							<p class="title-shoping-cart">Votre Panier</p>
							<form  method="POST" action="<?php echo site_url('AppArticle/updatePanier') ?>" name="form_panier">
							<?php 
							
								if ($userAppData) {
									$commandes = $userAppData['commandes'];
									if (count($commandes)>0) {
										foreach ($commandes as $commande){
											if(isset( $commande['prix_vente']) ){
												$compter ++;
												$total_commande = $total_commande + ($commande['prix_vente']*$commande['quantite']);
												echo '  <div class="relative full-width product-in-cart border no-border-l no-border-r overfollow-hidden">
															<div class="relative product-in-cart-col-1 center-vertical-image">
																<img src="'.base_url('assets/uploads/files/').$commande['photo_article'].'" alt="">
															</div>
															<div class="relative product-in-cart-col-2">
																<p class="title-hover-black"><a href="#" class="animate-default">'.$commande['nom_article'].'</a></p>
																
															</div>
															<div class="relative product-in-cart-col-3">
																<a href="'.site_url('AppArticle/panier/').$commande['id_article'].'/'.str_replace('/','_',$refer).'">
																	<span class="ti-close relative remove-product"></span>
																</a>
																<p class="text-red price-shoping-cart">'.number_format($commande['prix_vente'],2).' CDF<span class="total-product-cart-son">(x'.$commande['quantite'].')</span></p>
																<br>
																<p class="float-left" style="margin:0;">Qté:</p>
																<input type="number" style="width: 70px; margin:0; position: relative; top: -22px;" class="left-margin-15-default" min="01" step="1" max="1000" value="'.$commande['quantite'].'" name="quantite_'.$compter.'" >
																<input type="number" class="left-margin-15-default" value="'.$commande['id_article'].'" name="id_article_'.$compter.'" hidden>
															</div>
														</div>
														';
											}
										}
									}else{
										echo '<div class="relative full-width product-in-cart border no-border-l no-border-r overfollow-hidden">
												<center>
													<h6> Votre panier est vide </h6>
												</center>
											</div>';
									}
									
								}
							
							
							?>
								<input type="text"   value="<?php if($refer) echo $refer; ?>" name="refer" hidden>
								<input type="text"   value="<?php if($refer_add_card) echo $refer_add_card; ?>" name="refer_add_card" hidden>
								<input type="text"   value="<?php if($userAppData) echo $userAppData['usertel']; ?>" name="usertel" hidden>
								<input type="text"   value="<?php echo $compter; ?>" name="total_article_panier" hidden>
								<aside class="btn-shoping-cart justify-content top-margin-default bottom-margin-default">
									<button style="border: none; box-shadow: none; height: 40px; color: #fff; background-color: red;" type="submit"  class="clear-margin animate-default full-width" style="width: 100% !important;">Mettre à jour le panier</a>
								</aside>
							</form>
						</div>
						<!-- End Content Shoping Cart -->
						<!-- Content Right -->
						<div class="col-md-4 col-sm-12 col-xs-12 right-content-shoping relative clear-padding-right">
							
							<p class="title-shoping-cart">Total</p>
							<div class="full-width relative cart-total bg-gray  clearfix">
								<div class="relative justify-content bottom-padding-15-default border no-border-t no-border-r no-border-l">
									<p>Subtotal</p>
									<p class="text-red price-shoping-cart"><?php echo number_format($total_commande,2)?> CDF</p>
								</div>
								<div class="relative border top-margin-15-default bottom-padding-15-default no-border-t no-border-r no-border-l">
									<p class="bottom-margin-15-default">Livraison</p>
									<div class="relative justify-content">
										<ul class="check-box-custom title-check-box-black clear-margin clear-margin">
											<li>
												<label>Partout en RDC
													<input type="radio" name="shipping" checked>
													<span class="checkmark"></span>
												</label>
											</li>
										</ul>
										<p class="price-gray-sidebar"> </p>
									</div>
								</div>
								<div class="relative justify-content top-margin-15-default">
									<p class="bold">Total</p>
									<p class="text-red price-shoping-cart"><?php echo number_format(($total_commande),2) ?> CDF</p>
								</div>
							</div>
							<a href="<?php echo site_url('AppCheckOut/index/null/') ?>">
								<button class="btn-proceed-checkout button-hover-red full-width top-margin-15-default">Commander</button>
							</a>
						</div>
						<!-- End Content Right -->
					</div>
				</div>
			</div>
			<!-- End Content Shoping Cart -->
			<!-- Support -->
			<div class=" support-box full-width clear-padding bottom-margin-default" style="margin-top: -30px !important;">
				<div class="container-web clearfix">
					<div class=" container border top-padding-default bottom-padding-default">
						<div class="row">
							<div class=" support-box-info relative col-md-3 col-sm-3 col-xs-6">
								<img src="<?php echo base_url('assets/app_assets/') ?>img/icon_free_ship.png" alt="Icon Free Ship" class="absolute" />
								<p>Livraison </p>
								<p>Partout en RDC</p>
							</div>
							<div class=" support-box-info relative col-md-3 col-sm-3 col-xs-6">
								<img src="<?php echo base_url('assets/app_assets/') ?>img/icon_support.png" alt="Icon Supports" class="absolute">
								<p>Support</p>
								<p>24/7</p>
							</div>
							<div class=" support-box-info relative col-md-3 col-sm-3 col-xs-6">
								<img src="<?php echo base_url('assets/app_assets/') ?>img/icon_patner.png" alt="Icon partner" class="absolute">
								<p>Travaillez avec nous</p>
								<p>Devenez partenaire de TedExpress</p>
							</div>
							<div class=" support-box-info relative col-md-3 col-sm-3 col-xs-6">
								<img src="<?php echo base_url('assets/app_assets/') ?>img/icon_phone_big.png" alt="Icon Phone Tablet" class="absolute">
								<p>Contactez nous</p>
								<p>+243 999 857 746 / +243 896 299 558</p>
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
							<p>Informations</p>
							<ul class="list-footer">
								<li><a href="#">Termes & Conditions</a></li>
								<li><a href="#">Comment Acheter sur TedExpress</a></li>
								<li><a href="#">Comment Payer Sur TedExpress</a></li>
								<li><a href="#">Comment Devenir Partenaire </a></li>
							</ul>
						</div>
						<div class="clearfix col-md-3 col-sm-6 col-xs-12 text-footer">
							<p>Contactez nous</p>
							<ul class="icon-footer">
								<li><i class="fa fa-home" aria-hidden="true"></i> Texaco Bel-air, Lubumbashi RDC,</li>
								<li>
									<a href="mailto:business@tedexpress.com" target="__blank">
										<i class="fa fa-envelope" aria-hidden="true"></i> business@tedexpress.com</li>
									</a>
								<li>
									<a href="tel:243858810668" target="__blank">
										<i class="fa fa-phone" aria-hidden="true"></i> +243 999 857 746 / +243 896 299 558</li>
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
						</div>
					</div>
				</div>
			</div>
		</footer>
	</div>
	<!-- End Footer Box -->

	<?php echo $search_bar; ?>

	<div id="javascript-divider"></div>

	<?php echo $footer;?>

</body>
</html>