<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modèle KYC (Know Your Customer) - données chiffrées avec encrypt_ripa
 */
class Kyc_model extends CI_Model {

    private $table = 'kyc_utilisateur_application';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupère le KYC d'un utilisateur (une seule ligne par user)
     * @param int $user_id
     * @return array|null
     */
    public function get_by_user($user_id) {
        $row = $this->db->get_where($this->table, array('id_utilisateur_application' => $user_id))->row_array();
        return $row ?: null;
    }

    /**
     * Crée ou met à jour le KYC (soumission par l'utilisateur)
     * Données déjà chiffrées (nom_c, post_nom_c, etc.)
     * @param int $user_id
     * @param array $data Champs chiffrés + date_naissance_c, adresse_c, photo_piece_identite_c, photo_utilisateur_c
     * @return int id_kyc
     */
    public function upsert($user_id, $data) {
        $existing = $this->get_by_user($user_id);
        $data['statut'] = 'en_attente';
        $data['date_validation_kyc'] = null;
        $data['date_prochaine_kyc'] = null;

        if ($existing) {
            $this->db->where('id_utilisateur_application', $user_id);
            $this->db->update($this->table, $data);
            return (int) $existing['id_kyc'];
        }
        $data['id_utilisateur_application'] = $user_id;
        $this->db->insert($this->table, $data);
        return (int) $this->db->insert_id();
    }

    /**
     * Vérifie si l'utilisateur a un KYC validé (nécessaire pour enregistrer une carte)
     * @param int $user_id
     * @return bool
     */
    public function has_valid_kyc($user_id) {
        $row = $this->db->get_where($this->table, array(
            'id_utilisateur_application' => $user_id,
            'statut' => 'valide'
        ))->row_array();
        return !empty($row);
    }

    // -------------------------------------------------------------------------
    // Backoffice RIPA : liste, détail, valider, rejeter, supprimer
    // -------------------------------------------------------------------------

    /**
     * Liste tous les dossiers KYC pour le backoffice (avec filtre statut optionnel)
     * @param string|null $statut 'en_attente'|'valide'|'rejete' ou null pour tous
     * @return array
     */
    public function get_all_for_backoffice($statut = null) {
        $this->db->select('k.id_kyc, k.id_utilisateur_application, k.statut, k.date_enregistrement, k.date_validation_kyc, k.date_prochaine_kyc, u.phone');
        $this->db->from($this->table . ' k');
        $this->db->join('utilisateur_application u', 'u.id_utilisateur_application = k.id_utilisateur_application', 'left');
        $this->db->order_by('k.date_enregistrement', 'DESC');
        if ($statut !== null && $statut !== '') {
            $this->db->where('k.statut', $statut);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Récupère un dossier KYC par id_kyc pour le backoffice (données brutes, déchiffrement dans le contrôleur)
     * @param int $id_kyc
     * @return array|null
     */
    public function get_by_id_for_backoffice($id_kyc) {
        $row = $this->db->get_where($this->table, array('id_kyc' => (int) $id_kyc))->row_array();
        return $row ?: null;
    }

    /**
     * Met à jour le statut d'un dossier KYC (valide ou rejete)
     * @param int $id_kyc
     * @param string $statut 'valide'|'rejete'
     * @return bool
     */
    public function set_statut($id_kyc, $statut) {
        $id_kyc = (int) $id_kyc;
        if (!in_array($statut, array('valide', 'rejete'), true)) {
            return false;
        }
        $data = array('statut' => $statut);
        if ($statut === 'valide') {
            $data['date_validation_kyc'] = date('Y-m-d');
            $data['date_prochaine_kyc'] = date('Y-m-d', strtotime('+2 years'));
        } else {
            $data['date_validation_kyc'] = null;
            $data['date_prochaine_kyc'] = null;
        }
        $this->db->where('id_kyc', $id_kyc);
        return $this->db->update($this->table, $data);
    }

    /**
     * Supprime un dossier KYC (backoffice)
     * @param int $id_kyc
     * @return bool
     */
    public function delete_kyc($id_kyc) {
        $this->db->where('id_kyc', (int) $id_kyc);
        return $this->db->delete($this->table);
    }
}
