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
                ?>
                <a href="<?php echo $base; ?>" class="btn btn-small <?php echo ($f === '' || $f === null) ? 'purple' : 'grey'; ?>">Tous</a>
                <a href="<?php echo $base . '?statut=en_attente_validation'; ?>" class="btn btn-small <?php echo $f === 'en_attente_validation' ? 'purple' : 'grey'; ?>">En attente</a>
                <a href="<?php echo $base . '?statut=actif'; ?>" class="btn btn-small <?php echo $f === 'actif' ? 'purple' : 'grey'; ?>">Actifs</a>
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($list)) { ?>
                            <tr><td colspan="7">Aucune entrée.</td></tr>
                        <?php } else { ?>
                            <?php foreach ($list as $row) { ?>
                                <tr>
                                    <td><?php echo (int) $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['raison_sociale']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email_contact']); ?></td>
                                    <td><?php echo htmlspecialchars($row['telephone_contact']); ?></td>
                                    <td><?php echo htmlspecialchars($row['statut']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date_demande'] ?? ''); ?></td>
                                    <td>
                                        <?php if ($row['statut'] === 'en_attente_validation') { ?>
                                            <form method="post" action="<?php echo site_url('Business_marchand_backoffice/valider/' . (int) $row['id']); ?>" style="display:inline-block;" onsubmit="return confirm('Valider ce compte et créer l’administrateur portail ?');">
                                                <?php echo ripa_bo_csrf_field(); ?>
                                                <button type="submit" class="btn btn-small purple">Valider</button>
                                            </form>
                                            <button type="button"
                                                class="btn btn-small red lighten-2 ripa-bm-open-refus"
                                                style="margin-left:4px;"
                                                data-id-marchand="<?php echo (int) $row['id']; ?>"
                                                data-raison="<?php echo htmlspecialchars($row['raison_sociale'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-email="<?php echo htmlspecialchars($row['email_contact'], ENT_QUOTES, 'UTF-8'); ?>">
                                                Refuser
                                            </button>
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
        })();
    </script>
</body>
