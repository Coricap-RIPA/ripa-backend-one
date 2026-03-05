<?php
    echo  $header;
    echo  $footer;
?>

<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/');?>grocery_crud/css/jquery_plugins/chosen/chosen.css" />

<style>
    .dark_border {
        border: 1px solid black !important;
        color: black !important;
    }

    #repport_container {
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

        <?php echo $navbar; ?>

        <div id="row_content" class="row">

            <div class="col s12">

                <div class="col s12">
                    <fieldset>
                        <legend> CONFIGURATIONS / RAPPORTS <span title="Imprimer tout les rapports" id="print_all_report" style="float: right; cursor: pointer;"> IMPRIMER</span> </legend>
                    </fieldset>
                </div>

                <div class="col s12">
                    <form action="<?php echo site_url('Rapport/index/'); ?>" method="POST">

                        <div class="row">

                            <div class="col s6">
                                <div class="form-group">
                                    <label for="start_date">Date de début</label>
                                    <input type="date" class="form-control" name="start_date" id="start_date" value="<?php if (isset($_POST['start_date'])) {
                                                                                                                            echo $_POST['start_date'];
                                                                                                                        } ?>" required>
                                </div>
                            </div>

                            <div class="col s6">
                                <div class="form-group">
                                    <label for="end_date">Date de Fin</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date" value="<?php if (isset($_POST['end_date'])) {
                                                                                                                        echo $_POST['end_date'];
                                                                                                                    } ?>" required>
                                </div>
                            </div>

                            <div class="col s4" style="display: none;">
                                <div class="form-group">
                                    <label for="id_article">Selectionnez un article </label>
                                    <select name="id_article" id="id_article" class="form-control browser-default input-sm chosen-select" required>
                                        <option value="null"> Selectionnez un article </option>
                                        <?php
                                        if (!empty($articles)) {
                                            foreach ($articles as $key => $article) {
                                                if (isset($_POST['id_article']) and $_POST['id_article'] == $article['id_article']) {
                                                    echo '<option selected value="' . $article['id_article'] . '">' . $article['nom_article'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $article['id_article'] . '">' . $article['nom_article'] . '</option>';
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col s4" style="display: <?php if( $this->session->userdata['user']['id_foreign_fournisseur'] == 1 ) echo 'block'; else echo'none'; ?>;">
                                <div class="form-group">
                                    <label for="id_fournisseur">Selectionnez un fournisseur </label>
                                    <select name="id_fournisseur" id="id_fournisseur" class="form-control browser-default input-sm" required>
                                        <option value="null"> Selectionnez un fournisseur </option>
                                        <?php
                                        if (!empty($fournisseurs)) {
                                            foreach ($fournisseurs as $key => $fournisseur) {
                                                if (isset($_POST['id_fournisseur']) and $_POST['id_fournisseur'] == $fournisseur['id_fournisseur']) {
                                                    echo '<option selected value="' . $fournisseur['id_fournisseur'] . '">' . $fournisseur['nom_fournisseur'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $fournisseur['id_fournisseur'] . '">' . $fournisseur['nom_fournisseur'] . '</option>';
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col s4">
                                <div class="form-group">
                                    <label for="">Rechercher</label>
                                    <button class="btn btn-primary col s12">Rechercher</button>
                                </div>
                            </div>

                            <div class="col s4">
                                <div class="form-group">
                                    <label for="">Effacer le filtre</label>
                                    <a href="<?php echo site_url('Rapport/index/'); ?>" class="btn btn-success col s12">Effacer le filtre</a>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                <div id="repport_container" class="col s12" style="margin-top: 30px;">
                    <?php if (!empty($commandes['commandes'])) { ?>
                        <div class="col s12">

                            <div class="row">
                                <fieldset>
                                    <legend>LISTE DES COMMANDES </legend>
                                </fieldset>
                            </div>

                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-4">
                                        <tbody>
                                            <tr style="background-color: #000;">
                                                <td colspan="6" class="text-center" style="color: #fff;"><b> TOTAL DES COMMANDES: <?php  echo number_format(count($commandes['commandes']), 0); ?> POUR UN MONTANT DE : <?php  echo number_format($commandes['total_commandes'], 2); ?> CDF</b></td>
                                            </tr>
                                            <?php 
                                                $cpt = 0;
                                                foreach ($commandes['commandes'] as $key => $commande) {

                                                    if( is_array($commande)){

                                                        $cpt ++;
                                                        $compter = 0;

                                                        echo '  <tr style="background-color: #fff;">
                                                                    <td colspan="1" class="text-justify" style="color: #000;"><b> '.$cpt.'</b></td>
                                                                    <td colspan="5" class="text-justify" style="color: #000;"><b> COMMANDE DU CLIENT : '.$commande['id_foreign_client']['nom_client'].' DU '.format_date_fr($commande['date_commande']).'</b></td>
                                                                </tr>';
    
                                                        echo '  <tr style="background-color: #3b3f5c;">
                                                                    <td class="text-center" style="color: #fff;"><b>#</b></td>
                                                                    <td class="text-center" style="color: #fff;"><b>ARTICLE</b></td>
                                                                    <td class="text-center" style="color: #fff;"><b>FOURNISSEUR</b></td>
                                                                    <td class="text-center" style="color: #fff;"><b>QTE</b></td>
                                                                    <td class="text-center" style="color: #fff;"><b>MONTANT</b></td>
                                                                    <td class="text-center" style="color: #fff;"><b>STATUS </b></td>
                                                                </tr>';
    
                                                        foreach ($commande['ARTICLES'] as $key_in => $article) {
                                                            $compter ++;
                                                            echo '  <tr style="background-color: #3b3f5c;">
                                                                        <td class="text-center" style="color: #fff;"><b>'.$compter.'</b></td>
                                                                        <td class="text-center" style="color: #fff;"><b>'.$article['nom_article'].'</b></td>
                                                                        <td class="text-center" style="color: #fff;"><b>'.$article['id_foreign_fournisseur']['nom_fournisseur'].'</b></td>
                                                                        <td class="text-center" style="color: #fff;"><b>'.$article['article_commande_quantite'].'</b></td>
                                                                        <td class="text-center" style="color: #fff;"><b>'.number_format($article['montant_total'], 2).' CDF</b></td>
                                                                        <td class="text-center" style="color: #fff;"><b>'.$commande['id_status']['html'].' </b></td>
                                                                    </tr>';
                                                        }
    
                                                        echo '  <tr style="background-color: #3b3f5c;">
                                                                    <td  colspan="6" class="text-right" style="color: #fff;"><b> TOTAL DE LA COMMANDE : '.number_format($commande['total'],2).' CDF</b></td>
                                                                </tr>';
                                                    }
                                                }
                                            
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    <?php } ?>
                </div>

            </div>

            <div id="footer_divider" style="display: none !important;">
                <?php echo $output; ?>
            </div>

            <div id="row_footer" class="row">
                <div class="footer-wrappervcol s12">
                    <div class="footer-section f-section-1 col s6" style="padding-left: 2%;">
                        <p class="">Copyright © <?php echo date('Y'); ?> <a target="_blank" href="#"> CADEAUMART </a>, Tout droits reservés. </p>
                    </div>
                    <div class="footer-section f-section-2 col s6 text-right" style="padding-right: 2%;">
                        <p class="">
                            Coded with: <a href="mailto:pascalmmp@gmail.com" title="+243971403075"> CORICAP </a>
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>


    <script src="<?php echo base_url('assets/');?>grocery_crud/js/jquery_plugins/jquery.chosen.min.js"></script>
    <script src="<?php echo base_url('assets/');?>grocery_crud/js/jquery_plugins/config/jquery.chosen.config.js"></script>

    <script>
        $(function() {

            var file_export_name = "<?php echo 'RAPPORT CADEAUMART MANAGEMENT GENERE LE '.date('d/m/Y') ; ?>" + ".pdf";

            function _export() {
                kendo.drawing
                    .drawDOM("#repport_container", {
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

            $("#print_all_report").click(function(e) {
                
                e.preventDefault();

                Snackbar.show({
                    text: 'Veuillez patienter durant la création du document',
                    pos: 'bottom-left',
                    actionText: 'OK '
                });
                _export();

            });
            
        });
    </script>

</body>

</html>

