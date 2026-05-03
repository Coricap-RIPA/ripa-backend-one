<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Service Vault (simulation tokenisation cartes).
 * Reçoit PAN/CVV en mémoire, retourne token + last4 + brand. Aucune persistance de données sensibles.
 * Structure volontairement simple pour traduction Laravel : même interface en App\Services\VaultService.
 */
class Vault_service {

    /**
     * Tokenise une carte : en mémoire uniquement, pas de stockage PAN/CVV.
     * @param string $pan Numéro de carte (chiffres uniquement)
     * @param string $cvv CVV (non stocké)
     * @param string $expiry MM/YY
     * @return array [ 'token' => string, 'last4' => string, 'brand' => string ]
     */
    public function tokenize($pan, $cvv, $expiry) {
        $pan = preg_replace('/\s+/', '', $pan);
        $last4 = strlen($pan) >= 4 ? substr($pan, -4) : '0000';
        $brand = (substr($pan, 0, 1) === '4') ? 'Visa' : 'Mastercard';
        $token = 'mock_token_' . bin2hex(random_bytes(16));
        return array(
            'token' => $token,
            'last4' => $last4,
            'brand' => $brand,
        );
    }
}
