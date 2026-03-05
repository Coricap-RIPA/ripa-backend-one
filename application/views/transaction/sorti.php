<?php
echo  $header;
echo  $footer;
?>


<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/');?>grocery_crud/css/jquery_plugins/chosen/chosen.css" />
<script src="<?php echo base_url('assets/');?>grocery_crud/js/jquery_plugins/jquery.chosen.min.js"></script>
<script src="<?php echo base_url('assets/');?>grocery_crud/js/jquery_plugins/config/jquery.chosen.config.js"></script>
    

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
                <fieldset>
                    <legend>
                       LISTE DES TRANSACTIONS SORTIES
                    </legend>
                </fieldset>
            </div>
            
            
            <div class="col  s12">
                <div class="col s12">
                    <div class="col s5"><h5>TOTAL CDF : <?php echo number_format($total_transcation_cdf,2,'.',' '); ?></h5></div>
                    <div class="col s5"><h5>TOTAL USD : <?php echo number_format($total_transcation_usd,2,'.',' '); ?></h5></div>
                </div>
            </div>

            <div class="col  s12" style="display: <?php if ( (int) $_SESSION['role']['id_role']<>1 ) echo 'none'; else echo 'block'; ?>">
                <div class="col s12">
                    <div class="col s5"><h5>TOTAL COMMISSION RIPA CDF : <?php echo number_format($total_commission_ripa_cdf,2,'.',' '); ?></h5></div>
                    <div class="col s5"><h5>TOTAL COMMISSION RIPA USD : <?php echo number_format($total_commission_ripa_usd,2,'.',' '); ?></h5></div>
                </div>
            </div>


            <div class="col  s12" style="display: <?php if ( (int) $_SESSION['role']['id_role']<>1 ) echo 'none'; else echo 'block'; ?>">
                <div class="col s12">
                    <div class="col s5"><h5>TOTAL COMMISSION PARTENAIRE CDF : <?php echo number_format($total_commission_network_cdf,2,'.',' '); ?></h5></div>
                    <div class="col s5"><h5>TOTAL COMMISSION PARTENAIRE USD : <?php echo number_format($total_commission_network_usd,2,'.',' '); ?></h5></div>
                </div>
            </div>


            <form action="<?php echo site_url('Transaction/sorti/');?>" method="POST">
                
                <div class="row">

                    <div class="col s12">
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
                                <label for="id_foreign_statut_execution">Status d'éxécution </label>
                                <select name="id_foreign_statut_execution" id="id_foreign_statut_execution" class="form-control browser-default input-sm chosen-select" style="display: none;">
                                    <option value="null"> Status d'éxécution </option>
                                    <?php
                                    if (!empty($arr_status_executions)) {
                                        foreach ($arr_status_executions as $key => $status_execution) {
                                            if (isset($_POST['id_foreign_statut_execution']) and $_POST['id_foreign_statut_execution'] == $status_execution['id_foreign_statut_execution']) {
                                                echo '<option selected value="' . $status_execution['id_foreign_statut_execution'] . '">' .  $status_execution['designation'] . '</option>';
                                            } else {
                                                echo '<option value="' . $status_execution['id_foreign_statut_execution'] . '">' . $status_execution['designation'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
    
                        <div class="col s3">
                            <div class="form-group">
                                <label for="id_foreign_statut_transaction">Status de la transaction </label>
                                <select name="id_foreign_statut_transaction" id="id_foreign_statut_transaction" class="form-control browser-default input-sm chosen-select" style="display: none;">
                                    <option value="null"> Status de la transaction </option>
                                    <?php
                                    if (!empty($arr_status_transactions)) {
                                        foreach ($arr_status_transactions as $key => $status_transaction) {
                                            if (isset($_POST['id_foreign_statut_transaction']) and $_POST['id_foreign_statut_transaction'] == $status_transaction['id_foreign_statut_transaction']) {
                                                echo '<option selected value="' . $status_transaction['id_foreign_statut_transaction'] . '">' .  $status_transaction['designation'] . '</option>';
                                            } else {
                                                echo '<option value="' . $status_transaction['id_foreign_statut_transaction'] . '">' . $status_transaction['designation'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col s12" style="display: <?php if ( (int) $_SESSION['role']['id_role']<>1 ) echo 'none'; else echo 'block'; ?>">
                        <div class="form-group">
                            <label for="id_foreign_entreprise">Selectionnez un marchand </label>
                            <select name="id_foreign_entreprise" id="id_foreign_entreprise" class="form-control browser-default input-sm chosen-select" style="display: none;">
                                <option value="null"> Selectionnez un marchand </option>
                                <?php
                                if (!empty($entreprises)) {
                                    foreach ($entreprises as $key => $entreprise) {
                                        if (isset($_POST['id_foreign_entreprise']) and $_POST['id_foreign_entreprise'] == $entreprise['id_entreprise']) {
                                            echo '<option selected value="' .  $entreprise['id_entreprise'] . '">' .  $entreprise['nom'] . '</option>';
                                        } else {
                                            echo '<option value="' .  $entreprise['id_entreprise'] . '">' . $entreprise['nom'] . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col s12">
                        <div class="col s4">
                            <div class="form-group">
                                <label for="">Rechercher</label>
                                <button class="btn btn-primary col s12">Rechercher</button>
                            </div>
                        </div>
    
                        <div class="col s4">
                            <div class="form-group">
                                <label for="">Effacer le filtre</label>
                                <a href="<?php echo site_url('Transaction/sorti/'); ?>" class="btn btn-success col s12">Effacer le filtre</a>
                            </div>
                        </div>

                        <div class="col s4" style="display: <?php if ( (int) $_SESSION['role']['id_role']<>1 ) echo 'none'; else echo 'block'; ?>">
                            <div class="form-group">
                                <label for="">Téléchager l'Excel</label>
                                <a href="<?php echo base_url('assets/uploads/files/data_excel_output_dee_pay_export_'.date('d-m-Y').'.xlsx'); ?>" class="btn orange col s12">Téléchager l'Excel</a>
                            </div>
                        </div>
                    </div>


                </div>
                
            </form> 

            <div class="col s12">
                <?php echo $output; ?>
            </div>

        </div>

        <div id="row_footer" class="row">
            <div class="footer-wrappervcol s12">
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
        $(function(){
            var final_message = "<?php echo $final_message; ?>";
            if (final_message.length>5) {
                swal(final_message);
            }
        });
    </script>

</body>

</html>