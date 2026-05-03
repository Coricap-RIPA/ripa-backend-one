<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Utilisateurs du portail web marchand — table utilisateur_business
 */
class Utilisateur_business_model extends CI_Model {

    protected $table = 'utilisateur_business';

    public function __construct() {
        parent::__construct();
    }

    public function get_by_email($email) {
        $email = strtolower(trim($email));
        return $this->db->get_where($this->table, array('email' => $email))->row_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => (int) $id))->row_array();
    }

    /** Liste des comptes portail pour un marchand (liaison employé) */
    public function get_by_marchand($id_marchand) {
        $this->db->where('id_marchand', (int) $id_marchand);
        $this->db->where('actif', 1);
        $this->db->order_by('email', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    public function count_by_marchand($id_marchand) {
        return (int) $this->db->where('id_marchand', (int) $id_marchand)->count_all_results($this->table);
    }

    public function insert_row(array $data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_row($id, array $data) {
        $this->db->where('id', (int) $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Compte administrateur principal d'un marchand.
     */
    public function get_admin_by_marchand($id_marchand) {
        $this->db->where('id_marchand', (int) $id_marchand);
        $this->db->where('role', 'administrateur');
        $this->db->order_by('id', 'ASC');
        return $this->db->get($this->table, 1)->row_array();
    }

    /**
     * Crée l’administrateur initial (appelé à la validation RIPA)
     */
    public function create_administrateur($id_marchand, $email_contact, $password_plain) {
        $email = strtolower(trim($email_contact));
        if ($this->get_by_email($email)) {
            return array('ok' => false, 'message' => 'Un compte portail existe déjà pour cet email.');
        }
        $hash = password_hash($password_plain, PASSWORD_BCRYPT);
        $id = $this->insert_row(array(
            'id_marchand' => (int) $id_marchand,
            'email' => $email,
            'mot_de_passe' => $hash,
            'role' => 'administrateur',
            'doit_changer_mot_de_passe' => 1,
            'actif' => 1,
        ));
        return array('ok' => true, 'id' => $id);
    }
}
