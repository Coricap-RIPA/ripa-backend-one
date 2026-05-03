<?php
echo $header;
echo $footer;
$d = isset($data) ? $data : array();
$id_kyc = isset($d['id_kyc']) ? (int) $d['id_kyc'] : 0;
$statut = isset($d['statut']) ? $d['statut'] : '';
?>
<body style="background-color: whitesmoke;">
    <div id="load_screen" class="loader" style="display: none;"></div>
    <div class="main_container">
        <?php echo $navbar; ?>
        <div id="row_content" class="row">
            <div class="row col s12">
                <fieldset>
                    <legend>Dossier KYC #<?php echo $id_kyc; ?> — Détail pour vérification</legend>
                </fieldset>
            </div>
            <?php if (!empty($this->session->flashdata('message'))) { ?>
                <div class="col s12">
                    <div class="card-panel green lighten-4 green-text text-darken-2"><?php echo $this->session->flashdata('message'); ?></div>
                </div>
            <?php } ?>
            <div class="col s12 m8">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Identité</span>
                        <table class="striped">
                            <tr><td><strong>Nom</strong></td><td><?php echo htmlspecialchars($d['nom'] ?? ''); ?></td></tr>
                            <tr><td><strong>Post-nom</strong></td><td><?php echo htmlspecialchars($d['post_nom'] ?? ''); ?></td></tr>
                            <tr><td><strong>Prénom</strong></td><td><?php echo htmlspecialchars($d['prenom'] ?? ''); ?></td></tr>
                            <tr><td><strong>Date de naissance</strong></td><td><?php echo htmlspecialchars($d['date_naissance'] ?? ''); ?></td></tr>
                            <tr><td><strong>Adresse</strong></td><td><?php echo htmlspecialchars($d['adresse'] ?? ''); ?></td></tr>
                            <tr><td><strong>Téléphone (app)</strong></td><td><?php echo htmlspecialchars($d['phone'] ?? ''); ?></td></tr>
                            <tr><td><strong>Statut</strong></td><td><strong><?php echo htmlspecialchars($statut); ?></strong></td></tr>
                            <tr><td><strong>Date enregistrement</strong></td><td><?php echo htmlspecialchars($d['date_enregistrement'] ?? ''); ?></td></tr>
                            <?php if (!empty($d['date_validation_kyc'])) { ?>
                            <tr><td><strong>Date validation</strong></td><td><?php echo htmlspecialchars($d['date_validation_kyc']); ?></td></tr>
                            <?php } ?>
                            <?php if (!empty($d['date_prochaine_kyc'])) { ?>
                            <tr><td><strong>Prochaine révision KYC</strong></td><td><?php echo htmlspecialchars($d['date_prochaine_kyc']); ?></td></tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Photo pièce d'identité</span>
                        <?php if (!empty($d['photo_piece_base64'])) { ?>
                            <img src="data:image/jpeg;base64,<?php echo $d['photo_piece_base64']; ?>" alt="Pièce d'identité" style="max-width: 100%; max-height: 400px; border: 1px solid #ddd; border-radius: 8px;" />
                        <?php } else { ?>
                            <p class="grey-text">Non disponible.</p>
                        <?php } ?>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Photo utilisateur (selfie)</span>
                        <?php if (!empty($d['photo_utilisateur_base64'])) { ?>
                            <img src="data:image/jpeg;base64,<?php echo $d['photo_utilisateur_base64']; ?>" alt="Selfie" style="max-width: 100%; max-height: 400px; border: 1px solid #ddd; border-radius: 8px;" />
                        <?php } else { ?>
                            <p class="grey-text">Non disponible.</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col s12 m4">
                <div class="card purple lighten-5">
                    <div class="card-content">
                        <span class="card-title">Actions</span>
                        <p class="grey-text text-darken-2">Après vérification du dossier, validez, rejetez ou supprimez la demande.</p>
                        <?php $can_editer = !empty($d['can_editer']); $can_supprimer = !empty($d['can_supprimer']); ?>
                        <?php if ($can_editer && $statut === 'en_attente') { ?>
                        <form action="<?php echo site_url('Kyc_backoffice/valider/' . $id_kyc); ?>" method="post" style="margin-bottom: 12px;">
                            <button type="submit" class="btn green">Valider le KYC</button>
                        </form>
                        <?php } ?>
                        <?php if ($can_editer && $statut !== 'rejete') { ?>
                        <form action="<?php echo site_url('Kyc_backoffice/rejeter/' . $id_kyc); ?>" method="post" style="margin-bottom: 12px;">
                            <button type="submit" class="btn orange">Rejeter</button>
                        </form>
                        <?php } ?>
                        <?php if ($can_supprimer) { ?>
                        <form action="<?php echo site_url('Kyc_backoffice/supprimer/' . $id_kyc); ?>" method="post" onsubmit="return confirm('Supprimer définitivement ce dossier KYC ?');">
                            <button type="submit" class="btn red">Supprimer le dossier</button>
                        </form>
                        <?php } ?>
                        <p style="margin-top: 20px;">
                            <a href="<?php echo site_url('Kyc_backoffice/index'); ?>" class="btn grey">Retour à la liste</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div id="row_footer" class="row">
            <div class="footer-wrapper col s12">
                <div class="footer-section f-section-1 col s6" style="padding-left: 2%;">
                    <p class="">Copyright © <?php echo date('Y'); ?> <a target="_blank" href="#">RIPA</a>, Tous droits réservés.</p>
                </div>
                <div class="footer-section f-section-2 col s6 text-right" style="padding-right: 2%;">
                    <p style="float: right;">Coded with: <a href="mailto:pascalmmp@gmail.com">CORICAP</a></p>
                </div>
            </div>
        </div>
    </div>
    <div style="display: none;"><?php echo $output; ?></div>
</body>
</html>
