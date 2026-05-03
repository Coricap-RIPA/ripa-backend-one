<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transactions — Portail marchand</title>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/dore_assets/img/favicon.png'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome-4.7.0/css/font-awesome.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/dore_assets/css/vendor/bootstrap.min.css'); ?>" />
    <?php $this->load->view('business/partials/portal_shell_styles'); ?>
</head>
<body class="ripa-portal-app">
<?php $this->load->view('business/partials/nav_portal'); ?>
<main class="ripa-portal-main">
<div class="container">
    <?php if ($this->session->flashdata('message')) { ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($this->session->flashdata('message')); ?></div>
    <?php } ?>
    <?php if ($this->session->flashdata('warning_message')) { ?>
        <div class="alert alert-warning"><?php echo htmlspecialchars($this->session->flashdata('warning_message')); ?></div>
    <?php } ?>
    <?php
    $kyb_ok = !empty($kyb_transactions_ok);
    ?>
    <?php if (!empty($can_write) && !$kyb_ok) { ?>
        <div class="alert alert-warning mb-3">
            Les transactions ne sont pas autorisées tant que votre dossier KYB n’est pas <strong>approuvé</strong> et <strong>dans sa période de validité</strong> (12 mois après validation). Complétez ou renouvelez votre dossier depuis la page KYB.
            <a class="alert-link" href="<?php echo site_url('business/kyb'); ?>">Accéder au KYB</a>
        </div>
    <?php } ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Transactions</h1>
        <?php if (!empty($can_write) && $kyb_ok) { ?>
            <a class="btn btn-primary" href="<?php echo site_url('business/transactions/saisir'); ?>">Saisir une opération</a>
        <?php } ?>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm table-striped mb-0">
                <thead>
                    <tr>
                        <th>Date</th><th>Type</th><th>Sens</th><th>Montant</th><th>Devise</th><th>Libellé</th><th>Id paiement RIPA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($list)) { ?>
                        <tr><td colspan="7">Aucune transaction enregistrée.</td></tr>
                    <?php } else { foreach ($list as $r) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($r['created_at']); ?></td>
                            <td><?php echo htmlspecialchars($r['type_operation']); ?></td>
                            <td><?php echo htmlspecialchars($r['sens']); ?></td>
                            <td><?php echo htmlspecialchars(number_format((float) $r['montant'], 2, ',', ' ')); ?></td>
                            <td><?php echo htmlspecialchars($r['devise']); ?></td>
                            <td><?php echo htmlspecialchars($r['libelle'] ?? ''); ?></td>
                            <td><?php echo $r['id_paiement_ripa'] !== null ? (int) $r['id_paiement_ripa'] : '—'; ?></td>
                        </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</main>
</body>
</html>
