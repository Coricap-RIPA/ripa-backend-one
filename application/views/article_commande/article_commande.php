<?php
    echo  $header;
    echo  $footer;
?>


<style>
    .dark_border {
        border: 1px solid black !important;
        color: black !important;
    }

    #facture_container {
        font-family: "DejaVu Sans", "Arial", sans-serif;
        font-size: 12px;
    }

    @font-face {
        font-family: "DejaVu Sans";
        src: url("https://kendo.cdn.telerik.com/2022.1.119/styles/fonts/DejaVu/DejaVuSans.ttf") format("truetype");
    }

    @font-face {
        font-family: "DejaVu Sans";
        font-weight: bold;
        src: url("https://kendo.cdn.telerik.com/2022.1.119/styles/fonts/DejaVu/DejaVuSans-Bold.ttf") format("truetype");
    }

    @font-face {
        font-family: "DejaVu Sans";
        font-style: italic;
        src: url("https://kendo.cdn.telerik.com/2022.1.119/styles/fonts/DejaVu/DejaVuSans-Oblique.ttf") format("truetype");
    }

    @font-face {
        font-family: "DejaVu Sans";
        font-weight: bold;
        font-style: italic;
        src: url("https://kendo.cdn.telerik.com/2022.1.119/styles/fonts/DejaVu/DejaVuSans-Oblique.ttf") format("truetype");
    }
</style>

<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/');?>grocery_crud/css/jquery_plugins/chosen/chosen.css" />

