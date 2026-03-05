<?php
    echo  $header;
    echo  $footer;

?>

<style>
    .commande_header {
        color: #1b55e2;
        font-weight: 900;
        font-size: 13px;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-family: Arial, Helvetica, sans-serif;
    }
    .chosen-container, .chosen-container-multi {
        width : 100% !important; 
    }
</style>

<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/grocery_crud/css/jquery_plugins/chosen/chosen.css'); ?>" />
<script src="<?php echo base_url('assets/grocery_crud/js/jquery_plugins/jquery.chosen.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/grocery_crud/js/jquery_plugins/config/jquery.chosen.config.js'); ?>"></script>


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
                        <legend> SELECTIONNEZ UN LABORANTIN OU ENTREZ UN NOM </legend>
                    </fieldset>
                </div>

                <div class="col s12">
                    <form action="<?php echo site_url('Sortie_stock/index/'); ?>" method="POST">

                        <div class="col s6">
                            <div class="form-group">
                                <label for="id_client">Sélectionnez un client </label>
                                <select name="id_client" id="id_client" class="form-control input-sm chosen-select">
                                    <option value="null"> Sélectionnez un client  </option>
                                    <?php
                                    if (!empty($clients)) {
                                        foreach ($clients as $key => $client) {
                                            if (isset($sortie_stock['id_client']) and $sortie_stock['id_client'] == $client['id_client']) {
                                                echo '<option selected value="' . $client['id_client'] . '">' . $client['nom_client'] . '</option>';
                                            } else {
                                                echo '<option value="' . $client['id_client'] . '">' . $client['nom_client'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col s6">
                            <div class="form-group">
                                <label for="nom_client">Entrez le nom d'un client</label>
                                <input type="text" class="form-control" name="nom_client" id="nom_client" value="<?php if (isset($sortie_stock['nom_client'])) {
                                                                                                                        echo $sortie_stock['nom_client'];
                                                                                                                    } ?>" required>
                                <input type="number" id="id_sortie_stock" value="<?php if (isset($sortie_stock['id_sortie_stock'])) {
                                                                                        echo $sortie_stock['id_sortie_stock'];
                                                                                    } else {
                                                                                        echo 0;
                                                                                    } ?>" name="id_sortie_stock" style="display: none;">
                            </div>
                        </div>

                        <div class="col s6">
                            <div class="form-group">
                                <label for="nom_client">Entrez le nom d'un article (Produit) </label>
                                <select id="helper_selection_produit" multiple="multiple" class="chosen-multiple-select" data-placeholder="Ecrivez le nom d'un produit ici ... ">
                                    <?php  
                                        foreach ($articles_ventes as $key => $articles_vente) {
                                            echo '<option id="'.$articles_vente['id_article'].'" value="'.$articles_vente['id_article'].'" href="'.$articles_vente['id_article'].'-'.$articles_vente['prix_vente'].'-'.$articles_vente['quantite'].'" >'.$articles_vente['nom_article'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col s6">
                            <div class="form-group">
                                <label for="">VOIR LA LISTE DES SORTIES DE STOCK</label>
                                <a href="<?php echo site_url('Sortie_stock_list/index/'); ?>" class="btn btn-success col s12">VOIR LA LISTE DES SORTIES DE STOCK</a>
                            </div>
                        </div>

                    </form>
                </div>

                <div style="display: none;">
                    <?php echo $output; ?>
                </div>

                <div class="col s8" style="margin-top: 30px;" id="list_produit_container">
                    <div class="widget widget-chart-one" style="min-height: 1693px; overflow-y: auto; overflow-x: hidden;">
                        <?php 
                            if(isset($articles_ventes) AND !empty($articles_ventes)){

                                //echoDie($articles_ventes);

                                foreach ($articles_ventes as $key => $articles_vente) {

                                    //echoDie($articles_vente);
                                    $articles_vente['photo_article'] = (strlen($articles_vente['photo_article']) >3 ) ? $articles_vente['photo_article'] : 'lmds-image.jpeg';
                                    $articles_vente['prix_vente']    = (isset( $articles_vente['prix_vente'] ) ) ? $articles_vente['prix_vente'] : 0;
                                    $articles_vente['categorie'] = (!empty($articles_vente['categorie'])) ? $articles_vente['categorie'] : 'pièces';
                                    $articles_vente['color'] = ($articles_vente['quantite'] > $articles_vente['seuil_critique']) ? '#cddc39' : 'red';

                                    echo ' <a style="text-decoration: none; color:none; cursor:pointer;" class="add_to_order" href="'.$articles_vente['id_article'].'-'.$articles_vente['prix_vente'].'-'.$articles_vente['quantite'].'">
                                                <div class="col s3 card" style="height: 170px; width: 23%; padding: 0; border-radius: 10px; overflow: hidden; margin: 1%;">
                                                    <div class="col s12 black" style="position: absolute; top: 0;  right: -5px; z-index: 10; height: 15%;  padding-top: 3px;"> 
                                                        <b style="font-family:Quicksand; color: '.$articles_vente['color'].' !important;font-size: 12px;"> Quantité : '.number_format($articles_vente['quantite']).' '.$articles_vente['categorie'].'</b>
                                                    </div>
                                                    <div class="card-image" style="width: 100%; height: 100%;">
                                                        <img class="image_zomm" src="'.base_url('assets/uploads/files/').$articles_vente['photo_article'].'" style="width: 100%; height: 147px; object-fit: cover;">
                                                    </div>
                                                    <div class="col s12" style="position: absolute; bottom: 0; height: 15%;  padding-top: 2%; "> 
                                                        <b class="nom_article" style="font-family: Quicksand; color: #000 !important;"> '.$articles_vente['nom_article'].' </b>
                                                    </div>
                                                </div>
                                            </a>';


                                }
                            }
                        
                        ?>   
                    </div>
                </div>

                <div class="col s4" style="margin-top: 30px;" id="list_commande_container">
                    <div class="widget widget-chart-one" style="min-height: 1693px; overflow-y: auto; overflow-x: hidden;">

                        <br>
                        <form>
                            <div class="col s12" id="commande_header">
                                <center>
                                    <fieldset>
                                        <legend><span class="col s6">SORTIE - DU</span> <input id="date_sortie" type="date" class="form-control input-sm col s4" name="date_sortie" value="<?php if(!empty($sortie_stock)) echo $sortie_stock['date_enregistrement_sortie_stock'];  else echo date('Y-m-d'); ?>"/> </legend>
                                    </fieldset>
                                </center>
                            </div>

                            <div class="col s12" id="commande_container" style="padding-top: 3px;">
                                <div class="row" style="margin-bottom: 0;">
                                    <fieldset>
                                        <legend>
                                            <div class="col s1 commande_header"> # </div>
                                            <div class="col s5 text-center commande_header"> Produit </div>
                                            <div class="col s3 text-center commande_header"> Quantité </div>
                                            <div class="col s3 text-center commande_header"> Action </div>
                                        </legend>
                                    </fieldset>
                                </div>
                                <?php
                                if (isset($article_sortie_ventes) and !empty($article_sortie_ventes)) {
                                    $compteur = 0;
                                    foreach ($article_sortie_ventes as $key => $article_sortie_vente) {
                                        $compteur++;
                                        echo '<div class="row commande_detail">
                                                    <div class="col s1">' . $compteur . '</div>
                                                    <div class="col s5 text-center">' . $article_sortie_vente['nom_article'] . '</div>
                                                    <div class="col s3 text-center"> 
                                                        <input type="number" name="quantite_article" value="' . $article_sortie_vente['quantite'] . '" style="height: 1rem;"> 
                                                        <input type="number" name="id_article" value="' . $article_sortie_vente['id_article'] . '" style="display: none;"> 
                                                        <input type="number" value="' . $article_sortie_vente['prix_vente'] . '" name="prix_article" style="display: none;">
                                                    </div>
                                                    <div class="col s3 text-center"> 
                                                        <i class="fa fa-trash fa-1x delete_row_commande" style="color: red; font-size: 20px !important; cursor:pointer;"></i>
                                                    </div>
                                                </div>';
                                    }
                                }
                                ?>
                            </div>



                            <div class="col s12" id="commande_resume__reduction_total">
                                <div class="divider"></div><br>
                                <div class="col s3"></div>
                                <div class="col s9">

                                    <div class="col s12"  style="display:block;">
                                        <fieldset>
                                            <legend>
                                                <h6 class="text-left" style="font-size: 14px; font-weight: bolder; font-family: 'Quicksand', sans-serif;"> SOUS TOTAL : <span id="commande_sous_total"> 0000000.00 $ </span></h6>
                                            </legend>
                                        </fieldset>
                                    </div>

                                    <div class="col s12" style="display:block;">
                                        <fieldset>
                                            <legend>
                                                <h6 class="text-left" style="font-size: 14px; font-weight: bolder; font-family: 'Quicksand', sans-serif;">REDUCTION % : <input type="number" class="col s5" name="taux_reduction" id="taux_reduction_commande" value="<?php if (isset($sortie_stock['taux_reduction'])) {
                                                                                                                                                                                                                                                                            echo $sortie_stock['taux_reduction'];
                                                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                                            echo 0;
                                                                                                                                                                                                                                                                        } ?>" style="height: 1rem; float: right;"></h6>
                                            </legend>
                                        </fieldset>
                                    </div>

                                    <div class="col s12"  style="display:block;">
                                        <fieldset>
                                            <legend>
                                                <h6 class="text-left" style="font-size: 14px; font-weight: bolder; font-family: 'Quicksand', sans-serif;"> GRAND TOTAL : <span id="commande_grand_total"> 0000000.00 $ </span></h6>
                                            </legend>
                                        </fieldset>
                                    </div>

                                </div>
                            </div>

                            <div class="col s12" id="commande_validation_container">
                                <div class="form-group">
                                    <button type="submit" id="commande_validation" class="btn btn-primary col s12">VALIDER</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

        <div id="row_footer" class="row">
            <div class="footer-wrappervcol s12">
                <div class="footer-section f-section-1 col s6" style="padding-left: 2%;">
                    <p class="">Copyright © <?php echo date('Y'); ?> <a target="_blank" href="#"> CADEAUMART </a>, All rights reserved. </p>
                </div>
                <div class="footer-section f-section-2 col s6 text-right" style="padding-right: 2%;">
                    <p class="">
                        Coded with: <a href="mailto:pascalmmp@gmail.com" title="+243971403075"> CORICAP </a>
                    </p>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(function() {

            var compteur_keypress = 0;
            var compteur_sorting_click = 0;
            var compteur = 1;
            var compt = 0;
            var last_array_produit_val = [];
            var next_array_produit_val = [];

            $("#helper_selection_produit").change(function (e) { 

                e.preventDefault();
                last_array_produit_val = next_array_produit_val;
                next_array_produit_val = ($(this).val() !== null ) ? $(this).val() : [];

                if( next_array_produit_val.length > last_array_produit_val.length  ){
                    change_to_add_product_to_commmande( last_array_produit_val, next_array_produit_val );
                }else{
                    change_remove_product_to_commande(last_array_produit_val, next_array_produit_val);
                }

            });


            function change_to_add_product_to_commmande(values_in_1,values_in_2) {
                let tab_index = values_in_2.filter(item => !values_in_1.includes(item));
                let href_attribute = $("#helper_selection_produit").find("#"+tab_index[0]).attr('href');
                let tab_href_attribute = href_attribute.split("-");
                if (tab_href_attribute[2] == 0) {
                    swal("Impossible de faire une sortie, le stock est vide !");
                }else{
                    add_product_to_commande(tab_href_attribute[0], tab_href_attribute[1], $.trim( $("#helper_selection_produit").find("#"+tab_index[0]).text() )  );
                }               
            }


            function change_remove_product_to_commande(values_1, values_2) {
                let index = values_1.filter(item => !values_2.includes(item));
                let parent_element =  $("#list_commande_container").find('#'+index[0]);
                parent_element.remove();
                if (compteur >= 1) {
                    compteur--;
                }
                get_global_commande_detail();
            }



            $('body').click(function(event) {
                if ($(event.target).is('a')) {
                    if ($(event.target).attr('aria-controls')) {
                        click_to_add_product_to_commmande();
                    }
                }
            });


            $('input[type="search"]').keypress(function(e) {
                click_to_add_product_to_commmande();
            });


            $('.sorting').click(function(e) {

                compteur_sorting_click++;

                if (compteur_sorting_click == 1) {
                    click_to_add_product_to_commmande();
                }

            });


            function click_to_add_product_to_commmande() {
                $(".add_to_order").click(function(event) {
                    event.preventDefault();
                    let href_val = $(event.target).parent().parent().parent().attr('href');
                    let tab_href_val = href_val.split("-");
                    let nom_article = $(event.target).parent().parent().parent().find(".nom_article").text();
                    if (tab_href_val[2] == 0) {
                        swal("Impossible de faire une sortie, le stock est vide !");
                    }else{
                        add_product_to_commande(tab_href_val[0], tab_href_val[1], $.trim(nom_article));
                    }
                });
            }
            click_to_add_product_to_commmande();

            function add_product_to_commande(id_article, prix_article, nom_article) {

                let inset_row_commande = true;
                compteur = 1;

                $("#commande_container").find('.commande_detail').each(function(ind, elem) {
                    compteur++
                    let id_article_in = $(elem).find('input[name="id_article"]').val();
                    if (parseInt(id_article_in) == parseInt(id_article)) {
                        inset_row_commande = false;
                    }
                });

                let string_html = '<div class="row commande_detail" id="'+id_article+'">' +
                    '<div class="col s1">' + compteur + '</div>' +
                    '<div class="col s5 text-center">' + nom_article + '</div>' +
                    '<div class="col s3 text-center"> <input type="number" name="quantite_article" value="1" style="height: 1rem;" /> <input type="number" name="id_article" value="' + id_article + '" style="display: none;" /> <input type="number" value="' + prix_article + '" name="prix_article" style="display: none;" />  </div>' +
                    '<div class="col s3 text-center"> <i class="fa fa-trash fa-1x delete_row_commande" style="color: red; font-size: 20px !important; cursor:pointer;"></i></div>' +
                    '</div>';


                if (inset_row_commande) {
                    $("#commande_container").append(string_html);
                    blur_callback_quantite_article();
                    get_global_commande_detail();
                    remove_product_to_commande();
                } else {
                    swal("Le produit est déjà sur la liste !");
                }
            }

            function remove_product_to_commande() {
                $(".delete_row_commande").each(function(ind, elem) {
                    $(elem).click(function(event) {
                        let parent_element = $(this).parent().parent();
                        let next_parent = parent_element.next();
                        let br_element = next_parent.next();
                        parent_element.remove();
                        if (compteur >= 1) {
                            compteur--;
                        }
                        get_global_commande_detail();
                    });
                });
            }
            remove_product_to_commande();


            function blur_callback_quantite_article() {
                $('input[name="quantite_article"]').blur(function(event) {
                    get_global_commande_detail();
                });
            }
            blur_callback_quantite_article()


            function dislay_sous_total_commande(sous_total) {
                $("#commande_sous_total").text(sous_total.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' $');
            }


            function dislay_grand_total_commande(sous_total) {
                let grand_total = sous_total - ((sous_total * parseInt($("#taux_reduction_commande").val()) / 100));
                $("#commande_grand_total").text(grand_total.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' $');
            }

            function calculate_reduction() {
                $("#taux_reduction_commande").blur(function(event) {
                    dislay_grand_total_commande(get_global_commande_detail().total_commande);
                });
            }
            calculate_reduction();

            function get_global_commande_detail() {

                let total_nbr_commande = 0;
                let total_commande = 0;
                let total_ids_article = "";
                let total_qte_article = "";
                let total_prix_article = "";


                $("#commande_container").find('.commande_detail').each(function(ind, elem) {
                    let quantite_article = $(elem).find('input[name="quantite_article"]').val();
                    let id_article = $(elem).find('input[name="id_article"]').val();
                    let prix_article = $(elem).find('input[name="prix_article"]').val();

                    total_commande = total_commande + (quantite_article * prix_article);
                    total_ids_article = total_ids_article + "-" + id_article;
                    total_qte_article = total_qte_article + "-" + quantite_article;
                    total_prix_article = total_prix_article + "-" + prix_article;
                    total_nbr_commande = total_nbr_commande + 1;

                });

                let taux_reduction = parseInt($("#taux_reduction_commande").val());
                total_ids_article = total_ids_article.substring(1);
                total_qte_article = total_qte_article.substring(1);
                total_prix_article = total_prix_article.substring(1);

                dislay_sous_total_commande(total_commande);
                dislay_grand_total_commande(total_commande);

                return {
                    'total_commande': total_commande,
                    'total_ids_article': total_ids_article,
                    'total_qte_article': total_qte_article,
                    'total_prix_article': total_prix_article,
                    'taux_reduction': taux_reduction,
                    'total_nbr_commande': total_nbr_commande,
                }

            }
            get_global_commande_detail();


            function send_commande() {
                $('#commande_validation').click(function(event) {
                    event.preventDefault();

                    let url = "<?php echo site_url('Sortie_stock/process_commande/') ?>";
                    let url_redirect = "<?php echo site_url('Sortie_stock_list/index') ?>";
                    let form_data = {};
                    let send_commande = true;
                    let id_client = $("#id_client").val();
                    let nom_client = $("#nom_client").val();
                    let date_sortie = $("#date_sortie").val();
                    let id_sortie_stock = $('#id_sortie_stock').val();
                    let commande_object = get_global_commande_detail();



                    if (id_client !== "null" && nom_client.length > 1) {
                        swal("Vous avez choisi un ID client et le nom du client en même temps, veuillez n'en choisir qu'un !");
                        send_commande = false;
                    }

                    if (commande_object.total_nbr_commande == 0) {
                        swal("Il n'y a aucune sortie de stock à envoyer, merci d'en ajouter au moins une !");
                        send_commande = false;
                    }


                    if (send_commande) {

                        form_data['prosess_commande'] = 'prosess_commande';
                        form_data['id_client'] = id_client;
                        form_data['nom_client'] = nom_client;
                        form_data['date_sortie'] = date_sortie;
                        form_data['id_sortie_stock'] = id_sortie_stock;
                        form_data['total_commande'] = commande_object.total_commande;
                        form_data['total_ids_article'] = commande_object.total_ids_article;
                        form_data['total_qte_article'] = commande_object.total_qte_article;
                        form_data['total_prix_article'] = commande_object.total_prix_article;
                        form_data['taux_reduction'] = commande_object.taux_reduction;
                        form_data['total_nbr_commande'] = commande_object.total_nbr_commande;

                        swal("Processing ...");

                        $.ajax({
                            type: "POST",
                            url: url,
                            data: form_data,
                            success: function(response) {
                                console.log(response);
                                if (parseInt(response) > 0) {
                                    window.location = url_redirect + "/";
                                    window.location = url_redirect + "/" + parseInt(response) + "/";
                                }
                            },
                            error: function() {
                                swal("Une erreur s'est produite !, Nous ne pouvons pas accéder au serveur");
                            }
                        });
                    }

                });
            }
            send_commande();

        });
    </script>
</body>

</html>
<?php
