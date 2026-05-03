<?php
echo $header;
echo $footer;
?>
<body style="background-color: whitesmoke;">
    <div id="load_screen" class="loader" style="display: none;"></div>
    <div class="main_container">
        <?php echo $navbar; ?>
        <div id="row_content" class="row">
            <div class="row col s12">
                <fieldset>
                    <legend>Comptes marchands — validation portail B2B</legend>
                </fieldset>
            </div>
            <?php if (!empty($this->session->flashdata('message'))) { ?>
                <div class="col s12">
                    <div class="card-panel green lighten-4 green-text text-darken-2"><?php echo $this->session->flashdata('message'); ?></div>
                </div>
            <?php } ?>
            <div class="col s12">
                <p class="grey-text">Filtrer par statut :</p>
                <?php
                $base = site_url('Business_marchand_backoffice/index');
                $f = isset($filter_statut) ? $filter_statut : '';
                $can_editer = !empty($can_editer);
                $can_supprimer = !empty($can_supprimer);
                ?>
                <a href="<?php echo $base; ?>" class="btn btn-small <?php echo ($f === '' || $f === null) ? 'purple' : 'grey'; ?>">Tous</a>
                <a href="<?php echo $base . '?statut=en_attente_validation'; ?>" class="btn btn-small <?php echo $f === 'en_attente_validation' ? 'purple' : 'grey'; ?>">En attente</a>
                <a href="<?php echo $base . '?statut=actif'; ?>" class="btn btn-small <?php echo $f === 'actif' ? 'purple' : 'grey'; ?>">Actifs</a>
                <a href="<?php echo $base . '?statut=suspendu'; ?>" class="btn btn-small <?php echo $f === 'suspendu' ? 'purple' : 'grey'; ?>">Suspendus</a>
                <a href="<?php echo $base . '?statut=refuse'; ?>" class="btn btn-small <?php echo $f === 'refuse' ? 'purple' : 'grey'; ?>">Refusés</a>
            </div>
            <div class="col s12" style="margin-top: 20px;">
                <table class="striped bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Raison sociale</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th>Date demande</th>
                            <th>Motif refus</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($list)) { ?>
                            <tr><td colspan="8">Aucune entrée.</td></tr>
                        <?php } else { ?>
                            <?php foreach ($list as $row) { ?>
                                <tr>
                                    <td><?php echo (int) $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['raison_sociale']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email_contact']); ?></td>
                                    <td><?php echo htmlspecialchars($row['telephone_contact']); ?></td>
                                    <td><?php echo htmlspecialchars($row['statut']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date_demande'] ?? ''); ?></td>
                                    <td style="max-width:220px;font-size:12px;vertical-align:top;">
                                        <?php
                                        $mot = isset($row['motif_refus']) ? trim((string) $row['motif_refus']) : '';
                                        echo $mot !== '' ? nl2br(htmlspecialchars($mot, ENT_QUOTES, 'UTF-8')) : '—';
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($row['statut'] === 'en_attente_validation') { ?>
                                            <?php if ($can_editer) { ?>
                                                <button type="button"
                                                    class="btn btn-small purple ripa-bm-open-valider"
                                                    data-id-marchand="<?php echo (int) $row['id']; ?>"
                                                    data-raison="<?php echo htmlspecialchars($row['raison_sociale'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    data-email="<?php echo htmlspecialchars($row['email_contact'], ENT_QUOTES, 'UTF-8'); ?>">
                                                    Valider
                                                </button>
                                                <button type="button"
                                                    class="btn btn-small red lighten-2 ripa-bm-open-refus"
                                                    style="margin-left:4px;"
                                                    data-id-marchand="<?php echo (int) $row['id']; ?>"
                                                    data-raison="<?php echo htmlspecialchars($row['raison_sociale'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    data-email="<?php echo htmlspecialchars($row['email_contact'], ENT_QUOTES, 'UTF-8'); ?>">
                                                    Refuser
                                                </button>
                                            <?php } else { echo '—'; } ?>
                                        <?php } elseif ($row['statut'] === 'refuse') { ?>
                                            <?php if ($can_editer) { ?>
                                                <a class="btn btn-small grey darken-1" href="<?php echo site_url('Business_marchand_backoffice/edit/' . (int) $row['id']); ?>" style="margin-bottom:4px;">Modifier</a>
                                                <button type="button"
                                                    class="btn btn-small purple ripa-bm-open-valider"
                                                    style="margin-left:4px;"
                                                    data-id-marchand="<?php echo (int) $row['id']; ?>"
                                                    data-raison="<?php echo htmlspecialchars($row['raison_sociale'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    data-email="<?php echo htmlspecialchars($row['email_contact'], ENT_QUOTES, 'UTF-8'); ?>">
                                                    Approuver
                                                </button>
                                            <?php } ?>
                                            <?php if ($can_supprimer) { ?>
                                                <form method="post" action="<?php echo site_url('Business_marchand_backoffice/supprimer/' . (int) $row['id']); ?>" style="display:inline-block;margin-left:4px;" onsubmit="return confirm('Supprimer définitivement cette demande refusée ?');">
                                                    <?php echo ripa_bo_csrf_field(); ?>
                                                    <button type="submit" class="btn btn-small red lighten-2">Supprimer</button>
                                                </form>
                                            <?php } ?>
                                            <?php if (!$can_editer && !$can_supprimer) { echo '—'; } ?>
                                        <?php } elseif ($row['statut'] === 'actif') { ?>
                                            <?php if ($can_editer) { ?>
                                                <a class="btn btn-small grey darken-1" href="<?php echo site_url('Business_marchand_backoffice/edit/' . (int) $row['id']); ?>" style="margin-bottom:4px;">Modifier</a>
                                                <form method="post" action="<?php echo site_url('Business_marchand_backoffice/bloquer/' . (int) $row['id']); ?>" style="display:inline-block;" onsubmit="return confirm('Bloquer ce compte marchand (statut suspendu) ?');">
                                                    <?php echo ripa_bo_csrf_field(); ?>
                                                    <button type="submit" class="btn btn-small orange darken-2">Bloquer</button>
                                                </form>
                                            <?php } else { echo '—'; } ?>
                                        <?php } elseif ($row['statut'] === 'suspendu') { ?>
                                            <?php if ($can_editer) { ?>
                                                <a class="btn btn-small grey darken-1" href="<?php echo site_url('Business_marchand_backoffice/edit/' . (int) $row['id']); ?>" style="margin-bottom:4px;">Modifier</a>
                                                <form method="post" action="<?php echo site_url('Business_marchand_backoffice/debloquer/' . (int) $row['id']); ?>" style="display:inline-block;" onsubmit="return confirm('Réactiver ce compte marchand (retour au statut actif) ?');">
                                                    <?php echo ripa_bo_csrf_field(); ?>
                                                    <button type="submit" class="btn btn-small green darken-2">Réactiver</button>
                                                </form>
                                            <?php } ?>
                                            <?php if ($can_supprimer) { ?>
                                                <form method="post" action="<?php echo site_url('Business_marchand_backoffice/supprimer_actif/' . (int) $row['id']); ?>" style="display:inline-block;margin-left:4px;" onsubmit="return confirm('Supprimer définitivement ce compte suspendu ? (possible seulement si aucune activité métier n’est liée)');">
                                                    <?php echo ripa_bo_csrf_field(); ?>
                                                    <button type="submit" class="btn btn-small red lighten-2">Supprimer</button>
                                                </form>
                                            <?php } ?>
                                            <?php if (!$can_editer && !$can_supprimer) { echo '—'; } ?>
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

    <!-- Modal refus : motif saisi ici, pas dans le tableau -->
    <div id="ripa-bm-modal-refus" class="ripa-bm-modal-refus" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="ripa-bm-modal-refus-title" aria-hidden="true">
        <div class="ripa-bm-modal-refus-dialog">
            <h5 id="ripa-bm-modal-refus-title" style="margin-top:0;color:#270345;font-weight:600;">Refuser la demande</h5>
            <p class="grey-text text-darken-1" style="font-size:14px;margin-bottom:8px;">
                <strong id="ripa-bm-refus-raison"></strong><br />
                <span id="ripa-bm-refus-email"></span>
            </p>
            <p class="grey-text" style="font-size:13px;">Indiquez le motif (traçabilité du refus).</p>
            <form id="ripa-bm-form-refus" method="post" action="<?php echo site_url('Business_marchand_backoffice/refuser'); ?>">
                <?php echo ripa_bo_csrf_field(); ?>
                <input type="hidden" name="id_marchand" id="ripa-bm-refus-id-marchand" value="" />
                <div class="input-field" style="margin-top:1rem;margin-bottom:1rem;">
                    <label for="ripa-bm-refus-motif" style="position:static;transform:none;font-size:13px;color:#5c4a6e;">Motif du refus *</label>
                    <textarea id="ripa-bm-refus-motif" name="motif_refus" class="materialize-textarea validate" required rows="3" placeholder="Ex. pièces insuffisantes, doublon, secteur non éligible…" style="min-height:80px;border:1px solid #ccc;border-radius:4px;padding:10px;width:100%;box-sizing:border-box;"></textarea>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:flex-end;margin-top:12px;">
                    <button type="button" class="btn grey lighten-1 ripa-bm-modal-refus-close" style="color:#333;">Annuler</button>
                    <button type="submit" class="btn red lighten-2">Confirmer le refus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal validation : mot de passe provisoire éditable -->
    <div id="ripa-bm-modal-valider" class="ripa-bm-modal-refus" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="ripa-bm-modal-valider-title" aria-hidden="true">
        <div class="ripa-bm-modal-refus-dialog">
            <h5 id="ripa-bm-modal-valider-title" style="margin-top:0;color:#270345;font-weight:600;">Activer le compte marchand</h5>
            <p class="grey-text text-darken-1" style="font-size:14px;margin-bottom:8px;">
                <strong id="ripa-bm-valider-raison"></strong><br />
                <span id="ripa-bm-valider-email"></span>
            </p>
            <p class="grey-text" style="font-size:13px;">Vous pouvez ajuster le mot de passe provisoire avant activation.</p>
            <form id="ripa-bm-form-valider" method="post" action="">
                <?php echo ripa_bo_csrf_field(); ?>
                <div class="input-field" style="margin-top:1rem;margin-bottom:1rem;">
                    <label for="ripa-bm-valider-password" style="position:static;transform:none;font-size:13px;color:#5c4a6e;">Mot de passe provisoire *</label>
                    <input id="ripa-bm-valider-password" type="text" name="default_password" minlength="8" required style="border:1px solid #ccc;border-radius:4px;padding:10px;width:100%;box-sizing:border-box;" />
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:flex-end;margin-top:12px;">
                    <button type="button" class="btn grey lighten-1 ripa-bm-modal-valider-close" style="color:#333;">Annuler</button>
                    <button type="submit" class="btn purple">Confirmer l’activation</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .ripa-bm-modal-refus {
            position: fixed;
            inset: 0;
            z-index: 10050;
            background: rgba(39, 3, 69, 0.45);
            align-items: center;
            justify-content: center;
            padding: 16px;
            box-sizing: border-box;
        }
        .ripa-bm-modal-refus.is-open {
            display: flex !important;
        }
        .ripa-bm-modal-refus-dialog {
            background: #fff;
            border-radius: 10px;
            max-width: 440px;
            width: 100%;
            padding: 1.5rem;
            box-shadow: 0 12px 40px rgba(39, 3, 69, 0.25);
            border-top: 4px solid #270345;
        }
    </style>
    <script>
        (function () {
            var modal = document.getElementById('ripa-bm-modal-refus');
            if (!modal) return;
            var idInput = document.getElementById('ripa-bm-refus-id-marchand');
            var motif = document.getElementById('ripa-bm-refus-motif');
            var raisonEl = document.getElementById('ripa-bm-refus-raison');
            var emailEl = document.getElementById('ripa-bm-refus-email');

            function openModal(id, raison, email) {
                idInput.value = id;
                raisonEl.textContent = raison || '';
                emailEl.textContent = email || '';
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

            document.querySelectorAll('.ripa-bm-open-refus').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    openModal(
                        btn.getAttribute('data-id-marchand'),
                        btn.getAttribute('data-raison'),
                        btn.getAttribute('data-email')
                    );
                });
            });

            modal.querySelectorAll('.ripa-bm-modal-refus-close').forEach(function (el) {
                el.addEventListener('click', closeModal);
            });

            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
            });

            var modalValider = document.getElementById('ripa-bm-modal-valider');
            var formValider = document.getElementById('ripa-bm-form-valider');
            var pwdValider = document.getElementById('ripa-bm-valider-password');
            var validerRaison = document.getElementById('ripa-bm-valider-raison');
            var validerEmail = document.getElementById('ripa-bm-valider-email');

            function generateDefaultPassword() {
                var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
                var out = 'Ripa#';
                for (var i = 0; i < 8; i++) {
                    out += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                return out + '9';
            }

            function openValiderModal(id, raison, email) {
                formValider.setAttribute('action', '<?php echo site_url('Business_marchand_backoffice/valider'); ?>/' + id);
                validerRaison.textContent = raison || '';
                validerEmail.textContent = email || '';
                pwdValider.value = generateDefaultPassword();
                modalValider.style.display = 'flex';
                modalValider.classList.add('is-open');
                modalValider.setAttribute('aria-hidden', 'false');
                setTimeout(function () { pwdValider.focus(); pwdValider.select(); }, 50);
            }

            function closeValiderModal() {
                modalValider.style.display = 'none';
                modalValider.classList.remove('is-open');
                modalValider.setAttribute('aria-hidden', 'true');
                formValider.setAttribute('action', '');
                pwdValider.value = '';
            }

            document.querySelectorAll('.ripa-bm-open-valider').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    openValiderModal(
                        btn.getAttribute('data-id-marchand'),
                        btn.getAttribute('data-raison'),
                        btn.getAttribute('data-email')
                    );
                });
            });

            modalValider.querySelectorAll('.ripa-bm-modal-valider-close').forEach(function (el) {
                el.addEventListener('click', closeValiderModal);
            });

            modalValider.addEventListener('click', function (e) {
                if (e.target === modalValider) closeValiderModal();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modalValider.classList.contains('is-open')) closeValiderModal();
            });
        })();
    </script>
</body>
