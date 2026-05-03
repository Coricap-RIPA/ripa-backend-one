<?php
echo $header;
echo $footer;
?>
<body style="background-color: whitesmoke;">
    <div id="load_screen" class="loader" style="display: none;"></div>
    <div class="main_container">
        <?php echo $navbar; ?>
        <div id="row_content" class="row">
            <div class="row col s12">
                <fieldset>
                    <legend>KYC Application — Dossiers de vérification d'identité</legend>
                </fieldset>
            </div>
            <?php if (!empty($this->session->flashdata('message'))) { ?>
                <div class="col s12">
                    <div class="card-panel green lighten-4 green-text text-darken-2"><?php echo $this->session->flashdata('message'); ?></div>
                </div>
            <?php } ?>
            <div class="col s12">
                <p class="grey-text">Filtrer par statut :</p>
                <a href="<?php echo site_url('Kyc_backoffice/index'); ?>" class="btn btn-small <?php echo ($filter_statut === null || $filter_statut === '') ? 'purple' : 'grey'; ?>">Tous</a>
                <a href="<?php echo site_url('Kyc_backoffice/index?statut=en_attente'); ?>" class="btn btn-small <?php echo $filter_statut === 'en_attente' ? 'purple' : 'grey'; ?>">En attente</a>
                <a href="<?php echo site_url('Kyc_backoffice/index?statut=valide'); ?>" class="btn btn-small <?php echo $filter_statut === 'valide' ? 'purple' : 'grey'; ?>">Validés</a>
                <a href="<?php echo site_url('Kyc_backoffice/index?statut=rejete'); ?>" class="btn btn-small <?php echo $filter_statut === 'rejete' ? 'purple' : 'grey'; ?>">Rejetés</a>
            </div>
            <div class="col s12" style="margin-top: 20px;">
                <table class="striped bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th>Date enregistrement</th>
                            <th>Date validation</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($list)) { ?>
                            <tr><td colspan="6" class="center grey-text">Aucun dossier KYC.</td></tr>
                        <?php } else {
                            foreach ($list as $row) {
                                $statut_class = $row['statut'] === 'valide' ? 'green-text' : ($row['statut'] === 'rejete' ? 'red-text' : 'orange-text');
                                ?>
                        <tr>
                            <td><?php echo (int) $row['id_kyc']; ?></td>
                            <td><?php echo htmlspecialchars($row['phone'] ?? ''); ?></td>
                            <td class="<?php echo $statut_class; ?>"><strong><?php echo htmlspecialchars($row['statut']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['date_enregistrement'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($row['date_validation_kyc'] ?? '—'); ?></td>
                            <td>
                                <a href="<?php echo site_url('Kyc_backoffice/detail/' . $row['id_kyc']); ?>" class="btn btn-small blue">Voir le dossier</a>
                            </td>
                        </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
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
