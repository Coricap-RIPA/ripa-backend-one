    <!-- Hider Search Modal Content -->
    <div id="searchBoxHider" class="container" style="background-color: #000 !important; opacity: 0.3; position: fixed; z-index: 9; width: 100%; height: 100% !important; bottom: -100%; left:0; transition: all 0.5s linear;" onclick="hideSearchBox()">

    </div>
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