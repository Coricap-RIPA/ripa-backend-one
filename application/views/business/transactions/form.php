<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouvelle transaction — Portail marchand</title>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/dore_assets/img/favicon.png'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome-4.7.0/css/font-awesome.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/dore_assets/css/vendor/bootstrap.min.css'); ?>" />
    <?php $this->load->view('business/partials/portal_shell_styles'); ?>
</head>
<body class="ripa-portal-app">
<?php $this->load->view('business/partials/nav_portal'); ?>
<main class="ripa-portal-main">
<div class="container">
    <h1 class="h3 mb-4">Saisir une opération</h1>
    <?php if (empty($kyb_transactions_ok)) { ?>
        <div class="alert alert-warning">
            Les transactions ne sont pas autorisées sans dossier KYB approuvé et valide. <a class="alert-link" href="<?php echo site_url('business/kyb'); ?>">Dossier KYB</a>
        </div>
    <?php } ?>
    <?php if (!empty($error)) { ?><div class="alert alert-danger"><?php echo $error; ?></div><?php } ?>
    <div class="card p-4">
        <?php if (!empty($kyb_transactions_ok)) { ?>
        <form method="post" action="">
            <?php echo ripa_portal_csrf_field(); ?>
            <div class="form-group">
                <label>Type d’opération *</label>
                <select class="form-control" name="type_operation" required>
                    <?php
                    $types = array('encaissement', 'decaissement', 'salaire', 'virement', 'paiement_fournisseur', 'autre');
                    foreach ($types as $t) {
                        echo '<option value="' . htmlspecialchars($t) . '">' . htmlspecialchars($t) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Sens *</label>
                <select class="form-control" name="sens" required>
                    <option value="debit">Débit</option>
                    <option value="credit">Crédit</option>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Montant *</label>
                    <input class="form-control" type="number" step="0.01" min="0" name="montant" required />
                </div>
                <div class="form-group col-md-6">
                    <label>Devise</label>
                    <input class="form-control" name="devise" value="USD" maxlength="8" />
                </div>
            </div>
            <div class="form-group">
                <label>Libellé</label>
                <input class="form-control" name="libelle" maxlength="500" />
            </div>
            <div class="form-group">
                <label>Service (optionnel)</label>
                <select class="form-control" name="id_service">
                    <option value="">—</option>
                    <?php foreach ($services as $s) { ?>
                        <option value="<?php echo (int) $s['id']; ?>"><?php echo htmlspecialchars($s['libelle']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Employé (optionnel)</label>
                <select class="form-control" name="id_employe">
                    <option value="">—</option>
                    <?php foreach ($employes as $e) { ?>
                        <option value="<?php echo (int) $e['id']; ?>"><?php echo htmlspecialchars($e['nom'] . ' ' . $e['prenom']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a class="btn btn-link" href="<?php echo site_url('business/transactions'); ?>">Annuler</a>
        </form>
        <?php } else { ?>
            <a class="btn btn-secondary" href="<?php echo site_url('business/transactions'); ?>">Retour</a>
        <?php } ?>
    </div>
</div>
</main>
</body>
</html>
