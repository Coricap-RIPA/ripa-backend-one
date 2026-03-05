<!-- BEGIN GLOBAL MANDATORY SCRIPTS --> 
<script src="<?php echo base_url("assets/dore_assets/"); ?>js/libs/jquery-3.1.1.min.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>bootstrap/js/popper.min.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>js/app.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>js/jquery.number.min.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>js/jquery-qrcode.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>js/qrcode.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>js/sweetalert.js"></script> 
    <script src="<?php echo base_url("assets/dore_assets/"); ?>js/printThis.js"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>

    <script src="<?php echo base_url('assets/dore_assets/');?>js/custom.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="<?php echo base_url('assets/dore_assets/');?>plugins/apex/apexcharts.min.js"></script>
    <script src="<?php echo base_url('assets/dore_assets/');?>js/dashboard/dash_1.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

    <?php if( isset($js_files) ) { ?>    
        <?php foreach($js_files as $file): ?>
            <script src="<?php echo $file; ?>"></script>
        <?php endforeach; ?>
    <?php } ?>

    
    <script>
        $(function(){

            $('.dropdown-trigger').dropdown();
            $(".form-control").attr("style","border: 1px solid #ccc;border-radius: 4px;padding: 12px 20px;box-sizing: border-box; height:35px;");
            $(".input-sm").attr("style","border: 1px solid #ccc;padding: 1px 1px;box-sizing: border-box; height:35px; border-radius: 4px;");
            $(".chosen-container").attr("style","width: 500px;");
            $(".btn-default").attr("style","background:#fafafa; color: black;"); 

            $(".btn-primary").hover(function () {
                $(this).attr("style","background: #337ab7; background-image: linear-gradient(to bottom,#337ab7 0,#265a88 100%); background-position: none;");
            });

            $(".btn-success").hover(function () {
                $(this).attr("style","background: #3e8f3e; background-image: linear-gradient(to bottom,#5cb85c 0,#419641 100%); background-position: none;");
            });

            $(".edit_button").hover(function () {
                $(this).attr("style","background: #28a4c9; background-image: linear-gradient(to bottom,#5bc0de 0,#2aabd2 100%); background-position: none;");
            });

            $(".delete_button").hover(function () {
                $(this).attr("style","background: #b92c28; background-image: linear-gradient(to bottom,#d9534f 0,#c12e2a 100%); background-position: none;");
            });

            $("#cancel-button").hover(function () {
                $(this).attr("style","background: #e38d13; background-image: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); background-position: none;");
            });

            $("#dropdown1").find("a").each(function (index,el) {  
                    if($(el).attr('href')){
                        if(($(el).attr('href')).length<2){
                            $(el).remove();
                        }
                    }else{
                        $(el).remove();
                    }
            });

            $("#dropdown1").find("li").each(function (index,ele) {
                    $(ele).attr('style','height: 50px');
            });

            $(".image-thumbnail").find('img').attr("style","height:100px; width:130px;border-radius:10px;object-fit:cover;");
            
            $('body').click(function (event) {
                
                if( $(event.target).is('a') ){

                    if(  $(event.target).attr('aria-controls') ){

                        $("table").find(".image-thumbnail").each(function(ind,elem){
                            $(elem).find('img').attr("style","height:100px; width:130px;border-radius:10px;object-fit:cover;");
                        });
    
                        $(".edit_button").hover(function () {
                            $(this).attr("style","background: #28a4c9; background-image: linear-gradient(to bottom,#5bc0de 0,#2aabd2 100%); background-position: none;");
                        });
                    }
                }
            });

            

            $('input[type="search"]').keypress(function (e) { 

                console.log('in ....');

                $("table").find(".image-thumbnail").each(function(ind,elem){
                    $(elem).find('img').attr("style","height:100px; width:130px;border-radius:10px;object-fit:cover;");
                });

                $(".edit_button").hover(function () {
                    $(this).attr("style","background: #28a4c9; background-image: linear-gradient(to bottom,#5bc0de 0,#2aabd2 100%); background-position: none;");
                });
            });


        });
    </script>
<!-- /BEGIN GLOBAL MANDATORY SCRIPTS -->
