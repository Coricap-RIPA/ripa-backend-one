<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employés — Portail marchand</title>
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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Employés</h1>
        <?php if (!empty($can_write)) { ?>
            <a class="btn btn-primary" href="<?php echo site_url('business/employes/ajouter'); ?>">Ajouter</a>
        <?php } ?>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead><tr><th>#</th><th>Nom</th><th>Prénom</th><th>Poste</th><th>Email</th><th>Compte portail</th><th>Actif</th><?php if (!empty($can_write)) { ?><th></th><?php } ?></tr></thead>
                <tbody>
                    <?php if (empty($list)) { ?>
                        <tr><td colspan="8">Aucun employé.</td></tr>
                    <?php } else { foreach ($list as $r) { ?>
                        <tr>
                            <td><?php echo (int) $r['id']; ?></td>
                            <td><?php echo htmlspecialchars($r['nom']); ?></td>
                            <td><?php echo htmlspecialchars($r['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($r['poste'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($r['email'] ?? ''); ?></td>
                            <td><?php echo !empty($r['id_utilisateur_business']) ? '#' . (int) $r['id_utilisateur_business'] : '—'; ?></td>
                            <td><?php echo !empty($r['actif']) ? 'Oui' : 'Non'; ?></td>
                            <?php if (!empty($can_write)) { ?>
                                <td>
                                    <a class="btn btn-sm btn-outline-primary" href="<?php echo site_url('business/employes/modifier/' . (int) $r['id']); ?>">Modifier</a>
                                    <form action="<?php echo site_url('business/employes/supprimer/' . (int) $r['id']); ?>" method="post" style="display:inline;" onsubmit="return confirm('Supprimer cet employé ?');">
                                        <?php echo ripa_portal_csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </td>
                            <?php } ?>
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
