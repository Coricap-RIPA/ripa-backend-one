<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Référence des postes / fonctions (fiches RH portail marchand).
 */
class Ref_poste_fonction_model extends CI_Model {

    protected $table = 'ref_poste_fonction';

    public function list_ordered() {
        $this->db->from($this->table);
        $this->db->order_by('ordre', 'ASC');
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function get_by_id($id) {
        $this->db->where('id', (int) $id);
        return $this->db->get($this->table)->row_array();
    }
}
