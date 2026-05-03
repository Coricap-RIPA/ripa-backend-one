<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portail B2B — dossiers KYB (Know Your Business)
 */
class Business_kyb_model extends CI_Model {

    protected $table = 'business_kyb_dossier';

    /** @var bool|null cache colonne date_fin_validite */
    private $_has_col_date_fin_validite;

    /** Durée de validité d’un dossier approuvé (renouvellement / re-soumission). */
    const VALIDITE_APPROBATION_MOIS = 12;

    /**
     * La migration sql/23_business_kyb_date_fin_validite.sql ajoute date_fin_validite.
     * Sans cette colonne, les INSERT/UPDATE ne doivent pas l’inclure (sinon échec SQL silencieux).
     */
    public function has_date_fin_validite_column() {
        if ($this->_has_col_date_fin_validite !== null) {
            return $this->_has_col_date_fin_validite;
        }
        $q = $this->db->query('SHOW COLUMNS FROM `' . $this->table . "` LIKE 'date_fin_validite'");
        $this->_has_col_date_fin_validite = $q && $q->num_rows() > 0;
        return $this->_has_col_date_fin_validite;
    }

    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    private function _filter_payload_columns(array $data) {
        if (!$this->has_date_fin_validite_column()) {
            unset($data['date_fin_validite']);
        }
        return $data;
    }

    public function get_by_marchand($id_marchand) {
        return $this->db->get_where($this->table, array('id_marchand' => (int) $id_marchand))->row_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => (int) $id))->row_array();
    }

    public function insert_row(array $data) {
        $data = $this->_filter_payload_columns($data);
        $this->db->insert($this->table, $data);
        $err = $this->db->error();
        $errno = isset($err['code']) ? (int) $err['code'] : 0;
        if ($errno !== 0) {
            log_message('error', 'Business_kyb_model::insert_row failed: ' . json_encode($err));
            return 0;
        }
        return (int) $this->db->insert_id();
    }

    public function update_by_marchand($id_marchand, array $data) {
        $data = $this->_filter_payload_columns($data);
        $this->db->update($this->table, $data, array('id_marchand' => (int) $id_marchand));
        $err = $this->db->error();
        if ((isset($err['code']) ? (int) $err['code'] : 0) !== 0) {
            log_message('error', 'Business_kyb_model::update_by_marchand failed: ' . json_encode($err));
            return false;
        }
        return true;
    }

    public function update_by_id($id, array $data) {
        $data = $this->_filter_payload_columns($data);
        $this->db->where('id', (int) $id);
        $this->db->update($this->table, $data);
        $err = $this->db->error();
        if ((isset($err['code']) ? (int) $err['code'] : 0) !== 0) {
            log_message('error', 'Business_kyb_model::update_by_id failed: ' . json_encode($err));
            return false;
        }
        return true;
    }

    /**
     * Liste back-office avec raison sociale marchand.
     *
     * @param string|null $statut filtre strict ou null pour tous
     */
    public function list_with_marchand($statut = null) {
        $this->db->select('k.*, m.raison_sociale AS marchand_raison_sociale, m.email_contact AS marchand_email_contact');
        $this->db->from($this->table . ' k');
        $this->db->join('business_marchand m', 'm.id = k.id_marchand', 'inner');
        if ($statut !== null && $statut !== '') {
            $this->db->where('k.statut', $statut);
        }
        $this->db->order_by('k.date_soumission', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Date/heure de fin de validité pour un dossier approuvé (UTC serveur).
     *
     * @param array|null $row ligne business_kyb_dossier
     * @return string|null format Y-m-d H:i:s
     */
    public function get_date_fin_validite($row) {
        if (empty($row) || !is_array($row)) {
            return null;
        }
        if (!empty($row['date_fin_validite'])) {
            return (string) $row['date_fin_validite'];
        }
        if (($row['statut'] ?? '') === 'valide' && !empty($row['date_decision'])) {
            $ts = strtotime((string) $row['date_decision'] . ' +' . (int) self::VALIDITE_APPROBATION_MOIS . ' months');
            if ($ts !== false) {
                return date('Y-m-d H:i:s', $ts);
            }
        }
        return null;
    }

    /**
     * Dossier approuvé dont la période de validité est dépassée (renouvellement requis).
     *
     * @param array|null $row
     */
    public function is_expired($row) {
        if (empty($row) || !is_array($row) || ($row['statut'] ?? '') !== 'valide') {
            return false;
        }
        $end = $this->get_date_fin_validite($row);
        if ($end === null || $end === '') {
            return true;
        }
        return strtotime($end) <= time();
    }

    /**
     * KYB approuvé et dans sa période de validité → opérations métier (ex. transactions) autorisées.
     *
     * @param array|null $row
     */
    public function is_approved_for_operations($row) {
        if (empty($row) || !is_array($row) || ($row['statut'] ?? '') !== 'valide') {
            return false;
        }
        return !$this->is_expired($row);
    }

    /**
     * Le portail marchand peut soumettre / renouveler un dossier (pas de dossier, rejeté, ou validé expiré).
     *
     * @param array|null $row
     */
    public function portal_may_submit($row) {
        if (empty($row) || !is_array($row)) {
            return true;
        }
        $st = (string) ($row['statut'] ?? '');
        if ($st === 'rejete') {
            return true;
        }
        if ($st === 'valide' && $this->is_expired($row)) {
            return true;
        }
        return false;
    }

    /**
     * Supprime le dossier KYB et les fichiers sur disque — uniquement si statut « rejete » (abandon / nouveau départ).
     */
    public function delete_for_marchand_if_rejected($id_marchand) {
        $row = $this->get_by_marchand((int) $id_marchand);
        if (empty($row) || ($row['statut'] ?? '') !== 'rejete') {
            return false;
        }
        foreach (array('fichier_piece_legal', 'fichier_piece_complement') as $c) {
            if (!empty($row[$c])) {
                $p = FCPATH . $row[$c];
                if (is_file($p)) {
                    @unlink($p);
                }
            }
        }
        $dir = FCPATH . 'assets/uploads/business_kyb/' . (int) $id_marchand . '/';
        if (is_dir($dir)) {
            @rmdir($dir);
        }
        $this->db->delete($this->table, array('id_marchand' => (int) $id_marchand));
        return $this->db->affected_rows() > 0;
    }
}
