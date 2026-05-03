<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portail B2B — table business_marchand
 * (Nom distinct du Marchand_model legacy à la racine models/)
 */
class Business_marchand_model extends CI_Model {

    protected $table = 'business_marchand';

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => (int) $id))->row_array();
    }

    public function exists_by_email($email_contact, $exclude_id = null) {
        $email = strtolower(trim((string) $email_contact));
        $this->db->where('email_contact', $email);
        if ($exclude_id !== null) {
            $this->db->where('id !=', (int) $exclude_id);
        }
        return (int) $this->db->count_all_results($this->table) > 0;
    }

    public function exists_by_phone($telephone_contact, $exclude_id = null) {
        $tel = trim((string) $telephone_contact);
        $this->db->where('telephone_contact', $tel);
        if ($exclude_id !== null) {
            $this->db->where('id !=', (int) $exclude_id);
        }
        return (int) $this->db->count_all_results($this->table) > 0;
    }

    public function get_all_by_statut($statut = null) {
        if ($statut !== null && $statut !== '') {
            $this->db->where('statut', $statut);
        }
        $this->db->order_by('date_demande', 'DESC');
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result_array();
    }

    public function insert_row(array $data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_row($id, array $data) {
        $this->db->where('id', (int) $id);
        return $this->db->update($this->table, $data);
    }

    public function delete_row($id) {
        $this->db->where('id', (int) $id);
        return $this->db->delete($this->table);
    }

    public function create_demande($raison_sociale, $email_contact, $telephone_contact, $id_utilisateur_demandeur = null, $identifiant_legal = null) {
        $now = date('Y-m-d H:i:s');
        return $this->insert_row(array(
            'raison_sociale' => $raison_sociale,
            'email_contact' => strtolower(trim($email_contact)),
            'telephone_contact' => $telephone_contact,
            'identifiant_legal' => $identifiant_legal,
            'statut' => 'en_attente_validation',
            'id_utilisateur_demandeur' => $id_utilisateur_demandeur,
            'date_demande' => $now,
        ));
    }
}
