<?php 
    $refer 		=  $this->agent->referrer();
    $refer      = (strlen($refer) >3) ? $refer : current_url();
    $refer 		= str_replace( site_url(),"", $refer); 
    $refer 		= str_replace( base_url(),"", $refer); 

    $indexPubHabillement    = (!empty($pubHabillement)) ? mt_rand(0, count($pubHabillement)-1 ) : 0;
    $indexPubElectronique   = (!empty($pubElectronique)) ? mt_rand(0, count($pubElectronique)-1 ) : 0;
    $indexPubAlimentation   = (!empty($pubAlimentation)) ? mt_rand(0, count($pubAlimentation)-1 ) : 0;
    $indexPubAutomobile     = (!empty($pubAutomobile)) ? mt_rand(0, count($pubAutomobile)-1 ) : 0;
    $indexPubElectromenager = (!empty($pubElectromenager)) ? mt_rand(0, count($pubElectromenager)-1 ) : 0;
    
    $referalFullLink        = $this->agent->referrer();
    $refer_add_card         = current_url();
    $refer_add_card 		= str_replace( site_url(),"", $refer_add_card); 
    $refer_add_card 		= str_replace( base_url(),"", $refer_add_card);

?>

<?php echo $header; ?>

<body>
    <!-- Header Box -->
    <div class="wrappage">
        <header class="relative full-width box-shadow">
            <div class="clearfix container-web relative">
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
														<p class="bold clear-margin" style="color: #000;">Total: '.number_format($total,2,0,'','.').' CDF</p>
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

        </header>
        <!-- End Header Box -->
        <!-- Content Box -->
        <div class="relative clearfix full-width">
            <!-- Menu & Slide -->
            <div class="clearfix container-web relative">
                <div class=" container relative">
                    <div class="row">
                        <div class="clearfix relative menu-slide clear-padding bottom-margin-default">
                            <!-- Menu -->
                            <div class="clearfix menu-web relative">
                                <ul>
                                    <?php 
                                        if(!empty( $categories )){
                                            foreach( $categories as $categorie){
                                                echo '<li><a href="'.site_url('AppCategorie/siteindex/').$categorie['id_categorie'].'"><i class="fa fa-'.$categorie['icon_categorie'].'"></i> <p>'.$categorie['nom_categorie'].'</p></a></li>';
                                            }
                                        }
                                    ?>
                                </ul>
                            </div>
                            <!-- Slide -->
                            <div class="clearfix slide-box-home slide-v1 relative">
                                <div class="clearfix slide-home owl-carousel owl-theme">
                                    <?php 
                                        if( $pubMainSlide){
                                            foreach ($pubMainSlide as $key => $pub) {
                                               echo '<a href="'.$pub['lien_publicite'].'"><div class="item" style="text-align: center;padding-left:0%;"><img style="width: 900px; height: 360px; object-fit: scale-down; "src="'.base_url('assets/uploads/files/').$pub['image_publicite'].'" alt="Banner Header '.$key.'"/></div></a>';
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class=" box-banner-small-v1 box-banner-small">
                                <?php 
                                    if( $pubSousSlide){
                                        foreach ($pubSousSlide as $key => $pub) {
                                            echo ' <div class="effect-layla relative clear-padding col-md-4 col-sm-4 col-xs-4 float-left zoom-image-hover">
                                                        <img src="'.base_url('assets/uploads/files/').$pub['image_publicite'].'" alt="" style="width: 300px; height:230px; object-fit:cover;">
                                                            <a href="'.$pub['lien_publicite'].'"  class="relative"></a>
                                                    </div>';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <!-- End Menu & Slide -->
                    </div>
                </div>
            </div>
            
            <!-- Content Meilleur deal  -->
            <div class="clearfix box-product full-width top-padding-default bg-gray" >
                <div class="clearfix container-web">
                    <div class=" container">
                        <div class="row">
                            <!-- Title Product -->
                            <div class="clearfix title-box full-width bottom-margin-default border bg-white">
                                <div class="clearfix name-title-box title-hot-bg relative">
                                    <img src=" <?php echo base_url('assets/app_assets/'); ?>img/icon_percent.png" class="absolute" alt="Icon Hot Deals" />
                                    <p>Meilleurs Deals</p>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix content-product-box bottom-margin-default full-width">
                            <div class="row">
                                <div class="relative">
                                    <div class="good-deal-product animate-default active-box-category hidden-content-box" id="mobile-tablet">
                                        <!-- Product Son -->
                                        <div class="owl-carousel owl-theme">
                                            <!-- <div class=" product-son ">
                                                <div class="clearfix image-product relative animate-default">
                                                    <div class="center-vertical-image">
                                                        <img src="img/img_product.png" alt="Product . . ." />
                                                    </div>
                                                    <ul class="option-product animate-default">
                                                        <li class="relative"><a href="#"><i class="data-icon data-icon-ecommerce icon-ecommerce-bag"></i></a></li>
                                                        <li class="relative"><a href="#"><i class="data-icondata-icon-basic icon-basic-heart" aria-hidden="true"></i></a></li>
                                                        <li class="relative"><a href="#"><i class="data-icon data-icon-basic icon-basic-magnifier" aria-hidden="true"></i></a></li>
                                                    </ul>
                                                </div>
                                                <div class="clearfix ranking">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star-half" aria-hidden="true"></i>
                                                    <i class="fa fa-star-o" aria-hidden="true"></i>
                                                </div>
                                                <p class="title-product clearfix full-width title-hover-black animate-default"><a class="animate-default" href="#">Eos cu utroque inermis</a></p>
                                                <p class="clearfix price-product"><span class="price-old">$700</span> $350</p>
                                            </div> -->
                                            <?php 
                                                if($meilleurs_deals){
                                                    foreach ($meilleurs_deals as $key => $deals) {
                                                        if($key <= 10){
                                                            echo ' <div class="product-son">
                                                                        <div class="clearfix image-product relative animate-default">
                                                                            <div class="center-vertical-image">
                                                                                <a href="'.site_url('AppArticle/siteindex/').$deals['id_article'].'">
                                                                                    <img style="width: 300px; height: 300px; object-fit: cover;" src="'.base_url('assets/uploads/files/').$deals['photo_article'].'" alt="'.$deals['photo_article'].'" class="image-article" />
                                                                                </a>                                              
                                                                            </div>
                                                                        </div>
                                                                        <p class="title-product clearfix full-width title-hover-black animate-default"><a href="'.site_url('AppArticle/siteindex/').$deals['id_article'].'" class="animate-default name-article">'.$deals['nom_article'].'</a></p>
                                                                        <p class="clearfix price-product"><span class="price-old">'.number_format( ($deals['prix_vente']+1000),0,'','.').' CDF</span> <span class="price-article">'.number_format($deals['prix_vente'],0,'','.').' CDF</span></p>
                                                                    </div>';
                                                        }
                                                    }
                                                }
                                            ?>
                                            <!-- End Product Son -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Content Meilleur deal  -->


            <!-- Banner Full With -->
            <div class="clearfix relative full-width bottom-margin-default">
                <div class="clearfix container-web">
                    <div class=" container banner_full_width">
                        <?php 
                            if($pubInsidePage){
                                $max_random = count($pubInsidePage)-1;
                                $index_pub_one  = mt_rand(0, $max_random );
                                echo'<div class="row relative banners-effect5 overfollow-hidden">
                                        <img style="width: 1170; height: 150px; object-fit: scale-down;" class="max-width" src="'.base_url('assets/uploads/files/').$pubInsidePage[$index_pub_one]['image_publicite'].'" alt="'.$pubInsidePage[$index_pub_one]['nom_publicite'].'""/>
                                        <a href="'.$pubInsidePage[$index_pub_one]['lien_publicite'].'"></a>
                                    </div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
            <!-- End Banner Full With -->

            <!-- Product Restaurant & Super Marché -->
            <div class=" full-width category-percent-two bottom-margin-default" style="display:none;">
                <div class="clearfix container-web">
                    <div class=" container">
                        <div class="row">
                            <div class=" clearfix content-left col-md-6 col-sm-6">
                                <!-- Title Product -->
                                <div class="clearfix title-box full-width border">
                                    <div class="clearfix name-title-box title-category title-green-bg relative">
                                        <img src="<?php echo base_url('assets/app_assets/')?>img/icon_food.png" alt="Icon Mother" class="absolute">
                                        <p>Restaurations</p>
                                    </div>
                                    <div class="clearfix menu-title-box">
                                        <p class="view-all-product-category title-hover-red"><a href="<?php echo site_url('AppFournisseur/siteindex/').$restaurants['id_categorie'];?>" class="animate-default">PLUS</a></p>
                                    </div>
                                </div>
                                <!-- Content Product Box -->
                                <div class="clearfix product-percent-content border-collapsed-box full-width">
                                    <?php 
                                    
                                        if($restaurants){
                                            foreach ($restaurants as $key => $resto) {
                                                if($key<=3){
                                                    if(is_array($resto)){
                                                        echo'<div class="clearfix relative product-no-ranking border-collapsed-element percent-content-3">
                                                                <div class="effect-hover-zoom center-vertical-image">
                                                                    <img style="width: 160px !important; height: 160px !important; object-fit: scale-down;" src="'.base_url('assets/uploads/files/').$resto['logo_fournisseur'].'" alt="'.$resto['nom_fournisseur'].'">
                                                                    <a href="'.site_url('AppFournisseur/filter/').$resto['id_fournisseur'].'/'.$restaurants['id_categorie'].'"></a>
                                                                </div>
                                                                <div class="clearfix absolute name-product-no-ranking">
                                                                    <p class="title-product clearfix full-width title-hover-black"><a href="#">'.$resto['nom_fournisseur'].'</a></p>
                                                                </div>
                                                              </div>';
                                                    }
                                                }
                                            }
                                        }
                                    ?>
                                    
                                </div>
                            </div>
                            <div class=" clearfix content-left col-md-6 col-sm-6">
                                <!-- Title Product -->
                                <div class="clearfix title-box full-width border">
                                    <div class="clearfix name-title-box title-category title-jungle-green-bg relative">
                                        <img src="<?php echo base_url('assets/app_assets/')?>img/icon_voucher.png" alt="Supers Marchés" class="absolute">
                                        <p>Supers Marchés</p>
                                    </div>
                                    <div class="clearfix menu-title-box">
                                        <p class="view-all-product-category title-hover-red"><a href="<?php echo site_url('AppFournisseur/siteindex/').$supermarchets['id_categorie'];?>" class="animate-default">PLUS</a></p>
                                    </div>
                                </div>
                                <!-- Content Product Box -->
                                <div class="clearfix product-percent-content border-collapsed-box full-width">
                                    <?php 
                                    
                                        if($supermarchets){
                                            foreach ($supermarchets as $key => $supermarchet) {
                                                if($key<=3){
                                                    if(is_array($supermarchet)){
                                                        echo'<div class="clearfix relative product-no-ranking border-collapsed-element percent-content-3">
                                                                <div class="effect-hover-zoom center-vertical-image">
                                                                    <img style="width: 160px !important; height: 160px !important; object-fit: scale-down;" src="'.base_url('assets/uploads/files/').$supermarchet['logo_fournisseur'].'" alt="'.$supermarchet['nom_fournisseur'].'">
                                                                    <a href="'.site_url('AppFournisseur/filter/').$supermarchet['id_fournisseur'].'/'.$supermarchets['id_categorie'].'"></a>
                                                                </div>
                                                                <div class="clearfix absolute name-product-no-ranking">
                                                                    <p class="title-product clearfix full-width title-hover-black"><a href="#">'.$supermarchet['nom_fournisseur'].'</a></p>
                                                                </div>
                                                              </div>';
                                                    }
                                                }
                                            }
                                        }
                                    ?>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Product Restaurant & Super Marché -->




            <!-- Product restaurant  -->
            <div class=" container-web">
                <div class=" container">
                    <div class="row">
                        <div class="clearfix title-box full-width border">
                            <div class="clearfix name-title-box title-category title-magenta-bg relative">
                                <img src="<?php echo base_url('assets/app_assets/')?>img/icon_food.png" alt="Icon Mother" class="absolute">
                                <p>Restaurations</p>
                            </div>
                            <div class="clearfix menu-title-box">
                                <p class="view-all-product-category title-hover-red"><a href="<?php echo site_url('AppFournisseur/siteindex/').$restaurants['id_categorie'];?>" class="animate-default">PLUS</a></p>
                            </div>
                        </div>
                        <div class="display-table bottom-margin-default full-width">
                            <div class="clearfix clear-padding list-logo-category list-logo-category-v1 float-left border no-border-t no-border-r">
                                <ul>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_3.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_4.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_5.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_6.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_1.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_2.png" alt="Logo"></a></li>
                                </ul>
                            </div>
                            <div class=" banner-category float-left relative effect-bubba zoom-image-hover">
                                <img src="<?php echo base_url('assets/uploads/files/').$restaurants[0]['logo_fournisseur'] ?>" alt="Banner" style="width:470px;height:500px;object-fit:cover;">
                            </div>
                            <div class="clearfix list-products-category list-products-category-v1 float-left relative">
                                <div class="box-mobile-content border-collapsed-box animate-default hidden-content-box active-box-category" id="smart-phone">
                                    <?php 
                                        if($restaurants){
                                            foreach ($restaurants as $key => $restaurant) {
                                                if( $key<=3){
                                                    echo ' <div class="clearfix relative product-no-ranking border-collapsed-element percent-content-2">
                                                                <div class="effect-hover-zoom center-vertical-image">
                                                                    <img src="'.base_url('assets/uploads/files/').$restaurant['logo_fournisseur'].'" alt="'.$restaurant['nom_fournisseur'].'" style="width:249px; height:226px;object-fit:cover;">
                                                                </div>
                                                                <div class="clearfix absolute name-product-no-ranking">
                                                                    <p class="title-product clearfix full-width title-hover-black"><a href="'.site_url('AppFournisseur/sitefilter/').$restaurant['id_fournisseur'].'/'.$restaurants['id_categorie'].'" style="color: #fff;  text-shadow: 2px 2px 3px #000;">'.$restaurant['nom_fournisseur'].'</a></p>
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
                </div>
            </div>
            <!-- End Product restaurant -->
            
            <!-- Product super marché  -->
            <div class=" container-web">
                <div class=" container">
                    <div class="row">
                        <div class="clearfix title-box full-width border">
                            <div class="clearfix name-title-box title-category title-magenta-bg relative">
                                <img src="<?php echo base_url('assets/app_assets/')?>img/icon_voucher.png" alt="Supers Marchés" class="absolute">
                                <p>Supers Marchés</p>
                            </div>
                            <div class="clearfix menu-title-box">
                                <p class="view-all-product-category title-hover-red"><a href="<?php echo site_url('AppFournisseur/siteindex/').$supermarchets['id_categorie'];?>" class="animate-default">PLUS</a></p>
                            </div>
                        </div>
                        <div class="display-table bottom-margin-default full-width">
                            <div class="clearfix clear-padding list-logo-category list-logo-category-v1 float-left border no-border-t no-border-r">
                                <ul>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_3.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_4.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_5.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_6.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_1.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_2.png" alt="Logo"></a></li>
                                </ul>
                            </div>
                            <div class=" banner-category float-left relative effect-bubba zoom-image-hover">
                                <img src="<?php echo base_url('assets/uploads/files/').$supermarchets[0]['logo_fournisseur'] ?>" alt="Banner" style="width:470px;height:500px;object-fit:cover;">
                            </div>
                            <div class="clearfix list-products-category list-products-category-v1 float-left relative">
                                <div class="box-mobile-content border-collapsed-box animate-default hidden-content-box active-box-category" id="smart-phone">
                                    <?php 
                                        if($supermarchets){
                                            foreach ($supermarchets as $key => $supermarchet) {
                                                if( $key<=3){
                                                    echo ' <div class="clearfix relative product-no-ranking border-collapsed-element percent-content-2">
                                                                <div class="effect-hover-zoom center-vertical-image">
                                                                    <img src="'.base_url('assets/uploads/files/').$supermarchet['logo_fournisseur'].'" alt="'.$supermarchet['nom_fournisseur'].'" style="width:249px; height:226px;object-fit:cover;">
                                                                </div>
                                                                <div class="clearfix absolute name-product-no-ranking">
                                                                    <p class="title-product clearfix full-width title-hover-black"><a href="'.site_url('AppFournisseur/sitefilter/').$supermarchet['id_fournisseur'].'/'.$supermarchets['id_categorie'].'" style="color: #fff;  text-shadow: 2px 2px 3px #000;">'.$supermarchet['nom_fournisseur'].'</a></p>
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
                </div>
            </div>
            <!-- End Product super marché -->

            <!-- Product Santé  -->
            <div class=" container-web">
                <div class=" container">
                    <div class="row">
                        <div class="clearfix title-box full-width border">
                            <div class="clearfix name-title-box title-category title-magenta-bg relative">
                                <img src="<?php echo base_url('assets/app_assets/')?>img/icon_health.png" alt="Icon Mother" class="absolute">
                                <p>Santés</p>
                            </div>
                            <div class="clearfix menu-title-box">
                                <p class="view-all-product-category title-hover-red"><a href="<?php echo site_url('AppFournisseur/siteindex/').$pharmacies['id_categorie'];?>" class="animate-default">PLUS</a></p>
                            </div>
                        </div>
                        <div class="display-table bottom-margin-default full-width">
                            <div class="clearfix clear-padding list-logo-category list-logo-category-v1 float-left border no-border-t no-border-r">
                                <ul>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_3.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_4.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_5.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_6.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_1.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_2.png" alt="Logo"></a></li>
                                </ul>
                            </div>
                            <div class=" banner-category float-left relative effect-bubba zoom-image-hover">
                                <img src="<?php echo base_url('assets/uploads/files/').$pharmacies[0]['logo_fournisseur'] ?>" alt="Banner" style="width:470px;height:500px;object-fit:cover;">
                            </div>
                            <div class="clearfix list-products-category list-products-category-v1 float-left relative">
                                <div class="box-mobile-content border-collapsed-box animate-default hidden-content-box active-box-category" id="smart-phone">
                                    <?php 
                                        if($pharmacies){
                                            foreach ($pharmacies as $key => $pharmacie) {
                                                if( $key<=3){
                                                    echo ' <div class="clearfix relative product-no-ranking border-collapsed-element percent-content-2">
                                                                <div class="effect-hover-zoom center-vertical-image">
                                                                    <img src="'.base_url('assets/uploads/files/').$pharmacie['logo_fournisseur'].'" alt="'.$pharmacie['nom_fournisseur'].'" style="width:249px; height:226px;object-fit:cover;">
                                                                </div>
                                                                <div class="clearfix absolute name-product-no-ranking">
                                                                    <p class="title-product clearfix full-width title-hover-black"><a href="'.site_url('AppFournisseur/sitefilter/').$pharmacie['id_fournisseur'].'/'.$pharmacies['id_categorie'].'" style="color: #fff;  text-shadow: 2px 2px 3px #000;">'.$pharmacie['nom_fournisseur'].'</a></p>
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
                </div>
            </div>
            <!-- End Product Santé -->


            <!-- End Product  Habillements -->
            <div class=" container-web">
                <div class=" container">
                    <div class="row">
                        <div class="clearfix title-box full-width border">
                            <div class="clearfix name-title-box title-category title-turquoise-bg relative">
                                <img alt="Icon Electric" src="<?php echo base_url('assets/app_assets/')?>img/icon_fashion.png" class="absolute" />
                                <p>Habillements</p>
                            </div>
                            <div class="clearfix menu-title-box">
                                <p class="view-all-product-category title-hover-red"><a style="font-size: 16px;" href="<?php if($habillements) echo site_url('AppCategorie/siteindex/').$habillements[0]['id_foreign_categorie'];?>" class="animate-default">PLUS</a></p>
                            </div>
                        </div>
                        <div class="display-table bottom-margin-default full-width">
                            <div class="clearfix clear-padding list-logo-category list-logo-category-v1 float-left border no-border-t no-border-r">
                                <ul>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_3.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_4.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_5.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_6.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_1.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_2.png" alt="Logo"></a></li>
                                </ul>
                            </div>
                            <div class=" banner-category float-left relative effect-bubba zoom-image-hover">
                                <img src="<?php echo base_url('assets/uploads/files/').$habillements[0]['photo_article'] ?>" alt="Banner" style="width:470px;height:500px;object-fit:cover;">
                                <a href="#"></a>
                            </div>
                            <div class="clearfix list-products-category list-products-category-v1 float-left relative">
                                <div class="box-mobile-content border-collapsed-box animate-default hidden-content-box active-box-category" id="smart-phone">
                                    <?php 
                                        if($habillements){
                                            foreach ($habillements as $key => $habillement) {
                                                if( $key<=3){
                                                    echo ' <div class="clearfix relative product-no-ranking border-collapsed-element percent-content-2">
                                                                <div class="effect-hover-zoom center-vertical-image">
                                                                    <img src="'.base_url('assets/uploads/files/').$habillement['photo_article'].'" alt="'.$habillement['nom_article'].'" style="width:249px; height:226px;object-fit:cover;">
                                                                </div>
                                                                <div class="clearfix absolute name-product-no-ranking">
                                                                    <p class="title-product clearfix full-width title-hover-black"><a href="'.site_url('AppArticle/siteindex/').$habillement['id_article'].'" style="color: #fff;  text-shadow: 2px 2px 3px #000;">'.$habillement['nom_article'].'</a></p>
                                                                    <p class="clearfix price-product" style="visibility:hidden;">'. number_format($habillement['prix_vente'],0,'','.').' CDF</p>
                                                                    <a href="'.site_url('AppArticle/siteindex/').$habillement['id_article'].'" >
                                                                        <button style="border-radius: 5px; border: none; color:#fff; background-color: #2196f3; height: 33px; width:100%; padding-left: 5px; padding-right: 5px;" class="animate-default" id="ajout-panier">'. number_format($habillement['prix_vente'],0,'','.').' CDF</button>
                                                                    </a>
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
                </div>
            </div>
            <!-- End Product  Habillements -->


            <!-- End Product  Electromenagers -->
            <div class=" container-web">
                <div class=" container">
                    <div class="row">
                        <div class="clearfix title-box full-width border">
                            <div class="clearfix name-title-box title-category title-turquoise-bg relative">
                                <img alt="Icon Electric" src="<?php echo base_url('assets/app_assets/')?>img/icon_electric.png" class="absolute" />
                                <p>Eléctroménagers</p>
                            </div>
                            <div class="clearfix menu-title-box">
                                <p class="view-all-product-category title-hover-red"><a style="font-size: 16px;" href="<?php if($electromenagers) echo site_url('AppCategorie/siteindex/').$electromenagers[0]['id_foreign_categorie'];?>" class="animate-default">PLUS</a></p>
                            </div>
                        </div>
                        <div class="display-table bottom-margin-default full-width">
                            <div class="clearfix clear-padding list-logo-category list-logo-category-v1 float-left border no-border-t no-border-r">
                                <ul>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_3.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_4.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_5.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_6.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_1.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_2.png" alt="Logo"></a></li>
                                </ul>
                            </div>
                            <div class=" banner-category float-left relative effect-bubba zoom-image-hover">
                                <img src="<?php echo base_url('assets/uploads/files/').$electromenagers[0]['photo_article'] ?>" alt="Banner" style="width:470px;height:500px;object-fit:cover;">
                                <a href="#"></a>
                            </div>
                            <div class="clearfix list-products-category list-products-category-v1 float-left relative">
                                <div class="box-mobile-content border-collapsed-box animate-default hidden-content-box active-box-category" id="smart-phone">
                                    <?php 
                                        if($electromenagers){
                                            foreach ($electromenagers as $key => $electromenager) {
                                                if($key<=3){
                                                    echo ' <div class="clearfix relative product-no-ranking border-collapsed-element percent-content-2">
                                                                <div class="effect-hover-zoom center-vertical-image">
                                                                    <img src="'.base_url('assets/uploads/files/').$electromenager['photo_article'].'" alt="'.$electromenager['nom_article'].'" style="width:249px; height:226px;object-fit:cover;">
                                                                </div>
                                                                <div class="clearfix absolute name-product-no-ranking">
                                                                    <p class="title-product clearfix full-width title-hover-black"><a href="'.site_url('AppArticle/siteindex/').$electromenager['id_article'].'" style="color: #fff;  text-shadow: 2px 2px 3px #000;">'.$electromenager['nom_article'].'</a></p>
                                                                    <a href="'.site_url('AppArticle/siteindex/').$electromenager['id_article'].'" >
                                                                        <button style="border-radius: 5px; border: none; color:#fff; background-color: #2196f3; height: 33px; width:100%; padding-left: 5px; padding-right: 5px;" class="animate-default" id="ajout-panier">'. number_format($electromenager['prix_vente'],0,'','.').' CDF</button>
                                                                    </a>
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
                </div>
            </div>
            <!-- End Product  Electromenagers -->

            
            <!-- End Product  Electroniques -->
            <div class=" container-web">
                <div class=" container">
                    <div class="row">
                        <div class="clearfix title-box full-width border">
                            <div class="clearfix name-title-box title-category title-gold-bg relative">
                                <img src="<?php echo base_url('assets/app_assets/')?>img/icon_electric.png" alt="Icon Fashion" class="absolute" />
                                <p>Eléctroniques</p>
                            </div>
                            <div class="clearfix menu-title-box">
                                <p class="view-all-product-category title-hover-red"><a href="<?php if($electroniques) echo site_url('AppCategorie/siteindex/').$electroniques[0]['id_foreign_categorie'];?>" class="animate-default">PLUS</a></p>
                            </div>
                        </div>
                        <div class="display-table bottom-margin-default full-width">
                            <div class="clearfix clear-padding list-logo-category list-logo-category-v1 float-left border no-border-t no-border-r">
                                <ul>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_3.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_4.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_5.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_6.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_1.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_2.png" alt="Logo"></a></li>
                                </ul>
                            </div>
                            <div class=" banner-category float-left relative effect-bubba zoom-image-hover">
                                <img src="<?php echo base_url('assets/uploads/files/').$electroniques[0]['photo_article'] ?>" alt="Banner" style="width:470px;height:500px;object-fit:cover;">
                                <a href="#"></a>
                            </div>
                            <div class="clearfix list-products-category list-products-category-v1 float-left relative">
                                <div class="box-mobile-content border-collapsed-box animate-default hidden-content-box active-box-category" id="smart-phone">
                                    <?php 
                                        if($electroniques){
                                            foreach ($electroniques as $key => $electronique) {
                                                if( $key<=3){
                                                    echo ' <div class="clearfix relative product-no-ranking border-collapsed-element percent-content-2">
                                                                <div class="effect-hover-zoom center-vertical-image">
                                                                    <img src="'.base_url('assets/uploads/files/').$electronique['photo_article'].'" alt="'.$electronique['nom_article'].'" style="width:249px; height:226px;object-fit:cover;">
                                                                </div>
                                                                <div class="clearfix absolute name-product-no-ranking">
                                                                    <p class="title-product clearfix full-width title-hover-black"><a href="'.site_url('AppArticle/siteindex/').$electronique['id_article'].'" style="color: #fff;  text-shadow: 2px 2px 3px #000;">'.$electronique['nom_article'].'</a></p>
                                                                    <a href="'.site_url('AppArticle/siteindex/').$electronique['id_article'].'" >
                                                                        <button style="border-radius: 5px; border: none; color:#fff; background-color: #2196f3; height: 33px; width:100%; padding-left: 5px; padding-right: 5px;" class="animate-default" id="ajout-panier">'. number_format($electronique['prix_vente'],0,'','.').' CDF</button>
                                                                    </a>
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
                </div>
            </div>
            <!-- End Product  Electroniques -->


            <!-- End Product  Alimentations -->
            <div class=" container-web">
                <div class=" container">
                    <div class="row">
                        <div class="clearfix title-box full-width border">
                            <div class="clearfix name-title-box title-category title-violet-bg relative">
                                <img src="<?php echo base_url('assets/app_assets/')?>img/icon_food.png" alt="Alimentations" class="absolute" />
                                <p>Alimentations</p>
                            </div>
                            <div class="clearfix menu-title-box">
                                <p class="view-all-product-category title-hover-red"><a href="<?php if($alimentations) echo site_url('AppCategorie/siteindex/').$alimentations[0]['id_foreign_categorie'];?>" class="animate-default">PLUS</a></p>
                            </div>
                        </div>
                        <div class="display-table bottom-margin-default full-width">
                            <div class="clearfix clear-padding list-logo-category list-logo-category-v1 float-left border no-border-t no-border-r">
                                <ul>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_3.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_4.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_5.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_6.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_1.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_2.png" alt="Logo"></a></li>
                                </ul>
                            </div>
                            <div class=" banner-category float-left relative effect-bubba zoom-image-hover">
                                <img src="<?php echo base_url('assets/uploads/files/').$alimentations[0]['photo_article'] ?>" alt="Banner" style="width:470px;height:500px;object-fit:cover;">
                                <a href="#"></a>
                            </div>
                            <div class="clearfix list-products-category list-products-category-v1 float-left relative">
                                <div class="box-mobile-content border-collapsed-box animate-default hidden-content-box active-box-category" id="smart-phone">
                                    <?php 
                                        if($alimentations){
                                            foreach ($alimentations as $key => $alimentation) {
                                                if($key<=3){
                                                    echo ' <div class="clearfix relative product-no-ranking border-collapsed-element percent-content-2">
                                                                <div class="effect-hover-zoom center-vertical-image">
                                                                    <img src="'.base_url('assets/uploads/files/').$alimentation['photo_article'].'" alt="'.$alimentation['nom_article'].'" style="width:249px; height:226px;object-fit:cover;">
                                                                </div>
                                                                <div class="clearfix absolute name-product-no-ranking">
                                                                    <p class="title-product clearfix full-width title-hover-black"><a href="'.site_url('AppArticle/siteindex/').$alimentation['id_article'].'" style="color: #fff;  text-shadow: 2px 2px 3px #000;">'.$alimentation['nom_article'].'</a></p>
                                                                    <a href="'.site_url('AppArticle/siteindex/').$alimentation['id_article'].'" >
                                                                        <button style="border-radius: 5px; border: none; color:#fff; background-color: #2196f3; height: 33px; width:100%; padding-left: 5px; padding-right: 5px;" class="animate-default" id="ajout-panier">'. number_format($alimentation['prix_vente'],0,'','.').' CDF</button>
                                                                    </a>
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
                </div>
            </div>
            <!-- End Product  Alimentations-->


            <!-- End Product  Automobile -->
            <div class=" container-web">
                <div class=" container">
                    <div class="row">
                        <div class="clearfix title-box full-width border">
                            <div class="clearfix name-title-box title-category title-turquoise-bg relative">
                                <img alt="Icon Electric" src="<?php echo base_url('assets/app_assets/')?>img/icon_auto.png" class="absolute" />
                                <p>Automobiles</p>
                            </div>
                            <div class="clearfix menu-title-box">
                                <p class="view-all-product-category title-hover-red"><a style="font-size: 16px;" href="<?php if($automobiles) echo site_url('AppCategorie/siteindex/').$automobiles[0]['id_foreign_categorie'];?>" class="animate-default">PLUS</a></p>
                            </div>
                        </div>
                        <div class="display-table bottom-margin-default full-width">
                            <div class="clearfix clear-padding list-logo-category list-logo-category-v1 float-left border no-border-t no-border-r">
                                <ul>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_3.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_4.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_5.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_6.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_1.png" alt="Logo"></a></li>
                                    <li><a href="#"><img src="<?php echo base_url('assets/app_assets/')?>img/logo_2.png" alt="Logo"></a></li>
                                </ul>
                            </div>
                            <div class=" banner-category float-left relative effect-bubba zoom-image-hover">
                                <img src="<?php echo base_url('assets/uploads/files/').$automobiles[0]['photo_article'] ?>" alt="Banner" style="width:470px;height:500px;object-fit:cover;">
                                <a href="#"></a>
                            </div>
                            <div class="clearfix list-products-category list-products-category-v1 float-left relative">
                                <div class="box-mobile-content border-collapsed-box animate-default hidden-content-box active-box-category" id="smart-phone">
                                    <?php 
                                        if($automobiles){
                                            foreach ($automobiles as $key => $automobile) {
                                                if( $key<=3){
                                                    $message =  "Bonjour je suis interressé par votre véhicule  [ ".$automobile['nom_article']." ] ID système: [ ".$automobile['id_article']." ]";
                                                    echo ' <div class="clearfix relative product-no-ranking border-collapsed-element percent-content-2">
                                                                <div class="effect-hover-zoom center-vertical-image">
                                                                    <img src="'.base_url('assets/uploads/files/').$automobile['photo_article'].'" alt="'.$automobile['nom_article'].'" style="width:249px; height:226px;object-fit:cover;">
                                                                    <a href="#"></a>
                                                                </div>
                                                                <div class="clearfix absolute name-product-no-ranking">
                                                                    <p class="title-product clearfix full-width title-hover-black"><a href="'.site_url('AppArticle/siteindex/').$automobile['id_article'].'" style="color: #fff;  text-shadow: 2px 2px 3px #000;">'.$automobile['nom_article'].'</a></p>
                                                                    <p class="clearfix price-product">'. number_format($automobile['prix_vente'],0,'','.').' CDF</p>
                                                                    <p>
                                                                        <center>
                                                                            <button style="border-radius: 5px; border: none; color:#fff; background-color: #4bca5a; height: 33px; padding-left: 5px; padding-right: 5px;" class="animate-default" id="ajout-panier">
                                                                                <a target="_blank" style="color: #fff;" href="https://api.whatsapp.com/send?phone=243858810668&text='.$message.'"> <i class="fa fa-whatsapp"></i>  Whatsapp </a>    
                                                                            </button>
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
                </div>
            </div>
            <!-- End Product  Automobile-->


            <!-- Banner Half Website -->
             <div class=" relative banner-half-web full-width bottom-margin-default">
                <div class="clearfix container-web">
                    <div class=" container">
                        <div class="row">
                            <!-- <div class="clearfix content-left col-md-6 col-sm-6 col-xs-12 zoom-image-hover overfollow-hidden">
                                <div class="overfollow-hidden effect-oscar relative">
                                    <img class="max-width" src="img/banner_halt.png" alt="Banner . . ." />
                                    <a href="#"></a>
                                </div>
                            </div> -->
                            <?php 
                                if($pubInsidePage){
                                    $max_random = count($pubInsidePage)-1;
                                    $index_pub_one  = mt_rand(0, $max_random );
                                    $index_pub_two  = mt_rand(0 , $max_random );
                                    if( $index_pub_one == $index_pub_two){
                                        if( $index_pub_two < $max_random  ){
                                            $index_pub_two++;
                                        }else{
                                            $index_pub_two--;
                                        }
                                    }
                                }
                                echo '  <div class="clearfix content-left col-md-6 col-sm-6 col-xs-12 zoom-image-hover overfollow-hidden">
                                            <div class="overfollow-hidden effect-oscar relative">
                                                <img style="width: 400px; object-fit: scale-down;" class="max-width" src="'.base_url('assets/uploads/files/').$pubInsidePage[$index_pub_one]['image_publicite'].'" alt="'.$pubInsidePage[$index_pub_one]['nom_publicite'].'""/>
                                                <a href="'.$pub['lien_publicite'].'"></a>
                                            </div>
                                        </div>
                                        <div class="clearfix content-right col-md-6 col-sm-6 col-xs-12 zoom-image-hover overfollow-hidden">
                                            <div class="overfollow-hidden effect-oscar relative">
                                                <img style="width: 400px; object-fit: scale-down;" class="max-width" src="'.base_url('assets/uploads/files/').$pubInsidePage[$index_pub_two]['image_publicite'].'" alt="'.$pubInsidePage[$index_pub_two]['nom_publicite'].'""/>
                                                <a href="'.$pub['lien_publicite'].'"></a>
                                            </div>
                                        </div>';
                            ?>
                        </div>
                    </div>
                </div>
             </div>
            <!-- End Banner Half Website -->
            

            <!-- Slide Logo Brand -->
            <div class=" slide-brand-box full-width bottom-margin-default">
                <div class="clearfix container-web relative">
                    <div class=" container relative">
                        <div class="row">
                            <div class="nav-prev nav-slide-brand"></div>
                            <div class="slide-logo-brand col-md-12 clear-padding relative owl-theme owl-carousel border-collapsed-box">
                                <div class="item">
                                    <div class="clearfix border-collapsed-element relative logo-brand-son"><img src="<?php echo base_url('assets/app_assets/') ?>img/logo_3.png" alt="Logo"></div>
                                    <div class="clearfix border-collapsed-element relative logo-brand-son"><img src="<?php echo base_url('assets/app_assets/') ?>img/logo_7.png" alt="Logo"></div>
                                </div>
                                <div class="item">
                                    <div class="clearfix border-collapsed-element relative logo-brand-son"><img src="<?php echo base_url('assets/app_assets/') ?>img/logo_4.png" alt="Logo"></div>
                                    <div class="clearfix border-collapsed-element relative logo-brand-son"><img src="<?php echo base_url('assets/app_assets/') ?>img/logo_8.png" alt="Logo"></div>
                                </div>
                                <div class="item">
                                    <div class="clearfix border-collapsed-element relative logo-brand-son"><img src="<?php echo base_url('assets/app_assets/') ?>img/logo_5.png" alt="Logo"></div>
                                    <div class="clearfix border-collapsed-element relative logo-brand-son"><img src="<?php echo base_url('assets/app_assets/') ?>img/logo_9.png" alt="Logo"></div>
                                </div>
                                <div class="item">
                                    <div class="clearfix border-collapsed-element relative logo-brand-son"><img src="<?php echo base_url('assets/app_assets/') ?>img/logo_6.png" alt="Logo" /></div>
                                    <div class="clearfix border-collapsed-element relative logo-brand-son"><img src="<?php echo base_url('assets/app_assets/') ?>img/logo_10.png" alt="Logo" /></div>
                                </div>
                                <div class="item">
                                    <div class="clearfix border-collapsed-element relative logo-brand-son"><img src="<?php echo base_url('assets/app_assets/') ?>img/logo_1.png" alt="Logo" /></div>
                                    <div class="clearfix border-collapsed-element relative logo-brand-son"><img src="<?php echo base_url('assets/app_assets/') ?>img/logo_11.png" alt="Logo" /></div>
                                </div>
                                <div class="item">
                                    <div class="clearfix border-collapsed-element relative logo-brand-son"><img src="<?php echo base_url('assets/app_assets/') ?>img/logo_2.png" alt="Logo" /></div>
                                    <div class="clearfix border-collapsed-element relative logo-brand-son"><img src="<?php echo base_url('assets/app_assets/') ?>img/logo_12.png" alt="Logo" /></div>
                                </div>
                            </div>
                            <div class="nav-next nav-slide-brand"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Slide Brand -->
            

            <!-- Support -->
            <div class=" support-box full-width clear-padding bottom-margin-default">
                <div class="container-web clearfix">
                    <div class=" container border top-padding-default bottom-padding-default">
                        <div class="row">
                            <div class=" support-box-info relative col-md-3 col-sm-3 col-xs-6">
                                <img src="<?php echo base_url('assets/app_assets/') ?>img/icon_free_ship.png" alt="Icon Free Ship" class="absolute" />
                                <p>Livraison à</p>
                                <p>1000 CDF</p>
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
                                <p>+243 999 857 746</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Support Box -->
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
                                <li><a href="#">Vente en ligne</a></li>
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
    <?php echo $footer; ?>
</body>

</html>