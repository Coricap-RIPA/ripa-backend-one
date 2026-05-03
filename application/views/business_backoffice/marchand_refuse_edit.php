<?php
echo $header;
echo $footer;
$m = isset($marchand) ? $marchand : array();
$verr = isset($validation_errors) ? $validation_errors : '';
?>
<body style="background-color: whitesmoke;">
    <div id="load_screen" class="loader" style="display: none;"></div>
    <div class="main_container">
        <?php echo $navbar; ?>
        <div id="row_content" class="row">
            <div class="row col s12">
                <fieldset>
                    <legend>Fiche marchand — modification des informations</legend>
                </fieldset>
            </div>
            <?php if (!empty($this->session->flashdata('message'))) { ?>
                <div class="col s12">
                    <div class="card-panel green lighten-4 green-text text-darken-2"><?php echo $this->session->flashdata('message'); ?></div>
                </div>
            <?php } ?>
            <?php if ($verr !== '') { ?>
                <div class="col s12">
                    <div class="card-panel red lighten-4 red-text text-darken-2"><?php echo $verr; ?></div>
                </div>
            <?php } ?>
            <div class="col s12">
                <p class="grey-text">Dossier #<?php echo (int) ($m['id'] ?? 0); ?> — statut <strong><?php echo htmlspecialchars((string) ($m['statut'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>.</p>
                <?php if (!empty($m['motif_refus'])) { ?>
                    <p class="orange-text text-darken-2" style="font-size:14px;"><strong>Motif du refus :</strong><br /><?php echo nl2br(htmlspecialchars((string) $m['motif_refus'], ENT_QUOTES, 'UTF-8')); ?></p>
                <?php } ?>
            </div>
            <div class="col s12 m8 l6" style="margin-top:12px;">
                <div class="card-panel white" style="border-top:4px solid #270345;border-radius:8px;">
                    <form method="post" action="<?php echo site_url('Business_marchand_backoffice/update_refuse/' . (int) $m['id']); ?>">
                        <?php echo ripa_bo_csrf_field(); ?>
                        <div class="input-field" style="margin-top:0;">
                            <label for="raison_sociale" class="active">Raison sociale *</label>
                            <input id="raison_sociale" type="text" name="raison_sociale" required maxlength="255"
                                value="<?php echo htmlspecialchars($this->input->post('raison_sociale') ?: ($m['raison_sociale'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                        <div class="input-field">
                            <label for="email_contact" class="active">Email (login portail à la validation) *</label>
                            <input id="email_contact" type="email" name="email_contact" required maxlength="255"
                                value="<?php echo htmlspecialchars($this->input->post('email_contact') ?: ($m['email_contact'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                        <div class="input-field">
                            <label for="telephone_contact" class="active">Téléphone</label>
                            <input id="telephone_contact" type="text" name="telephone_contact" maxlength="50"
                                value="<?php echo htmlspecialchars($this->input->post('telephone_contact') ?: ($m['telephone_contact'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                        <div class="input-field">
                            <label for="identifiant_legal" class="active">Identifiant légal (RCCM, etc.)</label>
                            <input id="identifiant_legal" type="text" name="identifiant_legal" maxlength="100"
                                value="<?php echo htmlspecialchars($this->input->post('identifiant_legal') ?: ($m['identifiant_legal'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                        <div class="input-field">
                            <label for="notes_internes" class="active">Notes internes (non vues par le marchand)</label>
                            <?php
                            $notes_post = $this->input->post('notes_internes');
                            $notes_show = $notes_post !== null ? (string) $notes_post : (string) ($m['notes_internes'] ?? '');
                            ?>
                            <textarea id="notes_internes" name="notes_internes" class="materialize-textarea" style="min-height:72px;border:1px solid #ddd;border-radius:6px;padding:10px;"><?php echo htmlspecialchars($notes_show, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="input-field">
                            <label for="new_password" class="active">Nouveau mot de passe portail (optionnel)</label>
                            <input id="new_password" type="text" name="new_password" minlength="8" maxlength="128"
                                value="<?php echo htmlspecialchars((string) $this->input->post('new_password'), ENT_QUOTES, 'UTF-8'); ?>" />
                            <small class="grey-text">Laissez vide pour ne pas modifier le mot de passe actuel. Si rempli, le marchand devra le changer à la prochaine connexion.</small>
                        </div>
                        <button type="submit" class="btn purple">Enregistrer les modifications</button>
                        <a class="btn grey lighten-1" style="margin-left:8px;color:#333;" href="<?php echo site_url('Business_marchand_backoffice/index'); ?>">Retour à la liste</a>
                    </form>
                </div>
            </div>
            <div class="col s12" style="display: none;"><?php echo isset($output) ? $output : ''; ?></div>
        </div>
    </div>
</body>
