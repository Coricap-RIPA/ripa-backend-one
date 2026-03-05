    <?php 

        $url_to_send    = site_url('Commande/get_status_read_commande/');
        $url_redirect   = site_url('Commande/index/');
        $id_entr_user   = 0;
       //$id_entr_user   = $this->session->userdata['user']['id_foreign_fournisseur'];

        if( $id_entr_user <> 1){
            $url_to_send    = site_url('Commande/get_status_read_commande_fournisseur/');
            $url_redirect   = site_url('Article_commande/index/');
        }

    ?>    
    
    <audio class="audio_notification" id="audio_notification" controls loop  preload="auto" style="display:none;">
        <source src="<?php echo base_url("assets/dore_assets/"); ?>souds_notification/mixkit-bell-notification-933.wav" type="audio/wav">
        Your browser does not support the audio tag.
    </audio>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="<?php echo base_url("assets/dore_assets/"); ?>js/libs/jquery-3.1.1.min.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>bootstrap/js/popper.min.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>js/app.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>js/jquery.number.min.js"></script>

    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="<?php echo base_url('assets/dore_assets/'); ?>js/custom.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="<?php echo base_url('assets/dore_assets/'); ?>plugins/apex/apexcharts.min.js"></script>
    <script src="<?php echo base_url('assets/dore_assets/'); ?>js/dashboard/dash_1.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

    <?php if (isset($js_files)) { ?>
        <?php foreach ($js_files as $file) : ?>
            <script src="<?php echo $file; ?>"></script>
        <?php endforeach; ?>
    <?php } ?>
    <!-- /BEGIN GLOBAL MANDATORY SCRIPTS -->

    <script src="https://kendo.cdn.telerik.com/2017.2.621/js/jszip.min.js"></script>
    <script src="https://kendo.cdn.telerik.com/2017.2.621/js/kendo.all.min.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>plugins/notification/snackbar/snackbar.min.js"></script>
    <script src="<?php echo base_url('assets/materialize/'); ?>js/materialize.js"></script>
    <script src="<?php echo base_url('assets/dore_assets/'); ?>js/sweetalert.js"></script>


    <!-- <script src="<?php echo base_url('assets/datatables/'); ?>dataTables.js"></script>
    <script src="<?php echo base_url('assets/datatables/'); ?>dataTables.buttons.js"></script>
    <script src="<?php echo base_url('assets/datatables/'); ?>buttons.dataTables.js"></script>
    <script src="<?php echo base_url('assets/datatables/'); ?>jszip.min.js"></script>
    <script src="<?php echo base_url('assets/datatables/'); ?>pdfmake.min.js"></script>
    <script src="<?php echo base_url('assets/datatables/'); ?>vfs_fonts.js"></script>
    <script src="<?php echo base_url('assets/datatables/'); ?>buttons.html5.min.js"></script>
    <script src="<?php echo base_url('assets/datatables/'); ?>buttons.print.min.js"></script> -->

    <script>
        $(function() {

            var my_interval             = null;
            var my_interval_reminder    =  null;

            $('.dropdown-trigger').dropdown();
            $(".form-control").attr("style", "border: 1px solid #ccc;border-radius: 4px;padding: 12px 20px;box-sizing: border-box; height:35px;");
            $(".input-sm").attr("style", "border: 1px solid #ccc;padding: 1px 1px;box-sizing: border-box; height:35px; border-radius: 4px;");
            $(".chosen-container").attr("style", "width: 500px;");
            $(".btn-default").attr("style", "background:#fafafa; color: black;");

            $(".btn-primary").hover(function() {
                $(this).attr("style", "background: #337ab7; background-image: linear-gradient(to bottom,#337ab7 0,#265a88 100%); background-position: none;");
            });

            $(".btn-info").hover(function() {
                $(this).attr("style", "background: #28a4c9; background-image: background-image: linear-gradient(to bottom,#5bc0de 0,#2aabd2 100%) ; background-position: none;");
            });

            $(".btn-warning").hover(function() {
                $(this).attr("style", "background: #e38d13; background-image: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); background-position: none;");
            });

            $(".btn-success").hover(function() {
                $(this).attr("style", "background: #3e8f3e; background-image: linear-gradient(to bottom,#5cb85c 0,#419641 100%); background-position: none;");
            });

            $(".edit_button").hover(function() {
                $(this).attr("style", "background: #28a4c9; background-image: linear-gradient(to bottom,#5bc0de 0,#2aabd2 100%); background-position: none;");
            });

            $(".delete_button").hover(function() {
                $(this).attr("style", "background: #b92c28; background-image: linear-gradient(to bottom,#d9534f 0,#c12e2a 100%); background-position: none;");
            });

            $("#cancel-button").hover(function() {
                $(this).attr("style", "background: #e38d13; background-image: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); background-position: none;");
            });

            $("#dropdown1").find("a").each(function(index, el) {
                if ($(el).attr('href')) {
                    if (($(el).attr('href')).length < 2) {
                        $(el).remove();
                    }
                } else {
                    $(el).remove();
                }
            });

            $("#dropdown1").find("li").each(function(index, ele) {
                $(ele).attr('style', 'height: 50px');
            });

            (function initMaterializeView() {

                $('.collapsible').collapsible();

                $('.carousel').carousel({
                    'duration': 200,
                    'indicators': true,
                    'fullWidth': true,
                    'noWrap': true
                });


                $('.datepicker-input').datepicker({
                    'format': 'dd/mm/yyyy',
                    'i18n': {
                        cancel: 'Annuler',
                        clear: 'Effacer',
                        months: [
                            'Janvier',
                            'Février',
                            'Mars',
                            'Avril',
                            'Mai',
                            'Juin',
                            'Juillet',
                            'Aout',
                            'Septembre',
                            'Octobre',
                            'Novembre',
                            'Decembre'
                        ],
                        monthsShort: [
                            'Jan',
                            'Fev',
                            'Mar',
                            'Avr',
                            'Mai',
                            'Juin',
                            'Juil',
                            'Aout',
                            'Sep',
                            'Oct',
                            'Nov',
                            'Dec'
                        ],
                        weekdays: [
                            'Dimanche',
                            'Lundi',
                            'Mardi',
                            'Mercredi',
                            'Jeudi',
                            'Vendredi',
                            'Samedi'
                        ],
                        weekdaysShort: [
                            'Dim',
                            'Lun',
                            'Mar',
                            'Mer',
                            'Jeu',
                            'Ven',
                            'Sam'
                        ],
                        weekdaysAbbrev: ['D', 'L', 'M', 'M', 'J', 'V', 'S']

                    }
                });

            })();

            function notififyNewCommande() {
                var url_to_send  = "<?php echo $url_to_send; ?>";
                var url_redirect = "<?php echo $url_redirect; ?>";
                var id_entr_user = "<?php echo $id_entr_user; ?>";
                my_interval = setInterval(function() {
                    $.ajax({
                        type: "POST",
                        url: url_to_send,
                        data: {
                            'status': 'get_status',
                            'id_entr_user': id_entr_user
                        },
                        success: function(response) {

                            if (response === 'alert') {

                                $("body").trigger('click');
                                $(".audio_notification").trigger('play');

                                swal({
                                        title: "Nouvelle commande !",
                                        text: " Vous avez une ou plusieurs nouvelles commandes",
                                        icon: "warning",
                                        buttons: ["Annuler", "Voir"],
                                        dangerMode: true,
                                    })
                                    .then((willDelete) => {
                                        if (willDelete) {
                                            window.location.href = url_redirect;
                                        } else {
                                            clearInterval(my_interval);
                                            swal("N'oubliez pas d'aller voir les nouvelles commandes !");
                                            $(".audio_notification").trigger('pause');
                                            setTimeout(function() {
                                                setInterval(my_interval, 2000);
                                            }, 10000);
                                        }
                                    });

                                
                            }
                        },
                        error: function() {
                            console.log('error ');
                        }
                    });
                }, 2000);

            }

        });
    </script>