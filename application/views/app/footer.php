<?php 
    $scroll_top  = (isset( $_SESSION['scrolltop'] )) ? $_SESSION['scrolltop'] : 0;
?>
<script src="<?php echo base_url('assets/app_assets/');?>js/jquery-3.6.0.js"></script>
<script src="<?php echo base_url('assets/app_assets/');?>js/bootstrap.min.js" ></script>
<script src="<?php echo base_url('assets/app_assets/');?>js/multirange.js" defer=""></script>
<script src="<?php echo base_url('assets/app_assets/');?>js/owl.carousel.min.js" ></script>
<script src="<?php echo base_url('assets/app_assets/');?>js/sync_owl_carousel.js" ></script>
<script src="<?php echo base_url('assets/app_assets/');?>js/scripts.js"></script>
<script>









    var scrollAt = "<?php echo $scroll_top; ?>";
    scrollAt     = parseInt(scrollAt) - 100;

    function scrollAtPosition(position) {
    
        setTimeout( function ()  {
            $('html,body').animate({scrollTop: position }, 1000);
            setTimeout( function () {
                "<?php $_SESSION['scrolltop'] = 0; ?>";
            }, 1000);
        }, 2000);

    }scrollAtPosition(scrollAt);

    function sendFormApp() {
        $("form").each(function (index, element) {
            $(element).submit(function (e) { 

                var url_to_send     = "<?php echo site_url('AppIndex/setScrollTop/'); ?>";
                var scrollTop       = $(window).scrollTop();

                $.ajax({
                    type: "POST",
                    url: url_to_send,
                    data:  {'scrolltop': scrollTop},
                    success: function (response) {
                        console.log('session scrolltop good setted '+response);
                    },
                    error: function (response) { 
                        alert('Problème de connexion');
                    }
                });    
            }); 
        });
    }sendFormApp();

    function showSearchBox() {
        $("#searchBoxContainer").css('bottom','0');
        $("#searchBoxHider").css('bottom','0');
    }

    function hideSearchBox() {
        $("#searchBoxHider").click(function (e) { 
            $("#searchBoxContainer").css('bottom','-100%');
            $("#searchBoxHider").css('bottom','-100%');
        });
    }

</script>