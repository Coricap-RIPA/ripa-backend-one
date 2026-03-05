
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

        
            
            <div class="col s12" style="padding: 2%;">
                <div class="row">
                    <div class="col s12" style="border-radius: 10px; height: 100px; float: left; background-color: #0f4cf2; text-align: center;">
                        <span style="color: white; margin-top: 30px; width: 20px; height: 5px; box-shadow: 1px 1px 1px 1px #fff;"> ENTRER </span>
                    </div>
                    <div class="col s12 amber"  style="border-radius: 10px; height: 100px; float: right; margin-top: 30px;"></div>
                </div>
            </div>

            <div class="col s12" style=" display: none;">
                <?php echo $output; ?>
            </div>

        </div>

        <div id="row_footer" class="row">
            <div class="footer-wrapper col s12">
                <div class="footer-section f-section-1 col s6" style="padding-left: 2%;">
                    <p class="">Copyright © <?php echo date('Y'); ?> <a target="_blank" href="#"> FROMATTER </a>, Tout droits reservés. </p>
                </div>
                <div class="footer-section f-section-2 col s6 text-right" style="padding-right: 2%;">
                    <p style="float: right;">
                        Coded with: <a href="mailto:pascalmmp@gmail.com" title="+243971403075"> Dee Services Engineering </a>
                    </p>
                </div>
            </div>
        </div>

    </div>
    
</body>

</html>



