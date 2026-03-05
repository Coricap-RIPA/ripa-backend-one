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

            <div class="col s12">
                <fieldset>
                    <legend>
                        <h3> FILTRE SUR LES LOGS SYSTEMES </h3>
                    </legend>
                </fieldset>
            </div>
        
            <div class="col s12">
                <form action="<?php echo site_url('Action_utilisateur/index/');?>" method="POST">
                    
                    <div class="col s6">
                        <div class="form-group">
                            <label for="start_date">Date de début</label>
                            <input type="date" class="form-control" name="start_date" id="start_date" value="<?php if( isset($_POST['start_date']) ){ echo $_POST['start_date'] ;} ?>" required>
                        </div> 
                    </div>
        
                    <div class="col s6">
                        <div class="form-group">
                            <label for="end_date">Date de Fin</label>
                            <input type="date" class="form-control" name="end_date" id="end_date" value="<?php if( isset($_POST['end_date']) ){ echo $_POST['end_date'] ;} ?>" required>
                        </div> 
                    </div>
                    
                    <div class="col-md-4" style="display: none;" >
                        <div class="form-group">
                            <label for="id_utilisateur">Utilisateurs</label>
                            <?php  ?>
                            <select type="date" class="form-control input-sm" id="id_utilisateur" name="id_utilisateur" >
                                <option value="null"> Choisissez un utilisateur</option>
                                <?php 
                                    foreach ($liste_utilisateurs as $key => $liste_utilisateur) {
                                        if( isset( $_POST['id_utilisateur'] ) AND ( $liste_utilisateur['id_utilisateur'] ==  $_POST['id_utilisateur'] ) ){
                                            echo '<option selected value="'.$liste_utilisateur['id_utilisateur'].'">'.$liste_utilisateur['nom'].' '.$liste_utilisateur['post_nom'].' '.$liste_utilisateur['prenom'].'</option>';
                                        }else{
                                            echo '<option value="'.$liste_utilisateur['id_utilisateur'].'">'.$liste_utilisateur['nom'].' '.$liste_utilisateur['post_nom'].' '.$liste_utilisateur['prenom'].'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div> 
                    </div>
        
    
                    <div class=" col-md-4">
                        <div class="form-group">
                            <label for="action_table">Action sur les tables</label>
                            <?php  ?>
                            <select type="date" class="form-control input-sm" id="action_table" name="action_table" >
                                <option value="null"> Choisissez une action </option>
                                <?php 
                                    foreach ($liste_action_tables as $key => $liste_action_table) {
                                        if( isset( $_POST['action_table'] ) AND ( $key ==  $_POST['action_table'] ) ){
                                            echo '<option selected value="'.$key.'">'.$liste_action_table.'</option>';
                                        }else{
                                            echo '<option value="'.$key.'">'.$liste_action_table.'</option>';
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
                            <a href="<?php echo site_url('Action_utilisateur/index/'); ?>" class="btn btn-success col s12"> Effacer le filtre </a>
                        </div>
                    </div>
        
                </form>  
            </div>

            <div class="row col s12">
                <fieldset>
                    <legend>
                        CONFIGURATIONS / LISTE DES LOGS SYSTEMES
                    </legend>
                </fieldset>
            </div>

            <div class="col s12">
                <?php echo $output; ?>
            </div>

        </div>

        <div id="row_footer" class="row">
            <div class="footer-wrapper col s12">
                <div class="footer-section f-section-1 col s6" style="padding-left: 2%;">
                    <p class="">Copyright © <?php echo date('Y'); ?> <a target="_blank" href="#"> RIPA </a>, Tout droits reservés. </p>
                </div>
                <div class="footer-section f-section-2 col s6 text-right" style="padding-right: 2%;">
                    <p style="float: right;">
                        Coded with: <a href="mailto:pascalmmp@gmail.com" title="+243971403075"> CORICAP </a>
                    </p>
                </div>
            </div>
        </div>

    </div>

    <script>

    </script>

</body>

</html>
