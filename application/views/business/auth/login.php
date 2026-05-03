<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RIPA — Connexion portail marchand</title>
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
                <span style="opacity:0.9;">Connexion entreprise</span>
            </div>
        </div>
        <a class="topbar-action" href="<?php echo site_url('business/inscription'); ?>"><i class="fa fa-user-plus"></i> Demande de compte</a>
    </header>

    <main>
        <div class="container py-4 py-md-5">
            <div class="row">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <div class="card ripa-portail-card">
                        <div class="card-body-inner">
                            <h1 class="mb-2"><i class="fa fa-sign-in" style="opacity:0.85;"></i> Connexion</h1>
                            <p class="lead-muted mb-4">Accédez au portail marchand avec votre <strong>email professionnel</strong> et votre mot de passe.</p>

                            <?php if (!empty($error)) { ?>
                                <div class="alert alert-danger mb-4"><i class="fa fa-exclamation-circle"></i> <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                            <?php } ?>

                            <form action="<?php echo site_url('business/connexion/traitement'); ?>" method="post" autocomplete="on">
                                <?php echo ripa_portal_csrf_field(); ?>
                                <div class="form-group">
                                    <label for="email">Email professionnel</label>
                                    <div class="input-icon-wrap">
                                        <i class="fa fa-envelope"></i>
                                        <input class="form-control" id="email" type="email" name="email" required
                                            placeholder="contact@entreprise.cd"
                                            autocomplete="username" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password">Mot de passe</label>
                                    <div class="input-icon-wrap">
                                        <i class="fa fa-lock"></i>
                                        <input class="form-control" id="password" type="password" name="password" required
                                            placeholder="Votre mot de passe"
                                            autocomplete="current-password" />
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap align-items-center" style="gap:12px;">
                                    <button type="submit" class="btn btn-ripa-primary btn-lg"><i class="fa fa-unlock-alt"></i> Se connecter</button>
                                    <a class="text-decoration-none font-weight-bold" style="color:var(--ripa-bar);" href="<?php echo site_url('business/inscription'); ?>">Pas encore de compte ?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <p class="ripa-portail-footer-note">Connexion sécurisée. En cas de difficulté, contactez le support RIPA.</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
