<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portail B2B — table business_transaction
 * (Nom distinct du Transaction_model legacy à la racine models/)
 */
class Business_transaction_model extends CI_Model {

    protected $table = 'business_transaction';

    public function get_by_marchand($id_marchand, $limit = 200) {
        $this->db->where('id_marchand', (int) $id_marchand);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit((int) $limit);
        return $this->db->get($this->table)->result_array();
    }

    public function insert_row($id_marchand, array $data) {
        $data['id_marchand'] = (int) $id_marchand;
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function upsert_from_paiement_ripa($id_marchand, $id_paiement_ripa, array $row) {
        $existing = $this->db->get_where($this->table, array(
            'id_marchand' => (int) $id_marchand,
            'id_paiement_ripa' => (int) $id_paiement_ripa,
        ))->row_array();
        if ($existing) {
            $this->db->where('id', (int) $existing['id']);
            return $this->db->update($this->table, $row);
        }
        $row['id_marchand'] = (int) $id_marchand;
        $row['id_paiement_ripa'] = (int) $id_paiement_ripa;
        $this->db->insert($this->table, $row);
        return $this->db->insert_id();
    }

    /**
     * Nombre de transactions pour un marchand (optionnel : depuis une date).
     */
    public function count_by_marchand($id_marchand, $since_datetime = null) {
        $this->db->where('id_marchand', (int) $id_marchand);
        if ($since_datetime !== null && $since_datetime !== '') {
            $this->db->where('created_at >=', $since_datetime);
        }
        return (int) $this->db->count_all_results($this->table);
    }

    /**
     * Somme des montants pour un sens (credit|debit), optionnellement sur une période.
     */
    public function sum_montant_by_sens($id_marchand, $sens, $since_datetime = null) {
        if (!in_array($sens, array('credit', 'debit'), true)) {
            return 0.0;
        }
        $this->db->select_sum('montant');
        $this->db->where('id_marchand', (int) $id_marchand);
        $this->db->where('sens', $sens);
        if ($since_datetime !== null && $since_datetime !== '') {
            $this->db->where('created_at >=', $since_datetime);
        }
        $row = $this->db->get($this->table)->row_array();
        $v = isset($row['montant']) ? $row['montant'] : null;
        return $v === null ? 0.0 : (float) $v;
    }

    /**
     * Agrégation journalière crédits / débits sur N jours (depuis aujourd’hui inclus).
     *
     * @return array[] rows with keys jour (Y-m-d), sens, total
     */
    public function get_daily_totals_by_sens($id_marchand, $days = 14) {
        $id = (int) $id_marchand;
        $days = max(1, min(90, (int) $days));
        $sql = 'SELECT DATE(created_at) AS jour, sens, SUM(montant) AS total
            FROM ' . $this->db->protect_identifiers($this->table, true) . '
            WHERE id_marchand = ? AND created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            GROUP BY DATE(created_at), sens
            ORDER BY jour ASC';
        return $this->db->query($sql, array($id, $days))->result_array();
    }
}
