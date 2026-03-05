<?php
    echo  $header;
    echo  $footer;
?>

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

            <div class="row col s12">
                <fieldset>
                    <legend>
                        LISTE D'ARTICLES
                    </legend>
                </fieldset>
            </div>
            
            <div class="col s12">

                <form action="<?php echo site_url('Article/index/');?>" method="POST">
                
                    <div class="row">
                        <div class="col s3">
                            <div class="form-group">
                                <label for="start_date">Date de début</label>
                                <input type="date" class="form-control" name="start_date" id="start_date" value="<?php if (isset($_POST['start_date'])) {
                                                                                                                        echo $_POST['start_date'];
                                                                                                                    } ?>" >
                            </div>
                        </div>

                        <div class="col s3">
                            <div class="form-group">
                                <label for="end_date">Date de Fin</label>
                                <input type="date" class="form-control" name="end_date" id="end_date" value="<?php if (isset($_POST['end_date'])) {
                                                                                                                    echo $_POST['end_date'];
                                                                                                                } ?>" >
                            </div>
                        </div>

                        <div class="col s3">
                            <div class="form-group">
                                <label for="id_foreign_fournisseur">Selectionnez un fournisseur </label>
                                <select name="id_foreign_fournisseur" id="id_foreign_fournisseur" class="form-control browser-default input-sm" required>
                                    <option value="null"> Selectionnez un fournisseur </option>
                                    <?php
                                    if (!empty($fournisseurs_articles)) {
                                        foreach ($fournisseurs_articles as $key => $fournisseur_article) {
                                            if (isset($_POST['id_foreign_fournisseur']) and $_POST['id_foreign_fournisseur'] == $fournisseur_article['id_fournisseur']) {
                                                echo '<option selected value="' . $fournisseur_article['id_fournisseur'] . '">' . $fournisseur_article['nom_fournisseur'] . '</option>';
                                            } else {
                                                echo '<option value="' . $fournisseur_article['id_fournisseur'] . '">' . $fournisseur_article['nom_fournisseur'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col s3">
                            <div class="form-group">
                                <label for="id_foreign_categorie">Selectionnez une catégorie </label>
                                <select name="id_foreign_categorie" id="id_foreign_categorie" class="form-control browser-default input-sm" required>
                                    <option value="null"> Selectionnez une catégorie </option>
                                    <?php
                                    if (!empty($categories_articles)) {
                                        foreach ($categories_articles as $key => $categorie_article) {
                                            if (isset($_POST['id_foreign_categorie']) and $_POST['id_foreign_categorie'] == $categorie_article['id_categorie']) {
                                                echo '<option selected value="' . $categorie_article['id_categorie'] . '">' . $categorie_article['nom_categorie'] . '</option>';
                                            } else {
                                                echo '<option value="' . $categorie_article['id_categorie'] . '">' . $categorie_article['nom_categorie'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col s6">
                            <div class="form-group">
                                <label for="">Rechercher</label>
                                <button class="btn btn-primary col s12">Rechercher</button>
                            </div>
                        </div>

                        <div class="col s6">
                            <div class="form-group">
                                <label for="">Effacer le filtre</label>
                                <a href="<?php echo site_url('Article/index/'); ?>" class="btn btn-success col s12">Effacer le filtre</a>
                            </div>
                        </div>
                    </div>

                </form> 

            </div>

            <div class="col s12">
                <?php echo $output; ?>
            </div>

        </div>

        <div id="row_footer" class="row">
            <div class="footer-wrapper col s12">
                <div class="footer-section f-section-1 col s6" style="padding-left: 2%;">
                    <p class="">Copyright © <?php echo date('Y'); ?> <a target="_blank" href="#"> CADEAUMART </a>, Tout droits reservés. </p>
                </div>
                <div class="footer-section f-section-2 col s6 text-right" style="padding-right: 2%;">
                    <p style="float: right;">
                        Coded with: <a href="mailto:pascalmmp@gmail.com" title="+243971403075"> Dee Services Engineering </a>
                    </p>
                </div>
            </div>
        </div>

    </div>

    <script>

    </script>

</body>

</html>

