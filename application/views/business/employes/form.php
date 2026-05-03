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
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nom *</label>
                    <input class="form-control" name="nom" required value="<?php echo htmlspecialchars($row['nom'] ?? ''); ?>" />
                </div>
                <div class="form-group col-md-6">
                    <label>Prénom *</label>
                    <input class="form-control" name="prenom" required value="<?php echo htmlspecialchars($row['prenom'] ?? ''); ?>" />
                </div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($row['email'] ?? ''); ?>" />
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input class="form-control" name="telephone" value="<?php echo htmlspecialchars($row['telephone'] ?? ''); ?>" />
            </div>
            <div class="form-group">
                <label>Poste / fonction</label>
                <input class="form-control" name="poste" value="<?php echo htmlspecialchars($row['poste'] ?? ''); ?>" />
            </div>
            <div class="form-group">
                <label>Lier à un compte portail (optionnel)</label>
                <select class="form-control" name="id_utilisateur_business">
                    <option value="">— Aucun —</option>
                    <?php foreach ($users_portail as $u) { ?>
                        <option value="<?php echo (int) $u['id']; ?>" <?php echo (isset($row['id_utilisateur_business']) && (int) $row['id_utilisateur_business'] === (int) $u['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($u['email'] . ' (' . $u['role'] . ')'); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="actif" value="1" id="actif" <?php echo (!isset($row) || !empty($row['actif'])) ? 'checked' : ''; ?> />
                <label class="form-check-label" for="actif">Actif</label>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a class="btn btn-link" href="<?php echo site_url('business/employes'); ?>">Annuler</a>
        </form>
    </div>
</div>
</main>
</body>
</html>
