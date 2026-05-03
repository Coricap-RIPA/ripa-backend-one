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
<?php
$this->load->view('business/partials/nav_portal');
$postes_ref = isset($postes_ref) && is_array($postes_ref) ? $postes_ref : array();
$sel_ref = '';
$poste_libre_val = '';
$poste_autre_val = '';
$id_autre_ref = '';
foreach ($postes_ref as $_pr) {
    if (!empty($_pr['is_autre'])) {
        $id_autre_ref = (string) (int) $_pr['id'];
        break;
    }
}
if (!empty($row)) {
    if (!empty($row['id_ref_poste_fonction'])) {
        $sel_ref = (string) (int) $row['id_ref_poste_fonction'];
        if (!empty($row['ref_poste_is_autre'])) {
            $poste_autre_val = isset($row['poste']) ? (string) $row['poste'] : '';
        }
    } elseif (!empty($row['poste'])) {
        $sel_ref = '-1';
        $poste_libre_val = (string) $row['poste'];
    }
}
$phone_lookup_url = site_url('business/employes/telephone-app-lookup');
?>
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
                <input class="form-control" name="telephone" id="employe_telephone" autocomplete="tel" value="<?php echo htmlspecialchars($row['telephone'] ?? ''); ?>" />
                <div id="employe_phone_ripa_hint" class="small mt-1" style="display:none;" role="status"></div>
            </div>
            <div class="form-group">
                <label>Poste / fonction *</label>
                <select class="form-control" name="id_ref_poste_fonction" id="id_ref_poste_fonction" required>
                    <option value="">— Choisir —</option>
                    <option value="-1" <?php echo $sel_ref === '-1' ? 'selected' : ''; ?>>— Libellé libre (hors liste, ex. import) —</option>
                    <?php foreach ($postes_ref as $pr) {
                        $pid = (int) $pr['id'];
                        $plab = htmlspecialchars($pr['libelle']);
                        ?>
                        <option value="<?php echo $pid; ?>" <?php echo $sel_ref !== '' && (string) $pid === $sel_ref ? 'selected' : ''; ?>><?php echo $plab; ?></option>
                    <?php } ?>
                </select>
                <div id="wrap_poste_libre" class="mt-2" style="<?php echo $sel_ref === '-1' ? '' : 'display:none;'; ?>">
                    <label class="small text-muted">Précision (saisie libre)</label>
                    <input class="form-control" name="poste_libre" id="poste_libre" value="<?php echo htmlspecialchars($poste_libre_val); ?>" placeholder="Ex. Chef de projet digital" />
                </div>
                <div id="wrap_poste_autre" class="mt-2" style="display:none;">
                    <label class="small text-muted">Précisez la fonction (« Autre »)</label>
                    <input class="form-control" name="poste_autre" id="poste_autre" value="<?php echo htmlspecialchars($poste_autre_val); ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Matricule / réf. interne</label>
                    <input class="form-control" name="matricule" maxlength="64" value="<?php echo htmlspecialchars($row['matricule'] ?? ''); ?>" placeholder="Ex. EMP-001" />
                </div>
                <div class="form-group col-md-6">
                    <label>Service (liste des services du portail)</label>
                    <select class="form-control" name="id_service" id="id_service">
                        <option value="">— Aucun —</option>
                        <?php
                        $services_list = isset($services) ? $services : array();
                        $sel_svc = isset($row['id_service']) ? (int) $row['id_service'] : 0;
                        foreach ($services_list as $svc) {
                            $sid = (int) $svc['id'];
                            $label = htmlspecialchars($svc['libelle']);
                            if (empty($svc['actif'])) {
                                $label .= ' (inactif)';
                            }
                            ?>
                            <option value="<?php echo $sid; ?>" <?php echo $sel_svc === $sid ? 'selected' : ''; ?>><?php echo $label; ?></option>
                        <?php } ?>
                    </select>
                    <?php if (!empty($row['departement']) && empty($row['id_service'])) { ?>
                        <small class="form-text text-muted">Texte issu d’un import ou ancien saisi : <strong><?php echo htmlspecialchars($row['departement']); ?></strong>. Choisissez un service ci-dessus pour le remplacer par un rattachement structuré.</small>
                    <?php } elseif (empty($services_list)) { ?>
                        <small class="form-text text-muted">Aucun service défini. <a href="<?php echo site_url('business/services/ajouter'); ?>">Créer un service</a> pour pouvoir rattacher l’employé.</small>
                    <?php } ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Date d’entrée</label>
                    <input class="form-control" type="date" name="date_entree" value="<?php echo htmlspecialchars($row['date_entree'] ?? ''); ?>" />
                </div>
                <div class="form-group col-md-4">
                    <label>Ville</label>
                    <input class="form-control" name="ville" maxlength="120" value="<?php echo htmlspecialchars($row['ville'] ?? ''); ?>" />
                </div>
                <div class="form-group col-md-4">
                    <label>Pays (code ISO, ex. CD)</label>
                    <input class="form-control" name="pays" maxlength="3" value="<?php echo htmlspecialchars($row['pays'] ?? ''); ?>" />
                </div>
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
<script>
(function () {
    var idAutre = <?php echo json_encode($id_autre_ref); ?>;
    var sel = document.getElementById('id_ref_poste_fonction');
    var wrapLibre = document.getElementById('wrap_poste_libre');
    var wrapAutre = document.getElementById('wrap_poste_autre');
    function syncPosteFields() {
        if (!sel) return;
        var v = sel.value;
        if (wrapLibre) wrapLibre.style.display = (v === '-1') ? '' : 'none';
        if (wrapAutre) wrapAutre.style.display = (idAutre && v === idAutre) ? '' : 'none';
    }
    if (sel) {
        sel.addEventListener('change', syncPosteFields);
        syncPosteFields();
    }
    var phoneInput = document.getElementById('employe_telephone');
    var hint = document.getElementById('employe_phone_ripa_hint');
    var lookupUrl = <?php echo json_encode($phone_lookup_url); ?>;
    var tmo;
    function setHint(html, isInfo) {
        if (!hint) return;
        if (!html) {
            hint.style.display = 'none';
            hint.innerHTML = '';
            return;
        }
        hint.style.display = '';
        hint.className = 'small mt-1 ' + (isInfo ? 'text-info' : 'text-muted');
        hint.innerHTML = html;
    }
    function runLookup() {
        if (!phoneInput || !hint) return;
        var q = (phoneInput.value || '').trim();
        if (q.length < 8) {
            setHint('');
            return;
        }
        var url = lookupUrl + (lookupUrl.indexOf('?') >= 0 ? '&' : '?') + 'q=' + encodeURIComponent(q);
        fetch(url, { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data || !data.match) {
                    setHint('');
                    return;
                }
                var msg = '<i class="fa fa-mobile"></i> Ce numéro correspond à un utilisateur de l’<strong>application RIPA</strong>. '
                    + 'Un même utilisateur peut avoir <strong>plusieurs fiches employé</strong> dans des entreprises différentes — utile pour les versements vers wallet, carte, compte ou mobile money.';
                if (data.count > 1) {
                    msg += ' Plusieurs comptes correspondent à ce numéro : la liaison automatique à l’enregistrement peut être impossible.';
                }
                setHint(msg, true);
            })
            .catch(function () { setHint(''); });
    }
    if (phoneInput) {
        phoneInput.addEventListener('input', function () {
            clearTimeout(tmo);
            tmo = setTimeout(runLookup, 450);
        });
        phoneInput.addEventListener('blur', function () {
            clearTimeout(tmo);
            runLookup();
        });
        if ((phoneInput.value || '').trim().length >= 8) {
            runLookup();
        }
    }
})();
</script>
</body>
</html>
