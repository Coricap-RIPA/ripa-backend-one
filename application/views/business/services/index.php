<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Services — Portail marchand</title>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/dore_assets/img/favicon.png'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome-4.7.0/css/font-awesome.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/materialize/css/materialize.min.css'); ?>" />
    <?php $this->load->view('business/partials/portal_shell_styles'); ?>
    <style>
        .ripa-svc-wrap .container { max-width: 1100px; width: 92%; }
        .ripa-svc-card { border-radius: 14px; border-top: 4px solid #270345; box-shadow: 0 4px 24px rgba(39, 3, 69, 0.08); overflow: hidden; }
        .ripa-svc-card .card-content { padding: 1.25rem 1.5rem; }
        .ripa-svc-card h2 { font-size: 1.1rem; color: #270345; font-weight: 700; margin: 0 0 1rem; }
        .ripa-svc-table thead th { background: #ede7f6; color: #270345; font-weight: 600; font-size: 0.85rem; }
        .ripa-svc-table td, .ripa-svc-table th { padding: 10px 12px; font-size: 0.9rem; vertical-align: middle; }
        .btn-ripa-svc { background-color: #270345 !important; }
        .btn-ripa-svc:hover { background-color: #3d0f5c !important; }
        .btn-ripa-svc-outline { border: 1px solid #270345 !important; color: #270345 !important; background: #fff !important; }
    </style>
</head>
<body class="ripa-portal-app ripa-svc-wrap">
<?php $this->load->view('business/partials/nav_portal'); ?>
<main class="ripa-portal-main">
    <div class="container">
        <div class="row valign-wrapper" style="margin-bottom:1rem;">
            <div class="col s12 m8">
                <h1 style="color:#270345;font-weight:700;font-size:1.5rem;margin:0;"><i class="fa fa-cubes"></i> Services internes</h1>
                <p class="grey-text text-darken-1" style="margin:4px 0 0;">Organisez les pôles ou activités rattachées à votre entreprise (comptabilité, trésorerie, etc.).</p>
            </div>
            <div class="col s12 m4 right-align" style="margin-top:8px;">
                <?php if (!empty($can_write)) { ?>
                    <a class="btn waves-effect waves-light btn-ripa-svc" href="<?php echo site_url('business/services/ajouter'); ?>"><i class="fa fa-plus left"></i> Nouveau</a>
                <?php } ?>
            </div>
        </div>

        <?php if ($this->session->flashdata('message')) { ?>
            <div class="card-panel teal lighten-4 teal-text text-darken-3" style="border-radius:12px;">
                <?php echo htmlspecialchars($this->session->flashdata('message'), ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } ?>

        <div class="card ripa-svc-card white">
            <div class="card-content">
                <h2>Liste des services</h2>
                <div class="responsive-table">
                    <table class="striped bordered ripa-svc-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Libellé</th>
                                <th>Description</th>
                                <th>Code</th>
                                <th>Ordre</th>
                                <th>Actif</th>
                                <?php if (!empty($can_write)) { ?><th class="right-align">Actions</th><?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($list)) { ?>
                                <tr><td colspan="<?php echo !empty($can_write) ? '7' : '6'; ?>" class="grey-text">Aucun service. <?php if (!empty($can_write)) { ?><a href="<?php echo site_url('business/services/ajouter'); ?>">Créer un service</a><?php } ?></td></tr>
                            <?php } else { foreach ($list as $r) {
                                $desc = isset($r['description']) ? trim((string) $r['description']) : '';
                                if ($desc === '') {
                                    $desc_short = '—';
                                } elseif (function_exists('mb_strlen') && mb_strlen($desc, 'UTF-8') > 80) {
                                    $desc_short = mb_substr($desc, 0, 80, 'UTF-8') . '…';
                                } elseif (strlen($desc) > 80) {
                                    $desc_short = substr($desc, 0, 80) . '…';
                                } else {
                                    $desc_short = $desc;
                                }
                                ?>
                                <tr>
                                    <td><?php echo (int) $r['id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($r['libelle']); ?></strong></td>
                                    <td class="grey-text text-darken-1"><?php echo htmlspecialchars($desc_short); ?></td>
                                    <td><?php echo htmlspecialchars($r['code'] ?? ''); ?></td>
                                    <td><?php echo (int) ($r['ordre'] ?? 0); ?></td>
                                    <td><?php echo !empty($r['actif']) ? '<span class="chip green lighten-4 green-text text-darken-2" style="height:26px;line-height:26px;font-size:0.75rem;">Oui</span>' : '<span class="chip grey lighten-2" style="height:26px;line-height:26px;font-size:0.75rem;">Non</span>'; ?></td>
                                    <?php if (!empty($can_write)) { ?>
                                        <td class="right-align">
                                            <a class="btn-small waves-effect btn-ripa-svc-outline" href="<?php echo site_url('business/services/modifier/' . (int) $r['id']); ?>">Modifier</a>
                                            <form action="<?php echo site_url('business/services/supprimer/' . (int) $r['id']); ?>" method="post" style="display:inline;" onsubmit="return confirm('Supprimer ce service ?');">
                                                <?php echo ripa_portal_csrf_field(); ?>
                                                <button type="submit" class="btn-small waves-effect red lighten-3 red-text text-darken-2" style="border:none;">Supprimer</button>
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
    </div>
</main>
<script src="<?php echo base_url('assets/dore_assets/js/libs/jquery-3.1.1.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/materialize/js/materialize.min.js'); ?>"></script>
<script>document.addEventListener('DOMContentLoaded', function () { if (typeof M !== 'undefined') M.AutoInit(); });</script>
</body>
</html>
