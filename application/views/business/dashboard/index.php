<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RIPA — Tableau de bord marchand</title>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/dore_assets/img/favicon.png'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome-4.7.0/css/font-awesome.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/dore_assets/css/vendor/bootstrap.min.css'); ?>" />
    <?php $this->load->view('business/partials/portal_shell_styles'); ?>
</head>
<body class="ripa-portal-app">
<?php
$this->load->view('business/partials/nav_portal');
$mfmt = function ($n) {
    return number_format((float) $n, 2, ',', ' ');
};
$statut = !empty($marchand['statut']) ? $marchand['statut'] : '';
$badge_class = 'ripa-badge-default';
if ($statut === 'actif') {
    $badge_class = 'ripa-badge-actif';
} elseif ($statut === 'en_attente_validation') {
    $badge_class = 'ripa-badge-attente';
} elseif ($statut === 'refuse') {
    $badge_class = 'ripa-badge-refuse';
} elseif ($statut === 'suspendu') {
    $badge_class = 'ripa-badge-suspendu';
}
$statut_fr = array(
    'actif' => 'Actif',
    'brouillon' => 'Brouillon',
    'en_attente_validation' => 'En attente validation',
    'refuse' => 'Refusé',
    'suspendu' => 'Suspendu',
);
$statut_label = isset($statut_fr[$statut]) ? $statut_fr[$statut] : htmlspecialchars($statut);
?>
    <main class="ripa-portal-main">
        <?php if (!empty($flash_message)) { ?>
            <div class="alert alert-success border-0 shadow-sm mb-3" style="border-radius:12px;border-left:4px solid #2e7d32 !important;">
                <i class="fa fa-check-circle"></i> <?php echo htmlspecialchars($flash_message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } ?>

        <section class="ripa-dash-hero">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1>Bonjour<?php echo !empty($marchand['raison_sociale']) ? ', ' . htmlspecialchars($marchand['raison_sociale']) : ''; ?></h1>
                    <p>
                        <i class="fa fa-user-circle-o"></i> <?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>
                        <span class="mx-2">·</span>
                        <?php echo htmlspecialchars($role_fr, ENT_QUOTES, 'UTF-8'); ?>
                        <?php if (!empty($marchand)) { ?>
                            <span class="mx-2">·</span>
                            <span class="ripa-badge-statut <?php echo $badge_class; ?>"><?php echo $statut_label; ?></span>
                        <?php } ?>
                    </p>
                </div>
                <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                    <?php if (!empty($can_write)) { ?>
                        <a class="btn btn-light btn-sm font-weight-bold shadow-sm" style="border-radius:10px;" href="<?php echo site_url('business/transactions/saisir'); ?>"><i class="fa fa-plus-circle"></i> Saisir une opération</a>
                    <?php } ?>
                    <a class="btn btn-outline-light btn-sm font-weight-bold ml-1" style="border-radius:10px;opacity:0.95;" href="<?php echo site_url('business/transactions'); ?>">Voir tout</a>
                </div>
            </div>
        </section>

        <div class="row">
            <div class="col-sm-6 col-xl-3 mb-3">
                <div class="ripa-kpi-card">
                    <div class="kpi-icon" style="background:#ede7f6;color:#270345;"><i class="fa fa-cubes"></i></div>
                    <div class="kpi-label">Services actifs</div>
                    <div class="kpi-value"><?php echo (int) $cnt_services_actifs; ?><span style="font-size:0.85rem;font-weight:600;color:#888;"> / <?php echo (int) $cnt_services_total; ?></span></div>
                    <div class="kpi-sub">Services configurés pour votre entreprise</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 mb-3">
                <div class="ripa-kpi-card">
                    <div class="kpi-icon" style="background:#e3f2fd;color:#1565c0;"><i class="fa fa-users"></i></div>
                    <div class="kpi-label">Employés actifs</div>
                    <div class="kpi-value"><?php echo (int) $cnt_employes_actifs; ?></div>
                    <div class="kpi-sub">Fiches RH avec statut actif</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 mb-3">
                <div class="ripa-kpi-card">
                    <div class="kpi-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fa fa-arrow-circle-up"></i></div>
                    <div class="kpi-label">Crédits (30 j.)</div>
                    <div class="kpi-value" style="font-size:1.15rem;"><?php echo $mfmt($sum_credit_30); ?> <small><?php echo htmlspecialchars($devise_principale); ?></small></div>
                    <div class="kpi-sub">Somme des encaissements sur 30 jours</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 mb-3">
                <div class="ripa-kpi-card">
                    <div class="kpi-icon" style="background:#ffebee;color:#c62828;"><i class="fa fa-arrow-circle-down"></i></div>
                    <div class="kpi-label">Débits (30 j.)</div>
                    <div class="kpi-value" style="font-size:1.15rem;"><?php echo $mfmt($sum_debit_30); ?> <small><?php echo htmlspecialchars($devise_principale); ?></small></div>
                    <div class="kpi-sub"><?php echo (int) $cnt_tx_30; ?> mouvement(s) sur la période</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-3">
                <div class="ripa-panel">
                    <div class="panel-head">
                        <h2><i class="fa fa-line-chart text-muted"></i> Volume journalier (14 jours)</h2>
                        <span class="text-muted small">Crédits vs débits</span>
                    </div>
                    <div class="panel-body">
                        <div class="ripa-chart-wrap">
                            <canvas id="ripaDashChart" aria-label="Graphique des volumes"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="ripa-panel">
                    <div class="panel-head">
                        <h2>Synthèse 30 jours</h2>
                    </div>
                    <div class="panel-body">
                        <p class="mb-2" style="color:var(--ripa-muted);font-size:0.9rem;">Net crédits − débits (<?php echo htmlspecialchars($devise_principale); ?>)</p>
                        <p class="mb-3" style="font-size:1.75rem;font-weight:800;color:var(--ripa-bar);">
                            <?php echo $mfmt($solde_net_30); ?>
                        </p>
                        <hr style="border-color:rgba(39,3,69,0.08);" />
                        <p class="small text-muted mb-0">
                            Les graphiques et totaux s’appuient sur le journal <strong>Transactions</strong> du portail.
                            Données carte : conformité PCI — pas de PAN/CVV stockés côté portail.
                        </p>
                        <div class="mt-3 d-flex flex-wrap" style="gap:8px;">
                            <a href="<?php echo site_url('business/services'); ?>" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">Services</a>
                            <a href="<?php echo site_url('business/employes'); ?>" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">Employés</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-3">
                <div class="ripa-panel">
                    <div class="panel-head">
                        <h2><i class="fa fa-cubes text-muted"></i> Aperçu services</h2>
                        <a href="<?php echo site_url('business/services'); ?>" class="small font-weight-bold" style="color:var(--ripa-bar);">Tout voir</a>
                    </div>
                    <div class="panel-body py-2">
                        <?php if (empty($services_preview)) { ?>
                            <p class="text-muted small mb-0">Aucun service. <?php if (!empty($can_write)) { ?><a href="<?php echo site_url('business/services/ajouter'); ?>">Ajouter un service</a><?php } ?></p>
                        <?php } else { ?>
                            <?php foreach ($services_preview as $sv) { ?>
                                <div class="ripa-list-item">
                                    <div>
                                        <strong><?php echo htmlspecialchars($sv['libelle']); ?></strong>
                                        <?php if (!empty($sv['code'])) { ?>
                                            <br /><span class="text-muted small"><?php echo htmlspecialchars($sv['code']); ?></span>
                                        <?php } ?>
                                    </div>
                                    <?php if (!empty($sv['actif'])) { ?>
                                        <span class="badge badge-success" style="font-size:0.7rem;">Actif</span>
                                    <?php } else { ?>
                                        <span class="badge badge-secondary" style="font-size:0.7rem;">Inactif</span>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="ripa-panel">
                    <div class="panel-head">
                        <h2><i class="fa fa-clock-o text-muted"></i> Dernières transactions</h2>
                        <a href="<?php echo site_url('business/transactions'); ?>" class="small font-weight-bold" style="color:var(--ripa-bar);">Historique</a>
                    </div>
                    <div class="panel-body py-2">
                        <?php if (empty($recent_tx)) { ?>
                            <p class="text-muted small mb-0">Aucune transaction récente.</p>
                        <?php } else { ?>
                            <?php foreach ($recent_tx as $tx) { ?>
                                <div class="ripa-list-item">
                                    <div style="min-width:0;">
                                        <span class="text-muted small"><?php echo htmlspecialchars(substr($tx['created_at'], 0, 16)); ?></span>
                                        <br />
                                        <span style="font-size:0.9rem;"><?php echo htmlspecialchars($tx['type_operation'] ?? '—'); ?></span>
                                        <?php if (!empty($tx['libelle'])) { ?>
                                            <br /><span class="text-muted small text-truncate d-inline-block" style="max-width:100%;"><?php echo htmlspecialchars($tx['libelle']); ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="text-right text-nowrap">
                                        <span class="<?php echo ($tx['sens'] === 'credit') ? 'ripa-tx-credit' : 'ripa-tx-debit'; ?>">
                                            <?php echo ($tx['sens'] === 'credit') ? '+' : '−'; ?><?php echo $mfmt($tx['montant']); ?>
                                        </span>
                                        <br /><span class="text-muted small"><?php echo htmlspecialchars($tx['devise'] ?? ''); ?></span>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="ripa-panel">
                    <div class="panel-head">
                        <h2><i class="fa fa-user-plus text-muted"></i> Employés récents</h2>
                        <a href="<?php echo site_url('business/employes'); ?>" class="small font-weight-bold" style="color:var(--ripa-bar);">Gérer</a>
                    </div>
                    <div class="panel-body py-2">
                        <?php if (empty($recent_employes)) { ?>
                            <p class="text-muted small mb-0">Aucun employé enregistré.</p>
                        <?php } else { ?>
                            <?php foreach ($recent_employes as $em) { ?>
                                <div class="ripa-list-item">
                                    <div>
                                        <strong><?php echo htmlspecialchars($em['prenom'] . ' ' . $em['nom']); ?></strong>
                                        <?php if (!empty($em['poste'])) { ?>
                                            <br /><span class="text-muted small"><?php echo htmlspecialchars($em['poste']); ?></span>
                                        <?php } ?>
                                    </div>
                                    <?php if (!empty($em['actif'])) { ?>
                                        <span class="badge badge-light border" style="font-size:0.7rem;">Actif</span>
                                    <?php } else { ?>
                                        <span class="badge badge-secondary" style="font-size:0.7rem;">Inactif</span>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" crossorigin="anonymous"></script>
    <script>
        (function () {
            var el = document.getElementById('ripaDashChart');
            if (!el || typeof Chart === 'undefined') return;
            var labels = <?php echo $chart_labels_json; ?>;
            var credits = <?php echo $chart_credits_json; ?>;
            var debits = <?php echo $chart_debits_json; ?>;
            new Chart(el, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Crédits',
                            data: credits,
                            borderColor: 'rgb(46, 125, 50)',
                            backgroundColor: 'rgba(46, 125, 50, 0.12)',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 3
                        },
                        {
                            label: 'Débits',
                            data: debits,
                            borderColor: 'rgb(198, 40, 40)',
                            backgroundColor: 'rgba(198, 40, 40, 0.08)',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (v) {
                                    return v.toLocaleString('fr-FR', { maximumFractionDigits: 0 });
                                }
                            },
                            grid: { color: 'rgba(39, 3, 69, 0.06)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        })();
    </script>
</body>
</html>
