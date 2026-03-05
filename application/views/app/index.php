<?php 
    $refer 		=  $this->agent->referrer();

    $refer      = (strlen($refer) >3 AND $refer <> 'https://localhost/') ? $refer : current_url();
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
                <div class="container">
                    <div class="row">
                        <div class="header-top">
                            <p class="contact_us_header col-md-4 col-xs-12 col-sm-3 clear-margin" style="color: #fff;">
                                <i class="fa fa-phone-square"> </i>  Contactez-nous <span class="text-white bold"> +243 999 857 746 / +243 896 299 558</span>
                            </p>
                            <div class="clear-padding menu-header-top text-right col-md-8 col-xs-12 col-sm-6">
                                <ul class="clear-margin">
                                    <li class="relative"><a href="https://web.facebook.com/" target="_blank" style="color: #fff;"> <i class="fa fa-facebook-square"></i> Facebook</a></li>
                                    <li class="relative"><a href="https://www.instagram.com/" target="_blank" style="color: #fff;"> <i class="fa fa-instagram"></i> Instagram</a></li>
                                    <li class="relative"><a href="https://www.youtube.com/channel/" target="_blank" style="color: #fff;"><i class="fa fa-youtube-square"></i> Youtube</a></li>
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
                        <a class="menu-vertical hidden-md hidden-lg blue" >
							<span class="animate-default"><i class="fa fa-home" aria-hidden="true"></i>Accueil</span>
                            <i id="exitAppIcon" style="float: right; color: white; font-size:30px; margin-top: -6px;" class="fa fa-power-off" aria-hidden="true"></i>
						</a>
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
                            
                            <!-- Slide -->
                            <div class="clearfix slide-box-home slide-v1 relative">
                                <div class="clearfix slide-home owl-carousel owl-theme" id="main-slider">
                                    <?php 
                                        if( $pubMainSlide){
                                            foreach ($pubMainSlide as $key => $pub) {
                                               echo '<a href="'.$pub['lien_publicite'].'"><div class="item" style="text-align: center;padding-left:0%;"><img style="width: 350px; height: 150px; object-fit: cover; "src="'.base_url('assets/uploads/files/').$pub['image_publicite'].'" alt="Banner Header '.$key.'"/></div></a>';
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class=" box-banner-small-v1 box-banner-small" id="sous-slider">
                                <?php 
                                    if( $pubSousSlide){
                                        foreach ($pubSousSlide as $key => $pub) {
                                            echo ' <div class="effect-layla relative clear-padding col-md-4 col-sm-4 col-xs-4 float-left zoom-image-hover">
                                                        <img src="'.base_url('assets/uploads/files/').$pub['image_publicite'].'" alt="" style="width: 127px; height:120px; object-fit:cover;">
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
            
            <!-- Meilleurs Deals Product -->
            <div class="clearfix box-product full-width top-padding-default bg-gray" style="display:none;">
                <div class="clearfix container-web">
                    <div class=" container">
                        <div class="row">
                            <!-- Title Product -->
                            <div class="clearfix title-box full-width bottom-margin-default border bg-white">
                                <div class="clearfix name-title-box title-hot-bg relative">
                                    <img src="<?php echo base_url('assets/app_assets/')?>img/icon_percent.png" class="absolute" alt="Icon Hot Deals" />
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
                                            <?php 
                                                if($meilleurs_deals){
                                                    foreach ($meilleurs_deals as $key => $deals) {
                                                        if($key <= 10){
                                                            echo ' <div class="product-son">
                                                                        <div class="clearfix image-product relative animate-default">
                                                                            <div class="center-vertical-image">
                                                                                <a href="'.site_url('AppArticle/index/').$deals['id_article'].'">
                                                                                    <img style="width: 300px; height: 300px; object-fit: cover;" src="'.base_url('assets/uploads/files/').$deals['photo_article'].'" alt="'.$deals['photo_article'].'" class="image-article" />
                                                                                </a>                                              
                                                                            </div>
                                                                        </div>
                                                                        <p class="title-product clearfix full-width title-hover-black animate-default"><a href="'.site_url('AppArticle/index/').$deals['id_article'].'" class="animate-default name-article">'.$deals['nom_article'].'</a></p>
                                                                        <p class="clearfix price-product"><span class="price-old">'.number_format( ($deals['prix_vente']+1000),2).' CDF</span> <span class="price-article">'.number_format($deals['prix_vente'],2).' CDF</span></p>
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
                </div>
            </div>
            <!-- End Meilleurs Deals Product -->

            <!-- Start meilleurs deals Box -->
            <div class="full-width category-percent-two bottom-margin-default" style="display: none;">
                <div class="clearfix container-web">
                    <div class=" container">
                        <div class="row">
                            <div class=" clearfix content-left col-md-6 col-sm-6">
                                <!-- Title Product -->
                                <div class="clearfix title-box full-width border">
                                    <div class="clearfix name-title-box title-category title-gold-bg relative">
                                        <img src="<?php echo base_url('assets/app_assets/')?>img/icon_percent.png" alt="Icon Percent" class="absolute">
                                        <p>Meilleurs Deals</p>
                                    </div>
                                    <div class="clearfix menu-title-box">
                                        <p class="view-all-product-category title-hover-red"><a href="<?php echo site_url('AppCategorie/index/').$meilleurs_deals[0]['id_foreign_categorie'];?>" class="animate-default">PLUS</a></p>
                                    </div>
                                </div>
                                <!-- Content Product Box -->
                                <div class="clearfix product-percent-content border-collapsed-box full-width">
                                    <?php 
                                    
                                        if($meilleur_deal){
                                            foreach ($meilleurs_deals as $key => $meilleur_deal) {
                                                if($key<=3){
                                                    if(is_array($meilleur_deal)){
                                                        echo '  <div class="clearfix relative product-no-ranking border-collapsed-element percent-content-3">
                                                                    <div class="effect-hover-zoom center-vertical-image">
                                                                        <img style="height: 145px !important; object-fit: scale-down;" src="'.base_url('assets/uploads/files/').$meilleur_deal['photo_article'].'" alt="'.$meilleur_deal['nom_article'].'">
                                                                    </div>
                                                                    <br><br><br><br><br>
                                                                    <div class="clearfix absolute name-product-no-ranking">
                                                                        <p class="title-product clearfix full-width title-hover-black"><a style="color: #fff;  text-shadow: 2px 2px 3px #000;" href="'.site_url('AppArticle/index/').$meilleur_deal['id_article'].'">'.$meilleur_deal['nom_article'].'</a></p>
                                                                        <p class="clearfix price-product">
                                                                            <br>
                                                                            <span>'.number_format($meilleur_deal['prix_vente'],2).' CDF</span>
                                                                        </p>
                                                                        <p>
                                                                            <center>
                                                                                <form class="form_ajout_panier" method="POST" action="'.site_url('AppArticle/panier').'" name="form_add_article_'.$meilleur_deal['id_article'].'"> 
                                                                                    <input type="number" value="1" name="quantite" hidden>
                                                                                    <input type="number" value="'.$meilleur_deal['id_article'].'" name="id_article" hidden>
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
                                        }
                                    ?>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End meilleurs deals Box -->


            <!-- Publicités Half Website -->
            <div class="relative banner-half-web full-width bottom-margin-default">
                <div class="clearfix container-web">
                    <div class=" container">
                        <div class="row">
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
            <!-- End Publicités Half Website -->

            <!-- Habillements Box -->
            <div class="container-web">
                <div class=" container" style="min-height: 900px;">
                    <div class="row">
                        <div class="clearfix title-box full-width border">
                            <div class="clearfix name-title-box title-category title-turquoise-bg relative">
                                <img alt="Icon Electric" src="<?php echo base_url('assets/app_assets/')?>img/icon_percent.png" class="absolute" />
                                <p>Meubles</p>
                            </div>
                            <div class="clearfix menu-title-box">
                                <p class="view-all-product-category title-hover-red"><a style="font-size: 16px;" href="<?php if($habillements) echo site_url('AppCategorie/index/').$habillements[0]['id_foreign_categorie'];?>" class="animate-default">PLUS</a></p>
                            </div>
                        </div>
                        <?php 
                            if(!empty($pubHabillement)){
                                echo'<div class="banner-percent-product zoom-image-hover overfollow-hidden effect-oscar relative">
                                        <img src="'.base_url('assets/uploads/files/').$pubHabillement[$indexPubHabillement]['image_publicite'].'" class="max-width" alt="Image . . ." />
                                        <a href="'.$pubHabillement[$indexPubHabillement]['lien_publicite'].'"></a>
                                    </div>';
                            }
                        ?>
                        <div class="display-table bottom-margin-default full-width">
                            <div class="clearfix list-products-category list-products-category-v1 float-left relative">
                                <div class="border-collapsed-box active-box-category hidden-content-box box-electric-content animate-default" id="television">
                                    
                                    <?php 
                                        if($habillements){
                                            foreach ($habillements as $key => $habillement) {
                                                if( $key<=3){
                                                    echo '<div class="clearfix relative product-no-ranking border-collapsed-element percent-content-2">
                                                                <div class="effect-hover-zoom center-vertical-image">
                                                                    <img style="height: 145px !important; object-fit: scale-down;" src="'.base_url('assets/uploads/files/').$habillement['photo_article'].'" alt="'.$habillement['nom_article'].'">
                                                                </div>
                                                                <div class="clearfix absolute name-product-no-ranking">
                                                                    <p class="title-product clearfix full-width title-hover-black"><a href="'.site_url('AppArticle/index/').$habillement['id_article'].'" style="color: #fff;  text-shadow: 2px 2px 3px #000;">'.$habillement['nom_article'].'</a></p>
                                                                    <p class="clearfix price-product">
                                                                        <br>
                                                                        <span style="color:red;"> '. number_format($habillement['prix_vente'],2).' CDF<span>
                                                                    </p>
                                                                    <p>
                                                                        <center>
                                                                            <form class="form_ajout_panier" method="POST" action="'.site_url('AppArticle/panier').'" name="form_add_article_'.$habillement['id_article'].'"> 
                                                                                <input type="number" value="1" name="quantite" hidden>
                                                                                <input type="number" value="'.$habillement['id_article'].'" name="id_article" hidden>
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
                </div>
            </div>
            <!-- End Habillements Box -->
   
            <!-- Electronic  & Alimentations  -->
            <div class="full-width category-percent-two bottom-margin-default" style="margin-top: -220px;" >
                <div class="clearfix container-web">
                    <div class=" container">
                        <div class="row">
                            <div class=" clearfix content-left col-md-6 col-sm-6">
                                <!-- Title Product -->
                                <div class="clearfix title-box full-width border">
                                    <div class="clearfix name-title-box title-category title-gold-bg relative">
                                        <img src="<?php echo base_url('assets/app_assets/')?>img/icon_electric.png" alt="Icon Fashion" class="absolute" />
                                        <p>Equipements Industrielles</p>
                                    </div>
                                    <div class="clearfix menu-title-box">
                                        <p class="view-all-product-category title-hover-red"><a href="<?php if($electroniques) echo site_url('AppCategorie/index/').$electroniques[0]['id_foreign_categorie'];?>" class="animate-default">PLUS</a></p>
                                    </div>
                                </div>
                                <?php 
                                    if(!empty($pubElectronique)){
                                        echo'<div class="banner-percent-product zoom-image-hover overfollow-hidden effect-oscar relative">
                                                <img src="'.base_url('assets/uploads/files/').$pubElectronique[$indexPubElectronique]['image_publicite'].'" class="max-width" alt="Image . . ." />
                                                <a href="'.$pubElectronique[$indexPubElectronique]['lien_publicite'].'"></a>
                                             </div>';
                                    }
                                ?>
                                <!-- Content Product Box -->
                                <div class="clearfix product-percent-content border-collapsed-box full-width">
                                    
                                    <?php 
                                    
                                        if($electroniques){
                                            foreach ($electroniques as $key => $electronique) {
                                                if($key<=3){
                                                    echo '  <div class="clearfix relative product-no-ranking border-collapsed-element percent-content-3">
                                                                <div class="effect-hover-zoom center-vertical-image">
                                                                    <img style="height: 145px !important; object-fit: scale-down;" src="'.base_url('assets/uploads/files/').$electronique['photo_article'].'" alt="'.$electronique['nom_article'].'">
                                                                </div>
                                                                <br><br><br><br><br>
                                                                <div class="clearfix absolute name-product-no-ranking">
                                                                    <p class="title-product clearfix full-width title-hover-black"><a style="color: #fff;  text-shadow: 2px 2px 3px #000;" href="'.site_url('AppArticle/index/').$electronique['id_article'].'">'.$electronique['nom_article'].'</a></p>
                                                                    <p class="clearfix price-product">
                                                                        <br>
                                                                        <span>'.number_format($electronique['prix_vente'],2).' CDF</span>
                                                                    </p>
                                                                    <p>
                                                                        <center>
                                                                            <form class="form_ajout_panier" method="POST" action="'.site_url('AppArticle/panier').'" name="form_add_article_'.$electronique['id_article'].'"> 
                                                                                <input type="number" value="1" name="quantite" hidden>
                                                                                <input type="number" value="'.$electronique['id_article'].'" name="id_article" hidden>
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
                            <div class=" clearfix content-right col-md-6 col-sm-6">
                                <!-- Title Product -->
                                <div class="clearfix title-box full-width border">
                                    <div class="clearfix name-title-box title-category title-violet-bg relative">
                                        <img src="<?php echo base_url('assets/app_assets/')?>img/icon_percent.png" alt="Outils d'agriculture" class="absolute" />
                                        <p>Outils d'agriculture</p>
                                    </div>
                                    <div class="clearfix menu-title-box">
                                        <p class="view-all-product-category title-hover-red"><a href="<?php if($alimentations) echo site_url('AppCategorie/index/').$alimentations[0]['id_foreign_categorie'];?>" class="animate-default">PLUS</a></p>
                                    </div>
                                </div>
                                <?php 
                                    if(!empty($pubAlimentation)){
                                        echo'<div class="banner-percent-product zoom-image-hover overfollow-hidden effect-oscar relative">
                                                <img src="'.base_url('assets/uploads/files/').$pubAlimentation[$indexPubAlimentation]['image_publicite'].'" class="max-width" alt="Image . . ." />
                                                <a href="'.$pubAlimentation[$indexPubAlimentation]['lien_publicite'].'"></a>
                                             </div>';
                                    }
                                ?>
                                <!-- Content Product Box -->
                                <div class="clearfix product-percent-content border-collapsed-box full-width">

                                    <?php 
                                        if($alimentations){
                                            foreach ($alimentations as $key => $alimentation) {
                                                if($key<=3){

                                                    echo '  <div class="clearfix relative product-no-ranking border-collapsed-element percent-content-3">
                                                                <div class="effect-hover-zoom center-vertical-image">
                                                                    <img style="height: 145px !important; object-fit: scale-down;" src="'.base_url('assets/uploads/files/').$alimentation['photo_article'].'" alt="'.$alimentation['nom_article'].'">
                                                                </div>
                                                                <br><br><br><br><br>
                                                                <div class="clearfix absolute name-product-no-ranking">
                                                                    <p class="title-product clearfix full-width title-hover-black"><a style="color: #fff;  text-shadow: 2px 2px 3px #000;" href="'.site_url('AppArticle/index/').$alimentation['id_article'].'">'.$alimentation['nom_article'].'</a></p>
                                                                    <p class="clearfix price-product">
                                                                        <br>
                                                                        <span>'. number_format($alimentation['prix_vente'],2) .' CDF</span>
                                                                    </p>
                                                                    <p>
                                                                        <center>
                                                                            <form class="form_ajout_panier" method="POST" action="'.site_url('AppArticle/panier').'" name="form_add_article_'.$alimentation['id_article'].'"> 
                                                                                <input type="number" value="1" name="quantite" hidden>
                                                                                <input type="number" value="'.$alimentation['id_article'].'" name="id_article" hidden>
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
                </div>
            </div>
            <!-- End Electronic & Alimentations -->
           
            <!-- Entreprise Box -->
            <div class="container-web">
                <div class=" container" style="min-height: 900px;">
                    <div class="row">
                        <div class="clearfix title-box full-width border">
                            <div class="clearfix name-title-box title-category title-turquoise-bg relative">
                                <img alt="Icon Electric" src="<?php echo base_url('assets/app_assets/')?>img/icon_percent.png" class="absolute" />
                                <p>Entreprises</p>
                            </div>
                            <div class="clearfix menu-title-box">
                                <p class="view-all-product-category title-hover-red"><a style="font-size: 16px;" href="<?php if($automobiles) echo site_url('AppCategorie/index/').$automobiles[0]['id_foreign_categorie'];?>" class="animate-default">PLUS</a></p>
                            </div>
                        </div>
                        <?php 
                            if(!empty($pubAutomobile)){
                                echo'<div class="banner-percent-product zoom-image-hover overfollow-hidden effect-oscar relative">
                                        <img src="'.base_url('assets/uploads/files/').$pubAutomobile[$indexPubAutomobile]['image_publicite'].'" class="max-width" alt="Image . . ." />
                                        <a href="'.$pubAutomobile[$indexPubAutomobile]['lien_publicite'].'"></a>
                                    </div>';
                            }
                        ?>
                        <div class="display-table bottom-margin-default full-width">
                            <div class="clearfix list-products-category list-products-category-v1 float-left relative">
                                <div class="border-collapsed-box active-box-category hidden-content-box box-electric-content animate-default" id="television">
                                    
                                    <?php 
                                        if($automobiles){
                                            foreach ($automobiles as $key => $automobile) {
                                                if( $key<=3){
                                                    $message =  "Bonjour je suis interressé par votre véhicule  [ ".$automobile['nom_article']." ] ID système: [ ".$automobile['id_article']." ]";
                                                    echo '<div class="clearfix relative product-no-ranking border-collapsed-element percent-content-2">
                                                                <div class="effect-hover-zoom center-vertical-image">
                                                                    <img style="height: 145px !important; object-fit: scale-down;" src="'.base_url('assets/uploads/files/').$automobile['photo_article'].'" alt="'.$automobile['nom_article'].'">
                                                                </div>
                                                                <div class="clearfix absolute name-product-no-ranking">
                                                                    <p class="title-product clearfix full-width title-hover-black"><a style="color: #fff;  text-shadow: 2px 2px 3px #000;" href="'.site_url('AppArticle/index/').$automobile['id_article'].'" style="color: #000;  text-shadow: 2px 2px 3px #fff;">'.$automobile['nom_article'].'</a></p>
                                                                    <p class="clearfix price-product">
                                                                        <br>
                                                                        <span style="color:red;"> '.number_format($automobile['prix_vente'],2).' CDF<span>
                                                                    </p>
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
            <!-- End Entreprise Box -->

            <!-- Materiels Elétriques Box  -->
            <div class="container-web" style="margin-top: -220px;">
                <div class=" container" style="min-height: 900px;">
                    <div class="row">
                        <div class="clearfix title-box full-width border">
                            <div class="clearfix name-title-box title-category title-turquoise-bg relative">
                                <img alt="Icon Electric" src="<?php echo base_url('assets/app_assets/')?>img/icon_electric.png" class="absolute" />
                                <p>Materiels Elétriques</p>
                            </div>
                            <div class="clearfix menu-title-box">
                                <p class="view-all-product-category title-hover-red"><a style="font-size: 16px;" href="<?php if($electromenagers) echo site_url('AppCategorie/index/').$electromenagers[0]['id_foreign_categorie'];?>" class="animate-default">PLUS</a></p>
                            </div>
                        </div>
                        <?php 
                            if(!empty($pubElectromenager)){
                                echo'<div class="banner-percent-product zoom-image-hover overfollow-hidden effect-oscar relative">
                                        <img src="'.base_url('assets/uploads/files/').$pubElectromenager[$indexPubElectromenager]['image_publicite'].'" class="max-width" alt="Image . . ." />
                                        <a href="'.$pubElectromenager[$indexPubElectromenager]['lien_publicite'].'"></a>
                                    </div>';
                            }
                        ?>
                        <div class="display-table bottom-margin-default full-width">
                            <div class="clearfix list-products-category list-products-category-v1 float-left relative">
                                <div class="border-collapsed-box active-box-category hidden-content-box box-electric-content animate-default" id="television">
                                    
                                    <?php 
                                        if($electromenagers){
                                            foreach ($electromenagers as $key => $electromenager) {
                                                if( $key<=3){
                                                    echo '<div class="clearfix relative product-no-ranking border-collapsed-element percent-content-2">
                                                                <div class="effect-hover-zoom center-vertical-image">
                                                                    <img style="height: 145px !important; object-fit: scale-down;" src="'.base_url('assets/uploads/files/').$electromenager['photo_article'].'" alt="'.$electromenager['nom_article'].'">
                                                                </div>
                                                                <div class="clearfix absolute name-product-no-ranking">
                                                                    <p class="title-product clearfix full-width title-hover-black"><a style="color: #fff;  text-shadow: 2px 2px 3px #000;" href="'.site_url('AppArticle/index/').$electromenager['id_article'].'" style="color: #000;  text-shadow: 2px 2px 3px #fff;">'.$electromenager['nom_article'].'</a></p>
                                                                    <p class="clearfix price-product">
                                                                        <br>
                                                                        <span style="color:red;"> '.number_format( $electromenager['prix_vente'] ,2).' CDF<span>
                                                                    </p>
                                                                    <p>
                                                                        <center>
                                                                            <form class="form_ajout_panier" method="POST" action="'.site_url('AppArticle/panier').'" name="form_add_article_'.$electromenager['id_article'].'"> 
                                                                                <input type="number" value="1" name="quantite" hidden>
                                                                                <input type="number" value="'.$electromenager['id_article'].'" name="id_article" hidden>
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
                </div>
            </div>
            <!-- End Materiels Elétriques Box -->

            <!-- Slide Logo Brand -->
            <div class="slide-brand-box full-width bottom-margin-default" style="margin-top: -220px;">
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

            <!-- Banner Full With -->
            <div class="relative full-width bottom-margin-default">
                <div class="clearfix container-web">
                    <div class=" container banner_full_width">
                        <?php 
                            if($pubInsidePage){
                                $max_random = count($pubInsidePage)-1;
                                $index_pub_one  = mt_rand(0, $max_random );
                                echo'<div class="row relative banners-effect5 overfollow-hidden">
                                        <img style="width: 400px; height: 250px; object-fit: scale-down;" class="max-width" src="'.base_url('assets/uploads/files/').$pubInsidePage[$index_pub_one]['image_publicite'].'" alt="'.$pubInsidePage[$index_pub_one]['nom_publicite'].'""/>
                                        <a href="'.$pubInsidePage[$index_pub_one]['lien_publicite'].'"></a>
                                    </div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
            <!-- End Banner Full With -->


            <!-- Support -->
            <div class="support-box full-width clear-padding bottom-margin-default">
                <div class="container-web clearfix">
                    <div class=" container border top-padding-default bottom-padding-default">
                        <div class="row">
                            <div class=" support-box-info relative col-md-3 col-sm-3 col-xs-6">
                                <img src="<?php echo base_url('assets/app_assets/') ?>img/icon_free_ship.png" alt="Icon Free Ship" class="absolute" />
                                <p>Livraison</p>
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
            <!-- End Support Box -->

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
                                <li><i class="fa fa-home" aria-hidden="true"></i>  Lubumbashi RDC,</li>
                                <li>
                                    <a href="mailto:business@tedexpress.com" target="__blank">
                                        <i class="fa fa-envelope" aria-hidden="true"></i> business@tedexpress.com</li>
                                    </a>
                                <li>
                                    <a href="tel:+243999857746" target="__blank">
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
                                <p>Copyright © <?php echo date('Y') ?> by tedexpress. Tous droits reservés.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>


    <?php echo $search_bar; ?>

    <!-- End Footer Box -->
    <div id="javascript-divider"></div>

    <?php echo $footer; ?>

</body>

</html>