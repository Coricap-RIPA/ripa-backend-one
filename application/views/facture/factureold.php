<?php 
    echo  $header;
    echo  $footer;
?>
<link href="<?php echo base_url('assets/dore_assets/');?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<style>
    #print:hover{
        cursor: pointer;   
    }
    .invoive_font_size{
        font-size: 12px !important;
        font-family: 'Quicksand';
    }

    .dark_border {
        border: 1px solid black !important;
        color: black !important;
    }

    #invoice {
        font-family: "DejaVu Sans", "Arial", sans-serif;
        font-size: 12px;
    }

    @font-face {
        font-family: "DejaVu Sans";
        src: url("<?php echo base_url('assets/dore_assets/font/DejaVuSans.ttf') ?>") format("truetype");
    }

    @font-face {
        font-family: "DejaVu Sans";
        font-weight: bold;
        src: url("<?php echo base_url('assets/dore_assets/font/DejaVuSans-Bold.ttf')?>") format("truetype");
    }

    @font-face {
        font-family: "DejaVu Sans";
        font-style: italic;
        src: url("<?php echo base_url('assets/dore_assets/font/DejaVuSans-Oblique.ttf')?>") format("truetype");
    }

    @font-face {
        font-family: "DejaVu Sans";
        font-weight: bold;
        font-style: italic;
        src: url("<?php echo base_url('assets/dore_assets/font/DejaVuSans-Oblique.ttf')?>") format("truetype");
    }
</style>

