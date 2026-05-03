<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modèle User pour gérer les utilisateurs de l'application RIPA
 */
class User_model extends CI_Model {

    private $table = 'utilisateur_application';
    private $otp_table = 'otp_codes'; // Table temporaire pour les codes OTP

    public function __construct() {
        parent::__construct();
    }

    /**
     * Créer un nouvel utilisateur (app).
     * Clés possibles : phone, phone_hash, nom_c, post_nom_c, prenom_c, tel_c, email_c, mot_passe_pin, date_enregistrement.
     * @param array $data
     * @return int|bool ID de l'utilisateur créé ou false
     */
    public function create_user($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Récupérer un utilisateur par phone_hash (pour unicité / login si on ne stocke plus phone en clair)
     * @param string $phone_hash SHA256 du téléphone normalisé
     * @return array|null
     */
    public function get_user_by_phone_hash($phone_hash) {
        $query = $this->db->get_where($this->table, array('phone_hash' => $phone_hash));
        return $query->row_array();
    }

    /**
     * Récupérer un utilisateur par son ID
     * @param int $user_id
     * @return array|null
     */
    public function get_user_by_id($user_id) {
        $query = $this->db->get_where($this->table, array('id_utilisateur_application' => $user_id));
        return $query->row_array();
    }

    /**
     * Récupérer un utilisateur par son numéro de téléphone
     * @param string $phone
     * @return array|null
     */
    public function get_user_by_phone($phone) {
        $query = $this->db->get_where($this->table, array('phone' => $phone));
        return $query->row_array();
    }

    /**
     * Mettre à jour le profil d'un utilisateur
     * @param int $user_id
     * @param array $data
     * @return bool
     */
    public function update_user($user_id, $data) {
        $this->db->where('id_utilisateur_application', $user_id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Mettre à jour le PIN d'un utilisateur
     * @param int $user_id
     * @param string $pin_hashed
     * @return bool
     */
    public function update_pin($user_id, $pin_hashed) {
        $this->db->where('id_utilisateur_application', $user_id);
        return $this->db->update($this->table, array('mot_passe_pin' => $pin_hashed));
    }

    /**
     * Sauvegarder un code OTP pour un utilisateur
     * @param int $user_id
     * @param string $otp_code
     * @return bool
     */
    public function save_otp($user_id, $otp_code) {
        // D'abord, supprimer les anciens codes OTP non utilisés pour cet utilisateur
        $this->db->where('user_id', $user_id);
        $this->db->where('is_used', 0);
        $this->db->delete($this->otp_table);

        // Insérer le nouveau code OTP (valide 10 minutes)
        $data = array(
            'user_id' => $user_id,
            'otp_code' => $otp_code,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+10 minutes')),
            'is_used' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );

        return $this->db->insert($this->otp_table, $data);
    }

    /**
     * Vérifier un code OTP
     * @param int $user_id
     * @param string $otp_code
     * @return bool
     */
    public function verify_otp($user_id, $otp_code) {
        $this->db->where('user_id', $user_id);
        $this->db->where('otp_code', $otp_code);
        $this->db->where('is_used', 0);
        $this->db->where('expires_at >', date('Y-m-d H:i:s'));
        
        $query = $this->db->get($this->otp_table);
        
        return $query->num_rows() > 0;
    }

    /**
     * Marquer un code OTP comme utilisé
     * @param int $user_id
     * @return bool
     */
    public function mark_otp_used($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('is_used', 0);
        return $this->db->update($this->otp_table, array('is_used' => 1));
    }

    /**
     * Supprimer un utilisateur
     * @param int $user_id
     * @return bool
     */
    public function delete_user($user_id) {
        $this->db->where('id_utilisateur_application', $user_id);
        return $this->db->delete($this->table);
    }

    /**
     * Compter le nombre total d'utilisateurs
     * @return int
     */
    public function count_users() {
        return $this->db->count_all($this->table);
    }

    /**
     * Normalise un numéro pour comparaison (chiffres uniquement).
     */
    public function normalize_phone_digits($raw) {
        return preg_replace('/\D+/', '', (string) $raw);
    }

    /**
     * Utilisateurs application dont le champ `phone` correspond au numéro saisi
     * (égalité des chiffres ou même suffixe d’au moins 9 chiffres).
     * Ne pas journaliser le numéro en clair dans des traces applicatives.
     *
     * @param string $raw
     * @return array<int,array{id_utilisateur_application:int,phone:string|null}>
     */
    public function find_users_by_phone_match($raw) {
        $digits = $this->normalize_phone_digits($raw);
        if (strlen($digits) < 8) {
            return array();
        }
        $suffix = substr($digits, -8);
        $this->db->select('id_utilisateur_application, phone');
        $this->db->from($this->table);
        $this->db->where('phone IS NOT NULL', null, false);
        $this->db->where('phone !=', '');
        $this->db->group_start();
        $this->db->like('phone', $suffix, 'before');
        $this->db->or_like('phone', $digits, 'both');
        $this->db->group_end();
        $candidates = $this->db->get()->result_array();
        $out = array();
        $seen = array();
        foreach ($candidates as $r) {
            $rd = $this->normalize_phone_digits(isset($r['phone']) ? $r['phone'] : '');
            if ($rd === '') {
                continue;
            }
            $match = ($rd === $digits);
            if (!$match && strlen($rd) >= 9 && strlen($digits) >= 9) {
                $match = (substr($rd, -9) === substr($digits, -9));
            }
            if ($match) {
                $id = (int) $r['id_utilisateur_application'];
                if (!isset($seen[$id])) {
                    $seen[$id] = true;
                    $out[] = array(
                        'id_utilisateur_application' => $id,
                        'phone' => isset($r['phone']) ? $r['phone'] : null,
                    );
                }
            }
        }
        return $out;
    }
}

