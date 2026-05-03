<?php
echo $header;
echo $footer;
$bo_flash = ripa_session_consume_flashdata('message');
?>
<body style="background-color: whitesmoke;">
    <div id="load_screen" class="loader" style="display: none;"></div>
    <div class="main_container">
        <?php echo $navbar; ?>
        <div id="row_content" class="row">
            <div class="row col s12">
                <fieldset>
                    <legend>Dossiers KYB — Know Your Business (portail B2B)</legend>
                </fieldset>
            </div>
            <?php if ($bo_flash) { ?>
                <div class="col s12" role="status">
                    <div class="card-panel green lighten-4 green-text text-darken-2"><?php echo $bo_flash; ?></div>
                </div>
            <?php } ?>
            <div class="col s12">
                <p class="grey-text">Filtrer par statut :</p>
                <?php
                $base = site_url('Kyb_backoffice/index');
                $f = isset($filter_statut) ? $filter_statut : '';
                ?>
                <a href="<?php echo $base; ?>" class="btn btn-small <?php echo ($f === '' || $f === null) ? 'purple' : 'grey'; ?>">Tous</a>
                <a href="<?php echo $base . '?statut=en_attente'; ?>" class="btn btn-small <?php echo $f === 'en_attente' ? 'purple' : 'grey'; ?>">En attente</a>
                <a href="<?php echo $base . '?statut=valide'; ?>" class="btn btn-small <?php echo $f === 'valide' ? 'purple' : 'grey'; ?>">Validés</a>
                <a href="<?php echo $base . '?statut=rejete'; ?>" class="btn btn-small <?php echo $f === 'rejete' ? 'purple' : 'grey'; ?>">Rejetés</a>
            </div>
            <div class="col s12" style="margin-top: 20px;">
                <table class="striped bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Marchand</th>
                            <th>Dénomination (KYB)</th>
                            <th>Email dossier</th>
                            <th>Statut</th>
                            <th>Soumis le</th>
                            <th>Fin validité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($list)) { ?>
                            <tr><td colspan="8">Aucun dossier.</td></tr>
                        <?php } else { ?>
                            <?php foreach ($list as $row) { ?>
                                <tr>
                                    <td><?php echo (int) $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['marchand_raison_sociale'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['denomination_sociale'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['email_contact'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row['statut']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date_soumission'] ?? ''); ?></td>
                                    <td>
                                        <?php
                                        if (($row['statut'] ?? '') === 'valide') {
                                            $end = !empty($row['date_fin_validite'])
                                                ? (string) $row['date_fin_validite']
                                                : (!empty($row['date_decision'])
                                                    ? date('Y-m-d H:i:s', strtotime((string) $row['date_decision'] . ' +12 months'))
                                                    : '—');
                                            echo htmlspecialchars($end, ENT_QUOTES, 'UTF-8');
                                        } else {
                                            echo '—';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-small grey darken-1" href="<?php echo site_url('Kyb_backoffice/fiche/' . (int) $row['id']); ?>">Fiche</a>
                                        <?php if ($row['statut'] === 'en_attente') { ?>
                                            <form method="post" action="<?php echo site_url('Kyb_backoffice/valider/' . (int) $row['id']); ?>" style="display:inline-block;" onsubmit="return confirm('Valider ce dossier KYB ?');">
                                                <?php echo ripa_bo_csrf_field(); ?>
                                                <button type="submit" class="btn btn-small purple">Valider</button>
                                            </form>
                                            <button type="button"
                                                class="btn btn-small red lighten-2 ripa-kyb-open-refus"
                                                style="margin-left:4px;"
                                                data-id-kyb="<?php echo (int) $row['id']; ?>"
                                                data-marchand="<?php echo htmlspecialchars($row['marchand_raison_sociale'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                Refuser
                                            </button>
                                        <?php } elseif ($row['statut'] === 'rejete' && !empty($row['motif_refus'])) { ?>
                                            <?php
                                            $mot = (string) $row['motif_refus'];
                                            $mot_short = function_exists('mb_strlen') && mb_strlen($mot, 'UTF-8') > 40
                                                ? mb_substr($mot, 0, 40, 'UTF-8') . '…'
                                                : (strlen($mot) > 40 ? substr($mot, 0, 40) . '…' : $mot);
                                            ?>
                                            <span class="grey-text" title="<?php echo htmlspecialchars($mot, ENT_QUOTES, 'UTF-8'); ?>">Motif : <?php echo htmlspecialchars($mot_short, ENT_QUOTES, 'UTF-8'); ?></span>
                                        <?php } else { ?>
                                            —
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="col s12" style="display: none;"><?php echo isset($output) ? $output : ''; ?></div>
        </div>
    </div>

    <div id="ripa-kyb-modal-refus" class="ripa-bm-modal-refus" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="ripa-kyb-modal-title" aria-hidden="true">
        <div class="ripa-bm-modal-refus-dialog">
            <h5 id="ripa-kyb-modal-title" style="margin-top:0;color:#270345;font-weight:600;">Refuser le dossier KYB</h5>
            <p class="grey-text text-darken-1" style="font-size:14px;"><strong id="ripa-kyb-refus-marchand"></strong></p>
            <form method="post" action="<?php echo site_url('Kyb_backoffice/refuser'); ?>">
                <?php echo ripa_bo_csrf_field(); ?>
                <input type="hidden" name="id_kyb" id="ripa-kyb-refus-id" value="" />
                <div class="input-field" style="margin-top:1rem;">
                    <label for="ripa-kyb-refus-motif" style="position:static;font-size:13px;color:#5c4a6e;">Motif du refus *</label>
                    <textarea id="ripa-kyb-refus-motif" name="motif_refus" class="materialize-textarea" required rows="3" placeholder="Précisez la raison pour le marchand…" style="min-height:80px;border:1px solid #ccc;border-radius:4px;padding:10px;width:100%;box-sizing:border-box;"></textarea>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:flex-end;margin-top:12px;">
                    <button type="button" class="btn grey lighten-1 ripa-kyb-modal-close" style="color:#333;">Annuler</button>
                    <button type="submit" class="btn red lighten-2">Confirmer le refus</button>
                </div>
            </form>
        </div>
    </div>
    <style>
        .ripa-bm-modal-refus { position: fixed; inset: 0; z-index: 10050; background: rgba(39, 3, 69, 0.45); align-items: center; justify-content: center; padding: 16px; box-sizing: border-box; }
        .ripa-bm-modal-refus.is-open { display: flex !important; }
        .ripa-bm-modal-refus-dialog { background: #fff; border-radius: 10px; max-width: 440px; width: 100%; padding: 1.5rem; box-shadow: 0 12px 40px rgba(39, 3, 69, 0.25); border-top: 4px solid #270345; }
    </style>
    <script>
        (function () {
            var modal = document.getElementById('ripa-kyb-modal-refus');
            if (!modal) return;
            var idInput = document.getElementById('ripa-kyb-refus-id');
            var motif = document.getElementById('ripa-kyb-refus-motif');
            var marchandEl = document.getElementById('ripa-kyb-refus-marchand');
            function openModal(id, nom) {
                idInput.value = id;
                marchandEl.textContent = nom || '';
                motif.value = '';
                modal.style.display = 'flex';
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                setTimeout(function () { motif.focus(); }, 50);
            }
            function closeModal() {
                modal.style.display = 'none';
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                idInput.value = '';
                motif.value = '';
            }
            document.querySelectorAll('.ripa-kyb-open-refus').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    openModal(btn.getAttribute('data-id-kyb'), btn.getAttribute('data-marchand'));
                });
            });
            modal.querySelectorAll('.ripa-kyb-modal-close').forEach(function (el) { el.addEventListener('click', closeModal); });
            modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
            });
        })();
    </script>
</body>
