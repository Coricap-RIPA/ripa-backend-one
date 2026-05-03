<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RIPA — Demande de compte marchand</title>
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
                <span style="opacity:0.9;">Ouverture de compte entreprise</span>
            </div>
        </div>
        <a class="topbar-action" href="<?php echo site_url('business/connexion'); ?>"><i class="fa fa-sign-in"></i> Connexion</a>
    </header>

    <main>
        <div class="container py-4 py-md-5">
            <div class="row">
                <div class="col-lg-7 col-md-9 mx-auto">
                    <div class="card ripa-portail-card">
                        <div class="card-body-inner">
                            <h1 class="mb-2"><i class="fa fa-briefcase" style="opacity:0.85;"></i> Demande de compte entreprise</h1>
                            <p class="lead-muted mb-4">Remplissez le formulaire. L’équipe <strong>RIPA</strong> validera votre demande avant activation du portail.</p>

                            <?php if ($success) { ?>
                                <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $message; ?></div>
                                <a class="btn btn-ripa-primary btn-lg" href="<?php echo site_url('business/connexion'); ?>"><i class="fa fa-arrow-right"></i> Aller à la connexion</a>
                            <?php } elseif (!empty($message)) { ?>
                                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $message; ?></div>
                            <?php } ?>

                            <?php if (!$success) { ?>
                                <form action="<?php echo site_url('business/inscription/soumettre'); ?>" method="post">
                                    <?php echo ripa_portal_csrf_field(); ?>
                                    <div class="form-group">
                                        <label for="raison_sociale">Raison sociale *</label>
                                        <div class="input-icon-wrap">
                                            <i class="fa fa-building"></i>
                                            <input class="form-control" id="raison_sociale" name="raison_sociale" required minlength="2"
                                                placeholder="Ex. SARL Commerce Kinshasa"
                                                autocomplete="organization" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email_contact">Email professionnel *</label>
                                        <div class="input-icon-wrap">
                                            <i class="fa fa-envelope"></i>
                                            <input class="form-control" id="email_contact" type="email" name="email_contact" required
                                                placeholder="contact@entreprise.cd"
                                                autocomplete="email" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="telephone_contact">Téléphone *</label>
                                        <div class="input-icon-wrap">
                                            <i class="fa fa-phone"></i>
                                            <input class="form-control" id="telephone_contact" name="telephone_contact" required
                                                placeholder="Ex. +243 850 000 000"
                                                autocomplete="tel" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="identifiant_legal">RCCM / identifiant légal (optionnel)</label>
                                        <div class="input-icon-wrap">
                                            <i class="fa fa-id-card"></i>
                                            <input class="form-control" id="identifiant_legal" name="identifiant_legal"
                                                maxlength="100" placeholder="Ex. CD/KIN/RCCM/24-B-00001" />
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center" style="gap:12px;">
                                        <button type="submit" class="btn btn-ripa-primary btn-lg"><i class="fa fa-paper-plane"></i> Envoyer la demande</button>
                                        <a class="text-decoration-none font-weight-bold" style="color:var(--ripa-bar);" href="<?php echo site_url('business/connexion'); ?>">Déjà un compte ? Connexion</a>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                    <p class="ripa-portail-footer-note">Données traitées conformément aux engagements RIPA. Aucun mot de passe n’est demandé à cette étape.</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
