<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/dore_assets/img/favicon.png'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome-4.7.0/css/font-awesome.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/dore_assets/css/vendor/bootstrap.min.css'); ?>" />
    <?php $this->load->view('business/partials/portal_shell_styles'); ?>
</head>
<body class="ripa-portal-app">
<?php $this->load->view('business/partials/nav_portal'); ?>
<main class="ripa-portal-main">
<div class="container">
    <h1 class="h3 mb-4"><?php echo htmlspecialchars($title); ?></h1>
    <?php if (!empty($error)) { ?><div class="alert alert-danger"><?php echo $error; ?></div><?php } ?>
    <div class="card p-4">
        <form method="post" action="">
            <?php echo ripa_portal_csrf_field(); ?>
            <div class="form-group">
                <label>Libellé *</label>
                <input class="form-control" name="libelle" required value="<?php echo htmlspecialchars($row['libelle'] ?? ''); ?>" />
            </div>
            <div class="form-group">
                <label>Code (optionnel)</label>
                <input class="form-control" name="code" value="<?php echo htmlspecialchars($row['code'] ?? ''); ?>" />
            </div>
            <div class="form-group">
                <label>Ordre</label>
                <input class="form-control" type="number" name="ordre" value="<?php echo isset($row['ordre']) ? (int) $row['ordre'] : 0; ?>" />
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="actif" value="1" id="actif" <?php echo (!isset($row) || !empty($row['actif'])) ? 'checked' : ''; ?> />
                <label class="form-check-label" for="actif">Actif</label>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a class="btn btn-link" href="<?php echo site_url('business/services'); ?>">Annuler</a>
        </form>
    </div>
</div>
</main>
</body>
</html>
