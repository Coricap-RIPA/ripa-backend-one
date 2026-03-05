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
}