<body style="background-color: whitesmoke;">

    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <div class="main_container">

        <?php echo $navbar;?>

        <div id="row_content" class="row">

            <?php if( isset($client['nom_client']) AND isset( $commande['montant_total_commande'] ) ) {?>

                <div class="row col s12">
                    <fieldset>
                        <legend>
                            LISTE D'ARTICLES COMMANDES POUR LE CLIENT <?php echo strtoupper($client['nom_client'])." <br>TEL : ".$client['tel_whatsapp_client']." <br> MONTANT : ".number_format($commande['montant_total_commande'],2); ?> USD
                        </legend>
                    </fieldset>
                </div>

            <?php }?>

            <div class="col s6">
                <a class="btn btn-primary" href="<?php echo site_url('Commande/index/'); ?>"> RETOUR AUX COMMANDES</a>
            </div>

            <div class="col s6">
                <a class="btn orange white-text" href="#" style="float: right;" id="print_facture"> IMPRIMER LA FACTURE </a>
            </div>


            <?php if(!empty( $commande_fournisseur) AND !empty($article_fournisseur) ) {?>
                <div class="col s12">

                    <div class="row col s12" style="margin-bottom: -10px;">
                        <fieldset>
                            <legend>
                            FILTRE SUR LES ARTICLES COMMANDES 
                            </legend>
                        </fieldset>
                    </div>

                    <form action="<?php echo site_url('Article_commande/index/');?>" method="POST">

                        <div class="row">

                            <div class="col s3">
                                <div class="form-group">
                                    <label for="id_foreign_commande">Selectionnez un numéro de commande </label>
                                    <select name="id_foreign_commande" id="id_foreign_commande" class="form-control browser-default input-sm chosen-select" >
                                        <option value="null"> Selectionnez un numéro de commande </option>
                                        <?php
                                        if (!empty($commande_fournisseur)) {
                                            foreach ($commande_fournisseur as $key => $cmd_four) {
                                                if (isset($_POST['id_foreign_commande']) and $_POST['id_foreign_commande'] == $cmd_four['id_foreign_commande']) {
                                                    echo '<option selected value="' . $cmd_four['id_foreign_commande'] . '"> Commande N<sup>o</sup>: ' . $cmd_four['id_foreign_commande'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $cmd_four['id_foreign_commande'] . '">Commande N<sup>o</sup>: ' . $cmd_four['id_foreign_commande'] . '</option>';
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col s3">
                                <div class="form-group">
                                    <label for="id_foreign_article">Selectionnez un article </label>
                                    <select  name="id_foreign_article" id="id_foreign_article" class="form-control browser-default input-sm chosen-select" >
                                        <option value="null"> Selectionnez un article </option>
                                        <?php
                                        if (!empty($article_fournisseur)) {
                                            foreach ($article_fournisseur as $key => $art_four) {
                                                if (isset($_POST['id_foreign_article']) AND ($_POST['id_foreign_article'] == $art_four['id_article']) ) {
                                                    echo '<option selected value="' . $art_four['id_article'] . '">' . $art_four['nom_article'] . ' ( CODE: '.$art_four['code'].' )</option>';
                                                } else {
                                                    echo '<option value="' . $art_four['id_article'] . '">' . $art_four['nom_article'] . ' (CODE: '.$art_four['code'].' )</option>';
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col s3">
                                <div class="form-group">
                                    <label for="">Rechercher</label>
                                    <button class="btn btn-primary col s12">Rechercher</button>
                                </div>
                            </div>

                            <div class="col s3">
                                <div class="form-group">
                                    <label for="">Effacer le filtre</label>
                                    <a href="<?php echo site_url('Article_commande/index/'); ?>" class="btn btn-success col s12">Effacer le filtre</a>
                                </div>
                            </div>

                        </div>
                        
                    </form> 

                </div>
            <?php } ?>

            <?php if(!empty( $commande_fournisseur) AND !empty($status_commandes) ) {?>
                <div class="row col s12" style="margin-bottom: -10px;">
                    <fieldset>
                        <legend>
                            MISE A JOUR DU STATUS DE LA COMMANDE 
                        </legend>
                    </fieldset>

                    <form action="<?php echo site_url('Article_commande/index/');?>" method="POST">

                        <div class="row">

                            <div class="col s4">
                                <div class="form-group">
                                    <label for="id_foreign_commande_status">Selectionnez un numéro de commande </label>
                                    <select name="id_foreign_commande_status" id="id_foreign_commande_status" class="form-control browser-default input-sm chosen-select">
                                        <?php
                                        if (!empty($commande_fournisseur)) {
                                            foreach ($commande_fournisseur as $key => $cmd_four) {
                                                if (isset($_POST['id_foreign_commande_status']) and $_POST['id_foreign_commande_status'] == $cmd_four['id_foreign_commande']) {
                                                    echo '<option selected value="' . $cmd_four['id_foreign_commande'] . '"> Commande No: ' . $cmd_four['id_foreign_commande'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $cmd_four['id_foreign_commande'] . '">Commande No: ' . $cmd_four['id_foreign_commande'] . '</option>';
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col s4">
                                <div class="form-group">
                                    <label for="id_status_commande">Selectionnez un status </label>
                                    <select name="id_status_commande" id="id_status_commande" class="form-control browser-default input-sm chosen-select">
                                        <?php
                                        if (!empty($status_commandes)) {
                                            foreach ($status_commandes as $key => $status_commande) {
                                                if (isset($_POST['id_status_commande']) and $_POST['id_status_commande'] == $status_commande['id_status_commande']) {
                                                    echo '<option selected value="' . $status_commande['id_status_commande'] . '">' . $status_commande['designation'] .'</option>';
                                                } else {
                                                    echo '<option value="' . $status_commande['id_status_commande'] . '">' . $status_commande['designation'] .'</option>';
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col s4">
                                <div class="form-group">
                                    <label for="">Mettre à jour</label>
                                    <button class="btn btn-primary col s12">Mettre à jour</button>
                                </div>
                            </div>

                        </div>
                        
                    </form> 

                </div>
            <?php } ?> 

            <div class="row col s12" style="margin-top: 40px;">
                <fieldset>
                    <legend>
                        LISTE D'ARTICLES COMMANDES 
                    </legend>
                </fieldset>
            </div>
            
            <div class="col s12">
                <?php echo $output; ?>
            </div>

        </div>


        

        <div class="row" id="facture_container" style="display: none;">
            <div class="table-responsive">
                <table class="table table-bordered mb-4">
                    <tbody>

                        <tr style="background-color: #000;">
                            <td colspan="5" class="text-center" style="color: #fff;"><b> FACTURE CADEAUMART -  TOTAL D'ARTICLES: <?php  echo number_format(count($article_commandes), 0); ?> - POUR UN MONTANT DE : <?php  echo number_format($commande['montant_total_commande'],2); ?> USD</b></td>
                        </tr>
                        <tr style="background-color: #fff;">
                            <td colspan="5" class="text-justify" style="color: #000;"><b> COMMANDE DU CLIENT : <?php  echo $client['nom_client'].' / '.$client['tel_whatsapp_client']; ?> DU <?php echo format_date_fr($commande['date_commande']); ?> </b></td>
                        </tr>
                        <tr style="background-color: #3b3f5c;">
                            <td class="text-center" style="color: #fff;"><b>#</b></td>
                            <td class="text-center" style="color: #fff;"><b>ARTICLE</b></td>
                            <td class="text-center" style="color: #fff;"><b>FOURNISSEUR</b></td>
                            <td class="text-center" style="color: #fff;"><b>QTE</b></td>
                            <td class="text-center" style="color: #fff;"><b>MONTANT</b></td>
                        </tr>

                        <?php 
                            $cpt = 0;
                            $compter = 0;

                            if(isset($article_commandes)){

                                foreach ($article_commandes as $key => $article_commande) {
    
                                    $compter++;

                                    echo '  <tr style="background-color: #3b3f5c;">
                                                <td class="text-center" style="color: #fff;"><b>'.$compter.'</b></td>
                                                <td class="text-center" style="color: #fff;"><b>'.$article_commande['id_foreign_article']['nom_article'].'</b></td>
                                                <td class="text-center" style="color: #fff;"><b>'.$article_commande['id_foreign_fournisseur_commande']['nom_fournisseur'].'</b></td>
                                                <td class="text-center" style="color: #fff;"><b>'.$article_commande['article_commande_quantite'].'</b></td>
                                                <td class="text-center" style="color: #fff;"><b>'.number_format($article_commande['montant_total'], 2).' USD</b></td>
                                            </tr>';
                                    
                                }
                            }
                        
                        ?>

                        <tr style="background-color: #3b3f5c;">
                            <td  colspan="5" class="text-right" style="color: #fff;"><b> TOTAL DE LA COMMANDE : <?php echo number_format($commande['montant_total_commande'],2); ?> USD</b></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>


        <div id="row_footer" class="row">
            <div class="footer-wrapper col s12">
                <div class="footer-section f-section-1 col s6" style="padding-left: 2%;">
                    <p class="">Copyright © <?php echo date('Y'); ?> <a target="_blank" href="#"> Cadeau Mart </a>, Tout droits reservés. </p>
                </div>
                <div class="footer-section f-section-2 col s6 text-right" style="padding-right: 2%;">
                    <p style="float: right;">
                        Coded with: <a href="mailto:pascalmmp@gmail.com" title="+243971403075"> CORICAP </a>
                    </p>
                </div>
            </div>
        </div>

    </div>

    <script src="<?php echo base_url('assets/');?>grocery_crud/js/jquery_plugins/jquery.chosen.min.js"></script>
    <script src="<?php echo base_url('assets/');?>grocery_crud/js/jquery_plugins/config/jquery.chosen.config.js"></script>
    
    <script>
        $(function() {

            var file_export_name = "<?php echo 'FACTURE CADEAUMART DU CLIENT '.$client['nom_client']; ?>" + ".pdf";

            function _export() {
                kendo.drawing
                    .drawDOM("#facture_container", {
                        paperSize: "A4",
                        margin: {
                            top: "0.5cm",
                            bottom: "1.5cm",
                            left: "0.5cm",
                            right: "0.5cm",
                        },
                        scale: 0.5,
                        height: 500
                    })
                    .then(function(group) {
                        kendo.drawing.pdf.saveAs(group, file_export_name)
                    });
            }

            $("#print_facture").click(function(e) {
                
                e.preventDefault();

                $("#facture_container").show();

                Snackbar.show({
                    text: 'Veuillez patienter durant la création de la facture',
                    pos: 'bottom-left',
                    actionText: 'OK '
                });


                setTimeout(() => {
                    _export();
                   setTimeout(() => {
                        $("#facture_container").hide();
                   }, 2000);
                }, 500);




            });
            
        });
    </script>

</body>

</html>
