<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Business_Controller.php';

/**
 * Tableau de bord portail marchand — business_marchand_model (autoload)
 */
class Dashboard extends MY_Business_Controller {

    private static $roles_write = array('administrateur', 'gestionnaire');

    public function index() {
        $this->require_business_login();
        $id_m = (int) $this->session->userdata('business_marchand_id');
        $marchand = $this->business_marchand_model->get_by_id($id_m);

        $since30 = date('Y-m-d H:i:s', strtotime('-30 days'));
        $chart_days = 14;

        $cnt_services_actifs = $this->service_model->count_by_marchand($id_m, true);
        $cnt_services_total = $this->service_model->count_by_marchand($id_m, false);
        $cnt_employes_actifs = $this->employe_model->count_by_marchand($id_m, true);
        $cnt_tx_30 = $this->business_transaction_model->count_by_marchand($id_m, $since30);
        $sum_credit_30 = $this->business_transaction_model->sum_montant_by_sens($id_m, 'credit', $since30);
        $sum_debit_30 = $this->business_transaction_model->sum_montant_by_sens($id_m, 'debit', $since30);

        $daily_raw = $this->business_transaction_model->get_daily_totals_by_sens($id_m, $chart_days);
        $by_day = array();
        foreach ($daily_raw as $row) {
            $j = $row['jour'];
            if (!isset($by_day[$j])) {
                $by_day[$j] = array();
            }
            $by_day[$j][$row['sens']] = (float) $row['total'];
        }
        $chart_labels = array();
        $chart_credits = array();
        $chart_debits = array();
        for ($i = $chart_days - 1; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime('-' . $i . ' days'));
            $chart_labels[] = date('d/m', strtotime($d));
            $chart_credits[] = isset($by_day[$d]['credit']) ? $by_day[$d]['credit'] : 0.0;
            $chart_debits[] = isset($by_day[$d]['debit']) ? $by_day[$d]['debit'] : 0.0;
        }

        $recent_tx = $this->business_transaction_model->get_by_marchand($id_m, 10);
        $recent_employes = $this->employe_model->get_recent_by_marchand($id_m, 5);
        $services_list = $this->service_model->get_by_marchand($id_m);
        $services_preview = array_slice($services_list, 0, 5);

        $role_key = (string) $this->session->userdata('business_role');
        $role_labels = array(
            'administrateur' => 'Administrateur',
            'gestionnaire' => 'Gestionnaire',
            'lecteur_seul' => 'Lecture seule',
        );
        $role_fr = isset($role_labels[$role_key]) ? $role_labels[$role_key] : ucfirst(str_replace('_', ' ', $role_key));
        $can_write = in_array($role_key, self::$roles_write, true);

        $devise_principale = 'USD';
        if (!empty($recent_tx[0]['devise'])) {
            $devise_principale = $recent_tx[0]['devise'];
        }

        $json_flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
        $this->load->view('business/dashboard/index', array(
            'marchand' => $marchand,
            'email' => $this->session->userdata('business_email'),
            'role' => $role_key,
            'role_fr' => $role_fr,
            'flash_message' => $this->session->flashdata('message'),
            'nav_active' => 'home',
            'can_write' => $can_write,
            'cnt_services_actifs' => $cnt_services_actifs,
            'cnt_services_total' => $cnt_services_total,
            'cnt_employes_actifs' => $cnt_employes_actifs,
            'cnt_tx_30' => $cnt_tx_30,
            'sum_credit_30' => $sum_credit_30,
            'sum_debit_30' => $sum_debit_30,
            'solde_net_30' => $sum_credit_30 - $sum_debit_30,
            'devise_principale' => $devise_principale,
            'chart_labels' => $chart_labels,
            'chart_credits' => $chart_credits,
            'chart_debits' => $chart_debits,
            'chart_labels_json' => json_encode($chart_labels, $json_flags),
            'chart_credits_json' => json_encode($chart_credits, $json_flags),
            'chart_debits_json' => json_encode($chart_debits, $json_flags),
            'recent_tx' => $recent_tx,
            'recent_employes' => $recent_employes,
            'services_preview' => $services_preview,
        ));
    }
}
