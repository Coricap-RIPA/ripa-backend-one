<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Rate limiting API application mobile (Apiapp).
 * Comptage par fenêtre fixe (IP + palier), stockage fichiers dans application/cache/rate_limit/
 */
$config['ripa_rate_limit_enabled'] = true;

/** IPs qui ignorent le rate limit (dev local — laisser vide en prod si besoin) */
$config['ripa_rate_limit_bypass_ips'] = array('127.0.0.1', '::1');

/**
 * Paliers : max requêtes par période (secondes) pour une même IP.
 * - default : toutes les méthodes Apiapp non listées ailleurs
 * - auth : inscription, login, OTP, PIN (anti brute-force / spam)
 * - sensitive : paiement, enregistrement carte (moins tolérant)
 */
$config['ripa_rate_limit'] = array(
    'default'   => array('max' => 200, 'period' => 60),
    'auth'      => array('max' => 40,  'period' => 60),
    'sensitive' => array('max' => 30,  'period' => 60),
);

/** Nom de méthode PHP (router) => palier */
$config['ripa_rate_limit_methods'] = array(
    'register'        => 'auth',
    'login'           => 'auth',
    'verify_otp'      => 'auth',
    'resend_otp'      => 'auth',
    'verify_pin'      => 'auth',
    'payment_submit'  => 'sensitive',
    'cards_register'  => 'sensitive',
);