<body class="sidebar-noneoverflow" style="background-color: transparent;">
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <div id="retour" class="col s12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" >
        <br><br>
        <div class="col s5  col-xl-5 col-lg-5 col-md-5 col-sm-1 col-5" style="display: inline-block; vertical-align: top; margin-left: 9%;">
            <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn black white-text" style="width:100%;"> BACK </a>
        </div>
        <div class="col s5 col-xl-5 col-lg-5 col-md-5 col-sm-5 col-5" style="display: inline-block; vertical-align: top; margin-left: 0%;">
            <a class="btn enregistrer_facture" id="print_thiss" style="background-color: #cddc39; color: #fff;width:100%;"> 
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer" data-toggle="tooltip" data-placement="top" data-original-title="Imprimer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                IMPRIMER 
            </a>
        </div>
    </div>   
    <!--  /BEGIN NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container" style="margin-top: -50px;">

        <div class="overlay"></div>
        <div class="search-overlay"></div>
        
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content" style="margin-left: 0;">
            <div class="layout-px-spacing">

                <div class="row layout-top-spacing">
                    <div id="invoice" class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-9" style="border: 1px solid silver; border-radius: 5px; margin-left: 13%; padding: 2% 2% 30% 2%; margin-top: -30px;">
                        <form method="POST" action="<?php echo site_url('Facture/process_add/'); ?>" id="form_facture">
                            <div  class="doc-container">
                                <div class="invoice-container">
                                    <div class="invoice-inbox ps ps--active-y" style="height: calc(100vh - 197px);">
                                            <div id="ct" class="" style="display: block;">
                                                <div class="invoice-header-section" style="display: flex;">
                                                    <h5 class="inv-number"># <?php echo $num_facture;  ?></h5>
                                                    <div id="print" class="invoice-action"  style="margin-left: 30px;">
                                                    </div>
                                                </div>

                                                <div id="invoice1" class="invoice-00002">
                                                    <div class="content-section  animated animatedFadeInUp fadeInUp">

                                                        <div class="row inv--head-section">

                                                            <div class="col-sm-6 col-12">
                                                                <h5 class="in-heading" >Facture</h5>
                                                            </div>
                                                            <div class="col-sm-6 col-12 align-self-center text-sm-right">
                                                                <div class="company-info">
                                                                    <img style="width: 80px;" src="<?php echo base_url("assets/dore_assets/"); ?>img/logo.jpeg" />
                                                                    <!-- <h5 class="inv-brand-name">Manolii</h5> -->
                                                                </div>
                                                            </div>
                                                            
                                                        </div>

                                                        <div class="row inv--detail-section">

                                                            <div class="col-sm-7 align-self-center">
                                                                <p class="inv-to invoive_font_size" >Facturé à : <?php echo $nom_client ;?> </p>
                                                                <p class="inv-to invoive_font_size" >Facture N<sup>o</sup> : </span> <span class="inv-number invoive_font_size"># <?php  echo $num_facture; ?></span> </p>
                                                            </div>
                                                            <div class="col-sm-5 align-self-center  text-sm-right order-sm-0 order-1">
                                                                <p class="inv-detail-title invoive_font_size">De : CADEAUMART</p>
                                                            </div>
                                                            
                                                            <div  id="company_infos" class="col-sm-7 align-self-center" style="margin-top: -30px;">
                                                                <p class="inv-street-addr invoive_font_size">04, Av.Des musées C/ Lubumbashi</p>
                                                                <p class="inv-email-address invoive_font_size">admin@cadeaumart.com</p>
                                                            </div>
                                                            <div class="col-sm-5 align-self-center  text-sm-right order-2">
                                                                <p class="inv-created-date"><span class="inv-title invoive_font_size">Date : </span> <span class="inv-date invoive_font_size"><?php  echo $sortie_stock['date_enregistrement_sortie_stock']?></span></p>
                                                                <p class="inv-created-date"><span class="inv-title invoive_font_size">Date : </span> <span class="inv-date invoive_font_size">Tel : +243 835 669 290</span></p>
                                                                <p class="inv-due-date" style="visibility: hidden;"><span class="inv-title">Due Date : </span> <span class="inv-date">26 Aug 2019</span></p>
                                                            </div>
                                                        </div>

                                                        <div class="row inv--product-table-section" style="margin-top: -45px;">
                                                            <div class="col-12">
                                                                <div class="table-responsive">
                                                                    <table class="table">
                                                                        <thead class="">
                                                                            <tr>
                                                                                <th scope="col" class="invoive_font_size">No</th>
                                                                                <th scope="col" class="invoive_font_size">Produits</th>
                                                                                <th class="text-right invoive_font_size" scope="col">Qté</th>
                                                                                <th class="text-right invoive_font_size" scope="col">Prix unitaire</th>
                                                                                <th class="text-right invoive_font_size" scope="col">Total</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                        <?php 
                                                                            $counter =0;
                                                                            foreach ($articles as $key => $value) {
                                                                                $counter ++;
                                                                                echo '<tr>
                                                                                        <td class="invoive_font_size">'.$counter.'</td>
                                                                                        <td class="invoive_font_size">'.$value['nom_article'].'</td>
                                                                                        <td class="text-right invoive_font_size">'.$value['quantite'].'</td>
                                                                                        <td class="text-right invoive_font_size">'.$value['prix_vente'].' USD</td>
                                                                                        <td class="text-right invoive_font_size">'.$value['montant'].' USD</td>
                                                                                    </tr>';
                                                                            }
                                                                        
                                                                        ?>
                                                                        <input type="number" name="id_sortie_stock" value="<?php echo $sortie_stock['id_sortie_stock']; ?>"  hidden />

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-4">
                                                            <div class="col-sm-1  order-sm-0 order-1">
                                                                <div class="inv--payment-info" style="display: none;">
                                                                    <div class="col-sm-12 col-12">
                                                                        <h5 class="inv-title"> Payer ici Avec RIPA </h5>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-sm-12 col-12">
                                                                            <h6 id="qcode-content" style="width: 50px; height: 50px;"></h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-11  order-sm-1 order-0">
                                                                <div class="inv--total-amounts text-sm-right">
                                                                    <div class="row">
                                                                        <div class="col-sm-8 col-7">
                                                                            <p class="invoive_font_size">Sous Total:</p>
                                                                        </div>
                                                                        <div class="col-sm-4 col-5">
                                                                            <p class="invoive_font_size"> <?php echo $sous_total ?> USD</p>
                                                                        </div>
                                                                        <div class="col-sm-8 col-7">
                                                                            <p class="invoive_font_size">Tva (16%): </p>
                                                                        </div>
                                                                        <div class="col-sm-4 col-5">
                                                                            <p class="invoive_font_size"> <?php echo $tva ?> USD</p>
                                                                        </div>
                                                                        <div class="col-sm-8 col-7">
                                                                            <p class="discount-rate invoive_font_size">Réduction : <span class="discount-percentage"><?php echo (int) $sortie_stock['taux_reduction']?> %</span> </p>
                                                                        </div>
                                                                        <div class="col-sm-4 col-5">
                                                                            <p class="invoive_font_size"> <?php echo $reduction  ?> USD</p>
                                                                        </div>
                                                                        <div class="col-sm-8 col-7 grand-total-title">
                                                                            <h5 class=""> Total :</h5>
                                                                        </div>
                                                                        <div class="col-sm-4 col-5 grand-total-amount">
                                                                            <h5 class=""> <?php echo $montant_total; ?> USD</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-12 order-sm-1 order-0" >
                                                                <div id="print" class="invoice-action"  style="float: right;">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <br><br><br><br><br>
            <br><br><br><br><br>
            <br><br><br><br><br>
            <br><br><br><br><br>
            <br><br><br><br><br>
            <br><br><br><br><br>
            <br><br><br><br><br>
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © <?php echo date('Y'); ?> <a target="_blank" href="#">CADEAUMART </a>, Tout droits reservés. </p>
                </div>
                <div class="footer-section f-section-2">
                    <p class="">Coded with <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></p>
                </div>
            </div>
        </div>
        <!--  END CONTENT AREA  -->
        
    </div>
    <!-- END MAIN CONTAINER -->
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS  action-print --> <!-- /BEGIN GLOBAL MANDATORY SCRIPTS -->
    <!-- <script src="<?php echo base_url("assets/dore_assets/"); ?>js/invoice.js"></script> -->
    <script>
        $(function(){

            var string_data_qr_code  = "0971403075ripa<?php echo $montant_total; ?>ripaUSD";
            var url_path             = "<?php echo site_url('Sortie_stock/udpateSortieStockIsEditable/');  ?>";
            var id_sortie_stock      = "<?php echo $sortie_stock['id_sortie_stock'];?>";
            //$("#qcode-content").html("");
            //jQuery("#qcode-content").qrcode(string_data_qr_code);


            $('#print_this').click(function (event) {
                $("#invoice").printThis({
                    header: "FACTURE",
                    importCSS: true,
                    beforePrint: function () {
                        formatInvoiceBeforePrint();
                    },
                    afterPrint : function () {
                        formatInvoiceAfertPrint();
                        $.ajax({
                            type: "GET",
                            url: url_path,
                            data: {'id_sortie_stock' : id_sortie_stock},
                            success: function (response) {
                                console.log(response);
                            },
                            error : function (error) {
                                alert('Sorry we are unable to reach your servers !!!');
                            }
                        });
                    }
                });
            });

            function formatInvoiceBeforePrint() {
                $("#invoice").attr('class','col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8');
                $("#invoice").attr('style','border: 1px solid silver; border-radius: 5px; margin-left: 18%; padding: 2% 2% 30% 2%; margin-top: -30px;');
                $("#company_infos").attr('style','margin-top: -3px;');
            }

            function formatInvoiceAfertPrint() {
                $("#invoice").attr('class','col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4');
                $("#invoice").attr('style','border: 1px solid silver; border-radius: 5px; margin-left: 33%; padding: 2% 2% 30% 2%; margin-top: -30px;');
                $("#company_infos").attr('style','margin-top: -30px;');
            }

            $(".enregistrer_facture").click(function (e) { 
                e.preventDefault();
                $("#form_facture").submit();
            });


        });
    </script>
</body>
</html>
<?php 
