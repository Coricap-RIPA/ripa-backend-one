<?php 
    echo  $header;
    echo  $navbar;
    echo  $sidebar;
    echo  $footer;
?>

<body class="sidebar-noneoverflow">
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  --><!--  /BEGIN NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  --><!--  END SIDEBAR  -->
        
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">

                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h3>Filtrer En fonction de la date </h3>
                    </div>

                    <form action="<?php echo site_url('Depense/index/'.$nom_table.'/'.$id_entree.'/'.$champ.'/'.$id_pour_entreprise);?>" method="POST">
                        <div class=" col-md-4">
                            <div class="form-group">
                                <label for="start_date">Date de début</label>
                                <input type="date" class="form-control" name="start_date" id="start_date" value="<?php if( isset($_POST['start_date']) ){ echo $_POST['start_date'] ;} ?>" required>
                            </div> 
                        </div>

                        <div class=" col-md-4">
                            <div class="form-group">
                                <label for="end_date">Date de Fin</label>
                                <input type="date" class="form-control" name="end_date" id="end_date" value="<?php if( isset($_POST['end_date']) ){ echo $_POST['end_date'] ;} ?>" required>
                            </div> 
                        </div>

                        <div class=" col-md-3" style="display: none;">
                            <div class="form-group">
                                <label for="end_date">Nom de la table</label>
                                <input type="text" class="form-control" name="nom_table" id="nom_table" value="<?php echo $nom_table?>" >
                            </div> 
                        </div>

                        <div class=" col-md-3">
                            <div class="form-group">
                                <label for="">Rechercher</label>
                                <button class="btn btn-primary mb-2 form-control">Rechercher</button>
                            </div> 
                        </div>
                    </form> 

                    <div class=" col-md-3">
                        <div class="form-group">
                            <label for="">Effacer le filtre</label>
                            <a href="<?php echo site_url('Depense/index/'.$nom_table.'/'.$id_entree.'/'.$champ);?>">
                                <button class="btn btn-success mb-2 form-control">Effacer le filtre</button>
                            </a>
                        </div> 
                    </div>

                    <div class=" col-md-3">
                        <div class="form-group">
                            <a href="<?php echo site_url('Commande/index/'.$id_pour_entreprise);?>">
                                <button class="btn btn-warning mb-2 form-control"> <- Retour au commandes</button>
                            </a>
                        </div> 
                    </div>

                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h5>Dépense pour l'entreprise :<b> <?php echo strtoupper($nom_entreprise) ?> </b> le client <b> <?php  echo strtoupper($client_entreprise)?></b> sur la commande de :<b> <?php echo $marchandise; ?> </b> </h5>
                    </div>
                     
                    <?php if(  (check_privilege('finance', $this->session->user['id_role'], 'voir')) ) {?>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                            <div class=" col-md-3"> 
                                <h5> Prix de vente <?php echo $devise;?> : <b  id="total_caisse"> <?php echo $montant_entre; ?></b> </h5>
                            </div>
                            <div class=" col-md-3"> 
                                <h5> Prix d' achat <?php echo $devise;?> : <b  id="total_achat"> <?php echo $montant_achat; ?></b> </h5>
                            </div>

                            <div class=" col-md-3"> 
                                <h5> Solde caisse <?php echo $devise;?> : <b  id="solde_caisse"> <?php echo ($montant_entre - $montant_achat); ?></b> </h5>
                            </div>

                            <div class=" col-md-3"> 
                                <h5> Total dépenses <?php echo $devise;?> : <b  id="total_depense"> 00.00 </b> </h5>
                            </div>
                            
                            <div class=" col-md-3"> 
                                <h5 id="solde_restant_parent"> Solde Restant <?php echo $devise;?> : <b  id="solde_restant">  </b> </h5>
                            </div>
                        </div>
                    <?php }?>  

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-chart-one">
                            <?php echo $output; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © <?php echo date('Y'); ?> <a target="_blank" href="#"> EP_MAN </a>, Tout droits reservés. </p>
                </div>
                <div class="footer-section f-section-2">
                    <p class="">Coded with <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></p>
                </div>
            </div>
        </div>
        <!--  END CONTENT AREA  -->
        
    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS --> <!-- /BEGIN GLOBAL MANDATORY SCRIPTS -->

    <script>
        $(function(){

            var montant_total       = parseInt( $("#total_caisse").text() ) - parseInt(  $("#total_achat").text() );
            var montant_total_depense = 0;
            var solde_restant       = 0;
            var devise = '<?php echo $devise;?>';
            var is_mobile               = parseInt( '<?php echo isMobile();?>' );

            $('table').find('tr').each(function(index, el) {
                if( index >0){
                    $(el).find('td').each(function(range, element){
                        if( range == 3 ){
                            montant_total_depense = montant_total_depense + parseInt ( $(element).text() );
                        }
                    });
                }
            });

            var solde_caisse        = parseInt( $("#solde_caisse").text() );
            var solde_restant       = parseInt( montant_total - montant_total_depense );
            var marge_60_percent    = (solde_caisse * 60) /100 ;

            $("#total_depense").text( montant_total_depense.toLocaleString());
            $("#total_caisse").text( parseInt( $("#total_caisse").text() ).toLocaleString());
            $("#total_achat").text( parseInt( $("#total_achat").text() ).toLocaleString());
            $("#solde_caisse").text( parseInt( $("#solde_caisse").text() ).toLocaleString());
            $("#solde_restant").text( (montant_total - montant_total_depense).toLocaleString());

            if(solde_restant < marge_60_percent ){
                $("#solde_restant_parent").css("color","red");
            }

            if(is_mobile == 1){
                $('body').click(function(event){

                    if($(event.target).is('svg')){
                        if( $(event.target).attr('class')=='feather feather-list'){
                            console.log('nice');
                            $("#nav_sidebar").show();
                            $('#close_menu').show();
                        }
                    }else if( $(event.target).is('line') ){
                        $("#nav_sidebar").show();
                        $('#close_menu').show();
                    }
                });

                $('#close_menu').click(function(event){
                    $("#nav_sidebar").hide();
                    $('#close_menu').hide();
                });
            }

        });
    </script>

</body>
</html>
<?php 
