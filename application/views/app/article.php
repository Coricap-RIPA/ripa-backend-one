<?php 
    //$refer 		=  $this->agent->referrer();
    $refer 		=  '';
    $refer      = (strlen($refer) >3 AND $refer <> 'https://localhost/') ? $refer : current_url();
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
                            <a class="menu-vertical hidden-md hidden-lg blue">
                                <span class="animate-default"><i class="fa fa-circle" aria-hidden="true"></i><?php if(!empty($article)) echo $article['nom_article'];?></span>
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
                <div class="container-web relative"> </div>
                <!-- End Breadcrumb -->
                <!-- Content Category -->
                <div class="relative container-web">
                    <div class="container">
                        <div class="row ">
                            <div class="col-md-9 relative clear-padding">
                                <!-- Product Content Detail -->
                                <div class="top-product-detail relative ">
                                    <div class="row">
                                        <!-- Slide Product Detail -->
                                        <br><br>
                                        <div class="col-md-7 relative col-sm-12 col-xs-12">
                                            <div id="owl-big-slide" class="relative sync-owl-big-image">
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
                                                <h1 class="name-product" ><?php if(!empty($article)) echo $article['nom_article'];?></h1>
                                                
                                                <p class="clearfix price-product">
                                                    <span class="price-old"><?php echo number_format(($article['prix_vente']+500),2);?> CDF</span> 
                                                    <?php echo number_format(($article['prix_vente']),2) ;?> CDF
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
                                            <form method="POST"  action="<?php echo site_url('AppArticle/panier') ?>" name="form_add_article">
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
                                <!-- Article Similaires  -->
                                <div class="slide-product-bottom relative" style="margin-top: -100px !important;">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12 relative slide-related-product" style="min-height: 800px;">
                                            <p class="bold title-slide-product-bottom">ARTICLES SIMILAIRES 
                                                <a href="<?php echo site_url('AppCategorie/index/').$arcticles_similaires[0]['id_foreign_categorie'];?>">
                                                    <span style="float: right !important;"> PLUS >></span>
                                                </a>
                                            </p>
                                            <!--  -->
                                            <div class="display-table bottom-margin-default full-width">
                                                <div class="clearfix list-products-category list-products-category-v1 float-left relative">
                                                    <div class="border-collapsed-box active-box-category hidden-content-box box-electric-content animate-default" id="television">
                                                        <?php 
                                                            if($arcticles_similaires){
                                                                foreach ($arcticles_similaires as $key => $arcticles_similaire) {
                                                                    if( $key<=5 AND ($arcticles_similaires[$key]['id_article'] <> $article['id_article']) ){
                                                                        echo ' <div class="clearfix relative product-no-ranking border-collapsed-element percent-content-2">
                                                                                    <div class="effect-hover-zoom center-vertical-image">
                                                                                        <img style="height: 145px !important; object-fit: scale-down;" src="'.base_url('assets/uploads/files/').$arcticles_similaire['photo_article'].'" alt="'.$arcticles_similaire['nom_article'].'">
                                                                                    </div>
                                                                                    <div class="clearfix absolute name-product-no-ranking">
                                                                                        <p style="font-size: 25px; " class="title-product clearfix full-width title-hover-black"><a href="'.site_url('AppArticle/index/').$arcticles_similaire['id_article'].'" style="color: #000; text-shadow: 2px 2px 3px #fff;">'.$arcticles_similaire['nom_article'].'</a></p>
                                                                                        <p style="margin: -2px !important; z-index:100 !important; " class="clearfix price-product">
                                                                                            <br>
                                                                                            <span style="color:red;"><a href="'.site_url('AppArticle/index/').$arcticles_similaire['id_article'].'" style="color: #000; text-shadow: 2px 2px 3px #fff;">'.number_format($arcticles_similaire['prix_vente'],2).' CDF</a><span>
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
                                    </div>
                                </div>
                                <!--/ Article Similaires  -->
                                <!-- End Product Content Category -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Sider Bar -->
                <!-- Support -->
                <div class=" support-box full-width clear-padding bottom-margin-default" style="margin-top: -30px !important;">
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
                                    <li><i class="fa fa-home" aria-hidden="true"></i>Lubumbashi RDC,</li>
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
                                    <p>Copyright © <?php echo date('Y') ?> by TedExpress. Tous droits reservés.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
	    </div>
	</div>

    <!-- Hider Search Modal Content -->
    <div id="searchBoxHider" class="container" style="background-color: #000 !important; opacity: 0.3; position: fixed; z-index: 9; width: 100%; height: 100% !important; bottom: -100%; left:0; transition: all 0.5s linear;" onclick="hideSearchBox()"> </div>
    <!-- End Hider Search Modal Content -->

    <!-- Search Modal Content  -->
    <div id="searchBoxContainer" class="container" style="background-color: #f5f5f4 !important; position: fixed !important; z-index: 10; width: 100% !important; height: 50% !important; bottom: -100%; left: 0; transition: all 0.5s linear;">
        <form class="class_search_form_app" method="POST" action="<?php echo site_url('AppSearch/index/');?>" name="search_form_app" id="search_form_app">
            <div class="row">

                <input type="text" name="path_search_seesion_form_app" value="<?php echo site_url('AppSearch/setSessionAppSearch/') ?>"  hidden>

                <div class="col" style="padding: 3%; box-sizing: border-box; margin-top:2%;">
                    <label>Nom du Produit  </label>
                    <input type="text" id="search_nom_article" name="nom_article" placeholder="Saisir ici . . ." style="color: #000; width: 100%; height: 40px !important;" value="<?php if( isset($_POST['nom_article']) AND !empty( $_POST['nom_article']) ) echo $_POST['nom_article']; ?>">
                </div>

                <div class="col" style="padding: 3%; box-sizing: border-box;">
                    <label>Liste des maisons  </label>
                    <select id="search_id_fournisseur" class="col" name="id_fournisseur" style="color: #000 !important; width: 100% !important; height: 40px !important;">
                        <option value="null">Selectionnez  une maison (toutes) </option>
                        <?php 
                            if (isset($fournisseurs)) {
                                foreach ($fournisseurs as $key => $fournisseur) {
                                    if( isset($_POST['id_fournisseur']) AND $_POST['id_fournisseur'] == $fournisseur['id_fournisseur']){
                                        echo '<option value="'.$fournisseur['id_fournisseur'].'"  selected> '.$fournisseur['nom_fournisseur'].'</option>';
                                    }else{
                                        echo '<option value="'.$fournisseur['id_fournisseur'].'"> '.$fournisseur['nom_fournisseur'].'</option>';
                                    }
                                }
                            }
                        ?>
                    </select>
                </div>

                <div class="col" style="padding: 3%; box-sizing: border-box;">
                    <label>Liste des catégories  </label>
                    <select id="search_id_categorie" class="col" name="id_categorie" style="color: #000 !important; width: 100% !important; height: 40px !important;">
                        <option value="null">Selectionnez une catégorie (toutes)</option>
                        <?php 
                            if (isset($categories_articles)) {
                                foreach ($categories_articles as $key => $categorie_article) {
                                    if( isset($_POST['id_categorie']) AND $_POST['id_categorie'] == $categorie_article['id_categorie']){
                                        echo '<option value="'.$categorie_article['id_categorie'].'"  selected> '.$categorie_article['nom_categorie'].'</option>';
                                    }else{
                                        echo '<option value="'.$categorie_article['id_categorie'].'"> '.$categorie_article['nom_categorie'].'</option>';
                                    }
                                }
                            }
                        ?>
                    </select>
                </div>

                <div class="col" style="padding: 3%; box-sizing: border-box;">
                    <button style=" width: 100% !important; border-radius: 5px; border: none; color:#fff; background-color: #2196f3; height: 40px; padding-left: 5px; padding-right: 5px;" class="animate-default"  type="submit"> RECHERCHER </button>
                </div>

            </div>
        </form>
    </div>
    <!-- End Search Modal Content  -->

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