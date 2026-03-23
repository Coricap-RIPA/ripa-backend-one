<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RIPA — Définir votre mot de passe</title>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/dore_assets/img/favicon.png'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome-4.7.0/css/font-awesome.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/dore_assets/css/vendor/bootstrap.min.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/dore_assets/css/main.css'); ?>" />
    <?php $this->load->view('business/partials/business_portal_styles'); ?>
</head>
<body class="ripa-portail-page">
    <header class="ripa-portail-topbar">
        <div class="logo-wrap">
            <img src="<?php echo base_url('assets/dore_assets/img/logoappwhite.png'); ?>" alt="RIPA" class="logo-white" />
            <div class="title-block">
                <strong>Portail marchand</strong><br />
                <span style="opacity:0.9;">Sécurisation du compte</span>
            </div>
        </div>
        <a class="topbar-action" href="<?php echo site_url('business/connexion'); ?>"><i class="fa fa-sign-in"></i> Connexion</a>
    </header>

    <main>
        <div class="container py-4 py-md-5">
            <div class="row">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <div class="card ripa-portail-card">
                        <div class="card-body-inner">
                            <h1 class="mb-2"><i class="fa fa-key" style="opacity:0.85;"></i> Définir votre mot de passe</h1>
                            <p class="lead-muted mb-4">Pour des raisons de sécurité, choisissez un <strong>nouveau mot de passe</strong> (minimum 8 caractères). Vous l’utiliserez pour vos prochaines connexions.</p>

                            <?php if (!empty($error)) { ?>
                                <div class="alert alert-danger mb-4"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?></div>
                            <?php } ?>

                            <form action="<?php echo site_url('business/premier-mot-de-passe/enregistrer'); ?>" method="post" autocomplete="on">
                                <?php echo ripa_portal_csrf_field(); ?>
                                <div class="form-group">
                                    <label for="password">Nouveau mot de passe</label>
                                    <div class="input-icon-wrap">
                                        <i class="fa fa-lock"></i>
                                        <input class="form-control" id="password" type="password" name="password" required minlength="8"
                                            placeholder="Au moins 8 caractères"
                                            autocomplete="new-password" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirm">Confirmation</label>
                                    <div class="input-icon-wrap">
                                        <i class="fa fa-lock"></i>
                                        <input class="form-control" id="password_confirm" type="password" name="password_confirm" required minlength="8"
                                            placeholder="Saisissez le même mot de passe"
                                            autocomplete="new-password" />
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-ripa-primary btn-lg"><i class="fa fa-check"></i> Enregistrer et continuer</button>
                            </form>
                        </div>
                    </div>
                    <p class="ripa-portail-footer-note">Ne partagez jamais votre mot de passe. En cas de perte, contactez le support RIPA.</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
