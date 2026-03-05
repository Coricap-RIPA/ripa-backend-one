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
        <?php echo $navbar; ?>

        <div id="row_content" class="row">
            <div class="col s12">
                <fieldset>
                    <legend>
                        CONFIGURATIONS / INDEX DES REFERENCES 
                    </legend>
                </fieldset>
            </div>            

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

</body>

</html>