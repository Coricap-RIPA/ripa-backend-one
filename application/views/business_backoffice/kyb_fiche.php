<?php
echo $header;
echo $footer;
$d = isset($dossier) ? $dossier : array();
$can_edit = !empty($can_edit);
$dfv = isset($date_fin_validite) ? $date_fin_validite : null;
$ro = $can_edit ? '' : ' readonly';
$bo_flash = ripa_session_consume_flashdata('message');
$id_kyb = (int) ($d['id'] ?? 0);
$url_legal = isset($kyb_preview_legal_url) ? (string) $kyb_preview_legal_url : '';
$url_compl = isset($kyb_preview_complement_url) ? (string) $kyb_preview_complement_url : '';
$kind_legal = isset($kyb_preview_legal_kind) ? (string) $kyb_preview_legal_kind : 'none';
$kind_compl = isset($kyb_preview_complement_kind) ? (string) $kyb_preview_complement_kind : 'none';
$has_preview = ($url_legal !== '' || $url_compl !== '');
?>
<body style="background-color: whitesmoke;">
    <div id="load_screen" class="loader" style="display: none;"></div>
    <div class="main_container">
        <?php echo $navbar; ?>
        <div id="row_content" class="row">
            <div class="row col s12">
                <fieldset>
                    <legend>Dossier KYB #<?php echo $id_kyb; ?></legend>
                </fieldset>
            </div>
            <?php if ($bo_flash) { ?>
                <div class="col s12" role="status">
                    <div class="card-panel green lighten-4 green-text text-darken-2"><?php echo $bo_flash; ?></div>
                </div>
            <?php } ?>
            <div class="col s12">
                <p class="grey-text">
                    Statut : <strong><?php echo htmlspecialchars((string) ($d['statut'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                    <?php if ($dfv !== null && $dfv !== '' && ($d['statut'] ?? '') === 'valide') { ?>
                        — fin de validité : <strong><?php echo htmlspecialchars($dfv, ENT_QUOTES, 'UTF-8'); ?></strong>
                    <?php } ?>
                </p>
                <?php if (($d['statut'] ?? '') === 'rejete' && !empty($d['motif_refus'])) { ?>
                    <div class="card-panel orange lighten-4 orange-text text-darken-3" style="border-radius:8px;">
                        <strong>Motif du refus (marchand) :</strong><br /><?php echo nl2br(htmlspecialchars((string) $d['motif_refus'], ENT_QUOTES, 'UTF-8')); ?>
                    </div>
                <?php } ?>
                <a class="btn btn-small grey" href="<?php echo site_url('Kyb_backoffice/index'); ?>">← Liste KYB</a>
            </div>

            <div class="col s12 l7" style="margin-top:16px;">
                <div class="card-panel white" style="border-top:4px solid #270345;border-radius:8px;">
                    <?php if ($can_edit) { ?>
                    <form method="post" action="<?php echo site_url('Kyb_backoffice/update/' . $id_kyb); ?>">
                        <?php echo ripa_bo_csrf_field(); ?>
                    <?php } ?>
                        <div class="input-field" style="margin-top:0;">
                            <label class="active">Dénomination sociale *</label>
                            <input type="text" name="denomination_sociale" required maxlength="255" class="validate"<?php echo $ro; ?>
                                value="<?php echo htmlspecialchars((string) ($d['denomination_sociale'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                        <div class="input-field">
                            <label class="active">N° identification légale *</label>
                            <input type="text" name="numero_identification_legal" required maxlength="120"<?php echo $ro; ?>
                                value="<?php echo htmlspecialchars((string) ($d['numero_identification_legal'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                        <div class="input-field">
                            <label class="active">Adresse du siège *</label>
                            <textarea name="adresse_siege" class="materialize-textarea" required style="min-height:80px;border:1px solid #ddd;border-radius:6px;padding:10px;width:100%;box-sizing:border-box;"<?php echo $ro; ?>><?php echo htmlspecialchars((string) ($d['adresse_siege'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="input-field">
                            <label class="active">Ville</label>
                            <input type="text" name="ville" maxlength="120"<?php echo $ro; ?>
                                value="<?php echo htmlspecialchars((string) ($d['ville'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                        <div class="input-field">
                            <label class="active">Pays (code ISO)</label>
                            <input type="text" name="pays" maxlength="3"<?php echo $ro; ?>
                                value="<?php echo htmlspecialchars((string) ($d['pays'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                        <div class="input-field">
                            <label class="active">Téléphone *</label>
                            <input type="text" name="telephone" required maxlength="50"<?php echo $ro; ?>
                                value="<?php echo htmlspecialchars((string) ($d['telephone'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                        <div class="input-field">
                            <label class="active">Email *</label>
                            <input type="email" name="email_contact" required maxlength="255"<?php echo $ro; ?>
                                value="<?php echo htmlspecialchars((string) ($d['email_contact'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                        <div class="input-field">
                            <label class="active">Site web</label>
                            <input type="text" name="site_web" maxlength="255"<?php echo $ro; ?>
                                value="<?php echo htmlspecialchars((string) ($d['site_web'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                        <div class="input-field">
                            <label class="active">Activité principale</label>
                            <input type="text" name="activite_principale" maxlength="255"<?php echo $ro; ?>
                                value="<?php echo htmlspecialchars((string) ($d['activite_principale'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                        <div class="input-field">
                            <label class="active">Tranche d’effectif</label>
                            <input type="text" name="effectif_tranche" maxlength="50"<?php echo $ro; ?>
                                value="<?php echo htmlspecialchars((string) ($d['effectif_tranche'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                        <div class="input-field">
                            <label class="active">Commentaire marchand</label>
                            <textarea name="commentaire_marchand" class="materialize-textarea" style="min-height:60px;border:1px solid #ddd;border-radius:6px;padding:10px;width:100%;box-sizing:border-box;"<?php echo $ro; ?>><?php echo htmlspecialchars((string) ($d['commentaire_marchand'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <p class="grey-text small">Pièces jointes : prévisualisation à droite (accès sécurisé).</p>
                        <ul class="collection">
                            <li class="collection-item">Pièce légale : <?php echo !empty($d['fichier_piece_legal']) ? htmlspecialchars((string) $d['fichier_piece_legal'], ENT_QUOTES, 'UTF-8') : '—'; ?></li>
                            <li class="collection-item">Complément : <?php echo !empty($d['fichier_piece_complement']) ? htmlspecialchars((string) $d['fichier_piece_complement'], ENT_QUOTES, 'UTF-8') : '—'; ?></li>
                        </ul>
                    <?php if ($can_edit) { ?>
                        <button type="submit" class="btn purple">Enregistrer</button>
                    </form>
                    <?php } ?>
                </div>
            </div>

            <?php if ($has_preview) { ?>
            <div class="col s12 l5" style="margin-top:16px;">
                <div class="card-panel white" style="border-top:4px solid #5e35b1;border-radius:8px;position:sticky;top:12px;">
                    <h6 style="margin-top:0;color:#270345;font-weight:700;">Prévisualisation</h6>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px;">
                        <?php if ($url_legal !== '') { ?>
                            <button type="button" class="btn btn-small purple ripa-kyb-tab" data-target="legal">Pièce légale</button>
                        <?php } ?>
                        <?php if ($url_compl !== '') { ?>
                            <button type="button" class="btn btn-small grey ripa-kyb-tab" data-target="complement">Complément</button>
                        <?php } ?>
                    </div>
                    <div id="ripa-kyb-viewer-wrap" style="background:#f5f5f5;border-radius:8px;min-height:420px;overflow:hidden;border:1px solid #e0e0e0;">
                        <iframe id="ripa-kyb-iframe" title="Prévisualisation PDF" style="width:100%;height:68vh;border:0;display:none;"></iframe>
                        <img id="ripa-kyb-img" alt="Prévisualisation" style="max-width:100%;height:auto;display:none;vertical-align:top;" />
                        <p id="ripa-kyb-viewer-empty" class="grey-text" style="padding:16px;display:none;">Aucun aperçu.</p>
                    </div>
                    <p class="grey-text small" style="margin-top:8px;">Les fichiers sont servis via une URL authentifiée (session back-office).</p>
                </div>
            </div>
            <?php } ?>

            <div class="col s12" style="display: none;"><?php echo isset($output) ? $output : ''; ?></div>
        </div>
    </div>
    <script>
(function () {
    var uLegal = <?php echo json_encode($url_legal); ?>;
    var uCompl = <?php echo json_encode($url_compl); ?>;
    var kLegal = <?php echo json_encode($kind_legal); ?>;
    var kCompl = <?php echo json_encode($kind_compl); ?>;
    var iframe = document.getElementById('ripa-kyb-iframe');
    var img = document.getElementById('ripa-kyb-img');
    var empty = document.getElementById('ripa-kyb-viewer-empty');
    if (!iframe || !img) return;

    function hideAll() {
        iframe.style.display = 'none';
        img.style.display = 'none';
        if (empty) empty.style.display = 'none';
        iframe.removeAttribute('src');
        img.removeAttribute('src');
    }

    function showTab(which) {
        var url = which === 'complement' ? uCompl : uLegal;
        var kind = which === 'complement' ? kCompl : kLegal;
        if (!url) return;
        hideAll();
        if (kind === 'pdf') {
            iframe.style.display = 'block';
            iframe.src = url;
        } else if (kind === 'image') {
            img.style.display = 'block';
            img.src = url;
        } else if (url) {
            iframe.style.display = 'block';
            iframe.src = url;
        } else if (empty) {
            empty.textContent = 'Aucun aperçu.';
            empty.style.display = 'block';
        }
    }

    document.querySelectorAll('.ripa-kyb-tab').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.ripa-kyb-tab').forEach(function (b) {
                b.classList.remove('purple');
                b.classList.add('grey');
            });
            btn.classList.remove('grey');
            btn.classList.add('purple');
            showTab(btn.getAttribute('data-target'));
        });
    });

    var first = document.querySelector('.ripa-kyb-tab[data-target="legal"]') || document.querySelector('.ripa-kyb-tab');
    if (first) first.click();
})();
    </script>
</body>
