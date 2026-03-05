<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING - Configuration des routes API RIPA
| -------------------------------------------------------------------------
| Format: $route['uri'] = 'controller/method';
*/

// Route par défaut : page de connexion du backoffice (aucun contrôleur "welcome" n'existe)
$route['default_controller'] = 'starter';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// ===========================
// ROUTES API - APPLICATION (contrôleur unique Apiapp + JWT_Library)
// ===========================
$route['api/app/register'] = 'api/apiapp/register';
$route['api/app/verify-otp'] = 'api/apiapp/verify_otp';
$route['api/app/resend-otp'] = 'api/apiapp/resend_otp';
$route['api/app/login'] = 'api/apiapp/login';
$route['api/app/verify-token'] = 'api/apiapp/verify_token';
$route['api/app/verify-pin'] = 'api/apiapp/verify_pin';
$route['api/app/profile'] = 'api/apiapp/profile';
$route['api/app/accounts'] = 'api/apiapp/accounts';
$route['api/app/accounts/add'] = 'api/apiapp/accounts_add';
$route['api/app/accounts/types'] = 'api/apiapp/accounts_types';
$route['api/app/cards'] = 'api/apiapp/cards';
$route['api/app/cards/register'] = 'api/apiapp/cards_register';
$route['api/app/kyc'] = 'api/apiapp/kyc_get';
$route['api/app/kyc/submit'] = 'api/apiapp/kyc_submit';

// ===========================
// ROUTES API - AUTHENTIFICATION (legacy, déprécié – utiliser api/app/*)
// ===========================
$route['api/auth/register'] = 'api/auth/register';
$route['api/auth/verify-otp'] = 'api/auth/verify_otp';
$route['api/auth/resend-otp'] = 'api/auth/resend_otp';
$route['api/auth/login'] = 'api/auth/login';
$route['api/auth/verify-token'] = 'api/auth/verify_token';
$route['api/auth/forgot-password'] = 'api/auth/forgot_password';
$route['api/auth/reset-password'] = 'api/auth/reset_password';

// ===========================
// ROUTES API - UTILISATEURS (legacy)
// ===========================
$route['api/users/profile'] = 'api/users/get_profile';
$route['api/users/update-profile'] = 'api/users/update_profile';
$route['api/users/change-pin'] = 'api/users/change_pin';

// ===========================
// ROUTES API - COMPTES FINANCIERS (legacy)
// ===========================
$route['api/accounts'] = 'api/accounts/index';
$route['api/accounts/add'] = 'api/accounts/add';
$route['api/accounts/set-default/(:num)'] = 'api/accounts/set_default/$1';
$route['api/accounts/delete/(:num)'] = 'api/accounts/delete/$1';
$route['api/accounts/types'] = 'api/accounts/get_types';

// ===========================
// ROUTES API - TRANSACTIONS
// ===========================
$route['api/transactions'] = 'api/transactions/index';
$route['api/transactions/create'] = 'api/transactions/create';
$route['api/transactions/(:num)'] = 'api/transactions/get_by_id/$1';
$route['api/transactions/stats'] = 'api/transactions/get_stats';
$route['api/transactions/history'] = 'api/transactions/get_history';

// ===========================
// ROUTES API - PAIEMENTS
// ===========================
$route['api/payments/initiate'] = 'api/payments/initiate';
$route['api/payments/verify'] = 'api/payments/verify';
$route['api/payments/qr-scan'] = 'api/payments/process_qr';

// ===========================
// ROUTES API - DIVERS
// ===========================
$route['api/config/currencies'] = 'api/config/get_currencies';
$route['api/config/exchange-rates'] = 'api/config/get_exchange_rates';
$route['api/config/commissions'] = 'api/config/get_commissions';
$route['api/publicites'] = 'api/config/get_publicites';

