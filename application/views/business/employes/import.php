<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Import employés — Portail marchand</title>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/dore_assets/img/favicon.png'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome-4.7.0/css/font-awesome.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/materialize/css/materialize.min.css'); ?>" />
    <?php $this->load->view('business/partials/portal_shell_styles'); ?>
    <style>
        .ripa-mz-card { border-radius: 14px; box-shadow: 0 4px 24px rgba(39, 3, 69, 0.08); border-top: 4px solid #270345; overflow: hidden; }
        .ripa-mz-card .card-content { padding: 1.5rem 1.5rem 1.25rem; }
        .ripa-mz-card h2 { font-size: 1.15rem; color: #270345; font-weight: 700; margin: 0 0 1rem; }
        .ripa-guide-table th { background: #ede7f6; color: #270345; font-weight: 600; }
        .ripa-guide-table td, .ripa-guide-table th { padding: 10px 12px; font-size: 0.9rem; }
        .ripa-mz-page h1.page-title { color: #270345; font-weight: 700; font-size: 1.5rem; }
        .btn-ripa-mz { background-color: #270345 !important; }
        .btn-ripa-mz:hover { background-color: #3d0f5c !important; }
        .file-field .btn { background-color: #270345; }
        .modal.ripa-modal-guide { border-radius: 12px; max-width: 720px; }
        .modal.ripa-modal-guide h4 { color: #270345; font-size: 1.2rem; }
        .ripa-mz-page > .container { max-width: 1200px; width: 92%; }
    </style>
</head>
<body class="ripa-portal-app">
<?php $this->load->view('business/partials/nav_portal'); ?>
<main class="ripa-portal-main ripa-mz-page">
    <div class="container">
        <div class="row valign-wrapper" style="margin-bottom:1rem;">
            <div class="col s12 m8">
                <h1 class="page-title"><i class="fa fa-file-excel-o"></i> Import employés (Excel)</h1>
                <p class="grey-text text-darken-1" style="margin:0;">Première ligne = noms de colonnes. Téléchargez le modèle pour le format attendu.</p>
            </div>
            <div class="col s12 m4 right-align" style="margin-top:8px;">
                <a href="<?php echo site_url('business/employes'); ?>" class="btn-flat waves-effect purple-text text-darken-2">← Liste</a>
            </div>
        </div>

        <?php if ($this->session->flashdata('message')) { ?>
            <div class="card-panel teal lighten-4 teal-text text-darken-3" style="border-radius:12px;">
                <?php echo htmlspecialchars($this->session->flashdata('message'), ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } ?>

        <?php
        $report = isset($import_report) ? $import_report : null;
        if (!empty($report) && (!empty($report['errors']) || !empty($report['error_count']))) {
            ?>
            <div class="card-panel amber lighten-4 brown-text text-darken-2" style="border-radius:12px;">
                <strong>Résultat :</strong> <?php echo (int) ($report['imported'] ?? 0); ?> ligne(s) enregistrée(s).
                <?php if (!empty($report['error_count'])) { ?>
                    — <?php echo (int) $report['error_count']; ?> ligne(s) en erreur.
                <?php } ?>
                <?php if (!empty($report['errors'])) { ?>
                    <ul class="browser-default" style="margin:10px 0 0;">
                        <?php foreach ($report['errors'] as $er) { ?>
                            <li>Ligne <?php echo (int) $er['line']; ?> : <?php echo htmlspecialchars($er['msg'], ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col s12 l5">
                <div class="card ripa-mz-card white">
                    <div class="card-content">
                        <h2><i class="fa fa-upload"></i> Envoyer un fichier</h2>
                        <p class="grey-text text-darken-1 small">Formats : <strong>.xlsx</strong>, <strong>.xls</strong> — max. 2 Mo, 500 lignes max.</p>
                        <form method="post" action="<?php echo site_url('business/employes/import'); ?>" enctype="multipart/form-data">
                            <?php echo ripa_portal_csrf_field(); ?>
                            <div class="file-field input-field" style="margin-top:1.5rem;">
                                <div class="btn btn-ripa-mz">
                                    <span>Fichier</span>
                                    <input type="file" name="fichier_import" accept=".xlsx,.xls,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" required>
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" placeholder="Choisir un fichier Excel">
                                </div>
                            </div>
                            <button class="btn waves-effect waves-light btn-ripa-mz" type="submit" style="margin-top:12px;">
                                Importer <i class="fa fa-arrow-right right" style="margin-left:8px;"></i>
                            </button>
                            <a class="btn-flat waves-effect purple-text text-darken-2" style="margin-top:12px;" href="<?php echo site_url('business/employes/modele-excel'); ?>">
                                <i class="fa fa-download"></i> Télécharger le modèle
                            </a>
                        </form>
                    </div>
                </div>
                <p class="center-align" style="margin-top:12px;">
                    <a class="waves-effect waves-light btn modal-trigger white purple-text text-darken-2" style="border:1px solid #270345;" href="#modal-guide-colonnes">
                        <i class="fa fa-book"></i> Guide des colonnes
                    </a>
                </p>
            </div>
            <div class="col s12 l7">
                <div class="card ripa-mz-card white">
                    <div class="card-content">
                        <h2><i class="fa fa-list-alt"></i> Aperçu du guide</h2>
                        <p class="grey-text text-darken-1">La première ligne du fichier doit reprendre ces intitulés (ou synonymes : voir modale « Guide »).</p>
                        <div class="responsive-table" style="max-height:420px;overflow:auto;">
                            <table class="striped ripa-guide-table">
                                <thead>
                                    <tr>
                                        <th>Colonne</th>
                                        <th>Obligatoire</th>
                                        <th>Exemple</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($column_guide as $col) { ?>
                                        <tr>
                                            <td><code><?php echo htmlspecialchars($col['key'], ENT_QUOTES, 'UTF-8'); ?></code><br /><span class="grey-text"><?php echo htmlspecialchars($col['label'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                            <td><?php echo !empty($col['required']) ? '<span class="red-text text-darken-2">Oui</span>' : 'Non'; ?></td>
                                            <td><small><?php echo htmlspecialchars($col['example'], ENT_QUOTES, 'UTF-8'); ?></small></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-guide-colonnes" class="modal ripa-modal-guide">
        <div class="modal-content">
            <h4>Guide d’import employés</h4>
            <p class="grey-text text-darken-1">Utilisez la <strong>première ligne</strong> pour les en-têtes. Les noms suivants sont reconnus (insensible à la casse, accents tolérés) :</p>
            <ul class="browser-default">
                <li><strong>nom</strong> — obligatoire (syn. <em>nom de famille</em>, <em>lastname</em>)</li>
                <li><strong>prenom</strong> — obligatoire (syn. <em>prénom</em>, <em>firstname</em>)</li>
                <li><strong>email</strong>, <strong>telephone</strong>, <strong>poste</strong> — optionnels</li>
                <li><strong>matricule</strong>, <strong>departement</strong>, <strong>date_entree</strong>, <strong>ville</strong>, <strong>pays</strong> — optionnels</li>
                <li><strong>actif</strong> — 1 / oui / 0 / non (vide = actif)</li>
            </ul>
            <p><strong>Date d’entrée :</strong> AAAA-MM-JJ, JJ/MM/AAAA ou date native Excel.</p>
            <p class="grey-text small">Les lignes entièrement vides sont ignorées. En cas d’erreur sur une ligne, les autres lignes valides sont tout de même importées.</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Fermer</a>
            <a href="<?php echo site_url('business/employes/modele-excel'); ?>" class="waves-effect btn-flat purple-text text-darken-2"><i class="fa fa-download"></i> Modèle</a>
        </div>
    </div>
</main>
<script src="<?php echo base_url('assets/dore_assets/js/libs/jquery-3.1.1.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/materialize/js/materialize.min.js'); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof M !== 'undefined') {
            M.AutoInit();
        }
    });
</script>
</body>
</html>
