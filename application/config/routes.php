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
$route['api/app/accounts/delete'] = 'api/apiapp/accounts_delete';
$route['api/app/accounts/update'] = 'api/apiapp/accounts_update';
$route['api/app/accounts/bank'] = 'api/apiapp/accounts_bank';
$route['api/app/accounts/bank/add'] = 'api/apiapp/accounts_bank_add';
$route['api/app/accounts/bank/update'] = 'api/apiapp/accounts_bank_update';
$route['api/app/accounts/bank/delete'] = 'api/apiapp/accounts_bank_delete';
$route['api/app/cards'] = 'api/apiapp/cards';
$route['api/app/cards/register'] = 'api/apiapp/cards_register';
$route['api/app/cards/order-virtual'] = 'api/apiapp/cards_order_virtual';
$route['api/app/cards/delete'] = 'api/apiapp/cards_delete';
$route['api/app/cards/recharge'] = 'api/apiapp/cards_recharge';
$route['api/app/cards/withdraw'] = 'api/apiapp/cards_withdraw';
$route['api/app/kyc'] = 'api/apiapp/kyc_get';
$route['api/app/kyc/submit'] = 'api/apiapp/kyc_submit';
$route['api/app/transactions/recent'] = 'api/apiapp/transactions_recent';
$route['api/app/transactions/recent-contacts'] = 'api/apiapp/transactions_recent_contacts';
$route['api/app/transactions/with-contact'] = 'api/apiapp/transactions_with_contact';
$route['api/app/transactions/history'] = 'api/apiapp/transactions_history';
$route['api/app/notifications'] = 'api/apiapp/notifications_get';
$route['api/app/notifications/read'] = 'api/apiapp/notifications_mark_read';
// Paiement B2C (Scan & Pay, C2C) — Phase 1
$route['api/app/payment/sources'] = 'api/apiapp/payment_sources';
$route['api/app/payee/token'] = 'api/apiapp/payee_token';
$route['api/app/payee/qr-context'] = 'api/apiapp/payee_qr_context';
$route['api/app/payee/qr-preference'] = 'api/apiapp/payee_qr_preference';
$route['api/app/payee/lookup'] = 'api/apiapp/payee_lookup';
$route['api/app/payment/submit'] = 'api/apiapp/payment_submit';

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

// ===========================
// PORTAIL MARCHAND (B2B) — routes explicites (FR + chemins techniques CI)
// ===========================
$route['business'] = 'business/dashboard';
$route['business/tableau-de-bord'] = 'business/dashboard';
$route['business/inscription'] = 'business/inscription';
$route['business/inscription/soumettre'] = 'business/inscription/submit';

// Auth
$route['business/connexion'] = 'business/auth/login';
$route['business/connexion/traitement'] = 'business/auth/login_submit';
$route['business/deconnexion'] = 'business/auth/logout';
$route['business/premier-mot-de-passe'] = 'business/auth/first_password';
$route['business/premier-mot-de-passe/enregistrer'] = 'business/auth/first_password_submit';

// Services
$route['business/services'] = 'business/services/index';
$route['business/services/ajouter'] = 'business/services/add';
$route['business/services/modifier/(:num)'] = 'business/services/edit/$1';
$route['business/services/supprimer/(:num)'] = 'business/services/delete/$1';

// Employés
$route['business/employes'] = 'business/employes/index';
$route['business/employes/ajouter'] = 'business/employes/add';
$route['business/employes/modifier/(:num)'] = 'business/employes/edit/$1';
$route['business/employes/supprimer/(:num)'] = 'business/employes/delete/$1';

// Transactions
$route['business/transactions'] = 'business/transactions/index';
$route['business/transactions/saisir'] = 'business/transactions/add';
