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
                        LISTE DES PUBLICITES
                    </legend>
                </fieldset>
            </div>
            
            <div class="col s12">

                <form action="<?php echo site_url('Publicite/index/');?>" method="POST">
                
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
                                <label for="">Rechercher</label>
                                <button class="btn btn-primary col s12">Rechercher</button>
                            </div>
                        </div>
    
                        <div class="col s3">
                            <div class="form-group">
                                <label for="">Effacer le filtre</label>
                                <a href="<?php echo site_url('Publicite/index/'); ?>" class="btn btn-success col s12">Effacer le filtre</a>
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




