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
                        <legend> FILTRE PAR DATE </legend>
                    </fieldset>
                </div>

                <div class="col s12">
                    <form action="<?php echo site_url('Sortie_stock_list/index/'); ?>" method="POST">
                        <div class="col s3">
                            <div class="form-group">
                                <label for="start_date">Date de début </label>
                                <input type="date" class="form-control" name="start_date" id="start_date" value="<?php if (isset($_POST['start_date'])) {
                                                                                                                         echo $_POST['start_date'];
                                                                                                                    } ?>" required>
                            </div>
                        </div>

                        <div class="col s3">
                            <div class="form-group">
                                <label for="end_date">Date de fin </label>
                                <input type="date" class="form-control" name="end_date" id="end_date" value="<?php if (isset($_POST['end_date'])) {
                                                                                                                    echo $_POST['end_date'];
                                                                                                                } ?>" required>
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
                                <a href="<?php echo site_url('Sortie_stock_list/index/'); ?>" class="btn btn-success col s12">Effacer le filtre</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col s12">
                    <fieldset>
                        <legend> LISTE DES SORTIES DE STOCKS </legend>
                    </fieldset>
                </div>

                <div class="col s12">
                    <div class="col s4">
                        <div class="form-group">
                            <a href="<?php echo site_url('Sortie_stock/index/');?>" class="btn btn-primary col s12">RETOUR A LA SORTIE DE STOCK</a>
                        </div>
                    </div>
                </div>



                <div class="col s12" style="margin-top: 30px;">
                    <div class="widget widget-chart-one">
                        <?php echo $output; ?>
                    </div>
                </div>

            </div>
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

    <script>
        $(function() {
            $(".dataTable").find("tbody").find(".actions").find('.edit_button').each(function (ind,ele) { 
                if( ($(ele).attr('href')).length< 3 ){
                    $(ele).hide();
                }
            });
        });
    </script>

</body>

</html>
<?php
