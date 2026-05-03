<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/dore_assets/img/favicon.png'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome-4.7.0/css/font-awesome.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/materialize/css/materialize.min.css'); ?>" />
    <?php $this->load->view('business/partials/portal_shell_styles'); ?>
    <style>
        .ripa-svc-form .container { max-width: 720px; width: 92%; }
        .ripa-svc-form-card { border-radius: 14px; border-top: 4px solid #270345; box-shadow: 0 4px 24px rgba(39, 3, 69, 0.08); }
        .ripa-svc-form-card .card-content { padding: 1.5rem; }
        .btn-ripa-svc { background-color: #270345 !important; }
        .btn-ripa-svc:hover { background-color: #3d0f5c !important; }
        .input-field label { color: #5c4a6e; }
    </style>
</head>
<body class="ripa-portal-app ripa-svc-form">
<?php $this->load->view('business/partials/nav_portal'); ?>
<main class="ripa-portal-main">
    <div class="container">
        <h1 style="color:#270345;font-weight:700;font-size:1.45rem;"><?php echo htmlspecialchars($title); ?></h1>
        <?php if (!empty($error)) { ?>
            <div class="card-panel red lighten-4 red-text text-darken-2" style="border-radius:12px;"><?php echo $error; ?></div>
        <?php } ?>
        <div class="card ripa-svc-form-card white" style="margin-top:1rem;">
            <div class="card-content">
                <form method="post" action="" class="row" style="margin-bottom:0;">
                    <?php echo ripa_portal_csrf_field(); ?>
                    <div class="input-field col s12" style="margin-top:0;">
                        <label for="svc_lib" class="active">Libellé *</label>
                        <input id="svc_lib" type="text" name="libelle" required maxlength="255" value="<?php echo htmlspecialchars($row['libelle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                    </div>
                    <div class="input-field col s12">
                        <label for="svc_desc" class="active">Description (optionnel)</label>
                        <textarea id="svc_desc" name="description" class="materialize-textarea" style="min-height:88px;border:1px solid #ddd;border-radius:8px;padding:12px;"><?php echo htmlspecialchars($row['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <div class="input-field col s12 m6">
                        <label for="svc_code" class="active">Code interne</label>
                        <input id="svc_code" type="text" name="code" maxlength="64" value="<?php echo htmlspecialchars($row['code'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                    </div>
                    <div class="input-field col s12 m6">
                        <label for="svc_ordre" class="active">Ordre d’affichage</label>
                        <input id="svc_ordre" type="number" name="ordre" value="<?php echo isset($row['ordre']) ? (int) $row['ordre'] : 0; ?>" />
                    </div>
                    <p style="margin:12px 0;">
                        <label>
                            <input type="checkbox" name="actif" value="1" class="filled-in" <?php echo (!isset($row) || !empty($row['actif'])) ? 'checked' : ''; ?> />
                            <span>Service actif</span>
                        </label>
                    </p>
                    <button type="submit" class="btn waves-effect waves-light btn-ripa-svc">Enregistrer</button>
                    <a class="btn-flat purple-text text-darken-2" href="<?php echo site_url('business/services'); ?>">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</main>
<script src="<?php echo base_url('assets/dore_assets/js/libs/jquery-3.1.1.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/materialize/js/materialize.min.js'); ?>"></script>
<script>document.addEventListener('DOMContentLoaded', function () { if (typeof M !== 'undefined') M.AutoInit(); });</script>
</body>
</html>
