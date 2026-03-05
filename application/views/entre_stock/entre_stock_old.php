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
                            <h3>ENTREE STOCK </h3>
                     </div>

                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h3>Filtre En fonction de la date </h3>
                     </div>

                    <form action="<?php echo site_url('Entree_stock/index/');?>" method="POST">
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

                        <div class=" col-md-4">
                            <div class="form-group">
                                <label for="">Rechercher</label>
                                <button class="btn btn-primary mb-2 form-control">Rechercher</button>
                            </div> 
                        </div>
                    </form> 

                    <div class=" col-md-3">
                        <div class="form-group">
                            <label for="">Effacer le filtre</label>
                            <a href="<?php echo site_url('Entree_stock/index/');?>">
                                <button class="btn btn-success mb-2 form-control">Effacer le filtre</button>
                            </a>
                        </div> 
                    </div>

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class=" col-md-4"> 
                            <h5> Montant Tolal Achat : <b  id="total_achat"> <?php echo number_format($total_achat,0,'.',' ') ?> CDF</b> </h5>
                        </div>
                        <div class=" col-md-4"> 
                            <h5> Montant Total Vente  : <b id="total_vente">  <?php echo number_format($total_vente,0,'.',' ') ?>  CDF</b> </h5>
                        </div>
                        <div class=" col-md-4"> 
                            <h5> Montant Total Recette  : <b id="total_recette">  <?php echo number_format($recette,0,'.',' ') ?> CDF</b> </h5>
                        </div>
                    </div> 

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-chart-one">
                            <?php echo $output; ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © <?php echo date('Y'); ?> <a target="_blank" href="#"> CADEAUMART </a>, Tout droits reservés. </p>
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

</body>
</html>
<?php 
