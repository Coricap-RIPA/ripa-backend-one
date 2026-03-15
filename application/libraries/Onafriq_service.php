<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Service Onafriq (simulation recharge / décharge carte <-> Mobile Money).
 * Logique métier centralisée ici pour segmentation backend ; traduction Laravel : App\Services\OnafriqService.
 */
class Onafriq_service {

    private $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->helper('custom_helper');
    }

    /**
     * Recharge carte virtuelle depuis Mobile Money (simulation).
     * Met à jour le solde de la carte et insère la transaction.
     * @param int $user_id
     * @param int $card_id
     * @param int $account_id
     * @param float $amount
     * @return array [ 'reference' => string, 'new_balance' => float, 'transaction_id' => int ] ou erreur
     */
    public function recharge($user_id, $card_id, $account_id, $amount) {
        $db = $this->CI->db;
        $card = $db->get_where('carte_utilisateur_application', array(
            'id_carte' => $card_id,
            'id_utilisateur_application' => $user_id,
            'is_active' => 1,
        ))->row_array();
        if (empty($card)) {
            return array('error' => 'Carte introuvable');
        }
        $account = $db->get_where('compte_mobile_money_utilisateur_application', array(
            'id_compte_mobile_money' => $account_id,
            'id_utilisateur_application' => $user_id,
            'is_active' => 1,
        ))->row_array();
        if (empty($account)) {
            return array('error' => 'Compte Mobile Money introuvable');
        }

        $current_balance = 0;
        if (!empty($card['solde_c'])) {
            $dec = decrypt_ripa($card['solde_c']);
            $current_balance = (is_numeric($dec) || $dec === '') ? (float) $dec : 0;
        }
        $new_balance = $current_balance + $amount;
        $solde_c = encrypt_ripa((string) $new_balance);

        $db->where('id_carte', $card_id);
        $db->update('carte_utilisateur_application', array('solde_c' => $solde_c));

        $ref = 'ONAFRIQ-R-' . strtoupper(bin2hex(random_bytes(8)));
        $montant_c = encrypt_ripa((string) $amount);
        $reference_c = encrypt_ripa($ref);

        $db->insert('transaction_carte_mobile_money_utilisateur', array(
            'id_utilisateur_application' => $user_id,
            'id_carte' => $card_id,
            'id_compte_mobile_money' => $account_id,
            'type_operation' => 'recharge',
            'montant_c' => $montant_c,
            'reference_onafriq_c' => $reference_c,
        ));
        $tx_id = $db->insert_id();

        return array(
            'reference' => $ref,
            'new_balance' => $new_balance,
            'transaction_id' => (int) $tx_id,
        );
    }

    /**
     * Retrait carte virtuelle vers Mobile Money (simulation).
     * @param int $user_id
     * @param int $card_id
     * @param int $account_id
     * @param float $amount
     * @return array [ 'reference' => string, 'new_balance' => float, 'transaction_id' => int ] ou erreur
     */
    public function withdraw($user_id, $card_id, $account_id, $amount) {
        $db = $this->CI->db;
        $card = $db->get_where('carte_utilisateur_application', array(
            'id_carte' => $card_id,
            'id_utilisateur_application' => $user_id,
            'is_active' => 1,
        ))->row_array();
        if (empty($card)) {
            return array('error' => 'Carte introuvable');
        }
        $account = $db->get_where('compte_mobile_money_utilisateur_application', array(
            'id_compte_mobile_money' => $account_id,
            'id_utilisateur_application' => $user_id,
            'is_active' => 1,
        ))->row_array();
        if (empty($account)) {
            return array('error' => 'Compte Mobile Money introuvable');
        }

        $current_balance = 0;
        if (!empty($card['solde_c'])) {
            $dec = decrypt_ripa($card['solde_c']);
            $current_balance = (is_numeric($dec) || $dec === '') ? (float) $dec : 0;
        }
        if ($current_balance < $amount) {
            return array('error' => 'Solde insuffisant', 'current_balance' => $current_balance);
        }
        $new_balance = $current_balance - $amount;
        $solde_c = encrypt_ripa((string) $new_balance);

        $db->where('id_carte', $card_id);
        $db->update('carte_utilisateur_application', array('solde_c' => $solde_c));

        $ref = 'ONAFRIQ-W-' . strtoupper(bin2hex(random_bytes(8)));
        $montant_c = encrypt_ripa((string) $amount);
        $reference_c = encrypt_ripa($ref);

        $db->insert('transaction_carte_mobile_money_utilisateur', array(
            'id_utilisateur_application' => $user_id,
            'id_carte' => $card_id,
            'id_compte_mobile_money' => $account_id,
            'type_operation' => 'retrait',
            'montant_c' => $montant_c,
            'reference_onafriq_c' => $reference_c,
        ));
        $tx_id = $db->insert_id();

        return array(
            'reference' => $ref,
            'new_balance' => $new_balance,
            'transaction_id' => (int) $tx_id,
        );
    }
}
