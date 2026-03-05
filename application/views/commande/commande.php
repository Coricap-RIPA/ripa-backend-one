<?php
    echo  $header;
    echo  $footer;
?>

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

            <div class="row col s12">
                <fieldset>
                    <legend>
                        LISTE DES COMMANDES
                    </legend>
                </fieldset>
            </div>
            
            <div class="col s12">

                <form action="<?php echo site_url('Commande/index/');?>" method="POST">
                
                    <div class="row">

                        <div class="col s6">
                            <div class="form-group">
                                <label for="start_date">Date de début</label>
                                <input type="date" class="form-control" name="start_date" id="start_date" value="<?php if (isset($_POST['start_date'])) {
                                                                                                                        echo $_POST['start_date'];
                                                                                                                    } ?>" >
                            </div>
                        </div>

                        <div class="col s6">
                            <div class="form-group">
                                <label for="end_date">Date de Fin</label>
                                <input type="date" class="form-control" name="end_date" id="end_date" value="<?php if (isset($_POST['end_date'])) {
                                                                                                                    echo $_POST['end_date'];
                                                                                                                } ?>" >
                            </div>
                        </div>

                        <div class="col s12">
                            <div class="form-group">
                                <label for="id_foreign_client">Selectionnez un client </label>
                                <select name="id_foreign_client" id="id_foreign_client" class="form-control browser-default input-sm chosen-select" style="display: none;">
                                    <option value="null"> Selectionnez un client </option>
                                    <?php
                                    if (!empty($clients)) {
                                        foreach ($clients as $key => $client) {
                                            if (isset($_POST['id_foreign_client']) and $_POST['id_foreign_client'] == $client['id_client']) {
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
                                <a href="<?php echo site_url('Commande/index/'); ?>" class="btn btn-success col s12">Effacer le filtre</a>
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
                        Coded with: <a href="mailto:pascalmmp@gmail.com" title="+243971403075"> CORICAP </a>
                    </p>
                </div>
            </div>
        </div>

    </div>

    <script src="<?php echo base_url('assets/');?>grocery_crud/js/jquery_plugins/jquery.chosen.min.js"></script>
    <script src="<?php echo base_url('assets/');?>grocery_crud/js/jquery_plugins/config/jquery.chosen.config.js"></script>

    <script>
        
        var transfert_message = "<?php if(isset($_SESSION['transfert_message'])) echo $_SESSION['transfert_message'] ;?>";

        if(transfert_message.length > 3 ){
            swal(transfert_message);
            "<?php $_SESSION['transfert_message'] =  ''; ?>"
        }

        setTimeout(() => {
            $("#id_foreign_client").attr('style','display: none !important;');
            //$(".chosen-container,.chosen-container-single").attr('style','width:400px !important; height: 40px !important;');
        }, 1000);

    </script>

</body>

</html>



