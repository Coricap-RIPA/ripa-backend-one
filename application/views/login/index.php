<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>RIPA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/dore_assets/');?>img/favicon.png"/>
    <link rel="stylesheet" href="<?php echo base_url("assets/dore_assets/"); ?>font/iconsmind/style.css" />
    <link rel="stylesheet" href="<?php echo base_url("assets/dore_assets/"); ?>font/simple-line-icons/css/simple-line-icons.css" />
    <link rel="stylesheet" href="<?php echo base_url("assets/dore_assets/"); ?>css/vendor/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo base_url("assets/dore_assets/"); ?>css/vendor/bootstrap-float-label.min.css" />
    <link rel="stylesheet" href="<?php echo base_url("assets/dore_assets/"); ?>css/main.css" />
</head>

<body class="background show-spinner">
    <div class="fixed-background"></div>
    <main>
        <div class="container">
            <div class="row h-100">
                <div class="col-12 col-md-10 mx-auto my-auto">
                    <div class="card auth-card">
                        <div class="position-relative image-side ">

                            <p class=" text-white h2" style="text-shadow: 2px 1px 3px #000;">Espace de connexion </p>

                            <p class="mb-0" style="color: white; text-shadow: 2px 2px 2px #000;">
                                Veuillez introduire vos identifiants 
                                <br>Si vous n'etes pas utilisateur contactez l'administrateur
                                <a href="mailto:pascalmmp@gmail.com" style="color: white; text-shadow: 2px 1px 3px #000;"> <b> en cliquant ici </b> </a>.
                            </p>
                        </div>
                        <div class="form-side">
                            <h1> 
                                <img src="<?php echo base_url('assets/dore_assets/img/logoapp.png') ?>" style="width: 200px; height: 80px; display: inline-block; position: relative; top: -1px;"/>
                                <b style="color:black; font-size: 20px;"> Le paiment mobile tout en un  </b> 
                                <br>
                            </h1>
                            <?php

                                if($error != ''){
                                    ?><p class="text-center" style="color: red;"><b><?php echo $error ?></b></p><?php
                                }

                            ?>
                            <form action="<?php echo site_url("Starter/check_connexion") ?>" method="POST"> 
                                
                                <label class="form-group has-float-label mb-4">
                                    <input class="form-control" name="email" required />
                                    <span>E-mail *</span>
                                </label>

                                <label class="form-group has-float-label mb-4">
                                    <input class="form-control" type="password"  name="password" placeholder="" required />
                                    <span>Mot de passe *</span>
                                </label>

                                <div class="d-flex justify-content-between align-items-center">
                                    <button class="btn  btn-lg btn-shadow" type="submit" style="background-color: #270345; color: white;">CONNEXION</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>js/vendor/jquery-3.3.1.min.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>js/vendor/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>js/dore.script.js"></script>
    <script src="<?php echo base_url("assets/dore_assets/"); ?>js/scripts.js"></script>
</body>

</html>