<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>  RIPA   </title>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/dore_assets/');?>img/favicon.png"/>


    <link href="<?php echo base_url('assets/dore_assets/');?>css/loader.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url('assets/dore_assets/');?>js/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <!-- <link href="<?php echo base_url('assets/dore_assets/');?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> -->
    <link href="<?php echo base_url('assets/dore_assets/');?>css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="<?php echo base_url('assets/dore_assets/');?>plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/dore_assets/');?>css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/dore_assets/');?>plugins/font-icons/fontawesome/css/regular.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/dore_assets/');?>plugins/font-icons/fontawesome/css/fontawesome.css">

    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="<?php echo base_url("assets/dore_assets/"); ?>plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url("assets/dore_assets/"); ?>css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url("assets/dore_assets/"); ?>css/scrollspyNav.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url("assets/dore_assets/"); ?>css/components/cards/card.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/dore_assets/');?>plugins/font-icons/fontawesome/css/regular.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/dore_assets/');?>plugins/font-icons/fontawesome/css/fontawesome.css">
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

    <?php if( isset($css_files) ) { ?>
        <?php foreach($css_files as $file): ?>
            <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
        <?php endforeach; ?>
    <?php } ?>    

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/dore_assets/');?>plugins/notification/snackbar/snackbar.min.css" />
    <link rel="stylesheet" href="<?php echo base_url('assets/materialize/');?>css/materialize.css">   
    <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome-4.7.0/');?>css/font-awesome.css"> 
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2017.2.621/styles/kendo.common-material.min.css"/>
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2017.2.621/styles/kendo.material.min.css"> 
    
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/datatables/');?>dataTables.dataTables.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/datatables/');?>buttons.dataTables.css" /> -->

    <script>
        window.onload = function() {
            var context = new AudioContext();
        }
    </script>

</head>
<style>
    .link_menu_active{
        border-top: 4px solid #fff;
    }

    #dropdown1{
        width : 300px !important;
        top : 45px !important;
    }
    
</style>