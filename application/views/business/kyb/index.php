<?php
$d = $dossier;
$m = is_array($marchand) ? $marchand : array();
$statut = $d ? $d['statut'] : '';
$form_d = $d ? $d : array();
$prefill_nom = $d ? $d['denomination_sociale'] : ($m['raison_sociale'] ?? '');
$prefill_tel = $d ? $d['telephone'] : ($m['telephone_contact'] ?? '');
$prefill_email = $d ? $d['email_contact'] : ($m['email_contact'] ?? '');
$kyb_num = isset($form_d['numero_identification_legal']) ? trim((string) $form_d['numero_identification_legal']) : '';
$march_rccm = isset($m['identifiant_legal']) ? trim((string) $m['identifiant_legal']) : '';
$prefill_numero_legal = $kyb_num !== '' ? $kyb_num : $march_rccm;
$meta = isset($kyb_meta) && is_array($kyb_meta) ? $kyb_meta : array();
if (array_key_exists('portal_may_submit_kyb', $meta)) {
    $portal_may_submit = !empty($meta['portal_may_submit_kyb']);
} else {
    $portal_may_submit = !$d || $statut === 'rejete';
}
$kyb_expired = !empty($meta['kyb_expired']);
$date_fin_meta = isset($meta['date_fin_validite']) ? $meta['date_fin_validite'] : null;
$id_m_session = (int) $this->session->userdata('business_marchand_id');
$url_piece_legal = '';
$url_piece_compl = '';
if ($d && $id_m_session > 0) {
    if (!empty($d['fichier_piece_legal']) && ripa_kyb_storage_resolve_full_path((string) $d['fichier_piece_legal'], $id_m_session)) {
        $url_piece_legal = site_url('business/kyb/piece/legal');
    }
    if (!empty($d['fichier_piece_complement']) && ripa_kyb_storage_resolve_full_path((string) $d['fichier_piece_complement'], $id_m_session)) {
        $url_piece_compl = site_url('business/kyb/piece/complement');
    }
}
$kind_piece_legal = $d ? ripa_kyb_storage_file_kind((string) ($d['fichier_piece_legal'] ?? '')) : 'none';
$kind_piece_compl = $d ? ripa_kyb_storage_file_kind((string) ($d['fichier_piece_complement'] ?? '')) : 'none';
$show_portal_preview = ($url_piece_legal !== '' || $url_piece_compl !== '') && $d && in_array($statut, array('en_attente', 'valide', 'rejete'), true);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dossier KYB — Portail marchand</title>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/dore_assets/img/favicon.png'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome-4.7.0/css/font-awesome.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/materialize/css/materialize.min.css'); ?>" />
    <?php $this->load->view('business/partials/portal_shell_styles'); ?>
    <style>
        .ripa-kyb-wrap .container { max-width: 900px; width: 92%; }
        .ripa-kyb-card { border-radius: 14px; border-top: 4px solid #270345; box-shadow: 0 4px 24px rgba(39, 3, 69, 0.08); overflow: hidden; margin-bottom: 1.25rem; }
        .ripa-kyb-card .card-content { padding: 1.5rem; }
        .ripa-kyb-card h2 { font-size: 1.15rem; color: #270345; font-weight: 700; margin: 0 0 1rem; }
        .btn-ripa-kyb { background-color: #270345 !important; }
        .btn-ripa-kyb:hover { background-color: #3d0f5c !important; }
        .file-field .btn { background-color: #270345; }
        .input-field label { color: #5c4a6e; }
        .ripa-kyb-badge { display: inline-block; padding: 0.25rem 0.65rem; border-radius: 8px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; }
        .ripa-kyb-badge-attente { background: #fff3e0; color: #e65100; }
        .ripa-kyb-badge-valide { background: #e8f5e9; color: #1b5e20; }
        .ripa-kyb-badge-rejete { background: #ffebee; color: #b71c1c; }
        .ripa-kyb-preview-card { border-radius: 14px; border-top: 4px solid #5e35b1; box-shadow: 0 4px 24px rgba(39, 3, 69, 0.08); margin-bottom: 1.25rem; }
        .ripa-kyb-preview-card .card-content { padding: 1.25rem; }
        .ripa-kyb-readonly-dl dt { font-weight: 600; color: #5c4a6e; margin-top: 8px; }
        .ripa-kyb-readonly-dl dd { margin: 0 0 4px 0; }
    </style>
</head>
<body class="ripa-portal-app ripa-kyb-wrap">
<?php $this->load->view('business/partials/nav_portal'); ?>
<main class="ripa-portal-main">
    <div class="container">
        <div class="row" style="margin-bottom:8px;">
            <div class="col s12">
                <h1 style="color:#270345;font-weight:700;font-size:1.5rem;margin:0 0 4px;"><i class="fa fa-building"></i> Know Your Business (KYB)</h1>
                <p class="grey-text text-darken-1" style="margin:0;">Informations sur votre entreprise pour la conformité RIPA. Ne transmettez pas de données de carte bancaire dans ce formulaire.</p>
            </div>
        </div>

        <?php
        $flash_ok = ripa_session_consume_flashdata('kyb_message');
        $flash_err = ripa_session_consume_flashdata('kyb_errors');
        ?>
        <?php if ($flash_ok) { ?>
            <div class="card-panel teal lighten-4 teal-text text-darken-3" style="border-radius:12px;" role="status">
                <?php echo htmlspecialchars((string) $flash_ok, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } ?>
        <?php if ($flash_err) { ?>
            <div class="card-panel red lighten-4 red-text text-darken-2" style="border-radius:12px;" role="alert">
                <?php echo $flash_err; ?>
            </div>
        <?php } ?>

        <?php if ($d && $statut === 'valide' && !$kyb_expired && $show_portal_preview) { ?>
            <div class="row" style="margin-bottom:0;">
                <div class="col s12 m6">
                    <div class="card ripa-kyb-card white">
                        <div class="card-content">
                            <h2 style="font-size:1.05rem;">Dossier validé (lecture seule)</h2>
                            <dl class="ripa-kyb-readonly-dl">
                                <dt>Dénomination</dt><dd><?php echo htmlspecialchars((string) ($d['denomination_sociale'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></dd>
                                <dt>N° identification légale</dt><dd><?php echo htmlspecialchars((string) ($d['numero_identification_legal'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></dd>
                                <dt>Adresse du siège</dt><dd><?php echo nl2br(htmlspecialchars((string) ($d['adresse_siege'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></dd>
                                <dt>Téléphone / Email</dt><dd><?php echo htmlspecialchars((string) ($d['telephone'] ?? ''), ENT_QUOTES, 'UTF-8'); ?> — <?php echo htmlspecialchars((string) ($d['email_contact'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></dd>
                                <?php if (!empty($d['ville']) || !empty($d['pays'])) { ?>
                                    <dt>Ville / Pays</dt><dd><?php echo htmlspecialchars(trim((string) ($d['ville'] ?? '') . ' / ' . trim((string) ($d['pays'] ?? ''))), ENT_QUOTES, 'UTF-8'); ?></dd>
                                <?php } ?>
                            </dl>
                            <p class="grey-text small" style="margin-top:12px;">Les modifications d’un dossier déjà approuvé passent par RIPA (support). Renouvellement annuel avant échéance.</p>
                        </div>
                    </div>
                </div>
                <div class="col s12 m6">
                    <?php $this->load->view('business/kyb/_preview_pieces', array(
                        'url_legal' => $url_piece_legal,
                        'url_compl' => $url_piece_compl,
                        'kind_legal' => $kind_piece_legal,
                        'kind_compl' => $kind_piece_compl,
                    )); ?>
                </div>
            </div>
        <?php } ?>

        <?php if ($d && $statut === 'en_attente') { ?>
            <div class="card ripa-kyb-card white">
                <div class="card-content">
                    <h2>Statut du dossier <span class="ripa-kyb-badge ripa-kyb-badge-attente">En vérification</span></h2>
                    <p>Votre dossier a été soumis le <strong><?php echo htmlspecialchars($d['date_soumission'] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong>. Vous serez notifié après traitement par l’équipe RIPA.</p>
                    <p class="grey-text small">Dénomination : <?php echo htmlspecialchars($d['denomination_sociale'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>
        <?php } elseif ($d && $statut === 'valide' && !$kyb_expired) { ?>
            <?php if ($show_portal_preview) { ?>
            <div class="card ripa-kyb-card white" style="margin-bottom:12px;">
                <div class="card-content" style="padding-bottom:8px;">
                    <h2 style="margin-bottom:4px;">Dossier validé <span class="ripa-kyb-badge ripa-kyb-badge-valide">Validé</span></h2>
                    <p style="margin:0;">Merci : votre dossier KYB est <strong>validé</strong><?php if (!empty($d['date_decision'])) { ?> depuis le <?php echo htmlspecialchars($d['date_decision'], ENT_QUOTES, 'UTF-8'); ?><?php } ?>.</p>
                    <?php if ($date_fin_meta) { ?>
                        <p class="grey-text small" style="margin-top:8px;">Fin de validité : <strong><?php echo htmlspecialchars((string) $date_fin_meta, ENT_QUOTES, 'UTF-8'); ?></strong> (renouvellement annuel).</p>
                    <?php } ?>
                </div>
            </div>
            <?php } else { ?>
            <div class="card ripa-kyb-card white">
                <div class="card-content">
                    <h2>Dossier validé <span class="ripa-kyb-badge ripa-kyb-badge-valide">Validé</span></h2>
                    <p>Merci : votre dossier KYB est <strong>validé</strong><?php if (!empty($d['date_decision'])) { ?> depuis le <?php echo htmlspecialchars($d['date_decision'], ENT_QUOTES, 'UTF-8'); ?><?php } ?>.</p>
                    <?php if ($date_fin_meta) { ?>
                        <p class="grey-text small">Fin de validité du dossier : <strong><?php echo htmlspecialchars((string) $date_fin_meta, ENT_QUOTES, 'UTF-8'); ?></strong> (renouvellement annuel).</p>
                    <?php } ?>
                    <p class="grey-text small" style="margin-top:12px;">La consultation et la modification d’un dossier <strong>déjà approuvé</strong> s’effectuent côté RIPA (back-office). Pour toute correction, contactez le support.</p>
                </div>
            </div>
            <?php } ?>
        <?php } elseif ($d && $statut === 'valide' && $kyb_expired) { ?>
            <div class="card ripa-kyb-card white">
                <div class="card-content">
                    <h2>Renouvellement requis <span class="ripa-kyb-badge ripa-kyb-badge-attente">Validité dépassée</span></h2>
                    <p>La période de validité de votre dossier KYB est <strong>terminée</strong>. Vous devez soumettre un dossier de <strong>renouvellement</strong> (même formulaire ci-dessous).</p>
                    <?php if ($date_fin_meta) { ?>
                        <p class="grey-text small">Échéance passée : <?php echo htmlspecialchars((string) $date_fin_meta, ENT_QUOTES, 'UTF-8'); ?>.</p>
                    <?php } ?>
                </div>
            </div>
        <?php } elseif ($d && $statut === 'rejete') { ?>
            <div class="card ripa-kyb-card white">
                <div class="card-content">
                    <h2>Dossier à corriger <span class="ripa-kyb-badge ripa-kyb-badge-rejete">Rejeté</span></h2>
                    <p><strong>Motif :</strong> <?php echo nl2br(htmlspecialchars($d['motif_refus'] ?? '', ENT_QUOTES, 'UTF-8')); ?></p>
                    <p class="grey-text small">Modifiez les informations ci-dessous et renvoyez le dossier, ou supprimez le dossier pour repartir à zéro.</p>
                    <?php if (!empty($can_write)) { ?>
                    <form method="post" action="<?php echo site_url('business/kyb/supprimer'); ?>" style="margin-top:12px;" onsubmit="return confirm('Supprimer définitivement ce dossier rejeté et les pièces jointes ? Cette action est irréversible.');">
                        <?php echo ripa_portal_csrf_field(); ?>
                        <button type="submit" class="btn-flat red-text text-darken-2" style="border:1px solid #e57373;border-radius:8px;">
                            <i class="fa fa-trash"></i> Supprimer le dossier et repartir à zéro
                        </button>
                    </form>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <?php if ($show_portal_preview && !($d && $statut === 'valide' && !$kyb_expired)) { ?>
            <div class="row"><div class="col s12">
                <?php $this->load->view('business/kyb/_preview_pieces', array(
                    'url_legal' => $url_piece_legal,
                    'url_compl' => $url_piece_compl,
                    'kind_legal' => $kind_piece_legal,
                    'kind_compl' => $kind_piece_compl,
                )); ?>
            </div></div>
        <?php } ?>

        <?php
        $show_form = $portal_may_submit;
        $readonly_form = empty($can_write);
        if ($show_form && !$readonly_form) {
            ?>
            <div class="card ripa-kyb-card white">
                <div class="card-content">
                    <h2><?php
                        if (!$d) {
                            echo 'Soumettre votre dossier KYB';
                        } elseif ($kyb_expired) {
                            echo 'Renouvellement du dossier KYB';
                        } else {
                            echo 'Mettre à jour le dossier';
                        }
                    ?></h2>
                    <form method="post" action="<?php echo site_url('business/kyb/soumettre'); ?>" enctype="multipart/form-data">
                        <?php echo ripa_portal_csrf_field(); ?>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <label for="kyb_denomination" class="active">Dénomination sociale *</label>
                                <input id="kyb_denomination" type="text" name="denomination_sociale" required maxlength="255"
                                    value="<?php echo htmlspecialchars($prefill_nom, ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                            <div class="input-field col s12 m6">
                                <label for="kyb_numero" class="active">N° identification légale *</label>
                                <input id="kyb_numero" type="text" name="numero_identification_legal" required maxlength="120"
                                    placeholder="RCCM, NINEA, etc."
                                    value="<?php echo htmlspecialchars($prefill_numero_legal, ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <label for="kyb_adresse" class="active">Adresse du siège *</label>
                                <textarea id="kyb_adresse" name="adresse_siege" class="materialize-textarea" required style="min-height:72px;border:1px solid #ddd;border-radius:8px;padding:12px;"><?php echo htmlspecialchars($form_d['adresse_siege'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m4">
                                <label for="kyb_ville" class="active">Ville</label>
                                <input id="kyb_ville" type="text" name="ville" maxlength="120" value="<?php echo htmlspecialchars($form_d['ville'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                            <div class="input-field col s12 m4">
                                <label for="kyb_pays" class="active">Pays (code ISO, ex. CD)</label>
                                <input id="kyb_pays" type="text" name="pays" maxlength="3" value="<?php echo htmlspecialchars($form_d['pays'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                            <div class="input-field col s12 m4">
                                <label for="kyb_tel" class="active">Téléphone *</label>
                                <input id="kyb_tel" type="text" name="telephone" required maxlength="50" value="<?php echo htmlspecialchars($prefill_tel, ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <label for="kyb_email" class="active">Email de contact *</label>
                                <input id="kyb_email" type="email" name="email_contact" required maxlength="255" value="<?php echo htmlspecialchars($prefill_email, ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                            <div class="input-field col s12 m6">
                                <label for="kyb_web" class="active">Site web</label>
                                <input id="kyb_web" type="text" name="site_web" maxlength="255" value="<?php echo htmlspecialchars($form_d['site_web'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <label for="kyb_act" class="active">Activité principale</label>
                                <input id="kyb_act" type="text" name="activite_principale" maxlength="255" value="<?php echo htmlspecialchars($form_d['activite_principale'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                            <div class="input-field col s12 m6">
                                <label for="kyb_eff" class="active">Tranche d’effectif</label>
                                <input id="kyb_eff" type="text" name="effectif_tranche" maxlength="50" placeholder="ex. 1-10, 11-50"
                                    value="<?php echo htmlspecialchars($form_d['effectif_tranche'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <label for="kyb_com" class="active">Commentaire (optionnel)</label>
                                <textarea id="kyb_com" name="commentaire_marchand" class="materialize-textarea" style="min-height:60px;border:1px solid #ddd;border-radius:8px;padding:12px;"><?php echo htmlspecialchars($form_d['commentaire_marchand'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6">
                                <p class="grey-text text-darken-1 small" style="margin:0 0 8px;">Pièce d’identité légale (PDF, JPG, PNG — max. 3 Mo)</p>
                                <?php if (!empty($form_d['fichier_piece_legal'])) { ?>
                                    <p class="small green-text text-darken-2">Fichier actuel enregistré. Choisissez un nouveau fichier pour le remplacer.</p>
                                <?php } ?>
                                <div class="file-field input-field">
                                    <div class="btn"><span>Fichier</span><input type="file" name="piece_legal" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/*"></div>
                                    <div class="file-path-wrapper"><input class="file-path validate" type="text" placeholder="Optionnel"></div>
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <p class="grey-text text-darken-1 small" style="margin:0 0 8px;">Document complémentaire (optionnel)</p>
                                <?php if (!empty($form_d['fichier_piece_complement'])) { ?>
                                    <p class="small green-text text-darken-2">Fichier actuel enregistré. Nouveau fichier = remplacement.</p>
                                <?php } ?>
                                <div class="file-field input-field">
                                    <div class="btn"><span>Fichier</span><input type="file" name="piece_complement" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/*"></div>
                                    <div class="file-path-wrapper"><input class="file-path validate" type="text" placeholder="Optionnel"></div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn waves-effect waves-light btn-ripa-kyb" style="margin-top:16px;">
                            <?php echo $d ? 'Renvoyer le dossier' : 'Soumettre le dossier'; ?> <i class="fa fa-paper-plane right" style="margin-left:8px;"></i>
                        </button>
                    </form>
                </div>
            </div>
        <?php } elseif ($show_form && $readonly_form) { ?>
            <div class="card-panel amber lighten-4 brown-text text-darken-2" style="border-radius:12px;">
                Votre profil <strong>lecture seule</strong> ne permet pas de soumettre le dossier KYB. Contactez un administrateur du portail.
            </div>
        <?php } ?>
    </div>
</main>
<script src="<?php echo base_url('assets/dore_assets/js/libs/jquery-3.1.1.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/materialize/js/materialize.min.js'); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof M !== 'undefined') { M.AutoInit(); }
    });
</script>
</body>
</html>
